<?php
namespace App\Domain\Patrimonial\Protocolo\Repository\Processo\Contracts;

use App\Domain\Patrimonial\Protocolo\Model\Processo\Arquivamento;

/**
* Interface da classe ArquivamentoRepository
*
* @var string
*/
interface ArquivamentoRepository
{
    /**
     * Funчуo que salva um novo registro
     *
     * @param Arquivamento $model
     */
    public function persist(Arquivamento $model);
}
