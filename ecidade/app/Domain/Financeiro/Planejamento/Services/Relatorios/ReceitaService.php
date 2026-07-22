<?php


namespace App\Domain\Financeiro\Planejamento\Services\Relatorios;

use App\Domain\Financeiro\Orcamento\Models\FonteReceita;
use App\Domain\Financeiro\Planejamento\Models\Planejamento;
use ECidade\Financeiro\Contabilidade\PlanoDeContas\EstruturalReceita;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * Class ReceitaService
 * Essa classe é para facilitar a emissão dos relatórios referente ao planejamento da receita no PPA/LDO ou LOA
 *
 * @package App\Domain\Financeiro\Planejamento\Services\Relatorios
 */
abstract class ReceitaService
{
    /**
     * @var Planejamento
     */
    protected $planejamento;

    /**
     * Array com os exercicios anteriores ao planejamento
     * @var array
     */
    protected $exerciciosAnteriores = [];
    /**
     * Array com os dados para impressão
     * @var array
     */
    protected $dados = [];

    /**
     * Nível das contas retornadas
     * @var int
     */
    protected $nivel = 1;

    protected $filtros = [];

    protected $campos = [];

    /**
     * Totalizador os valores projetados por ano
     * @var array
     */
    protected $totalizador = [];

    abstract public function emitirPdf();

    public function processarFiltros()
    {
        if (empty($this->filtros)) {
            throw new Exception('Nenhum filtro foi informado.', 403);
        }

        if (empty($this->filtros['planejamento_id'])) {
            throw new Exception('Você deve selecionar o planejamento.', 403);
        }

        if (empty($this->filtros['DB_instit'])) {
            throw new Exception('A instituição deve ser informada.', 403);
        }
        if (!empty($this->filtros['natureza'])) {
            $fonte = str_pad($this->filtros['natureza'], 15, '0', STR_PAD_RIGHT);
            $estrutural = new EstruturalReceita($fonte);
            $this->nivel = $estrutural->getNivel();
        }
        $this->planejamento = Planejamento::find($this->filtros['planejamento_id']);

        foreach ($this->planejamento->execiciosPlanejamento() as $exercicio) {
            $this->totalizador[$exercicio] = 0;
        }
    }

    private function defaultCampos()
    {
        $this->campos = [
            'orcfontes_id',
            'o70_codrec',
            'o57_fonte as fonte',
            'o57_descr as descricao',
            'fonterecurso.codigo_siconfi as recurso',
            'fonterecurso.descricao as descricao_recurso',
            'o15_complemento as complemento',
            'valorbase as valor_base',
        ];

        $this->campos[] = DB::raw("
            (select json_agg(
                          json_build_object(
                            'ano', x.pl10_ano,
                            'valor', x.pl10_valor
                          )
                       )
                  from (select valores.pl10_ano, valores.pl10_valor
                         from planejamento.valores
                        where pl10_origem = 'RECEITA'
                          and pl10_chave = estimativareceita.id
                        order by pl10_ano
                     ) as x
            ) as valores
        ");

        return $this->campos;
    }

    /**
     * @param array $campos com a lista de campos
     * @return Collection
     */
    public function buscarProjecao(array $campos = [])
    {
        if (empty($campos)) {
            $campos = $this->defaultCampos();
        }

        $exercicioReceita = $this->planejamento->pl2_ano_inicial - 1;
        /**
         * @var Collection $estimativas
         */
        return DB::table('estimativareceita')
            ->select($campos)
            ->join('orcfontes', function ($join) {
                $join->on('orcfontes.o57_codfon', '=', 'estimativareceita.orcfontes_id')
                    ->on('orcfontes.o57_anousu', '=', 'estimativareceita.anoorcamento');
            })
            ->leftJoin('orcreceita', function ($join) use ($exercicioReceita) {
                $join->on('orcreceita.o70_codfon', '=', 'estimativareceita.orcfontes_id')
                    ->on('orcreceita.o70_concarpeculiar', '=', 'estimativareceita.concarpeculiar_id')
                    ->where('o70_anousu', '=', $exercicioReceita);
            })
            ->join('fonterecurso', function ($join) {
                $join->on('fonterecurso.orctiporec_id', '=', 'estimativareceita.recurso_id')
                    ->on('fonterecurso.exercicio', '=', 'estimativareceita.anoorcamento');
            })
            ->join('orctiporec', 'o15_codigo', '=', 'recurso_id')
            ->where('planejamento_id', '=', $this->planejamento->pl2_codigo)
            ->when(!empty($this->filtros['instituicoes']), function ($query) {
                $query->whereIn('instituicao_id', $this->filtros['instituicoes']);
            })
            ->when(!empty($this->filtros['natureza']), function ($query) {
                $query->where('o57_fonte', 'like', "{$this->filtros['natureza']}%");
            })
            ->get();
    }

    protected function montaArvoreEstrutural(Collection $dadosEstimativas)
    {
        $fontesReceitas = FonteReceita::where('o57_anousu', '=', $this->planejamento->pl2_ano_inicial)->get();
        $receitas = [];

        $estimativas = $this->processaValoresEstimativa($dadosEstimativas);

        foreach ($estimativas as $estimativa) {
            $estrutural = new EstruturalReceita($estimativa->fonte);
            $nivel = $estrutural->getNivel();
            $receitas[$estrutural->getEstrutural()] = $estimativa;

            while ($nivel != 1 && $this->nivel < $nivel) {
                $estrutural = new EstruturalReceita($estrutural->getCodigoEstruturalPai());

                $fonte = $estrutural->getEstrutural();
                $nivel = $estrutural->getNivel();

                if (!array_key_exists($fonte, $receitas)) {
                    $fonteReceita = $fontesReceitas->filter(function (FonteReceita $fonteReceita) use ($fonte) {
                        return $fonteReceita->o57_fonte === $fonte;
                    })->shift();

                    $receitas[$fonte] = $this->builder($estrutural, $fonteReceita->o57_descr, $nivel);
                }

                $receitas[$fonte]->valor_base += $estimativa->valor_base;

                foreach ($this->exerciciosAnteriores as $exercicio) {
                    $propriedade = "arrecadado_{$exercicio}";
                    $receitas[$fonte]->{$propriedade} += $estimativa->{$propriedade};
                }

                foreach ($this->planejamento->execiciosPlanejamento() as $exercicio) {
                    $propriedade = "valor_{$exercicio}";
                    $receitas[$fonte]->{$propriedade} += $estimativa->{$propriedade};
                }
            }
        }

        ksort($receitas);
        return $receitas;
    }

    protected function builder(EstruturalReceita $estrutural, $descricao)
    {
        $std = (object)[
            'orcfontes_id' => null,
            'o70_codrec' => null,
            'sintetico' => true,
            'nivel' => $estrutural->getNivel(),
            'fonte' => $estrutural->getEstrutural(),
            'estrutural' => $estrutural->getEstruturalComMascara(),
            'descricao' => $descricao,
            'recurso' => null,
            'complemento' => null,
            'valor_base' => 0,
        ];

        foreach ($this->exerciciosAnteriores as $exercicio) {
            $std->{"arrecadado_{$exercicio}"} = 0;
        }
        foreach ($this->planejamento->execiciosPlanejamento() as $exercicio) {
            $std->{"valor_{$exercicio}"} = 0;
        }
        return $std;
    }

    /**
     * @param $dadosEstimativa
     * @param EstruturalReceita $estrutural
     * @return object
     */
    protected function builderAnalitico($dadosEstimativa, EstruturalReceita $estrutural)
    {
        $estimativa = $this->builder($estrutural, $dadosEstimativa->descricao);
        $estimativa->sintetico = false;
        $estimativa->orcfontes_id = $dadosEstimativa->orcfontes_id;
        $estimativa->o70_codrec = $dadosEstimativa->o70_codrec;
        $estimativa->recurso = $dadosEstimativa->recurso;
        $estimativa->complemento = $dadosEstimativa->complemento;
        $estimativa->valor_base = $dadosEstimativa->valor_base;

        $valores = \JSON::create()->parse($dadosEstimativa->valores);
        foreach ($valores as $valor) {
            $estimativa->{"valor_{$valor->ano}"} = (float)$valor->valor;
            $this->totalizador[$valor->ano] += (float)$valor->valor;
        }

        foreach ($this->exerciciosAnteriores as $exercicio) {
            $estimativa->{"arrecadado_{$exercicio}"} = (float)$dadosEstimativa->{"arrecadado_{$exercicio}"};
        }

        return $estimativa;
    }

    /**
     * Quando realizamos a estimativa da receita os valores vem abertos por CP.
     * Nesse metodo somamos as estimativas como se não houvesse uma estimativa diferente para uma CP diferente
     * - Os valores arrecadados não enchergam a CP, sendo o total da natureza da receita.
     * @param Collection $dadosEstimativas
     * @return array
     */
    protected function processaValoresEstimativa(Collection $dadosEstimativas)
    {
        $receitas = [];
        foreach ($dadosEstimativas as $dadosEstimativa) {
            $estrutural = new EstruturalReceita($dadosEstimativa->fonte);
            $estimativa = $this->builderAnalitico($dadosEstimativa, $estrutural);

            if (array_key_exists($estrutural->getEstrutural(), $receitas)) {
                $receitas[$estrutural->getEstrutural()]->valor_base += $estimativa->valor_base;
                $valores = \JSON::create()->parse($dadosEstimativa->valores);
                foreach ($valores as $valor) {
                    $receitas[$estrutural->getEstrutural()]->{"valor_{$valor->ano}"} += (float)$valor->valor;
                }
            } else {
                $receitas[$estrutural->getEstrutural()] = $estimativa;
            }
        }

        ksort($receitas);
        return $receitas;
    }

    /**
     * @param array $dados
     */
    protected function organizaDados(array $dados)
    {
        $this->dados['planejamento'] = $this->planejamento->toArray();
        $this->dados['planejamento']['exercicios'] = $this->planejamento->execiciosPlanejamento();
        $this->dados['dados'] = $dados;

        $this->dados['totalizador'] = $this->totalizador;
    }
}
