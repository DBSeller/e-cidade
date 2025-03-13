<?php

namespace App\Domain\Integracoes\EFDReinf\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Configuracoes gerais EFD-REINF
 *
 * @property int $efd07_sequencial
 * @property bool $efd07_filtraorgaounidade
 * @property int $efd07_instit
 */
class EFDReinfConfiguracao extends Model
{
    protected $table = 'esocial.efdreinfconfiguracao';
    protected $primaryKey = 'efd07_sequencial';
    public $timestamps = false;
}
