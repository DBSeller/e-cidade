<?php


namespace ECidade\Educacao\Secretaria\BNCC\Resource;

use ECidade\Educacao\Secretaria\BNCC\Model\HabilidadeEducacaoInfantil;
use ECidade\Educacao\Secretaria\Services\ParametrosGlobaisService;

/**
 * Class HabilidadeEducacaoInfantilResource
 * @package ECidade\Educacao\Secretaria\BNCC\Resource
 */
class HabilidadeEducacaoInfantilResource
{
    /**
     * @param HabilidadeEducacaoInfantil[] $habilidades
     * @return array
     */
    public static function toJsonTree(array $habilidades)
    {
        $configuracao = ParametrosGlobaisService::get();
        $dados = [];
        foreach ($habilidades as $habilidade) {
            $disciplina = md5($habilidade->getDisciplina());
            if (!array_key_exists($disciplina, $dados)) {
                $std = (object) [
                    'nome' => $habilidade->getDisciplina(),
                    'nivel_2' => []
                ];
                $dados[$disciplina] = $std;
            }

            $faixaEtaria = md5($habilidade->getFaixaEtaria());

            if (!array_key_exists($faixaEtaria, $dados[$disciplina]->nivel_2)) {
                $stdObj = (object) [
                    'nome' => $habilidade->getFaixaEtaria(),
                    'nivel_3' => []
                ];

                $dados[$disciplina]->nivel_2[$faixaEtaria] = $stdObj;
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

            $dados[$disciplina]->nivel_2[$faixaEtaria]->nivel_3[] = $dadosHabilidade;
        }

        $dados = array_values($dados);
        foreach ($dados as $disciplina) {
            $disciplina->nivel_2 = array_values($disciplina->nivel_2);
        }

        return $dados;
    }

    /**
     * @param HabilidadeEducacaoInfantil $habilidadeComentada
     * @return object
     */
    public static function toJson(HabilidadeEducacaoInfantil $habilidadeComentada)
    {
        $obj =  (object) [
            "id" => $habilidadeComentada->getCodigo(),
            "disciplina" => $habilidadeComentada->getDisciplina(),
            "faixaEtaria" => $habilidadeComentada->getFaixaEtaria(),
            "codigo" => $habilidadeComentada->getCodigo(),
            "habilidade" => $habilidadeComentada->getHabilidade(),
            "habilidadeReferencial" => []
        ];

        $habilidadesReferencial = $habilidadeComentada->getHabilidadesReferencialCurricular();
        if (!empty($habilidadesReferencial)) {
            $obj->habilidadeReferencial = HabilidadeReferencialCurricularResource::toArray($habilidadesReferencial);
        }

        return $obj;
    }
}
