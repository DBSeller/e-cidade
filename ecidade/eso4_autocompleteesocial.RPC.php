<?php

require_once modification('libs/db_stdlib.php');
require_once modification('libs/db_conecta.php');
require_once modification('libs/db_sessoes.php');
require_once modification('libs/db_utils.php');

$parametros = JSON::requestParameters();
$ano = isset($parametros->ano) ? $parametros->ano : db_getsession('DB_anousu');
try {
    $retorno = new stdClass();
    $retorno->erro = false;
    switch ($parametros->exec) {
        case 'buscarCargos':
            $cargos = array();
            $campos = array(
                'rh37_funcao as value',
                'rh37_funcao || \' - \' || rh37_descr as label'
            );

            $where = array(
                'rh37_ativo is true',
                'rh37_instit = ' . db_getsession('DB_instit')
            );

            $dao = new cl_rhfuncao();
            $sql = $dao->sql_query_file(null, null, implode(', ', $campos), null, implode(' and ', $where));
            $retorno = executaQuery($sql);

            break;
        case 'buscarFuncoes':
            $funcoes = array();
            $campos = array(
                'rh04_codigo as value',
                'rh04_codigo || \' - \'  || rh04_descr as label'
            );

            $dao = new cl_rhcargo();
            $sql = $dao->sql_query_file(null, db_getsession('DB_instit'), implode(', ', $campos));
            $retorno = executaQuery($sql);
            break;
        case 'buscarHorarios':
            $dao = new cl_avaliacaogruporesposta();
            $sql = $dao->sqlRespostasHorarioPorInstituicao(db_getsession('DB_instit'));

            $rs = db_query($sql);

            if (!$rs) {
                throw new DBException('Não foi possível buscar as respostas da tabela de horários. Contate o suporte.');
            }

            $retorno = array();

            while ($horario = pg_fetch_object($rs)) {
                $horario->value = $horario->codigo;
                $horario->label = "$horario->codigo - $horario->descricao";

                $retorno[] = $horario;
            }

            break;
    }
} catch (Exception $exception) {
    $retorno->mensagem = $exception->getMessage();
    $retorno->erro = true;
}

echo JSON::create()->stringify($retorno);


function executaQuery($sql) {

    $dados = array();
    $resultado = db_query($sql);
    if (pg_num_rows($resultado) > 0) {
        while ($linha = pg_fetch_object($resultado)) {
            $dados[] = (object) $linha;
        }
    }

    return $dados;
}
