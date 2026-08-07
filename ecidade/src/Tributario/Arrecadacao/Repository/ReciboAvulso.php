<?php

namespace ECidade\Tributario\Arrecadacao\Repository;

use ECidade\Tributario\Arrecadacao\Model\ReciboAvulso as ReciboAvulsoModel;

use cl_recibo;
use Exception;

/**
 * Class ReciboAvulso
 * @package ECidade\Tributario\Arrecadacao\Repository
 */
class ReciboAvulso extends \BaseClassRepository
{
    /**
     * @var cl_recibo
     */
    private $dao;

    /**
     * @return cl_recibo
     */
    protected function getDao()
    {
        $this->dao = !empty($this->dao) ? $this->dao : new cl_recibo();

        return $this->dao;
    }

    /**
     * @param ReciboAvulsoModel $reciboAvulso
     * @throws Exception
     */
    public function save(ReciboAvulsoModel $reciboAvulso)
    {
        $this->getDao();

        $this->dao->k00_numcgm = $reciboAvulso->getNumeroCgm();
        $this->dao->k00_dtoper = $reciboAvulso->getDataOperacao()->format('Y-m-d');
        $this->dao->k00_receit = $reciboAvulso->getCodigoReceita();
        $this->dao->k00_hist = $reciboAvulso->getCodigoHistorico();
        $this->dao->k00_valor = $reciboAvulso->getValor();
        $this->dao->k00_dtvenc = $reciboAvulso->getDataVencimento()->format('Y-m-d');
        $this->dao->k00_numpre = $reciboAvulso->getNumpre();
        $this->dao->k00_numpar = $reciboAvulso->getNumpar();
        $this->dao->k00_numtot = $reciboAvulso->getNumtot();
        $this->dao->k00_numdig = $reciboAvulso->getNumdig();
        $this->dao->k00_tipo = $reciboAvulso->getTipoDebito();
        $this->dao->k00_tipojm = $reciboAvulso->getTipojm();
        $this->dao->k00_codsubrec = $reciboAvulso->getCodsubrec();
        $this->dao->k00_numnov = $reciboAvulso->getNumnov();
        $this->dao->incluir();

        if ($this->dao->erro_status == '0') {
            throw new Exception('Erro ao salvar o recibo avulso.');
        }
    }
}
