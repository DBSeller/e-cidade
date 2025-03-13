<?php

namespace App\Domain\RecursosHumanos\Pessoal\Model\Jetom;

use Exception;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ComissaoServidor
 *
 * @property int rh245_sequencial
 * @property int rh245_comissao
 * @property int rh245_matricula
 * @property int rh245_mesinicio
 * @property int rh245_mesfim
 * @property int rh245_anoinicio
 * @property int rh245_anofim
 * @property bool rh245_ativo
 * @property string rh245_atonomeacao
 * @property string rh245_documento
 * @property int rh245_funcao
 * @property ComissaoFuncao comissaoFuncao
 *
 * @package App\Domain\RecursosHumanos\Pessoal\Model\Jetom
 */
class ComissaoServidor extends Model
{
    protected $table = 'pessoal.jetomcomissaoservidor';
    protected $primaryKey = 'rh245_sequencial';
    public $timestamps = false;
    public $incrementing = false;
    protected $with = ['comissaoFuncao'];

    public function getSequencial()
    {
        return $this->rh245_sequencial;
    }

    public function setSequencial($sequencial)
    {
        $this->sequencial = $sequencial;
    }

    public function getComissao()
    {
        return $this->comissao;
    }

    public function setComissao($comissao)
    {
        $this->comissao = $comissao;
    }

    public function getMatricula()
    {
        return $this->matricula;
    }

    public function setMatricula($matricula)
    {
        $this->matricula = $matricula;
    }

    public function getMesinicio()
    {
        return $this->mesinicio;
    }

    public function setMesinicio($mesinicio)
    {
        $this->mesinicio = $mesinicio;
    }

    public function getMesfim()
    {
        return $this->mesfim;
    }

    public function setMesfim($mesfim)
    {
        $this->mesfim = $mesfim;
    }

    public function getAnoinicio()
    {
        return $this->anoinicio;
    }

    public function setAnoinicio($anoinicio)
    {
        $this->anoinicio = $anoinicio;
    }

    public function getAnofim()
    {
        return $this->anofim;
    }

    public function setAnofim($anofim)
    {
        $this->anofim = $anofim;
    }

    public function getAtivo()
    {
        return $this->ativo;
    }

    public function setAtivo($ativo)
    {
        $this->ativo = $ativo;
    }

    public function getAtonomeacao()
    {
        return $this->atonomeacao;
    }

    public function setAtonomeacao($atonomeacao)
    {
        $this->atonomeacao = $atonomeacao;
    }

    public function getDocumento()
    {
        return $this->documento;
    }

    public function setDocumento($documento)
    {
        $this->documento = $documento;
    }

    public function getFuncao()
    {
        return $this->funcao;
    }

    public function setFuncao($funcao)
    {
        $this->funcao = $funcao;
    }

    public function callSave(array $options = [])
    {
        $comissaoServidor = new $this;
        $comissaoServidor->rh245_comissao = $this->getComissao();
        $comissaoServidor->rh245_matricula = $this->getMatricula();
        $comissaoServidor->rh245_mesinicio = $this->getMesinicio();
        $comissaoServidor->rh245_mesfim = $this->getMesfim();
        $comissaoServidor->rh245_anoinicio = $this->getAnoinicio();
        $comissaoServidor->rh245_anofim = $this->getAnofim();
        $comissaoServidor->rh245_ativo = $this->getAtivo();
        $comissaoServidor->rh245_atonomeacao = $this->getAtonomeacao();
        $comissaoServidor->rh245_documento = $this->getDocumento();
        $comissaoServidor->rh245_funcao = $this->getFuncao();

        $next_id = \DB::select("select nextval('pessoal.jetomcomissaoservidor_rh245_sequencial_seq')");
        $comissaoServidor->rh245_sequencial = $next_id['0']->nextval;

        if ($comissaoServidor->save()) {
            $this->rh245_sequencial = $comissaoServidor->rh245_sequencial;
            return true;
        }
        return false;
    }

    public function callUpdate($id)
    {
        $comissaoServidor = $this::find($id);
        $comissaoServidor->rh245_comissao = $this->getComissao();
        $comissaoServidor->rh245_matricula = $this->getMatricula();
        $comissaoServidor->rh245_mesinicio = $this->getMesinicio();
        $comissaoServidor->rh245_mesfim = $this->getMesfim();
        $comissaoServidor->rh245_anoinicio = $this->getAnoinicio();
        $comissaoServidor->rh245_anofim = $this->getAnofim();
        $comissaoServidor->rh245_ativo = $this->getAtivo();
        $comissaoServidor->rh245_atonomeacao = $this->getAtonomeacao();
        $comissaoServidor->rh245_documento = $this->getDocumento();
        $comissaoServidor->rh245_funcao = $this->getFuncao();

        return $comissaoServidor->update();
    }

    public function callDelete($id)
    {
        $comissaoServidor = $this::destroy($id);
    }

    public function comissaoFuncao()
    {
        return $this->hasOne(
            'App\Domain\RecursosHumanos\Pessoal\Model\Jetom\Funcao',
            'rh241_sequencial',
            'rh245_funcao'
        );
    }

    public function buscaDadosServidor()
    {
        $servidores = $this::select(
            [
            'rh245_sequencial as codigo',
            'rh245_comissao as comissao',
            'rh01_numcgm as cgmcod',
            'z01_nome as nome',
            'rh245_funcao as funcaocodigo',
            'rh242_descricao as descricao',
            'rh245_matricula as matricula',
            'rh245_mesinicio as mesinicio',
            'rh245_mesfim as mesfim',
            'rh245_anoinicio as anoinicio',
            'rh245_anofim as anofim',
            'rh245_ativo as ativo',
            'rh245_atonomeacao as atonomeacao',
            'rh241_descricao as funcao',
            ]
        )
            ->where('rh245_comissao', '=', $this->getComissao())
            ->join("pessoal.jetomcomissao", "rh245_comissao", "=", "rh242_sequencial")
            ->join("pessoal.jetomfuncao", "rh245_funcao", "=", "rh241_sequencial")
            ->join("pessoal.rhpessoal", "rh245_matricula", "=", "rh01_regist")
            ->join("protocolo.cgm", "rh01_numcgm", "=", "z01_numcgm")
            ->orderBy('z01_nome')->orderBy('rh242_descricao')->get();
        return $servidores;
    }

    public function verificaCargo($comissao, $matricula, $ativo = false, $sequencial = null)
    {
        if (!$ativo) {
            return false;
        }

        // verifica se existe uma matricula com mesma funcao ativa desta comissao
        $servidorMatricula = self::where('rh245_matricula', $matricula)
            ->where('rh245_comissao', $comissao)
            ->where('rh245_ativo', true)
            ->when($sequencial, function ($query) use ($sequencial) {
                return $query->where('rh245_sequencial', '!=', $sequencial);
            })->get()->count();
        return $servidorMatricula;
    }
}
