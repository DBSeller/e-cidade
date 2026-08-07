<?php
namespace App\Domain\Patrimonial\Protocolo\Repository\Processo\Contracts;

use App\Domain\Patrimonial\Protocolo\Model\Processo\ProcessoTransferencia;

/**
* Interface da classe ProcessoRepository
*
* @var string
*/
interface ProcessoTransferenciaRepository
{
    /**
     * Funчуo que salva um novo registro
     *
     * @param ProcessoTransferencia $model
     */
    public function persist(ProcessoTransferencia $model);
}
