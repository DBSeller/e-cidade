<?php

class ProcessamentoRecadastramento
{
    /**
     * @table processamentorecadastramento
     */

    /**
     * @column h260_sequencial
     * @var integer
     */
    protected $sequencial;

    /**
     * @column h260_regist
     * @var integer
     */
    protected $matricula;

    /**
     * @column h260_codproc
     * @var integer
     */
    protected $processo;

    /**
     * @column h260_status
     * @var boolean
     */
    protected $status;

    /**
     * @column h260_erro
     * @var string
     */
    protected $erro;

    /**
     * @column h260_usuario
     * @var integer
     */
    protected $usuario;

    /**
     * @column h260_instit
     * @var integer
     */
    protected $instituicao;

    /**
     * @column h260_data
     * @var string
     */
    protected $data;

    public function setSequencial($sequencial)
    {
        $this->sequencial = $sequencial;
    }

    public function getSequencial()
    {
        return $this->sequencial;
    }

    public function setMatricula($matricula)
    {
        $this->matricula = $matricula;
    }

    public function getMatricula()
    {
        return $this->matricula;
    }

    public function setProcesso($processo)
    {
        $this->processo = $processo;
    }

    public function getProcesso()
    {
        return $this->processo;
    }

    public function setStatus($status)
    {   
        $this->status = $status;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setErro($erro)
    {
        $this->erro = $erro;
    }

    public function getErro()
    {
        return $this->erro;
    }

    public function setUsuario($usuario)
    {
        $this->usuario = $usuario;
    }

    public function getUsuario()
    {
        return $this->usuario;
    }
    
    public function setInstituicao($instituicao)
    {
        $this->instituicao = $instituicao;
    }

    public function getInstituicao()
    {
       return  $this->instituicao;
    }

    /**
     * @return string
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param string
     */
    public function setData($data)
    {
        $this->data = $data;
    }




    public static function findByMatriculaAprovada($matricula)
    {
        $processamentoDao = new cl_processamentorecadastramento();
        $dbwhere = "h260_regist = {$matricula} and h260_status = 't'";
        $sql = $processamentoDao->sql_query_file(null, "*", null, $dbwhere);
        $rs = db_query($sql);
        $servidorProcessamento = pg_fetch_object($rs);
        if (empty($servidorProcessamento)) {
            return false;
        }
        return self::fromDao($servidorProcessamento);
    }


    public static function fromDao(stdClass $processamentoRecadastramento)
    {
        $processamentoRecadastramentoModel = new self();
        $processamentoRecadastramentoModel->setSequencial($processamentoRecadastramento->h260_sequencial);
        $processamentoRecadastramentoModel->setMatricula($processamentoRecadastramento->h260_regist);
        $processamentoRecadastramentoModel->setProcesso($processamentoRecadastramento->h260_codproc);
        $processamentoRecadastramentoModel->setStatus($processamentoRecadastramento->h260_status);
        $processamentoRecadastramentoModel->setErro($processamentoRecadastramento->h260_erro);
        $processamentoRecadastramentoModel->setUsuario($processamentoRecadastramento->h260_usuario);
        $processamentoRecadastramentoModel->setInstituicao($processamentoRecadastramento->h260_instit);
        $processamentoRecadastramentoModel->setData($processamentoRecadastramentoModel->h260_data);

        return $processamentoRecadastramentoModel;
    }

    public function save()
    {

        $daoProcesmsamento = new cl_processamentorecadastramento();
        $daoProcesmsamento->h260_sequencial = $this->getSequencial();
        $daoProcesmsamento->h260_regist = $this->getMatricula();
        $daoProcesmsamento->h260_codproc = $this->getProcesso();
        $daoProcesmsamento->h260_status = $this->getStatus();
        $daoProcesmsamento->h260_erro = $this->getErro();
        $daoProcesmsamento->h260_usuario = $this->getUsuario();
        $daoProcesmsamento->h260_instit = $this->getInstituicao();
        $daoProcesmsamento->h260_data = $this->getData();

        
        if (empty($this->getSequencial())) {
            $daoProcesmsamento->incluir();
        } else {
            $daoProcesmsamento->alterar($this->getSequencial());
        }

        if ($daoProcesmsamento->erro_status == "0") {
            throw new \Exception( $daoProcesmsamento->erro_msg);
        }
    }
}