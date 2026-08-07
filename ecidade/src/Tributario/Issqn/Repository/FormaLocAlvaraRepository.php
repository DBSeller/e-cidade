<?php

namespace ECidade\Tributario\Issqn\Repository;

use BaseClassRepository;
use cl_formalocalvara;
use db_utils;
use ECidade\Tributario\Issqn\Model\FormaLocAlvara;

class FormaLocAlvaraRepository extends BaseClassRepository
{
    public function getLista($sWhere = "")
    {
        $dao = new cl_formalocalvara();
        $sql = $dao->sql_query("", "", "", $sWhere);
        $result = db_query($sql);

        if (!$result) {
            throw new \DBException('Erro ao buscar os locais de execução da atividade.');
        }

        return $this->makeColletion(db_utils::getColectionByRecord($result));
    }

    public function make($item)
    {
        $entity = new FormaLocAlvara();

        if (!empty($item->q167_sequencial)) {
            $entity->setSequencial($item->q167_sequencial);
        }

        if (!empty($item->q167_descricao)) {
            $entity->setDescricao($item->q167_descricao);
        }

        if (!empty($item->q167_data_validade)) {
            $entity->setDataValidade($item->q167_data_validade);
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

    public function persist(FormaLocAlvara $entity)
    {
        if (!$entity->getDescricao()) {
            throw new \Exception("Necessário informar uma descrição!");
        }

        if (!$entity->getDataValidade()) {
            throw new \Exception("Necessário informar uma data de validade!");
        }

        $dao = new cl_formalocalvara();

        $dao->q167_sequencial    = $entity->getSequencial();
        $dao->q167_descricao     = $entity->getDescricao();
        $dao->q167_data_validade = $entity->getDataValidade();

        if (!empty($dao->q167_sequencial)) {
            $dao->alterar($dao->q167_sequencial);
        } else {
            $dao->incluir(null);
        }

        if ($dao->erro_status == 0) {
            throw new \Exception($dao->erro_msg);
        }

        return $entity;
    }

    public function delete(FormaLocAlvara $entity)
    {
        $dao = new cl_formalocalvara();
        $dao->q167_sequencial = $entity->getSequencial();
        $dao->excluir($dao->q167_sequencial);

        if ($dao->erro_status == 0) {
            throw new \Exception($dao->erro_msg);
        }

        return $entity;
    }
}
