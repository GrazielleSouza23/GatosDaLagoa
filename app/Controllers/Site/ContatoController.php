<?php

namespace App\Controllers\Site;

use App\Core\Controller;
use App\Models\RedeSocial;

class ContatoController extends Controller
{
    public function index(): void
    {
        $this->view('contato', [
            'activePage' => 'contato',
            'redes' => RedeSocial::all(),
        ]);
    }
}
