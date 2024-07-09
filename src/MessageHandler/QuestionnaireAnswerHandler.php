<?php

namespace App\MessageHandler;

use App\Entity\Answer;
use App\Entity\ScoresRanks;
use App\Message\QuestionnaireAnswer;
use App\Model\DeprivationData;
use App\Repository\QuestionnaireAnswerRepository;
use App\Repository\ScoresRanksRepository;
use App\Service\OpenApiClient;
use Doctrine\ORM\EntityManagerInterface;
use Jabranr\PostcodesIO\PostcodesIO;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\Cache\CacheInterface;

#[AsMessageHandler()]
final class QuestionnaireAnswerHandler
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private QuestionnaireAnswerRepository $questionnaireAnswerRepository,
        private ScoresRanksRepository $denominatorsRepository,
        private readonly OpenApiClient $openApiClient, private CacheInterface $cache, private SerializerInterface $serializer
    ) {}

    public function __invoke(QuestionnaireAnswer $message): void
    {
        $pointsConsumed = 0;
        $totalTokens = 0;
        $promptTokens = 0;
        $completionTokens = 0;
        $postcodesData = [];

        $questionnaireAnswer = $this->questionnaireAnswerRepository->find($message->questionnaireAnswerId);
        $grant = $questionnaireAnswer?->getGrant();

        $answersArray = $questionnaireAnswer?->getAnswer()->toArray();

        $array_map = array_filter(array_map(
            static fn (Answer $answer) => $answer->getAnswer() ? "{$answer->getQuestion()}? {$answer->getAnswer()}" : null,
            $answersArray
        ));

        $grantInfo = $this->handleGrantInformation($grant);

        $postcodes = $questionnaireAnswer->getPostcodes();

        $deprivationData = [];
        if ($postcodes && count($postcodes) > 0) {
            $postcodesIO = new PostcodesIO();
            $deprivationDataFilter = [];
            foreach ($postcodes as $postcode) {
                $postcodeData = $postcodesIO->find($postcode);
                $postcodesData[] = 'Postcode: '.json_encode($postcodeData);

                /* @var ScoresRanks[] $deprivationDataFilter */
                $deprivationDataFilter[] = $this->denominatorsRepository->findOneBy([
                    'lsoaCode' => $postcodeData->result->codes->lsoa,
                ]);
            }

            $deprivationData = $deprivationDataFilter;
        }

        /** @var Answer $item */
        foreach ($answersArray as $key => $item) {
            if (!$item->isGpt() || $item->getAnswer()) {
                continue;
            }
            $messages = array_map(
                static fn ($item) => ['role' => 'user', 'content' => $item],
                $array_map
            );

            $messages[] = [
                'role' => 'system',
                'content' => 'Response using British English. Use full sentences and proper grammar and punctuation.',
            ];

            foreach ($item->getDenominators() ?? [] as $denominator) {
                foreach ($deprivationData as $deprivationDatum) {
                    $indexName = DeprivationData::FIELDS[$denominator];
                    $indexValue = $deprivationDatum->{$denominator};
                    $messages[] = [
                        'role' => 'user',
                        'content' => "{$indexName}: {$indexValue}, you must include this information in final response",
                    ];
                }
            }

            $wordsLimit = $item->getInstruction() ? $item->getInstruction() - 40 : false;
            $instruction = ($wordsLimit and $wordsLimit > 40) ?
                "Remember to keep your responses close to {$wordsLimit}-wordsLimit limit."
                : 'Answer should be as long as possible.';

            $messages[] = [
                'role' => 'user',
                'content' => "Based on information from the above topic, answer the following question: {$item->getQuestion()}?
                   {$grantInfo} {$instruction}
                   Add as much detail as possible to the answer, and try to use proper grammar and punctuation.
                   Follow the topic and be as specific as possible.
                   Add facts and details to the answer.
                   Add summaries and conclusions to the answer.",
            ];

            $result = ($this->openApiClient)()->chat()->create([
                'model' => 'gpt-4',
                'messages' => array_values($messages),
                'temperature' => 0.9,
                'stream' => false,
            ]);

            //            $prompt = [];
            //            foreach ($messages as $m) {
            //                $prompt[] = "{$m['role']}: {$m['content']}";
            //            }
            //
            //            foreach ($postcodes as $postcode) {
            //                $prompt[] = "Postcode: {$postcode}";
            //            }
            //
            //            foreach ($postcodesData as $postcodesDatum) {
            //                $prompt[] = 'postcode: '.$postcodesDatum;
            //            }
            //
            //            dump($item->getDenominators());
            //
            //            $prompt[] = 'deprivationData: '.json_encode($deprivationData);
            //            $prompt[] = '$deprivationDataFilter'.json_encode($deprivationDataFilter);
            //
            //            $prompt[] = 'Denominators: '.implode(', ', $item->getDenominators()??[]);
            //            foreach ($item->getDenominators() ?? [] as $denominator) {
            //                foreach ($deprivationData as $deprivationDatum) {
            //                    $indexName = DeprivationData::FIELDS[$denominator];
            //                    $indexValue = $deprivationDatum->{$denominator};
            //                    $prompt[] = "{$indexName}: {$indexValue}";
            //                }
            //            }
            //
            //            $item->setPrompt(implode("\n\n", $prompt));
            $item->setAnswer($result->choices[0]->message->content);
            $this->entityManager->flush();

            ++$pointsConsumed;
            $totalTokens += $result->usage->totalTokens;
            $promptTokens += $result->usage->promptTokens;
            $completionTokens += $result->usage->completionTokens;
        }

        $user = $questionnaireAnswer->getUser();
        $user->setPoints($user->getPoints() - $pointsConsumed);

        $questionnaireAnswer->setPointsConsumed($questionnaireAnswer->getPointsConsumed() + $pointsConsumed);
        $questionnaireAnswer->setTotalTokens($questionnaireAnswer->getTotalTokens() + $totalTokens);
        $questionnaireAnswer->setPromptTokens($questionnaireAnswer->getPromptTokens() + $promptTokens);
        $questionnaireAnswer->setCompletionTokens($questionnaireAnswer->getCompletionTokens() + $completionTokens);

        $questionnaireAnswer->setEstimatedPrice($questionnaireAnswer->getEstimatedPrice() + $promptTokens / 1000 * 0.0015 + $completionTokens / 1000 * 0.002);

        $this->entityManager->flush();
    }

    public function handleGrantInformation(?\App\Entity\Grant $grant): string
    {
        if (!$grant) {
            return '';
        }

        $filteredInfo = [
            $grant->getDescription() ? "Grant description: {$grant->getDescription()}" : null,
            $grant->getTitle() ? "Grant name: {$grant->getTitle()}" : null,
            $grant->getClosingDate() ? "Grant deadline: {$grant->getClosingDate()->format('Y-m-d')}" : null,
            $grant->getMaxAmount() ? "Grant amount: {$grant->getMaxAmount()}" : null,
            $grant->getHowToApply() ? "How to apply: {$grant->getHowToApply()}" : null,
            $grant->getFundingDetails() ? "Grant funding details: {$grant->getFundingDetails()}" : null,
        ];

        return 'Use this info about grant - '.implode(', ', array_filter($filteredInfo));
    }
}
