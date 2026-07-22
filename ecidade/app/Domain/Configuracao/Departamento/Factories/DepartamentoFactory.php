<?php

namespace App\Domain\Configuracao\Departamento\Factories;

use App\Domain\Configuracao\Departamento\Templates\ValidaDepartamentoTemplate;
use App\Domain\Saude\Ambulatorial\Services\ValidaUnidadeService;

class DepartamentoFactory
{
    /**
     * @param string $tipo
     * @return ValidaDepartamentoTemplate
     */
    public static function getValidador($tipo)
    {
        switch ($tipo) {
            case 'unidade':
                return new ValidaUnidadeService;
            default:
                throw new \Exception('Erro ao validar departamento logado. Informe um tipo valido!');
        }
    }
}
