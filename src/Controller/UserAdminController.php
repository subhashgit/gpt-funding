<?php

namespace App\Controller;

use App\Entity\User;
use Sonata\AdminBundle\Controller\CRUDController;

class UserAdminController extends CRUDController
{
    public function impersonateAction($id)
    {
        /** @var User $user */
        $user = $this->admin->getSubject();

        return $this->redirect('/?_switch_user='.$user->getEmail());
    }
}
