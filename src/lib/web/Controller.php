<?php

namespace Framework\Web;

use Framework\Myy;
use Framework\Exception\InvalidRedirectTargetException;
use Framework\Exception\ViewNotFoundException;
use Framework\Session;
use Framework\UserBase;

class Controller
{

    protected string $name = '';

    public function __construct()
    {
        $this->name = get_class($this);
    }

    public function getFullName(): string
    {
        return $this->name;
    }

    public function getName(): string
    {

        $names = explode('\\', $this->name);
        if (!empty($names)) {
            return $names[count($names) - 1];
        } else {
            return '';
        }
    }

    public function localRedirect(?string $controller_and_action = null, ?array $params = null): void
    {
        if (empty($controller_and_action) || count(explode('/', $controller_and_action)) !== 2) {
            throw new InvalidRedirectTargetException();
        }

        $result = new Response();
        $result->addHeader(
            'Location: /' . $controller_and_action
                . (!empty($params) ? '?' : '')
                . (!empty($params) ? http_build_query($params) : '')
        );
        exit();
    }

    public function renderView(?string $viewName = null, ?array $params = null): string
    {
        $viewFile = __DIR__ . '/../../app/views/'
            . strtolower(str_replace('Controller', '', $this->getName()))
            . '/' . ((!empty($viewName)) ? strtolower($viewName) : 'index')
            . '.php';
        if (!file_exists($viewFile)) {
            throw new ViewNotFoundException($this->name, $viewFile);
        }
        ob_start();
        require($viewFile);
        return ob_get_clean();
    }

    protected function login(?UserBase $user = null)
    {
        if (!empty($user) && $user->getId() > 0) {
            if (empty(Myy::$app->getSession())) {
                Myy::$app->setSession(new Session());
            }
            Myy::$app->getSession()->set('user_id', $user->getId());
            Myy::$user_id = $user->getId();
        }
    }

    protected function logout()
    {
        Myy::$app->getSession()->close();
        Myy::$user_id = 0;
    }
}
