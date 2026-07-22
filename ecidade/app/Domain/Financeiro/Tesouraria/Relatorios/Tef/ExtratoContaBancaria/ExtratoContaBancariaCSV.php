<?php

namespace App\Domain\Financeiro\Tesouraria\Relatorios\Tef\ExtratoContaBancaria;

use App\Domain\Financeiro\Tesouraria\Relatorios\Tef\ExtratoContaBancaria\ExtratoContaBancariaModel;
use ECidade\File\Csv\Dumper\Dumper;

class ExtratoContaBancariaCSV extends Dumper
{

    /**
     * @var ExtratoContaBancariaModel
     */
    private $dados;

    public function setDadosRelatorio(ExtratoContaBancariaModel $dadosRelatorio)
    {
        $this->dados = $dadosRelatorio;
    }

    public function emitir()
    {

        $fileName = 'tmp/ExtratoContaBancaria' . time() . '.csv';
        $this->dumpToFile($this->imprimir(), $fileName);
        return [
            "name" => "Relatório extrato conta bancária CSV",
            "path" => $fileName,
            'pathExterno' => ECIDADE_REQUEST_PATH . $fileName
        ];
    }

    public function imprimir()
    {
        $this->dadosImprimir = [];

        $this->quebra_data = '';
        $this->lQuebra_Historico = false;
        $this->contas  = $this->dados->dados;

        if (count($this->contas) > 0) {
            foreach ($this->contas as $oConta) {
                $this->lImprimeSaldo = true;
                
                $this->dadosImprimir[] = $this->imprimeContaEspacoTxt();
                $this->dadosImprimir[] = $this->imprimeContaTxt(
                    $oConta->k13_reduz,
                    $oConta->k13_descr,
                    $oConta->debito,
                    $oConta->credito,
                    $this->lImprimeSaldo
                );
    
                $this->lImprimeSaldo = false;
                $this->dadosImprimir[] = $this->imprimeCabecalhoPrincipalTxt();
                $this->dadosImprimir[] = $this->imprimeCabecalhoSecundarioTxt();
                $this->processaDadosConta($oConta);
            }
        }

        return $this->dadosImprimir;
    }

    public function processaDadosConta($conta)
    {
        foreach ($conta->data as $oData) {
            if (property_exists($oData, 'movimentacoes') && !empty($oData->movimentacoes)) {
                $this->processaMovimentosConta($conta, $oData);
            }
           
            if ($this->dados->totalizador_diario == 's') {
                $this->dados->saldo_dia_credito = $oData->saldo_dia_credito;
                $this->dados->saldo_dia_debito = $oData->saldo_dia_debito;
                $this->dados->saldo_dia_final = $oData->saldo_dia_final;
            }
        }

        $this->quebra_data = '';

        $this->dadosImprimir[] = $this->imprimeTotalMovDiaTxt(
            $this->dados->saldo_dia_debito,
            $this->dados->saldo_dia_credito
        );
        $this->dadosImprimir[] = $this->imprimeSaldoDiaTxt($this->dados->saldo_dia_final);

        $this->dados->saldo_dia_credito = 0;
        $this->dados->saldo_dia_debito = 0;
        $this->dados->saldo_dia_final = 0;
        
        $this->dadosImprimir[] = $this->imprimeTotalMovContaTxt(
            $conta->debitado,
            $conta->creditado
        );
        $this->dadosImprimir[] = $this->imprimeTotalSaldoContaTxt($conta->atual);
    }


    public function processaMovimentosConta($oConta, $oData)
    {
        foreach ($oData->movimentacoes as $oMovimento) {
            if ($this->dados->totalizador_diario == 's' &&
                $this->quebra_data != '' &&
                $this->quebra_data != $oData->data
            ) {
                $this->dadosImprimir[] = $this->imprimeTotalMovDiaTxt(
                    $this->dados->saldo_dia_debito,
                    $this->dados->saldo_dia_credito
                );
                $this->dadosImprimir[] = $this->imprimeSaldoDiaTxt($this->dados->saldo_dia_final);
                
                $this->dados->saldo_dia_debito = 0;
                $this->dados->saldo_dia_credito = 0;
                $this->dados->saldo_dia_final = 0;
            }
        

            if ($this->lQuebra_Historico) {
                $this->dadosImprimir[] = $this->imprimeContaEspacoTxt();
                $this->dadosImprimir[] = $this->imprimeContaTxt(
                    $oConta->k13_reduz,
                    $oConta->k13_descr,
                    $oConta->debito,
                    $oConta->credito,
                    $this->lImprimeSaldo
                );
                $this->dadosImprimir[] = $this->imprimeCabecalhoPrincipalTxt();
                $this->dadosImprimir[] = $this->imprimeCabecalhoSecundarioTxt();
                $this->lQuebra_Historico = false;
            }

            $this->dadosImprimir[] = $this->imprimeDadosMovimentoTxt($oMovimento, $oData->data);
            
            if ($this->dados->imprime_analitico == 'a') {
                $this->imprimeAnalitico($oConta, $oMovimento);
            }
            
            $this->quebra_data = $oData->data;
        }
    }

    public function imprimeAnalitico($oConta, $oMovimento)
    {

        if (!isset($oMovimento->agrupado)) {
            $aLinhaDados = [
                '',
                "Autenticação mecânica:",
                '',
                '',
                trim($oMovimento->k12_codautent),
                '',
                '',
                '',
                ''
            ];
            $this->dadosImprimir[] =  $aLinhaDados;


            $aLinhaDados = [
                '',
                "Classificação de baixa bancária:",
                '',
                '',
                $oMovimento->codigo,
                '',
                '',
                '',
                ''
            ];
            $this->dadosImprimir[] =  $aLinhaDados;


            $aLinhaDados = [
                '',
                "Nome/Razão Social:",
                '',
                '',
                $oMovimento->credor,
                '',
                '',
                '',
                ''
            ];
            $this->dadosImprimir[] =  $aLinhaDados;

            $this->lQuebra_Historico = false;
            if ($oMovimento->historico != '' && $this->dados->imprime_historico == 's') {
                $aLinhaDados = [
                    '',
                    "Histórico:",
                    '',
                    $oMovimento->historico,
                    '',
                    '',
                    '',
                    '',
                    ''
                ];
                $this->dadosImprimir[] =  $aLinhaDados;
            }
        }
    }


    public function imprimeContaEspacoTxt()
    {
        return [
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            ''
        ];
    }

    public function imprimeContaTxt($codigo, $descricao, $debito, $credito, $lImprimeSaldo)
    {

        $aConta = [
            'CONTA',
            $codigo . " - " . $descricao,
            '',
            '',
            '',
            '',
            '',
            '',
            ''
        ];

        if ($lImprimeSaldo) {
            $aConta[6] = 'SALDO ANTERIOR:';
            $aConta[7] = $debito == 0 ? "" : db_formatar($debito, 'f');
            $aConta[8] = $credito == 0 ? "" : db_formatar($credito, 'f');
        }

        return $aConta;
    }

    public function imprimeCabecalhoPrincipalTxt()
    {
        
        return [
            'DATA',
            'CONTRAPARTIDA',
            'PLANILHA',
            'EMPENHO',
            'ORDEM',
            'CHEQUE',
            'SLIP',
            'DÉBITO',
            'CRÉDITO'
        ];
    }

    public function imprimeCabecalhoSecundarioTxt()
    {
        
        return [
            '',
            'INFORMAÇÕES COMPLEMENTARES',
            '',
            '',
            '',
            '',
            '',
            '',
            ''
        ];
    }
    

    public function imprimeTotalMovDiaTxt($saldoDiaDebito, $saldoDiaCredito)
    {
        
        return [
            '',
            '',
            '',
            '',
            '',
            '',
            'TOTAIS DA MOVIMENTAÇÃO NO DIA:',
            $saldoDiaDebito == 0 ? "" : db_formatar($saldoDiaDebito, 'f'),
            $saldoDiaCredito == 0 ? "" : db_formatar($saldoDiaCredito, 'f')
        ];
    }

    
    public function imprimeSaldoDiaTxt($saldoDiaFinal)
    {
        return [
            '',
            '',
            '',
            '',
            '',
            '',
            'SALDO NO DIA:',
            '',
            $saldoDiaFinal == 0 ? "" : db_formatar($saldoDiaFinal, 'f')
        ];
    }
    

    public function imprimeTotalMovContaTxt($saldoDebitado, $saldoCreditado)
    {

        return [
            '',
            '',
            '',
            '',
            '',
            '',
            'TOTAIS DA MOVIMENTAÇÃO 2:',
            $saldoDebitado == 0 ? "" : db_formatar($saldoDebitado, 'f'),
            $saldoCreditado == 0 ? "" : db_formatar($saldoCreditado, 'f')
        ];
    }
    
    public function imprimeTotalSaldoContaTxt($saldoAtual)
    {
        return [
            '',
            '',
            '',
            '',
            '',
            '',
            'SALDO FINAL:',
            '',
            $saldoAtual == 0 ? "" : db_formatar($saldoAtual, 'f')
        ];
    }

    public function imprimeDadosMovimentoTxt($oMovimento, $data)
    {
        return [
            db_formatar($data, 'd'),
            $oMovimento->contrapartida,
            $oMovimento->planilha,
            $oMovimento->empenho,
            $oMovimento->ordem == "0" ? "" : $oMovimento->ordem,
            $oMovimento->cheque == "0" ? "" : $oMovimento->cheque,
            $oMovimento->slip,
            $oMovimento->valor_debito == 0 ? "" : db_formatar($oMovimento->valor_debito, "f"),
            $oMovimento->valor_credito == 0 ? "" : db_formatar(
                $oMovimento->valor_credito,
                "f"
            )
        ];
    }
}
