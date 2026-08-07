<?php

namespace ECidade\Patrimonial\Compras\TipoPrestacaoEmpenho\Model;

class TipoPrestacao
{

    /**
     * @var int
     */
    private $codigoTipoPrestacao;

    /**
     * @var string
     */
    private $descricao;

    /**
     * @var int
     */
    private $obrigacao;

    /**
     * @var int
     */
    private $naturezaEvento;

    /**
     * @var boolean
     */
    private $diaria;

    public function __construct($codigoTipoPrestacao = null)
    {
        if ($codigoTipoPrestacao) {
            $dao = new \cl_empprestatip();
            $sql = $dao->sql_query_file($codigoTipoPrestacao);

            $rs = $dao->sql_record($sql);

            if ($rs) {
                $this->codigoTipoPrestacao = pg_fetch_result($rs, 0, 'e44_tipo');
                $this->descricao = pg_fetch_result($rs, 0, 'e44_descr');
                $this->obrigacao = pg_fetch_result($rs, 0, 'e44_obriga');
                $this->naturezaEvento = pg_fetch_result($rs, 0, 'e44_naturezaevento');
                $this->diaria = pg_fetch_result($rs, 0, 'e44_diaria');
            }
        }
    }

    /**
     * @return int
     */
    public function getCodigoTipoPrestacao()
    {
        return $this->codigoTipoPrestacao;
    }

    /**
     * @param int $codigoTipoPrestacao
     */
    public function setCodigoTipoPrestacao($codigoTipoPrestacao)
    {
        $this->codigoTipoPrestacao = $codigoTipoPrestacao;
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

    /**
     * @return int
     */
    public function getObrigacao()
    {
        return $this->obrigacao;
    }

    /**
     * @param int $obrigacao
     */
    public function setObrigacao($obrigacao)
    {
        $this->obrigacao = $obrigacao;
    }

    /**
     * @return int
     */
    public function getNaturezaEvento()
    {
        return $this->naturezaEvento;
    }

    /**
     * @param int $naturezaEvento
     */
    public function setNaturezaEvento($naturezaEvento)
    {
        $this->naturezaEvento = $naturezaEvento;
    }

    /**
     * @return bool
     */
    public function isDiaria()
    {
        return $this->diaria;
    }

    /**
     * @param bool $diaria
     */
    public function setDiaria($diaria)
    {
        $this->diaria = $diaria;
    }

    public static function fromState(array $state)
    {
        $tipoPrestacao = new self();

        if (array_key_exists('e44_tipo', $state)) {
            $tipoPrestacao->setCodigoTipoPrestacao((int) $state['e44_tipo']);
        }

        if (array_key_exists('e44_descr', $state)) {
            $tipoPrestacao->setDescricao($state['e44_descr']);
        }

        if (array_key_exists('e44_obriga', $state)) {
            $tipoPrestacao->setObrigacao((int) $state['e44_obriga']);
        }

        if (array_key_exists('e44_naturezaevento', $state)) {
            $tipoPrestacao->setNaturezaEvento((int) $state['e44_naturezaevento']);
        }

        if (array_key_exists('e44_diaria', $state)) {
            $tipoPrestacao->setNaturezaEvento((boolean) $state['e44_diaria']);
        }

        return $tipoPrestacao;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $retorno = array(
            'e44_tipo' => $this->getCodigoTipoPrestacao(),
            'e44_descr' => $this->getDescricao(),
            'e44_obriga' => $this->getObrigacao(),
            'e44_naturezaevento' => $this->getNaturezaEvento(),
            'e44_diaria' => $this->isDiaria()
        );

        return $retorno;
    }
}
