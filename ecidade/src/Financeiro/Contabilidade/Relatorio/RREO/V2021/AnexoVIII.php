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

namespace ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2021;

//use ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2020\AnexoIV as Layout2020;

use db_utils;
use ECidade\Financeiro\Contabilidade\Relatorio\RREO\InterfaceRelatorioLegal;

//class AnexoIV extends Layout2020
class AnexoVIII extends \RelatoriosLegaisBase implements InterfaceRelatorioLegal
{
    /**
     * Código do relatório no E-Cidade
     * @type integer
     */
    //const CODIGO_RELATORIO = 195;
    const CODIGO_RELATORIO = 245;


    const LINHAS_IGNORAR_DESPESA = array();
    const LINHAS_IGNORAR_RECEITA = array();



    /**
     * AnexoVIII 2021 constructor.
     *
     * @param int $iAnoSessao
     * @param int $iCodigoPeriodo
     * @throws \BusinessException
     */
    public function __construct($iAnoSessao, $iCodigoPeriodo)
    {

        parent::__construct($iAnoSessao, static::CODIGO_RELATORIO, $iCodigoPeriodo);
        $aTiposInstituicoes = array(\Instituicao::TIPO_RPPS_EXCETO_AUTARQUIA, \Instituicao::TIPO_AUTARQUIA_RPPS);
        $this->aInstituicoesRPPS = \InstituicaoRepository::getInstituicoesPorTipo($aTiposInstituicoes);
        if (count($this->aInstituicoesRPPS) == 0) {
            $aItensTiposInstituicoes = \InstituicaoRepository::getTiposIntituicao($aTiposInstituicoes);
            $aDescricoesTiposInstituicoes = array();
            foreach ($aItensTiposInstituicoes as $itemTipoInstituicao) {
                $aDescricoesTiposInstituicoes[] = $itemTipoInstituicao->db21_codtipo
                    . ' - ' . $itemTipoInstituicao->db21_nome;
            }

            $sDescricaoTiposInstituicoes = implode("\n", $aDescricoesTiposInstituicoes);

            $oStdMensagem = (object)array('descricao' => $sDescricaoTiposInstituicoes);
            throw new \BusinessException(_M(self::MENSAGEM . 'tipo_instituicao_nao_encontrado', $oStdMensagem));
        }
        $aCodigosInstituicoes = array();
        foreach ($this->aInstituicoesRPPS as $oInstituicao) {
            $aCodigosInstituicoes[] = $oInstituicao->getCodigo();
        }
        $this->sListaInstit = implode(',', $aCodigosInstituicoes);

        $this->oDataInicialExercicioAnterior = clone $this->oDataInicial;
        $this->oDataInicialExercicioAnterior->modificarIntervalo('-1 year');
        $this->oDataFinalExercicioAnterior = clone $this->oDataFinal;
        $this->oDataFinalExercicioAnterior->modificarIntervalo('-1 year');
        $this->iAnoExercicioAnterior = ($this->iAnoUsu - 1);
    }



    public function getDados($trazerConfiguracaoPadrao = true)
    {

        if (empty($this->aLinhasConsistencia)) {
            parent::getDados($trazerConfiguracaoPadrao);
        }

        $this->getAjustaDisponibilidadeFinanceira();

        /**
         * processamento manual de valores e formulas
         *
         */

        foreach ($this->aLinhasConsistencia as $iLinha => $oLinha) {
            switch ($oLinha->ordem) {
                case "51":
                    $this->aLinhasConsistencia[$oLinha->ordem]->vlr_exigido =
                     $this->aLinhasConsistencia[19]->rec_atebim * 0.7;
                    $this->aLinhasConsistencia[$oLinha->ordem]->vlr_aplicado =
                    $this->aLinhasConsistencia[45]->emp_atebim ;
                    $this->aLinhasConsistencia[$oLinha->ordem]->vlr_consid_apos_ded =
                    $this->aLinhasConsistencia[45]->emp_atebim - $this->aLinhasConsistencia[45]->rpnp_sem_dc;
                    $this->aLinhasConsistencia[$oLinha->ordem]->perc_aplicado =
                    ($this->aLinhasConsistencia[51]->vlr_consid_apos_ded /
                     $this->aLinhasConsistencia[19]->rec_atebim) * 100;
                    break;

                case "52":
                    $this->aLinhasConsistencia[$oLinha->ordem]->vlr_exigido =
                      $this->aLinhasConsistencia[26]->rec_atebim * 0.5;
                    $this->aLinhasConsistencia[$oLinha->ordem]->vlr_aplicado =
                      $this->aLinhasConsistencia[49]->emp_atebim ;
                    $this->aLinhasConsistencia[$oLinha->ordem]->vlr_consid_apos_ded =
                      $this->aLinhasConsistencia[49]->emp_atebim - $this->aLinhasConsistencia[49]->rpnp_sem_dc;
                    $this->aLinhasConsistencia[$oLinha->ordem]->perc_aplicado =
                      ($this->aLinhasConsistencia[52]->vlr_consid_apos_ded /
                       $this->aLinhasConsistencia[26]->rec_atebim) * 100;
                    break;

                case "53":
                    $this->aLinhasConsistencia[$oLinha->ordem]->vlr_exigido =
                      $this->aLinhasConsistencia[26]->rec_atebim * 0.15;
                    $this->aLinhasConsistencia[$oLinha->ordem]->vlr_aplicado =
                      $this->aLinhasConsistencia[50]->emp_atebim ;
                    $this->aLinhasConsistencia[$oLinha->ordem]->vlr_consid_apos_ded =
                      $this->aLinhasConsistencia[50]->emp_atebim - $this->aLinhasConsistencia[50]->rpnp_sem_dc;
                    $this->aLinhasConsistencia[$oLinha->ordem]->perc_aplicado =
                      ($this->aLinhasConsistencia[53]->vlr_consid_apos_ded /
                       $this->aLinhasConsistencia[26]->rec_atebim) * 100;
                    break;

                case "54":
                    $this->aLinhasConsistencia[$oLinha->ordem]->vlr_maximo_permitido =
                        $this->aLinhasConsistencia[19]->rec_atebim * 0.1;

                    $this->aLinhasConsistencia[$oLinha->ordem]->vlr_nao_aplicado =
                        $this->aLinhasConsistencia[19]->rec_atebim - ($this->aLinhasConsistencia[46]->emp_atebim +
                                                                      $this->aLinhasConsistencia[47]->emp_atebim +
                                                                      $this->aLinhasConsistencia[48]->emp_atebim);

                    $this->aLinhasConsistencia[$oLinha->ordem]->vlr_nao_aplicado_apos_ajuste =
                      $this->aLinhasConsistencia[$oLinha->ordem]->vlr_nao_aplicado +
                       ($this->aLinhasConsistencia[46]->rpnp_sem_dc +
                        $this->aLinhasConsistencia[47]->rpnp_sem_dc +
                        $this->aLinhasConsistencia[48]->rpnp_sem_dc
                      );

                    $this->aLinhasConsistencia[$oLinha->ordem]->percentual_nao_aplicado =
                      ($this->aLinhasConsistencia[$oLinha->ordem]->vlr_nao_aplicado_apos_ajuste /
                      $this->aLinhasConsistencia[19]->rec_atebim ) * 100;
                    break;

                case "63":
                    $sPropriedadeValor = "liq_atebim";

                    if ($this->getPeriodo()->getCodigo() == 11) {
                        $sPropriedadeValor = "emp_atebim";
                    }

                    $this->aLinhasConsistencia[$oLinha->ordem]->valor =
                    $this->aLinhasConsistencia[46]->$sPropriedadeValor +
                    $this->aLinhasConsistencia[62]->$sPropriedadeValor +
                    $this->aLinhasConsistencia[56]->aplic_1q_limite_constitucional;
                    break;

                case "64": // 28
                    $this->aLinhasConsistencia[$oLinha->ordem]->valor = $this->aLinhasConsistencia[29]->rec_atebim;
                    break;

                case "65": // 29
                    $this->aLinhasConsistencia[$oLinha->ordem]->valor = $this->aLinhasConsistencia[46]->rpnp_sem_dc;
                    break;

                case "67":// 31
                    $this->aLinhasConsistencia[$oLinha->ordem]->valor =
                      $this->aLinhasConsistencia[71]->rp_cancelados + $this->aLinhasConsistencia[72]->rp_cancelados;
                    break;

                case "68": // 32
                    $this->aLinhasConsistencia[$oLinha->ordem]->valor =
                    $this->aLinhasConsistencia[63]->valor -
                    (
                        $this->aLinhasConsistencia[64]->valor +
                        $this->aLinhasConsistencia[65]->valor +
                        $this->aLinhasConsistencia[66]->valor +
                        $this->aLinhasConsistencia[67]->valor
                    );

                    break;

                case "69":
                    $this->aLinhasConsistencia[$oLinha->ordem]->vlr_exigido =
                      $this->aLinhasConsistencia[17]->rec_atebim + $this->aLinhasConsistencia[18]->rec_atebim;

                    $this->aLinhasConsistencia[$oLinha->ordem]->vlr_aplicado =
                      $this->aLinhasConsistencia[68]->valor;

                    $this->aLinhasConsistencia[$oLinha->ordem]->percent_aplicado =
                      ($this->aLinhasConsistencia[68]->valor /
                       $this->aLinhasConsistencia[$oLinha->ordem]->vlr_exigido
                      ) * 100;
                    break;

                case "102":
                    $this->aLinhasConsistencia[$oLinha->ordem]->vlr_fundeb =
                      $this->aLinhasConsistencia[102]->valor;

                    $this->aLinhasConsistencia[$oLinha->ordem]->vlr_sal_educacao =
                      $this->aLinhasConsistencia[109]->valor;
                    break;

                case "103":
                    $this->aLinhasConsistencia[$oLinha->ordem]->vlr_fundeb =
                      $this->aLinhasConsistencia[103]->valor - $this->aLinhasConsistencia[106]->valor;

                    $this->aLinhasConsistencia[$oLinha->ordem]->vlr_sal_educacao =
                      $this->aLinhasConsistencia[110]->valor - $this->aLinhasConsistencia[113]->valor;
                    break;

                case "104":
                    $this->aLinhasConsistencia[$oLinha->ordem]->vlr_fundeb =
                      $this->aLinhasConsistencia[104]->valor - $this->aLinhasConsistencia[107]->valor;

                    $this->aLinhasConsistencia[$oLinha->ordem]->vlr_sal_educacao =
                      $this->aLinhasConsistencia[111]->valor - $this->aLinhasConsistencia[114]->valor;
                    break;




                case "105":
                    $this->aLinhasConsistencia[$oLinha->ordem]->vlr_fundeb =
                      ($this->aLinhasConsistencia[102]->vlr_fundeb +
                      $this->aLinhasConsistencia[103]->vlr_fundeb) -
                      $this->aLinhasConsistencia[104]->vlr_fundeb;

                    $this->aLinhasConsistencia[$oLinha->ordem]->vlr_sal_educacao =
                    ($this->aLinhasConsistencia[102]->vlr_sal_educacao +
                    $this->aLinhasConsistencia[103]->vlr_sal_educacao) -
                    $this->aLinhasConsistencia[104]->vlr_sal_educacao;
                    break;





                case "106":
                    $this->aLinhasConsistencia[$oLinha->ordem]->vlr_fundeb =
                      $this->aLinhasConsistencia[106]->valor;

                    $this->aLinhasConsistencia[$oLinha->ordem]->vlr_sal_educacao =
                      $this->aLinhasConsistencia[113]->valor;
                    break;

                case "107":
                    $this->aLinhasConsistencia[$oLinha->ordem]->vlr_fundeb =
                      $this->aLinhasConsistencia[107]->valor;

                    $this->aLinhasConsistencia[$oLinha->ordem]->vlr_sal_educacao =
                      $this->aLinhasConsistencia[114]->valor;
                    break;



                case "108":
                    $this->aLinhasConsistencia[$oLinha->ordem]->vlr_fundeb =
                    ($this->aLinhasConsistencia[105]->vlr_fundeb +
                     $this->aLinhasConsistencia[106]->vlr_fundeb
                    ) -  $this->aLinhasConsistencia[107]->vlr_fundeb;

                    $this->aLinhasConsistencia[$oLinha->ordem]->vlr_sal_educacao =
                    ($this->aLinhasConsistencia[105]->vlr_sal_educacao +
                     $this->aLinhasConsistencia[106]->vlr_sal_educacao
                    ) -  $this->aLinhasConsistencia[107]->vlr_sal_educacao;


                    break;
            }
        }

       /*
        *  alterando o nome das linhas pois o campo o69_descr nao suporta total caracteres
        */
        $this->aLinhasConsistencia[18]->descricao .= " + (1.2) + (1.3) + (1.4) + (2.1.2) + (2.6) + (2.7))";
        $this->aLinhasConsistencia[63]->descricao .= ".1(t))";
        $this->aLinhasConsistencia[65]->descricao .= "h)";
        $this->aLinhasConsistencia[67]->descricao .= "O ENSINO = (L34.1(ac) + L34.2(ac))";

        return $this->aLinhasConsistencia;
    }


    /**
     *
     * irá ajustar os valores das linhas 52 e 53
     * será utilizado estrutural cadastrado na linha
     *                recurso cadastrado na linha
     *
     * os reduzidos cadastrados na conta que isso vai dizer se é debito ou credito
     *
     *
     *linhas
     *  106 / 113  -  debito
     *  107 / 114  -  credito
     *
     *
     * 106 e 113  a  DEBITO
     * Valores movimentados a DÉBITO nas contas, correspondentes a:
     *- transferências bancárias (coddoc 140);
     *- valores contabilizados nos docs 160,163,150,153
     *
     * 107 e 104  a CREDITO
     *   Valores movimentados a CRÉDITO nas contas, correspondentes a:
     * - transferências bancárias (coddoc 140);
     * - valores contabilizados nos docs 120,161,151
     *
     *
     *
     * codigo da coluna FUNDEB é o 106 e 107
     *
     * da coluna salario educ  113  e 114
     */

    protected function getAjustaDisponibilidadeFinanceira()
    {

        $aLinhas = array(
            106 ,
            107 ,
            113 ,
            114
        );

        foreach ($aLinhas as $iLinha) {
            $this->getValorDisponibildade($this->aLinhasConsistencia[$iLinha]);
        }
    }




    protected function getValorDisponibildade($oLinha)
    {

        $iLinha = $oLinha->ordem;

        $aDocumentos = array(140, 141, 160, 163, 150, 153, 130, 121);
        $sCreditoDebito = "c69_debito";

        if ($iLinha == 107 || $iLinha == 114) {
            $sCreditoDebito = "c69_credito";
            $aDocumentos = array(140, 141, 120, 161,151, 162, 152, 131);
        }

        $dataInicial = $this->getDataInicial()->getDate();
        $dataFinal   = $this->getDataFinal()->getDate();


        $sDocumentos = implode(", ", $aDocumentos);
        $aParametrosRecurso = $oLinha->parametros->orcamento->recurso->valor;



        $iListaRecurso = implode(", ", $aParametrosRecurso);
        $sOperadoRecurso = $oLinha->parametros->orcamento->recurso->operador;


            $sSqlValor = "

                 SELECT  coalesce(sum(c69_valor), 0) as valor
                   FROM conlancamval
                  INNER JOIN conplanoreduz ON c69_anousu = c61_anousu
                         AND {$sCreditoDebito} = c61_reduz
                  INNER JOIN conplano ON c61_anousu = c60_anousu
                         AND c61_codcon = c60_codcon
                  inner join conlancamdoc on c71_codlan = c69_codlan
                  WHERE c60_estrut like '111%'
                    AND c61_codigo $sOperadoRecurso ({$iListaRecurso})
                    and c71_coddoc in ({$sDocumentos})
                    and c69_data BETWEEN '{$dataInicial}' and '{$dataFinal}'
            ";

            $nValor = $oLinha->valor;
            $rsValor = db_query($sSqlValor);
        if (pg_numrows($rsValor) > 0) {
            $nValor = \db_utils::fieldsMemory($rsValor, 0)->valor;
        }

            $this->aLinhasConsistencia[$iLinha]->valor = $nValor;
    }



    /**
     * Retorna os dados do relatórios simplificado
     * @return \stdClass
     */
    public function getDadosSimplificado()
    {

        $aDados = $this->getLinhas();
        $oDados = new \stdClass();

        $oDados->MINIMO_ANUAL_25_ate_bim = $aDados[69]->vlr_aplicado;
        $oDados->MINIMO_ANUAL_25_min_aplicar = "25%";
        $oDados->MINIMO_ANUAL_25_percent_ate_bin = $aDados[69]->percent_aplicado;

        $oDados->MINIMO_ANUAL_70_ate_bim = $aDados[51]->vlr_consid_apos_ded;
        $oDados->MINIMO_ANUAL_70_min_aplicar = "70%";
        $oDados->MINIMO_ANUAL_70_percent_ate_bin = $aDados[51]->perc_aplicado;

        $oDados->PERC_50_ate_bim = $aDados[52]->vlr_consid_apos_ded;
        $oDados->PERC_50_min_aplicar = "50%";
        $oDados->PERC_50_percent_ate_bin = $aDados[52]->perc_aplicado;

        $oDados->MIN_15_ate_bim = $aDados[53]->vlr_consid_apos_ded;
        $oDados->MIN_15_min_aplicar = "15%";
        $oDados->MIN_15_percent_ate_bin = $aDados[53]->perc_aplicado;

        return $oDados;
    }


    /**
     * Retorna um array contendo as linhas do relatório já processadas.
     * @return \stdClass[]
     */
    public function getLinhas()
    {
        if (count($this->aLinhasConsistencia) == 0) {
            $this->getDados();
        }
        return $this->aLinhasConsistencia;
    }




    /**
     * @return int
     */
    public function getExercicioAnterior()
    {
        return $this->iAnoExercicioAnterior;
    }
}
