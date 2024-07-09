<?php

namespace App\Form;

use App\Entity\Request\GrantRequest;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use App\Enum\RequestStatus;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;


class GrantRequestType extends AbstractType
{
    
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        
        $builder
            ->add('projectOverview', TextareaType::class, [
                'label' => 'Project Overview',
                'required' => false,
                'attr' => [
                    'class' => 'py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400',
                ],
            ])
            ->add('budgetBreakdown', TextareaType::class, [
                'label' => 'Project Overview',
                'required' => false,
                'attr' => [
                    'class' => 'py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400',
                ],
            ])
            ->add('status', ChoiceType::class, [
                'choices' => RequestStatus::cases(),
                'choice_label' => fn (RequestStatus $choice) => $choice->label(),
                'attr' => [
                    'class' => 'py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400', // Add custom class for 
                ],
            ])
            ->add('dateSubmitted', DateTimeType::class, [
                'label' => 'Date Submitted',
                'widget' => 'single_text', // Use a single text box for both date and time
                'html5' => true, // Disable HTML5 date picker to use a JavaScript-based one
                'required' => false,
                'attr' => [
                    'class' => 'py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400', // Add custom class for JavaScript initialization
                    // 'data-date-format' => 'mmddyyyy',
                    // 'format' => 'yyyy-MM-dd\'T\'HH:mm:ss', // Date format (default for single_text widget)
                    'data-date-format' => 'MMM d, yyyy, h:mm:ss a',
                ],
            ])
            ->add('expectedDecision', DateTimeType::class, [
                'label' => 'Expected Decision',
                'widget' => 'single_text', // Use a single text box for both date and time
                'html5' => true, // Disable HTML5 date picker to use a JavaScript-based one
                'required' => false,
                'attr' => [
                    'class' => 'sonata-medium-date form-control py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400', // Add custom class for JavaScript initialization
                    // 'data-date-format' => 'MMMM D, YYYY',
                ],
            ])
            ->add('value', TextareaType::class, [
                'label' => 'Value',
                'required' => false,
                'attr' => [
                    'class' => 'py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400',
                ],
            ])
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => GrantRequest::class,
            'attr' => [
                'id' => 'grant_suggestion_form',
            ],
            'show_value_field' => false, // default value
        ]);
    }
}
