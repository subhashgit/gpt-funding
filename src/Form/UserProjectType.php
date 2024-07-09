<?php

namespace App\Form;

use App\Entity\UserProject;
use App\Repository\GrantLocationRepository;
use App\Repository\GrantRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserProjectType extends AbstractType
{
    public function __construct(
        private readonly GrantLocationRepository $grantLocationRepository,
        private readonly GrantRepository $grantRepository
    ) {}

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $locations = $this->grantRepository->getFilters('location');
        $openTo = $this->grantRepository->getFilters('open_to');

        $builder
            ->add('description', TextareaType::class, [
                'attr' => [
                    'rows' => 10,
                    'class' => 'py-3 px-4 block w-full border-gray-200 rounded-md text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400',
                ],
                'constraints' => [
                    new NotBlank(),
                    new Length(['max' => 2000]),
                ],
            ])
//            ->add('projectExamples', TextareaType::class, [
//                'attr' => [
//                    'rows' => 10,
//                    'class' => 'py-3 px-4 block w-full border-gray-200 rounded-md text-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-slate-900 dark:border-gray-700 dark:text-gray-400',
//                ],
//                'label' => 'Example projects you have delivered <i>(optional)</i>',
//                'label_html' => true,
//                'constraints' => [
//                    new Length(['max' => 2000]),
//                ],
//            ])
            ->add('locations', ChoiceType::class, [
                'multiple' => true,
                'required' => false,
                'attr' => [
                    'data-controller' => 'select2',
                ],
                'choices' => array_combine($locations, $locations),
            ])
            ->add('openTo', ChoiceType::class, [
                'multiple' => true,
                'required' => false,
                'attr' => [
                    'data-controller' => 'select2',
                ],
                'choices' => array_combine($openTo, $openTo),
            ])
//            ->add('Submit', SubmitType::class, [
//                'attr' => [
//                    'data-hs-overlay'=>'#hs-basic-modal',
//                    'class' => 'py-3 px-4 inline-flex justify-center items-center gap-2 rounded-md border border-transparent font-semibold bg-blue-500 text-white hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all text-sm dark:focus:ring-offset-gray-800',
//                ],
//            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UserProject::class,
        ]);
    }
}
