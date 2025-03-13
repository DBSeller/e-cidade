<?php


/**
 * Class AtendimentoProcessoEletronico
 */
class AtendimentoProcessoEletronico
{
    /**
     * @var integer
     */
    private $id;
    /**
     * @var integer
     */
    private $ouvidoriaAtendimentoId;
    /**
     * @var String
     */
    private $informacoesProcesso;
    /**
     * @var integer
     */
    private $ouvidoriaAtendimentoAnteriorId;
    /**
     * @var integer
     */
    private $clientAtendimentoId;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return $this;
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return int
     */
    public function getOuvidoriaAtendimentoId()
    {
        return $this->ouvidoriaAtendimentoId;
    }

    /**
     * @param int $ouvidoriaAtendimentoId
     */
    public function setOuvidoriaAtendimentoId($ouvidoriaAtendimentoId)
    {
        $this->ouvidoriaAtendimentoId = $ouvidoriaAtendimentoId;
        return $this;
    }

    /**
     * @return String
     */
    public function getInformacoesProcesso()
    {
        return $this->informacoesProcesso;
    }

    /**
     * @param String $informacoesProcesso
     */
    public function setInformacoesProcesso($informacoesProcesso)
    {
        $this->informacoesProcesso = $informacoesProcesso;
        return $this;
    }

    /**
     * @return int
     */
    public function getOuvidoriaAtendimentoAnteriorId()
    {
        return $this->ouvidoriaAtendimentoAnteriorId;
    }

    /**
     * @param int $ouvidoriaAtendimentoAnteriorId
     */
    public function setOuvidoriaAtendimentoAnteriorId($ouvidoriaAtendimentoAnteriorId)
    {
        $this->ouvidoriaAtendimentoAnteriorId = $ouvidoriaAtendimentoAnteriorId;
        return $this;
    }

    /**
     * @return int
     */
    public function getClientAtendimentoId()
    {
        return $this->clientAtendimentoId;
    }

    /**
     * @param int $clientAtendimentoId
     */
    public function setClientAtendimentoId($clientAtendimentoId)
    {
        $this->clientAtendimentoId = $clientAtendimentoId;
        return $this;
    }

    /**
     * @param $id
     * @return AtendimentoProcessoEletronico|false
     * @throws DBException
     */
    public static function find($id)
    {
        if (empty($id)) {
            return false;
        }

        $ouvidoriaAtendimento = new cl_ouvidoriaatendimentoprocessoeletronico();
        $objAtendimento = $ouvidoriaAtendimento->findBydId($id);
        if (empty($objAtendimento)) {
            return false;
        }

        return self::fromDao($objAtendimento);
    }

    /**
     * @param $idAtendimento
     * @return AtendimentoProcessoEletronico|false
     */
    public static function findByAtendimento($idAtendimento)
    {
        if (empty($idAtendimento)) {
            return false;
        }

        $ouvidoriaAtendimento = new cl_ouvidoriaatendimentoprocessoeletronico();
        $sql = $ouvidoriaAtendimento->sql_query(null,"*",null,"ov33_ouvidoriaatendimento={$idAtendimento}");
        $rs = $ouvidoriaAtendimento->sql_record($sql);


        if ($ouvidoriaAtendimento->numrows < 1) {
            return false;
        }
        $objOuvidoriaAtendimento = pg_fetch_object($rs);
        return self::fromDao($objOuvidoriaAtendimento);
    }

    /**
     * @param $resultDao
     * @return AtendimentoProcessoEletronico
     */
    private static function fromDao($resultDao)
    {
        $atendimento = new self();
        return $atendimento
            ->setId($resultDao->ov33_sequencial)
            ->setClientAtendimentoId($resultDao->ov33_client_atendimento_id)
            ->setInformacoesProcesso($resultDao->ov33_informacoesprocesso)
            ->setOuvidoriaAtendimentoAnteriorId($resultDao->ov33_ouvidoriaatendimento_anterior)
            ->setOuvidoriaAtendimentoId($resultDao->ov33_ouvidoriaatendimento);
    }

    /**
     * @return AtendimentoOuvidoria|false
     */
    public function getAtendimentoOuvidoria()
    {
        return AtendimentoOuvidoria::find($this->getOuvidoriaAtendimentoId());
    }


    /**
     * @return bool
     * @throws Exception
     */
    public function save(){
      $dao = new  cl_ouvidoriaatendimentoprocessoeletronico();
      $dao->ov33_ouvidoriaatendimento = $this->getOuvidoriaAtendimentoId();
      $dao->ov33_informacoesprocesso = $this->getInformacoesProcesso();
      $dao->ov33_ouvidoriaatendimento_anterior = $this->getOuvidoriaAtendimentoAnteriorId();
      $dao->ov33_client_atendimento_id = $this->getClientAtendimentoId();
      $dao->ov33_sequencial = $this->getId();

        if (empty($this->getId())) {
            if(!$dao->incluir(null)){
                throw new \Exception($dao->erro_msg);
            }
        } else {
            if(!$dao->alterar($this->getId())){
                throw new \Exception($dao->erro_msg);
            }
        }

        return true;

    }


}
