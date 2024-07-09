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
use Knp\Component\Pager\PaginatorInterface;

#[Route('/bid-writer', name: 'app_bid-writer')]
class BidWriterController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly MessageBusInterface $bus
    ) {}

    #[Route('', name: '')]
    public function index(
        Request $request,
        QuestionnaireAnswerRepository $questionnaireAnswerRepository,
        QuestionnaireRepository $questionnaireRepository,
        PaginatorInterface $paginator,
    ): Response {
        // $items = $questionnaireAnswerRepository->findBy(['user' => $this->getUser()], ['createdAt' => 'DESC']);

        // Get the current user
        $user = $this->getUser();

        // Create a query for fetching the items
        $queryBuilder = $questionnaireAnswerRepository->createQueryBuilder('qa')
            ->where('qa.user = :user')
            ->setParameter('user', $user)
            ->orderBy('qa.createdAt', 'DESC');

        // Paginate the query results
        $pagination = $paginator->paginate(
            $queryBuilder, /* query builder */
            $request->query->getInt('page', 1), /* page number */
            20 /* limit per page */
        );
        ///

        $q = $questionnaireRepository->findOneBy(['id' => 5]);
        $form = $this->createForm(QuestionnaireType::class, $q);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Questionnaire $questionnaire */
            $questionnaire = $form->getData();

            $answers = new QuestionnaireAnswer();
            $answers->setUser($this->getUser());
            $answers->setPostcodes($form->get('postcodes')->getData());

            $this->entityManager->persist($answers);

            foreach ($form->get('questions') as $question) {
                $denominators = $question->get('denominators')->getData();
                $question = $question->getData();
                $answer = new Answer();
                $answer->setQuestion($question->getQuestion() ?? $question->getAnswer());
                $answer->setInstruction($question->getInstruction());

                if ($question->getQuestion()) {
                    $answer->setAnswer($question->getAnswer());
                } else {
                    $answer->setDenominators($denominators);
                    $answer->setGpt(true);
                }
                $this->entityManager->detach($question);

                $answer->setQuestionnaireAnswer($answers);
                $this->entityManager->persist($answer);
            }

            $this->entityManager->detach($questionnaire);
            $this->entityManager->flush();

            $this->bus->dispatch(new \App\Message\QuestionnaireAnswer($answers->getId()));

            return $this->redirectToRoute('app_bid-writer');
        }

        return $this->render('bid_writer/index.html.twig', [
            // 'items' => $items,
            'items' => $pagination,
            'form' => $form,
        ]);
    }

    #[Route('/postcode', name: '-postcode')]
    public function postCodes(Request $request)
    {
        $postcodesIO = new PostcodesIO();
        $data = $postcodesIO->autocomplete($request->query->get('search'));

        return $this->json(['data' => $data->result]);
    }

    #[Route('/create', name: '-create')]
    public function create(
        Request $request,
        QuestionnaireRepository $questionnaireRepository
    ) {
        $q = $questionnaireRepository->findOneBy(['id' => 5]);
        $form = $this->createForm(QuestionnaireType::class, $q);
        $form->handleRequest($request);

        //        if ($form->isSubmitted() && $form->isValid()) {
        //            /** @var Questionnaire $questionnaire */
        //            $questionnaire = $form->getData();
        //
        //            $answers = new QuestionnaireAnswer();
        //            $answers->setUser($this->getUser());
        //            $answers->setPostcodes($form->get('postcodes')->getData());
        //
        //            $this->entityManager->persist($answers);
        //
        //            foreach ($form->get('questions') as $question) {
        //                $denominators = $question->get('denominators')->getData();
        //                $question = $question->getData();
        //                $answer = new Answer();
        //                $answer->setQuestion($question->getQuestion() ?? $question->getAnswer());
        //                $answer->setInstruction($question->getInstruction());
        //
        //                if ($question->getQuestion()) {
        //                    $answer->setAnswer($question->getAnswer());
        //                } else {
        //                    $answer->setDenominators($denominators);
        //                    $answer->setGpt(true);
        //                }
        //                $this->entityManager->detach($question);
        //
        //                $answer->setQuestionnaireAnswer($answers);
        //                $this->entityManager->persist($answer);
        //            }
        //
        //            $this->entityManager->detach($questionnaire);
        //            $this->entityManager->flush();
        //
        //            $this->bus->dispatch(new \App\Message\QuestionnaireAnswer($answers->getId()));
        //
        //            return $this->redirectToRoute('app_bid-writer');
        //        }

        return $this->render('bid_writer/create.html.twig', [
            'form' => $form,
        ]);
    }
}
