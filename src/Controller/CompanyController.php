<?php

namespace App\Controller;

use App\Entity\Embedding\UserCompanyEmbedding;
use App\Entity\User;
use App\Entity\UserCompany;
use App\Form\UserCompanyType;
use App\Service\EmbeddingService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CompanyController extends AbstractController
{
    public function __construct(
        private readonly EmbeddingService $embeddingService,
    ) {}

    #[Route('/company', name: 'app_company')]
    public function index(
        Request $request,
        EntityManagerInterface $em,
    ): Response {
        /** @var User $user */
        $user = $this->getUser();

        $company = $user->getCompanies()->first();

        $form = $this->createForm(UserCompanyType::class, $company ?: null);
        $form->handleRequest($request);

        if ($form->isSubmitted() and $form->isValid()) {
            /** @var UserCompany $company */
            $company = $form->getData();

            $vector = $this->embeddingService->generateEmbedding($company->getDescription().' '.$company->getProjectExamples());

            $company->setUser($user);

            $em->persist($company);

            $embedding = $company->getEmbedding() ?? new UserCompanyEmbedding();
            $embedding->setEntityId($company->getId());
            $embedding->setVector(json_encode($vector->embeddings[0]->embedding));
            $embedding->setEntity($company);

            $user->setPoints($user->getPoints() - 1);

            $em->persist($embedding);
            $em->flush();
        }

        return $this->render('company/index.html.twig', [
            'form' => $form,
        ]);
    }
}
