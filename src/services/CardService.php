<?php
declare(strict_types=1);

namespace App\Services;

use App\Dtos\CardDto\Commands\CardDto;
use App\Dtos\FormDto\FormResult;
use App\Repositories\CardRepository;
use App\Mappers\CardMapper;

class CardService{

    private CardRepository $cardRepository;

    public function __construct() {
        $this->cardRepository = new CardRepository();
    }

    // Return new array with key = categoryId wich each contains array cards
    public function getCardsByUserId(int $idUser): array
    {
        $rows = $this->cardRepository->getByIdUser($idUser);
        $cards = CardMapper::fromRows($rows);

        $cardsByCategory = [];
        foreach ($cards as $card) {
            $catId = $card->category?->id ?? 0;
            $cardsByCategory[$catId][] = $card;
        }
        return $cardsByCategory;
        // Display data :
            // [
            //      1 => [/* all cards cat 1 */],
            //      0 => [/* cards no cat */]
            // ]
    }
    
    public function saveCard(CardDto $dto, int $idUser) : FormResult
    {
        $result = new FormResult();

        // Calcul position
        $dto->position = $this->cardRepository->getNextPosition($dto->categoryId);

        // Map DTO -> Entity
        $card = CardMapper::toEntity($dto, $idUser);

        // Save DB
        $cardCreated = $this->cardRepository->add($card);

        if(!$cardCreated) {
            $result->addError("Une erreur est survenue lors de la création de la carte.");
            return $result;
        }
        $result->setSuccess('Carte créée avec succès.');
        return $result;
    }

    public function deleteCard(int $id, int $idUser): FormResult
    {
        $result = new FormResult();

        $card = $this->cardRepository->getById($id);
        if ($card === null || (int)$card['userId'] !== $idUser) {
            $result->addError("Vous n'êtes pas autorisé à supprimer cette carte.");
            return $result;
        }

        $deleted = $this->cardRepository->delete($id);

        if(!$deleted) {
            $result->addError("Une erreur est survenue lors de la suppression de la carte.");
            return $result;
        }
        $result->setSuccess('Carte supprimée avec succès.');
        return $result;
    }

    public function moveCard(int $cardId, int $categoryId, int $idUser): FormResult
    {
        $result = new FormResult();

        $card = $this->cardRepository->getById($cardId);
        if ($card === null || (int)$card['userId'] !== $idUser) {
            $result->addError("Vous n'êtes pas autorisé à déplacer cette carte.");
            return $result;
        }

        $position = $this->cardRepository->getNextPosition($categoryId);
        $moved = $this->cardRepository->move($cardId, $categoryId, $position);

        if (!$moved) {
            $result->addError("Une erreur est survenue lors du déplacement de la carte.");
            return $result;
        }
        $result->setSuccess('Carte déplacée.');
        return $result;
    }

    public function updateCard(CardDto $dto, int $idUser): FormResult
    {
        $result = new FormResult();

        // Si la catégorie a changé, recalculer la position pour mettre la carte en bas de la nouvelle liste
        $current = $this->cardRepository->getById($dto->id);
        if ($current === null || (int)$current['userId'] !== $idUser) {
            $result->addError("Vous n'êtes pas autorisé à modifier cette carte.");
            return $result;
        }
        if ((int)$current['categoryId'] !== $dto->categoryId) {
            $dto->position = $this->cardRepository->getNextPosition($dto->categoryId);
        }

        // Map DTO -> Entity
        $card = CardMapper::toEntity($dto,$idUser);

        // Update DB
        $updated = $this->cardRepository->update($card);

        if(!$updated) {
            $result->addError("Une erreur est survenue lors de la mise à jour de la carte.");
            return $result;
        }
        $result->setSuccess('Carte mise à jour avec succès.');
        return $result;
    }
}