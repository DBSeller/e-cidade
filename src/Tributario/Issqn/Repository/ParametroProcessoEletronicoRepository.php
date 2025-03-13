<?php


namespace ECidade\Tributario\Issqn\Repository;

use ECidade\Tributario\Issqn\Model\ParametroProcessoEletronico;
use Exception;

class ParametroProcessoEletronicoRepository
{
    /**
     * @var cl_isscadsimples
     */
    private $dao;

    /**
     * @var ParametroProcessoEletronicoRepository
     */
    private static $instance;

    /**
     * ParametroProcessoEletronicoRepository constructor.
     * @param cl_isscadsimples $dao
     */
    private function __construct($dao)
    {
        $this->dao = $dao;
    }

    /**
     * @param $dao
     * @return ParametroProcessoEletronicoRepository
     */
    public static function getInstance($dao)
    {
        if (is_null(static::$instance)) {
            static::$instance = new self($dao);
        }

        return static::$instance;
    }

    private function cleanDao()
    {
        $this->dao->q150_alvaraautonomo = null;
        $this->dao->q150_alvaraempresa = null;
        $this->dao->q150_alvaramei = null;
        $this->dao->q150_alvaraautonomo_processoeletronico = null;
        $this->dao->q150_alvaraempresa_processoeletronico = null;
        $this->dao->q150_alvaramei_processoeletronico = null;
        $this->dao->q150_alvarabaixorisco = null;
        $this->dao->q150_alvaramediorisco = null;
        $this->dao->q150_alvaraaltorisco = null;
    }

    /**
     * @param IssCadastroSimples $entity
     * @return IssCadastroSimples
     * @throws Exception
     */
    public function save(ParametroProcessoEletronico $entity)
    {
        $this->buscaConfiguracao();

        if ($entity->getAlvaraAutonomo() !== null) {
            $this->dao->q150_alvaraautonomo = $entity->getAlvaraAutonomo();
        }

        if ($entity->getAlvaraEmpresa() !== null) {
            $this->dao->q150_alvaraempresa = $entity->getAlvaraEmpresa();
        }

        if ($entity->getAlvaraMei() !== null) {
            $this->dao->q150_alvaramei = $entity->getAlvaraMei();
        }

        if ($entity->getAlvaraAutonomoProcessoEletronico() !== null) {
            $this->dao->q150_alvaraautonomo_processoeletronico = $entity->getAlvaraAutonomoProcessoEletronico();
        }

        if ($entity->getAlvaraEmpresaProcessoEletronico() !== null) {
            $this->dao->q150_alvaraempresa_processoeletronico = $entity->getAlvaraEmpresaProcessoEletronico();
        }

        if ($entity->getAlvaraMeiProcessoEletronico() !== null) {
            $this->dao->q150_alvaramei_processoeletronico = $entity->getAlvaraMeiProcessoEletronico();
        }

        if ($entity->getAlvaraBaixoRisco() !== null) {
            $this->dao->q150_alvarabaixorisco = $entity->getAlvaraBaixoRisco();
        }

        if ($entity->getAlvaraMedioRisco() !== null) {
            $this->dao->q150_alvaramediorisco = $entity->getAlvaraMedioRisco();
        }

        if ($entity->getAlvaraAltoRisco() !== null) {
            $this->dao->q150_alvaraaltorisco = $entity->getAlvaraAltoRisco();
        }

        if ($this->dao->q150_sequencial != null) {
            $this->dao->alterar($this->dao->q150_sequencial);
        } else {
            $this->dao->incluir();
        }

        if ($this->dao->erro_status === '0') {
            throw new Exception("Não foi possível salvar configurações.");
        }

        $this->cleanDao();

        return $entity;
    }

    public function buscaConfiguracao()
    {
        $rs =  \db_query($this->dao->sql_query());

        $arr = array();

        if (pg_num_rows($rs) > 0) {
            $arr = pg_fetch_array($rs);

            $this->dao->q150_sequencial = $arr['q150_sequencial'];
            $this->dao->q150_alvaraautonomo = $arr['q150_alvaraautonomo'];
            $this->dao->q150_alvaraempresa = $arr['q150_alvaraempresa'];
            $this->dao->q150_alvaramei = $arr['q150_alvaramei'];
            $this->dao->q150_alvarabaixorisco = $arr['q150_alvarabaixorisco'];
            $this->dao->q150_alvaramediorisco = $arr['q150_alvaramediorisco'];
            $this->dao->q150_alvaraaltorisco = $arr['q150_alvaraaltorisco'];
            $this->dao->q150_alvaraautonomo_processoeletronico = $arr['q150_alvaraautonomo_processoeletronico'];
            $this->dao->q150_alvaraempresa_processoeletronico = $arr['q150_alvaraempresa_processoeletronico'];
            $this->dao->q150_alvaramei_processoeletronico = $arr['q150_alvaramei_processoeletronico'];
        }

        return $arr;
    }
}
