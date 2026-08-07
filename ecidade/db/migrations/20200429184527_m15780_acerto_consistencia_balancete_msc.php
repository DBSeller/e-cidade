<?php

use Classes\PostgresMigration;

class M15780AcertoConsistenciaBalanceteMsc extends PostgresMigration
{

    public function up()
    {
        $this->execute(<<<SQL
delete from consistenciasistema where db160_json ilike '%5c6acff055a5a%';
SQL
        );

        $this->execute(<<<SQL
        insert into consistenciasistema values
                                       (nextval('consistenciasistema_db160_sequencial_seq'),
                                        10,
                                        '{
  "tipo": 1,
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
    "consistencia": "select estrutural, saldo_balancete, saldo_conta_matriz, atributos
from (select c60_estrut                                     as estrutural,
             sum((select round(saldos [ 4 ] :: numeric, 2)
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
                                              and  conpred.c61_instit in (select c125_db_config from contabilidade.configuracaoinstituicaosiconfi)
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


      select estrutural, reduzido, abs(
                                     coalesce(sum(case when natureza = ''D'' then valor else valor * -1 end),
                                              0)) as saldo_conta_matriz
      from conta_corrente
      where data >= (extract(year from ''#data_inicial#'' :: date) :: varchar ||''-01-01'') :: date
        and data <= ''#data_final#'' :: date
      GROUP by estrutural, reduzido
      order by estrutural) as valores_matriz on valores_matriz.reduzido = conplanoreduz.c61_reduz
      where c60_anousu = extract(year from ''#data_inicial#'' :: date)

      group by c60_estrut
      order by c60_estrut) as tabelao_saldo

where saldo_balancete <> saldo_conta_matriz
order by estrutural
"
  }
}' );
SQL
        );

    }

    public function down()
    {

    }
}
