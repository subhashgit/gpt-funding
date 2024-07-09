<?php

declare(strict_types=1);

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Stripe\Price;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

final class TariffAdmin extends AbstractAdmin
{
    public function __construct(
        //        string $code = null,
        //        string $class = null,
        //        string $baseControllerName = null,
        private readonly string $stripeSecretKey
    ) {
        parent::__construct(null, null, null);
    }

    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter
            ->add('id')
            ->add('title')
//            ->add('price')
            ->add('period')
//            ->add('points')
        ;
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->add('id')
            ->add('title')
//            ->add('price')
            ->add('period')
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
        \Stripe\Stripe::setApiKey($this->stripeSecretKey);

        $prices = array_column(Price::all([
            'type' => 'recurring',
        ])->toArray()['data'], 'id', 'id');
        $form
//            ->add('id')
            ->add('title')

            ->add('monthPrice', ChoiceType::class, [
                'choices' => $prices,
            ])

            ->add('yearPrice', ChoiceType::class, [
                'choices' => $prices,
            ])
        ;
    }

    protected function configureShowFields(ShowMapper $show): void
    {
        $show
            ->add('id')
            ->add('title')
            ->add('period')
        ;
    }
}
