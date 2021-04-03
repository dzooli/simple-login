<?php

namespace Framework;

use Framework\Db\DbConfiguration;
use Framework\Exception\ActionNotFoundException;
use Framework\Exception\ControllerNotFoundException;
use Framework\Exception\InvalidConfigurationException;
use Framework\Exception\MissingConfigurationException;
use Framework\Web\Controller;
use Framework\Web\Request;
use Framework\Web\Response;
use PDO;
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
    protected ?string $defControllerName = null;
    protected ?string $defActionName = null;
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
        if (!array_key_exists('db', $config)) {
            throw new InvalidConfigurationException();
        }

        self::$dbConf = new DbConfiguration($config['db']);
        $this->connectToDb();
        $this->params = $config['params'];

        if (self::hasValidParam($config, 'defaultcontroller')) {
            $this->defControllerName = $this->params['defaultcontroller'];
        }
        if (self::hasValidParam($config, 'defaultaction')) {
            $this->defActionName = $this->params['defaultaction'];
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
     * @return PDO                 The currently used PDO connection instance
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

    public function getSession(): ?Session
    {
        if (empty($this->session)) {
            return null;
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

    /**
     * Determine the controller and the action based on the REQUEST_URI
     * and invoke the related Controller's actionXy() method or throw and exception
     *
     * @return Response
     * @throws ActionNotFoundException when the specified Controller's method is not existing.
     * @throws ControllerNotFoundException when the controller does not exist
     */
    public function run(): Response
    {
        $this->response = new Response();
        $this->controller = null;
        $this->createController();
        $actionMethodName = $this->createActionMethodName();

        if (!method_exists($this->controller, $actionMethodName)) {
            throw new ActionNotFoundException($actionMethodName, $this->controllerName);
        }

        $this->session = new Session();
        if ($this->session->has('user_id')) {
            Myy::$user_id = $this->session->get('user_id');
        }

        $this->response = $this->controller->$actionMethodName();
        return $this->response;
    }

    /**
     * Create the route controller
     * 
     * Creates the Controller based on REQUEST_URI
     * or creates the Application's default controller if specified in the config/app.php
     * or throw and exception if no suitable Controller has been created.
     *
     * @return void
     * @throws ControllerNotFoundException
     */
    protected function createController(): void
    {
        $this->controllerName = $this->request->getControllerName();
        $newControllerName = ($this->controllerName === 'default' || empty($this->controllerName)) ?
            ($this->defControllerName ?? '') : $this->controllerName;
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

    /**
     * Calculate the route action
     * 
     * Calculates the action method's name based on the REQUEST_URI or
     * on the default action in the config/app.php config file.
     * Action name is 'action'+UPPERCASE_FIRST(ROUTE_ACTION) in best case.
     *
     * @return string
     * @throws ActionNotFoundException
     */
    protected function createActionMethodName(): string
    {
        $this->actionName = $this->request->getActionName();
        $newActionName = ($this->actionName === 'default' || empty($this->actionName)) ?
            ($this->defActionName ?? '') : $this->actionName;
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
}
