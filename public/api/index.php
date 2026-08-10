<?php
/**
 * Front Controller da API REST.
 * Todas as rotas /api/v1/* chegam aqui via public/.htaccess (ou
 * public/api/.htaccess) e são despachadas pelo Router.
 *
 * Respostas sempre em JSON: { "success": bool, "data"|"message"|"errors" }
 */

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/autoload.php';

use App\Controllers\Api\AuthApiController;
use App\Controllers\Api\ConfiguracaoApiController;
use App\Controllers\Api\EventoApiController;
use App\Controllers\Api\GaleriaApiController;
use App\Controllers\Api\HistoriaApiController;
use App\Controllers\Api\RedeSocialApiController;
use App\Controllers\Api\TopicoApiController;
use App\Core\Response;
use App\Core\Router;

$router = new Router();

// ---------- Autenticação ----------
$router->post('/auth/login', [AuthApiController::class, 'login']);
$router->post('/auth/logout', [AuthApiController::class, 'logout']);
$router->get('/auth/me', [AuthApiController::class, 'me']);

// ---------- Eventos ----------
$router->get('/eventos', [EventoApiController::class, 'index']);
$router->get('/eventos/{id}', [EventoApiController::class, 'show']);
$router->post('/eventos', [EventoApiController::class, 'store']);
$router->put('/eventos/{id}', [EventoApiController::class, 'update']);
$router->delete('/eventos/{id}', [EventoApiController::class, 'destroy']);

// ---------- Histórias de Sucesso ----------
$router->get('/historias', [HistoriaApiController::class, 'index']);
$router->get('/historias/{id}', [HistoriaApiController::class, 'show']);
$router->post('/historias', [HistoriaApiController::class, 'store']);
$router->put('/historias/{id}', [HistoriaApiController::class, 'update']);
$router->delete('/historias/{id}', [HistoriaApiController::class, 'destroy']);

// ---------- Galeria ----------
$router->get('/galeria', [GaleriaApiController::class, 'index']);
$router->get('/galeria/{id}', [GaleriaApiController::class, 'show']);
$router->post('/galeria', [GaleriaApiController::class, 'store']);
$router->put('/galeria/{id}', [GaleriaApiController::class, 'update']);
$router->delete('/galeria/{id}', [GaleriaApiController::class, 'destroy']);

// ---------- Configurações ----------
$router->get('/configuracoes', [ConfiguracaoApiController::class, 'index']);
$router->get('/configuracoes/{chave}', [ConfiguracaoApiController::class, 'show']);
$router->put('/configuracoes/{chave}', [ConfiguracaoApiController::class, 'update']);

// ---------- Redes Sociais ----------
$router->get('/redes-sociais', [RedeSocialApiController::class, 'index']);
$router->post('/redes-sociais', [RedeSocialApiController::class, 'store']);
$router->put('/redes-sociais/{id}', [RedeSocialApiController::class, 'update']);
$router->delete('/redes-sociais/{id}', [RedeSocialApiController::class, 'destroy']);

// ---------- Tópicos de Adoção ----------
$router->get('/topicos-adocao', [TopicoApiController::class, 'index']);
$router->post('/topicos-adocao', [TopicoApiController::class, 'store']);
$router->put('/topicos-adocao/{id}', [TopicoApiController::class, 'update']);
$router->delete('/topicos-adocao/{id}', [TopicoApiController::class, 'destroy']);

try {
    $router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI'], '/api/v1');
} catch (\Throwable $e) {
    systemLog('Erro na API: ' . $e->getMessage());
    Response::error('Erro interno no servidor.', 500);
}
