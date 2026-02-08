<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserValidator
{
    public function __construct(
        private ValidatorInterface $validator
    ) {}

    public function validateCreate(array $data): array
    {
        $constraints = new Assert\Collection([
            'email' => [
                new Assert\NotBlank(message: 'Email is required'),
                new Assert\Email(message: 'Invalid email format'),
                new Assert\Length(max: 180, maxMessage: 'Email cannot be longer than 180 characters'),
            ],
            'password' => [
                new Assert\NotBlank(message: 'Password is required'),
                new Assert\Length(
                    min: 8,
                    max: 4096,
                    minMessage: 'Password must be at least 8 characters',
                    maxMessage: 'Password is too long'
                ),
            ],
            'username' => new Assert\Optional([
                new Assert\Type('string'),
                new Assert\Length(max: 255, maxMessage: 'Username cannot be longer than 255 characters'),
            ]),
            'roles' => new Assert\Optional([
                new Assert\Type('array'),
                new Assert\All([
                    new Assert\Choice(
                        choices: ['ROLE_USER', 'ROLE_ADMIN', 'ROLE_MODERATOR'],
                        message: 'Invalid role: {{ value }}'
                    ),
                ]),
            ]),
        ]);

        $violations = $this->validator->validate($data, $constraints);

        return $this->formatViolations($violations);
    }

    public function validateUpdate(array $data): array
    {
        $constraints = new Assert\Collection([
            'fields' => [
                'email' => new Assert\Optional([
                    new Assert\Email(message: 'Invalid email format'),
                    new Assert\Length(max: 180, maxMessage: 'Email cannot be longer than 180 characters'),
                ]),
                'password' => new Assert\Optional([
                    new Assert\Length(
                        min: 8,
                        max: 4096,
                        minMessage: 'Password must be at least 8 characters',
                        maxMessage: 'Password is too long'
                    ),
                ]),
                'username' => new Assert\Optional([
                    new Assert\Type('string'),
                    new Assert\Length(max: 255, maxMessage: 'Username cannot be longer than 255 characters'),
                ]),
                'roles' => new Assert\Optional([
                    new Assert\Type('array'),
                    new Assert\All([
                        new Assert\Choice(
                            choices: ['ROLE_USER', 'ROLE_ADMIN', 'ROLE_MODERATOR'],
                            message: 'Invalid role: {{ value }}'
                        ),
                    ]),
                ]),
            ],
            'allowExtraFields' => true,
        ]);

        $violations = $this->validator->validate($data, $constraints);

        return $this->formatViolations($violations);
    }

    private function formatViolations($violations): array
    {
        $errors = [];

        foreach ($violations as $violation) {
            $propertyPath = $violation->getPropertyPath();
            
            $field = str_replace(['[', ']'], '', $propertyPath);
            
            $errors[$field] = $violation->getMessage();
        }

        return $errors;
    }

    public function isValidEmail(string $email): bool
    {
        $constraints = new Assert\Email();
        $violations = $this->validator->validate($email, $constraints);
        
        return count($violations) === 0;
    }

    public function isValidPassword(string $password): bool
    {
        return strlen($password) >= 8 && strlen($password) <= 4096;
    }
}