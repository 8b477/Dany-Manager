<?php
declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use App\Router;
use App\Services\SessionService;
use App\Controllers\AuthController;
use App\Controllers\HomeController;
use App\Controllers\BoardController;
use App\Controllers\ProfileController;

$sessionService = new SessionService();
$sessionService->sessionStart();
$sessionService->restoreSessionFromCookie();

$router = new Router();

// Pages
$router->get('/', [HomeController::class, 'home']);

$router->post('/register', [AuthController::class, 'register']);
$router->post('/login', [AuthController::class, 'login']);
$router->post('/logout', [AuthController::class, 'logout'], true);

$router->get('/board', [BoardController::class, 'board'],true);
$router->post('/board/addCard', [BoardController::class, 'addCard'], true);
$router->post('/board/edit', [BoardController::class, 'updateCard'], true);
$router->post('/board/delete', [BoardController::class, 'deleteCard'], true);
$router->post('/board/move', [BoardController::class, 'moveCard'], true);

$router->get('/profile', [ProfileController::class, 'profile'],true);

$router->get('/not-found', [HomeController::class, 'notFound']);

$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);