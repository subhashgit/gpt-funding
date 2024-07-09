<?php

namespace App\MessageHandler;

use App\Message\UserEmailNotify;
use App\Repository\FundingRepository;
use App\Repository\GrantRepository;
use App\Repository\Notification\UserFundingNotificationRepository;
use App\Repository\Notification\UserGrantNotificationRepository;
use App\Repository\UserRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;

#[AsMessageHandler]
final class UserEmailNotifyHandler
{
    public function __construct(
        private readonly MailerInterface $mailer,
        private readonly UserFundingNotificationRepository $userFundingNotificationRepository,
        private readonly UserGrantNotificationRepository $userGrantNotificationRepository,
        private readonly GrantRepository $grantRepository,
        private readonly FundingRepository $fundingRepository,
        private readonly UserRepository $userRepository,
    ) {}

    public function __invoke(UserEmailNotify $message)
    {
        $user = $this->userRepository->find($message->userId);

        if ('grant' === $message->type) {
            $userNotifications = $this->userGrantNotificationRepository->getGrantIdsByUser($message->userId);
            $data = $this->grantRepository->findForEmail($userNotifications);
            $this->userGrantNotificationRepository->updateNotified($message->userId);
        } elseif ('funding' === $message->type) {
            $userNotifications = $this->userFundingNotificationRepository->getFundingIdsByUser($message->userId);
            $data = $this->fundingRepository->findForEmail($userNotifications);
            $this->userFundingNotificationRepository->updateNotified($message->userId);
        }

        if (!$data) {
            return;
        }

        $emails = $user->getNotificationEmail() ? explode(', ', $user->getNotificationEmail()) : [$user->getEmail()];

        foreach ($emails as $notificationEmail) {
            $email = (new TemplatedEmail())
                ->from(new Address('no-replay@mail.fundin.uk', 'No Reply'))
                ->to($notificationEmail)
                ->priority(Email::PRIORITY_HIGH)
                ->subject('New Funding Alert')
                ->htmlTemplate('emails/new-grants.html.twig')
                ->context([
                    'user' => $user,
                    'datas' => $data,
                    'type' => $message->type,
                ])
            ;

            $this->mailer->send($email);
        }
    }
}
