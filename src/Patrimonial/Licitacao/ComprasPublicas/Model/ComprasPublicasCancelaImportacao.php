<?php

namespace ECidade\Patrimonial\Licitacao\ComprasPublicas\Model;

use Exception;
use licitacao;
use stdClass;
use db_utils;
use cl_licitacaoreservacotas;
use cl_pcorcamitemlic;
use fornecedor;

/**
 * Classe para importação dos dados retornados da API
 * @package Ecidade\Patrimonial\Licitacao\ComprasPublicas\Model
 */
class ComprasPublicasCancelaImportacao
{

    /**
     * @var licitacao
     */
    private $licitacao;
    
    
    public function __construct(licitacao $licitacao)
    {
        $this->licitacao = $licitacao;
    }
    /**
     *
     * @param $codigoLicitacao integer
     * @param $itensRetornados array
     */
    public function cancelar()
    {
        $oDaoliclicitemlance          = db_utils::getDao("liclicitemlances");
        $oDaopcorcamitemlic           = db_utils::getDao("pcorcamitemlic");
        $oDaopcorcamjulg              = db_utils::getDao("pcorcamjulg");
        $oDaopcorcamdescla            = db_utils::getDao("pcorcamdescla");
        $oDaopcorcamval               = db_utils::getDao("pcorcamval");
        $oDaopcorcamjulgamentologitem = db_utils::getDao("pcorcamjulgamentologitem");
        $oDaopcorcamitemresultado     = db_utils::getDao("pcorcamitemresultado");
        $oDaopcorcamitem              = db_utils::getDao("pcorcamitem");
        $oDaopcorcamfornelic          = db_utils::getDao("pcorcamfornelic");
        $oDaopcorcamforne             = db_utils::getDao("pcorcamforne");
        $oDaopcorcamjulgamentolog     = db_utils::getDao("pcorcamjulgamentolog");
        $oDaopcorcam                  = db_utils::getDao("pcorcam");
        $aItensCancelar = $this->licitacao->getItens();
        if (count($aItensCancelar) == 0) {
            throw new Exception("Não existem dados para cancelar importação");
        }

        $codigoOrcamento     = null;
        $codigoLogJulgamento = null;
        foreach ($aItensCancelar as $item) {
            $l21_codigo = $item->getCodigo();
            $resultOrcamentoLicitacao = $oDaopcorcamitemlic->sql_record(
                $oDaopcorcamitemlic->sql_query(
                    null,
                    "pc26_orcamitem, 
                    pc22_codorc",
                    null,
                    "pc26_liclicitem = {$l21_codigo}"
                )
            );
        
            if (!$resultOrcamentoLicitacao || $oDaopcorcamitemlic->numrows != 1) {
                throw new Exception("Não foi possível buscar o item no orçamento");
            }
          
            $orcamentoLicitacao = db_utils::fieldsMemory($resultOrcamentoLicitacao, 0);
            $orcamitem          = $orcamentoLicitacao->pc26_orcamitem;
            if ($codigoOrcamento == null) {
                $codigoOrcamento = $orcamentoLicitacao->pc22_codorc;
            }

            $oDaopcorcamitemlic->excluir(null, "pc26_liclicitem = {$l21_codigo}");
            if ($oDaopcorcamitemlic->erro_status == "0") {
                throw new Exception($oDaopcorcamitemlic->erro_msg);
            }
            $oDaoliclicitemlance->excluir(null, "l49_liclicitem = $l21_codigo");
            if ($oDaoliclicitemlance->erro_status == "0") {
                throw new Exception($oDaoliclicitemlance->erro_msg);
            }
            $oDaopcorcamjulg->excluir($orcamitem);
            if ($oDaopcorcamjulg->erro_status == "0") {
                throw new Exception($oDaopcorcamjulg->erro_msg);
            }
            $oDaopcorcamdescla->excluir($orcamitem);
            if ($oDaopcorcamdescla->erro_status == "0") {
                throw new Exception($oDaopcorcamdescla->erro_msg);
            }
            $oDaopcorcamval->excluir(null, $orcamitem);
            if ($oDaopcorcamval->erro_status == "0") {
                throw new Exception($oDaopcorcamval->erro_msg);
            }
            if ($codigoLogJulgamento == null) {
                $rsJulgamentoItem = $oDaopcorcamjulgamentologitem->sql_record(
                    $oDaopcorcamjulgamentologitem->sql_query_file(
                        null,
                        "pc93_pcorcamjulgamentolog",
                        null,
                        "pc93_pcorcamitem = {$orcamitem}"
                    )
                );
                if ($rsJulgamentoItem) {
                    $codigoLogJulgamento = db_utils::fieldsMemory($rsJulgamentoItem, 0)->pc93_pcorcamjulgamentolog;
                }
            }
          
            $oDaopcorcamjulgamentologitem->excluir(null, "pc93_pcorcamitem = {$orcamitem}");
            if ($oDaopcorcamjulgamentologitem == "0") {
                throw new Exception($oDaopcorcamjulgamentologitem->erro_msg);
            }

            $oDaopcorcamitemresultado->excluir($orcamitem);
            if ($oDaopcorcamitemresultado->erro_status == "0") {
                throw new Exception($oDaopcorcamitemresultado->erro_msg);
            }

            $oDaopcorcamitem->excluir($orcamitem);
            if ($oDaopcorcamitem->erro_status == "0") {
                throw new Exception($oDaopcorcamitem->erro_msg);
            }
        }
        
        if ($codigoLogJulgamento == null) {
            throw new Exception("Não houve julgamento da licitação");
        }

        if ($codigoOrcamento ==null) {
            throw new Exception("Não houve orçamento na licitação");
        }
        
         
        
        $oDaopcorcamfornelic->excluir(null, "exists(select 1 
                                                      from pcorcamforne 
                                                     where pc21_orcamforne = pc31_orcamforne 
                                                       and pc21_codorc = $codigoOrcamento)");
        if ($oDaopcorcamfornelic->erro_status == "0") {
            throw new Exception($oDaopcorcamfornelic->erro_msg);
        }

        $oDaopcorcamforne->excluir(null, "pc21_codorc = $codigoOrcamento");
        if ($oDaopcorcamforne->erro_status == "0") {
            throw new Exception($oDaopcorcamforne->erro_msg);
        }
        $oDaopcorcamjulgamentolog->excluir($codigoLogJulgamento);
        if ($oDaopcorcamjulgamentolog->erro_status == "0") {
            throw new Exception($oDaopcorcamjulgamentolog->erro_msg);
        }

        $oDaopcorcam->excluir($codigoOrcamento);
        if ($oDaopcorcam->erro_status == "0") {
            throw new Exception($oDaopcorcam->erro_msg);
        }
        

        $this->licitacao->alterarSituacao(0, "Cancelamento importação Compras Públicas");
    }
}
