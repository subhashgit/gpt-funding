<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BriefType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $inputClass = 'shrink-0 mt-0.5 border-gray-200 rounded-full text-blue-600 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-gray-800 dark:border-gray-700 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800';
        $labelClass = 'text-sm text-gray-500 ms-2 dark:text-gray-400';
        $divClass = 'flex';
        $builder
            ->add('achieve_with_fundin', ChoiceType::class, [
                'choices' => $options['choices']['achieve_with_fundin'],
                'label' => 'What are you looking to achieve with Fundin?',
                'label_attr' => [
                    'class' => $labelClass,
                ],
                'row_attr' => [
                    'class' => 'flex',
                ],
                'attr' => [
                    'class' => 'flex flex-col space-y-2',
                ],
                'choice_attr' => function ($choice, $key, $value) use ($inputClass) {
                    // Apply the class to each radio button
                    return ['class' => $inputClass];
                },
                'label_html' => true,
                'expanded' => true,
                'multiple' => false,
                'required' => true,
            ])
            ->add('applications_per_month', ChoiceType::class, [
                'choices' => $options['choices']['applications_per_month'],
                'label' => 'How many applications do you submit a month?',
                'label_attr' => [
                    'class' => $labelClass,
                ],
                'row_attr' => [
                    'class' => 'flex',
                ],
                'attr' => [
                    'class' => 'flex flex-col space-y-2',
                ],
                'choice_attr' => function ($choice, $key, $value) use ($inputClass) {
                    // Apply the class to each radio button
                    return ['class' => $inputClass];
                },
                'label_html' => true,
                'expanded' => true,
                'multiple' => false,
                'required' => true,
            ])
            ->add('time_looking_for_grants', ChoiceType::class, [
                'choices' => $options['choices']['time_looking_for_grants'],

                'label' => 'On average, how long do you spend looking for grants each week?',
                'label_attr' => [
                    'class' => $labelClass,
                ],
                'row_attr' => [
                    'class' => 'flex',
                ],
                'attr' => [
                    'class' => 'flex flex-col space-y-2',
                ],
                'choice_attr' => function ($choice, $key, $value) use ($inputClass) {
                    // Apply the class to each radio button
                    return ['class' => $inputClass];
                },
                'label_html' => true,
                'expanded' => true,
                'multiple' => false,
                'required' => true,
            ])
            ->add('time_writing_grants', ChoiceType::class, [
                'choices' => $options['choices']['time_writing_grants'],

                'label' => 'On average, how long do you spend writing grants each week?',
                'label_attr' => [
                    'class' => $labelClass,
                ],
                'row_attr' => [
                    'class' => 'flex',
                ],
                'attr' => [
                    'class' => 'flex flex-col space-y-2',
                ],
                'choice_attr' => function ($choice, $key, $value) use ($inputClass) {
                    // Apply the class to each radio button
                    return ['class' => $inputClass];
                },
                'label_html' => true,
                'expanded' => true,
                'multiple' => false,
                'required' => true,
            ])
            ->add('success_rate', ChoiceType::class, [
                'choices' => $options['choices']['success_rate'],

                'label' => 'What is your current success rate?',
                'label_attr' => [
                    'class' => $labelClass,
                ],
                'row_attr' => [
                    'class' => 'flex',
                ],
                'attr' => [
                    'class' => 'flex flex-col space-y-2',
                ],
                'choice_attr' => function ($choice, $key, $value) use ($inputClass) {
                    // Apply the class to each radio button
                    return ['class' => $inputClass];
                },
                'label_html' => true,
                'expanded' => true,
                'multiple' => false,
                'required' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'attr' => [
                'class' => '',
                'id' => 'stepperForm',
            ],
            'choices' => [],
        ]);
    }
}
