<?php

namespace App\Controller;

use App\Entity\Answer;
use App\Entity\QuestionnaireAnswer;
use App\Form\QuestionAnswerType;
use App\Repository\QuestionnaireAnswerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

class AnswerController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager, private MessageBusInterface $bus) {}

    #[Route('/answer/demo', name: 'app_answer_demo')]
    public function demo()
    {
        $QuestionnaireAnswer = new QuestionnaireAnswer();
        //        $QuestionnaireAnswer->set($this->entityManager->getRepository(\App\Entity\Questionnaire::class)->findOneBy(['id' => 5]));
        $QuestionnaireAnswer->setUser($this->getUser());
        $QuestionnaireAnswer->setPostcodes(['SW1A 1AA']);

        $answer = new Answer();
        $answer->setQuestion('Please describe your project. Consider including: what your project is, why it is important you deliver it, how you would deliver the project, where it will take place and the expected outcomes.');
        $answer->setInstruction(100);
        $answer->setAnswer('
The project aims to deliver a fitness-based programme for young people and adults with Special Educational Needs.

The programme will combine fitness sessions with workshops and social time to make a positive impact on the physical, mental, and social health of each participant.

Activities will include; boxercise, a chair-based adapted exercise programme, stretch and flex-based activities focusing on improving strength, balance and coordination and general fitness classes.

The project will be delivered over 2 years, with approximately 100 sessions lasting 2 hours.');
        $QuestionnaireAnswer->addAnswer($answer);
        $QuestionnaireAnswer->setSlug('111-111-111-11');

        $answer = new Answer();
        $answer->setQuestion('How do you know there is a need for your project? - Include any data from surveys you have completed');
        $answer->setInstruction(100);
        $answer->setAnswer("The need for this project became apparent through various indicators. Primarily, discussions with local special schools, parent groups, mental health charities, and healthcare providers have underlined a significant gap in accessible and adaptable fitness programmes for individuals with Special Educational Needs (SEN). There is a lack of services that promote physical activity while fostering social interaction and mental wellbeing within this demographic.

Additionally, we ran a survey that highlighted the importance of accessible fitness services for the SEN community. The survey received over 200 responses from families and caregivers, where an overwhelming 94% reported a lack of suitable fitness facilities or programs for their wards.

Furthermore, our research identified a significant drop in the engagement level of people with SEN in fitness activities compared to those without SEN. National statistics show that only 17% of adults with learning disabilities engage in the recommended 150 minutes of physical activity per week, compared to 53% of the general population. This disparity showcases a clear need to promote more fitness opportunities for individuals with SEN.

Moreover, it's not just the physical aspect that's lacking. Many parents and caregivers shared feedback on the need for social interaction and building self-esteem for their children and adults with SEN. This is particularly evident in adults with SEN, where loneliness, isolation, and depression are more prevalent.

Hence, our project, which combines physical activity with social interaction and self-esteem building, is not only important but essential. By providing a regular programme of varied fitness sessions, we aim to improve the overall physical health of participants. The social time and workshops are equally valuable, as these components will improve participants' mental health by creating a sense of community, building social skills, and boosting self-esteem.

In summary, a combination of conversations with key stakeholders, survey data, national statistics and anecdotal evidence clearly demonstrate a need for this project. Our fitness programme aims to enrich the lives of people with SEN, addressing their physical, mental and social health needs effectively.");
        $answer->setGpt(true);
        $QuestionnaireAnswer->addAnswer($answer);

        $form = $this->createForm(QuestionAnswerType::class, $QuestionnaireAnswer);

        return $this->render('answer/index.html.twig', [
            'answer' => $QuestionnaireAnswer,
            'form' => $form,
            'demo' => true,
        ]);
    }

    #[Route('/answer/{slug}', name: 'app_answer')]
    public function index(QuestionnaireAnswer $answer, Request $request): Response
    {
        $form = $this->createForm(QuestionAnswerType::class, $answer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $task = $form->getData();

            $this->entityManager->persist($task);
            $this->entityManager->flush();
        }

        return $this->render('answer/index.html.twig', [
            'answer' => $answer,
            'form' => $form,
        ]);
    }

    #[Route('/answer/{slug}/regenerate/{answerId}', name: 'app_answer_regenerate')]
    public function regenerate(QuestionnaireAnswer $answer, int $answerId, Request $request): Response
    {
        $a = $answer->getAnswer()->filter(fn (Answer $item) => $item->getId() === $answerId)->first();
        $a->setAnswer(null);

        $this->entityManager->flush();

        $this->bus->dispatch(new \App\Message\QuestionnaireAnswer($answer->getId()));

        return $this->redirectToRoute('app_bid-writer');
    }

    #[Route('/answer/{slug}/delete', name: 'app_answer_delete')]
    public function delete(QuestionnaireAnswer $answer, QuestionnaireAnswerRepository $answerRepository)
    {
        $this->entityManager->remove($answer);
        $this->entityManager->flush();

        return $this->redirectToRoute('app_bid-writer');
    }
}
