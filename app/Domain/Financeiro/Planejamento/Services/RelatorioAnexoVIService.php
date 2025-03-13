<?php

namespace App\Domain\Financeiro\Planejamento\Services;

use App\Domain\Financeiro\Planejamento\Relatorios\Anexos\XlsAnexoVI;
use Illuminate\Support\Facades\DB;
use linhaRelatorioContabil;
use stdClass;

class RelatorioAnexoVIService extends AnexosLDOService
{
    /**
     * Mapeia as seções do relatório no excell
     * @var \int[][]
     */
    protected $sections = [
        'rec_previdenciarias1' => [1, 32],
        'desp_previdenciarias1' => [33, 43],
        'recursos_rpps1' => [44, 44],
        'reserva_orcamentaria1' => [45, 45],
        'aportes_rpps1' => [46, 49],
        'bens_direitos_rpps1' => [50, 52],
        'rec_previdenciarias2' => [53, 83],
        'desp_previdenciarias2' => [84, 94],
        'aportes_rpps2' => [95, 96],
        'receitas_admin_rpps' => [97, 97],
        'despesa_admin_rpps' => [98, 99],
    ];

    const LINHA_PLANO_PREVIDENCIARIO = 100;
    const LINHA_PLANO_FINANCEIRO = 101;

    /**
     * @var array
     */
    private $colunasLinhasManuais = [
        'exercicio' => '',
        'receita_previdenciaria' => 0,
        'despesa_previdenciaria' => 0,
        'resultado_previdenciario' => 0,
        'saldo_financeiro' => 0,
    ];

    private $linhasImprimir = [];

    /**
     * @var array com os exercícios anteriores
     */
    private $anosAnteriores;
    /**
     * @var array com as propriedades por ano
     */
    private $propriedadesAno;

    /**
     * @var array com as estimativas das receitas anteriores
     */
    private $estimativasReceitaAnteriores = [];
    /**
     * @var array com as previsões da despesa anteriores
     */
    private $estimativasDespesaAnteriores = [];

    /**
     * @var array saldo das contas
     */
    private $balanceteVerificacaoAnteriores = [];

    public function __construct(array $filtros)
    {
        parent::__construct($filtros);

        $this->processar();
        $this->parser = new XlsAnexoVI();
    }

    public function processar()
    {
        parent::processar();
        $this->anosAnteriores();

        $this->processaBalancetes();

        $this->processaLinhas();
        $this->processaLinhasManuais();
        $this->organizaLinas();
    }

    public function emitir()
    {
        $this->parser->setEnteFederativo($this->enteFederativo);
        $this->parser->setEmissor($this->emissor);
        $this->parser->setAnoReferencia($this->plano->pl2_ano_inicial);
        $this->parser->setNotaExplicativa($this->getNotaExplicativa());

        foreach ($this->linhasImprimir as $section => $linhas) {
            $this->parser->addCollection($section, $linhas);
        }

        $filename = $this->parser->gerar();

        return [
            'xls' => $filename,
            'xlsLinkExterno' => ECIDADE_REQUEST_PATH . $filename
        ];
    }

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

            if ((int)$linhaRelatorio->getOrigemDados() == AnexosLDOService::VERIFICACAO) {
                $this->processaBalanceteVerificacao($linha);
            }
        }

        $this->processaValorManual();
    }

    protected function processaReceita($linha)
    {
        foreach ($this->estimativasReceitaAnteriores as $ano => $estimativasOrcamento) {
            $estimativas = $this->estimativasCompativeis($linha->parametros->contas, $estimativasOrcamento);
            $estimativas = $this->filtraRecurso($linha->parametros->orcamento, $estimativas);
            $estimativas->each(function ($estimativa) use ($linha, $ano) {
                $propriedade = $this->propriedadesAno[$ano];

                $linha->{$propriedade} += $estimativa->previsao_inicial;
            });
        }
    }

    protected function processaDespesa($linha)
    {
        foreach ($this->estimativasDespesaAnteriores as $ano => $estimativasOrcamento) {
            $estimativas = $this->estimativasCompativeis($linha->parametros->contas, $estimativasOrcamento);
            $estimativas = $this->filtraRecurso($linha->parametros->orcamento, $estimativas);

            $estimativas->each(function ($estimativa) use ($linha, $ano) {
                $propriedade = $this->propriedadesAno[$ano];
                $linha->{$propriedade} += $estimativa->previsao;
            });
        }
    }

    private function processaBalanceteVerificacao($linha)
    {
        foreach ($this->balanceteVerificacaoAnteriores as $ano => $balanceteVerificacao) {
            $estimativas = $this->estimativasCompativeis($linha->parametros->contas, $balanceteVerificacao);
            $estimativas = $this->filtraRecurso($linha->parametros->orcamento, $estimativas);

            $estimativas->each(function ($estimativa) use ($linha, $ano) {
                $propriedade = $this->propriedadesAno[$ano];
                $linha->{$propriedade} += $estimativa->saldo_final;
            });
        }
    }

    private function organizaLinas()
    {
        foreach ($this->sections as $section => $deAte) {
            $linhasSection = range($deAte[0], $deAte[1]);
            foreach ($linhasSection as $ordemLinha) {
                $this->linhasImprimir[$section][] = $this->linhas[$ordemLinha];
            }
        }
    }

    private function anosAnteriores()
    {
        $ano = $this->plano->pl2_ano_inicial - 4;
        $this->propriedadesAno[$ano] = "vlr_ano_menos_quatro";
        $this->anosAnteriores[] = $ano;

        $ano = $this->plano->pl2_ano_inicial - 3;
        $this->propriedadesAno[$ano] = "vlr_ano_menos_tres";
        $this->anosAnteriores[] = $ano;

        $ano = $this->plano->pl2_ano_inicial - 2;
        $this->propriedadesAno[$ano] = "vlr_ano_menos_dois";
        $this->anosAnteriores[] = $ano;
    }

    private function processaLinhasManuais()
    {
        $linha = $this->linhas[self::LINHA_PLANO_PREVIDENCIARIO];
        $this->criaLinhas('iterar_dados1', $linha);

        $linha = $this->linhas[self::LINHA_PLANO_FINANCEIRO];
        $this->criaLinhas('iterar_dados2', $linha);
    }

    private function criaLinhas($section, $linha)
    {
        foreach ($linha->oLinhaRelatorio->getValoresColunas() as $valoresManuais) {
            $objetoLinha = $this->criaLinha();
            foreach ($valoresManuais->colunas as $coluna) {
                $nomeColuna = $coluna->o115_nomecoluna;
                if (isset($this->colunasLinhasManuais[$nomeColuna])) {
                    if (is_string($this->colunasLinhasManuais[$nomeColuna])) {
                        $objetoLinha->{$nomeColuna} = $coluna->o117_valor;
                    } else {
                        $objetoLinha->{$nomeColuna} += $coluna->o117_valor;
                    }
                }
            }

            $this->linhasImprimir[$section][] = $objetoLinha;
        }
    }

    private function criaLinha()
    {
        return (object)$this->colunasLinhasManuais;
    }

    private function filtraRecurso($filtros, \Illuminate\Support\Collection $estimativas)
    {
        if (empty($filtros->recurso->valor)) {
            return $estimativas;
        }

        $operador = $filtros->recurso->operador;
        $valores = $filtros->recurso->valor;

        $estimativas = $estimativas->filter(function ($estimativa) use ($operador, $valores) {
            if ($operador === 'in') {
                return in_array($estimativa->recurso, $valores);
            } else {
                return !in_array($estimativa->recurso, $valores);
            }
        });

        return $estimativas;
    }

    protected function balanceteVerificacao($ano, array $idInstituicoes)
    {
        $filtros = [
            "c62_anousu = {$ano}",
            sprintf("c61_instit in (%s)", implode(',', $idInstituicoes))
        ];

        $dataInicio = "$ano-01-01";
        $dataFim = "$ano-12-31";
        $where = implode(' and ', $filtros);

        $sql = "
        with contas as (
            SELECT p.c60_estrut as estrutural,
                   c61_reduz as reduzido,
                   c61_codcon as codigo_conta,
                   c61_codigo as recurso,
                   p.c60_descr as descricao,
                   p.c60_finali as finalidade,
                   r.c61_instit as instituicao,
                   fc_planosaldonovo_array({$ano}, c61_reduz, '{$dataInicio}', '{$dataFim}', FALSE),
                   p.c60_identificadorfinanceiro as identificador_financeiro,
                   c60_consistemaconta as sistema_conta
              FROM conplanoexe e
              JOIN conplanoreduz r ON r.c61_anousu = c62_anousu
                   AND r.c61_reduz = c62_reduz
              JOIN conplano p ON r.c61_codcon = c60_codcon
                   AND r.c61_anousu = c60_anousu
              LEFT OUTER JOIN consistema ON c60_codsis = c52_codsis
             WHERE {$where}
        ), valores as (
           SELECT estrutural,
                  estrutural as natureza,
                  reduzido,
                  codigo_conta,
                  recurso,
                  descricao,
                  finalidade,
                  instituicao,
                  round(fc_planosaldonovo_array[1]::float8, 2)::float8 as saldo_anterior,
                  round(fc_planosaldonovo_array[2]::float8, 2)::float8 as saldo_anterior_debito,
                  round(fc_planosaldonovo_array[3]::float8, 2)::float8 as saldo_anterior_credito,
                  round(fc_planosaldonovo_array[4]::float8, 2)::float8 as saldo_final,
                  fc_planosaldonovo_array[5]::varchar(1) as sinal_anterior,
                  fc_planosaldonovo_array[6]::varchar(1) AS sinal_final,
                  identificador_financeiro,
                  sistema_conta
             FROM contas
        ) SELECT * from valores
        ";

        return DB::select($sql);
    }

    private function processaBalancetes()
    {
        foreach ($this->anosAnteriores as $anoAnterior) {
            $this->estimativasReceitaAnteriores[$anoAnterior] = $this->estimativaReceitaOrcamento(
                $anoAnterior,
                $this->codigosInstituicoes
            );

            $this->estimativasDespesaAnteriores[$anoAnterior] = $this->estimativaDespesaOrcamentoPorRecurso(
                $anoAnterior,
                $this->codigosInstituicoes
            );

            $this->balanceteVerificacaoAnteriores[$anoAnterior] = $this->balanceteVerificacao(
                $anoAnterior,
                $this->codigosInstituicoes
            );
        }
    }
}
