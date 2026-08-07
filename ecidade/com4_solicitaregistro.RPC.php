<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2016  DBselller Servicos de Informatica
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
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/JSON.php"));
require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_conecta." . "php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("std/db_stdClass.php"));
require_once(modification("classes/db_solicita_classe.php"));
require_once(modification("classes/db_solicitem_classe.php"));
require_once(modification("classes/db_solicitemvinculo_classe.php"));
require_once(modification("classes/db_solicitavinculo_classe.php"));

use Exception as Exception;

$clsolicita = new cl_solicita();
$clsolicitavinculo = new cl_solicitavinculo();
$clsolicitem = new cl_solicitem();
$clsolicitemvinculo = new cl_solicitemvinculo();
$clsolicitempcmater = new cl_solicitempcmater();
$clsolicitemunid = new cl_solicitemunid();
$clsolicitemregistropreco = new cl_solicitemregistropreco();

$oJson = new services_json();
$oParam = $oJson->decode(db_stdClass::db_stripTagsJson(str_replace("\\", "", $_POST["json"])));

$oRetorno = new stdClass();
$oRetorno->status = 1;
$oRetorno->mensagem = '';
$oRetorno->erro = false;
$sMensagem = "";

function buscaEstimativaOriginal($options = [])
{
    $numeroCompilacao = $options['numero_compilacao'] ?: 0;
    $codigoDepartamento = $options['codigo_departamento'] ?: 0;

    $sqlBuscaRegistroDePrecos = "
    SELECT solicita.* FROM solicitavinculo compilacao
        JOIN solicitavinculo estimativa ON compilacao.pc53_solicitapai = estimativa.pc53_solicitapai
        JOIN solicita ON estimativa.pc53_solicitafilho = pc10_numero
        LEFT JOIN solicitaanulada ON pc10_numero = pc67_solicita
        WHERE compilacao.pc53_solicitafilho = $numeroCompilacao
            AND pc10_solicitacaotipo = 4
            AND pc67_sequencial is null
            AND pc10_depto = $codigoDepartamento
        ORDER BY pc10_numero;
    ";
    $registroDePrecos = db_query($sqlBuscaRegistroDePrecos);

    if (!empty(pg_num_rows($registroDePrecos))) {
        return db_utils::fieldsMemory($registroDePrecos, 0);
    }

    return false;
}

function buscaInstituicao($options = [])
{
    $codigoDepartamento = $options['codigo_departamento'] ?: 0;
    $sqlBuscaDepartamento = "
        SELECT * FROM db_depart
        WHERE coddepto = $codigoDepartamento;
    ";
    $dadosDepartamento = db_query($sqlBuscaDepartamento);

    if (!empty(pg_num_rows($dadosDepartamento))) {
        return db_utils::fieldsMemory($dadosDepartamento, 0);
    }

    return false;
}

function verificaExisteEstimativa($options = [])
{
    $numeroCompilacao = $options['numero_compilacao'] ?: 0;
    $codigoDepartamento = $options['codigo_departamento'] ?: 0;

    $sqlVerificaExisteEstimativa = "
        SELECT * FROM solicitavinculo compilacao
        JOIN solicitavinculo estimativa ON compilacao.pc53_solicitapai = estimativa.pc53_solicitapai
        JOIN solicita ON estimativa.pc53_solicitafilho = pc10_numero
        LEFT JOIN solicitaanulada ON pc10_numero = pc67_solicita
        WHERE
            compilacao.pc53_solicitafilho = $numeroCompilacao
            AND pc10_solicitacaotipo = 4
            AND pc67_sequencial IS NULL
            AND pc10_depto = $codigoDepartamento;
    ";

    $existeEstimativa = db_query($sqlVerificaExisteEstimativa);

    if (!empty(pg_num_rows($existeEstimativa))) {
        return true;
    }

    return false;
}

function geraEstimativa($dados = [])
{
    $estimativaOriginal = $dados['estimativa_original'] ?: 0;
    $idUsuario = $dados['id_usuario'] ?: 0;
    $codigoInstituicao = $dados['codigo_instituicao'] ?: 0;
    $codigoDepartamento = $dados['codigo_departamento'] ?: 0;

    $estimativa = new cl_solicita();
    $estimativa->pc10_data = $estimativaOriginal->pc10_data;
    $estimativa->pc10_resumo = pg_escape_string($estimativaOriginal->pc10_resumo);
    $estimativa->pc10_depto = $codigoDepartamento;
    $estimativa->pc10_log = $estimativaOriginal->pc10_log;
    $estimativa->pc10_instit = $codigoInstituicao;
    $estimativa->pc10_correto = $estimativaOriginal->pc10_correto == 't' ? 'true' : 'false';
    $estimativa->pc10_login = $idUsuario;
    $estimativa->pc10_solicitacaotipo = $estimativaOriginal->pc10_solicitacaotipo;
    $estimativa->incluir(null);

    if ($estimativa->erro_status == '0') {
        throw new Exception($estimativa->erro_msg);
    }

    return $estimativa;
}

function buscaVinculoSolicita($dados = [])
{
    $estimativaOriginal = $dados['estimativa_original'] ?: 0;
    $numeroNovaEstimativa = $dados['numero_nova_estimativa'] ?: 0;

    $sqlSolcitaVinculos = "
        CREATE TEMP TABLE w_solicitavinculo AS
            SELECT * FROM solicitavinculo
            WHERE
                pc53_solicitafilho = {$estimativaOriginal->pc10_numero};

        UPDATE w_solicitavinculo
        SET
            pc53_sequencial = nextval('solicitavinculo_pc53_sequencial_seq'),
            pc53_solicitafilho = {$numeroNovaEstimativa};
    ";

    $solicitaVinculos = db_query($sqlSolcitaVinculos);
    if (empty($solicitaVinculos)) {
        throw new Exception(
            "Erro ao buscar vinculo do registro de preço\nContate o suporte!"
        );
    }

    $vinculosSolicita = db_query("select * from w_solicitavinculo");
    return db_utils::fieldsMemory($vinculosSolicita, 0);
}

function salvaSolicitaVinculo($dados = [])
{
    $vinculo = $dados['vinculo'] ?: 0;

    $clsolicitavinculo = new cl_solicitavinculo();
    $clsolicitavinculo->pc53_sequencial = $vinculo->pc53_sequencial;
    $clsolicitavinculo->pc53_solicitapai = $vinculo->pc53_solicitapai;
    $clsolicitavinculo->pc53_solicitafilho = $vinculo->pc53_solicitafilho;
    $clsolicitavinculo->incluir($vinculo->pc53_sequencial);

    if ($clsolicitavinculo->erro_status == '0') {
        throw new Exception($clsolicitavinculo->erro_msg);
    }

    return $clsolicitavinculo;
}

function buscaSolicitemEstimativaOriginal()
{
    $sqlBuscaSolicitem = "
        SELECT * FROM w_solicitem
        JOIN solicitempcmater ON solicitempai = pc16_solicitem
        LEFT JOIN solicitemunid ON solicitempai = pc17_codigo
        JOIN solicitemregistropreco ON solicitempai = pc57_solicitem
    ";

    $solicitem = db_query($sqlBuscaSolicitem);
    if (empty($solicitem)) {
        throw new Exception("Não foi possível buscar os itens da estimativa!");
    }

    return db_utils::getCollectionByRecord($solicitem);
}

function criaTabelaNovosSolicitem($dados = [])
{
    $estimativaOriginal = $dados['estimativa_original'] ?: 0;
    $numeroNovaEstimativa = $dados['numero_nova_estimativa'] ?: 0;

    $sqlTabelaNovosSolicitem = "
        CREATE TEMP TABLE w_solicitem AS
            SELECT
                *,
                pc11_codigo solicitempai
            FROM solicitem
            WHERE
                pc11_numero = {$estimativaOriginal->pc10_numero};

        UPDATE w_solicitem
        SET
            pc11_codigo = nextval('solicitem_pc11_codigo_seq'),
            pc11_quant  = 0,
            pc11_numero = {$numeroNovaEstimativa};
    ";

    $tabelaNovosSolicitem = db_query($sqlTabelaNovosSolicitem);
    if (empty($tabelaNovosSolicitem)) {
        throw new Exception(
            "Não foi possível buscar os dados dos itens"
        );
    }

    return true;
}

function criaTabelaSolicitemRelacionamentoVinculos($dados = [])
{
    $estimativaOriginal = $dados['estimativa_original'] ?: 0;
    $sqlTabelaSolicitemRelacionamento = "
        CREATE TEMP TABLE relacionamento_solicitem AS
        SELECT
            w_solicitem.pc11_codigo codigo_novo_solicitem,
            w_solicitem.pc11_seq novo_seq,
            solicitem.pc11_codigo codigo_solicitem_original,
            solicitem.pc11_seq seq_original,
            pc55_solicitempai,
            pc55_solicitemfilho
        FROM w_solicitem
        LEFT JOIN solicitem ON w_solicitem.pc11_seq = solicitem.pc11_seq
        JOIN solicitemvinculo ON pc55_solicitempai = solicitem.pc11_codigo
        WHERE
            solicitem.pc11_numero = {$estimativaOriginal->pc10_numero};
    ";

    $tabelaSolicitemRelacionamento = db_query($sqlTabelaSolicitemRelacionamento);
    if (empty($tabelaSolicitemRelacionamento)) {
        throw new Exception("Não foi possível buscar os vínculos dos itens!");
    }

    return true;
}

function buscaDadosSolicitemVinculos()
{
    $sqlBuscaDadosSolcitemVinculos = "
        SELECT * FROM relacionamento_solicitem;
    ";

    $solicitemVinculos = db_query($sqlBuscaDadosSolcitemVinculos);
    return db_utils::getCollectionByRecord($solicitemVinculos);
}

try {
    db_inicio_transacao();

    switch ($oParam->exec) {
        case 'lSalvar':
            $options = [];
            $options['numero_compilacao'] = $oParam->iSolicita;
            $options['codigo_departamento'] = db_getsession('DB_coddepto');
            $dadosEstimativaOriginal = buscaEstimativaOriginal($options);

            if (empty($dadosEstimativaOriginal)) {
                throw new Exception(
                    "Erro ao buscar registro de preços.\n Contate o suporte!"
                );
            }

            $options = [];
            $options['codigo_departamento'] = $oParam->iDepart;
            $dadosInstituicao = buscaInstituicao($options);

            if (empty($dadosInstituicao)) {
                throw new Exception(
                    "Erro ao buscar instituição do departamento: {$oParam->iDepart}!"
                );
            }

            $options = [];
            $options['numero_compilacao'] = $oParam->iSolicita;
            $options['codigo_departamento'] = $oParam->iDepart;
            $existeEstimativa = verificaExisteEstimativa($options);

            if ($existeEstimativa) {
                $mensagemErro = "Já existe uma estimativa para a compilação ";
                $mensagemErro .= "{$oParam->iSolicita} no departamento {$oParam->iDepart}!";
                throw new Exception($mensagemErro);
            }

            $dados = [];
            $dados['codigo_departamento'] = $oParam->iDepart;
            $dados['estimativa_original'] = $dadosEstimativaOriginal;
            $dados['id_usuario'] = db_getsession('DB_id_usuario');
            $dados['codigo_instituicao'] = $dadosInstituicao->instit;

            $novaEstimativa = geraEstimativa($dados);
            $numeroEstimativa = $novaEstimativa->pc10_numero;

            $dados['numero_nova_estimativa'] = $novaEstimativa->pc10_numero;
            $solicitaVinculo = buscaVinculoSolicita($dados);

            $dados['vinculo'] = $solicitaVinculo;
            $novoSolicitaVinculo = salvaSolicitaVinculo($dados);

            criaTabelaNovosSolicitem($dados);
            criaTabelaSolicitemRelacionamentoVinculos($dados);

            $solicitemEstimativaOriginal = buscaSolicitemEstimativaOriginal();
            foreach ($solicitemEstimativaOriginal as $solicitem) {
                // Solicitem
                $clsolicitem->pc11_codigo = $solicitem->pc11_codigo;
                $clsolicitem->pc11_numero = $solicitem->pc11_numero;
                $clsolicitem->pc11_seq = $solicitem->pc11_seq;
                $clsolicitem->pc11_quant = $solicitem->pc11_quant;
                $clsolicitem->pc11_vlrun = $solicitem->pc11_vlrun;
                $clsolicitem->pc11_prazo = $solicitem->pc11_prazo;
                $clsolicitem->pc11_resum = pg_escape_string($solicitem->pc11_resum);
                $clsolicitem->pc11_just = pg_escape_string($solicitem->pc11_just);
                $clsolicitem->pc11_liberado = $solicitem->pc11_liberado;
                $clsolicitem->pc11_servicoquantidade = $solicitem->pc11_servicoquantidade;
                $clsolicitem->incluir($solicitem->pc11_codigo);

                if ($clsolicitem->erro_status == '0') {
                    throw new Exception($clsolicitem->erro_msg);
                }

                // Registro de preços
                $clsolicitemregistropreco->pc57_solicitem = $solicitem->pc11_codigo;
                $clsolicitemregistropreco->pc57_quantmin = $solicitem->pc57_quantmin;
                $clsolicitemregistropreco->pc57_quantmax = $solicitem->pc57_quantmax;
                $clsolicitemregistropreco->pc57_itemorigem = $solicitem->pc57_itemorigem;
                $clsolicitemregistropreco->pc57_ativo = $solicitem->pc57_ativo;
                $clsolicitemregistropreco->pc57_quantidadeexecedente = 0;
                $clsolicitemregistropreco->incluir(null);

                if ($clsolicitemregistropreco->erro_status == '0') {
                    throw new Exception($clsolicitemregistropreco->erro_msg);
                }

                // Solicitem unidade
                if ($solicitem->pc17_unid !== null) {
                    $clsolicitemunid->pc17_unid = $solicitem->pc17_unid;
                    $clsolicitemunid->pc17_quant = $solicitem->pc17_quant;
                    $clsolicitemunid->pc17_codigo = $solicitem->pc11_codigo;
                    $clsolicitemunid->incluir($solicitem->pc11_codigo);

                    if ($clsolicitemunid->erro_status == '0') {
                        throw new Exception($clsolicitemunid->erro_msg);
                    }
                }

                // Solicitem - Material
                $clsolicitempcmater->pc16_codmater = $solicitem->pc16_codmater;
                $clsolicitempcmater->pc16_solicitem = $solicitem->pc11_codigo;
                $clsolicitempcmater->incluir(
                    $solicitem->pc16_codmater,
                    $solicitem->pc11_codigo
                );

                if ($clsolicitempcmater->erro_status == '0') {
                    throw new Exception($clsolicitempcmater->erro_msg);
                }
            }

            $dadosSolicitemVinculos = buscaDadosSolicitemVinculos();
            foreach ($dadosSolicitemVinculos as $vinculo) {
                if (!empty($vinculo->pc55_solicitemfilho)) {
                    // SolicitemVinculo
                    $clsolicitemvinculo->pc55_solicitempai = $vinculo->codigo_novo_solicitem;
                    $clsolicitemvinculo->pc55_solicitemfilho = $vinculo->pc55_solicitemfilho;
                    $clsolicitemvinculo->incluir(null);

                    if ($clsolicitemvinculo->erro_status == '0') {
                        throw new Exception($clsolicitemvinculo->erro_msg);
                    }
                }
            }

            $oRetorno->iSolicita = $dados['numero_nova_estimativa'];
            break;
        default:
            break;
    }

    db_fim_transacao(false);
} catch (Exception $e) {
    db_fim_transacao(true);
    $oRetorno->mensagem = $e->getMessage();
    $oRetorno->erro = true;
}

$oRetorno->mensagem = urlencode($oRetorno->mensagem);
echo $oJson->encode($oRetorno);
