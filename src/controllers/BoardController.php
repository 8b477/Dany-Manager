<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Configs\Template;
use App\Dtos\CardDto\Commands\CardDto;
use App\Services\CardService;
use App\Services\CategoryCardService;
use App\Services\SessionService;

class BoardController
{
    private CardService $cardService;
    private CategoryCardService $categoryCardService;
    private SessionService $sessionService;


    public function __construct() {
        $this->cardService = new CardService();
        $this->categoryCardService = new CategoryCardService();
        $this->sessionService = new SessionService();
    }

    public function board(): void
    {
        $categoryCards = $this->categoryCardService->getAllCategoryCards();
    
        $idUser = $this->checkAuth();

        $cardsByCategory = $this->cardService->getCardsByUserId($idUser);

        echo Template::get()->render('board.html', [
            'active_page' => 'board',
            'sidebar_active' => 'active',
            'categories' => $categoryCards,
            'cards_by_category' => $cardsByCategory,
        ]);

    }

    
    public function addCard(): void
    {
        $idUser = $this->checkAuth();

        // Validation DTO
        $dto = CardDto::fromRequest($_POST);
        if(!$dto->isValid()){
            echo Template::get()->render('board.html',
                [
                    'active_page' => 'board',
                    'sidebar_active' => 'active',
                    'categories' => $this->categoryCardService->getAllCategoryCards(),
                    'cards_by_category' => $this->cardService->getCardsByUserId($idUser),
                    'add_card_errors' => $dto->getErrors(),
                    'form_data' =>
                    [
                        'name' => $dto->name,
                        'description' => $dto->description,
                        'category_id' => $dto->categoryId,
                        'open_modal' => 'addCardModal'
                    ]
                ]);
            return;
        }

        $cardCreated = $this->cardService->saveCard($dto, $idUser);
        // Validation service
        if($cardCreated->hasErrors()){
            echo Template::get()->render('board.html',
                [
                    'active_page' => 'board',
                    'sidebar_active' => 'active',
                    'categories' => $this->categoryCardService->getAllCategoryCards(),
                    'cards_by_category' => $this->cardService->getCardsByUserId($idUser),
                    'add_card_errors' => $cardCreated->getErrors(),
                    'form_data' =>
                    [
                        'name' => $dto->name,
                        'description' => $dto->description,
                        'category_id' => $dto->categoryId,
                        'open_modal' => 'addCardModal',
                    ]
                ]);
            return;
        }

        $categories = $this->categoryCardService->getAllCategoryCards();
        $cardsByCategory = $this->cardService->getCardsByUserId($idUser);

        echo Template::get()->render('board.html',
            [
                'active_page' => 'board',
                'sidebar_active' => 'active',
                'categories' => $categories,
                'cards_by_category' => $cardsByCategory
            ]);
    }

    public function deleteCard(): void
    {
        $idUser = $this->checkAuth();

        $idCard = (int)($_POST['card_id'] ?? 0);
        if($idCard <= 0) {
            header('Location: /board');
            exit;
        }

        $result = $this->cardService->deleteCard($idCard, $idUser);
        $categories = $this->categoryCardService->getAllCategoryCards();
        $cardsByCategory = $this->cardService->getCardsByUserId($idUser);

        echo Template::get()->render('board.html',
            [
                'active_page' => 'board',
                'sidebar_active' => 'active',
                'categories' => $categories,
                'cards_by_category' => $cardsByCategory,
                'delete_card_result' => $result,
            ]);
    }

    public function updateCard(): void
    {
        $idUser = $this->checkAuth();

        // Validation DTO
        $dto = CardDto::fromRequest($_POST);
        if(!$dto->isValid()){
            echo Template::get()->render('board.html',
                [
                    'active_page' => 'board',
                    'sidebar_active' => 'active',
                    'update_card_errors' => $dto->getErrors(),
                    'form_data' =>
                    [
                        'id' => $dto->id,
                        'name' => $dto->name,
                        'description' => $dto->description,
                        'category_id' => $dto->categoryId,
                        'open_modal' => "updateCardModal-{$dto->id}",
                    ]
                ]);
            return;
        }

        $result = $this->cardService->updateCard($dto, $idUser);
        $categories = $this->categoryCardService->getAllCategoryCards();
        $cardsByCategory = $this->cardService->getCardsByUserId($idUser);


        echo Template::get()->render('board.html',
            [
                'active_page' => 'board',
                'sidebar_active' => 'active',
                'categories' => $categories,
                'cards_by_category' => $cardsByCategory,
                'update_card_result' => $result,
            ]);
    }

    public function moveCard(): void
    {
        $idUser = $this->checkAuth();

        $cardId     = (int)($_POST['card_id']     ?? 0);
        $categoryId = (int)($_POST['category_id'] ?? 0);

        if ($cardId <= 0 || $categoryId <= 0) {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => 'Paramètres invalides.']);
            return;
        }

        $result = $this->cardService->moveCard($cardId, $categoryId, $idUser);

        header('Content-Type: application/json');
        echo json_encode([
            'success' => $result->isSuccess(),
            'error'   => $result->isSuccess() ? null : implode(' ', $result->getErrors()),
        ]);
    }

    private function checkAuth(): int
    {
        $idUser = $this->sessionService->getIdByUserSession();
        if($idUser === null) {
            header('Location: /login');
            exit;
        }
        return $idUser;
    }
}
