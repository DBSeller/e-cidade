<?php

use Classes\PostgresMigration;

class M13705RecalculoIptu extends PostgresMigration
{
    public function up()
    {
        $sql = <<<EITA
create or replace function fc_iptu_getselectvencimentos(integer,integer,integer,integer,integer,integer,float,boolean) returns varchar as
$$
declare

  iMatricula         alias for $1;
  iAnousu            alias for $2;
  iCodCadVenc        alias for $3;
  iMesIni            alias for $4;
  iParcelas          alias for $5;
  iDiaPadrao         alias for $6;
  lRaise             alias for $8;

  iTotalParcelas     integer default 0;
  iParcelaAtual      integer default 0;
  iMesAtual          integer default 0;
  iAnousuAtual       integer default 0;
  iTipo              integer default 0;
  iHist              integer default 0;
  iParcCanceladas    integer default 0;
  iParcCalcular      integer default 0;
  iParcCadvenc       integer default 0;

  nValorPago         float8;
  nValorTotalImposto float8;
  nPercentual        float8;
  nVlrMinimo         float8;
  nValorTotal        float8;
  nTotalNovo         float8;
  nPercCalculadoNovo float8;

  dDataVencimento    date;

  sSqlRetorno        varchar default '';

  rPagamentos        record;

begin

  for rPagamentos in
  select coalesce(sum(case when arrepaga.k00_valor is null then arrecant.k00_valor else arrepaga.k00_valor end ),0) as valor, arrecant.k00_receit
    from iptunump
         inner join arrecant on arrecant.k00_numpre = iptunump.j20_numpre
         left  join arrepaga on arrepaga.k00_numpre = arrecant.k00_numpre
                            and arrepaga.k00_numpar = arrecant.k00_numpar
                            and arrepaga.k00_receit = arrecant.k00_receit
   where j20_matric = iMatricula
     and j20_anousu = iAnousu
     and arrepaga.k00_hist <> 918
   group by arrecant.k00_receit
  loop

      perform fc_debug('<iptu_getselectvencimentos> nValorPago '||rPagamentos.valor, true);

      update tmprecval
         set valor = valor - rPagamentos.valor
      where  receita = rPagamentos.k00_receit;
  end loop;

  if iMesIni <> 0 and iParcelas <> 0 and iDiaPadrao <> 0 then

    select q92_tipo, q92_hist, q92_vlrminimo
      into iTipo, iHist, nVlrMinimo
      from cadvencdesc
     where q92_codigo = iCodCadVenc;

     select coalesce(max(k00_numpar),0)
       into iParcelaAtual
       from iptunump
            inner join arrecant on arrecant.k00_numpre = iptunump.j20_numpre
      where j20_matric = iMatricula
        and j20_anousu = iAnousu;

    nPercentual    := ( 100::float / iParcelas );
    iTotalParcelas := ( iParcelas + iMesIni -1 );
    iParcelaAtual  := ( iParcelaAtual + 1 );
    iMesAtual      := iMesIni;
    iAnousuAtual   := iAnousu;

    for iParcela in iMesIni..iTotalParcelas loop

        dDataVencimento := cast( iAnousuAtual::varchar||'-'||iMesAtual::varchar||'-'||iDiaPadrao::varchar as date );

        insert into tmp_cadvenc (q92_codigo,q92_tipo,q92_hist,q92_vlrminimo,q82_parc,q82_venc,q82_perc,q82_hist)
                         values (iCodCadVenc,iTipo,iHist,nVlrMinimo,iParcelaAtual,dDataVencimento,nPercentual,iHist);

        iParcelaAtual  := ( iParcelaAtual + 1 );
        iMesAtual      := ( iMesAtual + 1 );
        if iMesAtual > 12 then
          iMesAtual    := 1;
          iAnousuAtual := ( iAnousuAtual + 1 );
        end if;

    end loop;

    sSqlRetorno := 'select q92_codigo,
                           q92_tipo,
                           q92_hist,
                           q92_vlrminimo,
                           q82_parc,
                           q82_venc,
                           q82_perc,
                           q82_hist
                      from tmp_cadvenc ';
  else

    select coalesce(count(*),0)
      into iParcCanceladas
      from ( select distinct k00_numpre,k00_numpar
               from iptunump
                    inner join arrecant on arrecant.k00_numpre = iptunump.j20_numpre
              where j20_matric = iMatricula
                and j20_anousu = iAnousu
           ) as x;

     select ( count(q82_parc) - iParcelas )
       into iParcCadvenc
       from cadvencdesc
            inner join cadvenc on q92_codigo = q82_codigo
      where q92_codigo = iCodCadVenc;

    iParcCalcular := (iParcCadvenc - iParcCanceladas);

    if iParcCanceladas <> 0 then

      if iParcCalcular <> 0 then
        nPercentual := ( 100::float / iParcCalcular );
      else
        nPercentual := ( 100::float );
      end if;
    end if;

    sSqlRetorno = 'select q92_codigo,
                          q92_tipo,
                          q92_hist,
                          q92_vlrminimo,
                          q82_parc,
                          q82_venc,';

    if iParcCanceladas <> 0 then
      sSqlRetorno = sSqlRetorno || nPercentual || '::float8 as q82_perc, ';
    else
      sSqlRetorno = sSqlRetorno || 'q82_perc, ';
    end if;

    sSqlRetorno   = sSqlRetorno || 'q82_hist
                              from cadvencdesc
                                   inner join cadvenc on q92_codigo = q82_codigo
                             where q92_codigo = ' || iCodCadVenc || ' order by q82_parc';

  end if;

  perform fc_debug(' <iptu_getselectvencimentos> ' || sSqlRetorno, lRaise );

  return sSqlRetorno;

end;
$$  language 'plpgsql';
EITA;
        $this->execute($sql);
    }

    public function down()
    {
        $sql = <<<EITA
create or replace function fc_iptu_getselectvencimentos(integer,integer,integer,integer,integer,integer,float,boolean) returns varchar as
$$
declare

  iMatricula         alias for $1;
  iAnousu            alias for $2;
  iCodCadVenc        alias for $3;
  iMesIni            alias for $4;
  iParcelas          alias for $5;
  iDiaPadrao         alias for $6;
  lRaise             alias for $8;

  iTotalParcelas     integer default 0;
  iParcelaAtual      integer default 0;
  iMesAtual          integer default 0;
  iAnousuAtual       integer default 0;
  iTipo              integer default 0;
  iHist              integer default 0;
  iParcCanceladas    integer default 0;
  iParcCalcular      integer default 0;
  iParcCadvenc       integer default 0;

  nValorPago         float8;
  nValorTotalImposto float8;
  nPercentual        float8;
  nVlrMinimo         float8;
  nValorTotal        float8;
  nTotalNovo         float8;
  nPercCalculadoNovo float8;

  dDataVencimento    date;

  sSqlRetorno        varchar default '';

begin

  select coalesce(sum(case when arrepaga.k00_valor is null then arrecant.k00_valor else arrepaga.k00_valor end ),0)
    into nValorPago
    from iptunump
         inner join arrecant on arrecant.k00_numpre = iptunump.j20_numpre
         left  join arrepaga on arrepaga.k00_numpre = arrecant.k00_numpre
                            and arrepaga.k00_numpar = arrecant.k00_numpar
                            and arrepaga.k00_receit = arrecant.k00_receit
   where j20_matric = iMatricula
     and j20_anousu = iAnousu
     and arrepaga.k00_hist <> 918;

  perform fc_debug('<iptu_getselectvencimentos> nValorPago '||nValorPago, true);

  select sum(valor)
    into nValorTotalImposto
    from tmprecval;

  perform fc_debug('<iptu_getselectvencimentos> nValorTotalImposto '||nValorTotalImposto, true);    

  if nValorPago > 0 then

    if nValorTotalImposto > 0 then
      nPercentual = ( 100 - ( nValorPago / nValorTotalImposto * 100 ) );
    else
      nPercentual = 100;
    end if;

    update tmprecval
       set valor = ( ( valor / 100 ) * nPercentual ) ;
  end if;

  if iMesIni <> 0 and iParcelas <> 0 and iDiaPadrao <> 0 then

    select q92_tipo, q92_hist, q92_vlrminimo
      into iTipo, iHist, nVlrMinimo
      from cadvencdesc
     where q92_codigo = iCodCadVenc;

     select coalesce(max(k00_numpar),0)
       into iParcelaAtual
       from iptunump
            inner join arrecant on arrecant.k00_numpre = iptunump.j20_numpre
      where j20_matric = iMatricula
        and j20_anousu = iAnousu;

    nPercentual    := ( 100::float / iParcelas );
    iTotalParcelas := ( iParcelas + iMesIni -1 );
    iParcelaAtual  := ( iParcelaAtual + 1 );
    iMesAtual      := iMesIni;
    iAnousuAtual   := iAnousu;

    for iParcela in iMesIni..iTotalParcelas loop

        dDataVencimento := cast( iAnousuAtual::varchar||'-'||iMesAtual::varchar||'-'||iDiaPadrao::varchar as date );

        insert into tmp_cadvenc (q92_codigo,q92_tipo,q92_hist,q92_vlrminimo,q82_parc,q82_venc,q82_perc,q82_hist)
                         values (iCodCadVenc,iTipo,iHist,nVlrMinimo,iParcelaAtual,dDataVencimento,nPercentual,iHist);

        iParcelaAtual  := ( iParcelaAtual + 1 );
        iMesAtual      := ( iMesAtual + 1 );
        if iMesAtual > 12 then
          iMesAtual    := 1;
          iAnousuAtual := ( iAnousuAtual + 1 );
        end if;

    end loop;

    sSqlRetorno := 'select q92_codigo,
                           q92_tipo,
                           q92_hist,
                           q92_vlrminimo,
                           q82_parc,
                           q82_venc,
                           q82_perc,
                           q82_hist
                      from tmp_cadvenc ';
  else

    select coalesce(count(*),0)
      into iParcCanceladas
      from ( select distinct k00_numpre,k00_numpar
               from iptunump
                    inner join arrecant on arrecant.k00_numpre = iptunump.j20_numpre
              where j20_matric = iMatricula
                and j20_anousu = iAnousu
           ) as x;

     select ( count(q82_parc) - iParcelas )
       into iParcCadvenc
       from cadvencdesc
            inner join cadvenc on q92_codigo = q82_codigo
      where q92_codigo = iCodCadVenc;

    iParcCalcular := (iParcCadvenc - iParcCanceladas);

    if iParcCanceladas <> 0 then

      if iParcCalcular <> 0 then
        nPercentual := ( 100::float / iParcCalcular );
      else
        nPercentual := ( 100::float );
      end if;
    end if;

    sSqlRetorno = 'select q92_codigo,
                          q92_tipo,
                          q92_hist,
                          q92_vlrminimo,
                          q82_parc,
                          q82_venc,';

    if iParcCanceladas <> 0 then
      sSqlRetorno = sSqlRetorno || nPercentual || '::float8 as q82_perc, ';
    else
      sSqlRetorno = sSqlRetorno || 'q82_perc, ';
    end if;

    sSqlRetorno   = sSqlRetorno || 'q82_hist
                              from cadvencdesc
                                   inner join cadvenc on q92_codigo = q82_codigo
                             where q92_codigo = ' || iCodCadVenc || ' order by q82_parc';

  end if;

  perform fc_debug(' <iptu_getselectvencimentos> ' || sSqlRetorno, lRaise );

  return sSqlRetorno;

end;
$$  language 'plpgsql';
EITA;

        $this->execute($sql);
    }
}
