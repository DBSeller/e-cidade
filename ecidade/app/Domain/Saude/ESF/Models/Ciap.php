<?php

namespace App\Domain\Saude\ESF\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @package App\Domain\Saude\ESF\Models
 * @property string codigo                  character varying(15)     not null
 * @property integer componente              integer
 * @property string titulo_original         character varying(255)
 * @property string titulo_leigo            character varying(255)
 * @property string cids10_possiveis        text
 * @property string cid10_mais_frequentes   text
 * @property string definicao               text
 * @property string criterios_inclusao      text
 * @property string criterios_exclusao      text
 * @property string considerar              text
 * @property string nota                    text
 */
class Ciap extends Model
{
    public $timestamps = false;
    protected $table = 'plugins.psf_ciap';
}
