<?php

use Classes\PostgresMigration;

class M15242CalculoIptuAlegrete extends PostgresMigration
{
    public function up()
    {
       $this->execute(
         <<<SQL
drop function if exists fc_iptu_calculavvc_ale_2009(integer,integer,boolean,boolean);

create or replace function fc_iptu_calculavvc_ale_2009(iMatricula integer,
                                                       iAnousu integer,
                                                       bMostrademo boolean,
                                                       lRaise boolean,
                                                       OUT rnVvc          numeric(15,2),
                                                       OUT rnTotarea      numeric,
                                                       OUT riNumconstr    integer,
                                                       OUT riMesesPredial integer,
                                                       OUT rtDemo         text,
                                                       OUT rtMsgerro      text,
                                                       OUT rbErro         boolean,
                                                       OUT riCodErro      integer,
                                                       OUT rnValorIsencao numeric,
                                                       OUT rtErro         text)
returns record as
$$
declare

  bAtualiza              boolean default true;

  iTotalConstrucoes integer default 0;
  iMesesPredial     integer default 0;

  iConstrucao       integer;
  rConstrucao       record;

  nValorConstrucao        numeric;
  nValorVenalPredial      numeric;
  nValorVenalPredialTotal numeric default 0;
  nAreaTotalEdificada     numeric default 0;
  aTotalMeses             integer[];

  nValorIsencao           numeric default 0;

begin

  perform fc_debug('INICIANDO CALCULO VVC ...', lRaise, false, false);

  rnVvc          := 0;
  rnTotarea      := 0;
  riNumconstr    := 0;
  riMesesPredial := 0;
  rnValorIsencao := 0;
  rtDemo         := '';
  rtMsgerro      := 'Retorno ok' ;
  rbErro         := 'f';
  riCodErro      := 0;
  rtErro         := '';

  perform *
     from db_plugin
    where db145_nome = 'calculo-de-iptu-proporcional'
      and db145_situacao is true;

  -- Caso o plugin de cálculo de IPTU proporcional esteja instalado e ativo
  -- efetua o cálculo de forma proporcional de acordo com as mudanças nas construções
  if found then

    perform fc_debug(' <calculo_vvc> - Plugin de Cálculo de IPTU proporcional instalado', lRaise, false, false);
    perform fc_debug(' <calculo_vvc> - Calculando o valor proporcional mes a mes', lRaise, false, false);

    for rConstrucao in

      select array_accum(mes) as meses,
             j39_idcons,
             j39_area,
             sum(coalesce((select area
                         from plugins.iptuconstrareahistorico
                        where matricula = j39_matric
                          and id_constr = j39_idcons
                          and data >= (iAnousu||'-'||mes||'-01')::date
                        order by data, sequencial desc
                        limit 1), j39_area)::numeric / 12) as area,
             sum(
                coalesce(
                  (select
                    (
                      (
                        (coalesce(
                          (select area
                             from plugins.iptuconstrareahistorico
                            where matricula = j39_matric
                              and id_constr = j39_idcons
                              and data >= (iAnousu||'-'||mes||'-01')::date
                            order by data, sequencial desc
                            limit 1
                          ), j39_area)
                        ) / 100
                      ) * (coalesce((select aliquota from fc_iptu_verifica_isencao_competencia(j39_matric, iAnousu, mes, lRaise)), 0))
                    )
                  ), 0)
              )::numeric / 12 as area_isencao
        from iptuconstr, generate_series(1,12) as mes
       where iptuconstr.j39_matric = iMatricula
         and j39_dtlan < (iAnousu||'-'||mes||'-01')::date
         and (j39_dtdemo is null or j39_dtdemo >= (iAnousu||'-'||mes||'-01')::date)
       group by j39_idcons,
                j39_area
    loop

      select fc_iptu_calculavvc_valor_m2_ale_2009(iMatricula, rConstrucao.j39_idcons, iAnousu, lRaise)
        into nValorConstrucao;

      nValorVenalPredial      := nValorConstrucao * rConstrucao.area;
      nValorVenalPredialTotal := nValorVenalPredialTotal + nValorVenalPredial;

      nValorIsencao := nValorIsencao + (nValorVenalPredial - (nValorConstrucao * rConstrucao.area_isencao));

      perform fc_debug(' <calculo_vvc> - IDConstr: '||rConstrucao.j39_idcons, lRaise, false, false);
      perform fc_debug(' <calculo_vvc> - Área da Construção: '||rConstrucao.area, lRaise, false, false);
      perform fc_debug(' <calculo_vvc> - Área com Isenção: '||rConstrucao.area_isencao, lRaise, false, false);
      perform fc_debug(' <calculo_vvc> - Valor da Construção: '||nValorConstrucao, lRaise, false, false);
      perform fc_debug(' <calculo_vvc> - Valor Venal: '||nValorVenalPredial, lRaise, false, false);

      if nValorConstrucao = 0 then
        rnVvc          := 0;
        rnTotarea      := 0;
        riMesesPredial := 0;
        rtDemo         := 'Valor do m2 da construcao zerado';
        rbErro         := 't';
        continue;
      end if;

      insert into tmpiptucale( anousu,
                               matric,
                               idcons,
                               areaed,
                               vm2,
                               pontos,
                               valor )
                       values ( iAnousu,
                                iMatricula,
                                rConstrucao.j39_idcons,
                                rConstrucao.j39_area,
                                nValorConstrucao,
                                0,
                                nValorVenalPredial );

      if bAtualiza then
         update tmpdadosiptu
            set predial = true;
         bAtualiza = false;
      end if;

      nAreaTotalEdificada := nAreaTotalEdificada + rConstrucao.j39_area;
      iTotalConstrucoes   := iTotalConstrucoes + 1;

      aTotalMeses := array_cat(aTotalMeses, rConstrucao.meses);
    end loop;

    select count(*)
      into iMesesPredial
      from (select distinct unnest(aTotalMeses)) as x;

    perform fc_debug(' <calculo_vvc> - Meses para cálculo predial: '||iMesesPredial, lRaise, false, false);

  else

    for rConstrucao in
      select distinct on (iptuconstr.j39_matric, j39_idcons)
                     iptuconstr.j39_matric,
                     j39_idcons,
                     j39_ano,
                     j39_area::numeric
        from iptuconstr
       where iptuconstr.j39_dtdemo is null
         and iptuconstr.j39_matric = iMatricula
    loop

      select fc_iptu_calculavvc_valor_m2_ale_2009(rConstrucao.j39_matric, rConstrucao.j39_idcons, iAnousu, lRaise)
        into nValorConstrucao;

      nValorVenalPredial      := nValorConstrucao * rConstrucao.j39_area;
      nValorVenalPredialTotal := nValorVenalPredialTotal + nValorVenalPredial;

      perform fc_debug(' <calculo_vvc> - IDConstr: '||rConstrucao.j39_idcons, lRaise, false, false);
      perform fc_debug(' <calculo_vvc> - Área da Construção: '||rConstrucao.j39_area, lRaise, false, false);
      perform fc_debug(' <calculo_vvc> - Valor da Construção: '||nValorConstrucao, lRaise, false, false);
      perform fc_debug(' <calculo_vvc> - Valor Venal: '||nValorVenalPredial, lRaise, false, false);

      if nValorConstrucao = 0 then
        rnVvc          := 0;
        rnTotarea      := 0;
        riMesesPredial := 0;
        rtDemo         := 'Valor do m2 da construcao zerado';
        rbErro         := 't';
        continue;
      end if;

      insert into tmpiptucale( anousu,
                               matric,
                               idcons,
                               areaed,
                               vm2,
                               pontos,
                               valor )
                       values ( iAnousu,
                                iMatricula,
                                rConstrucao.j39_idcons,
                                rConstrucao.j39_area,
                                nValorConstrucao,
                                0,
                                nValorVenalPredial );

      if bAtualiza then
         update tmpdadosiptu
            set predial = true;
         bAtualiza = false;
      end if;

      nAreaTotalEdificada := nAreaTotalEdificada + rConstrucao.j39_area;
      iTotalConstrucoes   := iTotalConstrucoes + 1;

    end loop;

  end if;

  perform fc_debug('Valor Venal Predial Total: '||nValorVenalPredialTotal, lRaise, false, false);
  perform fc_debug('Área Edificada Total: '||nAreaTotalEdificada, lRaise, false, false);
  perform fc_debug('Valor da Isenção: '||nValorIsencao, lRaise, false, false);


  rnVvc          := nValorVenalPredialTotal;
  rnTotarea      := nAreaTotalEdificada;
  riNumconstr    := iTotalConstrucoes;
  riMesesPredial := iMesesPredial;
  rnValorIsencao := nValorIsencao;
  rtDemo         := '';

  update tmpdadosiptu set vvc = rnVvc;
  return;

end;
$$  language 'plpgsql';
SQL
       );
    }

    public function down()
    {
        $this->execute(
          <<<SQL
drop function if exists fc_iptu_calculavvc_ale_2009(integer,integer,boolean,boolean);

create or replace function fc_iptu_calculavvc_ale_2009(iMatricula integer,
                                                       iAnousu integer,
                                                       bMostrademo boolean,
                                                       lRaise boolean,
                                                       OUT rnVvc          numeric(15,2),
                                                       OUT rnTotarea      numeric,
                                                       OUT riNumconstr    integer,
                                                       OUT riMesesPredial integer,
                                                       OUT rtDemo         text,
                                                       OUT rtMsgerro      text,
                                                       OUT rbErro         boolean,
                                                       OUT riCodErro      integer,
                                                       OUT rnValorIsencao numeric,
                                                       OUT rtErro         text)
returns record as
$$
declare

  bAtualiza              boolean default true;

  iTotalConstrucoes integer default 0;
  iMesesPredial     integer default 0;

  iConstrucao       integer;
  rConstrucao       record;

  nValorConstrucao        numeric;
  nValorVenalPredial      numeric;
  nValorVenalPredialTotal numeric default 0;
  nAreaTotalEdificada     numeric default 0;
  aTotalMeses             integer[];

  nValorIsencao           numeric default 0;

begin

  perform fc_debug('INICIANDO CALCULO VVC ...', lRaise, false, false);

  rnVvc          := 0;
  rnTotarea      := 0;
  riNumconstr    := 0;
  riMesesPredial := 0;
  rnValorIsencao := 0;
  rtDemo         := '';
  rtMsgerro      := 'Retorno ok' ;
  rbErro         := 'f';
  riCodErro      := 0;
  rtErro         := '';

  perform *
     from db_plugin
    where db145_nome = 'calculo-de-iptu-proporcional'
      and db145_situacao is true;

  -- Caso o plugin de cálculo de IPTU proporcional esteja instalado e ativo
  -- efetua o cálculo de forma proporcional de acordo com as mudanças nas construções
  if found then

    perform fc_debug(' <calculo_vvc> - Plugin de Cálculo de IPTU proporcional instalado', lRaise, false, false);
    perform fc_debug(' <calculo_vvc> - Calculando o valor proporcional mes a mes', lRaise, false, false);

    for rConstrucao in

      select array_accum(mes) as meses,
             j39_idcons,
             j39_area,
             sum(coalesce((select area
                         from plugins.iptuconstrareahistorico
                        where matricula = j39_matric
                          and id_constr = j39_idcons
                          and data >= (iAnousu||'-'||mes||'-01')::date
                        order by data
                        limit 1), j39_area)::numeric / 12) as area,
             sum(
                coalesce(
                  (select
                    (
                      (
                        (coalesce(
                          (select area
                             from plugins.iptuconstrareahistorico
                            where matricula = j39_matric
                              and id_constr = j39_idcons
                              and data >= (iAnousu||'-'||mes||'-01')::date
                            order by data
                            limit 1
                          ), j39_area)
                        ) / 100
                      ) * (coalesce((select aliquota from fc_iptu_verifica_isencao_competencia(j39_matric, iAnousu, mes, lRaise)), 0))
                    )
                  ), 0)
              )::numeric / 12 as area_isencao
        from iptuconstr, generate_series(1,12) as mes
       where iptuconstr.j39_matric = iMatricula
         and j39_dtlan < (iAnousu||'-'||mes||'-01')::date
         and (j39_dtdemo is null or j39_dtdemo >= (iAnousu||'-'||mes||'-01')::date)
       group by j39_idcons,
                j39_area
    loop

      select fc_iptu_calculavvc_valor_m2_ale_2009(iMatricula, rConstrucao.j39_idcons, iAnousu, lRaise)
        into nValorConstrucao;

      nValorVenalPredial      := nValorConstrucao * rConstrucao.area;
      nValorVenalPredialTotal := nValorVenalPredialTotal + nValorVenalPredial;

      nValorIsencao := nValorIsencao + (nValorVenalPredial - (nValorConstrucao * rConstrucao.area_isencao));

      perform fc_debug(' <calculo_vvc> - IDConstr: '||rConstrucao.j39_idcons, lRaise, false, false);
      perform fc_debug(' <calculo_vvc> - Área da Construção: '||rConstrucao.area, lRaise, false, false);
      perform fc_debug(' <calculo_vvc> - Área com Isenção: '||rConstrucao.area_isencao, lRaise, false, false);
      perform fc_debug(' <calculo_vvc> - Valor da Construção: '||nValorConstrucao, lRaise, false, false);
      perform fc_debug(' <calculo_vvc> - Valor Venal: '||nValorVenalPredial, lRaise, false, false);

      if nValorConstrucao = 0 then
        rnVvc          := 0;
        rnTotarea      := 0;
        riMesesPredial := 0;
        rtDemo         := 'Valor do m2 da construcao zerado';
        rbErro         := 't';
        continue;
      end if;

      insert into tmpiptucale( anousu,
                               matric,
                               idcons,
                               areaed,
                               vm2,
                               pontos,
                               valor )
                       values ( iAnousu,
                                iMatricula,
                                rConstrucao.j39_idcons,
                                rConstrucao.j39_area,
                                nValorConstrucao,
                                0,
                                nValorVenalPredial );

      if bAtualiza then
         update tmpdadosiptu
            set predial = true;
         bAtualiza = false;
      end if;

      nAreaTotalEdificada := nAreaTotalEdificada + rConstrucao.j39_area;
      iTotalConstrucoes   := iTotalConstrucoes + 1;

      aTotalMeses := array_cat(aTotalMeses, rConstrucao.meses);
    end loop;

    select count(*)
      into iMesesPredial
      from (select distinct unnest(aTotalMeses)) as x;

    perform fc_debug(' <calculo_vvc> - Meses para cálculo predial: '||iMesesPredial, lRaise, false, false);

  else

    for rConstrucao in
      select distinct on (iptuconstr.j39_matric, j39_idcons)
                     iptuconstr.j39_matric,
                     j39_idcons,
                     j39_ano,
                     j39_area::numeric
        from iptuconstr
       where iptuconstr.j39_dtdemo is null
         and iptuconstr.j39_matric = iMatricula
    loop

      select fc_iptu_calculavvc_valor_m2_ale_2009(rConstrucao.j39_matric, rConstrucao.j39_idcons, iAnousu, lRaise)
        into nValorConstrucao;

      nValorVenalPredial      := nValorConstrucao * rConstrucao.j39_area;
      nValorVenalPredialTotal := nValorVenalPredialTotal + nValorVenalPredial;

      perform fc_debug(' <calculo_vvc> - IDConstr: '||rConstrucao.j39_idcons, lRaise, false, false);
      perform fc_debug(' <calculo_vvc> - Área da Construção: '||rConstrucao.j39_area, lRaise, false, false);
      perform fc_debug(' <calculo_vvc> - Valor da Construção: '||nValorConstrucao, lRaise, false, false);
      perform fc_debug(' <calculo_vvc> - Valor Venal: '||nValorVenalPredial, lRaise, false, false);

      if nValorConstrucao = 0 then
        rnVvc          := 0;
        rnTotarea      := 0;
        riMesesPredial := 0;
        rtDemo         := 'Valor do m2 da construcao zerado';
        rbErro         := 't';
        continue;
      end if;

      insert into tmpiptucale( anousu,
                               matric,
                               idcons,
                               areaed,
                               vm2,
                               pontos,
                               valor )
                       values ( iAnousu,
                                iMatricula,
                                rConstrucao.j39_idcons,
                                rConstrucao.j39_area,
                                nValorConstrucao,
                                0,
                                nValorVenalPredial );

      if bAtualiza then
         update tmpdadosiptu
            set predial = true;
         bAtualiza = false;
      end if;

      nAreaTotalEdificada := nAreaTotalEdificada + rConstrucao.j39_area;
      iTotalConstrucoes   := iTotalConstrucoes + 1;

    end loop;

  end if;

  perform fc_debug('Valor Venal Predial Total: '||nValorVenalPredialTotal, lRaise, false, false);
  perform fc_debug('Área Edificada Total: '||nAreaTotalEdificada, lRaise, false, false);
  perform fc_debug('Valor da Isenção: '||nValorIsencao, lRaise, false, false);


  rnVvc          := nValorVenalPredialTotal;
  rnTotarea      := nAreaTotalEdificada;
  riNumconstr    := iTotalConstrucoes;
  riMesesPredial := iMesesPredial;
  rnValorIsencao := nValorIsencao;
  rtDemo         := '';

  update tmpdadosiptu set vvc = rnVvc;
  return;

end;
$$  language 'plpgsql';
SQL
        );
    }
}
