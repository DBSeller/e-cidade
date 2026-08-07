<?php
require_once(modification("libs/db_stdlib.php"));
require_once(modification("std/db_stdClass.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_app.utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("dbforms/db_funcoes.php"));

$parametro = JSON::create()->parse(str_replace("\\","", $_POST["json"]));
$retorno = new stdClass();
$retorno->erro = false;
$retorno->mensagem = '';

$anoSessao = db_getsession('DB_anousu');
$instituicaoSessao = db_getsession('DB_instit');

try {

    db_inicio_transacao();

    switch ($parametro->exec) {

        case 'pesquisar':

            $camposObrigatorios = array();
            if (empty($parametro->data_inicial)) {
                $camposObrigatorios[] = 'data inicial';
            }

            if (empty($parametro->data_final)) {
                $camposObrigatorios[] = 'data final';
            }

            if (!empty($camposObrigatorios)) {
                $ligacao = count($camposObrigatorios) === 1 ? 'é' : 'são';
                $mensagem = "Campo(s) ".implode(', ', $camposObrigatorios)." {$ligacao} de preenchimento obrigatório.";
                throw new Exception($mensagem);
            }

            $dataInicial = new DBDate($parametro->data_inicial);
            $dataFinal   = new DBDate($parametro->data_final);
            if ($dataInicial->getTimeStamp() > $dataFinal->getTimeStamp()) {
                throw new Exception("Data inicial é maior que a data final, verifique.");
            }

            $whereData = " o162_data between '{$dataInicial->getDate()}' and '{$dataFinal->getDate()}' ";

            $buscaInformacoes = <<<SQLBUSCA
select o58_coddot as codigo_dotacao,
       fc_estruturaldotacao(o58_anousu, o58_coddot) as estrutural_dotacao,
       o155_titulo as descricao_plano, 
       c07_titulo as descricao_linha,
       o156_valor as previsto,
       abs(coalesce((select sum(o162_valor)
                     from linhapactosaldomovimentacao
                     where o162_linhapacto = o156_sequencial
                       and {$whereData}
                       and o162_tipo in (3, 4)), 0)) as movimentacoes,
       coalesce((select sum(o162_valor)
                 from linhapactosaldomovimentacao
                 where o162_linhapacto = o156_sequencial
                   and {$whereData}
                   and o162_tipo in (1, 2, 5, 6)), 0) as suplementacoes_reducoes,
      orcdotacao.o58_projativ as acao,       
      linhaspacto.c07_sequencial as linha_pacto       
 from planoorcamentariolinhapacto
      inner join linhaspacto on c07_sequencial = o156_linhaspacto
      inner join orcdotacaoplanoorcamentario on o156_orcdotacaoplanoorcamentario = o155_sequencial
      inner join orcdotacao on o155_coddot = o58_coddot
                           and o155_anousu = o58_anousu
where o58_anousu = {$anoSessao}
  and o58_instit = {$instituicaoSessao}                                  
SQLBUSCA;


            if (!empty($parametro->acao)) {
                $buscaInformacoes .= " and o58_projativ = {$parametro->acao} ";
            }

            if (!empty($parametro->programa)) {
                $buscaInformacoes .= " and o58_programa = {$parametro->programa} ";
            }
            if (!empty($parametro->dotacao)) {
                $buscaInformacoes .= " and o58_coddot = {$parametro->dotacao} ";
            }

            if (!empty($parametro->linha_pacto)) {
                $buscaInformacoes .= " and c07_sequencial = {$parametro->linha_pacto} ";
            }

            $buscaInformacoes .= " order by o58_coddot, o155_sequencial, c07_sequencial ";
            $buscaDotacoes = db_query($buscaInformacoes);
            if (!$buscaDotacoes) {
                throw new Exception("Ocorreu um erro ao executar a consulta das informações de saldo do Plano Orçamentário.");
            }

            $totalRegistros = pg_num_rows($buscaDotacoes);

            $retorno->planos_orcamentarios = array();
            for ($row = 0; $row < $totalRegistros; $row++) {

                $stdInformacao = db_utils::fieldsMemory($buscaDotacoes, $row);

                $dados = (object)array(
                    'codigo_dotacao' => $stdInformacao->codigo_dotacao,
                    'estrutural_dotacao' => $stdInformacao->estrutural_dotacao,
                    'descricao_plano' => $stdInformacao->descricao_plano,
                    'descricao_linha' => $stdInformacao->descricao_linha,
                    'valor_previsto' => $stdInformacao->previsto,
                    'valor_alterado_remanejado' => $stdInformacao->suplementacoes_reducoes,
                    'valor_realizado' => $stdInformacao->movimentacoes,
                    'saldo_final' => (($stdInformacao->previsto + $stdInformacao->suplementacoes_reducoes) - $stdInformacao->movimentacoes),
                    'acao' => $stdInformacao->acao,
                    'linha_pacto' => $stdInformacao->linha_pacto,
                );
                $retorno->planos_orcamentarios[] = $dados;
            }

            break;

        case 'getDetalhamentoLinhaPacto' :

            $where = implode(' and ', array(
                'o58_anousu = '.$anoSessao,
                'o58_instit = '.$instituicaoSessao,
                'o58_projativ = '.$parametro->acao,
                'c07_sequencial = '.$parametro->linha,
            ));

            $campos = 'o55_descr , o55_projativ';
            $sqlDadosAcao = cl_linhaspacto::getDadosLinhaPacto($campos, $where);
            $buscaInformacoes = db_query($sqlDadosAcao);
            if (!$buscaInformacoes) {
                throw new Exception("Não foi possível executar a consulta.");
            }

            $stdDadosAcao = db_utils::fieldsMemory($buscaInformacoes, 0);
            $retorno->dados_acao = (object)array(
                'descricao' => "{$stdDadosAcao->o55_projativ} - {$stdDadosAcao->o55_descr}",
                'valor_previsto' => $stdDadosAcao->previsto,
                'valor_alterado_remanejado' => $stdDadosAcao->suplementacoes_reducoes,
                'valor_realizado' => $stdDadosAcao->movimentacoes,
                'saldo_final' => (($stdDadosAcao->previsto + $stdDadosAcao->suplementacoes_reducoes) - $stdDadosAcao->movimentacoes)
            );


            $camposSecretaria = "o41_orgao, o41_unidade, o41_descr";
            $sqlDadosAcao = cl_linhaspacto::getDadosLinhaPacto($camposSecretaria, $where, 'o41_orgao, o41_unidade');
            $buscaInformacoesSecretaria = db_query($sqlDadosAcao);
            if (!$buscaInformacoesSecretaria) {
                throw new Exception("Não foi possível executar a consulta.");
            }
            $totalRegistros = pg_num_rows($buscaInformacoesSecretaria);
            $secretariasRetorno = array();
            for ($row = 0; $row < $totalRegistros; $row++) {

                $stdLinha = db_utils::fieldsMemory($buscaInformacoesSecretaria, $row);

                $stdLinha->o41_unidade = str_pad($stdLinha->o41_unidade, 2, '0', STR_PAD_LEFT);
                $secretariasRetorno[] = (object)array(
                    'descricao' => "{$stdLinha->o41_orgao}.{$stdLinha->o41_unidade} - {$stdLinha->o41_descr}",
                    'valor_previsto' => $stdLinha->previsto,
                    'valor_alterado_remanejado' => $stdLinha->suplementacoes_reducoes,
                    'valor_realizado' => $stdLinha->movimentacoes,
                    'saldo_final' => (($stdLinha->previsto + $stdLinha->suplementacoes_reducoes) - $stdLinha->movimentacoes)
                );
            }
            $retorno->secretarias = $secretariasRetorno;


            break;


        default:
            throw new Exception("Parâmetros inválidos para execução da requisição.");
    }



    db_fim_transacao(false);
} catch (Exception $e) {

    db_fim_transacao(true);
    $retorno->erro = true;
    $retorno->mensagem = $e->getMessage();

}
echo JSON::create()->stringify($retorno);

