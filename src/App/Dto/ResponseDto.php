<?php

declare(strict_types=1);

namespace App\Dto;

class ResponseDto
{
    public $success = false;
    public $message = '';
    public $trace = [];
    public $data = [];
}
