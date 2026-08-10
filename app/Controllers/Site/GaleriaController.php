<?php

namespace App\Controllers\Site;

use App\Core\Controller;
use App\Core\Database;

class GaleriaController extends Controller
{
    public function index(): void
    {
        // Une imagens da galeria, eventos e histórias de sucesso (mesmo
        // comportamento do pages/Galeria.php original).
        $sql = "
            SELECT id, titulo, imagem, categoria, descricao, data_criacao
            FROM galeria
            WHERE ativo = 1 AND imagem IS NOT NULL AND imagem != ''

            UNION ALL

            SELECT id, titulo, imagem, 'eventos' AS categoria, descricao, data_criacao
            FROM eventos
            WHERE ativo = 1 AND imagem IS NOT NULL AND imagem != ''

            UNION ALL

            SELECT id, nome_gato AS titulo, imagem, 'adocoes' AS categoria, descricao, data_criacao
            FROM historias_sucesso
            WHERE ativo = 1 AND imagem IS NOT NULL AND imagem != ''

            ORDER BY data_criacao DESC
        ";

        $imagens = Database::query($sql)->fetchAll();

        $this->view('galeria', [
            'activePage' => 'galeria',
            'imagens' => $imagens,
        ]);
    }
}
