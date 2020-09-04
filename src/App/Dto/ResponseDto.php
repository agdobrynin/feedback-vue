<?php

declare(strict_types=1);

namespace App\Dto;

class ResponseDto
{
    public $success = false;
    public $message = '';
    public $trace = [];
    public $data = [];

    public function fromException(\Throwable $throwable): void
    {
        $this->success = false;
        $this->message = $throwable->getMessage();
        $this->trace = $throwable->getTrace();
    }
}
