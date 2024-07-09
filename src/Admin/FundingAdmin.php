<?php

declare(strict_types=1);

namespace App\Admin;

use App\Enum\Status;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\FieldDescription\FieldDescriptionInterface;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\EnumType;

final class FundingAdmin extends AbstractAdmin
{
    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter
            ->add('id')
            ->add('notice_identifier')
            ->add('notice_type')
            ->add('organisation_name')
//            ->add('status')
            ->add('published_date')
            ->add('title', null, [
                'force_case_insensitivity' => true,
            ])
            ->add('description', null, [
                'force_case_insensitivity' => true,
            ])
            ->add('postcode')
            ->add('fundingRegions')
            ->add('links')
            ->add('additional_text', null, [
                'force_case_insensitivity' => true,
            ])
            ->add('start_date')
            ->add('end_date')
            ->add('closing_date')
            ->add('suitable_for_sme')
            ->add('suitable_for_vco')
            ->add('supply_chain')
            ->add('ojeu_contract_type')
            ->add('value_low')
            ->add('value_high')
            ->add('ojeu_procedure_type')
            ->add('slug')
            ->add('external_link')
            ->add('createdAt')
            ->add('updatedAt')
        ;
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->add('id')
//            ->add('notice_identifier')
//            ->add('notice_type')
            ->add('organisation_name')
            ->add('status', FieldDescriptionInterface::TYPE_ENUM, [
                'class' => Status::class,
            ])
//            ->add('published_date')
            ->add('title')
//            ->add('description')
//            ->add('postcode')
            ->add('fundingRegions')
            ->add('cpv')
//            ->add('contact_name')
//            ->add('contact_email')
//            ->add('contact_address1')
//            ->add('contact_address2')
//            ->add('contact_town')
//            ->add('contact_postcode')
//            ->add('contact_country')
//            ->add('contact_telephone')
//            ->add('contact_website')
//            ->add('links')
//            ->add('additional_text')
//            ->add('start_date')
//            ->add('end_date')
//            ->add('closing_date')
//            ->add('suitable_for_sme')
//            ->add('suitable_for_vco')
//            ->add('supply_chain')
//            ->add('ojeu_contract_type')
            ->add('value_low')
            ->add('value_high')
//            ->add('ojeu_procedure_type')
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
            ->add('id')
            ->add('notice_identifier')
            ->add('notice_type')
            ->add('organisation_name')
            ->add('status', EnumType::class, [
                'class' => Status::class,
            ])
            ->add('published_date')
            ->add('title')
            ->add('description', CKEditorType::class)
            ->add('postcode')
            ->add('fundingRegions')
            ->add('additional_text', CKEditorType::class)
            ->add('start_date')
            ->add('end_date')
            ->add('closing_date')
            ->add('suitable_for_sme')
            ->add('suitable_for_vco')
            ->add('supply_chain')
            ->add('ojeu_contract_type')
            ->add('value_low')
            ->add('value_high')
            ->add('ojeu_procedure_type')
            ->add('slug')
            ->add('external_link')
            ->add('createdAt')
            ->add('updatedAt')
        ;
    }

    protected function configureShowFields(ShowMapper $show): void
    {
        $show
            ->add('id')
            ->add('notice_identifier')
            ->add('notice_type')
            ->add('organisation_name')
            ->add('status', FieldDescriptionInterface::TYPE_ENUM, [
                'class' => Status::class,
            ])
            ->add('published_date')
            ->add('title')
            ->add('description')
            ->add('postcode')
            ->add('fundingRegions')
            ->add('links')
            ->add('additional_text')
            ->add('start_date')
            ->add('end_date')
            ->add('closing_date')
            ->add('suitable_for_sme')
            ->add('suitable_for_vco')
            ->add('supply_chain')
            ->add('ojeu_contract_type')
            ->add('value_low')
            ->add('value_high')
            ->add('ojeu_procedure_type')
            ->add('slug')
            ->add('external_link')
            ->add('createdAt')
            ->add('updatedAt')
        ;
    }
}
