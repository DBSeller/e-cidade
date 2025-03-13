<?php

namespace ECidade\Tributario\Issqn\Repository;

use ECidade\Tributario\Issqn\Model\ConfIssqnRetidoPublicaTipoEmpresa;

class ConfIssqnRetidoPublicaTipoEmpresaRepository
{
    /**
     * Variável com a instância da DAO
     *
     * @var cl_confissqnretidopublicatipoempresa
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
     * ConfIssqnRetidoPublicaTipoEmpresaRepository constructor.
     */
    public function __construct()
    {
        $this->dao = new \cl_confissqnretidopublicatipoempresa();
    }

    /**
     * Salva os dados na base
     * @param ConfIssqnRetidoPublicaTipoEmpresa $entity
     * @return int
     * @throws \Exception
     */
    public function persist(ConfIssqnRetidoPublicaTipoEmpresa $entity)
    {
        $this->dao->j171_sequencial = $entity->getSequencial();
        $this->dao->j171_confissqnretidopublica = $entity->getConfissqnretidopublica();
        $this->dao->j171_tipoempresa = $entity->getTipoempresa();

        if (!empty($this->dao->j171_sequencial)) {
            $this->dao->alterar($this->dao->j171_sequencial);
        } else {
            $this->dao->incluir(null);
        }

        if ($this->dao->erro_status == "0") {
            throw new \Exception($this->dao->erro_msg);
        }
    }

    /**
     * Função que deleta dados da base
     * @throws \Exception
     */
    public function delete()
    {
        $this->dao->excluir(
            null,
            implode(" AND ", $this->aCondition)
        );

        if ($this->dao->erro_status == "0") {
            throw new \Exception($this->dao->erro_msg);
        }
    }

    /**
     * Faz uma cosulta na base
     * @param boolean $bAll | Se for true irá retornar uma coleção de linhas, senão somente uma linha
     * @return array | object
     * @throws \Exception
     */
    public function get($bAll = false)
    {
        $result = $this->dao->sql_record($this->dao->sql_query(
            "",
            $this->sCampos,
            "j171_sequencial",
            implode(" AND ", $this->aCondition)
        ));

        if (!$result) {
            throw new \Exception("Erro ao buscar o(s) tipo(s). \n\n {$this->dao->erro_msg}");
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
     * @param ConfIssqnRetidoPublicaTipoEmpresa $entity
     * @return $this
     */
    public function setDefaultCondition(ConfIssqnRetidoPublicaTipoEmpresa $entity)
    {
        if (!empty($entity->getSequencial())) {
            $this->aCondition[] = "j170_sequencial = {$entity->getSequencial()}";
        }

        if (!empty($entity->getConfissqnretidopublica())) {
            $this->aCondition[] = "j171_confissqnretidopublica = {$entity->getConfissqnretidopublica()}";
        }

        if (!empty($entity->getTipoempresa())) {
            $this->aCondition[] = "j171_tipoempresa = {$entity->getTipoempresa()}";
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
