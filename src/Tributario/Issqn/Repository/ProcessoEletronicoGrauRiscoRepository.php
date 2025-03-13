<?php


namespace ECidade\Tributario\Issqn\Repository;

use ECidade\Tributario\Issqn\Model\ProcessoEletronicoGrauRisco;
use Exception;

class ProcessoEletronicoGrauRiscoRepository
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
        $this->dao->q151_sequencial = null;
        $this->dao->q151_processo = null;
        $this->dao->q151_graurisco = null;
    }

    /**
     * @param IssCadastroSimples $entity
     * @return IssCadastroSimples
     * @throws Exception
     */
    public function save(ProcessoEletronicoGrauRisco $entity)
    {
        if ($entity->getCodigo() !== null) {
            $this->dao->q151_sequencial = $entity->getCodigo();
        }

        if ($entity->getProcesso() !== null) {
            $this->dao->q151_processo = $entity->getProcesso();
        }

        if ($entity->getGrauRisco() !== null) {
            $this->dao->q151_graurisco = $entity->getGrauRisco();
        }

        if ($this->dao->q151_sequencial != null) {
            $this->dao->alterar($this->dao->q151_sequencial);
        } else {
            $this->dao->incluir();
            $entity->setCodigo($this->dao->q151_sequencial);
        }

        if ($this->dao->erro_status === '0') {
            throw new Exception("Não foi possível salvar configurações.");
        }

        $this->cleanDao();

        return $entity;
    }

    public function findByProcesso($processo)
    {
        $this->dao->q151_processo = $processo;

        $rs = \db_query($this->dao->sql_query_file(null, "*", null, "q151_processo = $processo"));

        if (pg_num_rows($rs) == 0) {
            throw new Exception("Processo não encontrado");
        }

        $state = pg_fetch_array($rs);
        $entity = new ProcessoEletronicoGrauRisco;
        return $entity->fromState($state);
    }
}
