<?php

namespace app\domain\Financeiro\Empenho\Relatorios\RetencoesEfdReinf;

use ECidade\File\Csv\Dumper\Dumper;
use App\Domain\Financeiro\Empenho\Relatorios\RetencoesEfdReinf\RelatorioRetencoesEfdReinfFiltros;

class RelatorioRetencoesEfdReinfCsv extends Dumper
{
    
    protected $dados;
    protected $filtros;

    /**
     * Unidade lógica de processamento
     */
    public function emitir()
    {

        $fileName = 'tmp/RetencoesEfdReinf' . time() . '.csv';
        $this->dumpToFile($this->imprimir(), $fileName);
        return [
            "name" => "Relatório Retenções EFD-Reinf CSV",
            "path" => $fileName,
            'pathExterno' => ECIDADE_REQUEST_PATH . $fileName
        ];
    }

    public function imprimir()
    {
        $this->dadosImprimir = [];
        $aRetencoes = $this->dados['retencoes'];
        $nTotalRetencoes = 0;

        foreach ($aRetencoes as $oQuebra) {
            $lEscreverHeader = true;

            foreach ($oQuebra->itens as $oRetencaoAtiva) {
                if ($lEscreverHeader) {
                    if ($oQuebra->texto != "") {
                        $this->dadosImprimir[] = $this->imprimeQuebra($oQuebra->texto);
                    }

                    $this->dadosImprimir[] = $this->imprimeCabecalho();
                    $lEscreverHeader = false;
                }
                $this->dadosImprimir[] = $this->imprimeDadosRelatorio($oRetencaoAtiva);
            }
            $this->dadosImprimir[] = $this->imprimeTotalizadorQuebra($oQuebra);
            $nTotalRetencoes += $oQuebra->total;
        }

        if (count($aRetencoes) > 0) {
            $this->dadosImprimir[] = $this->imprimeTotalizadorGeral($nTotalRetencoes);
        }
        return $this->dadosImprimir;
    }

    public function imprimeCabecalho()
    {

        $cabecalho = [
            "NF",
            "DATA DA NF",
            "PRESTADOR DE SERVIÇO",
            "CNPJ/CPF",
            "EMPENHO",
            "RETENÇÃO",
            "TIPO DE SERVIÇO",
            "CNO",
            "VLR DA NL",
            "ALÍQ",
            "VLR RETIDO"
        ];
        
        return $cabecalho;
    }

    public function imprimeQuebra($texto)
    {

        $quebra = [
            $texto,
            "",
            "",
            "",
            "",
            "",
            "",
            "",
            "",
            "",
            ""
        ];
        
        return $quebra;
    }

    public function imprimeDadosRelatorio($oRetencaoAtiva)
    {
        $dadosRelatorio = [
            $oRetencaoAtiva->numero_nota,
            db_formatar($oRetencaoAtiva->data_emissao, 'd'),
            $oRetencaoAtiva->nome_prestador,
            $oRetencaoAtiva->cnpj_prestador,
            $oRetencaoAtiva->empenho_numero,
            $oRetencaoAtiva->retencao_tipo,
            $oRetencaoAtiva->indicativo_obra_tipo,
            $oRetencaoAtiva->indicativo_obra_cno,
            db_formatar($oRetencaoAtiva->valor_nota_liq, "f"),
            $oRetencaoAtiva->aliquota != "" ? $oRetencaoAtiva->aliquota."%": "",
            db_formatar($oRetencaoAtiva->valor_retencao, "f")
        ];

        return $dadosRelatorio;
    }

    public function imprimeTotalizadorQuebra($oQuebra)
    {
        $totalizadorQuebra = [
            "",
            "",
            "",
            "",
            "",
            "",
            "",
            "",
            "",
            "Total da Retenção:",
            db_formatar($oQuebra->total, "f")
        ];
    }

    public function imprimeTotalizadorGeral($nTotalRetencoes)
    {
        $totalizadorGeral = [
            "",
            "",
            "",
            "",
            "",
            "",
            "",
            "",
            "",
            "Total Geral:",
            db_formatar($nTotalRetencoes, "f")
        ];
        return $totalizadorGeral;
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
