<?php

namespace ECidade\Tributario\Issqn\Repository;

use ECidade\Tributario\Issqn\Model\ConfIssqnRetidoEmpresaPublica;

class ConfIssqnRetidoEmpresaPublicaRepository
{
    /**
     * Variável com a instância da DAO
     *
     * @var cl_confissqnretidopublica
     */
    private $dao;

    /**
     * Variável com as condições de consulta
     *
     * @var array
     */
    private $aCondition = [];

    /**
     * Varivável com os campos para consulta
     * @var string
     */
    private $sCampos = "*";

    /**
     * ConfIssqnRetidoEmpresaPublicaRepository constructor.
     */
    public function __construct()
    {
        $this->dao = new \cl_confissqnretidopublica();
    }

    /**
     * Salva os dados na base
     * @param ConfIssqnRetidoEmpresaPublica $entity
     * @return int
     * @throws \Exception
     */
    public function persist(ConfIssqnRetidoEmpresaPublica $entity)
    {
        $this->dao->j170_sequencial = $entity->getSequencial();
        $this->dao->j170_receit  = $entity->getReceit();
        $this->dao->j170_hist    = $entity->getHist();
        $this->dao->j170_tipo    = $entity->getTipo();
        $this->dao->j170_anousu  = $entity->getAnousu();

        if (!empty($this->dao->j170_sequencial)) {
            $this->dao->alterar($this->dao->j170_sequencial);
        } else {
            $this->dao->incluir(null);
        }

        if ($this->dao->erro_status == "0") {
            throw new \Exception($this->dao->erro_msg);
        }

        return $this->dao->j170_sequencial;
    }

    /**
     * Faz uma cosulta na base
     * @param boolean $bAll | Se for true irá retornar uma coleção de linhas, senão somente uma linha
     * @return array|stdClass
     * @throws \Exception
     */
    public function get($bAll = false)
    {
        $result = $this->dao->sql_record($this->dao->sql_query(
            "",
            $this->sCampos,
            "j170_sequencial",
            implode(" AND ", $this->aCondition)
        ));

        if (!$result) {
            throw new \Exception("Erro ao buscar a configuração. \n\n {$this->dao->erro_msg}");
        }

        if ($bAll) {
            return \db_utils::getCollectionByRecord($result);
        }

        return \db_utils::fieldsMemory($result, 0);
    }

    /**
     * Seta os campos para a consulta
     * @param string $sCampos
     * @return ConfIssqnRetidoEmpresaPublicaRepository
     */
    public function setCampos($sCampos)
    {
        $this->sCampos = $sCampos;
        return $this;
    }

    /**
     * Função para condições de consulta padão
     * @param ConfIssqnRetidoEmpresaPublica $entity
     * @return $this
     */
    public function setDefaultCondition(ConfIssqnRetidoEmpresaPublica $entity)
    {
        if (!empty($entity->getSequencial())) {
            $this->aCondition[] = "j170_sequencial = {$entity->getSequencial()}";
        }

        if (!empty($entity->getReceit())) {
            $this->aCondition[] = "j170_receit = {$entity->getReceit()}";
        }

        if (!empty($entity->getHist())) {
            $this->aCondition[] = "j170_hist = {$entity->getHist()}";
        }

        if (!empty($entity->getTipo())) {
            $this->aCondition[] = "j170_tipo = {$entity->getTipo()}";
        }

        if (!empty($entity->getAnousu())) {
            $this->aCondition[] = "j170_anousu = {$entity->getAnousu()}";
        }

        return $this;
    }

    /**
     * Função para personalizar condições de consulta
     * @param $sCondition
     * @return $this
     */
    public function setOuterCondition($sCondition)
    {
        if (!empty($sCondition)) {
            $this->aCondition[] = $sCondition;
        }

        return $this;
    }
}
