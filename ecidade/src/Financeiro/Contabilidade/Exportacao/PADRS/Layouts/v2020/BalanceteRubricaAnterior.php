<?php

namespace ECidade\Financeiro\Contabilidade\Exportacao\PADRS\Layouts\v2020;

use ECidade\Core\Mappers\ParseArray;
use ECidade\Financeiro\Contabilidade\Exportacao\PADRS\Layouts\LayoutPad;

/**
 * Class BalanceteRubricaAnterior
 * @package ECidade\Financeiro\Contabilidade\Exportacao\PADRS\v2020\Layout
 */
class BalanceteRubricaAnterior extends ParseArray implements LayoutPad
{
    protected $dePara = [
        "Código do Órgão" => "orgao",
        "Código da Unidade Orçamentária" => "unidade",
        "Código da Função" => "funcao",
        "Código da Subfunção" => "subFuncao",
        "Código do Programa" => "programa",
        "Campo Obsoleto" => "campoObsoleto",
        "Código do Projeto/Atividade/Op. Especial" => "projetoAtividade",
        "Código da Rubrica de Despesa - SG" => "elemento",
        "Código do Recurso Vinculado" => "fonteRecurso",
        "Valor Empenhado - 1° bimestre Período Anterior" => "primeiroBiEmpenhado",
        "Valor Empenhado - 2° bimestre Período Anterior" => "segundoBiEmpenhado",
        "Valor Empenhado - 3° bimestre Período Anterior" => "terceiroBiEmpenhado",
        "Valor Empenhado - 4° bimestre Período Anterior" => "quartoBiEmpenhado",
        "Valor Empenhado - 5° bimestre Período Anterior" => "quintoBiEmpenhado",
        "Valor Empenhado - 6° bimestre Período Anterior" => "sextoBiEmpenhado",
        "Valor Liquidado - 1° bimestre Período Anterior" => "primeiroBiLiquidado",
        "Valor Liquidado - 2° bimestre Período Anterior" => "segundoBiLiquidado",
        "Valor Liquidado - 3° bimestre Período Anterior" => "terceiroBiLiquidado",
        "Valor Liquidado - 4° bimestre Período Anterior" => "quartoBiLiquidado",
        "Valor Liquidado - 5° bimestre Período Anterior" => "quintoBiLiquidado",
        "Valor Liquidado - 6° bimestre Período Anterior" => "sextoBiLiquidado",
        "Valor Pago - 1° bimestre Período Anterior" => "primeiroBiPago",
        "Valor Pago - 2° bimestre Período Anterior" => "segundoBiPago",
        "Valor Pago - 3° bimestre Período Anterior" => "terceiroBiPago",
        "Valor Pago - 4° bimestre Período Anterior" => "quartoBiPago",
        "Valor Pago - 5° bimestre Período Anterior" => "quintoBiPago",
        "Valor Pago - 6° bimestre Período Anterior" => "sextoBiPago",
        "Complemento do Recurso Vinculado" => "complemento",
    ];

    protected $orgao;
    protected $unidade;
    protected $funcao;
    protected $subFuncao;
    protected $programa;
    protected $projetoAtividade;
    protected $codigoElemento;
    protected $elemento;
    protected $fonteRecurso;
    protected $complemento;
    protected $primeiroBimestreEmpenhado;
    protected $primeiroBimestreLiquidado;
    protected $primeiroBimestrePagamento;
    protected $segundoBimestreEmpenhado;
    protected $segundoBimestreLiquidado;
    protected $segundoBimestrePagamento;
    protected $terceiroBimestreEmpenhado;
    protected $terceiroBimestreLiquidado;
    protected $terceiroBimestrePagamento;
    protected $quartoBimestreEmpenhado;
    protected $quartoBimestreLiquidado;
    protected $quartoBimestrePagamento;
    protected $quintoBimestreEmpenhado;
    protected $quintoBimestreLiquidado;
    protected $quintoBimestrePagamento;
    protected $sextoBimestreEmpenhado;
    protected $sextoBimestreLiquidado;
    protected $sextoBimestrePagamento;

    /**
     * @return string
     */
    public function getOrgao()
    {
        return $this->orgao;
    }

    /**
     * @param string $orgao
     * @return BalanceteRubricaAnterior
     */
    public function setOrgao($orgao)
    {
        $this->orgao = $orgao;
        return $this;
    }

    /**
     * @return string
     */
    public function getUnidade()
    {
        return $this->unidade;
    }

    /**
     * @param string $unidade
     * @return BalanceteRubricaAnterior
     */
    public function setUnidade($unidade)
    {
        $this->unidade = $unidade;
        return $this;
    }

    /**
     * @return string
     */
    public function getFuncao()
    {
        return $this->funcao;
    }

    /**
     * @param string $funcao
     * @return BalanceteRubricaAnterior
     */
    public function setFuncao($funcao)
    {
        $this->funcao = $funcao;
        return $this;
    }

    /**
     * @return string
     */
    public function getSubFuncao()
    {
        return $this->subFuncao;
    }

    /**
     * @param string $subFuncao
     * @return BalanceteRubricaAnterior
     */
    public function setSubFuncao($subFuncao)
    {
        $this->subFuncao = $subFuncao;
        return $this;
    }

    /**
     * @return string
     */
    public function getPrograma()
    {
        return $this->programa;
    }

    /**
     * @param string $programa
     * @return BalanceteRubricaAnterior
     */
    public function setPrograma($programa)
    {
        $this->programa = $programa;
        return $this;
    }

    /**
     * @return string
     */
    public function getProjetoAtividade()
    {
        return $this->projetoAtividade;
    }

    /**
     * @param string $projetoAtividade
     * @return BalanceteRubricaAnterior
     */
    public function setProjetoAtividade($projetoAtividade)
    {
        $this->projetoAtividade = $projetoAtividade;
        return $this;
    }

    /**
     * @return string
     */
    public function getCodigoElemento()
    {
        return $this->codigoElemento;
    }

    /**
     * @param string $codigoElemento
     * @return BalanceteRubricaAnterior
     */
    public function setCodigoElemento($codigoElemento)
    {
        $this->codigoElemento = $codigoElemento;
        return $this;
    }

    /**
     * @return string
     */
    public function getElemento()
    {
        return $this->elemento;
    }

    /**
     * @param string $elemento
     * @return BalanceteRubricaAnterior
     */
    public function setElemento($elemento)
    {
        $this->elemento = $elemento;
        return $this;
    }

    /**
     * @return string
     */
    public function getFonteRecurso()
    {
        return $this->fonteRecurso;
    }

    /**
     * @param string $fonteRecurso
     * @return BalanceteRubricaAnterior
     */
    public function setFonteRecurso($fonteRecurso)
    {
        $this->fonteRecurso = $fonteRecurso;
        return $this;
    }

    /**
     * @return string
     */
    public function getComplemento()
    {
        return $this->complemento;
    }

    /**
     * @param string $complemento
     * @return BalanceteRubricaAnterior
     */
    public function setComplemento($complemento)
    {
        $this->complemento = $complemento;
        return $this;
    }

    /**
     * @return string
     */
    public function getPrimeiroBimestreEmpenhado()
    {
        return $this->primeiroBimestreEmpenhado;
    }

    /**
     * @param string $primeiroBimestreEmpenhado
     * @return BalanceteRubricaAnterior
     */
    public function setPrimeiroBimestreEmpenhado($primeiroBimestreEmpenhado)
    {
        $this->primeiroBimestreEmpenhado = $primeiroBimestreEmpenhado;
        return $this;
    }

    /**
     * @return string
     */
    public function getPrimeiroBimestreLiquidado()
    {
        return $this->primeiroBimestreLiquidado;
    }

    /**
     * @param string $primeiroBimestreLiquidado
     * @return BalanceteRubricaAnterior
     */
    public function setPrimeiroBimestreLiquidado($primeiroBimestreLiquidado)
    {
        $this->primeiroBimestreLiquidado = $primeiroBimestreLiquidado;
        return $this;
    }

    /**
     * @return string
     */
    public function getPrimeiroBimestrePagamento()
    {
        return $this->primeiroBimestrePagamento;
    }

    /**
     * @param string $primeiroBimestrePagamento
     * @return BalanceteRubricaAnterior
     */
    public function setPrimeiroBimestrePagamento($primeiroBimestrePagamento)
    {
        $this->primeiroBimestrePagamento = $primeiroBimestrePagamento;
        return $this;
    }

    /**
     * @return string
     */
    public function getSegundoBimestreEmpenhado()
    {
        return $this->segundoBimestreEmpenhado;
    }

    /**
     * @param string $segundoBimestreEmpenhado
     * @return BalanceteRubricaAnterior
     */
    public function setSegundoBimestreEmpenhado($segundoBimestreEmpenhado)
    {
        $this->segundoBimestreEmpenhado = $segundoBimestreEmpenhado;
        return $this;
    }

    /**
     * @return string
     */
    public function getSegundoBimestreLiquidado()
    {
        return $this->segundoBimestreLiquidado;
    }

    /**
     * @param string $segundoBimestreLiquidado
     * @return BalanceteRubricaAnterior
     */
    public function setSegundoBimestreLiquidado($segundoBimestreLiquidado)
    {
        $this->segundoBimestreLiquidado = $segundoBimestreLiquidado;
        return $this;
    }

    /**
     * @return string
     */
    public function getSegundoBimestrePagamento()
    {
        return $this->segundoBimestrePagamento;
    }

    /**
     * @param string $segundoBimestrePagamento
     * @return BalanceteRubricaAnterior
     */
    public function setSegundoBimestrePagamento($segundoBimestrePagamento)
    {
        $this->segundoBimestrePagamento = $segundoBimestrePagamento;
        return $this;
    }

    /**
     * @return string
     */
    public function getTerceiroBimestreEmpenhado()
    {
        return $this->terceiroBimestreEmpenhado;
    }

    /**
     * @param string $terceiroBimestreEmpenhado
     * @return BalanceteRubricaAnterior
     */
    public function setTerceiroBimestreEmpenhado($terceiroBimestreEmpenhado)
    {
        $this->terceiroBimestreEmpenhado = $terceiroBimestreEmpenhado;
        return $this;
    }

    /**
     * @return string
     */
    public function getTerceiroBimestreLiquidado()
    {
        return $this->terceiroBimestreLiquidado;
    }

    /**
     * @param string $terceiroBimestreLiquidado
     * @return BalanceteRubricaAnterior
     */
    public function setTerceiroBimestreLiquidado($terceiroBimestreLiquidado)
    {
        $this->terceiroBimestreLiquidado = $terceiroBimestreLiquidado;
        return $this;
    }

    /**
     * @return string
     */
    public function getTerceiroBimestrePagamento()
    {
        return $this->terceiroBimestrePagamento;
    }

    /**
     * @param string $terceiroBimestrePagamento
     * @return BalanceteRubricaAnterior
     */
    public function setTerceiroBimestrePagamento($terceiroBimestrePagamento)
    {
        $this->terceiroBimestrePagamento = $terceiroBimestrePagamento;
        return $this;
    }

    /**
     * @return string
     */
    public function getQuartoBimestreEmpenhado()
    {
        return $this->quartoBimestreEmpenhado;
    }

    /**
     * @param string $quartoBimestreEmpenhado
     * @return BalanceteRubricaAnterior
     */
    public function setQuartoBimestreEmpenhado($quartoBimestreEmpenhado)
    {
        $this->quartoBimestreEmpenhado = $quartoBimestreEmpenhado;
        return $this;
    }

    /**
     * @return string
     */
    public function getQuartoBimestreLiquidado()
    {
        return $this->quartoBimestreLiquidado;
    }

    /**
     * @param string $quartoBimestreLiquidado
     * @return BalanceteRubricaAnterior
     */
    public function setQuartoBimestreLiquidado($quartoBimestreLiquidado)
    {
        $this->quartoBimestreLiquidado = $quartoBimestreLiquidado;
        return $this;
    }

    /**
     * @return string
     */
    public function getQuartoBimestrePagamento()
    {
        return $this->quartoBimestrePagamento;
    }

    /**
     * @param string $quartoBimestrePagamento
     * @return BalanceteRubricaAnterior
     */
    public function setQuartoBimestrePagamento($quartoBimestrePagamento)
    {
        $this->quartoBimestrePagamento = $quartoBimestrePagamento;
        return $this;
    }

    /**
     * @return string
     */
    public function getQuintoBimestreEmpenhado()
    {
        return $this->quintoBimestreEmpenhado;
    }

    /**
     * @param string $quintoBimestreEmpenhado
     * @return BalanceteRubricaAnterior
     */
    public function setQuintoBimestreEmpenhado($quintoBimestreEmpenhado)
    {
        $this->quintoBimestreEmpenhado = $quintoBimestreEmpenhado;
        return $this;
    }

    /**
     * @return string
     */
    public function getQuintoBimestreLiquidado()
    {
        return $this->quintoBimestreLiquidado;
    }

    /**
     * @param string $quintoBimestreLiquidado
     * @return BalanceteRubricaAnterior
     */
    public function setQuintoBimestreLiquidado($quintoBimestreLiquidado)
    {
        $this->quintoBimestreLiquidado = $quintoBimestreLiquidado;
        return $this;
    }

    /**
     * @return string
     */
    public function getQuintoBimestrePagamento()
    {
        return $this->quintoBimestrePagamento;
    }

    /**
     * @param string $quintoBimestrePagamento
     * @return BalanceteRubricaAnterior
     */
    public function setQuintoBimestrePagamento($quintoBimestrePagamento)
    {
        $this->quintoBimestrePagamento = $quintoBimestrePagamento;
        return $this;
    }

    /**
     * @return string
     */
    public function getSextoBimestreEmpenhado()
    {
        return $this->sextoBimestreEmpenhado;
    }

    /**
     * @param string $sextoBimestreEmpenhado
     * @return BalanceteRubricaAnterior
     */
    public function setSextoBimestreEmpenhado($sextoBimestreEmpenhado)
    {
        $this->sextoBimestreEmpenhado = $sextoBimestreEmpenhado;
        return $this;
    }

    /**
     * @return string
     */
    public function getSextoBimestreLiquidado()
    {
        return $this->sextoBimestreLiquidado;
    }

    /**
     * @param string $sextoBimestreLiquidado
     * @return BalanceteRubricaAnterior
     */
    public function setSextoBimestreLiquidado($sextoBimestreLiquidado)
    {
        $this->sextoBimestreLiquidado = $sextoBimestreLiquidado;
        return $this;
    }

    /**
     * @return string
     */
    public function getSextoBimestrePagamento()
    {
        return $this->sextoBimestrePagamento;
    }

    /**
     * @param string $sextoBimestrePagamento
     * @return BalanceteRubricaAnterior
     */
    public function setSextoBimestrePagamento($sextoBimestrePagamento)
    {
        $this->sextoBimestrePagamento = $sextoBimestrePagamento;
        return $this;
    }

    public function toArray()
    {
        return [
            "orgao" => $this->getOrgao(),
            "unidade" => $this->getUnidade(),
            "funcao" => $this->getFuncao(),
            "subFuncao" => $this->getSubFuncao(),
            "programa" => $this->getPrograma(),
            "campoObsoleto" => "000",
            "projetoAtividade" => $this->getProjetoAtividade(),
            "elemento" => $this->getElemento(),
            "fonteRecurso" => $this->getFonteRecurso(),
            "primeiroBiEmpenhado" => $this->getPrimeiroBimestreEmpenhado(),
            "segundoBiEmpenhado" => $this->getSegundoBimestreEmpenhado(),
            "terceiroBiEmpenhado" => $this->getTerceiroBimestreEmpenhado(),
            "quartoBiEmpenhado" => $this->getQuartoBimestreEmpenhado(),
            "quintoBiEmpenhado" => $this->getQuintoBimestreEmpenhado(),
            "sextoBiEmpenhado" => $this->getSextoBimestreEmpenhado(),
            "primeiroBiLiquidado" => $this->getPrimeiroBimestreLiquidado(),
            "segundoBiLiquidado" => $this->getSegundoBimestreLiquidado(),
            "terceiroBiLiquidado" => $this->getTerceiroBimestreLiquidado(),
            "quartoBiLiquidado" => $this->getQuartoBimestreLiquidado(),
            "quintoBiLiquidado" => $this->getQuintoBimestreLiquidado(),
            "sextoBiLiquidado" => $this->getSextoBimestreLiquidado(),
            "primeiroBiPago" => $this->getPrimeiroBimestrePagamento(),
            "segundoBiPago" => $this->getSegundoBimestrePagamento(),
            "terceiroBiPago" => $this->getTerceiroBimestrePagamento(),
            "quartoBiPago" => $this->getQuartoBimestrePagamento(),
            "quintoBiPago" => $this->getQuintoBimestrePagamento(),
            "sextoBiPago" => $this->getSextoBimestrePagamento(),
            "complemento" => $this->getComplemento(),
        ];
    }
}
