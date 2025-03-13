<?php
namespace App\Domain\Patrimonial\Protocolo\Repository\Processo\Contracts;

use App\Domain\Patrimonial\Protocolo\Model\Processo\Transferencia;

/**
* Interface da classe ProcessoRepository
*
* @var string
*/
interface TransferenciaRepository
{
    /**
     * Funчуo que salva um novo registro
     *
     * @param Transferencia $model
     */
    public function persist(Transferencia $model);
}
