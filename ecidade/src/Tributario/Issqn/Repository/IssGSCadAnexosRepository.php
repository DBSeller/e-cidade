<?php

namespace Ecidade\Tributario\Issqn\Repository;

use BaseClassRepository;
use cl_issgscadanexos;
use db_utils;
use ECidade\Tributario\Issqn\Model\IssGSCadAnexos;

class IssGSCadAnexosRepository extends BaseClassRepository
{
    public function getByDescricao($sequencial)
    {
        $dao = new cl_issgscadanexos();
        $sql = $dao->sql_query("", "", "", "q157_descricao = '{$sequencial}'");
        $result = db_query($sql);

        if (!$result) {
            throw new \DBException('Erro ao buscar a descricao do anexo.');
        }

        return $this->make(db_utils::fieldsMemory($result, 0));
    }

    public function getAnexos()
    {
        $dao = new cl_issgscadanexos();
        $sql = $dao->sql_query();
        $result = db_query($sql);

        if (!$result) {
            throw new \DBException('Erro ao buscar os anexos.');
        }

        return $this->makeColletion(db_utils::getColectionByRecord($result));
    }

    public function make($item)
    {
        $entity = new IssGSCadAnexos();

        if (!empty($item->q157_sequencial)) {
            $entity->setSequencial($item->q157_sequencial);
        }
        if (!empty($item->q157_codigo)) {
            $entity->setCodigo($item->q157_codigo);
        }

        if (!empty($item->q157_descricao)) {
            $entity->setDescricao($item->q157_descricao);
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

    public function persist(IssGSCadAnexos $entity)
    {
        if ($entity->getDescricao() === null && $entity->getCodigo() === null) {
            throw new \Exception("Necessário informar uma descrição e um codigo!");
        }

        $dao = new cl_issgscadanexos();

        $dao->q157_codigo = $entity->getCodigo();
        $dao->q157_descricao = $entity->getDescricao();

        if (!empty($dao->j164_tipopromitente)) {
            $dao->alterar($dao->j164_tipopromitente);
        } else {
            $dao->incluir(null);
        }

        if ($dao->erro_status == 0) {
            throw new \Exception($dao->erro_msg);
        }

        return $entity;
    }

    public function delete(IssGSCadAnexos $entity)
    {
        $dao = new cl_issgscadanexos();
        $dao->q157_codigo = $entity->getCodigo();
        $dao->excluir($dao->q157_codigo);

        if ($dao->erro_status == 0) {
            throw new \Exception($dao->erro_msg);
        }

        return $entity;
    }
}
