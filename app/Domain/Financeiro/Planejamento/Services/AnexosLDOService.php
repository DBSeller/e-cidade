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

namespace App\Domain\Financeiro\Planejamento\Services;

use App\Domain\Configuracao\Departamento\Models\Departamento;
use App\Domain\Financeiro\Planejamento\Models\DetalhamentoDespesa;
use App\Domain\Financeiro\Planejamento\Models\EstimativaReceita;
use App\Domain\Financeiro\Planejamento\Models\FatorCorrecaoDespesa;
use App\Domain\Financeiro\Planejamento\Models\FatorCorrecaoReceita;
use App\Domain\Financeiro\Planejamento\Models\Planejamento;
use App\Domain\Financeiro\Planejamento\Models\Valor;
use ECidade\Financeiro\Contabilidade\PlanoDeContas\Estrutural;
use ECidade\Financeiro\Contabilidade\PlanoDeContas\EstruturalReceita;
use ECidade\Financeiro\Contabilidade\Relatorio\DemonstrativoFiscal;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Instituicao;
use linhaRelatorioContabil;
use RelatoriosLegaisBase;
use stdClass;

abstract class AnexosLDOService
{
    const NENHUMA = 0;
    const RECEITA = 1;
    const DESPESA = 2;
    const VERIFICACAO = 3;

    /**
     * @var RelatoriosLegaisBase
     */
    protected $relatorio;
    /**
     * @var array
     */
    protected $linhas;

    /**
     * @var Instituicao
     */
    protected $emissor;
    /**
     * @var Departamento
     */
    protected $departamento;
    /**
     * @var Planejamento
     */
    protected $plano;
    /**
     * @var integer[]
     */
    protected $codigosInstituicoes;

    /**
     * @var Collection
     */
    protected $projecaoReceita;
    /**
     * @var Collection
     */
    protected $projecaoDespesa;

    /**
     * Valores da RCL
     * @var Collection
     */
    protected $rclPlanejamento;

    /**
     * @var Collection|FatorCorrecaoReceita[]
     */
    protected $fatorCorrecaoReceita;
    /**
     * @var Collection|FatorCorrecaoDespesa[]
     */
    protected $fatorCorrecaoDespesa;


    /**
     * @var string
     */
    protected $enteFederativo;

    /**
     * para trabalhar com sections nos relatórios do planejamento
     * @var array
     */
    protected $sections = [];

    /**
     * Linhas organizadas nas sections
     * @var array
     */
    protected $linhasOrganizadas = [];

    /**
     * RelatorioLegalService constructor.
     * @param array $filtros
     */
    public function __construct(array $filtros)
    {
        $this->filtros = $filtros;
    }

    /**
     * @param stdClass $linha
     */
    abstract protected function processaReceita($linha);

    /**
     * @param stdClass $linha
     */
    abstract protected function processaDespesa($linha);

    protected function processaLinhas()
    {
        $linhas = $this->getLinhas();

        foreach ($linhas as $linha) {
            /**
             * @var linhaRelatorioContabil $linhaRelatorio
             */
            $linhaRelatorio = $linha->oLinhaRelatorio;
            if ((int)$linhaRelatorio->getOrigemDados() === AnexosLDOService::RECEITA) {
                $this->processaReceita($linha);
            }

            if ((int)$linhaRelatorio->getOrigemDados() == AnexosLDOService::DESPESA) {
                $this->processaDespesa($linha);
            }
        }

        $this->processaValorManual();
    }

    protected function processar()
    {
        $this->relatorio = new RelatoriosLegaisBase(
            $this->filtros['DB_anousu'],
            $this->filtros['codigo_relatorio'],
            $this->filtros['periodo']
        );
        $this->emissor = \InstituicaoRepository::getInstituicaoByCodigo($this->filtros['DB_instit']);
        $this->departamento = Departamento::find($this->filtros['DB_coddepto']);

        $this->plano = Planejamento::find($this->filtros['planejamento_id']);
        $this->codigosInstituicoes = $this->filtros['instituicoes'];

        $this->processaEnteFederativo();
    }


    protected function processaEnteFederativo()
    {
        $this->enteFederativo = DemonstrativoFiscal::getEnteFederativo($this->emissor);
        if ($this->emissor->getTipo() != Instituicao::TIPO_PREFEITURA) {
            $this->enteFederativo .= "\n" . $this->emissor->getDescricao();
        }
    }

    /**
     * Retorna a instância do relatório legal
     * @return RelatoriosLegaisBase
     */
    public function getRelatorio()
    {
        return $this->relatorio;
    }

    /**
     * Retorna as linhas do relatório legal
     * @return array
     */
    public function getLinhas()
    {
        if (empty($this->linhas)) {
            $this->linhas = $this->relatorio->getLinhasRelatorio();
        }
        return $this->linhas;
    }

    /**
     * Soma os valores manuais do relatório
     */
    public function processaValorManual()
    {
        foreach ($this->linhas as $linha) {
            foreach ($linha->oLinhaRelatorio->getValoresColunas() as $valoresManuais) {
                foreach ($valoresManuais->colunas as $valorManual) {
                    $linha->{$valorManual->o115_nomecoluna} += $valorManual->o117_valor;
                }
            }
        }
    }

    /**
     * @return Collection
     */
    public function buscarProjecaoReceita()
    {
        if (empty($this->projecaoReceita)) {
            $this->projecaoReceita = EstimativaReceita::query()
                ->where('planejamento_id', '=', $this->filtros['planejamento_id'])
                ->whereIn('instituicao_id', $this->filtros['instituicoes'])
                ->get();
        }
        return $this->projecaoReceita;
    }

    /**
     * @return Collection
     */
    public function buscarProjecaoDespesa()
    {
        if (empty($this->projecaoDespesa)) {
            $this->projecaoDespesa = DetalhamentoDespesa::query()
                ->join('iniciativaprojativ', 'pl12_codigo', '=', 'pl20_iniciativaprojativ')
                ->join('programaestrategico', 'pl9_codigo', '=', 'pl12_programaestrategico')
                ->where('pl9_planejamento', '=', $this->filtros['planejamento_id'])
                ->whereIn('pl20_instituicao', $this->filtros['instituicoes'])
                ->get();
        }
        return $this->projecaoDespesa;
    }

    protected function calcularRCLPlanejamento()
    {
        $service = new RCLPlanejamentoService();
        $this->rclPlanejamento = $service->getRCLProjecao($this->plano, $this->filtros);
        if ($this->rclPlanejamento->isEmpty()) {
            throw new Exception("Não foi realizado nenhuma estimativa da receita.");
        }
    }

    /**
     * Retorna os dados da RCL de um ano especifico
     * @param $ano
     * @return float
     */
    protected function getRCLAno($ano)
    {
        $valor = $this->rclPlanejamento->filter(function ($valor) use ($ano) {
            return $valor->exercicio === $ano;
        })->first();

        return (float)$valor->valor;
    }

    public function getNotaExplicativa()
    {
        return $this->relatorio->getTextoNotaExplicativa();
    }

    /**
     * Carrega os fatores de correções do planejamento
     */
    protected function fatorCorrecaoReceita()
    {
        $this->fatorCorrecaoReceita = FatorCorrecaoReceita::query()
            ->where('planejamento_id', '=', $this->plano->pl2_codigo)
            ->get();
    }

    /**
     * Retorna o fator de correção utilizado na receita.
     * @param EstimativaReceita $estimativaReceita
     * @return Collection
     */
    protected function getFatoresReceita(EstimativaReceita $estimativaReceita)
    {
        return $this->fatorCorrecaoReceita->filter(
            function (FatorCorrecaoReceita $fatorCorrecaoReceita) use ($estimativaReceita) {
                return $estimativaReceita->orcfontes_id === $fatorCorrecaoReceita->orcfontes_id;
            }
        );
    }

    /**
     * Retorna o Fator de correção do ano informado
     * @param Collection $fatores uma coleção de FatorCorrecaoReceita
     * @param $ano
     * @return FatorCorrecaoReceita
     */
    protected function filtraFatorReceitaUtilizadoNoAno(Collection $fatores, $ano)
    {
        return $fatores->filter(function (FatorCorrecaoReceita $fator) use ($ano) {
            return $fator->exercicio === $ano;
        })->first();
    }

    /**
     * Busca os fatores de correção da despesa
     */
    protected function fatorCorrecaoDespesa()
    {
        $this->fatorCorrecaoDespesa = FatorCorrecaoDespesa::query()
            ->where('pl7_planejamento', '=', $this->plano->pl2_codigo)
            ->get();
    }

    /**
     * Retorna o fator de correção utilizado na despesa.
     * @param DetalhamentoDespesa $detalhamentoDespesa
     * @return Collection
     */
    protected function getFatoresDespesa(DetalhamentoDespesa $detalhamentoDespesa)
    {
        return $this->fatorCorrecaoDespesa->filter(
            function (FatorCorrecaoDespesa $fatorCorrecaoDespesa) use ($detalhamentoDespesa) {
                return $fatorCorrecaoDespesa->pl7_orcelemento === $detalhamentoDespesa->pl20_orcelemento;
            }
        );
    }

    /**
     * Retorna o Fator de correção do ano informado
     * @param Collection $fatores uma coleção de FatorCorrecaoDespesa
     * @param $ano
     * @return FatorCorrecaoDespesa
     */
    protected function filtraFatorDespesaUtilizadoNoAno(Collection $fatores, $ano)
    {
        return $fatores->filter(function (FatorCorrecaoDespesa $fator) use ($ano) {
            return $fator->pl7_exercicio === $ano;
        })->first();
    }
    /**
     * Retorna as estimativas do Planejamento compativeis com as contas configuradas na linha do relatorio legal
     * -- Esse metodo deve ser usado apenas quando iteramos sobre as estimativas do PLANEJAMENO.
     * @param array $contas contas configurada na linha do relatorio legal
     * @return \Illuminate\Support\Collection
     */
    protected function estimativasPlanejamentoCompativeisReceita(array $contas)
    {
        $estimativas = collect([]);
        foreach ($contas as $conta) {
            if (!$conta->exclusao) {
                $estimativas = $this->estimativasCompativeisContaReceita($conta)->merge($estimativas);
            }
        }

        $estimativas = $this->removeContasExclusaoReceita($contas, $estimativas);
        return $estimativas;
    }

    /**
     * @param $natureza
     * @return string
     */
    protected function getEstruturalAteNivel($natureza)
    {
        $estrutural = new EstruturalReceita($natureza);
        if ((strpos($natureza, '3') === 0) || (strpos($natureza, '1') === 0)) {
            $estrutural = new Estrutural($natureza);
        }

        return $estrutural->getEstruturalAteNivel();
    }

    /**
     * Retorna as estimativas da receita compativel com a conta configurada na configuração do relatório legal
     * -- Esse metodo é usado apenas quando iteramos sobre as estimativas do PLANEJAMENO.
     * @param $conta
     * @return Collection
     */
    protected function estimativasCompativeisContaReceita($conta)
    {
        $ateNivel = $this->getEstruturalAteNivel($conta->estrutural);

        return $this->buscarProjecaoReceita()->filter(
            function (EstimativaReceita $estimativa) use ($ateNivel) {
                return strpos($this->getNatureza($estimativa), $ateNivel, 0) === 0;
            }
        );
    }

    public function getNatureza($estimativa)
    {
        if ($estimativa instanceof EstimativaReceita) {
            $natureza = $estimativa->getNaturezaOrcamento();
            return $natureza->o57_fonte;
        }
        if ($estimativa instanceof DetalhamentoDespesa) {
            $natureza = $estimativa->getNaturezaDespesa();
            return $natureza->o56_elemento;
        }
        throw new Exception('Não foi possivel identificar a natureza.', 403);
    }


    /**
     * Remove as contas de exclusão das linhas da receita de acordo com a configuração da linha no relatorio legal
     * -- Esse metodo deve ser usado apenas quando iteramos sobre as estimativas do PLANEJAMENO.
     * @param $contas
     * @param \Illuminate\Support\Collection $estimativas
     * @return \Illuminate\Support\Collection
     */
    public function removeContasExclusaoReceita($contas, \Illuminate\Support\Collection $estimativas)
    {
        foreach ($contas as $conta) {
            if ($conta->exclusao) {
                $ateNivel = $this->getEstruturalAteNivel($conta->estrutural);
                $estimativas = $estimativas->reject(function (EstimativaReceita $estimativa) use ($ateNivel) {
                    return strpos($this->getNatureza($estimativa), $ateNivel, 0) === 0;
                });
            }
        }

        return $estimativas;
    }

    /**
     * Retorna as estimativas da despesa compativel com a conta configurada na configuração do relatório legal
     * * -- Esse metodo deve ser usado apenas quando iteramos sobre as estimativas do PLANEJAMENO.
     * @param $conta
     * @return Collection
     */
    protected function estimativasCompativeisContaDespesa($conta)
    {
        $ateNivel = $this->getEstruturalAteNivel($conta->estrutural);

        return $this->buscarProjecaoDespesa()->filter(
            function (DetalhamentoDespesa $detalhamentoDespesa) use ($ateNivel) {
                $natureza = $detalhamentoDespesa->getNaturezaDespesa();
                return strpos($natureza->o56_elemento, $ateNivel, 0) === 0;
            }
        );
    }

    /**
     * Retorna as estimativas do Planejamento compativeis com as contas configuradas na linha do relatorio legal
     * -- Esse metodo deve ser usado apenas quando iteramos sobre as estimativas do PLANEJAMENO.
     * @param $contas
     * @return \Illuminate\Support\Collection
     */
    protected function estimativasPlanejamentoCompativeisDespesa($contas)
    {
        $estimativas = collect([]);
        foreach ($contas as $conta) {
            if (!$conta->exclusao) {
                $estimativas = $this->estimativasCompativeisContaDespesa($conta)->merge($estimativas);
            }
        }

        $estimativas = $this->removeContasExclusaoDespesa($contas, $estimativas);
        return $estimativas;
    }

    /**
     * Remove as contas de exclusão das linhas da despesa de acordo com a configuração da linha no relatorio legal
     * -- Esse metodo deve ser usado apenas quando iteramos sobre as estimativas do PLANEJAMENO.
     * @param $contas
     * @param \Illuminate\Support\Collection $estimativas
     * @return \Illuminate\Support\Collection
     */
    protected function removeContasExclusaoDespesa($contas, \Illuminate\Support\Collection $estimativas)
    {
        foreach ($contas as $conta) {
            if ($conta->exclusao) {
                $ateNivel = $this->getEstruturalAteNivel($conta->estrutural);
                $estimativas = $estimativas->reject(function (DetalhamentoDespesa $detalhamento) use ($ateNivel) {
                    $natureza = $detalhamento->getNaturezaDespesa();
                    return strpos($natureza->o56_elemento, $ateNivel, 0) === 0;
                });
            }
        }
        return $estimativas;
    }

    /**
     * @param $ano
     * @return float
     * @throws Exception
     */
    protected function getPibAno($ano)
    {
        $valor = $this->plano->getPIB()->filter(function (Valor $valor) use ($ano) {
            return $valor->pl10_ano === $ano;
        })->first();

        if (is_null($valor)) {
            $msg = sprintf(
                'Não foi configurado o PIB para o ano de %s. Acesse: "%s" e informe os valores para o plano.',
                $ano,
                'DB:FINANCEIRO > Planejamento > Cadastros > PIB'
            );
            throw new Exception($msg, 403);
        }

        return (float)$valor->pl10_valor;
    }

    /**
     * Filtra as contas configurado nas linhas do relatorio legal
     * - Usamos esse metodo quando iteramos sobre o orçamento anteriores.
     * - Esse metodo itera tanto sobre a receita quanto despesa, contanto que na coleção tenha uma propriedade
     *   "natureza" que deve ser ou o elemento (o56_elemento) da despesa ou a fonte(o57_fonte) da receita
     * @param array $contas contas configurada na linha do relatorio legal
     * @param stdClass[] $dadosOrcamento
     * @return \Illuminate\Support\Collection
     */
    protected function estimativasCompativeis(array $contas, $dadosOrcamento)
    {
        $estimativas = collect([]);
        foreach ($contas as $conta) {
            if (!$conta->exclusao) {
                $estimativas = $this->estimativasCompativeisConta($conta, $dadosOrcamento)->merge($estimativas);
            }
        }

        $estimativas = $this->removeContasExclusao($contas, $estimativas);
        return $estimativas;
    }

    /**
     * @param $conta
     * @param $estimativas
     * @return \Illuminate\Support\Collection
     */
    protected function estimativasCompativeisConta($conta, $estimativas)
    {
        $ateNivel = $this->getEstruturalAteNivel($conta->estrutural);
        return collect($estimativas)->filter(function ($estimativa) use ($ateNivel) {
            return strpos($estimativa->natureza, $ateNivel, 0) === 0;
        });
    }

    /**
     * @param $contas
     * @param $estimativas
     * @return mixed
     */
    protected function removeContasExclusao($contas, $estimativas)
    {
        foreach ($contas as $conta) {
            if ($conta->exclusao) {
                $ateNivel = $this->getEstruturalAteNivel($conta->estrutural);
                $estimativas = $estimativas->reject(function ($estimativa) use ($ateNivel) {
                    return strpos($estimativa->natureza, $ateNivel, 0) === 0;
                });
            }
        }
        return $estimativas;
    }

    /**
     * Busca as previsões da receita do orçamento.
     * Essa função deve ser usada quando precisamos de dados olhando o passado
     * @param $ano
     * @param array $idInstituicoes
     * @return array
     */
    protected function estimativaReceitaOrcamento($ano, array $idInstituicoes)
    {
        $filtros = [
            "o70_anousu = {$ano}",
            sprintf("o70_instit in (%s)", implode(',', $idInstituicoes))
        ];

        $dataInicio = "$ano-01-01";
        $dataFim = "$ano-12-31";
        $where = implode(' and ', $filtros);

        $sql = "
        with dados_recitas as (
        select fc_receitasaldo_codfon(o70_anousu, o57_codfon, o70_concarpeculiar, 3, '{$dataInicio}', '$dataFim')
                as valores_receita,
               o70_anousu as ano,
               o70_codrec as receita,
               o57_fonte as natureza,
               o70_codigo as recurso,
               o70_instit as instituicao,
               o70_concarpeculiar as cp,
               o70_orcorgao as orgao,
               o70_orcunidade as unidade,
               o70_esferaorcamentaria as esfera
          from orcreceita
          join orcfontes on (o57_codfon, o57_anousu) = (o70_codfon, o70_anousu)
         where {$where}
        ), valores as (
            select valores_receita[2] as previsao_inicial,
                   valores_receita[4] as previsao_atualizada,
                   valores_receita[7] as saldo_a_arrecadar,
                   valores_receita[8] as arrecadado_acumulado,
                   ano,
                   receita,
                   natureza,
                   recurso,
                   instituicao,
                   cp,
                   orgao,
                   unidade,
                   esfera
              from dados_recitas
        )
        select * from valores
         where (previsao_atualizada <> 0 or saldo_a_arrecadar <> 0)
         order by natureza;
        ";

        return DB::select($sql);
    }

    /**
     * Busca as previsões da receita do orçamento.
     * Essa função deve ser usada quando precisamos de dados olhando o passado
     * @param $ano
     * @param array $idInstituicoes
     * @return array
     */
    protected function estimativaDespesaOrcamento($ano, array $idInstituicoes)
    {
        $sql = $this->montaQueryDespesa($ano, $idInstituicoes);
        $sql .= "
        select sum(dot_ini) as previsao,
               sum(liquidado_acumulado) as liquidado_acumulado,
               natureza
          from valores
         group by natureza
        ";

        return DB::select($sql);
    }

    /**
     * Busca as previsões da receita do orçamento.
     * Essa função deve ser usada quando precisamos de dados olhando o passado
     * @param $ano
     * @param array $idInstituicoes
     * @return array
     */
    protected function estimativaDespesaOrcamentoPorRecurso($ano, array $idInstituicoes)
    {
        $sql = $this->montaQueryDespesa($ano, $idInstituicoes);
        $sql .= "
        select sum(dot_ini) as previsao,
               sum(liquidado_acumulado) as liquidado_acumulado,
               natureza,
               recurso
          from valores
         group by natureza, recurso
        ";

        return DB::select($sql);
    }

    protected function montaQueryDespesa($ano, array $idInstituicoes)
    {
        $filtros = [
            "o58_anousu = {$ano}",
            sprintf("o58_instit in (%s)", implode(',', $idInstituicoes))
        ];

        $dataInicio = "$ano-01-01";
        $dataFim = "$ano-12-31";
        $where = implode(' and ', $filtros);
        $sql = "
            with dotacoes as (
                select fc_dotacaosaldo(o58_anousu,o58_coddot, 3, '{$dataInicio}', '{$dataFim}') as valores_despesa,
                       o56_elemento as natureza,
                       o58_codigo as recurso
                from orcdotacao
                join orcelemento on (o56_codele, o56_anousu) = (o58_codele, o58_anousu)
                where {$where}
            ), valores as (
                select substr(valores_despesa,3,12)::float8 as dot_ini,
                       substr(valores_despesa,198,12)::float8 as liquidado_acumulado,
                       natureza,
                       recurso
                from dotacoes
            )
        ";
        return $sql;
    }

    /**
     * Quando template é por session, esse metodo joga as linhas dentro de cada sessão
     */
    protected function organizaLinhas()
    {
        foreach ($this->sections as $section => $deAte) {
            $linhasSection = range($deAte[0], $deAte[1]);
            foreach ($linhasSection as $ordemLinha) {
                $this->linhasOrganizadas[$section][] = $this->linhas[$ordemLinha];
            }
        }
    }
}
