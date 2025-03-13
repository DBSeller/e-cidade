<?php
namespace App\Domain\Patrimonial\Protocolo\Repository\Processo\Contracts\AndamentoPadrao;

use App\Domain\Patrimonial\Protocolo\Model\Processo\AndamentoPadrao\CamposDinamicosResposta;

/**
* Interface da classe CamposDinamicosRespostaRepository
*
* @var string
*/
interface CamposDinamicosRespostaRepository
{
    /**
     * Funчуo que salva um novo registro
     *
     * @param CamposDinamicosResposta $model
     */
    public function persist(CamposDinamicosResposta $model);
}
