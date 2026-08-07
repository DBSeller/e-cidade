<?php

namespace App\Domain\Patrimonial\Material\Services;

use App\Domain\Configuracao\Departamento\Models\Departamento;
use App\Domain\Patrimonial\Material\Models\Deposito;
use App\Domain\Patrimonial\Material\Models\Material;
use App\Domain\Patrimonial\Material\Models\MovimentacaoEstoqueItem;
use Exception;

class ControleEstoqueService
{
    private $dataFinal;
    private $dataInicial;
    /**
     * @var Deposito
     */
    private $deposito;

    public function setDataInicial($dataInicial)
    {
        $this->dataInicial = $dataInicial;
    }

    public function setDataFinal($dataFinal)
    {
        $this->dataFinal = $dataFinal;
    }

    public function setDeposito(Deposito $deposito)
    {
        $this->deposito = $deposito;
    }

    /**
     * @param Material $material
     * @return []
     * @throws Exception
     */
    public function buscarDadosRelatorio(Material $material)
    {
        $movimentacaoEstoqueItem = MovimentacaoEstoqueItem::select('matestoqueinimei.*')
            ->join('matestoqueini', 'm80_codigo', '=', 'm82_matestoqueini')
            ->join('matestoquetipo', 'm80_codtipo', '=', 'm81_codtipo')
            ->join('matestoqueitem', 'm82_matestoqueitem', '=', 'm71_codlanc')
            ->join('matestoque', 'm71_codmatestoque', '=', 'm70_codigo')
            ->join('db_depart as departamento_origem', 'm70_coddepto', '=', 'coddepto')
            ->join('db_almox', 'm91_depto', '=', 'coddepto')
            ->where('instit', '=', db_getsession('DB_instit'))
            ->where('m70_codmatmater', '=', $material->m60_codmater)
            ->where('m71_servico', '=', false)
            ->whereIn('m81_tipo', [1, 2]);

        if (!empty($this->dataInicial) && !empty($this->dataFinal)) {
            $movimentacaoEstoqueItem->whereBetween('m80_data', [$this->dataInicial, $this->dataFinal]);
        } elseif (!empty($this->dataInicial)) {
            $movimentacaoEstoqueItem->where('m80_data', '>=', $this->dataInicial);
        } elseif (!empty($this->dataFinal)) {
            $movimentacaoEstoqueItem->where('m80_data', '<=', $this->dataFinal);
        }

        if (!empty($this->deposito->m91_codigo)) {
            $movimentacaoEstoqueItem->where('m91_codigo', '=', $this->deposito->m91_codigo);
        }

        $movimentacaoEstoqueItem->orderBy('m80_data')
            ->orderBy('m80_hora')
            ->orderBy('m80_codigo')
            ->orderBy('m82_codigo');

        $movimentosExcluir = [];
        $movimentacoes = $movimentacaoEstoqueItem->get()->reduce(
            function ($depositos, $movimento) use ($material, &$movimentosExcluir) {
                $codigoDepartamento = $movimento->estoqueItem->estoque->m70_coddepto;
                if (is_null($depositos) || !array_key_exists($codigoDepartamento, $depositos)) {
                    $departamento = Departamento::find($codigoDepartamento);
                    list(
                        $quantidadeAnterior,
                        $saldoAnterior
                        ) = $this->buscarSaldoAnterior($departamento->deposito, $material);
                    $depositos[$codigoDepartamento] = (object)[
                        "codigo_departamento" => $codigoDepartamento,
                        "codigo" => $departamento->deposito->m91_codigo,
                        "descricao" => $departamento->getDescricao(),
                        "quantidade_anterior" => $quantidadeAnterior,
                        "saldo_anterior" => $saldoAnterior,
                        "tem_ajustes" => false,
                        "lancamentos" => []
                    ];
                }

                $movimentacao = (object)[
                    'data' => $movimento->lancamento->m80_data,
                    'matestoqueini' => $movimento->lancamento->m80_codigo,
                    'codigo_tipo_movimento' => $movimento->lancamento->m80_codtipo,
                    'descricao_movimentacao' => $movimento->lancamento->tipoLancamento->m81_descr,
                    'deposito' => $movimento->lancamento->departamentoDestino->descrdepto,
                    'login' => $movimento->lancamento->usuario->login,
                    'quantidade_entrada' => 0,
                    'valor_unitario_entrada' => 0,
                    'total_entrada' => 0,
                    'quantidade_saida' => 0,
                    'valor_unitario_saida' => 0,
                    'total_saida' => 0,
                    'saldo_quantidade' => 0,
                    'saldo_valor_unitario' => 0,
                    'saldo_subtotal' => 0
                ];

                $valorFinanceiro = $movimento->m82_quant * $movimento->valores->m89_valorunitario;
                if ($movimento->lancamento->tipoLancamento->m81_tipo == 1) {
                    $movimentacao->quantidade_entrada = $movimento->m82_quant;
                    $movimentacao->valor_unitario_entrada = $movimento->valores->m89_valorunitario;
                    $movimentacao->total_entrada = $valorFinanceiro;
                } else {
                    $movimentacao->quantidade_saida = $movimento->m82_quant;
                    $movimentacao->valor_unitario_saida = $movimento->valores->m89_valorunitario;
                    $movimentacao->total_saida = $valorFinanceiro;
                }

                if (count($depositos[$codigoDepartamento]->lancamentos) > 0) {
                    $keyAnterior = count($depositos[$codigoDepartamento]->lancamentos) - 1;
                    $movimentoAnterior = $depositos[$codigoDepartamento]->lancamentos[$keyAnterior];
                    $quantidade_anterior = $movimentoAnterior->saldo_quantidade;
                    $subtotal_anterior = $movimentoAnterior->saldo_subtotal;
                } else {
                    $quantidade_anterior = $depositos[$codigoDepartamento]->quantidade_anterior;
                    $subtotal_anterior = $depositos[$codigoDepartamento]->saldo_anterior;
                }

                /** @TODO fazer verificação
                 * fazer verificação se o m89_valorunitario da movimentacao é igual a
                 * saldo_quantidade / saldo_subtotal
                 */

                $quantidade_entrada = $quantidade_anterior + $movimentacao->quantidade_entrada;
                $movimentacao->saldo_quantidade = ($quantidade_entrada) - $movimentacao->quantidade_saida;
                $movimentacao->saldo_valor_unitario = $movimento->valores->m89_precomedio;
                $subtotal_entrada = $subtotal_anterior + $movimentacao->total_entrada;
                $movimentacao->saldo_subtotal = bcsub($subtotal_entrada, $movimentacao->total_saida, 5);

                /**
                 * Ajuste de estoque, caso tenha no relatório - Unificar entrada e saida em apenas um
                 */
                if (isset($movimentoAnterior) &&
                    $movimentacao->codigo_tipo_movimento == 998 &&
                    $movimentoAnterior->codigo_tipo_movimento == 999) {
                    $depositos[$codigoDepartamento]->tem_ajustes = true;
                    $keyAnterior = count($depositos[$codigoDepartamento]->lancamentos) - 1;
                    $movimentosExcluir[] = $codigoDepartamento."#".$keyAnterior;
                    $diferencaAjusteEstoque = $movimentacao->total_saida - $movimentoAnterior->total_entrada;
                    if ($diferencaAjusteEstoque < 0) {
                        $movimentacao->matestoqueini = $movimentoAnterior->matestoqueini;
                        $movimentacao->descricao_movimentacao = $movimentoAnterior->descricao_movimentacao;
                        $movimentacao->total_entrada = abs($diferencaAjusteEstoque);
                        $movimentacao->quantidade_entrada = 0;
                        $movimentacao->valor_unitario_entrada = 0;
                        $movimentacao->quantidade_saida = 0;
                        $movimentacao->valor_unitario_saida = 0;
                        $movimentacao->total_saida = 0;
                    } else {
                        $movimentacao->total_saida = $diferencaAjusteEstoque;
                        $movimentacao->quantidade_saida = 0;
                        $movimentacao->valor_unitario_saida = 0;
                        $movimentacao->quantidade_entrada = 0;
                        $movimentacao->valor_unitario_entrada = 0;
                        $movimentacao->total_entrada = 0;
                    }
                }

                $depositos[$codigoDepartamento]->lancamentos[] = $movimentacao;
                return $depositos;
            }
        );

        if (is_null($movimentacoes)) {
            return [];
        }

        foreach ($movimentosExcluir as $movimentoExcluir) {
            list($departamento, $key) = explode('#', $movimentoExcluir);
            unset($movimentacoes[$departamento]->lancamentos[$key]);
        }

        return $movimentacoes;
    }

    private function buscarSaldoAnterior(Deposito $deposito, Material $material)
    {
        $quantidade = 0;
        $saldo = 0;
        if (empty($this->dataInicial)) {
            return [$quantidade, $saldo];
        }

        $movimentacaoEstoqueItem = MovimentacaoEstoqueItem::select('matestoqueinimei.*')
            ->join('matestoqueini', 'm80_codigo', '=', 'm82_matestoqueini')
            ->join('matestoquetipo', 'm80_codtipo', '=', 'm81_codtipo')
            ->join('matestoqueitem', 'm82_matestoqueitem', '=', 'm71_codlanc')
            ->join('matestoque', 'm71_codmatestoque', '=', 'm70_codigo')
            ->join('db_depart as departamento_origem', 'm70_coddepto', '=', 'coddepto')
            ->join('db_almox', 'm91_depto', '=', 'coddepto')
            ->where('instit', '=', db_getsession('DB_instit'))
            ->where('m70_codmatmater', '=', $material->m60_codmater)
            ->where('m71_servico', '=', false)
            ->where('m80_data', '<', $this->dataInicial)
            ->where('m91_codigo', '=', $deposito->m91_codigo)
            ->whereIn('m81_tipo', [1, 2]);

        $totais = $movimentacaoEstoqueItem->get()->reduce(function ($acc, $movimentacao) {
            $valorMovimentacao = $movimentacao->m82_quant * $movimentacao->valores->m89_valorunitario;
            if ($movimentacao->lancamento->tipoLancamento->m81_tipo === 1) {
                $acc->quantidade += $movimentacao->m82_quant;
                $acc->saldo += $valorMovimentacao;
            } else {
                $acc->quantidade -= $movimentacao->m82_quant;
                $acc->saldo -= $valorMovimentacao;
            }
            return $acc;
        }, (object)['quantidade' => 0, 'saldo' => 0]);

        return [$totais->quantidade, $totais->saldo];
    }
}
