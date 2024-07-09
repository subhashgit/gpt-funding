<?php

namespace App\Twig\Components;

use App\Form\ChangePasswordType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
final class ChangePassword extends AbstractController
{
    use ComponentWithFormTrait;
    use DefaultActionTrait;

    //    #[LiveProp(writable: true)]
    //    public bool $isSuccessful = false;
    //
    //    #[LiveProp(writable: true)]
    //    public string $currentPassword = '';
    //
    //    #[LiveProp(writable: true)]
    //    public string $newPassword = '';
    //
    //    #[LiveProp(writable: true)]
    //    public string $confirmPassword = '';
    //
    //    #[LiveProp(writable: true, fieldName: 'formData')]
    //    public ?ChangePasswordType $change_password = null;

    #[LiveProp]
    public ?array $initialFormData = null;

    public function hasValidationErrors(): bool
    {
        return $this->getFormInstance()->isSubmitted() && !$this->getFormInstance()->isValid();
    }

    #[LiveAction]
    public function saveRegistration()
    {
        $this->submitForm();

        //        $this->change_password['currentPassword'] = $this->getFormInstance()
        //            ->get('currentPassword')
        //            ->getData();
        //        $this->change_password['newPassword'] = $this->getFormInstance()
        //            ->get('newPassword')
        //            ->getData();
        //        $this->change_password['confirmPassword'] = $this->getFormInstance()
        //            ->get('confirmPassword')
        //            ->getData();
        //        $this->isSuccessful = true;
    }

    protected function instantiateForm(): FormInterface
    {
        return $this->createForm(ChangePasswordType::class, $this->initialFormData);
    }
}
