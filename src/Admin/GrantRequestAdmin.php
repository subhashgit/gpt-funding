<?php

declare(strict_types=1);

namespace App\Admin;

use App\Entity\Request\GrantRequest;
use App\Enum\RequestStatus;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollectionInterface;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\Form\Type\CollectionType;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Contracts\Service\Attribute\Required;
use Sonata\Form\Type\DateTimePickerType;

final class GrantRequestAdmin extends AbstractAdmin
{
    #[Required]
    public Security $security;

    protected function configureRoutes(RouteCollectionInterface $collection): void
    {
        $collection->remove('create');
    }

    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter
            ->add('user')
            ->add('status')
            ->add('text')
            ->add('projectOverview')
            ->add('budgetBreakdown')
        ;
    }

    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->add('grant')
            ->add('user')
            ->add('dateSubmitted')
            ->add('status')
            ->add('expectedDecision')
            ->add('value')
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
            ->tab('General')
            ->add('status', ChoiceType::class, [
                'choices' => RequestStatus::cases(),
                'choice_label' => fn (RequestStatus $choice) => $choice->label(),
            ])
            ->add('text', CKEditorType::class)
            ->add('projectOverview', TextareaType::class)
            ->add('budgetBreakdown', TextareaType::class)
            ->add('dateSubmitted', DateTimePickerType::class, [
                'required' => false,
            ])
            ->add('expectedDecision', DateTimePickerType::class, [
                'required' => false,
            ])
            ->add('value', TextareaType::class, [
                'required' => false,
            ])
            ->end()
            ->end()
        ;
        $form
            ->tab('Messages')
            ->add(
                'messages',
                CollectionType::class,
                [
                    'by_reference' => false,
                    'label' => false,
                    'btn_add' => 'Add Message',
                    'btn_catalogue' => true,
                ],
                [
                    'edit' => 'inline',
                    'inline' => 'table',
                    'sortable' => 'position',
                ]
            )
            ->end()
        ;
    }

    protected function configureShowFields(ShowMapper $show): void
    {
        $show
            ->add('grant')
            ->add('user')
            ->add('dateSubmitted')
            ->add('status')
            ->add('expectedDecision')
            ->add('text', 'html')
            ->add('projectOverview')
            ->add('budgetBreakdown')
        ;
    }

    /**
     * @param GrantRequest $object
     */
    protected function preUpdate(object $object): void
    {
        $lastMessage = $object->getMessages()->last();

        if ($lastMessage && !$lastMessage->getAuthor()) {
            $lastMessage->setAuthor($this->security->getUser());
        }
    }
}
