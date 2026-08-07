<?php

namespace App\Domain\Financeiro\Contabilidade\Relatorios;

use DBDate;
use Exception;

class NotificacaoRecebimentoRecursosPDF extends \FpdfMultiCellBorder
{
    /**
     * @var string
     */
    private $assinaturaContador;
    /**
     * @var string
     */
    private $texto;

    public function __construct(DBDate $dataInicial, DBDate $dataFinal)
    {
        parent::__construct();

        $this->SetMargins(10, 8, 8);
        $this->Open();
        $this->SetAutoPageBreak(false, 10);
        $this->AliasNbPages();
        $this->SetFillColor(235);
        $this->exibeHeader(true);

        $this->mostrarRodape(true);
        $this->mostrarEmissor(true);
        $this->mostrarTotalDePaginas(true);

        global $head2;
        global $head4;
        $head2 = "Notificação de Recebimento de Recursos";
        $head4 = sprintf(
            "Período: %s até %s",
            $dataInicial->getDate(DBDate::DATA_PTBR),
            $dataFinal->getDate(DBDate::DATA_PTBR)
        );

        require_once(modification("libs/db_libdocumento.php"));
        $oDocumento = new \libdocumento(1005);
        if ($oDocumento->lErro) {
            db_redireciona("db_erros.php?fechar=true&db_erro={$oDocumento->sMsgErro}.");
            exit;
        }
        $oDocumento->getParagrafos();

        $this->assinaturaContador = $oDocumento->aParagrafos[1]->db02_texto;

        $oDocumento = new \libdocumento(92001);
        if ($oDocumento->lErro) {
            db_redireciona("db_erros.php?fechar=true&db_erro={$oDocumento->sMsgErro}.");
            exit;
        }
        $oDocumento->getParagrafos();
        $oDocumento->periodo_inicial = sprintf(
            "%s/%s",
            $dataInicial->getMes(),
            $dataInicial->getAno()
        );
        $oDocumento->periodo_final = sprintf(
            "%s/%s",
            $dataFinal->getMes(),
            $dataFinal->getAno()
        );

        $this->texto = $oDocumento->replaceText($oDocumento->aParagrafos[1]->db02_texto);
    }

    /**
     * @param array $dados
     * @param DBDate $dataInicial
     * @param DBDate $dataFinal
     * @return string
     * @throws Exception
     */
    public function emitir(array $dados)
    {
        $this->AddPage();
        $titulo = "NOTIFICAÇÃO DE RECEBIMENTOS DE RECURSOS FEDERAIS";
        $subtitulo = "         Notifica o recebimento de recursos recebidos do Governo Federal.";

        $this->SetFont('Arial', 'b', 8);
        $this->Cell(192, 8, $titulo, 0, 1, 'C');
        $this->SetFont('Arial', '', 8);
        $this->Cell(192, 8, $subtitulo, 0, 1, 'L');
        $this->MultiCell(192, 4, $this->texto);

        $this->SetY($this->GetY() + 2);
        foreach ($dados as $dia) {
            $this->imprimirCorpo($dia);
        }

        if ($this->getAvailHeight() < 18) {
            $this->AddPage();
        }
        $this->SetY($this->GetY() + 2);
        $this->MultiCell(192, 4, $this->assinaturaContador, 0, 'C');

        return $this->imprimir();
    }

    private function imprimirCabecalhoData($data)
    {
        $this->SetFont('Arial', 'B', 8);
        $this->Cell(192, 6, "Recursos recebidos em: $data", 0, 1);
        $this->SetFont('Arial', '', 6);

        $this->Cell(50, 5, 'Orgão concessor', 'B', 0, 'L');
        $this->Cell(92, 5, 'Descrição do Recurso', 'B', 0, 'L');
        $this->Cell(20, 5, 'Código da Receita', 'B', 0, 'L');
        $this->Cell(30, 5, 'Valor Recebido (R$)', 'B', 1, 'R');
    }

    private function imprimirCorpo($dia)
    {
        if ($this->getAvailHeight() < 20) {
            $this->AddPage();
        }

        $dataRecebimento = $dia->data->getDate(DBDate::DATA_PTBR);
        $this->imprimirCabecalhoData($dataRecebimento);

        foreach ($dia->recursos as $recurso) {
            if ($this->getAvailHeight() < 8) {
                $this->AddPage();
                $this->imprimirCabecalhoData($dataRecebimento);
            }
            $this->cellAdapt(6, 50, 4, $recurso->orgao_concessor);
            $this->cellAdapt(6, 92, 4, $recurso->descricao_do_recurso);
            $this->Cell(20, 4, $recurso->codigo_da_receita, 0, 0, 'L');
            $this->Cell(30, 4, formataValorMonetario($recurso->valor_recebido), 0, 1, 'R');
        }

        $this->SetFont('Arial', 'B', 6);
        $this->Cell(162, 4, "Total de recursos recebidos em {$dataRecebimento}:", 'TB');
        $this->Cell(30, 4, formataValorMonetario($dia->total_recebido), 'TB', 1, 'R');
        $this->SetFont('Arial', '', 6);

        $this->SetY($this->GetY() + 4);
    }

    protected function imprimir()
    {
        $fileName = 'tmp/notificacao_recebimento_recursos_federais_' . time() . '.pdf';
        $this->Output($fileName, false, true);
        return ECIDADE_REQUEST_PATH . $fileName;
    }
}
