<?php

namespace App\Domain\Assinador;

use DateTime;
use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $c142_codigo
 * @property integer $c142_usuario
 * @property integer $c142_arquivopfx
 * @property DateTime $c142_data
 * @property DateTime $c142_validade
 */
class CertificadoUsuario extends Model
{
    protected $table = 'configuracoes.certificadosusuarios';
    protected $primaryKey = 'c142_codigo';
    public $timestamps = false;

    protected $casts = [
        'c142_data' => 'DateTime',
        'c142_validade' => 'DateTime',
    ];
}
