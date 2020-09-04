<?php

declare(strict_types=1);

namespace Core;

final class Csrf
{
    public const CSRF_KEY = 'csrf-key-verifier';
    private const TTL = '+30min';

    public function getValue(): string
    {
        return $_SESSION[self::CSRF_KEY]['val'];
    }

    public function getTtl(): int
    {
        return $_SESSION[self::CSRF_KEY]['ttl'];
    }

    public function getField(): string
    {
        return sprintf('<input type="hidden" name="%s" value="%s">', self::CSRF_KEY, $this->refresh()->getValue());
    }

    public function verify(): self
    {
        $normalizeHeaderKey = strtoupper(str_replace('-', '_', 'HTTP-' . self::CSRF_KEY));
        $inputFromHttpHeaders = filter_input(\INPUT_SERVER, $normalizeHeaderKey, FILTER_SANITIZE_SPECIAL_CHARS);
        if ($inputFromHttpHeaders !== $this->getValue()) {
            $inputFromPost = filter_input(\INPUT_POST, self::CSRF_KEY, FILTER_SANITIZE_SPECIAL_CHARS);
            if ($inputFromPost !== $this->getValue()) {
                throw new \UnexpectedValueException('Ошибка подписи CSRF');
            }
        }
        if ($this->getTtl() < time()) {
            $message = sprintf('CSRF истекло время. Обновите страницу. Настройка TTL "%s"', self::TTL);
            throw new \UnexpectedValueException($message);
        }

        return $this;
    }

    public function refresh(): self
    {
        $csrf = $this->generate();
        $_SESSION[self::CSRF_KEY] = ['val' => $csrf, 'ttl' => strtotime(self::TTL)];

        return $this;
    }

    public function setResponseHeader(Response &$response): void
    {
        $response->setHeader(self::CSRF_KEY, $this->getValue());
    }

    private function generate(): string
    {
        return uniqid('', true);
    }
}
