--  '--------------------------------------------------------------------------------------------'
--   Criando tabela com quantidade de vínculos dos servidores em todas as competencias de 2015
--  '--------------------------------------------------------------------------------------------'
drop table if exists w_duplosmaisaposentados;
create table w_duplosmaisaposentados AS
select *
  from (select (    select count(rh01_regist)
                      from rhpessoal
                 left join rhpessoalmov on rhpessoalmov.rh02_regist = rhpessoal.rh01_regist
                 left join rhpesrescisao on rhpessoalmov.rh02_seqpes = rhpesrescisao.rh05_seqpes
                 left join rhregime on rhregime.rh30_codreg = rhpessoalmov.rh02_codreg
                     where extract(years from age(rhpessoal.rh01_nasc)) >= 65
                       and rhpessoal.rh01_numcgm = rhp.rh01_numcgm
                       and rhpessoalmov.rh02_anousu = rhpmv.rh02_anousu
                       and rhpessoalmov.rh02_mesusu = rhpmv.rh02_mesusu
                       and rhpesrescisao.rh05_recis is null
                       and (rhregime.rh30_vinculo = 'I' or rhregime.rh30_vinculo = 'P')
                  group by rhpessoal.rh01_numcgm
                    having count(rh01_regist) > 1) as vinculos,
               rhp.rh01_numcgm as numero_cgm,
               extract(years from age(rhp.rh01_nasc)) as idade,
               rhp.rh01_regist as matriculas,
               rhpmv.rh02_anousu as ano,
               rhpmv.rh02_mesusu as mes,
               rhp.rh01_instit as instituicao
          from rhpessoal as rhp
     left join rhpessoalmov as rhpmv on rhpmv.rh02_regist = rhp.rh01_regist
     left join rhpesrescisao as rhpres on rhpmv.rh02_seqpes = rhpres.rh05_seqpes
     left join rhregime as rhreg on rhreg.rh30_codreg = rhpmv.rh02_codreg
         where extract(years from age(rhp.rh01_nasc)) >= 65
           and rhpmv.rh02_anousu = 2015
           and rhpres.rh05_recis is null
           and (rhreg.rh30_vinculo = 'I' or rhreg.rh30_vinculo = 'P')
      group by rhp.rh01_numcgm, rhp.rh01_regist, rhpmv.rh02_anousu, rhpmv.rh02_mesusu
      order by vinculos desc, rhp.rh01_numcgm desc, mes desc ) as duplosmaisaposentados
 where duplosmaisaposentados.vinculos > 1;

--  '--------------------------------------------------------------------------------------------'
--   Criando tabela para armazenar os valores da rubrica R997, R981
--   e valor da parcela de dudução para os aposentados e pensionistas
--   da tabela gerfsal que serão atualizados antes de fazer update
--  '--------------------------------------------------------------------------------------------'
drop table if exists w_gerfsal_duplosmaisaposentados;
create table w_gerfsal_duplosmaisaposentados AS
      select r07_valor as deducao_aposentado,
             valores_aposentados.*
        from (  select numero_cgm as cgm,
                       matriculas,
                       vinculos,
                       mes,
                       instituicao,
                       ano,
                       (select r14_valor
                          from gerfsal
                         where r14_anousu = aposentados.ano
                           and r14_mesusu = aposentados.mes
                           and r14_regist = aposentados.matriculas
                           and r14_instit = aposentados.instituicao
                           and r14_rubric = 'R997'
                        ) as valor_deducao,
                       ( select r14_valor
                          from gerfsal
                         where r14_anousu = aposentados.ano
                           and r14_mesusu = aposentados.mes
                           and r14_regist = aposentados.matriculas
                           and r14_instit = aposentados.instituicao
                           and r14_rubric = 'R981'
                       ) as valor_irrf
                  from w_duplosmaisaposentados as aposentados) as valores_aposentados
   left join pesdiver on r07_anousu = valores_aposentados.ano
                     and r07_mesusu = valores_aposentados.mes
                     and r07_instit = valores_aposentados.instituicao
                     and r07_codigo = 'D902'
    order by cgm, matriculas, vinculos desc, mes asc;

--  '--------------------------------------------------------------------------------------------'
--   Criando tabela temporária para guardar valores brutos de base de IRRF
--   por CGM somente quando a soma for maior que a parcela de dedução
--  '--------------------------------------------------------------------------------------------'
drop table if exists w_baseirrf_salario_por_cgm;
create table w_baseirrf_salario_por_cgm AS
      select sum(valor_irrf) as base_irf_cgm,
             cgm,
             mes,
             ano,
             instituicao
        from w_gerfsal_duplosmaisaposentados
   left join pesdiver on r07_anousu = ano
         and r07_mesusu             = mes
         and r07_instit             = instituicao
         and r07_codigo             = 'D902'
    group by cgm, mes, ano, instituicao, r07_valor
      having sum(valor_irrf) > r07_valor;

--  '--------------------------------------------------------------------------------------------'
--   Criando tabela para guardar o valor da parcela de isenção proporcional por matrícula
--  '--------------------------------------------------------------------------------------------'
drop table if exists w_deducao_proporcional_salario;
create table w_deducao_proporcional_salario AS
      select w_gerfsal_duplosmaisaposentados.matriculas,
             w_baseirrf_salario_por_cgm.ano,
             w_baseirrf_salario_por_cgm.mes,
             w_baseirrf_salario_por_cgm.instituicao,
             (w_gerfsal_duplosmaisaposentados.valor_irrf / w_baseirrf_salario_por_cgm.base_irf_cgm) * w_gerfsal_duplosmaisaposentados.deducao_aposentado as valor
        from w_baseirrf_salario_por_cgm
  inner join w_gerfsal_duplosmaisaposentados
          on w_gerfsal_duplosmaisaposentados.cgm = w_baseirrf_salario_por_cgm.cgm
         and w_gerfsal_duplosmaisaposentados.mes = w_baseirrf_salario_por_cgm.mes
         and w_gerfsal_duplosmaisaposentados.ano = w_baseirrf_salario_por_cgm.ano;


--  '--------------------------------------------------------------------------------------------'
--   Criando tabela para armazenar os valores da rubrica R999
--   da tabela gerfs13 que serão atualizados antes de fazer update
--  '--------------------------------------------------------------------------------------------'
drop table if exists w_gerfs13_duplosmaisaposentados;
create table w_gerfs13_duplosmaisaposentados AS
     select r07_valor as deducao_aposentado,
            valores_aposentados.*
       from (  select numero_cgm as cgm,
                      matriculas,
                      vinculos,
                      mes,
                      instituicao,
                      ano,
                      (select r35_valor
                         from gerfs13
                        where r35_anousu = aposentados.ano
                          and r35_mesusu = aposentados.mes
                          and r35_regist = aposentados.matriculas
                          and r35_instit = aposentados.instituicao
                          and r35_rubric = 'R999'
                       ) as valor,
                       ( select r35_valor
                          from gerfs13
                         where r35_anousu = aposentados.ano
                           and r35_mesusu = aposentados.mes
                           and r35_regist = aposentados.matriculas
                           and r35_instit = aposentados.instituicao
                           and r35_rubric = 'R982'
                       ) as valor_irrf
                 from w_duplosmaisaposentados as aposentados) as valores_aposentados
  left join pesdiver on r07_anousu = valores_aposentados.ano
                    and r07_mesusu = valores_aposentados.mes
                    and r07_instit = valores_aposentados.instituicao
                    and r07_codigo = 'D902'
   order by cgm, matriculas, vinculos desc, mes asc;

--  '--------------------------------------------------------------------------------------------'
--   Criando tabela temporária para guardar valores brutos de base de IRRF
--   por CGM somente quando a soma for maior que a parcela de dedução
--  '--------------------------------------------------------------------------------------------'
drop table if exists w_baseirrf_13salario_por_cgm;
create table w_baseirrf_13salario_por_cgm AS
      select sum(valor_irrf) as base_irf_cgm,
             cgm,
             mes,
             ano,
             instituicao
        from w_gerfs13_duplosmaisaposentados
   left join pesdiver on r07_anousu = ano
         and r07_mesusu             = mes
         and r07_instit             = instituicao
         and r07_codigo             = 'D902'
    group by cgm, mes, ano, instituicao, r07_valor
      having sum(valor_irrf) > r07_valor;

--  '--------------------------------------------------------------------------------------------'
--   Criando tabela para guardar o valor da parcela de isenção proporcional por matrícula
--  '--------------------------------------------------------------------------------------------'
drop table if exists w_deducao_proporcional_13salario;
create table w_deducao_proporcional_13salario AS
      select w_gerfs13_duplosmaisaposentados.matriculas,
             w_baseirrf_13salario_por_cgm.ano,
             w_baseirrf_13salario_por_cgm.mes,
             w_baseirrf_13salario_por_cgm.instituicao,
             (w_gerfs13_duplosmaisaposentados.valor_irrf / w_baseirrf_13salario_por_cgm.base_irf_cgm) * w_gerfs13_duplosmaisaposentados.deducao_aposentado as valor
        from w_baseirrf_13salario_por_cgm
  inner join w_gerfs13_duplosmaisaposentados
          on w_gerfs13_duplosmaisaposentados.cgm = w_baseirrf_13salario_por_cgm.cgm
         and w_gerfs13_duplosmaisaposentados.mes = w_baseirrf_13salario_por_cgm.mes
         and w_gerfs13_duplosmaisaposentados.ano = w_baseirrf_13salario_por_cgm.ano;

-- select * from w_gerfs13_duplosmaisaposentados where cgm = 260373;
-- select * from w_baseirrf_13salario_por_cgm where cgm = 260373;
-- select * from w_deducao_proporcional_13salario where matriculas in (20302, 50267) order by 1;

--  '--------------------------------------------------------------------------------------------'
--   Guardando dados da tabela gerfsal antes do update
--  '--------------------------------------------------------------------------------------------'
drop table if exists w_gerfsal_valorespensao;
create table w_gerfsal_valorespensao AS
select gerfsal.*
  from gerfsal, w_gerfsal_duplosmaisaposentados as aposentados
 where gerfsal.r14_anousu = aposentados.ano
   and gerfsal.r14_mesusu = aposentados.mes
   and gerfsal.r14_instit = aposentados.instituicao
   and gerfsal.r14_regist = aposentados.matriculas
   and gerfsal.r14_rubric = 'R997';


--  '--------------------------------------------------------------------------------------------'
--   Atualizando a tabela gerfsal com novos valores
--  '--------------------------------------------------------------------------------------------'
   update gerfsal
      set r14_valor = w_deducao_proporcional_salario.valor
     from w_deducao_proporcional_salario
    where gerfsal.r14_anousu = w_deducao_proporcional_salario.ano
      and gerfsal.r14_mesusu = w_deducao_proporcional_salario.mes
      and gerfsal.r14_instit = w_deducao_proporcional_salario.instituicao
      and gerfsal.r14_regist = w_deducao_proporcional_salario.matriculas
      and gerfsal.r14_rubric = 'R997'
returning r14_anousu,
          r14_mesusu,
          r14_instit,
          r14_regist,
          round(r14_valor, 2),
          r14_rubric;

--  '--------------------------------------------------------------------------------------------'
--   Guardando dados da tabela gerfs13 antes do update
--  '--------------------------------------------------------------------------------------------'
drop table if exists w_gerfs13_valorespensao;
create table w_gerfs13_valorespensao AS
select gerfs13.*
  from gerfs13, w_gerfs13_duplosmaisaposentados as aposentados
 where gerfs13.r35_anousu = aposentados.ano
   and gerfs13.r35_mesusu = aposentados.mes
   and gerfs13.r35_instit = aposentados.instituicao
   and gerfs13.r35_regist = aposentados.matriculas
   and gerfs13.r35_rubric = 'R999';

--  '--------------------------------------------------------------------------------------------'
--   Atualizando a tabela gerfs13 com novos valores
--  '--------------------------------------------------------------------------------------------'
   update gerfs13
      set r35_valor = w_deducao_proporcional_13salario.valor
     from w_deducao_proporcional_13salario
    where gerfs13.r35_anousu = w_deducao_proporcional_13salario.ano
      and gerfs13.r35_mesusu = w_deducao_proporcional_13salario.mes
      and gerfs13.r35_instit = w_deducao_proporcional_13salario.instituicao
      and gerfs13.r35_regist = w_deducao_proporcional_13salario.matriculas
      and gerfs13.r35_rubric = 'R999'
returning r35_anousu,
          r35_mesusu,
          r35_instit,
          r35_regist,
          round(r35_valor, 2),
          r35_rubric;
--  '--------------------------------------------------------------------------------------------'
--  '                                     F      I      M                                        '
--  '--------------------------------------------------------------------------------------------'



--  '--------------------------------------------------------------------------------------------'
--  '                                    Acerto DIRF Lotação                                     '
--  '--------------------------------------------------------------------------------------------'
--  '--------------------------------------------------------------------------------------------'
--  '--------------------------------------------------------------------------------------------'
--   Criando variaveis de sessao a ser utilizadas nos acertos
--  '--------------------------------------------------------------------------------------------'
select fc_putsession('ano', '2015');


--  '--------------------------------------------------------------------------------------------'
--   Criando tabela temporaria de salario com as lotacoes divergentes nos eventos financeiros
--  '--------------------------------------------------------------------------------------------'
DROP TABLE IF EXISTS w_eventos_financeiros_salario_lotacao_divergente;
CREATE TABLE w_eventos_financeiros_salario_lotacao_divergente AS
      select *
        from (  select rh02_regist as matricula,
                       rh02_lota::varchar as lotacao,
                       rh02_anousu as ano,
                       rh02_mesusu as mes,
                       rh02_instit as instituicao
                  from rhpessoalmov
                 where rh02_anousu = fc_getsession('ano')::int
              order by matricula, mes) as lotacoes_matriculas
  inner join gerfsal
          on r14_instit = instituicao
         and r14_anousu = ano
         and r14_mesusu = mes
         and r14_regist = matricula
         and r14_lotac <> lotacao
;


--  '--------------------------------------------------------------------------------------------'
--   Criando tabela temporaria de complementar com as lotacoes divergentes nos eventos financeiros
--  '--------------------------------------------------------------------------------------------'
DROP TABLE IF EXISTS w_eventos_financeiros_complementar_lotacao_divergente;
CREATE TABLE w_eventos_financeiros_complementar_lotacao_divergente AS
      select *
        from (  select rh02_regist as matricula,
                       rh02_lota::varchar as lotacao,
                       rh02_anousu as ano,
                       rh02_mesusu as mes,
                       rh02_instit as instituicao
                  from rhpessoalmov
                 where rh02_anousu = fc_getsession('ano')::int
              order by matricula, mes) as lotacoes_matriculas
  inner join gerfcom
          on r48_instit = instituicao
         and r48_anousu = ano
         and r48_mesusu = mes
         and r48_regist = matricula
         and r48_lotac <> lotacao
;


--  '--------------------------------------------------------------------------------------------'
--   Criando tabela temporaria de rescisao com as lotacoes divergentes nos eventos financeiros
--  '--------------------------------------------------------------------------------------------'
DROP TABLE IF EXISTS w_eventos_financeiros_rescisao_lotacao_divergente;
CREATE TABLE w_eventos_financeiros_rescisao_lotacao_divergente AS
      select *
        from (  select rh02_regist as matricula,
                       rh02_lota::varchar as lotacao,
                       rh02_anousu as ano,
                       rh02_mesusu as mes,
                       rh02_instit as instituicao
                  from rhpessoalmov
                 where rh02_anousu = fc_getsession('ano')::int
              order by matricula, mes) as lotacoes_matriculas
  inner join gerfres
          on r20_instit = instituicao
         and r20_anousu = ano
         and r20_mesusu = mes
         and r20_regist = matricula
         and r20_lotac <> lotacao
;


--  '--------------------------------------------------------------------------------------------'
--   Criando tabela temporaria de 13 salario com as lotacoes divergentes nos eventos financeiros
--  '--------------------------------------------------------------------------------------------'
DROP TABLE IF EXISTS w_eventos_financeiros_13_lotacao_divergente;
CREATE TABLE w_eventos_financeiros_13_lotacao_divergente AS
      select *
        from (  select rh02_regist as matricula,
                       rh02_lota::varchar as lotacao,
                       rh02_anousu as ano,
                       rh02_mesusu as mes,
                       rh02_instit as instituicao
                  from rhpessoalmov
                 where rh02_anousu = fc_getsession('ano')::int
              order by matricula, mes) as lotacoes_matriculas
  inner join gerfs13
          on r35_instit = instituicao
         and r35_anousu = ano
         and r35_mesusu = mes
         and r35_regist = matricula
         and r35_lotac <> lotacao
;



--  '--------------------------------------------------------------------------------------------'
--   Atualizando na tabela de salario lotacoes dos eventos financeiros
--  '--------------------------------------------------------------------------------------------'
      -- select gerfsal.*
      update gerfsal
         set r14_lotac = w_eventos_financeiros_salario_lotacao_divergente.lotacao
        from w_eventos_financeiros_salario_lotacao_divergente
       where gerfsal.r14_instit = w_eventos_financeiros_salario_lotacao_divergente.r14_instit
         and gerfsal.r14_anousu = w_eventos_financeiros_salario_lotacao_divergente.r14_anousu
         and gerfsal.r14_mesusu = w_eventos_financeiros_salario_lotacao_divergente.r14_mesusu
         and gerfsal.r14_regist = w_eventos_financeiros_salario_lotacao_divergente.r14_regist
         and gerfsal.r14_rubric = w_eventos_financeiros_salario_lotacao_divergente.r14_rubric
   returning gerfsal.r14_regist,
             gerfsal.r14_anousu||'/'||gerfsal.r14_mesusu as competencias,
             gerfsal.r14_rubric,
             gerfsal.r14_lotac,
             gerfsal.r14_instit,
             w_eventos_financeiros_salario_lotacao_divergente.r14_lotac
;


--  '--------------------------------------------------------------------------------------------'
--   Atualizando na tabela complementar lotacoes dos eventos financeiros
--  '--------------------------------------------------------------------------------------------'
      -- select gerfcom.*
      update gerfcom
         set r48_lotac = w_eventos_financeiros_complementar_lotacao_divergente.lotacao
        from w_eventos_financeiros_complementar_lotacao_divergente
       where gerfcom.r48_instit = w_eventos_financeiros_complementar_lotacao_divergente.r48_instit
         and gerfcom.r48_anousu = w_eventos_financeiros_complementar_lotacao_divergente.r48_anousu
         and gerfcom.r48_mesusu = w_eventos_financeiros_complementar_lotacao_divergente.r48_mesusu
         and gerfcom.r48_regist = w_eventos_financeiros_complementar_lotacao_divergente.r48_regist
         and gerfcom.r48_rubric = w_eventos_financeiros_complementar_lotacao_divergente.r48_rubric
   returning gerfcom.r48_regist,
             gerfcom.r48_anousu||'/'||gerfcom.r48_mesusu as competencias,
             gerfcom.r48_rubric,
             gerfcom.r48_lotac,
             gerfcom.r48_instit,
             w_eventos_financeiros_complementar_lotacao_divergente.r48_lotac
;


--  '--------------------------------------------------------------------------------------------'
--   Atualizando na tabela de rescisao lotacoes dos eventos financeiros
--  '--------------------------------------------------------------------------------------------'
      -- select gerfres.*
      update gerfres
         set r20_lotac = w_eventos_financeiros_rescisao_lotacao_divergente.lotacao
        from w_eventos_financeiros_rescisao_lotacao_divergente
       where gerfres.r20_instit = w_eventos_financeiros_rescisao_lotacao_divergente.r20_instit
         and gerfres.r20_anousu = w_eventos_financeiros_rescisao_lotacao_divergente.r20_anousu
         and gerfres.r20_mesusu = w_eventos_financeiros_rescisao_lotacao_divergente.r20_mesusu
         and gerfres.r20_regist = w_eventos_financeiros_rescisao_lotacao_divergente.r20_regist
         and gerfres.r20_rubric = w_eventos_financeiros_rescisao_lotacao_divergente.r20_rubric
   returning gerfres.r20_regist,
             gerfres.r20_anousu||'/'||gerfres.r20_mesusu as competencias,
             gerfres.r20_rubric,
             gerfres.r20_lotac,
             gerfres.r20_instit,
             w_eventos_financeiros_rescisao_lotacao_divergente.r20_lotac
;


--  '--------------------------------------------------------------------------------------------'
--   Atualizando na tabela de 13 salario lotacoes dos eventos financeiros
--  '--------------------------------------------------------------------------------------------'
      -- select gerfs13.*
      update gerfs13
         set r35_lotac = w_eventos_financeiros_13_lotacao_divergente.lotacao
        from w_eventos_financeiros_13_lotacao_divergente
       where gerfs13.r35_instit = w_eventos_financeiros_13_lotacao_divergente.r35_instit
         and gerfs13.r35_anousu = w_eventos_financeiros_13_lotacao_divergente.r35_anousu
         and gerfs13.r35_mesusu = w_eventos_financeiros_13_lotacao_divergente.r35_mesusu
         and gerfs13.r35_regist = w_eventos_financeiros_13_lotacao_divergente.r35_regist
         and gerfs13.r35_rubric = w_eventos_financeiros_13_lotacao_divergente.r35_rubric
   returning gerfs13.r35_regist,
             gerfs13.r35_anousu||'/'||gerfs13.r35_mesusu as competencias,
             gerfs13.r35_rubric,
             gerfs13.r35_lotac,
             gerfs13.r35_instit,
             w_eventos_financeiros_13_lotacao_divergente.r35_lotac
;



--  '--------------------------------------------------------------------------------------------'
--   Excluindo variaveis de sessao utilizadas nos acertos
--  '--------------------------------------------------------------------------------------------'
select fc_delsession('ano');