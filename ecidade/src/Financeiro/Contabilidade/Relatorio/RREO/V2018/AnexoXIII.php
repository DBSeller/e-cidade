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

namespace ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2018;

use DBException;
use ECidade\Financeiro\Contabilidade\Calculo\ReceitaCorrenteLiquida;
use Periodo;

/**
 * Class AnexoXIII
 * @package ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2018
 */
class AnexoXIII extends \RelatoriosLegaisBase
{
    /**
     * @var int
     */
    const CODIGO_RELATORIO = 180;
    /**
     * @var int
     */
    const LINHA_TOTAL_DE_ATIVOS = 1;
    /**
     * @var int
     */
    const LINHA_ATIVOS_CONSTITUIDOS_PELA_SPE = 2;
    /**
     * @var int
     */
    const LINHA_TOTAL_DE_PASSIVOS = 3;
    /**
     * @var int
     */
    const LINHA_OBRIGACOES_DECORRENTES_DE_ATIVOS_CONSTITUIDOS_PELA_SPE = 4;
    /**
     * @var int
     */
    const LINHA_PROVISOES_DE_PPP = 5;
    /**
     * @var int
     */
    const LINHA_OUTROS_PASSIVOS = 6;
    /**
     * @var int
     */
    const LINHA_ATOS_POTENCIAIS_PASSIVOS = 7;
    /**
     * @var int
     */
    const LINHA_OBRIGACOES_CONTRATUAIS = 8;
    /**
     * @var int
     */
    const LINHA_RISCOS_NAO_PROVISIONADOS = 9;
    /**
     * @var int
     */
    const LINHA_GARANTIAS_CONCEDIDAS = 10;
    /**
     * @var int
     */
    const LINHA_OUTROS_PASSIVOS_CONTINGENTES = 11;
    /**
     * @var int
     */
    const LINHA_DO_ENTE_FEDERADO_EXCETO_ESTATAIS_NAO_DEPENDENTES_I = 12;
    /**
     * @var int
     */
    const LINHA_DESPESAS_DE_PPP = 13;
    /**
     * @var int
     */
    const LINHA_DAS_ESTATAIS_NAO_DEPENDENTES = 14;
    /**
     * @var int
     */
    const LINHA_DESPESAS_DE_PPP_ESTATAIS = 15;
    /**
     * @var int
     */
    const LINHA_TOTAL_DAS_DESPESAS = 16;
    /**
     * @var int
     */
    const LINHA_PPP_A_CONTRATAR_II = 17;
    /**
     * @var int
     */
    const LINHA_RECEITA_CORRENTE_LIQUIDA_RCL_III = 18;
    /**
     * @var int
     */
    const LINHA_TOTAL_DAS_DESPESAS_CONSIDERADAS_PARA_O_LIMITE_IV_I_II = 19;
    /**
     * @var int
     */
    const LINHA_TOTAL_DAS_DESPESAS_RCL_V_IV_III = 20;

    /**
     * @var int
     */
    protected $linhasAdicionadasEnteFederado = 0;
    /**
     * @var int
     */
    protected $linhasAdicionadasEstatais = 0;

    /**
     * AnexoXIII constructor.
     * @param $ano
     * @param $periodo
     */
    public function __construct($ano, $periodo)
    {
        parent::__construct($ano, static::CODIGO_RELATORIO, $periodo);
    }

    /**
     * @return array
     */
    public function getLinhas()
    {
        if (empty($this->aLinhasConsistencia)) {
            $this->processar();
        }

        return $this->aLinhasConsistencia;
    }

    /**
     * @return int
     */
    public function getUltimaLinhaQuadroImpactosContratacoes()
    {
        return static::LINHA_OUTROS_PASSIVOS_CONTINGENTES;
    }

    /**
     * @throws DBException
     */
    private function processar()
    {
        $this->getDados();
        $this->processarDespesasPPP();
    }

    /**
     * @throws DBException
     */
    protected function processarDespesasPPP()
    {
        $linhas = array_slice($this->aLinhasConsistencia, 0, 12, true);

        $this->adicionaLinhasDespesasPPP(static::LINHA_DESPESAS_DE_PPP, $linhas);

        $linhas[] = $this->aLinhasConsistencia[static::LINHA_DAS_ESTATAIS_NAO_DEPENDENTES];

        $this->adicionaLinhasDespesasPPP(static::LINHA_DESPESAS_DE_PPP_ESTATAIS, $linhas);

        $ini = static::LINHA_TOTAL_DAS_DESPESAS;
        $fim = static::LINHA_TOTAL_DAS_DESPESAS_RCL_V_IV_III;
        for ($linha = $ini; $linha <= $fim; $linha++) {
            $linhas[] = $this->aLinhasConsistencia[$linha];
        }

        $this->aLinhasConsistencia = $this->atualizarOrdens($linhas);
        $this->calcularLinhaTotalDespesas();
        $this->calcularTotalDespeasasConsideradasLimtie();
        $this->calcularRCL();
        $this->calcularTotalDespesasRcl();
    }

    /**
     * @param $linhas
     * @return array
     */
    protected function atualizarOrdens($linhas)
    {
        foreach ($linhas as $indice => $linha) {
            $linha->ordem = $indice;
            $linha->oLinhaRelatorio->setOrdem($indice);
            $linhas[$indice] = $linha;
        }

        return $linhas;
    }

    /**
     * @param $linha
     * @param $linhas
     * @throws DBException
     */
    protected function adicionaLinhasDespesasPPP($linha, &$linhas)
    {
        $codigoRelatorio = static::CODIGO_RELATORIO;
        $codigoPeriodo = $this->getPeriodo()->getCodigo();
        $codigoInstituicao = $this->getInstituicoes();

        $sql = "
            SELECT
              o117_linha,
              o116_ordem,
              o117_instit,
              o117_valor
            FROM orcparamseqorcparamseqcoluna
              INNER JOIN configuracoes.orcparamseqorcparamseqcolunavalor
              ON o117_orcparamseqorcparamseqcoluna = o116_sequencial
            WHERE o116_codparamrel = {$codigoRelatorio}
            AND o116_codseq = {$linha}
            AND o116_periodo = {$codigoPeriodo}
            AND o117_instit IN ({$codigoInstituicao})
            ORDER BY o117_linha, o116_ordem
        ";

        $rsDespesasPPEnteFederado = db_query($sql);

        if (!$rsDespesasPPEnteFederado) {
            throw new DBException('Não foi possível buscar as linhas dos entes federados.');
        }

        $aLinhasRetornadas = array();

        while ($dados = pg_fetch_object($rsDespesasPPEnteFederado)) {
            $aLinhasRetornadas[$dados->o117_instit][$dados->o117_linha][$dados->o116_ordem] = $dados->o117_valor;
        }

        foreach ($aLinhasRetornadas as $aLinhasInstituicao) {
            foreach ($aLinhasInstituicao as $linhaRetornada) {
                $novaLinhaEnteFederado = clone $this->aLinhasConsistencia[$linha];
                $novaLinhaEnteFederado->oLinhaRelatorio = clone $this->aLinhasConsistencia[$linha]->oLinhaRelatorio;
                $novaLinhaEnteFederado->descricao = $linhaRetornada[1];
                $novaLinhaEnteFederado->oLinhaRelatorio->setDescricaoLinha($linhaRetornada[1]);
                $novaLinhaEnteFederado->exercicio_anterior = $linhaRetornada[2];
                $novaLinhaEnteFederado->exercicio_corrente = $linhaRetornada[3];
                $novaLinhaEnteFederado->exercicio_corrente_1 = $linhaRetornada[4];
                $novaLinhaEnteFederado->exercicio_corrente_2 = $linhaRetornada[5];
                $novaLinhaEnteFederado->exercicio_corrente_3 = $linhaRetornada[6];
                $novaLinhaEnteFederado->exercicio_corrente_4 = $linhaRetornada[7];
                $novaLinhaEnteFederado->exercicio_corrente_5 = $linhaRetornada[8];
                $novaLinhaEnteFederado->exercicio_corrente_6 = $linhaRetornada[9];
                $novaLinhaEnteFederado->exercicio_corrente_7 = $linhaRetornada[10];
                $novaLinhaEnteFederado->exercicio_corrente_8 = $linhaRetornada[11];
                $novaLinhaEnteFederado->exercicio_corrente_9 = $linhaRetornada[12];

                unset($novaLinhaEnteFederado->despesas_ppp);

                $linhas[] = $novaLinhaEnteFederado;

                switch ($linha) {
                    case static::LINHA_DESPESAS_DE_PPP:
                        $this->linhasAdicionadasEnteFederado++;
                        break;
                    case static::LINHA_DESPESAS_DE_PPP_ESTATAIS:
                        $this->linhasAdicionadasEstatais++;
                        break;
                }
            }
        }
    }

    /**
     *
     */
    protected function calcularLinhaTotalDespesas()
    {
        $linhaTotalDespesaAtualizada = static::LINHA_TOTAL_DAS_DESPESAS +
            $this->linhasAdicionadasEnteFederado + $this->linhasAdicionadasEstatais - 2;
        $linhaDespesasEstataisAtualizada = static::LINHA_DESPESAS_DE_PPP + $this->linhasAdicionadasEnteFederado;
        $indice = static::LINHA_DO_ENTE_FEDERADO_EXCETO_ESTATAIS_NAO_DEPENDENTES_I;
        $this->aLinhasConsistencia[$linhaTotalDespesaAtualizada]->exercicio_anterior =
            $this->aLinhasConsistencia[$indice]->exercicio_anterior +
            $this->aLinhasConsistencia[$linhaDespesasEstataisAtualizada]->exercicio_anterior;
        $this->aLinhasConsistencia[$linhaTotalDespesaAtualizada]->exercicio_corrente =
            $this->aLinhasConsistencia[$indice]->exercicio_corrente +
            $this->aLinhasConsistencia[$linhaDespesasEstataisAtualizada]->exercicio_corrente;

        for ($i = 1; $i <= 9; $i++) {
            $coluna = "exercicio_corrente_{$i}";
            $this->aLinhasConsistencia[$linhaTotalDespesaAtualizada]->$coluna =
                $this->aLinhasConsistencia[static::LINHA_DO_ENTE_FEDERADO_EXCETO_ESTATAIS_NAO_DEPENDENTES_I]->$coluna +
                $this->aLinhasConsistencia[$linhaDespesasEstataisAtualizada]->$coluna;
        }
    }

    /**
     *
     */
    protected function calcularTotalDespeasasConsideradasLimtie()
    {
        $quantidadeLinhasDinamicas = $this->linhasAdicionadasEnteFederado + $this->linhasAdicionadasEstatais - 2;
        $linhaPPPContratarAtualizada = static::LINHA_PPP_A_CONTRATAR_II + $quantidadeLinhasDinamicas;
        $calc = static::LINHA_TOTAL_DAS_DESPESAS_CONSIDERADAS_PARA_O_LIMITE_IV_I_II + $quantidadeLinhasDinamicas;
        $linhaDespesaConsideradaLimiteAtualizada = $calc;

        $indice = static::LINHA_DO_ENTE_FEDERADO_EXCETO_ESTATAIS_NAO_DEPENDENTES_I;
        $calc = $this->aLinhasConsistencia[$indice]->exercicio_anterior +
            $this->aLinhasConsistencia[$linhaPPPContratarAtualizada]->exercicio_anterior;
        $this->aLinhasConsistencia[$linhaDespesaConsideradaLimiteAtualizada]->exercicio_anterior = $calc;

        $calc = $this->aLinhasConsistencia[$indice]->exercicio_corrente +
            $this->aLinhasConsistencia[$linhaPPPContratarAtualizada]->exercicio_corrente;
        $this->aLinhasConsistencia[$linhaDespesaConsideradaLimiteAtualizada]->exercicio_corrente = $calc;

        for ($i = 1; $i <= 9; $i++) {
            $coluna = "exercicio_corrente_{$i}";
            $this->aLinhasConsistencia[$linhaDespesaConsideradaLimiteAtualizada]->$coluna =
                $this->aLinhasConsistencia[static::LINHA_DO_ENTE_FEDERADO_EXCETO_ESTATAIS_NAO_DEPENDENTES_I]->$coluna +
                $this->aLinhasConsistencia[$linhaPPPContratarAtualizada]->$coluna;
        }
    }

    /**
     *
     */
    protected function calcularTotalDespesasRcl()
    {
        $quantidadeLinhasDinamicas = $this->linhasAdicionadasEnteFederado + $this->linhasAdicionadasEstatais - 2;
        $linhaRCLAtualizada = static::LINHA_RECEITA_CORRENTE_LIQUIDA_RCL_III + $quantidadeLinhasDinamicas;
        $calc = static::LINHA_TOTAL_DAS_DESPESAS_CONSIDERADAS_PARA_O_LIMITE_IV_I_II + $quantidadeLinhasDinamicas;
        $linhaDespesaConsideradaLimiteAtualizada = $calc;
        $linhaTotalDespesasRCLAtualizada = static::LINHA_TOTAL_DAS_DESPESAS_RCL_V_IV_III + $quantidadeLinhasDinamicas;

        if (!empty($this->aLinhasConsistencia[$linhaRCLAtualizada]->exercicio_anterior)) {
            $this->aLinhasConsistencia[$linhaTotalDespesasRCLAtualizada]->exercicio_anterior =
                $this->aLinhasConsistencia[$linhaDespesaConsideradaLimiteAtualizada]->exercicio_anterior
                / $this->aLinhasConsistencia[$linhaRCLAtualizada]->exercicio_anterior;
        }

        if (!empty($this->aLinhasConsistencia[$linhaRCLAtualizada]->exercicio_corrente)) {
            $this->aLinhasConsistencia[$linhaTotalDespesasRCLAtualizada]->exercicio_corrente =
                $this->aLinhasConsistencia[$linhaDespesaConsideradaLimiteAtualizada]->exercicio_corrente /
                $this->aLinhasConsistencia[$linhaRCLAtualizada]->exercicio_corrente;
        }

        for ($i = 1; $i <= 9; $i++) {
            $coluna = "exercicio_corrente_{$i}";
            if (!empty($this->aLinhasConsistencia[$linhaRCLAtualizada]->$coluna)) {
                $this->aLinhasConsistencia[$linhaTotalDespesasRCLAtualizada]->$coluna =
                    $this->aLinhasConsistencia[$linhaDespesaConsideradaLimiteAtualizada]->$coluna /
                    $this->aLinhasConsistencia[$linhaRCLAtualizada]->$coluna;
            }
        }
    }

    /**
     *
     */
    protected function calcularRCL($linhaRCL)
    {
        $dataInicioPeriodoAnterior = clone $this->getDataInicial();
        $dataInicioPeriodoAnterior->modificarIntervalo('+ 1 day');
        $dataInicioPeriodoAnterior->modificarIntervalo('- 1 year');

        $dataInicialAnterior = clone $dataInicioPeriodoAnterior;
        $dataInicialAnterior->modificarIntervalo('- 1 year');

        $dataFinalAnterior = clone $this->getDataFinal();
        $dataFinalAnterior->modificarIntervalo('- 1 year');

        $anoCorrente = $this->getAno();
        $receitaCorrenteLiquidaAnterior = new ReceitaCorrenteLiquida($anoCorrente - 1, null, 178);
        $receitaCorrenteLiquidaCorrente = new ReceitaCorrenteLiquida($anoCorrente, null, 178);

        $calc = $this->linhasAdicionadasEnteFederado + $this->linhasAdicionadasEstatais - 2;
        $linhaReceitaCorrenteLiquidaAtualizada = static::LINHA_RECEITA_CORRENTE_LIQUIDA_RCL_III + $calc;
        $linhaReceitaCorrenteLiquida = $this->aLinhasConsistencia[$linhaReceitaCorrenteLiquidaAtualizada];
        if ($this->getAno() >= 2020) {
            $linhaReceitaCorrenteLiquida = $this->aLinhasConsistencia[$linhaRCL];
        }


        $calc = $receitaCorrenteLiquidaAnterior->somaRCLPeriodo(Periodo::SEXTO_BIMESTRE);
        $linhaReceitaCorrenteLiquida->exercicio_anterior = $calc;
        $calc = $receitaCorrenteLiquidaCorrente->somaRCLPeriodo($this->getPeriodo()->getCodigo());
        $linhaReceitaCorrenteLiquida->exercicio_corrente = $calc;
    }

    /**
     * @return mixed
     */
    public function getDadosSimplificado()
    {
        $linhas = $this->getLinhas();
        $calc = $this->linhasAdicionadasEnteFederado + $this->linhasAdicionadasEstatais - 2;
        $linha = static::LINHA_TOTAL_DAS_DESPESAS_RCL_V_IV_III + $calc;
        return $linhas[$linha];
    }
}
