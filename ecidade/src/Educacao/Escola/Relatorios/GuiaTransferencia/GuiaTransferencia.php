<?php


namespace ECidade\Educacao\Escola\Relatorios\GuiaTransferencia;

use Exception;
use FpdfMultiCellBorder;
use stdClass;
use DBDate;
use db_utils;
use ECidade\Educacao\Escola\Service\NotificacaoTransferenciaService;
use Escola;

class GuiaTransferencia
{
    /**
     * Dados para impressão
     * @var array
     */
    public $dados;

    public $pdf;

    public $larguraLinha;

    public $tamanhoFonte = 10;

    public $alturaLinha = 5;

    public $vias = 1;

    public $orientation;

    public $assinatura;

    public $notificar;

    public $tipoTransferencia;


    public function __construct($orientation, $tipoTransf = "", $modelo = null, $notificar = false, $escola = "")
    {
        $this->escola = $escola;
        $this->tipoTransferencia = $tipoTransf;
        $this->notificar = $notificar;
        $this->orientation = strtoupper($orientation);
        $this->pdf = new FpdfMultiCellBorder($orientation);
        $this->pdf->Open();
        $this->pdf->AliasNbPages();
        $this->pdf->SetFont('Arial', 'B', $this->tamanhoFonte);
        $this->pdf->setfillcolor(225);
        $this->pdf->exibeHeader(true);
        $this->pdf->setExibeBrasao(true);
        $this->pdf->mostrarRodape(true);
        $this->pdf->mostrarEmissor(true);
        $this->pdf->mostrarTotalDePaginas(true);
        $this->pdf->SetAutoPageBreak(false);
        global $head1;
        $head1 = "GUIA DE TRANSFERÊNCIA";


        $this->larguraLinha = $this->orientation == 'L' ? 279 : 192;

        if (!is_null($modelo)) {
            if ($this->orientation == "P") {
                $this->tamanhoFonte = 8;
                $this->alturaLinha = 3;
                $this->pdf->mostrarRodape(false);
            }
            $this->vias = 2;
        }
    }



    public function setDados($dados)
    {
        $this->dados = $dados;
    }


    public function setAssinatura($diretor)
    {
        $arrayAssinatura = $diretor != "" ? explode("|", $diretor) : null;
        $this->assinatura['nome'] = !is_null($arrayAssinatura) ? $arrayAssinatura[1] : null;
        $this->assinatura['atividade'] = !is_null($arrayAssinatura) ? $arrayAssinatura[0] . " desta escola," : null;
        $funcao = trim($arrayAssinatura[2]) != "" ? $arrayAssinatura[2] : "";
        $this->assinatura['funcao'] = !is_null($arrayAssinatura) ? $arrayAssinatura[0] . $funcao : null;
    }

    /**
     * @return object
     */
    public function emitir()
    {
        $this->imprimeDados();
        $path = sprintf("tmp/guia-de-transferencia-%s.pdf", time());
        $this->pdf->output($path, false, true);
        $retorno = (object)[
            "guia_pdf" => $path,
            "status_notificacao" => ""
        ];
        if ($this->notificar) {
            try {
                $this->notifica($path);
                $retorno->status_notificacao = "E-mail enviado para as escolas de Origem e Destino.";
            } catch (Exception $e) {
                $retorno->status_notificacao = "O E-mail de notificação não foi enviado! \\n\\n" . $e->getMessage();
            }
        }
        return $retorno;
    }

    public function notifica($path)
    {
        foreach ($this->dados['alunos'] as $aluno) {
            $notificacaoService = new NotificacaoTransferenciaService(
                $this->escola,
                $aluno->codigo_escola_destino,
                $aluno->ed47_i_codigo,
                $this->tipoTransferencia,
                $aluno->data_transf
            );
            $notificacaoService->setGuiaTransferencia($path);
            $turma = isset($aluno->descr_turma) ? $aluno->descr_turma : $aluno->matricula[0]->descr_turma;
            $notificacaoService->notificar($turma, $aluno->obs);
        }
    }

    public function imprimeDados()
    {
        $folha = 1;
        $nome = !is_null($this->assinatura['nome']) ?
                            $this->assinatura['nome'] :
                            "------------------------------------------------------";

        $funcao = !is_null($this->assinatura['funcao']) ? $this->assinatura['funcao'] : "";

        foreach ($this->dados['alunos'] as $aluno) {
            $folha = 1;
            if (($this->vias == 1 && $this->orientation == "P") || ($this->vias == 1 && $this->orientation == "L")) {
                $this->pdf->addPage();
            } elseif ($this->vias > 1 && $this->orientation == "P") {
                global $head1;
                $head1 = "GUIA DE TRANSFERÊNCIA - 1º VIA";
                $this->pdf->addPage();
            }

            for ($i = 1; $i <= $this->vias; $i++) {
                if ($this->vias > 1) {
                    if ($this->orientation == "L") {
                        global $head1;
                        $head1 = "GUIA DE TRANSFERÊNCIA - {$i}º VIA";
                        $this->pdf->addPage();
                    }
                }
                $matricula = new \Matricula($aluno->codigomatricula);
                $espaco = "_______________________________________";
                $titulo = "Guia de Transferência";
                $this->pdf->setfont('arial', 'b', $this->tamanhoFonte);
                $this->pdf->cell($this->larguraLinha, $this->alturaLinha * 2, $titulo, "LRT", 1, "C", 0, 0);
                $this->pdf->setfont('arial', '', $this->alturaLinha * 2);
                $this->pdf->MultiCell($this->larguraLinha, 5, $aluno->atestado, 'LR', 'J', 0);
                $this->pdf->cell($this->larguraLinha, $this->alturaLinha * 2, "", "LRB", 1, "C", 0, 0);
                $this->pdf->cell($this->larguraLinha, $this->alturaLinha, $aluno->descricaoEnsino, "LRB", 1, "C", 1, 0);
                $grade = new \RelatorioGradeAproveitamento($this->pdf, $matricula, $this->larguraLinha);
                $grade->montarGrade();
                $this->pdf->cell($this->larguraLinha, $this->alturaLinha, "", "LRB", 1, "C", 1, 0);
                $this->pdf->cell($this->larguraLinha, $this->alturaLinha * 2, "", "LR", 1, "C", 0, 0);
                $this->pdf->setfont('arial', '', $this->tamanhoFonte);
                $this->pdf->MultiCell($this->larguraLinha, $this->alturaLinha, $aluno->obs, 'LR', 'J', 0);
                $this->pdf->cell($this->larguraLinha, $this->alturaLinha * 2, "", "LR", 1, "C", 0, 0);
                $this->pdf->cell($this->larguraLinha, $this->alturaLinha, $aluno->cidadeDataTransf, "LR", 1, "C", 0, 0);
                $this->pdf->cell($this->larguraLinha, $this->alturaLinha, "", "LR", 1, "C", 0, 0);
                $this->pdf->cell($this->larguraLinha, $this->alturaLinha, $espaco, "LR", 1, "C", 0, 0);
                $this->pdf->cell($this->larguraLinha, $this->alturaLinha * 2, $nome, "LR", 1, "C", 0, 0);
                if ($funcao == "") {
                    $this->pdf->cell($this->larguraLinha, $this->alturaLinha, "", "LRB", 1, "C", 0, 0);
                } else {
                    $this->pdf->cell($this->larguraLinha, $this->alturaLinha, $funcao, "LRB", 1, "C", 0, 0);
                }
                if ($this->vias > 1) {
                    if ($this->orientation == "P") {
                        global $head1;
                        $head1 = "GUIA DE TRANSFERÊNCIA - 2º VIA";
                        if ($this->pdf->GetY() > 145.9) {
                            if ($folha <= 1) {
                                $this->pdf->AddPage();
                            }
                        } else {
                            if ($folha <= 1) {
                                $this->pdf->headerMovel(145.9);
                                $this->pdf->setY(180);
                            }
                        }
                    }
                }
                $folha++;
            }
        }
    }
}
