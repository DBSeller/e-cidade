<?php

use ECidade\RecursosHumanos\ESocial\Integracao\ESocial;
use ECidade\RecursosHumanos\ESocial\Integracao\Recurso;
use ECidade\RecursosHumanos\ESocial\ESocialContextException;
use ECidade\RecursosHumanos\ESocial\Integracao\Formatter\Error as FormatterError;
use ECidade\V3\Extension\Registry;

require_once modification("interfaces/iTarefa.interface.php");

class FilaESocialTask extends Task implements iTarefa
{
   /**
   * Mesagem a ser exibida em tela
   * @var string
   */
  protected $mensagem;
   
    /**
     * Para debugar, start o serviço no terminal
     * /usr/bin/php53/php cli.php --executable=con4_gerenciadortarefas002.php
     * @param null $idFila
     * @throws Exception
     */
    public function iniciar($idFila = null)
    {
        // emula sessao
        if (!isset($_SESSION)) {
            $_SESSION = array();
        }

        // desativa accounts, pois nao tem menu
        $_SESSION['DB_desativar_account'] = true;

        require_once modification("libs/db_conn.php");
        require_once modification("libs/db_stdlib.php");
        require_once modification("libs/db_utils.php");
        require_once modification("dbforms/db_funcoes.php");

        try {

            // json e tipo do evento
            $dadosEvento = $this->getEventData($idFila);

            // envia para api
            $this->sendEvent($dadosEvento);

            // atualiza a situacao do evento
            $this->updateEvento($idFila, 2);

            // atualiza o status do evento
            $this->updateEventStatus($idFila, true, "Enviado para a API");
            return true;

        } catch (ESocialContextException $e) {

            $message = sprintf("Erro ao enviar para API\n%s", DBString::utf8_decode_all($e->getMessage()));

            $this->setMensagem($message);
            
            $context = $e->getContext();

            // evento recusado, formatado retorno para buscar descricao dos campos do layout
            if ($e->getCode() == 422 && is_object($context)
                && isset($context->errors) && is_array($context->errors)) {

                $errorFormatter = new FormatterError($dadosEvento->tipoEvento, true);
                $message = $errorFormatter->formatErrors($context->errors);
            }

            $this->updateEvento($idFila, 1, true);
            $this->updateEventStatus($idFila, false, $message);
            return false;
        } catch (Exception $e) {

            $this->updateEvento($idFila, 1, true);
            $this->updateEventStatus(
                $idFila, false, "Ocorreu um erro técnico, tente novamente mais tarde."
            );
            return false;
        }
    }

    private function sendEvent($data)
    {
        $sRecurso = Recurso::getRecursoByEvento($data->tipoEvento);
        $exportar = new ESocial(Registry::get('app.config'), $sRecurso);
        $exportar->setDados(array(json_decode($data->evento)));
        return $exportar->request();
    }

    private function getEventData($idFila)
    {
        $dao = new \cl_esocialenvio();
        $sql = $dao->sql_query_file($idFila);
        $rs  = \db_query($sql);

        if (!$rs && pg_num_rows($rs) == 0) {
            throw new \Exception('Agendamento nao encontrado.');
        }

        $dadosEnvio = \db_utils::fieldsMemory($rs, 0);

        return (object) array(
            'tipoEvento' => $dadosEnvio->rh213_evento,
            'evento' => $dadosEnvio->rh213_dados,
        );
    }

    private function updateEvento($idFila, $situacao, $clearMD5 = false)
    {
        $dao = new \cl_esocialenvio();
        $dao->rh213_situacao = $situacao;
        $dao->rh213_sequencial = $idFila;

        // altera md5 do evento para pode ser enviado
        if ($clearMD5) {
            $dao->rh213_md5 = 'invalid';
        }
        $dao->alterar($idFila);

        if ($dao->erro_status == 0) {
            throw new \Exception("Não foi possível alterar situação da fila.");
        }
    }

    private function updateEventStatus($idFila, $status, $message)
    {
        $oDaoEsocialEnvioStatus = new cl_esocialenviostatus();

        $oDaoEsocialEnvioStatus->excluir(null, "rh214_esocialenvio = {$idFila}");

        if ($oDaoEsocialEnvioStatus->erro_status == 0) {
            throw new \Exception("Não foi possível atualizar o status do evento.");
        }

        $oDaoEsocialEnvioStatus->rh214_esocialenvio = $idFila;
        $oDaoEsocialEnvioStatus->rh214_descricao = pg_escape_string($message);
        $oDaoEsocialEnvioStatus->rh214_situacao = $status ? 'true' : 'false';

        $oDaoEsocialEnvioStatus->incluir(null);

        if ($oDaoEsocialEnvioStatus->erro_status == 0) {
            throw new \Exception("Não foi possível incluir o status do evento.");
        }
    }

    public function cancelar()
    {
    }

    public function abortar()
    {
    }

  /**
   * Get mesagem a ser exibida em tela
   *
   * @return  string
   */ 
  public function getMensagem()
  {
    return $this->mensagem;
  }

  /**
   * Set mesagem a ser exibida em tela
   *
   * @param  string  $menssagem  Mesagem a ser exibida em tela
   */ 
  public function setMensagem($mensagem)
  {
    $this->mensagem = $mensagem;
  }
}
