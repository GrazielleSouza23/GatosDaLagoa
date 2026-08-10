<?php

namespace App\Controllers\Site;

use App\Core\Controller;

class VoluntariadoController extends Controller
{
    public function index(): void
    {
        $this->view('voluntariado', ['activePage' => 'voluntariado']);
    }
}
