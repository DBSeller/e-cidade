<?php

require_once modification("model/configuracao/Task.model.php");
require_once modification("interfaces/iTarefa.interface.php");
require_once(modification("libs/db_conecta.php"));




class JuntaComercialTask extends Task implements iTarefa
{


    /**
     * Inicia Execucao da Tarefa
     *
     * @return void
     */
    public function iniciar()
    {
        parent::iniciar();

        try {

            /**
             * Variaveis necessarias para usar as bibliotecas padroes
             */
            global $HTTP_SERVER_VARS, $HTTP_POST_VARS, $HTTP_GET_VARS, $_SESSION, $conn;
            $HTTP_SERVER_VARS = $_SESSION;
            $HTTP_POST_VARS = $_POST;
            $HTTP_GET_VARS = $_GET;

            require_once modification("libs/db_conn.php");
            require_once modification("libs/db_stdlib.php");
            require_once modification("libs/db_utils.php");
            require_once "libs/db_autoload.php";
            require_once modification("dbforms/db_funcoes.php");

            /**
             * Conecta no banco com variaveis definidas no 'libs/db_conn.php'
             */
            if (!($conn = @pg_connect("host=$DB_SERVIDOR dbname=$DB_BASE port=$DB_PORTA user=$DB_USUARIO password=$DB_SENHA"))) {
                throw new Exception('Erro ao conectar ao banco.');
            }

            /**
             * Desativa log de alteracoes nas classes de dao
             */
            db_putsession('DB_desativar_account', true);

            db_inicio_transacao();

            $parametros = $this->oTarefa->getParametros();

            $envioInscricao = new \ECidade\Tributario\Integracao\JuntaComercial\Envio\Inscricao();
            $envioInscricao->enviar($parametros);

            unlink($this->oTarefa->getCaminhoTarefa());


            db_fim_transacao();

        } catch (Exception $oErro) {

            db_fim_transacao(true);
            file_put_contents("tmp/erro.txt", $oErro->getMessage());
            $this->log("Erro na execução:\n{$oErro->getMessage()}");
        }

        parent::terminar();
    }

    public function cancelar()
    {
    }

    public function abortar()
    {
    }
}
