<?php

namespace App\Controllers;

use Framework\Myy;
use Framework\Web\Response;
use Framework\Web\Controller;

use App\Models\User;

class UserController extends Controller
{
    protected string $defaultRedirectTarget = 'site/index';

    public function actionLogin(): void
    {
        if (Myy::$app->getRequest()->isPost()) {
            $formData = Myy::$app->getRequest()->getPostParameters();
            if (empty($formData) || empty($formData['User'])) {
                $this->localRedirect($this->defaultRedirectTarget);
                return;
            }
            $pass = $formData['User']['password'];
            $mail = $formData['User']['email'];
            $userFound = User::findByEmail($mail);
            if (!$userFound) {
                $this->localRedirect($this->defaultRedirectTarget);
                return;
            }
            if (!$userFound->authenticate($pass)) {
                $this->localRedirect($this->defaultRedirectTarget);
                return;
            }
            $this->login($userFound);
        }
        $this->localRedirect('site/starter');
    }

    public function actionLogout(): Response
    {
        $this->logout();
        $this->localRedirect('site/index');
        return new Response();
    }
}
