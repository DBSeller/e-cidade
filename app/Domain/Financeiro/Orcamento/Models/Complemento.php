<?php


namespace App\Domain\Financeiro\Orcamento\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Complemento
 * @package App\Domain\Financeiro\Orcamento\Models
 * @property integer $o200_sequencial
 * @property string $o200_descricao
 * @property boolean $o200_msc
 * @property boolean $o200_tribunal
 */
class Complemento extends Model
{
    protected $table = 'orcamento.complementofonterecurso';
    protected $primaryKey = 'o200_sequencial';
    public $timestamps = false;
    public $incrementing = false;

    protected $casts = [
        'o200_msc' => "boolean",
        'o200_tribunal' => "boolean",
    ];

    public $hidden = ['o200_sequencial', 'o200_descricao', 'o200_msc', 'o200_tribunal'];

    protected $appends = ["codigo", 'descricao', 'msc', 'tribunal'];

    public function getCodigoAttribute()
    {
        return $this->attributes['o200_sequencial'];
    }

    public function getDescricaoAttribute()
    {
        return $this->attributes['o200_descricao'];
    }

    public function getMscAttribute()
    {
        return $this->attributes['o200_msc'];
    }

    public function getTribunalAttribute()
    {
        return $this->attributes['o200_tribunal'];
    }

    /**
     * @param integer $sequencial
     * @return $this
     */
    public function setSequencial($sequencial)
    {
        $this->o200_sequencial = $sequencial;
        return $this;
    }

    /**
     * @param string $descricao
     * @return $this
     */
    public function setDescricao($descricao)
    {
        $this->o200_descricao = $descricao;
        return $this;
    }

    /**
     * @param $msc
     * @return $this
     */
    public function setMsc($msc)
    {
        $this->o200_msc = $this->convertBoolean($msc);
        return $this;
    }

    /**
     * @param $tribunal
     * @return $this
     */
    public function setTribunal($tribunal)
    {
        $this->o200_tribunal = $this->convertBoolean($tribunal);
        return $this;
    }

    /**
     * @param $valor
     * @return bool
     */
    private function convertBoolean($valor)
    {
        if (is_bool($valor)) {
            return $valor;
        }

        if (is_integer((int)$valor)) {
            return (int)$valor === 1;
        }

        return false;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function recursos()
    {
        return $this->hasMany(Recurso::class, 'o15_complemento', 'o200_sequencial');
    }
}
