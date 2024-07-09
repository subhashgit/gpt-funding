<?php

namespace App\EntityListener;

use App\Entity\Message\GrantMessage;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Event\PostPersistEventArgs;
use Doctrine\ORM\Events;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

#[AsEntityListener(event: Events::postPersist, entity: GrantMessage::class, method: 'postPersist')]
class GrantMessageEntityListener
{
    public function __construct(
        private readonly MailerInterface $mailer,
    ) {}

    public function postPersist(GrantMessage $grantMessage, PostPersistEventArgs $event): void
    {
        if ($grantMessage->getAuthor()->getId() === $grantMessage->getRequest()->getUser()->getId()) {
            return;
        }

        $message = (new TemplatedEmail())
            ->from(new Address('no-replay@mail.fundin.uk', 'No Reply'))
            ->to($grantMessage->getRequest()->getUser()->getEmail())
            ->subject('New Comment For Your Grant Request')
            ->htmlTemplate('grant_request/new_comment_email.html.twig')
            ->context([
                'message' => $grantMessage,
            ])
        ;

        $this->mailer->send($message);
    }
}
