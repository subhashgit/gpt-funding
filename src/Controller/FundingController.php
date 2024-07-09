<?php

namespace App\Controller;

use App\Entity\Funding;
use App\Entity\Party;
use App\Event\View;
use App\Repository\FundingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FundingController extends AbstractController
{
    #[Route('/tenders', name: 'app_funding')]
    public function index(FundingRepository $fundingRepository): Response
    {
        return $this->render('funding/index.html.twig', []);
    }

    #[Route('/tender/{slug}', name: 'app_funding_view')]
    public function view(Funding $funding, EventDispatcherInterface $dispatcher): Response
    {
        $dispatcher->dispatch(new View($funding));

        return $this->render('funding/view.html.twig', [
            'buyer' => $funding->getParties()->findFirst(fn ($key, Party $party) => in_array('buyer', $party->getRoles())),
            'funding' => $funding,
        ]);
    }
}
