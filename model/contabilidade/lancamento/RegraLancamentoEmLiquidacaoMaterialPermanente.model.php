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
 * Verifica a regra de lançamentos para o movimento em liquidacao de materiais permanentes.
 * @author Iuri Guntchnigg iuri@dbseller.com.br
 * @author Matheus Felini  matheus.felini@dbseller.com.br
 * @package contabilidade
 * @subpackage lancamento
 * @version $Revision: 1.8 $
 */
class RegraLancamentoEmLiquidacaoMaterialPermanente implements IRegraLancamentoContabil {

    /**
     * @param int $iCodigoDocumento
     * @param int $iCodigoLancamento
     * @param ILancamentoAuxiliar $oLancamentoAuxiliar
     * @return bool|mixed|RegraLancamentoContabil
     * @throws BusinessException|Exception
     */
    public function getRegraLancamento($iCodigoDocumento, $iCodigoLancamento, ILancamentoAuxiliar $oLancamentoAuxiliar) {

        $oEventoContabil           = EventoContabilRepository::getEventoContabilByCodigo($iCodigoDocumento, db_getsession("DB_anousu"));
        $oLancamentoEventoContabil = $oEventoContabil->getEventoContabilLancamentoPorCodigo($iCodigoLancamento);

        /**
         * Nao encontrou nenhuam regra para o lancamento
         */
        if (!$oLancamentoEventoContabil || count($oLancamentoEventoContabil->getRegrasLancamento()) == 0) {
            return false;
        }

        $aRegrasDoLancamento = $oLancamentoEventoContabil->getRegrasLancamento();
        $aContasEncontradas  = array();

        foreach ($aRegrasDoLancamento as  $oRegraLancamentoContabil ) {

            if ( $oLancamentoEventoContabil->getOrdem() == 1 ) {

                if (UTILIZA_INCORPORACAO_BEM) {
                    $aContasEncontradas[] = $oRegraLancamentoContabil;
                } else {
                    $iContaClassificacao = $oLancamentoAuxiliar->getClassificacao()->getContaContabil()->getReduzido();
                    if ($oEventoContabil->estorno()) {
                        $oRegraLancamentoContabil->setContaCredito($iContaClassificacao);
                    } else {
                        $oRegraLancamentoContabil->setContaDebito($iContaClassificacao);
                    }
                    $aContasEncontradas[] = $oRegraLancamentoContabil;
                }
            } else {
                $aContasEncontradas[] = $oRegraLancamentoContabil;
            }
        }

        /**
         * Nenhuma regra de lancamento encontrada para o documento
         */
        if (count($aContasEncontradas) == 0) {
            return false;
        }

        if (count($aContasEncontradas) > 1) {

            if (!UTILIZA_INCORPORACAO_BEM) {
                $oContaContabilClassificacao = $oLancamentoAuxiliar->getClassificacao()->getContaContabil();
                $oStdDadosMensagem = new stdClass();
                $oStdDadosMensagem->estrutural              = $oContaContabilClassificacao->getEstrutural();
                $oStdDadosMensagem->descricao_conta         = $oContaContabilClassificacao->getDescricao();
                $oStdDadosMensagem->descricao_classificacao = $oLancamentoAuxiliar->getClassificacao()->getDescricao();
                $oStdDadosMensagem->documento               = $iCodigoDocumento . ' - ' . $oEventoContabil->getDescricaoDocumento();
                $oStdDadosMensagem->ordem_lancamento        = $oLancamentoEventoContabil->getOrdem();
                $mensagem = 'financeiro.contabilidade.RegraLancamentoEmLiquidacaoMaterialPermanente.mais_de_uma_regra_encontrada';
            } else {

                $oStdDadosMensagem = (object)array(
                    'documento' => $iCodigoDocumento . ' - ' . $oEventoContabil->getDescricaoDocumento()
                );
                $mensagem = 'financeiro.contabilidade.RegraLancamentoEmLiquidacaoMaterialPermanente.mais_de_uma_regra_encontrada_1';
            }
            throw new BusinessException(_M($mensagem, $oStdDadosMensagem));
        }

        $oRegraLancamentoContabil = $aContasEncontradas[0];
        return $oRegraLancamentoContabil;
    }

}