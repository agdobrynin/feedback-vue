<?php

declare(strict_types=1);

namespace Core;

final class Container
{
    private $container;
    private $resolvedContainer;

    public function __construct()
    {
        $this->container = [];
    }

    public function set(string $name, $container): void
    {
        if ($this->has($name)) {
            throw new \UnexpectedValueException(sprintf('Контейнер "%s" уже зарегистрирован', $name));
        }
        $this->container[$name] = $container;
    }

    public function get(string $name, ...$arg)
    {
        if (!empty($this->container[$name])) {
            if (empty($this->resolvedContainer[$name])) {
                $this->resolvedContainer[$name] = $this->container[$name](...$arg);
            }

            return $this->resolvedContainer[$name];
        }
        throw new \UnexpectedValueException(sprintf('Контейнер "%s" не зарегистрирован', $name));
    }

    public function has(string $name): bool
    {
        return isset($this->container[$name]);
    }
}
