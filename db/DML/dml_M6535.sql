INSERT into selecao (r44_selec, r44_descr, r44_obs, r44_instit, r44_where)
     values ((select max(r44_selec) + 1 from selecao where r44_instit = 1), 'TRIÊNIO', '', 1,
             'RH02_REGIST IN (12190619,12213874,12214641,12241016,12244820,12261196,12265734,12288769,12290989,12298479,12298727)
              and RH05_SEQPES IS NULL AND rh30_vinculo = \'A\''
            );

INSERT into selecao (r44_selec, r44_descr, r44_obs, r44_instit, r44_where)
     values ((select max(r44_selec) + 1 from selecao where r44_instit = 1), 'QUINQUÊNIO PREFEITURA', '', 1,
             'RH02_REGIST not IN (12190619,12213874,12214641,12241016,12244820,12261196,12265734,12288769,12290989,12298479,12298727)
              and RH05_SEQPES IS NULL AND rh30_vinculo = \'A\''
            );

INSERT into selecao (r44_selec, r44_descr, r44_obs, r44_instit, r44_where)
     values ((select max(r44_selec) + 1 from selecao where r44_instit = 1), 'QUINQUÊNIO FME', '', 11,
             'RH05_SEQPES IS NULL AND rh30_vinculo = \'A\''
            );

-- UPDATE db_formulas SET db148_formula =
INSERT INTO db_formulas values( nextval('db_formulas_db148_sequencial_seq'),
                                'CODIGO_ASSENTAMENTO_AVERBACAO',
                                'Retorna o código sequencial do assentamento de averbação para contar no período de direito ao trienio/quinquenio.',
                                'select array_accum(h12_codigo) as codigo_assentamento_averbacao from tipoasse where h12_codigo in(288, 237, 3, 6)', -- where db148_nome = 'CODIGO_ASSENTAMENTO_AVERBACAO';
                              false);

INSERT INTO db_formulas values( nextval('db_formulas_db148_sequencial_seq'),
                                'CODIGO_ASSENTAMENTO_ULTIMO_TRIENIO',
                                'Retorna o código sequencial do assentamento do último trienio/quinquenio para iniciar período de direito.',
                                'select 349 as codigo_assentamento_ultimo_trienio',
                              false);

INSERT INTO db_formulas values( nextval('db_formulas_db148_sequencial_seq'),
                                'CODIGO_ASSENTAMENTO_ULTIMO_QUINQUENIO',
                                'Retorna o código sequencial do assentamento do último trienio/quinquenio para iniciar período de direito.',
                                'select 350 as codigo_assentamento_ultimo_quinquenio',
                              false);

INSERT INTO db_formulas values( nextval('db_formulas_db148_sequencial_seq'),
                                'CODIGO_ASSENTAMENTO_TRIENIO_QUINQUENIO_ANTERIOR',
                                'Retorna o código sequencial do assentamento do último trienio/quinquenio para iniciar período de direito.',
                                'select 202 as codigo_assentamento_trienio_quinquenio_anterior',
                              false);

INSERT INTO db_formulas values( nextval('db_formulas_db148_sequencial_seq'),
                                'RUBRICAS_TRIENIO_SMA',
                                'Retorna as rubricas do trienio para SMA.',
                                'select array_accum(rh27_rubric) from rhrubricas where rh27_rubric in(\'0208\')',
                              false);

INSERT INTO db_formulas values( nextval('db_formulas_db148_sequencial_seq'),
                                'RUBRICAS_QUINQUENIO_SMA',
                                'Retorna as rubricas do quinquenio para sma.',
                                'select array_accum(rh27_rubric) from rhrubricas where rh27_rubric in(\'0109\', \'1109\')',
                              false);

INSERT INTO db_formulas values( nextval('db_formulas_db148_sequencial_seq'),
                                'RUBRICAS_QUINQUENIO_FME',
                                'Retorna as rubricas do quinquenio pa fme.',
                                'select array_accum(rh27_rubric) from rhrubricas where rh27_rubric in(\'0010\')',
                              false);

INSERT INTO db_formulas values( nextval('db_formulas_db148_sequencial_seq'),
                                'FALTAS_TRIENIO_QUINQUENIO',
                                'Retorna os assentamentos de faltas para trienio/quinquenio.',
                                'select array_accum(h12_codigo) as codigo_assentamento_faltas_trienio_quinquenio from tipoasse where h12_codigo in(294, 14, 304, 277, 273, 248, 330, 323, 143, 312)',
                              false);

-- UPDATE db_formulas SET db148_formula =
INSERT into db_formulas values( nextval('db_formulas_db148_sequencial_seq'),
                             'DADOS_QUINQUENIO',
                             'Fórmula que retorna o percentual de gratificação a ser lançado na rubrica.',
                              'select
                                  case 
                                       when (select quantidade_direito from fc_direitos_niteroi([SERVIDOR], [CODIGO_ASSENTAMENTO_AVERBACAO], [RUBRICAS_QUINQUENIO_SMA],  \'quinquenio\'::varchar) as dados) >= 35 then 0
                                       when data_ultima_gratificacao is null and ((SELECT anosaverbacao FROM fc_direitos_niteroi([SERVIDOR], [CODIGO_ASSENTAMENTO_AVERBACAO], [RUBRICAS_QUINQUENIO_SMA],  \'quinquenio\'::varchar) as dados) / 5) > 0  then 1
                                       when data_ultima_gratificacao is null and (fim + (diasafastamentototal || \'days\')::interval) <= current_date then 1
                                       when data_ultima_gratificacao is not null and (fim + (diasafastamentototal || \'days\')::interval) <= current_date then 1
                                       else 0
                                   end as condicao,  *
                                from (select
                                        [INICIO_QUINQUENIO] as inicio,
                                        [FIM_QUINQUENIO] as fim,
                                        (select diasafastamentototal from fc_gts_padrao([INICIO_QUINQUENIO],
                                                                                        [FIM_QUINQUENIO],
                                                                                        [SERVIDOR],
                                                                                        1,
                                                                                        0,
                                                                                        [FALTAS_TRIENIO_QUINQUENIO],
                                                                                        null)),
                                        (select h16_dtconc from assenta where h16_regist = [SERVIDOR] and h16_assent = [CODIGO_ASSENTAMENTO_ULTIMO_QUINQUENIO] order by h16_dtconc desc limit 1) as data_ultima_gratificacao
                                      ) as dados', -- where db148_nome = 'DADOS_QUINQUENIO';
                            false);

-- UPDATE db_formulas SET db148_formula =
INSERT INTO db_formulas values( nextval('db_formulas_db148_sequencial_seq'),
                                'INICIO_QUINQUENIO',
                                'Retorna a data de início para o período de direito do quinquenio.',
                                'select(case when ultimo_quinquenio is not null
                                             then ultimo_quinquenio
                                             else admissao
                                         end) as inicio
                                 from(select
                                         rh01_regist,
                                         rh01_admiss as admissao,
                                         (select case when h16_dtterm is null then h16_dtconc else h16_dtterm end as trienio from assenta where h16_regist = rh01_regist and (h16_assent = [CODIGO_ASSENTAMENTO_ULTIMO_QUINQUENIO] or h16_assent= [CODIGO_ASSENTAMENTO_TRIENIO_QUINQUENIO_ANTERIOR]) order by h16_assent desc, h16_dtconc desc limit 1) as ultimo_quinquenio
                                        from
                                         rhpessoal
                                        where
                                         rh01_regist IN [SERVIDOR]
                                       ) as dados', -- where db148_nome = 'INICIO_QUINQUENIO';
                              false);

-- UPDATE db_formulas SET db148_formula =
INSERT into db_formulas values( nextval('db_formulas_db148_sequencial_seq'),
                               'FIM_QUINQUENIO',
                               'Formula responsável por retornar a data final para então verificar faltas que possam protelar o direito, primeira data fim.',
                               'select ( ((([INICIO_QUINQUENIO] + interval \'5 years\'))::Date ) - 1 - (SELECT saldoaverbacao FROM fc_direitos_niteroi([SERVIDOR], [CODIGO_ASSENTAMENTO_AVERBACAO], [RUBRICAS_QUINQUENIO_SMA],  \'quinquenio\') as dados) )::date', -- where db148_nome = 'FIM_QUINQUENIO';
                              false);

-- UPDATE db_formulas SET db148_formula =
INSERT into db_formulas values( nextval('db_formulas_db148_sequencial_seq'), 
                                'CONDICAO_QUINQUENIO',
                                'Fórmula responsável por verificar se o servidor tem direito à lançar o assentamento para o tipo configurado.',
                                'select condicao from [DADOS_QUINQUENIO] as direito', -- where db148_nome = 'CONDICAO_QUINQUENIO';
                                false);

-- UPDATE db_formulas SET db148_formula =
INSERT INTO db_formulas values( nextval('db_formulas_db148_sequencial_seq'),
                                'DATA_ASSENTAMENTO_QUINQUENIO',
                                'Retorna a data de início para o período de direito do quinquenio.',
                                'select case when (select exists(select h16_codigo from assenta where h16_regist = [SERVIDOR] and h16_assent = [CODIGO_ASSENTAMENTO_ULTIMO_QUINQUENIO])) 
                                             then (fim + (diasafastamentototal || \'days\')::interval)::date
                                             else 
                                               case when ( (select anosaverbacao from fc_direitos_niteroi([SERVIDOR], [CODIGO_ASSENTAMENTO_AVERBACAO] , [RUBRICAS_QUINQUENIO_SMA], \'quinquenio\') ) / 5 > 0 )
                                                    then (
                                                      select
                                                        rh01_admiss
                                                      from
                                                        rhpessoal
                                                      where
                                                        rh01_regist =  [SERVIDOR])
                                                   else (fim + (diasafastamentototal || \'days\')::interval)::date
                                              end
                                             end from [DADOS_QUINQUENIO] as dados', -- where db148_nome = 'DATA_ASSENTAMENTO_QUINQUENIO';
                              false);

 -- UPDATE db_formulas SET db148_formula =
INSERT INTO db_formulas values( nextval('db_formulas_db148_sequencial_seq'),
                               'PERCENTUAL_QUINQUENIO_SMA',
                               'Retorna a data de início para o período de direito do quinquenio.',
                               'select
                                  case when quinquenio_anterior >= percentual_maximo_quinquenio then percentual_maximo_quinquenio
                                       when qtde_assentamentos = 1 and trunc(anos_averbacao/percentual_quinquenio) > 0 then coalesce(trunc(anos_averbacao/percentual_quinquenio) * percentual_quinquenio, 0)
                                       when qtde_assentamentos >= 1 then round((dia_gratificacao_atual::float/30 * percentual_quinquenio), 2) + quinquenio_anterior
                                   end as percentual
                                from (select
                                        rh01_regist as matricula,
                                        (select count(h16_codigo) from assenta where (h16_assent = [CODIGO_ASSENTAMENTO_ULTIMO_QUINQUENIO] or h16_assent = [CODIGO_ASSENTAMENTO_TRIENIO_QUINQUENIO_ANTERIOR]) and h16_regist = rh01_regist) as qtde_assentamentos,
                                        (SELECT anosaverbacao FROM fc_direitos_niteroi(rh01_regist, [CODIGO_ASSENTAMENTO_AVERBACAO], [RUBRICAS_QUINQUENIO_SMA], \'quinquienio\' ) as dados) as anos_averbacao,
                                        (select 5) as primeiro_quinquenio,
                                        (select 5) as percentual_quinquenio,
                                        (select 35) as percentual_maximo_quinquenio,
                                        coalesce((select r53_quant
                                                    from gerffx
                                                   where r53_instit = fc_getsession(\'DB_instit\')::int
                                                     and r53_anousu = fc_anofolha(fc_getsession(\'DB_instit\')::int)
                                                     and r53_mesusu = fc_mesfolha(fc_getsession(\'DB_instit\')::int)
                                                     and r53_rubric in (\'0109\', \'1109\')
                                                     and r53_regist = rh01_regist
                                                   order by r53_quant desc
                                                   limit 1
                                                 ), 0) as quinquenio_anterior,
                                        (select (30-extract(day from h16_dtconc)::int)+1 as dia_gratificacao_atual
                                           from assenta
                                          where h16_assent = [CODIGO_ASSENTAMENTO_ULTIMO_QUINQUENIO]
                                            and h16_regist = rh01_regist
                                          order by h16_dtconc desc limit 1)
                                      from
                                        rhpessoal
                                      where
                                        rh01_regist = (select h16_regist from assenta where h16_codigo = [CODIGO_ASSENTAMENTO])
                                ) as dados', -- where db148_nome = 'PERCENTUAL_QUINQUENIO_SMA';
                              false);

/**
 * Dados do trieniox
 */
-- UPDATE db_formulas SET db148_formula =
INSERT into db_formulas values( nextval('db_formulas_db148_sequencial_seq'),
                              'DADOS_TRIENIO',
                              'Fórmula que retorna o percentual de gratificação a ser lançado na rubrica.',
                              'select 
                                  case when data_ultima_gratificacao is null and ((SELECT anosaverbacao FROM fc_direitos_niteroi([SERVIDOR], [CODIGO_ASSENTAMENTO_AVERBACAO], string_to_array(\'0218\', \',\'),  \'trienio\'::varchar) as dados) / 3) > 0  then 1
                                       when data_ultima_gratificacao is null and (fim + (diasafastamentototal || \'days\')::interval) <= current_date then 1
                                       when data_ultima_gratificacao is not null and (fim + (diasafastamentototal || \'days\')::interval) <= current_date then 1
                                       else 0
                                   end as condicao, *
                                from (select
                                        [INICIO_TRIENIO] as inicio,
                                        [FIM_TRIENIO] as fim,
                                        (select diasafastamentototal from fc_gts_padrao([INICIO_TRIENIO],
                                                                                        [FIM_TRIENIO],
                                                                                        [SERVIDOR],
                                                                                        1,
                                                                                        0,
                                                                                        [FALTAS_TRIENIO_QUINQUENIO],
                                                                                        null)),
                                        (select h16_dtconc from assenta where h16_regist = [SERVIDOR] and h16_assent = [CODIGO_ASSENTAMENTO_ULTIMO_TRIENIO] order by h16_dtconc desc limit 1) as data_ultima_gratificacao
                                      ) as dados', --  where db148_nome = 'DADOS_TRIENIO';
                            false);

-- UPDATE db_formulas SET db148_formula =
INSERT INTO db_formulas values( nextval('db_formulas_db148_sequencial_seq'),
                                'INICIO_TRIENIO',
                                'Retorna a data de início para o período de direito do trienio.',
                                'select(case when ultimo_trienio is not null
                                             then ultimo_trienio
                                             else admissao
                                         end) as inicio
                                 from(select
                                         rh01_regist,
                                         rh01_admiss as admissao,
                                         (select case when h16_dtterm is null then h16_dtconc else h16_dtterm end as trienio from assenta where h16_regist = rh01_regist and (h16_assent = [CODIGO_ASSENTAMENTO_ULTIMO_TRIENIO] or h16_assent= [CODIGO_ASSENTAMENTO_TRIENIO_QUINQUENIO_ANTERIOR]) order by h16_assent desc, h16_dtconc desc limit 1) as ultimo_trienio
                                        from
                                         rhpessoal
                                        where
                                         rh01_regist IN [SERVIDOR]
                                       ) as dados', -- where db148_nome = 'INICIO_TRIENIO';
                              false);

 UPDATE db_formulas SET db148_formula =
-- INSERT into db_formulas values( nextval('db_formulas_db148_sequencial_seq'),
                               -- 'FIM_TRIENIO',
                               -- 'Formula responsável por retornar a data final para então verificar faltas que possam protelar o direito, primeira data fim.',
                               'select ( ((([INICIO_TRIENIO] + interval \'3 years\'))::Date)-1 - (SELECT saldoaverbacao FROM fc_direitos_niteroi([SERVIDOR], [CODIGO_ASSENTAMENTO_AVERBACAO], string_to_array(\'0218\', \',\'), \'trienio\'::VARCHAR) as dados) )::date' where db148_nome = 'FIM_TRIENIO';
                              false);

 -- UPDATE db_formulas SET db148_formula =
INSERT into db_formulas values( nextval('db_formulas_db148_sequencial_seq'), 
                                'CONDICAO_TRIENIO',
                                'Fórmula responsável por verificar se o servidor tem direito à lançar o assentamento para o tipo configurado.',
                                'select condicao from [DADOS_TRIENIO] as direito', -- where db148_nome = 'CONDICAO_TRIENIO';
                                false);

-- UPDATE db_formulas SET db148_formula =
INSERT INTO db_formulas values( nextval('db_formulas_db148_sequencial_seq'),
                               'DATA_ASSENTAMENTO_TRIENIO',
                               'Retorna a data de início para o período de direito do trienio.',
                               'select case when (select exists(select h16_codigo from assenta where h16_regist = [SERVIDOR] and (h16_assent = [CODIGO_ASSENTAMENTO_ULTIMO_TRIENIO] or h16_assent= [CODIGO_ASSENTAMENTO_TRIENIO_QUINQUENIO_ANTERIOR])))
                                             then (fim+(diasafastamentototal || \'days\')::interval)::date
                                             else
                                            case when ( (select anosaverbacao from fc_direitos_niteroi([SERVIDOR], [CODIGO_ASSENTAMENTO_AVERBACAO], [RUBRICAS_TRIENIO_SMA], \'trienio\') ) / 3 > 0 )
                                                    then (
                                                      select
                                                        rh01_admiss
                                                      from
                                                        rhpessoal
                                                      where
                                                        rh01_regist =  [SERVIDOR])
                                                   else (fim+(diasafastamentototal || \'days\')::interval)::date
                                              end end from [DADOS_TRIENIO] as dados', -- where db148_nome = 'DATA_ASSENTAMENTO_TRIENIO';
                             false);


-- UPDATE db_formulas SET db148_formula =
INSERT INTO db_formulas values( nextval('db_formulas_db148_sequencial_seq'),
                               'PERCENTUAL_TRIENIO',
                               'Retorna a data de início para o período de direito do trienio.',
                               'select
                                    case when qtde_assentamentos = 1 and trunc(anos_averbacao/trienio) > 0 then coalesce((trunc(anos_averbacao/trienio)-1) * percentual, 0) + primeiro_trienio
                                         when qtde_assentamentos >= 1 and trienio_anterior > 0 then round(((dia_gratificacao_atual::float/30) * percentual), 2) + trienio_anterior
                                         else round(((dia_gratificacao_atual::float/30) * primeiro_trienio), 2)
                                     end as percentual
                                  from (select
                                          rh01_regist as matricula,
                                          (select count(h16_codigo) from assenta where (h16_assent = [CODIGO_ASSENTAMENTO_ULTIMO_TRIENIO] or h16_assent = [CODIGO_ASSENTAMENTO_TRIENIO_QUINQUENIO_ANTERIOR]) and h16_regist = rh01_regist) as qtde_assentamentos,
                                          (SELECT coalesce(anosaverbacao, 0) FROM fc_direitos_niteroi(rh01_regist, [CODIGO_ASSENTAMENTO_AVERBACAO], [RUBRICAS_TRIENIO_SMA], \'trienio\') as dados) as anos_averbacao,
                                          (select 10) as primeiro_trienio,
                                          (select 3) as trienio,
                                          (select 5) as percentual,
                                          coalesce((select r53_quant
                                                      from gerffx
                                                     where r53_instit = fc_getsession(\'DB_instit\')::int
                                                       and r53_anousu = fc_anofolha(fc_getsession(\'DB_instit\')::int)
                                                       and r53_mesusu = fc_mesfolha(fc_getsession(\'DB_instit\')::int)
                                                       and r53_rubric in (\'0208\')
                                                       and r53_regist = rh01_regist
                                                     order by r53_quant desc
                                                     limit 1
                                                   ), 0) as trienio_anterior,
                                          (select (30-extract(day from h16_dtconc)::int)+1 as dia_gratificacao_atual
                                             from assenta
                                            where h16_assent = [CODIGO_ASSENTAMENTO_ULTIMO_TRIENIO]
                                              and h16_regist = rh01_regist
                                            order by h16_dtconc desc limit 1)
                                        from
                                          rhpessoal
                                        where
                                          rh01_regist = (select h16_regist from assenta where h16_codigo = [CODIGO_ASSENTAMENTO])
                                  ) as dados', --where db148_nome = 'PERCENTUAL_TRIENIO';
                              false);

------FME -----

-- UPDATE db_formulas SET db148_formula =
INSERT into db_formulas values( nextval('db_formulas_db148_sequencial_seq'),
                                'DADOS_QUINQUENIO_FME',
                                'Fórmula que retorna o percentual de gratificação a ser lançado na rubrica.',
                                'select
                                  case
                                       when (select quantidade_direito from fc_direitos_niteroi([SERVIDOR], [CODIGO_ASSENTAMENTO_AVERBACAO], [RUBRICAS_QUINQUENIO_FME],  \'quinquenio\'::varchar) as dados) >= 35 then 0
                                       when data_ultima_gratificacao is null and ((SELECT anosaverbacao FROM fc_direitos_niteroi([SERVIDOR], [CODIGO_ASSENTAMENTO_AVERBACAO], [RUBRICAS_QUINQUENIO_FME],  \'quinquenio\'::varchar) as dados) / 5) > 0  then 1
                                       when data_ultima_gratificacao is null and (fim + (diasafastamentototal || \'days\')::interval) <= current_date then 1
                                       when data_ultima_gratificacao is not null and (fim + (diasafastamentototal || \'days\')::interval) <= current_date then 1
                                       else 0
                                   end as condicao,  *
                                from (select
                                        [INICIO_QUINQUENIO] as inicio,
                                        [FIM_QUINQUENIO_FME] as fim,
                                        (select diasafastamentototal from fc_gts_padrao([INICIO_QUINQUENIO],
                                                                                        [FIM_QUINQUENIO_FME],
                                                                                        [SERVIDOR],
                                                                                        1,
                                                                                        0,
                                                                                        [FALTAS_TRIENIO_QUINQUENIO],
                                                                                        null)),
                                        (select h16_dtconc from assenta where h16_regist = [SERVIDOR] and h16_assent = [CODIGO_ASSENTAMENTO_ULTIMO_QUINQUENIO] order by h16_dtconc desc limit 1) as data_ultima_gratificacao
                                      ) as dados', -- where db148_nome = 'DADOS_QUINQUENIO_FME';
                                false);

-- UPDATE db_formulas SET db148_formula =
INSERT INTO db_formulas values( nextval('db_formulas_db148_sequencial_seq'),
                                'PERCENTUAL_QUINQUENIO_FME',
                                'Retorna a data de início para o período de direito do quinquenio.',
                                'select
                                  case when quinquenio_anterior >= percentual_maximo_quinquenio then percentual_maximo_quinquenio
                                       when qtde_assentamentos = 1 and trunc(anos_averbacao/percentual_quinquenio) > 0 then coalesce(trunc(anos_averbacao/percentual_quinquenio) * percentual_quinquenio, 0)
                                       when qtde_assentamentos >= 1 then round((dia_gratificacao_atual::float/30 * percentual_quinquenio), 2) + quinquenio_anterior
                                   end as percentual
                                from (select
                                        rh01_regist as matricula,
                                        (select count(h16_codigo) from assenta where (h16_assent = [CODIGO_ASSENTAMENTO_ULTIMO_QUINQUENIO] or h16_assent = [CODIGO_ASSENTAMENTO_TRIENIO_QUINQUENIO_ANTERIOR]) and h16_regist = rh01_regist) as qtde_assentamentos,
                                        (SELECT anosaverbacao FROM fc_direitos_niteroi(rh01_regist, [CODIGO_ASSENTAMENTO_AVERBACAO], [RUBRICAS_QUINQUENIO_FME], \'quinquienio\' ) as dados) as anos_averbacao,
                                        (select 5) as primeiro_quinquenio,
                                        (select 5) as percentual_quinquenio,
                                        (select 35) as percentual_maximo_quinquenio,
                                        coalesce((select r53_quant
                                                    from gerffx
                                                   where r53_instit = fc_getsession(\'DB_instit\')::int
                                                     and r53_anousu = fc_anofolha(fc_getsession(\'DB_instit\')::int)
                                                     and r53_mesusu = fc_mesfolha(fc_getsession(\'DB_instit\')::int)
                                                     and r53_rubric in (\'0010\')
                                                     and r53_regist = rh01_regist
                                                   order by r53_quant desc
                                                   limit 1
                                                 ), 0) as quinquenio_anterior,
                                        (select (30-extract(day from h16_dtconc)::int)+1 as dia_gratificacao_atual
                                           from assenta
                                          where h16_assent = [CODIGO_ASSENTAMENTO_ULTIMO_QUINQUENIO]
                                            and h16_regist = rh01_regist
                                          order by h16_dtconc desc limit 1)
                                      from
                                        rhpessoal
                                      where
                                        rh01_regist = (select h16_regist from assenta where h16_codigo = [CODIGO_ASSENTAMENTO])
                                ) as dados',--  where db148_nome = 'PERCENTUAL_QUINQUENIO_FME';
                                false);

INSERT into db_formulas values( nextval('db_formulas_db148_sequencial_seq'),
                                'CONDICAO_QUINQUENIO_FME',
                                'Fórmula responsável por verificar se o servidor tem direito à lançar o assentamento para o tipo configurado.',
                                'select condicao from [DADOS_QUINQUENIO_FME] as direito',
                                false);

-- update db_formulas set db148_formula =
INSERT INTO db_formulas values( nextval('db_formulas_db148_sequencial_seq'),
                                'DATA_ASSENTAMENTO_QUINQUENIO_FME',
                                'Retorna a data de início para o período de direito do quinquenio.',
                                'select case when (select exists(select h16_codigo from assenta where h16_regist = [SERVIDOR] and (h16_assent = [CODIGO_ASSENTAMENTO_ULTIMO_QUINQUENIO] or h16_assent= [CODIGO_ASSENTAMENTO_TRIENIO_QUINQUENIO_ANTERIOR])))
                                             then fim + (diasafastamentototal || \'days\')::interval
                                             else
                                               case when ( (select anosaverbacao from fc_direitos_niteroi([SERVIDOR], [CODIGO_ASSENTAMENTO_AVERBACAO], [RUBRICAS_QUINQUENIO_FME], \'quinquenio\') ) / 5 > 0 )
                                                    then (
                                                      select
                                                        rh01_admiss
                                                      from
                                                        rhpessoal
                                                      where
                                                        rh01_regist =  [SERVIDOR])
                                                   else fim + (diasafastamentototal || \'days\')::interval
                                              end
                                             end from [DADOS_QUINQUENIO_FME] as dados', -- where db148_nome = 'DATA_ASSENTAMENTO_QUINQUENIO_FME';
                                false);

-- update db_formulas set db148_formula =
INSERT into db_formulas values( nextval('db_formulas_db148_sequencial_seq'),
                                'FIM_QUINQUENIO_FME',
                                'Formula responsável por retornar a data final para então verificar faltas que possam protelar o direito, primeira data fim.',
                                 'select (((([INICIO_QUINQUENIO] + interval \'5 years\'))::Date )-1 - (SELECT saldoaverbacao FROM fc_direitos_niteroi([SERVIDOR], [CODIGO_ASSENTAMENTO_AVERBACAO], [RUBRICAS_QUINQUENIO_FME],  \'quinquenio\') as dados) )::date', -- where db148_nome = 'FIM_QUINQUENIO_FME';
                                false);
