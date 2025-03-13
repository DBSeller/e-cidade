<?php

namespace App\Domain\Financeiro\Tesouraria\Services\Relatorios\ExtratoContaBancaria;

use App\Domain\Financeiro\Tesouraria\Relatorios\Tef\ExtratoContaBancaria\ExtratoContaBancariaPDF;
use App\Domain\Financeiro\Tesouraria\Relatorios\Tef\ExtratoContaBancaria\ExtratoContaBancariaCSV;
use App\Domain\Financeiro\Tesouraria\Relatorios\Tef\ExtratoContaBancaria\ExtratoContaBancariaModel;
use Illuminate\Support\Facades\DB;
use stdClass;
use Carbon\Carbon;
use Exception;

class RelatorioExtratoContaBancariaService extends ExtratoContaBancariaService
{
   
    public function setFiltrosRequest(array $filtros)
    {

        $this->setDataInicial($filtros['dataInicio']);
        $this->setDataFinal($filtros['dataFinal']);
        $this->setAno(Carbon::createFromFormat('d/m/Y', $filtros['dataFinal'])->year);
        $this->setInstit($filtros['DB_instit']);
        $this->setContaBancariaCodigo($filtros['contabancaria_codigo']);
        $this->setfiltroSomenteContasBancarias($filtros['somente_contas_bancarias']);
        $this->setfiltroSomenteContasComMovimento($filtros['somente_contas_com_movimento']);
        $this->setfiltroImprimeAnalitico($filtros['imprime_analitico']);
        $this->setfiltroImprimeHistorico($filtros['imprime_historico']);
        $this->setfiltroTotalizadorDiario($filtros['totalizador_diario']);
        $this->setfiltroAgrupaPor($filtros['agrupapor']);
        $this->setfiltroReceitasPor($filtros['receitaspor']);
        $this->setfiltroPagempenhos($filtros['pagempenhos']);
        $this->setfiltroModeloRelatorio($filtros['imprime_pdf']);
    }

    public function emitir()
    {

        $dados = $this->processar();
        $this->relatorio = $this->getInstanceRelatorio();
        
        $this->inicializaRelatorio($dados);
        
        if ($this->filtroModeloRelatorio == 'p') {
            $this->relatorio->headers();
        }

        $links = $this->relatorio->emitir();
        return $links;
    }
    
    public function processar()
    {
        return $this->getcontasMovimento();
    }

    public function getcontasMovimento()
    {
        $sqlContasMovimento = $this->sqlContasMovimento();

        $this->contasMovimento = [];

        $contasMovimento = DB::select($sqlContasMovimento);

        foreach ($contasMovimento as $contaMovimento) {
            if ($this->filtroSomenteContasComMovimento == 's' &&
                 ($contaMovimento->debitado == 0 && $contaMovimento->creditado == 0)) {
                continue;
            }
            
            $k13_reduz = $contaMovimento->k13_reduz;
            if (!array_key_exists($k13_reduz, $this->contasMovimento)) {
                $this->contasMovimento[$k13_reduz] = new stdClass();
            }

            $this->contasMovimento[$k13_reduz]->k13_reduz = $k13_reduz;
            $this->contasMovimento[$k13_reduz]->k13_descr = $contaMovimento->k13_descr;
            $this->contasMovimento[$k13_reduz]->k13_dtimplantacao = $contaMovimento->k13_dtimplantacao;
    
            // para contas bancárias, saldo positivo = debito, negativos indica debito
            if ($contaMovimento->anterior > 0) {
                $this->contasMovimento[$k13_reduz]->debito = $contaMovimento->anterior;
                $this->contasMovimento[$k13_reduz]->credito = 0;
            } else {
                $this->contasMovimento[$k13_reduz]->credito = $contaMovimento->anterior;
                $this->contasMovimento[$k13_reduz]->debito = 0;
            }
       
            $this->processaMovimentacaoConta($contaMovimento);
        }

        $this->agrupaContas();

        return $this->contasMovimento;
    }


    public function processaMovimentacaoConta($contamovimento)
    {

        $movimentacoesconta = DB::select($this->getMovimentacaoConta($contamovimento));

        $k13_reduz = $contamovimento->k13_reduz;
        $this->contasMovimento[$k13_reduz]->data = array();
        $this->saldo_dia_debito = 0;
        $this->saldo_dia_credito = 0;

        $iInd = -1;
        $quebra_data = '';
        $this->saldo_dia_final = $contamovimento->anterior;

        foreach ($movimentacoesconta as $movimentacao) {
            /* if($this->filtroConsiderarRetencoes == "n"){
                if ($movimentacao->ordem > 0 &&
                    ($movimentacao->k105_corgrupotipo == 0 || $movimentacao->k105_corgrupotipo == 2)) {
                    continue;
                }
            } */
            // controla quebra de saldo por dia
            if ($quebra_data != $movimentacao->data && $quebra_data != '' && $this->filtroTotalizadorDiario == 's') {
                $this->contasMovimento[$k13_reduz]->data[$iInd]->saldo_dia_debito = $this->saldo_dia_debito;
                $this->contasMovimento[$k13_reduz]->data[$iInd]->saldo_dia_credito = $this->saldo_dia_credito;
                // calcula saldo a debito ou credito
                if ($this->saldo_dia_debito < 0) {
                    $this->saldo_dia_final -= abs($this->saldo_dia_debito);
                    $this->contasMovimento[$k13_reduz]->data[$iInd]->saldo_dia_final = $this->saldo_dia_final;
                } else {
                    $this->saldo_dia_final += $this->saldo_dia_debito;
                    $this->contasMovimento[$k13_reduz]->data[$iInd]->saldo_dia_final = $this->saldo_dia_final;
                }
                if ($this->saldo_dia_credito < 0) {
                    $this->saldo_dia_final += abs($this->saldo_dia_credito);
                    $this->contasMovimento[$k13_reduz]->data[$iInd]->saldo_dia_final = $this->saldo_dia_final;
                } else {
                    $this->saldo_dia_final -= $this->saldo_dia_credito;
                    $this->contasMovimento[$k13_reduz]->data[$iInd]->saldo_dia_final = $this->saldo_dia_final;
                }

                $this->saldo_dia_debito = 0;
                $this->saldo_dia_credito = 0;
            }


            if ($quebra_data != $movimentacao->data) {
                $iInd++;

                if (!array_key_exists($iInd, $this->contasMovimento[$k13_reduz]->data)) {
                    $this->contasMovimento[$k13_reduz]->data[$iInd] = new stdClass();
                }

                $this->contasMovimento[$k13_reduz]->data[$iInd]->data = $movimentacao->data;
                $this->contasMovimento[$k13_reduz]->data[$iInd]->movimentacoes = array();
            }


            $oMovimentacao = new stdClass();
            $oMovimentacao->caixa = $movimentacao->caixa;
            $oMovimentacao->valor_debito = $movimentacao->valor_debito;
            $oMovimentacao->valor_credito = $movimentacao->valor_credito;
            $oMovimentacao->receita = $movimentacao->receita;
            $oMovimentacao->k12_codautent = $movimentacao->k12_codautent;
            $oMovimentacao->codigo = $movimentacao->codigo;
            $oMovimentacao->credor = $movimentacao->credor;
            $oMovimentacao->codigocredor = $movimentacao->numcgm;
            $oMovimentacao->codret = $movimentacao->codret;
            $oMovimentacao->dtretorno = $movimentacao->dtretorno != ''
                ? db_formatar($movimentacao->dtretorno, 'd') : '';
            $oMovimentacao->arqret = $movimentacao->arqret;
            $oMovimentacao->dtarquivo = $movimentacao->dtarquivo != ''
                ? db_formatar($movimentacao->dtarquivo, 'd') : '';
            $oMovimentacao->tipo = $movimentacao->tipo;
            $oMovimentacao->planilha = $movimentacao->tipo == 'planilha' ? $movimentacao->codigo : '';
            $oMovimentacao->k12_codcla = $movimentacao->tipo == 'baixa' ? $movimentacao->codigo : '';
            $oMovimentacao->ordem = $movimentacao->ordem;
            $oMovimentacao->empenho = $movimentacao->tipo == 'empenho' ? $movimentacao->codigo : '';
            $oMovimentacao->cheque = $movimentacao->cheque;
            $oMovimentacao->slip = $movimentacao->tipo == 'slip' ? $movimentacao->codigo : '';


            // DEBITO E CREDITO
            if ($movimentacao->valor_debito == 0 && $movimentacao->valor_credito != 0) {
                $oMovimentacao->valor_debito = '';
                //Modificação feita para acertar a forma quando é mostrada os valores relativos as planilha de dedução
                if ($movimentacao->tipo == 'planilha') {
                    $movimentacao->valor_credito = $movimentacao->valor_credito * -1;
                    $oMovimentacao->valor_credito = $movimentacao->valor_credito;
                } else {
                    $oMovimentacao->valor_credito = $movimentacao->valor_credito;
                }
            } elseif ($movimentacao->valor_credito == 0 && $movimentacao->valor_debito != 0) {
                $oMovimentacao->valor_debito = $movimentacao->valor_debito;
                $oMovimentacao->valor_credito = $movimentacao->valor_credito;
            } else {
                $oMovimentacao->valor_debito = $movimentacao->valor_debito;
                $oMovimentacao->valor_credito = $movimentacao->valor_credito;
            }

            $c61_reduz = '';
            if ($movimentacao->receita > 0) {
                $reduzido_conta = DB::select($this->getReduzidoReceita($movimentacao->receita));
                if (count($reduzido_conta) > 0) {
                    $c61_reduz  = $reduzido_conta[0]->c61_reduz;
                }
            }
            
            $oMovimentacao->contrapartida = '';
            if ($movimentacao->tipo == 'recibo' ||
                $movimentacao->tipo == 'planilha' ||
                $movimentacao->tipo == 'Baixa') {
                if ($movimentacao->receita > 0) {
                    $oMovimentacao->contrapartida = "{$movimentacao->receita} ";
                    if (!empty($c61_reduz)) {
                        $oMovimentacao->contrapartida .= "({$c61_reduz}) - ";
                    }
                    $oMovimentacao->contrapartida .= $movimentacao->receita_descr;
                }
            }

            if ($movimentacao->tipo == 'slip') {
                $oMovimentacao->contrapartida = $movimentacao->contrapartida;
            }

            $oMovimentacao->credor = $movimentacao->credor;
            $oMovimentacao->historico = $movimentacao->historico;

            // soma acumuladores diarios
            $this->saldo_dia_debito += $movimentacao->valor_debito;
            $this->saldo_dia_credito += $movimentacao->valor_credito;

            $quebra_data = $movimentacao->data;

            $this->contasMovimento[$k13_reduz]->data[$iInd]->movimentacoes[] = $oMovimentacao;
        }

        if ($this->filtroTotalizadorDiario == 's') {
            // calcula saldo a debito ou credito
            $this->contasMovimento[$k13_reduz]->data[$iInd]->saldo_dia_debito = $this->saldo_dia_debito;
            $this->contasMovimento[$k13_reduz]->data[$iInd]->saldo_dia_credito = $this->saldo_dia_credito;
            // calcula saldo a debito ou credito
            if ($this->saldo_dia_debito < 0) {
                $this->saldo_dia_final -= abs($this->saldo_dia_debito);
                $this->contasMovimento[$k13_reduz]->data[$iInd]->saldo_dia_final = $this->saldo_dia_final;
            } else {
                $this->saldo_dia_final += $this->saldo_dia_debito;
                $this->contasMovimento[$k13_reduz]->data[$iInd]->saldo_dia_final = $this->saldo_dia_final;
            }
            if ($this->saldo_dia_credito < 0) {
                $this->saldo_dia_final += abs($this->saldo_dia_credito);
                $this->contasMovimento[$k13_reduz]->data[$iInd]->saldo_dia_final = $this->saldo_dia_final;
            } else {
                $this->saldo_dia_final -= $this->saldo_dia_credito;
                $this->contasMovimento[$k13_reduz]->data[$iInd]->saldo_dia_final = $this->saldo_dia_final;
            }
        }

        $this->contasMovimento[$k13_reduz]->debitado = $contamovimento->debitado;
        $this->contasMovimento[$k13_reduz]->creditado = $contamovimento->creditado;
        $this->contasMovimento[$k13_reduz]->atual = $contamovimento->atual;
    }

    public function getMovimentacaoConta($contamovimento)
    {

        $this->setReduzido($contamovimento->k13_reduz);
        $sqlcontaMovimento= $this->sqlMovimentacaoConta();

        return $sqlcontaMovimento;
    }

    public function agrupaContas()
    {
        if ($this->filtroAgrupaPor != 1 || $this->filtroReceitasPor == 2) {
            $aMovimentacao = array();
            $aContasNovas = array();
            foreach ($this->contasMovimento as $key2 => $oConta) {
                $aContasNovas[$key2] = $oConta;
                foreach ($oConta->data as $key1 => $oData) {
                    foreach ($oData->movimentacoes as $oMovimento) {
                        if ($this->filtroReceitasPor == 2 && $oMovimento->tipo == 'Baixa') {
                            $controle = false;
                            foreach ($aMovimentacao as $key => $oValor) {
                                if ($oValor->tipo == $oMovimento->tipo &&
                                    $oValor->codigo == $oMovimento->codigo &&
                                    $controle == false) {
                                    $controle = true;
                                    $chave = $key;
                                }
                            }
                            if ($controle) {
                                $aMovimentacao[$chave]->valor_debito += $oMovimento->valor_debito;
                                $aMovimentacao[$chave]->valor_credito += $oMovimento->valor_credito;
                                $aMovimentacao[$chave]->caixa = '';
                                $aMovimentacao[$chave]->planilha = '';
                                $aMovimentacao[$chave]->empenho = '';
                                $aMovimentacao[$chave]->ordem = '';
                                $aMovimentacao[$chave]->cheque = '';
                                $aMovimentacao[$chave]->slip = '';
                                $aMovimentacao[$chave]->contrapartida = 'Baixa Bancária ref Arquivo ';
                                $aMovimentacao[$chave]->contrapartida .= "{$oMovimento->arqret}, do dia ";
                                $aMovimentacao[$chave]->contrapartida .= "{$oMovimento->dtarquivo}, retorno ";
                                $aMovimentacao[$chave]->contrapartida .= "{$oMovimento->codret} de ";
                                $aMovimentacao[$chave]->contrapartida .= $oMovimento->dtretorno;
                                $aMovimentacao[$chave]->credor = '';
                                $aMovimentacao[$chave]->historico = '';
                                $aMovimentacao[$chave]->agrupado = 'Baixa';
                            } else {
                                $oMovimento->contrapartida = 'Baixa Bancária ref Arquivo ';
                                $oMovimento->contrapartida .= "{$oMovimento->arqret}, do dia ";
                                $oMovimento->contrapartida .= "{$oMovimento->dtarquivo}, retorno ";
                                $oMovimento->contrapartida .= "{$oMovimento->codret} de ";
                                $oMovimento->contrapartida .= $oMovimento->dtretorno;
    
                                $aMovimentacao[] = $oMovimento;
                            }
                        } else {
                            if ($this->filtroAgrupaPor == 2 &&
                                $oMovimento->receita != '0' &&
                                $oMovimento->tipo != 'Baixa') {
                                $controle = false;

                                if ($oMovimento->tipo == 'slip') {
                                    $aMovimentacao[] = $oMovimento;
                                } else {
                                    foreach ($aMovimentacao as $key => $oValor) {
                                        if ($oValor->receita == $oMovimento->receita && $controle == false) {
                                            $controle = true;
                                            $chave = $key;
                                        }
                                    }
                                    if ($controle) {
                                        $aMovimentacao[$chave]->valor_debito += $oMovimento->valor_debito ?: 0;
                                        $aMovimentacao[$chave]->valor_credito += $oMovimento->valor_credito;
                                        $aMovimentacao[$chave]->caixa = '';
                                        $aMovimentacao[$chave]->k123_codautent = '';
                                        $aMovimentacao[$chave]->tipo = '';
                                        $aMovimentacao[$chave]->planilha = '';
                                        $aMovimentacao[$chave]->empenho = '';
                                        $aMovimentacao[$chave]->ordem = '';
                                        $aMovimentacao[$chave]->cheque = '';
                                        $aMovimentacao[$chave]->slip = '';
                                        $aMovimentacao[$chave]->contrapartida = $oMovimento->contrapartida;
                                        $aMovimentacao[$chave]->credor = '';
                                        $aMovimentacao[$chave]->historico = '';
                                        $aMovimentacao[$chave]->agrupado = 'receita';
                                    } else {
                                        $aMovimentacao[] = $oMovimento;
                                    }
                                }
                            } else {
                                if ($this->filtroAgrupaPor == 3 && $oMovimento->tipo == 'empenho') {
                                    $controle = false;
                                    foreach ($aMovimentacao as $key => $oValor) {
                                        if ($oValor->receita == $oMovimento->receita &&
                                            $oValor->codigo == $oMovimento->codigo &&
                                            $oValor->tipo == $oMovimento->tipo &&
                                            $controle == false) {
                                            $controle = true;
                                            $chave = $key;
                                        }
                                    }
                                    if ($controle) {
                                        $aMovimentacao[$chave]->valor_debito += $oMovimento->valor_debito;
                                        $aMovimentacao[$chave]->valor_credito += $oMovimento->valor_credito;
                                        $aMovimentacao[$chave]->caixa = '';
                                        $aMovimentacao[$chave]->k123_codautent = '';
                                        $aMovimentacao[$chave]->planilha = '';
                                        $aMovimentacao[$chave]->ordem = '';
                                        $aMovimentacao[$chave]->cheque = '';
                                        $aMovimentacao[$chave]->slip = '';
                                        $aMovimentacao[$chave]->contrapartida = $oMovimento->credor;
                                        $aMovimentacao[$chave]->credor = '';
                                        $aMovimentacao[$chave]->historico = '';
                                        $aMovimentacao[$chave]->agrupado = 'empenho';
                                    } else {
                                        $oMovimento->contrapartida = $oMovimento->credor;
    
                                        $aMovimentacao[] = $oMovimento;
                                    }
                                } else {
                                    if ($this->filtroAgrupaPor == 2 && $this->filtroPagempenhos == 2) {
                                        $controle = false;
                                        if ($oMovimento->tipo != 'empenho') {
                                            $aMovimentacao[] = $oMovimento;
                                        } else {
                                            foreach ($aMovimentacao as $key => $oValor) {
                                                if ($oValor->ordem == $oMovimento->ordem &&
                                                    $controle == false &&
                                                    $oValor->tipo == 'empenho') {
                                                    $controle = true;
                                                    $chave = $key;
                                                }
                                            }
                                            if ($controle) {
                                                $aMovimentacao[$chave]->valor_debito += $oMovimento->valor_debito;
                                                $aMovimentacao[$chave]->valor_credito += $oMovimento->valor_credito;
    
                                                if ($oMovimento->tipo == 'empenho' && $oMovimento->empenho != '') {
                                                    $oMovimento->contrapartida  = "{$oMovimento->codigocredor}";
                                                    $oMovimento->contrapartida .= " - {$oMovimento->credor}";
                                                    $aMovimentacao[$chave]->contrapartida = $oMovimento->contrapartida;
                                                }
                                            } else {
                                                if ($oMovimento->tipo == 'empenho' && $oMovimento->empenho != '') {
                                                    $oMovimento->contrapartida  = "{$oMovimento->codigocredor}";
                                                    $oMovimento->contrapartida .= " - {$oMovimento->credor}";
                                                }
                                                if ($oMovimento->tipo == 'empenho' || $oMovimento->tipo == 'slip') {
                                                    $oMovimento->codigo = '';
                                                }
                                                $aMovimentacao[] = $oMovimento;
                                            }
                                        }
                                    } else {
                                        if ($this->filtroPagempenhos == 2 && $this->filtroImprimeAnalitico == 's') {
                                            if ($oMovimento->tipo != 'empenho') {
                                                $aMovimentacao[] = $oMovimento;
                                            } else {
                                                $controle = false;
                                                foreach ($aMovimentacao as $key => $oValor) {
                                                    if ($oValor->ordem == $oMovimento->ordem &&
                                                        $oValor->tipo == $oMovimento->tipo &&
                                                        $controle == false &&
                                                        $oValor->tipo == 'empenho') {
                                                        $controle = true;
                                                        $chave = $key;
                                                    }
                                                }
                                                if ($controle) {
                                                    $aMovimentacao[$chave]->valor_debito += $oMovimento->valor_debito;
                                                    $aMovimentacao[$chave]->valor_credito += $oMovimento->valor_credito;
                                                    $aMovimentacao[$chave]->caixa = '';
                                                    $aMovimentacao[$chave]->k123_codautent = '';
                                                    $aMovimentacao[$chave]->planilha = '';
                                                    $aMovimentacao[$chave]->cheque = '';
                                                    $aMovimentacao[$chave]->slip = '';
                                                    $aMovimentacao[$chave]->contrapartida = $oMovimento->credor;
                                                    $aMovimentacao[$chave]->credor = '';
                                                    $aMovimentacao[$chave]->historico = '';
                                                    $aMovimentacao[$chave]->agrupado = 'empenho';
                                                } else {
                                                    if ($oMovimento->tipo == 'empenho' && $oMovimento->empenho != '') {
                                                        $oMovimento->contrapartida  = "{$oMovimento->codigocredor}";
                                                        $oMovimento->contrapartida .= " - {$oMovimento->credor}";
                                                    }
                                                    if ($oMovimento->tipo == 'empenho' || $oMovimento->tipo == 'slip') {
                                                        $oMovimento->codigo = '';
    
                                                        $aMovimentacao[] = $oMovimento;
                                                    }
                                                }
                                            }
                                        } else {
                                            if ($oMovimento->tipo == 'empenho' && $oMovimento->empenho != '') {
                                                $oMovimento->contrapartida  = $oMovimento->codigocredor . " - " ;
                                                $oMovimento->contrapartida .= $oMovimento->credor;
                                            }
                                            if ($oMovimento->tipo == 'empenho' || $oMovimento->tipo == 'slip') {
                                                $oMovimento->codigo = '';
                                            }
                                            $aMovimentacao[] = $oMovimento;
                                        }
                                    }
                                }
                            }
                        }
                    }
                    $aContasNovas[$oConta->k13_reduz]->data[$key1]->movimentacoes = $aMovimentacao;
                    $aMovimentacao = array();
                }
            }
    
            $this->contasMovimento = $aContasNovas;
        } elseif ($this->filtroAgrupaPor == 1 && $this->filtroPagempenhos == 2) {
                $aMovimentacao = array();
                $aContasNovas = array();
    
            foreach ($this->contasMovimento as $key2 => $oConta) {
                $aContasNovas[$key2] = $oConta;
                foreach ($oConta->data as $key1 => $oData) {
                    foreach ($oData->movimentacoes as $oMovimento) {
                        $controle = false;
    
                        if ($oMovimento->tipo != 'empenho') {
                            $aMovimentacao[] = $oMovimento;
                        } else {
                            foreach ($aMovimentacao as $key => $oValor) {
                                if ($oValor->ordem == $oMovimento->ordem &&
                                    $controle == false &&
                                    $oMovimento->tipo == 'empenho' &&
                                    $oValor->tipo == 'empenho') {
                                    $controle = true;
                                    $chave = $key;
                                }
                            }
                            if ($controle) {
                                $aMovimentacao[$chave]->valor_debito += $oMovimento->valor_debito;
                                $aMovimentacao[$chave]->valor_credito += $oMovimento->valor_credito;
                                if ($oMovimento->tipo == 'empenho' && $oMovimento->empenho != '') {
                                    $oMovimento->contrapartida  = $oMovimento->codigocredor . " - ";
                                    $oMovimento->contrapartida .= $oMovimento->credor;
                                    $aMovimentacao[$chave]->contrapartida = $oMovimento->contrapartida;
                                }
                            } else {
                                if ($oMovimento->tipo == 'empenho' && $oMovimento->empenho != '') {
                                    $oMovimento->contrapartida = "{$oMovimento->codigocredor} - {$oMovimento->credor}";
                                }
                                $aMovimentacao[] = $oMovimento;
                            }
                        }
                    }
                    $aContasNovas[$oConta->k13_reduz]->data[$key1]->movimentacoes = $aMovimentacao;
                    $aMovimentacao = array();
                }
            }

                $this->contasMovimento = $aContasNovas;
        }
    }

    public function getInstanceRelatorio()
    {
        if ($this->filtroModeloRelatorio == 'p') {
            $relatorio = new ExtratoContaBancariaPDF();
        } else {
            $relatorio = new ExtratoContaBancariaCSV();
        }
        return $relatorio;
    }

    public function inicializaRelatorio($dados)
    {
        
        $relatorioDados = new ExtratoContaBancariaModel();
        $relatorioDados->setTotalizadorDiario($this->filtroTotalizadorDiario);
        $relatorioDados->setImprimeAnalitico($this->filtroImprimeAnalitico);
        $relatorioDados->setImprimeHistorico($this->filtroImprimeHistorico);
        $relatorioDados->setSomenteContasBancarias($this->filtroSomenteContasBancarias);
        $relatorioDados->setDataInicial($this->dataInicial);
        $relatorioDados->setDataFinal($this->dataFinal);
        $relatorioDados->setAgrupaPor($this->filtroAgrupaPor);
        $relatorioDados->setReceitasPor($this->filtroReceitasPor);
        $relatorioDados->setDados($dados);

        $this->relatorio->setDadosRelatorio($relatorioDados);
    }
}
