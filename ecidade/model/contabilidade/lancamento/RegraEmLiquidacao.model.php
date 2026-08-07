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
 * Retorna a regra cadastrada para a arrecadação de receita
 * @author Andrio Costa
 * @package contabilidade
 * @subpackage lancamento
 * @version $Revision: 1.8 $
 */
class RegraEmLiquidacao implements IRegraLancamentoContabil
{

    /**
     * Retorna um objeto RegraLancamentoContabil
     * @see IRegraLancamentoContabil::getRegraLancamento()
     * @param integer $iCodigoDocumento - Documento contabil
     * @param integer $iCodigoLancamento - Codigo do lancamento contabil
     * @param ILancamentoAuxiliar $oLancamentoAuxiliar
     */
    public function getRegraLancamento($iCodigoDocumento, $iCodigoLancamento, ILancamentoAuxiliar $oLancamentoAuxiliar)
    {
        if (!UTILIZA_INCORPORACAO_BEM) {
            $oDaoTransacao = db_utils::getDao('contranslr');
            $sWhere = "     c45_coddoc      = {$iCodigoDocumento}";
            $sWhere .= " and c45_anousu      = " . db_getsession("DB_anousu");
            $sWhere .= " and c46_seqtranslan = {$iCodigoLancamento}";
            $sSqlTransacao = $oDaoTransacao->sql_query(null, "*", null, $sWhere);
            $rsTransacao = $oDaoTransacao->sql_record($sSqlTransacao);
            $oDadosTransacao = db_utils::fieldsMemory($rsTransacao, 0);

            if ($oDaoTransacao->numrows > 1) {

                $sMensagemException = "Mais de uma conta débito/crédito configurada para o ";
                $sMensagemException .= "lançamento [{$iCodigoLancamento}] de ordem {$oDadosTransacao->c46_ordem}.";
                throw new BusinessException($sMensagemException);
            }

            /**
             * Nao encontrou regra de lancamento para o documento
             */
            if ($oDaoTransacao->numrows == 0) {
                return false;
            }

            $oDadosTransacao = db_utils::fieldsMemory($rsTransacao, 0);
            $oRegraLancamentoContabil = new RegraLancamentoContabil($oDadosTransacao->c47_seqtranslr);
        } else {
            $aDocumentosLiquidacaoRP = array(33, 34);
            $this->iCodigoDocumento = $iCodigoDocumento;
            $iAnoSessao = db_getsession("DB_anousu");
            $oDaoTransacao = new cl_contranslr();
            $sWhere = "     c45_coddoc      = {$iCodigoDocumento}";
            $sWhere .= " and c45_anousu      = {$iAnoSessao}";
            $sWhere .= " and c46_seqtranslan = {$iCodigoLancamento}";

            if (in_array($iCodigoDocumento, $aDocumentosLiquidacaoRP)) {
                $sWhere .= " and c47_anousu = {$oLancamentoAuxiliar->getEmpenhoFinanceiro()->getAno()}";
            }
            $sSqlTransacao = $oDaoTransacao->sql_query(null, "*", "c114_elemento desc", $sWhere);
            $rsTransacao = $oDaoTransacao->sql_record($sSqlTransacao);

            if ($oDaoTransacao->erro_status == '0') {
                return false;
            }

            $iNumeroRegistros = $oDaoTransacao->numrows;

            /**
             * Caso o lancamento tenha mais de uma conta configurada, devemos descobrir qual a conta que efetuaremos os
             * lancamentos. Para isso comparamos as contas com base no COMPARA (c47_compara) da regra
             */
            for ($i = 0; $i < $iNumeroRegistros; $i++) {

                $oDadosTransacao = db_utils::fieldsMemory($rsTransacao, $i);

                if ($iNumeroRegistros == 1 && $oDadosTransacao->c46_ordem > 1) {

                    $oDadosTransacao = db_utils::fieldsMemory($rsTransacao, 0);
                    $oRegraLancamentoContabil = new RegraLancamentoContabil($oDadosTransacao->c47_seqtranslr);
                    return $oRegraLancamentoContabil;
                }

                $oRegraLancamentoContabil = false;
                switch ($oDadosTransacao->c47_compara) {

                    /**
                     * criado case 0 para ser usado em ordem acima de um onde o reduzido do elenento nao precisa ser igual ao da conta
                     * configurado na regra.
                     */
                    case 0:

                        if ($oDadosTransacao->c46_ordem > 1) {
                            $oRegraLancamentoContabil = new RegraLancamentoContabil($oDadosTransacao->c47_seqtranslr);
                            return $oRegraLancamentoContabil;
                        }

                        break;

                    case RegraLancamentoContabil::COMPARA_DEBITO:

                        if ($oLancamentoAuxiliar->getCodigoContaPlano() == $oDadosTransacao->c47_debito && $oDadosTransacao->c46_ordem == 1) {

                            $oRegraLancamentoContabil = new RegraLancamentoContabil($oDadosTransacao->c47_seqtranslr);
                        }

                        break;

                    case RegraLancamentoContabil::COMPARA_CREDITO:

                        if ($oLancamentoAuxiliar->getCodigoContaPlano() == $oDadosTransacao->c47_credito) {
                            $oRegraLancamentoContabil = new RegraLancamentoContabil($oDadosTransacao->c47_seqtranslr);
                        }

                        break;

                    case RegraLancamentoContabil::COMPARA_DEBITO_ELEMENTO:

                        if (empty($oDadosTransacao->c114_elemento)) {
                            throw new Exception("Regra configurada para comparação a Débito / Elemento, porém sem estrutural configurado.");
                        }

                        $oContaOrcamento = $oLancamentoAuxiliar->getEmpenhoFinanceiro()->getContaOrcamento();
                        $iReduzido = $this->getReduzidoPlanoContaPCASP($oContaOrcamento);

                        if ($oContaOrcamento->getEstrutural() >= $oDadosTransacao->c114_elemento) {

                            $oRegraLancamentoContabil = new RegraLancamentoContabil($oDadosTransacao->c47_seqtranslr);
                            $oRegraLancamentoContabil->setContaDebito($iReduzido);
                        }

                        break;

                    case RegraLancamentoContabil::COMPARA_CREDITO_ELEMENTO:

                        if (empty($oDadosTransacao->c114_elemento)) {
                            throw new Exception("Regra configurada para comparação a Débito / Elemento, porém sem estrutural configurado.");
                        }

                        $oContaOrcamento = $oLancamentoAuxiliar->getEmpenhoFinanceiro()->getContaOrcamento();
                        $iReduzido = $this->getReduzidoPlanoContaPCASP($oContaOrcamento);
                        if ($oContaOrcamento->getEstrutural() >= $oDadosTransacao->c114_elemento) {

                            $oRegraLancamentoContabil = new RegraLancamentoContabil($oDadosTransacao->c47_seqtranslr);
                            $oRegraLancamentoContabil->setContaCredito($iReduzido);
                        }
                        break;
                }
            }
        }
        return $oRegraLancamentoContabil;
    }

    /**
     *
     * @param ContaOrcamento $oContaOrcamento
     * @return integer
     * @throws Exception
     */
    private function getReduzidoPlanoContaPCASP(ContaOrcamento $oContaOrcamento)
    {


        if (in_array($this->iCodigoDocumento, array(33, 34))) {


            $sEstrutural = $oContaOrcamento->getEstrutural();
            $iAnoSessao = db_getsession("DB_anousu");
            $oInstituicao = new Instituicao(db_getsession("DB_instit"));


            $oContaOrcamento = ContaOrcamentoRepository::getContaPorEstrutural($sEstrutural, $iAnoSessao, $oInstituicao);
            if (empty($oContaOrcamento)) {
                throw new Exception("A Conta do Orçamento {$sEstrutural} não existe no ano de {$iAnoSessao}. Verifique a configuração.");
            }

        }

        $oPlanoContaPCASP = $oContaOrcamento->getPlanoContaPCASP();
        $iReduzido = empty($oPlanoContaPCASP) ? null : $oContaOrcamento->getPlanoContaPCASP()->getReduzido();

        if (empty($oPlanoContaPCASP) || empty($iReduzido)) {
            throw new Exception("A Conta do Orçamento não tem vínculo com o Plano de Contas PCASP. Verifique a configuração.");
        }
        return $iReduzido;
    }

}
