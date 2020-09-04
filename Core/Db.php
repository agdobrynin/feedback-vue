<?php

declare(strict_types=1);

namespace Core;

final class Db
{
    private static $connection;

    public static function connect(Config $config): \PDO
    {
        self::$connection = new \PDO($config->getDbDsn(), $config->getDbUser(), $config->getDbPassword(), $config->getDbOptions());
        self::$connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        return self::$connection;
    }
}
