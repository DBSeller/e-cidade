<?php
namespace App\Domain\Patrimonial\Protocolo\Repository\Processo\Contracts;

use App\Domain\Patrimonial\Protocolo\Model\Processo\ArquivamentoAndamento;

/**
* Interface da classe ArquivamentoAndamentoRepository
*
* @var string
*/
interface ArquivamentoAndamentoRepository
{
    /**
     * Funчуo que salva um novo registro
     *
     * @param ArquivamentoAndamento $model
     */
    public function persist(ArquivamentoAndamento $model);
}
