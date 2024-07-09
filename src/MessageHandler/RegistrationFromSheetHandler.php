<?php

namespace App\MessageHandler;

use App\Entity\User;
use App\Enum\AccountType;
use App\Message\RegistrationFromSheet;
use App\Repository\UserRepository;
use Google\Client;
use Google\Service\Sheets as GoogleSheets;
use Revolution\Google\Sheets\Sheets;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class RegistrationFromSheetHandler implements MessageHandlerInterface
{
    public function __construct(
        private Client $client,
        private UserRepository $userRepository,
        private UserPasswordHasherInterface $userPasswordHasher,
        private MailerInterface $mailer
    ) {}

    public function __invoke(RegistrationFromSheet $message): void
    {
        $this->freeSubscription();
        $this->active();
        $this->cancelSubscription();
    }

    public function random_password($lower, $upper, $digits, $special_characters): string
    {
        $lower_case = 'abcdefghijklmnopqrstuvwxyz';
        $upper_case = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $numbers = '1234567890';
        $symbols = '!@#$%^&*';

        $lower_case = str_shuffle($lower_case);
        $upper_case = str_shuffle($upper_case);
        $numbers = str_shuffle($numbers);
        $symbols = str_shuffle($symbols);

        $random_password = substr($lower_case, 0, $lower);
        $random_password .= substr($upper_case, 0, $upper);
        $random_password .= substr($numbers, 0, $digits);
        $random_password .= substr($symbols, 0, $special_characters);

        return str_shuffle($random_password);
    }

    public function cancelSubscription(): void
    {
        $values = $this->readGoogleSheet('Cancelled Subscriptions');

        foreach ($values as $value) {
            $email = $value['Email'];

            /** @var User $user */
            $user = $this->userRepository->getUser($email);

            if (!$user) {
                continue;
            }

            $user->setPoints(0);
            $user->setSubscribedAt(null);
            $user->setAccountType(AccountType::FREE);

            $this->userRepository->save($user, true);
        }
    }

    public function freeSubscription(): void
    {
        $values = $this->readGoogleSheet('Free Users');

        foreach ($values as $value) {
            $email = $value['Email'];

            $user = $this->userRepository->getUser($email);
            if (!$user) {
                $password = $this->random_password(5, 3, 2, 5);

                $user = new User();
                $user->setEmail($email);
                $user->setPoints(0);
                $user->setSubscribedAt(null);
                $user->setRoles(['ROLE_USER']);
                $user->setAccountType(AccountType::FREE);
                $user->setPassword(
                    $this->userPasswordHasher->hashPassword(
                        $user,
                        $password
                    )
                );

                $this->userRepository->save($user, true);

                $emailToSend = $this->getEmailToSend($user, $email, $password);

                $this->mailer->send($emailToSend);
            } else {
                $user->setPoints(0);
                $user->setSubscribedAt(null);
                $user->setAccountType(AccountType::FREE);

                $this->userRepository->save($user, true);
            }
        }
    }

    private function readGoogleSheet(string $sheetName): array
    {
        $this->client->setScopes([GoogleSheets::DRIVE, GoogleSheets::SPREADSHEETS]);

        $service = new GoogleSheets($this->client);

        $sheets = new Sheets();
        $sheets->setService($service);

        $values = $sheets
            ->spreadsheet('1GqyPJbvzYqVPGsRHDmqO5EwwqNB9ShAKNfI9H5SewdE')
            ->sheet($sheetName)
            ->all()
        ;

        $keys = array_shift($values);

        return array_map(static function ($row) use ($keys) {
            if (count($row) === count($keys)) {
                return array_combine($keys, $row);
            }

            $itemsToAdd = count($keys) - count($row);

            return array_combine($keys, $row + array_fill(count($row), $itemsToAdd, null));
        }, $values);
    }

    public function active(): void
    {
        $values = $this->readGoogleSheet('Active Subscriptions');

        foreach ($values as $value) {
            $email = $value['Email'];
            $user = $this->userRepository->getUser($email);
            if (!$user) {
                $password = $this->random_password(5, 3, 2, 5);

                $user = new User();
                $user->setEmail($email);
                $user->setPoints('Year' === $value['Type'] ? 3000 : 250);
                $user->setAccountType('Year' === $value['Type'] ? AccountType::YEARLY : AccountType::MONTHLY);
                $user->setSubscribedAt(new \DateTimeImmutable());
                $user->setRoles(['ROLE_USER']);
                $user->setPassword(
                    $this->userPasswordHasher->hashPassword(
                        $user,
                        $password
                    )
                );

                $this->userRepository->save($user, true);

                $emailToSend = $this->getEmailToSend($user, $email, $password);

                $this->mailer->send($emailToSend);
            } else {
                $user->setPoints('Year' === $value['Type'] ? 3000 : 250);
                $user->setAccountType('Year' === $value['Type'] ? AccountType::YEARLY : AccountType::MONTHLY);
                $user->setSubscribedAt(new \DateTimeImmutable($value['Date']));

                $this->userRepository->save($user, true);
            }
        }
    }

    public function getEmailToSend(User $user, mixed $email, string $password): TemplatedEmail
    {
        return (new TemplatedEmail())
            ->from(new Address('no-replay@mail.fundin.uk', 'Fundin.ai'))
            ->to($user->getEmail())
            ->subject('Your account has been created!')
            ->htmlTemplate('emails/create-account.html.twig')
            ->context([
                'Email' => $email,
                'password' => $password,
            ])
        ;
    }
}
