<?php

namespace App\Formatter;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\User;

class ApiResponseFormatter
{
    private mixed $data = null;
    private string|array|null $messages = null;
    private array $errors = [];
    private int $statusCode = Response::HTTP_OK;
    private array $additionalData = [];

    public function formatUser(User $user): array
    {
        return [
            'id' => $user->getId(),
            'email' => $user->getEmail(),
            'username' => $user->getUsername(),
            'roles' => $user->getRoles(),
        ];
    }

    public function formatUsers(array $users): array
    {
        return array_map(fn(User $user) => $this->formatUser($user), $users);
    }

    public function setData(mixed $data): self
    {
        $this->data = $data;
        return $this;
    }

    public function setMessages(string|array $messages): self
    {
        $this->messages = is_array($messages) ? $messages : [$messages];
        return $this;
    }

    public function setErrors(string|array $errors): self
    {
        $this->errors = is_array($errors) ? $errors : [$errors];
        return $this;
    }

    public function setStatusCode(int $statusCode): self
    {
        $this->statusCode = $statusCode;
        return $this;
    }

    public function setAdditionalData(array $additionalData): self
    {
        $this->additionalData = $additionalData;
        return $this;
    }

    public function getResponse(): JsonResponse
    {
        return new JsonResponse([
            'data' => $this->data,
            'messages' => $this->messages,
            'errors' => empty($this->errors) ? null : $this->errors,
            'statusCode' => $this->statusCode,
            'additionalData' => empty($this->additionalData) ? null : $this->additionalData,
        ], $this->statusCode);
    }

    public function success(mixed $data = null, string|array $messages = null, int $statusCode = Response::HTTP_OK): JsonResponse
    {
        return $this
            ->setData($data)
            ->setMessages($messages ?? 'Success')
            ->setStatusCode($statusCode)
            ->getResponse();
    }

    public function error(string|array $errors, int $statusCode = Response::HTTP_BAD_REQUEST, mixed $data = null): JsonResponse
    {
        return $this
            ->setData($data)
            ->setErrors($errors)
            ->setStatusCode($statusCode)
            ->getResponse();
    }

    public function notFound(string $message = 'Resource not found'): JsonResponse
    {
        return $this->error($message, Response::HTTP_NOT_FOUND);
    }

    public function unauthorized(string $message = 'Unauthorized'): JsonResponse
    {
        return $this->error($message, Response::HTTP_UNAUTHORIZED);
    }

    public function conflict(string $message = 'Resource already exists'): JsonResponse
    {
        return $this->error($message, Response::HTTP_CONFLICT);
    }
}