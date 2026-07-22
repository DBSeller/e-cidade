<?php

namespace ECidade\Educacao\Escola\Repository;

abstract class Repository
{
    /**
     * @var array
     */
    protected $scopes = array();

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

    /**
     * @param string $sql
     * @param string $message
     * @return bool|resource
     * @throws Exception
     */
    protected function execute($sql, $message = null)
    {
        $rs = db_query($sql);

        if (empty($message)) {
            $message = "Erro ao executar query.";
        }
        if (!$rs) {
            throw new Exception($message);
        }
        return $rs;
    }
}
