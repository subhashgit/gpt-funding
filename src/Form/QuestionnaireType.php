<?php

namespace App\Form;

use App\Entity\Questionnaire;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ChoiceList\Loader\CallbackChoiceLoader;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuestionnaireType extends AbstractType
{
    public function __construct(private RequestStack $requestStack) {}

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('postcodes', ChoiceType::class, [
                'mapped' => false,
                'multiple' => true,
                'required' => false,
                'attr' => [
                    'data-controller' => 'select2-bidwriter',
                ],
                'choice_loader' => new CallbackChoiceLoader(fn () => $this->requestStack->getCurrentRequest()->request->all()['questionnaire']['postcodes'] ?? []),
            ])
            ->add('questions', CollectionType::class, [
                'entry_type' => QuestionType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                // 'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Questionnaire::class,
        ]);
    }
}
