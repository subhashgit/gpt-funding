<?php

namespace App\WebhookConsumer;

use App\Entity\Tariff;
use App\Entity\User;
use App\Entity\UserTariff;
use Carbon\Carbon;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\RemoteEvent\Attribute\AsRemoteEventConsumer;
use Symfony\Component\RemoteEvent\Consumer\ConsumerInterface;
use Symfony\Component\RemoteEvent\RemoteEvent;

#[AsRemoteEventConsumer('stripe')]
class StripeCheckoutSessionComplete implements ConsumerInterface
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager
    ) {}

    public function consume(RemoteEvent $event): void
    {
        $eventName = $event->getName();
        if ('invoice.paid' === $eventName) {
            $object = $event->getPayload()['data']['object'];
            $customer = $object['customer'];
            $price = $object['lines']['data'][0]['price'];
            $pricePeriodEnd = $object['lines']['data'][0]['period']['end'];

            $user = $this->entityManager->getRepository(User::class)->findOneBy(['customerId' => $customer]);

            $userTariff = new UserTariff();
            $userTariff->setUser($user);
            $userTariff->setTariff(
                $this->entityManager->getRepository(Tariff::class)->findOneByPrice($price['id'])
            );
            $userTariff->setDateEnd(
                Carbon::createFromTimestampUTC($pricePeriodEnd)->toDateTimeImmutable()
            );
            $userTariff->setPriceId($price['id']);
            $userTariff->setPeriod($price['recurring']['interval']);

            $this->entityManager->persist($userTariff);
            $this->entityManager->flush();
        }
    }
}
