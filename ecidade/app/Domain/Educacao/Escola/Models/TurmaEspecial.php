<?php

namespace App\Domain\Educacao\Escola\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * Class TurmaEspecial
 * @package App\Domain\Educacao\Escola\Models
 * @property integer $ed268_i_codigo
 * @property integer $ed268_i_codigoinep
 * @property integer $ed268_i_escola
 * @property integer $ed268_i_calendario
 * @property string $ed268_c_descr
 * @property integer $ed268_i_turno
 * @property integer $ed268_i_sala
 * @property integer $ed268_i_numvagas
 * @property integer $ed268_i_nummatr
 * @property string $ed268_t_obs
 * @property integer $ed268_i_tipoatend
 * @property integer $ed268_i_ativqtd
 * @property string $ed268_c_aee
 * @property integer $ed268_programamaiseducacao
 */
class TurmaEspecial extends Model
{
    protected $table = 'escola.turmaac';
    protected $primaryKey = 'ed268_i_codigo';
    public $timestamps = false;
    public $incrementing = false;

    protected $casts = [
        "ed268_i_codigo" => "integer",
        "ed268_i_codigoinep" => "integer",
        "ed268_i_escola" => "integer",
        "ed268_i_calendario" => "integer",
        "ed268_c_descr" => "string",
        "ed268_i_turno" => "integer",
        "ed268_i_sala" => "integer",
        "ed268_i_numvagas" => "integer",
        "ed268_i_nummatr" => "integer",
        "ed268_t_obs" => "string",
        "ed268_i_tipoatend" => "integer",
        "ed268_i_ativqtd" => "integer",
        "ed268_c_aee" => "string",
        "ed268_programamaiseducacao" => "integer",
    ];

    /**
     * @var array
     */
    private $storage = [];

    /**
     * @return integer
     */
    public function getCodigo()
    {
        return $this->ed268_i_codigo;
    }

    /**
     * @return integer
     */
    public function getCodigoINEP()
    {
        return $this->ed268_i_codigoinep;
    }

    /**
     * @return string
     */
    public function getDescricao()
    {
        return $this->ed268_c_descr;
    }

    /**
     * @return integer
     */
    public function getSala()
    {
        return $this->ed268_i_sala;
    }

    /**
     * @return integer
     */
    public function getNumeroVagas()
    {
        return $this->ed268_i_numvagas;
    }

    /**
     * @return integer
     */
    public function getNumeroMatriculas()
    {
        return $this->ed268_i_nummatr;
    }

    /**
     * @return string
     */
    public function getObservacao()
    {
        return $this->ed268_t_obs;
    }

    /**
     * @return integer
     */
    public function getTipoAtendimento()
    {
        return $this->ed268_i_tipoatend;
    }

    /**
     * @return integer
     */
    public function getQuantidadeVezesPorSemana()
    {
        return $this->ed268_i_ativqtd;
    }

    /**
     * @return string
     */
    public function getAee()
    {
        return $this->ed268_c_aee;
    }

    /**
     * @return integer
     */
    public function getProgramaMaisEducacao()
    {
        return $this->ed268_programamaiseducacao;
    }

    /**
     * @return Collection
     */
    public function getMatriculas()
    {
        if (empty($this->storage['matriculas'])) {
            $this->storage['matriculas'] = $this->matriculas;
        }

        return $this->storage['matriculas'];
    }

    /**
     * @return Escola
     */
    public function getEscola()
    {
        if (empty($this->storage['escola'])) {
            $this->storage['escola'] = $this->escola;
        }

        return $this->storage['escola'];
    }

    /**
     * @return Calendario
     */
    public function getCalendario()
    {
        if (empty($this->storage['calendario'])) {
            $this->storage['calendario'] = $this->calendario;
        }

        return $this->storage['calendario'];
    }

    /**
     * @return Turno
     */
    public function getTurno()
    {
        if (empty($this->storage['turno'])) {
            $this->storage['turno'] = $this->turno;
        }

        return $this->storage['turno'];
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function escola()
    {
        return $this->belongsTo(Escola::class, 'ed268_i_escola', 'ed18_i_codigo');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function calendario()
    {
        return $this->belongsTo(Calendario::class, 'ed268_i_calendario', 'ed52_i_codigo');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function turno()
    {
        return $this->belongsTo(Turno::class, 'ed268_i_turno', 'ed15_i_codigo');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function matriculas()
    {
        return $this->hasMany(TurmaEspecialMatricula::class, 'ed269_i_turmaac', 'ed268_i_codigo')
            ->with('aluno');
    }

    public function scopeGetPorCalendarioEscola($query, $calendario, $escola)
    {
        return $query->where('ed268_i_escola', $escola)
                    ->where('ed268_i_calendario', $calendario);
    }
}
