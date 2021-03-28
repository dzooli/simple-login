<?php

namespace Framework;

use Framework\Db\DbConfiguration;
use Framework\Session;
use Framework\Web\Request;
use Framework\Web\Response;
use Framework\Web\Controller;
use Framework\Exception\InvalidConfigurationException;
use Framework\Exception\MissingConfigurationException;
use Framework\Exception\NotInitializedException;
use Framework\Exception\ActionNotFoundException;
use Framework\Exception\ControllerNotFoundException;
use PDOException;

class Application
{
    protected ?Request $request = null;
    protected ?Response $response = null;
    protected string $controllerName = '';
    protected string $actionName = '';
    protected ?Controller $controller = null;

    protected static \PDO $dbConn;
    protected static DbConfiguration $dbConf;
    protected static array $dbOptions = [
        \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
        //\PDO::ATTR_PERSISTENT => true,
    ];

    protected array $params;
    protected array $config;
    protected ?string $defaultControllerName = null;
    protected ?string $defaultActionName = null;
    protected Session $session;
    /**
     * Creates the application instance
     *
     * @param array $config         Application configuration array @see file://../../config/app.php
     *
     * @throws MissingConfigurationException        on missing configuration array
     * @throws InvalidConfigurationException        on missing configuration parameter
     * @throws PDOException                         on database connection error
     */
    public function __construct(array $config)
    {
        if (empty($config)) {
            throw new MissingConfigurationException();
        }
        if (array_key_exists('db', $config)) {
            self::$dbConf = new DbConfiguration($config['db']);
        } else {
            throw new InvalidConfigurationException();
        }
        $this->connectToDb();
        $this->params = $config['params'];

        if (self::hasValidParam($config, 'defaultcontroller')) {
            $this->defaultControllerName = $this->params['defaultcontroller'];
        }
        if (self::hasValidParam($config, 'defaultaction')) {
            $this->defaultActionName = $this->params['defaultaction'];
        }
        $this->config = $config;
        $this->request = new Request();
    }

    /**
     * Get the DB connection configuration
     *
     * @return DbConfiguration      The currently used DB connection configuration
     */
    public static function getDbConfiguration(): DbConfiguration
    {
        return self::$dbConf;
    }

    /**
     * Get the DB connection instance
     *
     * @return \PDO                 The currently used PDO connection instance
     */
    public static function getDb(): \PDO
    {
        return self::$dbConn;
    }

    public function getParams(): array
    {
        return $this->params;
    }

    public function getName(): string
    {
        return array_key_exists('name', $this->params) ? $this->params['name'] : 'default';
    }

    public static function hasValidParam(array $config, string $paramName): bool
    {
        return (!empty($config['params']) && ($config['params'][$paramName] ?? false));
    }

    public function setSession(Session $session): void
    {
        $this->session = $session;
    }

    public function getSession(): Session
    {
        if (!$this->session) {
            throw new NotInitializedException();
        }
        return $this->session;
    }

    protected function connectToDb(): void
    {

        self::$dbConn = new \PDO(
            self::$dbConf->getDSN(),
            self::$dbConf->getUserName(),
            self::$dbConf->getPassword(),
            self::$dbOptions
        );
    }

    public function getRequest(): Request
    {
        return $this->request;
    }

    public function run(): Response
    {
        $this->response = new Response();
        $this->controller = null;
        $this->createController();
        $actionMethodName = $this->createActionMethodName();

        if (!method_exists($this->controller, $actionMethodName)) {
            throw new ActionNotFoundException($actionMethodName, $this->controllerName);
        }
        $this->response = $this->controller->$actionMethodName();
        return $this->response;
    }

    protected function createController(): void
    {
        $this->controllerName = $this->request->getControllerName();
        $newControllerName = ($this->controllerName === 'default' || empty($this->controllerName)) ?
            ($this->defaultControllerName ?? '') : $this->controllerName;
        $newControllerName = ucfirst(strtolower($newControllerName));
        $this->controllerName = $newControllerName;
        if (empty($this->controllerName)) {
            throw new ControllerNotFoundException($this->controllerName);
        }
        $controllerClassName = '\\App\\Controllers\\' . $this->controllerName . 'Controller';
        if (!class_exists($controllerClassName, true)) {
            throw new ControllerNotFoundException($controllerClassName);
        }
        $this->controller = new $controllerClassName();
    }

    protected function createActionMethodName(): string
    {
        $this->actionName = $this->request->getActionName();
        $newActionName = ($this->actionName === 'default' || empty($this->actionName)) ?
            ($this->defaultActionName ?? '') : $this->actionName;
        $this->actionName = $newActionName;
        if (empty($this->actionName)) {
            throw new ActionNotFoundException($this->actionName, $this->controllerName);
        }
        return 'action' . ucfirst(strtolower($this->actionName));
    }

    public function isDebugging(): bool
    {
        return (self::hasValidParam($this->config, 'debug') && $this->params['debug'] === true);
    }

    public function __destruct()
    {
        if ($this->session->getStatus() == PHP_SESSION_ACTIVE) {
            $this->session->close();
        }
    }
}
