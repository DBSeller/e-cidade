<?php

require_once modification("model/configuracao/Task.model.php");
require_once modification("interfaces/iTarefa.interface.php");
require_once(modification("libs/db_conecta.php"));

use ECidade\Tributario\Integracao\Civitas\Service;
use ECidade\Tributario\Integracao\Civitas\Repository\Importador as ImportadorRepository;
use ECidade\Tributario\Integracao\Civitas\Model\Importador as ImportadorModel;

/**
 * Class CivitasTask
 * @author Roberto Carneiro <roberto@dbseller.com.br>
 */
class CivitasTask extends Task implements iTarefa
{
    /**
     * Inicia Execucao da Tarefa
     *
     * @return void
     */
    public function iniciar()
    {
        parent::iniciar();

        $parametros = $this->oTarefa->getParametros();

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
            require_once modification("libs/db_autoload.php");
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
            db_putsession('DB_datausu', date('Y-m-d'));
            db_putsession('DB_acessado', "1325613");
            db_putsession('DB_anousu', date("Y"));
            db_putsession('DB_id_usuario', '1');

            db_inicio_transacao();


            db_query("
                select fc_startsession();
                SET statement_timeout           = 0;
                SET client_encoding             = 'LATIN1';
                SET standard_conforming_strings = off;
                SET check_function_bodies       = false;
                SET client_min_messages         = warning;
                SET escape_string_warning       = off;

                select fc_putsession('DB_instit'::varchar ,    codigo::varchar      )                   as \"PutSession DB_instit\",
                      fc_putsession('DB_datausu'::varchar,    current_date::varchar)                    as \"PutSession DB_datausu\",
                      fc_putsession('DB_anousu'::varchar ,    extract(year from current_date)::varchar) as \"PutSession DB_anousu\",
                      fc_putsession('DB_id_usuario'::varchar, '1')                                      as \"PutSession DB_id_usuario\",
                      fc_putsession('DB_use_pcasp'::varchar,  '1')                                      as \"PutSession DB_use_pcasp\"
                 from configuracoes.db_config
                 WHERE prefeitura is true;
            ");

            $erro = array();


            $arquivos = array();

            if (!empty($parametros['arquivoLotes'])) {
                $arquivos[] = array(
                    "Nome" => $parametros['arquivoLotes'],
                    "TipoArquivo" => ImportadorModel::ARQUIVO_LOTES,
                    "Data" => $parametros['data'],
                    "Caminho" => Service::FILE_PATH
                );
            }

            if (!empty($parametros['arquivoEdificacoes'])) {
                $arquivos[] = array(
                    "Nome" => $parametros['arquivoEdificacoes'],
                    "TipoArquivo" => ImportadorModel::ARQUIVO_EDIFICACOES,
                    "Data" => $parametros['data'],
                    "Caminho" => Service::FILE_PATH
                );
            }

            if (!empty($parametros['arquivoTestadas'])) {
                $arquivos[] = array(
                    "Nome" => $parametros['arquivoTestadas'],
                    "TipoArquivo" => ImportadorModel::ARQUIVO_TESTADAS,
                    "Data" => $parametros['data'],
                    "Caminho" => Service::FILE_PATH
                );
            }

            $oImportador = ImportadorRepository::getImportador($arquivos);

            $objSituacao =  $oImportador->processar();

            $situacao = ImportadorRepository::CODIGO_SUCESSO;

            db_fim_transacao();

        } catch (Exception $oErro) {
            db_fim_transacao(true);
            $situacao = ImportadorRepository::CODIGO_ERRO;
            $this->log("Erro na execução:\n{$oErro->getMessage()}");
        }


        $logs = array_merge($objSituacao->erros, $erro);
        ImportadorRepository::setLog($logs);

        try {

            db_inicio_transacao();
            ImportadorRepository::atualizarSituacao($situacao, $parametros['sequencialRequisicao'], $parametros['data']);
            db_fim_transacao();
        } catch (Exception $oErro) {
            db_fim_transacao(true);
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
