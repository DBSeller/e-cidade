<?php

use Classes\PostgresMigration;

class M15242CalculoIptuAlegreteTaxaLimpeza extends PostgresMigration
{
    public function up()
    {
        $this->execute(
          <<<SQL
drop function if exists fc_iptu_taxalimpeza_ale_2009(integer,numeric,integer,numeric,numeric,boolean);
create or replace function fc_iptu_taxalimpeza_ale_2009(integer,numeric,integer,numeric,numeric,boolean) returns boolean as
$$
declare

    iReceita  alias for $1;
    iAliquota alias for $2;
    iHistCalc alias for $3;
    iPercIsen alias for $4;
    nValpar   alias for $5;
    lRaise    alias for $6;

    nValTaxa   numeric default 0;
    nValorBase numeric default 0;
    nFator     numeric default 0;

    iCaract        integer default 0;
    iIdbql         integer default 0;
    iNparc         integer default 0;
    iAnousu        integer default 0;
    iZona          integer default 0;
    nLimpeza       numeric default 0;
    nTestada       numeric default 0;
    iCartaxa       integer default 0;
    nAreaTotConstr numeric default 0;

    bPredial boolean default false;
    tSql     text    default '';

    r_Carconstr record;
    nj72_valor  numeric;

    aTotalMeses     integer[];
    iMesesPredial   integer default 0;
    nTaxaTemporaria numeric;

    nLimpezaIsen        numeric default 0;
    nTaxaTeritorialIsen numeric default 0;

begin

  perform fc_debug('CALCULANDO TAXA DE COLETA DE LIXO ...',lRaise,false,false);
  perform fc_debug('receita - '||iReceita||' aliq - '||iAliquota||' historico - '||iHistCalc,lRaise,false,false);

  iPercIsen := coalesce(iPercIsen, 0);

  select predial
    into bPredial
    from tmpdadosiptu;

  select anousu,
         idbql
    into iAnousu,
         iIdbql
    from tmpdadostaxa;

  if not found then
    return false;
  end if;

  select j34_zona into iZona from lote where j34_idbql = iIdbql;

  perform *
     from db_plugin
    where db145_nome = 'calculo-de-iptu-proporcional'
      and db145_situacao is true;

  -- Caso o plugin de cálculo de IPTU proporcional esteja instalado e ativo
  -- efetua o cálculo de forma proporcional de acordo com as mudanças nas construções
  if found then

    perform fc_debug(' <calcula_taxa_limpeza> ---------------- I N I C I O ----------------', lRaise);
    perform fc_debug(' <calcula_taxa_limpeza> Plugin de calculo de IPTU proporcional ativo',  lRaise);

    for r_Carconstr in
      select j48_caract,
             area,
             j39_idcons,
             area_isencao,
             meses
        from (
            select array_accum(mes) as meses,
                   j39_idcons,
                   sum(coalesce((select area
                                   from plugins.iptuconstrareahistorico
                                  where matricula = j39_matric
                                    and id_constr = j39_idcons
                                    and data >= (iAnousu||'-'||mes||'-01')::date
                                  order by data, sequencial desc
                                  limit 1), j39_area) / 12) as area,
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
                          ) * (coalesce((select aliquota from fc_iptu_verifica_isencao_taxa_competencia(j39_matric, iAnousu, mes, iReceita, lRaise)), 0))
                        )
                      ), 0)
                  )::numeric / 12 as area_isencao,
                   j39_area as areafinal
              from iptuconstr, generate_series(1,12) as mes
             where iptuconstr.j39_matric in (select matric from tmpdadostaxa limit 1)
               and j39_dtlan < (iAnousu||'-'||mes||'-01')::date
               and (j39_dtdemo is null or j39_dtdemo >= (iAnousu||'-'||mes||'-01')::date)
             group by j39_idcons,
                      j39_area
             ) as x
             inner join carconstr on j48_matric in (select matric from tmpdadostaxa limit 1) and j48_idcons = j39_idcons
             inner join caracter on j48_caract = j31_codigo
       where j31_grupo = 4
    loop

      iCarTaxa = r_Carconstr.j48_caract;
      nAreaTotConstr = r_Carconstr.area;

      perform fc_debug(' <calcula_taxa_limpeza> j39_idcons ................: ' || r_Carconstr.j39_idcons,   lRaise);
      perform fc_debug(' <calcula_taxa_limpeza> area ......................: ' || r_Carconstr.area,         lRaise);
      perform fc_debug(' <calcula_taxa_limpeza> area_isencao ..............: ' || r_Carconstr.area_isencao, lRaise);
      perform fc_debug(' <calcula_taxa_limpeza> Caracteristica ............: ' || iCarTaxa,                 lRaise);

      if iCarTaxa is not null then

        select j72_valor
          into nj72_valor
          from carzonavalor
         where j72_anousu = iAnousu
           and j72_caract = iCartaxa
           and j72_zona = iZona
           and j72_tipo = 'P';

        if nj72_valor is null then
          nLimpeza := 0;
          nLimpezaIsen := 0;
        else
          nLimpeza := nLimpeza + (nj72_valor * nAreaTotConstr);
          nLimpezaIsen := nLimpezaIsen + ((nj72_valor * nAreaTotConstr) - (nj72_valor * r_Carconstr.area_isencao));
        end if;

        perform fc_debug(' <calcula_taxa_limpeza> Valor da Caracteristica ...: ' || nj72_valor, lRaise);
        perform fc_debug(' <calcula_taxa_limpeza> Taxa de Limpeza ...........: ' || nLimpeza,   lRaise);

      end if;

      aTotalMeses := array_cat(aTotalMeses, r_Carconstr.meses);

      perform fc_debug(' <calcula_taxa_limpeza> ', lRaise);

    end loop;

    select count(*)
      into iMesesPredial
      from (select distinct unnest(aTotalMeses)) as x;

    perform fc_debug(' <calcula_taxa_limpeza> Meses para calculo predial ....: ' || iMesesPredial, lRaise);

    if bPredial is false or (iMesesPredial <> 0 and iMesesPredial <> 12) then

      select j72_valor
        into nj72_valor
        from carzonavalor
       where j72_anousu = iAnousu
         and j72_caract = 124
         and j72_zona = iZona
         and j72_tipo = 'T';

      select case when j36_testle = 0 then j36_testad else j36_testle end as j36_testle
        into nTestada
        from iptuconstr
             inner join testada on j36_face = j39_codigo and j36_idbql = iidbql
             inner join face on j37_face = j36_face
             inner join facevalor on j81_face = j37_face and j81_anousu = ianousu
             inner join iptubase on j01_matric = j39_matric
       where j39_matric in (select matric from tmpdadostaxa limit 1)
         and j39_dtdemo is null
         and j01_baixa is null
       limit 1;

      if nTestada is null then

        select case when j36_testle = 0 then j36_testad else j36_testle end as j36_testle
          into nTestada
          from testpri
               inner join face on j49_face = j37_face
               inner join facevalor on j81_face = j37_face and j81_anousu = ianousu
               inner join testada on j49_face = j36_face and j49_idbql = j36_idbql
         where j49_idbql = iIdbql;

      end if;

      nTaxaTemporaria := nj72_valor * nTestada;

      perform fc_debug(' <calcula_taxa_limpeza> Valor da Caracteristica Territorial ....: ' || nj72_valor,      lRaise);
      perform fc_debug(' <calcula_taxa_limpeza> Area da Testada ........................: ' || nTestada,        lRaise);
      perform fc_debug(' <calcula_taxa_limpeza> Taxa Territorial .......................: ' || nTaxaTemporaria, lRaise);

      nTaxaTeritorialIsen := (nTaxaTemporaria / 100) * (100 - iPercIsen);

      if iMesesPredial <> 0 then
        nLimpeza := nLimpeza + ((nTaxaTemporaria / 12) * (12 - iMesesPredial));
        nLimpezaIsen := nLimpezaIsen + ((nTaxaTeritorialIsen / 12) * (12 - iMesesPredial));
      else
        nLimpeza := nTaxaTemporaria;
        nLimpezaIsen := nTaxaTeritorialIsen;
      end if;

    end if;

    nLimpezaIsen := nLimpeza - nLimpezaIsen;

    iPercIsen := (100 * nLimpezaIsen) / nLimpeza;

    perform fc_debug(' <calcula_taxa_limpeza> Meses para calculo predial ....: ' || iMesesPredial, lRaise);
    perform fc_debug(' <calcula_taxa_limpeza> iPercIsen .....................: ' || iPercIsen,     lRaise);
    perform fc_debug(' <calcula_taxa_limpeza> nLimpeza ......................: ' || nLimpeza,      lRaise);

  else

    if bPredial is false then

      select j72_valor
        into nj72_valor
        from carzonavalor
       where j72_anousu = iAnousu
         and j72_caract = 124
         and j72_zona = iZona
         and j72_tipo = 'T';

      select case when j36_testle = 0 then j36_testad else j36_testle end as j36_testle
        into nTestada
        from iptuconstr
             inner join testada on j36_face = j39_codigo and j36_idbql = iidbql
             inner join face on j37_face = j36_face
             inner join facevalor on j81_face = j37_face and j81_anousu = ianousu
             inner join iptubase on j01_matric = j39_matric
       where j39_matric in (select matric from tmpdadostaxa limit 1)
         and j39_dtdemo is null and j01_baixa is null
       limit 1;

      if nTestada is null then

        select case when j36_testle = 0 then j36_testad else j36_testle end as j36_testle
          into nTestada
          from testpri
               inner join face on j49_face = j37_face
               inner join facevalor on j81_face = j37_face and j81_anousu = ianousu
               inner join testada on j49_face = j36_face and j49_idbql = j36_idbql
         where j49_idbql = iIdbql;

      end if;

      nLimpeza := nj72_valor * nTestada;

      perform fc_debug(' <calcula_taxa_limpeza> Valor da Caracteristica Territorial ....: ' || nj72_valor, lRaise);
      perform fc_debug(' <calcula_taxa_limpeza> Área da Testada ........................: ' || nTestada,   lRaise);
      perform fc_debug(' <calcula_taxa_limpeza> Taxa Territorial .......................: ' || nLimpeza,   lRaise);

    else

      for r_Carconstr in
        select j48_caract, j39_area, j39_idcons
          from iptuconstr
               inner join carconstr on j48_matric = j39_matric and j48_idcons = j39_idcons
               inner join caracter on j48_caract = j31_codigo
         where j39_dtdemo is null
           and j48_matric in (select matric from tmpdadostaxa limit 1)
           and j31_grupo = 4
      loop

        iCarTaxa = r_Carconstr.j48_caract;
        nAreaTotConstr = r_Carconstr.j39_area;

        perform fc_debug(' <calcula_taxa_limpeza> Contrucao ........: ' || r_Carconstr.j39_idcons, lRaise);
        perform fc_debug(' <calcula_taxa_limpeza> Caracteristica ...: ' || iCarTaxa,               lRaise);
        perform fc_debug(' <calcula_taxa_limpeza> Area Total .......: ' || nAreaTotConstr,         lRaise);

        if iCarTaxa is not null then

          select j72_valor
            into nj72_valor
            from carzonavalor
           where j72_anousu = iAnousu
             and j72_caract = iCartaxa
             and j72_zona = iZona
             and j72_tipo = 'P';

          if nj72_valor is null then
            nLimpeza = 0;
          else
            nLimpeza = nLimpeza + ( nj72_valor * nAreaTotConstr );
          end if;

          perform fc_debug(' <calcula_taxa_limpeza> Valor da Característica ....: ' || nj72_valor, lRaise);
          perform fc_debug(' <calcula_taxa_limpeza> Taxa de Limpeza ............: ' || nLimpeza,   lRaise);

        end if;

      end loop;

    end if;
  end if;

  nLimpeza := coalesce(nLimpeza, 0);

  perform fc_debug(' <calcula_taxa_limpeza> Taxa de Limpeza Calculada .....: ' || nLimpeza, lRaise);

  insert into tmptaxapercisen values (iReceita, iPercIsen, 0, nLimpeza);

  if iPercIsen > 0 then

    perform fc_debug(' <calcula_taxa_limpeza> Percentual de Isencao ........: ' || iPercIsen, lRaise);
    nLimpeza := nLimpeza * (100 - iPercIsen) / 100;
    perform fc_debug(' <calcula_taxa_limpeza> Taxa de Limpeza Calculada ....: ' || nLimpeza,  lRaise);
  end if;

  perform fc_debug(' <calcula_taxa_limpeza> ------------------- F I M -------------------', lRaise);

  tSql := 'insert into tmprecval values ('||iReceita||','||nLimpeza||','||iHistCalc||',true)';
  execute tSql;

  return true;

end;
$$ language 'plpgsql';
SQL
        );
    }

    public function down()
    {
        $this->execute(
          <<<SQL
drop function if exists fc_iptu_taxalimpeza_ale_2009(integer,numeric,integer,numeric,numeric,boolean);
create or replace function fc_iptu_taxalimpeza_ale_2009(integer,numeric,integer,numeric,numeric,boolean) returns boolean as
$$
declare

    iReceita  alias for $1;
    iAliquota alias for $2;
    iHistCalc alias for $3;
    iPercIsen alias for $4;
    nValpar   alias for $5;
    lRaise    alias for $6;

    nValTaxa   numeric default 0;
    nValorBase numeric default 0;
    nFator     numeric default 0;

    iCaract        integer default 0;
    iIdbql         integer default 0;
    iNparc         integer default 0;
    iAnousu        integer default 0;
    iZona          integer default 0;
    nLimpeza       numeric default 0;
    nTestada       numeric default 0;
    iCartaxa       integer default 0;
    nAreaTotConstr numeric default 0;

    bPredial boolean default false;
    tSql     text    default '';

    r_Carconstr record;
    nj72_valor  numeric;

    aTotalMeses     integer[];
    iMesesPredial   integer default 0;
    nTaxaTemporaria numeric;

    nLimpezaIsen        numeric default 0;
    nTaxaTeritorialIsen numeric default 0;

begin

  perform fc_debug('CALCULANDO TAXA DE COLETA DE LIXO ...',lRaise,false,false);
  perform fc_debug('receita - '||iReceita||' aliq - '||iAliquota||' historico - '||iHistCalc,lRaise,false,false);

  iPercIsen := coalesce(iPercIsen, 0);

  select predial
    into bPredial
    from tmpdadosiptu;

  select anousu,
         idbql
    into iAnousu,
         iIdbql
    from tmpdadostaxa;

  if not found then
    return false;
  end if;

  select j34_zona into iZona from lote where j34_idbql = iIdbql;

  perform *
     from db_plugin
    where db145_nome = 'calculo-de-iptu-proporcional'
      and db145_situacao is true;

  -- Caso o plugin de cálculo de IPTU proporcional esteja instalado e ativo
  -- efetua o cálculo de forma proporcional de acordo com as mudanças nas construções
  if found then

    perform fc_debug(' <calcula_taxa_limpeza> ---------------- I N I C I O ----------------', lRaise);
    perform fc_debug(' <calcula_taxa_limpeza> Plugin de calculo de IPTU proporcional ativo',  lRaise);

    for r_Carconstr in
      select j48_caract,
             area,
             j39_idcons,
             area_isencao,
             meses
        from (
            select array_accum(mes) as meses,
                   j39_idcons,
                   sum(coalesce((select area
                                   from plugins.iptuconstrareahistorico
                                  where matricula = j39_matric
                                    and id_constr = j39_idcons
                                    and data >= (iAnousu||'-'||mes||'-01')::date
                                  order by data
                                  limit 1), j39_area) / 12) as area,
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
                          ) * (coalesce((select aliquota from fc_iptu_verifica_isencao_taxa_competencia(j39_matric, iAnousu, mes, iReceita, lRaise)), 0))
                        )
                      ), 0)
                  )::numeric / 12 as area_isencao,
                   j39_area as areafinal
              from iptuconstr, generate_series(1,12) as mes
             where iptuconstr.j39_matric in (select matric from tmpdadostaxa limit 1)
               and j39_dtlan < (iAnousu||'-'||mes||'-01')::date
               and (j39_dtdemo is null or j39_dtdemo >= (iAnousu||'-'||mes||'-01')::date)
             group by j39_idcons,
                      j39_area
             ) as x
             inner join carconstr on j48_matric in (select matric from tmpdadostaxa limit 1) and j48_idcons = j39_idcons
             inner join caracter on j48_caract = j31_codigo
       where j31_grupo = 4
    loop

      iCarTaxa = r_Carconstr.j48_caract;
      nAreaTotConstr = r_Carconstr.area;

      perform fc_debug(' <calcula_taxa_limpeza> j39_idcons ................: ' || r_Carconstr.j39_idcons,   lRaise);
      perform fc_debug(' <calcula_taxa_limpeza> area ......................: ' || r_Carconstr.area,         lRaise);
      perform fc_debug(' <calcula_taxa_limpeza> area_isencao ..............: ' || r_Carconstr.area_isencao, lRaise);
      perform fc_debug(' <calcula_taxa_limpeza> Caracteristica ............: ' || iCarTaxa,                 lRaise);

      if iCarTaxa is not null then

        select j72_valor
          into nj72_valor
          from carzonavalor
         where j72_anousu = iAnousu
           and j72_caract = iCartaxa
           and j72_zona = iZona
           and j72_tipo = 'P';

        if nj72_valor is null then
          nLimpeza := 0;
          nLimpezaIsen := 0;
        else
          nLimpeza := nLimpeza + (nj72_valor * nAreaTotConstr);
          nLimpezaIsen := nLimpezaIsen + ((nj72_valor * nAreaTotConstr) - (nj72_valor * r_Carconstr.area_isencao));
        end if;

        perform fc_debug(' <calcula_taxa_limpeza> Valor da Caracteristica ...: ' || nj72_valor, lRaise);
        perform fc_debug(' <calcula_taxa_limpeza> Taxa de Limpeza ...........: ' || nLimpeza,   lRaise);

      end if;

      aTotalMeses := array_cat(aTotalMeses, r_Carconstr.meses);

      perform fc_debug(' <calcula_taxa_limpeza> ', lRaise);

    end loop;

    select count(*)
      into iMesesPredial
      from (select distinct unnest(aTotalMeses)) as x;

    perform fc_debug(' <calcula_taxa_limpeza> Meses para calculo predial ....: ' || iMesesPredial, lRaise);

    if bPredial is false or (iMesesPredial <> 0 and iMesesPredial <> 12) then

      select j72_valor
        into nj72_valor
        from carzonavalor
       where j72_anousu = iAnousu
         and j72_caract = 124
         and j72_zona = iZona
         and j72_tipo = 'T';

      select case when j36_testle = 0 then j36_testad else j36_testle end as j36_testle
        into nTestada
        from iptuconstr
             inner join testada on j36_face = j39_codigo and j36_idbql = iidbql
             inner join face on j37_face = j36_face
             inner join facevalor on j81_face = j37_face and j81_anousu = ianousu
             inner join iptubase on j01_matric = j39_matric
       where j39_matric in (select matric from tmpdadostaxa limit 1)
         and j39_dtdemo is null
         and j01_baixa is null
       limit 1;

      if nTestada is null then

        select case when j36_testle = 0 then j36_testad else j36_testle end as j36_testle
          into nTestada
          from testpri
               inner join face on j49_face = j37_face
               inner join facevalor on j81_face = j37_face and j81_anousu = ianousu
               inner join testada on j49_face = j36_face and j49_idbql = j36_idbql
         where j49_idbql = iIdbql;

      end if;

      nTaxaTemporaria := nj72_valor * nTestada;

      perform fc_debug(' <calcula_taxa_limpeza> Valor da Caracteristica Territorial ....: ' || nj72_valor,      lRaise);
      perform fc_debug(' <calcula_taxa_limpeza> Area da Testada ........................: ' || nTestada,        lRaise);
      perform fc_debug(' <calcula_taxa_limpeza> Taxa Territorial .......................: ' || nTaxaTemporaria, lRaise);

      nTaxaTeritorialIsen := (nTaxaTemporaria / 100) * (100 - iPercIsen);

      if iMesesPredial <> 0 then
        nLimpeza := nLimpeza + ((nTaxaTemporaria / 12) * (12 - iMesesPredial));
        nLimpezaIsen := nLimpezaIsen + ((nTaxaTeritorialIsen / 12) * (12 - iMesesPredial));
      else
        nLimpeza := nTaxaTemporaria;
        nLimpezaIsen := nTaxaTeritorialIsen;
      end if;

    end if;

    nLimpezaIsen := nLimpeza - nLimpezaIsen;

    iPercIsen := (100 * nLimpezaIsen) / nLimpeza;

    perform fc_debug(' <calcula_taxa_limpeza> Meses para calculo predial ....: ' || iMesesPredial, lRaise);
    perform fc_debug(' <calcula_taxa_limpeza> iPercIsen .....................: ' || iPercIsen,     lRaise);
    perform fc_debug(' <calcula_taxa_limpeza> nLimpeza ......................: ' || nLimpeza,      lRaise);

  else

    if bPredial is false then

      select j72_valor
        into nj72_valor
        from carzonavalor
       where j72_anousu = iAnousu
         and j72_caract = 124
         and j72_zona = iZona
         and j72_tipo = 'T';

      select case when j36_testle = 0 then j36_testad else j36_testle end as j36_testle
        into nTestada
        from iptuconstr
             inner join testada on j36_face = j39_codigo and j36_idbql = iidbql
             inner join face on j37_face = j36_face
             inner join facevalor on j81_face = j37_face and j81_anousu = ianousu
             inner join iptubase on j01_matric = j39_matric
       where j39_matric in (select matric from tmpdadostaxa limit 1)
         and j39_dtdemo is null and j01_baixa is null
       limit 1;

      if nTestada is null then

        select case when j36_testle = 0 then j36_testad else j36_testle end as j36_testle
          into nTestada
          from testpri
               inner join face on j49_face = j37_face
               inner join facevalor on j81_face = j37_face and j81_anousu = ianousu
               inner join testada on j49_face = j36_face and j49_idbql = j36_idbql
         where j49_idbql = iIdbql;

      end if;

      nLimpeza := nj72_valor * nTestada;

      perform fc_debug(' <calcula_taxa_limpeza> Valor da Caracteristica Territorial ....: ' || nj72_valor, lRaise);
      perform fc_debug(' <calcula_taxa_limpeza> Área da Testada ........................: ' || nTestada,   lRaise);
      perform fc_debug(' <calcula_taxa_limpeza> Taxa Territorial .......................: ' || nLimpeza,   lRaise);

    else

      for r_Carconstr in
        select j48_caract, j39_area, j39_idcons
          from iptuconstr
               inner join carconstr on j48_matric = j39_matric and j48_idcons = j39_idcons
               inner join caracter on j48_caract = j31_codigo
         where j39_dtdemo is null
           and j48_matric in (select matric from tmpdadostaxa limit 1)
           and j31_grupo = 4
      loop

        iCarTaxa = r_Carconstr.j48_caract;
        nAreaTotConstr = r_Carconstr.j39_area;

        perform fc_debug(' <calcula_taxa_limpeza> Contrucao ........: ' || r_Carconstr.j39_idcons, lRaise);
        perform fc_debug(' <calcula_taxa_limpeza> Caracteristica ...: ' || iCarTaxa,               lRaise);
        perform fc_debug(' <calcula_taxa_limpeza> Area Total .......: ' || nAreaTotConstr,         lRaise);

        if iCarTaxa is not null then

          select j72_valor
            into nj72_valor
            from carzonavalor
           where j72_anousu = iAnousu
             and j72_caract = iCartaxa
             and j72_zona = iZona
             and j72_tipo = 'P';

          if nj72_valor is null then
            nLimpeza = 0;
          else
            nLimpeza = nLimpeza + ( nj72_valor * nAreaTotConstr );
          end if;

          perform fc_debug(' <calcula_taxa_limpeza> Valor da Característica ....: ' || nj72_valor, lRaise);
          perform fc_debug(' <calcula_taxa_limpeza> Taxa de Limpeza ............: ' || nLimpeza,   lRaise);

        end if;

      end loop;

    end if;
  end if;

  nLimpeza := coalesce(nLimpeza, 0);

  perform fc_debug(' <calcula_taxa_limpeza> Taxa de Limpeza Calculada .....: ' || nLimpeza, lRaise);

  insert into tmptaxapercisen values (iReceita, iPercIsen, 0, nLimpeza);

  if iPercIsen > 0 then

    perform fc_debug(' <calcula_taxa_limpeza> Percentual de Isencao ........: ' || iPercIsen, lRaise);
    nLimpeza := nLimpeza * (100 - iPercIsen) / 100;
    perform fc_debug(' <calcula_taxa_limpeza> Taxa de Limpeza Calculada ....: ' || nLimpeza,  lRaise);
  end if;

  perform fc_debug(' <calcula_taxa_limpeza> ------------------- F I M -------------------', lRaise);

  tSql := 'insert into tmprecval values ('||iReceita||','||nLimpeza||','||iHistCalc||',true)';
  execute tSql;

  return true;

end;
$$ language 'plpgsql';
SQL
        );
    }
}
