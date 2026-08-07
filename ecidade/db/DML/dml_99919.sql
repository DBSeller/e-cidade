SELECT fc_executa_ddl('
  ALTER TABLE db_auditoria_migracao 
    ADD datahora_ini TIMESTAMP WITH TIME ZONE,
    ADD datahora_fim TIMESTAMP WITH TIME ZONE,
    ADD instit INTEGER[];');

CREATE OR REPLACE FUNCTION fc_auditoria_busca_datahora_e_instit(
	data_inicial DATE,
	id_acount_ini INTEGER,
	id_acount_fim INTEGER,
	OUT datahora_ini TIMESTAMPTZ,
	OUT datahora_fim TIMESTAMPTZ,
	OUT instit INTEGER[])

AS $$
  select min(datahora_ini) as datahora_ini,
         max(datahora_fim) as datahora_fim,
         array_agg(instit) as instit
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

-- Trunca db_auditoria_migracao caso nao tenham sido migrados registros para db_auditoria
SELECT CASE WHEN sum(reltuples)>0 THEN NULL ELSE fc_executa_ddl('TRUNCATE db_auditoria_migracao') END 
  FROM pg_class
 WHERE relkind='r' 
   AND relname ~ '^db_auditoria_[0-9]';

-- Ajusta registros ainda nao migrados
UPDATE db_auditoria_migracao
   SET datahora_ini = COALESCE((dhi).datahora_ini, NOW() - interval '6 months'),
       datahora_fim = COALESCE((dhi).datahora_fim, NOW()),
       instit       = COALESCE((dhi).instit, (SELECT array_agg(codigo) FROM db_config))
  FROM (SELECT sequencial,
               fc_auditoria_busca_datahora_e_instit((current_date - interval '1 week')::date, id_acount_ini, id_acount_fim) AS dhi
          FROM db_auditoria_migracao
         WHERE status <> 'FINALIZADO'
           AND (datahora_ini IS NULL OR datahora_fim IS NULL OR instit IS NULL)) AS x
 WHERE db_auditoria_migracao.sequencial = x.sequencial;


CREATE OR REPLACE FUNCTION fc_auditoria_adiciona_acount_fila()
 RETURNS void
 LANGUAGE sql
AS $function$

  SELECT NEXTVAL('configuracoes.db_auditoria_migracao_sequencial_seq');

  INSERT INTO configuracoes.db_auditoria_migracao (sequencial, id_acount_ini, id_acount_fim, status)
  SELECT CURRVAL('configuracoes.db_auditoria_migracao_sequencial_seq'),
         id_acount_ini,
         id_acount_fim,
         status
    FROM (SELECT COALESCE(MIN(id_acount), 0) AS id_acount_ini,
                 COALESCE(MAX(id_acount), 0) AS id_acount_fim,
                 'NAO INICIADO'::text        AS status
            FROM ONLY configuracoes.db_acount
           WHERE id_acount > COALESCE((SELECT id_acount_fim FROM configuracoes.db_auditoria_migracao ORDER BY id_acount_fim DESC LIMIT 1), 0)) AS lote
   WHERE (id_acount_ini + id_acount_fim) > 0;
  
  UPDATE db_auditoria_migracao
     SET datahora_ini = COALESCE((dhi).datahora_ini, NOW() - interval '6 months'),
         datahora_fim = COALESCE((dhi).datahora_fim, NOW()),
         instit       = COALESCE((dhi).instit, (SELECT array_agg(codigo) FROM db_config))
    FROM (SELECT sequencial,
                 fc_auditoria_busca_datahora_e_instit((current_date - interval '6 months')::date, id_acount_ini, id_acount_fim) AS dhi
            FROM db_auditoria_migracao
           WHERE sequencial = CURRVAL('configuracoes.db_auditoria_migracao_sequencial_seq')) AS x
   WHERE db_auditoria_migracao.sequencial = x.sequencial;

$function$;

