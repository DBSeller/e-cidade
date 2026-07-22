<?php

use Classes\PostgresMigration;

class M12714 extends PostgresMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function up()
    {

        $sql = <<<SQL
        create or replace function fc_parc_getselectorigens_atjuros(integer,integer,integer) returns varchar as
$$
declare

  iParcelamento      alias for $1;
  iTipo              alias for $2;
  iTipoAnulacao      alias for $3;

  iAnoUsu            integer default 0;

  dDataCorrecao      date;

  sCamposSql         varchar default '';
  sSubsSql           varchar default '';
  sSqlRetorno        varchar default '';
  sSql               varchar default '';
  sCampoInicial      varchar default '';

  lRaise             boolean default false;


begin

  lRaise := ( case when fc_getsession('DB_debugon') is null then false else true end );

  iAnoUsu := cast( (select fc_getsession('DB_anousu')) as integer);
  if iAnoUsu is null then
    raise exception 'ERRO : Variavel de sessao [DB_anousu] nao encontrada.';
  end if;

  dDataCorrecao := cast( (select fc_getsession('DB_datausu')) as date);



  if dDataCorrecao is null then
    raise exception 'ERRO : Variavel de sessao [DB_datausu] nao encontrada.';
  end if;


  perform fc_debug(''                                                      ,lRaise,false,false);
  perform fc_debug('Processando funcao fc_parc_getselectorigens_atjuros...' ,lRaise,false,false);

  sCamposSql := ' distinct
                  k00_numcgm,
                  k00_dtoper,
                  k00_receit,
                  k00_hist,
                  k00_valor,
                  k00_dtvenc,
                  k00_numpre,
                  k00_numpar,
                  k00_numtot,
                  k00_numdig,
                  k00_tipo,
                  k00_tipojm,
                  termo.v07_dtlanc,';

  perform fc_debug('Verificamos a Regra de Anulacao:'                                                                    ,lRaise,false,false);
  perform fc_debug('Regra de Anulacao da Regra de Parcelamento (cadtipoparc:k40_tipoanulacao): '||iTipoAnulacao          ,lRaise,false,false);
  perform fc_debug('Regra de Anulacao 1 ......: Utilizamos o valor do arreold (campo: k00_valor) como corrigido sem aplicar correcao',lRaise,false,false);
  perform fc_debug('Regra de Anulacao 2 e 3 ..: Aplicamos correcao (fc_corre) sobre o valor do arreold (campo: k00_valor)'           ,lRaise,false,false);

  if iTipoAnulacao = 1 then

    sCamposSql := sCamposSql || ' k00_valor as corrigido, \n';
    sCamposSql := sCamposSql || ' 0 as juros,             \n';
    sCamposSql := sCamposSql || ' 0 as multa              \n';

  else

    sSubsSql := '(
                   CASE  (select k02_corven from tabrec inner join tabrecjm on tabrec.k02_codjm = tabrecjm.k02_codjm
                          where k02_codigo = arreold.k00_receit) WHEN true THEN arreold.k00_dtvenc
                      
                   ELSE \''|| dDataCorrecao ||'\'
                        
                   END
                            
                )'; 

    sCamposSql := sCamposSql || ' fc_corre(arreold.k00_receit,arreold.k00_dtoper,arreold.k00_valor,\''||dDataCorrecao||'\','||iAnoUsu||','||sSubsSql||') as corrigido, \n';
    sCamposSql := sCamposSql || ' 0 as juros, \n';
    sCamposSql := sCamposSql || ' 0 as multa  \n';

  end if;

  if iTipo = 1 then

    perform fc_debug('Tipo de Parcelamento 1 - termodiv: '                                             ,lRaise,false,false);
    perform fc_debug('Sql busca os dados da termodiv, divida e arreold'                                ,lRaise,false,false);
    perform fc_debug('Valor corrigido varia de acordo com a Regra de Anulacao explicado anteriormente' ,lRaise,false,false);

    sSqlRetorno :=                ' select '||sCamposSql||                                                                                          '\n';
    sSqlRetorno := sSqlRetorno || '   from termo                                                                                                     \n';
    sSqlRetorno := sSqlRetorno || '        inner join termodiv  on termo.v07_parcel 	= termodiv.parcel                                              \n';
    sSqlRetorno := sSqlRetorno || '        inner join divida    on termodiv.coddiv   	= divida.v01_coddiv                                            \n';
    sSqlRetorno := sSqlRetorno || '        inner join arreold   on arreold.k00_numpre	= divida.v01_numpre and arreold.k00_numpar = divida.v01_numpar \n';
    sSqlRetorno := sSqlRetorno || '  where termo.v07_parcel = ' || iParcelamento ||                                                                 '\n';
    sSqlRetorno := sSqlRetorno || '  order by k00_dtoper,k00_dtvenc,k00_numpre, k00_numpar, k00_receit                                               \n';

  elsif iTipo = 2 then

    perform fc_debug('Tipo de Parcelamento 2 - termoreparc: ' ,lRaise,false,false);
    perform fc_debug('Sql busca os dados da termoreparc, termo, tabelas de origem do termo (termodiv, termoini, termodiver e termocontrib), arreold etc ' ,lRaise,false,false);
    perform fc_debug('Valor corrigido varia de acordo com a Regra de Anulacao explicado anteriormente' ,lRaise,false,false);

    sSqlRetorno := sSqlRetorno || '   select '||sCamposSql||'                                                         \n';
    sSqlRetorno := sSqlRetorno || '     from termoreparc                                                              \n';
    sSqlRetorno := sSqlRetorno || '          inner join termo on v07_parcel            = termoreparc.v08_parcelorigem \n';
    sSqlRetorno := sSqlRetorno || '          inner join arreold on arreold.k00_numpre  = termo.v07_numpre             \n';
    sSqlRetorno := sSqlRetorno || '   where termoreparc.v08_parcel = ' || iParcelamento ||                           '\n';

    sSqlRetorno := sSqlRetorno || ' union all \n';	-- tras os reparcelamentos de divida

    sSqlRetorno := sSqlRetorno || '   select '||sCamposSql||'                                                         \n';
    sSqlRetorno := sSqlRetorno || '     from termoreparc                                                              \n';
    sSqlRetorno := sSqlRetorno || '          inner join termo     on v07_parcel         = termoreparc.v08_parcel      \n';
    sSqlRetorno := sSqlRetorno || '          inner join termodiv  on termo.v07_parcel 	= termodiv.parcel             \n';
    sSqlRetorno := sSqlRetorno || '          inner join divida  	on termodiv.coddiv   	= divida.v01_coddiv           \n';
    sSqlRetorno := sSqlRetorno || '          inner join arreold 	on arreold.k00_numpre	= divida.v01_numpre           \n';
    sSqlRetorno := sSqlRetorno || '                              and arreold.k00_numpar = divida.v01_numpar           \n';
    sSqlRetorno := sSqlRetorno || '   where termoreparc.v08_parcel = ' || iParcelamento ||                           '\n';

  	sSqlRetorno := sSqlRetorno || ' union all \n';	-- tras os reparcelamentos do foro

    sSqlRetorno := sSqlRetorno || '   select '||sCamposSql||                                                               '\n';
    sSqlRetorno := sSqlRetorno || '     from termoreparc                                                                    \n';
    sSqlRetorno := sSqlRetorno || '          inner join termo         on v07_parcel                = termoreparc.v08_parcel \n';
    sSqlRetorno := sSqlRetorno || '          inner join termoini      on termo.v07_parcel 	       = termoini.parcel        \n';
    sSqlRetorno := sSqlRetorno || '          inner join inicialnumpre on inicialnumpre.v59_inicial = termoini.inicial       \n';
    sSqlRetorno := sSqlRetorno || '          inner join divida 	      on inicialnumpre.v59_numpre  = divida.v01_numpre      \n';
    sSqlRetorno := sSqlRetorno || '          inner join arreold 	    on arreold.k00_numpre        = divida.v01_numpre      \n';
	  sSqlRetorno := sSqlRetorno || '                                  and arreold.k00_numpar        = divida.v01_numpar      \n';
    sSqlRetorno := sSqlRetorno || '   where termoreparc.v08_parcel = ' || iParcelamento;

	  sSqlRetorno := sSqlRetorno || ' union all \n';	-- tras os reparcelamentos de diversos

    sSqlRetorno := sSqlRetorno || '   select '||sCamposSql||                                                              '\n';
    sSqlRetorno := sSqlRetorno || '     from termoreparc                                                                   \n';
    sSqlRetorno := sSqlRetorno || '          inner join termo         on v07_parcel             = termoreparc.v08_parcel   \n';
    sSqlRetorno := sSqlRetorno || '          inner join termodiver    on termo.v07_parcel 	 	  = termodiver.dv10_parcel   \n';
    sSqlRetorno := sSqlRetorno || '          inner join diversos      on diversos.dv05_coddiver = termodiver.dv10_coddiver \n';
    sSqlRetorno := sSqlRetorno || '          inner join arreold 	    on arreold.k00_numpre     = diversos.dv05_numpre     \n';
    sSqlRetorno := sSqlRetorno || '   where termoreparc.v08_parcel = ' || iParcelamento ||                                '\n';

	  sSqlRetorno := sSqlRetorno || ' union all \n';	-- tras os reparcelamentos de contribuicao de melhorias

    sSqlRetorno := sSqlRetorno || '   select '||sCamposSql||'                                                                          \n';
    sSqlRetorno := sSqlRetorno || '     from termoreparc                                                                               \n';
    sSqlRetorno := sSqlRetorno || '          inner join termo         on v07_parcel                = termoreparc.v08_parcel            \n';
    sSqlRetorno := sSqlRetorno || '          inner join termocontrib  on termo.v07_parcel          = termocontrib.parcel               \n';
    sSqlRetorno := sSqlRetorno || '          inner join contricalc    on contricalc.d09_sequencial = termocontrib.contricalc           \n';
    sSqlRetorno := sSqlRetorno || '          inner join arreold 	     on arreold.k00_numpre        = contricalc.d09_numpre            \n';
	  sSqlRetorno := sSqlRetorno || '          left  join divold  	     on arreold.k00_numpre        = divold.k10_numpre                \n';
    sSqlRetorno := sSqlRetorno || '                                  and arreold.k00_numpar        = divold.k10_numpar                 \n';
	  sSqlRetorno := sSqlRetorno || '                                  and arreold.k00_receit        = divold.k10_receita                \n';
    sSqlRetorno := sSqlRetorno || '   where ( divold.k10_numpre is null and divold.k10_numpar is null and divold.k10_receita is null ) \n';
	  sSqlRetorno := sSqlRetorno || '     and termoreparc.v08_parcel = ' || iParcelamento ||                                            '\n';
    sSqlRetorno := sSqlRetorno || '   order by k00_dtoper,k00_dtvenc,k00_numpre, k00_numpar, k00_receit                                \n';

  elsif iTipo = 3 then  -- parcelamento de inicial

    perform fc_debug('Tipo de Parcelamento 3 - termoini: '                                                                                      ,lRaise,false,false);
    perform fc_debug('Sql busca os dados da termo, termoini, inicialnumpre, inicialcert, certdiv, divida, arreold, arreoldcalc, certter, termo' ,lRaise,false,false);
    perform fc_debug('Valor corrigido varia de acordo com a Regra de Anulacao explicado anteriormente'                                          ,lRaise,false,false);

    sSqlRetorno :=                '  select '||sCamposSql||', inicial                                                       \n';
    sSqlRetorno := sSqlRetorno || '   from termo                                                                            \n';
    sSqlRetorno := sSqlRetorno || '        inner join termoini    	on termo.v07_parcel 	       = termoini.parcel          \n';
    sSqlRetorno := sSqlRetorno || '        inner join inicialnumpre on inicialnumpre.v59_inicial = termoini.inicial         \n';
  	sSqlRetorno := sSqlRetorno || '        inner join inicialcert   on termoini.inicial          = inicialcert.v51_inicial  \n';
	  sSqlRetorno := sSqlRetorno || '        inner join certdiv       on certdiv.v14_certid        = inicialcert.v51_certidao \n';
    sSqlRetorno := sSqlRetorno || '        inner join divida        on certdiv.v14_coddiv        = divida.v01_coddiv        \n';
    sSqlRetorno := sSqlRetorno || '        inner join arreold 	    on arreold.k00_numpre        = divida.v01_numpre        \n';
    sSqlRetorno := sSqlRetorno || '                               and arreold.k00_numpar         = divida.v01_numpar        \n';
    sSqlRetorno := sSqlRetorno || '  where termo.v07_parcel = ' || iParcelamento ||                                        '\n';
	  sSqlRetorno := sSqlRetorno || '  union                                                                                  \n';
    sSqlRetorno := sSqlRetorno || '   select '||sCamposSql||', inicial                                                      \n';
    sSqlRetorno := sSqlRetorno || '   from termo                                                                            \n';
    sSqlRetorno := sSqlRetorno || '        inner join termoini    	     on termo.v07_parcel 	  = termoini.parcel           \n';
    sSqlRetorno := sSqlRetorno || '        inner join inicialnumpre      on inicialnumpre.v59_inicial = termoini.inicial    \n';
	  sSqlRetorno := sSqlRetorno || '        inner join inicialcert        on termoini.inicial    = inicialcert.v51_inicial   \n';
	  sSqlRetorno := sSqlRetorno || '        inner join certter            on certter.v14_certid  = inicialcert.v51_certidao  \n';
    sSqlRetorno := sSqlRetorno || '        inner join termo termo_origem on termo_origem.v07_parcel = certter.v14_parcel    \n';
    sSqlRetorno := sSqlRetorno || '        inner join arreold 	         on arreold.k00_numpre	= termo_origem.v07_numpre   \n';
    sSqlRetorno := sSqlRetorno || '  where termo.v07_parcel = ' || iParcelamento ||                                        '\n';
    sSqlRetorno := sSqlRetorno || '  order by k00_dtoper,k00_dtvenc,k00_numpre, k00_numpar, k00_receit                      \n';

  elsif iTipo = 4 then -- parcelamento de diveros

    perform fc_debug('Tipo de Parcelamento 4 - termodiver: '                                           ,lRaise,false,false);
    perform fc_debug('Sql busca os dados da termo, termodiver, diversos e arreold'                     ,lRaise,false,false);
    perform fc_debug('Valor corrigido varia de acordo com a Regra de Anulacao explicado anteriormente' ,lRaise,false,false);

    sSqlRetorno :=                '   select '||sCamposSql ||                                                        '\n';
    sSqlRetorno := sSqlRetorno || '   from termo                                                                      \n';
    sSqlRetorno := sSqlRetorno || '        inner join termodiver on termo.v07_parcel       = termodiver.dv10_parcel   \n';
    sSqlRetorno := sSqlRetorno || '        inner join diversos   on diversos.dv05_coddiver = termodiver.dv10_coddiver \n';
    sSqlRetorno := sSqlRetorno || '        inner join arreold    on arreold.k00_numpre 	   = diversos.dv05_numpre     \n';
    sSqlRetorno := sSqlRetorno || '  where termo.v07_parcel = ' || iParcelamento ||                                  '\n';
    sSqlRetorno := sSqlRetorno || '  order by k00_dtoper,k00_dtvenc,k00_numpre, k00_numpar, k00_receit                \n';

  elsif iTipo = 5 then -- parcelamento de contribuicao de melhorias

    perform fc_debug('Tipo de Parcelamento 2 - termocontrib: '                                                             ,lRaise,false,false);
    perform fc_debug('Sql busca os dados da termo, termocontrib, contricalc e arreold, '                                   ,lRaise,false,false);
    perform fc_debug('havendo um left com a divold apenas para garantir que nao virao registros que sao oriundos da divida',lRaise,false,false);
    perform fc_debug('Valor corrigido varia de acordo com a Regra de Anulacao explicado anteriormente'                     ,lRaise,false,false);

    sSqlRetorno :=                '   select '||sCamposSql ||                                                                         '\n';
    sSqlRetorno := sSqlRetorno || '   from termo                                                                                       \n';
    sSqlRetorno := sSqlRetorno || '        inner join termocontrib on termo.v07_parcel          = termocontrib.parcel                  \n';
    sSqlRetorno := sSqlRetorno || '        inner join contricalc   on contricalc.d09_sequencial = termocontrib.contricalc              \n';
    sSqlRetorno := sSqlRetorno || '        inner join arreold	  	 on arreold.k00_numpre        = contricalc.d09_numpre                \n';
		-- left com divold porque o numpre da contricalc pode estar na arreold tanto por parcelamento como por importacao de divida mais como o que interessa e so os
		-- registros referente ao parcelamento dou um left com divold para garantir que nao vira registros que sao oriundos da divida
	  sSqlRetorno := sSqlRetorno || '        left  join divold       on arreold.k00_numpre        = divold.k10_numpre                    \n';
    sSqlRetorno := sSqlRetorno || '                               and arreold.k00_numpar        = divold.k10_numpar                    \n';
    sSqlRetorno := sSqlRetorno || '                               and arreold.k00_receit        = divold.k10_receita                   \n';
    sSqlRetorno := sSqlRetorno || '   where ( divold.k10_numpre is null and divold.k10_numpar is null and divold.k10_receita is null ) \n';
    sSqlRetorno := sSqlRetorno || '     and termo.v07_parcel = ' || iParcelamento ||                                                  '\n';
    sSqlRetorno := sSqlRetorno || '   order by k00_dtoper,k00_dtvenc,k00_numpre, k00_numpar, k00_receit                                \n';

  end if;

  if iTipoAnulacao <> 1 then

    perform fc_debug('Tipo de Anulacao '||iTipoAnulacao||', retornamos o sql com calculo do juro e multa em cima do valor corrigido'  ,lRaise,false,false);

    sSql = sSqlRetorno;

    if iTipo = 3 then -- adiciona o numero da inicial aos campos da query quando parcelamento de inicial
      sCampoInicial := ' , inicial \n';
    end if;

    sSqlRetorno := '';
    sSqlRetorno := sSqlRetorno||'select distinct        \n';
    sSqlRetorno := sSqlRetorno||'       x.k00_numcgm,   \n';
    sSqlRetorno := sSqlRetorno||'       x.k00_dtoper,   \n';
    sSqlRetorno := sSqlRetorno||'       x.k00_receit,   \n';
    sSqlRetorno := sSqlRetorno||'       x.k00_hist,     \n';
    sSqlRetorno := sSqlRetorno||'       x.k00_valor,    \n';
    sSqlRetorno := sSqlRetorno||'       x.k00_dtvenc,   \n';
    sSqlRetorno := sSqlRetorno||'       x.k00_numpre,   \n';
    sSqlRetorno := sSqlRetorno||'       x.k00_numpar,   \n';
    sSqlRetorno := sSqlRetorno||'       x.k00_numtot,   \n';
    sSqlRetorno := sSqlRetorno||'       x.k00_numdig,   \n';
    sSqlRetorno := sSqlRetorno||'       x.k00_tipo,     \n';
    sSqlRetorno := sSqlRetorno||'       x.k00_tipojm,   \n';
    sSqlRetorno := sSqlRetorno||'       x.corrigido,    \n';
    sSqlRetorno := sSqlRetorno||'       ( x.corrigido * coalesce( fc_juros(x.k00_receit,x.k00_dtvenc,\''||dDataCorrecao||'\',\''||dDataCorrecao||'\',false,'||iAnoUsu||'),0)) as juros, \n';
    sSqlRetorno := sSqlRetorno||'       ( x.corrigido * coalesce( fc_multa(x.k00_receit,x.k00_dtvenc,\''||dDataCorrecao||'\',x.k00_dtoper,'||iAnoUsu||'),0)) as multa                   \n';
    sSqlRetorno := sSqlRetorno||'       '||sCampoInicial||' \n';
    sSqlRetorno := sSqlRetorno||'  from ( '||sSql||' ) as x \n';
    sSqlRetorno := sSqlRetorno||' order by k00_dtoper,k00_dtvenc,k00_numpre, k00_numpar, k00_receit\n';

  end if;

  return sSqlRetorno;

end;
$$  language 'plpgsql';

SQL;

$this->execute($sql);
    }

   public function down()
   {
       $sql = <<<SQL
create or replace function fc_parc_getselectorigens_atjuros(integer,integer,integer) returns varchar as
$$
declare

  iParcelamento      alias for $1;
  iTipo              alias for $2;
  iTipoAnulacao      alias for $3;

  iAnoUsu            integer default 0;

  dDataCorrecao      date;

  sCamposSql         varchar default '';
  sSqlRetorno        varchar default '';
  sSql               varchar default '';
  sCampoInicial      varchar default '';

  lRaise             boolean default false;


begin

  lRaise := ( case when fc_getsession('DB_debugon') is null then false else true end );

  iAnoUsu := cast( (select fc_getsession('DB_anousu')) as integer);
  if iAnoUsu is null then
    raise exception 'ERRO : Variavel de sessao [DB_anousu] nao encontrada.';
  end if;

  dDataCorrecao := cast( (select fc_getsession('DB_datausu')) as date);
  if dDataCorrecao is null then
    raise exception 'ERRO : Variavel de sessao [DB_datausu] nao encontrada.';
  end if;

  perform fc_debug(''                                                      ,lRaise,false,false);
  perform fc_debug('Processando funcao fc_parc_getselectorigens_atjuros...' ,lRaise,false,false);

  sCamposSql := ' distinct
                  k00_numcgm,
                  k00_dtoper,
                  k00_receit,
                  k00_hist,
                  k00_valor,
                  k00_dtvenc,
                  k00_numpre,
                  k00_numpar,
                  k00_numtot,
                  k00_numdig,
                  k00_tipo,
                  k00_tipojm,
                  termo.v07_dtlanc,';

  perform fc_debug('Verificamos a Regra de Anulacao:'                                                                    ,lRaise,false,false);
  perform fc_debug('Regra de Anulacao da Regra de Parcelamento (cadtipoparc:k40_tipoanulacao): '||iTipoAnulacao          ,lRaise,false,false);
  perform fc_debug('Regra de Anulacao 1 ......: Utilizamos o valor do arreold (campo: k00_valor) como corrigido sem aplicar correcao',lRaise,false,false);
  perform fc_debug('Regra de Anulacao 2 e 3 ..: Aplicamos correcao (fc_corre) sobre o valor do arreold (campo: k00_valor)'           ,lRaise,false,false);

  if iTipoAnulacao = 1 then

    sCamposSql := sCamposSql || ' k00_valor as corrigido, \n';
    sCamposSql := sCamposSql || ' 0 as juros,             \n';
    sCamposSql := sCamposSql || ' 0 as multa              \n';

  else

    sCamposSql := sCamposSql || ' fc_corre(arreold.k00_receit,arreold.k00_dtoper,arreold.k00_valor,\''||dDataCorrecao||'\','||iAnoUsu||',\''||dDataCorrecao||'\') as corrigido, \n';
    sCamposSql := sCamposSql || ' 0 as juros, \n';
    sCamposSql := sCamposSql || ' 0 as multa  \n';

  end if;

  if iTipo = 1 then

    perform fc_debug('Tipo de Parcelamento 1 - termodiv: '                                             ,lRaise,false,false);
    perform fc_debug('Sql busca os dados da termodiv, divida e arreold'                                ,lRaise,false,false);
    perform fc_debug('Valor corrigido varia de acordo com a Regra de Anulacao explicado anteriormente' ,lRaise,false,false);

    sSqlRetorno :=                ' select '||sCamposSql||                                                                                          '\n';
    sSqlRetorno := sSqlRetorno || '   from termo                                                                                                     \n';
    sSqlRetorno := sSqlRetorno || '        inner join termodiv  on termo.v07_parcel 	= termodiv.parcel                                              \n';
    sSqlRetorno := sSqlRetorno || '        inner join divida    on termodiv.coddiv   	= divida.v01_coddiv                                            \n';
    sSqlRetorno := sSqlRetorno || '        inner join arreold   on arreold.k00_numpre	= divida.v01_numpre and arreold.k00_numpar = divida.v01_numpar \n';
    sSqlRetorno := sSqlRetorno || '  where termo.v07_parcel = ' || iParcelamento ||                                                                 '\n';
    sSqlRetorno := sSqlRetorno || '  order by k00_dtoper,k00_dtvenc,k00_numpre, k00_numpar, k00_receit                                               \n';

  elsif iTipo = 2 then

    perform fc_debug('Tipo de Parcelamento 2 - termoreparc: ' ,lRaise,false,false);
    perform fc_debug('Sql busca os dados da termoreparc, termo, tabelas de origem do termo (termodiv, termoini, termodiver e termocontrib), arreold etc ' ,lRaise,false,false);
    perform fc_debug('Valor corrigido varia de acordo com a Regra de Anulacao explicado anteriormente' ,lRaise,false,false);

    sSqlRetorno := sSqlRetorno || '   select '||sCamposSql||'                                                         \n';
    sSqlRetorno := sSqlRetorno || '     from termoreparc                                                              \n';
    sSqlRetorno := sSqlRetorno || '          inner join termo on v07_parcel            = termoreparc.v08_parcelorigem \n';
    sSqlRetorno := sSqlRetorno || '          inner join arreold on arreold.k00_numpre  = termo.v07_numpre             \n';
    sSqlRetorno := sSqlRetorno || '   where termoreparc.v08_parcel = ' || iParcelamento ||                           '\n';

    sSqlRetorno := sSqlRetorno || ' union all \n';	-- tras os reparcelamentos de divida

    sSqlRetorno := sSqlRetorno || '   select '||sCamposSql||'                                                         \n';
    sSqlRetorno := sSqlRetorno || '     from termoreparc                                                              \n';
    sSqlRetorno := sSqlRetorno || '          inner join termo     on v07_parcel         = termoreparc.v08_parcel      \n';
    sSqlRetorno := sSqlRetorno || '          inner join termodiv  on termo.v07_parcel 	= termodiv.parcel             \n';
    sSqlRetorno := sSqlRetorno || '          inner join divida  	on termodiv.coddiv   	= divida.v01_coddiv           \n';
    sSqlRetorno := sSqlRetorno || '          inner join arreold 	on arreold.k00_numpre	= divida.v01_numpre           \n';
    sSqlRetorno := sSqlRetorno || '                              and arreold.k00_numpar = divida.v01_numpar           \n';
    sSqlRetorno := sSqlRetorno || '   where termoreparc.v08_parcel = ' || iParcelamento ||                           '\n';

  	sSqlRetorno := sSqlRetorno || ' union all \n';	-- tras os reparcelamentos do foro

    sSqlRetorno := sSqlRetorno || '   select '||sCamposSql||                                                               '\n';
    sSqlRetorno := sSqlRetorno || '     from termoreparc                                                                    \n';
    sSqlRetorno := sSqlRetorno || '          inner join termo         on v07_parcel                = termoreparc.v08_parcel \n';
    sSqlRetorno := sSqlRetorno || '          inner join termoini      on termo.v07_parcel 	       = termoini.parcel        \n';
    sSqlRetorno := sSqlRetorno || '          inner join inicialnumpre on inicialnumpre.v59_inicial = termoini.inicial       \n';
    sSqlRetorno := sSqlRetorno || '          inner join divida 	      on inicialnumpre.v59_numpre  = divida.v01_numpre      \n';
    sSqlRetorno := sSqlRetorno || '          inner join arreold 	    on arreold.k00_numpre        = divida.v01_numpre      \n';
	  sSqlRetorno := sSqlRetorno || '                                  and arreold.k00_numpar        = divida.v01_numpar      \n';
    sSqlRetorno := sSqlRetorno || '   where termoreparc.v08_parcel = ' || iParcelamento;

	  sSqlRetorno := sSqlRetorno || ' union all \n';	-- tras os reparcelamentos de diversos

    sSqlRetorno := sSqlRetorno || '   select '||sCamposSql||                                                              '\n';
    sSqlRetorno := sSqlRetorno || '     from termoreparc                                                                   \n';
    sSqlRetorno := sSqlRetorno || '          inner join termo         on v07_parcel             = termoreparc.v08_parcel   \n';
    sSqlRetorno := sSqlRetorno || '          inner join termodiver    on termo.v07_parcel 	 	  = termodiver.dv10_parcel   \n';
    sSqlRetorno := sSqlRetorno || '          inner join diversos      on diversos.dv05_coddiver = termodiver.dv10_coddiver \n';
    sSqlRetorno := sSqlRetorno || '          inner join arreold 	    on arreold.k00_numpre     = diversos.dv05_numpre     \n';
    sSqlRetorno := sSqlRetorno || '   where termoreparc.v08_parcel = ' || iParcelamento ||                                '\n';

	  sSqlRetorno := sSqlRetorno || ' union all \n';	-- tras os reparcelamentos de contribuicao de melhorias

    sSqlRetorno := sSqlRetorno || '   select '||sCamposSql||'                                                                          \n';
    sSqlRetorno := sSqlRetorno || '     from termoreparc                                                                               \n';
    sSqlRetorno := sSqlRetorno || '          inner join termo         on v07_parcel                = termoreparc.v08_parcel            \n';
    sSqlRetorno := sSqlRetorno || '          inner join termocontrib  on termo.v07_parcel          = termocontrib.parcel               \n';
    sSqlRetorno := sSqlRetorno || '          inner join contricalc    on contricalc.d09_sequencial = termocontrib.contricalc           \n';
    sSqlRetorno := sSqlRetorno || '          inner join arreold 	     on arreold.k00_numpre        = contricalc.d09_numpre            \n';
	  sSqlRetorno := sSqlRetorno || '          left  join divold  	     on arreold.k00_numpre        = divold.k10_numpre                \n';
    sSqlRetorno := sSqlRetorno || '                                  and arreold.k00_numpar        = divold.k10_numpar                 \n';
	  sSqlRetorno := sSqlRetorno || '                                  and arreold.k00_receit        = divold.k10_receita                \n';
    sSqlRetorno := sSqlRetorno || '   where ( divold.k10_numpre is null and divold.k10_numpar is null and divold.k10_receita is null ) \n';
	  sSqlRetorno := sSqlRetorno || '     and termoreparc.v08_parcel = ' || iParcelamento ||                                            '\n';
    sSqlRetorno := sSqlRetorno || '   order by k00_dtoper,k00_dtvenc,k00_numpre, k00_numpar, k00_receit                                \n';

  elsif iTipo = 3 then  -- parcelamento de inicial

    perform fc_debug('Tipo de Parcelamento 3 - termoini: '                                                                                      ,lRaise,false,false);
    perform fc_debug('Sql busca os dados da termo, termoini, inicialnumpre, inicialcert, certdiv, divida, arreold, arreoldcalc, certter, termo' ,lRaise,false,false);
    perform fc_debug('Valor corrigido varia de acordo com a Regra de Anulacao explicado anteriormente'                                          ,lRaise,false,false);

    sSqlRetorno :=                '  select '||sCamposSql||', inicial                                                       \n';
    sSqlRetorno := sSqlRetorno || '   from termo                                                                            \n';
    sSqlRetorno := sSqlRetorno || '        inner join termoini    	on termo.v07_parcel 	       = termoini.parcel          \n';
    sSqlRetorno := sSqlRetorno || '        inner join inicialnumpre on inicialnumpre.v59_inicial = termoini.inicial         \n';
  	sSqlRetorno := sSqlRetorno || '        inner join inicialcert   on termoini.inicial          = inicialcert.v51_inicial  \n';
	  sSqlRetorno := sSqlRetorno || '        inner join certdiv       on certdiv.v14_certid        = inicialcert.v51_certidao \n';
    sSqlRetorno := sSqlRetorno || '        inner join divida        on certdiv.v14_coddiv        = divida.v01_coddiv        \n';
    sSqlRetorno := sSqlRetorno || '        inner join arreold 	    on arreold.k00_numpre        = divida.v01_numpre        \n';
    sSqlRetorno := sSqlRetorno || '                               and arreold.k00_numpar         = divida.v01_numpar        \n';
    sSqlRetorno := sSqlRetorno || '  where termo.v07_parcel = ' || iParcelamento ||                                        '\n';
	  sSqlRetorno := sSqlRetorno || '  union                                                                                  \n';
    sSqlRetorno := sSqlRetorno || '   select '||sCamposSql||', inicial                                                      \n';
    sSqlRetorno := sSqlRetorno || '   from termo                                                                            \n';
    sSqlRetorno := sSqlRetorno || '        inner join termoini    	     on termo.v07_parcel 	  = termoini.parcel           \n';
    sSqlRetorno := sSqlRetorno || '        inner join inicialnumpre      on inicialnumpre.v59_inicial = termoini.inicial    \n';
	  sSqlRetorno := sSqlRetorno || '        inner join inicialcert        on termoini.inicial    = inicialcert.v51_inicial   \n';
	  sSqlRetorno := sSqlRetorno || '        inner join certter            on certter.v14_certid  = inicialcert.v51_certidao  \n';
    sSqlRetorno := sSqlRetorno || '        inner join termo termo_origem on termo_origem.v07_parcel = certter.v14_parcel    \n';
    sSqlRetorno := sSqlRetorno || '        inner join arreold 	         on arreold.k00_numpre	= termo_origem.v07_numpre   \n';
    sSqlRetorno := sSqlRetorno || '  where termo.v07_parcel = ' || iParcelamento ||                                        '\n';
    sSqlRetorno := sSqlRetorno || '  order by k00_dtoper,k00_dtvenc,k00_numpre, k00_numpar, k00_receit                      \n';

  elsif iTipo = 4 then -- parcelamento de diveros

    perform fc_debug('Tipo de Parcelamento 4 - termodiver: '                                           ,lRaise,false,false);
    perform fc_debug('Sql busca os dados da termo, termodiver, diversos e arreold'                     ,lRaise,false,false);
    perform fc_debug('Valor corrigido varia de acordo com a Regra de Anulacao explicado anteriormente' ,lRaise,false,false);

    sSqlRetorno :=                '   select '||sCamposSql ||                                                        '\n';
    sSqlRetorno := sSqlRetorno || '   from termo                                                                      \n';
    sSqlRetorno := sSqlRetorno || '        inner join termodiver on termo.v07_parcel       = termodiver.dv10_parcel   \n';
    sSqlRetorno := sSqlRetorno || '        inner join diversos   on diversos.dv05_coddiver = termodiver.dv10_coddiver \n';
    sSqlRetorno := sSqlRetorno || '        inner join arreold    on arreold.k00_numpre 	   = diversos.dv05_numpre     \n';
    sSqlRetorno := sSqlRetorno || '  where termo.v07_parcel = ' || iParcelamento ||                                  '\n';
    sSqlRetorno := sSqlRetorno || '  order by k00_dtoper,k00_dtvenc,k00_numpre, k00_numpar, k00_receit                \n';

  elsif iTipo = 5 then -- parcelamento de contribuicao de melhorias

    perform fc_debug('Tipo de Parcelamento 2 - termocontrib: '                                                             ,lRaise,false,false);
    perform fc_debug('Sql busca os dados da termo, termocontrib, contricalc e arreold, '                                   ,lRaise,false,false);
    perform fc_debug('havendo um left com a divold apenas para garantir que nao virao registros que sao oriundos da divida',lRaise,false,false);
    perform fc_debug('Valor corrigido varia de acordo com a Regra de Anulacao explicado anteriormente'                     ,lRaise,false,false);

    sSqlRetorno :=                '   select '||sCamposSql ||                                                                         '\n';
    sSqlRetorno := sSqlRetorno || '   from termo                                                                                       \n';
    sSqlRetorno := sSqlRetorno || '        inner join termocontrib on termo.v07_parcel          = termocontrib.parcel                  \n';
    sSqlRetorno := sSqlRetorno || '        inner join contricalc   on contricalc.d09_sequencial = termocontrib.contricalc              \n';
    sSqlRetorno := sSqlRetorno || '        inner join arreold	  	 on arreold.k00_numpre        = contricalc.d09_numpre                \n';
		-- left com divold porque o numpre da contricalc pode estar na arreold tanto por parcelamento como por importacao de divida mais como o que interessa e so os
		-- registros referente ao parcelamento dou um left com divold para garantir que nao vira registros que sao oriundos da divida
	  sSqlRetorno := sSqlRetorno || '        left  join divold       on arreold.k00_numpre        = divold.k10_numpre                    \n';
    sSqlRetorno := sSqlRetorno || '                               and arreold.k00_numpar        = divold.k10_numpar                    \n';
    sSqlRetorno := sSqlRetorno || '                               and arreold.k00_receit        = divold.k10_receita                   \n';
    sSqlRetorno := sSqlRetorno || '   where ( divold.k10_numpre is null and divold.k10_numpar is null and divold.k10_receita is null ) \n';
    sSqlRetorno := sSqlRetorno || '     and termo.v07_parcel = ' || iParcelamento ||                                                  '\n';
    sSqlRetorno := sSqlRetorno || '   order by k00_dtoper,k00_dtvenc,k00_numpre, k00_numpar, k00_receit                                \n';

  end if;

  if iTipoAnulacao <> 1 then

    perform fc_debug('Tipo de Anulacao '||iTipoAnulacao||', retornamos o sql com calculo do juro e multa em cima do valor corrigido'  ,lRaise,false,false);

    sSql = sSqlRetorno;

    if iTipo = 3 then -- adiciona o numero da inicial aos campos da query quando parcelamento de inicial
      sCampoInicial := ' , inicial \n';
    end if;

    sSqlRetorno := '';
    sSqlRetorno := sSqlRetorno||'select distinct        \n';
    sSqlRetorno := sSqlRetorno||'       x.k00_numcgm,   \n';
    sSqlRetorno := sSqlRetorno||'       x.k00_dtoper,   \n';
    sSqlRetorno := sSqlRetorno||'       x.k00_receit,   \n';
    sSqlRetorno := sSqlRetorno||'       x.k00_hist,     \n';
    sSqlRetorno := sSqlRetorno||'       x.k00_valor,    \n';
    sSqlRetorno := sSqlRetorno||'       x.k00_dtvenc,   \n';
    sSqlRetorno := sSqlRetorno||'       x.k00_numpre,   \n';
    sSqlRetorno := sSqlRetorno||'       x.k00_numpar,   \n';
    sSqlRetorno := sSqlRetorno||'       x.k00_numtot,   \n';
    sSqlRetorno := sSqlRetorno||'       x.k00_numdig,   \n';
    sSqlRetorno := sSqlRetorno||'       x.k00_tipo,     \n';
    sSqlRetorno := sSqlRetorno||'       x.k00_tipojm,   \n';
    sSqlRetorno := sSqlRetorno||'       x.corrigido,    \n';
    sSqlRetorno := sSqlRetorno||'       ( x.corrigido * coalesce( fc_juros(x.k00_receit,x.k00_dtvenc,\''||dDataCorrecao||'\',\''||dDataCorrecao||'\',false,'||iAnoUsu||'),0)) as juros, \n';
    sSqlRetorno := sSqlRetorno||'       ( x.corrigido * coalesce( fc_multa(x.k00_receit,x.k00_dtvenc,\''||dDataCorrecao||'\',x.k00_dtoper,'||iAnoUsu||'),0)) as multa                   \n';
    sSqlRetorno := sSqlRetorno||'       '||sCampoInicial||' \n';
    sSqlRetorno := sSqlRetorno||'  from ( '||sSql||' ) as x \n';
    sSqlRetorno := sSqlRetorno||' order by k00_dtoper,k00_dtvenc,k00_numpre, k00_numpar, k00_receit\n';

  end if;

  return sSqlRetorno;

end;
$$  language 'plpgsql';
SQL;



       $this->execute($sql);
   }
}
