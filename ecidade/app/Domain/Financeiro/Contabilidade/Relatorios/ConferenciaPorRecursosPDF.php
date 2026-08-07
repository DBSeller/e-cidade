<?php

namespace App\Domain\Financeiro\Contabilidade\Relatorios;

use DBDate;
use Exception;
use stdClass;

class ConferenciaPorRecursosPDF extends \FpdfMultiCellBorder
{
    /**
     * @var string
     */
    private $assinaturaContador;
    /**
     * @var string
     */

    private $filtros ;

    public function __construct(stdClass $filtros)
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

        $this->filtros = $filtros;

        global $head1, $head3;

        $head1 = "Conferência por Recursos";
        $dtInicial = db_formatar($this->filtros->dataInicial, "d");
        $dtFinal = db_formatar($this->filtros->dataFinal, "d");
        $head3 = "Data: {$dtInicial} à {$dtFinal}";
    }


    public function cabecalho($lImprime)
    {
        if ($this->getAvailHeight() < 4 || $lImprime) {
            $this->SetFont('arial', 'b', 7);

            if (!$lImprime) {
                $this->AddPage("L");
            }

            $this->cell(10, 4, "Recurso", "", 0, "C", 0);
            $this->cell(70, 4, "Descricao", "", 0, "C", 0);

            $this->cell(22, 4, "Saldo Ativo", "", 0, "R", 0);
            $this->cell(22, 4, "Saldo Extra", "", 0, "R", 0);
            $this->cell(22, 4, "Vlr. Liquidar", "", 0, "R", 0);
            $this->cell(22, 4, "Vlr. Pagar", "", 0, "R", 0);
            $this->cell(22, 4, "Vlr. Liq. RP", "", 0, "R", 0);
            $this->cell(22, 4, "Vlr. Pagar RP", "", 0, "R", 0);
            $this->cell(22, 4, "Total", "", 0, "R", 0);
            $this->cell(22, 4, "Vlr. Disp.", "", 0, "R", 0);
            $this->cell(22, 4, "Diferença", "", 1, "R", 0);
        }
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
        $this->AddPage("L");
        $this->SetFont('Arial', '', 7);
        $this->cabecalho(true);

        foreach ($dados['registros'] as $oDados) {
            $this->SetFont('Arial', '', 8);
            $this->cell(10, 4, $oDados->recurso, "", 0, "R", 0);

            $this->cellAdapt(7, 70, 4, $oDados->o15_descr);

            $this->cell(22, 4, $oDados->saldo_ativo_at_f, "", 0, "R", 0);
            $this->cell(22, 4, $oDados->saldo_extra_orcamentario, "", 0, "R", 0);
            $this->cell(22, 4, $oDados->valor_a_liquidar, "", 0, "R", 0);
            $this->cell(22, 4, $oDados->valor_a_pagar, "", 0, "R", 0);
            $this->cell(22, 4, $oDados->valor_a_liquidar_rp, "", 0, "R", 0);
            $this->cell(22, 4, $oDados->valor_a_pagar_rp, "", 0, "R", 0);
            $this->cell(22, 4, $oDados->total, "", 0, "R", 0);
            $this->cell(22, 4, $oDados->valor_disponibilidade, "", 0, "R", 0);
            $this->cell(22, 4, $oDados->diferenca, "", 1, "R", 0);
            $this->cabecalho(false);
        }

        $this->totalizadores($dados['totais']);

        return $this->imprimir();
    }

    public function totalizadores(stdClass $oTotais)
    {
        $this->ln();
        $this->SetFont('Arial', 'B', 7);
        $this->cell(80, 4, "TOTAIS", "", 0, "R", 0);
        $this->cell(22, 4, $oTotais->saldo_ativo_at_f, "", 0, "R", 0);
        $this->cell(22, 4, $oTotais->saldo_extra_orcamentario, "", 0, "R", 0);
        $this->cell(22, 4, $oTotais->valor_a_liquidar, "", 0, "R", 0);
        $this->cell(22, 4, $oTotais->valor_a_pagar, "", 0, "R", 0);
        $this->cell(22, 4, $oTotais->valor_a_liquidar_rp, "", 0, "R", 0);
        $this->cell(22, 4, $oTotais->valor_a_pagar_rp, "", 0, "R", 0);
        $this->cell(22, 4, $oTotais->total, "", 0, "R", 0);
        $this->cell(22, 4, $oTotais->valor_disponibilidade, "", 0, "R", 0);
        $this->cell(22, 4, $oTotais->diferenca, "", 1, "R", 0);
    }

    protected function imprimir()
    {
        $fileName = 'tmp/conferencia_por_recursos_' . time() . '.pdf';
        $this->Output($fileName, false, true);
        return  $fileName;
    }
}
