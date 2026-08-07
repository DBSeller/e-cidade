<?php

namespace App\Domain\RecursosHumanos\Pessoal\Model\Jetom;

use ECidade\Lib\Session\DefaultSession;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

/**
 * Class Funcao
 * @property string rh241_descricao
 * @property int rh241_sequencial
 * @property int rh241_instit
 * @package App\Domain\RecursosHumanos\Pessoal\Model\Jetom
 */
class Funcao extends Model
{
    protected $table = 'pessoal.jetomfuncao';
    protected $primaryKey = 'rh241_sequencial';
    public $timestamps = false;

    /**
     * @param array $columns
     * @return \Illuminate\Database\Eloquent\Collection|Model[]|\Illuminate\Support\Collection
     */
    public static function all($columns = ['*'])
    {
        $campos = ['rh241_sequencial as codigo', 'rh241_descricao as descricao'];
        return self::where('rh241_instit', '=', DefaultSession::getInstance()->get('DB_instit'))
            ->orderBy('rh241_descricao')->get($campos);
    }

    /**
     * @param string $funcao
     * @param int $instituicao
     * @param int|null $id
     * @return bool
     */
    public static function existeFuncao($funcao, $instituicao, $id = null)
    {
        $funcao = trim($funcao);
        if (empty($id)) {
            $existeFuncao = self::where('rh241_instit', '=', $instituicao)
                ->where('rh241_descricao', '=', $funcao)->get();
        } else {
            $existeFuncao = self::where('rh241_instit', '=', $instituicao)
                ->where('rh241_descricao', '=', $funcao)
                ->where('rh241_sequencial', '!=', $id)->get();
        }

        if ($existeFuncao->count() > 0) {
            return true;
        }
        return false;
    }

    /**
     * @param array|null $options
     * @return bool
     */
    public function save(array $options = null)
    {
        $next_id = \DB::select("select nextval('pessoal.jetomfuncao_rh241_sequencial_seq')");
        //Seta o sequencial
        $this->rh241_sequencial = $next_id['0']->nextval;

        return parent::save();
    }

    /**
     * @return bool
     */
    public function edit()
    {
        return self::where(
            'rh241_sequencial',
            '=',
            $this->rh241_sequencial
        )->update(['rh241_descricao' => $this->rh241_descricao]);
    }

    /*
     * Validacoes
     */
    public function rules()
    {
        return [
            'descricao' => [
                'required',
                'string',
                Rule::unique('jetomfuncao', 'rh241_descricao')->where("rh241_instit", $this->instituicao)
            ],
            'instituicao' => [
                'required',
                'integer'
            ]
        ];
    }

    public function messages()
    {
        return [
            'instituicao.required' => 'Instituição não informada.',
            'descricao.required' => 'Descrição da função não informada.'
        ];
    }

    /**
     * @return int
     */
    public function getSequencial()
    {
        return $this->rh241_sequencial;
    }

    /**
     * @param int $sequencial
     */
    public function setSequencial($sequencial)
    {
        $this->rh241_sequencial = $sequencial;
    }

    /**
     * @return int
     */
    public function getInstituicao()
    {
        return $this->rh241_instit;
    }

    /**
     * @param int $instit
     */
    public function setInstituicao($instit)
    {
        $this->rh241_instit = $instit;
    }

    /**
     * @return string
     */
    public function getDescricao()
    {
        return $this->rh241_descricao;
    }

    /**
     * @param string $descricao
     */
    public function setDescricao($descricao)
    {
        $this->rh241_descricao = $descricao;
    }

    /**
     * @param integer $id
     */
    public static function getFuncao($id)
    {
        $funcao = parent::find($id);
        if (empty($funcao)) {
            return false;
        }
        $novaFuncao = new Funcao();
        $novaFuncao->setSequencial($funcao->rh241_sequencial);
        $novaFuncao->setDescricao($funcao->rh241_descricao);
        $novaFuncao->setInstituicao($funcao->rh241_instit);
        $novaFuncao->exists = true;
        return $novaFuncao;
    }
}
