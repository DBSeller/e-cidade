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
class AnexoIV extends \RelatoriosLegaisBase implements InterfaceRelatorioLegal
{
    /**
     * Código do relatório no E-Cidade
     * @type integer
     */
    //const CODIGO_RELATORIO = 196;
    const CODIGO_RELATORIO = 244;


    const LINHAS_IGNORAR_DESPESA = array();
    const LINHAS_IGNORAR_RECEITA = array();



    /**
     * AnexoIV  2021 constructor.
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
            throw new \BusinessException(
                _M('financeiro.contabilidade.AnexoIV.tipo_instituicao_nao_encontrado', $oStdMensagem)
            );
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

        /*
          ajuste para cancelar o processamento de algumas linhas e deixar de forma fixa:
          acontece que uma linha que tenha 4 colunas utilizar de uma linha que tenha 2 colunas
          sistema se perde e busca na linha que tem 2 colunas uma terceira e quarta coluna devido a linha que a
          utiliza possuir mais colunas que a da origem dos dados
        */
        $this->aLinhasProcessamentoManual = array(31, 70, 80, 88);

        if (empty($this->aLinhasConsistencia)) {
            parent::getDados($trazerConfiguracaoPadrao);
        }


        /**
         * processamento manual de valores e formulas
         */
        foreach ($this->aLinhasConsistencia as $iLinha => $oLinha) {
            foreach ($oLinha->colunas as $oColuna) {
                $this->ajustaValorManualPorColunaDaLinha($oColuna->o116_sequencial, $iLinha);
            }




            if (in_array($iLinha, $this->aLinhasProcessamentoManual)) {
                switch ($iLinha) {
                    case 31:
                        $iLinhaValorA = 23;
                        $iLinhaValorB = 30;
                        break;

                    case 70:
                        $iLinhaValorA = 62;
                        $iLinhaValorB = 69;
                        break;

                    case 80:
                        $iLinhaValorA = 74;
                        $iLinhaValorB = 79;
                        break;

                    case 88:
                        $iLinhaValorA = 83;
                        $iLinhaValorB = 87;
                        break;
                }
                    $this->aLinhasConsistencia[$iLinha]->dot_atual  =
                     $this->aLinhasConsistencia[$iLinhaValorA]->prev_atual -
                     $this->aLinhasConsistencia[$iLinhaValorB]->dot_atual;
                    $this->aLinhasConsistencia[$iLinha]->emp_atebim =
                      $this->aLinhasConsistencia[$iLinhaValorA]->rec_atebim -
                      $this->aLinhasConsistencia[$iLinhaValorB]->emp_atebim;
                    $this->aLinhasConsistencia[$iLinha]->liq_atebim =
                      $this->aLinhasConsistencia[$iLinhaValorA]->rec_atebim -
                      $this->aLinhasConsistencia[$iLinhaValorB]->liq_atebim;
                    $this->aLinhasConsistencia[$iLinha]->desppag    =
                      $this->aLinhasConsistencia[$iLinhaValorA]->rec_atebim -
                      $this->aLinhasConsistencia[$iLinhaValorB]->desppag;
            }
        }


        return $this->aLinhasConsistencia;
    }

    /**
     * Executa o cálculo entre receita e despesa
     */
    protected function totalizarResultadosPrevidenciarios()
    {

        $this->aLinhasConsistencia[50]->dot_ini =
         ($this->aLinhasConsistencia[33]->prev_ini - $this->aLinhasConsistencia[49]->dot_ini);
        $this->aLinhasConsistencia[50]->dot_atual =
          ($this->aLinhasConsistencia[33]->prev_atual - $this->aLinhasConsistencia[49]->dot_atual);
        $this->aLinhasConsistencia[50]->liq_atebim =
          ($this->aLinhasConsistencia[33]->rec_atebim - $this->aLinhasConsistencia[49]->liq_atebim);
        $this->aLinhasConsistencia[50]->liq_atebimexant =
          ($this->aLinhasConsistencia[33]->recbiexant - $this->aLinhasConsistencia[49]->liq_atebimexant);

        $this->aLinhasConsistencia[108]->dot_ini =
          ($this->aLinhasConsistencia[91]->prev_ini - $this->aLinhasConsistencia[107]->dot_ini);
        $this->aLinhasConsistencia[108]->dot_atual =
          ($this->aLinhasConsistencia[91]->prev_atual - $this->aLinhasConsistencia[107]->dot_atual);
        $this->aLinhasConsistencia[108]->liq_atebim =
          ($this->aLinhasConsistencia[91]->rec_atebim - $this->aLinhasConsistencia[107]->liq_atebim);
        $this->aLinhasConsistencia[108]->liq_atebimexant =
          ($this->aLinhasConsistencia[91]->recbiexant - $this->aLinhasConsistencia[107]->liq_atebimexant);
    }

    /**
     * Retorna os dados do relatórios simplificado
     * @return \stdClass
     */
    public function getDadosSimplificado()
    {
        $this->getDados();
        $iPeriodo = $this->getPeriodo()->getCodigo();

        $oDadosSimples = new \stdClass();



        $oDadosSimples->TOTAL_RECEITAS_FUNDO_CAPITALIZACAO_rec_atebim = $this->aLinhasConsistencia[23]->rec_atebim;

        $oDadosSimples->TOTAL_DESPESAS_FUNDO_CAPITALIZACAO_emp_atebim = $this->aLinhasConsistencia[30]->emp_atebim;
        $oDadosSimples->TOTAL_DESPESAS_FUNDO_CAPITALIZACAO_liq_atebim = $this->aLinhasConsistencia[30]->liq_atebim;


        $oDadosSimples->TOTAL_RECEITAS_FUNDO_REPARTICAO_rec_atebim = $this->aLinhasConsistencia[62]->rec_atebim;

        $oDadosSimples->TOTAL_DESPESAS_FUNDO_REPARTICAO_emp_atebim = $this->aLinhasConsistencia[69]->emp_atebim;
        $oDadosSimples->TOTAL_DESPESAS_FUNDO_REPARTICAO_desppag = $this->aLinhasConsistencia[69]->liq_atebim;



        if ($iPeriodo <= 10) {
            $oDadosSimples->RESULTADO_PREVIDENCIARIO_FUNDO_CAPITALIZACAO_desppag =
              $this->aLinhasConsistencia[31]->liq_atebim;
            $oDadosSimples->RESULTADO_PREVIDENCIARIO_FUNDO_REPARTICAO_desppag =
              $this->aLinhasConsistencia[70]->liq_atebim;
        } else {
        //if ($iPeriodo == 11) {

            $oDadosSimples->RESULTADO_PREVIDENCIARIO_FUNDO_CAPITALIZACAO_desppag =
              $this->aLinhasConsistencia[31]->emp_atebim;
            $oDadosSimples->RESULTADO_PREVIDENCIARIO_FUNDO_REPARTICAO_desppag =
              $this->aLinhasConsistencia[70]->emp_atebim;
        }

/*
        [TOTAL_DESPESAS_FUNDO_REPARTICAO_desppag] => 6332225.62
        [RESULTADO_PREVIDENCIARIO_FUNDO_CAPITALIZACAO_desppag] => -3044989.09
        [RESULTADO_PREVIDENCIARIO_FUNDO_REPARTICAO_desppag] => -3044989.09

        echo "<pre>";
        print_r($oDadosSimples);
        echo "</pre>";
        echo "<pre>";
        print_r($this->aLinhasConsistencia);
        echo "</pre>";
        die();

        die();
        */


        return $oDadosSimples;
    }




    /**
     * @return int
     */
    public function getExercicioAnterior()
    {
        return $this->iAnoExercicioAnterior;
    }
}
