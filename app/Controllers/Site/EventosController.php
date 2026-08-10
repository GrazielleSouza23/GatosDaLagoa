<?php

namespace App\Controllers\Site;

use App\Core\Controller;
use App\Models\Evento;

class EventosController extends Controller
{
    public function index(): void
    {
        $this->view('eventos', [
            'activePage' => 'eventos',
            'eventosFuturos' => Evento::futuros(),
        ]);
    }
}
