<?php


namespace ECidade\Educacao\Escola\Resource;

use ECidade\Educacao\Escola\Model\CensoDisciplina;
use ECidade\Educacao\Escola\Model\ComponenteCurricular;

/**
 * Class ComponenteCurricularResource
 * @package ECidade\Educacao\Escola\Resource
 */
class ComponenteCurricularResource
{
    /**
     * @param ComponenteCurricular[] $disciplinas
     * @return array
     */
    public static function toArray(array $disciplinas)
    {
        $data = array();

        foreach ($disciplinas as $disciplina) {
            $std = (object)[
                "codigo" => $disciplina->getCodigo(),
                "nome" => $disciplina->getNome(),
                "sigla" => $disciplina->getSigla(),
                "nome_completo" => $disciplina->getNomeCompleto(),
                "corhtml" => $disciplina->getCorHtml(),
                "area_conhecimento" => null,
                "censo_disciplinas" => [],
            ];

            if (!is_null($disciplina->getAreaConhecimento())) {
                $std->area_conhecimento = (object)[
                    'codigo' => $disciplina->getAreaConhecimento()->getCodigo(),
                    'descricao' => $disciplina->getAreaConhecimento()->getDescricao()
                ];
            }

            $censoDisciplinas = $disciplina->getCensoDisciplina();
            if (!!count($censoDisciplinas)) {
                $std->censo_disciplinas = array_map(function (CensoDisciplina $censoDisciplina) {
                    return (object)[
                        'codigo' => $censoDisciplina->getCodigo(),
                        'descricao' => $censoDisciplina->getDescricao(),
                    ];
                }, $censoDisciplinas);
            }

            $data[] = $std;
        }
        return $data;
    }
}
