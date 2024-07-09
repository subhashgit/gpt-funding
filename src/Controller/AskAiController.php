<?php

namespace App\Controller;

use App\Entity\Answer;
use App\Entity\Questionnaire;
use App\Entity\QuestionnaireAnswer;
use App\Form\QuestionnaireType;
use App\Repository\QuestionnaireAnswerRepository;
use App\Repository\QuestionnaireRepository;
use Doctrine\ORM\EntityManagerInterface;
use Jabranr\PostcodesIO\PostcodesIO;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\OpenApiClient;

#[Route('/ask-ai', name: 'ask-ai')]
class AskAiController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly MessageBusInterface $bus,
        private readonly OpenApiClient $openApiClient
    ) {}

    #[Route('', name: '')]
    public function index(
        Request $request,
        QuestionnaireAnswerRepository $questionnaireAnswerRepository,
        QuestionnaireRepository $questionnaireRepository
    ): Response {
        $content = $request->getContent();
        $data = json_decode($content, true);

        // Extract the 'query' parameter from the nested 'payload' in the request
        $query = $data['query'] ?? 'Default Query';
        // $messages[] = [
        //     'role' => 'user',
        //     'content' => 'Based on information from the above topic, answer the following question: ' . $query
        // ];
        $messages = [
            ['role' => 'system', 'content' => 'You are an assistant. Please respond in UK English'],
            ['role' => 'user', 'content' => 'Query: ' . $query],
        ];
        $result = ($this->openApiClient)()->chat()->create([
            'model' => 'gpt-4',
            'messages' => array_values($messages),
            'temperature' => 0.9,
            'stream' => false,
        ]);
        $responseContent = $result['choices'][0]['message']['content'] ?? 'No response from GPT-4.';

        return $this->json(['data' => $responseContent]);
        // return $request;
    }

    // #[Route('/postcode', name: '-postcode')]
    // public function postCodes(Request $request)
    // {
    //     $postcodesIO = new PostcodesIO();
    //     $data = $postcodesIO->autocomplete($request->query->get('search'));

    //     return $this->json(['data' => $data->result]);
    // }
}
