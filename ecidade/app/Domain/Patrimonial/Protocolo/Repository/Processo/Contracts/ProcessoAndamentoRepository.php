<?php
namespace App\Domain\Patrimonial\Protocolo\Repository\Processo\Contracts;

use App\Domain\Patrimonial\Protocolo\Model\Processo\ProcessoAndamento;

/**
* Interface da classe ProcessoAndamentoRepository
*
* @var string
*/
interface ProcessoAndamentoRepository
{
    /**
     * Funчуo que salva um novo registro
     *
     * @param ProcessoAndamento $model
     */
    public function persist(ProcessoAndamento $model);
}
