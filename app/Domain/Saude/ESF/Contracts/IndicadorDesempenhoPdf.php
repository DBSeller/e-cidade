<?php

namespace App\Domain\Saude\ESF\Contracts;

/**
 * @package App\Domain\Saude\ESF\Contracts
 */
interface IndicadorDesempenhoPdf
{
    /**
     * @param array $dados
     */
    public function __construct(array $dados);

    /**
     * @return array
     * `['name' => $nomeRelatorio, 'path' => $nomeArquivo, 'pathExterno' => ECIDADE_REQUEST_PATH . $nomeArquivo]`
     */
    public function emitir();
}
