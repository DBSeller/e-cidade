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

namespace App\Domain\Patrimonial\Ouvidoria\Services;

/**
 * Classe InformacoesProcessoPDFService
 */
class InformacoesProcessoPDFService
{
    private $larguraLinha = 190;
    private $alturaLinha = 4;
    private $fonteSecoes = 10;
    private $fonteConteudo = 8;
    private $pdf;
    private $motivoRejeicao;
    private $labelMotivoRejeicao = "Motivo da rejeição : ";


    /**
     * Construtor da classe
     */
    public function __construct()
    {
        $this->pdf = new \pdf1();
    }

    /**
     * Função para imprimir os dados da solicitação web
     *
     * @param ProcessoProtocolo $processo
     * @param stdClass $dadosSolicitacao
     */
    public function gerar($processo, $dadosSolicitacao)
    {
        $this->pdf->Open();
        $this->pdf->setAutoPageBreak(false);
        $this->pdf->AliasNbPages();
        $this->pdf->AddPage();
        $this->pdf->SetFont('Arial', 'B', $this->fonteSecoes);
        $this->pdf->Cell($this->larguraLinha, $this->alturaLinha, 'Informações do Processo', 0, 0, 'C');
        $this->pdf->Ln();
        $this->pdf->Line(10, 32, 200, 32);

        foreach ($dadosSolicitacao->secoes as $secao) {
            if ($secao->tipo == 'anexo') {
                continue;
            }

            $this->quebrarPagina();
            $this->imprimirSecao($secao->label);

            if ($secao->tipo == 'form') {
                $this->imprimirCamposForm($secao);
            }

            if ($secao->tipo == 'tabela') {
                $this->imprimirCamposTabela($secao);
            }
        }

        if (!empty($this->getMotivoRejeicao())) {
            $this->pdf->Ln();

            $tamanhoLabel = $this->pdf->GetStringWidth($this->labelMotivoRejeicao);
            $tamanhoColunaResposta = $this->larguraLinha - $tamanhoLabel;
            $this->pdf->SetFont('Arial', 'B', $this->fonteConteudo);
            $this->pdf->Cell($tamanhoLabel, $this->alturaLinha, $this->labelMotivoRejeicao, '', 0);
            $this->pdf->SetFont('Arial', '', $this->fonteConteudo);
            $this->pdf->MultiCell($tamanhoColunaResposta, $this->alturaLinha, $this->getMotivoRejeicao());
        }

        $caminho = "tmp/informacoes_processo_{$processo->getCodigoProcesso()}.pdf";
        $this->pdf->Output($caminho, false, true);
        return $caminho;
    }

    /**
     * Valida se deve ser quebrada a página e imprime o cabeçalho novamente
     *
     * @param string $labelSecao
     */
    private function quebrarPagina($labelSecao = null)
    {
        if ($this->pdf->GetY() > $this->pdf->h - 30) {
            $this->pdf->AddPage();
            $this->pdf->SetFont('Arial', 'B', $this->fonteSecoes);
            $this->pdf->Cell($this->larguraLinha, $this->alturaLinha, 'Informações do Processo', 0, 0, 'C');
            $this->pdf->SetFont('Arial', 'B', $this->fonteConteudo);
            $this->pdf->Ln();
            $this->pdf->Line(10, 32, 200, 32);

            if (!empty($labelSecao)) {
                $this->imprimirSecao($labelSecao);
            }

            return true;
        }

        return false;
    }

    /**
     * Imprimir a secão
     *
     * @param string $labelSecao
     */
    private function imprimirSecao($labelSecao)
    {
        $this->pdf->Ln();
        $this->pdf->SetFont('Arial', 'B', $this->fonteSecoes);
        $this->pdf->Cell($this->larguraLinha, $this->alturaLinha, $labelSecao, 'B', 1);
        $this->pdf->Ln();
        $this->pdf->SetFont('Arial', 'B', $this->fonteConteudo);
    }


    /**
     * Imprime o corpo do relatório
     *
     * @param stdClass $secao
     */
    private function imprimirCamposForm($secao)
    {
        foreach ($secao->campos as $campo) {
            if (!isset($campo->label) || (isset($campo->resposta) && is_array($campo->resposta))) {
                continue;
            }

            $this->quebrarPagina($secao->label);

            $this->pdf->SetFont('Arial', 'B', $this->fonteConteudo);

            $label =  trim($campo->label) . ": ";
            $tamanhoLabel = $this->pdf->GetStringWidth($label);

            $resposta = '';
            if (!empty($campo->resposta)) {
                $resposta = gettype($campo->resposta) == 'object' ? $campo->resposta->descricao : $campo->resposta;
            }

            $tamanhoColunaResposta = $this->larguraLinha - $tamanhoLabel;
            $linhasOcupadas = $this->pdf->NBLines($tamanhoColunaResposta, $resposta);


            $yPDF = $this->pdf->GetY();
            $this->pdf->SetY($yPDF + ($linhasOcupadas * $this->alturaLinha));
            if (!$this->quebrarPagina($secao->label)) {
                $this->pdf->SetY($yPDF);
            }

            $this->pdf->Cell($tamanhoLabel, $this->alturaLinha, $label, '', 0);
            $this->pdf->SetFont('Arial', '', $this->fonteConteudo);
            $this->pdf->MultiCell($tamanhoColunaResposta, $this->alturaLinha, $resposta);
        }
    }

    /**
     * Normaliza os objetos para pode aproveitar o método imprimirCamposForm
     *
     * @param stdClass $secao
     */
    private function imprimirCamposTabela($secao)
    {

        if (!isset($secao->resposta) || !is_array($secao->resposta)) {
            return;
        }

        $secaoTabela = new \stdClass();
        $secaoTabela->label = $secao->label;

        $camposLabel = array();

        foreach ($secao->campos as $campo) {
            $camposLabel[$campo->nome] = $campo->label;
        }

        foreach ($secao->resposta as $resposta) {
            $secaoTabela->campos = array();

            foreach ($resposta as $chave => $valor) {
                if (array_key_exists($chave, $camposLabel)) {
                    $campo = new \stdClass();
                    $campo->label = $camposLabel[$chave];
                    $campo->resposta = $valor;
                    $secaoTabela->campos[] = $campo;
                }
            }

            $this->imprimirCamposForm($secaoTabela);
            $this->pdf->Ln();
        }
    }

    /**
     * Set the value of motivoRejeicao
     *
     * @return  self
     */
    public function setMotivoRejeicao($motivoRejeicao)
    {
        $this->motivoRejeicao = $motivoRejeicao;

        return $this;
    }

    /**
     * Get the value of motivoRejeicao
     */
    public function getMotivoRejeicao()
    {
        return $this->motivoRejeicao;
    }
}
