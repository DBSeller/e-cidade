<?php


namespace ECidade\Educacao\Secretaria\BNCC\Resource;

use ECidade\Educacao\Secretaria\BNCC\Model\HabilidadesEnsinoFundamental;
use ECidade\Educacao\Secretaria\Services\ParametrosGlobaisService;

/**
 * Class HabilidadeEnsinoFundamentalResource
 * @package ECidade\Educacao\Secretaria\BNCC\Resource
 */
class HabilidadeEnsinoFundamentalResource
{

    /**
     * @param HabilidadesEnsinoFundamental[] $habilidades
     * @return array
     */
    public static function toJsonTree(array $habilidades)
    {
        $configuracao = ParametrosGlobaisService::get();

        $dados = [];
        foreach ($habilidades as $habilidade) {
            $unidadeTematica = md5($habilidade->getUnidadeTematica());
            if (!array_key_exists($unidadeTematica, $dados)) {
                $std = (object)[
                    'nome' => $habilidade->getUnidadeTematica(),
                    'nivel_2' => []
                ];
                $dados[$unidadeTematica] = $std;
            }

            $objetoConhecimento = md5($habilidade->getObjetoConhecimento());

            if (!array_key_exists($objetoConhecimento, $dados[$unidadeTematica]->nivel_2)) {
                $stdObj = (object)[
                    'nome' => $habilidade->getObjetoConhecimento(),
                    'nivel_3' => []
                ];

                $dados[$unidadeTematica]->nivel_2[$objetoConhecimento] = $stdObj;
            }

            $dadosHabilidade = (object)[
                'codigo' => $habilidade->getCodigo(),
                'nome' => $habilidade->getHabilidade()
            ];

            if ($configuracao->isReferencialCurricularEstadual()) {
                $dadosHabilidade->nivel_4 = HabilidadeReferencialCurricularResource::toArray(
                    $habilidade->getHabilidadesReferencialCurricular()
                );
            }

            $dados[$unidadeTematica]->nivel_2[$objetoConhecimento]->nivel_3[] = $dadosHabilidade;
        }

        $dados = array_values($dados);
        foreach ($dados as $unidadeTematica) {
            $unidadeTematica->nivel_2 = array_values($unidadeTematica->nivel_2);
        }

        return $dados;
    }

    /**
     * @param HabilidadesEnsinoFundamental[] $habilidades
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
     * @param HabilidadesEnsinoFundamental $habilidadeComentada
     * @return object
     */
    public static function toJson(HabilidadesEnsinoFundamental $habilidadeComentada)
    {
        $obj = (object)[
            "id" => $habilidadeComentada->getId(),
            "disciplina" => $habilidadeComentada->getDisciplina(),
            "etapa" => $habilidadeComentada->getEtapa(),
            "codigo" => $habilidadeComentada->getCodigo(),
            "unidadeTematica" => $habilidadeComentada->getUnidadeTematica(),
            "objetoConhecimento" => $habilidadeComentada->getObjetoConhecimento(),
            "habilidade" => $habilidadeComentada->getHabilidade(),
            "habilidadeComentada" => null
        ];
       
        $habilidadesReferencial = $habilidadeComentada->getHabilidadesReferencialCurricular();
        
        if (!empty($habilidadesReferencial)) {
            $obj->habilidadeComentada = HabilidadeReferencialCurricularResource::toArray($habilidadesReferencial);
        }
        return $obj;
    }
}
