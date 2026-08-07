<?php

use Classes\PostgresMigration;

class M14205AjustaConsistenciaMsc extends PostgresMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function up()
    {
        $this->execute(<<<SQL

delete from consistenciasistema where db160_json ilike '%5c6acff055a5a%';
insert into consistenciasistema values
                                       (nextval('consistenciasistema_db160_sequencial_seq'),
                                        10,
                                        '{
  "tipo": 10,
  "uuid": "5c6acff055a5a",
  "nome": "Conferência entre Balancete de Verificação e MSC",
  "descricao": "Conferência dos saldos finais entre Balancete de Verificação e MSC",
  "formulario": {
    "campos": [
      {
        "propriedade": "estrutural",
        "nome": "Estrutural da conta",
        "chave_primaria": true
      },
      {
        "propriedade": "saldo_balancete",
        "nome": "Saldo Balancete Verificação"
      },
      {
        "propriedade": "saldo_conta_matriz",
        "nome": "Saldo MSC"
      },
      {
        "propriedade": "atributos",
        "nome": "Atributos Configurados"
      }
    ]
  },
  "filtros": {
    "campos": [
      {
        "label": "Data Inicial",
        "nome" : "data_inicial",
        "tipo": "data"
      },
      {
        "label": "Data Final",
        "nome" : "data_final",
        "tipo": "data"
      }
    ]
  },
  "sql": {
    "consistencia": "select * from (
select estrutural, abs(saldo_balancete) as saldo_balancete, abs(saldo_conta_matriz +(
           case when estrutural = ''821110100000000'' then
                          (select abs(sum(case when c125_natureza = ''C'' then c125_valor * -1 else c125_valor end)) as saldo
                           from conplanoatributosaldo
                           where c125_hashcontaatributos ilike ''821110100%''
                             and c125_anousu = extract(year from ''#data_inicial#'' :: date) - 1
                             and c125_mesusu = 12
                             and c125_tiposaldo = 1
                             and c125_conplanosistema = 1
                          )
                      else 0 end
    )) as saldo_conta_matriz, atributos
from (select c60_estrut                                     as estrutural,
              sum((select case when  saldos [ 6 ] = ''C'' then round(saldos [ 4 ] :: numeric, 2) * -1 else round(saldos [ 4 ] :: numeric, 2) end
                  from (select fc_planosaldonovo_array(c60_anousu, c61_reduz,
                                                       ''#data_inicial#'' :: date,
                                                       ''#data_final#'' :: date,
                                                       false
                                   ) as saldos) as saldo_final)
                 )                                          as saldo_balancete,
             sum(round(coalesce(saldo_conta_matriz, 0), 2)) as saldo_conta_matriz,

             array_to_string(array_accum((select array_to_string(array_accum(distinct c121_sigla), '','')
                        from conplanoatributos
                               inner join conplanoinfocomplementar on c121_sequencial = c120_infocomplementar
                        where c120_conplanosistema = 1
                          and c120_conplano = c60_codcon
                          and c120_anousu=c60_anousu)), '''') as atributos
      from conplano
             inner join contabilidade.conplanoreduz on conplano.c60_codcon = conplanoreduz.c61_codcon
                                                         and conplano.c60_anousu = c61_anousu
             left join (with lancamentos as (select c124_sequencial as codigo,
                                                    c124_data       as data,
                                                    c124_natureza   as natureza,
                                                    c124_valor      as valor,
                                                    c124_lancamento as codigo_lancamento,
                                                    c71_coddoc      as documento,
                                                    c123_reduzido   as reduzido,
                                                    conp.c60_estrut as estrutural,
                                                    conp.c60_descr  as nome_conta,
                                                    c123_valor      as valor_atributo,
                                                    c121_sigla      as sigla_atributo,
                                                    c121_sequencial as ordem
                                             from infocomplementarvalor
                                                    inner join conplanoatributolancamentos
                                                      on c124_sequencial = c123_conplanoatributolancamentos
                                                    inner join conplanoinfocomplementar
                                                      on c121_sequencial = c123_infocomplementar
                                                    inner join conplanoreduz conpred
                                                      on conpred.c61_reduz = c123_reduzido
                                                           and extract(year from c124_data) :: int = conpred.c61_anousu
                                                    inner join conplano conp on conpred.c61_codcon = conp.c60_codcon
                                                                                  and
                                                                                conp.c60_anousu = conpred.c61_anousu
                                                    left join conlancam on c70_codlan = c124_lancamento
                                                    left join conlancamdoc on c71_codlan = c70_codlan

                                             where c124_data >=
                                                   (extract(year from ''#data_inicial#'' :: date) :: varchar ||''-01-01'') :: date
                                               and c124_data <= ''#data_final#'' :: date
                                               and c123_conplanosistema = 1
                                             order by c124_sequencial, c71_coddoc, c124_data, c123_reduzido,
                                                      c121_sequencial,
                                                      c124_lancamento,
                                                      conp.c60_estrut),
          conta_corrente as (select codigo,
                                    data,
                                    natureza,
                                    valor,
                                    codigo_lancamento,
                                    reduzido,
                                    estrutural,
                                    nome_conta,
                                    array_to_string(
                                      array_agg(valor_atributo||''#''||sigla_atributo order by ordem),
                                      ''|'') as atributos
                             from lancamentos
                             group by codigo,
                                      data,
                                      natureza,
                                      valor,
                                      codigo_lancamento,
                                      reduzido,
                                      estrutural,
                                      nome_conta
                             order by codigo,
                                      data,
                                      natureza,
                                      valor,
                                      codigo_lancamento,
                                      reduzido,
                                      estrutural)


      select estrutural, reduzido, (
                                     coalesce(sum(case when natureza = ''C'' then valor else valor * -1 end),
                                              0)) as saldo_conta_matriz
      from conta_corrente
      where data >= (extract(year from ''#data_inicial#'' :: date) :: varchar ||''-01-01'') :: date
        and data <= ''#data_final#'' :: date
      GROUP by estrutural, reduzido
      order by estrutural) as valores_matriz on valores_matriz.reduzido = conplanoreduz.c61_reduz
      where c60_anousu = extract(year from ''#data_inicial#'' :: date)

      group by c60_estrut
      order by c60_estrut) as tabelao_saldo
order by estrutural) as tt
where abs(saldo_balancete) <> abs(saldo_conta_matriz)
"
  }
}' );

SQL
        );
    }
}
