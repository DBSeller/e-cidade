<?php 

namespace ECidade\Tributario\Cadastro\Repository;

use ECidade\Tributario\Library\Repository;
use ECidade\Tributario\Cadastro\Model\Iptutaxacalv;

final class IptutaxacalvRepository extends Repository
{
    public function persist(Iptutaxacalv $iptutaxacalv)
    {
        $this->dao->j152_iptutaxanump = $iptutaxacalv->getIptutaxanump();
        $this->dao->j152_codhis = $iptutaxacalv->getCodhis();
        $this->dao->j152_receit = $iptutaxacalv->getreceit();
        $this->dao->j152_valor = $iptutaxacalv->getValor();

        $codigo = $iptutaxacalv->getCodigo();

        if (empty($codigo)) {
            $result = $this->dao->incluir(null);
        } else {

            $this->dao->j152_codigo = $codigo;
            $result = $this->dao->alterar($codigo);
        }

        if (!$result) {
            
            $mensagem = 'Ocorreu um erro ao ';
            $mensagem .= (empty($codigo) ? 'incluir' : 'alterar');
            $mensagem .= ' a tabela iptutaxacalv . ' . $this->dao->erro_msg;

            throw new \Exception($mensagem);
        }

        return true;
    }

    public function make($object)
    {
        if (empty($object)) {
            return null;
        }

        $iptutaxacalv = new Iptutaxacalv();

        $iptutaxacalv->setCodigo($object->j152_codigo);
        $iptutaxacalv->setIptutaxanump($object->j152_iptutaxanump);
        $iptutaxacalv->setCodhis($object->j152_codhis);
        $iptutaxacalv->setReceit($object->j152_receit);
        $iptutaxacalv->setValor($object->j152_valor);

        return $iptutaxacalv;
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
