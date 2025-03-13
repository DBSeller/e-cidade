<?php

namespace App\Domain\RecursosHumanos\Pessoal\Model\Jetom;

use ECidade\Lib\Session\DefaultSession;
use Illuminate\Database\Eloquent\Model;
use App\Core\Base\Model\BaseModel;

/**
 * Class ComissaoTipoSessao
 * @property int rh249_sequencial
 * @property int rh249_comissao
 * @property int rh249_tiposessao
 * @property int rh249_quantidade
 * @property TipoSessao tipo
 * @package App\Domain\RecursosHumanos\Pessoal\Model\Jetom
 */

class ComissaoTipoSessao extends Model
{
    protected $table = 'pessoal.jetomcomissaotiposessao';
    protected $primaryKey = 'rh249_sequencial';
    public $timestamps = false;
    public $incrementing = false;
    protected $with = ['tipo'];

    /**
     * @return int
     */
    public function getSequencial()
    {
        return $this->rh249_sequencial;
    }

    public function setComissao($comissao)
    {
        $this->comissao = $comissao;
    }

    public function getComissao()
    {
        return $this->comissao;
    }

    public function setTipoSessao($tiposessao)
    {
        $this->tiposessao = $tiposessao;
    }

    public function getTipoSessao()
    {
        return $this->tiposessao;
    }

    public function setQuantidade($quantidade)
    {
        $this->quantidade = $quantidade;
    }

    public function getQuantidade()
    {
        return $this->quantidade;
    }

    public function callSave(array $options = [])
    {

        $comissaoTipoSessao = new $this;
        $comissaoTipoSessao->rh249_comissao   = $this->getComissao();
        $comissaoTipoSessao->rh249_tiposessao     = $this->getTipoSessao();
        $comissaoTipoSessao->rh249_quantidade = $this->getQuantidade();

        $next_id = \DB::select("select nextval('pessoal.jetomcomissaotiposessao_rh249_sequencial_seq')");
        $comissaoTipoSessao->rh249_sequencial = $next_id['0']->nextval;

        if ($comissaoTipoSessao->save()) {
            $this->rh249_sequencial = $comissaoTipoSessao->rh249_sequencial;
            return true;
        }
        return false;
    }

    public function callUpdate($id)
    {
        $comissaoTipoSessao = $this::find($id);
        $comissaoTipoSessao->rh249_comissao = $this->getComissao();
        $comissaoTipoSessao->rh249_tiposessao = $this->getTipoSessao();
        $comissaoTipoSessao->rh249_quantidade = $this->getQuantidade();

        return $comissaoTipoSessao->update();
    }

    public function callDelete($id)
    {
        $comissaoTipoSessao = $this::destroy($id);
    }

    public static function buscaTipoSessaoPorComissao($codigoComissao)
    {
        $campos = [
            'rh249_quantidade as quantidade',
            'rh240_descricao as descricao',
            'rh249_tiposessao as tiposessao',
            'rh249_sequencial as codigo',
            'rh249_comissao as comissao'
        ];
        return self::select($campos)->where('rh249_comissao', '=', $codigoComissao)
            ->join('pessoal.jetomtiposessao', 'rh240_sequencial', '=', 'rh249_tiposessao')
            ->orderBy('rh240_descricao')->get();
    }

    public function tipo()
    {
        return $this
            ->hasOne(
                'App\Domain\RecursosHumanos\Pessoal\Model\Jetom\TipoSessao',
                'rh240_sequencial',
                'rh249_tiposessao'
            );
    }
}
