<?php

namespace ECidade\Financeiro\Contabilidade\Relatorio;

use db_utils;
use ECidade\Configuracao\RelatorioLegal\Enum\OrigemDadosEnum;
use ECidade\Configuracao\RelatorioLegal\Modelo\Coluna;
use ECidade\Configuracao\RelatorioLegal\Modelo\ColunaEstrutural;
use ECidade\Configuracao\RelatorioLegal\Modelo\Linha;
use ECidade\Configuracao\RelatorioLegal\Modelo\LinhaColuna;
use ECidade\Configuracao\RelatorioLegal\Modelo\Relatorio;
use ECidade\Configuracao\RelatorioLegal\Registry\ColunaRegistry;
use ECidade\Configuracao\RelatorioLegal\Registry\LinhaRegistry;
use ECidade\Configuracao\RelatorioLegal\Registry\RelatorioRegistry;
use ECidade\Configuracao\RelatorioLegal\Repositorio\InformacaoComplementarLancamentoRepositorio;
use ECidade\Configuracao\RelatorioLegal\Repositorio\LinhaInformacaoComplementarRepositorio;
use ECidade\Configuracao\RelatorioLegal\Repositorio\LinhaRepositorio;
use ECidade\Financeiro\Contabilidade\PlanoDeContas\Estrutural;
use Exception;
use RelatoriosLegaisBase;
use stdClass;

/**
 * Class RelatoriosLegaisBaseMSC
 *
 * @package ECidade\Financeiro\Contabilidade\Relatorio
 */
class RelatoriosLegaisBaseMSC extends RelatoriosLegaisBase
{
    /**
     * @var array
     */
    public static $camposMSC = array(
        'beginning_balance',
        'period_change_debit',
        'period_change_credit',
        'ending_balance'
    );
    /**
     * @var array
     */
    protected $linhasMSC = array();
    /**
     * @var Relatorio
     */
    protected $relatorio;

    /**
     * RelatoriosLegaisBaseMSC constructor.
     * @param int $ano
     * @param int $codigoRelatorio
     * @param int $codigoPeriodo
     * @throws Exception
     */
    public function __construct($ano, $codigoRelatorio, $codigoPeriodo)
    {
        parent::__construct($ano, $codigoRelatorio, $codigoPeriodo);
        $this->relatorio = RelatorioRegistry::get($codigoRelatorio);
    }

    /**
     * @param Linha $linha
     * @return stdClass
     * @throws Exception
     */
    public function decomporValoresMSCPorLinha(Linha $linha)
    {
        $contas = array();
        $colunas = array();
        $atributos = array();
        $filtroEstruturais = array();
        $siglasInformacoesComplementares = array();

        $linhaColunas = $linha->getLinhaColunas();
        $linhaInformacoesComplementares = $linha->getLinhaInformacoesComplementares();

        foreach ($linhaInformacoesComplementares as $linhaInformacaoComplementar) {
            $sigla = $linhaInformacaoComplementar->getSigla();
            $siglasInformacoesComplementares[$sigla] = $sigla;
        }

        $this->montarFiltroEstruturais($linhaColunas, $filtroEstruturais);

        $lancamentosContabeis = $this->buscarDecomposicaoMSC($linha, $filtroEstruturais);

        if (empty($lancamentosContabeis)) {
            $linhaInformacaoComplementarRepositorio = new LinhaInformacaoComplementarRepositorio();
            $informacaoComplementarLancamentoRepositorio = new InformacaoComplementarLancamentoRepositorio();
            $informacaoComplementarLancamentos = $informacaoComplementarLancamentoRepositorio
                ->setUseJoin(true)
                ->scopeRelatorio($this->relatorio)
                ->scopeLinha($linha)
                ->get(array('DISTINCT orcparamseqinfocomplementarlancamento.*'));

            if (empty($informacaoComplementarLancamentos)) {
                foreach ($linhaColunas as $linhaColuna) {
                    $coluna = $linhaColuna->getColuna();
                    $descricaoColuna = $coluna->getDescricao();
                    $colunaEstruturais = $coluna->getColunaEstruturais();
                    $ordemLinhaColuna = $linhaColuna->getOrdem();

                    $colunas[$ordemLinhaColuna] = array(
                        'descricao' => $descricaoColuna,
                        'ordem' => $ordemLinhaColuna,
                        'contas' => array()
                    );

                    $contas[$ordemLinhaColuna] = array();

                    foreach ($colunaEstruturais as $colunaEstrutural) {
                        $estrutural = $colunaEstrutural->getEstrutural();
                        $estruturalContaColuna = new Estrutural($estrutural);
                        $estruturalContaColuna = $estruturalContaColuna->getEstruturalComMascara();
                        $colunas[$ordemLinhaColuna]['contas'][] = $estruturalContaColuna;
                        $contas[$ordemLinhaColuna][] = $estruturalContaColuna;
                    }

                    sort($colunas[$ordemLinhaColuna]['contas']);
                }
            }

            foreach ($informacaoComplementarLancamentos as $informacaoComplementarLancamento) {
                $linhaInformacoesComplementares = $linhaInformacaoComplementarRepositorio
                    ->scopeInformacaoComplementarLancamento($informacaoComplementarLancamento)
                    ->get();

                $informacoesComplementares = array();
                $hashAtributos = '';

                foreach ($linhaInformacoesComplementares as $linhaInformacaoComplementar) {
                    $linhaInformacaoComplementarValor = $linhaInformacaoComplementar->getValor();
                    $linhaInformacaoComplementarSigla = $linhaInformacaoComplementar->getSigla();

                    $informacoesComplementares[$linhaInformacaoComplementarSigla] = $linhaInformacaoComplementarValor;

                    $hashAtributos .= "{$linhaInformacaoComplementarValor}#{$linhaInformacaoComplementarSigla}";
                }

                $atributos[$hashAtributos] = array(
                    'informacoes' => $informacoesComplementares,
                    'valores' => array()
                );

                foreach ($linhaColunas as $linhaColuna) {
                    $coluna = $linhaColuna->getColuna();
                    $descricaoColuna = $coluna->getDescricao();
                    $colunaEstruturais = $coluna->getColunaEstruturais();
                    $ordemLinhaColuna = $linhaColuna->getOrdem();

                    $colunas[$ordemLinhaColuna] = array(
                        'descricao' => $descricaoColuna,
                        'ordem' => $ordemLinhaColuna,
                        'contas' => array()
                    );

                    if (empty($atributos[$hashAtributos]['valores'][$ordemLinhaColuna])) {
                        $atributos[$hashAtributos]['valores'][$ordemLinhaColuna] = array();
                    }

                    $contas[$ordemLinhaColuna] = array();

                    foreach ($colunaEstruturais as $colunaEstrutural) {
                        $estrutural = $colunaEstrutural->getEstrutural();
                        $estruturalContaColuna = new Estrutural($estrutural);
                        $estruturalContaColuna = $estruturalContaColuna->getEstruturalComMascara();
                        $colunas[$ordemLinhaColuna]['contas'][] = $estruturalContaColuna;
                        $contas[$ordemLinhaColuna][] = $estruturalContaColuna;
                        $atributos[$hashAtributos]['valores'][$ordemLinhaColuna][$estruturalContaColuna] = 0;
                    }

                    sort($colunas[$ordemLinhaColuna]['contas']);
                }
            }
        }

        foreach ($lancamentosContabeis as $lancamentoContabil) {
            $atributosLancamentoContabil = explode('|', $lancamentoContabil->atributos);
            $informacoesComplementares = array();

            foreach ($atributosLancamentoContabil as $atributoLancamentoContabil) {
                $informacaoComplementar = explode('#', $atributoLancamentoContabil);

                if (empty($siglasInformacoesComplementares) ||
                    in_array($informacaoComplementar[1], $siglasInformacoesComplementares)) {
                    $informacoesComplementares[$informacaoComplementar[1]] = $informacaoComplementar[0];
                }
            }

            ksort($informacoesComplementares);
            $hashAtributos = '';

            foreach ($informacoesComplementares as $siglaInformacaoComplementar => $valorInformacaoComplementar) {
                $hashAtributos .= "{$valorInformacaoComplementar}#{$siglaInformacaoComplementar}";
            }

            if ($informacoesComplementares) {
                if (empty($atributos[$hashAtributos]['valores'])) {
                    $atributos[$hashAtributos]['valores'] = array();
                }

                $atributos[$hashAtributos] = array(
                    'informacoes' => $informacoesComplementares,
                    'valores' => $atributos[$hashAtributos]['valores']
                );

                foreach ($linhaColunas as $linhaColuna) {
                    $excluidos = array();
                    $coluna = $linhaColuna->getColuna();
                    $descricaoColuna = $coluna->getDescricao();
                    $colunaEstruturais = $coluna->getColunaEstruturais();
                    $ordemLinhaColuna = $linhaColuna->getOrdem();

                    $colunas[$ordemLinhaColuna] = array(
                        'descricao' => $descricaoColuna,
                        'ordem' => $ordemLinhaColuna,
                        'contas' => array()
                    );

                    if (empty($atributos[$hashAtributos]['valores'][$ordemLinhaColuna])) {
                        $atributos[$hashAtributos]['valores'][$ordemLinhaColuna] = array();
                    }

                    $contas[$ordemLinhaColuna] = array();

                    foreach ($colunaEstruturais as $colunaEstrutural) {
                        $estruturalAteNivel = $this->getEstruturalAteNivel($lancamentoContabil, $colunaEstrutural);

                        $this->negativarColunaEstrutural(
                            $excluidos,
                            $lancamentoContabil,
                            $colunaEstrutural,
                            $estruturalAteNivel
                        );

                        $estrutural = $colunaEstrutural->getEstrutural();
                        $estruturalContaColuna = new Estrutural($estrutural);
                        $estruturalContaColuna = $estruturalContaColuna->getEstruturalComMascara();
                        $colunas[$ordemLinhaColuna]['contas'][] = $estruturalContaColuna;
                        $contas[$ordemLinhaColuna][] = $estruturalContaColuna;

                        if (empty($atributos[$hashAtributos]['valores'][$ordemLinhaColuna][$estruturalContaColuna])) {
                            $atributos[$hashAtributos]['valores'][$ordemLinhaColuna][$estruturalContaColuna] = 0;
                        }

                        if ($colunaEstrutural->getEstrutural() === $estruturalAteNivel) {
                            $valorLancamentoContabil = $this->resolverFormulaColuna($coluna, $lancamentoContabil);
                            $atributos[$hashAtributos]['valores'][$ordemLinhaColuna][$estruturalContaColuna] +=
                                $valorLancamentoContabil;
                        }
                    }

                    sort($colunas[$ordemLinhaColuna]['contas']);
                }
            }
        }

        $decomposicao = new stdClass();
        $decomposicao->contas = $contas;
        $decomposicao->colunas = $colunas;
        $decomposicao->atributos = $atributos;

        return $decomposicao;
    }

    /**
     * @param LinhaColuna[] $linhaColunas
     * @param array $filtroEstruturais
     */
    private function montarFiltroEstruturais(
        array $linhaColunas,
        array &$filtroEstruturais
    ) {
        foreach ($linhaColunas as $linhaColuna) {
            $coluna = $linhaColuna->getColuna();
            $colunaEstruturais = $coluna->getColunaEstruturais();

            foreach ($colunaEstruturais as $colunaEstrutural) {
                $estrutural = $colunaEstrutural->getEstrutural();
                $filtroEstruturais[$estrutural] = "c133_estrutural LIKE '{$estrutural}%'";
            }
        }
    }

    /**
     * @param Linha $linha
     * @param array $filtroEstruturais
     * @return stdClass[]
     * @throws Exception
     */
    private function buscarDecomposicaoMSC(
        Linha $linha,
        array $filtroEstruturais = array()
    ) {
        $sql = "
            {$this->montarDeclaracoesAuxiliares($linha, $filtroEstruturais)}
            SELECT estrutural,
                   atributos,
                   beginning_balance,
                   period_change_debit,
                   period_change_credit,
                   ending_balance
            FROM matriz_saldo_contabil_valores;
        ";

        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception('Não foi possível buscar os valores da matriz saldo contábil.');
        }

        return db_utils::getCollectionByRecord($rs);
    }

    /**
     * @param Linha $linha
     * @param array $filtroEstruturais
     * @return string
     * @throws Exception
     */
    private function montarDeclaracoesAuxiliares(
        Linha $linha,
        array $filtroEstruturais = array()
    ) {
        $temInformacoesComplementares = count($linha->getLinhaInformacoesComplementares()) > 0;
        $nomeDeclaracao = $temInformacoesComplementares
            ? 'matriz_saldo_contabil_lancamentos'
            : 'matriz_saldo_contabil_valores';

        $declaracao = "
            WITH matriz_saldo_contabil_emissoes AS ({$this->sqlMatrizSaldoContabilEmissoes()}),
                 {$nomeDeclaracao} AS ({$this->sqlMatrizSaldoContabilLancamentos($filtroEstruturais)})
        ";

        if ($temInformacoesComplementares) {
            $declaracao .= "
                , matriz_saldo_contabil_configuracao AS ({$this->sqlMatrizSaldoContabilConfiguracao($linha)}),
                matriz_saldo_contabil_valores AS ({$this->sqlMatrizSaldoContabilValores()})
            ";
        }

        return $declaracao;
    }

    /**
     * @param array $filtroEstruturais
     * @return string
     * @throws Exception
     */
    private function sqlMatrizSaldoContabilLancamentos(array $filtroEstruturais = array())
    {
        $filtroEstruturais = $filtroEstruturais ? 'WHERE (' . implode(' OR ', $filtroEstruturais) . ')' : '';

        return "
            SELECT c133_estrutural           AS estrutural,
                   c133_atributos            AS atributos,
                   c133_beginning_balance    AS beginning_balance,
                   c133_period_change_debit  AS period_change_debit,
                   c133_period_change_credit AS period_change_credit,
                   c133_ending_balance       AS ending_balance
            FROM matriz_saldo_contabil_lancamentos
                     JOIN matriz_saldo_contabil_emissoes
                          ON c132_sequencial = c133_matriz_saldo_contabil
            {$filtroEstruturais}
        ";
    }

    /**
     * @param Linha $linha
     * @return string
     */
    private function sqlMatrizSaldoContabilConfiguracao(Linha $linha)
    {
        return "
            SELECT o102_exclusao AS exclusao, array_agg(o157_valor || '#' || c121_sigla) AS atributos
            FROM orcparamseqinfocomplementar
                     JOIN conplanoinfocomplementar ON c121_sequencial = o157_conplanoinfocomplementar
                     JOIN orcparamseqinfocomplementarlancamento ON o102_sequencial = o157_infocomplementarlancamento
            WHERE o157_relatorio = {$this->relatorio->getSequencial()}
              AND o157_linha = {$linha->getLinha()}
              AND (NOT EXISTS(SELECT TRUE FROM orcparamseqinfocomplementar WHERE o157_linha = {$linha->getLinha()}
                                     AND o157_relatorio = {$this->relatorio->getSequencial()}) OR
                   o157_padrao = (NOT EXISTS(SELECT TRUE
                                             FROM orcparamseqinfocomplementar
                                             WHERE o157_linha = {$linha->getLinha()} AND
                                                   o157_relatorio = {$this->relatorio->getSequencial()} AND
                                                   o157_padrao IS FALSE)))
            GROUP BY o102_sequencial, o102_exclusao
        ";
    }

    /**
     * @return string
     */
    private function sqlMatrizSaldoContabilValores()
    {
        return "
            SELECT matriz_saldo_contabil_lancamentos.estrutural                        AS estrutural,
                   matriz_saldo_contabil_lancamentos.atributos                         AS atributos,
                   CASE
                       WHEN exclusao IS TRUE THEN -matriz_saldo_contabil_lancamentos.beginning_balance
                       ELSE matriz_saldo_contabil_lancamentos.beginning_balance END    AS beginning_balance,
                   CASE
                       WHEN exclusao IS TRUE THEN -matriz_saldo_contabil_lancamentos.period_change_debit
                       ELSE matriz_saldo_contabil_lancamentos.period_change_debit END  AS period_change_debit,
                   CASE
                       WHEN exclusao IS TRUE THEN -matriz_saldo_contabil_lancamentos.period_change_credit
                       ELSE matriz_saldo_contabil_lancamentos.period_change_credit END AS period_change_credit,
                   CASE
                       WHEN exclusao IS TRUE THEN -matriz_saldo_contabil_lancamentos.ending_balance
                       ELSE matriz_saldo_contabil_lancamentos.ending_balance END       AS ending_balance
            FROM matriz_saldo_contabil_lancamentos
                  JOIN matriz_saldo_contabil_configuracao ON
                  (SELECT count(TRUE) = array_length(matriz_saldo_contabil_configuracao.atributos, 1)
                    FROM UNNEST(string_to_array(matriz_saldo_contabil_lancamentos.atributos, '|')) AS U
                   WHERE U ILIKE ANY (matriz_saldo_contabil_configuracao.atributos) IS TRUE)
        ";
    }

    /**
     * @param stdClass $lancamentoContabil
     * @param ColunaEstrutural $colunaEstrutural
     * @return bool|string
     */
    private function getEstruturalAteNivel(
        stdClass $lancamentoContabil,
        ColunaEstrutural $colunaEstrutural
    ) {
        $estruturalAteNivel = substr(
            $lancamentoContabil->estrutural,
            0,
            strlen($colunaEstrutural->getEstrutural())
        );
        return $estruturalAteNivel;
    }

    /**
     * @param array $excluidos
     * @param stdClass $lancamentoContabil
     * @param ColunaEstrutural $colunaEstrutural
     * @param $estruturalAteNivel
     */
    private function negativarColunaEstrutural(
        array $excluidos,
        stdClass $lancamentoContabil,
        ColunaEstrutural $colunaEstrutural,
        $estruturalAteNivel
    ) {
        foreach (static::$camposMSC as $campoMSC) {
            if (!array_key_exists($campoMSC, $excluidos) ||
                !in_array($lancamentoContabil->estrutural, $excluidos)) {
                $excluidos[$campoMSC] = $lancamentoContabil->estrutural;

                if ($colunaEstrutural->isExclusao() &&
                    $colunaEstrutural->getEstrutural() === $estruturalAteNivel) {
                    $lancamentoContabil->{$campoMSC} *= -1;
                }
            }
        }
    }

    /**
     * @param Coluna $coluna
     * @param stdClass $lancamentoContabil
     * @return int
     */
    private function resolverFormulaColuna(Coluna $coluna, stdClass $lancamentoContabil)
    {
        $formulaColuna = str_replace('#', '$lancamentoContabil->', $coluna->getFormula());
        $valorLancamentoContabil = 0;

        if (trim($formulaColuna) !== '') {
            eval("\$valorLancamentoContabil = {$formulaColuna};");
        }

        return $valorLancamentoContabil;
    }

    /**
     *
     */
    protected function processarTiposDeCalculo()
    {
        foreach ($this->aLinhasConsistencia as $chaveLinha => $linha) {
            if ($linha->totalizar) {
                continue;
            }

            switch ($linha->origem) {
                case OrigemDadosEnum::BALANCETE_RECEITA:
                    $this->aLinhasProcessarReceita[] = $chaveLinha;
                    break;
                case OrigemDadosEnum::BALANCETE_DESPESA:
                    $this->aLinhasProcessarDespesa[] = $chaveLinha;
                    break;
                case OrigemDadosEnum::RESTOS_PAGAR:
                    $this->aLinhasProcessarRestosPagar[] = $chaveLinha;
                    break;
                case OrigemDadosEnum::BALANCETE_VERIFICACAO:
                    $this->aLinhasProcessarVerificacao[] = $chaveLinha;
                    break;
                case OrigemDadosEnum::MSC:
                    $this->linhasMSC[] = $chaveLinha;
                    break;
            }
        }
    }
    protected function organizaLinhasPorTipoDeCalculo()
    {
        $this->processarTiposDeCalculo();
    }

    /**
     * @throws Exception
     */
    protected function executarBalancetesNecessarios()
    {
        parent::executarBalancetesNecessarios();

        if (count($this->linhasMSC) > 0) {
            $this->executarMSC();
        }
    }

    /**
     * @throws Exception
     */
    public function executarMSC()
    {
        if (empty($this->linhasMSC)) {
            $this->carregarLinhasMSC();
        }

        $linhaRepositorio = new LinhaRepositorio();
        $linhas = $linhaRepositorio->scopeRelatorio($this->relatorio)
            ->scopePeriodo($this->iCodigoPeriodo)
            ->scopeOrigem(OrigemDadosEnum::MSC)
            ->scopeConfiguracao()
            ->get();

        foreach ($this->linhasMSC as $chaveLinhaConsistencia) {
            $linhaConsistencia = $this->aLinhasConsistencia[$chaveLinhaConsistencia];
            $linha = $linhas[$chaveLinhaConsistencia];
            $linhaColunas = $linha->getLinhaColunas();
            $filtroEstruturais = array();

            $this->montarFiltroEstruturais($linhaColunas, $filtroEstruturais);
            $valoresMSC = $this->buscarValoresMSC($linha, $filtroEstruturais);

            foreach ($linhaColunas as $linhaColuna) {
                $coluna = $linhaColuna->getColuna();
                $nomeColuna = $coluna->getNome();
                $linhaConsistencia->{$nomeColuna} = 0;
                $excluidos = array();

                foreach ($valoresMSC as $valorMSC) {
                    $valorMSCCalculo = clone $valorMSC;

                    foreach ($coluna->getColunaEstruturais() as $colunaEstrutural) {
                        $estruturalAteNivel = $this->getEstruturalAteNivel($valorMSCCalculo, $colunaEstrutural);

                        $this->negativarColunaEstrutural(
                            $excluidos,
                            $valorMSCCalculo,
                            $colunaEstrutural,
                            $estruturalAteNivel
                        );

                        if ($colunaEstrutural->getEstrutural() === $estruturalAteNivel) {
                            $valor = $this->resolverFormulaColuna($coluna, $valorMSCCalculo);
                            $linhaConsistencia->{$nomeColuna} += $valor;
                        }
                    }
                }
            }
        }
    }

    /**
     * @param Linha $linha
     * @param array $filtroEstruturais
     * @return stdClass[]
     * @throws Exception
     */
    private function buscarValoresMSC(
        Linha $linha,
        array $filtroEstruturais = array()
    ) {
        $sql = "
            {$this->montarDeclaracoesAuxiliares($linha, $filtroEstruturais)}
            SELECT estrutural,
                   atributos,
                   sum(beginning_balance)    AS beginning_balance,
                   sum(period_change_debit)  AS period_change_debit,
                   sum(period_change_credit) AS period_change_credit,
                   sum(ending_balance)       AS ending_balance
            FROM matriz_saldo_contabil_valores
            GROUP BY estrutural, atributos;
        ";

        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception('Não foi possível buscar os valores da matriz saldo contábil.');
        }

        return db_utils::getCollectionByRecord($rs);
    }

    /**
     * @param stdClass $linhaConsistencia
     * @param array $colunas
     * @return array
     * @throws Exception
     */
    protected function getColunasPorLinha(stdClass $linhaConsistencia, array $colunas = array())
    {
        $linha = LinhaRegistry::get($this->relatorio, $linhaConsistencia->oLinhaRelatorio->getCodigo());
        $colunasProcessar = array();

        foreach ($linhaConsistencia->colunas as $ordemColuna => $colunaRelatorio) {
            if (!empty($colunas) && !in_array($ordemColuna, $colunas)) {
                continue;
            }

            $coluna = ColunaRegistry::get($colunaRelatorio->o115_sequencial);
            $nomeColuna = $colunaRelatorio->o115_nomecoluna;
            $formula = $linha->isTotalizadora() || $coluna->getFormula() === ''
                ? $colunaRelatorio->o116_formula
                : $coluna->getFormula();

            if (!isset($linhaConsistencia->{$nomeColuna})) {
                $linhaConsistencia->{$nomeColuna} = 0;
            }

            $colunaProcessar = new stdClass();
            $colunaProcessar->nome = $nomeColuna;
            $colunaProcessar->formula = $formula;
            $colunaProcessar->analisada = false;

            if (property_exists($colunaRelatorio, 'agrupar')) {
                $colunaProcessar->agrupar = $colunaRelatorio->agrupar;
            }

            $colunasProcessar[] = $colunaProcessar;
        }

        return $colunasProcessar;
    }

    /**
     * @return string
     */
    private function sqlMatrizSaldoContabilEmissoes()
    {
        return "
            SELECT *
            FROM matriz_saldo_contabil
            WHERE c132_mes = {$this->getDataFinal()->getMes()}
              AND c132_ano = {$this->getDataFinal()->getAno()}
        ";
    }

    /**
     *
     */
    private function carregarLinhasMSC()
    {
        $this->aLinhasConsistencia = $this->getLinhasRelatorio();

        foreach ($this->aLinhasConsistencia as $chaveLinha => $linha) {
            if ($linha->totalizar) {
                continue;
            }

            if ($linha->origem == OrigemDadosEnum::MSC) {
                $this->linhasMSC[] = $chaveLinha;
            }
        }
    }
}
