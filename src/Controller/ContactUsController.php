<?php

namespace App\Controller;

use App\Form\ContactUsType;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;

class ContactUsController extends AbstractController
{
    #[Route('/contact-us', name: 'app_contact_us')]
    public function index(Request $request, MailerInterface $mailer): Response
    {
        $form = $this->createForm(ContactUsType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $email = (new TemplatedEmail())
                ->from('no-replay@mail.fundin.uk')
                ->to('info@fundin.uk')
                ->priority(Email::PRIORITY_HIGH)
                ->subject('New Contact Us Message')
                ->htmlTemplate('emails/contact-us.html.twig')
                ->context([
                    'data' => $data,
                ])
            ;

            $mailer->send($email);

            return $this->redirectToRoute('app_contact_us', [
                'success' => true,
            ]);
        }

        return $this->render('contact_us/index.html.twig', [
            'form' => $form,
        ]);
    }
}
