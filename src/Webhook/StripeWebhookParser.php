<?php

namespace App\Webhook;

use Stripe\Stripe;
use Symfony\Component\HttpFoundation\ChainRequestMatcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestMatcher\HostRequestMatcher;
use Symfony\Component\HttpFoundation\RequestMatcher\IsJsonRequestMatcher;
use Symfony\Component\HttpFoundation\RequestMatcher\MethodRequestMatcher;
use Symfony\Component\HttpFoundation\RequestMatcherInterface;
use Symfony\Component\RemoteEvent\RemoteEvent;
use Symfony\Component\Webhook\Client\AbstractRequestParser;

class StripeWebhookParser extends AbstractRequestParser
{
    public function __construct(
        private readonly string $stripeSecretKey,
        private readonly string $stripeWebhookSecret
    ) {}

    protected function getRequestMatcher(): RequestMatcherInterface
    {
        return new ChainRequestMatcher([
            //            new HostRequestMatcher('stripe.com'),
            //            new IsJsonRequestMatcher(),
            new MethodRequestMatcher('POST'),
        ]);
    }

    protected function doParse(Request $request, string $secret): ?RemoteEvent
    {
        Stripe::setApiKey($this->stripeSecretKey);
        $webhook_secret = $this->stripeWebhookSecret;
        $sig_header = $request->headers->get('stripe-signature');

        if (!$sig_header) {
            return null;
        }

        $payload = $request->getContent();
        if (!$payload) {
            return null;
        }

        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload, $sig_header, $webhook_secret
            );
        } catch (\UnexpectedValueException $e) {
            // Invalid payload
            http_response_code(400);
            exit;
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            // Invalid signature
            http_response_code(400);
            exit;
        }

        $payloadData = json_decode($payload, true);

        // Handle the event
        switch ($event->type) {
            case 'checkout.session.completed':
                // Payment is successful and the subscription is created.
                // You should provision the subscription and save the customer ID to your database.

                return new RemoteEvent('checkout.session.completed', $payloadData['data']['object']['id'], $payloadData);
            case 'invoice.paid':
                // Continue to provision the subscription as payments continue to be made.
                // Store the status in your database and check when a user accesses your service.
                // This approach helps you avoid hitting rate limits.

                return new RemoteEvent('invoice.paid', $payloadData['data']['object']['id'], $payloadData);
            case 'invoice.payment_failed':
                // The payment failed or the customer does not have a valid payment method.
                // The subscription becomes past_due. Notify your customer and send them to the
                // customer portal to update their payment information.

                return new RemoteEvent('invoice.payment_failed', $payloadData['data']['object']['id'], $payloadData);
            case 'payment_intent.canceled':
                return new RemoteEvent('payment_intent.canceled', $payloadData['data']['object']['id'], $payloadData);
            default:
                return null;
        }
    }
}
