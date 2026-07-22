<?php

use Classes\PostgresMigration;

class M15219ConsistenciaRp extends PostgresMigration
{

    public function up()
    {
        $this->execute(<<<SQL
delete from consistenciasistema where db160_json ilike '%5dd711a05ef67%';
insert into consistenciasistema
values (10000010, 100, '{
  "tipo": 100,
  "uid": "5dd711a05ef67",
  "nome": "Conferência entre restos a pagar e contas do encerramento",
  "descricao": "Conferência entre restos a pagar e contas do encerramento",
  "formulario": {
    "campos": [
      {
        "propriedade": "rp_np_ex_ant",
        "nome": "RNP Ex", "chave_primaria": true
      },
      {
        "propriedade": "saldo_rnp_bal_ex_ant",
        "nome": "Conta 5317"
      },
      {
        "propriedade": "saldo_conta_6317",
        "nome": "conta 6317"
      },
      {
        "propriedade": "rp_np_outros_ex",
        "nome": "RNP Ex Ant"
      },
      {
        "propriedade": "saldo_rnp_bal_outros_ex",
        "nome": "Conta 6311"
      },
      {
        "propriedade": "saldo_conta_5312",
        "nome": "Conta 5312"
      },
      {
        "propriedade": "rp_proc_ex_ant",
        "nome": "RP Ex"
      },
      {
        "propriedade": "saldo_rp_bal_ex_ant",
        "nome": "Conta 6327"
      },
      {
        "propriedade": "saldo_conta_5327",
        "nome": "Conta 5327"
      },
      {
        "propriedade": "rp_proc_outros_ex",
        "nome": "RP Ex Ant"

      },
      {
        "propriedade": "saldo_rp_bal_outros_ex",
        "nome": "Conta 6321"
      },
      {
        "propriedade": "saldo_conta_5322",
        "nome": "Conta 5322"
      }
    ]
  },
  "sql": {
    "consistencia": "select round(rp_np_ex_ant, 2) as rp_np_ex_ant,
       round(saldo_rnp_bal_ex_ant, 2) as saldo_rnp_bal_ex_ant,
       round(saldo_conta_6317, 2) as saldo_conta_6317,
       round(rp_np_outros_ex, 2) as rp_np_outros_ex,
       round(saldo_rnp_bal_outros_ex, 2) as saldo_rnp_bal_outros_ex,
       round(saldo_conta_5312, 2) as saldo_conta_5312,
       round(rp_proc_ex_ant, 2) as rp_proc_ex_ant,
       round(saldo_rp_bal_ex_ant, 2) as saldo_rp_bal_ex_ant,
       round(saldo_conta_5327, 2) as  saldo_conta_5327,
       round(rp_proc_outros_ex, 2) as rp_proc_outros_ex,
       round(saldo_rp_bal_outros_ex, 2) as saldo_rp_bal_outros_ex,
       round(saldo_conta_5322, 2) as  saldo_conta_5322
 from (
         select sum(case
                        when e60_anousu = e91_anousu - 1 then e91_vlremp - e91_vlranu - e91_vlrliq
                        else 0 end)                                                                    as rp_np_ex_ant,

                (select sum((select sum(saldo_final)
                             from fc_planosaldonovo_record(c61_anousu, c61_reduz, (fc_getsession(''DB_anousu'')::integer||''-01-01'')::date,
                                                           (fc_getsession(''DB_anousu'')::integer||''-12-31'')::date, true)
                         )) as saldo
                 from contabilidade.conplano
                          inner join contabilidade.conplanoreduz
                                     on conplano.c60_codcon = conplanoreduz.c61_codcon
                                         and conplano.c60_anousu = conplanoreduz.c61_anousu
                 where c61_instit = fc_getsession(''DB_instit'')::integer
                   and c61_anousu = fc_getsession(''DB_anousu'')::integer
                   and (c60_estrut like ''5317%'') limit 1
                )                                                                                      as saldo_rnp_bal_ex_ant,

                (select sum((select sum(saldo_final)
                             from fc_planosaldonovo_record(c61_anousu, c61_reduz, (fc_getsession(''DB_anousu'')::integer||''-01-01'')::date,
                                                           (fc_getsession(''DB_anousu'')::integer||''-12-31'')::date, true)
                )) as saldo
                 from contabilidade.conplano
                          inner join contabilidade.conplanoreduz
                                     on conplano.c60_codcon = conplanoreduz.c61_codcon
                                         and conplano.c60_anousu = conplanoreduz.c61_anousu
                 where c61_instit = fc_getsession(''DB_instit'')::integer
                   and c61_anousu = fc_getsession(''DB_anousu'')::integer
                   and (c60_estrut like ''6317%'')
                limit 1  )                                                                                      as saldo_conta_6317,
                sum(case
                        when e60_anousu < e91_anousu - 1 then e91_vlremp - e91_vlranu - e91_vlrliq
                        else 0 end)                                                                    as rp_np_outros_ex,
                (select sum((select sum(saldo_final)
                         from fc_planosaldonovo_record(c61_anousu, c61_reduz, (fc_getsession(''DB_anousu'')::integer||''-01-01'')::date,
                                                       (fc_getsession(''DB_anousu'')::integer||''-12-31'')::date, true))     )
                       from contabilidade.conplano
                          inner join contabilidade.conplanoreduz
                                     on conplano.c60_codcon = conplanoreduz.c61_codcon
                                         and conplano.c60_anousu = conplanoreduz.c61_anousu
                 where c61_instit = fc_getsession(''DB_instit'')::integer
                   and c61_anousu = fc_getsession(''DB_anousu'')::integer
                   and (c60_estrut like ''6311%'' or c60_estrut like ''6312%'') limit 1
                )                                                                                      as saldo_rnp_bal_outros_ex,
                (select (select sum(saldo_final)
                         from fc_planosaldonovo_record(c61_anousu, c61_reduz, (fc_getsession(''DB_anousu'')::integer||''-01-01'')::date,
                                                       (fc_getsession(''DB_anousu'')::integer||''-12-31'')::date, true)) as saldo
                 from contabilidade.conplano
                          inner join contabilidade.conplanoreduz
                                     on conplano.c60_codcon = conplanoreduz.c61_codcon
                                         and conplano.c60_anousu = conplanoreduz.c61_anousu
                 where c61_instit = fc_getsession(''DB_instit'')::integer
                   and c61_anousu = fc_getsession(''DB_anousu'')::integer
                   and c60_estrut like ''5312%''
                limit 1  )                                                                                      as saldo_conta_5312,
                sum(
                        case when e60_anousu = e91_anousu - 1 then e91_vlrliq - e91_vlrpag else 0 end) as rp_proc_ex_ant,
                ((select (select sum(saldo_final)
                          from fc_planosaldonovo_record(c61_anousu, c61_reduz, (fc_getsession(''DB_anousu'')::integer||''-01-01'')::date,
                                                        (fc_getsession(''DB_anousu'')::integer||''-12-31'')::date, true)) as saldo
                  from contabilidade.conplano
                           inner join contabilidade.conplanoreduz
                                      on conplano.c60_codcon = conplanoreduz.c61_codcon
                                          and conplano.c60_anousu = conplanoreduz.c61_anousu

                  where c61_instit = fc_getsession(''DB_instit'')::integer
                    and c61_anousu = fc_getsession(''DB_anousu'')::integer
                    and c60_estrut like ''6327%''
                ) limit 1 )                                                                                     as saldo_rp_bal_ex_ant,
                ((select (select sum(saldo_final)
                          from fc_planosaldonovo_record(c61_anousu, c61_reduz, (fc_getsession(''DB_anousu'')::integer||''-01-01'')::date,
                                                        (fc_getsession(''DB_anousu'')::integer||''-12-31'')::date, true)) as saldo
                  from contabilidade.conplano
                           inner join contabilidade.conplanoreduz
                                      on conplano.c60_codcon = conplanoreduz.c61_codcon
                                          and conplano.c60_anousu = conplanoreduz.c61_anousu

                  where c61_instit = fc_getsession(''DB_instit'')::integer
                    and c61_anousu = fc_getsession(''DB_anousu'')::integer
                    and c60_estrut like ''5327%''
                ) limit 1  )                                                                                     as saldo_conta_5327,
                sum(
                        case when e60_anousu < e91_anousu - 1 then e91_vlrliq - e91_vlrpag else 0 end) as rp_proc_outros_ex,
                ((select (select sum(saldo_final)
                          from fc_planosaldonovo_record(c61_anousu, c61_reduz, (fc_getsession(''DB_anousu'')::integer||''-01-01'')::date,
                                                        (fc_getsession(''DB_anousu'')::integer||''-12-31'')::date, true)) as saldo
                  from contabilidade.conplano
                           inner join contabilidade.conplanoreduz
                                      on conplano.c60_codcon = conplanoreduz.c61_codcon
                                          and conplano.c60_anousu = conplanoreduz.c61_anousu

                  where c61_instit = fc_getsession(''DB_instit'')::integer
                    and c61_anousu = fc_getsession(''DB_anousu'')::integer
                    and c60_estrut like ''6321%''
                ) limit 1)                                                                                     as saldo_rp_bal_outros_ex,
                ((select (select sum(saldo_final)
                          from fc_planosaldonovo_record(c61_anousu, c61_reduz, (fc_getsession(''DB_anousu'')::integer||''-01-01'')::date,
                                                        (fc_getsession(''DB_anousu'')::integer||''-12-31'')::date, true)) as saldo
                  from contabilidade.conplano
                           inner join contabilidade.conplanoreduz
                                      on conplano.c60_codcon = conplanoreduz.c61_codcon
                                          and conplano.c60_anousu = conplanoreduz.c61_anousu

                  where c61_instit = fc_getsession(''DB_instit'')::integer
                    and c61_anousu = fc_getsession(''DB_anousu'')::integer
                    and c60_estrut like ''5322%''
                ) limit 1 )                                                                                     as saldo_conta_5322
         from empresto
                  inner join empempenho on e91_numemp = e60_numemp
         where e91_anousu = ( fc_getsession(''DB_anousu'')::integer::integer + 1 )
           and e60_instit = fc_getsession(''DB_instit'')::integer
     ) as dados;
",
    "correcao": ""
  }
}
');
SQL
        );

    }

    public function down()
    {
        $this->execute(<<<SQL
delete from consistenciasistema where db160_json ilike '%5dd711a05ef67%';
SQL
        );
    }
}
