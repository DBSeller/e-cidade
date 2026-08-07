<?php

namespace Ecidade\Tributario\Issqn\Repository;

use BaseClassRepository;
use cl_isscnaeanexos;
use db_utils;
use ECidade\Tributario\Issqn\Model\IssCnaeAnexos;

class IssCnaeAnexosRepository extends BaseClassRepository
{
    public function getByAnexosCnae($cnae)
    {
        $sql = "SELECT
                    *
                FROM
                    issqn.isscnaeanexos
                    INNER JOIN issqn.cnae ON q178_cnae = q71_sequencial
                    INNER JOIN issqn.issgscadanexos ON q178_issgscadanexos = q157_sequencial
                WHERE
                    q71_sequencial = $cnae";
        $result = db_query($sql);

        if (!$result) {
            throw new \DBException('Erro ao buscar anexos adicionados ao grupo de serviço.');
        }

        return $this->make(db_utils::fieldsMemory($result, 0));
    }

    public function getAnexos()
    {
        $dao = new cl_isscnaeanexos();
        $sql = $dao->sql_query();
        $result = db_query($sql);

        if (!$result) {
            throw new \DBException('Erro ao buscar os anexos.');
        }

        return $this->makeColletion(db_utils::getColectionByRecord($result));
    }

    public function make($item)
    {
        $entity = new IssCnaeAnexos();

        if (!empty($item->q178_sequencial)) {
            $entity->setSequencial($item->q178_sequencial);
        }
        if (!empty($item->q178_cnae)) {
            $entity->setCnae($item->q178_cnae);
        }
        if (!empty($item->q178_issgscadanexos)) {
            $entity->setIssgscadanexos($item->q178_issgscadanexos);
        }
        if (!empty($item->q178_data_fim)) {
            $entity->setDataFim($item->q178_data_fim);
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

    public function persist(IssCnaeAnexos $entity)
    {
        if ($entity->getDataFim() === null) {
            throw new \Exception("Necessário informar uma data limite!");
        }

        $dao = new cl_isscnaeanexos();

        $dao->q178_sequencial = $entity->getSequencial();
        $dao->q178_cnae = $entity->getCnae();
        $dao->q178_issgscadanexos = $entity->getIssgscadanexos();
        $dao->q178_data_fim = $entity->getDataFim();

        if (!empty($dao->q178_sequencial)) {
            $dao->alterar($dao->q178_sequencial);
        } else {
            $dao->incluir(null);
        }

        if ($dao->erro_status == 0) {
            throw new \Exception($dao->erro_msg);
        }

        return $entity;
    }

    public function delete(IssCnaeAnexos $entity)
    {
        $dao = new cl_isscnaeanexos();
        $dao->q178_codigo = $entity->getSequencial();
        $dao->excluir($dao->q178_codigo);

        if ($dao->erro_status == 0) {
            throw new \Exception($dao->erro_msg);
        }

        return $entity;
    }
}
