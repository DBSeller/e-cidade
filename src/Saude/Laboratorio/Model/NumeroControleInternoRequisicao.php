<?php

namespace ECidade\Saude\Laboratorio\Model;

use cl_numerocontroleinternorequisicao;

class NumeroControleInternoRequisicao
{
    /**
     * sequencial
     *
     * @var int
     */
    private $sequencial;

    /**
     * numero
     *
     * @var int
     */
    private $numero;

    /**
     * ano
     *
     * @var int
     */
    private $ano;

    /**
     * codigoRequisicao
     *
     * @var int
     */
    private $codigoRequisicao;

    /**
     * __construct
     *
     * @param  int $sequencial
     * @return void
     */
    public function __construct($sequencial = null)
    {
        if (!empty($sequencial)) {
            $dao = new cl_numerocontroleinternorequisicao();
            $sql = $dao->sql_query_file($sequencial);
            $rs = db_query($sql);
            $this::fromState($rs);
        }
    }

    /**
     * setSequencial
     *
     * @param  int $sequencial
     * @return void
     */
    public function setSequencial($sequencial)
    {
        $this->sequencial = $sequencial;
    }

    /**
     * getSequencial
     *
     * @return int
     */
    public function getSequencial()
    {
        return $this->sequencial;
    }

    /**
     * setNumero
     *
     * @param  int $numero
     * @return void
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;
    }

    /**
     * getNumero
     *
     * @return int
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * setAno
     *
     * @param  int $ano
     */
    public function setAno($ano)
    {
        $this->ano = $ano;
    }

    /**
     * getAno
     *
     * @return int
     */
    public function getAno()
    {
        return $this->ano;
    }

    /**
     * setCodigoRequisicao
     *
     * @param  int $codigoRequisicao
     * @return int
     */
    public function setCodigoRequisicao($codigoRequisicao)
    {
        $this->codigoRequisicao = $codigoRequisicao;
    }

    /**
     * getCodigoRequisicao
     *
     * @return int
     */
    public function getCodigoRequisicao()
    {
        return $this->codigoRequisicao;
    }

    /**
     * toArray
     *
     * @return array
     */
    public function toArray()
    {
        return array(
          'la65_sequencial' => $this->getSequencial(),
          'la65_numero'     => $this->getNumero(),
          'la65_ano'        => $this->getAno(),
          'la65_requisicao' => $this->getCodigoRequisicao()
        );
    }

    /**
     * fromState
     *
     * @param  array $state
     * @return NumeroControleInternoRequisicao
     */
    public static function fromState($state)
    {
        $numeroControleInternoRequisicao = new self;

        if (array_key_exists('la65_sequencial', $state)) {
            $numeroControleInternoRequisicao->setSequencial((int)$state['la65_sequencial']);
        }

        if (array_key_exists('la65_numero', $state)) {
            $numeroControleInternoRequisicao->setNumero((int)$state['la65_numero']);
        }

        if (array_key_exists('la65_ano', $state)) {
            $numeroControleInternoRequisicao->setAno((int)$state['la65_ano']);
        }

        if (array_key_exists('la65_requisicao', $state)) {
            $numeroControleInternoRequisicao->setCodigoRequisicao((int)$state['la65_requisicao']);
        }

        return $numeroControleInternoRequisicao;
    }
}
