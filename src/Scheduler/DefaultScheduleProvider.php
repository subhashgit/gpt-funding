<?php

namespace App\Scheduler;

use App\Message\EmailNotify;
use App\Message\GetGrantInfo;
use App\Message\RegistrationFromSheet;
use App\Message\ScrapContractFinder;
use App\Message\ScrapFindTender;
use App\Message\UpdateStatus;
use Symfony\Component\Scheduler\Attribute\AsSchedule;
use Symfony\Component\Scheduler\RecurringMessage;
use Symfony\Component\Scheduler\Schedule;
use Symfony\Component\Scheduler\ScheduleProviderInterface;

#[AsSchedule('default')]
class DefaultScheduleProvider implements ScheduleProviderInterface
{
    /**
     * Returns a Schedule object containing recurring messages that need to be sent at specific time intervals.
     *
     * @return Schedule object containing recurring messages to be sent at specific time intervals
     */
    public function getSchedule(): Schedule
    {
        return (new Schedule())
            ->add(
                RecurringMessage::cron('30 * * * *', new UpdateStatus()),
                //                RecurringMessage::cron('0 0 * * *', new GetGrantInfo()),
                RecurringMessage::cron('0 0 * * *', new ScrapContractFinder()),
                RecurringMessage::cron('0 3 * * *', new ScrapFindTender()),
                RecurringMessage::cron('0 12 * * *', new EmailNotify()),
                RecurringMessage::cron('* * * * *', new RegistrationFromSheet()),
            )
        ;
    }
}
