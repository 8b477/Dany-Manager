<?php
declare(strict_types=1);

namespace App\Services;

use App\Entities\User;
use App\Repositories\SessionRepository;
use App\Repositories\UserRepository;
use App\Entities\UserSession;
use DateTimeImmutable;

class SessionService
{

    private UserRepository $userRepository;
    private SessionRepository $sessionRepository;

    public function __construct() {
        $this->userRepository = new UserRepository();
        $this->sessionRepository = new SessionRepository();
    }

    public function sessionStart(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function restoreSessionFromCookie(): void
    {
        // Session existe ?
        if (isset($_SESSION['userSession'])) {
            return;
        }

        // Cookie remember me existe ?
        $token = $_COOKIE['remember_token'] ?? null;
        if ($token === null || empty($token)) {
            return;
        }

        // Token existe ?
        $session = $this->sessionRepository->findByToken($token);
        if ($session === null) {
            return;
        }

        // Token est expiré ?
        if ($session->getExpiration()->getTimestamp() < time()) {
            return;
        }

        // Tente de récupérer l'utilisateur associé au token
        $user = $this->userRepository->getById($session->getUserId());
        if ($user === null) {
            return;
        }

        // Restaure la session PHP avec les nouvelles données
        $_SESSION['userSession'] = [
            'id'    => $user->getId(),
            'name'  => $user->getUsername(),
            'email' => $user->getEmail(),
        ];
    }

    public function openPhpSession(User $user): void
    {
        session_regenerate_id(true);
        $_SESSION['userSession'] = [
            'id'    => $user->getId(),
            'name'  => $user->getUsername(),
            'email' => $user->getEmail(),
        ];
    }

    public function getIdByUserSession(): ?int
    {
        return $_SESSION['userSession']['id'] ?? null;
    }

    public function getNameByUserSession(): ?string
    {
        return $_SESSION['userSession']['name'] ?? null;
    }

    public function getEmailByUserSession(): ?string
    {
        return $_SESSION['userSession']['email'] ?? null;
    }

    public function persistRememberMe(User $user): void
    {
        $userSession = new UserSession(
            null,
            bin2hex(random_bytes(32)),
            new DateTimeImmutable('+7 days'),
            $user->getId()
        );
        // Check if user already has token in DB if yes update it, otherwise create a new one
        $this->sessionRepository->getByUserID($user->getId())
                ? $this->sessionRepository->updateSession($userSession)
                : $this->sessionRepository->setUserSession($userSession);

        $this->createCookieRememberMe($userSession);
    }

    public function getUserSession(): ?array
    {
        return $_SESSION['userSession'] ?? null;
    }

    public function clearSession(): void
    {
        $this->clearCookieRememberMe();
        $this->clearSessionData();   
    }


    private function createCookieRememberMe(UserSession $userSession):void{
        setcookie('remember_token', $userSession->getToken(), [
            'expires' => $userSession->getExpiration()->getTimestamp(),
            'path' => '/',
            'httponly' => true,
            'secure' => true,
            'samesite' => 'Lax',
        ]);
    }

    private function clearCookieRememberMe(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
        $rememberParams = [
            'expires' => time() - 3600,
            'path' => '/',
            'httponly' => true,
            'secure' => true, 
            'samesite' => 'Lax'
        ];
        setcookie('remember_token', '', $rememberParams);
        }
    }

    private function clearSessionData(): void
    {
        unset($_SESSION['userSession']);
        $_SESSION = [];
        
        if (session_status() === PHP_SESSION_ACTIVE) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params['path'] ?? '/',
                $params['domain'] ?? '',
                $params['secure'] ?? true,
                $params['httponly'] ?? true
            );
            session_destroy();
        }
    }
}