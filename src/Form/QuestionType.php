<?php

namespace App\Form;

use App\Entity\Question;
use App\Model\DeprivationData;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuestionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('answer', TextareaType::class, [
                'label' => false,
                'required' => true,

                'attr' => [
                    'class' => 'py-2 px-3 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400   ',
                    'placeholder' => 'Your question',
                    'rows' => '10',
                    // 'data-controller' => 'ckeditor',
                ],
                'row_attr' => [
                    'class' => 'py-2',
                ],
            ])
            ->add('instruction', NumberType::class, [
                'label' => false,
                'html5' => true,
                'required' => false,
                'attr' => [
                    'class' => 'py-2 px-3 block w-full border-gray-200 rounded-lg text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400   ',
                    'placeholder' => 'Max words count',
                ],
                'row_attr' => [
                    'class' => 'py-2',
                ],
            ])
            ->add('denominators', ChoiceType::class, [
                'label' => false,
                'multiple' => true,
                'required' => false,
                'mapped' => false,
                'placeholder' => 'Select indexes',
                'attr' => [
                    'data-controller' => 'select2',
                ],
                'choices' => array_flip(array_filter(DeprivationData::FIELDS, static fn ($item) => str_contains($item, 'Decile'))),
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Question::class,
        ]);
    }
}
