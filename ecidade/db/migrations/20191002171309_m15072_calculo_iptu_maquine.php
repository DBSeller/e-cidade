<?php

use Classes\PostgresMigration;

class M15072CalculoIptuMaquine extends PostgresMigration
{

    public function up()
    {
        return true;
        $sql = <<<SQL_UP

               insert into db_sysfuncoes( codfuncao ,nomefuncao ,nomearquivo ,obsfuncao ,corpofuncao ,triggerfuncao ) values ( 188 ,'fc_iptu_taxalixo_maq_2019' ,'fc_iptu_taxalixo_maq_2019' ,'Função para calculo da taxa de lixo de Maquiné.' ,'create or replace function fc_iptu_taxalixo_maq_2019(integer, numeric, integer, numeric, boolean) returns boolean as
$$

declare

   iReceita        alias for $1;
   iAliquota       alias for $2;
   iHistCalc       alias for $3;
   iPercIsen       alias for $4;
   lRaise          alias for $5;

   nValTaxa         numeric(15,2) default 0;
   nValTaxaBase     numeric(15,2) default 0;

   iIdbql           integer       default 0;
   iAnousu          integer       default 0;
   iMatric          integer       default 0;
   iMultiplicador   integer       default 0;
   iCaracterCalculo integer       default 0;

   dDataConstr      date;
   dDataBase        date;

   bPredial         boolean       default false;

   tSql             text          default \'\';
   tRetorno         text          default \'\';

begin

   perform fc_debug(\' <iptu_taxalixo> Calculando taxa de lixo\', lRaise);
   perform fc_debug(\' \',                                           lRaise);
   perform fc_debug(\' <iptu_taxalixo> receita: \'   || iReceita,    lRaise);
   perform fc_debug(\' <iptu_taxalixo> aliq: \'      || iAliquota,   lRaise);
   perform fc_debug(\' <iptu_taxalixo> historico: \' || iHistCalc,   lRaise);

   -- busca informacoes cadastrais para o calculo

   select idbql, anousu, matric,
          case when totareaconst > 0 then true
               else false
          end
    into iIdbql, iAnousu, iMatric, bPredial
   from tmpdadostaxa limit 1;

   select j35_caract
     into iCaracterCalculo
   from iptubase inner join lote     on j34_idbql  = j01_idbql
                 inner join carlote  on j35_idbql  = j34_idbql
                 inner join caracter on j31_codigo = j35_caract
                                    and j31_grupo  = 1
   where j01_matric = iMatric;

   if bPredial = false or iCaracterCalculo = 3 then
      perform fc_debug(\'Verifica se existe caracteristica do grupo 50 para calcular a taxa de lixo para terrenos\', lRaise);
   
      select j74_fator
        into nValTaxaBase
      from carlote inner join caracter on j31_codigo = j35_caract
                   inner join carfator on j74_anousu = iAnousu
                                      and j74_caract = j35_caract
      where j35_idbql = iIdbql
        and j31_grupo = 50;
   else
      perform fc_debug(\'Verifica se existe caracteristica do grupo 60 para calcular a taxa de lixo para predios\', lRaise);
   
      select j74_fator
        into nValTaxaBase
      from carconstr inner join caracter on j31_codigo = j48_caract
                     inner join carfator on j74_anousu = iAnousu
                                        and j74_caract = j48_caract
      where j48_matric = iMatric
        and j31_grupo = 60 limit 1;
   end if;

   if nValTaxaBase = 0 or nValTaxaBase is null then
      if bPredial = false or iCaracterCalculo = 3 then
        select fc_iptu_geterro(106,\'do grupo 50. Valor zerado ou não informado. Tabela carfator.\')
        into tRetorno;
      else
        select fc_iptu_geterro(106,\'do grupo 60. Valor zerado ou não informado. Tabela carfator.\')
        into tRetorno;
      end if;

      return false;
   end if;

   perform fc_debug(\'Verifica a data da construção para calcular proporcional a taxa, se necessário.\', lRaise);

   dDataBase := (iAnousu||\'-01-01\')::date;

   select coalesce(j39_dtlan,dDataBase)
      into dDataConstr
   from iptuconstr 
   where j39_matric = iMatric
     and j39_dtdemo is null
   order by j39_dtlan;

   if dDataConstr > dDataBase then

      select count(*) - 1
        into iMultiplicador
      from generate_series(dDataBase,dDataConstr, INTERVAL \'1 month\');

      nValTaxa := ((nValTaxaBase / 12) * iMultiplicador);

   else

      nValTaxa := nValTaxaBase;

   end if;

   insert into tmptaxapercisen values (iReceita,iPercIsen,0,nValTaxa);
   
   if iPercIsen > 0 then
     nValTaxa := nValTaxa * (100 - iPercIsen) / 100;
   end if;

   perform fc_debug(\' <iptu_taxalixo> Percentual Isencao: \' || iPercIsen, lRaise);
   perform fc_debug(\' <iptu_taxalixo> Valor final da taxa: \' || nValTaxa, lRaise);

   tSql := \'insert into tmprecval values (\'||iReceita||\',\'||nValTaxa||\',\'||iHistCalc||\',true)\';

   execute tSql;

   return true;

end;

$$ language \'plpgsql\';' ,'0' );
               insert into db_sysfuncoesparam( db42_sysfuncoesparam ,db42_funcao ,db42_ordem ,db42_nome ,db42_tipo ,db42_tamanho ,db42_precisao ,db42_valor_default ,db42_descricao ) values ( 1016 ,188 ,1 ,'iReceita' ,'int4' ,0 ,0 ,'0' ,'RECEITA' );
               insert into db_sysfuncoesparam( db42_sysfuncoesparam ,db42_funcao ,db42_ordem ,db42_nome ,db42_tipo ,db42_tamanho ,db42_precisao ,db42_valor_default ,db42_descricao ) values ( 1017 ,188 ,2 ,'iAliquota' ,'numeric' ,0 ,0 ,'0' ,'ALIQUOTA' );
               insert into db_sysfuncoesparam( db42_sysfuncoesparam ,db42_funcao ,db42_ordem ,db42_nome ,db42_tipo ,db42_tamanho ,db42_precisao ,db42_valor_default ,db42_descricao ) values ( 1018 ,188 ,3 ,'iHistCalc' ,'int4' ,0 ,0 ,'0' ,'HISTORICO DE                CALCULO' );               
               insert into db_sysfuncoesparam( db42_sysfuncoesparam ,db42_funcao ,db42_ordem ,db42_nome ,db42_tipo ,db42_tamanho ,db42_precisao ,db42_valor_default ,db42_descricao ) values ( 1019 ,188 ,4 ,'iPercIsen' ,'numeric' ,0 ,0 ,'0' ,'PERCENTUAL                DE                ISENCAO' );
               insert into db_sysfuncoesparam( db42_sysfuncoesparam ,db42_funcao ,db42_ordem ,db42_nome ,db42_tipo ,db42_tamanho ,db42_precisao ,db42_valor_default ,db42_descricao ) values ( 1020 ,188 ,5 ,'bRaise' ,'bool' ,0 ,0 ,'FALSE' ,'DEBUG' );

               insert into db_sysfuncoes( codfuncao ,nomefuncao ,nomearquivo ,obsfuncao ,corpofuncao ,triggerfuncao ) values ( 189 ,'fc_iptu_calculavvt_maq_2019' ,'fc_iptu_calculavvt_maq_2019' ,'Procedimento para calculo do valor venal do terreno para Maquiné.' ,'create or replace function fc_iptu_calculavvt_maq_2019(integer,integer,integer,numeric,boolean,boolean) returns tp_iptu_calculavvt as
$$

declare

  iIdbql            alias for $1;
  iMatricula        alias for $2;
  iAnousu           alias for $3;
  nFracao           alias for $4;
  lMostrademo       alias for $5;
  lRaise            alias for $6;

  iZona             integer;

  nVm2t             numeric default 0;
  nAreaLoteCorrigi  numeric default 0;
  nArealote         numeric default 0;
  nValor            numeric default 0;
  nTestada          numeric default 0;

  rCfiptu           record;

  rtp_iptu_calculavvt tp_iptu_calculavvt%ROWTYPE;

begin
 
    rtp_iptu_calculavvt.rnAreaTotalC := 0;
    rtp_iptu_calculavvt.rnArea       := 0;
    rtp_iptu_calculavvt.rnTestada    := 0;
    rtp_iptu_calculavvt.rtDemo       := \'\';
    rtp_iptu_calculavvt.rtMsgerro    := \'\';
    rtp_iptu_calculavvt.rbErro       := \'f\';
    rtp_iptu_calculavvt.riCoderro    := 0;
    rtp_iptu_calculavvt.rtErro       := \'\';

    perform fc_debug(\'INICIANDO CALCULO DO VALOR VENAL TERRITORIAL...\', lRaise);

    select j34_zona,
           case when j34_area = 0
             then j34_areal
             else j34_area
           end as nAreal
      into iZona,
           nArealote
      from lote
     where j34_idbql = iIdbql;

    if nArealote is null or nArealote = 0 then
     
        rtp_iptu_calculavvt.rbErro    := \'t\';
        rtp_iptu_calculavvt.riCoderro := 36;
        rtp_iptu_calculavvt.rtErro    := \'\';
     
        return rtp_iptu_calculavvt;
    end if;

    select coalesce(j36_testad,0)
      into nTestada
    from testada inner join testpri on j49_idbql  = j36_idbql
                                   and j49_face   = j36_face
                                   and j49_codigo = j36_codigo ;

    select j51_valorm2t
      into nVm2t
      from zonasvalor
     where j51_zona   = iZona
       and j51_anousu = iAnousu;

    perform fc_debug(\'nVm2t    \'||nVm2t, lRaise);
    perform fc_debug(\'iZona    \'||iZona, lRaise);
    perform fc_debug(\'iAnousu  \'||iAnousu, lRaise);

    if nVm2t is null or nVm2t = 0 then

      rtp_iptu_calculavvt.rbErro    := \'t\';
      rtp_iptu_calculavvt.riCoderro := 7;
      rtp_iptu_calculavvt.rtErro    := iZona::text||\' - Tabela: zonasvlor\';

      return rtp_iptu_calculavvt;
    end if;

    /*============================================================================================*/

    nAreaLoteCorrigi := ( nArealote * ( nFracao / 100::numeric ))::numeric;

    nValor := round( (nAreaLoteCorrigi * nVm2t)::numeric, 2);

    select * from into rCfiptu cfiptu where j18_anousu = iAnousu;

    rtp_iptu_calculavvt.rnArea       := nArealote;
    rtp_iptu_calculavvt.rnVvt        := nValor;
    rtp_iptu_calculavvt.rnAreaTotalC := nAreaLoteCorrigi;
    rtp_iptu_calculavvt.rnTestada    := nTestada;
    rtp_iptu_calculavvt.rtDemo       := \'\';
    rtp_iptu_calculavvt.rtMsgerro    := \'\';
    rtp_iptu_calculavvt.rbErro       := \'f\';

    update tmpdadosiptu set vvt = rtp_iptu_calculavvt.rnVvt, vm2t=nVm2t, areat=nAreaLoteCorrigi;

    return rtp_iptu_calculavvt;

end;
$$  language \'plpgsql\';' ,'0' );
               insert into db_sysfuncoesparam( db42_sysfuncoesparam ,db42_funcao ,db42_ordem ,db42_nome ,db42_tipo ,db42_tamanho ,db42_precisao ,db42_valor_default ,db42_descricao ) values ( 1021, 189, 1, 'iIdbql' ,'int4' ,0 ,0 ,'0' ,'CÓDIGO DO IDBQL DO LOTE.' );
               insert into db_sysfuncoesparam( db42_sysfuncoesparam ,db42_funcao ,db42_ordem ,db42_nome ,db42_tipo ,db42_tamanho ,db42_precisao ,db42_valor_default ,db42_descricao ) values ( 1022, 189, 2, 'iMatricula' ,'int4' ,0 ,0 ,'0' ,'MATRICULA DO IMÓVEL' );
               insert into db_sysfuncoesparam( db42_sysfuncoesparam ,db42_funcao ,db42_ordem ,db42_nome ,db42_tipo ,db42_tamanho ,db42_precisao ,db42_valor_default ,db42_descricao ) values ( 1023, 189, 3, 'iAnousu' ,'int4' ,0 ,0 ,'0' ,'ANO DO CÁLCULO' );               
               insert into db_sysfuncoesparam( db42_sysfuncoesparam ,db42_funcao ,db42_ordem ,db42_nome ,db42_tipo ,db42_tamanho ,db42_precisao ,db42_valor_default ,db42_descricao ) values ( 1024, 189, 4, 'nFracao' ,'numeric' ,0 ,0 ,'0' ,'VALOR DA FRAÇÃO DO IMÓVEL' );
               insert into db_sysfuncoesparam( db42_sysfuncoesparam ,db42_funcao ,db42_ordem ,db42_nome ,db42_tipo ,db42_tamanho ,db42_precisao ,db42_valor_default ,db42_descricao ) values ( 1025, 189, 5, 'bMostrademo' ,'bool' ,0 ,0 ,'FALSE' ,'DEFINE SE EXECUTA O CÁLCULO OU O DEMOSTRATIVO' );
               insert into db_sysfuncoesparam( db42_sysfuncoesparam ,db42_funcao ,db42_ordem ,db42_nome ,db42_tipo ,db42_tamanho ,db42_precisao ,db42_valor_default ,db42_descricao ) values ( 1026, 189, 6, 'bRaise' ,'bool' ,0 ,0 ,'FALSE' ,'DEBUG' );


               insert into db_sysfuncoes( codfuncao ,nomefuncao ,nomearquivo ,obsfuncao ,corpofuncao ,triggerfuncao ) values ( 190 ,'fc_iptu_getaliquota_maq_2019' ,'fc_iptu_getaliquota_maq_2019' ,'Função que busca a alíquota de cálculo do IPTU.' ,'create or replace function fc_iptu_getaliquota_maq_2019(integer,boolean,boolean) returns numeric as
$$


declare

    iIdbql     alias for $1;
    bPredial   alias for $2;
    bRaise     alias for $3;

    rnAliq     numeric default 0;
    nAlipre    numeric default 0;
    nAliter    numeric default 0;

    sPredial   varchar;

begin

  /* EXECUTAR SOMENTE SE NAO TIVER ISENCAO */

  if bPredial is true then
     sPredial = \'PREDIAL\';
  else
     sPredial = \'TERRITORIAL\';
  end if;

  if bRaise then
     perform fc_debug(\'DEFININDO QUAL ALIQUOTA APLICAR ...\', bRaise);
     perform fc_debug(\'IPTU \'||sPredial, bRaise);
  end if;

  select j30_aliter, j30_alipre
    into nAliter, nAlipre
    from lote
         inner join setor on j34_setor = j30_codi
   where j34_idbql = iIdbql;

 -- criterios para escolha da aliquota

 if bPredial then -- predial
   rnAliq = nAlipre;
 else  -- territorial
   rnAliq = nAliter;
 end if;

 if bRaise then
   raise notice \'aliquota final : %\',rnAliq;
 end if;

 execute \'update tmpdadosiptu set aliq = \'||rnAliq;

 return rnAliq;
   
end;
$$  language \'plpgsql\';' ,'0' );
               insert into db_sysfuncoesparam( db42_sysfuncoesparam ,db42_funcao ,db42_ordem ,db42_nome ,db42_tipo ,db42_tamanho ,db42_precisao ,db42_valor_default ,db42_descricao ) values ( 1027 ,190 ,1 ,'iIdbql' ,'int4' ,0 ,0 ,'' ,'CÓDIGO DO IDBQL DO LOTE.' );
               insert into db_sysfuncoesparam( db42_sysfuncoesparam ,db42_funcao ,db42_ordem ,db42_nome ,db42_tipo ,db42_tamanho ,db42_precisao ,db42_valor_default ,db42_descricao ) values ( 1028 ,190 ,2 ,'bPredial' ,'0' ,0 ,0 ,'FALSE' ,'IDENTIFICA SE O IMÓVEL É PREDIAL OU TERRITORIAL' );
               insert into db_sysfuncoesparam( db42_sysfuncoesparam ,db42_funcao ,db42_ordem ,db42_nome ,db42_tipo ,db42_tamanho ,db42_precisao ,db42_valor_default ,db42_descricao ) values ( 1029 ,190 ,3 ,'bRaise' ,'0' ,0 ,0 ,'FALSE' ,'DEBUG' );

               insert into db_sysfuncoes( codfuncao ,nomefuncao ,nomearquivo ,obsfuncao ,corpofuncao ,triggerfuncao ) values ( 191 ,'fc_iptu_calculavvc_maq_2019' ,'fc_iptu_calculavvc_maq_2019' ,'Função para calculo do valor venal da construção para Maquiné.' ,'create or replace function fc_iptu_calculavvc_maq_2019(integer,integer,boolean,boolean) returns tp_iptu_calculavvc as
$$

 declare

     iMatricula       alias for $1;
     iAnousu          alias for $2;
     lMostrademo      alias for $3;
     lRaise           alias for $4;

     nAreatc          numeric default 0;
     nVm2c            numeric default 0;
     nVm2cAD          numeric default 0;
     nVvcP            numeric default 0;
     nVvc             numeric default 0;
     nCondominio      numeric default 0;
     nPontosCalculo   numeric default 0;

     iPontos          integer default 0;
     iAnoconstrucao   integer default 0;
     iNumerocontr     integer default 0;

     lAtualiza        boolean default true;

     rConstr          record;
     rIdconstr        record;

     rtp_iptu_calculavvc tp_iptu_calculavvc%ROWTYPE;

 begin
     perform fc_debug(\'INICIANDO CALCULO VVC ...\', lRaise);

     rtp_iptu_calculavvc.rnVvc       := 0;
     rtp_iptu_calculavvc.rnTotarea   := 0;
     rtp_iptu_calculavvc.riNumconstr := 0;
     rtp_iptu_calculavvc.rtDemo      := \'\';
     rtp_iptu_calculavvc.rtMsgerro   := \'Retorno ok\' ;
     rtp_iptu_calculavvc.rbErro      := \'f\';
     rtp_iptu_calculavvc.riCodErro   := 0;
     rtp_iptu_calculavvc.rtErro      := \'\';

     iNumerocontr := 0;

     for rConstr in select * from iptuconstr
                     where j39_matric = iMatricula
                       and j39_dtdemo is null
     loop

       iNumerocontr   := iNumerocontr + 1;

       select j83_pontos
         into iPontos
       from iptuconstrpontos
       where j83_matric = rConstr.j39_matric
         and j83_idcons = rConstr.j39_idcons;

       if iPontos is null or iPontos = 0 then

         rtp_iptu_calculavvc.rbErro    := \'t\';
         rtp_iptu_calculavvc.riCoderro := 23;
         rtp_iptu_calculavvc.rtErro    := \' PARA A MATRICULA: \'||rConstr.j39_matric::text||\' IDCONS: \'||rConstr.j39_idcons;
         rtp_iptu_calculavvc.rtMsgerro := \'\';
         return rtp_iptu_calculavvc;
       end if;

       perform fc_debug(\'iPontos - \'||iPontos, lRaise);

       select j71_valor
         into nVm2c
       from iptuconstrpontos inner join carconstr on j48_matric = j83_matric
                                                 and j48_idcons = j83_idcons
                             inner join caracter  on j31_codigo = j48_caract
                             inner join carvalor  on j71_anousu = iAnousu
                                                 and j71_caract = j48_caract
                                                 and j83_pontos between j71_quantini and j71_quantfim
       where j83_matric = rConstr.j39_matric
         and j83_idcons = rConstr.j39_idcons
         and j31_grupo  = 28;

       if nVm2c = 0 or nVm2c is null then

          rtp_iptu_calculavvc.rbErro    := \'t\';
          rtp_iptu_calculavvc.riCoderro := 104;
          rtp_iptu_calculavvc.rtErro    := \' 28 PARA A MATRICULA: \'||rConstr.j39_matric::text||\' IDCONS: \'||rConstr.j39_idcons||\' - VERIFIQUE A TABELA CARVALOR.\';
          rtp_iptu_calculavvc.rtMsgerro := \'\';
          return rtp_iptu_calculavvc;

       end if;

       nAreatc := (nAreatc::numeric + rConstr.j39_area::numeric);

       nVvcP := ( rConstr.j39_area::numeric * nVm2c * (iPontos::numeric / 100) );
       nVvcP := round(nVvcP,2);

       perform fc_debug(\'Matricula: \'||rConstr.j39_matric||\'iPontos: \'||iPontos||\' - Area da construcao: \'||rConstr.j39_area||
                        \' - Valor m2 construcao: \'||nVm2c||\' construcao: \'||rConstr.j39_idcons, lRaise);

       perform fc_debug(\'Valor venal da construcao - nVvcp - \'||nVvcp, lRaise);

       nVvc    := round(nVvc + nVvcp,2)::numeric;

       perform fc_debug(\'Valor venal total parcial - nVvc - \'||nVvc, lRaise);

       insert into tmpiptucale (anousu, matric,idcons,areaed,vm2,pontos,valor)
                        values (iAnousu,iMatricula,rConstr.j39_idcons,rConstr.j39_area,nVm2c,iPontos,nVvcp);
       if lAtualiza then
         update tmpdadosiptu set predial = true;
         lAtualiza := false;
       end if;

     end loop;

     perform fc_debug(\'Valor venal total final - nVvc - \'||nVvc, lRaise);

     rtp_iptu_calculavvc.rnVvc       := nVvc::numeric;
     rtp_iptu_calculavvc.rnTotarea   := nAreatc::numeric;
     rtp_iptu_calculavvc.riNumconstr := iNumerocontr;
     rtp_iptu_calculavvc.rtDemo      := \'\';
     rtp_iptu_calculavvc.rbErro      := \'f\';

     update tmpdadosiptu set vvc = rtp_iptu_calculavvc.rnVvc;

     return rtp_iptu_calculavvc;

 end;

$$  language \'plpgsql\';' ,'0' );
               insert into db_sysfuncoesparam( db42_sysfuncoesparam ,db42_funcao ,db42_ordem ,db42_nome ,db42_tipo ,db42_tamanho ,db42_precisao ,db42_valor_default ,db42_descricao ) values ( 1030 ,191 ,1 ,'iMatricula' ,'int4' ,0 ,0 ,'0' ,'MATRÍCULA DO IMÓVEL' );
               insert into db_sysfuncoesparam( db42_sysfuncoesparam ,db42_funcao ,db42_ordem ,db42_nome ,db42_tipo ,db42_tamanho ,db42_precisao ,db42_valor_default ,db42_descricao ) values ( 1031 ,191 ,2 ,'iAnousu' ,'int4' ,0 ,0 ,'' ,'ANO DO CÁLCULO' );
               insert into db_sysfuncoesparam( db42_sysfuncoesparam ,db42_funcao ,db42_ordem ,db42_nome ,db42_tipo ,db42_tamanho ,db42_precisao ,db42_valor_default ,db42_descricao ) values ( 1032 ,191 ,3 ,'bMostrademo' ,'bool' ,0 ,0 ,'' ,'DEFINE SE EXECUTA O CÁLCULO OU O DEMOSTRATIVO' );
               insert into db_sysfuncoesparam( db42_sysfuncoesparam ,db42_funcao ,db42_ordem ,db42_nome ,db42_tipo ,db42_tamanho ,db42_precisao ,db42_valor_default ,db42_descricao ) values ( 1033 ,191 ,4 ,'bRaise' ,'bool' ,0 ,0 ,'' ,'DEBUG' );

               insert into db_sysfuncoes( codfuncao ,nomefuncao ,nomearquivo ,obsfuncao ,corpofuncao ,triggerfuncao ) values ( 192 ,'fc_calculoiptu_maq_2019' ,'fc_calculoiptu_maq_2019' ,'Função principal do cálculo de iptu de Maquiné.' ,'CREATE OR REPLACE FUNCTION fc_calculoiptu_maq_2019(integer,integer,boolean,boolean,boolean,boolean,boolean,integer,integer) RETURNS varchar(100) AS
$$

declare

   iMatricula                          alias   for $1;
   iAnousu                             alias   for $2;
   lGerafinanc                         alias   for $3;
   lAtualizaParcela                    alias   for $4;
   lNovonumpre                         alias   for $5;
   lCalculogeral                       alias   for $6;
   lDemonstrativo                      alias   for $7;
   iParcelaini                         alias   for $8;
   iParcelafim                         alias   for $9;

   iIdbql                              integer default 0;
   iNumcgm                             integer default 0;
   iZona                               integer default 0;
   iCodcli                             integer default 0;
   iCodisen                            integer default 0;
   iTipois                             integer default 0;
   iParcelas                           integer default 0;
   iNumconstr                          integer default 0;
   iCaracterCalculo                    integer default 0;
   iCaracterAnexo                      integer default 0;
   iCaracterTpConstr                   integer default 0;
   iCodErro                            integer default 0;

   dDatabaixa                          date;

   nAreal                              numeric default 0;
   nAreac                              numeric default 0;
   nTotarea                            numeric default 0;
   nFracao                             numeric default 0;
   nFracaolote                         numeric default 0;
   nAliquota                           numeric default 0;
   nIsenaliq                           numeric default 0;
   nArealo                             numeric default 0;
   nVvc                                numeric(15,2) default 0;
   nVvt                                numeric(15,2) default 0;
   nVv                                 numeric(15,2) default 0;
   nViptu                              numeric(15,2) default 0;

   tRetorno                            text default \'\';
   tDemo                               text default \'\';
   tErro                               text default \'\';

   lFinanceiro                         boolean;
   lDadosIptu                          boolean;
   lErro                               boolean;
   lIsentaxas                          boolean;
   lTempagamento                       boolean;
   lEmpagamento                        boolean;
   lTaxasCalculadas                    boolean;
   lRaise                              boolean default false; -- true para habilitar raise na funcao principal
   lSubRaise                           boolean default false; -- true para habilitar raise nas sub-funcoes

   rCfiptu                             record;

begin

  lRaise := ( case when fc_getsession(\'DB_debugon\') is null then false else true end );
  lSubRaise := lRaise;

  perform fc_debug(\'INICIANDO CALCULO\',lRaise,true,false);

  perform fc_debug(\'\',lRaise,true,false);

  select j34_zona, j35_caract
    into iZona, iCaracterCalculo
  from iptubase inner join lote     on j34_idbql  = j01_idbql
                inner join carlote  on j35_idbql  = j34_idbql
                inner join caracter on j31_codigo = j35_caract
                                   and j31_grupo  = 1
  where j01_matric = iMatricula;

  if not found then
    select fc_iptu_geterro( 101, \' 1\' ) into tRetorno;
    return tRetorno;
  end if;

  select coalesce(j48_caract,0)
    into iCaracterAnexo
  from carconstr inner join caracter on j31_codigo = j48_caract
                 inner join carfator on j74_anousu = iAnousu
                                    and j74_caract = j48_caract
  where j48_matric = iMatricula
  and j31_grupo = 59;


  if found or iCaracterAnexo is not null then
     perform fc_debug(\'Caracteristica Tipo de Matricula, nao calcula taxa de lixo -> \' || iCaracterAnexo, lRaise);
  end if;


  select coalesce(j48_caract,0)
    into iCaracterTpConstr
  from carconstr inner join caracter on j31_codigo = j48_caract
                 inner join carfator on j74_anousu = iAnousu
                                    and j74_caract = j48_caract
  where j48_matric = iMatricula
  and j31_grupo = 28;


  if found or iCaracterTpConstr is not null then
     perform fc_debug(\'Caracteristica Tipo Construcao, nao calcula taxa de lixo -> \' || iCaracterTpConstr, lRaise);
  end if;



  /* Verifica a característica do lote, se for (2 - água) não calcula */

  if iCaracterCalculo = 2 then

    select fc_iptu_geterro( 37, \'\' ) into tRetorno;
    return tRetorno;

  end if;

  /**
   * Executa PRE CALCULO
   */
  select r_iIdbql, r_nAreal, r_nFracao, r_iNumcgm, r_dDatabaixa, r_nFracaolote,
         r_tDemo, r_lTempagamento, r_lEmpagamento, r_iCodisen, r_iTipois, r_nIsenaliq,
         r_lIsentaxas, r_nArealote, r_iCodCli, r_tRetorno

    into iIdbql, nAreal, nFracao, iNumcgm, dDatabaixa, nFracaolote, tDemo, lTempagamento,
         lEmpagamento, iCodisen, iTipois, nIsenaliq, lIsentaxas, nArealo, iCodCli, tRetorno

    from fc_iptu_precalculo( iMatricula, iAnousu, lCalculogeral, lAtualizaParcela, lDemonstrativo, lRaise );

  perform fc_debug(\' RETORNO DA PRE CALCULO: \',            lRaise);
  perform fc_debug(\'  iIdbql        -> \' || iIdbql,        lRaise);
  perform fc_debug(\'  nAreal        -> \' || nAreal,        lRaise);
  perform fc_debug(\'  nFracao       -> \' || nFracao,       lRaise);
  perform fc_debug(\'  iNumcgm       -> \' || iNumcgm,       lRaise);
  perform fc_debug(\'  dDatabaixa    -> \' || dDatabaixa,    lRaise);
  perform fc_debug(\'  nFracaolote   -> \' || nFracaolote,   lRaise);
  perform fc_debug(\'  tDemo         -> \' || tDemo,         lRaise);
  perform fc_debug(\'  lTempagamento -> \' || lTempagamento, lRaise);
  perform fc_debug(\'  lEmpagamento  -> \' || lEmpagamento,  lRaise);
  perform fc_debug(\'  iCodisen      -> \' || iCodisen,      lRaise);
  perform fc_debug(\'  iTipois       -> \' || iTipois,       lRaise);
  perform fc_debug(\'  nIsenaliq     -> \' || nIsenaliq,     lRaise);
  perform fc_debug(\'  lIsentaxas    -> \' || lIsentaxas,    lRaise);
  perform fc_debug(\'  nArealote     -> \' || nArealo,       lRaise);
  perform fc_debug(\'  iCodCli       -> \' || iCodCli,       lRaise);
  perform fc_debug(\'  tRetorno      -> \' || tRetorno,      lRaise);
  perform fc_debug(\'\',lRaise,true,false);

  /**
   * Variavel de retorno contem a msg
   * de erro retornada do pre calculo
   */
  
  if trim(tRetorno) <> \'\' then
    return tRetorno;
  end if;

  update tmpdadosiptu set matric = iMatricula;

  /**
   * Guarda os parametros do calculo
   */
  
  select * from into rCfiptu cfiptu where j18_anousu = iAnousu;

  if iCaracterCalculo = 1 then

     /**
      * Calcula valor do terreno
      */
     
     perform fc_debug(\'PARAMETROS fc_iptu_calculavvt_maq_2019 IDBQL: \'||iIdbql||\' - iMatricula: \'||iMatricula||\' - Anousu: \'||iAnousu||\' - FRACAO DO LOTE: \'||nFracaolote||\' - DEMO: \'||lDemonstrativo||\' - DEBUG: \'||lRaise, lRaise);
   
     select rnvvt, rnarea, rtdemo, rtmsgerro, rberro, riCodErro, rtErro
       into nVvt, nAreac, tDemo, tRetorno, lErro, iCodErro, tErro
       from fc_iptu_calculavvt_maq_2019( iIdbql, iMatricula, iAnousu, nFracaolote, lDemonstrativo, lRaise );
     
     perform fc_debug(\'RETORNO fc_iptu_calculavvt_maq_2019 -> VVT: \'||nVvt||\' - AREA CONSTRUIDA: \'||nAreac||\' - RETORNO: \'||tRetorno||\' - ERRO: \'||lErro, lRaise);
     perform fc_debug(\'\', lRaise);
     
     if lErro is true then
       select fc_iptu_geterro( iCodErro, tErro ) into tRetorno;
       return tRetorno;
     end if;
     
     /**
      * Calcula valor da construcao
      */
     
     perform fc_debug(\'PARAMETROS fc_iptu_calculavvc_maq_2019 MATRICULA: \'||iMatricula||\' - ANOUSU: \'||iAnousu||\' - DEMO: \'||lDemonstrativo||\' - DEBUG: \'||lRaise, lRaise);
     
     select rnvvc, rntotarea, rinumconstr, rtdemo, rtmsgerro, rberro, riCodErro, rtErro
       into nVvc, nTotarea, iNumconstr, tDemo, tRetorno, lErro, iCodErro, tErro
       from fc_iptu_calculavvc_maq_2019( iMatricula, iAnousu, lDemonstrativo,lRaise );
     
     perform fc_debug(\'RETORNO fc_iptu_calculavvc_maq_2019 -> VVC: \'||nVvc||\' - AREA TOTAL: \'||nTotarea||\' - NUMERO DE CONSTRUCOES: \'||iNumconstr||\' - RETORNO: \'||tRetorno||\' - ERRO: \'||lErro, lRaise);
     perform fc_debug(\'\', lRaise);
     
     if lErro is true then
       select fc_iptu_geterro(iCodErro, tErro) into tRetorno;
       return tRetorno;
     end if;
     
     /* BUSCA A ALIQUOTA  */
     
     -- so executar se nao for isento
     perform fc_debug(\'BUSCA A ALIQUOTA DO IPTU \', lRaise);
     
     if iNumconstr is not null and iNumconstr > 0 then
       select fc_iptu_getaliquota_maq_2019(iIdbql,true,lSubRaise) into nAliquota;
     else
       select fc_iptu_getaliquota_maq_2019(iIdbql,false,lSubRaise) into nAliquota;
     end if;
     
     perform fc_debug(\'RETORNO DA BUSCA A ALIQUOTA DO IPTU \', lRaise);
     perform fc_debug(\' \', lRaise);
     
     if not found or nAliquota = 0 then
       select fc_iptu_geterro(13,\' OU NÃO ENCONTRADA\') into tRetorno;
       return tRetorno;
     end if;
   
     /*--------- CALCULA O VALOR VENAL -----------*/
     
     perform fc_debug(\'valor venal construcao (nVvc) - \'||nVvc||\' valor venal terreno (nVvt) - \'||nVvt, lRaise);
     
     nVv    := nVvc + nVvt;
     
     perform fc_debug(\'valor venal total - \'||nVv, lRaise);
     
     nViptu := nVv * ( nAliquota / 100 );
     
     perform fc_debug(\'valor iptu \'||nViptu||\' - aliquota \'||nAliquota||\'%\', lRaise);
     perform fc_debug(\' \', lRaise);
     perform fc_debug(\'Inserindo as receitas de IPTU na tabela tmprecval \', lRaise);
     perform fc_debug(\' \', lRaise);
     perform predial from tmpdadosiptu where predial is true;
   
     if found then
       insert into tmprecval values (rCfiptu.j18_rpredi, nViptu, 1, false);
     else
       insert into tmprecval values (rCfiptu.j18_rterri, nViptu, 1, false);
     end if;
   
     perform fc_debug(\'Inserindo valor e codigo de vencimento do IPTU na tabela tmpdadosiptu\', lRaise);
     perform fc_debug(\' \', lRaise);
   
  end if;
     
  update tmpdadosiptu set viptu = nViptu, codvenc = rCfiptu.j18_vencim;
   
  /*-------------------------------------------*/
  
  select count(*)
    into iParcelas
    from cadvencdesc
         inner join cadvenc on q92_codigo = q82_codigo
   where q92_codigo = rCfiptu.j18_vencim ;
  
  if not found or iParcelas = 0 then
    select fc_iptu_geterro(14,\'\') into tRetorno;
    return tRetorno;
  end if;

  update tmpdadostaxa set anousu = iAnousu, matric = iMatricula, idbql = iIdbql, valiptu = nViptu, zona = iZona,
                             valref = rCfiptu.j18_vlrref, vvt = nVvt, nparc = iParcelas, totareaconst = nTotarea;

  if ( iCaracterCalculo = 1 and iNumconstr is not null and iNumconstr > 0) or iCaracterCalculo = 3 then

     /* caso exista a caracteristica 228 na edificacao, nao devera calcular a taxa de lixo */
     if (iCaracterAnexo = 0 or iCaracterAnexo is null) and (iCaracterTpConstr = 0 or iCaracterTpConstr is null) then

        /* CALCULA AS TAXAS */
        perform fc_debug(\'PARAMETROS fc_iptu_calculataxas  ANOUSU \'||iAnousu||\' -- CODCLI \'||iCodcli, lRaise);
        perform fc_debug(\' \', lRaise);
      
        select fc_iptu_calculataxas(iMatricula,iAnousu,iCodcli,lSubRaise)
          into lTaxasCalculadas;
      
        perform fc_debug(\'RETORNO fc_iptu_calculataxas --->>> TAXASCALCULADAS - \'||lTaxasCalculadas, lRaise);

     end if;
  end if;


  /* MONTA O DEMONSTRATIVO */
  select fc_iptu_demonstrativo(iMatricula,iAnousu,iIdbql,lSubRaise )
    into tDemo;

  /* GERA FINANCEIRO */
  if lDemonstrativo is false then -- Se nao for demonstrativo gera o financeiro, caso contrario retorna o demonstrativo

    select fc_iptu_geradadosiptu(iMatricula,iIdbql,iAnousu,nIsenaliq,lDemonstrativo,lSubRaise)
      into lDadosIptu;

      if lGerafinanc then
        select fc_iptu_gerafinanceiro(iMatricula,iAnousu,iParcelaini,iParcelafim,lCalculogeral,lTempagamento,lNovonumpre,lDemonstrativo,lSubRaise)
          into lFinanceiro;
      end if;
  else
    return tDemo;
  end if;

  if lDemonstrativo is false then

    update iptucalc
       set j23_manual = tDemo
     where j23_matric = iMatricula
       and j23_anousu = iAnousu;
  end if;

  select fc_iptu_geterro(1, \'\') into tRetorno;
  return tRetorno;

end;

$$ LANGUAGE \'plpgsql\';' ,'0' );
               insert into db_sysfuncoesparam( db42_sysfuncoesparam ,db42_funcao ,db42_ordem ,db42_nome ,db42_tipo ,db42_tamanho ,db42_precisao ,db42_valor_default ,db42_descricao ) values ( 1034 ,192 ,1 ,'iMatricula' ,'int4' ,0 ,0 ,'' ,'MATRÍCULA DO IMÓVEL' );
               insert into db_sysfuncoesparam( db42_sysfuncoesparam ,db42_funcao ,db42_ordem ,db42_nome ,db42_tipo ,db42_tamanho ,db42_precisao ,db42_valor_default ,db42_descricao ) values ( 1035 ,192 ,2 ,'iAnousu' ,'int4' ,0 ,0 ,'' ,'ANO DO CÁLCULO' );
               insert into db_sysfuncoesparam( db42_sysfuncoesparam ,db42_funcao ,db42_ordem ,db42_nome ,db42_tipo ,db42_tamanho ,db42_precisao ,db42_valor_default ,db42_descricao ) values ( 1036 ,192 ,3 ,'lGerafinanc' ,'bool' ,0 ,0 ,'' ,'SE GERA FINANCEIRO' );
               insert into db_sysfuncoesparam( db42_sysfuncoesparam ,db42_funcao ,db42_ordem ,db42_nome ,db42_tipo ,db42_tamanho ,db42_precisao ,db42_valor_default ,db42_descricao ) values ( 1037 ,192 ,4 ,'lAtualizaParcela' ,'bool' ,0 ,0 ,'' ,'ATUALIZA PARCELAS' );
               insert into db_sysfuncoesparam( db42_sysfuncoesparam ,db42_funcao ,db42_ordem ,db42_nome ,db42_tipo ,db42_tamanho ,db42_precisao ,db42_valor_default ,db42_descricao ) values ( 1038 ,192 ,5 ,'lNovonumpre' ,'bool' ,0 ,0 ,'' ,'NOVO NUMPRE' );
               insert into db_sysfuncoesparam( db42_sysfuncoesparam ,db42_funcao ,db42_ordem ,db42_nome ,db42_tipo ,db42_tamanho ,db42_precisao ,db42_valor_default ,db42_descricao ) values ( 1039 ,192 ,6 ,'lCalculogeral' ,'bool' ,0 ,0 ,'' ,'SE CALCULO GERAL' );
               insert into db_sysfuncoesparam( db42_sysfuncoesparam ,db42_funcao ,db42_ordem ,db42_nome ,db42_tipo ,db42_tamanho ,db42_precisao ,db42_valor_default ,db42_descricao ) values ( 1040 ,192 ,7 ,'lDemonstrativo' ,'bool' ,0 ,0 ,'' ,'SE É DEMONSTRATIVO' );
               insert into db_sysfuncoesparam( db42_sysfuncoesparam ,db42_funcao ,db42_ordem ,db42_nome ,db42_tipo ,db42_tamanho ,db42_precisao ,db42_valor_default ,db42_descricao ) values ( 1041 ,192 ,8 ,'iParcelaini' ,'int4' ,0 ,0 ,'' ,'PARCELA INICIAL' );
               insert into db_sysfuncoesparam( db42_sysfuncoesparam ,db42_funcao ,db42_ordem ,db42_nome ,db42_tipo ,db42_tamanho ,db42_precisao ,db42_valor_default ,db42_descricao ) values ( 1042 ,192 ,9 ,'iParcelafim' ,'int4' ,0 ,0 ,'' ,'PARCELA FINAL' );


SQL_UP;
        $this->execute($sql);


        $sql = <<<STRING1

CREATE OR REPLACE FUNCTION fc_calculoiptu_maq_2019(integer,integer,boolean,boolean,boolean,boolean,boolean,integer,integer) RETURNS varchar(100) AS
$$

declare

   iMatricula                          alias   for $1;
   iAnousu                             alias   for $2;
   lGerafinanc                         alias   for $3;
   lAtualizaParcela                    alias   for $4;
   lNovonumpre                         alias   for $5;
   lCalculogeral                       alias   for $6;
   lDemonstrativo                      alias   for $7;
   iParcelaini                         alias   for $8;
   iParcelafim                         alias   for $9;

   iIdbql                              integer default 0;
   iNumcgm                             integer default 0;
   iZona                               integer default 0;
   iCodcli                             integer default 0;
   iCodisen                            integer default 0;
   iTipois                             integer default 0;
   iParcelas                           integer default 0;
   iNumconstr                          integer default 0;
   iCaracterCalculo                    integer default 0;
   iCaracterAnexo                      integer default 0;
   iCaracterTpConstr                   integer default 0;
   iCodErro                            integer default 0;

   dDatabaixa                          date;

   nAreal                              numeric default 0;
   nAreac                              numeric default 0;
   nTotarea                            numeric default 0;
   nFracao                             numeric default 0;
   nFracaolote                         numeric default 0;
   nAliquota                           numeric default 0;
   nIsenaliq                           numeric default 0;
   nArealo                             numeric default 0;
   nVvc                                numeric(15,2) default 0;
   nVvt                                numeric(15,2) default 0;
   nVv                                 numeric(15,2) default 0;
   nViptu                              numeric(15,2) default 0;

   tRetorno                            text default '';
   tDemo                               text default '';
   tErro                               text default '';

   lFinanceiro                         boolean;
   lDadosIptu                          boolean;
   lErro                               boolean;
   lIsentaxas                          boolean;
   lTempagamento                       boolean;
   lEmpagamento                        boolean;
   lTaxasCalculadas                    boolean;
   lRaise                              boolean default false; -- true para habilitar raise na funcao principal
   lSubRaise                           boolean default false; -- true para habilitar raise nas sub-funcoes

   rCfiptu                             record;

begin

  lRaise := ( case when fc_getsession('DB_debugon') is null then false else true end );
  lSubRaise := lRaise;

  perform fc_debug('INICIANDO CALCULO',lRaise,true,false);

  perform fc_debug('',lRaise,true,false);

  select j34_zona, j35_caract
    into iZona, iCaracterCalculo
  from iptubase inner join lote     on j34_idbql  = j01_idbql
                inner join carlote  on j35_idbql  = j34_idbql
                inner join caracter on j31_codigo = j35_caract
                                   and j31_grupo  = 1
  where j01_matric = iMatricula;

  if not found then
    select fc_iptu_geterro( 101, ' 1' ) into tRetorno;
    return tRetorno;
  end if;

  select coalesce(j48_caract,0)
    into iCaracterAnexo
  from carconstr inner join caracter on j31_codigo = j48_caract
                 inner join carfator on j74_anousu = iAnousu
                                    and j74_caract = j48_caract
  where j48_matric = iMatricula
  and j31_grupo = 59;


  if found or iCaracterAnexo is not null then
     perform fc_debug('Caracteristica Tipo de Matricula, nao calcula taxa de lixo -> ' || iCaracterAnexo, lRaise);
  end if;


  select coalesce(j48_caract,0)
    into iCaracterTpConstr
  from carconstr inner join caracter on j31_codigo = j48_caract
                 inner join carfator on j74_anousu = iAnousu
                                    and j74_caract = j48_caract
  where j48_matric = iMatricula
  and j31_grupo = 28;


  if found or iCaracterTpConstr is not null then
     perform fc_debug('Caracteristica Tipo Construcao, nao calcula taxa de lixo -> ' || iCaracterTpConstr, lRaise);
  end if;



  /* Verifica a característica do lote, se for (2 - água) não calcula */

  if iCaracterCalculo = 2 then

    select fc_iptu_geterro( 37, '' ) into tRetorno;
    return tRetorno;

  end if;

  /**
   * Executa PRE CALCULO
   */
  select r_iIdbql, r_nAreal, r_nFracao, r_iNumcgm, r_dDatabaixa, r_nFracaolote,
         r_tDemo, r_lTempagamento, r_lEmpagamento, r_iCodisen, r_iTipois, r_nIsenaliq,
         r_lIsentaxas, r_nArealote, r_iCodCli, r_tRetorno

    into iIdbql, nAreal, nFracao, iNumcgm, dDatabaixa, nFracaolote, tDemo, lTempagamento,
         lEmpagamento, iCodisen, iTipois, nIsenaliq, lIsentaxas, nArealo, iCodCli, tRetorno

    from fc_iptu_precalculo( iMatricula, iAnousu, lCalculogeral, lAtualizaParcela, lDemonstrativo, lRaise );

  perform fc_debug(' RETORNO DA PRE CALCULO: ',            lRaise);
  perform fc_debug('  iIdbql        -> ' || iIdbql,        lRaise);
  perform fc_debug('  nAreal        -> ' || nAreal,        lRaise);
  perform fc_debug('  nFracao       -> ' || nFracao,       lRaise);
  perform fc_debug('  iNumcgm       -> ' || iNumcgm,       lRaise);
  perform fc_debug('  dDatabaixa    -> ' || dDatabaixa,    lRaise);
  perform fc_debug('  nFracaolote   -> ' || nFracaolote,   lRaise);
  perform fc_debug('  tDemo         -> ' || tDemo,         lRaise);
  perform fc_debug('  lTempagamento -> ' || lTempagamento, lRaise);
  perform fc_debug('  lEmpagamento  -> ' || lEmpagamento,  lRaise);
  perform fc_debug('  iCodisen      -> ' || iCodisen,      lRaise);
  perform fc_debug('  iTipois       -> ' || iTipois,       lRaise);
  perform fc_debug('  nIsenaliq     -> ' || nIsenaliq,     lRaise);
  perform fc_debug('  lIsentaxas    -> ' || lIsentaxas,    lRaise);
  perform fc_debug('  nArealote     -> ' || nArealo,       lRaise);
  perform fc_debug('  iCodCli       -> ' || iCodCli,       lRaise);
  perform fc_debug('  tRetorno      -> ' || tRetorno,      lRaise);
  perform fc_debug('',lRaise,true,false);

  /**
   * Variavel de retorno contem a msg
   * de erro retornada do pre calculo
   */
  
  if trim(tRetorno) <> '' then
    return tRetorno;
  end if;

  update tmpdadosiptu set matric = iMatricula;

  /**
   * Guarda os parametros do calculo
   */
  
  select * from into rCfiptu cfiptu where j18_anousu = iAnousu;

  if iCaracterCalculo = 1 then

     /**
      * Calcula valor do terreno
      */
     
     perform fc_debug('PARAMETROS fc_iptu_calculavvt_maq_2019 IDBQL: '||iIdbql||' - iMatricula: '||iMatricula||' - Anousu: '||iAnousu||' - FRACAO DO LOTE: '||nFracaolote||' - DEMO: '||lDemonstrativo||' - DEBUG: '||lRaise, lRaise);
   
     select rnvvt, rnarea, rtdemo, rtmsgerro, rberro, riCodErro, rtErro
       into nVvt, nAreac, tDemo, tRetorno, lErro, iCodErro, tErro
       from fc_iptu_calculavvt_maq_2019( iIdbql, iMatricula, iAnousu, nFracaolote, lDemonstrativo, lRaise );
     
     perform fc_debug('RETORNO fc_iptu_calculavvt_maq_2019 -> VVT: '||nVvt||' - AREA CONSTRUIDA: '||nAreac||' - RETORNO: '||tRetorno||' - ERRO: '||lErro, lRaise);
     perform fc_debug('', lRaise);
     
     if lErro is true then
       select fc_iptu_geterro( iCodErro, tErro ) into tRetorno;
       return tRetorno;
     end if;
     
     /**
      * Calcula valor da construcao
      */
     
     perform fc_debug('PARAMETROS fc_iptu_calculavvc_maq_2019 MATRICULA: '||iMatricula||' - ANOUSU: '||iAnousu||' - DEMO: '||lDemonstrativo||' - DEBUG: '||lRaise, lRaise);
     
     select rnvvc, rntotarea, rinumconstr, rtdemo, rtmsgerro, rberro, riCodErro, rtErro
       into nVvc, nTotarea, iNumconstr, tDemo, tRetorno, lErro, iCodErro, tErro
       from fc_iptu_calculavvc_maq_2019( iMatricula, iAnousu, lDemonstrativo,lRaise );
     
     perform fc_debug('RETORNO fc_iptu_calculavvc_maq_2019 -> VVC: '||nVvc||' - AREA TOTAL: '||nTotarea||' - NUMERO DE CONSTRUCOES: '||iNumconstr||' - RETORNO: '||tRetorno||' - ERRO: '||lErro, lRaise);
     perform fc_debug('', lRaise);
     
     if lErro is true then
       select fc_iptu_geterro(iCodErro, tErro) into tRetorno;
       return tRetorno;
     end if;
     
     /* BUSCA A ALIQUOTA  */
     
     -- so executar se nao for isento
     perform fc_debug('BUSCA A ALIQUOTA DO IPTU ', lRaise);
     
     if iNumconstr is not null and iNumconstr > 0 then
       select fc_iptu_getaliquota_maq_2019(iIdbql,true,lSubRaise) into nAliquota;
     else
       select fc_iptu_getaliquota_maq_2019(iIdbql,false,lSubRaise) into nAliquota;
     end if;
     
     perform fc_debug('RETORNO DA BUSCA A ALIQUOTA DO IPTU ', lRaise);
     perform fc_debug(' ', lRaise);
     
     if not found or nAliquota = 0 then
       select fc_iptu_geterro(13,' OU NÃO ENCONTRADA') into tRetorno;
       return tRetorno;
     end if;
   
     /*--------- CALCULA O VALOR VENAL -----------*/
     
     perform fc_debug('valor venal construcao (nVvc) - '||nVvc||' valor venal terreno (nVvt) - '||nVvt, lRaise);
     
     nVv    := nVvc + nVvt;
     
     perform fc_debug('valor venal total - '||nVv, lRaise);
     
     nViptu := nVv * ( nAliquota / 100 );
     
     perform fc_debug('valor iptu '||nViptu||' - aliquota '||nAliquota||'%', lRaise);
     perform fc_debug(' ', lRaise);
     perform fc_debug('Inserindo as receitas de IPTU na tabela tmprecval ', lRaise);
     perform fc_debug(' ', lRaise);
     perform predial from tmpdadosiptu where predial is true;
   
     if found then
       insert into tmprecval values (rCfiptu.j18_rpredi, nViptu, 1, false);
     else
       insert into tmprecval values (rCfiptu.j18_rterri, nViptu, 1, false);
     end if;
   
     perform fc_debug('Inserindo valor e codigo de vencimento do IPTU na tabela tmpdadosiptu', lRaise);
     perform fc_debug(' ', lRaise);
   
  end if;
     
  update tmpdadosiptu set viptu = nViptu, codvenc = rCfiptu.j18_vencim;
   
  /*-------------------------------------------*/
  
  select count(*)
    into iParcelas
    from cadvencdesc
         inner join cadvenc on q92_codigo = q82_codigo
   where q92_codigo = rCfiptu.j18_vencim ;
  
  if not found or iParcelas = 0 then
    select fc_iptu_geterro(14,'') into tRetorno;
    return tRetorno;
  end if;

  update tmpdadostaxa set anousu = iAnousu, matric = iMatricula, idbql = iIdbql, valiptu = nViptu, zona = iZona,
                             valref = rCfiptu.j18_vlrref, vvt = nVvt, nparc = iParcelas, totareaconst = nTotarea;

  if ( iCaracterCalculo = 1 and iNumconstr is not null and iNumconstr > 0) or iCaracterCalculo = 3 then

     /* caso exista a caracteristica 228 na edificacao, nao devera calcular a taxa de lixo */
     if (iCaracterAnexo = 0 or iCaracterAnexo is null) and (iCaracterTpConstr = 0 or iCaracterTpConstr is null) then

        /* CALCULA AS TAXAS */
        perform fc_debug('PARAMETROS fc_iptu_calculataxas  ANOUSU '||iAnousu||' -- CODCLI '||iCodcli, lRaise);
        perform fc_debug(' ', lRaise);
      
        select fc_iptu_calculataxas(iMatricula,iAnousu,iCodcli,lSubRaise)
          into lTaxasCalculadas;
      
        perform fc_debug('RETORNO fc_iptu_calculataxas --->>> TAXASCALCULADAS - '||lTaxasCalculadas, lRaise);

     end if;
  end if;


  /* MONTA O DEMONSTRATIVO */
  select fc_iptu_demonstrativo(iMatricula,iAnousu,iIdbql,lSubRaise )
    into tDemo;

  /* GERA FINANCEIRO */
  if lDemonstrativo is false then -- Se nao for demonstrativo gera o financeiro, caso contrario retorna o demonstrativo

    select fc_iptu_geradadosiptu(iMatricula,iIdbql,iAnousu,nIsenaliq,lDemonstrativo,lSubRaise)
      into lDadosIptu;

      if lGerafinanc then
        select fc_iptu_gerafinanceiro(iMatricula,iAnousu,iParcelaini,iParcelafim,lCalculogeral,lTempagamento,lNovonumpre,lDemonstrativo,lSubRaise)
          into lFinanceiro;
      end if;
  else
    return tDemo;
  end if;

  if lDemonstrativo is false then

    update iptucalc
       set j23_manual = tDemo
     where j23_matric = iMatricula
       and j23_anousu = iAnousu;
  end if;

  select fc_iptu_geterro(1, '') into tRetorno;
  return tRetorno;

end;

$$ LANGUAGE 'plpgsql';
STRING1;

        $this->execute($sql);

        $sql = <<<STRING2

create or replace function fc_iptu_taxalixo_maq_2019(integer, numeric, integer, numeric, boolean) returns boolean as
$$

declare

   iReceita        alias for $1;
   iAliquota       alias for $2;
   iHistCalc       alias for $3;
   iPercIsen       alias for $4;
   lRaise          alias for $5;

   nValTaxa         numeric(15,2) default 0;
   nValTaxaBase     numeric(15,2) default 0;

   iIdbql           integer       default 0;
   iAnousu          integer       default 0;
   iMatric          integer       default 0;
   iMultiplicador   integer       default 0;
   iCaracterCalculo integer       default 0;

   dDataConstr      date;
   dDataBase        date;

   bPredial         boolean       default false;

   tSql             text          default '';
   tRetorno         text          default '';

begin

   perform fc_debug(' <iptu_taxalixo> Calculando taxa de lixo', lRaise);
   perform fc_debug(' ',                                           lRaise);
   perform fc_debug(' <iptu_taxalixo> receita: '   || iReceita,    lRaise);
   perform fc_debug(' <iptu_taxalixo> aliq: '      || iAliquota,   lRaise);
   perform fc_debug(' <iptu_taxalixo> historico: ' || iHistCalc,   lRaise);

   -- busca informacoes cadastrais para o calculo

   select idbql, anousu, matric,
          case when totareaconst > 0 then true
               else false
          end
    into iIdbql, iAnousu, iMatric, bPredial
   from tmpdadostaxa limit 1;

   select j35_caract
     into iCaracterCalculo
   from iptubase inner join lote     on j34_idbql  = j01_idbql
                 inner join carlote  on j35_idbql  = j34_idbql
                 inner join caracter on j31_codigo = j35_caract
                                    and j31_grupo  = 1
   where j01_matric = iMatric;

   if bPredial = false or iCaracterCalculo = 3 then
      perform fc_debug('Verifica se existe caracteristica do grupo 50 para calcular a taxa de lixo para terrenos', lRaise);
   
      select j74_fator
        into nValTaxaBase
      from carlote inner join caracter on j31_codigo = j35_caract
                   inner join carfator on j74_anousu = iAnousu
                                      and j74_caract = j35_caract
      where j35_idbql = iIdbql
        and j31_grupo = 50;
   else
      perform fc_debug('Verifica se existe caracteristica do grupo 60 para calcular a taxa de lixo para predios', lRaise);
   
      select j74_fator
        into nValTaxaBase
      from carconstr inner join caracter on j31_codigo = j48_caract
                     inner join carfator on j74_anousu = iAnousu
                                        and j74_caract = j48_caract
      where j48_matric = iMatric
        and j31_grupo = 60 limit 1;
   end if;

   if nValTaxaBase = 0 or nValTaxaBase is null then
      if bPredial = false or iCaracterCalculo = 3 then
        select fc_iptu_geterro(106,'do grupo 50. Valor zerado ou não informado. Tabela carfator.')
        into tRetorno;
      else
        select fc_iptu_geterro(106,'do grupo 60. Valor zerado ou não informado. Tabela carfator.')
        into tRetorno;
      end if;

      return false;
   end if;

   perform fc_debug('Verifica a data da construção para calcular proporcional a taxa, se necessário.', lRaise);

   dDataBase := (iAnousu||'-01-01')::date;

   select coalesce(j39_dtlan,dDataBase)
      into dDataConstr
   from iptuconstr 
   where j39_matric = iMatric
     and j39_dtdemo is null
   order by j39_dtlan;

   if dDataConstr > dDataBase then

      select count(*) - 1
        into iMultiplicador
      from generate_series(dDataBase,dDataConstr, INTERVAL '1 month');

      nValTaxa := ((nValTaxaBase / 12) * iMultiplicador);

   else

      nValTaxa := nValTaxaBase;

   end if;

   insert into tmptaxapercisen values (iReceita,iPercIsen,0,nValTaxa);
   
   if iPercIsen > 0 then
     nValTaxa := nValTaxa * (100 - iPercIsen) / 100;
   end if;

   perform fc_debug(' <iptu_taxalixo> Percentual Isencao: ' || iPercIsen, lRaise);
   perform fc_debug(' <iptu_taxalixo> Valor final da taxa: ' || nValTaxa, lRaise);

   tSql := 'insert into tmprecval values ('||iReceita||','||nValTaxa||','||iHistCalc||',true)';

   execute tSql;

   return true;

end;

$$ language 'plpgsql';

STRING2;

        $this->execute($sql);


        $sql = <<<STRING3

create or replace function fc_iptu_calculavvc_maq_2019(integer,integer,boolean,boolean) returns tp_iptu_calculavvc as
$$

 declare

     iMatricula       alias for $1;
     iAnousu          alias for $2;
     lMostrademo      alias for $3;
     lRaise           alias for $4;

     nAreatc          numeric default 0;
     nVm2c            numeric default 0;
     nVvcP            numeric default 0;
     nVvc             numeric default 0;

     iPontos          integer default 0;
     iNumerocontr     integer default 0;

     lAtualiza        boolean default true;

     rConstr          record;

     rtp_iptu_calculavvc tp_iptu_calculavvc%ROWTYPE;

 begin
     perform fc_debug('INICIANDO CALCULO VVC ...', lRaise);

     rtp_iptu_calculavvc.rnVvc       := 0;
     rtp_iptu_calculavvc.rnTotarea   := 0;
     rtp_iptu_calculavvc.riNumconstr := 0;
     rtp_iptu_calculavvc.rtDemo      := '';
     rtp_iptu_calculavvc.rtMsgerro   := 'Retorno ok' ;
     rtp_iptu_calculavvc.rbErro      := 'f';
     rtp_iptu_calculavvc.riCodErro   := 0;
     rtp_iptu_calculavvc.rtErro      := '';

     iNumerocontr := 0;

     for rConstr in select * from iptuconstr
                     where j39_matric = iMatricula
                       and j39_dtdemo is null
     loop

       iNumerocontr   := iNumerocontr + 1;

       select j83_pontos
         into iPontos
       from iptuconstrpontos
       where j83_matric = rConstr.j39_matric
         and j83_idcons = rConstr.j39_idcons;

       if iPontos is null or iPontos = 0 then

         rtp_iptu_calculavvc.rbErro    := 't';
         rtp_iptu_calculavvc.riCoderro := 23;
         rtp_iptu_calculavvc.rtErro    := ' PARA A MATRICULA: '||rConstr.j39_matric::text||' IDCONS: '||rConstr.j39_idcons;
         rtp_iptu_calculavvc.rtMsgerro := '';
         return rtp_iptu_calculavvc;
       end if;

       perform fc_debug('iPontos - '||iPontos, lRaise);

       select j71_valor
         into nVm2c
       from iptuconstrpontos inner join carconstr on j48_matric = j83_matric
                                                 and j48_idcons = j83_idcons
                             inner join caracter  on j31_codigo = j48_caract
                             inner join carvalor  on j71_anousu = iAnousu
                                                 and j71_caract = j48_caract
                                                 and j83_pontos between j71_quantini and j71_quantfim
       where j83_matric = rConstr.j39_matric
         and j83_idcons = rConstr.j39_idcons
         and j31_grupo  = 28;

       if nVm2c = 0 or nVm2c is null then

          rtp_iptu_calculavvc.rbErro    := 't';
          rtp_iptu_calculavvc.riCoderro := 104;
          rtp_iptu_calculavvc.rtErro    := ' 28 PARA A MATRICULA: '||rConstr.j39_matric::text||' IDCONS: '||rConstr.j39_idcons||' - VERIFIQUE A TABELA CARVALOR.';
          rtp_iptu_calculavvc.rtMsgerro := '';
          return rtp_iptu_calculavvc;

       end if;

       nAreatc := (nAreatc::numeric + rConstr.j39_area::numeric);

       nVvcP := ( rConstr.j39_area::numeric * nVm2c * (iPontos::numeric / 100) );
       nVvcP := round(nVvcP,2);

       perform fc_debug('Matricula: '||rConstr.j39_matric||'iPontos: '||iPontos||' - Area da construcao: '||rConstr.j39_area||
                        ' - Valor m2 construcao: '||nVm2c||' construcao: '||rConstr.j39_idcons, lRaise);

       perform fc_debug('Valor venal da construcao - nVvcp - '||nVvcp, lRaise);

       nVvc    := round(nVvc + nVvcp,2)::numeric;

       perform fc_debug('Valor venal total parcial - nVvc - '||nVvc, lRaise);

       insert into tmpiptucale (anousu, matric,idcons,areaed,vm2,pontos,valor)
                        values (iAnousu,iMatricula,rConstr.j39_idcons,rConstr.j39_area,nVm2c,iPontos,nVvcp);
       if lAtualiza then
         update tmpdadosiptu set predial = true;
         lAtualiza := false;
       end if;

     end loop;

     perform fc_debug('Valor venal total final - nVvc - '||nVvc, lRaise);

     rtp_iptu_calculavvc.rnVvc       := nVvc::numeric;
     rtp_iptu_calculavvc.rnTotarea   := nAreatc::numeric;
     rtp_iptu_calculavvc.riNumconstr := iNumerocontr;
     rtp_iptu_calculavvc.rtDemo      := '';
     rtp_iptu_calculavvc.rbErro      := 'f';

     update tmpdadosiptu set vvc = rtp_iptu_calculavvc.rnVvc;

     return rtp_iptu_calculavvc;

 end;

$$  language 'plpgsql';

STRING3;

        $this->execute($sql);


        $sql = <<<STRING4

create or replace function fc_iptu_calculavvt_maq_2019(integer,integer,integer,numeric,boolean,boolean) returns tp_iptu_calculavvt as
$$

declare

  iIdbql            alias for $1;
  iMatricula        alias for $2;
  iAnousu           alias for $3;
  nFracao           alias for $4;
  lMostrademo       alias for $5;
  lRaise            alias for $6;

  iZona             integer;

  nVm2t             numeric default 0;
  nAreaLoteCorrigi  numeric default 0;
  nArealote         numeric default 0;
  nValor            numeric default 0;
  nTestada          numeric default 0;

  rCfiptu           record;

  rtp_iptu_calculavvt tp_iptu_calculavvt%ROWTYPE;

begin
 
    rtp_iptu_calculavvt.rnAreaTotalC := 0;
    rtp_iptu_calculavvt.rnArea       := 0;
    rtp_iptu_calculavvt.rnTestada    := 0;
    rtp_iptu_calculavvt.rtDemo       := '';
    rtp_iptu_calculavvt.rtMsgerro    := '';
    rtp_iptu_calculavvt.rbErro       := 'f';
    rtp_iptu_calculavvt.riCoderro    := 0;
    rtp_iptu_calculavvt.rtErro       := '';

    perform fc_debug('INICIANDO CALCULO DO VALOR VENAL TERRITORIAL...', lRaise);

    select j34_zona,
           case when j34_area = 0
             then j34_areal
             else j34_area
           end as nAreal
      into iZona,
           nArealote
      from lote
     where j34_idbql = iIdbql;

    if nArealote is null or nArealote = 0 then
     
        rtp_iptu_calculavvt.rbErro    := 't';
        rtp_iptu_calculavvt.riCoderro := 36;
        rtp_iptu_calculavvt.rtErro    := '';
     
        return rtp_iptu_calculavvt;
    end if;

    select coalesce(j36_testad,0)
      into nTestada
    from testada inner join testpri on j49_idbql  = j36_idbql
                                   and j49_face   = j36_face
                                   and j49_codigo = j36_codigo ;

    select j51_valorm2t
      into nVm2t
      from zonasvalor
     where j51_zona   = iZona
       and j51_anousu = iAnousu;

    perform fc_debug('nVm2t    '||nVm2t, lRaise);
    perform fc_debug('iZona    '||iZona, lRaise);
    perform fc_debug('iAnousu  '||iAnousu, lRaise);

    if nVm2t is null or nVm2t = 0 then

      rtp_iptu_calculavvt.rbErro    := 't';
      rtp_iptu_calculavvt.riCoderro := 7;
      rtp_iptu_calculavvt.rtErro    := iZona::text||' - Tabela: zonasvlor';

      return rtp_iptu_calculavvt;
    end if;

    /*============================================================================================*/

    nAreaLoteCorrigi := ( nArealote * ( nFracao / 100::numeric ))::numeric;

    nValor := round( (nAreaLoteCorrigi * nVm2t)::numeric, 2);

    select * from into rCfiptu cfiptu where j18_anousu = iAnousu;

    rtp_iptu_calculavvt.rnArea       := nArealote;
    rtp_iptu_calculavvt.rnVvt        := nValor;
    rtp_iptu_calculavvt.rnAreaTotalC := nAreaLoteCorrigi;
    rtp_iptu_calculavvt.rnTestada    := nTestada;
    rtp_iptu_calculavvt.rtDemo       := '';
    rtp_iptu_calculavvt.rtMsgerro    := '';
    rtp_iptu_calculavvt.rbErro       := 'f';

    update tmpdadosiptu set vvt = rtp_iptu_calculavvt.rnVvt, vm2t=nVm2t, areat=nAreaLoteCorrigi;

    return rtp_iptu_calculavvt;

end;
$$  language 'plpgsql';

STRING4;

        $this->execute($sql);

        $sql = <<<STRING5

create or replace function fc_iptu_getaliquota_maq_2019(integer,boolean,boolean) returns numeric as
$$


declare

    iIdbql     alias for $1;
    bPredial   alias for $2;
    bRaise     alias for $3;

    rnAliq     numeric default 0;
    nAlipre    numeric default 0;
    nAliter    numeric default 0;

    sPredial   varchar;

begin

  /* EXECUTAR SOMENTE SE NAO TIVER ISENCAO */

  if bPredial is true then
     sPredial = 'PREDIAL';
  else
     sPredial = 'TERRITORIAL';
  end if;

  if bRaise then
     perform fc_debug('DEFININDO QUAL ALIQUOTA APLICAR ...', bRaise);
     perform fc_debug('IPTU '||sPredial, bRaise);
  end if;

  select j30_aliter, j30_alipre
    into nAliter, nAlipre
    from lote
         inner join setor on j34_setor = j30_codi
   where j34_idbql = iIdbql;

 -- criterios para escolha da aliquota

 if bPredial then -- predial
   rnAliq = nAlipre;
 else  -- territorial
   rnAliq = nAliter;
 end if;

 if bRaise then
   raise notice 'aliquota final : %',rnAliq;
 end if;

 execute 'update tmpdadosiptu set aliq = '||rnAliq;

 return rnAliq;
   
end;
$$  language 'plpgsql';

STRING5;

        $this->execute($sql);

    }


    public function down()
    {
        $sql = <<<SQL_DOWN

               delete from db_sysfuncoesparam where db42_funcao between 188 and 192;
               delete from db_sysfuncoes where codfuncao between 188 and 192;
               drop function fc_calculoiptu_maq_2019(integer,integer,boolean,boolean,boolean,boolean,boolean,integer,integer);
               drop function fc_iptu_taxalixo_maq_2019(integer, numeric, integer, numeric, boolean);
               drop function fc_iptu_calculavvc_maq_2019(integer,integer,boolean,boolean);
               drop function fc_iptu_calculavvt_maq_2019(integer,integer,integer,numeric,boolean,boolean);
               drop function fc_iptu_getaliquota_maq_2019(integer,boolean,boolean);

SQL_DOWN;
       $this->execute($sql);
    }

}
