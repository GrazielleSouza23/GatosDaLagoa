<?php

namespace App\Controllers\Site;

use App\Core\Controller;

class DoacoesController extends Controller
{
    public function index(): void
    {
        $this->view('doacoes', ['activePage' => 'doacoes']);
    }
}
