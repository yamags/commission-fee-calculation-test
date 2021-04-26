<?php

declare(strict_types=1);

namespace CommissionTask\App\Config;

class Configuration
{
    private static $instances = null;

    private static $values = [];

    protected function __construct()
    {
    }

    protected function __clone()
    {
    }

    public function __wakeup()
    {
        throw new \Exception('Cannot unserialize a singleton.');
    }

    public static function getInstance(): Configuration
    {
        if (!isset(self::$instances)) {
            self::$instances = new static();
        }

        return self::$instances;
    }

    public static function load($values)
    {
        self::$values = $values;
    }

    public static function get($name, $default = null)
    {
        if (isset(self::$values[$name])) {
            return self::$values[$name];
        } else {
            return $default;
        }
    }
}
