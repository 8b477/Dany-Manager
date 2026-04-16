<?php
declare(strict_types=1);

namespace App\Services;

use App\Repositories\CategoryCardRepository;
use App\Entities\Category;
use App\Dtos\CategoryCardDto\Commands\CategoryCardDto;

class CategoryCardService
{

    public CategoryCardRepository $categoryCardRepository;


    public function __construct()
    {
        $this->categoryCardRepository = new CategoryCardRepository();
    }


    public function saveCategoryCard()
    {
        throw new \Exception("Not implemented yet.");
    }

    public function getAllCategoryCards() : array
    {
        return $this->categoryCardRepository->getAll();
    }

}