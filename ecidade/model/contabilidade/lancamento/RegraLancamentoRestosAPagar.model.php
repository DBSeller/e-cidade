<?php
/*
 *     E-cidade Software Publico para Gestao Municipal                
 *  Copyright (C) 2009  DBSeller Servicos de Informatica             
 *                            www.dbseller.com.br                     
 *                         e-cidade@dbseller.com.br                   
 *                                                                    
 *  Este programa e software livre; voce pode redistribui-lo e/ou     
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme  
 *  publicada pela Free Software Foundation; tanto a versao 2 da      
 *  Licenca como (a seu criterio) qualquer versao mais nova.          
 *                                                                    
 *  Este programa e distribuido na expectativa de ser util, mas SEM   
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de              
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM           
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais  
 *  detalhes.                                                         
 *                                                                    
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU     
 *  junto com este programa; se nao, escreva para a Free Software     
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA          
 *  02111-1307, USA.                                                  
 *  
 *  Copia da licenca no diretorio licenca/licenca_en.txt 
 *                                licenca/licenca_pt.txt 
 */

require_once(modification("interfaces/IRegraLancamentoContabil.interface.php"));

/**
 * Model responsavel por descobrir as contas credito/debito dos Restos a pagar não processados
 * @author Bruno Silva <bruno.silva@dbseller.com.br>
 * @package contabilidade
 * @subpackage lancamento
 * @version $Revision: 1.6 $
 */
class RegraLancamentoRestosAPagar implements IRegraLancamentoContabil {

  /**
   * @see IRegraLancamentoContabil::getRegraLancamento()
   */
  public function getRegraLancamento($iCodigoDocumento, $iCodigoLancamento, ILancamentoAuxiliar $oLancamentoAuxiliar) {

    $oDaoTransacao   = new cl_contranslr();
    $sWhere            = "     c45_coddoc      = {$iCodigoDocumento}";
    $sWhere           .= " and c45_anousu      = ".db_getsession("DB_anousu");
    $sWhere           .= " and c46_seqtranslan = {$iCodigoLancamento}";
    $sSqlTransacao     = $oDaoTransacao->sql_query(null, "*", null, $sWhere);

    $chaveRegistrada = "inscricao_restos_pagar_{$iCodigoDocumento}_{$iCodigoLancamento}";
    $chaveRegistry = DBRegistry::get($chaveRegistrada);
    if (empty($registry)) {
      DBRegistry::add($chaveRegistrada, $oDaoTransacao->sql_record($sSqlTransacao));
    }

    $rsTransacao = DBRegistry::get($chaveRegistrada);
    $iTotalLancamentos = pg_num_rows($rsTransacao);

    if ($iTotalLancamentos > 1) {
      throw new BusinessException("Mais de uma regra cadastrada para o documento {$iCodigoDocumento}.");
    }

    /**
     * Nao encontrou regra de lancamento para o documento 
     */
    if ($iTotalLancamentos == 0) {
      return false;
    }

    $oDadosTransacao = db_utils::fieldsMemory($rsTransacao, 0);
    return new RegraLancamentoContabil($oDadosTransacao->c47_seqtranslr);
  }

}