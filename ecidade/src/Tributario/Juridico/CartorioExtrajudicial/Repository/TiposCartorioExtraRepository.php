<?php

namespace ECidade\Tributario\Juridico\CartorioExtrajudicial\Repository;

use ECidade\Tributario\Juridico\CartorioExtrajudicial\Model\CartorioExtraTipo;
use ECidade\Tributario\Juridico\CartorioExtrajudicial\Model\TiposCartorioExtra;

class TiposCartorioExtraRepository extends \BaseClassRepository
{
    /**
     * Variável com a instância da DAO
     *
     * @var TiposCartorioExtra
     */
    private $dao;

    /**
     * Variável com as condições de consulta
     *
     * @var array
     */
    private $aCondition = [];

    public function __construct()
    {
        $this->dao = new \cl_tiposcartorioextra();
    }

    public function persist(TiposCartorioExtra $entity)
    {
        $this->dao->j169_sequencial = $entity->getSequencial();
        $this->dao->j167_descricao = $entity->getDescricao();

        if (!empty($this->dao->j169_sequencial)) {
            $this->dao->alterar($this->dao->j169_sequencial);
        } else {
            $this->dao->incluir(null);
        }

        if ($this->dao->erro_status == "0") {
            throw new \Exception($this->dao->erro_msg);
        }
    }

    public function get()
    {
        $result = db_query($this->dao->sql_query("", "", "j169_sequencial", implode(" AND ", $this->aCondition)));

        if (!$result) {
            throw new \Exception("Erro ao buscar os tipos de cartórios extrajudiciais.");
        }

        return \db_utils::getCollectionByRecord($result);
    }

    public function setDefaultCondition(TiposCartorioExtra $entity)
    {
        if (!empty($entity->getSequencial())) {
            $this->aCondition[] = "j169_sequencial = {$entity->getSequencial()}";
        }

        if (!empty($entity->getDescricao())) {
            $this->aCondition[] = "j169_descricao = {$entity->getDescricao()}";
        }

        return $this;
    }

    public function setOuterCondition($sCondition)
    {
        if (!empty($sCondition)) {
            $this->aCondition[] = $sCondition;
        }

        return $this;
    }
}
