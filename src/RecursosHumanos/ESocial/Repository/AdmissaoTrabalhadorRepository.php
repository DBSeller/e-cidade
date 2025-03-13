<?php

namespace ECidade\RecursosHumanos\ESocial\Repository;

use cl_avaliacaogruporespostarhpessoal;
use Exception;
use ECidade\RecursosHumanos\ESocial\Entity\AdmissaoTrabalhador;

class AdmissaoTrabalhadorRepository
{
    /**
     * @var AdmissaoTrabalhadorRepository
     */
    private static $instance;

    /**
     * @var array
     */
    private $scopes;

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

    private function scope($id, $campo, $operacao, $valor)
    {
        $this->scopes[$id] = "{$campo} {$operacao} {$valor}";
        return $this;
    }

    /**
     * Filtra por 'eso02_rhpessoal'
     *
     * @param mixed $matricula
     * @param string $operacao
     * @return $this
     */
    public function scopeMatricula($matricula, $operacao = '=')
    {
        return $this->scope('eso02_rhpessoal', 'eso02_rhpessoal', $operacao, $matricula);
    }

    /**
     * @param array $campos Campos a serem selecionados eso02_rhpessoal
     * @return AdmissaoTrabalhador[]
     */
    public function get($campos = array('*'))
    {
        $dao = new cl_avaliacaogruporespostarhpessoal();
        $sql = $dao->sql_avaliacao_preenchida($campos, $this->scopes);

        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception('Não foi possível buscar as informações do servidor!');
        }

        $alteracoes = array();
        while ($alteracao = pg_fetch_assoc($rs)) {
            $alteracoes[] = AdmissaoTrabalhador::fromState($alteracao);
        }

        $this->clearScopes();

        return $alteracoes;
    }

    private function clearScopes()
    {
        $this->scopes = array();
    }
}
