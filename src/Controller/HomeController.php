<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\Notification\UserGrantNotificationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly MessageBusInterface $bus
    ) {}

    #[Route('', name: 'app_home')]
    public function index(Request $request,
        PaginatorInterface $paginator,
        UserGrantNotificationRepository $grantNotificationRepository,
    ): Response {
        /** @var User $user */
        $user = $this->getUser();
        //        if (!$user->isBrief()) {
        //            return $this->redirectToRoute('app_brief');
        //        }

        $query = $grantNotificationRepository->paginationQuery($user);

        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1), /* page number */
            20 /* limit per page */
        );

        return $this->render('home/index.html.twig', [
            'pagination' => $pagination,
            'paginationData' => $pagination->getPaginationData(),
        ]);
    }
}
