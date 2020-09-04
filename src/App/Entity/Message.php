<?php

declare(strict_types=1);

namespace App\Entity;

use Core\BaseEntity;

final class Message extends BaseEntity
{
    public $name;
    public $email;
    public $message;
    public $createdAt;

    public function validate(): self
    {
        $this->name = $this->sanitizeString($this->name, "Имя");
        $this->message = $this->sanitizeString($this->message, "Сообщение");
        $this->email = $this->verifyEmail($this->email);
        $this->createdAt = (new \DateTimeImmutable())->getTimestamp();

        return $this;
    }

    private function verifyEmail(?string $value): string
    {
        $email = filter_var($value, FILTER_SANITIZE_EMAIL);
        if (false === filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $message = sprintf('Email "%s" невалидный', $email);
            throw new \UnexpectedValueException($message);
        }

        return $email;
    }

    private function sanitizeString(?string $value, string $paramName): string
    {
        $value = trim(filter_var($value, FILTER_SANITIZE_STRING));
        if (empty($value)) {
            throw new \UnexpectedValueException(sprintf('"%s" неможет быть пустой строкой', $paramName));
        }

        return $value;
    }
}
