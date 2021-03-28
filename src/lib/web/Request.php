<?php

namespace Framework\Web;

use Framework\Web\Controller;

class Request
{

    protected ?string $controller = null;
    protected ?string $action = null;
    protected array  $getParams = [];
    protected array  $postParams = [];
    protected string $method = 'GET';

    protected ?string $parsedUrl = null;

    public function __construct()
    {
        $this->method = strtoupper($_SERVER['REQUEST_METHOD'] ?? $this->method);
        $this->getParams = $_GET ?? [];
        $this->postParams = $_POST ?? [];
        $this->parseUrl();
        $this->controller = $this->getControllerName();
        $this->action = $this->getActionName();
    }

    public function isPost(): bool
    {
        return ($this->method === 'POST');
    }

    public function isGet(): bool
    {
        return ($this->method === 'GET');
    }

    public function getGetParameters(): array
    {
        return $this->getParams;
    }

    public function getPostParameters(): array
    {
        return $this->postParams;
    }

    public function getControllerName(): string
    {
        if ($this->parsedUrl !== '') {
            $pathElements = array_filter(explode('/', $this->parsedUrl), function ($element) {
                return !empty($element);
            });
            if (count($pathElements) >= 2) {
                return $pathElements[count($pathElements) - 1];
            }
        }
        return 'default';
    }

    public function getActionName(): string
    {
        if ($this->parsedUrl !== '') {
            $pathElements = array_filter(explode('/', $this->parsedUrl), function ($element) {
                return !empty($element);
            });
            if (count($pathElements) >= 2) {
                return $pathElements[count($pathElements)];
            }
        }
        return 'default';
    }

    protected function parseUrl(): void
    {
        $this->parsedUrl = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '';
    }
}
