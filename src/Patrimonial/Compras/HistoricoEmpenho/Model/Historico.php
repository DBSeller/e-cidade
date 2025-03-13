<?php

namespace ECidade\Patrimonial\Compras\HistoricoEmpenho\Model;

use db_utils;

class Historico
{

    /**
     * @var int
     */
    private $codigo;

    /**
     * @var string
     */
    private $descricao;


    public function __construct($codigoHistorico = null)
    {
        if (!is_null($codigoHistorico)) {
            $dao = new \cl_emphist();
            $sql = $dao->sql_query_file($codigoHistorico);

            $rs = $dao->sql_record($sql);

            if ($rs) {
                $this->codigo = pg_fetch_result($rs, 0, 'e40_codhist');
                $this->descricao = pg_fetch_result($rs, 0, 'e40_descr');
            }
        }
    }

    /**
     * @return int
     */
    public function getCodigo()
    {
        return $this->codigo;
    }

    /**
     * @param int $codigo
     */
    public function setCodigo($codigo)
    {
        $this->codigo = $codigo;
    }

    /**
     * @return string
     */
    public function getDescricao()
    {
        return $this->descricao;
    }

    /**
     * @param string $descricao
     */
    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;
    }



    public static function fromState(array $state)
    {
        $historico = new self();

        if (array_key_exists('e40_codhist', $state)) {
            $historico->setCodigo((int)$state['e40_codhist']);
        }

        if (array_key_exists('e40_descr', $state)) {
            $historico->setDescricao($state['e40_descr']);
        }

        return $historico;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $retorno = array(
            'e40_codhist' => $this->getCodigo(),
            'e40_desc' => $this->getDescricao()
        );

        return $retorno;
    }
}
