<?php 

namespace ECidade\Tributario\Caixa\Repository;

use ECidade\Tributario\Library\Repository;
use ECidade\Tributario\Caixa\Model\Dbreciboweb;
use ECidade\Tributario\Caixa\Collection\DbrecibowebCollection;

final class DbrecibowebRepository extends Repository
{
    public function insert(Dbreciboweb $dbreciboweb)
    {
        $codbco = $dbreciboweb->getCodbco();

        if (empty($codbco)) {
            $codbco = '0';
        }

        $codage = $dbreciboweb->getCodage();

        if (empty($codage)) {
            $codage = '0';
        }

        $this->dao->k99_numpre = $dbreciboweb->getNumpre();
        $this->dao->k99_numpar = $dbreciboweb->getNumpar();
        $this->dao->k99_numpre_n = $dbreciboweb->getNumpren();
        $this->dao->k99_codbco = $codbco;
        $this->dao->k99_codage = $codage;
        $this->dao->k99_numbco = (string) $dbreciboweb->getNumbco();
        $this->dao->k99_desconto = $dbreciboweb->getDesconto();
        $this->dao->k99_tipo = $dbreciboweb->getTipo();
        $this->dao->k99_origem = $dbreciboweb->getOrigem();

        return $this->dao->incluir(
            $dbreciboweb->getNumpre(), 
            $dbreciboweb->getNumpar(), 
            $dbreciboweb->getNumpren()
        );
    }

    public function make($object)
    {
        if (empty($object)) {
            return null;
        }

        $dbreciboweb = new Dbreciboweb();

        $dbreciboweb->setNumpre($object->k99_numpre);
        $dbreciboweb->setNumpar($object->k99_numpar);
        $dbreciboweb->setNumpren($object->k99_numpre_n);
        $dbreciboweb->setCodbco($object->k99_codbco);
        $dbreciboweb->setCodage($object->k99_codage);
        $dbreciboweb->setNumbco($object->k99_numbco);
        $dbreciboweb->setDesconto($object->k99_desconto);
        $dbreciboweb->setTipo($object->k99_tipo);
        $dbreciboweb->setOrigem($object->k99_origem);

        return $dbreciboweb;
    }

    public function find($numpre, $numpar, $numpren)
    {
        $sql = $this->dao->sql_query_file($numpre, $numpar, $numpren);

        $result = $this->dataBase->execute($sql);

        $object = $this->dataBase->fetchRow($result);

        return $this->make($object);
    }

    public function findAll($where = "")
    {
        $sql = $this->dao->sql_query_file(null, "*", null, $where);

        $result = $this->dataBase->execute($sql);

        return new DbrecibowebCollection($result);
    }
}
