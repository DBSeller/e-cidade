<?php


namespace ECidade\RecursosHumanos\RH\Assentamento\Repository;

class AssentamentoSubstituicao
{
    public static function salvar($assentamento, $assentamentoSubstituicao)
    {
        if (empty($assentamento->getServidor()->getMatricula())) {
            throw new \BusinessException("É necessário informar o servidor a substituir.");
        }

        $assentamentoSubstituicao->persist();

        if ($assentamentoSubstituicao->persist() !== true) {
            throw new \BusinessException($assentamentoSubstituicao->persist());
        }
    }
}
