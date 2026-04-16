<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Configs\Template;

class ProfileController
{
    public function profile(): void
    {
        echo Template::get()->render('profile.html', [
            'active_page' => 'profile',
        ]);
    }
}
