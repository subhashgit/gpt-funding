<?php

namespace App\Controller;

use App\Entity\Answer;
use App\Entity\Grant;
use App\Entity\Questionnaire;
use App\Entity\QuestionnaireAnswer;
use App\Event\View;
use App\Form\GrantRequestType;
use App\Form\QuestionnaireType;
use App\Repository\Embedding\GrantEmbeddingRepository;
use App\Repository\QuestionnaireRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class GrantController extends AbstractController
{
    #[Route('/grant', name: 'app_grant')]
    public function index(): Response
    {
        return $this->render('grant/index.html.twig', []);
    }

    #[Route('/grant/{slug}', name: 'app_grant_view')]
    public function view(
        Grant $grant,
        Request $request,
        QuestionnaireRepository $questionnaireRepository,
        EntityManagerInterface $entityManager,
        MessageBusInterface $bus,
        GrantEmbeddingRepository $grantEmbeddingRepository,
        EventDispatcherInterface $dispatcher,
        MailerInterface $mailer
    ): Response {
        $dispatcher->dispatch(new View($grant));

        $q = $questionnaireRepository->findOneBy(['id' => 5]);
        $form = $this->createForm(QuestionnaireType::class, $q);

        $formRequest = $this->createForm(GrantRequestType::class);

        $form->handleRequest($request);
        $formRequest->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Questionnaire $questionnaire */
            $questionnaire = $form->getData();

            $answers = new QuestionnaireAnswer();
            $answers->setUser($this->getUser());
            $answers->setGrant($grant);

            $entityManager->persist($answers);

            foreach ($questionnaire->getQuestions() as $question) {
                $answer = new Answer();
                $answer->setQuestion($question->getQuestion() ?? $question->getAnswer());
                $answer->setInstruction($question->getInstruction());
                if ($question->getQuestion()) {
                    $answer->setAnswer($question->getAnswer());
                } else {
                    $answer->setGpt(true);
                }
                $entityManager->detach($question);

                $answer->setQuestionnaireAnswer($answers);
                $entityManager->persist($answer);
            }

            $entityManager->detach($questionnaire);
            $entityManager->flush();

            $bus->dispatch(new \App\Message\QuestionnaireAnswer($answers->getId()));

            return $this->redirectToRoute('app_answer', ['slug' => $answers->getSlug()]);
        }

        if ($formRequest->isSubmitted() && $formRequest->isValid()) {
            $grantRequest = $formRequest->getData();
            $grantRequest->setUser($this->getUser());
            $grantRequest->setGrant($grant);
            $entityManager->persist($grantRequest);
            $entityManager->flush();

            // $email = (new TemplatedEmail())
            //     ->from(new Address('no-replay@mail.fundin.uk', 'No Reply'))
            //     ->to('info@fundin.uk')
            //     ->priority(Email::PRIORITY_HIGH)
            //     ->subject('New Grant Request')
            //     ->htmlTemplate('emails/new-grant-request.html.twig')
            //     ->context([
            //         'user' => $this->getUser(),
            //         'view' => $this->generateUrl('admin_app_request_grantrequest_edit', ['id' => $grantRequest->getId()], UrlGeneratorInterface::ABSOLUTE_URL),
            //     ])
            // ;

            // $mailer->send($email);

            return $this->redirectToRoute('app_grant_request_view', ['id' => $grantRequest->getId()]);
        }

        $similarGrants = $grantEmbeddingRepository->similarGrants(
            vector: $grant->getEmbedding()->getVector(),
            locations: $grant->getLocations()->map(fn ($l) => $l->getId())->toArray(),
            categories: $grant->getCategories()->map(fn ($l) => $l->getId())->toArray(),
            openTo: $grant->getOpenTo()->map(fn ($l) => $l->getId())->toArray(),
            limit: 6
        );


        return $this->render('grant/view.html.twig', [
            'grant' => $grant,
            'form' => $form,
            'formRequest' => $formRequest,
            'similarGrants' => $similarGrants,
        ]);
    }
}
