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
use linhaRelatorioContabil;
use stdClass;

/**
 * Class AnexoX
 * @package Ecidade\Financeiro\Contabilidade\Relatorio\RREO\V2018
 */
class AnexoX extends \RelatoriosLegaisBase
{
    /**
     * @type integer
     */
    const CODIGO_RELATORIO = 188;

    private $totalLinhas = 2;
    /**
     * @param integer $iAnoUsu ano de emissao do relatorio
     * @param integer $iCodigoRelatorio codigo do relatorio
     * @param integer $iCodigoPeriodo Codigo do periodo de emissao do relatorio
     */
    function __construct($iAnoUsu, $iCodigoRelatorio, $iCodigoPeriodo) {
      parent::__construct($iAnoUsu, $iCodigoRelatorio, $iCodigoPeriodo);
    }

   /**
    * retorna os dados da classe em forma de objeto.
    * o objeto de retorno tera a seguinte forma:
    *
    * @return array - Colecao de stdClass
    */
    public function getDados() {

        $dados = array();

        for ($linha =1; $linha <= $this->totalLinhas; $linha++) {

            $linhaRelatorioContabil = new linhaRelatorioContabil($this->iCodigoRelatorio, $linha);
            $linhaRelatorioContabil->setPeriodo($this->iCodigoPeriodo);

            $instituicoes = explode(",", $this->getInstituicoes(false));
            foreach ($instituicoes as $instituicao) {

                $valoresColunas = $linhaRelatorioContabil->getValoresColunas(null,
                                                                             null,
                                                                             $instituicao,
                                                                             $this->iAnoUsu
                );

                foreach ($valoresColunas as $valor) {

                    $ano = $valor->colunas[0]->o117_valor;
                    if (!isset($dados[$linha][$ano])) {

                        $dados[$linha][$ano]                          = new stdClass();
                        $dados[$linha][$ano]->ano                     = $ano;
                        $dados[$linha][$ano]->receitasprevidenciarias = 0;
                        $dados[$linha][$ano]->despesasprevidenciarias = 0;
                        $dados[$linha][$ano]->resultadoprevidenciario = 0;
                        $dados[$linha][$ano]->saldofinanceiro         = 0;
                    }

                    $dados[$linha][$ano]->receitasprevidenciarias += $valor->colunas[1]->o117_valor;
                    $dados[$linha][$ano]->despesasprevidenciarias += $valor->colunas[2]->o117_valor;
                    $dados[$linha][$ano]->resultadoprevidenciario = $dados[$linha][$ano]->receitasprevidenciarias
                                                                  - $dados[$linha][$ano]->despesasprevidenciarias;
                }
            }

            /*
             * Ordena os Resultados no array sem perder indices
             * E Calcula Saldo Financeiro do exercicio anterior
             * com exercicio atual
             *
             */
            if (!empty($dados[$linha])) {

                ksort($dados[$linha]);
                foreach ($dados[$linha] as $ano => &$dado) {

                    $valorAnterior = 0;
                    if (isset($dados[$linha][$ano-1])) {
                        $valorAnterior = $dados[$linha][$ano-1]->saldofinanceiro;
                    }
                    $dado->saldofinanceiro = $valorAnterior + $dados[$linha][$ano]->resultadoprevidenciario;
                }
            }            
        }

        return $dados;
    }

    /**
     * Método que retorna para o anexo XVIII
     * as receitas,  despesas e   resultado para os exercicios os proximos 10 , 20 e 35 a frente
     * @return Objeto com os dados
     */
    public function getDadosSimplificado() {

        /*
         * inicia o metodo anterior, para receber os valores calculados
         */
        $dadosSimplificados = new stdClass();

        $dadosSimplificados->receitasprevidenciarias = new stdClass();
        $dadosSimplificados->receitasprevidenciarias->exercicio = 0;
        $dadosSimplificados->receitasprevidenciarias->exercicio10 = 0;
        $dadosSimplificados->receitasprevidenciarias->exercicio20 = 0;
        $dadosSimplificados->receitasprevidenciarias->exercicio35 = 0;

        $dadosSimplificados->despesasprevidenciarias = new stdClass();
        $dadosSimplificados->despesasprevidenciarias->exercicio = 0;
        $dadosSimplificados->despesasprevidenciarias->exercicio10 = 0;
        $dadosSimplificados->despesasprevidenciarias->exercicio20 = 0;
        $dadosSimplificados->despesasprevidenciarias->exercicio35 = 0;

        $dadosSimplificados->resultadoprevidenciario = new stdClass();
        $dadosSimplificados->resultadoprevidenciario->exercicio = 0;
        $dadosSimplificados->resultadoprevidenciario->exercicio10 = 0;
        $dadosSimplificados->resultadoprevidenciario->exercicio20 = 0;
        $dadosSimplificados->resultadoprevidenciario->exercicio35 = 0;

        $dados = $this->getDados();

        /*
         * Define as variaveis para o exercicio corrente
         * e 10,20,35 anos a frente do exercicio corrente
         */
        $ano   = $this->iAnoUsu-1;
        $ano10 = $ano + 10;
        $ano20 = $ano + 20;
        $ano35 = $ano + 35;

        for ($linha =1; $linha <= $this->totalLinhas; $linha++) {
            // Valida se o ano corrente está na lista
            if (isset($dados[$linha][$ano])) {

                $dadosSimplificados->receitasprevidenciarias->exercicio += $dados[$linha][$ano]->receitasprevidenciarias;
                $dadosSimplificados->despesasprevidenciarias->exercicio += $dados[$linha][$ano]->despesasprevidenciarias;
                $dadosSimplificados->resultadoprevidenciario->exercicio += $dados[$linha][$ano]->resultadoprevidenciario;
            }

            // Testa se o ano corrente +10 esta cadastrado
            if (isset($dados[$linha][$ano10])) {

                $dadosSimplificados->receitasprevidenciarias->exercicio10 += $dados[$linha][$ano10]->receitasprevidenciarias;
                $dadosSimplificados->despesasprevidenciarias->exercicio10 += $dados[$linha][$ano10]->despesasprevidenciarias;
                $dadosSimplificados->resultadoprevidenciario->exercicio10 += $dados[$linha][$ano10]->resultadoprevidenciario;
            }

            // Testa se o ano corrente +20 esta cadastrado
            if (isset($dados[$linha][$ano20])) {

                $dadosSimplificados->receitasprevidenciarias->exercicio20 += $dados[$linha][$ano20]->receitasprevidenciarias;
                $dadosSimplificados->despesasprevidenciarias->exercicio20 += $dados[$linha][$ano20]->despesasprevidenciarias;
                $dadosSimplificados->resultadoprevidenciario->exercicio20 += $dados[$linha][$ano20]->resultadoprevidenciario;
            }

            // Testa se o ano corrente +35 esta cadastrado e assume os valores calculados no metodo anterior
            if (isset($dados[$linha][$ano35])) {

                $dadosSimplificados->receitasprevidenciarias->exercicio35 += $dados[$linha][$ano35]->receitasprevidenciarias;
                $dadosSimplificados->despesasprevidenciarias->exercicio35 += $dados[$linha][$ano35]->despesasprevidenciarias;
                $dadosSimplificados->resultadoprevidenciario->exercicio35 += $dados[$linha][$ano35]->resultadoprevidenciario;
            }
        }

        return $dadosSimplificados;
    }
}
