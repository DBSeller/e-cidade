<?php

namespace App\Domain\Financeiro\Orcamento\Builder;

use App\Domain\Financeiro\Orcamento\Mapper\AcompanhamentoReceitaMapper;
use ECidade\Financeiro\Contabilidade\PlanoDeContas\EstruturalReceita;
use stdClass;

class AcompanhamentoReceitaBuilder
{
    /**
     * @var AcompanhamentoReceitaMapper
     */
    private $mapper;

    public function __construct()
    {
        $this->mapper = new AcompanhamentoReceitaMapper();
    }


    private function buildDefaultDados(stdClass $estimativa, EstruturalReceita $estrutural, $temDesdobramento)
    {
        $this->mapper->reduzido = $estimativa->reduzido;
        $this->mapper->fonte = $estimativa->fonte;
        $this->mapper->ano = $estimativa->ano;
        $this->mapper->natureza = $estimativa->natureza;
        $this->mapper->naturezaMascara = $estrutural->getEstruturalComMascara();
        $this->mapper->nivel = $estrutural->getNivel();
        $this->mapper->descricao = $estimativa->descricao;
        $this->mapper->cp = $estimativa->cp;
        $this->mapper->instituicao = $estimativa->instituicao;
        $this->mapper->orgao = $estimativa->orgao;
        $this->mapper->unidade = $estimativa->unidade;
        $this->mapper->esfera = $estimativa->esfera;
        $this->mapper->fonteRecurso = $estimativa->fonte_recurso;
        $this->mapper->idRecursoLancamento = $estimativa->recurso_lancamento;
        $this->mapper->complementoLancamento = $estimativa->complemento_lancamento;
        $this->mapper->valorInicial = $estimativa->valor_inicial;
        $this->mapper->previsaoAdicionalAcumulado = $estimativa->previsao_adicional_acumulado;
        $this->mapper->previsaoAtualizada = $estimativa->previsao_atualizada;
        $this->mapper->arrecadadoAnterior = $estimativa->arrecadado_anterior;
        $this->mapper->arrecadadoPeriodo = $estimativa->arrecadado_periodo;
        $this->mapper->valor_a_Arrecadar = $estimativa->valor_a_arrecadar;
        $this->mapper->arrecadadoAcumulado = $estimativa->arrecadado_acumulado;
        $this->mapper->previsaoAdicional = $estimativa->previsao_adicional;
        $this->mapper->ordem = $estimativa->ordem;
        $this->mapper->classe = $estimativa->classe;
        $this->mapper->resto = $estimativa->resto;
        $this->mapper->descricaoFonte = $estimativa->descricao_fonte;
        $this->mapper->descricaoOrgao = $estimativa->descricao_orgao;
        $this->mapper->descricaoUnidade = $estimativa->descricao_unidade;
        $this->mapper->caracteristicaPeculiar = $estimativa->caracteristica_peculiar;
        $this->mapper->nomeInstituicao = $estimativa->nome_instituicao;
        $this->mapper->descricaoRecurso = $estimativa->descricao_recurso;
        $this->mapper->descricaoComplemento = $estimativa->complemento;
        $this->mapper->identificadorResultado = $estimativa->identificador_resultado;
        $this->mapper->esferaOrcamentaria = $estimativa->esfera_orcamentaria;
        $this->mapper->temDesdobramento = $temDesdobramento;
    }

    public function buildAnalitico(stdClass $estimativa, EstruturalReceita $estrutural, $temDesdobramento = false)
    {
        $this->buildDefaultDados($estimativa, $estrutural, $temDesdobramento);
        $this->mapper->idCronograma = $estimativa->id_cronograma;
        $this->mapper->exercicio = $estimativa->exercicio;
        $this->mapper->baseCalculo = $estimativa->base_calculo;
        $this->mapper->valorBase = $estimativa->valorBase;
        $this->mapper->janeiro = $estimativa->janeiro;
        $this->mapper->fevereiro = $estimativa->fevereiro;
        $this->mapper->marco = $estimativa->marco;
        $this->mapper->abril = $estimativa->abril;
        $this->mapper->maio = $estimativa->maio;
        $this->mapper->junho = $estimativa->junho;
        $this->mapper->julho = $estimativa->julho;
        $this->mapper->agosto = $estimativa->agosto;
        $this->mapper->setembro = $estimativa->setembro;
        $this->mapper->outubro = $estimativa->outubro;
        $this->mapper->novembro = $estimativa->novembro;
        $this->mapper->dezembro = $estimativa->dezembro;

        return (object) $this->mapper->toArray();
    }


    /**
     * @param EstruturalReceita $estrutural
     * @param $descricao
     * @return object
     */
    public function buildSintetico(EstruturalReceita $estrutural, $descricao)
    {
        $this->mapper->natureza = $estrutural->getEstrutural();
        $this->mapper->naturezaMascara = $estrutural->getEstruturalComMascara();
        $this->mapper->nivel = $estrutural->getNivel();
        $this->mapper->descricaoFonte = $descricao;
        return (object) $this->mapper->toArray();
    }
}
