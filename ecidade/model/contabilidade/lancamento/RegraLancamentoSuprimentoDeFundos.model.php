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
 * Class RegraLancamentoSuprimentoDeFundos
 */
class RegraLancamentoSuprimentoDeFundos implements IRegraLancamentoContabil {

  /**
   * @param int $iCodigoDocumento
   * @param int $iCodigoLancamento
   * @param ILancamentoAuxiliar $oLancamentoAuxiliar
   * @return RegraLancamentoContabil|void
   */
  public function getRegraLancamento($iCodigoDocumento, $iCodigoLancamento, ILancamentoAuxiliar $oLancamentoAuxiliar) {

    $oEventoContabil = EventoContabilRepository::getEventoContabilByCodigo($iCodigoDocumento, db_getsession("DB_anousu"));
    $aLancamentosContabeis = $oEventoContabil->getEventoContabilLancamento();

    /**
     * Percorremos os lançamentos encontrados para o documento
     */
    foreach ($aLancamentosContabeis as $oLancamentoContabil) {

      /**
       * Buscamos as regras encontradas para o lançamento (conta crédito / débito)
       */
      $aRegrasContabeis = $oLancamentoContabil->getRegrasLancamento();

      if ($oLancamentoContabil->getOrdem() == 1) {

        /**
         * Caso seja ordem um, percorremos as regras cadastradas para retornar ao usuário uma que tenha o
         * mesmo tipo de comparação e tipo de prestação de contas do empenho
         */
        foreach ($aRegrasContabeis as $oRegraContabil) {

          $oStdDadosPrestacaoContas = $oLancamentoAuxiliar->getEmpenhoFinanceiro()->getDadosPrestacaoContas();
          if ($oRegraContabil->getCompara() == RegraLancamentoContabil::COMPARA_PRESTACAO_CONTA &&
              $oStdDadosPrestacaoContas && $oStdDadosPrestacaoContas->e45_tipo == $oRegraContabil->getReferencia() ) {

            return $oRegraContabil;
          }
        }
        return false;
      }
      return $aRegrasContabeis[0];
    }

    return false;
  }
}