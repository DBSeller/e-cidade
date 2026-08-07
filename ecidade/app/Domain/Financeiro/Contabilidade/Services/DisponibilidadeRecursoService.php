<?php


namespace App\Domain\Financeiro\Contabilidade\Services;

use App\Domain\Financeiro\Contabilidade\Relatorios\ConferenciaPorRecursosPDF;
use App\Domain\Financeiro\Contabilidade\Relatorios\DisponibilidadeRecursosPDF;
use cl_conlancam;
use cl_conplanoreduz;
use db_utils;
use Illuminate\Support\Facades\DB;
use stdClass;

/**
 * Class DisponibilidadeRecursoService
 * @package App\Domain\Financeiro\Contabilidade\Services
 */
class DisponibilidadeRecursoService
{
    public function relatorioSaldoDisponibilidadeRecurso($filtros)
    {

        $aDadosRelatorio = array();
        $oDao = new cl_conplanoreduz;

        $sql = $oDao->sql_query_saldo_disponibilidade_recurso($filtros);

        $rs = $oDao->sql_record($sql);

        $totalSaldoAnterior = 0;
        $totalSalsoAtual = 0;

        for ($i = 0; $i < $oDao->numrows; $i++) {
            $oDadosConsulta = db_utils::fieldsMemory($rs, $i);

            $oDados = new stdClass();
            $oDados->id = $oDadosConsulta->id;
            $oDados->tipo = $oDadosConsulta->tipo;
            $oDados->saldo_anterior = db_formatar($oDadosConsulta->saldo_anterior, "f");
            $oDados->saldo_atual = db_formatar($oDadosConsulta->saldo_atual, "f");

            $aDadosRelatorio[] = $oDados;

            if ($i < 5) {
                $totalSaldoAnterior += $oDadosConsulta->saldo_anterior;
                $totalSalsoAtual += $oDadosConsulta->saldo_atual;
            }
        }

         $oTotais = new stdClass();
         $oTotais->total_saldo_atual = db_formatar($totalSalsoAtual, "f");
         $oTotais->diferenca_saldo_atual = db_formatar($totalSalsoAtual +
                                                       db_utils::fieldsMemory($rs, 5)->saldo_atual, "f");

         $oTotais->total_saldo_anterior = db_formatar($totalSaldoAnterior, "f");
         $oTotais->diferenca_saldo_anterior = db_formatar($totalSaldoAnterior +
                                                            db_utils::fieldsMemory($rs, 5)->saldo_anterior, "f");

        $aDadosRelatorio[] = $oTotais;

        $pdf = new DisponibilidadeRecursosPDF($filtros);
        return $pdf->emitir($aDadosRelatorio);
    }

    public function obterDadosConferenciaPorRecurso($filtros)
    {
        $aDadosRelatorio = array();

        $filtros->dataInicial = implode('-', array_reverse(explode('/', $filtros->dataInicial)));
        $filtros->dataFinal = implode('-', array_reverse(explode('/', $filtros->dataFinal)));

        $oDao = new cl_conlancam;
        $sql = $oDao->sql_conferenciaPorRecurso($filtros);
        $rs = $oDao->sql_record($sql);

        $valoresExtraOrcamentario = $this->valoresSaldoExtraOrcamentario($filtros);

        $total_saldo_ativo_at_f = 0;
        $total_saldo_extra_orcamentario = 0;
        $total_valor_a_liquidar = 0;
        $total_valor_a_pagar = 0;
        $total_valor_a_liquidar_rp = 0;
        $total_valor_a_pagar_rp = 0;
        $total_total = 0;
        $total_valor_disponibilidade = 0;
        $total_diferenca = 0;

        for ($i = 0; $i < $oDao->numrows; $i++) {
            $oDadosConsulta = db_utils::fieldsMemory($rs, $i);

            $oDados = new stdClass();
            $oDados->recurso = $oDadosConsulta->recurso;
            $oDados->o15_descr = $oDadosConsulta->o15_descr;

            $valorExtra = array_filter($valoresExtraOrcamentario, function ($valorExtra) use ($oDados) {
                return $oDados->recurso == $valorExtra->recurso;
            });

            $saldo_extra_orcamentario = 0;
            if (!empty($valorExtra)) {
                $saldo_extra_orcamentario = array_shift($valorExtra)->valor;
            }

            $oDados->saldo_ativo_at_f = db_formatar($oDadosConsulta->saldo_ativo_at_f, "f");
            $oDados->saldo_extra_orcamentario = db_formatar($saldo_extra_orcamentario, 'f');
            $oDados->valor_a_liquidar = db_formatar($oDadosConsulta->valor_a_liquidar, "f");
            $oDados->valor_a_pagar = db_formatar($oDadosConsulta->valor_a_pagar, "f");
            $oDados->valor_a_liquidar_rp = db_formatar($oDadosConsulta->valor_a_liquidar_rp, "f");
            $oDados->valor_a_pagar_rp = db_formatar($oDadosConsulta->valor_a_pagar_rp, "f");
            $total = $oDadosConsulta->total - $saldo_extra_orcamentario;
            $oDados->total = db_formatar($total, "f");
            $oDados->valor_disponibilidade = db_formatar($oDadosConsulta->valor_disponibilidade, "f");
            $diferenca = $total - $oDadosConsulta->valor_disponibilidade;
            $oDados->diferenca = db_formatar($diferenca, "f");

            // totais
            $total_saldo_ativo_at_f += $oDadosConsulta->saldo_ativo_at_f;
            $total_saldo_extra_orcamentario += $saldo_extra_orcamentario;
            $total_valor_a_liquidar += $oDadosConsulta->valor_a_liquidar;
            $total_valor_a_pagar += $oDadosConsulta->valor_a_pagar;
            $total_valor_a_liquidar_rp += $oDadosConsulta->valor_a_liquidar_rp;
            $total_valor_a_pagar_rp += $oDadosConsulta->valor_a_pagar_rp;
            $total_total += $total;
            $total_valor_disponibilidade += $oDadosConsulta->valor_disponibilidade;
            $total_diferenca += $diferenca;

            $aDadosRelatorio[] = $oDados;
        }

        $oTotais = new stdClass();
        $oTotais->saldo_ativo_at_f = db_formatar($total_saldo_ativo_at_f, "f");
        $oTotais->saldo_extra_orcamentario = db_formatar($total_saldo_extra_orcamentario, 'f');
        $oTotais->valor_a_liquidar = db_formatar($total_valor_a_liquidar, "f");
        $oTotais->valor_a_pagar = db_formatar($total_valor_a_pagar, "f");
        $oTotais->valor_a_liquidar_rp = db_formatar($total_valor_a_liquidar_rp, "f");
        $oTotais->valor_a_pagar_rp = db_formatar($total_valor_a_pagar_rp, "f");
        $oTotais->total = db_formatar($total_total, "f");
        $oTotais->valor_disponibilidade = db_formatar($total_valor_disponibilidade, "f");
        $oTotais->diferenca =  db_formatar($total_diferenca, "f");

        $aDados['registros'] = $aDadosRelatorio;
        $aDados['totais'] = $oTotais;

        return $aDados;
    }

    public function relatorioConferenciaPorRecurso($filtros)
    {
        $info = $this->obterDadosConferenciaPorRecurso($filtros);

        $pdf = new ConferenciaPorRecursosPDF($filtros);
        return $pdf->emitir($info);
    }

    private function valoresSaldoExtraOrcamentario($filtros)
    {
        return DB::select("
        with saldos as (
            SELECT o15_recurso as recurso,
                  fc_planosaldonovo_array({$filtros->ano},
                    c61_reduz,
                    '{$filtros->dataInicial}',
                    '{$filtros->dataFinal}', FALSE),
                  p.c60_identificadorfinanceiro,
                  c60_consistemaconta
           FROM conplanoexe e
           INNER JOIN conplanoreduz r ON r.c61_anousu = c62_anousu
           AND r.c61_reduz = c62_reduz
           INNER JOIN conplano p ON r.c61_codcon = c60_codcon
           AND r.c61_anousu = c60_anousu
           join orctiporec on orctiporec.o15_codigo = r.c61_codigo
           LEFT OUTER JOIN consistema ON c60_codsis = c52_codsis
           WHERE c62_anousu = {$filtros->ano}
             AND c61_instit IN ($filtros->instituicoes)
             AND c60_identificadorfinanceiro = 'F'
             AND (p.c60_estrut LIKE '2188%')
        ), saldos_por_recurso as (
          select recurso,
                 case when sinal_final = 'D' then saldo_final *-1 else saldo_final end as saldo_final,
                 sinal_final
            from (
            select recurso,
                    round(fc_planosaldonovo_array[4]::float8, 2)::float8 as saldo_final,
                    fc_planosaldonovo_array[6]::varchar(1) AS sinal_final
               from saldos
           ) as x
        ), agrupa_por_recurso as (
          select recurso, sum(saldo_final) as valor
            from saldos_por_recurso
          group by recurso
        ) select * from agrupa_por_recurso
        ");
    }
}
