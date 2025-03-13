<?php
/**
 * Created by PhpStorm.
 * User: iuriag
 * Date: 19/04/19
 * Time: 15:46
 */

namespace ECidade\Financeiro\Contabilidade\MatrizSaldoContabil;

/**
 * Processa a lista de recursos atravez da matriz
 * Class SaldoRecurso
 * @package ECidade\Financeiro\Contabilidade\MatrizSaldoContabil
 */
class SaldoRecurso
{


    protected $tableName;

    protected $conta;

    protected $estrutural;

    /**
     * @var \DateTime
     */
    protected $dataInicial;

    /**
     * @var \DateTime
     */
    protected $dataFinal;

    /**
     * @var \Instituicao[]
     */
    private $instituicoes;

    private $verificaSaldoAnterior = true;


    /**
     * retorna os saldos por recurso da conta informadoda
     * @param $conta
     * @param array $instituicoes
     * @param \DateTime $dataInicial
     * @param \DateTime $dataFinal
     * @param null $estrutural
     * @return \stdClass[]
     */
    public function getRecursos(
        array $instituicoes,
        \DateTime $dataInicial,
        \DateTime $dataFinal,
        $conta = null,
        $estrutural = null,
        $verificaSaldoAnterior = true
    ) {

        $this->conta = $conta;
        $this->estrutural = $estrutural;
        $this->instituicoes = $instituicoes;
        $this->dataInicial = $dataInicial;
        $this->dataFinal = $dataFinal;
        $this->verificaSaldoAnterior = $verificaSaldoAnterior;

        $ano = $this->dataInicial->format('Y');
        $mes = $this->dataInicial->format('m') - 1;
        if ($mes == 0) {
            $mes = 12;
            $ano -= 1;
        }
        $this->processarSaldoAnterior($ano, $mes);
        $this->processarMovimentacao();



        $sqlRecursos = "SELECT  recurso,";
        $sqlRecursos .= "      (select o15_descr ";
        $sqlRecursos .= "         from orctiporec ";
        $sqlRecursos .= "        where o15_recurso = recurso limit 1) as descricao,";
        $sqlRecursos .= "       abs(sum(saldo_anterior)) as saldo_anterior,";
        $sqlRecursos .= "       case when sum(saldo_anterior) < 0 then 'D'else 'C' end as natureza_saldo_anterior,";
        $sqlRecursos .= "       SUM(valor_debito)   AS valor_debito,";
        $sqlRecursos .= "       sum(valor_credito)  as valor_credito,";
        $sqlRecursos .= "       abs(sum((saldo_anterior - valor_debito) + valor_credito))  as saldo_final, ";
        $sqlRecursos .= "       case when sum((saldo_anterior - valor_debito) + valor_credito) < 0 then 'D' ";
        $sqlRecursos .= "                  else 'C' end as natureza_saldo_final";
        $sqlRecursos .= " from {$this->tableName} ";
        $sqlRecursos .= " group by 1, 2 ";
        $sqlRecursos .= " order by 1; ";

        $resRecursos = db_query($sqlRecursos);

        db_query("drop table {$this->tableName}");
        return \db_utils::getCollectionByRecord($resRecursos);
    }

    /**
     * Calcula o saldo da matriz no saldo anterior calculado.
     * @param $ano
     * @param $mes
     */
    protected function processarSaldoAnterior($ano, $mes)
    {

        global $DB_BASE;
        $codigoInstituicoes = array_map(function (\Instituicao $instituicao) {

            return "'%" . $instituicao->getCodigoTribunal() . "#PO%'";
        }, $this->instituicoes);

        $whereInstituicoes = 'like ' . implode(' or c125_hashcontaatributos like', $codigoInstituicoes);
        $this->tableName = "saldo_recursos_matriz_" . time();
        $sql = <<<SQL
        drop table if exists {$this->tableName};
        create temp table {$this->tableName} (

          recurso              varchar,
          saldo_anterior       numeric,
          sinal_saldo_anterior char(1),
          valor_debito         numeric,
          valor_credito        numeric,
          saldo_final          numeric,
          sinal_saldo_final    char(1)
        );
SQL;
        db_query($sql);

        $sqlInsert = <<<SQL

        insert into {$this->tableName}
        SELECT fonte_recurso,
               saldo_anterior,
               case when saldo_anterior < 0 then 'D' else 'C' end as sinal_saldo_anterior,
               0,
               0,
               0,
               'D'
        from (select replace(case
                               when position('#FR' IN c125_hashcontaatributos) > 0
                                       THEN substring(c125_hashcontaatributos,
                                                      position('#PO' IN c125_hashcontaatributos) + 4,
                                                      position('#FR' IN c125_hashcontaatributos) - 12) end, '#FR',
                             '') as fonte_recurso,
                     sum(case when c125_natureza = 'D' then c125_valor * -1 else c125_valor end) as saldo_anterior

              from conplanoatributosaldo
              where c125_hashcontaatributos ilike '{$this->estrutural}%'
                and c125_conplanosistema = 1
                and c125_mesusu = {$mes}
                and c125_anousu = {$ano}
                and (c125_hashcontaatributos {$whereInstituicoes})
                and c125_tiposaldo = 2
              group by 1
              order by 1) as x;
SQL;
        if ($this->verificaSaldoAnterior || ($mes != 12 && !$this->verificaSaldoAnterior)) {
            db_query($sqlInsert);
        }
    }


    protected function processarMovimentacao()
    {
        $dataInicial = $this->dataInicial->format('Y-m-d');
        $dataFinal = $this->dataFinal->format('Y-m-d');
        $codigoInstituicoes = implode(',', array_map(function (\Instituicao $instituicao) {

            return $instituicao->getCodigo();
        }, $this->instituicoes));

        $ano = $this->dataInicial->format('Y');

        $wherePorTipo = " and c124_tipo <> '1' ";
        $sqlRegistrosSaldoAnterior = "select count(*) as total from {$this->tableName}";
        $totalRegistros = \db_utils::fieldsMemory(db_query($sqlRegistrosSaldoAnterior), 0)->total;

        /**
         * primeiro ano da mSC
         */
        if ($totalRegistros == 0) {
            $wherePorTipo = "  and (c124_tipo <> '1' or ( c124_tipo =  '1' and c124_data = '{$ano}-01-01'))";
        }
        $sqlMovimentacao = <<<SQL
            insert into {$this->tableName}
                select recurso,
                       saldo_anterior                                     as saldo_anterior,
                       case when saldo_anterior < 0 then 'D' else 'C' end as natureza_saldo_anterior,
                       valor_debito,
                       valor_credito,
                       ((saldo_anterior - valor_debito) + valor_credito)  as saldo_final,
                       case
                         when ((saldo_anterior - valor_debito) + valor_credito) < 0 then 'D'
                         else 'C' end                                     as natureza_saldo_final
                from (with lancamentos as (
                        select c124_sequencial   as codigo,
                               c124_data         as data,
                               c124_natureza     as natureza,
                               c124_valor        as valor,
                               c124_lancamento   as codigo_lancamento,
                               c71_coddoc        as documento,
                               c123_reduzido     as reduzido,
                               c60_estrut        as estrutural,
                               c60_descr         as nome_conta,
                               c123_valor        as valor_atributo,
                               c121_sigla        as sigla_atributo,
                               c121_sequencial   as ordem,
                               c124_tipo         as tipo
                          from contabilidade.infocomplementarvalor
                          join contabilidade.conplanoatributolancamentos
                                   on c124_sequencial = c123_conplanoatributolancamentos
                          join contabilidade.conplanoinfocomplementar on c121_sequencial = c123_infocomplementar
                          join contabilidade.conplanoreduz on c61_reduz = c123_reduzido and {$ano} = c61_anousu
                          join contabilidade.conplano on c61_codcon = c60_codcon and c60_anousu = c61_anousu
                                 left join contabilidade.conlancam on c70_codlan = c124_lancamento
                                 left join contabilidade.conlancamdoc on c71_codlan = c70_codlan

                        where c124_data >= '{$dataInicial}' :: date
                          and c124_data <= '{$dataFinal}' :: date
                          and c123_conplanosistema = 1
                          {$wherePorTipo}
                          and c121_sequencial = 3
                          and c60_estrut ilike '{$this->estrutural}%'
                        and c61_instit in({$codigoInstituicoes})
                                               order by c124_sequencial, c71_coddoc, c124_data, c123_reduzido,
                                                        c121_sequencial,
                                                        c124_lancamento,
                                                        c60_estrut
                    ), conta_corrente as (
                        select codigo,
                               data,
                               natureza,
                               valor,
                               codigo_lancamento,
                               reduzido,
                               estrutural,
                               nome_conta,
                               tipo,
                               valor_atributo as recurso_id
                          from lancamentos
                         order by codigo, data, natureza, valor, codigo_lancamento, reduzido, estrutural
                    ), totaliza as (
                        select recurso_id,
                               round(coalesce(sum(
                                   CASE
                                       WHEN (DATA < '{$dataInicial}' :: date or tipo = '1') AND natureza = 'D'
                                           THEN valor * -1
                                       WHEN (DATA < '{$dataInicial}' :: date or tipo = '1') AND natureza = 'C'
                                           THEN valor END), 0), 2
                               ) AS saldo_anterior,
                               round(coalesce(sum(
                                   case
                                       when DATA >= '{$dataInicial}' :: date and natureza = 'D' and tipo = '2'
                                           then valor end), 0), 2
                               ) as valor_debito,
                               round(coalesce(sum(
                                   case
                                       when DATA >= '{$dataInicial}' :: date and natureza = 'C' and tipo = '2'
                                           then valor end), 0), 2
                               ) as valor_credito
                        from conta_corrente
                    where data >= '{$dataInicial}' :: date
                      and data <= '{$dataFinal}' :: date
                    GROUP by recurso_id
                    order by recurso_id
                    ) select o15_recurso as recurso
                             ,saldo_anterior
                             ,valor_debito
                             ,valor_credito
                             ,o15_descr as descricao
                      FROM totaliza
                      JOIN orctiporec ON o15_codigo = recurso_id::int
                      ORDER BY o15_recurso,
                               o15_descr
                ) as tabelao;
SQL;

        db_query($sqlMovimentacao);
    }
}
