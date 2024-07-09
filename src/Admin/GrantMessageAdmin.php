<?php

declare(strict_types=1);

namespace App\Admin;

use App\Entity\Message\GrantMessage;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Contracts\Service\Attribute\Required;

final class GrantMessageAdmin extends AbstractAdmin
{
    #[Required]
    public Security $security;

    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter
            ->add('id')
            ->add('text')
            ->add('author')
        ;
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->addIdentifier('text')
            ->add('request')
            ->add('author')
            ->add(ListMapper::NAME_ACTIONS, null, [
                'actions' => [
                    'show' => [],
                    'edit' => [],
                    'delete' => [],
                ],
            ])
        ;
    }

    protected function configureFormFields(FormMapper $form): void
    {
        $form
            ->add('text')
        ;
    }

    protected function configureShowFields(ShowMapper $show): void
    {
        $show
            ->add('grant')
            ->add('author')
            ->add('text')
        ;
    }

    /**
     * @param GrantMessage $object
     */
    protected function prePersist(object $object): void
    {
        $object->setAuthor($this->security->getUser());
    }

    /**
     * @param GrantMessage $object
     */
    protected function preUpdate(object $object): void
    {
        $object->setAuthor($this->security->getUser());
    }
}
