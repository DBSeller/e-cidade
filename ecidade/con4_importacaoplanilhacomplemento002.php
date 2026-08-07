<?php

require_once(modification("libs/db_stdlib.php"));
require_once(modification("std/db_stdClass.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/db_usuariosonline.php"));
require_once(modification("dbforms/db_funcoes.php"));

$oParam  = JSON::create()->parse(str_replace("\\","",$_POST["json"]));

$retorno = new stdClass();
$retorno->erro = false;
$retorno->mensagem = '';

try {
    switch ($oParam->exec) {
        case 'importar':
            db_inicio_transacao();
            $retorno->mensagem = 'Arquivo importado com sucesso.';
            $arquivo = file($_FILES['arquivo']['tmp_name']);
            unset($arquivo[0]);

            $despesas = [];
            $receitas = [];
            foreach ($arquivo as $linha) {
                list($empenho, $lancamento, $complemento, $dotacao) = explode(',', str_replace("\n", '', $linha));

                if (empty($complemento)) {
                    continue;
                }

                if (!empty($empenho) && empty($lancamento)) {
                    $despesas[$empenho] = $complemento."#".$dotacao;
                }

                if (!empty($lancamento) && empty($empenho)) {
                    $receitas[$lancamento] = $complemento;
                }
            }

            if (!empty($receitas)) {
                salvarReceita($receitas);
            }

            if (!empty($despesas)) {
                salvarDespesa($despesas);
            }

            db_fim_transacao(false);
            break;
    }

} catch (Exception $e) {
    $retorno->mensagem = $e->getMessage();
    db_fim_transacao(true);
}
echo JSON::create()->stringify($retorno);


/**
 * @param $despesas
 *
 * @throws Exception
 */
function salvarDespesa($despesas) {

    $empenhos = implode(',', array_keys($despesas));
    $buscaLancamentos = db_query("select * from conlancamemp where c75_numemp in ({$empenhos}) order by c75_numemp;");
    if (!$buscaLancamentos) {
        throw new Exception("Ocorreu um erro ao localizar os empenhos.");
    }
    $totalRegistros = pg_num_rows($buscaLancamentos);
    $comandosDespesa = [];
    $lancamentosExclusao = [];
    for ($row = 0; $row < $totalRegistros; $row++) {

        $stdDados = db_utils::fieldsMemory($buscaLancamentos, $row);
        $lancamentosExclusao[] = $stdDados->c75_codlan;
        $valor = $despesas[$stdDados->c75_numemp];
        list($complemento, $dotacao) = explode('#', $valor);
        $comandosDespesa[] = "insert into conlancamcomplementorecurso values (nextval('conlancamcomplementorecurso_o201_sequencial_seq'), {$stdDados->c75_codlan}, {$complemento});";
        if ($dotacao != "") {
            $comandosDespesa[] = "update conlancamdot set c73_coddot = {$dotacao} where c73_codlan = {$stdDados->c75_codlan};";
        }
    }

    foreach($despesas as $empenho => $valor) {
        list($complemento, $dotacao) = explode('#', $valor);
        if (!empty($dotacao)) {
            $comandosDespesa[] = "update empempenho set e60_coddot = {$dotacao} where e60_numemp = {$empenho};";
        }
    }

    $lancamentos = implode(',', $lancamentosExclusao);
    $deleteLancamentos = db_query("delete from conlancamcomplementorecurso where o201_codlan in ({$lancamentos});");
    if (!$deleteLancamentos) {
        throw new Exception('Ocorreu um erro ao excluir os complementos para os lançamentos da despesa.');
    }

    $insertDespesa = db_query(implode("\n", $comandosDespesa));
    if (!$insertDespesa) {
        throw new Exception("Ocorreu um erro ao incluir os complementos da despesa.\n\n".pg_last_error());
    }
}

/**
 * @param array $receitas
 * @throws Exception
 */
function salvarReceita($receitas) {
    $lancamentos = implode(',', array_keys($receitas));
    $deleteLancamentos = db_query("delete from conlancamcomplementorecurso where o201_codlan in ({$lancamentos});");
    if (!$deleteLancamentos) {
        throw new Exception('Ocorreu um erro ao excluir os complementos para os lançamentos de receita.');
    }
    $comandosReceita = [];
    foreach ($receitas as $lancamento => $complemento) {
        $comandosReceita[] = "insert into conlancamcomplementorecurso values (nextval('conlancamcomplementorecurso_o201_sequencial_seq'), {$lancamento}, {$complemento});";
    }
    $insertReceita = db_query(implode("\n", $comandosReceita));
    if (!$insertReceita) {
        throw new Exception("Ocorreu um erro ao incluir os complementos da receita.\n\n".pg_last_error());
    }
}
