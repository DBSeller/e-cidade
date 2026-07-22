<?php


namespace ECidade\Educacao\Escola\Resource;

use ECidade\Educacao\Escola\Model\BaseCurricular;

/**
 * Class BaseCurricularResource
 * @package ECidade\Educacao\Escola\Resource
 */
class BaseCurricularResource
{
    /**
     * @param BaseCurricular[] $bases
     * @return array
     */
    public static function toArray(array $bases)
    {
        $data = [];

        foreach ($bases as $base) {
            $data[] = (object)[
                "codigo" => $base->getCodigo(),
                "descricao" => $base->getDescricao(),
                "turno" => $base->getTurno(),
                "calculoFrequencia" => $base->getCalculoFrequencia(),
                "controleFrequencia" => $base->getControleFrequencia(),
                "observacao" => $base->getObservacao(),
                "concluiCurso" => $base->getConcluiCurso(),
                "ativo" => $base->getAtivo(),
            ];
        }

        return $data;
    }
}
