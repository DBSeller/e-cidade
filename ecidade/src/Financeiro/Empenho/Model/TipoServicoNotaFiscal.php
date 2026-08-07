<?php

namespace ECidade\Financeiro\Empenho\Model;

use ECidade\Financeiro\Empenho\Repository\TipoServicoNotaFiscalRepository;
use Exception;

/**
 * Class TipoServicoNotaFiscal
 * @package ECidade\Financeiro\Empenho\Model
 */
class TipoServicoNotaFiscal
{
    /**
     * @var int
     */
    private $sequencial;
    /**
     * @var string
     */
    private $referencia;
    /**
     * @var string
     */
    private $descricao;

    /**
     * TipoServicoNotaFiscal constructor.
     * @param null $sequencial
     * @throws Exception
     */
    public function __construct($sequencial = null)
    {
        if ($sequencial) {
            $tipoServicoNotaFiscal = TipoServicoNotaFiscalRepository::find($sequencial);

            $this->sequencial = $tipoServicoNotaFiscal->getSequencial();
            $this->referencia = $tipoServicoNotaFiscal->getReferencia();
            $this->descricao = $tipoServicoNotaFiscal->getDescricao();
        }
    }

    /**
     * @return int
     */
    public function getSequencial()
    {
        return $this->sequencial;
    }

    /**
     * @param int $sequencial
     */
    public function setSequencial($sequencial)
    {
        $this->sequencial = $sequencial;
    }

    /**
     * @return string
     */
    public function getReferencia()
    {
        return $this->referencia;
    }

    /**
     * @param string $referencia
     * @return TipoServicoNotaFiscal
     */
    public function setReferencia($referencia)
    {
        $this->referencia = $referencia;
        return $this;
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
     * @return TipoServicoNotaFiscal
     */
    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;
        return $this;
    }

    /**
     * @param array $state
     * @return TipoServicoNotaFiscal
     * @throws Exception
     */
    public static function fromState(array $state)
    {
        $tipoServicoNotaFiscal = new self();

        if (array_key_exists('e18_sequencial', $state)) {
            $tipoServicoNotaFiscal->setSequencial((int)$state['e18_sequencial']);
        }

        if (array_key_exists('e18_referencia', $state)) {
            $tipoServicoNotaFiscal->setReferencia((string)$state['e18_referencia']);
        }

        if (array_key_exists('e18_descricao', $state)) {
            $tipoServicoNotaFiscal->setDescricao((string)$state['e18_descricao']);
        }

        return $tipoServicoNotaFiscal;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return array(
            'sequencial' => $this->getSequencial(),
            'referencia' => $this->getReferencia(),
            'descricao' => $this->getDescricao(),
        );
    }
}
