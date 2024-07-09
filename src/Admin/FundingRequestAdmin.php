<?php

declare(strict_types=1);

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

final class FundingRequestAdmin extends AbstractAdmin
{
    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter
            ->add('id')
            ->add('status')
            ->add('text')
            ->add('projectOverview')
            ->add('budgetBreakdown')
        ;
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->add('id')
            ->add('status')
            ->add('text')
            ->add('projectOverview')
            ->add('budgetBreakdown')
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
            ->tab('General')
            ->add('status')
            ->add('text')
            ->add('projectOverview')
            ->add('budgetBreakdown')
            ->end()
            ->tab('Messages')
            ->add('grantMessages', null, [
                'by_reference' => false,
                'required' => false,
            ])
            ->end()
        ;
    }

    protected function configureShowFields(ShowMapper $show): void
    {
        $show
            ->add('id')
            ->add('status')
            ->add('text')
            ->add('projectOverview')
            ->add('budgetBreakdown')
        ;
    }
}
