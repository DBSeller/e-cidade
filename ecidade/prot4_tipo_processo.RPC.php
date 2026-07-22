<?php

require_once(modification("libs/db_stdlib.php"));
require_once(modification("libs/db_utils.php"));
require_once(modification("libs/db_conecta.php"));
require_once(modification("libs/db_sessoes.php"));
require_once(modification("libs/JSON.php"));

class protocoloTipoProcessoRPC
{

    private $request;
    private $response = array('success' => false, 'message' => '', 'data' => null);

    public function __construct()
    {
        $this->detectRequest();
        $this->route();
    }

    private function route()
    {

        switch ($this->request->action) {
            case "find":
                $this->find();
                break;
            case "update":
                $this->update();
                break;
            case "tipos-processo":
                $this->getTiposProcesso();
                break;
        }
    }

    private function find()
    {

        try {

            $processoDao = new cl_protprocesso();
            if (!isset($this->request->codigoProcesso)) {
                throw new \Exception("Não foi encontrado parametros!");
            }
            $sql = $processoDao->sql_query($this->request->codigoProcesso);
            $result = pg_query($sql);
            $processo = pg_fetch_object($result);
            if (count($processo) < 1) {
                throw new \Exception("Não encontrado!");
            }

            $this->response["data"] = $processo;
            $this->response["message"] = "Encontrado com sucesso!";
            $this->response["success"] = true;

        } catch (\Exception $ex) {
            $this->response["message"] = $ex->getMessage();
        }
        $this->responseJson();
    }

    private function getTiposProcesso()
    {
        try {
            $tipoProcessoDao = new cl_tipoprocesso();
            $result = db_query($tipoProcessoDao->sql_query());
            $tiposProcessos = pg_fetch_all($result);
            if (count($tiposProcessos) < 1) {
                throw new \Exception("Não encontrado!");
            }
            $this->response["data"] = $tiposProcessos;
            $this->response["message"] = "Encontrado com sucesso!";
            $this->response["success"] = true;
        } catch (\Exception $ex) {
            $this->response["message"] = $ex->getMessage();
        }

        $this->responseJson();


    }

    private function update()
    {

        try
        {
            if(empty($this->request->codigoProcesso)){
                throw new \Exception("Não foi encontrado o  processo!");
            }

            if(empty($this->request->tipo_processo)){
                throw new \Exception("Não foi encontrado tipo de processo!");
            }

            $processoDao = new cl_protprocesso();
            $processoDao->p58_tipoprocesso = $this->request->tipo_processo;
            $processoDao->p58_codproc = $this->request->codigoProcesso;
            $processoDao->alterar($this->request->codigoProcesso);
            if($processoDao->erro_status == '0'){
                throw new \Exception($processoDao->erro_msg." - ".$processoDao->erro_sql);
            }

            $this->response["message"] = "Salvo com sucesso!";
            $this->response["success"] = true;
        } catch (\Exception $ex) {
            $this->response["message"] = $ex->getMessage();
        }
        $this->responseJson();
    }

    private function detectRequest()
    {
        $this->request = (object)json_decode(file_get_contents('php://input'), true);
    }

    private function responseJson()
    {
        header('Content-Type: application/json');
        echo JSON::create()->stringify($this->response);
    }

}

new protocoloTipoProcessoRPC();
