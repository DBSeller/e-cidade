<?php

namespace ECidade\RecursosHumanos\ESocial\Repository;

use cl_avaliacaogruporespostaadmissaopreliminar;
use Exception;
use ECidade\RecursosHumanos\ESocial\Entity\AdmissaoPreliminar;

class AdmissaoPreliminarRepository
{
    /**
     * @var AdmissaoPreliminar
     */
    private static $instance;

    /**
     * @var array
     */
    private $scopes = array();

    public static function getInstance()
    {
        if (empty(static::$instance)) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    private function __construct()
    {
    }

    /**
     * @return AdmissaoPreliminar[]
     */
    public function get()
    {
        $dao = new cl_avaliacaogruporespostaadmissaopreliminar();
        $where = implode(' AND ', $this->scopes);
        $sql = $dao->sql_query_file(null, '*', null, $where);

        $res = db_query($sql);

        if (!$res) {
            throw new Exception('Não foi possível buscar a admissão preliminar.');
        }

        $admissoes = array();

        $rows = pg_fetch_all($res);
        if ($rows) {
            foreach ($rows as $admissao) {
                $admissoes[] = AdmissaoPreliminar::fromState($admissao);
            }
        }

        $this->clearScopes();

        return $admissoes;
    }

    /**
     * @return $this
     */
    private function scope($id, $campo, $operacao, $valor)
    {
        $this->scopes[$id] = "({$campo} {$operacao} {$valor})";
        return $this;
    }

    /**
     * Filtra por 'eso18_cgm'
     *
     * @return $this
     */
    public function scopeCgm($valor, $operacao = '=')
    {
        return $this->scope('eso18_cgm', 'eso18_cgm', $operacao, $valor);
    }

    /**
     * Filtra por 'eso18_cpf'
     *
     * @return $this
     */
    public function scopeCpf($valor, $operacao = '=')
    {
        return $this->scope('eso18_cpf', 'eso18_cpf', $operacao, "'{$valor}'");
    }

     /**
     * Filtra por 'eso18_regist'
     *
     * @return $this
     */
    public function scopeMatricula($valor, $operacao = '=')
    {
        return $this->scope('eso18_regist', 'eso18_regist', $operacao, "'{$valor}'");
    }

    private function clearScopes()
    {
        $this->scopes = array();
    }
}
