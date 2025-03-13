<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (c) 2018  DBSeller Servicos de Informatica
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

/**
 * @fileoverview Controla Ações no cadastro de contrução da obra
 * @version   $Revision: 1.6 $
 * @revision  $Author: dbjeferson.belmiro $
 */
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/JSON.php"));
require_once(modification("dbforms/db_funcoes.php"));

$oJson = new services_json();
$oParam = $oJson->decode(str_replace("\\", "", $_POST["json"]));

$oRetorno = new stdClass();
$oRetorno->iStatus = 1;
$oRetorno->sMessage = '';

$aDadosRetorno = array();
/**
 * Camada de Tentativas do RPC
 */
try {
    db_inicio_transacao();

    switch ($oParam->sExec) {
        case "getCaracteristicasConstrucoes":
            $oDaoCaracter = new cl_caracter();
            $oDaoIptuConstrObrasConstr = new cl_iptuconstrobrasconstr();
            $oDaoObrasConstrCaracter = new cl_obrasconstrcaracter();

            $sSqlCaracter = $oDaoObrasConstrCaracter->sql_query_selecoesCaracteristicas($oParam->iCodigoObra);
            $rsCaracter = db_query($sSqlCaracter);

            if (!$rsCaracter) {
                throw new Exception(_M('tributario.projetos.pro1_obrasconstr.erro_caracteristicas_grupo_construcoes'));
            }

            $aCaracter = db_utils::getCollectionByRecord($rsCaracter, false, false, true);
            $aDadosCaracter = array();

            /**
             * Cria nova estrutura do array a ser retornado, para evitar dados duplicados
             */
            foreach ($aCaracter as $oCaracter) {
                $oDadosCaracter = new stdClass;
                $oDadosCaracter->iCodigoGrupo = $oCaracter->j32_grupo;
                $oDadosCaracter->sDescricao = $oCaracter->j32_descr;

                $oDetalheCaracteristica = new stdClass();
                $oDetalheCaracteristica->j31_codigo = $oCaracter->j31_codigo;
                $oDetalheCaracteristica->j31_descr = $oCaracter->j31_descr;
                $oDetalheCaracteristica->lSelecionada = $oCaracter->selecionada;

                if (isset($aDadosCaracter[$oCaracter->j32_grupo])) {
                    $oDadosExistentes = $aDadosCaracter[$oCaracter->j32_grupo];
                    $oDadosCaracter->aCaracteristicas = $oDadosExistentes->aCaracteristicas;
                    $oDadosCaracter->aCaracteristicas[] = $oDetalheCaracteristica;
                } else {
                    $oDadosCaracter->aCaracteristicas[] = $oDetalheCaracteristica;
                }

                $aDadosCaracter[$oCaracter->j32_grupo] = $oDadosCaracter;
            }

            $oRetorno->aDadosCaracteristicas = $aDadosCaracter;

            break;

        case "salvar":
            $oDaoObrasConstr = new cl_obrasconstr();
            $oDaoObrasEnder = new cl_obrasender();
            $oDaoObrasConstrCaracter = new cl_obrasconstrcaracter();

            $oDaoObrasConstr->ob08_codobra = $oParam->oDados->ob08_codobra;
            $oDaoObrasConstr->ob08_ocupacao = $oParam->oDados->ob08_ocupacao;
            $oDaoObrasConstr->ob08_tipoconstr = $oParam->oDados->ob08_tipoconstr;
            $oDaoObrasConstr->ob08_area = $oParam->oDados->ob08_area;
            $oDaoObrasConstr->ob08_tipolanc = $oParam->oDados->ob08_tipolanc;

            if (empty($oParam->oDados->ob08_codconstr)) {
                $oDaoObrasConstr->incluir("");
            } else {
                $oDaoObrasConstr->ob08_codconstr = $oParam->oDados->ob08_codconstr;
                $oDaoObrasConstr->alterar($oParam->oDados->ob08_codconstr);
            }

            if ((int)$oDaoObrasConstr->erro_status == 0) {
                $oParms = new stdClass();
                $oParms->sErroBanco = $oDaoObrasConstr->erro_msg;
                throw new Exception(_M('tributario.projetos.pro1_obrasconstr.erro_salvar_construcao', $oParms));
            }

            /**
             * Manutenção na tabela obrasender
             */
            $oDaoObrasEnder->ob07_codconstr = $oDaoObrasConstr->ob08_codconstr;
            $oDaoObrasEnder->ob07_codobra = $oParam->oDados->ob08_codobra;
            $oDaoObrasEnder->ob07_lograd = $oParam->oDados->ob07_lograd;
            $oDaoObrasEnder->ob07_numero = $oParam->oDados->ob07_numero;
            $oDaoObrasEnder->ob07_compl = $oParam->oDados->ob07_compl;
            $oDaoObrasEnder->ob07_bairro = $oParam->oDados->ob07_bairro;
            $oDaoObrasEnder->ob07_areaatual = $oParam->oDados->ob07_areaatual;
            $oDaoObrasEnder->ob07_unidades = $oParam->oDados->ob07_unidades;
            $oDaoObrasEnder->ob07_pavimentos = $oParam->oDados->ob07_pavimentos;
            $oDaoObrasEnder->ob07_inicio = $oParam->oDados->ob07_inicio;
            $oDaoObrasEnder->ob07_fim = $oParam->oDados->ob07_fim;
            $oDaoObrasEnder->ob07_areacoberta = $oParam->oDados->ob07_areacoberta;
            $oDaoObrasEnder->ob07_areadescoberta = $oParam->oDados->ob07_areadescoberta;

            if (empty($oParam->oDados->ob08_codconstr)) {
                $oDaoObrasEnder->incluir($oDaoObrasConstr->ob08_codconstr);
            } else {
                $oDaoObrasEnder->alterar_alternativo($oDaoObrasConstr->ob08_codconstr);
            }

            if ((int)$oDaoObrasEnder->erro_status == 0) {
                $oParms = new stdClass();
                $oParms->sErroBanco = $oDaoObrasEnder->erro_msg;
                throw new Exception(_M('tributario.projetos.pro1_obrasconstr.erro_salvar_endereco', $oParms));
            }

            /**
             * Valida se houveram modificações nas caracteristicas
             */
            if (isset($oParam->oDados->oCaracteristicas)) {
                $lExclusao = $oDaoObrasConstrCaracter->excluir(null,
                  "ob34_obrasconstr = {$oDaoObrasConstr->ob08_codconstr}");

                if ($lExclusao) {
                    foreach ($oParam->oDados->oCaracteristicas as $iGrupo => $iCaracteristica) {
                        if ((int)$iCaracteristica != 0) {
                            $oDaoObrasConstrCaracter = new cl_obrasconstrcaracter();
                            $oDaoObrasConstrCaracter->ob34_obrasconstr = $oDaoObrasConstr->ob08_codconstr;
                            $oDaoObrasConstrCaracter->ob34_caracter = $iCaracteristica;
                            $oDaoObrasConstrCaracter->incluir("");

                            if ((int)$oDaoObrasConstrCaracter->erro_status == 0) {
                                $oParms = new stdClass();
                                $oParms->sErroBanco = $oDaoObrasEnder->erro_msg;
                                throw new Exception(_M('tributario.projetos.pro1_obrasconstr.erro_salvar_caracteristica',
                                  $oParms));
                            }
                        }
                    }
                } else {
                    $oParms = new stdClass();
                    $oParms->sErroBanco = $oDaoObrasEnder->erro_msg;
                    throw new Exception(_M('tributario.projetos.pro1_obrasconstr.erro_excluir_caracteristica',
                      $oParms));
                }
            }

            $oRetorno->sMessage = $oDaoObrasConstr->erro_msg;
            break;

        case "excluir":
            $oDaoObrasConstr = new cl_obrasconstr();
            $oDaoObrasEnder = new cl_obrasender();
            $oDaoObrasConstrCaracter = new cl_obrasconstrcaracter();
            $oDaoObrasAlvara = new cl_obrasalvara();

            /**
             * Valida a existencia de alvara para a obra
             */
            $sSqlObrasAlvara = $oDaoObrasAlvara->sql_query_file($oParam->iCodigoObra);
            $rsObrasAlavara = $oDaoObrasAlvara->sql_record($sSqlObrasAlvara);

            if ($oDaoObrasAlvara->numrows != 0) {
                throw new Exception(_M('tributario.projetos.pro1_obrasconstr.obra_com_alvara_liberado'));
            }

            /**
             * Tenta excluir registros da caracteristicas da construção
             */
            $oDaoObrasConstrCaracter->excluir(null, "ob34_obrasconstr = {$oParam->iCodigoConstrucao}");

            if ((int)$oDaoObrasConstrCaracter->erro_status == 0) {
                throw new Exception($oDaoObrasConstrCaracter->erro_msg);
            }

            /**
             * Tenta excluir os Registros do endereço da obra
             */
            $oDaoObrasEnder->excluir($oParam->iCodigoConstrucao);

            if ((int)$oDaoObrasEnder->erro_status == 0) {
                throw new Exception($oDaoObrasEnder->erro_msg);
            }

            /**
             * Tenta excluir os Dados da construção
             */
            $oDaoObrasConstr->excluir($oParam->iCodigoConstrucao);

            if ((int)$oDaoObrasConstr->erro_status == 0) {
                throw new Exception($oDaoObrasConstr->erro_msg);
            }

            $oRetorno->sMessage = _M('tributario.projetos.pro1_obrasconstr.construcao_excluida');

            break;

        default:
            throw new Exception(_M('tributario.projetos.pro1_obrasconstr.nenhum_opcao_definida'));
            break;
    }

    $oRetorno->sMessage = urlencode($oRetorno->sMessage);
    db_fim_transacao();
} catch (Exception $eErro) {
    db_fim_transacao(true);
    $oRetorno->iStatus = 2;
    $oRetorno->sMessage = urlencode($eErro->getMessage());
}

echo $oJson->encode($oRetorno);
