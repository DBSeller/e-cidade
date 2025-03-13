<?php

namespace ECidade\Patrimonial\Compras\AutorizacaoEmpenho\Model;

class AndamentoPreAutorizacao
{
    protected $id;
    
    protected $empautoriza_id;
    
    protected $status_id;
    
    protected $id_usuario;

    protected $status;

    protected $usuario;
    
    protected $data;

    public function __construct(
        $id = null,
        $empautoriza_id = null,
        $status_id = null,
        $observacao = null,
        $id_usuario = null
    ) {
        $this->id = $id;
        $this->empautoriza_id  = $empautoriza_id;
        $this->status_id  = $status_id;
        $this->observacao  = $observacao;
        $this->id_usuario  = $id_usuario;
    }
    
    /**
     * @param mixed $status
     */
    public function setCodigoAndamento($codigoAndamento)
    {
        $this->id = $codigoAndamento;
    }

    /**
     * @return string
     */
    public function getCodigoAndamento()
    {
        return $this->id;
    }

      /**
     * @param mixed $status
     */
    public function setCodigoAutorizacao($empautorizaId)
    {
        $this->empautoriza_id = $empautorizaId;
    }

   /**
     * @return string
     */
    public function getCodigoAutorizacao()
    {
        return $this->empautoriza_id;
    }

   /**
     * @param mixed $status
     */
    public function setIdStatus($statusId)
    {
        $this->status_id = $statusId;
    }

     /**
     * @return string
     */
    public function getIdStatus()
    {
        return $this->status_id;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

     /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

   /**
     * @param mixed $status
     */
    public function setObservacao($observacao)
    {
        $this->observacao = $observacao;
    }

     /**
     * @return string
     */
    public function getObservacao()
    {
        return $this->observacao;
    }
      /**
     * @param mixed $status
     */
    public function setIdUsuario($idUsuario)
    {
        $this->id_usuario = $idUsuario;
    }

     /**
     * @return string
     */
    public function getIdUsuario()
    {
        return $this->id_usuario;
    }
    

    /**
     * @param mixed $usuario
     */
    public function setUsuario($usuario)
    {
        $this->usuario = $usuario;
    }

    /**
     * @return string
     */
    public function getUsuario()
    {
        return $this->usuario;
    }

    /**
     * @param mixed $usuario
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * @return string
     */
    public function getData()
    {
        return $this->data;
    }
}
