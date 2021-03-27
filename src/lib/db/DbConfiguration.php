<?php

namespace Framework\Db;

use \Framework\Exception\InvalidConfigurationException;
use \Framework\Exception\MissingConfigurationException;

class DbConfiguration
{

    protected string $dsn;
    protected string $username;
    protected string $password;

    /**
     * Creates a new Database Configuration from a given array
     *
     * @param array $config The configuration parameters
     */
    public function __construct(array $config)
    {
        $requiredParams = array_keys(get_class_vars(self::class));
        if (empty($config)) {
            throw new MissingConfigurationException();
        }
        if (count(array_diff($requiredParams, array_keys($config)) ?? []) > 0) {
            throw new InvalidConfigurationException();
        }
        $this->dsn = $config['dsn'] ?? null;
        $this->username = $config['username'] ?? null;
        $this->password = $config['password'] ?? null;
    }

    public function getUserName(): string
    {
        return $this->username ?? null;
    }

    public function getPassword(): string
    {
        return $this->password ?? null;
    }

    public function getDSN(): string
    {
        return $this->dsn ?? null;
    }
}
