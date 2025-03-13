<?php
/**
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

use ECidade\Financeiro\Contabilidade\LancamentoContabil\Retificacao\AlteracaoLancamento;
use ECidade\Financeiro\Contabilidade\LancamentoContabil\Retificacao\InclusaoLancamento;

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("dbforms/db_funcoes.php"));
require_once(modification("libs/JSON.php"));
require_once(modification("libs/db_libcontabilidade.php"));

$parametros = JSON::create()->parse(str_replace('\\', "", $_POST["json"]));
$oRetorno = new stdClass();
$oRetorno->mensagem = '';
$oRetorno->erro = false;

$instituicaoSessao = db_getsession('DB_instit');
$anoSessao = db_getsession('DB_anousu');
try {
    switch ($parametros->exec) {
        case 'consulta':
            $filtros = array(
                "c60_anousu = {$anoSessao}",
            );
            if (!empty($parametros->estrutural)) {
                $filtros[] = "c60_estrut like '{$parametros->estrutural}%'";
            }

            $campos = implode(
                ', ',
                array(
                    'c60_codcon as conta',
                    'c60_estrut as estrutural',
                    'c60_descr as descricao',
                    'c60_identificadoresultadoprimario as indicador_superavit',
                )
            );

            $where = implode(' and ', $filtros);
            $daoConplanoOrcamento = new cl_conplanoorcamento();
            $sqlConta = $daoConplanoOrcamento->sql_query_file(null, null, $campos, 2, $where);
            $rsConta = db_query($sqlConta);
            if (!$rsConta) {
                die($sqlConta);
                throw new Exception("Ocorreu um erro ao consultar as contas.");
            }

            $totalRegistros = pg_num_rows($rsConta);
            if ($totalRegistros === 0) {
                throw new Exception("Nenhuma conta encontrada para o filtro selecionados.");
            }
            $oRetorno->contas = db_utils::getCollectionByRecord($rsConta);

            break;

        case 'processar':
            if (empty($parametros->registros)) {
                throw new Exception("Nenhum registro foi selecionado.");
            }

            $daoConplanoOrcamento = new cl_orcdotacao();
            foreach ($parametros->registros as $stdConta) {
                if (empty($stdConta->chave_pk_1)) {
                    continue;
                }
                $valorIndicador = $stdConta->campos[0]->valor;
                if (empty($valorIndicador)) {
                    $valorIndicador = 'null';
                }
                $updateConplano = "update contabilidade.conplanoorcamento set ";
                $updateConplano .= " c60_identificadoresultadoprimario = {$valorIndicador} ";
                $updateConplano .= " where c60_anousu >= {$anoSessao} ";
                $updateConplano .= "   and c60_codcon = {$stdConta->chave_pk_1} ";
                $rsUpdate = db_query($updateConplano);
                if (!$rsUpdate) {
                    throw new \Exception("Erro ao salvar conta {$stdConta->chave_pk_1}");
                }
            }

            $oRetorno->mensagem = 'Contas alteradas com sucesso.';
            break;

        default:
            throw new \Exception('Metodo ' . $parametros->exec . ' não existe;');
            break;
    }
    db_fim_transacao(false);
} catch (Exception $oErro) {
    db_fim_transacao(true);
    $oRetorno->erro = true;
    $oRetorno->mensagem = $oErro->getMessage();
}
echo JSON::create()->stringify($oRetorno);
