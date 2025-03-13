<?php

namespace ECidade\Financeiro\Contabilidade\Exportacao\PADRS\Layouts\v2022;

use ECidade\Financeiro\Contabilidade\Exportacao\PADRS\Layouts\v2020\BalanceteRubricaAnterior as BRAnterior;

class BalanceteRubricaAnterior extends BRAnterior
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
        "Codigo da Fonte de Recurso" => "fonteRecursoSiconfi",
        "Codigo de Execucao Orcamentaria - CO" => "complementoSiconfi",
    ];

    protected $fonteRecursoSiconfi = '0000';
    protected $complementoSiconfi = '0000';

    /**
     * @return mixed
     */
    public function getFonteRecursoSiconfi()
    {
        return $this->fonteRecursoSiconfi;
    }

    /**
     * @param mixed $fonteRecursoSiconfi
     * @return BalanceteRubricaAnterior
     */
    public function setFonteRecursoSiconfi($fonteRecursoSiconfi)
    {
        $this->fonteRecursoSiconfi = $fonteRecursoSiconfi;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getComplementoSiconfi()
    {
        return $this->complementoSiconfi;
    }

    /**
     * @param mixed $complementoSiconfi
     * @return BalanceteRubricaAnterior
     */
    public function setComplementoSiconfi($complementoSiconfi)
    {
        $this->complementoSiconfi = $complementoSiconfi;
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
            "fonteRecursoSiconfi" => $this->getFonteRecursoSiconfi(),
            "complementoSiconfi" => $this->getComplementoSiconfi()
        ];
    }
}
