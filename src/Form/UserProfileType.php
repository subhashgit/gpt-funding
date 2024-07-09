<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class UserProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', null, [
                'required' => false,
                'label' => 'Username',
            ])
            ->add('notificationEmail', TextType::class, [
                'label' => 'Email address',
                'help' => 'Comma-separated list of email addresses',
                'help_attr' => [
                    'class' => 'text-sm text-gray-500 mt-2',
                ],
                'required' => false,
                'attr' => [
                    'placeholder' => 'Email address',
                ],
                'constraints' => [
                    new Assert\Callback([$this, 'validateEmailList']),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }

    public function validateEmailList($value, ExecutionContextInterface $context)
    {
        $emails = explode(',', $value);

        foreach ($emails as $email) {
            $email = trim($email); // Trim spaces
            $errors = $context->getValidator()->validate($email, new Assert\Email());

            if (count($errors) > 0) {
                $context->buildViolation('Invalid email address: '.$email)
                    ->atPath('notificationEmail')
                    ->addViolation()
                ;
            }
        }
    }
}
