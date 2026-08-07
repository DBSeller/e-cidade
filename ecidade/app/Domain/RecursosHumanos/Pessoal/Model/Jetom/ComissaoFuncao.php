<?php
namespace App\Domain\RecursosHumanos\Pessoal\Model\Jetom;

use ECidade\Lib\Session\DefaultSession;
use Illuminate\Database\Eloquent\Model;
use App\Core\Base\Model\BaseModel;
use Illuminate\Database\Query\JoinClause;

/**
 * Class ComissaoFuncao
 *
 * @property int rh246_sequencial
 * @property int rh246_comissao
 * @property int rh246_funcao
 * @property int rh246_quantidade
 * @package  App\Domain\RecursosHumanos\Pessoal\Model\Jetom
 */

class ComissaoFuncao extends Model
{
    protected $table = 'pessoal.jetomcomissaofuncao';
    protected $primaryKey = 'rh246_sequencial';
    public $timestamps = false;
    public $incrementing = false;

    /**
     * @return int
     */
    public function getSequencial()
    {
        return $this->rh246_sequencial;
    }

    public function setComissao($comissao)
    {
        $this->comissao = $comissao;
    }

    public function getComissao()
    {
        return $this->comissao;
    }

    public function setFuncao($funcao)
    {
        $this->funcao = $funcao;
    }

    public function getFuncao()
    {
        return $this->funcao;
    }

    public function setQuantidade($quantidade)
    {
        $this->quantidade = $quantidade;
    }

    public function getQuantidade()
    {
        return $this->quantidade;
    }

    /**
     * Retorna a model Funcao relacionada ao ComissaoFuncao
     *
     * @return App\Domain\RecursosHumanos\Pessoal\Model\Jetom\Funcao;
     */
    public function funcaoModel()
    {
        return $this->belongsTo('App\Domain\RecursosHumanos\Pessoal\Model\Jetom\Funcao', 'rh246_funcao');
    }

    public function callSave(array $options = [])
    {
        $comissaoFuncao = new $this;
        $comissaoFuncao->rh246_comissao = $this->getComissao();
        $comissaoFuncao->rh246_funcao = $this->getFuncao();
        $comissaoFuncao->rh246_quantidade = $this->getQuantidade();

        $next_id = \DB::select("select nextval('pessoal.jetomcomissaofuncao_rh246_sequencial_seq')");
        $comissaoFuncao->rh246_sequencial = $next_id['0']->nextval;

        if ($comissaoFuncao->save()) {
            $this->rh246_sequencial = $comissaoFuncao->rh246_sequencial;
            return true;
        }
        return false;
    }

    public function callUpdate($id)
    {
        $comissaoFuncao = $this::find($id);
        $comissaoFuncao->rh246_comissao = $this->getComissao();
        $comissaoFuncao->rh246_funcao = $this->getFuncao();
        $comissaoFuncao->rh246_quantidade = $this->getQuantidade();

        return $comissaoFuncao->update();
    }

    public function callDelete($id)
    {
        $comissaoFuncao = $this::destroy($id);
    }

    /**
     * Get the phone record associated with the user.
     */
    public function funcao()
    {
        return $this->hasOne('App\Domain\RecursosHumanos\Pessoal\Model\Jetom\Funcao');
    }


    /**
     * Função responsavel por retornar os códigos de todas as sessões
     * em que a função da comissão estiveram presentes dentro de 1 periodo determinado
     *
     * @param  string $dataInicial 'YYYY-mm-dd'
     * @param  string $dataFinal   'YYYY-mm-dd'
     * @return mixed
     */
    public function getFuncaoDaComissaoEmSessoesPorPeriodo($dataInicial, $dataFinal)
    {
        return \DB::table("pessoal.jetomsessao")
            ->select('rh247_sequencial')
            ->distinct('rh247_sequencial')
            ->where('rh247_comissao', "=", $this->getComissao())
            ->where('rh245_funcao', "=", $this->getFuncao())
            ->whereBetween('rh247_data', [$dataInicial, $dataFinal])
            ->join("pessoal.jetomsessaoservidor", "rh248_sessao", "=", "rh247_sequencial")
            ->join("pessoal.jetomcomissaoservidor", "rh248_servidor", "=", "rh245_sequencial")
            ->get();
    }

    /**
     * @param integer $comissao
     * @param integer $funcao
     */
    public static function getComissaoFuncaoByComissaoFuncao($comissao, $funcao)
    {
        $find = parent::where("rh246_comissao", "=", $comissao)
            ->where("rh246_funcao", "=", $funcao)->get()->first();

        if (empty($find)) {
            return false;
        }
        $comissaoFuncao = new ComissaoFuncao();
        $comissaoFuncao->setComissao($find->rh246_comissao);
        $comissaoFuncao->setFuncao($find->rh246_funcao);
        $comissaoFuncao->setQuantidade($find->rh246_quantidade);
        return $comissaoFuncao;
    }

    public function getServidoresByFuncao()
    {

        return $this->select(['z01_nome', 'jetomcomissaoservidor.*'])
            ->join('pessoal.jetomcomissaoservidor', function ($join) {
                $join->on('jetomcomissaofuncao.rh246_funcao', '=', 'jetomcomissaoservidor.rh245_funcao')
                    ->on('jetomcomissaofuncao.rh246_comissao', '=', 'jetomcomissaoservidor.rh245_comissao');
            })
            ->join('pessoal.rhpessoal', 'rh245_matricula', '=', 'rh01_regist')
            ->join('protocolo.cgm', 'rh01_numcgm', '=', 'z01_numcgm')
            ->where('rh245_funcao', '=', $this->rh246_funcao)
            ->where('rh246_comissao', '=', $this->rh246_comissao)->get();
    }
}
