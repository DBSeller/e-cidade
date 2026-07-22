<?php

namespace App\Domain\Educacao\Escola\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class TurmaEspecialMatricula
 * @package App\Domain\Educacao\Escola\Models
 * @property integer $ed269_i_codigo
 * @property integer $ed269_i_turmaac
 * @property Carbon $ed269_d_data
 * @property integer $ed269_aluno
 */
class TurmaEspecialMatricula extends Model
{
    protected $table = 'escola.turmaacmatricula';
    protected $primaryKey = 'ed269_i_codigo';
    public $timestamps = false;
    public $incrementing = false;

    /**
     * @var array
     */
    private $storage = [];

    /**
     * @return integer
     */
    public function getCodigo()
    {
        return $this->ed269_i_codigo;
    }

    /**
     * @param $ed269_i_codigo
     * @return TurmaEspecialMatricula
     */
    public function setCodigo($ed269_i_codigo)
    {
        $this->ed269_i_codigo = $ed269_i_codigo;
        return $this;
    }

    /**
     * @return TurmaEspecial
     */
    public function getTurmaEspecial()
    {
        if (empty($this->storage['turma'])) {
            $this->storage['turma'] = $this->turma;
        }
        return $this->storage['turma'];
    }

    /**
     * @param $ed269_i_turmaac
     * @return $this
     */
    public function setCodigoTurmaEspecial($ed269_i_turmaac)
    {
        $this->ed269_i_turmaac = $ed269_i_turmaac;
        return $this;
    }

    /**
     * @return Carbon
     */
    public function getData()
    {
        return $this->ed269_d_data;
    }

    /**
     * @param $ed269_d_data
     * @return $this
     */
    public function setData($ed269_d_data)
    {
        $this->ed269_d_data = $ed269_d_data;
        return $this;
    }

    /**
     * @return Aluno
     */
    public function getAluno()
    {
        if (empty($this->storage['aluno'])) {
            $this->storage['aluno'] = $this->aluno;
        }
        return $this->storage['aluno'];
    }

    /**
     * @param $ed269_aluno
     * @return $this
     */
    public function setCodigoAluno($ed269_aluno)
    {
        $this->ed269_aluno = $ed269_aluno;
        return $this;
    }

    /**
     * @return BelongsTo
     */
    public function turma()
    {
        return $this->belongsTo(TurmaEspecial::class, 'ed269_i_turmaac', 'ed268_i_codigo');
    }

    /**
     * @return BelongsTo
     */
    public function aluno()
    {
        return $this->belongsTo(Aluno::class, 'ed269_aluno', 'ed47_i_codigo');
    }
}
