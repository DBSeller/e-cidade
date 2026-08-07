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
 * Retorna a regra cadastrada para a apropriação da receita
 * @author Andrio Costa
 * @package contabilidade
 * @subpackage lancamento
 * @version $Revision: 1.8 $
 */
class RegraLancamentoApropriacaoRetencao implements IRegraLancamentoContabil
{
    private $iCodigoDocumento;

    /**
     * @param int $iCodigoDocumento
     * @param int $iCodigoLancamento
     * @param LancamentoAuxiliarEmpenhoLiquidacao|\ILancamentoAuxiliar $oLancamentoAuxiliar
     *
     * @return bool|\RegraLancamentoContabil
     * @throws \Exception
     */
    public function getRegraLancamento($iCodigoDocumento, $iCodigoLancamento, ILancamentoAuxiliar $oLancamentoAuxiliar)
    {
        $this->iCodigoDocumento = $iCodigoDocumento;
        $iAnoSessao = db_getsession("DB_anousu");
        $oDaoTransacao = new cl_contranslr();
        $sWhere = "     c45_coddoc      = {$iCodigoDocumento}";
        $sWhere .= " and c45_anousu      = {$iAnoSessao}";
        $sWhere .= " and c46_seqtranslan = {$iCodigoLancamento}";
        $sWhere .= " and c47_instit = " . db_getsession('DB_instit');
        $sSqlTransacao = $oDaoTransacao->sql_query(null, "*", "c114_elemento desc", $sWhere);
        $rsTransacao = $oDaoTransacao->sql_record($sSqlTransacao);

        if ($oDaoTransacao->erro_status == '0') {
            return false;
        }

        $iNumeroRegistros = $oDaoTransacao->numrows;

        for ($i = 0; $i < $iNumeroRegistros; $i++) {
            $oDadosTransacao = db_utils::fieldsMemory($rsTransacao, $i);

            if ($iNumeroRegistros == 1 && $oDadosTransacao->c46_ordem > 1) {
                $oDadosTransacao = db_utils::fieldsMemory($rsTransacao, 0);
                $oRegraLancamentoContabil = new RegraLancamentoContabil($oDadosTransacao->c47_seqtranslr);
                return $oRegraLancamentoContabil;
            }

            switch ($oDadosTransacao->c47_compara) {
                /**
                 * criado case 0 para ser usado em ordem acima de um onde o reduzido do elenento nao precisa ser igual ao da conta
                 * configurado na regra.
                 */
                case RegraLancamentoContabil::COMPARA_TIPO_RETENCAO:
                    if ($oLancamentoAuxiliar->getTipoCalculoRetencao() == $oDadosTransacao->c47_ref && $oDadosTransacao->c46_ordem == 1) {
                        if (method_exists($oLancamentoAuxiliar, 'getEmpenhoFinanceiro')) {
                            $empenhoFinanceiro = $oLancamentoAuxiliar->getEmpenhoFinanceiro();
                            $estruturalOrcamento = $empenhoFinanceiro->getContaOrcamento()->getEstrutural();
                            $estruturalValido = !empty($oDadosTransacao->c114_elemento) && $oDadosTransacao->c114_elemento != '000000000000000';
                            if ($estruturalValido && $estruturalOrcamento <= $oDadosTransacao->c114_elemento) {
                                continue;
                            }
                        }

                        $oRegraLancamentoContabil = new RegraLancamentoContabil($oDadosTransacao->c47_seqtranslr);
                        if (in_array($iCodigoDocumento, array(6000, 6001))) {
                            if ($iCodigoDocumento == 6001) {
                                $oRegraLancamentoContabil->setContaDebito($oLancamentoAuxiliar->getContaDebito());
                            } else {
                                $oRegraLancamentoContabil->setContaCredito($oLancamentoAuxiliar->getContaCredito());
                            }
                        }

                        if (in_array($iCodigoDocumento, array(6002, 6003))) {
                            if ($iCodigoDocumento == 6002) {
                                $oRegraLancamentoContabil->setContaCredito($this->getContaExtraOrcamentariaDaRetencao($oLancamentoAuxiliar->getRetencao()));
                            } else {
                                $oRegraLancamentoContabil->setContaDebito($this->getContaExtraOrcamentariaDaRetencao($oLancamentoAuxiliar->getRetencao()));
                            }
                        }


                        /*

                        if (in_array($iCodigoDocumento, array(6002, 6003, 6010, 6011, 6008, 6009))) {
                            if (in_array($iCodigoDocumento, array(6002, 6010, 6008))) {
                                $oRegraLancamentoContabil->setContaCredito($this->getContaExtraOrcamentariaDaRetencao($oLancamentoAuxiliar->getRetencao()));
                            } else {
                                $oRegraLancamentoContabil->setContaDebito($this->getContaExtraOrcamentariaDaRetencao($oLancamentoAuxiliar->getRetencao()));
                            }
                        }
                        */

                        if (in_array($iCodigoDocumento, array(6012, 6013))) {

                            if (!$oLancamentoAuxiliar->isEstorno()) {
                                $oRegraLancamentoContabil->setContaCredito($oLancamentoAuxiliar->getCodigoReduzido());
                            } else {
                                $oRegraLancamentoContabil->setContaDebito($oLancamentoAuxiliar->getCodigoReduzido());
                            }
                        }
                        if (in_array($iCodigoDocumento, array(6006, 6007))) {

                            if ($iCodigoDocumento == 6006) {
                                $oRegraLancamentoContabil->setContaDebito($oLancamentoAuxiliar->getContaDebito());
                            } else {
                                $oRegraLancamentoContabil->setContaCredito($oLancamentoAuxiliar->getContaCredito());
                            }
                        }
                        return $oRegraLancamentoContabil;
                    }
                    break;
                default :

                    if ($oDadosTransacao->c46_ordem > 1) {
                        $oRegraLancamentoContabil = new RegraLancamentoContabil($oDadosTransacao->c47_seqtranslr);
                        return $oRegraLancamentoContabil;
                    }
                    break;
            }
        }


        return false;
    }

    /**
     *
     * @param $codigoRetencao
     * @return null|int
     */
    protected function getContaExtraOrcamentariaDaRetencao($codigoRetencao)
    {
        $sSqlTipoRec = " SELECT k02_reduz                                                   ";
        $sSqlTipoRec .= "   from retencaotiporec ";
        $sSqlTipoRec .= "       inner join tabrec  on e21_receita = k02_codigo               ";
        $sSqlTipoRec .= "       inner join tabplan on tabrec.k02_codigo = tabplan.k02_codigo ";
        $sSqlTipoRec .= " where e21_sequencial  = {$codigoRetencao} ";
        $sSqlTipoRec .= "   and k02_anousu = " . db_getsession("DB_anousu");
        $rsTipoRec = db_query($sSqlTipoRec);
        if (pg_num_rows($rsTipoRec) == 0) {
            return null;
        }
        return db_utils::fieldsMemory($rsTipoRec, 0)->k02_reduz;
    }

    /**
     * Retorna a conta a credito do evento
     * @param $documento
     * @param $ano
     * @param $instituicao
     * @param $tipoRetencao
     * @return bool|int
     */
    public static function getContaCreditoDoDocumento($documento, $ano, $instituicao, $tipoRetencao)
    {

        $dadosConta = self::getContasDoDocumentoComTipodeRetencao($documento, $ano, $instituicao, $tipoRetencao);
        if (!$dadosConta) {
            return false;
        }
        return $dadosConta->credito;
    }

    /**
     * @param $documento
     * @param $ano
     * @param $instituicao
     * @param $tipoRetencao
     * @return bool|int
     */
    public static function getContaDebitoDoDocumento($documento, $ano, $instituicao, $tipoRetencao)
    {

        $dadosConta = self::getContasDoDocumentoComTipodeRetencao($documento, $ano, $instituicao, $tipoRetencao);
        if (!$dadosConta) {
            return false;
        }
        return $dadosConta->debito;
    }

    /**
     * Retorna as contas do 1 lancamento
     * @param $documento
     * @param $ano
     * @param $instituicao
     * @param $tipoRetencao
     * @return _db_fields|bool|stdClass
     */
    protected static function getContasDoDocumentoComTipodeRetencao($documento, $ano, $instituicao, $tipoRetencao)
    {
        $sql = " select c47_debito as debito, ";
        $sql .= "        c47_credito as credito";
        $sql .= "   from contrans ";
        $sql .= "        inner join contranslan on c46_seqtrans = c45_seqtrans ";
        $sql .= "        inner join contabilidade.contranslr on c47_seqtranslan = c46_seqtranslan ";
        $sql .= "  where c45_anousu = {$ano} ";
        $sql .= "    and c45_coddoc = {$documento} ";
        $sql .= "    and c45_instit = {$instituicao}";
        $sql .= "    and c46_ordem = 1 ";
        $sql .= "    and c47_compara = 15";
        $sql .= "    and c47_ref = {$tipoRetencao}";
        $rsRegra = db_query($sql);
        if (pg_num_rows($rsRegra) == 0) {
            return false;
        }
        return \db_utils::fieldsMemory($rsRegra, 0);

    }
}
