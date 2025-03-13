<?php

use Classes\PostgresMigration;

class M16369ParcelamentoDiversosArreoldcalc extends PostgresMigration
{
    public function up()
    {
        $this->execute("
        create or replace function fc_parc_getselectorigens_jurori(integer,integer,integer) returns varchar as
        $$
        declare
        
        iParcelamento      alias for $1;
        iTipo              alias for $2;
        iTipoAnulacao      alias for $3;
        
        iAnoUsu            integer default 0;

        dDataCorrecao      date;
        
        sSqlRetorno     varchar default '';
        
        lRaise          boolean default false;
        
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
        perform fc_debug('Processando funcao fc_parc_getselectorigens_jurori...' ,lRaise,false,false);
        
        if iTipo = 1 then
        
        
            perform fc_debug('Tipo de Parcelamento 1 - termodiv: ' ,lRaise,false,false);
            perform fc_debug('Sql busca os dados da termodiv, divida e arreold' ,lRaise,false,false);
            perform fc_debug('Nao eh realizado nenhum calculo aplicando correcao ou juro e multa sobre os valores'                                                   ,lRaise,false,false);
            perform fc_debug('Sao retornados como valor corrigido, juro e multa, respectivamente os campos,vlrcor,juros e multa da tabela termodiv' ,lRaise,false,false);    
            
            sSqlRetorno :=                ' select k00_numcgm,                                                                                               \n'; 
            sSqlRetorno := sSqlRetorno || '        k00_dtoper,                                                                                               \n';
            sSqlRetorno := sSqlRetorno || '        k00_receit,                                                                                               \n';
            sSqlRetorno := sSqlRetorno || '        k00_hist,                                                                                                 \n';
            sSqlRetorno := sSqlRetorno || '        k00_valor,                                                                                                \n';
            sSqlRetorno := sSqlRetorno || '        k00_dtvenc,                                                                                               \n';
            sSqlRetorno := sSqlRetorno || '        k00_numpre,                                                                                               \n';
            sSqlRetorno := sSqlRetorno || '        k00_numpar,                                                                                               \n';
            sSqlRetorno := sSqlRetorno || '        k00_numtot,                                                                                               \n';
            sSqlRetorno := sSqlRetorno || '        k00_numdig,                                                                                               \n';
            sSqlRetorno := sSqlRetorno || '        k00_tipo,                                                                                                 \n';
            sSqlRetorno := sSqlRetorno || '        k00_tipojm,                                                                                               \n';
            sSqlRetorno := sSqlRetorno || '        v01_exerc,                                                                                                \n';
            sSqlRetorno := sSqlRetorno || '        v01_coddiv,                                                                                               \n';
            sSqlRetorno := sSqlRetorno || '        termodiv.vlrcor as corrigido,                                                                             \n';
            sSqlRetorno := sSqlRetorno || '        termodiv.juros  as juros,                                                                                 \n';
            sSqlRetorno := sSqlRetorno || '        termodiv.multa  as multa                                                                                  \n';
            sSqlRetorno := sSqlRetorno || '   from termo                                                                                                     \n';
            sSqlRetorno := sSqlRetorno || '        inner join termodiv  on termo.v07_parcel 	= termodiv.parcel                                              \n';
            sSqlRetorno := sSqlRetorno || '        inner join divida    on termodiv.coddiv   	= divida.v01_coddiv                                            \n';
            sSqlRetorno := sSqlRetorno || '        inner join arreold   on arreold.k00_numpre	= divida.v01_numpre and arreold.k00_numpar = divida.v01_numpar \n';
            sSqlRetorno := sSqlRetorno || '  where termo.v07_parcel = ' || iParcelamento||'                                                                  \n';
            sSqlRetorno := sSqlRetorno || '  order by v01_exerc,k00_dtvenc,k00_receit                                                                        \n';
            
        elsif iTipo = 2 then

            perform fc_debug('Tipo de Parcelamento 2 - termoreparc: '                                                                               ,lRaise,false,false);
            perform fc_debug('Sql busca os dados da termoreparc, termo, tabelas de origem do termo (termodiv, termoini, termodiver e termocontrib), arreold etc' ,lRaise,false,false);  
            perform fc_debug('=> Reparcelamento de reparcelamento .............................................:'                                   ,lRaise,false,false); 
            perform fc_debug('     Utilizamos o campo k00_valor do arreold e aplicamos a correcao (fc_corre)'                                       ,lRaise,false,false);
            perform fc_debug('=> Reparcelamento de parcelamento de Divida, Inicial, Diversos ou Contribuicao ..:'                                   ,lRaise,false,false); 
            perform fc_debug('     Utilizamos os campos vlrcor, juros e multa (tabelas termodiv, termoini, termodiver, termocontrib)'               ,lRaise,false,false);
            perform fc_debug('     *Quando reparcelamento de divida, calculamos o juro (fc_juros) e multa (fc_multa) sobre o valor corrigido '      ,lRaise,false,false);
            perform fc_debug('      e desconsideramos os valores dos campos juros e multa da tabela termodiv, nos demais casos, juro e multa '      ,lRaise,false,false);
            perform fc_debug('      ficam com o valor dos campos juro e multa '                                                                     ,lRaise,false,false);
            
            sSqlRetorno :=                '  select x.k00_numcgm,                                                           \n';      
            sSqlRetorno := sSqlRetorno || '         x.k00_dtoper,                                                           \n';      
            sSqlRetorno := sSqlRetorno || '         x.k00_receit,                                                           \n';      
            sSqlRetorno := sSqlRetorno || '         x.k00_hist,                                                             \n';      
            sSqlRetorno := sSqlRetorno || '         x.k00_valor,                                                            \n';      
            sSqlRetorno := sSqlRetorno || '         x.k00_dtvenc,                                                           \n';      
            sSqlRetorno := sSqlRetorno || '         x.k00_numpre,                                                           \n';      
            sSqlRetorno := sSqlRetorno || '         x.k00_numpar,                                                           \n';      
            sSqlRetorno := sSqlRetorno || '         x.k00_numtot,                                                           \n';      
            sSqlRetorno := sSqlRetorno || '         x.k00_numdig,                                                           \n';      
            sSqlRetorno := sSqlRetorno || '         x.k00_tipo,                                                             \n';      
            sSqlRetorno := sSqlRetorno || '         x.k00_tipojm,                                                           \n';      
            sSqlRetorno := sSqlRetorno || '         x.v07_dtlanc,                                                           \n';      
            sSqlRetorno := sSqlRetorno || '         x.corrigido,                                                            \n';
            sSqlRetorno := sSqlRetorno || '         case                                                                    \n';
            sSqlRetorno := sSqlRetorno || '           when x.tp = 1                                                         \n'; 
            sSqlRetorno := sSqlRetorno || '            then                                                                 \n';
            sSqlRetorno := sSqlRetorno || '             ( x.corrigido * coalesce( fc_juros(x.k00_receit,x.k00_dtvenc,x.v07_dtlanc,x.v07_dtlanc,false,  cast( extract(year from x.v07_dtlanc) as integer)),0)) \n'; 
            sSqlRetorno := sSqlRetorno || '            else                                                                 \n';
            sSqlRetorno := sSqlRetorno || '             x.juros                                                             \n';
            sSqlRetorno := sSqlRetorno || '         end as juros,                                                           \n';
            sSqlRetorno := sSqlRetorno || '         case                                                                    \n';
            sSqlRetorno := sSqlRetorno || '           when x.tp = 1                                                         \n'; 
            sSqlRetorno := sSqlRetorno || '            then                                                                 \n';
            sSqlRetorno := sSqlRetorno || '             ( x.corrigido * coalesce( fc_multa(x.k00_receit,x.k00_dtvenc,x.v07_dtlanc,x.k00_dtoper,cast( extract(year from x.v07_dtlanc) as integer)),0)) \n'; 
            sSqlRetorno := sSqlRetorno || '            else                                                                 \n';
            sSqlRetorno := sSqlRetorno || '             x.multa                                                             \n';
            sSqlRetorno := sSqlRetorno || '         end as multa                                                            \n';
            sSqlRetorno := sSqlRetorno || '    from ( select distinct 1 as tp,                                              \n'; 
            sSqlRetorno := sSqlRetorno || '                  k00_numcgm,                                                    \n';
            sSqlRetorno := sSqlRetorno || '                  k00_dtoper,                                                    \n';
            sSqlRetorno := sSqlRetorno || '                  k00_receit,                                                    \n';
            sSqlRetorno := sSqlRetorno || '                  k00_hist,                                                      \n';
            sSqlRetorno := sSqlRetorno || '                  k00_valor,                                                     \n';
            sSqlRetorno := sSqlRetorno || '                  k00_dtvenc,                                                    \n';
            sSqlRetorno := sSqlRetorno || '                  k00_numpre,                                                    \n';
            sSqlRetorno := sSqlRetorno || '                  k00_numpar,                                                    \n';
            sSqlRetorno := sSqlRetorno || '                  k00_numtot,                                                    \n';
            sSqlRetorno := sSqlRetorno || '                  k00_numdig,                                                    \n';
            sSqlRetorno := sSqlRetorno || '                  k00_tipo,                                                      \n';
            sSqlRetorno := sSqlRetorno || '                  k00_tipojm,                                                    \n';
            sSqlRetorno := sSqlRetorno || '                  termo.v07_dtlanc,                                              \n';
            sSqlRetorno := sSqlRetorno || '                  fc_corre(arreold.k00_receit,arreold.k00_dtvenc,arreold.k00_valor,termo.v07_dtlanc, cast( extract(year from termo.v07_dtlanc) as integer) ,termo.v07_dtlanc) as corrigido, \n'; 
            sSqlRetorno := sSqlRetorno || '                  0 as juros,                                                    \n';
            sSqlRetorno := sSqlRetorno || '                  0 as multa                                                     \n';    
            sSqlRetorno := sSqlRetorno || '             from termoreparc                                                    \n';
            sSqlRetorno := sSqlRetorno || '                  inner join termo                 on termo.v07_parcel       = termoreparc.v08_parcel \n';    
            sSqlRetorno := sSqlRetorno || '                  inner join termo as termoorigem  on termoorigem.v07_parcel = termoreparc.v08_parcelorigem \n';
            sSqlRetorno := sSqlRetorno || '                  inner join arreold               on arreold.k00_numpre     = termoorigem.v07_numpre             \n';
            sSqlRetorno := sSqlRetorno || '            where termoreparc.v08_parcel = ' || iParcelamento||'                           \n';
            sSqlRetorno := sSqlRetorno || '            union all                                                                      \n';	-- tras os reparcelamentos de divida
            sSqlRetorno := sSqlRetorno || '            select distinct 2 as tp,                                             \n'; 
            sSqlRetorno := sSqlRetorno || '                   k00_numcgm,                                                   \n';
            sSqlRetorno := sSqlRetorno || '                   k00_dtoper,                                                   \n';
            sSqlRetorno := sSqlRetorno || '                   k00_receit,                                                   \n';
            sSqlRetorno := sSqlRetorno || '                   k00_hist,                                                     \n';
            sSqlRetorno := sSqlRetorno || '                   k00_valor,                                                    \n';
            sSqlRetorno := sSqlRetorno || '                   k00_dtvenc,                                                   \n';
            sSqlRetorno := sSqlRetorno || '                   k00_numpre,                                                   \n';
            sSqlRetorno := sSqlRetorno || '                   k00_numpar,                                                   \n';
            sSqlRetorno := sSqlRetorno || '                   k00_numtot,                                                   \n';
            sSqlRetorno := sSqlRetorno || '                   k00_numdig,                                                   \n';
            sSqlRetorno := sSqlRetorno || '                   k00_tipo,                                                     \n';
            sSqlRetorno := sSqlRetorno || '                   k00_tipojm,                                                   \n';
            sSqlRetorno := sSqlRetorno || '                   v07_dtlanc,                                                   \n';
            sSqlRetorno := sSqlRetorno || '                   termodiv.vlrcor as corrigido,                                 \n';
            sSqlRetorno := sSqlRetorno || '                   termodiv.juros  as juros,                                     \n';
            sSqlRetorno := sSqlRetorno || '                   termodiv.multa  as multa                                      \n';
            sSqlRetorno := sSqlRetorno || '              from termoreparc                                                   \n';
            sSqlRetorno := sSqlRetorno || '                   inner join termo    on v07_parcel         = termoreparc.v08_parcel \n';
            sSqlRetorno := sSqlRetorno || '                   inner join termodiv on termo.v07_parcel 	= termodiv.parcel   \n';
            sSqlRetorno := sSqlRetorno || '                   inner join divida 	on termodiv.coddiv   	= divida.v01_coddiv \n';
            sSqlRetorno := sSqlRetorno || '                   inner join arreold 	on arreold.k00_numpre	= divida.v01_numpre \n';
            sSqlRetorno := sSqlRetorno || '                                      and arreold.k00_numpar = divida.v01_numpar \n';
            sSqlRetorno := sSqlRetorno || '            where termoreparc.v08_parcel = ' || iParcelamento||'                 \n';    
            sSqlRetorno := sSqlRetorno || '            union all                                                            \n';	-- tras os reparcelamentos do foro
            sSqlRetorno := sSqlRetorno || '            select distinct 3 as tp,                                             \n'; 
            sSqlRetorno := sSqlRetorno || '                   k00_numcgm,                                                   \n';
            sSqlRetorno := sSqlRetorno || '                   k00_dtoper,                                                   \n';
            sSqlRetorno := sSqlRetorno || '                   k00_receit,                                                   \n';
            sSqlRetorno := sSqlRetorno || '                   k00_hist,                                                     \n';
            sSqlRetorno := sSqlRetorno || '                   k00_valor,                                                    \n';
            sSqlRetorno := sSqlRetorno || '                   k00_dtvenc,                                                   \n';
            sSqlRetorno := sSqlRetorno || '                   k00_numpre,                                                   \n';
            sSqlRetorno := sSqlRetorno || '                   k00_numpar,                                                   \n';
            sSqlRetorno := sSqlRetorno || '                   k00_numtot,                                                   \n';
            sSqlRetorno := sSqlRetorno || '                   k00_numdig,                                                   \n';
            sSqlRetorno := sSqlRetorno || '                   k00_tipo,                                                     \n';
            sSqlRetorno := sSqlRetorno || '                   k00_tipojm,                                                   \n';
            sSqlRetorno := sSqlRetorno || '                   v07_dtlanc,                                                   \n';
            sSqlRetorno := sSqlRetorno || '                   termoini.vlrcor as corrigido,                                 \n';
            sSqlRetorno := sSqlRetorno || '                   termoini.juros  as juros,                                     \n';
            sSqlRetorno := sSqlRetorno || '                   termoini.multa  as multa                                      \n';
            sSqlRetorno := sSqlRetorno || '              from termoreparc                                                   \n';
            sSqlRetorno := sSqlRetorno || '                   inner join termo         on v07_parcel                = termoreparc.v08_parcel \n';
            sSqlRetorno := sSqlRetorno || '                   inner join termoini      on termo.v07_parcel 	        = termoini.parcel        \n';
            sSqlRetorno := sSqlRetorno || '                   inner join inicialnumpre on inicialnumpre.v59_inicial = termoini.inicial       \n';
            sSqlRetorno := sSqlRetorno || '                   inner join divida 	     on inicialnumpre.v59_numpre 	= divida.v01_numpre      \n';
            sSqlRetorno := sSqlRetorno || '                   inner join arreold 	     on arreold.k00_numpre        = divida.v01_numpre      \n';
            sSqlRetorno := sSqlRetorno || '                                           and arreold.k00_numpar        = divida.v01_numpar      \n';
            sSqlRetorno := sSqlRetorno || '            where termoreparc.v08_parcel = ' || iParcelamento||'\n';			
            sSqlRetorno := sSqlRetorno || '            union all \n';	-- tras os reparcelamentos de diversos
            sSqlRetorno := sSqlRetorno || '            select distinct 4 as tp,                                             \n'; 
            sSqlRetorno := sSqlRetorno || '                   k00_numcgm,                                                   \n';
            sSqlRetorno := sSqlRetorno || '                   k00_dtoper,                                                   \n';
            sSqlRetorno := sSqlRetorno || '                   k00_receit,                                                   \n';
            sSqlRetorno := sSqlRetorno || '                   k00_hist,                                                     \n';
            sSqlRetorno := sSqlRetorno || '                   k00_valor,                                                    \n';
            sSqlRetorno := sSqlRetorno || '                   k00_dtvenc,                                                   \n';
            sSqlRetorno := sSqlRetorno || '                   k00_numpre,                                                   \n';
            sSqlRetorno := sSqlRetorno || '                   k00_numpar,                                                   \n';
            sSqlRetorno := sSqlRetorno || '                   k00_numtot,                                                   \n';
            sSqlRetorno := sSqlRetorno || '                   k00_numdig,                                                   \n';
            sSqlRetorno := sSqlRetorno || '                   k00_tipo,                                                     \n';
            sSqlRetorno := sSqlRetorno || '                   k00_tipojm,                                                   \n';
            sSqlRetorno := sSqlRetorno || '                   v07_dtlanc,                                                   \n';
            sSqlRetorno := sSqlRetorno || '                   termodiver.dv10_vlrcor as corrigido,                          \n';
            sSqlRetorno := sSqlRetorno || '                   termodiver.dv10_juros  as juros,                              \n';
            sSqlRetorno := sSqlRetorno || '                   termodiver.dv10_multa  as multa                               \n';
            sSqlRetorno := sSqlRetorno || '              from termoreparc                                                   \n';
            sSqlRetorno := sSqlRetorno || '                   inner join termo         on v07_parcel                    = termoreparc.v08_parcel   \n';
            sSqlRetorno := sSqlRetorno || '                   inner join termodiver    on termo.v07_parcel              = termodiver.dv10_parcel   \n';
            sSqlRetorno := sSqlRetorno || '                   inner join diversos      on diversos.dv05_coddiver        = termodiver.dv10_coddiver \n';
            sSqlRetorno := sSqlRetorno || '                   inner join arreold 	     on arreold.k00_numpre            = diversos.dv05_numpre     \n';
            sSqlRetorno := sSqlRetorno || '            where termoreparc.v08_parcel = ' || iParcelamento||'\n';
            sSqlRetorno := sSqlRetorno || '            union all \n';	-- tras os reparcelamentos de contribuicao de melhorias
            sSqlRetorno := sSqlRetorno || '            select distinct 5 as tp,                                             \n'; 
            sSqlRetorno := sSqlRetorno || '                   k00_numcgm,                                                   \n';
            sSqlRetorno := sSqlRetorno || '                   k00_dtoper,                                                   \n';
            sSqlRetorno := sSqlRetorno || '                   k00_receit,                                                   \n';
            sSqlRetorno := sSqlRetorno || '                   k00_hist,                                                     \n';
            sSqlRetorno := sSqlRetorno || '                   k00_valor,                                                    \n';
            sSqlRetorno := sSqlRetorno || '                   k00_dtvenc,                                                   \n';
            sSqlRetorno := sSqlRetorno || '                   k00_numpre,                                                   \n';
            sSqlRetorno := sSqlRetorno || '                   k00_numpar,                                                   \n';
            sSqlRetorno := sSqlRetorno || '                   k00_numtot,                                                   \n';
            sSqlRetorno := sSqlRetorno || '                   k00_numdig,                                                   \n';
            sSqlRetorno := sSqlRetorno || '                   k00_tipo,                                                     \n';
            sSqlRetorno := sSqlRetorno || '                   k00_tipojm,                                                   \n';
            sSqlRetorno := sSqlRetorno || '                   v07_dtlanc,                                                   \n';
            sSqlRetorno := sSqlRetorno || '                   termocontrib.vlrcor as corrigido,                             \n';
            sSqlRetorno := sSqlRetorno || '                   termocontrib.juros  as juros,                                 \n';
            sSqlRetorno := sSqlRetorno || '                   termocontrib.multa  as multa                                  \n';
            sSqlRetorno := sSqlRetorno || '              from termoreparc                                                   \n';
            sSqlRetorno := sSqlRetorno || '                   inner join termo         on v07_parcel                = termoreparc.v08_parcel  \n';
            sSqlRetorno := sSqlRetorno || '                   inner join termocontrib  on termo.v07_parcel          = termocontrib.parcel     \n';
            sSqlRetorno := sSqlRetorno || '                   inner join contricalc    on contricalc.d09_sequencial = termocontrib.contricalc \n';
            sSqlRetorno := sSqlRetorno || '                   inner join arreold 	     on arreold.k00_numpre        = contricalc.d09_numpre   \n';
            sSqlRetorno := sSqlRetorno || '                   left  join divold  	     on arreold.k00_numpre        = divold.k10_numpre       \n';
            sSqlRetorno := sSqlRetorno || '                                           and arreold.k00_numpar        = divold.k10_numpar       \n';
            sSqlRetorno := sSqlRetorno || '                                           and arreold.k00_receit        = divold.k10_receita      \n';
            sSqlRetorno := sSqlRetorno || '            where ( divold.k10_numpre is null and divold.k10_numpar is null and divold.k10_receita is null ) \n';
            sSqlRetorno := sSqlRetorno || '              and termoreparc.v08_parcel = ' || iParcelamento||'\n';
            sSqlRetorno := sSqlRetorno || '         ) as x \n'; 
            sSqlRetorno := sSqlRetorno || '   order by k00_dtoper,k00_dtvenc,k00_receit \n';

        elsif iTipo = 3 then  -- parcelamento de inicial 
        
        
            perform fc_debug('Tipo de Parcelamento 3 - termoini: '                                                                                                   ,lRaise,false,false);
            perform fc_debug('Sql busca os dados da termo, termoini, inicialnumpre, inicialcert, certdiv, divida, arreold, arreoldcalc, certter, termo'              ,lRaise,false,false);  
            perform fc_debug('Nao eh realizado nenhum calculo aplicando correcao ou juro e multa sobre os valores'                                                   ,lRaise,false,false);
            perform fc_debug('Sao retornados como valor corrigido, juro e multa, respectivamente os campos,k00_vlrcor,k00_vlrjur e k00_vlrmul da tabela arreodlcalc' ,lRaise,false,false);

            
            sSqlRetorno :=                'select * from (                             \n'; 
            sSqlRetorno := sSqlRetorno || ' select distinct                            \n'; 
            sSqlRetorno := sSqlRetorno || '        arreold.k00_numcgm,                 \n'; 
            sSqlRetorno := sSqlRetorno || '        arreold.k00_dtoper,                 \n';
            sSqlRetorno := sSqlRetorno || '        arreold.k00_receit,                 \n';
            sSqlRetorno := sSqlRetorno || '        arreold.k00_hist,                   \n';
            sSqlRetorno := sSqlRetorno || '        arreold.k00_valor,                  \n';
            sSqlRetorno := sSqlRetorno || '        arreold.k00_dtvenc,                 \n'; 
            sSqlRetorno := sSqlRetorno || '        arreold.k00_numpre,                 \n'; 
            sSqlRetorno := sSqlRetorno || '        arreold.k00_numpar,                 \n';
            sSqlRetorno := sSqlRetorno || '        arreold.k00_numtot,                 \n';
            sSqlRetorno := sSqlRetorno || '        arreold.k00_numdig,                 \n';
            sSqlRetorno := sSqlRetorno || '        arreold.k00_tipo,                   \n';
            sSqlRetorno := sSqlRetorno || '        arreold.k00_tipojm,                 \n';
            sSqlRetorno := sSqlRetorno || '        inicial,                            \n';
            sSqlRetorno := sSqlRetorno || '        arreoldcalc.k00_vlrcor as corrigido,\n';         
            sSqlRetorno := sSqlRetorno || '        arreoldcalc.k00_vlrjur as juros,    \n';        
            sSqlRetorno := sSqlRetorno || '        arreoldcalc.k00_vlrmul as multa     \n';
            sSqlRetorno := sSqlRetorno || '   from termo \n';
            sSqlRetorno := sSqlRetorno || '        inner join termoini      on termo.v07_parcel 	 = termoini.parcel        \n';
            sSqlRetorno := sSqlRetorno || '        inner join inicialnumpre on inicialnumpre.v59_inicial = termoini.inicial \n';
            sSqlRetorno := sSqlRetorno || '        inner join inicialcert   on termoini.inicial   = inicialcert.v51_inicial \n';
            sSqlRetorno := sSqlRetorno || '        inner join certdiv       on certdiv.v14_certid = inicialcert.v51_certidao\n';
            sSqlRetorno := sSqlRetorno || '        inner join divida        on certdiv.v14_coddiv = divida.v01_coddiv       \n';
            sSqlRetorno := sSqlRetorno || '        inner join arreold 	    on arreold.k00_numpre = divida.v01_numpre       \n';
            sSqlRetorno := sSqlRetorno || '                                and arreold.k00_numpar = divida.v01_numpar       \n'; 
            sSqlRetorno := sSqlRetorno || '        left join arreoldcalc   on arreoldcalc.k00_numpre = arreold.k00_numpre   \n';
            sSqlRetorno := sSqlRetorno || '                                and arreoldcalc.k00_numpar = arreold.k00_numpar  \n';
            sSqlRetorno := sSqlRetorno || '                                and arreoldcalc.k00_receit = arreold.k00_receit  \n';
            sSqlRetorno := sSqlRetorno || '  where termo.v07_parcel = ' || iParcelamento||'\n' ;
            sSqlRetorno := sSqlRetorno || '  union ';
            sSqlRetorno := sSqlRetorno || ' select distinct                            \n'; 
            sSqlRetorno := sSqlRetorno || '        arreold.k00_numcgm,                 \n';
            sSqlRetorno := sSqlRetorno || '        arreold.k00_dtoper,                 \n';
            sSqlRetorno := sSqlRetorno || '        arreold.k00_receit,                 \n';
            sSqlRetorno := sSqlRetorno || '        arreold.k00_hist,                   \n';
            sSqlRetorno := sSqlRetorno || '        arreold.k00_valor,                  \n';
            sSqlRetorno := sSqlRetorno || '        arreold.k00_dtvenc,                 \n';
            sSqlRetorno := sSqlRetorno || '        arreold.k00_numpre,                 \n';
            sSqlRetorno := sSqlRetorno || '        arreold.k00_numpar,                 \n';
            sSqlRetorno := sSqlRetorno || '        arreold.k00_numtot,                 \n';
            sSqlRetorno := sSqlRetorno || '        arreold.k00_numdig,                 \n';
            sSqlRetorno := sSqlRetorno || '        arreold.k00_tipo,                   \n';
            sSqlRetorno := sSqlRetorno || '        arreold.k00_tipojm,                 \n';
            sSqlRetorno := sSqlRetorno || '        inicial,                            \n';
            sSqlRetorno := sSqlRetorno || '        arreoldcalc.k00_vlrcor as corrigido,\n';         
            sSqlRetorno := sSqlRetorno || '        arreoldcalc.k00_vlrjur as juros,    \n';        
            sSqlRetorno := sSqlRetorno || '        arreoldcalc.k00_vlrmul as multa     \n';
            sSqlRetorno := sSqlRetorno || '   from termo \n';
            sSqlRetorno := sSqlRetorno || '        inner join termoini    	on termo.v07_parcel 	  = termoini.parcel            \n';
            sSqlRetorno := sSqlRetorno || '        inner join inicialnumpre on inicialnumpre.v59_inicial = termoini.inicial      \n';
            sSqlRetorno := sSqlRetorno || '        inner join inicialcert   on termoini.inicial    = inicialcert.v51_inicial     \n';
            sSqlRetorno := sSqlRetorno || '        inner join certter       on certter.v14_certid  = inicialcert.v51_certidao    \n';
            sSqlRetorno := sSqlRetorno || '        inner join termo termo_origem on termo_origem.v07_parcel = certter.v14_parcel \n';
            sSqlRetorno := sSqlRetorno || '        inner join arreold 	    on arreold.k00_numpre	= termo_origem.v07_numpre      \n';
            sSqlRetorno := sSqlRetorno || '         left join arreoldcalc   on arreoldcalc.k00_numpre = arreold.k00_numpre       \n';
            sSqlRetorno := sSqlRetorno || '                                and arreoldcalc.k00_numpar = arreold.k00_numpar       \n';
            sSqlRetorno := sSqlRetorno || '                                and arreoldcalc.k00_receit = arreold.k00_receit       \n';
            sSqlRetorno := sSqlRetorno || '  where termo.v07_parcel = ' || iParcelamento||'\n';
            sSqlRetorno := sSqlRetorno || '  ) as x order by k00_dtvenc,k00_dtoper,k00_receit \n';

            elsif iTipo = 4 then -- parcelamento de diveros
            
            perform fc_debug('Tipo de Parcelamento 4 - termodiver: '                                                                                                 ,lRaise,false,false);
            perform fc_debug('Sql busca os dados da termo, termodiver, arreoldcalc, diversos e arreold'                                                                           ,lRaise,false,false);
            perform fc_debug('Nao eh realizado nenhum calculo aplicando correcao ou juro e multa sobre os valores'                                                   ,lRaise,false,false);
            perform fc_debug('Sao retornados como valor corrigido, juro e multa, respectivamente os campos,dv10_vlrcor,dv10_juros e dv10_multa da tabela termodiver' ,lRaise,false,false);
            
            sSqlRetorno :=                ' select arreold.k00_numcgm,                          \n'; 
            sSqlRetorno := sSqlRetorno || '        arreold.k00_dtoper,                          \n';
            sSqlRetorno := sSqlRetorno || '        arreold.k00_receit,                          \n';
            sSqlRetorno := sSqlRetorno || '        arreold.k00_hist,                            \n';
            sSqlRetorno := sSqlRetorno || '        arreold.k00_valor,                           \n';
            sSqlRetorno := sSqlRetorno || '        arreold.k00_dtvenc,                          \n';
            sSqlRetorno := sSqlRetorno || '        arreold.k00_numpre,                          \n';
            sSqlRetorno := sSqlRetorno || '        arreold.k00_numpar,                          \n';
            sSqlRetorno := sSqlRetorno || '        arreold.k00_numtot,                          \n';
            sSqlRetorno := sSqlRetorno || '        arreold.k00_numdig,                          \n';
            sSqlRetorno := sSqlRetorno || '        arreold.k00_tipo,                            \n';
            sSqlRetorno := sSqlRetorno || '        arreold.k00_tipojm,                          \n';

            sSqlRetorno := sSqlRetorno || '        arreoldcalc.k00_vlrcor as corrigido, \n';
            sSqlRetorno := sSqlRetorno || '        arreoldcalc.k00_vlrjur  as juros,     \n';
            sSqlRetorno := sSqlRetorno || '        arreoldcalc.k00_vlrmul  as multa      \n';

            sSqlRetorno := sSqlRetorno || '   from termo \n';
            sSqlRetorno := sSqlRetorno || '        inner join termodiver	on termo.v07_parcel      	= termodiver.dv10_parcel  \n';
            sSqlRetorno := sSqlRetorno || '        inner join diversos   on diversos.dv05_coddiver = termodiver.dv10_coddiver \n';
            sSqlRetorno := sSqlRetorno || '        inner join arreold   	on arreold.k00_numpre 	  = diversos.dv05_numpre    \n';
            sSqlRetorno := sSqlRetorno || '        inner join arreoldcalc   on arreoldcalc.k00_numpre     = diversos.dv05_numpre AND arreoldcalc.k00_numpar = arreold.k00_numpar \n';
            sSqlRetorno := sSqlRetorno || '  where termo.v07_parcel = ' || iParcelamento||'\n';
            sSqlRetorno := sSqlRetorno || '  order by k00_dtoper,k00_dtvenc,k00_receit \n';
            
            elsif iTipo = 5 then -- parcelamento de contribuicao de melhorias
            
            perform fc_debug('Tipo de Parcelamento 5 - termocontrib: '                                                                                               ,lRaise,false,false);
            perform fc_debug('Sql busca os dados da termo, termocontrib, contricalc e arreold, '                                                                     ,lRaise,false,false);
            perform fc_debug('havendo um left com a divold apenas para garantir que nao virao registros que sao oriundos da divida'                                  ,lRaise,false,false);
            perform fc_debug('Nao eh realizado nenhum calculo aplicando correcao ou juro e multa sobre os valores'                                                   ,lRaise,false,false);
            perform fc_debug('Sao retornados como valor corrigido, juro e multa, respectivamente os campos,dv10_vlrcor,dv10_juros e dv10_multa da tabela termodiver' ,lRaise,false,false);
            
            sSqlRetorno :=                ' select k00_numcgm,                       \n'; 
            sSqlRetorno := sSqlRetorno || '        k00_dtoper,                       \n';
            sSqlRetorno := sSqlRetorno || '        k00_receit,                       \n';
            sSqlRetorno := sSqlRetorno || '        k00_hist,                         \n';
            sSqlRetorno := sSqlRetorno || '        k00_valor,                        \n';
            sSqlRetorno := sSqlRetorno || '        k00_dtvenc,                       \n';
            sSqlRetorno := sSqlRetorno || '        k00_numpre,                       \n';
            sSqlRetorno := sSqlRetorno || '        k00_numpar,                       \n';
            sSqlRetorno := sSqlRetorno || '        k00_numtot,                       \n';
            sSqlRetorno := sSqlRetorno || '        k00_numdig,                       \n';
            sSqlRetorno := sSqlRetorno || '        k00_tipo,                         \n';
            sSqlRetorno := sSqlRetorno || '        k00_tipojm,                       \n';
            sSqlRetorno := sSqlRetorno || '        termocontrib.vlrcor as corrigido, \n';
            sSqlRetorno := sSqlRetorno || '        termocontrib.juros  as juros,     \n';
            sSqlRetorno := sSqlRetorno || '        termocontrib.multa  as multa      \n';
            sSqlRetorno := sSqlRetorno || '   from termo                             \n';
            sSqlRetorno := sSqlRetorno || '        inner join termocontrib on termo.v07_parcel          = termocontrib.parcel     \n';
            sSqlRetorno := sSqlRetorno || '        inner join contricalc   on contricalc.d09_sequencial = termocontrib.contricalc \n';
            sSqlRetorno := sSqlRetorno || '        inner join arreold	     on arreold.k00_numpre        = contricalc.d09_numpre   \n';
                -- left com divold porque o numpre da contricalc pode estar na arreold tanto por parcelamento como por importacao de divida mais como o que interessa e so os 
                -- registros referente ao parcelamento dou um left com divold para garantir que nao vira registros que sao oriundos da divida
            sSqlRetorno := sSqlRetorno || '        left  join divold       on arreold.k00_numpre        = divold.k10_numpre  \n'; 
            sSqlRetorno := sSqlRetorno || '                               and arreold.k00_numpar        = divold.k10_numpar  \n';
            sSqlRetorno := sSqlRetorno || '                               and arreold.k00_receit        = divold.k10_receita \n';
            sSqlRetorno := sSqlRetorno || '   where ( divold.k10_numpre is null and divold.k10_numpar is null and divold.k10_receita is null ) \n';
            sSqlRetorno := sSqlRetorno || '     and termo.v07_parcel = ' || iParcelamento||'\n';
            sSqlRetorno := sSqlRetorno || '   order by k00_dtoper,k00_dtvenc,k00_receit \n';
            
        end if;
        
        return sSqlRetorno;
        
        end;
        $$  language 'plpgsql';

        ");
    }

    public function down()
    {
        $this->execute("
        create or replace function fc_parc_getselectorigens_jurori(integer,integer,integer) returns varchar as
        $$
        declare
        
        iParcelamento      alias for $1;
        iTipo              alias for $2;
        iTipoAnulacao      alias for $3;
        
        iAnoUsu            integer default 0;

        dDataCorrecao      date;
        
        sSqlRetorno     varchar default '';
        
        lRaise          boolean default false;
        
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
        perform fc_debug('Processando funcao fc_parc_getselectorigens_jurori...' ,lRaise,false,false);
        
        if iTipo = 1 then
        
        
            perform fc_debug('Tipo de Parcelamento 1 - termodiv: ' ,lRaise,false,false);
            perform fc_debug('Sql busca os dados da termodiv, divida e arreold' ,lRaise,false,false);
            perform fc_debug('Nao eh realizado nenhum calculo aplicando correcao ou juro e multa sobre os valores'                                                   ,lRaise,false,false);
            perform fc_debug('Sao retornados como valor corrigido, juro e multa, respectivamente os campos,vlrcor,juros e multa da tabela termodiv' ,lRaise,false,false);    
            
            sSqlRetorno :=                ' select k00_numcgm,                                                                                               \n'; 
            sSqlRetorno := sSqlRetorno || '        k00_dtoper,                                                                                               \n';
            sSqlRetorno := sSqlRetorno || '        k00_receit,                                                                                               \n';
            sSqlRetorno := sSqlRetorno || '        k00_hist,                                                                                                 \n';
            sSqlRetorno := sSqlRetorno || '        k00_valor,                                                                                                \n';
            sSqlRetorno := sSqlRetorno || '        k00_dtvenc,                                                                                               \n';
            sSqlRetorno := sSqlRetorno || '        k00_numpre,                                                                                               \n';
            sSqlRetorno := sSqlRetorno || '        k00_numpar,                                                                                               \n';
            sSqlRetorno := sSqlRetorno || '        k00_numtot,                                                                                               \n';
            sSqlRetorno := sSqlRetorno || '        k00_numdig,                                                                                               \n';
            sSqlRetorno := sSqlRetorno || '        k00_tipo,                                                                                                 \n';
            sSqlRetorno := sSqlRetorno || '        k00_tipojm,                                                                                               \n';
            sSqlRetorno := sSqlRetorno || '        v01_exerc,                                                                                                \n';
            sSqlRetorno := sSqlRetorno || '        v01_coddiv,                                                                                               \n';
            sSqlRetorno := sSqlRetorno || '        termodiv.vlrcor as corrigido,                                                                             \n';
            sSqlRetorno := sSqlRetorno || '        termodiv.juros  as juros,                                                                                 \n';
            sSqlRetorno := sSqlRetorno || '        termodiv.multa  as multa                                                                                  \n';
            sSqlRetorno := sSqlRetorno || '   from termo                                                                                                     \n';
            sSqlRetorno := sSqlRetorno || '        inner join termodiv  on termo.v07_parcel 	= termodiv.parcel                                              \n';
            sSqlRetorno := sSqlRetorno || '        inner join divida    on termodiv.coddiv   	= divida.v01_coddiv                                            \n';
            sSqlRetorno := sSqlRetorno || '        inner join arreold   on arreold.k00_numpre	= divida.v01_numpre and arreold.k00_numpar = divida.v01_numpar \n';
            sSqlRetorno := sSqlRetorno || '  where termo.v07_parcel = ' || iParcelamento||'                                                                  \n';
            sSqlRetorno := sSqlRetorno || '  order by v01_exerc,k00_dtvenc,k00_receit                                                                        \n';
            
        elsif iTipo = 2 then

            perform fc_debug('Tipo de Parcelamento 2 - termoreparc: '                                                                               ,lRaise,false,false);
            perform fc_debug('Sql busca os dados da termoreparc, termo, tabelas de origem do termo (termodiv, termoini, termodiver e termocontrib), arreold etc' ,lRaise,false,false);  
            perform fc_debug('=> Reparcelamento de reparcelamento .............................................:'                                   ,lRaise,false,false); 
            perform fc_debug('     Utilizamos o campo k00_valor do arreold e aplicamos a correcao (fc_corre)'                                       ,lRaise,false,false);
            perform fc_debug('=> Reparcelamento de parcelamento de Divida, Inicial, Diversos ou Contribuicao ..:'                                   ,lRaise,false,false); 
            perform fc_debug('     Utilizamos os campos vlrcor, juros e multa (tabelas termodiv, termoini, termodiver, termocontrib)'               ,lRaise,false,false);
            perform fc_debug('     *Quando reparcelamento de divida, calculamos o juro (fc_juros) e multa (fc_multa) sobre o valor corrigido '      ,lRaise,false,false);
            perform fc_debug('      e desconsideramos os valores dos campos juros e multa da tabela termodiv, nos demais casos, juro e multa '      ,lRaise,false,false);
            perform fc_debug('      ficam com o valor dos campos juro e multa '                                                                     ,lRaise,false,false);
            
            sSqlRetorno :=                '  select x.k00_numcgm,                                                           \n';      
            sSqlRetorno := sSqlRetorno || '         x.k00_dtoper,                                                           \n';      
            sSqlRetorno := sSqlRetorno || '         x.k00_receit,                                                           \n';      
            sSqlRetorno := sSqlRetorno || '         x.k00_hist,                                                             \n';      
            sSqlRetorno := sSqlRetorno || '         x.k00_valor,                                                            \n';      
            sSqlRetorno := sSqlRetorno || '         x.k00_dtvenc,                                                           \n';      
            sSqlRetorno := sSqlRetorno || '         x.k00_numpre,                                                           \n';      
            sSqlRetorno := sSqlRetorno || '         x.k00_numpar,                                                           \n';      
            sSqlRetorno := sSqlRetorno || '         x.k00_numtot,                                                           \n';      
            sSqlRetorno := sSqlRetorno || '         x.k00_numdig,                                                           \n';      
            sSqlRetorno := sSqlRetorno || '         x.k00_tipo,                                                             \n';      
            sSqlRetorno := sSqlRetorno || '         x.k00_tipojm,                                                           \n';      
            sSqlRetorno := sSqlRetorno || '         x.v07_dtlanc,                                                           \n';      
            sSqlRetorno := sSqlRetorno || '         x.corrigido,                                                            \n';
            sSqlRetorno := sSqlRetorno || '         case                                                                    \n';
            sSqlRetorno := sSqlRetorno || '           when x.tp = 1                                                         \n'; 
            sSqlRetorno := sSqlRetorno || '            then                                                                 \n';
            sSqlRetorno := sSqlRetorno || '             ( x.corrigido * coalesce( fc_juros(x.k00_receit,x.k00_dtvenc,x.v07_dtlanc,x.v07_dtlanc,false,  cast( extract(year from x.v07_dtlanc) as integer)),0)) \n'; 
            sSqlRetorno := sSqlRetorno || '            else                                                                 \n';
            sSqlRetorno := sSqlRetorno || '             x.juros                                                             \n';
            sSqlRetorno := sSqlRetorno || '         end as juros,                                                           \n';
            sSqlRetorno := sSqlRetorno || '         case                                                                    \n';
            sSqlRetorno := sSqlRetorno || '           when x.tp = 1                                                         \n'; 
            sSqlRetorno := sSqlRetorno || '            then                                                                 \n';
            sSqlRetorno := sSqlRetorno || '             ( x.corrigido * coalesce( fc_multa(x.k00_receit,x.k00_dtvenc,x.v07_dtlanc,x.k00_dtoper,cast( extract(year from x.v07_dtlanc) as integer)),0)) \n'; 
            sSqlRetorno := sSqlRetorno || '            else                                                                 \n';
            sSqlRetorno := sSqlRetorno || '             x.multa                                                             \n';
            sSqlRetorno := sSqlRetorno || '         end as multa                                                            \n';
            sSqlRetorno := sSqlRetorno || '    from ( select distinct 1 as tp,                                              \n'; 
            sSqlRetorno := sSqlRetorno || '                  k00_numcgm,                                                    \n';
            sSqlRetorno := sSqlRetorno || '                  k00_dtoper,                                                    \n';
            sSqlRetorno := sSqlRetorno || '                  k00_receit,                                                    \n';
            sSqlRetorno := sSqlRetorno || '                  k00_hist,                                                      \n';
            sSqlRetorno := sSqlRetorno || '                  k00_valor,                                                     \n';
            sSqlRetorno := sSqlRetorno || '                  k00_dtvenc,                                                    \n';
            sSqlRetorno := sSqlRetorno || '                  k00_numpre,                                                    \n';
            sSqlRetorno := sSqlRetorno || '                  k00_numpar,                                                    \n';
            sSqlRetorno := sSqlRetorno || '                  k00_numtot,                                                    \n';
            sSqlRetorno := sSqlRetorno || '                  k00_numdig,                                                    \n';
            sSqlRetorno := sSqlRetorno || '                  k00_tipo,                                                      \n';
            sSqlRetorno := sSqlRetorno || '                  k00_tipojm,                                                    \n';
            sSqlRetorno := sSqlRetorno || '                  termo.v07_dtlanc,                                              \n';
            sSqlRetorno := sSqlRetorno || '                  fc_corre(arreold.k00_receit,arreold.k00_dtvenc,arreold.k00_valor,termo.v07_dtlanc, cast( extract(year from termo.v07_dtlanc) as integer) ,termo.v07_dtlanc) as corrigido, \n'; 
            sSqlRetorno := sSqlRetorno || '                  0 as juros,                                                    \n';
            sSqlRetorno := sSqlRetorno || '                  0 as multa                                                     \n';    
            sSqlRetorno := sSqlRetorno || '             from termoreparc                                                    \n';
            sSqlRetorno := sSqlRetorno || '                  inner join termo                 on termo.v07_parcel       = termoreparc.v08_parcel \n';    
            sSqlRetorno := sSqlRetorno || '                  inner join termo as termoorigem  on termoorigem.v07_parcel = termoreparc.v08_parcelorigem \n';
            sSqlRetorno := sSqlRetorno || '                  inner join arreold               on arreold.k00_numpre     = termoorigem.v07_numpre             \n';
            sSqlRetorno := sSqlRetorno || '            where termoreparc.v08_parcel = ' || iParcelamento||'                           \n';
            sSqlRetorno := sSqlRetorno || '            union all                                                                      \n';	-- tras os reparcelamentos de divida
            sSqlRetorno := sSqlRetorno || '            select distinct 2 as tp,                                             \n'; 
            sSqlRetorno := sSqlRetorno || '                   k00_numcgm,                                                   \n';
            sSqlRetorno := sSqlRetorno || '                   k00_dtoper,                                                   \n';
            sSqlRetorno := sSqlRetorno || '                   k00_receit,                                                   \n';
            sSqlRetorno := sSqlRetorno || '                   k00_hist,                                                     \n';
            sSqlRetorno := sSqlRetorno || '                   k00_valor,                                                    \n';
            sSqlRetorno := sSqlRetorno || '                   k00_dtvenc,                                                   \n';
            sSqlRetorno := sSqlRetorno || '                   k00_numpre,                                                   \n';
            sSqlRetorno := sSqlRetorno || '                   k00_numpar,                                                   \n';
            sSqlRetorno := sSqlRetorno || '                   k00_numtot,                                                   \n';
            sSqlRetorno := sSqlRetorno || '                   k00_numdig,                                                   \n';
            sSqlRetorno := sSqlRetorno || '                   k00_tipo,                                                     \n';
            sSqlRetorno := sSqlRetorno || '                   k00_tipojm,                                                   \n';
            sSqlRetorno := sSqlRetorno || '                   v07_dtlanc,                                                   \n';
            sSqlRetorno := sSqlRetorno || '                   termodiv.vlrcor as corrigido,                                 \n';
            sSqlRetorno := sSqlRetorno || '                   termodiv.juros  as juros,                                     \n';
            sSqlRetorno := sSqlRetorno || '                   termodiv.multa  as multa                                      \n';
            sSqlRetorno := sSqlRetorno || '              from termoreparc                                                   \n';
            sSqlRetorno := sSqlRetorno || '                   inner join termo    on v07_parcel         = termoreparc.v08_parcel \n';
            sSqlRetorno := sSqlRetorno || '                   inner join termodiv on termo.v07_parcel 	= termodiv.parcel   \n';
            sSqlRetorno := sSqlRetorno || '                   inner join divida 	on termodiv.coddiv   	= divida.v01_coddiv \n';
            sSqlRetorno := sSqlRetorno || '                   inner join arreold 	on arreold.k00_numpre	= divida.v01_numpre \n';
            sSqlRetorno := sSqlRetorno || '                                      and arreold.k00_numpar = divida.v01_numpar \n';
            sSqlRetorno := sSqlRetorno || '            where termoreparc.v08_parcel = ' || iParcelamento||'                 \n';    
            sSqlRetorno := sSqlRetorno || '            union all                                                            \n';	-- tras os reparcelamentos do foro
            sSqlRetorno := sSqlRetorno || '            select distinct 3 as tp,                                             \n'; 
            sSqlRetorno := sSqlRetorno || '                   k00_numcgm,                                                   \n';
            sSqlRetorno := sSqlRetorno || '                   k00_dtoper,                                                   \n';
            sSqlRetorno := sSqlRetorno || '                   k00_receit,                                                   \n';
            sSqlRetorno := sSqlRetorno || '                   k00_hist,                                                     \n';
            sSqlRetorno := sSqlRetorno || '                   k00_valor,                                                    \n';
            sSqlRetorno := sSqlRetorno || '                   k00_dtvenc,                                                   \n';
            sSqlRetorno := sSqlRetorno || '                   k00_numpre,                                                   \n';
            sSqlRetorno := sSqlRetorno || '                   k00_numpar,                                                   \n';
            sSqlRetorno := sSqlRetorno || '                   k00_numtot,                                                   \n';
            sSqlRetorno := sSqlRetorno || '                   k00_numdig,                                                   \n';
            sSqlRetorno := sSqlRetorno || '                   k00_tipo,                                                     \n';
            sSqlRetorno := sSqlRetorno || '                   k00_tipojm,                                                   \n';
            sSqlRetorno := sSqlRetorno || '                   v07_dtlanc,                                                   \n';
            sSqlRetorno := sSqlRetorno || '                   termoini.vlrcor as corrigido,                                 \n';
            sSqlRetorno := sSqlRetorno || '                   termoini.juros  as juros,                                     \n';
            sSqlRetorno := sSqlRetorno || '                   termoini.multa  as multa                                      \n';
            sSqlRetorno := sSqlRetorno || '              from termoreparc                                                   \n';
            sSqlRetorno := sSqlRetorno || '                   inner join termo         on v07_parcel                = termoreparc.v08_parcel \n';
            sSqlRetorno := sSqlRetorno || '                   inner join termoini      on termo.v07_parcel 	        = termoini.parcel        \n';
            sSqlRetorno := sSqlRetorno || '                   inner join inicialnumpre on inicialnumpre.v59_inicial = termoini.inicial       \n';
            sSqlRetorno := sSqlRetorno || '                   inner join divida 	     on inicialnumpre.v59_numpre 	= divida.v01_numpre      \n';
            sSqlRetorno := sSqlRetorno || '                   inner join arreold 	     on arreold.k00_numpre        = divida.v01_numpre      \n';
            sSqlRetorno := sSqlRetorno || '                                           and arreold.k00_numpar        = divida.v01_numpar      \n';
            sSqlRetorno := sSqlRetorno || '            where termoreparc.v08_parcel = ' || iParcelamento||'\n';			
            sSqlRetorno := sSqlRetorno || '            union all \n';	-- tras os reparcelamentos de diversos
            sSqlRetorno := sSqlRetorno || '            select distinct 4 as tp,                                             \n'; 
            sSqlRetorno := sSqlRetorno || '                   k00_numcgm,                                                   \n';
            sSqlRetorno := sSqlRetorno || '                   k00_dtoper,                                                   \n';
            sSqlRetorno := sSqlRetorno || '                   k00_receit,                                                   \n';
            sSqlRetorno := sSqlRetorno || '                   k00_hist,                                                     \n';
            sSqlRetorno := sSqlRetorno || '                   k00_valor,                                                    \n';
            sSqlRetorno := sSqlRetorno || '                   k00_dtvenc,                                                   \n';
            sSqlRetorno := sSqlRetorno || '                   k00_numpre,                                                   \n';
            sSqlRetorno := sSqlRetorno || '                   k00_numpar,                                                   \n';
            sSqlRetorno := sSqlRetorno || '                   k00_numtot,                                                   \n';
            sSqlRetorno := sSqlRetorno || '                   k00_numdig,                                                   \n';
            sSqlRetorno := sSqlRetorno || '                   k00_tipo,                                                     \n';
            sSqlRetorno := sSqlRetorno || '                   k00_tipojm,                                                   \n';
            sSqlRetorno := sSqlRetorno || '                   v07_dtlanc,                                                   \n';
            sSqlRetorno := sSqlRetorno || '                   termodiver.dv10_vlrcor as corrigido,                          \n';
            sSqlRetorno := sSqlRetorno || '                   termodiver.dv10_juros  as juros,                              \n';
            sSqlRetorno := sSqlRetorno || '                   termodiver.dv10_multa  as multa                               \n';
            sSqlRetorno := sSqlRetorno || '              from termoreparc                                                   \n';
            sSqlRetorno := sSqlRetorno || '                   inner join termo         on v07_parcel                    = termoreparc.v08_parcel   \n';
            sSqlRetorno := sSqlRetorno || '                   inner join termodiver    on termo.v07_parcel              = termodiver.dv10_parcel   \n';
            sSqlRetorno := sSqlRetorno || '                   inner join diversos      on diversos.dv05_coddiver        = termodiver.dv10_coddiver \n';
            sSqlRetorno := sSqlRetorno || '                   inner join arreold 	     on arreold.k00_numpre            = diversos.dv05_numpre     \n';
            sSqlRetorno := sSqlRetorno || '            where termoreparc.v08_parcel = ' || iParcelamento||'\n';
            sSqlRetorno := sSqlRetorno || '            union all \n';	-- tras os reparcelamentos de contribuicao de melhorias
            sSqlRetorno := sSqlRetorno || '            select distinct 5 as tp,                                             \n'; 
            sSqlRetorno := sSqlRetorno || '                   k00_numcgm,                                                   \n';
            sSqlRetorno := sSqlRetorno || '                   k00_dtoper,                                                   \n';
            sSqlRetorno := sSqlRetorno || '                   k00_receit,                                                   \n';
            sSqlRetorno := sSqlRetorno || '                   k00_hist,                                                     \n';
            sSqlRetorno := sSqlRetorno || '                   k00_valor,                                                    \n';
            sSqlRetorno := sSqlRetorno || '                   k00_dtvenc,                                                   \n';
            sSqlRetorno := sSqlRetorno || '                   k00_numpre,                                                   \n';
            sSqlRetorno := sSqlRetorno || '                   k00_numpar,                                                   \n';
            sSqlRetorno := sSqlRetorno || '                   k00_numtot,                                                   \n';
            sSqlRetorno := sSqlRetorno || '                   k00_numdig,                                                   \n';
            sSqlRetorno := sSqlRetorno || '                   k00_tipo,                                                     \n';
            sSqlRetorno := sSqlRetorno || '                   k00_tipojm,                                                   \n';
            sSqlRetorno := sSqlRetorno || '                   v07_dtlanc,                                                   \n';
            sSqlRetorno := sSqlRetorno || '                   termocontrib.vlrcor as corrigido,                             \n';
            sSqlRetorno := sSqlRetorno || '                   termocontrib.juros  as juros,                                 \n';
            sSqlRetorno := sSqlRetorno || '                   termocontrib.multa  as multa                                  \n';
            sSqlRetorno := sSqlRetorno || '              from termoreparc                                                   \n';
            sSqlRetorno := sSqlRetorno || '                   inner join termo         on v07_parcel                = termoreparc.v08_parcel  \n';
            sSqlRetorno := sSqlRetorno || '                   inner join termocontrib  on termo.v07_parcel          = termocontrib.parcel     \n';
            sSqlRetorno := sSqlRetorno || '                   inner join contricalc    on contricalc.d09_sequencial = termocontrib.contricalc \n';
            sSqlRetorno := sSqlRetorno || '                   inner join arreold 	     on arreold.k00_numpre        = contricalc.d09_numpre   \n';
            sSqlRetorno := sSqlRetorno || '                   left  join divold  	     on arreold.k00_numpre        = divold.k10_numpre       \n';
            sSqlRetorno := sSqlRetorno || '                                           and arreold.k00_numpar        = divold.k10_numpar       \n';
            sSqlRetorno := sSqlRetorno || '                                           and arreold.k00_receit        = divold.k10_receita      \n';
            sSqlRetorno := sSqlRetorno || '            where ( divold.k10_numpre is null and divold.k10_numpar is null and divold.k10_receita is null ) \n';
            sSqlRetorno := sSqlRetorno || '              and termoreparc.v08_parcel = ' || iParcelamento||'\n';
            sSqlRetorno := sSqlRetorno || '         ) as x \n'; 
            sSqlRetorno := sSqlRetorno || '   order by k00_dtoper,k00_dtvenc,k00_receit \n';

        elsif iTipo = 3 then  -- parcelamento de inicial 
        
        
            perform fc_debug('Tipo de Parcelamento 3 - termoini: '                                                                                                   ,lRaise,false,false);
            perform fc_debug('Sql busca os dados da termo, termoini, inicialnumpre, inicialcert, certdiv, divida, arreold, arreoldcalc, certter, termo'              ,lRaise,false,false);  
            perform fc_debug('Nao eh realizado nenhum calculo aplicando correcao ou juro e multa sobre os valores'                                                   ,lRaise,false,false);
            perform fc_debug('Sao retornados como valor corrigido, juro e multa, respectivamente os campos,k00_vlrcor,k00_vlrjur e k00_vlrmul da tabela arreodlcalc' ,lRaise,false,false);

            
            sSqlRetorno :=                'select * from (                             \n'; 
            sSqlRetorno := sSqlRetorno || ' select distinct                            \n'; 
            sSqlRetorno := sSqlRetorno || '        arreold.k00_numcgm,                 \n'; 
            sSqlRetorno := sSqlRetorno || '        arreold.k00_dtoper,                 \n';
            sSqlRetorno := sSqlRetorno || '        arreold.k00_receit,                 \n';
            sSqlRetorno := sSqlRetorno || '        arreold.k00_hist,                   \n';
            sSqlRetorno := sSqlRetorno || '        arreold.k00_valor,                  \n';
            sSqlRetorno := sSqlRetorno || '        arreold.k00_dtvenc,                 \n'; 
            sSqlRetorno := sSqlRetorno || '        arreold.k00_numpre,                 \n'; 
            sSqlRetorno := sSqlRetorno || '        arreold.k00_numpar,                 \n';
            sSqlRetorno := sSqlRetorno || '        arreold.k00_numtot,                 \n';
            sSqlRetorno := sSqlRetorno || '        arreold.k00_numdig,                 \n';
            sSqlRetorno := sSqlRetorno || '        arreold.k00_tipo,                   \n';
            sSqlRetorno := sSqlRetorno || '        arreold.k00_tipojm,                 \n';
            sSqlRetorno := sSqlRetorno || '        inicial,                            \n';
            sSqlRetorno := sSqlRetorno || '        arreoldcalc.k00_vlrcor as corrigido,\n';         
            sSqlRetorno := sSqlRetorno || '        arreoldcalc.k00_vlrjur as juros,    \n';        
            sSqlRetorno := sSqlRetorno || '        arreoldcalc.k00_vlrmul as multa     \n';
            sSqlRetorno := sSqlRetorno || '   from termo \n';
            sSqlRetorno := sSqlRetorno || '        inner join termoini      on termo.v07_parcel 	 = termoini.parcel        \n';
            sSqlRetorno := sSqlRetorno || '        inner join inicialnumpre on inicialnumpre.v59_inicial = termoini.inicial \n';
            sSqlRetorno := sSqlRetorno || '        inner join inicialcert   on termoini.inicial   = inicialcert.v51_inicial \n';
            sSqlRetorno := sSqlRetorno || '        inner join certdiv       on certdiv.v14_certid = inicialcert.v51_certidao\n';
            sSqlRetorno := sSqlRetorno || '        inner join divida        on certdiv.v14_coddiv = divida.v01_coddiv       \n';
            sSqlRetorno := sSqlRetorno || '        inner join arreold 	    on arreold.k00_numpre = divida.v01_numpre       \n';
            sSqlRetorno := sSqlRetorno || '                                and arreold.k00_numpar = divida.v01_numpar       \n'; 
            sSqlRetorno := sSqlRetorno || '        left join arreoldcalc   on arreoldcalc.k00_numpre = arreold.k00_numpre   \n';
            sSqlRetorno := sSqlRetorno || '                                and arreoldcalc.k00_numpar = arreold.k00_numpar  \n';
            sSqlRetorno := sSqlRetorno || '                                and arreoldcalc.k00_receit = arreold.k00_receit  \n';
            sSqlRetorno := sSqlRetorno || '  where termo.v07_parcel = ' || iParcelamento||'\n' ;
            sSqlRetorno := sSqlRetorno || '  union ';
            sSqlRetorno := sSqlRetorno || ' select distinct                            \n'; 
            sSqlRetorno := sSqlRetorno || '        arreold.k00_numcgm,                 \n';
            sSqlRetorno := sSqlRetorno || '        arreold.k00_dtoper,                 \n';
            sSqlRetorno := sSqlRetorno || '        arreold.k00_receit,                 \n';
            sSqlRetorno := sSqlRetorno || '        arreold.k00_hist,                   \n';
            sSqlRetorno := sSqlRetorno || '        arreold.k00_valor,                  \n';
            sSqlRetorno := sSqlRetorno || '        arreold.k00_dtvenc,                 \n';
            sSqlRetorno := sSqlRetorno || '        arreold.k00_numpre,                 \n';
            sSqlRetorno := sSqlRetorno || '        arreold.k00_numpar,                 \n';
            sSqlRetorno := sSqlRetorno || '        arreold.k00_numtot,                 \n';
            sSqlRetorno := sSqlRetorno || '        arreold.k00_numdig,                 \n';
            sSqlRetorno := sSqlRetorno || '        arreold.k00_tipo,                   \n';
            sSqlRetorno := sSqlRetorno || '        arreold.k00_tipojm,                 \n';
            sSqlRetorno := sSqlRetorno || '        inicial,                            \n';
            sSqlRetorno := sSqlRetorno || '        arreoldcalc.k00_vlrcor as corrigido,\n';         
            sSqlRetorno := sSqlRetorno || '        arreoldcalc.k00_vlrjur as juros,    \n';        
            sSqlRetorno := sSqlRetorno || '        arreoldcalc.k00_vlrmul as multa     \n';
            sSqlRetorno := sSqlRetorno || '   from termo \n';
            sSqlRetorno := sSqlRetorno || '        inner join termoini    	on termo.v07_parcel 	  = termoini.parcel            \n';
            sSqlRetorno := sSqlRetorno || '        inner join inicialnumpre on inicialnumpre.v59_inicial = termoini.inicial      \n';
            sSqlRetorno := sSqlRetorno || '        inner join inicialcert   on termoini.inicial    = inicialcert.v51_inicial     \n';
            sSqlRetorno := sSqlRetorno || '        inner join certter       on certter.v14_certid  = inicialcert.v51_certidao    \n';
            sSqlRetorno := sSqlRetorno || '        inner join termo termo_origem on termo_origem.v07_parcel = certter.v14_parcel \n';
            sSqlRetorno := sSqlRetorno || '        inner join arreold 	    on arreold.k00_numpre	= termo_origem.v07_numpre      \n';
            sSqlRetorno := sSqlRetorno || '         left join arreoldcalc   on arreoldcalc.k00_numpre = arreold.k00_numpre       \n';
            sSqlRetorno := sSqlRetorno || '                                and arreoldcalc.k00_numpar = arreold.k00_numpar       \n';
            sSqlRetorno := sSqlRetorno || '                                and arreoldcalc.k00_receit = arreold.k00_receit       \n';
            sSqlRetorno := sSqlRetorno || '  where termo.v07_parcel = ' || iParcelamento||'\n';
            sSqlRetorno := sSqlRetorno || '  ) as x order by k00_dtvenc,k00_dtoper,k00_receit \n';

            elsif iTipo = 4 then -- parcelamento de diveros
            
            perform fc_debug('Tipo de Parcelamento 4 - termodiver: '                                                                                                 ,lRaise,false,false);
            perform fc_debug('Sql busca os dados da termo, termodiver, diversos e arreold'                                                                           ,lRaise,false,false);
            perform fc_debug('Nao eh realizado nenhum calculo aplicando correcao ou juro e multa sobre os valores'                                                   ,lRaise,false,false);
            perform fc_debug('Sao retornados como valor corrigido, juro e multa, respectivamente os campos,dv10_vlrcor,dv10_juros e dv10_multa da tabela termodiver' ,lRaise,false,false);
            
            sSqlRetorno :=                ' select k00_numcgm,                          \n'; 
            sSqlRetorno := sSqlRetorno || '        k00_dtoper,                          \n';
            sSqlRetorno := sSqlRetorno || '        k00_receit,                          \n';
            sSqlRetorno := sSqlRetorno || '        k00_hist,                            \n';
            sSqlRetorno := sSqlRetorno || '        k00_valor,                           \n';
            sSqlRetorno := sSqlRetorno || '        k00_dtvenc,                          \n';
            sSqlRetorno := sSqlRetorno || '        k00_numpre,                          \n';
            sSqlRetorno := sSqlRetorno || '        k00_numpar,                          \n';
            sSqlRetorno := sSqlRetorno || '        k00_numtot,                          \n';
            sSqlRetorno := sSqlRetorno || '        k00_numdig,                          \n';
            sSqlRetorno := sSqlRetorno || '        k00_tipo,                            \n';
            sSqlRetorno := sSqlRetorno || '        k00_tipojm,                          \n';
            sSqlRetorno := sSqlRetorno || '        termodiver.dv10_vlrcor as corrigido, \n';
            sSqlRetorno := sSqlRetorno || '        termodiver.dv10_juros  as juros,     \n';
            sSqlRetorno := sSqlRetorno || '        termodiver.dv10_multa  as multa      \n';
            sSqlRetorno := sSqlRetorno || '   from termo \n';
            sSqlRetorno := sSqlRetorno || '        inner join termodiver	on termo.v07_parcel      	= termodiver.dv10_parcel  \n';
            sSqlRetorno := sSqlRetorno || '        inner join diversos   on diversos.dv05_coddiver = termodiver.dv10_coddiver \n';
            sSqlRetorno := sSqlRetorno || '        inner join arreold   	on arreold.k00_numpre 	  = diversos.dv05_numpre    \n';
            sSqlRetorno := sSqlRetorno || '  where termo.v07_parcel = ' || iParcelamento||'\n';
            sSqlRetorno := sSqlRetorno || '  order by k00_dtoper,k00_dtvenc,k00_receit \n';
            
            elsif iTipo = 5 then -- parcelamento de contribuicao de melhorias
            
            perform fc_debug('Tipo de Parcelamento 5 - termocontrib: '                                                                                               ,lRaise,false,false);
            perform fc_debug('Sql busca os dados da termo, termocontrib, contricalc e arreold, '                                                                     ,lRaise,false,false);
            perform fc_debug('havendo um left com a divold apenas para garantir que nao virao registros que sao oriundos da divida'                                  ,lRaise,false,false);
            perform fc_debug('Nao eh realizado nenhum calculo aplicando correcao ou juro e multa sobre os valores'                                                   ,lRaise,false,false);
            perform fc_debug('Sao retornados como valor corrigido, juro e multa, respectivamente os campos,dv10_vlrcor,dv10_juros e dv10_multa da tabela termodiver' ,lRaise,false,false);
            
            sSqlRetorno :=                ' select k00_numcgm,                       \n'; 
            sSqlRetorno := sSqlRetorno || '        k00_dtoper,                       \n';
            sSqlRetorno := sSqlRetorno || '        k00_receit,                       \n';
            sSqlRetorno := sSqlRetorno || '        k00_hist,                         \n';
            sSqlRetorno := sSqlRetorno || '        k00_valor,                        \n';
            sSqlRetorno := sSqlRetorno || '        k00_dtvenc,                       \n';
            sSqlRetorno := sSqlRetorno || '        k00_numpre,                       \n';
            sSqlRetorno := sSqlRetorno || '        k00_numpar,                       \n';
            sSqlRetorno := sSqlRetorno || '        k00_numtot,                       \n';
            sSqlRetorno := sSqlRetorno || '        k00_numdig,                       \n';
            sSqlRetorno := sSqlRetorno || '        k00_tipo,                         \n';
            sSqlRetorno := sSqlRetorno || '        k00_tipojm,                       \n';
            sSqlRetorno := sSqlRetorno || '        termocontrib.vlrcor as corrigido, \n';
            sSqlRetorno := sSqlRetorno || '        termocontrib.juros  as juros,     \n';
            sSqlRetorno := sSqlRetorno || '        termocontrib.multa  as multa      \n';
            sSqlRetorno := sSqlRetorno || '   from termo                             \n';
            sSqlRetorno := sSqlRetorno || '        inner join termocontrib on termo.v07_parcel          = termocontrib.parcel     \n';
            sSqlRetorno := sSqlRetorno || '        inner join contricalc   on contricalc.d09_sequencial = termocontrib.contricalc \n';
            sSqlRetorno := sSqlRetorno || '        inner join arreold	     on arreold.k00_numpre        = contricalc.d09_numpre   \n';
                -- left com divold porque o numpre da contricalc pode estar na arreold tanto por parcelamento como por importacao de divida mais como o que interessa e so os 
                -- registros referente ao parcelamento dou um left com divold para garantir que nao vira registros que sao oriundos da divida
            sSqlRetorno := sSqlRetorno || '        left  join divold       on arreold.k00_numpre        = divold.k10_numpre  \n'; 
            sSqlRetorno := sSqlRetorno || '                               and arreold.k00_numpar        = divold.k10_numpar  \n';
            sSqlRetorno := sSqlRetorno || '                               and arreold.k00_receit        = divold.k10_receita \n';
            sSqlRetorno := sSqlRetorno || '   where ( divold.k10_numpre is null and divold.k10_numpar is null and divold.k10_receita is null ) \n';
            sSqlRetorno := sSqlRetorno || '     and termo.v07_parcel = ' || iParcelamento||'\n';
            sSqlRetorno := sSqlRetorno || '   order by k00_dtoper,k00_dtvenc,k00_receit \n';
            
        end if;
        
        return sSqlRetorno;
        
        end;
        $$  language 'plpgsql';
        ");
    }
}
