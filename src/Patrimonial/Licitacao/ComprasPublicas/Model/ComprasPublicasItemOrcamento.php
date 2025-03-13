<?php

namespace ECidade\Patrimonial\Licitacao\ComprasPublicas\Model;

use stdClass;
use Exception;
use cl_pcorcamitem;
use cl_pcorcamitemlic;
use cl_pcorcamitemresultado;

class ComprasPublicasItemOrcamento
{
    
    public function __construct($codigoOrcamento, $itemLicitacao, $resultado = "A")
    {
        $this->codigoOrcamento = $codigoOrcamento;
        $this->itemLicitacao   = $itemLicitacao;
        $this->resultado       = $resultado;
    }

    public function importar()
    {
        
        $pcorcamitem = new cl_pcorcamitem();
        $pcorcamitem->pc22_codorc  = $this->codigoOrcamento;
        $pcorcamitem->incluir(null);
        if ($pcorcamitem->erro_status == 0) {
            throw new Exception($pcorcamitem->erro_msg);
        }

        $pcorcamitemresultado = new cl_pcorcamitemresultado();
        $pcorcamitemresultado->pc220_orcamitem = $pcorcamitem->pc22_orcamitem;
        $pcorcamitemresultado->pc220_resultado = $this->resultado;
        $pcorcamitemresultado->incluir($pcorcamitem->pc22_orcamitem);
        if ($pcorcamitemresultado->erro_status == 0) {
            throw new Exception($pcorcamitemresultado->erro_msg);
        }

        $pcorcamitemlic = new cl_pcorcamitemlic();
        $pcorcamitemlic->pc26_orcamitem  = $pcorcamitem->pc22_orcamitem;
        $pcorcamitemlic->pc26_liclicitem = $this->itemLicitacao;
        $pcorcamitemlic->incluir();
        if ($pcorcamitemlic->erro_status == 0) {
            throw new Exception($pcorcamitemlic->erro_msg);
        }

        return $pcorcamitem->pc22_orcamitem;
    }
}
