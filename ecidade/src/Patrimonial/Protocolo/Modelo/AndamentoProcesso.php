<?php

namespace ECidade\Patrimonial\Protocolo\Modelo;

/**
 * Class AndamentoProcesso
 * @package ECidade\Patrimonial\Protocolo\Modelo
 */
class AndamentoProcesso
{
    const STATUS_A_RECEBER = 1;
    const STATUS_RECEBIDO = 2;
    const STATUS_DESPACHADO = 3;

    /**
     * @var string
     */
    private $codigo;
    /**
     * @var string
     */
    private $transferencia;
    /**
     * @var string
     */
    private $processo;
    /**
     * @var string
     */
    private $requerente;
    /**
     * @var string
     */
    private $descricao;
    /**
     * @var string
     */
    private $data;
    /**
     * @var string
     */
    private $observacao;
    /**
     * @var string
     */
    private $status;
    /**
     * @var string
     */
    private $codigoStatus;
    /**
     * @var string
     */
    private $codigoProcesso;
}
