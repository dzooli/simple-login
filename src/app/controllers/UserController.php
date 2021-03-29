<?php

namespace App\Controllers;

use Framework\Myy;
use Framework\Web\Response;
use Framework\Web\Controller;

use App\Models\User;
use Framework\Session;

class UserController extends Controller
{
    protected string $defaultRedirect = 'site/index';

    public function actionLogin(): void
    {
        if (empty(Myy::$app->getSession())) {
            Myy::$app->setSession(new Session());
        }

        if (Myy::$app->getRequest()->isPost()) {
            $formData = Myy::$app->getRequest()->getPostParameters();
            if (empty($formData) || empty($formData['User'])) {
                Session::setFlash('warning', 'Please enter a valid e-mail and password below');
                $this->localRedirect($this->defaultRedirect);
                return;
            }
            $pass = $formData['User']['password'];
            $mail = $formData['User']['email'];
            $userFound = User::findByEmail($mail);
            if (!$userFound) {
                Session::setFlash('danger', 'Invalid username or password');
                $this->localRedirect($this->defaultRedirect);
                return;
            }
            if (!$userFound->verifyPassword($pass)) {
                Session::setFlash('danger', 'Invalid username or password');
                $this->localRedirect($this->defaultRedirect);
                return;
            }
            Myy::login($userFound);
        }
        $this->localRedirect('site/starter');
    }

    public function actionLogout(): Response
    {
        Myy::logout();
        $this->localRedirect('site/index');
        return new Response();
    }
}
