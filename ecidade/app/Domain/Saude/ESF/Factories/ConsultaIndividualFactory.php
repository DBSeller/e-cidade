<?php

namespace App\Domain\Saude\ESF\Factories;

class ConsultaIndividualFactory
{
    public static function getRepository()
    {
        $repository = ECIDADE_PATH . 'src/Saude/ESF/Repositories/ConsultaIndividualRepository.php';
        if (file_exists($repository)) {
            return new \ECidade\Saude\ESF\Repositories\ConsultaIndividualRepository;
        }

        throw new \Exception('Erro ao buscar arquivo do ESF. Por favor, verifique se o plugin está atualizado!');
    }
}
