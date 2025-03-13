<?php

namespace App\Domain\Patrimonial\Licitacoes\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property $l20_codigo
 * @property $l20_codtipocom
 * @property $l20_numero
 * @property $l20_id_usucria
 * @property $l20_datacria
 * @property $l20_horacria
 * @property $l20_dataaber
 * @property $l20_dtpublic
 * @property $l20_horaaber
 * @property $l20_local
 * @property $l20_objeto
 * @property $l20_tipojulg
 * @property $l20_liccomissao
 * @property $l20_liclocal
 * @property $l20_procadmin
 * @property $l20_correto
 * @property $l20_instit
 * @property $l20_licsituacao
 * @property $l20_edital
 * @property $l20_anousu
 * @property $l20_usaregistropreco
 * @property $l20_localentrega
 * @property $l20_prazoentrega
 * @property $l20_condicoespag
 * @property $l20_validadeproposta
 * @property $l20_formacontroleregistropreco
 * @property $l20_tipo
 */
class Licitacao extends Model
{
    protected $table = 'licitacao.liclicita';
    protected $primaryKey = 'l20_codigo';
    public $timestamps = false;

    public function modalidade()
    {
        return $this->hasOne(Modalidade::class, 'l03_codigo', 'l20_codtipocom');
    }
}
