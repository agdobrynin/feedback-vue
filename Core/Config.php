<?php

declare(strict_types=1);

namespace Core;

final class Config
{
    private $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    public function getDbDsn(): string
    {
        $dsn = $this->config['db']['dsn'] ?? '';
        if (empty($dsn)) {
            throw new \UnexpectedValueException('DSN базы неуказан в настройке [db][dsn]');
        }

        return $dsn;
    }

    public function getDbUser(): ?string
    {
        return $this->config['db']['user'] ?? null;
    }

    public function getDbPassword(): ?string
    {
        return $this->config['db']['password'] ?? null;
    }

    public function getDbOptions(): ?array
    {
        return $this->config['db']['options'] ?? null;
    }

    public function getViewDirectory(): string
    {
        $defaultDirectory = dirname(__FILE__, 2) . DIRECTORY_SEPARATOR . 'App' . DIRECTORY_SEPARATOR . 'View';
        return $this->config['viewDirectory'] ?? $defaultDirectory;
    }
}
