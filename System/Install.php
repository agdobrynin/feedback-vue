<?php

declare(strict_types=1);

namespace System;

use App\Entity\Message;
use Core\Config;
use Core\Db;

final class Install
{
    private static function getConfigDirectory(): string
    {
        return dirname(__FILE__, 2).DIRECTORY_SEPARATOR.'src';
    }

    private static function getConfigFilePath(): string
    {
        return self::getConfigDirectory().DIRECTORY_SEPARATOR.'config.php';
    }

    private static function getConfigExampleFilePath(): string
    {
        return self::getConfigDirectory().DIRECTORY_SEPARATOR.'config-example.php';
    }

    private static function getConfig(): Config
    {
        return new Config(require self::getConfigFilePath());
    }


    public static function makeConfig(): void
    {
        $exampleConfig = self::getConfigExampleFilePath();

        if (!is_file($exampleConfig)) {
            $message = sprintf('Ненайден файл примера конфигурации проекта %s', $exampleConfig);
            throw new \UnexpectedValueException($message);
        }

        $fileConfig = self::getConfigFilePath();
        if (is_file($fileConfig)) {
            print sprintf('Файл конфигурации проекта уже создан в %s', $fileConfig).PHP_EOL;
            return;
        }

        if (!copy($exampleConfig , $fileConfig)) {
            $message = sprintf('Произошла ошибка копировани файла конфиграции из %s в %s', $exampleConfig, $fileConfig);
            throw new \RuntimeException($message);
        }

        print sprintf('Создан файл конфигурации проекта в %s', $fileConfig).PHP_EOL;
    }

    public static function migrationSqlUp(): void
    {
        $tableMessages = (new Message())->getTable();
        $sql = <<< SQL
            CREATE TABLE IF NOT EXISTS {$tableMessages}
            (
                id integer
                    constraint Messages_pk
                        primary key autoincrement,
                name varchar default 255 not null,
                email varchar default 100 not null,
                message text not null,
                createdAt integer not null
            );
        SQL;
        print 'DSN базы данных = '.self::getConfig()->getDbDsn().PHP_EOL;
        $pdo = Db::connect(self::getConfig());
        print 'Создание таблицы '.$tableMessages.' = ';
        print $pdo->query($sql)->execute() ? 'success' : 'fail';
        print PHP_EOL;
    }

    public static function migrationSqlDown(): void
    {
        print 'DSN базы данных = '.self::getConfig()->getDbDsn().PHP_EOL;
        $pdo = Db::connect(self::getConfig());
        $tableMessages = (new Message())->getTable();
        $sql = sprintf('DROP TABLE IF EXISTS %s;', $tableMessages);
        print 'Удаление таблицы '.$tableMessages.' = '.$sql.' : ';
        print $pdo->query($sql)->execute() ? 'success' : 'fail';
        print PHP_EOL;
    }
}
