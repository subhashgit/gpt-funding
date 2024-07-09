<?php

namespace App\Controller;

use App\Entity\Grant;
use App\Entity\User;
use App\Repository\Grant\SuggestionRepository;
use App\Service\SuggestionService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GrantSuggestionController extends AbstractController
{
    public function __construct(
        private readonly SuggestionRepository $suggestionRepository,
        private readonly SuggestionService $suggestionService,
        private readonly EntityManagerInterface $entityManager,
    ) {}

    #[Route('/suggestion/grant', name: 'app_grant_suggestion')]
    public function index(): Response
    {
        /** @var User $user */
        $user = $this->getUser();

        $items = $this->suggestionRepository->findBy(
            ['user' => $user],
            ['createdAt' => 'DESC']
        );

        return $this->render('grant_suggestion/index.html.twig', [
            'controller_name' => 'GrantSuggestionController',
            'items' => $items,
        ]);
    }

    #[Route('/suggestion/grant/{slug}/generate', name: 'app_grant_suggestion_generate', methods: ['POST'])]
    public function generate(Grant $grant): RedirectResponse
    {
        /** @var User $user */
        $user = $this->getUser();
        $company = $user->getCompanies()->first();

        $grantSuggestion = $this->suggestionRepository->findOneBy(['user' => $user, 'grant' => $grant]) ?: new Grant\Suggestion();

        $suggestions = $this->suggestionService->suggest($company, $grant);

        $grantSuggestion->setUser($user);
        $grantSuggestion->setGrant($grant);
        $grantSuggestion->setDescription($suggestions->choices[0]->message->content);
        $promptTokens = $grantSuggestion->getPromptTokens() + $suggestions->usage->promptTokens;
        $grantSuggestion->setPromptTokens($promptTokens);
        $completionTokens = $grantSuggestion->getCompletionTokens() + $suggestions->usage->completionTokens;
        $grantSuggestion->setCompletionTokens($completionTokens);
        $grantSuggestion->setTotalTokens($grantSuggestion->getTotalTokens() + $suggestions->usage->totalTokens);
        $grantSuggestion->setPointsConsumed($grantSuggestion->getPointsConsumed() + 1);

        $grantSuggestion->setEstimatedPrice(
            $grantSuggestion->getEstimatedPrice() + $promptTokens / 1000 * 0.0015 + $completionTokens / 1000 * 0.002
        );

        $user->setPoints($user->getPoints() - 1);

        $this->entityManager->persist($grantSuggestion);
        $this->entityManager->flush();

        return $this->redirectToRoute('app_grant_suggestion_view', ['slug' => $grantSuggestion->getSlug()]);
    }

    #[Route('/suggestion/grant/{slug}/delete', name: 'app_grant_suggestion_delete', methods: ['POST'])]
    public function delete(Grant\Suggestion $suggestion): RedirectResponse
    {
        /** @var User $user */
        $user = $this->getUser();
        if ($user->getId() !== $suggestion->getUser()->getId()) {
            return $this->redirectToRoute('app_grant_suggestion');
        }

        $this->entityManager->remove($suggestion);
        $this->entityManager->flush();

        return $this->redirectToRoute('app_grant_suggestion');
    }

    #[Route('/suggestion/grant/{slug}/view', name: 'app_grant_suggestion_view', methods: ['GET'])]
    public function view(Grant\Suggestion $suggestion): Response
    {
        /** @var User $user */
        $user = $this->getUser();
        if ($user->getId() !== $suggestion->getUser()->getId()) {
            return $this->redirectToRoute('app_grant_suggestion');
        }

        return $this->render('grant_suggestion/view.html.twig', [
            'suggestion' => $suggestion,
        ]);
    }
}
