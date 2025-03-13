<?php

namespace App\Domain\RecursosHumanos\Pessoal\Model\Jetom;

use App\Domain\RecursosHumanos\Pessoal\Helper\DateHelper;
use ECidade\Lib\Session\DefaultSession;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Comissao
 * @property int rh242_sequencial
 * @property int rh242_instit
 * @property string rh242_descricao
 * @property date rh242_datainicio
 * @property date rh242_datafim
 * @package App\Domain\RecursosHumanos\Pessoal\Model\Jetom
 */
class Comissao extends Model
{
    protected $table = 'pessoal.jetomcomissao';
    protected $primaryKey = 'rh242_sequencial';
    public $timestamps = false;
    public $incrementing = false;

    /**
     * @param array $columns
     * @return \Illuminate\Database\Eloquent\Collection|Model[]|\Illuminate\Support\Collection
     */
    public static function all($columns = ['*'])
    {
        return self::where('rh242_instit', '=', DefaultSession::getInstance()->get('DB_instit'))
            ->orderBy('rh242_descricao')->get();
    }

    public static function getComissoesVigentes($instituicao)
    {
        $date = new \DBDate(date('Y-m-d'));
        return self::where('rh242_datafim', '>=', $date)
            ->where('rh242_instit', '=', $instituicao)
            ->orderBy('rh242_descricao')->get();
    }


    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;
    }

    public function getDescricao()
    {
        return $this->descricao;
    }

    public function setDataInicio($datainicio)
    {
        $this->datainicio = $datainicio;
    }

    public function getDataInicio()
    {
        return $this->datainicio;
    }

    public function setDataFim($datafim)
    {
        $this->datafim = $datafim;
    }

    public function getDataFim()
    {
        return $this->datafim;
    }

    public function setInstituicao($instituicao)
    {
        $this->instituicao = $instituicao;
    }

    public function getInstituicao()
    {
        return $this->instituicao;
    }

    public function callSave(array $options = [])
    {
        $comissao = new $this;
        $comissao->rh242_descricao = $this->getDescricao();
        $comissao->rh242_instit = $this->getInstituicao();
        $comissao->rh242_datainicio = $this->getDataInicio();
        $comissao->rh242_datafim = $this->getDataFim();

        $next_id = \DB::select("select nextval('pessoal.jetomcomissao_rh242_sequencial_seq')");
        $comissao->rh242_sequencial = $next_id['0']->nextval;

        if ($comissao->save()) {
            $this->rh242_sequencial = $comissao->rh242_sequencial;
            return true;
        }
        return false;
    }

    public function callUpdate($id)
    {
        $comissao = $this::find($id);
        $comissao->rh242_descricao = $this->getDescricao();
        $comissao->rh242_instit = $this->getInstituicao();
        $comissao->rh242_datainicio = $this->getDataInicio();
        $comissao->rh242_datafim = $this->getDataFim();

        return $comissao->update();
    }

    public function callDelete($id)
    {
        $comissao = $this::destroy($id);
    }

    /**
     * @return int
     */
    public function getSequencial()
    {
        return $this->rh242_sequencial;
    }

    public function servidores()
    {
        return $this->hasMany(
            'App\Domain\RecursosHumanos\Pessoal\Model\Jetom\ComissaoServidor',
            'rh245_comissao'
        )->select(['z01_nome', 'jetomcomissaoservidor.*'])
            ->join('pessoal.rhpessoal', 'rh245_matricula', '=', 'rh01_regist')
            ->join('protocolo.cgm', 'rh01_numcgm', '=', 'z01_numcgm')
            ->orderBy('z01_nome');
    }

    public function tipoSessao()
    {
        return $this->hasMany(
            'App\Domain\RecursosHumanos\Pessoal\Model\Jetom\ComissaoTipoSessao',
            'rh249_comissao'
        );
    }

    public function sessao()
    {
        return $this->hasMany(
            'App\Domain\RecursosHumanos\Pessoal\Model\Jetom\Sessao',
            'rh247_comissao'
        );
    }
}
