<?php

namespace App\Domain\Patrimonial\Material\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $m61_codmatunid
 * @property string $m61_descr
 * @property boolean $m61_usaquant
 * @property string $m61_abrev
 * @property boolean $m61_usadesc
 * @property string $m61_codigotribunal
 */
class UnidadeMaterial extends Model
{
    public $timestamps = false;
    protected $table = 'material.matunid';
    protected $primaryKey = 'm61_codmatunid';
}
