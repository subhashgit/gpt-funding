<?php

namespace App\EventListener;

use App\Entity\CPVCode;
use App\Entity\Funding;
use App\Entity\FundingRegion;
use App\Entity\Notification\UserFundingNotification;
use App\Repository\CPVCodeRepository;
use App\Repository\UserRepository;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ORM\Event\PostPersistEventArgs;
use Doctrine\ORM\Events;

#[AsDoctrineListener(event: Events::postPersist, priority: 500, connection: 'default')]
class TenderNotificationListener
{
    public function __construct(
        private readonly CPVCodeRepository $codeRepository,
        private readonly UserRepository $userRepository,
    ) {}

    public function __invoke(PostPersistEventArgs $event): void
    {
        if ($event->getObject() instanceof Funding) {
            /** @var Funding $funding */
            $funding = $event->getObject();

            $sectorCodes = array_map(fn (CPVCode $item) => $item->getCode(), $funding->getCpv()?->toArray());
            $regionIds = array_map(fn (FundingRegion $item) => $item->getId(), $funding->getFundingRegions()?->toArray());

            $sectors = array_unique(
                array_map(
                    static fn ($item) => "{$item[0]}{$item[1]}000000",
                    $sectorCodes
                )
            );
            $s = $this->codeRepository->findMainCategoryIds($sectors);

            foreach ($this->userRepository->findAll() as $user) {
                $filters = $user->getUserFilters()->toArray();
                if (!$filters) {
                    continue;
                }

                $notified = [];

                foreach ($filters as $filter) {
                    if ('region' === $filter->getType() && in_array($filter->getTypeId(), $regionIds)) {
                        if (in_array($funding->getId(), $notified)) {
                            continue;
                        }
                        $userNotification = new UserFundingNotification();
                        $userNotification->setUser($user);
                        $userNotification->setFunding($funding);
                        $userNotification->setType($filter->getType());

                        $notified[] = $funding->getId();

                        $event->getObjectManager()->persist($userNotification);
                    } elseif ('sector' === $filter->getType() && in_array($filter->getTypeId(), $s)) {
                        if (in_array($funding->getId(), $notified)) {
                            continue;
                        }
                        $userNotification = new UserFundingNotification();
                        $userNotification->setUser($user);
                        $userNotification->setFunding($funding);
                        $userNotification->setType($filter->getType());

                        $notified[] = $funding->getId();

                        $event->getObjectManager()->persist($userNotification);
                    } elseif ('keyword' === $filter->getType() && str_contains($funding->getTitle(), $filter->getTypeId())) {
                        if (in_array($funding->getId(), $notified)) {
                            continue;
                        }
                        $userNotification = new UserFundingNotification();
                        $userNotification->setUser($user);
                        $userNotification->setFunding($funding);
                        $userNotification->setType($filter->getType());

                        $notified[] = $funding->getId();

                        $event->getObjectManager()->persist($userNotification);
                    }
                }
            }

            $event->getObjectManager()->flush();
        }
    }
}
