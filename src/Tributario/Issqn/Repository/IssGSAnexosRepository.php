<?php

namespace Ecidade\Tributario\Issqn\Repository;

use BaseClassRepository;
use cl_issgsanexos;
use db_utils;
use ECidade\Tributario\Issqn\Model\IssGSAnexos;

class IssGSAnexosRepository extends BaseClassRepository
{
    public function getByAnexosGrupoServico($gruposervico)
    {
        $sql = "SELECT
                    *
                FROM
                    issqn.issgsanexos
                    INNER JOIN issqn.issgruposervico ON q162_issgruposervico = q126_sequencial
                    INNER JOIN issqn.issgscadanexos ON q162_issgscadanexos = q157_sequencial
                WHERE
                    q126_sequencial = $gruposervico";
        $result = db_query($sql);

        if (!$result) {
            throw new \DBException('Erro ao buscar anexos adicionados ao grupo de serviço.');
        }

        return $this->make(db_utils::fieldsMemory($result, 0));
    }

    public function getAnexos()
    {
        $dao = new cl_issgsanexos();
        $sql = $dao->sql_query();
        $result = db_query($sql);

        if (!$result) {
            throw new \DBException('Erro ao buscar os anexos.');
        }

        return $this->makeColletion(db_utils::getColectionByRecord($result));
    }

    public function make($item)
    {
        $entity = new IssGSAnexos();

        if (!empty($item->q162_sequencial)) {
            $entity->setSequencial($item->q162_sequencial);
        }
        if (!empty($item->q162_issgruposervico)) {
            $entity->setIssgruposervico($item->q162_issgruposervico);
        }
        if (!empty($item->q162_issgscadanexos)) {
            $entity->setIssgscadanexos($item->q162_issgscadanexos);
        }
        if (!empty($item->q162_data_fim)) {
            $entity->setDataFim($item->q162_data_fim);
        }

        return $entity;
    }

    public function makeColletion($collection)
    {
        $dados = array();

        foreach ($collection as $item) {
            $dados[] = $this->make($item);
        }

        return $dados;
    }

    public function persist(IssGSAnexos $entity)
    {
        if ($entity->getDataFim() === null) {
            throw new \Exception("Necessário informar uma data limite!");
        }

        $dao = new cl_issgsanexos();

        $dao->$q162_sequencial = $entity->getSequencial();
        $dao->$q162_issgruposervico = $entity->getIssgruposervico();
        $dao->$q162_issgscadanexos = $entity->getIssgscadanexos();
        $dao->$q162_data_fim = $entity->getData_fim();

        if (!empty($dao->q162_sequencial)) {
            $dao->alterar($dao->q162_sequencial);
        } else {
            $dao->incluir(null);
        }

        if ($dao->erro_status == 0) {
            throw new \Exception($dao->erro_msg);
        }

        return $entity;
    }

    public function delete(IssGSAnexos $entity)
    {
        $dao = new cl_issgsanexos();
        $dao->q162_sequencial = $entity->getSequencial();
        $dao->excluir($dao->q162_sequencial);

        if ($dao->erro_status == 0) {
            throw new \Exception($dao->erro_msg);
        }

        return $entity;
    }
}
