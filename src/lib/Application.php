<?php

namespace Framework;

use Framework\Db\DbConfiguration;
use Framework\Session;
use Framework\Exception\InvalidConfigurationException;
use Framework\Exception\MissingConfigurationException;
use Framework\Exception\NotInitializedException;
use PDOException;

class Application
{
        protected static \PDO $dbConn;
        protected static DbConfiguration $dbConf;
        protected static array $dbOptions = [
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
        ];

        protected array $params;
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

        public function __destruct()
        {
                if ($this->session->getStatus() == PHP_SESSION_ACTIVE) {
                        $this->session->close();
                }
        }
}
