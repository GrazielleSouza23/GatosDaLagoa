<?php

namespace App\Controllers\Site;

use App\Core\Controller;
use App\Models\Configuracao;
use App\Models\HistoriaSucesso;
use App\Models\RedeSocial;
use App\Models\TopicoAdocao;

class HomeController extends Controller
{
    public function index(): void
    {
        $this->view('home', [
            'activePage' => 'home',
            'configs' => Configuracao::allAsMap(),
            'topicos' => TopicoAdocao::allOrdenados(),
            'historias' => HistoriaSucesso::recentes(3),
            'redes' => RedeSocial::all(),
        ]);
    }
}
