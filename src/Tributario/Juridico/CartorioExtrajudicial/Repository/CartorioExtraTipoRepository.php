<?php

namespace ECidade\Tributario\Juridico\CartorioExtrajudicial\Repository;

use ECidade\Tributario\Arrecadacao\Model\TaxasLancadasDepart;
use ECidade\Tributario\Juridico\CartorioExtrajudicial\Model\CartorioExtraTipo;

class CartorioExtraTipoRepository extends \BaseClassRepository
{
    /**
     * Variável com a instância da DAO
     *
     * @var CartorioExtraTipo
     */
    private $dao;

    /**
     * Variável com as condições de consulta
     *
     * @var array
     */
    private $aCondition = [];

    /**
     * Variável com o group by da consulta
     *
     * @var array
     */
    private $sGroupBy = "";

    /**
     * Variável com os campos da consulta
     *
     * @var string
     */
    private $sCampos = "cartorioextratipo.*";

    /**
     * Variável com os order by da consulta
     *
     * @var string
     */
    private $sOrderBy = "j168_sequencial";

    public function __construct()
    {
        $this->dao = new \cl_cartorioextratipo();
    }

    public function persist(CartorioExtraTipo $entity)
    {
        $this->dao->j168_sequencial = $entity->getSequencial();
        $this->dao->j168_cartorioextra = $entity->getCartorioextra();
        $this->dao->j168_tiposcartorioextra = $entity->getTiposcartorioextra();

        $this->dao->incluir(null);

        if ($this->dao->erro_status == "0") {
            throw new \Exception($this->dao->erro_msg);
        }
    }

    public function delete()
    {
        $this->dao->excluir("", implode(" AND ", $this->aCondition));

        if ($dao->erro_status == "0") {
            throw new \Exception($dao->erro_msg);
        }
    }

    public function get()
    {
        $result = db_query($this->dao->sql_query(
            "",
            $this->sCampos,
            $this->sOrderBy,
            implode(" AND ", $this->aCondition),
            $this->sGroupBy
        ));

        if (!$result) {
            throw new \Exception("Erro ao fazer a busca nos cartórios.\n\n{$this->dao->erro_msg}");
        }

        return \db_utils::getCollectionByRecord($result);
    }

    public function setDefaultCondition(CartorioExtraTipo $entity)
    {
        if (!empty($entity->getSequencial())) {
            $this->aCondition[] = "j168_sequencial = {$entity->getSequencial()}";
        }

        if (!empty($entity->getCartorioextra())) {
            $this->aCondition[] = "j168_cartorioextra = {$entity->getCartorioextra()}";
        }

        if (!empty($entity->getTiposcartorioextra())) {
            $this->aCondition[] = "j168_tiposcartorioextra = {$entity->getTiposcartorioextra()}";
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

    public function setGroupBy($sGroupBy)
    {
        if (!empty($sGroupBy)) {
            $this->sGroupBy = $sGroupBy;
        }

        return $this;
    }

    public function setCampos($sCampos)
    {
        if (!empty($sCampos)) {
            $this->sCampos = $sCampos;
        }

        return $this;
    }

    public function setOrderBy($sOrderBy)
    {
        if (!empty($sOrderBy)) {
            $this->sOrderBy = $sOrderBy;
        }

        return $this;
    }
}
