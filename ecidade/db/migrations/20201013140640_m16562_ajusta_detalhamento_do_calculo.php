<?php

use Classes\PostgresMigration;

class M16562AjustaDetalhamentoDoCalculo extends PostgresMigration
{
    public function up()
    {
        $this->upPlIptuGeraDados();
        $this->upPlIptuCalculavvtSap();
    }

    public function down()
    {
        $this->downPlIptuGeraDados();
        $this->downPlIptuCalculavvtSap();
    }

    public function upPlIptuGeraDados()
    {
        $sql = <<<SQL
create or replace function fc_iptu_geradadosiptu(integer,integer,integer,numeric,boolean,boolean) returns boolean as
$$
declare

    iMatricula          alias for $1;
    iIdbql              alias for $2;
    iAnousu             alias for $3;
    nAliqIsen           alias for $4;
    bRaise              alias for $6;

    nTestada            numeric       default 0;
    nBase               numeric(15,2) default 0;

    nViptu              numeric(15,2) default 0;
    nVlrisen            numeric(15,2) default 0;
    nAreatrib           numeric       default 0;
    nAreal              numeric       default 0;

    iReciptu            integer;
    iVencim             integer;
    iHistiptu           integer;
    iHistIsenIptu       integer;
    iHistIsenI          integer;
    iTipoIsen           integer;
    iEspReciptu         integer;
    iTipoCalculo        integer;

    dDtoper             date;
    bErro               boolean;
    bIsenTaxas          boolean;
    lTemDigitacaoManual boolean;
    tSql                text    default '';

    rDadosIptu          record;
    rIptucale           record;
    rIptucalv           record;
    rIptuHistorico      record;

    lRaise              boolean;

begin

  lRaise              := bRaise;
  lTemDigitacaoManual := fc_getsession('DB_iptumanual');

  perform fc_debug(' <iptu_geradadosiptu> Gerando Dados Iptu', lRaise);
  perform fc_debug(' <iptu_geradadosiptu> nAliqIsen: ' || nAliqIsen, lRaise);

  iTipoCalculo := 1;
  if lTemDigitacaoManual is not null and lTemDigitacaoManual is true then
    iTipoCalculo := 2;
  end if;

  select tipoisen, isentaxas
    into iTipoIsen, bIsenTaxas
    from tmpdadosiptu;

  /**
   * Insere os dados na iptucale, dados q foram manipulados na tmpiptucale durante o calculo
   */
  insert into iptucale
       select anousu, matric, idcons, round(areaed, 2),
              round(vm2, 2), pontos, round(valor, 2)
         from tmpiptucale;

  select * into rDadosIptu from tmpdadosiptu;

  select sum(areaed) as areaed
    into rIptucale
    from tmpiptucale;

    /**
     * Grava os dados do iptu na iptucalc, iptucalv(onde fica os dados referente aos valores)
     */
   select case when j36_testle = 0
               then j36_testad
               else j36_testle end as j36_testle,
          case when j34_areal  = 0
               then j34_area
              else j34_areal   end as j34_areal
     into nTestada, nAreal
     from testpri
          inner join lote    on j34_idbql = j49_idbql
          inner join face    on j49_face  = j37_face
          inner join testada on j49_face  = j36_face
                            and j49_idbql = j36_idbql
    where j49_idbql = iIdbql;

    select case when rDadosIptu.predial is false
						    then j18_rterri
							  else j18_rpredi
					 end,
					 j18_vencim,
					 j18_dtoper,
					 j18_vlrref,
					 j18_iptuhistisen
      into iReciptu, iVencim, dDtoper, nBase, iHistIsenIptu
      from cfiptu
     where j18_anousu = iAnousu;

   /**
    *  Calcula a area tributada
    */
   begin

     /**
      * Verifica se tem receita especifica por matricula pre-configurada
      * troca a receita default(cfiptu) pela receita especifica( iptucalcconfrec)
      */
     select j23_recdst
       into iEspReciptu
       from iptucalcconfrec
      where j23_matric = iMatricula
        and j23_anousu = iAnousu
        and j23_recorg = iReciptu
        and j23_tipo   = 1;

     if found then

       perform fc_debug(' <iptu_geradadosiptu> Alterando receita: ' || iReciptu || ' por receita especifica: ' || iEspReciptu, lRaise);

       /**
        * Troca a receita da tmprec para seguir a mesma logica na hora de gerar o financeiro
        */
       update tmprecval
          set receita = iEspReciptu
        where receita = iReciptu
          and taxa is false;

       update tmptaxapercisen
          set rectaxaisen = iEspReciptu
        where rectaxaisen = iReciptu;

       iReciptu := iEspReciptu;

     end if;

   exception

     when undefined_table then
     when others then
   end;

  perform *
  from db_plugin
  where db145_nome = 'calculo-de-iptu-proporcional'
    and db145_situacao is true;

  -- Caso o plugin de cÃ¡lculo de IPTU proporcional esteja instalado e ativo
  -- efetua o cÃ¡lculo de forma proporcional de acordo com as mudanÃ§as nas construÃ§Ãµes
  if found then
    select sum(z.valor) as areaed into rIptuHistorico
    from (select
              case when x.areadohistorico > 0 then
                       areadohistorico
                   else
                       areaprincipal
                  end as valor
          from (
               select j39_idcons,
                      (
                          select sum(area) as areadohistorico from plugins.iptuconstrareahistorico
                          where plugins.iptuconstrareahistorico.matricula = iMatricula
                            and plugins.iptuconstrareahistorico.datainicio <= (iAnousu||'-'||'01'||'-01')::date
                            and (iAnousu||'-'||'01'||'-01')::date <= plugins.iptuconstrareahistorico.data
                            and plugins.iptuconstrareahistorico.id_constr = j39_idcons
                      ),
                      (
                          select sum(j22_areaed) as areaprincipal from iptucale
                          where iptucale.j22_anousu = iAnousu
                            and iptucale.j22_matric = iMatricula
                            and iptucale.j22_idcons = j39_idcons
                      )
               from iptuconstr
               where j39_matric = iMatricula
                 and (iptuconstr.j39_dtdemo > (iAnousu||'-'||'01'||'-01')::date or iptuconstr.j39_dtdemo is null)
          ) as x
    ) as z;

      if rIptuHistorico.areaed is not null then
          rIptucale.areaed := rIptuHistorico.areaed;
      end if;
  end if;

  perform fc_debug(' <iptu_geradadosiptu> WHUIDHASD!! UI!: ' || rIptucale.areaed, lRaise);
   nAreatrib := rIptucale.areaed * (rDadosIptu.fracao / 100);

   perform fc_debug(' <iptu_geradadosiptu> Area tributada: ' || coalesce( nAreatrib, 0 ), lRaise);

   insert into iptucalc
               ( j23_anousu,
                 j23_matric,
                 j23_testad,
                 j23_arealo,
                 j23_areafr,
                 j23_areaed,
                 j23_m2terr,
                 j23_vlrter,
                 j23_aliq  ,
                 j23_vlrisen,
                 j23_tipoim,
                 j23_manual,
                 j23_tipocalculo )
        values ( iAnousu,
                 iMatricula,
                 round(case when rDadosIptu.testada is null or rDadosIptu.testada = 0 then nTestada else rDadosIptu.testada end,2),
                 round(rDadosIptu.areat, 2),
                 round(rDadosIptu.fracao,2),
                 round(rIptucale.areaed, 2),
                 round(rDadosIptu.vm2t,  2),
                 round(rDadosIptu.vvt,   2),
                 round(rDadosIptu.aliq,  2),
                 round(nVlrisen,         2),
                 (case when rDadosIptu.predial is true then 'P' else 'T' end),
                 '',
                 iTipoCalculo ) ;

    /**
     * Incluindo com taxa false
     */
    for rIptucalv in select *
                       from tmprecval
                            left join tmptaxapercisen on tmprecval.receita = tmptaxapercisen.rectaxaisen
                      where taxa is false
    loop

      perform fc_debug(' <iptu_geradadosiptu> Receita: '           || iReciptu, lRaise);
      perform fc_debug(' <iptu_geradadosiptu> Valor: '             || coalesce( round(rIptucalv.valor,2), 0 ), lRaise);
      perform fc_debug(' <iptu_geradadosiptu> Historico: '         || rIptucalv.hist, lRaise);
      perform fc_debug(' <iptu_geradadosiptu> Historico Insecao: ' || rIptucalv.histcalcisen, lRaise);

      if rIptucalv.hist = 1 then

        iHistiptu  := 1;
        iHistIsenI := iHistIsenIptu;
      else

        iHistiptu  := rIptucalv.hist;
        iHistIsenI := rIptucalv.histcalcisen;
      end if;

      if rIptucalv.valor > 0 then

        insert into iptucalv ( j21_anousu,
                               j21_matric,
                               j21_receit,
                               j21_valor,
                               j21_quant,
                               j21_codhis )
                      values ( iAnousu,
                               iMatricula,
                               iReciptu,
                               round(rIptucalv.valor, 2),
                               0,
                               iHistiptu );
      end if;

      if iTipoIsen = 1 and rIptucalv.valor <> 0 then

         nVlrisen  := rIptucalv.valor * ( 100 / 100);
         perform fc_debug(' <iptu_geradadosiptu> Valor da Isencao: ' || coalesce( nVlrisen, 0 ), lRaise);

         insert into iptucalv ( j21_anousu, j21_matric, j21_receit, j21_valor, j21_quant, j21_codhis )
                       values ( iAnousu, iMatricula, iReciptu, round( ( nVlrisen *-1),2) , 0, iHistIsenI );

      elsif nAliqIsen is not null and nAliqIsen > 0 then

         nVlrisen  := rIptucalv.valor * ( nAliqIsen / 100);
         perform fc_debug(' <iptu_geradadosiptu> Valor da Isencao (Utilizando Aliquota): ' || coalesce( nVlrisen, 0 ), lRaise);

         insert into iptucalv ( j21_anousu, j21_matric, j21_receit, j21_valor, j21_quant, j21_codhis )
                       values ( iAnousu, iMatricula, iReciptu,  round( ( nVlrisen *-1),2) , 0, iHistIsenI );
      end if;

    end loop;

    for rIptucalv in select *
                       from tmprecval
                            inner join tmptaxapercisen on tmprecval.receita = tmptaxapercisen.rectaxaisen
                      where taxa is true
    loop

      perform fc_debug(' <iptu_geradadosiptu> Receita Isencao de Taxa: '    || rIptucalv.rectaxaisen, lRaise);
      perform fc_debug(' <iptu_geradadosiptu> Percentual Isencao de Taxa: ' || rIptucalv.percisen,    lRaise);
      perform fc_debug(' <iptu_geradadosiptu> Taxa: '                        || rIptucalv.taxa,        lRaise);

      /**
       * Grava o valor da isencao na iptucalv
       */
      if rIptucalv.rectaxaisen is not null then

        if rIptucalv.histcalcisen is not null then

          perform fc_debug(' <iptu_geradadosiptu> Incluindo valores', lRaise);

          if rIptucalv.valsemisen <> 0 then

          insert into iptucalv (j21_anousu, j21_matric, j21_receit, j21_valor, j21_quant, j21_codhis)
               values (iAnousu, iMatricula, rIptucalv.receita, round( rIptucalv.valsemisen, 2), 0, rIptucalv.hist);

          end if;

          if rIptucalv.percisen > 0 then

            perform fc_debug(' <iptu_geradadosiptu> Incluindo valor de isencao', lRaise);

            insert into iptucalv (j21_anousu, j21_matric, j21_receit, j21_valor, j21_quant, j21_codhis)
                 values (iAnousu, iMatricula, rIptucalv.receita, round( ((rIptucalv.valsemisen * rIptucalv.percisen) / 100),2) * -1, 0, rIptucalv.histcalcisen);

				  end if;

          update tmprecval
             set valor = ( select round(sum(coalesce(j21_valor, 0)), 2)
                             from iptucalv
                            where j21_matric = iMatricula
                              and j21_receit = receita
                              and j21_anousu = iAnousu )
           where receita = receita;

			  end if;

      end if;

    end loop;

    perform fc_debug(' <iptu_geradadosiptu> Valor Iptu SEM isencao' || rDadosIptu.viptu, lRaise);

    nViptu := rDadosIptu.viptu - ( rDadosIptu.viptu * ( nAliqIsen / 100) );

    perform fc_debug(' <iptu_geradadosiptu> Valor Iptu:'          || nViptu,   lRaise);
    perform fc_debug(' <iptu_geradadosiptu> Valor Iptu isencao: ' || nVlrisen, lRaise);

     update tmpdadosiptu set viptu = nViptu;
     update tmprecval    set valor = nViptu where taxa is false and hist = 1 ;

    return true;

end;
$$  language 'plpgsql';

SQL;

    $this->execute($sql);
    }

    public function upPlIptuCalculavvtSap()
    {
        $sql = <<<SQL
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

    perform fc_debug( 'profundidade - '||nProfund||' area corrigida - '||nAreaCorrig||' testada - '||nTestada ,lRaise);

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

	  nValDesconto := ((nValor * nDescontot)/100)::numeric;
		nValor       := round( (nValor - nValDesconto)::numeric, 2);


		select * from into rCfiptu cfiptu where j18_anousu = iAnousu;

    rnAreaTotLote                 := nAreaCalcLote * (nFracao / 100);
    rtp_iptu_calculavvt.rnArea    := rnAreaTotLote;
    rtp_iptu_calculavvt.rnVvt     := nValor;
    rtp_iptu_calculavvt.rtDemo    := '';
    rtp_iptu_calculavvt.rtMsgerro := '';
    rtp_iptu_calculavvt.rbErro    := 'f';

    if (bTestadaPrincipal) then 
      select j36_testad 
        from testada  
        where j36_idbql = iIdbql;
      update tmpdadosiptu set vvt = rtp_iptu_calculavvt.rnVvt, vm2t=nVm2t, areat=rnAreaTotLote, testada = j36_testad;
    end if;


    if (bSomaTestadas) then 
      select (case when bSomaTestadas then sum(j36_testad) else 0 end) as j36_testad
        into nValorTestada
        from testada
      where j36_idbql = iIdbql;

      update tmpdadosiptu set vvt = rtp_iptu_calculavvt.rnVvt, vm2t=nVm2t, areat=rnAreaTotLote, testada = nValorTestada;
    end if;

    return rtp_iptu_calculavvt;

end;
$$  language 'plpgsql';
SQL;

    $this->execute($sql);
    }

    public function downPlIptuCalculavvtSap()
    {
        $sql = <<<SQL
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

    perform fc_debug( 'profundidade - '||nProfund||' area corrigida - '||nAreaCorrig||' testada - '||nTestada ,lRaise);

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

	  nValDesconto := ((nValor * nDescontot)/100)::numeric;
		nValor       := round( (nValor - nValDesconto)::numeric, 2);


		select * from into rCfiptu cfiptu where j18_anousu = iAnousu;

    rnAreaTotLote                 := nAreaCalcLote * (nFracao / 100);
    rtp_iptu_calculavvt.rnArea    := rnAreaTotLote;
    rtp_iptu_calculavvt.rnVvt     := nValor;
    rtp_iptu_calculavvt.rtDemo    := '';
    rtp_iptu_calculavvt.rtMsgerro := '';
    rtp_iptu_calculavvt.rbErro    := 'f';

    update tmpdadosiptu set vvt = rtp_iptu_calculavvt.rnVvt, vm2t=nVm2t, areat=rnAreaTotLote;

    return rtp_iptu_calculavvt;

end;
$$  language 'plpgsql';

SQL;
    $this->execute($sql);
    }

    public function downPlIptuGeraDados()
    {
        $sql = <<<SQL
create or replace function fc_iptu_geradadosiptu(integer,integer,integer,numeric,boolean,boolean) returns boolean as
$$
declare

    iMatricula          alias for $1;
    iIdbql              alias for $2;
    iAnousu             alias for $3;
    nAliqIsen           alias for $4;
    bRaise              alias for $6;

    nTestada            numeric       default 0;
    nBase               numeric(15,2) default 0;

    nViptu              numeric(15,2) default 0;
    nVlrisen            numeric(15,2) default 0;
    nAreatrib           numeric       default 0;
    nAreal              numeric       default 0;

    iReciptu            integer;
    iVencim             integer;
    iHistiptu           integer;
    iHistIsenIptu       integer;
    iHistIsenI          integer;
    iTipoIsen           integer;
    iEspReciptu         integer;
    iTipoCalculo        integer;

    dDtoper             date;
    bErro               boolean;
    bIsenTaxas          boolean;
    lTemDigitacaoManual boolean;
    tSql                text    default '';

    rDadosIptu          record;
    rIptucale           record;
    rIptucalv           record;
    rIptuHistorico      record;

    lRaise              boolean;

begin

  lRaise              := bRaise;
  lTemDigitacaoManual := fc_getsession('DB_iptumanual');

  perform fc_debug(' <iptu_geradadosiptu> Gerando Dados Iptu', lRaise);
  perform fc_debug(' <iptu_geradadosiptu> nAliqIsen: ' || nAliqIsen, lRaise);

  iTipoCalculo := 1;
  if lTemDigitacaoManual is not null and lTemDigitacaoManual is true then
    iTipoCalculo := 2;
  end if;

  select tipoisen, isentaxas
    into iTipoIsen, bIsenTaxas
    from tmpdadosiptu;

  /**
   * Insere os dados na iptucale, dados q foram manipulados na tmpiptucale durante o calculo
   */
  insert into iptucale
       select anousu, matric, idcons, round(areaed, 2),
              round(vm2, 2), pontos, round(valor, 2)
         from tmpiptucale;

  select * into rDadosIptu from tmpdadosiptu;

  select sum(areaed) as areaed
    into rIptucale
    from tmpiptucale;

    /**
     * Grava os dados do iptu na iptucalc, iptucalv(onde fica os dados referente aos valores)
     */
   select case when j36_testle = 0
               then j36_testad
               else j36_testle end as j36_testle,
          case when j34_areal  = 0
               then j34_area
              else j34_areal   end as j34_areal
     into nTestada, nAreal
     from testpri
          inner join lote    on j34_idbql = j49_idbql
          inner join face    on j49_face  = j37_face
          inner join testada on j49_face  = j36_face
                            and j49_idbql = j36_idbql
    where j49_idbql = iIdbql;

    select case when rDadosIptu.predial is false
						    then j18_rterri
							  else j18_rpredi
					 end,
					 j18_vencim,
					 j18_dtoper,
					 j18_vlrref,
					 j18_iptuhistisen
      into iReciptu, iVencim, dDtoper, nBase, iHistIsenIptu
      from cfiptu
     where j18_anousu = iAnousu;

   /**
    *  Calcula a area tributada
    */
   begin

     /**
      * Verifica se tem receita especifica por matricula pre-configurada
      * troca a receita default(cfiptu) pela receita especifica( iptucalcconfrec)
      */
     select j23_recdst
       into iEspReciptu
       from iptucalcconfrec
      where j23_matric = iMatricula
        and j23_anousu = iAnousu
        and j23_recorg = iReciptu
        and j23_tipo   = 1;

     if found then

       perform fc_debug(' <iptu_geradadosiptu> Alterando receita: ' || iReciptu || ' por receita especifica: ' || iEspReciptu, lRaise);

       /**
        * Troca a receita da tmprec para seguir a mesma logica na hora de gerar o financeiro
        */
       update tmprecval
          set receita = iEspReciptu
        where receita = iReciptu
          and taxa is false;

       update tmptaxapercisen
          set rectaxaisen = iEspReciptu
        where rectaxaisen = iReciptu;

       iReciptu := iEspReciptu;

     end if;

   exception

     when undefined_table then
     when others then
   end;

  perform *
  from db_plugin
  where db145_nome = 'calculo-de-iptu-proporcional'
    and db145_situacao is true;

  -- Caso o plugin de cÃ¡lculo de IPTU proporcional esteja instalado e ativo
  -- efetua o cÃ¡lculo de forma proporcional de acordo com as mudanÃ§as nas construÃ§Ãµes
  if found then
    select sum(z.valor) as areaed into rIptuHistorico
    from (select
              case when x.areadohistorico > 0 then
                       areadohistorico
                   else
                       areaprincipal
                  end as valor
          from (
               select j39_idcons,
                      (
                          select sum(area) as areadohistorico from plugins.iptuconstrareahistorico
                          where plugins.iptuconstrareahistorico.matricula = iMatricula
                            and plugins.iptuconstrareahistorico.datainicio <= (iAnousu||'-'||'01'||'-01')::date
                            and (iAnousu||'-'||'01'||'-01')::date <= plugins.iptuconstrareahistorico.data
                            and plugins.iptuconstrareahistorico.id_constr = j39_idcons
                      ),
                      (
                          select sum(j22_areaed) as areaprincipal from iptucale
                          where iptucale.j22_anousu = iAnousu
                            and iptucale.j22_matric = iMatricula
                            and iptucale.j22_idcons = j39_idcons
                      )
               from iptuconstr
               where j39_matric = iMatricula
                 and (iptuconstr.j39_dtdemo > (iAnousu||'-'||'01'||'-01')::date or iptuconstr.j39_dtdemo is null)
          ) as x
    ) as z;

      if rIptuHistorico.areaed is not null then
          rIptucale.areaed := rIptuHistorico.areaed;
      end if;
  end if;

  perform fc_debug(' <iptu_geradadosiptu> WHUIDHASD!! UI!: ' || rIptucale.areaed, lRaise);
   nAreatrib := rIptucale.areaed * (rDadosIptu.fracao / 100);

   perform fc_debug(' <iptu_geradadosiptu> Area tributada: ' || coalesce( nAreatrib, 0 ), lRaise);

   insert into iptucalc
               ( j23_anousu,
                 j23_matric,
                 j23_testad,
                 j23_arealo,
                 j23_areafr,
                 j23_areaed,
                 j23_m2terr,
                 j23_vlrter,
                 j23_aliq  ,
                 j23_vlrisen,
                 j23_tipoim,
                 j23_manual,
                 j23_tipocalculo )
        values ( iAnousu,
                 iMatricula,
                 round(nTestada,         2),
                 round(rDadosIptu.areat, 2),
                 round(rDadosIptu.fracao,2),
                 round(rIptucale.areaed, 2),
                 round(rDadosIptu.vm2t,  2),
                 round(rDadosIptu.vvt,   2),
                 round(rDadosIptu.aliq,  2),
                 round(nVlrisen,         2),
                 (case when rDadosIptu.predial is true then 'P' else 'T' end),
                 '',
                 iTipoCalculo ) ;

    /**
     * Incluindo com taxa false
     */
    for rIptucalv in select *
                       from tmprecval
                            left join tmptaxapercisen on tmprecval.receita = tmptaxapercisen.rectaxaisen
                      where taxa is false
    loop

      perform fc_debug(' <iptu_geradadosiptu> Receita: '           || iReciptu, lRaise);
      perform fc_debug(' <iptu_geradadosiptu> Valor: '             || coalesce( round(rIptucalv.valor,2), 0 ), lRaise);
      perform fc_debug(' <iptu_geradadosiptu> Historico: '         || rIptucalv.hist, lRaise);
      perform fc_debug(' <iptu_geradadosiptu> Historico Insecao: ' || rIptucalv.histcalcisen, lRaise);

      if rIptucalv.hist = 1 then

        iHistiptu  := 1;
        iHistIsenI := iHistIsenIptu;
      else

        iHistiptu  := rIptucalv.hist;
        iHistIsenI := rIptucalv.histcalcisen;
      end if;

      if rIptucalv.valor > 0 then

        insert into iptucalv ( j21_anousu,
                               j21_matric,
                               j21_receit,
                               j21_valor,
                               j21_quant,
                               j21_codhis )
                      values ( iAnousu,
                               iMatricula,
                               iReciptu,
                               round(rIptucalv.valor, 2),
                               0,
                               iHistiptu );
      end if;

      if iTipoIsen = 1 and rIptucalv.valor <> 0 then

         nVlrisen  := rIptucalv.valor * ( 100 / 100);
         perform fc_debug(' <iptu_geradadosiptu> Valor da Isencao: ' || coalesce( nVlrisen, 0 ), lRaise);

         insert into iptucalv ( j21_anousu, j21_matric, j21_receit, j21_valor, j21_quant, j21_codhis )
                       values ( iAnousu, iMatricula, iReciptu, round( ( nVlrisen *-1),2) , 0, iHistIsenI );

      elsif nAliqIsen is not null and nAliqIsen > 0 then

         nVlrisen  := rIptucalv.valor * ( nAliqIsen / 100);
         perform fc_debug(' <iptu_geradadosiptu> Valor da Isencao (Utilizando Aliquota): ' || coalesce( nVlrisen, 0 ), lRaise);

         insert into iptucalv ( j21_anousu, j21_matric, j21_receit, j21_valor, j21_quant, j21_codhis )
                       values ( iAnousu, iMatricula, iReciptu,  round( ( nVlrisen *-1),2) , 0, iHistIsenI );
      end if;

    end loop;

    for rIptucalv in select *
                       from tmprecval
                            inner join tmptaxapercisen on tmprecval.receita = tmptaxapercisen.rectaxaisen
                      where taxa is true
    loop

      perform fc_debug(' <iptu_geradadosiptu> Receita Isencao de Taxa: '    || rIptucalv.rectaxaisen, lRaise);
      perform fc_debug(' <iptu_geradadosiptu> Percentual Isencao de Taxa: ' || rIptucalv.percisen,    lRaise);
      perform fc_debug(' <iptu_geradadosiptu> Taxa: '                        || rIptucalv.taxa,        lRaise);

      /**
       * Grava o valor da isencao na iptucalv
       */
      if rIptucalv.rectaxaisen is not null then

        if rIptucalv.histcalcisen is not null then

          perform fc_debug(' <iptu_geradadosiptu> Incluindo valores', lRaise);

          if rIptucalv.valsemisen <> 0 then

          insert into iptucalv (j21_anousu, j21_matric, j21_receit, j21_valor, j21_quant, j21_codhis)
               values (iAnousu, iMatricula, rIptucalv.receita, round( rIptucalv.valsemisen, 2), 0, rIptucalv.hist);

          end if;

          if rIptucalv.percisen > 0 then

            perform fc_debug(' <iptu_geradadosiptu> Incluindo valor de isencao', lRaise);

            insert into iptucalv (j21_anousu, j21_matric, j21_receit, j21_valor, j21_quant, j21_codhis)
                 values (iAnousu, iMatricula, rIptucalv.receita, round( ((rIptucalv.valsemisen * rIptucalv.percisen) / 100),2) * -1, 0, rIptucalv.histcalcisen);

				  end if;

          update tmprecval
             set valor = ( select round(sum(coalesce(j21_valor, 0)), 2)
                             from iptucalv
                            where j21_matric = iMatricula
                              and j21_receit = receita
                              and j21_anousu = iAnousu )
           where receita = receita;

			  end if;

      end if;

    end loop;

    perform fc_debug(' <iptu_geradadosiptu> Valor Iptu SEM isencao' || rDadosIptu.viptu, lRaise);

    nViptu := rDadosIptu.viptu - ( rDadosIptu.viptu * ( nAliqIsen / 100) );

    perform fc_debug(' <iptu_geradadosiptu> Valor Iptu:'          || nViptu,   lRaise);
    perform fc_debug(' <iptu_geradadosiptu> Valor Iptu isencao: ' || nVlrisen, lRaise);

     update tmpdadosiptu set viptu = nViptu;
     update tmprecval    set valor = nViptu where taxa is false and hist = 1 ;

    return true;

end;
$$  language 'plpgsql';

SQL;
    $this->execute($sql);
    }
}
