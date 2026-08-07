<?php

namespace App\Domain\RecursosHumanos\Pessoal\Model\Jetom;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\JoinClause;

/**
 * Class ComissaoConfiguracao
 * @property int rh243_sequencial
 * @property int rh243_comissao
 * @property int rh243_funcao
 * @property int rh243_tiposessao
 * @property int rh243_rubrica
 * @property int rh243_valor
 * @package App\Http\Controllers\Controller
 */
class ComissaoConfiguracao extends Model
{
    protected $table = 'pessoal.jetomcomissaoconfiguracao';
    protected $primaryKey = 'rh243_sequencial';
    public $timestamps = false;
    public $incrementing = false;
    public $alias = [
        "rh243_sequencial as codigo",
        "rh243_comissao as comissao",
        "rh243_funcao as funcao",
        "rh243_tiposessao as tiposessao",
        "rh243_rubrica as rubrica",
        "rh243_valor as valor"];

    /**
     * @return int
     */
    public function getSequencial()
    {
        return $this->rh243_sequencial;
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

    public function setTipoSessao($tipoSessao)
    {
        $this->tipoSessao = $tipoSessao;
    }

    public function getTipoSessao()
    {
        return $this->tipoSessao;
    }

    public function setRubrica($rubrica)
    {
        $this->rubrica = $rubrica;
    }

    public function getRubrica()
    {
        return $this->rubrica;
    }

    public function setValor($valor)
    {
        $this->valor = $valor;
    }

    public function getValor()
    {
        return $this->valor;
    }

    public function callSave(array $options = [])
    {
        $comissaoConfiguracao = new $this;
        $comissaoConfiguracao->rh243_comissao = $this->getComissao();
        $comissaoConfiguracao->rh243_funcao = $this->getFuncao();
        $comissaoConfiguracao->rh243_tiposessao = $this->getTipoSessao();
        $comissaoConfiguracao->rh243_rubrica = $this->getRubrica();
        $comissaoConfiguracao->rh243_valor = $this->getValor();

        $next_id = \DB::select("select nextval('pessoal.jetomcomissaoconfiguracao_rh243_sequencial_seq')");
        $comissaoConfiguracao->rh243_sequencial = $next_id['0']->nextval;

        if ($comissaoConfiguracao->save()) {
            $this->rh243_sequencial = $comissaoConfiguracao->rh243_sequencial;
            return true;
        }
        return false;
    }

    public function callUpdate($id)
    {
        $comissaoFuncao = $this::find($id);
        $comissaoFuncao->rh243_comissao = $this->getComissao();
        $comissaoFuncao->rh243_funcao = $this->getFuncao();
        $comissaoFuncao->rh243_tiposessao = $this->getTipoSessao();
        $comissaoFuncao->rh243_rubrica = $this->getRubrica();
        $comissaoFuncao->rh243_valor = $this->getValor();

        return $comissaoFuncao->update();
    }

    public function callDelete($id)
    {
        $comissaoFuncao = $this::destroy($id);
    }

    public static function buscaPorComissao($codigoComissao)
    {
        return self::select([
            'rh243_sequencial as codigo',
            'rh243_comissao as comissao',
            'rh243_funcao as funcao',
            'rh243_tiposessao as tiposessao',
            'rh243_rubrica as rubrica',
            'rh243_valor as valor',
            'rh241_descricao as funcaodescricao',
            'rh240_descricao as tiposessaodescricao',
            'rh27_descr as rubricadescricao',
            ])
            ->where('rh243_comissao', "=", $codigoComissao)
            ->where('rhrubricas.rh27_ativo', "=", true)
            ->join("pessoal.jetomcomissao", "rh242_sequencial", "=", "rh243_comissao")
            ->join("pessoal.jetomtiposessao", "rh240_sequencial", "=", "rh243_tiposessao")
            ->join("pessoal.jetomfuncao", "rh243_funcao", "=", "rh241_sequencial")
            ->join("pessoal.rhrubricas", function (JoinClause $join) {
                $join->on('pessoal.jetomcomissaoconfiguracao.rh243_rubrica', '=', 'rhrubricas.rh27_rubric');
                $join->on('pessoal.jetomcomissao.rh242_instit', '=', 'rhrubricas.rh27_instit');
            })->orderBy('rh241_descricao')->orderBy('rh240_descricao')->get();
    }

    public static function buscaFuncaoPorComissao($codigoComissao, $funcao = [], $ignorar = [])
    {
        $alias = [
            "rh243_sequencial as codigo",
            "rh243_comissao as comissao",
            "rh243_funcao as funcao",
            "rh243_tiposessao as tiposessao",
            "rh243_rubrica as rubrica",
            "rh243_valor as valor"];

        $find = self::select($alias)->where('rh243_comissao', '=', $codigoComissao);

        if (!empty($funcao)) {
            $find->whereIn('rh243_funcao', $funcao);
        }

        if (!empty($ignorar)) {
            $find->whereNotIn('rh243_sequencial', $ignorar);
        }
        return $find->get();
    }

    /**
     * @param integer $sequencial
     * @param integer $codigoComissao
     * @param integer $funcao
     * @param integer $tiposessao
     * @return bool
     */
    public static function validaAlteracao($sequencial, $codigoComissao, $funcao, $tiposessao)
    {
        $find = self::select()->where('rh243_comissao', '=', $codigoComissao)
            ->where('rh243_funcao', '=', $funcao)
            ->where('rh243_tiposessao', '=', $tiposessao)
            ->where('rh243_sequencial', '!=', $sequencial);
        $elementos = $find->get();

        if ($elementos->count() == 0) {
            return true;
        }
        return false;
    }

    /**
     * @param int $codigoComissao
     * @param int $codigoFuncao
     * @param int $codigoTipoFuncao
     * @return ComissaoConfiguracao|Model|null
     */
    public static function buscaConfiguracaoFiltrosProcessamento($codigoComissao, $codigoFuncao, $codigoTipoFuncao)
    {
        return self::where([
            'rh243_comissao' => $codigoComissao,
            'rh243_funcao' => $codigoFuncao,
            'rh243_tiposessao' => $codigoTipoFuncao
        ])->first();
    }
}
