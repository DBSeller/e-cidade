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

namespace ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2019;

use ECidade\Financeiro\Contabilidade\Relatorio\RREO\InterfaceRelatorioLegal;
use stdClass;

class AnexoXII extends \RelatoriosLegaisBase implements InterfaceRelatorioLegal
{
    const CODIGO_RELATORIO = 194;

    const INSCRITOS_RP_DINAMICA = 51;
    const INSCRITOS_RP_MENOS_4_SOMATORIO = 52;
    const INSCRITOS_RP_TOTALIZADORA = 53;

    const RP_CANCELADO_OU_PRESCRITO_DINAMICA = 54;
    const RP_CANCELADO_OU_PRESCRITO_MENOS_4_SOMATORIO = 55;
    const RP_CANCELADO_OU_PRESCRITO_TOTALIZADORA = 56;

    const RP_PERCENTUAL_MINIMO_DINAMICA = 57;
    const RP_PERCENTUAL_MINIMO_MENOS_5_SOMATORIO = 58;
    const RP_PERCENTUAL_MINIMO_TOTALIZADORA = 59;

    const QUADRO_RP_CANCELADO_OU_PRESCRITO = 1;
    const QUADRO_RP_PERCENTUAL_MINIMO = 2;

    protected $controleRestosAPagar = array();

    protected $linhasRestosPagar = array();

    public function __construct($iAnoUsu, $iCodigoPeriodo)
    {
        parent::__construct($iAnoUsu, self::CODIGO_RELATORIO, $iCodigoPeriodo);
    }

    public function getDados($trazerConfiguracaoPadrao = true)
    {
        parent::getDados($trazerConfiguracaoPadrao);
        $this->executarRestosPagar();
        $this->calcularRPCanceladosOuPrescritos();
        $this->calcularRPPercentual();

        $aLinhasProcessarFormula = array(49, 50);
        foreach ($aLinhasProcessarFormula as $iLinha) {
            $this->processarFormulaDaLinha($iLinha);
        }
        return $this->aLinhasConsistencia;
    }

    public function getLinhasRestosAPagar()
    {
        return $this->linhasRestosPagar;
    }

    public function getControleRestosAPagar()
    {
        return $this->controleRestosAPagar;
    }

    public function ultimoPeriodo()
    {
        return ($this->iCodigoPeriodo == 11);
    }

    public function getDadosSimplificado()
    {
        $aDados = $this->getDados();

        $oDadosSimplificado = new stdClass();
        $oDadosSimplificado->nTotalDespesasSaudeComImpostos = $aDados[48]->liq_atebim;
        $oDadosSimplificado->nPercentualDespesasSaudeComImpostos = $aDados[49]->valor;

        return $oDadosSimplificado;
    }


    /**
     * Calcula o quadro EXECUÇÃO DE RESTOS A PAGAR NÃO PROCESSADOS INSCRITOS COM DISPONIBILDADE DE CAIXA
     */
    public function executarRestosPagar(array $linhas = array(), $coluna = null)
    {
        // Calcula a Linha self::INSCRITOS_RP_DINAMICA que representa as linhas
        // Inscritos em <Exercício de Referência> e Inscritos em <Exercício de Referência - 4>
        $this->linhasRestosPagar = array();
        $linhaDinamica = $this->aLinhasConsistencia[self::INSCRITOS_RP_DINAMICA];
        $edicaoManual = $linhaDinamica->oLinhaRelatorio->getValoresColunas(
            null,
            null,
            $this->getInstituicoes(),
            $this->getAno()
        );
        $configuracaoManual = $this->totalizaConfiguracaoManualRPInscritos($edicaoManual);
        $iAnoCalcular = $this->iAnoUsu - 1;

        for ($iAnoAtual = $iAnoCalcular; $iAnoAtual >= ($this->iAnoUsu - 5); $iAnoAtual--) {
            $this->linhasRestosPagar[] = $this->calculaAnoAnterior(
                $iAnoAtual,
                $configuracaoManual,
                self::INSCRITOS_RP_DINAMICA
            );
        }

        // Calcula a Linha self::INSCRITOS_RP_MENOS_4_SOMATORIO que representa a linha
        // Inscritos em <Exercícios Anteriores ao de Referência - 4 (Somatório)>
        $linhaSomatorio = $this->clonaLinha(self::INSCRITOS_RP_MENOS_4_SOMATORIO, true);
        $edicaoManual = $linhaSomatorio->oLinhaRelatorio->getValoresColunas(
            null,
            null,
            $this->getInstituicoes(),
            $this->getAno()
        );
        $configuracaoManual = $this->totalizaConfiguracaoManualRPInscritos($edicaoManual);

        $iAnoCalcular = $this->iAnoUsu - 5;
        $iAnoReferencia = $this->iAnoUsu - 5;
        $linhaSomatorio->descricao .= " {$iAnoReferencia}";
        $linhaSomatorio->exercicio = $iAnoReferencia;

        for ($iAnoAtual = $iAnoCalcular; $iAnoAtual >= ($iAnoCalcular - 4); $iAnoAtual--) {
            $linhaTemporaria = $this->calculaAnoAnterior(
                $iAnoAtual,
                $configuracaoManual,
                self::INSCRITOS_RP_MENOS_4_SOMATORIO
            );
            $linhaSomatorio->inscritos += $linhaTemporaria->inscritos;
            $linhaSomatorio->cancelados_prescritos += $linhaTemporaria->cancelados_prescritos;
            $linhaSomatorio->pagos += $linhaTemporaria->pagos;
            $linhaSomatorio->a_pagar += $linhaTemporaria->a_pagar;
            $linhaSomatorio->parcela_limite += $linhaTemporaria->parcela_limite;
        }
        $this->linhasRestosPagar[] = $linhaSomatorio;

        // Calcula  a Linha  self::INSCRITOS_RP_TOTALIZADORA que representa a linha: Total
        $linhaTotalizadora = $this->clonaLinha(self::INSCRITOS_RP_TOTALIZADORA, true);
        foreach ($this->linhasRestosPagar as $linhaRP) {
            $linhaTotalizadora->inscritos += $linhaRP->inscritos;
            $linhaTotalizadora->cancelados_prescritos += $linhaRP->cancelados_prescritos;
            $linhaTotalizadora->pagos += $linhaRP->pagos;
            $linhaTotalizadora->a_pagar += $linhaRP->a_pagar;
            $linhaTotalizadora->parcela_limite += $linhaRP->parcela_limite;
        }

        $this->linhasRestosPagar[] = $linhaTotalizadora;
    }

    private function calculaAnoAnterior($iAnoAtual, $aConfiguracaoManual, $codigoLinha)
    {
        $oDaoRestosAPagar = new \cl_empresto();
        $sSqlRestosaPagar = $oDaoRestosAPagar->sql_query_restosPagarPorPeriodo(
            $this->iAnoUsu,
            "{$this->getDataInicial()->getAno()}-01-01",
            $this->getDataFinal()->getDate(),
            $this->getInstituicoes(),
            "*",
            "e60_anousu = " . ($iAnoAtual)
        );

        $rsRestosPagar = db_query($sSqlRestosaPagar);

        $oLinha = $this->clonaLinha($codigoLinha, true);

        $aColunasProcessar = $this->processarColunasDaLinha($oLinha);
        \RelatoriosLegaisBase::calcularValorDaLinha(
            $rsRestosPagar,
            $oLinha,
            $aColunasProcessar,
            \RelatoriosLegaisBase::TIPO_CALCULO_RESTO
        );

        $anoDaInscricao = $iAnoAtual;
        $oLinha->descricao .= " {$anoDaInscricao}";

        if (isset($aConfiguracaoManual[$anoDaInscricao])) {
            $oLinha->inscritos += $aConfiguracaoManual[$anoDaInscricao]->inscritos;
            $oLinha->cancelados_prescritos += $aConfiguracaoManual[$anoDaInscricao]->cancelados_prescritos;
            $oLinha->pagos += $aConfiguracaoManual[$anoDaInscricao]->pagos;
            $oLinha->a_pagar += $aConfiguracaoManual[$anoDaInscricao]->a_pagar;
            $oLinha->parcela_limite += $aConfiguracaoManual[$anoDaInscricao]->parcela_limite;

            unset($aConfiguracaoManual[$anoDaInscricao]);
        }

        $oLinha->inscritos = round($oLinha->inscritos, 2);
        $oLinha->cancelados_prescritos = round($oLinha->cancelados_prescritos, 2);
        $oLinha->pagos = round($oLinha->pagos, 2);
        $oLinha->a_pagar = round($oLinha->a_pagar, 2);
        $oLinha->parcela_limite = round($oLinha->parcela_limite, 2);

        return $oLinha;
    }

    protected function calcularRPCanceladosOuPrescritos()
    {
        $linha = $this->clonaLinha(self::RP_CANCELADO_OU_PRESCRITO_DINAMICA);
        $edicaoManual = $linha->oLinhaRelatorio->getValoresColunas(
            null,
            null,
            $this->getInstituicoes(),
            $this->getAno()
        );
        $configuracaoManual = $this->totalizaConfiguracaoManualRPControle($edicaoManual);

        $anoCalcular = $this->iAnoUsu;
        $anoLimite = $this->iAnoUsu - 4;
        while ($anoCalcular >= $anoLimite) {
            $linhaDinamica = $this->clonaLinha(self::RP_CANCELADO_OU_PRESCRITO_DINAMICA);
            $linhaDinamica->descricao = "{$linhaDinamica->descricao} " . ($anoCalcular - 1);
            $this->controleRestosAPagar[self::QUADRO_RP_CANCELADO_OU_PRESCRITO][] =
                $this->calcularValorManualRPControle(
                    $anoCalcular,
                    $configuracaoManual,
                    $linhaDinamica
                );
            $anoCalcular--;
        }

        // Calculando linha self::RP_CANCELADO_OU_PRESCRITO_MENOS_4_SOMATORIO referente a linha
        // Restos a Pagar Cancelados ou Prescritos em <Exercícios Anteriores ao de Referência - 4
        $linhaSomatorio = $this->clonaLinha(self::RP_CANCELADO_OU_PRESCRITO_MENOS_4_SOMATORIO);
        $anoCalcular = $this->iAnoUsu - 5;
        $anoLimite = $anoCalcular - 4;

        $edicaoManual = $linhaSomatorio->oLinhaRelatorio->getValoresColunas(
            null,
            null,
            $this->getInstituicoes(),
            $this->getAno()
        );
        $configuracaoManual = $this->totalizaConfiguracaoManualRPControle($edicaoManual);
        $linhasSomatorio = array();
        while ($anoCalcular > $anoLimite) {
            $linhaDinamica = $this->clonaLinha(self::RP_CANCELADO_OU_PRESCRITO_MENOS_4_SOMATORIO);
            $linhaDinamica->exercicio = $anoCalcular;
            $linhasSomatorio[] = $this->calcularValorManualRPControle(
                $anoCalcular,
                $configuracaoManual,
                $linhaDinamica
            );
            $anoCalcular--;
        }

        foreach ($linhasSomatorio as $linha) {
            $linhaSomatorio->saldo_inicial += $linha->saldo_inicial;
            $linhaSomatorio->despesas_custeadas_exercicio += $linha->despesas_custeadas_exercicio;
            $linhaSomatorio->saldo_final += $linha->saldo_final;
        }

        $anoSomatorio = $this->iAnoUsu - 4;
        $linhaSomatorio->descricao = "{$linhaSomatorio->descricao} " . ($anoSomatorio - 1);
        $this->controleRestosAPagar[self::QUADRO_RP_CANCELADO_OU_PRESCRITO][] = $linhaSomatorio;

        // Calculando linha self::RP_CANCELADO_OU_PRESCRITO_TOTALIZADORA referente a linha
        // Total (VIII)
        $linhaTotalizadora = $this->clonaLinha(self::RP_CANCELADO_OU_PRESCRITO_TOTALIZADORA);
        foreach ($this->controleRestosAPagar[self::QUADRO_RP_CANCELADO_OU_PRESCRITO] as $linha) {
            $linhaTotalizadora->saldo_inicial += $linha->saldo_inicial;
            $linhaTotalizadora->despesas_custeadas_exercicio += $linha->despesas_custeadas_exercicio;
            $linhaTotalizadora->saldo_final += $linha->saldo_final;
        }

        $this->controleRestosAPagar[self::QUADRO_RP_CANCELADO_OU_PRESCRITO][] = $linhaTotalizadora;
    }

    protected function calcularValorManualRPControle($ano, array $configuracaoManual, $linha)
    {
        if (array_key_exists($ano, $configuracaoManual)) {
            $configuracao = $configuracaoManual[$ano];

            $linha->saldo_inicial = $configuracao->saldo_inicial;
            $linha->despesas_custeadas_exercicio = $configuracao->despesas_custeadas_exercicio;
            $linha->saldo_final = $configuracao->saldo_final;
        }

        return $linha;
    }

    /**
     * @param array $edicaoManual
     * @return array
     */
    protected function mapeiaConfiguracaoManual(array $edicaoManual)
    {
        $configuracaoManual = array();
        // cria um array das colunas indexado por ano
        foreach ($edicaoManual as $colunas) {
            $configuracaoManual[$colunas->colunas[0]->o117_valor] = $colunas;
        }
        return $configuracaoManual;
    }

    /**
     * @param array $edicaoManual
     * @return array
     */
    private function totalizaConfiguracaoManualRPInscritos(array $edicaoManual)
    {
        $configuracaoManual = $this->mapeiaConfiguracaoManual($edicaoManual);
        $configuracaoTotalizada = array();
        foreach ($configuracaoManual as $configuracao) {
            $ano = $configuracao->colunas[0]->o117_valor;
            $linhaAuxiliar = new \stdClass();

            $linhaAuxiliar->inscritos = $configuracao->colunas[1]->o117_valor;
            $linhaAuxiliar->cancelados_prescritos = $configuracao->colunas[2]->o117_valor;
            $linhaAuxiliar->pagos = $configuracao->colunas[3]->o117_valor;
            $linhaAuxiliar->a_pagar = $configuracao->colunas[4]->o117_valor;
            $linhaAuxiliar->parcela_limite = $configuracao->colunas[5]->o117_valor;

            //Agrupa se já tiver este ano.
            if (isset($configuracaoTotalizada[$ano])) {
                $configuracaoTotalizada[$ano]->inscritos += $linhaAuxiliar->inscritos;
                $configuracaoTotalizada[$ano]->cancelados_prescritos += $linhaAuxiliar->cancelados_prescritos;
                $configuracaoTotalizada[$ano]->pagos += $linhaAuxiliar->pagos;
                $configuracaoTotalizada[$ano]->a_pagar += $linhaAuxiliar->a_pagar;
                $configuracaoTotalizada[$ano]->parcela_limite += $linhaAuxiliar->parcela_limite;
                continue;
            }
            $configuracaoTotalizada[$ano] = $linhaAuxiliar;
        }

        return $configuracaoTotalizada;
    }

    /**
     * @param $edicaoManual
     * @return array
     */
    protected function totalizaConfiguracaoManualRPControle($edicaoManual)
    {
        $configuracaoManual = $this->mapeiaConfiguracaoManual($edicaoManual);
        $configuracaoTotalizada = array();
        foreach ($configuracaoManual as $configuracao) {
            $ano = $configuracao->colunas[0]->o117_valor;
            $linhaAuxiliar = new \stdClass();

            $linhaAuxiliar->exercicio = $configuracao->colunas[0]->o117_valor;
            $linhaAuxiliar->saldo_inicial = $configuracao->colunas[1]->o117_valor;
            $linhaAuxiliar->despesas_custeadas_exercicio = $configuracao->colunas[2]->o117_valor;
            $linhaAuxiliar->saldo_final = $configuracao->colunas[3]->o117_valor;

            //Agrupa se já tiver este ano.
            if (isset($configuracaoTotalizada[$ano])) {
                $configuracaoTotalizada[$ano]->saldo_inicial += $linhaAuxiliar->saldo_inicial;
                $configuracaoTotalizada[$ano]->despesas_custeadas_exercicio +=
                    $linhaAuxiliar->despesas_custeadas_exercicio;
                $configuracaoTotalizada[$ano]->saldo_final += $linhaAuxiliar->saldo_final;
                continue;
            }
            $configuracaoTotalizada[$ano] = $linhaAuxiliar;
        }

        return $configuracaoTotalizada;
    }


    /**
     * @param $codigoLinha
     * @param bool $inscricaoRP
     * @return mixed
     */
    protected function clonaLinha($codigoLinha, $inscricaoRP = false)
    {
        $linhaClone = clone $this->aLinhasConsistencia[$codigoLinha];
        if ($inscricaoRP) {
            $linhaClone->inscritos = 0;
            $linhaClone->cancelados_prescritos = 0;
            $linhaClone->pagos = 0;
            $linhaClone->a_pagar = 0;
            $linhaClone->parcela_limite = 0;
        } else {
            $linhaClone->saldo_inicial = 0;
            $linhaClone->despesas_custeadas_exercicio = 0;
            $linhaClone->saldo_final = 0;
        }

        return $linhaClone;
    }

    protected function calcularRPPercentual()
    {
        $linha = $this->clonaLinha(self::RP_PERCENTUAL_MINIMO_DINAMICA);
        $edicaoManual = $linha->oLinhaRelatorio->getValoresColunas(
            null,
            null,
            $this->getInstituicoes(),
            $this->getAno()
        );
        $configuracaoManual = $this->totalizaConfiguracaoManualRPControle($edicaoManual);

        $anoCalcular = $this->iAnoUsu - 1;
        $anoLimite = $this->iAnoUsu - 5;
        while ($anoCalcular >= $anoLimite) {
            $linhaDinamica = $this->clonaLinha(self::RP_PERCENTUAL_MINIMO_DINAMICA);
            $linhaDinamica->descricao = "{$linhaDinamica->descricao} {$anoCalcular}";
            $this->controleRestosAPagar[self::QUADRO_RP_PERCENTUAL_MINIMO][] = $this->calcularValorManualRPControle(
                $anoCalcular,
                $configuracaoManual,
                $linhaDinamica
            );
            $anoCalcular--;
        }

        // Calculando linha self::RP_PERCENTUAL_MINIMO_MENOS_5_SOMATORIO referente a linha
        // Diferença de limite não cumprido em <Exercícios Anteriores ao de Referência - 5 (Somatório)>
        $linhaSomatorio = $this->clonaLinha(self::RP_PERCENTUAL_MINIMO_MENOS_5_SOMATORIO);
        $anoCalcular = $this->iAnoUsu - 6;
        $anoLimite = $anoCalcular - 5;

        $edicaoManual = $linhaSomatorio->oLinhaRelatorio->getValoresColunas(
            null,
            null,
            $this->getInstituicoes(),
            $this->getAno()
        );
        $configuracaoManual = $this->totalizaConfiguracaoManualRPControle($edicaoManual);
        $linhasSomatorio = array();
        while ($anoCalcular > $anoLimite) {
            $linhaDinamica = $this->clonaLinha(self::RP_PERCENTUAL_MINIMO_MENOS_5_SOMATORIO);
            $linhaDinamica->exercicio = $anoCalcular;
            $linhasSomatorio[] = $this->calcularValorManualRPControle(
                $anoCalcular,
                $configuracaoManual,
                $linhaDinamica
            );
            $anoCalcular--;
        }

        foreach ($linhasSomatorio as $linha) {
            $linhaSomatorio->saldo_inicial += $linha->saldo_inicial;
            $linhaSomatorio->despesas_custeadas_exercicio += $linha->despesas_custeadas_exercicio;
            $linhaSomatorio->saldo_final += $linha->saldo_final;
        }

        $anoSomatorio = $this->iAnoUsu - 5;
        $linhaSomatorio->descricao = "{$linhaSomatorio->descricao} {$anoSomatorio}";
        $this->controleRestosAPagar[self::QUADRO_RP_PERCENTUAL_MINIMO][] = $linhaSomatorio;

        // Calculando linha self::RP_CANCELADO_OU_PRESCRITO_TOTALIZADORA referente a linha
        // Total (IX)
        $linhaTotalizadora = $this->clonaLinha(self::RP_PERCENTUAL_MINIMO_TOTALIZADORA);
        foreach ($this->controleRestosAPagar[self::QUADRO_RP_PERCENTUAL_MINIMO] as $linha) {
            $linhaTotalizadora->saldo_inicial += $linha->saldo_inicial;
            $linhaTotalizadora->despesas_custeadas_exercicio += $linha->despesas_custeadas_exercicio;
            $linhaTotalizadora->saldo_final += $linha->saldo_final;
        }

        $this->controleRestosAPagar[self::QUADRO_RP_PERCENTUAL_MINIMO][] = $linhaTotalizadora;
    }
}
