<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (c) 2014  DBSeller Servicos de Informatica
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
 *
 */

use ECidade\Saude\Laboratorio\Service\NumeroControleInternoRequisicao as NumeroControleInternoRequisicaoService;
use ECidade\Saude\Laboratorio\Repository\NumeroControleInternoRequisicao as NumeroControleInternoRequisicaoRepository;
use ECidade\Saude\Laboratorio\Model\NumeroControleInternoRequisicao as NumeroControleInternoRequisicaoModel;

define('MENSAGENS_LAB4_AUTORIZACAO_RPC', 'saude.laboratorio.lab4_autorizacao_RPC.');

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_stdlibwebseller.php"));
require_once(modification("libs/JSON.php"));
require_once(modification("dbforms/db_funcoes.php"));

$oJson = new services_json();
$oParam = $oJson->decode(str_replace("\\", "", $_POST["json"]));
$oRetorno = new stdClass();
$oRetorno->iStatus = 1;
$oRetorno->erro = false;
$oRetorno->sMensagem = '';

try {
    switch ($oParam->exec) {
        case 'autorizaExames':
            db_inicio_transacao();

            $oDaoAutoriza = new cl_lab_autoriza();
            $oDaoRequisicao = new cl_lab_requisicao();
            $oDaoRequiItem = new cl_lab_requiitem();

            /**
             * Inclui autorização
             */
            $dtAut = isset($oParam->dataAutoriza) ? $oParam->dataAutoriza : date('Y-m-d', db_getsession("DB_datausu"));
            $oDaoAutoriza->la48_i_requisicao = $oParam->iRequisicao;
            $oDaoAutoriza->la48_d_data = $dtAut;
            $oDaoAutoriza->la48_c_hora = db_hora();
            $oDaoAutoriza->la48_i_usuario = db_getsession("DB_id_usuario");
            $oDaoAutoriza->incluir(null);

            if ($oDaoAutoriza->erro_status == "0") {
                throw new Exception("Ocorreu um erro ao salvar a autorização das requisições selecionadas.");
            }

            /**
             * Altera a requisição para autorizada
             */
            $oDaoRequisicao->la22_i_autoriza = 2;
            $oDaoRequisicao->la22_i_codigo = $oParam->iRequisicao;
            $oDaoRequisicao->alterar($oParam->iRequisicao);

            if ($oDaoRequisicao->erro_status == "0") {
                $oErro = new stdClass();
                $oErro->sErro = pg_last_error();
                throw new Exception(_M(MENSAGENS_LAB4_AUTORIZACAO_RPC . 'erro_autorizar_requisicao', $oErro));
            }

            /**
             * Caso o parâmetro Número de Controle Interno da Requisição esteja habilitado,
             * insere número de controle na tabela numerocontroleinternorequisicao
             */

            if ($oParam->parametroAtivo === true) {
                $numeroControleInternoRequisicaoService = new NumeroControleInternoRequisicaoService(
                    NumeroControleInternoRequisicaoRepository::getInstance()
                );

                $parametrosNumeroControleInterno = new stdClass();
                $parametrosNumeroControleInterno->numero = $oParam->numeroControleInterno;
                $parametrosNumeroControleInterno->ano = $oParam->ano;
                $parametrosNumeroControleInterno->codigoRequisicao = $oParam->iRequisicao;

                $numeroControleInternoRequisicaoService->salvar($parametrosNumeroControleInterno);
            }

            /**
             * Busca os itens da requisição e altera a situação para autorizado
             */
            $sExames = implode(", ", $oParam->aCodigosExames);
            $sWhere = " la21_i_requisicao = {$oParam->iRequisicao}";
            $sWhere .= " and la21_i_setorexame in ({$sExames}) ";
            $sWhere .= " and la21_c_situacao = '10 - Nao Digitado'";
            $sSqlItem = $oDaoRequiItem->sql_query_file(null, "la21_i_codigo", null, $sWhere);
            $rsItem = $oDaoRequiItem->sql_record($sSqlItem);
            $iLinhas = $oDaoRequiItem->numrows;

            for ($i = 0; $i < $iLinhas; $i++) {
                $iCodigoItem = db_utils::fieldsMemory($rsItem, $i)->la21_i_codigo;
                $oDaoRequiItem->la21_c_situacao = "20 - Autorizado";
                $oDaoRequiItem->la21_i_codigo = $iCodigoItem;
                $oDaoRequiItem->alterar($iCodigoItem);

                if ($oDaoRequiItem->erro_status == 0) {
                    $oErro = new stdClass();
                    $oErro->sErro = pg_last_error();
                    throw new Exception(_M(MENSAGENS_LAB4_AUTORIZACAO_RPC . 'erro_autorizar_item_requisicao', $oErro));
                }
            }

            $departamento = db_getsession('DB_coddepto');
            $sql = $oDaoRequisicao->sql_query_file($oParam->iRequisicao, 'la22_i_departamento');
            $rs = $oDaoRequisicao->sql_record($sql);
            if ($oDaoRequisicao->numrows > 0) {
                $departamento = db_utils::fieldsMemory($rs, 0)->la22_i_departamento;
            }

            foreach ($oParam->aExames as $oExame) {
                if (empty($oExame->sDataColeta)) {
                    throw new Exception(_M(MENSAGENS_LAB4_AUTORIZACAO_RPC . 'informe_data_coleta'));
                }

                $oDataColeta = new DBDate($oExame->sDataColeta);
                $sDataConvertida = $oDataColeta->convertTo(DBDate::DATA_EN);

                $controleModel = new controleExamesLaboratorio($oExame->iExame, $departamento);
                $infoControle = $controleModel->getInfoControle($sDataConvertida);
                if ($infoControle !== null) {
                    $saldo = $controleModel->getSaldoGasto($infoControle, $sDataConvertida);
                    if ($saldo > $infoControle->la56_n_limite) {
                        $nValorAutorizado = trim(db_formatar($saldo, 'f'));
                        $nLimiteDisponivel = trim(db_formatar($infoControle->la56_n_limite, 'f'));

                        $dao = new cl_lab_exame();
                        $sql = $dao->sql_query_file($controleModel->getExame(), 'la08_c_descr');
                        $rs = $dao->sql_record($sql);
                        if (!$rs) {
                            throw new Exception(
                                "Ocorreu um erro ao buscar os dados do exame: {$controleModel->getExame()}"
                            );
                        }

                        $oErro = (object)array(
                            "valor_disponivel" => $nLimiteDisponivel,
                            "valor_autorizado" => $nValorAutorizado,
                            "exame"            => db_utils::fieldsMemory($rs, 0)->la08_c_descr
                        );
                        throw new Exception(_M(MENSAGENS_LAB4_AUTORIZACAO_RPC . 'saldo_insuficiente', $oErro));
                    }
                }
            }

            $oRetorno->sMensagem = urlencode(_M(MENSAGENS_LAB4_AUTORIZACAO_RPC . 'exames_autorizados'));
            db_fim_transacao(false);
            break;
    }
} catch (Exception $oErro) {
    $oRetorno->iStatus = 2;
    $oRetorno->erro = true;
    $oRetorno->sMensagem = urlencode($oErro->getMessage());
    db_fim_transacao(true);
}
echo $oJson->encode($oRetorno);
