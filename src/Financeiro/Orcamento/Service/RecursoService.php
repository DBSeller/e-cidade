<?php


namespace ECidade\Financeiro\Orcamento\Service;

use DotacaoRepository;
use RecursoRepository;

class RecursoService
{

    /**
     * @param $codigoDotacao
     * @param $ano
     * @param $complemento
     * @return \ECidade\Financeiro\Orcamento\Recurso\Recurso
     * @throws \Exception
     */
    public static function identificaRecursoComplemento($codigoDotacao, $ano, $complemento)
    {
        $dotacao = DotacaoRepository::getDotacaoPorCodigoAno($codigoDotacao, $ano);
        $recursoDotacao = RecursoRepository::getRecursoPorCodigo($dotacao->getRecurso());
        $recurso = RecursoRepository::getRecursoPorCodigoRecursoAndComplemento(
            $recursoDotacao->getRecurso(),
            $complemento
        );
        return $recurso;
    }
}
