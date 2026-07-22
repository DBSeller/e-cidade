<?php


namespace App\Domain\Financeiro\Orcamento\Models;

use ECidade\Enum\Financeiro\Orcamento\TipologiaProgramaEnum;
use Exception;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Programa
 * @package App\Domain\Financeiro\Orcamento\Models
 * @property $o54_anousu
 * @property $o54_programa
 * @property $o54_descr
 * @property $o54_codtri
 * @property $o54_finali
 * @property $o54_problema
 * @property $o54_publicoalvo
 * @property $o54_justificativa
 * @property $o54_objsetorassociado
 * @property $o54_tipoprograma
 * @property $o54_estrategiaimp
 */
class Programa extends Model
{
    protected $table = 'orcamento.orcprograma';

    public function formataCodigo()
    {
        return str_pad($this->o54_programa, 4, '0', STR_PAD_LEFT);
    }
    /**
     * @return TipologiaProgramaEnum
     * @throws Exception
     */
    public function getTipologia()
    {
        return new TipologiaProgramaEnum((int) $this->o54_tipoprograma);
    }
}
