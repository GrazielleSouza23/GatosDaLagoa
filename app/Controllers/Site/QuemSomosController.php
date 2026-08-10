<?php

namespace App\Controllers\Site;

use App\Core\Controller;

class QuemSomosController extends Controller
{
    public function index(): void
    {
        $this->view('quem_somos', ['activePage' => 'quem-somos']);
    }
}
