<?php

namespace app\domain\Financeiro\Empenho\Relatorios\RetencoesEfdReinf;

use App\Domain\Financeiro\Empenho\Relatorios\RetencoesEfdReinf\RelatorioRetencoesEfdReinfFiltros;
use ECidade\Pdf\Pdf;

class RelatorioRetencoesEfdReinfPdf extends Pdf
{

    /**
     * @var RetencoesEfdReinfFiltros $filtros
     */
    protected $filtros;
    protected $dados;
    

    public function __construct($orientation = 'L')
    {
        parent::__construct($orientation);
    }

    /**
     * Cabeçalho do relatório
     */
    public function headers()
    {
        $this->addTitulo("Relatório de Retenções da Efd Reinf");
        $this->addTitulo("Data  : ".$this->dados['headerData']);
        $this->addTitulo("Quebra: ".$this->dados['headerQuebra']);
        $this->addTitulo($this->dados['headerOrgaoUnidade']);

        $this->init(false);
    }

    /**
     * Unidade lógica de processamento
     */


    public function emitir()
    {
        $this->imprimir();
        
        $fileName = 'tmp/RetencoesEfdReinf' . time() . '.pdf';
        $this->output('F', $fileName);

        return [
            "name" => "Relatório Retenções EFD-Reinf PDF",
            "path" => $fileName,
            'pathExterno' => ECIDADE_REQUEST_PATH . $fileName
        ];
    }

    public function imprimir()
    {
        $sFonte = "Arial";
        $lEscreverHeader = true;
        $nTotalRetencoes = 0;
        
        $iTamCell = 0;
        $iTamFonte = 6;
       
        $this->addPage();
        $aRetencoes = $this->dados['retencoes'];

        foreach ($aRetencoes as $oQuebra) {
            $this->setFont($sFonte, "b", $iTamFonte + 2);
            $lEscreverHeader = true;

            foreach ($oQuebra->itens as $oRetencaoAtiva) {
                if ($this->Gety() > $this->getH() - 27 || $lEscreverHeader) {
                    if ($this->Gety() > $this->getH() - 27) {
                        $this->addPage();
                    }
                    if ($oQuebra->texto != "") {
                        $this->cell(0, 5, $oQuebra->texto, 0, 1);
                    }

                    $this->imprimeCabecalho($sFonte, $iTamCell, $iTamFonte);
                    $lEscreverHeader = false;
                }
                $this->imprimeDadosRelatorio($sFonte, $iTamCell, $iTamFonte, $oRetencaoAtiva);
            }
            $this->imprimeTotalizadorQuebra($sFonte, $iTamFonte, $oQuebra);
            $nTotalRetencoes += $oQuebra->total;
        }
        if (count($aRetencoes) > 0) {
            $this->imprimeTotalizadorGeral($sFonte, $iTamFonte, $nTotalRetencoes);
        }
    }

    public function imprimeDadosRelatorio($sFonte, $iTamCell, $iTamFonte, $oRetencaoAtiva)
    {

        $this->SetFont($sFonte, "", $iTamFonte);
        $this->cell(15 + $iTamCell, 5, $oRetencaoAtiva->numero_nota, "TBR", 0, "R", 0);
        $this->cell(20 + $iTamCell, 5, db_formatar($oRetencaoAtiva->data_emissao, 'd'), "TBR", 0, "C");
        $this->cell(65 + $iTamCell, 5, $oRetencaoAtiva->nome_prestador, "TBR", 0, "C");
        $this->cell(24, 5, $oRetencaoAtiva->cnpj_prestador, "TBR", 0, "C");
        $this->cell(16, 5, $oRetencaoAtiva->empenho_numero, "TBR", 0, "R");
        $this->cell(18 + $iTamCell, 5, $oRetencaoAtiva->retencao_tipo, "TBR", 0, "C");
        $this->cell(37 + $iTamCell, 5, $oRetencaoAtiva->indicativo_obra_tipo, "TBR", 0, "C");
        $this->cell(25, 5, $oRetencaoAtiva->indicativo_obra_cno, "TBR", 0, "C");
        $this->cell(22, 5, db_formatar($oRetencaoAtiva->valor_nota_liq, "f"), "TBR", 0, "C");
        $this->cell(14, 5, $oRetencaoAtiva->aliquota != "" ? $oRetencaoAtiva->aliquota."%": "", "TBR", 0, "C");
        $this->cell(22, 5, db_formatar($oRetencaoAtiva->valor_retencao, "f"), "TBR", 1, "C");
    }

    public function imprimeCabecalho($sFonte, $iTamCell, $iTamFonte)
    {
        $this->setFont($sFonte, "b", $iTamFonte + 1);

        $this->cell(15 + $iTamCell, 5, "NF", 1, 0, "C", 1);
        $this->cell(20, 5, "DATA DA NF", 1, 0, "C", 1);
        $this->cell(65 + $iTamCell, 5, "PRESTADOR DE SERVIÇO", 1, 0, "C", 1);
        $this->cell(24 + $iTamCell, 5, "CNPJ/CPF", 1, 0, "C", 1);
        $this->cell(16, 5, "EMPENHO", 1, 0, "C", 1);
        $this->cell(18 + $iTamCell, 5, "RETENÇÃO", 1, 0, "C", 1);
        $this->cell(37 + $iTamCell, 5, "TIPO DE SERVIÇO", 1, 0, "C", 1);
        $this->cell(25, 5, "CNO", 1, 0, "C", 1);
        $this->cell(22, 5, "VLR DA NL", 1, 0, "C", 1);
        $this->cell(14, 5, "ALÍQ", 1, 0, "C", 1);
        $this->cell(22, 5, "VLR RETIDO", 1, 1, "C", 1);
    }

    public function imprimeTotalizadorQuebra($sFonte, $iTamFonte, $oQuebra)
    {
        $this->SetFont($sFonte, "b", $iTamFonte);
        $this->cell(256, 5, "Total da Retenção:", "TBR", 0, "R");
        $this->cell(22, 5, db_formatar($oQuebra->total, "f"), "TBL", 1, "C");
    }

    public function imprimeTotalizadorGeral($sFonte, $iTamFonte, $nTotalRetencoes)
    {
        $this->SetFont($sFonte, "b", $iTamFonte);
        $this->cell(256, 5, "Total Geral:", "TBR", 0, "R");
        $this->cell(22, 5, db_formatar($nTotalRetencoes, "f"), "TBL", 1, "C");
    }
    /**
     * Inicialização do relatorio com informações sobre filtros e dados de processamento;
     */
    public function setDados($dados)
    {
        $this->dados = $dados;
    }

    public function setFiltros(RelatorioRetencoesEfdReinfFiltros $filtros)
    {
        $this->filtros = $filtros;
    }
}
