<?php

namespace ECidade\Tributario\Issqn\Inscricao\Atividades\Filter;

use \DBDate;

/**
 * Filtro para consulta de processos de processo eletrônico
 */
class ListagemAtividades
{
    private $numeroProcesso;
    private $anoProcesso;
    private $dataInicio;
    private $dataFim;
    private $codigoInstituicao;
    private $codigoDepartamento;
    private $codigoAtividade = [];
    private $estruturalCnae;

    public function setNumeroProcesso($numeroProcesso)
    {
        $this->numeroProcesso = $numeroProcesso;
    }

    public function getNumeroProcesso()
    {
        return $this->numeroProcesso;
    }

    public function setAnoProcesso($anoProcesso)
    {
        $this->anoProcesso = $anoProcesso;
    }

    public function getAnoProcesso()
    {
        return $this->anoProcesso;
    }

    public function setDataInicio(DBDate $dataInicio)
    {
        $this->dataInicio = $dataInicio;
    }

    public function getDataInicio()
    {
        return $this->dataInicio;
    }

    public function setDataFim(DBDate $dataFim)
    {
        $this->dataFim = $dataFim;
    }

    public function getDataFim()
    {
        return $this->dataFim;
    }

    public function setCodigoInstituicao($codigoInstituicao)
    {
        $this->codigoInstituicao = $codigoInstituicao;
    }

    public function getCodigoInstituicao()
    {
        return $this->codigoInstituicao;
    }

    public function setCodigoDepartamento($codigoDepartamento)
    {
        $this->codigoDepartamento = $codigoDepartamento;
    }

    public function getCodigoDepartamento()
    {
        return $this->codigoDepartamento;
    }

    /**
     * @return array
     */
    public function getCodigoAtividade()
    {
        return $this->codigoAtividade;
    }

    /**
     * @param array|int $codigoAtividade
     * @return ListagemAtividades
     */
    public function setCodigoAtividade($codigoAtividade)
    {
        if (!is_array($codigoAtividade)) {
            $this->codigoAtividade[] = $codigoAtividade;
        } else {
            $this->codigoAtividade = $codigoAtividade;
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getEstruturalCnae()
    {
        return $this->estruturalCnae;
    }

    /**
     * @param string $estruturalCnae
     */
    public function setEstruturalCnae($estruturalCnae)
    {
        $this->estruturalCnae = $estruturalCnae;
    }

    public function ajustaFiltros()
    {
        $aFiltros = [];

        if (!empty($this->codigoAtividade)) {
            $sCodigosAtividades = implode(", ", $this->codigoAtividade);
            $aFiltros[] = "q03_ativ IN ({$sCodigosAtividades})";
        }

        if (!empty($this->estruturalCnae)) {
            $aFiltros[] = "q71_estrutural ilike '%{$this->estruturalCnae}'";
        }

        return $aFiltros;
    }
}
