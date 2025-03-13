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


    $daoConDataconf = new cl_condataconf();
    $sqlDadosContabilidade = $daoConDataconf->sql_query_file($anoSessao, $instituicaoSessao);
    $resDadosContabilidade = db_query($sqlDadosContabilidade);
    if (!$resDadosContabilidade) {
        throw new Exception('Não foi possível conferir se o período contábil está aberto.');
    }

    if (pg_num_rows($resDadosContabilidade) > 0) {
        $mensagem  = "O período contábil para a instituição logada está fechada. Para utilizar esta rotina é necessário";
        $mensagem .= " abrir o período contábil através do menu abaixo:\n\n";
        $mensagem .= "Procedimentos > Utilitários da Contabilidade > Encerramento de Período Contábil";
        throw new Exception($mensagem);
    }


    switch ($parametros->exec) {
        case 'consultaDespesa':
            $dataInicial = null;
            $dataFinal = null;

            $filtros = [];
            $filtros[] = "o58_anousu = {$anoSessao}";
            $filtros[] = "e60_instit = {$instituicaoSessao}";

            if (!empty($parametros->data_inicial)) {
                $dataInicial = new DBDate($parametros->data_inicial);
                $filtros[] = "e60_emiss >= '{$dataInicial->getDate()}'";
            }

            if (!empty($parametros->data_final)) {
                $dataFinal = new DBDate($parametros->data_final);
                $filtros[] = "e60_emiss <= '{$dataFinal->getDate()}'";
            }


            if (!empty($parametros->recurso)) {
                $filtros[] = "o58_codigo = {$parametros->recurso}";
            }

            if (!empty($parametros->empenho)) {

                $empenhoExplode = explode('/', $parametros->empenho);
                $anoPesquisa = $anoSessao;
                if (count($empenhoExplode) == 2) {
                    $anoPesquisa = $empenhoExplode[1];
                }
                $filtros[] = "e60_codemp = '{$empenhoExplode[0]}' and e60_anousu = {$anoPesquisa}";
            }


            $campos = implode(
                ', ',
                array(
                    "e60_codemp||'/'||e60_anousu as numero",
                    'e60_numemp as seq_empenho',
                    "fc_estruturaldotacao({$anoSessao},o58_coddot) as dotacao",
                    'e60_vlremp as valor',
                    '(select o206_complementorecurso
                        from origemcomplementorecurso
                       where o206_numero = e60_numemp
                         and o206_origem = 1 limit 1) as codigo',
                )
            );

            $where = implode(' and ', $filtros);
            $daoEmpenho = new cl_empempenho();
            $sqlEmpenhos = $daoEmpenho->sql_query_buscaempenhos(null, $campos, 2, $where);
            $resBusca = db_query($sqlEmpenhos);
            if (!$resBusca) {
                throw new Exception("Ocorreu um erro ao consultar as depesas.");
            }

            $totalRegistros = pg_num_rows($resBusca);
            if ($totalRegistros === 0) {
                throw new Exception("Nenhuma despesa encontrada para o filtro selecionados.");
            }

            for ($row = 0; $row < $totalRegistros; $row++) {
                $linha = db_utils::fieldsMemory($resBusca, $row);
                $linha->valor = "R$ ".trim(db_formatar($linha->valor, 'f'));
                $oRetorno->registros[] = $linha;
            }

            break;

        case 'consultaReceita':
            $dataInicial = null;
            $dataFinal = null;

            $filtros = [];
            $filtros[] = "o70_anousu = {$anoSessao}";
            $filtros[] = "o70_instit = {$instituicaoSessao}";

            if (!empty($parametros->data_inicial)) {
                $dataInicial = new DBDate($parametros->data_inicial);
                $filtros[] = "c70_data >= '{$dataInicial->getDate()}'";
            }

            if (!empty($parametros->data_final)) {
                $dataFinal = new DBDate($parametros->data_final);
                $filtros[] = "c70_data <= '{$dataFinal->getDate()}'";
            }

            if (!empty($parametros->recurso)) {
                $filtros[] = "o70_codigo = {$parametros->recurso}";
            }

            if (!empty($parametros->receita)) {
                $filtros[] = "o70_codrec = {$parametros->receita}";
            }


            $campos = implode(
                ', ',
                array(
                    "c70_codlan as lancamento",
                    'c70_valor as valor',
                    "fc_estruturalreceita(".db_getsession("DB_anousu").",orcreceita.o70_codrec) || ' - ' ||o57_descr as receita",
                    '(select o201_complemento
                        from conlancamcomplementorecurso
                             join conlancamrec on c74_codlan = o201_codlan
                       where o201_codlan = c70_codlan
                       limit 1) as codigo',
                )
            );

            $where = implode(' and ', $filtros);
            $daoLancamentoReceita = new cl_conlancamrec();
            $sqlReceita = $daoLancamentoReceita->sql_query_dados_receita(null, $campos, 1, $where);
            $resBusca = db_query($sqlReceita);
            if (!$resBusca) {
                throw new Exception("Ocorreu um erro ao consultar as receitas.");
            }

            $totalRegistros = pg_num_rows($resBusca);
            if ($totalRegistros === 0) {
                throw new Exception("Nenhuma receita encontrada para o filtro selecionados.");
            }

            for ($row = 0; $row < $totalRegistros; $row++) {
                $linha = db_utils::fieldsMemory($resBusca, $row);
                $linha->valor = "R$ ".trim(db_formatar($linha->valor, 'f'));
                $oRetorno->registros[] = $linha;
            }
            break;

        case 'processarDespesa':
            if (empty($parametros->registros)) {
                throw new Exception("Nenhum registro foi selecionado.");
            }

            foreach ($parametros->registros as $stdEmpenho) {
                if (empty($stdEmpenho->chave_pk_1)) {
                    continue;
                }
                $empenhoFinanceiro = EmpenhoFinanceiroRepository::getEmpenhoFinanceiroPorNumero($stdEmpenho->chave_pk_1);
                $complemento = $stdEmpenho->campos[0]->valor;
                /* altera os registros da tabela generica */
                $daoOrigemRecurso = new cl_origemcomplementorecurso();
                $daoOrigemRecurso->excluir(null, "o206_origem = 1 and o206_numero = {$stdEmpenho->chave_pk_1}");
                $daoOrigemRecurso->o206_sequencial = null;
                $daoOrigemRecurso->o206_origem = 1;
                $daoOrigemRecurso->o206_numero = $stdEmpenho->chave_pk_1;
                $daoOrigemRecurso->o206_recurso = $empenhoFinanceiro->getDotacao()->getRecurso();
                $daoOrigemRecurso->o206_complementorecurso = $complemento;
                $daoOrigemRecurso->incluir(null);
                if ($daoOrigemRecurso->erro_status === '0') {
                    throw new Exception('Não foi possível salvar a origem do complemento do recurso. '.pg_last_error());
                }

                /* altera todos os lancamentos envolvidos do empenho */
                $daoLancamentos = new cl_conlancamemp();
                $sqlLancamentos = $daoLancamentos->sql_query_file(
                    null,
                    'c75_codlan',
                    'c75_codlan',
                    "c75_numemp = {$stdEmpenho->chave_pk_1}"
                );
                $resLancamentos = db_query($sqlLancamentos);
                if (!$resLancamentos) {
                    throw new Exception('Não foi possível consultar os lançamentos.'.pg_last_error());
                }
                $totalRegistros = pg_num_rows($resLancamentos);
                if ($totalRegistros == 0) {
                    throw new Exception('Não foi possível consultar os lançamentos.'.pg_last_error());
                }


                for ($row = 0; $row < $totalRegistros; $row++) {
                    $stdLancamento = db_utils::fieldsMemory($resLancamentos, $row);
                    $daoComplemento = new cl_conlancamcomplementorecurso();
                    $daoComplemento->excluir(null, "o201_codlan = {$stdLancamento->c75_codlan}");
                    $daoComplemento->o201_sequencial = null;
                    $daoComplemento->o201_complemento = $complemento;
                    $daoComplemento->o201_codlan = $stdLancamento->c75_codlan;
                    $daoComplemento->incluir(null);
                    if ($daoComplemento->erro_status === '0') {
                        throw new Exception('Erro ao incluir o novo complemento para o lançamento.');
                    }
                }
            }


            $oRetorno->mensagem = 'Origens da despesa alteradas com sucesso.';
            break;


        case 'processarReceita':
            if (empty($parametros->registros)) {
                throw new Exception("Nenhum registro foi selecionado.");
            }

            foreach ($parametros->registros as $stdReceita) {
                if (empty($stdReceita->chave_pk_1)) {
                    continue;
                }
                $codigoLancamento = $stdReceita->chave_pk_1;
                $complemento = $stdReceita->campos[0]->valor;
                $buscaReceita = new cl_conlancamrec();
                $sqlBuscaReceita = $buscaReceita->sql_query_file($codigoLancamento);
                $resBuscaReceita = db_query($sqlBuscaReceita);
                $stdReceita = db_utils::fieldsMemory($resBuscaReceita, 0);

                $receitaContabil = ReceitaContabilRepository::getReceitaByCodigo($stdReceita->c74_codrec, $stdReceita->c74_anousu);
                /* altera os registros da tabela generica */
                $daoOrigemRecurso = new cl_origemcomplementorecurso();
                $daoOrigemRecurso->excluir(null, "o206_origem = 2 and o206_numero = {$codigoLancamento}");
                $daoOrigemRecurso->o206_sequencial = null;
                $daoOrigemRecurso->o206_origem = 2;
                $daoOrigemRecurso->o206_numero = $codigoLancamento;
                $daoOrigemRecurso->o206_recurso = $receitaContabil->getRecurso()->getCodigo();
                $daoOrigemRecurso->o206_complementorecurso = $complemento;
                $daoOrigemRecurso->incluir(null);
                if ($daoOrigemRecurso->erro_status === '0') {
                    throw new Exception('Não foi possível salvar a origem do complemento do recurso. '.pg_last_error());
                }

                /* altera todos os lancamentos envolvidos do empenho */
                $daoComplemento = new cl_conlancamcomplementorecurso();
                $daoComplemento->excluir(null, "o201_codlan = {$codigoLancamento}");
                $daoComplemento->o201_sequencial = null;
                $daoComplemento->o201_complemento = $complemento;
                $daoComplemento->o201_codlan = $codigoLancamento;
                $daoComplemento->incluir(null);
                if ($daoComplemento->erro_status === '0') {
                    throw new Exception('Erro ao incluir o novo complemento para o lançamento.');
                }
            }


            $oRetorno->mensagem = 'Origens da despesa alteradas com sucesso.';
            break;

        case 'consultaRecursoOrigem':

            $recurso = null;
            if ($parametros->origem === 'despesa') {
                $empenhoExplode = explode('/', $parametros->empenho);
                $anoPesquisa = $anoSessao;
                if (count($empenhoExplode) == 2) {
                    $anoPesquisa = $empenhoExplode[1];
                }
                $empenho = EmpenhoFinanceiroRepository::getEmpenhoFinanceiroPorCodigoAno($empenhoExplode[0], $anoPesquisa, InstituicaoRepository::getInstituicaoSessao());
                $recurso = $empenho->getDotacao()->getRecurso();
            }

            if ($parametros->origem === 'receita') {

                $receita = ReceitaContabilRepository::getReceitaByCodigo($parametros->receita, $anoSessao);
                $recurso = $receita->getRecurso()->getCodigo();
            }

            $oRetorno->recurso = $recurso;

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
