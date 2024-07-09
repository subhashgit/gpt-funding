<?php

declare(strict_types=1);

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridInterface;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Form\Type\ModelAutocompleteType;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\DoctrineORMAdminBundle\Filter\ModelFilter;

final class UserGrantNotificationAdmin extends AbstractAdmin
{
    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter
            ->add('id')
            ->add('type')
            ->add('notified')
            ->add('user')
            ->add('createdAt')
            ->add('updatedAt')
            ->add('grant', ModelFilter::class, [
                // in related CategoryAdmin there must be datagrid filter on `title` field to make the autocompletion work
                'field_options' => ['property' => 'title'],
                'field_type' => ModelAutocompleteType::class,
            ])
        ;
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->add('id')
            ->add('type')
            ->add('notified')
            ->add('user')
            ->add('grant')
            ->add('priority')
            ->add('createdAt')
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
            ->add('id')
            ->add('type')
            ->add('notified')
            ->add('user')
            ->add('priority')
            ->add('createdAt')
            ->add('updatedAt')
            ->add('grant', ModelAutocompleteType::class, [
                'multiple' => false,
                'required' => false,
                'btn_add' => false,
                'property' => 'title',
            ])
        ;
    }

    protected function configureShowFields(ShowMapper $show): void
    {
        $show
            ->add('id')
            ->add('type')
            ->add('notified')
            ->add('user')
            ->add('priority')
            ->add('createdAt')
            ->add('updatedAt')
            ->add('grant')
        ;
    }

    protected function configureDefaultSortValues(array &$sortValues): void
    {
        $sortValues[DatagridInterface::SORT_ORDER] = 'DESC';

        // name of the ordered field (default = the model's id field, if any)
        $sortValues[DatagridInterface::SORT_BY] = 'id';
    }
}
