<?php

namespace App\Domain\RecursosHumanos\Pessoal\Model\Jetom;

use Illuminate\Database\Eloquent\Model;

/**
 * Class PermissaoComissao
 * @property int rh251_sequencial
 * @property int rh251_matricula
 * @property int rh251_comissao
 * @package App\Domain\RecursosHumanos\Pessoal\Model\Jetom
 */
class PermissaoComissao extends Model
{
    protected $table = 'pessoal.jetompermissao';
    protected $primaryKey = 'rh251_sequencial';
    public $timestamps = false;
    public $incrementing = false;

    public function getSequencial()
    {
        return $this->rh251_sequencial;
    }


    public function setMatricula($matricula)
    {
        $this->rh251_matricula = $matricula;
    }

    public function getMatricula()
    {
        return $this->rh251_matricula;
    }

    public function setComissao($comissao)
    {
        $this->rh251_comissao = $comissao;
    }

    public function getComissao()
    {
        return $this->rh251_comissao;
    }

    /**
     * Seleciona e seta e  o próximo id sequencial, e persiste dados
     * @param array|null $options
     * @return bool
     */
    public function callSave(array $options = null)
    {
        $next_id = \DB::select("select nextval('pessoal.jetompermissao_rh251_sequencial_seq')");
        
        //Seta o sequencial
        $this->rh251_sequencial = $next_id['0']->nextval;

        return parent::save();
    }

    public static function comissaoExists($comissao)
    {
        return self::where('rh251_comissao', $comissao)->first();
    }
}
