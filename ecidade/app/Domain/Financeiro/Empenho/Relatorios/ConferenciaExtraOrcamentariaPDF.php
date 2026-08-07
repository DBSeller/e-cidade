<?php

namespace App\Domain\Financeiro\Empenho\Relatorios;

use cl_assinatura;
use ECidade\Pdf\Pdf;

class ConferenciaExtraOrcamentariaPDF extends Pdf
{
    private $datainicial;
    private $datafinal;
    private $tipo;
    private $dados;


    public function __construct($datainicial, $datafinal, $tipo, $dados)
    {
        parent::__construct();
//        dd($dados);
        $this->datainicial = $datainicial;
        $this->datafinal = $datafinal;
        $this->tipo = $tipo;
        $this->dados = $dados;
    }

    public function emitir()
    {
        $this->initPdf();
        $this->addPage();
        if (sizeof($this->dados) < 1) {
            $this->montaCorpoVazio();
            $this->imprimeAssinaturas();
            return $this->imprimir();
        }
        $this->montaCabecalho();
        $this->montaCorpo();
        $this->imprimeAssinaturas();
        return $this->imprimir();
    }

    public function imprimir()
    {
        $fileName = 'tmp/conferencia_extraorcamentaria_'.$this->tipo . time() . '.pdf';
        $this->output('F', $fileName);

        return [
            "name" => "Relatório de Conferência " .$this->tipo. " Extraorçamentária",
            "path" => $fileName,
            'pathExterno' => ECIDADE_REQUEST_PATH . $fileName
        ];
    }

    private function initPdf()
    {
        $this->addTitulo('Relatório de Conferência '.$this->tipo .' Extraorçamentária.');
        $this->addTitulo('Período: '.str_replace('-', '/', $this->datainicial).' até '
            .str_replace('-', '/', $this->datafinal));
        $this->mostrarRodape();
        $this->mostrarTotalDePaginas();
        $this->setMargins(8, 8, 8);
        $this->setAutoPageBreak(false, 10);
        $this->aliasNbPages();
        $this->setFillColor(235);
        $this->setFont('Arial', 'B', 9);
        $this->exibeHeader();
    }

    private function montaCabecalho()
    {
        $this->setFont('Arial', '', 7);
        $this->setFillColor('226', '226', '230');
        $this->cell(9, 4, 'Instit', 1, 0, '', true);
        $this->cell(13, 4, 'Data', 1, 0, '', true);
        $this->cell(8, 4, 'Emp.', 1, 0, '', true);
        $this->cell(13, 4, 'Cód. Lanç.', 1, 0, '', true);
        $this->cell(8, 4, 'Cód.', 1, 0, '', true);
        $this->cell(74, 4, 'Descrição', 1, 0, '', true);
        $this->cell(18, 4, 'CPF/CNPJ', 1, 0, '', true);
        $this->cell(20, 4, 'Estrutural', 1, 0, '', true);
        $this->cell(12, 4, 'Ano Emp.', 1, 0, '', true);
        $this->cell(22, 4, 'Valor lançamento', 1, 0, '', true);
        $this->ln();
    }

    private function montaCorpo()
    {
        $soma = 0;
        foreach ($this->dados as $dado) {
            $this->setFont('Arial', '', 6);
            $this->cell(9, 4, $dado->nomeinstabrev, 1, 0);
            $this->cell(13, 4, db_formatar($dado->c71_data, 'd'), 1, 0);
            $this->cell(8, 4, $dado->empenho, 1, 0);
            $this->cell(13, 4, $dado->c71_codlan, 1, 0);
            $this->cell(8, 4, $dado->c71_coddoc, 1, 0);
            $this->cell(74, 4, $dado->c53_descr, 1, 0);
            $this->cell(18, 4, $dado->cpf_cnpj, 1, 0);
            $this->cell(20, 4, $dado->estrutural, 1, 0);
            $this->cell(12, 4, $dado->ano_empenho, 1, 0);
            $this->cell(22, 4, db_formatar($dado->c70_valor, 'f'), 1, 0);
            $this->ln();
            if ($this->gety() > ($this->getH() - 35)) {
                $this->addpage();
            }
            $soma += (float)$dado->c70_valor;
        }
        $this->cell(190, 4, "Soma: ".db_formatar($soma, 'f'), 0, 0, 'R');
    }

    private function montaCorpoVazio()
    {
        $this->setfont('arial', '', 10);
        $this->cell($this->getW()-15, ($this->getH()/2)-50, "NADA A REGISTRAR", 0, 0, "C", 0);
    }

    private function imprimeAssinaturas()
    {
        $this->setfont('arial', '', 7);
        $sec = "______________________________" . "\n" . "Secretaria da Fazenda";
        $cont = "______________________________" . "\n" . "Contador";
        $pref = "______________________________" . "\n" . "Prefeito";
        $classinatura = new cl_assinatura;
        $ass_pref = $classinatura->assinatura(1000, $pref);
        $ass_sec = $classinatura->assinatura(1002, $sec);
        $ass_cont = $classinatura->assinatura(1005, $cont);

        if ($this->gety() > ($this->getH() - 35)) {
            $this->addpage();
        }
        $largura = (($this->getW())/3);
        $this->ln();
        $this->ln();
        $pos = $this->gety();
        $this->Multicell($largura, 4, $ass_pref, 0, "C", 0, 0);
        $this->setxy($largura, $pos);
        $this->Multicell($largura, 4, $ass_sec, 0, "C", 0, 0);
        $this->setxy(($largura*2)-10, $pos);
        $this->Multicell($largura, 4, $ass_cont, 0, "C", 0, 0);
    }
}
