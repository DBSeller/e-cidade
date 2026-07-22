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

namespace ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2020;

use ECidade\Financeiro\Contabilidade\Balancete\Receita\Mensal;
use ECidade\Financeiro\Contabilidade\Relatorio\RREO\InterfaceRelatorioLegal;
use stdClass;

/**
 * Class AnexoXII
 * @package ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2020
 */
class AnexoIII extends \RelatoriosLegaisBase implements InterfaceRelatorioLegal
{
    const CODIGO_RELATORIO = 178;

    protected $dataInicial;

    protected $dataFinal;

    protected $anoAnterior = 0;

    /**
     * linha com o valor da RCL normal
     * @var int
     */
    const LINHA_RCL = 46;

    /**
     * linha com o valor da RCL extendida com endividadamento
     * @var int
     */
    const LINHA_RCL_ENDIVIDAMENTO = 48;

    /**
     * linha com o valor de transferencias individuais
     * @var int
     */
    const LINHA_RCL_TRANS_INDIVIDUAL = 47;

    /**
     * linha com o valor de transferencias DE BANCADA
     * @var int
     */
    const LINHA_RCL_TRANS_BANCADA = 49;

    /**
     * linha com o valor da RCL extendida com pessoal
     * @var int
     */
    const LINHA_RCL_PESSOAL = 50;

    /**
     * linhas
     * @var array
     */
    protected $linhasRelatorio = [];

    protected $totalizadores = [];

    protected $linhasDedutoras = [];

    protected $linhasComBorda = [];

    /**
     * Linhas em que as somas nos totalizadores devem ser absolutos
     * @var array
     */
    protected $linhasSomarAbsoluto = [];

    /**
     * Linhas em que o valor deve ser demonstrado como absoluto
     * @var array
     */
    protected $linhasFormatoAbsoluto = [];

    /**
     * @var array Meses do relatório
     */
    protected $mesesProcessar = [];


    /**
     * As linhas 47 e 49 devem calcular os valores dos lançamentos contábeis de complementos específicos
     * @var int
     */
    protected $complementoLinha47 = 3110;
    protected $complementoLinha49 = 3120;

    /**
     * AnexoXII constructor.
     * @param $ano
     * @param null $codigorelatorio
     * @param null $codigoPeriodo
     *
     * @throws \Exception
     */
    public function __construct($ano, $codigorelatorio = null, $codigoPeriodo = null)
    {
        $codigoPeriodo = $this->getDeParaPeriodo($codigoPeriodo);
        parent::__construct($ano, self::CODIGO_RELATORIO, $codigoPeriodo);
        $this->anoAnterior = $this->iAnoUsu - 1;
        $this->dataInicial = new \DateTime($this->getDataInicial()->getDate());
        $this->dataFinal = new \DateTime($this->getDataFinal()->getDate());
        if ($this->getDataFinal()->getMes() != 12) {
            $proximoMes = $this->getDataFinal()->getMes() + 1;
            $this->dataInicial = new \DateTime("{$this->anoAnterior}-{$proximoMes}-01");
        }
        /**
         * totalizadores do relatorio:
         * Indice é a linhas e os itens do array são as linhas que ele deve somar
         */
        $this->totalizadores = [

            2 => [3, 4, 5, 6, 7],
            9 => [10, 11],
            15 => [16, 17, 18, 19, 20, 21, 22, 23],
            25 => [26, 27, 28],
            1 => [2, 8, 9, 12, 13, 14, 15, 24],
            46 => [1, 25],
            48 => [46, 47],
            50 => [48, 49],
        ];
        /**
         * Linhas que possuem bordas diferentes do padrão
         */
        $this->linhasComBorda = [
            46 => 'TB',
            47 => 'TB',
            48 => 'TB',
            49 => 'TB',
            50 => 'TB'
        ];
        /**
         * Linhas que compoem o relatório da RCL
         */
        $this->linhasRelatorio = range(1, 28);
        $this->linhasRelatorio = array_merge($this->linhasRelatorio, range(46, 50));
        /**
         * Linhas que devem ser diminuidas nos totalizadores
         */
        $this->linhasDedutoras = [25, 47, 49];
        /**
         * linhas em a apresentação dos valores devem ser absolutos
         */
        $this->linhasFormatoAbsoluto = [25];
        /**
         * linhas em  que a soma deve ser absoluta
         */
        $this->linhasSomarAbsoluto = array_merge(range(26, 28));
    }

    /**
     * Processa os dados do Relatorio
     * @return array
     * @throws \Exception
     */
    public function getDados($trazerConfiguracaoPadrao = true)
    {
        $this->aLinhasConsistencia = $this->getLinhasRelatorio();
        $meses = $this->getMesesParaProcessar();
        $dadosReceita = $this->getQuadroRCL();
        $linhasParaProcessamento = [];


        /**
         * Prepara a formatacao de cada Celula e valores inicials
         */
        foreach ($this->aLinhasConsistencia as $linha) {
            $linha->total = 0;
            $linha->previsao_atualizada = 0;
            $linha->lBold = $linha->totalizar;
            $linha->lMultiCell = true;
            $linha->borda = '';
            $linha->formato = [];
            $linha->descricao = str_repeat('   ', $linha->nivel) . $linha->descricao;
            $linha->iAlturaLinha = 5;
            foreach ($meses as $mes) {
                $linha->{$mes["nome_coluna"]} = 0;
            }
            if (!empty($this->linhasComBorda[$linha->ordem])) {
                $linha->borda = $this->linhasComBorda[$linha->ordem];
            }
            if (in_array($linha->ordem, $this->linhasFormatoAbsoluto)) {
                $linha->formato = ['abs'];
            }
        }

        $this->calculaLinhas47e49();

        $this->processarValoresManuais();

        /**
         * processamos os valores de cada linha
         */
        foreach ($dadosReceita as $receita) {
            $receitaParaCalculo = clone $receita;
            foreach ($this->aLinhasConsistencia as $linha) {
                if (!in_array($linha->ordem, $this->linhasRelatorio)) {
                    continue;
                }
                $receitaClonada = clone $receitaParaCalculo;
                $parametrosConfigurados = $linha->parametros;

                foreach ($parametrosConfigurados->contas as $contas) {
                    $match = $linha->oLinhaRelatorio->match($contas, $parametrosConfigurados->orcamento, $receita, 3);
                    if ($match->match) {
                        /**
                         * Constas de exclusão, invertemos o sinal
                         */
                        if ($match->exclusao) {
                            $receitaClonada->previsao_atualizada *= -1;
                            foreach ($meses as $mes) {
                                $receitaClonada->{$mes["nome_coluna"]} *= -1;
                            }
                        }
                        $linha->previsao_atualizada += $receitaClonada->previsao_atualizada;
                        foreach ($meses as $mes) {
                            $linha->{$mes["nome_coluna"]} += $receitaClonada->{$mes["nome_coluna"]};
                            $linha->total += $linha->{$mes["nome_coluna"]};
                        }
                    }
                }
            }
        }
        /**
         * recalcular os totais de linhas manuais
         */
        foreach ($this->aLinhasConsistencia as $linha) {
            $linha->total = 0;
            foreach ($meses as $mes) {
                $linha->total += $linha->{$mes["nome_coluna"]};
            }
        }

        $this->processaTotalizadores($this->totalizadores);
        /**
         * Ajusta as linhas que devem ser informadas
         */
        foreach ($this->aLinhasConsistencia as $linha) {
            if (!in_array($linha->ordem, $this->linhasRelatorio)) {
                continue;
            }

            $linhasParaProcessamento[$linha->ordem] = $linha;
        }
        return $linhasParaProcessamento;
    }

    /**
     * Retorna os Meses que devem ser processados os valores
     */
    public function getMesesParaProcessar()
    {
        if (!empty($this->mesesProcessar)) {
            return $this->mesesProcessar;
        }

        $this->mesesProcessar = [];
        $anoInicial = $this->dataInicial->format('Y');
        $mesInicial = $this->dataInicial->format('n');
        $anoFinal = $this->dataFinal->format('Y');
        $totalMeses = $mesInicial + 11;
        $mesCorrente = $mesInicial;
        $anoCorrente = $anoInicial;
        $mesColuna = 1;
        $listaMeses = \DBDate::getMesesExtenso();
        for ($mes = $mesInicial; $mes <= $totalMeses; $mes++) {
            if ($mesCorrente > 12) {
                $anoCorrente = $anoFinal;
                $mesCorrente = 1;
            }
            $nomeMes = mb_strtolower(\DBString::removerAcentuacao($listaMeses[$mesCorrente]));
            $this->mesesProcessar[] = [
                "mes" => $nomeMes,
                "ano" => $anoCorrente,
                "nome_coluna" => "mes_{$mesColuna}",
                "label" => \DBDate::getMesAbreviado($mesCorrente) . "/{$anoCorrente}",
                "codigo_mes" => str_pad($mesCorrente, 2, '0', STR_PAD_LEFT)
            ];
            $mesCorrente++;
            $mesColuna++;
        }

        return $this->mesesProcessar;
    }

    /**
     * Agrupa os valores da Receita em 12 colunas , 1 para cada mes do processamento
     * return array
     */
    protected function getQuadroRCL()
    {
        $meses = $this->getMesesParaProcessar();
        $receitaMensal = new Mensal();
        $receitaMensal->setDataFinal($this->dataFinal->format("Y-m-d"));
        $receitaMensal->setDataInicial($this->dataInicial->format("Y-m-d"));
        $receitaMensal->setInstitucoes(explode("-", $this->sListaInstit));
        $rsReceitas = $receitaMensal->getDados();

        $totalLinhas = pg_num_rows($rsReceitas);
        $receitas = [];
        for ($i = 0; $i < $totalLinhas; $i++) {
            $dadosReceita = \db_utils::fieldsMemory($rsReceitas, $i);
            if (empty($receitas[$dadosReceita->o57_fonte])) {
                $receita = new \stdClass();
                $receita->estrutural = $dadosReceita->o57_fonte;
                $receita->c61_codigo = $dadosReceita->o70_codigo;
                $receita->descricao = $dadosReceita->o57_descr;
                $receita->previsao_atualizada = 0;
                foreach ($meses as $mes) {
                    $receita->{$mes["nome_coluna"]} = 0;
                }
                $receitas[$dadosReceita->o57_fonte] = $receita;
            }
            $receita = $receitas[$dadosReceita->o57_fonte];
            $receita->previsao_atualizada += $dadosReceita->o70_valor + $dadosReceita->adicional;

            foreach ($meses as $mes) {
                if ($dadosReceita->o70_anousu == $mes["ano"]) {
                    $receita->{$mes["nome_coluna"]} += $dadosReceita->{$mes["mes"]};
                }
            }
        }
        return $receitas;
    }

    /**
     * @param array $linhasProcessar
     */
    public function processaTotalizadores($linhasProcessar = array())
    {

        /**
         * agrupamos todas as linhas com nivel menor que o passado
         */

        $linhasTotalizar = $linhasProcessar;
        foreach ($linhasTotalizar as $linha => $linhas) {
            foreach ($linhas as $linhaParaTotalizar) {
                $dadosDalinha = $this->aLinhasConsistencia[$linhaParaTotalizar];
                $modificador = 1;
                /**
                 * Linhas que devemos diminuir o valor
                 */
                if (in_array($linhaParaTotalizar, $this->linhasDedutoras)) {
                    $modificador = -1;
                }
                $linhaCorrente = $this->aLinhasConsistencia[$linha];
                $valorDaColunaPrevisao = ($dadosDalinha->previsao_atualizada * $modificador);
                if (in_array($linhaParaTotalizar, $this->linhasSomarAbsoluto)) {
                    $valorDaColunaPrevisao = abs($valorDaColunaPrevisao);
                }

                $linhaCorrente->previsao_atualizada += $valorDaColunaPrevisao;
                foreach (range(1, 12) as $mes) {
                    $valorMes = $dadosDalinha->{"mes_{$mes}"} * $modificador;
                    if (in_array($linhaParaTotalizar, $this->linhasSomarAbsoluto)) {
                        $valorMes = abs($valorMes);
                    }
                    $linhaCorrente->{"mes_{$mes}"} += $valorMes;
                    $linhaCorrente->total += $valorMes;
                }
            }
        }
    }

    /**
     * @return stdClass
     * @throws \Exception
     */
    public function getDadosSimplificado()
    {
        $dados = $this->getDados();
        $dadosSimplificado = new \StdClass();
        $dadosSimplificado->valor_rcl_mdf = $dados[self::LINHA_RCL]->total;
        $dadosSimplificado->valor_rcl_transferencia_individual = $dados[self::LINHA_RCL_TRANS_INDIVIDUAL]->total;
        $dadosSimplificado->valor_rcl_transferencia_bancada = $dados[self::LINHA_RCL_TRANS_BANCADA]->total;
        $dadosSimplificado->valor_rcl_endividamento = $dados[self::LINHA_RCL_ENDIVIDAMENTO]->total;
        $dadosSimplificado->valor_rcl_pessoal = $dados[self::LINHA_RCL_PESSOAL]->total;
        return $dadosSimplificado;
    }

    /**
     * @return \DateTime
     */
    public function getDataDeinicio()
    {
        return $this->dataInicial;
    }

    /**
     * Faz o de para do relatorio
     * @param $codigoPeriodo
     * @return int
     */
    protected function getDeParaPeriodo($codigoPeriodo)
    {

        $periodo = $codigoPeriodo;
        switch ($codigoPeriodo) {
            case \Periodo::PRIMEIRO_SEMESTRE:
                $periodo = \Periodo::TERCEIRO_BIMESTRE;
                break;
            case \Periodo::SEGUNDO_SEMESTRE:
            case \Periodo::TERCEIRO_QUADRIMESTRE:
                $periodo = \Periodo::SEXTO_BIMESTRE;
                break;
            case \Periodo::PRIMEIRO_QUADRIMESTRE:
                $periodo = \Periodo::SEGUNDO_BIMESTRE;
                break;
            case \Periodo::SEGUNDO_QUADRIMESTRE:
                $periodo = \Periodo::QUARTO_BIMESTRE;
                break;
        }
        return $periodo;
    }

    protected function calculaLinhas47e49()
    {
        $linha47 = $this->aLinhasConsistencia[self::LINHA_RCL_TRANS_INDIVIDUAL];
        $linha49 = $this->aLinhasConsistencia[self::LINHA_RCL_TRANS_BANCADA];
        foreach ($this->getMesesParaProcessar() as $mes) {
            $diaFinal = \DBDate::getQuantidadeDiasMes($mes['codigo_mes'], $mes['ano']);

            $dataInicio = "{$mes['ano']}-{$mes['codigo_mes']}-01";
            $dataFinal = "{$mes['ano']}-{$mes['codigo_mes']}-{$diaFinal}";

            $linha47->{$mes['nome_coluna']} = $this->getValorComplemento(
                $this->complementoLinha47,
                $dataInicio,
                $dataFinal
            );

            $linha49->{$mes['nome_coluna']} = $this->getValorComplemento(
                $this->complementoLinha49,
                $dataInicio,
                $dataFinal
            );
        }
    }
}
