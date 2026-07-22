<?php


namespace ECidade\Educacao\Secretaria\BNCC\Resource;

use ECidade\Educacao\Secretaria\BNCC\Model\BnccOriginalEducacaoInfantil;

/**
 * Class BnccOriginalEducacaoInfantilResource
 * @package ECidade\Educacao\Secretaria\BNCC\Resource
 */
class BnccOriginalEducacaoInfantilResource
{
    /**
     * @param BnccOriginalEducacaoInfantil[] $habilidades
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
                    'faixas_etaria' => []
                ];
                $dados[$disciplina] = $std;
            }

            $faixaEtaria = md5($habilidade->getFaixaEtaria());
            $dados[$disciplina]->faixas_etaria[$faixaEtaria] = (object)[
                'nome' => $habilidade->getFaixaEtaria(),
            ];
        }

        $dados = array_values($dados);
        foreach ($dados as $disciplina) {
            $disciplina->faixas_etaria = array_values($disciplina->faixas_etaria);
        }
        return $dados;
    }

    /**
     * @param BnccOriginalEducacaoInfantil[] $habilidades
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
     * @param BnccOriginalEducacaoInfantil $habilidade
     * @return object
     */
    public static function toJson(BnccOriginalEducacaoInfantil $habilidade)
    {
        $obj =  (object) [
            "id" => $habilidade->getCodigo(),
            "disciplina" => $habilidade->getDisciplina(),
            "faixaEtaria" => $habilidade->getFaixaEtaria(),
            "codigo" => $habilidade->getCodigo(),
            "habilidade" => $habilidade->getHabilidade(),
            "habilidadeComentada" => null
        ];

        $habilidadeComentada = $habilidade->getHabilidadeComentada();
        if (!is_null($habilidadeComentada)) {
            $obj->habilidadeComentada = HabilidadeEducacaoInfantilResource::toJson($habilidadeComentada);
        }

        return $obj;
    }
}
