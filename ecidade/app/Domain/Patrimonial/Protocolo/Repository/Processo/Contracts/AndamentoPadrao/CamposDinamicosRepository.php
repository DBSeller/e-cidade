<?php
namespace App\Domain\Patrimonial\Protocolo\Repository\Processo\Contracts\AndamentoPadrao;

use App\Domain\Patrimonial\Protocolo\Model\Processo\AndamentoPadrao\CamposDinamicos;

/**
* Interface da classe CamposDinamicosRepository
*
* @var string
*/
interface CamposDinamicosRepository
{
    /**
     * Funчуo que salva um novo registro
     *
     * @param CamposDinamicos $model
     */
    public function persist(CamposDinamicos $model);
}
