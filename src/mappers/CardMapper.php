<?php
declare(strict_types=1);

namespace App\Mappers;

use App\Entities\Card as CardEntity;
use App\Dtos\CardDto\Commands\CardDto;
use App\Dtos\CardDto\Queries\CardViewDto;

class CardMapper
{
    // Lecture : depuis une ligne SQL (JOIN card + category)
    public static function fromRow(array $row): CardViewDto
    {
        return CardViewDto::fromRow($row);
    }

    // Lecture : depuis un tableau de lignes SQL
    public static function fromRows(array $rows): array
    {
        return array_map(fn(array $row) => CardViewDto::fromRow($row), $rows);
    }

    // Insertion : depuis un DTO de commande vers une entité
    public static function toEntity(CardDto $cardDto, int $idUser): CardEntity
    {
        return new CardEntity(
            $cardDto->id,
            $cardDto->name,
            $cardDto->description,
            $cardDto->position,
            $cardDto->categoryId,
            $idUser,
        );
    }
}