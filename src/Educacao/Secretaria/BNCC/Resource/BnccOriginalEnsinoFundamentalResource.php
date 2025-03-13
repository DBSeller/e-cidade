<?php


namespace ECidade\Educacao\Secretaria\BNCC\Resource;

use ECidade\Educacao\Secretaria\BNCC\Model\BnccOriginalEnsinoFundamental;

/**
 * Class BnccOriginalEsinoFundamentalResource
 * @package ECidade\Educacao\Secretaria\BNCC\Resource
 */
class BnccOriginalEnsinoFundamentalResource
{
    /**
     * @param BnccOriginalEnsinoFundamental[] $habilidades
     * @return array
     */
    public static function toArrayFiltros(array $habilidades)
    {
        $dados = [];
        foreach ($habilidades as $habilidade) {
            $disciplina = md5($habilidade->getDisciplina());
            if (!array_key_exists($disciplina, $dados)) {
                $std = (object)[
                    'nome' => $habilidade->getDisciplina(),
                    'unidades_tematicas' => []
                ];
                $dados[$disciplina] = $std;
            }


            $unidadeTematica = md5($habilidade->getUnidadeTematica());
            if (!array_key_exists($unidadeTematica, $dados[$disciplina]->unidades_tematicas)) {
                $dados[$disciplina]->unidades_tematicas[$unidadeTematica] = (object)[
                    'nome' => $habilidade->getUnidadeTematica(),
                    'objetos' => []
                ];
            }

            $dados[$disciplina]->unidades_tematicas[$unidadeTematica]->objetos[] = (object)[
                'nome' => $habilidade->getObjetoConhecimento(),
            ];
        }

        $dados = array_values($dados);
        foreach ($dados as $disciplina) {
            $disciplina->unidades_tematicas = array_values($disciplina->unidades_tematicas);
        }
        return $dados;
    }

    /**
     * @param BnccOriginalEnsinoFundamental[] $habilidades
     * @return array
     */
    public static function toArray(array $habilidades)
    {
        $dados = [];
        foreach ($habilidades as $habilidade) {
            $dados[] = self::toJson($habilidade);
        }

        return $dados;
    }

    /**
     * @param BnccOriginalEnsinoFundamental $habilidade
     * @return object
     */
    private static function toJson(BnccOriginalEnsinoFundamental $habilidade)
    {
        $obj = (object)[
            "id" => $habilidade->getId(),
            "disciplina" => $habilidade->getDisciplina(),
            "etapas" => $habilidade->getEtapa(),
            "codigo" => $habilidade->getCodigo(),
            "unidadeTematica" => $habilidade->getUnidadeTematica(),
            "objetoConhecimento" => $habilidade->getObjetoConhecimento(),
            "habilidade" => $habilidade->getHabilidade(),
            "habilidadeComentada" => null,
        ];

        $habilidadeComentada = $habilidade->getHabilidadeComentada();

        if (!is_null($habilidadeComentada)) {
            $obj->habilidadeComentada = HabilidadeEnsinoFundamentalResource::toJson($habilidadeComentada);
        }
        return $obj;
    }
}
