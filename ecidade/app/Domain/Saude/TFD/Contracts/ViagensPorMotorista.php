<?php

namespace App\Domain\Saude\TFD\Contracts;

/**
 * Interface responsável por garantir a funcionalidade do relatorio de viagens por motorista
 * @package App\Domain\Saude\TFD\Contracts
 */
interface ViagensPorMotorista
{
    const ORDEM_DATA = 1;
    const ORDEM_VEICULO = 2;

    /**
     * @param array $dados
     */
    public function __construct(array $dados);

    /**
     * @param int $ordem
     * @return array ['name' => '', 'path' => '']
     */
    public function emitir($ordem);
}
