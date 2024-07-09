<?php

declare(strict_types=1);

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridInterface;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\TextType;

final class AnswerAdmin extends AbstractAdmin
{
    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter
            ->add('id')
            ->add('question')
            ->add('answer')
            ->add('gpt')
            ->add('instruction')
            ->add('denominators')
            ->add('prompt')
            ->add('createdAt')
            ->add('updatedAt')
        ;
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->add('id')
            ->add('question')
            ->add('answer', TextType::class, [
                'length' => 50,
            ])
            ->add('gpt')
            ->add('instruction')
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
            ->add('question')
            ->add('answer', null, [
                'attr' => [
                    'rows' => 20,
                ],
            ])
            ->add('gpt')
            ->add('instruction')
//            ->add('denominators', )
            ->add('prompt', null, [
                'attr' => [
                    'rows' => 20,
                ],
            ])
            ->add('createdAt')
            ->add('updatedAt')
        ;
    }

    protected function configureShowFields(ShowMapper $show): void
    {
        $show
            ->add('id')
            ->add('question')
            ->add('answer')
            ->add('gpt')
            ->add('instruction')
            ->add('denominators')
            ->add('prompt')
            ->add('createdAt')
            ->add('updatedAt')
        ;
    }

    protected function configureDefaultSortValues(array &$sortValues): void
    {
        $sortValues[DatagridInterface::SORT_ORDER] = 'DESC';

        // name of the ordered field (default = the model's id field, if any)
        $sortValues[DatagridInterface::SORT_BY] = 'id';
    }
}
