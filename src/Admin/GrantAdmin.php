<?php

declare(strict_types=1);

namespace App\Admin;

use App\Entity\Grant;
use App\Enum\Status;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridInterface;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\FieldDescription\FieldDescriptionInterface;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\DoctrineORMAdminBundle\Filter\ChoiceFilter;
use Sonata\Form\Type\DatePickerType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;

final class GrantAdmin extends AbstractAdmin
{
    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter
            ->add('id')
            ->add('title', null, [
                'force_case_insensitivity' => true,
            ])
            ->add('funder', null, [
                'force_case_insensitivity' => true,
            ])
            ->add('description', null, [
                'force_case_insensitivity' => true,
            ])
            ->add('max_amount')
            ->add('how_to_apply', null, [
                'force_case_insensitivity' => true,
            ])
            ->add('closing_date')
            ->add('slug')
            ->add('status', ChoiceFilter::class, [
                'field_type' => ChoiceType::class,
                'field_options' => [
                    'multiple' => true,
                    'choices' => [
                        'OPEN' => Status::OPEN->value,
                        'CLOSED' => Status::CLOSED->value,
                        'ACTIVE' => Status::ACTIVE->value,
                        'COMPLETE' => Status::COMPLETE->value,
                    ],
                ],
            ])
            ->add('createdAt')
            ->add('updatedAt')
        ;
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->add('id')
            ->add('title')
            ->add('funder')
            ->add('status', FieldDescriptionInterface::TYPE_ENUM, [
                'class' => Status::class,
            ])
            //            ->add('description')
//            ->add('max_amount')
//            ->add('how_to_apply')
            ->add('closing_date')
//            ->add('slug')
//            ->add('createdAt')
//            ->add('updatedAt')
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
            ->add('title')
            ->add('status', EnumType::class, [
                'class' => Status::class,
            ])
            ->add('funder')
            ->add('description', CKEditorType::class)
            ->add('howToApply', CKEditorType::class, [
                'required' => false,
            ])
            ->add('fundingDetails', CKEditorType::class, [
                'required' => false,
            ])
            ->add('whoCanApply', CKEditorType::class, [
                'required' => false,
            ])
            ->add('findOutMore', null, [
                'required' => false,
            ])
            ->add('guidanceDocuments', CKEditorType::class, [
                'required' => false,
            ])
            ->add('max_amount')
            ->add('closing_date', DatePickerType::class, [
                'required' => false,
            ])
            ->add('published_date', DatePickerType::class)
            ->add('locations', null, [
                'query_builder' => fn (EntityRepository $er): QueryBuilder => $er
                    ->createQueryBuilder('u')
                    ->orderBy('u.location', 'ASC')
                    ->where('u.show_in_filter = true'),
            ])
            ->add('categories', null, [
                'query_builder' => fn (EntityRepository $er): QueryBuilder => $er
                    ->createQueryBuilder('u')
                    ->orderBy('u.category', 'ASC')
                    ->where('u.show_in_filter = true'),
            ])
            ->add('openTo', null, [
                'query_builder' => fn (EntityRepository $er): QueryBuilder => $er
                    ->createQueryBuilder('u')
                    ->orderBy('u.open_to', 'ASC')
                    ->where('u.show_in_filter = true'),
            ])
        ;
    }

    protected function configureShowFields(ShowMapper $show): void
    {
        $show
            ->add('id')
            ->add('title')
            ->add('funder')
            ->add('description', 'html')
            ->add('howToApply', 'html')
            ->add('fundingDetails', 'html')
            ->add('whoCanApply', 'html')
            ->add('findOutMore', 'html')
            ->add('guidanceDocuments', 'html')
            ->add('max_amount')
            ->add('closing_date')
            ->add('published_date')
            ->add('locations')
            ->add('categories')
            ->add('openTo')
            ->add('slug')
            ->add('createdAt')
            ->add('updatedAt')
        ;
    }

    protected function updateList(Grant $grant): void {}

    protected function prePersist(object $object): void
    {
        parent::prePersist($object);

        $this->updateList($object);
    }

    protected function preUpdate(object $object): void
    {
        parent::preUpdate($object);

        $this->updateList($object);
    }

    protected function configureDefaultSortValues(array &$sortValues): void
    {
        $sortValues[DatagridInterface::SORT_ORDER] = 'DESC';

        // name of the ordered field (default = the model's id field, if any)
        $sortValues[DatagridInterface::SORT_BY] = 'id';
    }
}
