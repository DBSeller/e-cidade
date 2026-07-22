<?php

use Classes\PostgresMigration;

class M16768AjusteQueryCalculaVvtSap2017 extends PostgresMigration
{

    public function up(){

        $sSql = <<<SQL


create or replace function fc_iptu_calculavvt_sap_2017(integer,integer,numeric,numeric,boolean,boolean) returns tp_iptu_calculavvt as
$$

declare

  iIdbql            alias for $1;
  iAnousu           alias for $2;
  nFracao           alias for $3;
  nAreaIsenta       alias for $4;
  lMostrademo       alias for $5;
  lRaise            alias for $6;

  iMatricula        integer;
  iZona             integer;
  iQface            integer;
  iCarAmbas         integer;
  iCarEsq           integer;
  iPavimentacao     integer default 0;
  iIluminacao       integer default 0;
  iCondicaoEdif     integer default 0;

  lAlagado          boolean default false;
  lEncravado        boolean default false;
  lGleba            boolean default false;

  nDescontot        numeric default 0;
  nValDesconto      numeric default 0;
  nProfund          numeric default 0;
  nAreaCorrig       numeric default 0;
  nAreaCalcLote     numeric default 0;

  nTestada          numeric default 0;
  nVm2t             numeric default 0;
  rnAreaTotLote     numeric default 0;
  rnArealote        numeric default 0;
  nValor            numeric default 0;

  rCarlote          record;
  rCarface          record;
  rCfiptu           record;

  rtp_iptu_calculavvt tp_iptu_calculavvt%ROWTYPE;

  bSomaTestadas boolean default false;
  bTestadaPrincipal boolean default false;


  nValorTestada numeric(15, 2) default 0;
  nValorTestadaMI numeric(15, 2) default 0;

begin


    rtp_iptu_calculavvt.rnVvt        := 0;
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
           case when j34_areal = 0
             then j34_area
             else j34_areal
           end as nAreal
      into iZona,
           rnArealote
      from lote
     where j34_idbql = iIdbql;

    select matric
      into iMatricula
      from tmpdadosiptu
      limit 1;

    select j37_face,
           j37_valor,
           case
             when j36_testle = 0
               then j36_testad
             else j36_testle
           end as j36_testle
      into iQface,
           nVm2t,
           nTestada
      from iptuconstr
           inner join testada  on j36_face   = j39_codigo
                              and j36_idbql  = iIdbql
           inner join face     on j37_face   = j36_face
           inner join iptubase on j01_matric = j39_matric
     where j39_matric = iMatricula
       and j39_dtdemo is null
       and j01_baixa is null limit 1;


    if iQface is null then

      select j49_face,
             case
               when j36_testle = 0
                 then j36_testad
                 else j36_testle
             end as j36_testle
        into iQface,
             nTestada
        from testpri
             inner join face    on j49_face = j37_face
             inner join testada on j49_face = j36_face
                               and j49_idbql = j36_idbql
      where j49_idbql = iIdbql;

    end if;

    if iQface is null then

      rtp_iptu_calculavvt.rbErro    := 't';
      rtp_iptu_calculavvt.riCoderro := 6;
      return rtp_iptu_calculavvt;
    end if;


    select j51_valorm2t
      into nVm2t
      from zonasvalor
     where j51_zona   = iZona
       and j51_anousu = iAnousu;

    perform fc_debug('nVm2t    '||nVm2t, lRaise);
    perform fc_debug('iZona    '||iZona, lRaise);
    perform fc_debug('iAnousu  '||iAnousu, lRaise);

    if nVm2t is null then

      rtp_iptu_calculavvt.rbErro    := 't';
      rtp_iptu_calculavvt.riCoderro := 7;
      rtp_iptu_calculavvt.rtErro    := iZona::text;

      return rtp_iptu_calculavvt;
    end if;

-- testar ambas testadas
    select j35_caract
      into iCarAmbas
      from carlote
           inner join caracter on j31_codigo = j35_caract
     where j31_grupo = 47
		   and j35_idbql = iIdbql;

    if iCarAmbas is not null then
      if iCarAmbas = 143 then -- tributar ambas as testadas
        select sum(j36_testle)
          into nTestada
				  from ( select case
					                when j36_testle = 0
													  then j36_testad
														else j36_testle
												end as j36_testle
                   from testada
                  where j36_idbql = iIdbql
				 			 ) as x;
      end if;
    end if;

    if nAreaIsenta > 0 then
      rnArealote := (rnArealote-nAreaIsenta);
    end if;

    nProfund := rnArealote / nTestada;

    nAreaCorrig := sqrt(nProfund);

    perform fc_debug( 'profundidade - '||nProfund||' area corrigida - '||nAreaCorrig||' testada - '||nTestada||' - nVm2t '||nVm2t ,lRaise);

    nAreaCalcLote := nTestada * nAreaCorrig * 5::numeric;
    nAreaCorrig   := nTestada * nAreaCorrig * 5::numeric * nVm2t;

-- verifica caracteristica de esquina

    select j35_caract
      into iCarEsq
      from carlote
           inner join caracter on j31_codigo = j35_caract
     where j31_grupo = 31
		   and j35_idbql = iIdbql;

    if iCarEsq is null then
      rtp_iptu_calculavvt.rbErro    := 't';
      rtp_iptu_calculavvt.riCoderro := 18;

      return rtp_iptu_calculavvt;
    end if;

    if iCarEsq = 100 then -- esquina
      nAreaCorrig    := nAreaCorrig   + ( nAreaCorrig / 3::numeric );
      nAreaCalcLote  := nAreaCalcLote + ( nAreaCalcLote / 3::numeric);
    else
      if iCarEsq = 108 then -- mais de uma esquina
        nAreaCorrig    := nAreaCorrig   + ( nAreaCorrig / 2::numeric );
        nAreaCalcLote  := nAreaCalcLote + ( nAreaCalcLote / 2::numeric);
      end if;
    end if;

		/* REGRAS PARA DESCONTO TERRITORIAL */

--============================================================================================================================================================================
-- COMECO CALCULO DESCONTO


	 /* PROCURA AS CARACTERISTICAS DO LOTE */
    for rCarlote in select j35_caract, j31_grupo, j31_descr, j32_descr
                      from carlote
                           inner join caracter on j31_codigo = j35_caract
                           inner join cargrup  on j32_grupo = j31_grupo
                     where j35_idbql = iIdbql
		loop

-- se alagado
      if rCarlote.j35_caract = 96 then
        lAlagado = true;
      end if;

-- se encravado
      if rCarlote.j35_caract = 98 then
        lEncravado = true;
      end if;

-- se gleba
      if rCarlote.j35_caract = 94 then
        lGleba = true;
      end if;

-- se tipo de pavimentacao
      if rCarlote.j31_grupo = 2 then
        iPavimentacao = rCarlote.j35_caract;
      end if;

-- se tipo de iluminacao
      if rCarlote.j31_grupo = 1 then
        iIluminacao = rCarlote.j35_caract;
      end if;


-- se cond. edificação
      if rCarlote.j31_grupo = 59 then
        iCondicaoEdif = rCarlote.j35_caract;
      end if;

      
      if rCarlote.j35_caract = 143 then
        bSomaTestadas := true;
      end if;

      if rCarlote.j35_caract = 144 then 
        bTestadaPrincipal := true;
      end if;


    end loop;

--=======================================================================================

-- verifica caracteristicas da face de quadra
    for rCarface in select j38_caract, j31_grupo
                      from carface
                           inner join caracter on j31_codigo = j38_caract
                      where j38_face = iQface
		loop

-- se tipo de pavimentacao
      if rCarface.j31_grupo = 37 then
        iPavimentacao = rCarface.j38_caract;
      end if;

-- se tipo de iluminacao
      if rCarface.j31_grupo = 36 and iIluminacao = 0 then
        iIluminacao = rCarface.j38_caract;
      end if;
    end loop;

--=======================================================================================

    perform fc_debug('iPavimentacao '||iPavimentacao, lRaise);
    perform fc_debug('iIluminacao   '||iIluminacao, lRaise);

    if iIluminacao = 119 then
      iIluminacao = 1;
    end if;

    if iPavimentacao = 120 then
      iPavimentacao = 7;
    end if;

    if iPavimentacao = 121 then
      iPavimentacao = 8;
    end if;

-- desconto se rua projetada
    if iPavimentacao = 7 then
      nDescontot = 20::float8;
    end if;

-- desconto se rua sem calcamento
    if iPavimentacao = 8 then
      nDescontot = 10::float8;
    end if;

-- desconto se sem rede eletrica
    if iIluminacao = 1 then
      nDescontot = nDescontot::float8 + 10::float8;
    end if;

-- desconto se alagado
    if lAlagado then
      nDescontot = nDescontot::float8 + 40::float8;
    end if;

-- desconto se encravado
    if lEncravado then
      nDescontot = 50::float8;
    end if;

-- desconto se gleba
    if lGleba then
      nDescontot = 50::float8;
    end if;

-- desconto de imóvel sem condição de edificação
    if iCondicaoEdif = 210  then
      nDescontot = nDescontot::float8  + 40::float8;
    end if;

-- Comentando este select a pedido do cliente para que as matrículas destes loteamentos
-- calcule limpeza de acordo com as características.

		/*============================================================================================*/

    nValor       := ( nAreaCorrig * ( nFracao / 100::numeric ))::numeric;

    perform fc_debug('nValor antes desconto '||nValor, lRaise);

	  nValDesconto := ((nValor * nDescontot)/100)::numeric;

    perform fc_debug('nValDesconto '||nValDesconto, lRaise);

		nValor       := round( (nValor - nValDesconto)::numeric, 2);

    perform fc_debug('nValor depois do desconto '||nValor, lRaise);

		select * from into rCfiptu cfiptu where j18_anousu = iAnousu;

    perform fc_debug('nAreaCalcLote '||nAreaCalcLote|| ' - nFracao '||nFracao, lRaise);

    rnAreaTotLote                 := nAreaCalcLote * (nFracao / 100);
    nValorTestada                 := nTestada;
    rtp_iptu_calculavvt.rnArea    := rnAreaTotLote;
    rtp_iptu_calculavvt.rnVvt     := nValor;
    rtp_iptu_calculavvt.rtDemo    := '';
    rtp_iptu_calculavvt.rtMsgerro := '';
    rtp_iptu_calculavvt.rbErro    := 'f';

    if (bTestadaPrincipal) then
       perform fc_debug('bTestadaPrincipal '||bTestadaPrincipal, lRaise);
 
       select j36_testad 
         into nValorTestada
         from testada  
         where j36_idbql = iIdbql;

       perform fc_debug('nValorTestada '||nValorTestada, lRaise);
    end if;


    if (bSomaTestadas) then
       perform fc_debug('bSomaTestadas '||bSomaTestadas, lRaise);
 
       select (case when bSomaTestadas then sum(j36_testad) else 0 end) as j36_testad
         into nValorTestada
         from testada
       where j36_idbql = iIdbql;
 
       perform fc_debug('nValorTestada '||nValorTestada, lRaise);
    end if;

    update tmpdadosiptu set vvt = rtp_iptu_calculavvt.rnVvt, vm2t=nVm2t, areat=rnAreaTotLote, testada = nValorTestada;
    return rtp_iptu_calculavvt;

end;
$$  language 'plpgsql';


SQL;


        $this->execute( $sSql );

    }


    public function down(){


        $sSql = <<<SQL


create or replace function fc_iptu_calculavvt_sap_2017(integer,integer,numeric,numeric,boolean,boolean) returns tp_iptu_calculavvt as
$$

declare

  iIdbql            alias for $1;
  iAnousu           alias for $2;
  nFracao           alias for $3;
  nAreaIsenta       alias for $4;
  lMostrademo       alias for $5;
  lRaise            alias for $6;

  iMatricula        integer;
  iZona             integer;
  iQface            integer;
  iCarAmbas         integer;
  iCarEsq           integer;
  iPavimentacao     integer default 0;
  iIluminacao       integer default 0;
  iCondicaoEdif     integer default 0;

  lAlagado          boolean default false;
  lEncravado        boolean default false;
  lGleba            boolean default false;

  nDescontot        numeric default 0;
  nValDesconto      numeric default 0;
  nProfund          numeric default 0;
  nAreaCorrig       numeric default 0;
  nAreaCalcLote     numeric default 0;

  nTestada          numeric default 0;
  nVm2t             numeric default 0;
  rnAreaTotLote     numeric default 0;
  rnArealote        numeric default 0;
  nValor            numeric default 0;

  rCarlote          record;
  rCarface          record;
  rCfiptu           record;

  rtp_iptu_calculavvt tp_iptu_calculavvt%ROWTYPE;

  bSomaTestadas boolean default false;
  bTestadaPrincipal boolean default false;


  nValorTestada numeric(15, 2) default 0;
  nValorTestadaMI numeric(15, 2) default 0;

begin


    rtp_iptu_calculavvt.rnVvt        := 0;
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
           case when j34_areal = 0
             then j34_area
             else j34_areal
           end as nAreal
      into iZona,
           rnArealote
      from lote
     where j34_idbql = iIdbql;

    select matric
      into iMatricula
      from tmpdadosiptu
      limit 1;

    select j37_face,
           j37_valor,
           case
             when j36_testle = 0
               then j36_testad
             else j36_testle
           end as j36_testle
      into iQface,
           nVm2t,
           nTestada
      from iptuconstr
           inner join testada  on j36_face   = j39_codigo
                              and j36_idbql  = iIdbql
           inner join face     on j37_face   = j36_face
           inner join iptubase on j01_matric = j39_matric
     where j39_matric = iMatricula
       and j39_dtdemo is null
       and j01_baixa is null limit 1;


    if iQface is null then

      select j49_face,
             case
               when j36_testle = 0
                 then j36_testad
                 else j36_testle
             end as j36_testle
        into iQface,
             nTestada
        from testpri
             inner join face    on j49_face = j37_face
             inner join testada on j49_face = j36_face
                               and j49_idbql = j36_idbql
      where j49_idbql = iIdbql;

    end if;

    if iQface is null then

      rtp_iptu_calculavvt.rbErro    := 't';
      rtp_iptu_calculavvt.riCoderro := 6;
      return rtp_iptu_calculavvt;
    end if;


    select j51_valorm2t
      into nVm2t
      from zonasvalor
     where j51_zona   = iZona
       and j51_anousu = iAnousu;

    perform fc_debug('nVm2t    '||nVm2t, lRaise);
    perform fc_debug('iZona    '||iZona, lRaise);
    perform fc_debug('iAnousu  '||iAnousu, lRaise);

    if nVm2t is null then

      rtp_iptu_calculavvt.rbErro    := 't';
      rtp_iptu_calculavvt.riCoderro := 7;
      rtp_iptu_calculavvt.rtErro    := iZona::text;

      return rtp_iptu_calculavvt;
    end if;

-- testar ambas testadas
    select j35_caract
      into iCarAmbas
      from carlote
           inner join caracter on j31_codigo = j35_caract
     where j31_grupo = 47
		   and j35_idbql = iIdbql;

    if iCarAmbas is not null then
      if iCarAmbas = 143 then -- tributar ambas as testadas
        select sum(j36_testle)
          into nTestada
				  from ( select case
					                when j36_testle = 0
													  then j36_testad
														else j36_testle
												end as j36_testle
                   from testada
                  where j36_idbql = iIdbql
				 			 ) as x;
      end if;
    end if;

    if nAreaIsenta > 0 then
      rnArealote := (rnArealote-nAreaIsenta);
    end if;

    nProfund := rnArealote / nTestada;

    nAreaCorrig := sqrt(nProfund);

    perform fc_debug( 'profundidade - '||nProfund||' area corrigida - '||nAreaCorrig||' testada - '||nTestada||' - nVm2t '||nVm2t ,lRaise);

    nAreaCalcLote := nTestada * nAreaCorrig * 5::numeric;
    nAreaCorrig   := nTestada * nAreaCorrig * 5::numeric * nVm2t;

-- verifica caracteristica de esquina

    select j35_caract
      into iCarEsq
      from carlote
           inner join caracter on j31_codigo = j35_caract
     where j31_grupo = 31
		   and j35_idbql = iIdbql;

    if iCarEsq is null then
      rtp_iptu_calculavvt.rbErro    := 't';
      rtp_iptu_calculavvt.riCoderro := 18;

      return rtp_iptu_calculavvt;
    end if;

    if iCarEsq = 100 then -- esquina
      nAreaCorrig    := nAreaCorrig   + ( nAreaCorrig / 3::numeric );
      nAreaCalcLote  := nAreaCalcLote + ( nAreaCalcLote / 3::numeric);
    else
      if iCarEsq = 108 then -- mais de uma esquina
        nAreaCorrig    := nAreaCorrig   + ( nAreaCorrig / 2::numeric );
        nAreaCalcLote  := nAreaCalcLote + ( nAreaCalcLote / 2::numeric);
      end if;
    end if;

		/* REGRAS PARA DESCONTO TERRITORIAL */

--============================================================================================================================================================================
-- COMECO CALCULO DESCONTO


	 /* PROCURA AS CARACTERISTICAS DO LOTE */
    for rCarlote in select j35_caract, j31_grupo, j31_descr, j32_descr
                      from carlote
                           inner join caracter on j31_codigo = j35_caract
                           inner join cargrup  on j32_grupo = j31_grupo
                     where j35_idbql = iIdbql
		loop

-- se alagado
      if rCarlote.j35_caract = 96 then
        lAlagado = true;
      end if;

-- se encravado
      if rCarlote.j35_caract = 98 then
        lEncravado = true;
      end if;

-- se gleba
      if rCarlote.j35_caract = 94 then
        lGleba = true;
      end if;

-- se tipo de pavimentacao
      if rCarlote.j31_grupo = 2 then
        iPavimentacao = rCarlote.j35_caract;
      end if;

-- se tipo de iluminacao
      if rCarlote.j31_grupo = 1 then
        iIluminacao = rCarlote.j35_caract;
      end if;


-- se cond. edificação
      if rCarlote.j31_grupo = 59 then
        iCondicaoEdif = rCarlote.j35_caract;
      end if;

      
      if rCarlote.j35_caract = 143 then
        bSomaTestadas := true;
      end if;

      if rCarlote.j35_caract = 144 then 
        bTestadaPrincipal := true;
      end if;


    end loop;

--=======================================================================================

-- verifica caracteristicas da face de quadra
    for rCarface in select j38_caract, j31_grupo
                      from carface
                           inner join caracter on j31_codigo = j38_caract
                      where j38_face = iQface
		loop

-- se tipo de pavimentacao
      if rCarface.j31_grupo = 37 then
        iPavimentacao = rCarface.j38_caract;
      end if;

-- se tipo de iluminacao
      if rCarface.j31_grupo = 36 and iIluminacao = 0 then
        iIluminacao = rCarface.j38_caract;
      end if;
    end loop;

--=======================================================================================

    perform fc_debug('iPavimentacao '||iPavimentacao, lRaise);
    perform fc_debug('iIluminacao   '||iIluminacao, lRaise);

    if iIluminacao = 119 then
      iIluminacao = 1;
    end if;

    if iPavimentacao = 120 then
      iPavimentacao = 7;
    end if;

    if iPavimentacao = 121 then
      iPavimentacao = 8;
    end if;

-- desconto se rua projetada
    if iPavimentacao = 7 then
      nDescontot = 20::float8;
    end if;

-- desconto se rua sem calcamento
    if iPavimentacao = 8 then
      nDescontot = 10::float8;
    end if;

-- desconto se sem rede eletrica
    if iIluminacao = 1 then
      nDescontot = nDescontot::float8 + 10::float8;
    end if;

-- desconto se alagado
    if lAlagado then
      nDescontot = nDescontot::float8 + 40::float8;
    end if;

-- desconto se encravado
    if lEncravado then
      nDescontot = 50::float8;
    end if;

-- desconto se gleba
    if lGleba then
      nDescontot = 50::float8;
    end if;

-- desconto de imóvel sem condição de edificação
    if iCondicaoEdif = 210  then
      nDescontot = nDescontot::float8  + 40::float8;
    end if;

-- Comentando este select a pedido do cliente para que as matrículas destes loteamentos
-- calcule limpeza de acordo com as características.

		/*============================================================================================*/

    nValor       := ( nAreaCorrig * ( nFracao / 100::numeric ))::numeric;

    perform fc_debug('nValor antes desconto '||nValor, lRaise);

	  nValDesconto := ((nValor * nDescontot)/100)::numeric;

    perform fc_debug('nValDesconto '||nValDesconto, lRaise);

		nValor       := round( (nValor - nValDesconto)::numeric, 2);

    perform fc_debug('nValor depois do desconto '||nValor, lRaise);

		select * from into rCfiptu cfiptu where j18_anousu = iAnousu;

    perform fc_debug('nAreaCalcLote '||nAreaCalcLote|| ' - nFracao '||nFracao, lRaise);

    rnAreaTotLote                 := nAreaCalcLote * (nFracao / 100);
    nValorTestada                 := nTestada;
    rtp_iptu_calculavvt.rnArea    := rnAreaTotLote;
    rtp_iptu_calculavvt.rnVvt     := nValor;
    rtp_iptu_calculavvt.rtDemo    := '';
    rtp_iptu_calculavvt.rtMsgerro := '';
    rtp_iptu_calculavvt.rbErro    := 'f';

    if (bTestadaPrincipal) then
       perform fc_debug('bTestadaPrincipal '||bTestadaPrincipal, lRaise);
 
       select j36_testad 
         from testada  
         where j36_idbql = iIdbql;

       perform fc_debug('nValorTestada '||nValorTestada, lRaise);
    end if;


    if (bSomaTestadas) then
       perform fc_debug('bSomaTestadas '||bSomaTestadas, lRaise);
 
       select (case when bSomaTestadas then sum(j36_testad) else 0 end) as j36_testad
         into nValorTestada
         from testada
       where j36_idbql = iIdbql;
 
       perform fc_debug('nValorTestada '||nValorTestada, lRaise);
    end if;

    update tmpdadosiptu set vvt = rtp_iptu_calculavvt.rnVvt, vm2t=nVm2t, areat=rnAreaTotLote, testada = nValorTestada;
    return rtp_iptu_calculavvt;

end;
$$  language 'plpgsql';


SQL;


        $this->execute( $sSql );
    }

}
