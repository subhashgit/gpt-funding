<?php

namespace App\Controller;

use App\Entity\Embedding\UserProjectEmbedding;
use App\Entity\User;
use App\Entity\UserProject;
use App\Form\UserProjectType;
use App\Service\EmbeddingService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProjectController extends AbstractController
{
    public function __construct(
        private readonly EmbeddingService $embeddingService,
    ) {}

    #[Route('/project', name: 'app_project')]
    public function index(
        Request $request,
        EntityManagerInterface $em,
    ): Response {
        /** @var User $user */
        $user = $this->getUser();

        $project = $user->getProjects()->first();

        $form = $this->createForm(UserProjectType::class, $project ?: null);
        $form->handleRequest($request);

        if ($form->isSubmitted() and $form->isValid()) {
            /** @var UserProject $project */
            $project = $form->getData();

            $vector = $this->embeddingService->generateEmbedding($project->getDescription().' '.$project->getProjectExamples());

            $project->setUser($user);

            $em->persist($project);

            $embedding = $project->getEmbedding() ?? new UserProjectEmbedding();
            $embedding->setEntityId($project->getId());
            $embedding->setVector(json_encode($vector->embeddings[0]->embedding));
            $embedding->setEntity($project);

            $user->setPoints($user->getPoints() - 1);

            $em->persist($embedding);
            $em->flush();
        }

        return $this->render('project/index.html.twig', [
            'form' => $form,
        ]);
    }
}
