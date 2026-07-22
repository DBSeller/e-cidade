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

namespace ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2021\Layout;

use \ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2021\AnexoIV as Relatorio;
use \ECidade\Financeiro\Contabilidade\Relatorio\DemonstrativoFiscal;

class AnexoIV
{
    const PREVIDENCIARIO_RECEITAS_CORRENTES = 1;
    const PREVIDENCIARIO_TOTAL_RECEITAS = 23;

    const PREVIDENCIARIO_ADMNISTRACAO = 24;
    const PREVIDENCIARIO_TOTAL_DESPESAS = 30;

    const RESULTADO_PREVIDENCIÁRIO_FUNDO_EM_CAPITALIZACAO = 31;

    const RECURSOS_RPPS_ARRECADADOS_EM_EXERCICIO_ANTERIOR = 32;

    const RESERVA_ORCAMENTARIA_RPPS = 33;

    const APORTES_RECURSOS_PARA_FUNDO_CAPITALIZACAO_RPPS_INI = 34;
    const APORTES_RECURSOS_PARA_FUNDO_CAPITALIZACAO_RPPS_FIM = 37;

    const BENS_E_DIREITOS_DO_RPPS_FUNDO_EM_CAPITALIZACAO_INI = 38;
    const BENS_E_DIREITOS_DO_RPPS_FUNDO_EM_CAPITALIZACAO_FIM = 40;

    const RECEITAS_PREVIDENCIARIAS_RPPS_FUNDO_REPARTICAO = 41;
    const TOTAL_RECEITAS_PREVIDENCIARIAS_RPPS_FUNDO_REPARTICAO = 62;

    const DESPESAS_PREVIDENCIARIAS_RPPS_FUNDO_EM_REPARTICAO = 63;
    const TOTAL_DESPESAS_PREVIDENCIARIAS_RPPS_FUNDO_EM_REPARTICAO = 69;

    const RESULTADO_PREVIDENCIARIO_FUNDO_EM_REPARTICAO_XI = 70;

    const APORTES_RECURSOS_PARA_FUNDO_REPARTICAO_RPPS_INI = 71;
    const APORTES_RECURSOS_PARA_FUNDO_REPARTICAO_RPPS_FIM = 72;

    const RECEITAS_DA_ADMINISTRACAO_RPPS_INI = 73;
    const RECEITAS_DA_ADMINISTRACAO_RPPS_FIM = 74;

    const DESPESAS_DA_ADMINISTRACAO_RPPS_INI = 75;
    const DESPESAS_DA_ADMINISTRACAO_RPPS_FIM = 79;

    const RESULTADO_DA_ADMINISTRACAO_RPPS_XVI = 80;

    const RECEITAS_PREVIDENCIARIAS_BENEFICIOS_MANTIDOS_PELO_TESOURO_INI = 81;
    const RECEITAS_PREVIDENCIARIAS_BENEFICIOS_MANTIDOS_PELO_TESOURO_FIM = 83;

    const DESPESAS_PREVIDENCIARIAS_BENEFICIOS_MANTIDOS_PELO_TESOURO_INI = 84;
    const DESPESAS_PREVIDENCIARIAS_BENEFICIOS_MANTIDOS_PELO_TESOURO_FIM = 87;

    const RESULTADO_DOS_BENEFICIOS_MANTIDOS_PELO_TESOURO_XIX = 88;



    /**
     * @var \PDFDocument
     */
    protected $oPdf;

    /**
     * @var \ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2017\AnexoIV
     */
    protected $oRelatorio;

    /**
     * @var \stdClass[]
     */
    protected $aLinhas;

    /**
     * @var array
     */
    protected $sessao = null;

    /**
     * @throws \Exception
     */
    public function emitir()
    {
        if (empty($this->oRelatorio)) {
            throw new \Exception('Não foi informado ao AnexoIV para impressão.');
        }

        $this->aLinhas = $this->oRelatorio->getDados();
        $this->oPdf = new \PDFDocument(\PDFDocument::PRINT_LANDSCAPE);
        $this->addHeaders();

        $nLargura = ($this->oPdf->getAvailWidth() / 2);
        $this->oPdf->cell($nLargura, 4, 'RREO - Anexo 4 (LRF, Art. 53, inciso II)', 0, 0);
        $this->oPdf->cell($nLargura, 4, 'Em Reais', 0, 1, \PDFDocument::ALIGN_RIGHT);


        foreach ($this->aLinhas as $nroLinha => $oStdLinha) {
            if (!empty($this->sessao) && !empty($this->sessao['DB_DEBUG']) && $this->sessao['DB_DEBUG'] === true) {
                if (!empty($oStdLinha) && !empty($oStdLinha->descricao)) {
                    $oStdLinha->descricao = "{$nroLinha}) " . $oStdLinha->descricao;
                }
            }


            /*
             * Inicio da impressão do relatório pelos quadros referente ao PLANO PREVIDENCIÁRIO
             */
            if (\Check::between(
                $oStdLinha->ordem,
                static::PREVIDENCIARIO_RECEITAS_CORRENTES,
                static::PREVIDENCIARIO_TOTAL_RECEITAS
            )) {
                if ($oStdLinha->ordem == static::PREVIDENCIARIO_RECEITAS_CORRENTES
                ) {
                    $this->cabecalhoReceita('REGIME PRÓPRIO DE PREVIDÊNCIA DOS SERVIDORES - RPPS');
                }
                $this->imprimeReceita($oStdLinha);
            }

            /**
             * DESPESAS
             */

            if (\Check::between(
                $oStdLinha->ordem,
                static::PREVIDENCIARIO_ADMNISTRACAO,
                static::PREVIDENCIARIO_TOTAL_DESPESAS
            )
            ) {
                if ($oStdLinha->ordem == static::PREVIDENCIARIO_ADMNISTRACAO) {
                    $this->oPdf->addPage();
                    $this->cabecalhoDespesa();
                }
                $this->imprimeDespesa($oStdLinha);
            }

            // LINHA 31
            if ($oStdLinha->ordem == static::RESULTADO_PREVIDENCIÁRIO_FUNDO_EM_CAPITALIZACAO) {
                $this->imprimeResultPrevFundoCap($oStdLinha);
            }

            // LINHA 32
            if ($oStdLinha->ordem == static::RECURSOS_RPPS_ARRECADADOS_EM_EXERCICIO_ANTERIOR) {
                $this->imprimeRecRPPSArrecExercAnt($oStdLinha);
            }

            // LINHA 33
            if ($oStdLinha->ordem == static::RESERVA_ORCAMENTARIA_RPPS) {
                $this->imprimeReservaOrcamentariaRpps($oStdLinha);
            }


            /**
             * LINHA 34 a 37
             */

            if (\Check::between(
                $oStdLinha->ordem,
                static::APORTES_RECURSOS_PARA_FUNDO_CAPITALIZACAO_RPPS_INI,
                static::APORTES_RECURSOS_PARA_FUNDO_CAPITALIZACAO_RPPS_FIM
            )
            ) {
                if ($oStdLinha->ordem == static::APORTES_RECURSOS_PARA_FUNDO_CAPITALIZACAO_RPPS_INI) {
                    $this->cabecalhoAportesRecFundoCapRpps();
                }
                $this->imprimeAportesRecFundoCapRpps($oStdLinha);
            }

            /**
             * BENS_E_DIREITOS_DO_RPPS_FUNDO_EM_CAPITALIZACAO_INI
             * LINHAS 38 a 40
            */
            if (\Check::between(
                $oStdLinha->ordem,
                static::BENS_E_DIREITOS_DO_RPPS_FUNDO_EM_CAPITALIZACAO_INI,
                static::BENS_E_DIREITOS_DO_RPPS_FUNDO_EM_CAPITALIZACAO_FIM
            )
            ) {
                if ($oStdLinha->ordem == static::BENS_E_DIREITOS_DO_RPPS_FUNDO_EM_CAPITALIZACAO_INI) {
                    $this->cabecalhoBensDireitosRppsFundoCap();
                }
                $this->imprimeBensDireitosRppsFundoCap($oStdLinha);
            }

            /**
             * LINHAS 41 a 62
             * RECEITAS_PREVIDENCIARIAS_RPPS_FUNDO_REPARTICAO = 41;
             * TOTAL_RECEITAS_PREVIDENCIARIAS_RPPS_FUNDO_REPARTICAO = 62;
             */
            if (\Check::between(
                $oStdLinha->ordem,
                static::RECEITAS_PREVIDENCIARIAS_RPPS_FUNDO_REPARTICAO,
                static::TOTAL_RECEITAS_PREVIDENCIARIAS_RPPS_FUNDO_REPARTICAO
            )) {
                if ($oStdLinha->ordem == static::RECEITAS_PREVIDENCIARIAS_RPPS_FUNDO_REPARTICAO
                ) {
                    $this->oPdf->addPage();
                    $this->cabecalhoReceita(
                        'FUNDO EM REPARTIÇÃO (PLANO FINANCEIRO)',
                        "RECEITAS PREVIDENCIÁRIAS - RPPS (FUNDO EM REPARTIÇÃO)",
                        false
                    );
                }
                $this->imprimeReceita($oStdLinha);
            }

            /**
             * DESPESAS_PREVIDENCIARIAS_RPPS_FUNDO_EM_REPARTICAO = 63;
             * TOTAL_DESPESAS_PREVIDENCIARIAS_RPPS_FUNDO_EM_REPARTICAO = 69;
             */

            if (\Check::between(
                $oStdLinha->ordem,
                static::DESPESAS_PREVIDENCIARIAS_RPPS_FUNDO_EM_REPARTICAO,
                static::TOTAL_DESPESAS_PREVIDENCIARIAS_RPPS_FUNDO_EM_REPARTICAO
            )
            ) {
                if ($oStdLinha->ordem == static::DESPESAS_PREVIDENCIARIAS_RPPS_FUNDO_EM_REPARTICAO) {
                    $this->oPdf->addPage();
                    $this->cabecalhoDespesa("DESPESAS PREVIDENCIÁRIAS - RPPS (FUNDO EM REPARTIÇÃO)");
                }
                $this->imprimeDespesa($oStdLinha);
            }

            // LINHA 70
            if ($oStdLinha->ordem == static::RESULTADO_PREVIDENCIARIO_FUNDO_EM_REPARTICAO_XI) {
                $this->imprimeResultPrevFundoCap($oStdLinha);
            }

            /**
             *
             * APORTES_RECURSOS_PARA_FUNDO_REPARTICAO_RPPS_INI = 71;
             * APORTES_RECURSOS_PARA_FUNDO_REPARTICAO_RPPS_FIM = 72;
             * LINHAS 71 e 72
             */

            if (\Check::between(
                $oStdLinha->ordem,
                static::APORTES_RECURSOS_PARA_FUNDO_REPARTICAO_RPPS_INI,
                static::APORTES_RECURSOS_PARA_FUNDO_REPARTICAO_RPPS_FIM
            )
            ) {
                if ($oStdLinha->ordem == static::APORTES_RECURSOS_PARA_FUNDO_REPARTICAO_RPPS_INI) {
                    $this->cabecalhoAportesRecFundoCapRpps("APORTES DE RECURSOS PARA O FUNDO EM REPARTIÇÃO DO RPPS");
                }
                $this->imprimeAportesRecFundoCapRpps($oStdLinha);
            }


            /**
             * LINHAS 73 a 74
             * RECEITAS_DA_ADMINISTRACAO_RPPS_INI = 73;
             * RECEITAS_DA_ADMINISTRACAO_RPPS_FIM = 74;
             */
            if (\Check::between(
                $oStdLinha->ordem,
                static::RECEITAS_DA_ADMINISTRACAO_RPPS_INI,
                static::RECEITAS_DA_ADMINISTRACAO_RPPS_FIM
            )) {
                if ($oStdLinha->ordem == static::RECEITAS_DA_ADMINISTRACAO_RPPS_INI
                ) {
                    $this->espaco(4);
                    $this->cabecalhoReceita(
                        'ADMINISTRAÇÃO DO REGIME PRÓPRIO DE PREVIDÊNCIA DOS SERVIDORES - RPPS',
                        "RECEITAS DA ADMINISTRAÇÃO - RPPS",
                        false
                    );
                }
                $this->imprimeReceita($oStdLinha);
            }


            /**
             * DESPESAS_DA_ADMINISTRACAO_RPPS_INI = 75;
             * DESPESAS_DA_ADMINISTRACAO_RPPS_FIM = 79;
             */

            if (\Check::between(
                $oStdLinha->ordem,
                static::DESPESAS_DA_ADMINISTRACAO_RPPS_INI,
                static::DESPESAS_DA_ADMINISTRACAO_RPPS_FIM
            )
            ) {
                if ($oStdLinha->ordem == static::DESPESAS_DA_ADMINISTRACAO_RPPS_INI) {
                    $this->espaco(4);
                    $this->cabecalhoDespesa("DESPESAS DA ADMINISTRAÇÃO - RPPS");
                }
                $this->imprimeDespesa($oStdLinha);
            }


            // LINHA 80 RESULTADO_DA_ADMINISTRACAO_RPPS_XVI
            if ($oStdLinha->ordem == static::RESULTADO_DA_ADMINISTRACAO_RPPS_XVI) {
                $this->imprimeResultPrevFundoCap($oStdLinha);
            }


            /**
             * LINHAS 81 a 83
             * RECEITAS_PREVIDENCIARIAS_BENEFICIOS_MANTIDOS_PELO_TESOURO_INI = 81;
             * RECEITAS_PREVIDENCIARIAS_BENEFICIOS_MANTIDOS_PELO_TESOURO_FIM = 83;
             */
            if (\Check::between(
                $oStdLinha->ordem,
                static::RECEITAS_PREVIDENCIARIAS_BENEFICIOS_MANTIDOS_PELO_TESOURO_INI,
                static::RECEITAS_PREVIDENCIARIAS_BENEFICIOS_MANTIDOS_PELO_TESOURO_FIM
            )) {
                if ($oStdLinha->ordem == static::RECEITAS_PREVIDENCIARIAS_BENEFICIOS_MANTIDOS_PELO_TESOURO_INI
                ) {
                    $this->oPdf->addPage();
                    $this->cabecalhoReceita(
                        'BENEFÍCIOS PREVIDENCIÁRIOS MANTIDOS PELO TESOURO',
                        "RECEITAS PREVIDENCIÁRIAS (BENEFÍCIOS MANTIDOS PELO TESOURO)",
                        false
                    );
                }
                $this->imprimeReceita($oStdLinha);
            }

            /**
             * DESPESAS_PREVIDENCIARIAS_BENEFICIOS_MANTIDOS_PELO_TESOURO_INI
             * DESPESAS_PREVIDENCIARIAS_BENEFICIOS_MANTIDOS_PELO_TESOURO_FIM
             * LINHAS 84 a 87
             *
             */

            if (\Check::between(
                $oStdLinha->ordem,
                static::DESPESAS_PREVIDENCIARIAS_BENEFICIOS_MANTIDOS_PELO_TESOURO_INI,
                static::DESPESAS_PREVIDENCIARIAS_BENEFICIOS_MANTIDOS_PELO_TESOURO_FIM
            )
            ) {
                if ($oStdLinha->ordem == static::DESPESAS_PREVIDENCIARIAS_BENEFICIOS_MANTIDOS_PELO_TESOURO_INI) {
                    $this->espaco(4);
                    $this->cabecalhoDespesa("DESPESAS PREVIDENCIÁRIAS (BENEFÍCIOS MANTIDOS PELO TESOURO)");
                }
                $this->imprimeDespesa($oStdLinha);
            }


            // LINHA 88 RESULTADO_DOS_BENEFICIOS_MANTIDOS_PELO_TESOURO_XIX
            if ($oStdLinha->ordem == static::RESULTADO_DOS_BENEFICIOS_MANTIDOS_PELO_TESOURO_XIX) {
                $this->imprimeResultPrevFundoCap($oStdLinha);
            }
        }

        $this->espaco(4);


        $this->oPdf->Cell($this->oPdf->getAvailWidth(), 4, '', 'T', 1);
        $this->espaco(4);
        $this->legendas();

        $this->oPdf->ln($this->oPdf->getAvailHeight() - 10);
        $oDaoAssinatura = new \cl_assinatura();
        assinaturas($this->oPdf, $oDaoAssinatura, 'LRF');

        $this->oPdf->showPDF("RREO_Anexo_IV_DemonstrativoRPPS_v2021_" . time());
    }

     /**
     * Linhas e Valores
     * BENS E DIREITOS DO RPPS (FUNDO EM CAPITALIZAÇÃO)
     */
    protected function imprimeBensDireitosRppsFundoCap(\stdClass $oStdLinha)
    {
        $this->oPdf->setBold(false);
        $iLargura = $this->oPdf->getAvailWidth();
        $this->oPdf->cell(
            $iLargura * (0.4),
            4,
            \relatorioContabil::getIdentacao($oStdLinha->nivel) . $oStdLinha->descricao,
            "",
            0,
            'L',
            0
        );

        $this->oPdf->cell($iLargura * 0.1 + 30, 4, db_formatar($oStdLinha->vlrexatual, "f"), "", 0, "R", 0);
        $this->oPdf->cell($iLargura * 0.1 + 81, 4, "", "", 1, "R", 0);
    }


    /**
     * CABECALHO
     * BENS E DIREITOS DO RPPS (FUNDO EM CAPITALIZAÇÃO)
     */
    protected function cabecalhoBensDireitosRppsFundoCap()
    {

        $this->espaco(4);
        $this->oPdf->setBold(true);
        $iLargura = $this->oPdf->getAvailWidth();

        $this->oPdf->cell(
            $iLargura * (0.4),
            4,
            "BENS E DIREITOS DO RPPS (FUNDO EM CAPITALIZAÇÃO)",
            "TBR",
            0,
            'L',
            1
        );

        $this->oPdf->cell($iLargura * 0.1 + 30, 4, "SALDO ATUAL", "TB", 0, "R", 1);
        $this->oPdf->cell($iLargura * 0.1 + 81, 4, "", "TB", 1, "R", 1);

        $this->oPdf->setBold(false);
    }


    /**
     * Linhas e Valores
     * APORTES DE RECURSOS PARA O FUNDO EM CAPITALIZAÇÃO DO RPPS
     */
    protected function imprimeAportesRecFundoCapRpps(\stdClass $oStdLinha)
    {
        $this->oPdf->setBold(false);
        $iLargura = $this->oPdf->getAvailWidth();
        $this->oPdf->cell(
            $iLargura * (0.4),
            4,
            \relatorioContabil::getIdentacao($oStdLinha->nivel) . $oStdLinha->descricao,
            "",
            0,
            'L',
            0
        );

        $this->oPdf->cell($iLargura * 0.1 + 30, 4, db_formatar($oStdLinha->valor, "f"), "", 0, "R", 0);
        $this->oPdf->cell($iLargura * 0.1 + 81, 4, "", "", 1, "R", 0);
    }

    /**
     * CABECALHO
     * APORTES DE RECURSOS PARA O FUNDO EM CAPITALIZAÇÃO DO RPPS
     */
    protected function cabecalhoAportesRecFundoCapRpps($sTitulo = null)
    {

        $sTituloColuna = "APORTES DE RECURSOS PARA O FUNDO EM CAPITALIZAÇÃO DO RPPS";
        if (!empty($sTitulo)) {
            $sTituloColuna = $sTitulo ;
        }

        $this->espaco(4);
        $this->oPdf->setBold(true);
        $iLargura = $this->oPdf->getAvailWidth();

        $this->oPdf->cell(
            $iLargura * (0.4),
            4,
            $sTituloColuna,
            "TBR",
            0,
            'L',
            1
        );

        $this->oPdf->cell($iLargura * 0.1 + 30, 4, "APORTES REALIZADOS ", "TB", 0, "R", 1);
        $this->oPdf->cell($iLargura * 0.1 + 81, 4, "", "TB", 1, "R", 1);

        $this->oPdf->setBold(false);
    }

    protected function imprimeReservaOrcamentariaRpps(\stdClass $oStdLinha)
    {

        //RESERVA_ORCAMENTARIA_RPPS
        $this->espaco(4);
        $this->oPdf->setBold(true);
        $iLargura = $this->oPdf->getAvailWidth();

        $this->oPdf->cell(
            $iLargura * (0.4),
            4,
            "RESERVA ORÇAMENTÁRIA DO RPPS",
            "TBR",
            0,
            'L',
            1
        );

        $this->oPdf->cell($iLargura * 0.1 + 30, 4, "PREVISÃO ORÇAMENTÁRIA ", "TB", 0, "R", 1);
        $this->oPdf->cell($iLargura * 0.1 + 81, 4, "", "TB", 1, "R", 1);

        $this->oPdf->setBold(false);

        $this->oPdf->cell(
            $iLargura * (0.4),
            4,
            \relatorioContabil::getIdentacao($oStdLinha->nivel) . $oStdLinha->descricao,
            "",
            0,
            'L',
            1
        );

        $this->oPdf->cell($iLargura * 0.1 + 30, 4, db_formatar($oStdLinha->valor, "f"), "", 0, "R", 1);
        $this->oPdf->cell($iLargura * 0.1 + 81, 4, "", "", 1, "R", 1);
    }

    protected function imprimeRecRPPSArrecExercAnt(\stdClass $oStdLinha)
    {

        //RECURSOS_RPPS_ARRECADADOS_EM_EXERCICIO_ANTERIOR
        $this->espaco(4);
        $this->oPdf->setBold(true);
        $iLargura = $this->oPdf->getAvailWidth();

        $this->oPdf->cell(
            $iLargura * (0.4),
            4,
            "RECURSOS RPPS ARRECADADOS EM EXERCÍCIOS ANTERIORES",
            "TBR",
            0,
            'L',
            1
        );

        $this->oPdf->cell($iLargura * 0.1 + 30, 4, "PREVISÃO ORÇAMENTÁRIA ", "TB", 0, "R", 1);
        $this->oPdf->cell($iLargura * 0.1 + 81, 4, "", "TB", 1, "R", 1);

        $this->oPdf->setBold(false);

        $this->oPdf->cell(
            $iLargura * (0.4),
            4,
            \relatorioContabil::getIdentacao($oStdLinha->nivel) . $oStdLinha->descricao,
            "",
            0,
            'L',
            1
        );

        $this->oPdf->cell($iLargura * 0.1 + 30, 4, db_formatar($oStdLinha->valor, "f"), "", 0, "R", 1);
        $this->oPdf->cell($iLargura * 0.1 + 81, 4, "", "", 1, "R", 1);
    }

    protected function imprimeResultPrevFundoCap(\stdClass $oStdLinha)
    {
        $this->espaco(4);
        $this->oPdf->setBold(true);
       //RESULTADO_PREVIDENCIÁRIO_FUNDO_EM_CAPITALIZACAO
       /*
       [dot_atual] => 0
       [emp_atebim] => 0
       [liq_atebim] => 0
       [desppag] => 0
       */
        $iLargura = $this->oPdf->getAvailWidth();
       //$lTransparencia = $this->transparente($oStdLinha->ordem);

        $nDotacaoAtual = db_formatar($oStdLinha->dot_atual, "f");
        $nEmpAteBin    = db_formatar($oStdLinha->emp_atebim, "f");
        $nLiqAteBin    = db_formatar($oStdLinha->liq_atebim, "f");
        $nDespPagas    = db_formatar($oStdLinha->desppag, "f");

        $this->oPdf->cell(
            $iLargura * (0.4),
            4,
            \relatorioContabil::getIdentacao($oStdLinha->nivel) . $oStdLinha->descricao,
            "TBR",
            "",
            'L',
            1
        );

        $this->oPdf->cell($iLargura * 0.1 + 7, 4, $nDotacaoAtual, "TBR", 0, "R", 1);
        $this->oPdf->cell($iLargura * 0.1 + 7, 4, $nEmpAteBin, "TBR", 0, "R", 1);
        $this->oPdf->cell($iLargura * 0.1 + 7, 4, $nLiqAteBin, "TBR", 0, "R", 1);
        $this->oPdf->cell($iLargura * 0.1 + 7, 4, $nDespPagas, "TBL", 0, "R", 1);
        $this->oPdf->cell($iLargura * 0.1, 4, "", "TB", 1, 0, 1);


        $this->oPdf->setBold(false);
    }


    /**
     * IMprime valores das despesas
     * @param \stdClass $oStdLinha
     */
    protected function imprimeDespesa(\stdClass $oStdLinha)
    {

        if ($oStdLinha->totalizar) {
            $this->oPdf->setBold(true);
        }
        $sBorda = in_array(
            $oStdLinha->ordem,
            array( static::PREVIDENCIARIO_TOTAL_DESPESAS,
                   static::TOTAL_DESPESAS_PREVIDENCIARIAS_RPPS_FUNDO_EM_REPARTICAO)
        ) ? "TB" : '';

        $lUltimoPeriodo = $this->oRelatorio->getPeriodo()->getCodigo() == 11;

        $lQuebra = 1;
        $iLarguraAdicional = 30;
        if ($lUltimoPeriodo) {
            $iLarguraAdicional = 0;
            $lQuebra = 0;
        }

        $iLargura = $this->oPdf->getAvailWidth();


        $nDotacaoAtual  = db_formatar($oStdLinha->dot_atual, "f");
        $nEmpAteBin     = db_formatar($oStdLinha->emp_atebim, "f");
        $nLiqAteBin     = db_formatar($oStdLinha->liq_atebim, "f");
        $nDespPaga      = db_formatar($oStdLinha->desppag, "f");
        $nRpNProcessado = db_formatar($oStdLinha->rp_nproc, "f");
        if ($lUltimoPeriodo) {
            $nRpNProcessado = db_formatar(abs($oStdLinha->rp_nproc), 'f');
        }

        $lTransparencia = $this->transparente($oStdLinha->ordem);

        $this->oPdf->cell(
            $iLargura * (0.4) + $iLarguraAdicional,
            4,
            \relatorioContabil::getIdentacao($oStdLinha->nivel) . $oStdLinha->descricao,
            "$sBorda R",
            0,
            'L',
            $this->transparente($oStdLinha->ordem)
        );

        $this->oPdf->cell($iLargura * 0.1 + 7, 4, $nDotacaoAtual, "$sBorda R", 0, 'R', $lTransparencia);
        $this->oPdf->cell($iLargura * 0.1 + 7, 4, $nEmpAteBin, "$sBorda R", 0, 'R', $lTransparencia);
        $this->oPdf->cell($iLargura * 0.1 + 7, 4, $nLiqAteBin, "$sBorda R", 0, 'R', $lTransparencia);
        $this->oPdf->cell($iLargura * 0.1 + 7, 4, $nDespPaga, "$sBorda L", $lQuebra, 'R', $lTransparencia);
        if ($lUltimoPeriodo) {
            $this->oPdf->cell($iLargura * 0.1, 4, $nRpNProcessado, "$sBorda L", 1, 'R', $lTransparencia);
        }

        $this->oPdf->setBold(false);
    }

     /**
     * Imprime o cabeçalho da despesa
     */
    protected function cabecalhoDespesa($legendaQuadro = null)
    {

        $legenda = "DESPESAS PREVIDENCIÁRIAS - RPPS (FUNDO EM CAPITALIZAÇÃO)";
        if (!empty($legendaQuadro)) {
            $legenda = $legendaQuadro;
        }
        $lUltimoPeriodo = $this->oRelatorio->getPeriodo()->getCodigo() == 11;


        $lQuebra = 1;
        $iLarguraAdicional = 30;
        if ($lUltimoPeriodo) {
            $iLarguraAdicional = 0;
            $lQuebra = 0;
        }

        $iLargura = $this->oPdf->getAvailWidth();

        $this->oPdf->setBold(true);

        $this->oPdf->cell($iLargura * (0.4) + $iLarguraAdicional, 3, "", "TR", 0, 'C', 1);
        $this->oPdf->cell($iLargura * 0.1 + 7, 3, "DOTAÇÃO", "TR", 0, 'C', 1);
        $this->oPdf->cell($iLargura * 0.1 + 7, 3, "DESPESAS", "TR", 0, 'C', 1);
        $this->oPdf->cell($iLargura * 0.1 + 7, 3, "DESPESAS", "TR", 0, 'C', 1);
        $this->oPdf->cell($iLargura * 0.1 + 7, 3, "DESPESAS", "TL", $lQuebra, 'C', 1);
        if ($lUltimoPeriodo) {
            $this->oPdf->cell($iLargura * 0.1, 3, "INCRITAS EM RESTOS", "TL", 1, 'C', 1);
        }


        $this->oPdf->cell($iLargura * (0.4) + $iLarguraAdicional, 3, $legenda, 'R', 0, 'C', 1);
        $this->oPdf->cell($iLargura * 0.1 + 7, 3, "ATUALIZADA", "R", 0, 'C', 1);
        $this->oPdf->cell($iLargura * 0.1 + 7, 3, "EMPENHADAS", "R", 0, 'C', 1);
        $this->oPdf->cell($iLargura * 0.1 + 7, 3, "LIQUIDADAS", "R", 0, 'C', 1);
        $this->oPdf->cell($iLargura * 0.1 + 7, 3, "PAGAS", "L", $lQuebra, 'C', 1);
        if ($lUltimoPeriodo) {
            $this->oPdf->cell($iLargura * 0.1, 3, "A PAGAR ", "L", 1, 'C', 1);
        }

        $this->oPdf->cell($iLargura * (0.4) + $iLarguraAdicional, 3, "", "R", 0, 'C', 1);
        $this->oPdf->cell($iLargura * 0.1 + 7, 3, "(c)", "R", 0, 'C', 1);
        $this->oPdf->cell($iLargura * 0.1 + 7, 3, "Até o Bimestre", "R", 0, 'C', 1);
        $this->oPdf->cell($iLargura * 0.1 + 7, 3, "Até o Bimestre", "R", 0, 'C', 1);
        $this->oPdf->cell($iLargura * 0.1 + 7, 3, "Até o Bimestre", "L", $lQuebra, 'C', 1);
        if ($lUltimoPeriodo) {
            $this->oPdf->cell($iLargura * 0.1, 3, "NÃO PROCESSADOS ", "L", 1, 'C', 1);
        }

        $this->oPdf->cell($iLargura * (0.4) + $iLarguraAdicional, 3, "", "BR", 0, 'C', 1);
        $this->oPdf->cell($iLargura * 0.1 + 7, 3, "", "BR", 0, 'C', 1);
        $this->oPdf->cell($iLargura * 0.1 + 7, 3, "(d)", "BR", 0, 'C', 1);
        $this->oPdf->cell($iLargura * 0.1 + 7, 3, "(e)", "BR", 0, 'C', 1);
        $this->oPdf->cell($iLargura * 0.1 + 7, 3, "(f)", "BL", $lQuebra, 'C', 1);
        if ($lUltimoPeriodo) {
            $this->oPdf->cell($iLargura * 0.1, 3, "No Exercício (g)", "BL", 1, 'C', 1);
        }

        $this->oPdf->setBold(false);
    }


    /**
     * @param \stdClass $oStdLinha
     */
    protected function imprimeReceita(\stdClass $oStdLinha)
    {

        $iLargura = $this->oPdf->getAvailWidth();
        if ($oStdLinha->totalizar) {
            $this->oPdf->setBold(true);
        }
        $sBorda = in_array(
            $oStdLinha->ordem,
            array(static::PREVIDENCIARIO_TOTAL_RECEITAS, static::TOTAL_RECEITAS_PREVIDENCIARIAS_RPPS_FUNDO_REPARTICAO)
        ) ? "TB" : '';

        $this->oPdf->cell(
            $iLargura * 0.4,
            4,
            (\relatorioContabil::getIdentacao($oStdLinha->nivel)) . $oStdLinha->descricao,
            $sBorda . 'R',
            0,
            'L',
            $this->transparente($oStdLinha->ordem)
        );
        $this->oPdf->cell(
            $iLargura * 0.30,
            4,
            db_formatar($oStdLinha->prev_atual, 'f'),
            $sBorda . 'R',
            0,
            'R',
            $this->transparente($oStdLinha->ordem)
        );
        $this->oPdf->cell(
            $iLargura * 0.30,
            4,
            db_formatar($oStdLinha->rec_atebim, 'f'),
            $sBorda . '',
            1,
            'R',
            $this->transparente($oStdLinha->ordem)
        );

        $this->oPdf->setBold(false);
    }


    /**
     * @param $sTituloQuadro
     */
    protected function cabecalhoReceita($sTituloQuadro, $tituloColuna = null, $lExibeSubTitulo = true)
    {
        $legenda = !empty($tituloColuna) ? $tituloColuna : 'RECEITAS PREVIDENCIÁRIAS - RPPS (FUNDO EM CAPITALIZAÇÃO)';

        $lBold = $this->oPdf->getBold();
        $this->oPdf->setBold(true);
        $this->oPdf->cell($this->oPdf->getAvailWidth(), 4, $sTituloQuadro, "TB", 1, \PDFDocument::ALIGN_CENTER, 1);


        if ($lExibeSubTitulo) {
            $sSubTitulo = 'FUNDO EM CAPITALIZAÇÃO (PLANO PREVIDENCIÁRIO)';
            $this->oPdf->cell($this->oPdf->getAvailWidth(), 4, "$sSubTitulo", "TB", 1, \PDFDocument::ALIGN_CENTER, 1);
        }


        $iLargura = $this->oPdf->getAvailWidth();

        $this->oPdf->cell($iLargura * 0.4, 3, "", 'T', 0, 'C', 1);
        $this->oPdf->cell($iLargura * 0.30, 3, 'PREVISÃO', 'TLR', 0, 'C', 1);
        $this->oPdf->cell($iLargura * 0.30, 3, 'RECEITAS REALIZADAS', 'TL', 1, 'C', 1);

        $this->oPdf->cell($iLargura * 0.4, 3, $legenda, '', 0, 'C', 1);
        $this->oPdf->cell($iLargura * 0.30, 3, 'ATUALIZADA', 'LR', 0, 'C', 1);
        $this->oPdf->cell($iLargura * 0.30, 3, 'Até o Bimestre', 'L', 1, 'C', 1);

        $this->oPdf->cell($iLargura * 0.4, 3, "", 'B', 0, 'C', 1);
        $this->oPdf->cell($iLargura * 0.30, 3, '(a)', 'BLR', 0, 'C', 1);
        $this->oPdf->cell($iLargura * 0.30, 3, '(b)', 'BL', 1, 'C', 1);

        $iPosicaoX = $this->oPdf->getX();
        $this->oPdf->setX($iPosicaoX);
        $this->oPdf->setbold($lBold);
    }



    /**
     * Adiciona as legendas do relatório
     * @throws \BusinessException
     */
    protected function legendas()
    {
        $this->notas();
        $oRelatorio = new \relatorioContabil(Relatorio::CODIGO_RELATORIO, false);
        $oRelatorio->notaExplicativa(
            $this->oPdf,
            $this->oRelatorio->getPeriodo()->getCodigo(),
            $this->oPdf->getAvailWidth()
        );
    }


    /**
     * @param $iOrdem
     * @return bool
     */
    protected function transparente($iOrdem)
    {
        return in_array(
            $iOrdem,
            array(static::PREVIDENCIARIO_TOTAL_RECEITAS,
                  static::PREVIDENCIARIO_TOTAL_DESPESAS,
                  static::TOTAL_RECEITAS_PREVIDENCIARIAS_RPPS_FUNDO_REPARTICAO,
                  static::TOTAL_DESPESAS_PREVIDENCIARIAS_RPPS_FUNDO_EM_REPARTICAO,
                  static::RECEITAS_DA_ADMINISTRACAO_RPPS_FIM,
                  static::DESPESAS_DA_ADMINISTRACAO_RPPS_FIM,
                  static::RECEITAS_PREVIDENCIARIAS_BENEFICIOS_MANTIDOS_PELO_TESOURO_FIM,
                  static::DESPESAS_PREVIDENCIARIAS_BENEFICIOS_MANTIDOS_PELO_TESOURO_FIM

                )
        );
    }

    /**
     * @param $iAltura
     */
    protected function espaco($iAltura)
    {
        $this->oPdf->ln($iAltura);
    }

    /**
     * @param array $sessao
     *
     * @return \Ecidade\Financeiro\Contabilidade\Relatorio\RREO\V2017\Layout\AnexoIV | array | null
     */
    public function sessao($sessao = null)
    {
        if (!empty($sessao)) {
            $this->sessao = $sessao;
        }

        return $this;

        return $this->sessao;
    }

    public function setAnexo(\ECidade\Financeiro\Contabilidade\Relatorio\RREO\V2021\AnexoIV $oRelatorio)
    {
        $this->oRelatorio = $oRelatorio;
    }

    /**
     * Adiciona o cabeçalho do relatório.
     *
     * @throws \ParameterException
     */
    protected function addHeaders()
    {
        $oPrefeitura = \InstituicaoRepository::getInstituicaoPrefeitura();
        $sMesInicio = mb_strtoupper(\DBDate::getMesExtenso($this->oRelatorio->getDataInicialPeriodo()->getMes()));
        $sMesFim = mb_strtoupper(\DBDate::getMesExtenso($this->oRelatorio->getDataFinal()->getMes()));

        $this->oPdf->addHeaderDescription('');
        $this->oPdf->addHeaderDescription(DemonstrativoFiscal::getEnteFederativo($oPrefeitura));

        $aInstituicoes = explode(',', $this->oRelatorio->getInstituicoes());

        if (count($aInstituicoes) == 1) {
            $oInstituicao = \InstituicaoRepository::getInstituicaoByCodigo($aInstituicoes[0]);

            if ($oInstituicao->getTipo() != \Instituicao::TIPO_PREFEITURA) {
                $this->oPdf->addHeaderDescription($oInstituicao->getDescricao());
            }
        }

        $nomeRelatorio = 'DEMONSTRATIVO DE RECEITAS E DESPESAS PREVIDENCIÁRIAS DO REGIME PRÓPRIO DOS SERVIDORES';
        $periodo = $sMesInicio.' A '.$sMesFim.'/'.$this->oRelatorio->getAno().
            ' - BIMESTRE '.$sMesInicio.'-'.$sMesFim;
        $this->oPdf->addHeaderDescription('RELATÓRIO RESUMIDO DA EXECUÇÃO ORÇAMENTÁRIA ');
        $this->oPdf->addHeaderDescription($nomeRelatorio);
        $this->oPdf->addHeaderDescription($periodo);

        $this->oPdf->open();
        $this->oPdf->addPage();
        $this->oPdf->SetFontSize(6);
        $this->oPdf->SetFillColor(235);
    }

    protected function notas()
    {
        $this->nota1();
        $this->nota2();
    }
    protected function nota1()
    {


        $sNota1  = "1 Como a Portaria MPS 746/2011 determina que os recursos provenientes desses aportes devem ";
        $sNota1 .= "permanecer aplicados, no mínimo, por 5 (cinco) anos, essa receita não deverá compor o ";
        $sNota1 .= "total das receitas previdenciárias do período de apuração";

        $this->oPdf->cell($this->oPdf->getAvailWidth(), 4, $sNota1, 0, 1);
    }

    protected function nota2()
    {
        $sNota2  = "2 O resultado previdenciário será apresentada por meio da diferença entre previsão da receita ";
        $sNota2 .= "e a dotação da despesa e entre a receita realizada e a despesa liquidada ";
        $sNota2 .= "(do 1º ao 5º bimestre) e a despesa empenhada (no 6º bimestre).";
        $this->oPdf->cell($this->oPdf->getAvailWidth(), 4, $sNota2, 0, 1);
    }
}
