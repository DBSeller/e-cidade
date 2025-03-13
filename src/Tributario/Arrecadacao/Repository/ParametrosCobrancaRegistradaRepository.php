<?php

namespace ECidade\Tributario\Arrecadacao\Repository;

use ECidade\Tributario\Arrecadacao\Model\ParametrosCobrancaRegistrada;

class ParametrosCobrancaRegistradaRepository extends \BaseClassRepository
{
    public function persist(ParametrosCobrancaRegistrada $entity)
    {
        $error = false;

        if ($entity->getClientid() === "" and $entity->getClientsecret() !== "") {
            $error = true;
        } else {
            if ($entity->getClientid() !== "" and $entity->getClientsecret() === "") {
                $error = true;
            }
        }

        if ($error) {
            throw new \Exception("Os campos ClientID e Client Secret devem ser preenchidos");
        }

        $dao = new \cl_parametroscobrancaregistrada();

        $dao->ar28_sequencial = $entity->getSequencial();
        $dao->ar28_usuario = $entity->getUsuario();
        $dao->ar28_clientid = $entity->getClientid();
        $dao->ar28_clientsecret = $entity->getClientsecret();
        $dao->ar28_codban = $entity->getCodban();
        $dao->ar28_chavej = $entity->getChavej();

        if (!empty($dao->ar28_sequencial)) {
            $dao->alterar($dao->ar28_sequencial);
        } else {
            $dao->incluir(null);
        }

        if ($dao->erro_status == 0) {
            throw new \Exception($dao->erro_msg);
            $entity->setSequencial($dao->ar28_sequencial);
        }

        return $entity;
    }

    public function delete(ParametrosCobrancaRegistrada $entity)
    {
        $dao = new \cl_parametroscobrancaregistrada();
        $dao->ar28_sequencial = $entity->getSequencial();
        $dao->excluir($dao->ar28_sequencial);

        if ($dao->erro_status == 0) {
            throw new \Exception($dao->erro_msg);
        }

        return $entity;
    }

    public function make($item)
    {
        $entity = new ParametrosCobrancaRegistrada();

        $entity->setSequencial($item->ar28_sequencial);

        if (!empty($item->ar28_usuario)) {
            $entity->setUsuario($item->ar28_usuario);
        }

        if (!empty($item->ar28_clientid)) {
            $entity->setClientid($item->ar28_clientid);
        }

        if (!empty($item->ar28_clientsecret)) {
            $entity->setClientsecret($item->ar28_clientsecret);
        }

        if (!empty($item->ar28_codban)) {
            $entity->setCodban($item->ar28_codban);
        }

        if (!empty($item->ar28_chavej)) {
            $entity->setChavej($item->ar28_chavej);
        }

        return $entity;
    }

    public function getByWebservice(ParametrosCobrancaRegistrada $entity)
    {
        $dao = new \cl_parametroscobrancaregistrada();

        $codban = $entity->getCodban();

        $sql = $dao->sql_query("*", "", "", "ar28_codban = '{$codban}'");

        $result = db_query($sql);

        if (!$result) {
            throw new \Exception("Erro ao buscar as configurações para o banco {$codban}.");
        }

        return $this->make(\db_utils::fieldsMemory($result, 0));
    }

    public function getByCollectionWebservice()
    {
        $dao = new \cl_parametroscobrancaregistrada();

        $sql = $dao->sql_query();

        $result = db_query($sql);

        if (!$result) {
            throw new \Exception("Erro ao buscar as configurações de todos os bancos.");
        }

        return (object) \db_utils::getColectionByRecord($result);
    }
}
