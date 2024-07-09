<?php

namespace App\Controller;

use App\Entity\Message\GrantMessage;
use App\Entity\Request\GrantRequest;
use App\Entity\User;
use App\Form\GrantRequestType;
use App\Form\Message\GrantMessageType;
use App\Repository\Request\GrantRequestRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('requests')]
class GrantRequestController extends AbstractController
{
    #[Route('/grant', name: 'app_grant_request')]
    public function index(
        GrantRequestRepository $grantRequestRepository,
        PaginatorInterface $paginator,
        Request $request,
    ): Response {
        /** @var User $user */
        $user = $this->getUser();
        
        $status = $request->query->get('status');

        $requestQuery = $grantRequestRepository->paginationQuery($user, $status);

        $pagination = $paginator->paginate(
            $requestQuery, /* query NOT result */
            $request->query->getInt('page', 1), /* page number */
            20 /* limit per page */
        );

        return $this->render('grant_request/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    #[Route('/grant/{id}/delete', name: 'app_grant_request_delete')]
    public function delete(
        GrantRequest $grantRequest,
        EntityManagerInterface $entityManager,
    ): Response {
        $user = $this->getUser();
        if ($user && $grantRequest->getUser()?->getId() === $user->getId()) {
            $entityManager->remove($grantRequest);
            $entityManager->flush();
        } else {
            return $this->createNotFoundException('Page not found');
        }

        return $this->redirectToRoute('app_grant_request');
    }

    #[Route('/grant/{id}', name: 'app_grant_request_view')]
    public function view(
        GrantRequest $grantRequest,
        EntityManagerInterface $entityManager,
        Request $request,
    ) {
        $messages = $entityManager->getRepository(GrantMessage::class)
            ->getByRequest($grantRequest)
        ;

        /** @var User $user */
        $user = $this->getUser();
        $messageForm = $this->createForm(GrantMessageType::class);

        $messageForm->handleRequest($request);

        if ($messageForm->isSubmitted() && $messageForm->isValid()) {
            $message = $messageForm->getData();
            $message->setAuthor($user);
            $message->setRequest($grantRequest);
            $entityManager->persist($message);
            $entityManager->flush();

            return $this->redirectToRoute('app_grant_request_view', ['id' => $grantRequest->getId()]);
        }

        $formRequest = $this->createForm(GrantRequestType::class, $grantRequest);
        $formRequest->handleRequest($request);
        if ($formRequest->isSubmitted() && $formRequest->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_grant_request_view', ['id' => $grantRequest->getId()]);
        }

        return $this->render('grant_request/view.html.twig', [
            'grantRequest' => $grantRequest,
            'formRequest' => $formRequest,
            'messageForm' => $messageForm,
            'messages' => $messages
        ]);
    }
}
