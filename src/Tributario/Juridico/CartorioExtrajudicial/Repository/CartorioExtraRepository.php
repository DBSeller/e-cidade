<?php

namespace ECidade\Tributario\Juridico\CartorioExtrajudicial\Repository;

use ECidade\Tributario\Juridico\CartorioExtrajudicial\Model\CartorioExtra;
use ECidade\Tributario\Juridico\CartorioExtrajudicial\Model\CartorioExtraTipo;

class CartorioExtraRepository extends \BaseClassRepository
{
    /**
     * Variável com a instância da DAO
     *
     * @var CartorioExtra
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
        $this->dao = new \cl_cartorioextra();
    }

    public function persist(CartorioExtra $entity)
    {
        $this->dao->j167_sequencial = $entity->getSequencial();
        $this->dao->j167_descricao = $entity->getDescricao();
        $this->dao->j167_numcgm = $entity->getNumcgm();
        $this->dao->j167_observacao = $entity->getObservacao();

        if (!empty($this->dao->j167_sequencial)) {
            $this->dao->alterar($this->dao->j167_sequencial);
        } else {
            $this->dao->incluir(null);
        }

        if ($this->dao->erro_status == "0") {
            throw new \Exception($this->dao->erro_msg);
        }

        return $this->dao->j167_sequencial;
    }

    public function get()
    {
        $result = $this->dao->sql_record($this->dao->sql_query(
            "",
            "*",
            "j167_sequencial",
            implode(" AND ", $this->aCondition)
        ));

        if (!$result) {
            throw new \Exception("Erro ao buscar o cartório. \n\n {$this->dao->erro_msg}");
        }

        return \db_utils::fieldsMemory($result, 0);
    }

    public function setDefaultCondition(CartorioExtra $entity)
    {
        if (!empty($entity->getSequencial())) {
            $this->aCondition[] = "j167_sequencial = {$entity->getSequencial()}";
        }

        if (!empty($entity->getDescricao())) {
            $this->aCondition[] = "j167_descricao ILIKE '{$entity->getDescricao()}'";
        }

        if (!empty($entity->getNumcgm())) {
            $this->aCondition[] = "j167_numcgm = {$entity->getNumcgm()}";
        }

        if (!empty($entity->getObservacao())) {
            $this->aCondition[] = "j167_observacao ILIKE '{$entity->getObservacao()}'";
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

    public function getAllData($sCampos = "")
    {
        $result = db_query($this->dao->sql_all(
            $sCampos,
            implode(" AND ", $this->aCondition)
        ));

        if (!$result) {
            throw new \Exception("Erro ao buscar todos os dados do cartório.");
        }

        return \db_utils::fieldsMemory($result, 0);
    }
}
