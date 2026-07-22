<?php


namespace ECidade\Tributario\Issqn\Repository;

use cl_isscadsimples;
use ECidade\Tributario\Issqn\Model\IssCadastroSimples;
use Exception;

class IssCadastroSimplesRepository
{
    /**
     * @var cl_isscadsimples
     */
    private $dao;

    /**
     * @var IssCadastroSimplesRepository
     */
    private static $instance;

    /**
     * IssCadastroSimplesRepository constructor.
     * @param cl_isscadsimples $dao
     */
    private function __construct($dao)
    {
        $this->dao = $dao;
    }

    /**
     * @param $dao
     * @return IssCadastroSimplesRepository
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
        $this->dao->q38_categoria = null;
        $this->dao->q38_inscr = null;
        $this->dao->q38_dtinicial = null;
        $this->dao->q38_sequencial = null;
    }

    /**
     * @param IssCadastroSimples $entity
     * @return IssCadastroSimples
     * @throws Exception
     */
    public function save(IssCadastroSimples $entity)
    {
        $this->dao->q38_categoria = $entity->getCategoria();
        $this->dao->q38_inscr = $entity->getInscricao();

        if ($entity->getDataInicial() !== null) {
            $this->dao->q38_dtinicial = $entity->getDataInicial()->format('Y-m-d');
        }

        if ($entity->getSequencial()) {
            $this->dao->alterar($entity->getSequencial());
        } else {
            $this->dao->incluir(null);
        }

        if ($this->dao->erro_status === '0') {
            throw new Exception("Não foi possível salvar as informações do Optante do Simples.");
        }

        $entity->setSequencial($this->dao->q38_sequencial);

        $this->cleanDao();

        return $entity;
    }
}
