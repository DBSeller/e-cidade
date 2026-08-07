<?php

namespace ECidade\Tributario\Cadastro\Repository;

use cl_setorloc;
use ECidade\Tributario\Cadastro\Model\Setorloc;

class SetorlocRepository
{
    /**
     * @var SetorlocRepository
     */
    private static $instance;

    /**
     * @var string[]
     */
    private $scopes;

    /**
     * @var string[]
     */
    private $joins;

    private function __construct()
    {
    }

    /**
     * @return SetorlocRepository
     */
    public static function getInstance()
    {
        if (empty(static::$instance)) {
            static::$instance = new static();
        }

        return static ::$instance;
    }

    /**
     * @param string[] $campos
     *
     * @return Setorloc[]
     */
    public function get($campos = array('*'))
    {
        $dao = new cl_setorloc;
        $campos = implode(', ', $campos);
        $where = implode(' AND ', $this->scopes);
        $joins = implode(' ', $this->joins);

        $sql = $dao->query($campos, $joins, $where);

        $rs = db_query($sql);
        $registros = pg_fetch_all($rs);

        $setorlocs = array();
        foreach ($registros as $registro) {
            $setorlocs[] = Setorloc::fromState($registro);
        }

        $this->scopes = array();

        return $setorlocs;
    }

    /**
     * @param string[] $campos
     * @return Setorloc|null
     */
    public function first($campos = array('*'))
    {
        $all = $this->get($campos);

        if (empty($all)) {
            return null;
        }

        return $all[0];
    }

    /**
     * @param string $valor
     * @param string $operacao
     * @return $this
     */
    public function scopeCodigo($valor, $operacao = '=')
    {
        $this->scopes['codigo'] = "j05_codigo {$operacao} {$valor}";
        return $this;
    }

    /**
     * @param string $valor
     * @param string $operacao
     * @return $this
     */
    public function scopeIdbql($valor, $operacao = '=')
    {
        $this->innerJoin('loteloc', 'j06_setorloc = j05_codigo');
        $this->scopes['lote'] = "j06_idbql {$operacao} {$valor}";
        return $this;
    }

    /**
     * @param string $tabela
     * @param string $condicao
     * @return $this
     */
    private function innerJoin($tabela, $condicao)
    {
        $this->joins[$tabela] = "INNER JOIN {$tabela} ON {$condicao}";
        return $this;
    }
}
