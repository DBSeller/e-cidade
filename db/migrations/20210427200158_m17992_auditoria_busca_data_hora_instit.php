<?php

use Classes\PostgresMigration;

class M17992AuditoriaBuscaDataHoraInstit extends PostgresMigration
{

    public function up()
    {
        $sql = <<<SQL_UP
        DROP FUNCTION IF EXISTS configuracoes.fc_auditoria_busca_datahora_e_instit(DATE, INTEGER, INTEGER);
        CREATE FUNCTION configuracoes.fc_auditoria_busca_datahora_e_instit(
            data_inicial DATE,
            id_acount_ini INTEGER,
            id_acount_fim INTEGER,
            OUT datahora_ini TIMESTAMPTZ,
            OUT datahora_fim TIMESTAMPTZ,
            OUT instit INTEGER[])
        AS $$
        BEGIN
             WITH acount (_datahr, _instit) AS (
                  SELECT
                       DISTINCT ON (a.datahr)
                       to_timestamp(a.datahr),
                       COALESCE(i.id_instit, (SELECT codigo FROM db_config WHERE prefeitura IS TRUE LIMIT 1))
                  FROM
                       db_acount a
                       LEFT JOIN db_userinst i on i.id_usuario = a.id_usuario
                  WHERE
                       a.id_acount BETWEEN id_acount_ini AND id_acount_fim
             ),
             acount_e_acesso_agg (_datahora_ini, _datahora_fim, _instit) AS (
                  SELECT
                       min(_datahr),
                       max(_datahr),
                       _instit
                  FROM
                       acount
                  GROUP BY
                       _instit
                  UNION ALL
                  SELECT
                       (min(data)||' '||min(hora))::timestamptz,
                       (max(data)||' '||max(hora))::timestamptz,
                       la.instit
                  FROM
                       db_acountacesso ac
                       JOIN db_logsacessa la    ON la.codsequen = ac.codsequen
                                                AND la.data     >= data_inicial
                                                AND la.instit   IN (SELECT codigo FROM db_config)
                  WHERE
                       ac.id_acount BETWEEN id_acount_ini AND id_acount_fim
                  GROUP BY
                       la.instit
             )
             SELECT
                  min(_datahora_ini),
                  max(_datahora_fim),
                  array_agg(DISTINCT _instit)
             INTO
                  datahora_ini,
                  datahora_fim,
                  instit
             FROM
                  acount_e_acesso_agg;
        END;
        $$
        LANGUAGE plpgsql STABLE;
        
        -- \timing on
        -- select fc_auditoria_busca_datahora_e_instit((current_date - interval '6 months')::date, 340776498, 340777497 );
        -- select fc_auditoria_adiciona_acount_fila();
        -- select fc_auditoria_adiciona_todos_acount_fila();
               
SQL_UP;
        $this->execute($sql);
    }

    public function down()
    {
        $sql = <<<SQL_DOWN
        DROP FUNCTION IF EXISTS configuracoes.fc_auditoria_busca_datahora_e_instit(DATE, INTEGER, INTEGER);
        CREATE OR REPLACE FUNCTION configuracoes.fc_auditoria_busca_datahora_e_instit(
            data_inicial DATE,
            id_acount_ini INTEGER,
            id_acount_fim INTEGER,
            OUT datahora_ini TIMESTAMPTZ,
            OUT datahora_fim TIMESTAMPTZ,
            OUT instit INTEGER[])
        
        AS $$
          select min(datahora_ini) as datahora_ini,
                 max(datahora_fim) as datahora_fim,
                 array_agg(distinct instit) as instit
            from ( (select to_timestamp(min(datahr)) as datahora_ini,
                           to_timestamp(max(datahr)) as datahora_fim,
                           instit
                      from (select datahr,
                                   coalesce((select min(i.id_instit) from db_userinst i where i.id_usuario=a.id_usuario),
                                   (select codigo from db_config where prefeitura is true limit 1)) as instit
                              from db_acount a
                             where not exists
                                     (select 1
                                        from db_acountacesso ac
                                             join db_logsacessa la  on la.codsequen = ac.codsequen
                                                                   and la.data >= $1
                                                                   and la.instit = coalesce((select min(i.id_instit) from db_userinst i where i.id_usuario=a.id_usuario),
                                                                                            (select codigo from db_config where prefeitura is true limit 1))
                                       where ac.id_acount = a.id_acount)
                               and a.id_acount between $2 and $3
                           ) as y
                     group by instit)
                   union all
                   (select (min(data)||' '||min(hora))::timestamptz as datahora_ini,
                           (max(data)||' '||max(hora))::timestamptz as datahora_fim,
                           la.instit
                      from db_acountacesso ac join db_logsacessa la on la.codsequen=ac.codsequen
                                                                   and la.data >= $1
                                                                   and la.instit in (select codigo from db_config)
                     where ac.id_acount between $2 and $3
                     group by la.instit)
                 ) as x
        $$
        LANGUAGE sql;
               
SQL_DOWN;
    
        $this->execute($sql);
    }

}
