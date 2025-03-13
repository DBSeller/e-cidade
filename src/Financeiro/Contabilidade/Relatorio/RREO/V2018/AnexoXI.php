<?php

namespace ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2018;

use AnexoXIRREO_2017;

/**
 * Class AnexoXI
 * @package ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2018
 */
class AnexoXI extends AnexoXIRREO_2017
{
    /**
     *
     */
    protected function imprimirLinhaSaldoAtual()
    {
        $w = $this->iLargura * 0.10;
        $h = $this->iAltura * 1.5;

        $this->oPdf->MultiCell($w, $h, "SALDO ATUAL\n(k) = (IIIi + IIIj)", 'TBL', 'C', 1);
    }
}