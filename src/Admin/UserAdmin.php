<?php

declare(strict_types=1);

namespace App\Admin;

use App\Entity\User;
use App\Enum\AccountType;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\FieldDescription\FieldDescriptionInterface;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollectionInterface;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\Form\Type\DateTimePickerType;
use Sonata\UserBundle\Form\Type\RolesMatrixType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Contracts\Service\Attribute\Required;

final class UserAdmin extends \Sonata\UserBundle\Admin\Model\UserAdmin
{
    #[Required]
    public UserPasswordHasherInterface $passwordHasher;

    protected function configureRoutes(RouteCollectionInterface $collection): void
    {
        $collection
            ->add('impersonate', $this->getRouterIdParameter().'/impersonate')
        ;
    }

    #[Required]
    public function setPasswordHasher(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    /**
     * @param User $object
     */
    //    public function preUpdate(object $object): void
    //    {
    //        $uniqid = $this->getRequest()->query->get('uniqid');
    //        $plainPassword = $this->getRequest()->request->all()[$uniqid]['plainPassword'];
    //
    //        if ($plainPassword) {
    //            $object->setPassword(
    //                $this->passwordHasher->hashPassword(
    //                    $object,
    //                    $plainPassword
    //                )
    //            );
    //        }
    //    }

    /**
     * @param User $object
     */
    public function prePersist($object): void
    {
        $object->setUsername($object->getEmail());
        $object->setRoles(['ROLE_USER']);
        $object->setBrief(false);
    }

    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter
            ->add('id')
            ->add('email', null, [
                'force_case_insensitivity' => true,
            ])
            ->add('username', null, [
                'force_case_insensitivity' => true,
            ])
            ->add('notificationEmail', null, [
                'force_case_insensitivity' => true,
            ])
            ->add('accountType')
            ->add('tariff')
            ->add('isVerified')
            ->add('points')
            ->add('subscribed_at')
            ->add('lastLoggedIn')
        ;
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->addIdentifier('email')
            ->add('username')
            ->add('accountType')
            ->add('notificationEmail')
            ->add('lastLoggedIn')
//            ->add('roles',ChoiceType::class,[
//                'choices' => [
//                    'ROLE_SUPER_ADMIN' => 'ROLE_SUPER_ADMIN',
//                    'ROLE_USER' => 'ROLE_USER',
//                ],
//                'multiple' => true,
//                'expanded' => false,
//            ])
//            ->add('password')
//            ->add('isVerified')

            ->add('tariff')
            ->add('subscribed_at')
            ->add('createdAt')
            ->add('isSubscribed', FieldDescriptionInterface::TYPE_BOOLEAN)
            ->add(ListMapper::NAME_ACTIONS, ListMapper::TYPE_ACTIONS, [
                'translation_domain' => 'messages',

                'actions' => [
                    'show' => [],
                    'edit' => [],
                    'delete' => [],
                    //                    'impersonate' => [
                    //                        'template' => 'CRUD/list__action_impersonate.html.twig',
                    //                    ],
                ],
            ])
        ;

        if ($this->isGranted('ROLE_ALLOWED_TO_SWITCH')) {
            $list
                ->add('impersonating', FieldDescriptionInterface::TYPE_STRING, [
                    'virtual_field' => true,
                    'template' => '@SonataUser/Admin/Field/impersonating.html.twig',
                ])
            ;
        }
    }

    protected function configureFormFields(FormMapper $form): void
    {
        $form
            ->with('General', ['class' => 'col-md-4'])
            ->add('email')
            ->add('username')
            ->add('accountType', EnumType::class, [
                'class' => AccountType::class,
            ])
            ->add('notificationEmail')
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'ROLE_SUPER_ADMIN' => 'ROLE_SUPER_ADMIN',
                    'ROLE_USER' => 'ROLE_USER',
                ],
                'multiple' => true,
                'expanded' => false,
            ])
            ->add('plainPassword', PasswordType::class, [
                'required' => (!$this->hasSubject() || null === $this->getSubject()->getId()),
                'label' => 'Password',
            ])
            ->add('isVerified')
            ->add('points')
            ->add('subscribed_at', DateTimePickerType::class, [
                'required' => false,
            ])
            ->add('lastLoggedIn', DateTimePickerType::class, [
                'required' => false,
            ])
            ->add('userFilters')
            ->end()

            ->with('roles', ['class' => 'col-md-8'])
            ->add('realRoles', RolesMatrixType::class, [
                'label' => false,
                'multiple' => true,
                'required' => false,
            ])
            ->end()
        ;
    }

    protected function configureShowFields(ShowMapper $show): void
    {
        $show
            ->add('id')
            ->add('email')
            ->add('username')
            ->add('accountType')
            ->add('notificationEmail')
            ->add('roles')
            ->add('password')
            ->add('isVerified')
            ->add('subscribed_at')
            ->add('lastLoggedIn')
            ->add('points')
            ->add('userFilters')
            ->add('companies')
        ;
    }
}
