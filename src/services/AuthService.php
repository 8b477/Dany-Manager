<?php
declare(strict_types=1);

namespace App\Services;

use App\Dtos\FormDto\FormResult;
use App\Dtos\UserDto\Commands\userLoginDto;
use App\Dtos\UserDto\Commands\userRegisterDto;
use App\Mappers\UserMapper;
use App\Repositories\UserRepository;
use App\Services\SessionService;

class AuthService
{
    private UserRepository $userRepository;
    private SessionService $sessionService;


    public function __construct()
    {
        $this->userRepository = new UserRepository();
        $this->sessionService = new SessionService();
    }


    public function register(UserRegisterDto $dto): FormResult
    {
        $result = new FormResult();

        // Check Constraints Unique - email
        if ($this->userRepository->existsByEmail($dto->email)) {
            $result->addError("Cet email est déjà utilisé.");
            return $result;
        }

        // Map DTO to Entity
        $user = UserMapper::toEntity($dto);
        $userCreated = $this->userRepository->add($user);

        // Check if user was created successfully
        if ($userCreated === null) {
            $result->addError("Une erreur est survenue lors de l'inscription.");
            return $result;
        }
        // Create $_SESSION
        $this->sessionService->openPhpSession($userCreated);

        // If remember me is checked, persist session in DB + set cookie
        if ($dto->rememberMe) {
            $this->sessionService->persistRememberMe($userCreated);
        }

        $result->setSuccess('Inscription réussie.');
        return $result;
    }

    public function login(UserLoginDto $dto): FormResult
    {
        $result = new FormResult();
        $user = $this->userRepository->getByEmail($dto->email);

        if (!$user || !password_verify($dto->password, $user->getPassword())) {
            $result->addError('Email ou mot de passe incorrect.');
            return $result;
        }

        $this->sessionService->openPhpSession($user);

        if ($dto->rememberMe) {
            $this->sessionService->persistRememberMe($user);
        }
        $result->setData(['user' => $user]);
        $result->setSuccess('Connexion réussie.');
        return $result;
    }
    
    public function logout(): void
    {
        $this->sessionService->clearSession();
    }

}
