<?php


namespace App\Domain\Financeiro\Tesouraria\Services;

use App\Domain\Financeiro\Tesouraria\Relatorios\Tef\ListaMovimentosOperacoesCsv;
use App\Domain\Financeiro\Tesouraria\Relatorios\Tef\ListaMovimentosOperacoesPDF;
use DBDate;
use Illuminate\Support\Facades\DB;

class RelatorioListaOperacoesService
{
    private $dataInicio;
    private $dataFinal;

    public function setPeriodo($dataInicio, $dataFinal)
    {
        $this->dataInicio = $dataInicio;
        $this->dataFinal = $dataFinal;
    }

    public function emitir()
    {
        $data = $this->getRegistros();


        $documentos = array_merge($this->emitirPdf($data), $this->emitirCsv($data));

        return $documentos;
    }

    private function getRegistros()
    {
        $sql = "
            select linha_tef.*
              from linha_tef
              join linha_tef_processado on linha_tef_processado.linha_tef_id = linha_tef.id
              join conlancam on conlancam_id = c70_codlan
            where c70_data between '{$this->dataInicio}' and '{$this->dataFinal}'
        ";
        return DB::select($sql);
    }

    private function emitirPdf($data)
    {
        $periodo = sprintf(
            "Período: %s até %s",
            db_formatar($this->dataInicio, 'd'),
            db_formatar($this->dataFinal, 'd')
        );
        $pdf = new ListaMovimentosOperacoesPDF();

        $pdf->headers($periodo);
        $pdf->setData($data);

        return $pdf->emitir();
    }

    private function emitirCsv($data)
    {
        $csv = new ListaMovimentosOperacoesCsv();
        $csv->setData($data);
        return $csv->emitir();
    }
}
