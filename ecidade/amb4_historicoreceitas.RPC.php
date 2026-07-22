<?php

use ECidade\Patrimonial\Material\Helpers\Material;

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/JSON.php"));
require_once(modification("dbforms/db_funcoes.php"));

$parametros = JSON::requestParameters();
$retorno = (object)['erro' => false, 'mensagem' => '', 'dados' => []];


try {
    switch ($parametros->acao) {
        case 'getReceitasPaciente':
            $daoReceitaMedica = new cl_sau_receitamedica();
            $campos = 's162_i_prontuario, s158_i_codigo, fa03_c_descr, s158_d_validade, s158_d_data, s158_i_situacao';
            $where = "z01_i_cgsund = {$parametros->cgs} and s158_i_situacao != 3";
            $sql = $daoReceitaMedica->sql_query_prontuario('', $campos, 's158_i_codigo desc', $where);
            $rs = $daoReceitaMedica->sql_record($sql);
            
            if ($daoReceitaMedica->numrows == 0)  {
                throw new Exception('Não foi encontrado receitas para o Paciente.');
            }
            $retorno->dados = db_utils::getCollectionByRecord($rs); 
            break;
        case 'getMedicamentosReceita':
            $daoMedicamentosReceita = new cl_sau_medicamentosreceita();
            $campos = 's159_i_codigo, fa01_i_codigo, m60_descr, s159_n_quant, s160_c_descr, s159_t_posologia';

            $where = "s159_i_receita = {$parametros->idReceita}";
            $sql = $daoMedicamentosReceita->sql_query_receita('', $campos, 's159_i_codigo', $where);
            $rs = $daoMedicamentosReceita->sql_record($sql);

            if ($daoMedicamentosReceita->numrows == 0) {
                throw new Exception('Não foi encontrado medicamentos para a receita.');
            }
            $retorno->dados = db_utils::getCollectionByRecord($rs);
            foreach($retorno->dados as $dado) {
                $dado->s159_n_quant = Material::arredondarQuantidade($dado->s159_n_quant);
            }
            break;
    }
} catch (Exception $erro) {
    $retorno->mensagem = $erro->getMessage();
    $retorno->erro = true;
}

db_fim_transacao($retorno->erro);
echo JSON::create()->stringify($retorno);