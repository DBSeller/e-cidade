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

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/JSON.php"));
require_once(modification("dbforms/db_funcoes.php"));

$parametros = JSON::requestParameters();
$retorno = (object)['erro' => false, 'mensagem' => ''];

$daoConfig = new cl_far_parametros();
$sql = $daoConfig->sql_query2();
$rs = $daoConfig->sql_record($sql);
$config = db_utils::fieldsmemory($rs, 0);

try {
    switch ($parametros->acao) {
        case 'getAvisoRetirada':
            if (!$config->fa02_i_avisoretirada) {
                throw new Exception('Aviso de retirada não configurado.');
            }

            $data = new DateTime(date('Y-m-d', db_getsession("DB_datausu")));
            $data->modify("-{$config->fa02_i_avisoretirada} days");
            $idInstituicao = db_getsession('DB_instit');

            $campos = "fa04_i_codigo,fa04_d_data,fa04_i_unidades,descrdepto";
            $where = [];
            $where[] = "fa04_i_cgsund = {$parametros->idCgs}";
            $where[] = "fa04_d_data >= '{$data->format('Y-m-d')}'";
            $where[] = "instit = {$idInstituicao}";
            $where = implode(' AND ', $where);

            $daoRetirada = new cl_far_retirada();
            $sql = $daoRetirada->sql_query("", $campos, "fa04_d_data desc", $where);
            $rs = $daoRetirada->sql_record($sql);

            if (!$rs) {
                throw new Exception('Paciente sem retiradas.');
            }

            $retorno->retiradas = [];
            $retiradas = db_utils::getCollectionByRecord($rs);

            // Monta um array de objetos indexado pela data da retirada e pelo departamento
            foreach ($retiradas as $retirada) {
                $index = "{$retirada->fa04_i_unidades}{$retirada->fa04_d_data}";
                if (!array_key_exists($index, $retorno->retiradas)) {
                    $retorno->retiradas[$index] = (object) [
                        'data' => db_formatar($retirada->fa04_d_data, 'd'),
                        'departamento' => utf8_encode("{$retirada->fa04_i_unidades} - {$retirada->descrdepto}"),
                        'idsRetirada' => [],
                        'medicamentos' => []
                    ];
                }
                $retorno->retiradas[$index]->idsRetirada[] = $retirada->fa04_i_codigo;
            }
            unset($retiradas);
            $retorno->retiradas = array_values($retorno->retiradas);

            /**
             * Busca todos os medicamentos de cada retirada, buscando também se aquela retirada possui devolução.
             * Caso não tenha itens retira a retirada do array
             */
            foreach ($retorno->retiradas as $key => $retirada) {
                $idsRetiradas = implode(', ', $retirada->idsRetirada);

                $campos = "fa06_i_codigo, m60_descr, fa06_f_quant";
                $where = "fa06_i_retirada in ({$idsRetiradas})";

                $daoRetiradaItens = new cl_far_retiradaitens();
                $sql = $daoRetiradaItens->sql_query("", $campos, "", $where);
                $rs = $daoRetiradaItens->sql_record($sql);

                $itensRetirada = db_utils::getCollectionByRecord($rs);
                $daoDevolucao = new cl_far_devolucaomed();

                /**
                 * Procura se os itens da retirada tenha devolução, caso tenha, subtrai a quantidade dos itens.
                 * Caso a quantidade final seja menor ou igual a zero retira o item do array
                 */
                foreach ($itensRetirada as $key => $itemRetirada) {
                    $itemRetirada->m60_descr = utf8_encode($itemRetirada->m60_descr);
                    $campos = 'sum(fa23_i_quantidade) as quantidade';
                    $where = "fa23_i_retiradaitens = {$itemRetirada->fa06_i_codigo}";
                    $groupBy = "group by fa23_i_retiradaitens";
                    $sql = $daoDevolucao->sql_query_file('', $campos, '', "{$where} {$groupBy}");
                    $rs = $daoDevolucao->sql_record($sql);

                    if (!$rs) {
                        continue;
                    }
                    $itemDevolucao = db_utils::fieldsMemory($rs, 0);

                    $itemRetirada->fa06_f_quant -= $itemDevolucao->quantidade;

                    if ($itemRetirada->fa06_f_quant <= 0) {
                        unset($itensRetirada[$key]);
                        continue;
                    }
                }
                if (count($itensRetirada) == 0) {
                    unset($retorno->retiradas[$key]);
                    continue;
                }
                $retirada->medicamentos = array_values($itensRetirada);
            }

            if (count($retorno->retiradas) == 0) {
                throw new Exception('Paciente efetuou retiradas, porém foram devolvidas e/ou canceladas.');
            }

            $retorno->periodo = $config->fa02_i_avisoretirada;
            $retorno->retiradas = array_values($retorno->retiradas);
            break;
        case 'getDetalhesMedicamento':
            $dao = new cl_far_matersaude;
            $sql = $dao->sql_query($parametros->medicamento, 'fa20_c_prescricao');
            $rs = $dao->sql_record($sql);

            if ($dao->numrows == 1) {
                $retorno->detalhes = db_utils::fieldsMemory($rs, 0);
            }
            break;
        case 'validaCgsMunicipio':
            $retorno->validar = $config->fa02_alerta_nao_morador === 't';
            if ($retorno->validar) {
                $dao = new cl_cgs_und;
                $sql = $dao->sql_query_file($parametros->idCgs, 'z01_codigoibge, z01_v_munic');
                $rs = $dao->sql_record($sql);
                if (!$rs) {
                    throw  new Exception('Erro ao buscar paciente.');
                }

                $dados = db_utils::fieldsMemory($rs, 0);
                $retorno->validar = $config->fa02_ibge_municipio != $dados->z01_codigoibge;
                $retorno->municipio = "{$dados->z01_v_munic}(IBGE: {$dados->z01_codigoibge})";
            }
            break;

        case 'getTiposDeReceita':
            $dao = new cl_far_tiporeceita();
            $campos = 'fa03_i_codigo AS codigo, fa03_c_descr AS descricao';
            $sql = $dao->sql_query_file('', $campos, 'fa03_c_descr', 'fa03_i_ativa = 1');
            $result = $dao->sql_record($sql);
            if (!$result) {
                throw  new Exception('Erro ao buscar tipos de receitas.');
            }

            $retorno->receitas = db_utils::getCollectionByRecord($result);
            break;
        default:
            break;
    }
} catch (Exception $erro) {
    $retorno->mensagem = $erro->getMessage();
    $retorno->erro = true;
}

db_fim_transacao($retorno->erro);
echo JSON::create()->stringify($retorno);
