<?php

namespace Framework\Web;

use Framework\Exception\InvalidRedirectTargetException;
use Framework\Exception\ViewNotFoundException;

class Controller
{

    protected string $name = '';

    public function __construct()
    {
        $this->name = get_class($this);
    }

    /**
     * Returns the class name with namespace
     *
     * @return string
     */
    public function getFullName(): string
    {
        return $this->name;
    }

    /**
     * Returns the class name without namespace like __CLASS__
     *
     * @return string
     */
    public function getName(): string
    {

        $names = explode('\\', $this->name);
        if (empty($names)) {
            return '';
        }
        return $names[count($names) - 1];
    }

    public function localRedirect(?string $controllerAction = null, ?array $params = null): void
    {
        if (empty($controllerAction) || count(explode('/', $controllerAction)) !== 2) {
            throw new InvalidRedirectTargetException();
        }

        $result = new Response();
        $result->addHeader(
            'Location: /' . $controllerAction
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
        if (!empty($params)) {
            extract($params, EXTR_OVERWRITE);
        }
        require($viewFile);
        return ob_get_clean();
    }
}
