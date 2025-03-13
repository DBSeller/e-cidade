<?php

namespace App\Domain\Patrimonial\Protocolo\Factories;

use App\Domain\Patrimonial\Protocolo\Contracts\BuscaCgmLogado;
use App\Domain\Saude\Ambulatorial\Services\BuscaProfissionalLogadoService;

class BuscaCgmLogadoFactory
{
    /**
     * @param string $tipo
     * @return BuscaCgmLogado
     */
    public static function getService($tipo)
    {
        switch ($tipo) {
            case 'profissionalSaude':
                return new BuscaProfissionalLogadoService;
            default:
                throw new \Exception('Não foi possivel buscar o CGM logado! Tipo inválido.');
        }
    }
}
