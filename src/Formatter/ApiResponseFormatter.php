<?php

namespace App\Formatter;

use Symfony\Component\HttpFoundation\Response;

class ApiResponseFormatter{
    private $data;
    private string $messages = 'OK';
    private array $errors = [];
    private int $statusCode = Response::HTTP_OK;
    private array $additionalData = [];
}