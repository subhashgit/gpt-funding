<?php

namespace App\Controller;

use App\Entity\CPVCode;
use App\Entity\FundingRegion;
use App\Entity\GrantCategory;
use App\Entity\GrantLocation;
use App\Entity\GrantOpenTo;
use App\Entity\UserFilter;
use App\Form\ChangePasswordType;
use App\Form\SearchKeywordsType;
use App\Form\UserProfileType;
use App\Repository\CPVCodeRepository;
use App\Repository\FundingRegionRepository;
use App\Repository\FundingRepository;
use App\Repository\GrantCategoryRepository;
use App\Repository\GrantLocationRepository;
use App\Repository\GrantOpenToRepository;
use App\Repository\GrantRepository;
use App\Repository\UserFilterRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;

class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    public function index(
        EntityManagerInterface $manager,
        Request $request,
        UserPasswordHasherInterface $userPasswordHasher,
    ): Response {
        $form = $this->createForm(ChangePasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();

            $data = $form->getData();
            $currentPassword = $data['currentPassword'];

            if (!$userPasswordHasher->isPasswordValid($user, $currentPassword)) {
                throw new BadCredentialsException('Invalid current password');
            }

            $newPassword = $data['newPassword'];

            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $newPassword
                )
            );

            $manager->persist($user);
            $manager->flush();

            // Redirect or add a flash message to indicate successful password change
            // Replace 'profile' with the route name or URL you want to redirect to
            return $this->redirectToRoute('app_profile');
        }

        return $this->render('profile/index.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/profile/alerts', name: 'app_profile_alerts')]
    public function alerts(
        EntityManagerInterface $manager,
        GrantRepository $grantRepository,
        FundingRepository $fundingRepository,
        CPVCodeRepository $codeRepository,
        UserFilterRepository $userFilterRepository,
        GrantLocationRepository $grantLocationRepository,
        GrantCategoryRepository $grantCategoryRepository,
        GrantOpenToRepository $grantOpenToRepository,
        FundingRegionRepository $fundingRegionRepository,
    ): Response|\Symfony\Component\HttpFoundation\RedirectResponse {
        $emailNotificationForm = $this->createForm(UserProfileType::class, $this->getUser());
        $userKeywords = $userFilterRepository->findBy(['user' => $this->getUser(), 'type' => 'keyword']);
        $keywordsForm = $this->createForm(SearchKeywordsType::class, [
            'keywords' => $userKeywords,
        ]);

        $filtersLocation = $grantRepository->getFilters('location');
        $filtersCategory = $grantRepository->getFilters('category');
        $filtersOpenTo = $grantRepository->getFilters('open_to');

        $filterRegions = $fundingRepository->getFilters('region');
        $filterSectors = $fundingRepository->getFilters('sector');

        if ($keywordsForm->isSubmitted() && $keywordsForm->isValid()) {
            foreach ($userKeywords as $userKeyword) {
                $manager->remove($userKeyword);
            }

            $manager->flush();

            /** @var UserFilter $keyword */
            foreach ($keywordsForm->getData()['keywords'] as $keyword) {
                $keyword->setUser($this->getUser());
                $keyword->setType('keyword');
                $keyword->setNotificationType('keyword');
                $manager->persist($keyword);
            }

            $manager->flush();

            // Redirect or add a flash message to indicate successful password change
            // Replace 'profile' with the route name or URL you want to redirect to
            return $this->redirectToRoute('app_profile');
        }

        if ($emailNotificationForm->isSubmitted() && $emailNotificationForm->isValid()) {
            $manager->persist($emailNotificationForm->getData());
            $manager->flush();
        }

        $userFilters = $userFilterRepository->findBy(['user' => $this->getUser()]);

        $filters = [
            'location' => [],
            'category' => [],
            'openTo' => [],
            'region' => [],
            'sector' => [],
        ];
        array_map(static function (UserFilter $item) use (&$filters) {
            $filters[$item->getType()][] = $item->getTypeId();
        }, $userFilters);

        $data = [
            'location' => [],
            'category' => [],
            'openTo' => [],
            'region' => [],
            'sector' => [],
        ];

        foreach ($filters as $key => $filter) {
            switch ($key) {
                case 'location':
                    $data['location'] = array_map(
                        fn (GrantLocation $item) => $item->getLocation(),
                        $grantLocationRepository->findBy(['id' => $filter])
                    );

                    break;

                case 'category':
                    $data['category'] = array_map(
                        fn (GrantCategory $item) => $item->getCategory(),
                        $grantCategoryRepository->findBy(['id' => $filter])
                    );

                    break;

                case 'openTo':
                    $data['openTo'] = array_map(
                        fn (GrantOpenTo $item) => $item->getOpenTo(),
                        $grantOpenToRepository->findBy(['id' => $filter])
                    );

                    break;

                case 'region':
                    $data['region'] = array_map(
                        fn (FundingRegion $item) => $item->getRegion(),
                        $fundingRegionRepository->findBy(['id' => $filter])
                    );

                    break;

                case 'sector':
                    $data['sector'] = array_map(
                        fn (CPVCode $item) => $item->getDescription(),
                        $codeRepository->findBy(['id' => $filter])
                    );

                    break;
            }
        }

        $sectors = array_unique(
            array_map(
                static fn ($item) => "{$item[0]}{$item[1]}000000",
                array_keys($filterSectors)
            )
        );
        $s = $codeRepository->findMainCategory($sectors);
        sort($s);

        return $this->render('profile/alerts.html.twig', [
            'keywordsForm' => $keywordsForm,
            'emailNotificationForm' => $emailNotificationForm,
            'filtersLocation' => $filtersLocation,
            'filtersCategory' => $filtersCategory,
            'filtersOpenTo' => $filtersOpenTo,
            'filterRegions' => $filterRegions,
            'filterSectors' => $s,
            'selectedFilters' => $data,
        ]);
    }
}
