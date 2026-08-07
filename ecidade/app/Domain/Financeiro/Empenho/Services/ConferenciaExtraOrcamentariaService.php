<?php

namespace App\Domain\Financeiro\Empenho\Services;

use App\Domain\Financeiro\Empenho\Relatorios\ConferenciaExtraOrcamentariaPDF;
use Illuminate\Support\Facades\DB;

class ConferenciaExtraOrcamentariaService
{

    public function __construct()
    {
    }

    /**
     * @param array $dados
     * @throws Exception
     */
    public function exportar(array $all)
    {
        $data1 = str_replace('/', '-', $all['data1']);
        $data2 = str_replace('/', '-', $all['data2']);
        $instit = $all['DB_instit'];
        $estorno = $all['estorno'];
        $tipo = $all['tipo'];
        if ($all['tipo'] == 'Despesa') {
            $sql = $this->getSqlDespesa($data1, $data2, $instit, $estorno);
            $relatorio = new ConferenciaExtraOrcamentariaPDF($data1, $data2, $tipo, DB::select($sql));
            return $relatorio->emitir();
        } else {
            $sql = $this->getSqlReceita($data1, $data2, $instit, $estorno);
            $relatorio = new ConferenciaExtraOrcamentariaPDF($data1, $data2, $tipo, DB::select($sql));
            return $relatorio->emitir();
        }
    }

    private function getSqlReceita($dataini, $datafim, $instit, $estorno)
    {
        $sql = "SELECT *
            FROM
              (SELECT c71_codlan,
                      c71_data,
                      c71_coddoc,
                      c53_descr,
                      c02_instit,
                      nomeinstabrev,

                 (SELECT z01_cgccpf
                  FROM conlancamemp
                  INNER JOIN empempenho ON c75_numemp = e60_numemp
                  INNER JOIN cgm ON z01_numcgm = e60_numcgm
                  WHERE c75_codlan = c70_codlan ) AS cpf_cnpj,

                 (SELECT e60_codemp
                  FROM conlancamemp
                  INNER JOIN empempenho ON c75_numemp = e60_numemp
                  INNER JOIN cgm ON z01_numcgm = e60_numcgm
                  WHERE c75_codlan = c70_codlan ) AS empenho,

                 (SELECT e60_anousu
                  FROM conlancamemp
                  INNER JOIN empempenho ON c75_numemp = e60_numemp
                  INNER JOIN cgm ON z01_numcgm = e60_numcgm
                  WHERE c75_codlan = c70_codlan ) AS ano_empenho,
                      CASE
                          WHEN
                                 (SELECT c60_estrut
                                  FROM conlancamretencao
                                  INNER JOIN retencaotiporec ON e21_sequencial = c127_retencaotiporec
                                  INNER JOIN tabrec a ON e21_receita = a.k02_codigo
                                  INNER JOIN tabplan b ON b.k02_anousu = c70_anousu
                                  AND a.k02_codigo = b.k02_codigo
                                  INNER JOIN conplanoreduz ON b.k02_anousu = c61_anousu
                                  AND b.k02_reduz = c61_reduz
                                  INNER JOIN conplano ON c60_anousu = c61_anousu
                                  AND c60_codcon = c61_codcon
                                  WHERE c127_conlancam = c71_codlan ) IS NOT NULL THEN
                                 (SELECT c60_estrut
                                  FROM conlancamretencao
                                  INNER JOIN retencaotiporec ON e21_sequencial = c127_retencaotiporec
                                  INNER JOIN tabrec a ON e21_receita = a.k02_codigo
                                  INNER JOIN tabplan b ON b.k02_anousu = c70_anousu
                                  AND a.k02_codigo = b.k02_codigo
                                  INNER JOIN conplanoreduz ON b.k02_anousu = c61_anousu
                                  AND b.k02_reduz = c61_reduz
                                  INNER JOIN conplano ON c60_anousu = c61_anousu
                                  AND c60_codcon = c61_codcon
                                  WHERE c127_conlancam = c71_codlan )
                          ELSE CASE
                                   WHEN
                                          (SELECT c60_estrut
                                           FROM conlancamcorrente
                                           INNER JOIN cornump ON c86_data = k12_data
                                           AND c86_id = k12_id
                                           AND c86_autent = k12_autent
                                           INNER JOIN tabplan ON k02_codigo = k12_receit
                                           AND k02_anousu = c70_anousu
                                           INNER JOIN conplanoreduz ON c61_anousu = c70_anousu
                                           AND c61_reduz = k02_reduz
                                           INNER JOIN conplano ON c61_anousu = c60_anousu
                                           AND c61_codcon = c60_codcon
                                           WHERE c86_conlancam = c71_codlan ) IS NOT NULL THEN
                                          (SELECT c60_estrut
                                           FROM conlancamcorrente
                                           INNER JOIN cornump ON c86_data = k12_data
                                           AND c86_id = k12_id
                                           AND c86_autent = k12_autent
                                           INNER JOIN tabplan ON k02_codigo = k12_receit
                                           AND k02_anousu = c70_anousu
                                           INNER JOIN conplanoreduz ON c61_anousu = c70_anousu
                                           AND c61_reduz = k02_reduz
                                           INNER JOIN conplano ON c61_anousu = c60_anousu
                                           AND c61_codcon = c60_codcon
                                           WHERE c86_conlancam = c71_codlan )
                                   ELSE
                                          (SELECT c60_estrut
                                           FROM conlancamslip
                                           INNER JOIN slip ON c84_slip = k17_codigo
                                           INNER JOIN conplanoreduz ON c61_anousu = c70_anousu
                                           AND c61_reduz = k17_credito
                                           INNER JOIN conplano ON c61_anousu = c60_anousu
                                           AND c61_codcon = c60_codcon
                                           WHERE c84_conlancam = c71_codlan )
                               END
                      END AS estrutural,
                 (SELECT count(*)
                  FROM conlancampag
                  WHERE c82_codlan = c71_codlan ) AS ctabancaria,
                      CASE
                          WHEN c53_tipo = 31 THEN c70_valor*-1
                          WHEN c71_coddoc IN (152,
                                              162) THEN c70_valor*-1
                          ELSE c70_valor
                      END AS c70_valor
               FROM conlancamdoc
               JOIN conhistdoc ON c71_coddoc = c53_coddoc
               JOIN conlancam ON c70_codlan = c71_codlan
               JOIN conlancaminstit ON c71_codlan = c02_codlan
               JOIN db_config ON codigo = c02_instit
               WHERE CASE
                         WHEN $estorno = FALSE THEN c71_coddoc IN
                                (SELECT c53_coddoc
                                 FROM conhistdoc
                                 WHERE (c53_tipo = 30
                                        AND c53_coddoc IN (6002,
                                                           6008,
                                                           6010))
                                   OR c53_coddoc IN (160,
                                                     150) )
                         ELSE c71_coddoc IN
                                (SELECT c53_coddoc
                                 FROM conhistdoc
                                 WHERE (c53_tipo = 31
                                        AND c53_coddoc IN (6003,
                                                           6009,
                                                           6011))
                                   OR c53_coddoc IN (162,
                                                     152) )
                     END
                 AND c71_data BETWEEN '$dataini' AND '$datafim'
                 AND c02_instit = $instit ) AS x
            WHERE estrutural IS NOT NULL
            ORDER BY c71_data,
                     c70_valor;";
        return $sql;
    }

    private function getSqlDespesa($dataini, $datafim, $instit, $estorno)
    {
        $sql = "SELECT c71_codlan,
               c71_data,
               c71_coddoc,
               c53_descr,
               c02_instit,
               nomeinstabrev,

          (SELECT z01_cgccpf
           FROM conlancamemp
           INNER JOIN empempenho ON c75_numemp = e60_numemp
           INNER JOIN cgm ON z01_numcgm = e60_numcgm
           WHERE c75_codlan = c70_codlan ) AS cpf_cnpj,

          (SELECT e60_codemp
           FROM conlancamemp
           INNER JOIN empempenho ON c75_numemp = e60_numemp
           INNER JOIN cgm ON z01_numcgm = e60_numcgm
           WHERE c75_codlan = c70_codlan ) AS empenho,

          (SELECT e60_anousu
           FROM conlancamemp
           INNER JOIN empempenho ON c75_numemp = e60_numemp
           INNER JOIN cgm ON z01_numcgm = e60_numcgm
           WHERE c75_codlan = c70_codlan ) AS ano_empenho,

          (SELECT c84_slip
           FROM conlancamslip
           WHERE c84_conlancam = c71_codlan ) AS slip,
               CASE
                   WHEN
                          (SELECT c60_estrut
                           FROM conlancamretencao
                           INNER JOIN retencaotiporec ON e21_sequencial = c127_retencaotiporec
                           INNER JOIN tabrec a ON e21_receita = a.k02_codigo
                           INNER JOIN tabplan b ON b.k02_anousu = c70_anousu
                           AND a.k02_codigo = b.k02_codigo
                           INNER JOIN conplanoreduz ON b.k02_anousu = c61_anousu
                           AND b.k02_reduz = c61_reduz
                           INNER JOIN conplano ON c60_anousu = c61_anousu
                           AND c60_codcon = c61_codcon
                           WHERE c127_conlancam = c71_codlan ) IS NOT NULL THEN
                          (SELECT c60_estrut
                           FROM conlancamretencao
                           INNER JOIN retencaotiporec ON e21_sequencial = c127_retencaotiporec
                           INNER JOIN tabrec a ON e21_receita = a.k02_codigo
                           INNER JOIN tabplan b ON b.k02_anousu = c70_anousu
                           AND a.k02_codigo = b.k02_codigo
                           INNER JOIN conplanoreduz ON b.k02_anousu = c61_anousu
                           AND b.k02_reduz = c61_reduz
                           INNER JOIN conplano ON c60_anousu = c61_anousu
                           AND c60_codcon = c61_codcon
                           WHERE c127_conlancam = c71_codlan )
                   ELSE CASE
                            WHEN
                                   (SELECT c60_estrut
                                    FROM conlancamcorrente
                                    INNER JOIN cornump ON c86_data = k12_data
                                    AND c86_id = k12_id
                                    AND c86_autent = k12_autent
                                    INNER JOIN tabplan ON k02_codigo = k12_receit
                                    AND k02_anousu = c70_anousu
                                    INNER JOIN conplanoreduz ON c61_anousu = c70_anousu
                                    AND c61_reduz = k02_reduz
                                    INNER JOIN conplano ON c61_anousu = c60_anousu
                                    AND c61_codcon = c60_codcon
                                    WHERE c86_conlancam = c71_codlan ) IS NOT NULL THEN
                                   (SELECT c60_estrut
                                    FROM conlancamcorrente
                                    INNER JOIN cornump ON c86_data = k12_data
                                    AND c86_id = k12_id
                                    AND c86_autent = k12_autent
                                    INNER JOIN tabplan ON k02_codigo = k12_receit
                                    AND k02_anousu = c70_anousu
                                    INNER JOIN conplanoreduz ON c61_anousu = c70_anousu
                                    AND c61_reduz = k02_reduz
                                    INNER JOIN conplano ON c61_anousu = c60_anousu
                                    AND c61_codcon = c60_codcon
                                    WHERE c86_conlancam = c71_codlan )
                            ELSE
                                   (SELECT c60_estrut
                                    FROM conlancamslip
                                    INNER JOIN slip ON c84_slip = k17_codigo
                                    INNER JOIN conplanoreduz ON c61_anousu = c70_anousu
                                    AND c61_reduz = k17_credito
                                    INNER JOIN conplano ON c61_anousu = c60_anousu
                                    AND c61_codcon = c60_codcon
                                    WHERE c84_conlancam = c71_codlan )
                        END
               END AS estrutural,

          (SELECT Count(*)
           FROM conlancampag
           WHERE c82_codlan = c71_codlan ) AS ctabancaria,
               CASE
                   WHEN c53_tipo IN (153,
                                     163) THEN c70_valor*-1
                   ELSE c70_valor
               END AS c70_valor
        FROM conlancamdoc
        INNER JOIN conhistdoc ON c71_coddoc = c53_coddoc
        INNER JOIN conlancam ON c70_codlan = c71_codlan
        INNER JOIN conlancaminstit ON c71_codlan = c02_codlan
        INNER JOIN db_config ON codigo = c02_instit
        WHERE CASE
                  WHEN $estorno = FALSE THEN c71_coddoc IN
                         (SELECT c53_coddoc
                          FROM conhistdoc
                          WHERE c53_coddoc IN (151,
                                               161) )
                  ELSE c71_coddoc IN
                         (SELECT c53_coddoc
                          FROM conhistdoc
                          WHERE c53_coddoc IN (153,
                                               163) )
              END
          AND c71_data BETWEEN '$dataini' AND '$datafim'
          AND c02_instit = $instit::integer
        UNION
        SELECT c71_codlan,
               c71_data,
               c71_coddoc,
               c53_descr,
               c02_instit,
               nomeinstabrev,

          (SELECT z01_cgccpf
           FROM conlancamemp
           INNER JOIN empempenho ON c75_numemp = e60_numemp
           INNER JOIN cgm ON z01_numcgm = e60_numcgm
           WHERE c75_codlan = c70_codlan ) AS cpf_cnpj,

          (SELECT e60_codemp
           FROM conlancamemp
           INNER JOIN empempenho ON c75_numemp = e60_numemp
           INNER JOIN cgm ON z01_numcgm = e60_numcgm
           WHERE c75_codlan = c70_codlan ) AS empenho,

          (SELECT e60_anousu
           FROM conlancamemp
           INNER JOIN empempenho ON c75_numemp = e60_numemp
           INNER JOIN cgm ON z01_numcgm = e60_numcgm
           WHERE c75_codlan = c70_codlan ) AS ano_empenho,
               0 AS slip,
               CASE
                   WHEN
                          (SELECT c60_estrut
                           FROM conlancamretencao
                           INNER JOIN retencaotiporec ON e21_sequencial = c127_retencaotiporec
                           INNER JOIN tabrec a ON e21_receita = a.k02_codigo
                           INNER JOIN tabplan b ON b.k02_anousu = c70_anousu
                           AND a.k02_codigo = b.k02_codigo
                           INNER JOIN conplanoreduz ON b.k02_anousu = c61_anousu
                           AND b.k02_reduz = c61_reduz
                           INNER JOIN conplano ON c60_anousu = c61_anousu
                           AND c60_codcon = c61_codcon
                           WHERE c127_conlancam = c71_codlan ) IS NOT NULL THEN
                          (SELECT c60_estrut
                           FROM conlancamretencao
                           INNER JOIN retencaotiporec ON e21_sequencial = c127_retencaotiporec
                           INNER JOIN tabrec a ON e21_receita = a.k02_codigo
                           INNER JOIN tabplan b ON b.k02_anousu = c70_anousu
                           AND a.k02_codigo = b.k02_codigo
                           INNER JOIN conplanoreduz ON b.k02_anousu = c61_anousu
                           AND b.k02_reduz = c61_reduz
                           INNER JOIN conplano ON c60_anousu = c61_anousu
                           AND c60_codcon = c61_codcon
                           WHERE c127_conlancam = c71_codlan )
                   ELSE CASE
                            WHEN
                                   (SELECT c60_estrut
                                    FROM conlancamcorrente
                                    INNER JOIN cornump ON c86_data = k12_data
                                    AND c86_id = k12_id
                                    AND c86_autent = k12_autent
                                    INNER JOIN tabplan ON k02_codigo = k12_receit
                                    AND k02_anousu = c70_anousu
                                    INNER JOIN conplanoreduz ON c61_anousu = c70_anousu
                                    AND c61_reduz = k02_reduz
                                    INNER JOIN conplano ON c61_anousu = c60_anousu
                                    AND c61_codcon = c60_codcon
                                    WHERE c86_conlancam = c71_codlan ) IS NOT NULL THEN
                                   (SELECT c60_estrut
                                    FROM conlancamcorrente
                                    INNER JOIN cornump ON c86_data = k12_data
                                    AND c86_id = k12_id
                                    AND c86_autent = k12_autent
                                    INNER JOIN tabplan ON k02_codigo = k12_receit
                                    AND k02_anousu = c70_anousu
                                    INNER JOIN conplanoreduz ON c61_anousu = c70_anousu
                                    AND c61_reduz = k02_reduz
                                    INNER JOIN conplano ON c61_anousu = c60_anousu
                                    AND c61_codcon = c60_codcon
                                    WHERE c86_conlancam = c71_codlan )
                            ELSE
                                   (SELECT c60_estrut
                                    FROM conlancamslip
                                    INNER JOIN slip ON c84_slip = k17_codigo
                                    INNER JOIN conplanoreduz ON c61_anousu = c70_anousu
                                    AND c61_reduz = k17_credito
                                    INNER JOIN conplano ON c61_anousu = c60_anousu
                                    AND c61_codcon = c60_codcon
                                    WHERE c84_conlancam = c71_codlan )
                        END
               END AS estrutural,

          (SELECT count(*)
           FROM conlancampag
           WHERE c82_codlan = c71_codlan ) AS ctabancaria,
               CASE
                   WHEN c53_tipo = 31 THEN c70_valor*-1
                   ELSE c70_valor
               END AS c70_valor
        FROM conlancamdoc
        INNER JOIN conhistdoc ON c71_coddoc = c53_coddoc
        INNER JOIN conlancam ON c70_codlan = c71_codlan
        INNER JOIN conlancaminstit ON c71_codlan = c02_codlan
        INNER JOIN db_config ON codigo = c02_instit
        WHERE CASE
                  WHEN $estorno = FALSE THEN c71_coddoc IN
                         (SELECT c53_coddoc
                          FROM conhistdoc
                          WHERE c53_coddoc IN (35,
                                               37) )
                  ELSE c71_coddoc IN
                         (SELECT c53_coddoc
                          FROM conhistdoc
                          WHERE c53_coddoc IN (36,
                                               38) )
              END
          AND c71_data BETWEEN '$dataini' AND '$datafim'
          AND c02_instit = $instit::integer
        UNION
        SELECT c71_codlan,
               c71_data,
               c71_coddoc,
               c53_descr,
               c02_instit,
               nomeinstabrev,

          (SELECT z01_cgccpf
           FROM conlancamemp
           INNER JOIN empempenho ON c75_numemp = e60_numemp
           INNER JOIN cgm ON z01_numcgm = e60_numcgm
           WHERE c75_codlan = c70_codlan ) AS cpf_cnpj,

          (SELECT e60_codemp
           FROM conlancamemp
           INNER JOIN empempenho ON c75_numemp = e60_numemp
           INNER JOIN cgm ON z01_numcgm = e60_numcgm
           WHERE c75_codlan = c70_codlan ) AS empenho,

          (SELECT e60_anousu
           FROM conlancamemp
           INNER JOIN empempenho ON c75_numemp = e60_numemp
           INNER JOIN cgm ON z01_numcgm = e60_numcgm
           WHERE c75_codlan = c70_codlan ) AS ano_empenho,
               0 AS slip,
               CASE
                   WHEN
                          (SELECT c60_estrut
                           FROM conlancamretencao
                           INNER JOIN retencaotiporec ON e21_sequencial = c127_retencaotiporec
                           INNER JOIN tabrec a ON e21_receita = a.k02_codigo
                           INNER JOIN tabplan b ON b.k02_anousu = c70_anousu
                           AND a.k02_codigo = b.k02_codigo
                           INNER JOIN conplanoreduz ON b.k02_anousu = c61_anousu
                           AND b.k02_reduz = c61_reduz
                           INNER JOIN conplano ON c60_anousu = c61_anousu
                           AND c60_codcon = c61_codcon
                           WHERE c127_conlancam = c71_codlan ) IS NOT NULL THEN
                          (SELECT c60_estrut
                           FROM conlancamretencao
                           INNER JOIN retencaotiporec ON e21_sequencial = c127_retencaotiporec
                           INNER JOIN tabrec a ON e21_receita = a.k02_codigo
                           INNER JOIN tabplan b ON b.k02_anousu = c70_anousu
                           AND a.k02_codigo = b.k02_codigo
                           INNER JOIN conplanoreduz ON b.k02_anousu = c61_anousu
                           AND b.k02_reduz = c61_reduz
                           INNER JOIN conplano ON c60_anousu = c61_anousu
                           AND c60_codcon = c61_codcon
                           WHERE c127_conlancam = c71_codlan )
                   ELSE CASE
                            WHEN
                                   (SELECT c60_estrut
                                    FROM conlancamcorrente
                                    INNER JOIN cornump ON c86_data = k12_data
                                    AND c86_id = k12_id
                                    AND c86_autent = k12_autent
                                    INNER JOIN tabplan ON k02_codigo = k12_receit
                                    AND k02_anousu = c70_anousu
                                    INNER JOIN conplanoreduz ON c61_anousu = c70_anousu
                                    AND c61_reduz = k02_reduz
                                    INNER JOIN conplano ON c61_anousu = c60_anousu
                                    AND c61_codcon = c60_codcon
                                    WHERE c86_conlancam = c71_codlan ) IS NOT NULL THEN
                                   (SELECT c60_estrut
                                    FROM conlancamcorrente
                                    INNER JOIN cornump ON c86_data = k12_data
                                    AND c86_id = k12_id
                                    AND c86_autent = k12_autent
                                    INNER JOIN tabplan ON k02_codigo = k12_receit
                                    AND k02_anousu = c70_anousu
                                    INNER JOIN conplanoreduz ON c61_anousu = c70_anousu
                                    AND c61_reduz = k02_reduz
                                    INNER JOIN conplano ON c61_anousu = c60_anousu
                                    AND c61_codcon = c60_codcon
                                    WHERE c86_conlancam = c71_codlan )
                            ELSE
                                   (SELECT c60_estrut
                                    FROM conlancamslip
                                    INNER JOIN slip ON c84_slip = k17_codigo
                                    INNER JOIN conplanoreduz ON c61_anousu = c70_anousu
                                    AND c61_reduz = k17_credito
                                    INNER JOIN conplano ON c61_anousu = c60_anousu
                                    AND c61_codcon = c60_codcon
                                    WHERE c84_conlancam = c71_codlan )
                        END
               END AS estrutural,

          (SELECT count(*)
           FROM conlancampag
           WHERE c82_codlan = c71_codlan ) AS ctabancaria,
               CASE
                   WHEN c53_tipo = 31 THEN c70_valor*-1
                   ELSE c70_valor
               END AS c70_valor
        FROM conlancamdoc
        INNER JOIN conhistdoc ON c71_coddoc = c53_coddoc
        INNER JOIN conlancam ON c70_codlan = c71_codlan
        INNER JOIN conlancaminstit ON c71_codlan = c02_codlan
        INNER JOIN db_config ON codigo = c02_instit
        WHERE CASE
                  WHEN $estorno = FALSE THEN c71_coddoc IN
                         (SELECT c53_coddoc
                          FROM conhistdoc
                          WHERE c53_coddoc IN (6008,
                                               6010) )
                  ELSE c71_coddoc IN
                         (SELECT c53_coddoc
                          FROM conhistdoc
                          WHERE c53_coddoc IN (6009,
                                               6011) )
      END
  AND c71_data BETWEEN '$dataini' AND '$datafim'
  AND c02_instit = $instit::integer;";
        return $sql;
    }
}
