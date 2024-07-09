<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\BriefType;
use Doctrine\ORM\EntityManagerInterface;
use Google\Client;
use Google\Service\Sheets as GoogleSheets;
use Revolution\Google\Sheets\Sheets;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BriefController extends AbstractController
{
    #[Route('/brief', name: 'app_brief')]
    public function index(
        Request $request,
        Client $client,
        EntityManagerInterface $entityManager
    ): Response {
        $choices = [
            'achieve_with_fundin' => [
                'Find more funding opportunities' => 'find_more_funding',
                'Save time writing funding applications' => 'save_time_writing',
                'Fully outsource your bid writing' => 'outsource_bid_writing',
            ],
            'applications_per_month' => [
                '0' => '0',
                '1-3' => '1_3',
                '4-6' => '4_6',
                '7-10' => '7_10',
                '10+' => 'more_than_10',
            ],
            'time_looking_for_grants' => [
                '<1 hour' => 'less_than_1_hour',
                '1 to 3 hours' => '1_to_3_hours',
                '3 to 5 hours' => '3_to_5_hours',
                'More than 5 hours' => 'more_than_5_hours',
            ],
            'time_writing_grants' => [
                '<1 hour' => 'less_than_1_hour',
                '1 to 3 hours' => '1_to_3_hours',
                '3 to 5 hours' => '3_to_5_hours',
                'More than 5 hours' => 'more_than_5_hours',
            ],
            'success_rate' => [
                '0% - 20%' => '0_20_percent',
                '21% to 30%' => '21_30_percent',
                '31% to 50%' => '31_50_percent',
                '50% +' => 'more_than_50_percent',
            ],
        ];

        $client->setScopes([
            GoogleSheets::SPREADSHEETS,
            GoogleSheets::DRIVE,
        ]);

        $service = new GoogleSheets($client);

        $sheets = new Sheets();
        $sheets->setService($service);

        $sheet = $sheets
            ->spreadsheet('1MgRQUKFSsj14p53kKW7ZY9NPov9DFI4OMpG2mBYO-qg')
            ->sheet('Sheet1')
        ;

        $form = $this->createForm(BriefType::class, null, [
            'choices' => $choices,
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var User $user */
            $user = $this->getUser();
            $sheet->append([[
                'First name' => $user->getFirstName() ?? '',
                'Last name' => $user->getLastName() ?? '',
                'Email' => $user->getEmail() ?? '',
                'Company' => $user->getCompany() ?? '',
                'What are you looking to achieve with Fundin?' => $form->get('achieve_with_fundin')->getData() ? array_flip($choices['achieve_with_fundin'])[$form->get('achieve_with_fundin')->getData()] : '',
                'How many applications do you submit a month?' => $form->get('applications_per_month')->getData() ? array_flip($choices['applications_per_month'])[$form->get('applications_per_month')->getData()] : '',
                'On average, how long do you spend looking for grants each week?' => $form->get('time_looking_for_grants')->getData() ? array_flip($choices['time_looking_for_grants'])[$form->get('time_looking_for_grants')->getData()] : '',
                'On average, how long do you spend writing grants each week?' => $form->get('time_writing_grants')->getData() ? array_flip($choices['time_writing_grants'])[$form->get('time_writing_grants')->getData()] : '',
                'What is your current success rate?' => $form->get('success_rate')->getData() ? array_flip($choices['success_rate'])[$form->get('success_rate')->getData()] : '',
            ]]);

            $user->setBrief(true);
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_home');
        }

        return $this->render('brief/index.html.twig', [
            'form' => $form,
        ]);
    }
}
