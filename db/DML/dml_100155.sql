UPDATE db_auditoria_migracao
   SET datahora_ini = COALESCE((dhi).datahora_ini, NOW() - interval '6 months'),
       datahora_fim = COALESCE((dhi).datahora_fim, NOW()),
       instit       = COALESCE((dhi).instit, (SELECT array_agg(codigo) FROM db_config))
  FROM (SELECT sequencial,
               fc_auditoria_busca_datahora_e_instit((current_date - interval '6 months')::date, id_acount_ini, id_acount_fim) AS dhi
          FROM db_auditoria_migracao
         WHERE status <> 'FINALIZADO'
           AND datahora_ini IS NULL
           AND datahora_fim IS NULL
           AND instit       IS NULL) AS x
 WHERE db_auditoria_migracao.sequencial = x.sequencial;
