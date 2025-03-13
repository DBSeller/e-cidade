<?php 

namespace ECidade\Tributario\Cadastro\Repository;

use ECidade\Tributario\Library\Repository;
use ECidade\Tributario\Cadastro\Model\Iptutaxanump;

final class IptutaxanumpRepository extends Repository
{
    public function persist(Iptutaxanump $iptutaxanump)
    {
        $this->dao->j151_matric = $iptutaxanump->getMatric();
        $this->dao->j151_numpre = $iptutaxanump->getNumpre();
        $this->dao->j151_iptucadtaxaexe = $iptutaxanump->getIptucadtaxaexe();

        $codigo = $iptutaxanump->getCodigo();

        if (empty($codigo)) {
            $result = $this->dao->incluir(null);
        } else {

            $this->dao->j152_codigo = $codigo;
            $result = $this->dao->alterar($codigo);
        }

        if (!$result) {
            
            $mensagem = 'Ocorreu um erro ao ';
            $mensagem .= (empty($codigo) ? 'incluir' : 'alterar');
            $mensagem .= ' a tabela iptutaxanump . ' . $this->dao->erro_msg;

            throw new \Exception($mensagem);
        }

        return true;
    }

    public function make($object)
    {
        if (empty($object)) {
            return null;
        }

        $iptutaxanump = new Iptutaxanump();

        $iptutaxanump->setCodigo($object->codigo);
        $iptutaxanump->setMatric($object->matric);
        $iptutaxanump->setNumpre($object->numpre);
        $iptutaxanump->setIptucadtaxaexe($object->iptucadtaxaexe);

        return $iptutaxanump;
    }

    private function makeCollection($array)
    {
        $collection = array();

        if (empty($array)) {
            return $collection;
        }

        foreach ($array as $value) {
            $collection[] = $this->make((object)$value);
        }

        return $collection;
    }

    public function find($codigo, $receit)
    {
        $sql = $this->dao->sql_query_file($codigo, $receit);

        $result = $this->dataBase->execute($sql);

        $object = $this->dataBase->fetchRow($result);

        return $this->make($object);
    }

    public function findAll($where = "")
    {
        $sql = $this->dao->sql_query_file(null, "*", null, $where);

        $result = $this->dataBase->execute($sql);

        $array = $this->dataBase->getCollectionByRecord($result);
        
        return $this->makeCollection($array);
    }
}
