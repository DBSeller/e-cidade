<?php

namespace App\Domain\RecursosHumanos\Pessoal\Model\Servidor;

use Illuminate\Database\Eloquent\Model;

/**
 * @package App\Domain\RecursosHumanos\Pessoal\Model\Servidor
 * @property integer $rh70_sequencial
 * @property string $rh70_estrutural
 * @property string $rh70_descr
 * @property integer $rh70_tipo
 * @property string $rh70_classificacaorisco
 */
class Cbo extends Model
{
    public $timestamps = false;
    protected $table = 'pessoal.rhcbo';
    protected $primaryKey = 'rh70_sequencial';
}
