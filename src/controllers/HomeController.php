<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Configs\Template;

class HomeController
{
    public function home(): void
    {
        echo Template::get()->render('home.html', [
            'active_page' => 'home',
        ]);
    }

    public function notFound(): void
    {
        http_response_code(404);
        readfile(__DIR__ . '/../templates/pages/not-found.html');
    }
}