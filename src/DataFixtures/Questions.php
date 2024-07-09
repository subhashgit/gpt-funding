<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class Questions extends Fixture
{
    private array $sectionA = [
        'Name' => [
            'section' => 'A',
            'type' => 'input',
        ],
        'Who do you support?' => [
            'section' => 'A',
            'type' => 'input',
        ],
        'Mission statement' => [
            'section' => 'A',
            'type' => 'textarea',
        ],
        'How many people/organisations do you work with?' => [
            'section' => 'A',
            'type' => 'number',
        ],
    ];

    private array $sectionB = [
        'Give a brief description of your project?' => [
            'section' => 'B',
            'type' => 'textarea',
        ],
        'Who is your project going to support?' => [
            'section' => 'B',
            'type' => 'textarea',
        ],
        'Why is it important you support these people?' => [
            'section' => 'B',
            'type' => 'textarea',
        ],
        'What does this project aim to do?' => [
            'section' => 'B',
            'type' => 'textarea',
        ],
        'How do we know if this project is successful?' => [
            'section' => 'B',
            'type' => 'textarea',
        ],
        'Will your project partner with any other organisations?' => [
            'section' => 'B',
            'type' => 'textarea',
        ],
        'Do you have examples of similar projects you have delivered?' => [
            'section' => 'B',
            'type' => 'textarea',
        ],
        'Anything else that is relevant to the application.' => [
            'section' => 'B',
            'type' => 'textarea',
        ],
    ];

    public function load(ObjectManager $manager): void
    {
        $questionnaire = new \App\Entity\Questionnaire();
        $manager->persist($questionnaire);

        foreach ($this->sectionA as $key => $item) {
            $question = new \App\Entity\Question();
            $question->setQuestion($key);
            $question->setSection($item['section']);
            $question->setType($item['type']);
            $question->setQuestionnaire($questionnaire);
            $manager->persist($question);
        }

        foreach ($this->sectionB as $key => $item) {
            $question = new \App\Entity\Question();
            $question->setQuestion($key);
            $question->setSection($item['section']);
            $question->setType($item['type']);
            $question->setQuestionnaire($questionnaire);
            $manager->persist($question);
        }

        $manager->flush();
    }
}
