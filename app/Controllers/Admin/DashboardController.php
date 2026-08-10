<?php

namespace App\Controllers\Admin;

use App\Core\Auth;
use App\Core\Controller;
use App\Models\Evento;
use App\Models\Galeria;
use App\Models\HistoriaSucesso;

class DashboardController extends Controller
{
    public function index(): void
    {
        Auth::requireLogin();

        $stats = [
            'eventos' => count(Evento::ativos()),
            'historias' => count(HistoriaSucesso::ativas()),
            'galeria' => count(Galeria::ativas()),
        ];

        $this->adminView('dashboard', [
            'stats' => $stats,
            'eventosProximos' => Evento::proximos(5),
            'historiasRecentes' => HistoriaSucesso::recentes(5),
        ]);
    }
}
