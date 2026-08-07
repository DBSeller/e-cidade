<?php

namespace App\Domain\Financeiro\Orcamento\Mapper;

class ReceitaMapper
{
    public $reduzido;
    public $fonte;
    public $ano;
    public $natureza;
    public $naturezaMascara;
    public $nivel;
    public $descricao;
    public $cp;
    public $instituicao;
    public $orgao;
    public $unidade;
    public $esfera;
    public $fonteRecurso;
    public $idRecursoLancamento;
    public $complementoLancamento;
    public $valorInicial = 0;
    public $previsaoAdicionalAcumulado = 0;
    public $previsaoAtualizada = 0;
    public $arrecadadoAnterior = 0;
    public $arrecadadoPeriodo = 0;
    public $valor_a_Arrecadar = 0;
    public $arrecadadoAcumulado = 0;
    public $previsaoAdicional = 0;
    public $ordem;
    public $classe;
    public $resto;
    public $descricaoFonte;
    public $descricaoOrgao;
    public $descricaoUnidade;
    public $caracteristicaPeculiar;
    public $nomeInstituicao;
    public $descricaoRecurso;
    public $descricaoComplemento;
    public $identificadorResultado;
    public $esferaOrcamentaria;
    public $temDesdobramento = false;
    public $contasDesdobramento = [];
    public $fontesPai = [];

    public function toArray()
    {
        return [
            "reduzido" => $this->reduzido,
            "fonte" => $this->fonte,
            "ano" => $this->ano,
            "natureza" => $this->natureza,
            "naturezaMascara" => $this->naturezaMascara,
            "nivel" => $this->nivel,
            "descricao" => $this->descricao,
            "cp" => $this->cp,
            "instituicao" => $this->instituicao,
            "orgao" => $this->orgao,
            "unidade" => $this->unidade,
            "esfera" => $this->esfera,
            "fonteRecurso" => $this->fonteRecurso,
            "idRecursoLancamento" => $this->idRecursoLancamento,
            "complementoLancamento" => $this->complementoLancamento,
            "valorInicial" => $this->valorInicial,
            "previsaoAdicionalAcumulado" => $this->previsaoAdicionalAcumulado,
            "previsaoAtualizada" => $this->previsaoAtualizada,
            "arrecadadoAnterior" => $this->arrecadadoAnterior,
            "arrecadadoPeriodo" => $this->arrecadadoPeriodo,
            "valor_a_arrecadar" => $this->valor_a_Arrecadar,
            "arrecadadoAcumulado" => $this->arrecadadoAcumulado,
            "previsaoAdicional" => $this->previsaoAdicional,
            "ordem" => $this->ordem,
            "classe" => $this->classe,
            "resto" => $this->resto,
            "descricaoFonte" => $this->descricaoFonte,
            "descricaoOrgao" => $this->descricaoOrgao,
            "descricaoUnidade" => $this->descricaoUnidade,
            "caracteristicaPeculiar" => $this->caracteristicaPeculiar,
            "nomeInstituicao" => $this->nomeInstituicao,
            "descricaoRecurso" => $this->descricaoRecurso,
            "descricaoComplemento" => $this->descricaoComplemento,
            "identificadorResultado" => $this->identificadorResultado,
            "esferaOrcamentaria" => $this->esferaOrcamentaria,
            "temDesdobramento" => $this->temDesdobramento,
            "contasDesdobramento" => $this->contasDesdobramento,
            "fontesPai" => $this->fontesPai,
        ];
    }
}
