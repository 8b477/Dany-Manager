<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Configs\Template;
use App\Dtos\UserDto\Commands\userLoginDto;
use App\Dtos\UserDto\Commands\userRegisterDto;
use App\Services\SessionService;
use App\Services\CardService;
use App\Services\CategoryCardService;
use App\Services\AuthService;

class AuthController
{
    private AuthService $authService;
    private CardService $cardService;
    private SessionService $sessionService;
    private CategoryCardService $categoryCardService;

    public function __construct() {
        $this->authService = new AuthService();
        $this->cardService = new CardService();
        $this->sessionService = new SessionService();
        $this->categoryCardService = new CategoryCardService();
    }


    public function register(): void
    {
        $dto = userRegisterDto::fromRequest($_POST); // check constraint + build object

        if (!$dto->isValid()) {
            echo Template::get()->render('home.html', [
                'active_page' => 'home',
                'register_errors' => $dto->getErrors(),
                'open_modal' => 'registerModal', // re-open target modal
                'form_data' => ['username' => $dto->username, 'email' => $dto->email] // reload inserted data
            ]);
            return;
        }

        $result = $this->authService->register($dto);
        if ($result->hasErrors()) {
            echo Template::get()->render('home.html', [
                'active_page' => 'home',
                'register_errors' => $result->getErrors(),
                'open_modal' => 'registerModal',
                'form_data' => ['username' => $dto->username, 'email' => $dto->email]
            ]);
            return;
        }

        $idUser = $this->sessionService->getIdByUserSession();
        $cards = $this->cardService->getCardsByUserId($idUser);
        $categories = $this->categoryCardService->getAllCategoryCards();

        echo Template::get()->render('board.html', [
            'active_page' => 'board',
            'success' => $result->getSuccessMessage(),
            'categories' => $categories,
            'cards_by_category' => $cards
        ]);
        exit;
    }


    public function login(): void
    {
        $dto = userLoginDto::fromRequest($_POST);

        if (!$dto->isValid()) {
            echo Template::get()->render('home.html', [
                'active_page' => 'home',
                'login_errors' => $dto->getErrors(),
                'open_modal' => 'loginModal',
                'form_data' => ['email' => $dto->email]
            ]);
            return;
        }

        $result = $this->authService->login($dto);

        if ($result->hasErrors()) {
            echo Template::get()->render('home.html', [
                'active_page' => 'home',
                'login_errors' => $result->getErrors(),
                'open_modal' => 'loginModal',
                'form_data' => ['email' => $dto->email]
            ]);
            return;
        }
        $idUser = $this->sessionService->getIdByUserSession();
        $cards = $this->cardService->getCardsByUserId($idUser);
        $categories = $this->categoryCardService->getAllCategoryCards();

        echo Template::get()->render('board.html', [
            'active_page' => 'board',
            'success' => $result->getSuccessMessage(),
            'categories' => $categories,
            'cards_by_category' => $cards
        ]);
        
        exit;
    }

    public function logout(): void
    {
        $this->authService->logout();
        header('Location: /');
        exit;
    }
}