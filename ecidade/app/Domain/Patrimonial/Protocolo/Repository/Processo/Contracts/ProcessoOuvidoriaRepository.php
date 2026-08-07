<?php
namespace App\Domain\Patrimonial\Protocolo\Repository\Processo\Contracts;

use App\Domain\Patrimonial\Protocolo\Model\Processo\ProcessoOuvidoria;

/**
* Interface da classe ProcessoOuvidoriaRepository
*
* @var string
*/
interface ProcessoOuvidoriaRepository
{
    /**
     * Funчуo que salva um novo registro
     *
     * @param ProcessoOuvidoria $model
     */
    public function persist(ProcessoOuvidoria $model);
}
