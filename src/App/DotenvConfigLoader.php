<?php
declare(strict_types=1);

namespace CommissionTask\App;

use Symfony\Component\Config\Loader\FileLoader;
use Symfony\Component\Dotenv\Dotenv;

class DotenvConfigLoader extends FileLoader
{
    public function load($resource, string $type = null)
    {
        $dotenv = new Dotenv();
        $configValues = $dotenv->parse(file_get_contents($resource));

        return $configValues;
    }

    public function supports($resource, string $type = null)
    {
        return is_string($resource) && 'env' === pathinfo(
                $resource,
                PATHINFO_EXTENSION
            );
    }
}