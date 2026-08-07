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
                "o58_anousu = {$anoSessao}",
            );
            if (!empty($parametros->orgao)) {
                $filtros[] = "o58_orgao = {$parametros->orgao}";
            }
            if (!empty($parametros->unidade)) {
                $filtros[] = "o58_unidade = {$parametros->unidade}";
            }
            if (!empty($parametros->funcao)) {
                $filtros[] = "o58_funcao = {$parametros->funcao}";
            }
            if (!empty($parametros->subfuncao)) {
                $filtros[] = "o58_subfuncao = {$parametros->subfuncao}";
            }
            if (!empty($parametros->programa)) {
                $filtros[] = "o58_programa = {$parametros->programa}";
            }
            if (!empty($parametros->projeto)) {
                $filtros[] = "o58_projativ = {$parametros->projeto}";
            }

            $campos = implode(', ', array(
                'o58_coddot as dotacao',
                'fc_estruturaldotacao(o58_anousu, o58_coddot) as estrutural',
                'o58_esferaorcamentaria as esfera_orcamentaria',
            ));

            $where = implode(' and ', $filtros);
            $daoDotacao = new cl_orcdotacao();
            $buscaDotacao = $daoDotacao->sql_query_file(null, null, $campos, 2, $where);
            $resultDotacao = db_query($buscaDotacao);
            if (!$resultDotacao) {
                throw new Exception("Ocorreu um erro ao consultar as dotações.");
            }

            $totalRegistros = pg_num_rows($resultDotacao);
            if ($totalRegistros === 0) {
                throw new Exception("Nenhuma dotação encontrada para o filtro selecionados.");
            }
            $oRetorno->dotacoes = db_utils::getCollectionByRecord($resultDotacao);

            break;

        case 'processar':

            if (empty($parametros->registros)) {
                throw new Exception("Nenhum registro foi selecionado.");
            }

            $daoDotacao = new cl_orcdotacao();
            foreach ($parametros->registros as $stdDotacao) {

                if(empty($stdDotacao->chave_pk_1)){
                    continue;
                }

                $daoDotacao->o58_coddot = $stdDotacao->chave_pk_1;
                $daoDotacao->o58_anousu = $anoSessao;
                $daoDotacao->o58_esferaorcamentaria = $stdDotacao->campos[0]->valor;
                $daoDotacao->alterar($daoDotacao->o58_anousu, $daoDotacao->o58_coddot);
                if ($daoDotacao->erro_status === '0') {
                    throw new Exception("Não foi possível alterar a dotação {$daoDotacao->o58_coddot}.");
                }
            }

            $oRetorno->mensagem = 'Dotações alteradas com sucesso.';
            break;

        default :

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
