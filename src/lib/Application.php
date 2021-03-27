<?php

namespace Framework;

use Framework\Db\DbConfiguration;
use Framework\Session;
use Framework\Exception\InvalidConfigurationException;
use Framework\Exception\MissingConfigurationException;
use PDOException;

class Application
{
        protected static Session $session;
        protected static DbConfiguration $dbConf;
        protected static \PDO $dbConn;
        protected static array $dbOptions = [
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
        ];
        protected array $params;

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
                $this->session = new Session();
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

        public static function hasValidParam(array $config, string $paramName): bool
        {
                return (!empty($config['params']) && ($config['params'][$paramName] ?? false));
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
}
