<?php


namespace App\Domain\Financeiro\Contabilidade\Services\Relatorios;

use App\Domain\Configuracao\Instituicao\Model\DBConfig;
use App\Domain\Financeiro\Contabilidade\Relatorios\Balancete\Receita\BalanceteReceitaPaisagem;
use App\Domain\Financeiro\Orcamento\Models\FonteReceita;
use Carbon\Carbon;
use ECidade\Financeiro\Contabilidade\PlanoDeContas\EstruturalReceita;
use Exception;
use Illuminate\Support\Facades\DB;

class BalanceteReceitaService
{
    /**
     * Estrutural da receita
     * @var array
     */
    private $filtrarNatureza;

    /**
     * Lista de IDs das instituições selecionadas
     * @var \Illuminate\Support\Collection
     */
    private $filtrarInstituicoes;

    /**
     * @var \Illuminate\Support\Collection
     */
    private $filtrarRecursos;

    /**
     * @var Carbon
     */
    private $filtroDataInicio;

    /**
     * @var Carbon
     */
    private $filtroDataFinal;

    /**
     * @var array
     */
    private $filtrarOrgaoUnidade = [];

    /**
     * @var integer
     */
    private $agrupador;
    /**
     * @var bool
     */
    private $filtrarApenasComMovimentacao = false;
    /**
     * @var int
     */
    private $ano;

    /**
     * @var string[]
     */
    private $nomeInstituicoes = [];


    public function setFiltrosRequest(array $filtros)
    {
        if (!empty($filtros['natureza'])) {
            $this->filtrarNatureza = explode(',', str_replace('.', '', $filtros['natureza']));
        }

        if (!empty($filtros['instituicoes'])) {
            $instituicoes = str_replace('\"', '"', $filtros['instituicoes']);
            $instituicoes = \JSON::create()->parse($instituicoes);

            $this->filtrarInstituicoes = collect($instituicoes)->map(function ($instituicao) {
                $nome = DBConfig::find($instituicao->codigo)->nomeinstabrev;
                if (strlen(trim($nome)) == 0) {
                    $nome = $instituicao->nome;
                }
                $this->nomeInstituicoes[] = $nome;
                return $instituicao->codigo;
            });
        }

        if (!empty($filtros['apenasComMovimentacao'])) {
            $this->filtrarApenasComMovimentacao = $filtros['apenasComMovimentacao'] == 1;
        }

        if (!empty($filtros['recursos'])) {
            $this->filtrarRecursos = collect($filtros['recursos']);
        }

        if (!empty($filtros['filtros'])) {
            $dados = str_replace('\"', '"', $filtros['filtros']);
            $dados = \JSON::create()->parse($dados);
            if (!empty($dados->unidade->aUnidades)) {
                $this->filtrarOrgaoUnidade = $dados->unidade->aUnidades;
            }
        }

        $this->filtroDataInicio = Carbon::createFromFormat('d/m/Y', $filtros['dataInicio']);
        $this->filtroDataFinal = Carbon::createFromFormat('d/m/Y', $filtros['dataFinal']);
        $this->ano = $this->filtroDataFinal->year;

        $this->agrupador = $filtros['nivel_agrupar'];
    }

    /**
     * @param DBConfig[] $instituicoes
     */
    public function setInstituicoes($instituicoes)
    {
        $this->filtrarInstituicoes = $instituicoes->map(function (DBConfig $config) {
            $this->nomeInstituicoes[] = $config->nome;
            return $config->codigo;
        });
    }

    public function emitir()
    {
        $dados = $this->processar();

        $periodo = sprintf(
            '%s até %s',
            $this->filtroDataInicio->format('d/m/Y'),
            $this->filtroDataFinal->format('d/m/Y')
        );

        $relatorio = new BalanceteReceitaPaisagem();
        $relatorio->headers($periodo, implode(', ', $this->nomeInstituicoes));
        $relatorio->setDadosBalancete($dados);

        return $relatorio->imprimir();
    }

    public function getArvore()
    {
        return $this->processar();
    }

    private function processar()
    {
        $receitas = $this->buscarDadosBalancete();
        if ($this->agrupador == 0) {
            $balancete = $this->montaArvore($receitas);
        } else {
            $balancete = $this->montaArvorePorGrupo($receitas);
        }

        return $balancete;
    }

    private function buscarDadosBalancete()
    {
        list($where, $dataInicio, $dataFinal) = $this->montaWhere();

        $sql = "
            select balancete_receita_complemento.*,
                   substr(natureza,1,1)::int4 as classe,
                   substr(natureza, 2)::varchar as resto
              from orcreceita
              join balancete_receita_complemento(
                     o70_anousu, o70_codfon, o70_concarpeculiar, '{$dataInicio}', '{$dataFinal}'
                   ) on o70_codfon = fonte and o70_anousu = ano
              where {$where}
              order by resto, natureza;
        ";
        return DB::select($sql);
    }

    private function montaFiltroOrgaoUnidade()
    {
        $orgaoUnidade = [];
        foreach ($this->filtrarOrgaoUnidade as $dadoOrgaoUnidade) {
            $dados = explode('-', $dadoOrgaoUnidade);
            $orgaoUnidade[] = "(o70_orcorgao = {$dados[0]} and o70_orcunidade = {$dados[1]})";
        }

        $filtro = "(" . implode(' or ', $orgaoUnidade) . ")";
        return $filtro;
    }

    private function montaArvore($receitas)
    {
        $fontesReceitas = FonteReceita::where('o57_anousu', '=', $this->ano)->get();

        $arvore = [];
        foreach ($receitas as $receita) {
            $estrutural = new EstruturalReceita($receita->natureza);
            $nivel = $estrutural->getNivel();

            $hash = "{$receita->natureza}#{$receita->cp}#$receita->fonte_recurso#$receita->complemento_lancamento";
            $arvore[$hash] = $this->mapperReceitaAnalitica($receita, $estrutural);
            list($estrutural, $arvore) = $this->montaContaPai($nivel, $estrutural, $arvore, $fontesReceitas, $receita);
        }
        ksort($arvore);
        return $arvore;
    }

    private function montaArvorePorGrupo($receitas)
    {
        $fontesReceitas = FonteReceita::where('o57_anousu', '=', $this->ano)->get();

        $arvore = [];
        foreach ($receitas as $receita) {
            $estrutural = new EstruturalReceita($receita->natureza);
            $hash = "{$receita->resto}#{$receita->cp}#$receita->recurso_lancamento#$receita->classe";
            $arvore[$hash] = $this->mapperReceitaAnalitica($receita, $estrutural);
            //Se a classe da receita for 9 (dedução) altera pela classe 4, para deduzir nas contas pais
            if ($receita->classe = 9) {
                $estrutural = new EstruturalReceita("4{$receita->resto}");
            }
            $nivel = $estrutural->getNivel();

            list($estrutural, $arvore) = $this->montaContaPai($nivel, $estrutural, $arvore, $fontesReceitas, $receita);
        }
        ksort($arvore);
        return $arvore;
    }

    /**
     * @param $nivel
     * @param $estrutural
     * @param array $arvore
     * @param $fontesReceitas
     * @param $receita
     * @return array
     */
    private function montaContaPai($nivel, $estrutural, array $arvore, $fontesReceitas, $receita)
    {
        while ($nivel != 1) {
            $estrutural = new EstruturalReceita($estrutural->getCodigoEstruturalPai());

            $hash = $fonte = $estrutural->getEstrutural();
            $nivel = $estrutural->getNivel();

            // Quando alterado o agrupamento para agrupar as deduções do mesmo grupo, retiramos a classe do estrutural
            // no index para mander a dedução junto a receita.
            if ($this->agrupador == 2) {
                $hash = substr($fonte, 1);
            }

            if (!array_key_exists($fonte, $arvore)) {
                /**
                 * @var FonteReceita $fonteReceita
                 */
                $fonteReceita = $fontesReceitas->filter(function (FonteReceita $fonteReceita) use ($fonte) {
                    return $fonteReceita->o57_fonte === $fonte;
                })->shift();

                if (is_null($fonteReceita)) {
                    throw new Exception("Não foi encontrado a fonte de receita: $fonte.\nRevise o cadastro.");
                }

                $arvore[$hash] = $this->mapperReceitaSintetica($fonteReceita, $estrutural);
            }

            $arvore[$hash]->valor_inicial += $receita->valor_inicial;
            $arvore[$hash]->previsao_adicional_acumulado += $receita->previsao_adicional_acumulado;
            $arvore[$hash]->previsao_atualizada += $receita->previsao_atualizada;
            $arvore[$hash]->arrecadado_anterior += $receita->arrecadado_anterior;
            $arvore[$hash]->arrecadado_periodo += $receita->arrecadado_periodo;
            $arvore[$hash]->valor_a_arrecadar += $receita->valor_a_arrecadar;
            $arvore[$hash]->arrecadado_acumulado += $receita->arrecadado_acumulado;
            $arvore[$hash]->previsao_adicional += $receita->previsao_adicional;
        }
        return array($estrutural, $arvore);
    }

    private function mapperReceitaAnalitica($receita, EstruturalReceita $estrutural)
    {
        $std = $this->criaObjetoReceita();
        $std->natureza = $receita->natureza;
        $std->mascara = $estrutural->getEstruturalComMascara();
        $std->reduzido = $receita->reduzido;
        $std->fonte = $receita->fonte;
        $std->ano = $receita->ano;
        $std->descricao = $receita->descricao;
        $std->cp = $receita->cp;
        $std->instituicao = $receita->instituicao;
        $std->orgao = $receita->orgao;
        $std->unidade = $receita->unidade;
        $std->esfera = $receita->esfera;
        $std->fonte_recurso = $receita->fonte_recurso;
        $std->recurso_lancamento = $receita->recurso_lancamento;
        $std->complemento_lancamento = $receita->complemento_lancamento;
        $std->valor_inicial = $receita->valor_inicial;
        $std->previsao_adicional_acumulado = $receita->previsao_adicional_acumulado;
        $std->previsao_atualizada = $receita->previsao_atualizada;
        $std->arrecadado_anterior = $receita->arrecadado_anterior;
        $std->arrecadado_periodo = $receita->arrecadado_periodo;
        $std->valor_a_arrecadar = $receita->valor_a_arrecadar;
        $std->arrecadado_acumulado = $receita->arrecadado_acumulado;
        $std->previsao_adicional = $receita->previsao_adicional;
        $std->ordem = $receita->ordem;
        return $std;
    }

    private function mapperReceitaSintetica(FonteReceita $fonteReceita, EstruturalReceita $estrutural)
    {
        $std = $this->criaObjetoReceita();
        $std->natureza = $estrutural->getEstrutural();
        $std->mascara = $estrutural->getEstruturalComMascara();
        $std->descricao = $fonteReceita->o57_descr;
        $std->sintetico = true;
        return $std;
    }

    private function criaObjetoReceita()
    {
        return (object)[
            "natureza" => '',
            "mascara" => '',
            "reduzido" => null,
            "fonte" => null,
            "ano" => null,
            "descricao" => '',
            "cp" => '',
            "instituicao" => null,
            "orgao" => null,
            "unidade" => null,
            "esfera" => null,
            "valor_inicial" => 0,
            "fonte_recurso" => null,
            "recurso_lancamento" => null,
            "complemento_lancamento" => null,
            "previsao_adicional_acumulado" => 0,
            "previsao_atualizada" => 0,
            "arrecadado_anterior" => 0,
            "arrecadado_periodo" => 0,
            "valor_a_arrecadar" => 0,
            "arrecadado_acumulado" => 0,
            "previsao_adicional" => 0,
            "ordem" => 0,
            "sintetico" => false,
        ];
    }

    /**
     * @return array
     */
    private function montaWhere()
    {
        $instituicoes = $this->filtrarInstituicoes->implode(',');


        $where = [
            "o70_anousu = {$this->ano}",
            "instituicao in ($instituicoes)",
        ];

        if (!empty($this->filtrarNatureza)) {
            $naturezas = array_map(function ($natureza) {
                $estrutural = new EstruturalReceita($natureza);
                return "(natureza like '{$estrutural->getEstruturalAteNivel()}%')";
            }, $this->filtrarNatureza);

            $where[] = '(' . implode(' or ', $naturezas) . ')';
        }

        if (!empty($this->filtrarRecursos)) {
            $recursos = $this->filtrarRecursos->implode(',');
            $where[] = "recurso_lancamento in ($recursos)";
        }

        if ($this->filtrarApenasComMovimentacao) {
            $where[] = sprintf(
                "(%s or %s or %s or %s)",
                'previsao_adicional_acumulado != 0',
                'valor_a_arrecadar != 0',
                'arrecadado_periodo != 0',
                'arrecadado_acumulado != 0'
            );
        }

        if (!empty($this->filtrarOrgaoUnidade)) {
            $where[] = $this->montaFiltroOrgaoUnidade();
        }

        $dataInicio = $this->filtroDataInicio->format('Y-m-d');
        $dataFinal = $this->filtroDataFinal->format('Y-m-d');
        $where = implode(' and ', $where);
        return array($where, $dataInicio, $dataFinal);
    }
}
