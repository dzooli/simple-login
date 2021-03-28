<?php

namespace Framework\Web;

use Framework\Exception\InvalidRedirectTargetException;

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
                . http_build_query($params)
        );
        exit();
    }
}
