<?php

namespace App\Controller;

use App\Entity\Tariff;
use App\Entity\Transaction;
use App\Entity\User;
use App\Entity\UserTariff;
use App\Repository\TariffRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Stripe\Customer;
use Stripe\Price;
use Stripe\Product;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class PaymentController extends AbstractController
{
    public function __construct(
        private UserRepository $userRepository,
        private TariffRepository $tariffRepository,
        private readonly EntityManagerInterface $entityManager,
        private readonly string $stripeSecretKey
    ) {}

    //    #[Route('/payment/list', name: 'app_payment_list')]
    //    public function list(): Response
    //    {
    //        $tariffs = $this->tariffRepository->findAll();
    //
    //        return $this->render('payment/list.html.twig', [
    //            'tariffs' => $tariffs,
    //        ]);
    //    }

    //    #[Route('/payment/{tariff}', name: 'app_payment', requirements: ['tariff' => '\d+'])]
    //    public function index(Payum $payum, Tariff $tariff): Response
    //    {
    //        $gatewayName = 'stripe_js';
    //
    //        $storage = $payum->getStorage(Payment::class);
    //
    //        /** @var Payment $payment */
    //        $payment = $storage->create();
    //
    //        $payment->setTotalAmount($tariff->getPrice() * 100);
    //        $payment->setCurrencyCode('GBP');
    //        $payment->setDescription("{$tariff->getPrice()} GBP");
    //        $payment->setClientId($this->getUser()->getId());
    //        $payment->setClientEmail($this->getUser()->getEmail());
    //
    //        $payment->setDetails(new \ArrayObject([
    //            'amount' => 2000,
    //            'currency' => 'gbp',
    //
    //            //            'customer' => ['plan' => 'gold'],
    //            // everything in this section is never sent to the payment gateway
    //            //            'local' => [
    //            //                'save_card' => true,
    //            //            ],
    //        ]));
    //
    //        $payment->setTariff($tariff);
    //
    //        $storage->update($payment);
    //
    //        $captureToken = $payum->getTokenFactory()->createCaptureToken(
    //            $gatewayName,
    //            $payment,
    //            'app_payment_done' // the route to redirect after capture;
    //        );
    //
    //        return $this->redirect($captureToken->getTargetUrl());
    //    }

    //    #[Route('/payment/done', name: 'app_payment_done')]
    //    public function done(Payum $payum, Request $request): Response
    //    {
    //        try {
    //            $token = $payum->getHttpRequestVerifier()->verify($request);
    //        } catch (\Exception $e) {
    //            return $this->redirectToRoute('app_payment_list');
    //        }
    //        if (!$token) {
    //            return $this->redirectToRoute('app_payment_list');
    //        }
    //
    //        $gateway = $payum->getGateway($token->getGatewayName());
    //
    //        // You can invalidate the token, so that the URL cannot be requested any more:
    //        $payum->getHttpRequestVerifier()->invalidate($token);
    //
    //        // Once you have the token, you can get the payment entity from the storage directly.
    //        // $identity = $token->getDetails();
    //        // $payment = $this->get('payum')->getStorage($identity->getClass())->find($identity);
    //
    //        // Or Payum can fetch the entity for you while executing a request (preferred).
    //        $gateway->execute($status = new GetHumanStatus($token));
    //        $payment = $status->getFirstModel();
    //
    //        if ('captured' === $status->getValue()) {
    //            /** @var User $user */
    //            $user = $this->getUser();
    //            /* @var Payment $payment */
    //            $user->setPoints($user->getPoints() + $payment->getTariff()->getPoints());
    //            $user->setSubscribedAt(new \DateTimeImmutable());
    //
    //            $this->userRepository->save($user, true);
    //        }
    //
    //        return $this->render('captured' === $status->getValue() ? 'payment/success.html.twig' : 'payment/error.html.twig', [
    //            'status' => $status->getValue(),
    //            'payment' => [
    //                'total_amount' => $payment->getTotalAmount(),
    //                'currency_code' => $payment->getCurrencyCode(),
    //                'details' => $payment->getDetails(),
    //            ],
    //        ]);
    //    }

    #[Route('/payment/subscribe', name: 'app_payment_subscribe')]
    public function subscribtions(
    ): Response {
        Stripe::setApiKey($this->stripeSecretKey);

        $products = Product::all([
            'active' => true,
        ]);

        $data = [];

        foreach ($products->data as $product) {
            $price = Price::all([
                'product' => $product->id,
                'active' => true,
            ]);
            $data[$product->id]['product'] = $product;
            $data[$product->id]['price'] = $price->data;
        }

        usort($data, static fn ($a, $b) => $a['product']->metadata->order <=> $b['product']->metadata->order);

        $data = array_filter($data, static fn ($item) => isset($item['product']->metadata->order));

        return $this->render('payment/subscribe.html.twig', [
            'tariffs' => $data,
        ]);
    }

    #[Route('/payment/subscribe/{priceId}', name: 'app_payment_subscribe_price')]
    public function subscribe(string $priceId,
        UrlGeneratorInterface $urlGenerator): Response
    {
        Stripe::setApiKey($this->stripeSecretKey);

        /** @var User $user */
        $user = $this->getUser();

        if (!$user->getCustomerId()) {
            $customers = Customer::all([
                'email' => $user->getEmail(),
                'limit' => 1, // Adjust the limit as needed
            ]);

            $id = $customers->first()?->id;

            if (!$id) {
                try {
                    $customer = Customer::create([
                        'name' => $user->getFirstName().' '.$user->getLastName(),
                        'email' => $user->getEmail(),
                    ]);
                    // Work with the $customer object
                    $user->setCustomerId($customer->id);
                } catch (\Stripe\Exception\ApiErrorException $e) {
                    // Handle the exception
                    //                    echo 'Error: '.$e->getMessage();
                }
            } else {
                $user->setCustomerId($id);
            }

            $this->entityManager->flush();
        }

        $session = \Stripe\Checkout\Session::create([
            'success_url' => $urlGenerator->generate('app_payment_success', [
                'session_id' => '{CHECKOUT_SESSION_ID}',
            ], UrlGeneratorInterface::ABSOLUTE_URL),
            'cancel_url' => $urlGenerator->generate('app_payment_subscribe', [], UrlGeneratorInterface::ABSOLUTE_URL),
            'mode' => 'subscription',

            'customer' => $user->getCustomerId(),

            'line_items' => [[
                'price' => $priceId,
                // For metered billing, do not pass quantity
                'quantity' => 1,
            ]],
            'subscription_data' => [
                'trial_period_days' => 7,
            ],
        ]);

        $transaction = new Transaction();
        $transaction->setUser($user);
        $transaction->setSessionId($session->id);
        $transaction->setAmountTotal($session->amount_total);
        $transaction->setAmountSubtotal($session->amount_subtotal);

        $this->entityManager->persist($transaction);
        $this->entityManager->flush();

        return $this->redirect($session->url);
    }

    #[Route('/payment/cancel', name: 'app_payment_cancel')]
    public function cancel(): \Symfony\Component\HttpFoundation\RedirectResponse
    {
        Stripe::setApiKey($this->stripeSecretKey);

        try {
            /** @var User $user */
            $user = $this->getUser();
            $customerId = $user->getCustomerId(); // Replace with your actual customer ID
            $subscriptions = \Stripe\Subscription::all(['customer' => $customerId]);

            // Process the subscriptions as needed
            foreach ($subscriptions->autoPagingIterator() as $subscription) {
                // You can access subscription details here
                $userSubscription = \Stripe\Subscription::retrieve($subscription->id);
                $userSubscription->cancel();
            }
        } catch (\Stripe\Exception\ApiErrorException $e) {
            // Handle exceptions (e.g., customer not found, API error)
        }

        $userTariffs = $this->entityManager->getRepository(UserTariff::class)
            ->findBy(['user' => $user])
        ;

        foreach ($userTariffs as $userTariff) {
            $this->entityManager->remove($userTariff);
        }

        $this->entityManager->flush();

        return $this->redirectToRoute('app_payment_subscribe');
    }

    #[Route('/payment/success', name: 'app_payment_success')]
    public function success(): Response
    {
        return $this->render('payment/success.html.twig');
    }

    #[Route('/payment/failure', name: 'app_payment_failure')]
    public function failure(): Response
    {
        return $this->render('payment/error.html.twig');
    }
}
