<?php

namespace App\Twig\Components;

use App\Entity\Questionnaire;
use App\Form\QuestionnaireType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveArg;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent('question')]
final class QuestionComponent extends AbstractController
{
    use DefaultActionTrait;
    use ComponentWithFormTrait;

    #[LiveProp(fieldName: 'formData')]
    public Questionnaire $q;

    #[LiveProp()]
    public bool $submitted = false;

    public function __construct() {}

    #[LiveAction]
    public function addQuestion(): void
    {
        // "formValues" represents the current data in the form
        // this modifies the form to add an extra comment
        // the result: another embedded comment form!
        // change "comments" to the name of the field that uses CollectionType
        $this->formValues['questions'][] = [];
    }

    #[LiveAction]
    public function removeQuestion(#[LiveArg] int $index): void
    {
        unset($this->formValues['questions'][$index]);
    }

    protected function instantiateForm(): FormInterface
    {
        // we can extend AbstractController to get the normal shortcuts
        return $this->createForm(QuestionnaireType::class, $this->q);
    }
}
