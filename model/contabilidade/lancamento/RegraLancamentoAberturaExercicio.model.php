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
 * Model responsavel por descobrir as contas credito/debito da abertura do exercicio
 *
 * @author     Bruno Silva <bruno.silva@dbseller.com.br>
 * @package    contabilidade
 * @subpackage lancamento
 * @version    $Revision: 1.6 $
 */
class RegraLancamentoAberturaExercicio implements IRegraLancamentoContabil
{

    /**
     * @see IRegraLancamentoContabil::getRegraLancamento()
     */
    public function getRegraLancamento($iCodigoDocumento, $iCodigoLancamento, ILancamentoAuxiliar $oLancamentoAuxiliar)
    {

        $oDaoTransacao = db_utils::getDao('contranslr');
        $regras = DBRegistry::get("regras_lancamnto_doc_{$iCodigoDocumento}_{$iCodigoLancamento}");
        if (empty($regras)) {

            $sWhere = "     c45_coddoc      = {$iCodigoDocumento}";
            $sWhere .= " and c45_anousu      = " . db_getsession("DB_anousu");
            $sWhere .= " and c46_seqtranslan = {$iCodigoLancamento}";
            $sSqlTransacao = $oDaoTransacao->sql_query(null, "*", null, $sWhere);
            $rsTransacao = $oDaoTransacao->sql_record($sSqlTransacao);
            $regras = db_utils::getCollectionByRecord($rsTransacao);
            DBRegistry::add("regras_lancamnto_doc_{$iCodigoDocumento}_{$iCodigoLancamento}", $regras);
        }

        /**
         * Nao encontrou regra de lancamento para o documento
         */
        if (count($regras) == 0) {
            return false;
        }

        $documentosDespesa = array(2001, 2002);
        if (in_array($iCodigoDocumento, $documentosDespesa)) {

            return new RegraLancamentoContabil($regras[0]->c47_seqtranslr);
        }
        $caracteristica = $oLancamentoAuxiliar->getReceita()->getCaracteristicaPeculiar();
        $estruturalReceita = $oLancamentoAuxiliar->getReceita()->getEstrutural();
        foreach ($regras as $regra) {

            $tipoReceita = substr($estruturalReceita, 0, 1);
            if ($regra->c47_compara == 0 && $tipoReceita == 4) {

                return new RegraLancamentoContabil($regra->c47_seqtranslr);
            }
            if ($tipoReceita == 9 && in_array($regra->c47_compara, array(12))  && ((int)$caracteristica == (int)$regra->c47_ref)) {
                return new RegraLancamentoContabil($regra->c47_seqtranslr);
            }
        }

        $mensagem = "Não foram encontradas regras de lançamento para o documento {$iCodigoDocumento} com a Característica Peculiar {$caracteristica}.";
        if ($oLancamentoAuxiliar->getReceita() != '') {
            $mensagem .= "\nVerifique a regra para a Receita {$estruturalReceita}. A regra para receitas de dedução devem estar configuradas com a regra de comparacao 'CP/CA igual a' {$caracteristica}.";
        }
        throw new \Exception($mensagem);

    }
}
