<?php

namespace App\EventSubscriber;

use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class PaidCheckSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly Security $security,
        private readonly RequestStack $requestStack,
        private readonly UrlGeneratorInterface $urlGenerator
    ) {}

    public function onKernelRequest(RequestEvent $event): void
    {
        /** @var User $user */
        $user = $this->security->getUser();

        $request = $this->requestStack->getCurrentRequest();
        if (null === $request->get('_route')) {
            return;
        }

        if (str_starts_with($request->get('_route'), 'app_payment')) {
            return;
        }

        if (str_starts_with($request->get('_route'), 'sonata_')) {
            return;
        }

        if (str_starts_with($request->get('_route'), 'admin')) {
            return;
        }

        if ('app_brief' === $request->get('_route')) {
            return;
        }

        //        if ($user && !$user->isSubscriptionActive()) {
        //            $event->setResponse(new RedirectResponse($this->urlGenerator->generate('app_payment_subscribe', [], UrlGeneratorInterface::ABSOLUTE_URL)));
        //        }
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => 'onKernelRequest',
        ];
    }
}
