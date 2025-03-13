<?php
namespace App\Domain\Tributario\ISSQN\Repository\AlvaraEventos\Contracts;

use App\Domain\Tributario\ISSQN\Model\AlvaraEventos\AlvaraEvento;

/**
* Interface da classe AlvaraEventoRepository
*
* @var string
*/
interface AlvaraEventoRepository
{
    /**
     * Funчуo que salva um novo registro
     *
     * @param AlvaraEvento $model
     */
    public function persist(AlvaraEvento $model);

    /**
     * Funчуo que remove um registro
     *
     * @param integer $id
     */
    public function delete($id);
}
