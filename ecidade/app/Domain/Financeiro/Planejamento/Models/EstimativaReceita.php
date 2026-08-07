<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2009  DBSeller Servicos de Informatica
 *                            www.dbseller.com.br
 *                         e-cidade@dbseller.com.br
 *
 *  Este programa e software livre; voce pode redistribui-lo e/ou
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme
 *  publicada pela Free Software Foundation; tanto a versao 2 da
 *  Licenca como (a seu criterio) qualquer versao mais nova.
 *
 *  Este programa e distribuido na expectativa de ser util, mas SEM
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais
 *  detalhes.
 *
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU
 *  junto com este programa; se nao, escreva para a Free Software
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *  02111-1307, USA.
 *
 *  Copia da licenca no diretorio licenca/licenca_en.txt
 *                                licenca/licenca_pt.txt
 */

namespace App\Domain\Financeiro\Planejamento\Models;

use App\Domain\Configuracao\Instituicao\Model\DBConfig;
use App\Domain\Financeiro\Orcamento\Models\CaracteristicaPeculiar;
use App\Domain\Financeiro\Orcamento\Models\NaturezaReceita;
use App\Domain\Financeiro\Orcamento\Models\Orgao;
use App\Domain\Financeiro\Orcamento\Models\Recurso;
use App\Domain\Financeiro\Orcamento\Models\Unidade;
use App\Domain\Financeiro\Planejamento\Mappers\ProjecaoReceitaMapper;
use ECidade\Enum\Financeiro\Orcamento\EsferaOrcamentariaEnum;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * Class EstimativaReceita
 * @package App\Domain\Financeiro\Planejamento\Models
 * @property $id
 * @property $planejamento_id
 * @property $anoorcamento
 * @property $orcfontes_id
 * @property $recurso_id
 * @property $instituicao_id
 * @property $concarpeculiar_id
 * @property $orcorgao_id
 * @property $orcunidade_id
 * @property $esferaorcamentaria
 * @property $inclusaomanual
 * @property $created_at
 * @property $updated_at
 */
class EstimativaReceita extends Model
{
    protected $table = 'planejamento.estimativareceita';
    /**
     * @var []
     */
    private $storage = [];

    /**
     * @param Collection $valores
     */
    public function setValores(Collection $valores)
    {
        $this->storage['valores'] = $valores;
    }

    /**
     * Retorna os valores do programa estratégico
     * @return Collection
     */
    public function getValores()
    {
        if (!array_key_exists('valores', $this->storage)) {
            $this->storage['valores'] = Valor::where('pl10_chave', '=', $this->id)
                ->where('pl10_origem', '=', Valor::ORIGEM_RECEITA)
                ->orderBy('pl10_ano')
                ->get();
        }

        return $this->storage['valores'];
    }
    /**
     * Retorna a natureza da receita
     * @return NaturezaReceita
     */
    public function getNaturezaOrcamento()
    {
        if (!array_key_exists('natureza', $this->storage)) {
            $this->storage['natureza'] = NaturezaReceita::where('o57_codfon', '=', $this->orcfontes_id)
                ->where('o57_anousu', '=', $this->anoorcamento)
                ->first();
        }

        return $this->storage['natureza'];
    }

    /**
     * Retorna o orgao do orcamento
     * @return Orgao|Model|\Illuminate\Database\Query\Builder|mixed|null
     */
    public function getOrgaoOrcamento()
    {
        if (!array_key_exists('orgaoOrcamento', $this->storage)) {
            $this->storage['orgaoOrcamento'] = Orgao::where('o40_orgao', '=', $this->orcorgao_id)
                ->where('o40_anousu', '=', $this->anoorcamento)
                ->first();
        }

        return $this->storage['orgaoOrcamento'];
    }

    /**
     * Retorna o orgao do orcamento
     * @return Orgao|Model|\Illuminate\Database\Query\Builder|mixed|null
     */
    public function getUnidadeOrcamentaria()
    {
        if (!array_key_exists('unidade', $this->storage)) {
            $this->storage['unidade'] = Unidade::where('o41_orgao', '=', $this->orcorgao_id)
                ->where('o41_unidade', '=', $this->orcunidade_id)
                ->where('o41_anousu', '=', $this->anoorcamento)
                ->first();
        }

        return $this->storage['unidade'];
    }

    /**
     * @return EsferaOrcamentariaEnum
     * @throws Exception
     */
    public function getEsferaOrcamentaria()
    {
        $this->esferaOrcamentaria = new EsferaOrcamentariaEnum((int)$this->esferaorcamentaria);
        return $this->esferaOrcamentaria;
    }

    /**
     * @param Builder $query
     * @param ProjecaoReceitaMapper $projecao
     * @return Builder
     */
    public function scopeHasEstimativaProjecao(Builder $query, ProjecaoReceitaMapper $projecao)
    {
        return $query->where('planejamento_id', '=', $projecao->planejamento->pl2_codigo)
            ->where('anoorcamento', '=', $projecao->planejamento->pl2_ano_inicial)
            ->where('orcfontes_id', '=', $projecao->codigoFonte)
            ->where('instituicao_id', '=', $projecao->instituicao)
            ->where('concarpeculiar_id', '=', $projecao->caracteristicaPeculiar)
            ->where('orcorgao_id', '=', $projecao->orgao)
            ->where('orcunidade_id', '=', $projecao->unidade)
            ->where('esferaorcamentaria', '=', $projecao->esferaOrcamentaria);
    }

    /**
     * Valida se já existe uma estimativa para os dados informados
     * @param Builder $query
     * @param $dados
     * @return mixed
     */
    public function scopeExisteEstimativa(Builder $query, $dados)
    {
        return $query->when(!empty($dados->id), function (Builder $query) use ($dados) {
            $query->where('id', '!=', $dados->id);
        })
            ->where('planejamento_id', '=', $dados->planejamento_id)
            ->where('anoorcamento', '=', $dados->anoorcamento)
            ->where('orcfontes_id', '=', $dados->orcfontes_id)
            ->where('instituicao_id', '=', $dados->instituicao_id)
            ->where('concarpeculiar_id', '=', $dados->concarpeculiar_id);
    }

    /**
     * @return array
     * @throws Exception
     */
    public function toArray()
    {
        $this->recurso;
        $this->instituicao;
        $this->planejamento;
        $this->caracteristicaPeculiar;

        $this->recurso->fonteRecurso = $this->recurso->fonteRecurso($this->anoorcamento);


        $dados = parent::toArray();
        $dados['natureza_receita'] = $this->getNaturezaOrcamento();
        $dados['esfera'] = $this->getEsferaOrcamentaria();
        $dados['orgao'] = $this->getOrgaoOrcamento();
        $dados['unidade'] = $this->getUnidadeOrcamentaria();
        $dados['valores'] = $this->getValores();

        $dados['descricao_esfera'] = $this->getEsferaOrcamentaria()->name();

        return $dados;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function planejamento()
    {
        return $this->belongsTo(Planejamento::class, 'planejamento_id', 'pl2_codigo');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function caracteristicaPeculiar()
    {
        return $this->belongsTo(CaracteristicaPeculiar::class, 'concarpeculiar_id', 'c58_sequencial');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function instituicao()
    {
        return $this->belongsTo(DBConfig::class, 'instituicao_id', 'codigo');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function cronogramaDesembolso()
    {
        return $this->hasMany(CronogramaDesembolsoReceita::class, 'estimativareceita_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function recurso()
    {
        return $this->belongsTo(Recurso::class, 'recurso_id', 'o15_codigo');
    }
}
