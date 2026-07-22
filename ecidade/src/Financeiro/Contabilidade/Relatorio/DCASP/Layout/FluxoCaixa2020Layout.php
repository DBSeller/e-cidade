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

namespace ECidade\Financeiro\Contabilidade\Relatorio\DCASP\Layout;

use ECidade\Financeiro\Contabilidade\Relatorio\DCASP\Model\FluxoCaixa;
use ECidade\Financeiro\Contabilidade\Relatorio\DCASP\Service\FluxoCaixaService;
use InstituicaoRepository;
use PDFDocument;

/**
 * Class FluxoCaixa2020Layout
 * @package ECidade\Financeiro\Contabilidade\Relatorio\DCASP\Layout
 */
abstract class FluxoCaixa2020Layout implements FluxoCaixaLayout
{
    /**
     * Linhas totalizadoras que não devem imprimir em negrito
     * @var integer[]
     */
    protected $totalizadorasSemNegrito = [];

    protected $nomeQuadros = [
        FluxoCaixaService::QUADRO_PRINCIPAL => "QUADRO PRINCIPAL",
        FluxoCaixaService::QUADRO_TRANSFERENCIAS => "QUADRO DE TRANSFÊRENCIAS RECEBIDAS E CONCEDIDAS",
        FluxoCaixaService::QUADRO_DESEMBOLSOS => "QUADRO DE DESEMBOLSOS DE PESSOAL E DEMAIS DESPESAS POR FUNÇÃO",
        FluxoCaixaService::QUADRO_DIVIDA => "QUADRO DE JUROS E ENCARGOS DA DÍVIDA",
    ];

    /**
     * @var array
     */
    protected $quadrosExibir = [];

    /**
     * @var FluxoCaixa
     */
    protected $relatorio;

    /**
     * @var string
     */
    protected $nomeInstituicao;
    /**
     * @var PDFDocument
     */
    protected $pdf;
    /**
     * @var float
     */
    protected $wTexto;
    /**
     * @var float
     */
    protected $wValor;
    /**
     * @var int
     */
    protected $alturaLinha;
    /**
     * @var float
     */
    protected $larguraPagina;

    /**
     * Linhas que não deve imprimir valor... como se fosse um titulo
     * @var int[]
     */
    protected $linhasSemValorQuadroPrincipal = [];

    /**
     * Contém as linhas do Quadro Principal
     * @var array
     */
    protected $dadosQuadroPrincipal;

    /**
     * Contém as linhas do Quadro de Transferência Recebidas e Concedidas
     * @var array
     */
    protected $dadosQuadroTransferencia;
    /**
     * Contém as linhas do Quadro de Desembolso de Pessoal e Demasis Despesas por Função
     * @var array
     */
    protected $dadosQuadroDesenbolsos;
    /**
     * Contém as linhas do Quadro de Juros e Encargos da Dívida
     * @var array
     */
    protected $dadosQuadroDivida;

    /**
     * FluxoCaixa2020Layout constructor.
     * @param FluxoCaixa $relatorio
     */
    public function __construct(FluxoCaixa $relatorio)
    {
        $this->relatorio = $relatorio;
        $this->pdf = new PDFDocument();

        $this->alturaLinha = 4;
        $this->larguraPagina = $this->pdf->getAvailWidth() - 10;
        $this->wTexto = $this->larguraPagina * 0.60;
        $this->wValor = $this->larguraPagina * 0.20;

        $this->pdf->SetLeftMargin(10);
        $this->pdf->Open();
        $this->pdf->AliasNbPages();
        $this->pdf->SetAutoPageBreak(true);
        $this->pdf->SetFillcolor(235);
        $this->pdf->SetFont('arial', '', 6);
    }

    public function imprimir()
    {
        $this->processaDados();
        foreach ($this->quadrosExibir as $codigoQuadro) {
            switch ($codigoQuadro) {
                case FluxoCaixaService::QUADRO_PRINCIPAL:
                    $this->imprimeQuadroPrincipal();
                    break;
                case FluxoCaixaService::QUADRO_TRANSFERENCIAS:
                    $this->imprimeQuadroTransferencias();
                    break;
                case FluxoCaixaService::QUADRO_DESEMBOLSOS:
                    $this->imprimeQuadroDesembolsos();
                    break;
                case FluxoCaixaService::QUADRO_DIVIDA:
                    $this->imprimeQuadroDivida();
                    break;
            }
        }

        $this->relatorio->getNota($this->pdf);
        $oAssinaturas = $this->relatorio->getAssinatura($this->pdf);


        /*
        assinaturas($this->pdf, $oAssinaturas, 'BG', false, false);
        echo "<pre>";
        print_r( $oAssinaturas);
        echo "</pre>";
        die();
        */


        $this->pdf->showPDF();
    }

    /**
     * Adiciona uma nova página, reinserindo o cabeçalho do relatório.
     *
     * @param string $sNomeQuadro Nome do quadro do relatório.
     */
    protected function adicionarPagina($sNomeQuadro)
    {
        $this->pdf->clearHeaderDescription();
        $this->pdf->addHeaderDescription($this->nomeInstituicao);
        $this->pdf->addHeaderDescription("FLUXO DE CAIXA");
        $this->pdf->addHeaderDescription($sNomeQuadro);
        $this->pdf->addHeaderDescription("EXERCÍCIO : {$this->relatorio->getAno()}");
        $this->pdf->addHeaderDescription("PERÍODO : {$this->relatorio->getNomePeriodo()}");
        $this->pdf->AddPage();
    }

    /**
     * Escreve o cabeçalho do quadro.
     *
     * @param string $descricao Nome da coluna de descrição do quadro.
     */
    protected function escreverCabecalhoQuadro($descricao = '')
    {
        $this->pdf->setBold(true);
        $this->pdf->Cell($this->wTexto, $this->alturaLinha, $descricao, 'TB', 0, 'C');
        $this->pdf->Cell($this->wValor, $this->alturaLinha, "Exercício Atual", 'LTB', 0, 'C');
        $this->pdf->Cell($this->wValor, $this->alturaLinha, "Exercício Anterior", 'LTB', 1, 'C');
        $this->pdf->setBold(false);
    }

    protected function escreveLinhaValores($descricao, $valor1, $valor2, $totalizador = false, $comBordas = false)
    {
        $bordaDescricao = 'R';
        $bordaValores = 'L';
        if ($totalizador) {
            $this->pdf->setBold(true);
            if ($comBordas) {
                $bordaDescricao = 'TBR';
                $bordaValores = 'LTB';
            }
        }
        $this->pdf->Cell($this->wTexto, $this->alturaLinha, $descricao, $bordaDescricao, 0, 'L');
        $this->pdf->Cell($this->wValor, $this->alturaLinha, $valor1, $bordaValores, 0, 'R');
        $this->pdf->Cell($this->wValor, $this->alturaLinha, $valor2, $bordaValores, 1, 'R');
        if ($totalizador) {
            $this->pdf->setBold(false);
        }
    }

    /**
     * @param array $quadros
     */
    public function setExibirQuadros(array $quadros)
    {
        $this->quadrosExibir = $quadros;
    }


    protected function preparaCabecalho()
    {
        $instituicoes = $this->relatorio->getInstituicoes(true);
        if (count($instituicoes) > 1) {
            $prefeitura = InstituicaoRepository::getInstituicaoPrefeitura();
            $this->nomeInstituicao = "INSTITUIÇÃO : {$prefeitura->getDescricao()} - CONSOLIDAÇÃO";
        } else {
            $oInstituicao = current($instituicoes);
            $this->nomeInstituicao = "INSTITUIÇÃO : {$oInstituicao->getDescricao()}";
        }
    }

    protected function processaDados()
    {
        $this->preparaCabecalho();
        $this->organizaQuadrosImpressao($this->relatorio->getDados());
    }

    protected function organizaQuadrosImpressao($linhas)
    {
        foreach ($linhas as $linha) {
            if ($linha->ordem >= static::QUADRO_PRINCIPAL_INICIAL && $linha->ordem <= static::QUADRO_PRINCIPAL_FINAL) {
                $this->dadosQuadroPrincipal[] = $linha;
            }

            if ($linha->ordem >= static::QUADRO_TRANSFERENCIAS_INICIAL &&
                $linha->ordem <= static::QUADRO_TRANSFERENCIAS_FINAL) {
                $this->dadosQuadroTransferencia[] = $linha;
            }

            if ($linha->ordem >= static::QUADRO_DESEMBOLSOS_PESSOAL_INICIAL
                && $linha->ordem <= static::QUADRO_DESEMBOLSOS_PESSOAL_FINAL) {
                $this->dadosQuadroDesenbolsos[] = $linha;
            }
            if ($linha->ordem >= static::QUADRO_DIVIDA_INICIAL && $linha->ordem <= static::QUADRO_DIVIDA_FINAL) {
                $this->dadosQuadroDivida[] = $linha;
            }
        }
    }

    protected function imprimeQuadroPrincipal()
    {
        $this->adicionarPagina($this->nomeQuadros[FluxoCaixaService::QUADRO_PRINCIPAL]);
        $this->escreverCabecalhoQuadro('');

        $this->imprimeLinhas($this->dadosQuadroPrincipal);
    }

    protected function imprimeQuadroTransferencias()
    {
        $this->adicionarPagina($this->nomeQuadros[FluxoCaixaService::QUADRO_TRANSFERENCIAS]);
        $this->escreverCabecalhoQuadro();
        $this->imprimeLinhas($this->dadosQuadroTransferencia);
    }

    protected function imprimeQuadroDesembolsos()
    {
        $this->adicionarPagina($this->nomeQuadros[FluxoCaixaService::QUADRO_DESEMBOLSOS]);
        $this->escreverCabecalhoQuadro();
        $this->imprimeLinhas($this->dadosQuadroDesenbolsos);
    }

    protected function imprimeQuadroDivida()
    {
        $this->adicionarPagina($this->nomeQuadros[FluxoCaixaService::QUADRO_DIVIDA]);
        $this->escreverCabecalhoQuadro();
        $this->imprimeLinhas($this->dadosQuadroDivida);
    }

    /**
     * @param $valor
     * @return string
     */
    protected function formataValor($valor)
    {
        return number_format($valor, '2', ',', '.');
    }

    protected function imprimeLinhas(array $linhas)
    {
        foreach ($linhas as $linha) {
            if (in_array($linha->ordem, $this->linhasSemValorQuadroPrincipal)) {
                $this->alturaLinha = 8;
                $this->escreveLinhaValores($linha->descricao, '', '', true);
                $this->alturaLinha = 4;
            } else {
                $totalizador = $linha->totalizar;
                if (in_array($linha->ordem, $this->totalizadorasSemNegrito)) {
                    $totalizador = false;
                }

                $comBordas = in_array($linha->ordem, $this->linhasComBordas);

                $valor1 = '-';
                if (isset($linha->vlrexatual)) {
                    $valor1 = $this->formataValor($linha->vlrexatual);
                }
                $valor2 = '-';
                if ($this->relatorio->isExibirExercicioAnterior()) {
                    $valor2 = $this->formataValor($linha->vlrexanter);
                }
                $descricao = str_repeat(' ', $linha->nivel * 2) . $linha->descricao;

                $this->escreveLinhaValores($descricao, $valor1, $valor2, $totalizador, $comBordas);
            }
        }
        $x2 = $this->pdf->w - $this->pdf->rMargin;
        $this->pdf->Line($this->pdf->GetX(), $this->pdf->GetY(), $x2, $this->pdf->GetY());
    }
}
