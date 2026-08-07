<?php

namespace ECidade\Tributario\Caixa\Repository;

use ECidade\Tributario\Library\Repository;
use ECidade\Tributario\Caixa\Model\Arrecad;
use ECidade\Tributario\Caixa\Collection\ArrecadCollection;

final class ArrecadRepository extends Repository
{
    public function insert(Arrecad $arrecad)
    {
        $this->dao->k00_numpre = $arrecad->getNumpre();
        $this->dao->k00_numpar = $arrecad->getNumpar();
        $this->dao->k00_numcgm = $arrecad->getNumcgm();
        $this->dao->k00_dtoper = $arrecad->getDtoper()->format('Y-m-d');
        $this->dao->k00_receit = $arrecad->getReceit();
        $this->dao->k00_hist = $arrecad->getHist();
        $this->dao->k00_valor = $arrecad->getValor();
        $this->dao->k00_dtvenc = $arrecad->getDtvenc()->format('Y-m-d');
        $this->dao->k00_numtot = $arrecad->getNumtot();
        $this->dao->k00_numdig = $arrecad->getNumdig();
        $this->dao->k00_tipo = $arrecad->getTipo();
        $this->dao->k00_tipojm = $arrecad->getTipojm();

        return $this->dao->incluir();
    }

    public function update(Arrecad $arrecad)
    {
        $this->dao->k00_numpre = $arrecad->getNumpre();
        $this->dao->k00_numpar = $arrecad->getNumpar();
        $this->dao->k00_numcgm = $arrecad->getNumcgm();
        $this->dao->k00_dtoper = $arrecad->getDtoper()->format('Y-m-d');
        $this->dao->k00_receit = $arrecad->getReceit();
        $this->dao->k00_hist = $arrecad->getHist();
        $this->dao->k00_valor = $arrecad->getValor();
        $this->dao->k00_dtvenc = $arrecad->getDtvenc()->format('Y-m-d');
        $this->dao->k00_numtot = $arrecad->getNumtot();
        $this->dao->k00_numdig = $arrecad->getNumdig();
        $this->dao->k00_tipo = $arrecad->getTipo();
        $this->dao->k00_tipojm = $arrecad->getTipojm();

        $where = "k00_numpre = {$arrecad->getNumpre()}
            and k00_numpar = {$arrecad->getNumpar()}
            and k00_receit = {$arrecad->getReceit()}";

        return $this->dao->alterar_arrecad($where);
    }

    private function delete(Arrecad $arrecad)
    {
        $where = "k00_numpre = {$arrecad->getNumpre()}
            and k00_numpar = {$arrecad->getNumpar()}
            and k00_receit = {$arrecad->getReceit()}";

        $result = $this->dao->excluir(null, $where);

        if (!$result) {
            return false;
        }

        return true;
    }

    public function make($object)
    {
        if (empty($object)) {
            return null;
        }

        $arrecad = new Arrecad();

        $arrecad->setNumpre($object->k00_numpre);
        $arrecad->setNumpar($object->k00_numpar);
        $arrecad->setNumcgm($object->k00_numcgm);
        $arrecad->setDtoper(new \DateTime($object->k00_dtoper));
        $arrecad->setReceit($object->k00_receit);
        $arrecad->setHist($object->k00_hist);
        $arrecad->setValor($object->k00_valor);
        $arrecad->setDtvenc(new \DateTime($object->k00_dtvenc));
        $arrecad->setNumtot($object->k00_numtot);
        $arrecad->setNumdig($object->k00_numdig);
        $arrecad->setTipo($object->k00_tipo);
        $arrecad->setTipojm($object->k00_tipojm);

        return $arrecad;
    }

    public function find($numpre, $numpar, $receit)
    {
        $where = "k00_numpre = {$numpre} and k00_numpar = {$numpar} and k00_receit = {$receit}";

        $sql = $this->dao->sql_query_file(null, "*", null, $where);

        $result = $this->dataBase->execute($sql);

        $object = $this->dataBase->fetchRow($result);

        return $this->make($object);
    }

    public function findAll($where = "")
    {
        $sql = $this->dao->sql_query_file(null, "*", null, $where);

        return $this->findAllFromSQL($sql);
    }

    public function findAllFromSQL($sql)
    {
        $result = $this->dataBase->execute($sql);

        return new ArrecadCollection($result);
    }

    /**
     * @param integer $numpre
     * @return integer
     */
    public function findMaxNumpar($numpre)
    {
        $where = "k00_numpre = {$numpre}";

        $sql = $this->dao->sql_query_file(null, "max(k00_numtot)", null, $where);

        $result = $this->dataBase->execute($sql);

        $object = $this->dataBase->fetchRow($result, 0);

        return $object->max;
    }
}
