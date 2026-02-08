<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Formatter\ApiResponseFormatter;
use Symfony\Component\HttpFoundation\Request;  
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserController extends AbstractController
{
    public function __construct(
        private UserRepository $userRepository,
        private UserPasswordHasherInterface $passwordHasher,
        private ApiResponseFormatter $formatter
    ) {}

    #[Route('/api/test-auth', name: 'api_test_auth', methods: ['GET'])]
    public function testAuth(): JsonResponse
    {
        $user = $this->getUser();
        
        if (!$user) {
            return $this->formatter->unauthorized('Not authenticated');
        }

        return $this->formatter->success([
            'authenticated' => true,
            'user' => $user->getUserIdentifier(),
        ], 'JWT działa!');
    }

    #[Route('/api/users/me', name: 'api_user_me', methods: ['GET'])]
    public function showCurrentUser(): JsonResponse
    {
        $currentUser = $this->getUser();

        if (!$currentUser) {
            return $this->formatter->unauthorized('Not authenticated');
        }

        return $this->formatter->success(
            $this->formatter->formatUser($currentUser)
        );
    }

    #[Route('/api/users', name: 'api_users_index', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $users = $this->userRepository->findAll();

        return $this->formatter
            ->setData($this->formatter->formatUsers($users))
            ->setAdditionalData(['total' => count($users)])
            ->getResponse();
    }

    #[Route('/api/users/{id}', name: 'api_user_show', methods: ['GET'])]
    public function show(int $id): JsonResponse
    {
        $user = $this->userRepository->find($id);

        if (!$user) {
            return $this->formatter->notFound('User not found');
        }

        return $this->formatter->success(
            $this->formatter->formatUser($user)
        );
    }

    #[Route('/api/users', name: 'api_user_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['email']) || !isset($data['password'])) {
            return $this->formatter->error(
                'Email and password are required',
                Response::HTTP_BAD_REQUEST
            );
        }

        $existingUser = $this->userRepository->findOneBy(['email' => $data['email']]);
        if ($existingUser) {
            return $this->formatter->conflict('User with this email already exists');
        }

        $user = new User();
        $user->setEmail($data['email']);
        
        $username = $data['username'] ?? explode('@', $data['email'])[0];
        $user->setUsername($username);
        
        $hashedPassword = $this->passwordHasher->hashPassword($user, $data['password']);
        $user->setPassword($hashedPassword);

        $roles = $data['roles'] ?? ['ROLE_USER'];
        $user->setRoles($roles);

        $this->userRepository->save($user, true);

        return $this->formatter->success(
            $this->formatter->formatUser($user),
            'User created successfully',
            Response::HTTP_CREATED
        );
    }

    #[Route('/api/users/{id}', name: 'api_user_update', methods: ['PUT', 'PATCH'])]
    public function update(int $id, Request $request): JsonResponse
    {
        $user = $this->userRepository->find($id);

        if (!$user) {
            return $this->formatter->notFound('User not found');
        }

        $data = json_decode($request->getContent(), true);

        if (isset($data['email'])) {
            $existingUser = $this->userRepository->findOneBy(['email' => $data['email']]);
            if ($existingUser && $existingUser->getId() !== $id) {
                return $this->formatter->conflict('Email already taken by another user');
            }
            $user->setEmail($data['email']);
        }

        if (isset($data['username'])) {
            $user->setUsername($data['username']);
        }

        if (isset($data['password'])) {
            $hashedPassword = $this->passwordHasher->hashPassword($user, $data['password']);
            $user->setPassword($hashedPassword);
        }

        if (isset($data['roles'])) {
            $user->setRoles($data['roles']);
        }

        $this->userRepository->save($user, true);

        return $this->formatter->success(
            $this->formatter->formatUser($user),
            'User updated successfully'
        );
    }

    #[Route('/api/users/{id}', name: 'api_user_delete', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        $user = $this->userRepository->find($id);

        if (!$user) {
            return $this->formatter->notFound('User not found');
        }

        $this->userRepository->remove($user, true);

        return $this->formatter->success(
            null,
            'User deleted successfully'
        );
    }
}