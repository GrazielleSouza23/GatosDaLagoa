<?php
/**
 * Front Controller - site público e painel administrativo.
 * Todas as requisições (exceto arquivos estáticos e /api) chegam aqui
 * via .htaccess e são despachadas pelo Router para o Controller correto.
 */

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/autoload.php';

use App\Controllers\Admin\AuthController;
use App\Controllers\Admin\ConfiguracaoController;
use App\Controllers\Admin\DashboardController;
use App\Controllers\Admin\EventoController;
use App\Controllers\Admin\GaleriaController;
use App\Controllers\Admin\HistoriaController;
use App\Controllers\Admin\PerfilController;
use App\Controllers\Site\ContatoController;
use App\Controllers\Site\DoacoesController;
use App\Controllers\Site\EventosController;
use App\Controllers\Site\GaleriaController as SiteGaleriaController;
use App\Controllers\Site\HomeController;
use App\Controllers\Site\QuemSomosController;
use App\Controllers\Site\VoluntariadoController;
use App\Core\Router;

$router = new Router();

// ---------- Site público ----------
$router->get('/', [HomeController::class, 'index']);
$router->get('/quem-somos', [QuemSomosController::class, 'index']);
$router->get('/eventos', [EventosController::class, 'index']);
$router->get('/voluntariado', [VoluntariadoController::class, 'index']);
$router->get('/doacoes', [DoacoesController::class, 'index']);
$router->get('/galeria', [SiteGaleriaController::class, 'index']);
$router->get('/contato', [ContatoController::class, 'index']);

// ---------- Painel administrativo ----------
$router->get('/admin/login', [AuthController::class, 'loginForm']);
$router->post('/admin/login', [AuthController::class, 'login']);
$router->get('/admin/logout', [AuthController::class, 'logout']);
$router->get('/admin/dashboard', [DashboardController::class, 'index']);

$router->get('/admin/eventos', [EventoController::class, 'index']);
$router->post('/admin/eventos', [EventoController::class, 'index']);

$router->get('/admin/historias', [HistoriaController::class, 'index']);
$router->post('/admin/historias', [HistoriaController::class, 'index']);

$router->get('/admin/galeria', [GaleriaController::class, 'index']);
$router->post('/admin/galeria', [GaleriaController::class, 'index']);

$router->get('/admin/configuracoes', [ConfiguracaoController::class, 'index']);
$router->post('/admin/configuracoes', [ConfiguracaoController::class, 'index']);

$router->get('/admin/perfil', [PerfilController::class, 'index']);
$router->post('/admin/perfil', [PerfilController::class, 'index']);

$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
