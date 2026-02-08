<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/api/test-auth', name: 'api_test_auth', methods: ['GET'])]
    public function testAuth(): JsonResponse
    {
        $user = $this->getUser();
        
        return new JsonResponse([
            'authenticated' => $user !== null,
            'user' => $user ? $user->getUserIdentifier() : null,
            'message' => 'Jeśli to widzisz to JWT działa!'
        ]);
    }

    #[Route('/api/users/me', name: 'api_user_me', methods: ['GET'])]
    public function showCurrentUser(): JsonResponse
    {
        $currentUser = $this->getUser();

        if (!$currentUser) {
            return new JsonResponse([
                'data' => null,
                'messages' => null,
                'errors' => ['Not authenticated'],
                'statusCode' => 401,
                'additionalData' => null
            ], 401);
        }

        return new JsonResponse([
            'data' => [
                'user_id' => $currentUser->getId(),
                'user_email' => $currentUser->getEmail(),
                'roles' => $currentUser->getRoles(),
            ],
            'messages' => null,
            'errors' => null,
            'statusCode' => 200,
            'additionalData' => null
        ]);
    }

    #[Route('/api/users', name: 'api_users_index', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $users = $this->userRepository->findAll();

        $data = [];
        foreach ($users as $user) {
            $data[] = [
                'id' => $user->getId(),
                'email' => $user->getEmail(),
                'roles' => $user->getRoles(),
            ];
        }

        return new JsonResponse([
            'data' => $data,
            'messages' => null,
            'errors' => null,
            'statusCode' => 200,
            'additionalData' => ['total' => count($data)]
        ]);
    }

    #[Route('/api/users/{id}', name: 'api_user_show', methods: ['GET'])]
    public function show(int $id): JsonResponse
    {
        $user = $this->userRepository->find($id);

        if (!$user) {
            return new JsonResponse([
                'data' => null,
                'messages' => null,
                'errors' => ['User not found'],
                'statusCode' => 404,
                'additionalData' => null
            ], 404);
        }

        return new JsonResponse([
            'data' => [
                'id' => $user->getId(),
                'email' => $user->getEmail(),
                'roles' => $user->getRoles(),
            ],
            'messages' => null,
            'errors' => null,
            'statusCode' => 200,
            'additionalData' => null
        ]);
    }

    #[Route('/api/users', name: 'api_user_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['email']) || !isset($data['password'])) {
            return new JsonResponse([
                'data' => null,
                'messages' => null,
                'errors' => ['Email and password are required'],
                'statusCode' => 400,
                'additionalData' => null
            ], 400);
        }

        $existingUser = $this->userRepository->findByEmail($data['email']);
        if ($existingUser) {
            return new JsonResponse([
                'data' => null,
                'messages' => null,
                'errors' => ['User with this email already exists'],
                'statusCode' => 409,
                'additionalData' => null
            ], 409);
        }

        $user = new User();
        $user->setEmail($data['email']);
        
        $hashedPassword = $this->passwordHasher->hashPassword($user, $data['password']);
        $user->setPassword($hashedPassword);

        $roles = $data['roles'] ?? ['ROLE_USER'];
        $user->setRoles($roles);

        $this->userRepository->save($user);

        return new JsonResponse([
            'data' => [
                'id' => $user->getId(),
                'email' => $user->getEmail(),
                'roles' => $user->getRoles(),
            ],
            'messages' => ['User created successfully'],
            'errors' => null,
            'statusCode' => 201,
            'additionalData' => null
        ], 201);
    }

    #[Route('/api/users/{id}', name: 'api_user_update', methods: ['PUT', 'PATCH'])]
    public function update(int $id, Request $request): JsonResponse
    {
        $user = $this->userRepository->find($id);

        if (!$user) {
            return new JsonResponse([
                'data' => null,
                'messages' => null,
                'errors' => ['User not found'],
                'statusCode' => 404,
                'additionalData' => null
            ], 404);
        }

        $data = json_decode($request->getContent(), true);

        
        if (isset($data['email'])) {
            
            $existingUser = $this->userRepository->findByEmail($data['email']);
            if ($existingUser && $existingUser->getId() !== $id) {
                return new JsonResponse([
                    'data' => null,
                    'messages' => null,
                    'errors' => ['Email already taken by another user'],
                    'statusCode' => 409,
                    'additionalData' => null
                ], 409);
            }
            $user->setEmail($data['email']);
        }

        if (isset($data['password'])) {
            $hashedPassword = $this->passwordHasher->hashPassword($user, $data['password']);
            $user->setPassword($hashedPassword);
        }

        if (isset($data['roles'])) {
            $user->setRoles($data['roles']);
        }

        $this->userRepository->save($user);

        return new JsonResponse([
            'data' => [
                'id' => $user->getId(),
                'email' => $user->getEmail(),
                'roles' => $user->getRoles(),
            ],
            'messages' => ['User updated successfully'],
            'errors' => null,
            'statusCode' => 200,
            'additionalData' => null
        ]);
    }

    #[Route('/api/users/{id}', name: 'api_user_delete', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        $user = $this->userRepository->find($id);

        if (!$user) {
            return new JsonResponse([
                'data' => null,
                'messages' => null,
                'errors' => ['User not found'],
                'statusCode' => 404,
                'additionalData' => null
            ], 404);
        }

        $this->userRepository->remove($user);

        return new JsonResponse([
            'data' => null,
            'messages' => ['User deleted successfully'],
            'errors' => null,
            'statusCode' => 200,
            'additionalData' => null
        ]);
    }
}