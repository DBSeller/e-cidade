<?php

namespace ECidade\Configuracao\RelatorioLegal\Modelo;

use ECidade\Configuracao\RelatorioLegal\Registry\RelatorioRegistry;

class Relatorio
{
    /**
     * @var int
     */
    private $sequencial;
    /**
     * @var string
     */
    private $descricao;
    /**
     * @var int
     */
    private $grupo;
    /**
     * @var string
     */
    private $notaPadrao;

    /**
     * @var Linha[]
     */
    private $linhas;

    /**
     * @var RelatorioPeriodo[]
     */
    private $periodos;
    /**
     * @var InformacaoComplementarLancamento[]
     */
    private $informacoesComplementaresLancamentos = array();

    public static function fromState(array $state)
    {
        $self = new self();

        if (array_key_exists('o42_codparrel', $state)) {
            $self->setSequencial($state['o42_codparrel']);
        }

        if (array_key_exists('o42_descrrel', $state)) {
            $self->setDescricao($state['o42_descrrel']);
        }

        if (array_key_exists('o42_orcparamrelgrupo', $state)) {
            $self->setGrupo($state['o42_orcparamrelgrupo']);
        }

        if (array_key_exists('o42_notapadrao', $state)) {
            $self->setNotaPadrao($state['o42_notapadrao']);
        }

        RelatorioRegistry::set($self);

        return $self;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $dados = array(
            'sequencial' => $this->getSequencial(),
            'descricao' => $this->getDescricao(),
            'grupo' => $this->getGrupo(),
            'notaPadrao' => $this->getNotaPadrao(),
            'linhas' => array(),
            'periodos' => array(),
            'informacoesComplementaresLancamentos' => array()
        );

        if ($this->informacoesComplementaresLancamentos) {
            $dados['informacoesComplementaresLancamentos'] = array_map(function (
                InformacaoComplementarLancamento $informacaoComplementarLancamento
            ) {
                return $informacaoComplementarLancamento->toArray();
            }, $this->informacoesComplementaresLancamentos);
        }

        if ($this->linhas) {
            foreach ($this->linhas as $linha) {
                $dados['linhas'][] = $linha->toArray();
            }
        }

        if ($this->periodos) {
            foreach ($this->periodos as $periodo) {
                $dados['periodos'][] = $periodo->toArray();
            }
        }
        return $dados;
    }

    /**
     * @return int
     */
    public function getSequencial()
    {
        return (int)$this->sequencial;
    }

    /**
     * @param int $sequencial
     */
    public function setSequencial($sequencial)
    {
        $this->sequencial = (int)$sequencial;
    }

    /**
     * @return string
     */
    public function getDescricao()
    {
        return (string)$this->descricao;
    }

    /**
     * @param string $descricao
     */
    public function setDescricao($descricao)
    {
        $this->descricao = (string)$descricao;
    }

    /**
     * @return int
     */
    public function getGrupo()
    {
        return (int)$this->grupo;
    }

    /**
     * @param int $grupo
     */
    public function setGrupo($grupo)
    {
        $this->grupo = (int)$grupo;
    }

    /**
     * @return string
     */
    public function getNotaPadrao()
    {
        return (string)$this->notaPadrao;
    }

    /**
     * @param string $notaPadrao
     */
    public function setNotaPadrao($notaPadrao)
    {
        $this->notaPadrao = (string)$notaPadrao;
    }

    public function setLinhas($linhas)
    {
        $this->linhas = $linhas;
    }

    /**
     * @return RelatorioPeriodo[]
     */
    public function getPeriodos()
    {
        return $this->periodos;
    }

    /**
     * @param RelatorioPeriodo[] $periodos
     * @return Relatorio
     */
    public function setPeriodos($periodos)
    {
        $this->periodos = $periodos;
        return $this;
    }

    /**
     * @return InformacaoComplementarLancamento[]
     */
    public function getInformacoesComplementaresLancamentos()
    {
        return $this->informacoesComplementaresLancamentos;
    }

    /**
     * @param InformacaoComplementarLancamento[] $informacoesComplementaresLancamentos
     */
    public function setInformacoesComplementaresLancamentos(
        array $informacoesComplementaresLancamentos
    ) {
        $this->informacoesComplementaresLancamentos = $informacoesComplementaresLancamentos;
    }
}
