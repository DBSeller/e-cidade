<?php

namespace ECidade\Tributario\Caixa\Repository;

use cl_recibopaga;
use \DateTime;
use ECidade\Tributario\Library\Repository;
use ECidade\Tributario\Caixa\Model\Recibopaga;
use ECidade\Tributario\Caixa\Collection\RecibopagaCollection;
use Exception;

final class RecibopagaRepository extends Repository
{
    /**
     * @var array
     */
    private $scopes = array();

    /**
     * @param  $numpre
     * @param string $operador
     * @return $this
     */
    public function scopeNumpre($numpre, $operador = '=')
    {
        $this->scopes['numpre'] = "k00_numpre {$operador} {$numpre}";
        return $this;
    }

    /**
     * @return ReciboPaga[]
     * @throws Exception
     */
    public function get()
    {
        $dao = new cl_recibopaga();
        $sql = $dao->sql_query_file(null, '*', 'k00_dtpaga asc', implode(' AND ', $this->scopes));
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Não foi possível buscar os débitos pagos.");
        }

        $debitos = array();

        if (pg_num_rows($rs) === 0) {
            return $debitos;
        }

        while ($debito = pg_fetch_array($rs)) {
            $debitos[] = ReciboPaga::fromState($debito);
        }

        return $debitos;
    }

    public function make($object)
    {
        if (empty($object)) {
            return null;
        }

        $recibopaga = new Recibopaga();

        $recibopaga->setNumcgm($object->k00_numcgm);
        $recibopaga->setDtoper(new DateTime($object->k00_dtoper));
        $recibopaga->setReceit($object->k00_receit);
        $recibopaga->setHist($object->k00_hist);
        $recibopaga->setValor($object->k00_valor);
        $recibopaga->setDtvenc(new DateTime($object->k00_dtvenc));
        $recibopaga->setNumpre($object->k00_numpre);
        $recibopaga->setNumpar($object->k00_numpar);
        $recibopaga->setNumtot($object->k00_numtot);
        $recibopaga->setNumdig($object->k00_numdig);
        $recibopaga->setConta($object->k00_conta);
        $recibopaga->setDtpaga(new DateTime($object->k00_dtpaga));
        $recibopaga->setNumnov($object->k00_numnov);

        return $recibopaga;
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

        $result = $this->dataBase->execute($sql);

        return new RecibopagaCollection($result);
    }

    public function findAllByNumnov($numnov)
    {
        return $this->findAll("k00_numnov = {$numnov}");
    }

    /**
     * @param Recibopaga $recibopaga
     * @throws Exception
     */
    public function save(Recibopaga $recibopaga)
    {
        $this->dao->k00_numcgm = $recibopaga->getNumcgm();
        $this->dao->k00_dtoper = $recibopaga->getDtoper()->format('Y-m-d');
        $this->dao->k00_receit = $recibopaga->getReceit();
        $this->dao->k00_hist = $recibopaga->getHist();
        $this->dao->k00_valor = $recibopaga->getValor();
        $this->dao->k00_dtvenc = $recibopaga->getDtvenc()->format('Y-m-d');
        $this->dao->k00_numpre = $recibopaga->getNumpre();
        $this->dao->k00_numpar = $recibopaga->getNumpar();
        $this->dao->k00_numtot = $recibopaga->getNumtot();
        $this->dao->k00_numdig = $recibopaga->getNumdig();
        $this->dao->k00_conta = $recibopaga->getConta();
        $this->dao->k00_dtpaga = $recibopaga->getDtpaga()->format('Y-m-d');
        $this->dao->k00_numnov = $recibopaga->getNumnov();
        $this->dao->incluir();

        if ($this->dao->erro_status == '0') {
            throw new Exception('Erro ao salvar o recibo.');
        }
    }
}
