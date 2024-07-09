<?php

namespace App\Form;

use App\Entity\QuestionnaireAnswer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuestionAnswerType extends AbstractType
{
    public function __construct(private RequestStack $requestStack) {}

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
//            ->add('postcodes', ChoiceType::class, [
//                'multiple' => true,
// //                'attr' => [
// //                    'data-controller' => 'select2-bidwriter',
// //                ],
// //                'choice_loader' => new CallbackChoiceLoader(fn() => $this->requestStack->getCurrentRequest()->request->all()['questionnaire']['postcodes'] ?? []),
//            ])
            ->add('answer', CollectionType::class, [
                'entry_type' => AnswerType::class,
                'label' => false,
            ])
//            ->get('postcodes')
//            ->addModelTransformer(new CallbackTransformer(
//                fn($postcodesAsString) => array_map(
//                    fn($postcode) => [$postcode => $postcode],
//                $postcodesAsString
//                ),
//
//                fn($postcodesAsArray) => implode(',', $postcodesAsArray),
//            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => QuestionnaireAnswer::class,
        ]);
    }
}
