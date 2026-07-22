<?php

namespace ECidade\RecursosHumanos\RH\Efetividade\Repository;

use cl_configuracoesdatasefetividade;
use ECidade\RecursosHumanos\RH\Efetividade\Model\ConfiguracaoDataEfetividade;
use Exception;
use Instituicao;

class ConfiguracaoDataEfetividadeRepository
{
    /**
     * @var array
     */
    protected $scopes = array();

    /**
     * @param Instituicao $instituicao
     * @param string $operator
     * @return $this
     */
    public function scopeInstituicao(Instituicao $instituicao, $operator = '=')
    {
        $this->scopes['rh186_instituicao'] = "rh186_instituicao {$operator} {$instituicao->getSequencial()}";
        return $this;
    }

    /**
     * @param array $columns
     * @param array $order
     * @return ConfiguracaoDataEfetividade[]
     * @throws Exception
     */
    public function get($columns = array('*'), $order = array())
    {
        $dao = new cl_configuracoesdatasefetividade;

        $sql = $dao->sql_query_file(
            null,
            implode(', ', $columns),
            implode(', ', $order),
            implode(' AND ', $this->scopes)
        );

        $resultado = db_query($sql);

        if (!$resultado) {
            throw new Exception(
                "Não foi possível buscar as configurações de data da efetividade.\nContate o suporte."
            );
        }

        $registros = array();

        if (pg_num_rows($resultado) === 0) {
            return $registros;
        }

        while ($registro = pg_fetch_array($resultado)) {
            $registros[] = ConfiguracaoDataEfetividade::fromState($registro);
        }

        return $registros;
    }

    /**
     * @return $this
     */
    public function resetScopes()
    {
        $this->scopes = array();

        return $this;
    }

    /**
     * @param $key
     * @return $this
     */
    public function removeScope($key)
    {
        if (array_key_exists($key, $this->scopes)) {
            unset($this->scopes[$key]);
        }

        return $this;
    }
}
