<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ChangePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('currentPassword', PasswordType::class, [
                'label' => 'Current Password',
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('newPassword', PasswordType::class, [
                'label' => 'New Password',
                'constraints' => [
                    new NotBlank(),
                    new Length(['min' => 4]),
                ],
            ])
            ->add('confirmPassword', PasswordType::class, [
                'label' => 'Confirm Password',
                'constraints' => [
                    new NotBlank(),
                    new Length(['min' => 4]),
                ],
            ])
        ;
    }
    //
    //    public function configureOptions(OptionsResolver $resolver): void
    //    {
    //        $resolver->setDefaults([
    //            'data_class' => null,
    //        ]);
    //    }
}
