<?php

namespace ECidade\Saude\Laboratorio\Repository;

use cl_numerocontroleinternorequisicao;
use ECidade\Saude\Laboratorio\Model\NumeroControleInternoRequisicao as NumeroControleInternoRequisicaoModel;
use RequisicaoExame;
use Exception;
use db_utils;

/**
 * NumeroControleInternoRequisicao
 */
class NumeroControleInternoRequisicao
{
    /**
     * dao
     *
     * @var cl_numerocontroleinternorequisicao
     */
    private $dao;

    /**
     * scopes
     *
     * @var array
     */
    private $scopes;

    /**
     * collection
     *
     * @var NumeroControleInternoRequisicaoModel[]
     */
    private $collection = array();

    /**
     * instance
     *
     * @var NumeroControleInternoRequisicao
     */
    public static $instance;

    /**
     * @param NumeroControleInternoRequisicaoModel $numeroControleInternoRequisicaoModel
     */
    public function add(NumeroControleInternoRequisicaoModel $numeroControleInternoModel)
    {
        $this->collection[$numeroControleInternoModel->getSequencial()] = $numeroControleInternoModel;
    }

    /**
     * @param NumeroControleInternoRequisicaoModel $numeroControleInternoRequisicaoModel
     * @throws Exception
     */
    public function excluir()
    {
        $this->dao->excluir(null, implode(' AND ', $this->scopes));

        if ($this->dao->erro_status == '0') {
            throw new Exception('Erro ao excluir Número de Controle Interno da Requisição.');
        }

        $this->resetScopes();
    }

    /**
     * @return NumeroControleInternoRequisicao
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new static();
        }

        if (self::$instance->dao === null) {
            self::$instance->dao = new cl_numerocontroleinternorequisicao();
        }

        return self::$instance;
    }

    /**
     * scopeRequisicao
     *
     * @param  int $codigoRequisicao
     * @param  String $operator
     * @return void
     */
    public function scopeRequisicao($codigoRequisicao, $operator = '=')
    {
        $this->scopes['requisicao'] = "la65_requisicao {$operator} {$codigoRequisicao}";
    }

    /**
     * scopeAno
     *
     * @param  int $ano
     * @param  String $operator
     * @return void
     */
    public function scopeAno($ano, $operator = '=')
    {
        $this->scopes['ano'] = "la65_ano {$operator} {$ano}";
    }

    /**
     * @param $numeroControleInterno
     * @param string $operator
     */
    public function scopeNumeroControleInterno($numeroControleInterno, $operator = '=')
    {
        $this->scopes['numeroControleInterno'] = "la65_numero {$operator} {$numeroControleInterno}";
    }

    /**
     * resetScopes
     *
     * @return void
     */
    public function resetScopes()
    {
        $this->scopes = array();
    }

    /**
     * get
     *
     * @return NumeroControleInternoRequisicaoModel|null
     * @throws Exception
     */
    public function get()
    {
        $sql = $this->dao->sql_query_file(null, '*', null, implode(' AND ', $this->scopes));
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception('Erro ao buscar Número de Controle Interno da Requisição.');
        }

        $linhas = pg_num_rows($rs);

        if ($linhas === 0) {
            return null;
        }

        $dados = (array)db_utils::fieldsMemory($rs, 0);
        $numeroControleInternoRequisicaoModel = NumeroControleInternoRequisicaoModel::fromState($dados);

        $this->add($numeroControleInternoRequisicaoModel);
        $this->resetScopes();
        
        return $numeroControleInternoRequisicaoModel;
    }

    /**
     * getByRequisicao
     *
     * @param RequisicaoExame $requisicao
     * @return NumeroControleInternoRequisicaoModel|null
     * @throws Exception
     */
    public function getByRequisicao(RequisicaoExame $requisicao)
    {
        foreach ($this->collection as $numeroControleInternoRequisicaoModel) {
            if ($numeroControleInternoRequisicaoModel->getCodigoRequisicao() === $requisicao->getCodigoRequisicao()
            ) {
                return $numeroControleInternoRequisicaoModel;
            }
        }

        $this->scopeRequisicao($requisicao->getCodigoRequisicao());

        return $this->get();
    }

    /**
     * salvar
     *
     * @param NumeroControleInternoRequisicaoModel $numeroControleInternoRequisicaoModel
     * @return NumeroControleInternoRequisicaoModel
     * @throws Exception
     */
    public function salvar(NumeroControleInternoRequisicaoModel $numeroControleInternoRequisicaoModel)
    {
        $this->dao->la65_sequencial = $numeroControleInternoRequisicaoModel->getSequencial();
        $this->dao->la65_numero = $numeroControleInternoRequisicaoModel->getNumero();
        $this->dao->la65_ano = $numeroControleInternoRequisicaoModel->getAno();
        $this->dao->la65_requisicao = $numeroControleInternoRequisicaoModel->getCodigoRequisicao();

        $this->dao->incluir(null);

        if ($this->dao->erro_status == '0') {
            throw new Exception('Erro ao incluir Número de Controle Interno da Requisição.');
        }

        $numeroControleInternoRequisicaoModel->setSequencial($this->dao->la65_sequencial);

        return $numeroControleInternoRequisicaoModel;
    }


    /**
     * __construct
     *
     * @return void
     */
    private function __construct()
    {
    }

    /**
     * __clone
     *
     * @return void
     */
    private function __clone()
    {
    }
}
