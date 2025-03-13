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

/**
 * Class AnexoVIII
 * @package ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2019
 */
class AnexoVIII extends \RelatoriosLegaisBase implements InterfaceRelatorioLegal
{
    /**
     * Código Padrão do Relatório
     * @var integer
     */
    const CODIGO_RELATORIO = 195;

    private $aLinhasOrcamento = array();

    private $aLinhasOrcamentoDebito = array();

    private $aLinhasProcessar = array();

    /**
     * AnexoVII constructor.
     *
     * @param int $iAnoUsu
     * @param int $iCodigoRelatorio
     * @param int $iCodigoPeriodo
     */
    public function __construct($iAnoUsu, $iCodigoPeriodo)
    {
        parent::__construct($iAnoUsu, static::CODIGO_RELATORIO, $iCodigoPeriodo);

        /**
         * Array associativo onde o indice é o código da linha e os valores em 'inclusao' e 'exclusao' são os documentos
         */
        $this->aLinhasOrcamento = array(
            // 47.1 Orçamento do Exercício
          106 => array(
            'inclusao' => array(5, 161, 151, 120, 140)
          ),
          119 => array(
            'inclusao' => array(5, 161, 151, 120, 140)
          ),
            // 48.2 Restos a Pagar
          107 => array(
            'inclusao' => array(35, 37)
          ),
          120 => array(
            'inclusao' => array(35, 37)
          ),
            // 51.1 Retenções
          111 => array(
            'inclusao' => array(160, 163)
          ),
          124 => array(
            'inclusao' => array(160, 163)
          )
        );

        $this->aLinhasOrcamentoDebito = array(
            //  47- (+) INGRESSO DE RECURSOS ATÉ O BIMESTRE
          104 => array(
            'inclusao' => array(100, 130),
            'exclusao' => array(101, 131)
          ),
          117 => array(
            'inclusao' => array(100, 130),
            'exclusao' => array(101, 131)
          )
        );

        $this->aLinhasProcessar = array(
          49,
          57,
          58,
          59,
          60,
          61,
          62,
          63,
          64,
          65,
          66,
          67,
          105,
          109,
          110,
          115,
          118,
          122,
          123,
          128
        );
    }

    /**
     * Retorna um array contendo as linhas do relatório já processadas.
     * @return \stdClass[]
     */
    public function getLinhas()
    {
        if (count($this->aLinhasConsistencia) == 0) {
            $this->processar();
        }
        return $this->aLinhasConsistencia;
    }

    /**
     * Processa a busca e cálculo necessários para emissão do relatório
     * @throws \DBException|\Exception
     */
    private function processar()
    {
        $this->getDados();

        // Caso ultimo bimestre, sao alteradas algumas colunas de formulas
        if ($this->verificaUltimoBimestre()) {
            $formula = "(L[70]->empenhado_atebim+L[77]->empenhado_atebim)-L[91]->valor";
            $this->aLinhasConsistencia[92]->colunas[0]->o116_formula = $formula;
            $this->aLinhasConsistencia[64]->colunas[0]->o116_formula = "L[56]->empenhado_atebim - L[63]->valor";

            $formula = "((L[50]->empenhado_atebim-(L[58]->valor+L[61]->valor))/L[45]->recatebim)*100";
            $this->aLinhasConsistencia[65]->colunas[0]->o116_formula = $formula;

            $formula = "((L[53]->empenhado_atebim-(L[59]->valor+L[62]->valor))/L[45]->recatebim)*100";
            $this->aLinhasConsistencia[66]->colunas[0]->o116_formula = $formula;

            $this->processarFormasDasLinhas(array(92, 64, 65, 66));
        }

        foreach ($this->aLinhasOrcamento as $linha => $aConfiguracao) {
            $this->processarOrcamentoExercicio($linha, $aConfiguracao);
        }

        foreach ($this->aLinhasOrcamentoDebito as $linha => $aConfiguracao) {
            $this->processarOrcamentoExercicioDebito($linha, $aConfiguracao);
        }

        /**
         * Ajustes dos Ingressos linha 107 - descontamos todas as receitas a rendimento e recebimentos de retenção.
         */
        $this->aLinhasConsistencia[104]->valor -= $this->aLinhasConsistencia[108]->valor;
        // $this->aLinhasConsistencia[104]->valor -= $this->aLinhasConsistencia[111]->valor;

        $this->aLinhasConsistencia[117]->valor -= $this->aLinhasConsistencia[121]->valor;
        // $this->aLinhasConsistencia[117]->valor -= $this->aLinhasConsistencia[124]->valor;

        $this->processarFormasDasLinhas($this->aLinhasProcessar);

        $this->arredondarValores();
    }

    /**
     * Processa os dados das contas bancárias no exercício
     * @param $iLinha
     * @param $aConfiguracao
     * @throws \DBException
     */
    protected function processarOrcamentoExercicio($iLinha, $aConfiguracao)
    {
        $iCodigoRecurso = '';
        // Verifica se existe recurso configurado
        if (!empty($this->aLinhasConsistencia[$iLinha]->parametros->orcamento->recurso->valor)) {
            $iCodigoRecurso = $this->aLinhasConsistencia[$iLinha]->parametros->orcamento->recurso->valor[0];
        }

        $sInclusao = implode(",", $aConfiguracao['inclusao']);
        $aContas = array();

        foreach ($this->aLinhasConsistencia[$iLinha]->parametros->contas as $oStdConta) {
            $aContas[$oStdConta->estrutural] = $oStdConta->nivel;
        }

        $oValor = new \stdClass();
        $oValor->valor = 0;

        if (!in_array($iLinha, array(111, 124))) {
            $oDaoLancamento = new \cl_conlancam();
            $sCampos = "sum(case when c71_coddoc in(".$sInclusao.") then c70_valor else c70_valor * -1 end) as valor";
            $aWhere = array(
                "c70_data between '{$this->getDataInicial()->getDate()}' and '{$this->getDataFinal()->getDate()}'",
                "c71_coddoc in (" . $sInclusao . ")",
                "(c60_estrut ilike '111%' or c60_estrut ilike '114%')",
                "c02_instit in ({$this->getInstituicoes()})"
            );
            // Caso exista recurso, adiciona na busca o recurso configurado
            if (!empty($iCodigoRecurso)) {
                $aWhere[] = "c61_codigo = {$iCodigoRecurso}";
            }

            $sWhere = implode(" and ", $aWhere);
            $sSql = $oDaoLancamento->sql_query_conta($sCampos, null, $sWhere);
            $rsValor = db_query($sSql);

            if (!$rsValor) {
                throw new \DBException("Ocorreu algum erro na consulta da linha {$iLinha}");
            }

            if (pg_num_rows($rsValor) <= 0) {
                throw new \DBException("Ocorreu algum erro ao buscar informações da linha {$iLinha}");
            }

            $oValor->valor += \db_utils::fieldsMemory($rsValor, 0)->valor;
        } else {
            $oValor->valor += $this->processarOrcamentoExercicioLinhaRetencoes($iCodigoRecurso);
        }
        //Busca configuracao de valor manual e soma na linha
        $aValorManual = $this->aLinhasConsistencia[$iLinha]->oLinhaRelatorio->getValoresColunas();

        if ($aValorManual > 0) {
            foreach ($aValorManual as $oValorManual) {
                $oValor->valor += $oValorManual->colunas[0]->o117_valor;
            }
        }

        if (in_array($iLinha, array(106, 119))) {
            $oValor->valor = $this->processarOrcamentoExercicioLinhaOrcamentoExercicio($iCodigoRecurso);
        }

        $this->aLinhasConsistencia[$iLinha]->valor = $oValor->valor;
    }

    /**
     * Processa os dados das contas bancárias no exercício
     * @param $iLinha
     * @param $aConfiguracao
     * @throws \DBException
     */
    protected function processarOrcamentoExercicioDebito($iLinha, $aConfiguracao)
    {


        $valorRetorno = 0;
        foreach ($aConfiguracao as $indice => $valor) {
            $documentos = implode(',', $valor);
            $oDaoLancamento = new \cl_conlancam();
            $campoPesquisa = $indice === 'inclusao' ? 'c69_debito' : 'c69_credito';
            $sCampos = "coalesce(sum(c70_valor), 0) as valor";
            $aWhere = array(
                "c70_data between '{$this->getDataInicial()->getDate()}' and '{$this->getDataFinal()->getDate()}'",
                "c71_coddoc in ({$documentos})",
                "(c60_estrut ilike '111%' or c60_estrut ilike '114%')",
                "c02_instit in ({$this->getInstituicoes()})",
                "{$campoPesquisa} = c61_reduz"
            );

            $iCodigoRecurso = '';
            // Verifica se existe recurso configurado
            if (!empty($this->aLinhasConsistencia[$iLinha]->parametros->orcamento->recurso->valor)) {
                $iCodigoRecurso = $this->aLinhasConsistencia[$iLinha]->parametros->orcamento->recurso->valor[0];
            }

            // Caso exista recurso, adiciona na busca o recurso configurado
            if (!empty($iCodigoRecurso)) {
                $aWhere[] = "c61_codigo = {$iCodigoRecurso}";
            }

            $sWhere = implode(" and ", $aWhere);
            $sSql = $oDaoLancamento->sql_query_conta($sCampos, null, $sWhere);
            $rsValor = db_query($sSql);

            if (!$rsValor) {
                throw new \DBException("Ocorreu algum erro na consulta da linha {$iLinha}.");
            }

            if (pg_num_rows($rsValor) <= 0) {
                throw new \DBException("Ocorreu algum erro ao buscar informações da linha {$iLinha}");
            }

            if ($indice == "inclusao") {
                $valorRetorno += \db_utils::fieldsMemory($rsValor, 0)->valor;
            } else {
                $valorRetorno -= \db_utils::fieldsMemory($rsValor, 0)->valor;
            }
        }

        $this->aLinhasConsistencia[$iLinha]->valor = $valorRetorno;
    }

    /**
     * Calcula o valor das retenções no quadro ONTROLE DA DISPONIBILIDADE FINANCEIRA
     * @param $recurso
     * @return mixed
     */
    protected function processarOrcamentoExercicioLinhaOrcamentoExercicio($recurso)
    {
        $aWhere = array(
            "c70_data between '{$this->getDataInicial()->getDate()}' and '{$this->getDataFinal()->getDate()}'",
            "c71_coddoc in (140, 161,151, 120,5,121,163,153,141,6)",
            "(c60_estrut ilike '111%' or c60_estrut ilike '114%')",
            "c02_instit in ({$this->getInstituicoes()})"
        );
        if (!empty($recurso)) {
            $aWhere[] = "c61_codigo = {$recurso}";
        }
        $oDaoLancamento = new \cl_conlancam();
        $sCampos = "coalesce( sum(case 
                                    when c61_reduz = c69_credito 
                                        then c70_valor 
                                    else c70_valor * -1 end), 0) as valor_credito";
        $sSqlCredito = $oDaoLancamento->sql_query_conta($sCampos, null, implode(' and ', $aWhere));
        $rsBuscaCredito = db_query($sSqlCredito);
        if (!$rsBuscaCredito || pg_num_rows($rsBuscaCredito) === 0) {
            throw new Exception("Ocorreu um erro ao buscar o valor a débito para o documento 140.");
        }
        return \db_utils::fieldsMemory($rsBuscaCredito, 0)->valor_credito;
    }

    /**
     * Calcula o valor da linha retenções
     * @param $recurso
     * @return mixed
     */
    protected function processarOrcamentoExercicioLinhaRetencoes($recurso)
    {
        $dataInicial = $this->getDataInicial()->getDate();
        $dataFinal = $this->getDataFinal()->getDate();

        $sqlRetencoes = "select  coalesce(sum(case when c71_coddoc in(160, 150) then c69_valor 
                                                   when c71_coddoc in(152, 162) then c69_valor * -1 end ), 0) as valor
                           from conlancamval
                          inner join conplanoreduz on c69_debito = c61_reduz
                                                  and c69_anousu = c61_anousu
                          inner join conplano     ON conplano.c60_anousu = conplanoreduz.c61_anousu
                                                  and conplano.c60_codcon = conplanoreduz.c61_codcon
 
                          inner join conplanoreduz as reduzcredito on c69_credito = reduzcredito.c61_reduz
                                                  and c69_anousu = reduzcredito.c61_anousu
                          inner join conplano as cp_credito   ON cp_credito.c60_anousu = reduzcredito.c61_anousu
                                                  and cp_credito.c60_codcon = reduzcredito.c61_codcon
                                                  
                            
                          inner join conlancam on conlancam.c70_codlan = conlancamval.c69_codlan
                          inner join conlancaminstit on c02_codlan = c70_codlan
                          inner join conlancamdoc on conlancamdoc.c71_codlan = conlancam.c70_codlan
                    where (conplano.c60_estrut like '111%' or conplano.c60_estrut like '114%')
                      and (cp_credito.c60_estrut like '21881%')
                      and c70_data between '{$dataInicial}' and '{$dataFinal}'
                      and c02_instit in ({$this->getInstituicoes()})
                      and c71_coddoc in (160,150, 152,162) ";


        $sqlRetencoes2 = "select  (coalesce(sum(case when c71_coddoc in(160, 150) then c69_valor
                     when c71_coddoc in(152, 162) then c69_valor * -1 end ), 0)) as valor
                    from conlancamval
                      inner join conplanoreduz on c69_credito = c61_reduz
                                                  and c69_anousu = c61_anousu
                      inner join conplano     ON conplano.c60_anousu = conplanoreduz.c61_anousu
                                                 and conplano.c60_codcon = conplanoreduz.c61_codcon
                    
                      inner join conplanoreduz as reduzdebito on c69_debito = reduzdebito.c61_reduz
                                                                  and c69_anousu = reduzdebito.c61_anousu
                      inner join conplano as cp_debito ON cp_debito.c60_anousu = reduzdebito.c61_anousu
                                                      and cp_debito.c60_codcon = reduzdebito.c61_codcon
                    
                      inner join conlancam on conlancam.c70_codlan = conlancamval.c69_codlan
                      inner join conlancaminstit on c02_codlan = c70_codlan
                      inner join conlancamdoc on conlancamdoc.c71_codlan = conlancam.c70_codlan
                    where (conplano.c60_estrut like '111%' or conplano.c60_estrut like '114%')
                      and (cp_debito.c60_estrut like '21881%')
                      and c70_data between '{$dataInicial}' and '{$dataFinal}'
                      and c02_instit in ({$this->getInstituicoes()})
                      and c71_coddoc in (160,150, 152,162)";


        if (!empty($recurso)) {
            $sqlRetencoes .= " and conplanoreduz.c61_codigo = {$recurso}";
            $sqlRetencoes2 .= " and conplanoreduz.c61_codigo = {$recurso}";
        }

        $sqlRetencoesUnion =  $sqlRetencoes." union all " . $sqlRetencoes2;

        $sqlRetencoes =  "select sum(valor) as valor from  ($sqlRetencoesUnion) as x";

        $rsOrcamentoRetencoes = db_query($sqlRetencoes);
        if (!$rsOrcamentoRetencoes) {
            throw new Exception("Ocorreu um erro ao buscar o valor a débito para o documento 140.");
        }
        return \db_utils::fieldsMemory($rsOrcamentoRetencoes, 0)->valor;
    }


    /**
     * Retorna os dados para Demonstrativo Simplificado
     * @return \stdClass
     */
    public function getDadosSimplificado()
    {
        $aDados = $this->getLinhas();
        $oDados = new \stdClass();
        $oDados->nMinimoAtualMDEAteBimestre = $aDados[92]->valor;
        $oDados->nPercentualAplicadoComMDE = $aDados[93]->valor;

        $resultadoLiquidado = $aDados[50]->liquidado_atebim - ($aDados[58]->valor + $aDados[61]->valor);
        $oDados->nMinimoAtualFUNDEBAteBimestre = $resultadoLiquidado;

        // Caso ultimo bimestre altera a coluna da formula
        if ($this->verificaUltimoBimestre()) {
            $resultadoEmpenhado = $aDados[50]->empenhado_atebim - ($aDados[58]->valor + $aDados[61]->valor);
            $oDados->nMinimoAtualFUNDEBAteBimestre = $resultadoEmpenhado;
        }
        $oDados->nPercentualAplicadoComFUNDEB = $aDados[65]->valor;
        return $oDados;
    }

    /**
     * verifica se é 6 bimestre retorna true se sim, caso contrario retorna false
     * @return bool
     */
    public function verificaUltimoBimestre()
    {
        if ($this->oPeriodo->getCodigo() == 11) {
            return true;
        }
        return false;
    }
}
