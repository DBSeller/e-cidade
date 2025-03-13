<?php

use Classes\PostgresMigration;

class M15289PlExcluiParcelamento extends PostgresMigration
{

    public function up()
    {

        $sql = <<<SQL
set check_function_bodies to on;
create or replace function fc_excluiparcelamento(integer,integer,text,integer)
returns varchar(100)
as $$
declare

  iCodTermoSimula				 alias for $1; -- parcelamento
  iCodUsuario						 alias for $2; -- login de quem anulou
  sMotivo                alias for $3; -- motivo da anulacao
  iCodProcesso           alias for $4; -- processo de protocolo

  iParcelamento          integer default 0;
  iNumpreParcelamento	   integer default 0;
  iSituacaoParcelamento  integer default 0; -- v07_situacao = 1-ativo 2-anulado 3-reparcelado
  iSeqTermoanu           integer default 0;
  iSeqTermoAnuSimula     integer default 0;
  iTipoParcelamento 	 	 integer default 0;
  iReparcelamento        integer default 0;
  iInstit         		 	 integer default 0;
  iIdInicialMov   		 	 integer default 0;
  iTipoAnuParc    		 	 integer default 0;
  iAnousu         		 	 integer default 0;
  iAbatimento            integer default 0;
  iArrecKey              integer default 0;
  iArrecadCompos         integer default 0;
  iAbatimentoArreckey    integer default 0;
  iNumpreReciboAvulso    integer default 0;

  record_inicial         record;

  nValorParcela          numeric default 0;
  nPercParcial           numeric default 0;
  nTotalComJurMul        numeric default 0;
  nPercRetorno           numeric default 0;
  nValorPago             numeric default 0;
  inicialTermo           integer default 0;

  lGeraAbatimento        boolean default false;

  sRetornoPag            text;
  sTipoParcelamento      text;
  sSql                   text;
  sSqlInicial            text;

  rTermoSimulaReg        record;
  rInicialMov            record;
  rAbatimento            record;
  rNumprePago            record;

	lRaise	           		 boolean default false;

begin

  lRaise  := ( case when fc_getsession('DB_debugon') is null then false else true end );
  iInstit := fc_getsession('DB_instit');
  if iInstit is null then
    raise exception 'Variavel de sessão instituição não encontrada.';
  end if;

  iAnousu := fc_getsession('DB_anousu');
  if iAnousu is null then
    raise exception 'Variavel de sessão exercicio não encontrada.';
  end if;

  perform fc_debug('INICIANDO ANULACAO DO PARCELAMENTO '||coalesce(iParcelamento,0)||'...',lRaise,true,false);
  perform fc_debug('Buscando dados da simulacao '||iCodTermoSimula,lRaise,true,false);

  select v21_parcel,
         v07_numpre,
         k40_tipoanulacao,
         v21_percretorno,
         v21_valorpago
    into iParcelamento,
         iNumpreParcelamento,
         iTipoAnuParc,
         nPercRetorno,
         nValorPago
    from termosimula
         inner join termo       on termo.v07_parcel = termosimula.v21_parcel
         inner join cadtipoparc on cadtipoparc.k40_codigo = termo.v07_desconto
   where v21_sequencial = iCodTermoSimula;


  perform fc_debug('Inserindo dados da anulação do parcelamento na termoanu',lRaise,true,false);
  iSeqTermoanu := nextval('termoanu_v09_sequencial_seq');
  insert into termoanu (v09_sequencial,v09_parcel,v09_usuario,v09_data,v09_hora,v09_motivo)
               values  (iSeqTermoanu,iParcelamento,iCodUsuario,current_date::date,current_time::char(5),sMotivo);

  if iCodProcesso is not null then

    perform fc_debug('Inserindo o processo de protocolo',lRaise,true,false);
    insert into termoanuproc (v22_sequencial,v22_termoanu,v22_processo)
    values(nextval('termoanuproc_v22_sequencial_seq'),iSeqTermoanu,iCodProcesso);

  end if;

  perform fc_debug('Gerando tabela de ligacao da simulacao com a anulacao do parcelamento (termoanusimula)...',lRaise,true,false);
  iSeqTermoAnuSimula := nextval('termoanusimula_v20_sequencial_seq');
  insert into termoanusimula ( v20_sequencial,
                               v20_termosimula,
                               v20_termoanu )
                      values ( iSeqTermoAnuSimula,
                               iCodTermoSimula,
                               iSeqTermoanu );


  perform fc_debug('Buscando o tipo do parcelamento...',lRaise,true,false);

  sTipoParcelamento := fc_parc_gettipoparcelamento(iParcelamento);

  iTipoParcelamento := ( case
                           when sTipoParcelamento is not null and sTipoParcelamento = 'termoreparc'  then 2
                           when sTipoParcelamento is not null and sTipoParcelamento = 'termodiv'     then 1
                           when sTipoParcelamento is not null and sTipoParcelamento = 'termoini'     then 3
                           when sTipoParcelamento is not null and sTipoParcelamento = 'termodiver'   then 4
                           when sTipoParcelamento is not null and sTipoParcelamento = 'termocontrib' then 5
                         end );
  perform fc_debug('Tipo do Parcelamento'||iTipoParcelamento||' - '||sTipoParcelamento,lRaise,true,false);

    /*******************************************************************************************************************
     *  GERA ABATIMENTOS
     ******************************************************************************************************************/
    --
    -- Verifica se existe abatimentos sendo eles compensação
    --

  if nValorPago > 0 then

    lGeraAbatimento := true;

    if lRaise is true then
      perform fc_debug('GERANDO ABATIMENTO (COMPENSACAO)...',lRaise,true,false);
    end if;

  end if;

  if lGeraAbatimento is true then

      -- Insere Abatimento
       select nextval('abatimento_k125_sequencial_seq')
              into iAbatimento;

      insert into abatimento ( k125_sequencial,
                               k125_tipoabatimento,
                               k125_datalanc,
                               k125_hora,
                               k125_usuario,
                               k125_instit,
                               k125_valor,
                               k125_perc
                             ) values (
                               iAbatimento,
                               4,
                               cast(fc_getsession('DB_datausu') as date),
                               to_char(current_timestamp,'HH24:MI'),
                               cast(fc_getsession('DB_id_usuario') as integer),
                               iInstit,
                               nValorPago,
                               nPercRetorno
                             );

      -- Gera um Recibo Avulso
      select nextval('numpref_k03_numpre_seq')
        into iNumpreReciboAvulso;


      insert into abatimentorecibo ( k127_sequencial,
                                     k127_abatimento,
                                     k127_numprerecibo,
                                     k127_numpreoriginal
                                   ) values (
                                     nextval('abatimentorecibo_k127_sequencial_seq'),
                                     iAbatimento,
                                     iNumpreReciboAvulso,
                                     iNumpreParcelamento
                                   );

  end if;

  --
  -- For buscando os registros da termosimulareg e gerando arrecad
  --
  for rTermoSimulaReg in select * from termosimulareg where v23_termosimula = iCodTermoSimula
  loop

  /*******************************************************************************************************************
   *
   *  Geração da compensação dos pagamentos efetuados para o parcelamento
   *
   ******************************************************************************************************************/

          select arreckey.k00_sequencial,
                 arrecadcompos.k00_sequencial
            into iArreckey,
                 iArrecadCompos
            from arreckey
                 left join arrecadcompos on arrecadcompos.k00_arreckey = arreckey.k00_sequencial
           where k00_numpre = rTermoSimulaReg.v23_numpre
             and k00_numpar = rTermoSimulaReg.v23_numpar
             and k00_receit = rTermoSimulaReg.v23_receit
             and k00_hist   = rTermoSimulaReg.v23_hist;

          if iArreckey is null then

            select nextval('arreckey_k00_sequencial_seq')
              into iArreckey;
           raise notice 'inserindo arreckey';
            insert into arreckey ( k00_sequencial,
                                   k00_numpre,
                                   k00_numpar,
                                   k00_receit,
                                   k00_hist,
                                   k00_tipo
                                 ) values (
                                   iArreckey,
                                   rTermoSimulaReg.v23_numpre,
                                   rTermoSimulaReg.v23_numpar,
                                   rTermoSimulaReg.v23_receit,
                                   rTermoSimulaReg.v23_hist,
                                   rTermoSimulaReg.v23_tipo
                                 );

          end if;


          if lGeraAbatimento is true then

             -- Insere ligação do abatimento com o débito
             select nextval('abatimentoarreckey_k128_sequencial_seq')
               into iAbatimentoArreckey;

             -- raise notice 'inserindo abatimentoarreckey';
             insert into abatimentoarreckey ( k128_sequencial,
                                              k128_arreckey,
                                              k128_abatimento,
                                              k128_valorabatido,
                                              k128_correcao,
                                              k128_juros,
                                              k128_multa
                                            ) values (
                                              iAbatimentoArreckey,
                                              iArreckey,
                                              iAbatimento,
                                              rTermoSimulaReg.v23_vlrabatido,
                                              rTermoSimulaReg.v23_vlrcor,
                                              rTermoSimulaReg.v23_vlrjur,
                                              rTermoSimulaReg.v23_vlrmul
                                            );

             if iArrecadCompos is not null then

               -- raise notice 'inserindo abatimentoarreckeyarrecadcompos';
               insert into abatimentoarreckeyarrecadcompos ( k129_sequencial,
                                                             k129_abatimentoarreckey,
                                                             k129_arrecadcompos,
                                                             k129_vlrhist,
                                                             k129_correcao,
                                                             k129_juros,
                                                             k129_multa
                                                           ) values (
                                                             nextval('abatimentoarreckeyarrecadcompos_k129_sequencial_seq'),
                                                             iAbatimentoArreckey,
                                                             iArrecadCompos,
                                                             rTermoSimulaReg.v23_valor,
                                                             rTermoSimulaReg.v23_vlrcor,
                                                             rTermoSimulaReg.v23_vlrjur,
                                                             rTermoSimulaReg.v23_vlrmul
                                                           );
             end if;

          end if;
  /*******************************************************************************************************************
   *
   *  FIM da geração da compensação dos pagamentos efetuados para o parcelamento
   *
   ******************************************************************************************************************/


    perform k00_numpre from arreold where k00_numpre = rTermoSimulaReg.v23_numpre;
    if found then

      perform fc_debug('Excluindo dados do numpre '||rTermoSimulaReg.v23_numpre||' da tabela arreold',lRaise,true,false);
      delete from arreold where k00_numpre = rTermoSimulaReg.v23_numpre;

    end if;


    perform fc_debug(''                              ,lRaise,true,false);
    perform fc_debug('Calculando valor da Parcela...',lRaise,true,false);
    perform fc_debug('Tipo de Anulaca: '||iTipoAnuParc,lRaise,true,false);

    nTotalComJurMul := ( rTermoSimulaReg.v23_vlrcor + rTermoSimulaReg.v23_vlrjur + rTermoSimulaReg.v23_vlrmul );

    perform fc_debug('Total com Juro e Multa ..:'||nTotalComJurMul,lRaise,true,false);
    if (iTipoAnuParc = 3  or iTipoAnuParc = 2) and nTotalComJurMul > rTermoSimulaReg.v23_vlrabatido then

      nPercParcial  := ( ( rTermoSimulaReg.v23_vlrabatido * 100 ) / nTotalComJurMul );
      perform fc_debug('Percentual para calculo ..:'||'( ( '||rTermoSimulaReg.v23_vlrabatido||' * 100 ) / '||nTotalComJurMul||' ) = '||nPercParcial,lRaise,true,false);

      nValorParcela := ( rTermoSimulaReg.v23_valor - ( ( rTermoSimulaReg.v23_valor * nPercParcial ) / 100 ) );
      perform fc_debug('Valor da Parcela .........:'||'( '||rTermoSimulaReg.v23_valor||' - ( ( '||rTermoSimulaReg.v23_valor||' * '||nPercParcial||' ) / 100 ) ) = '||nValorParcela,lRaise,true,false);

    else

      nValorParcela := ( rTermoSimulaReg.v23_valor - rTermoSimulaReg.v23_vlrabatido );
      perform fc_debug('Valor da Parcela .........:'||rTermoSimulaReg.v23_valor||' - '||rTermoSimulaReg.v23_vlrabatido||' = '||nValorParcela,lRaise,true,false);

    end if;




  /*******************************************************************************************************************
   *
   *  Processamento do retorno das origens do parcelamento para o arrecad
   *
   *  Somente são inseridas no arrecad as parcelas com o valor maior que zero, as parcelas já quitadas são inseridas na
   *  tabela arrecantpgtoparcial para o abatimento(compensacao) gerado.
   *
   ******************************************************************************************************************/

    if round(nValorParcela,2) > 0 then

	    insert into arrecad ( k00_numpre,
	                          k00_numpar,
	                          k00_numcgm,
	                          k00_dtoper,
	                          k00_receit,
	                          k00_hist,
	                          k00_valor,
	                          k00_dtvenc,
	                          k00_numtot,
	                          k00_numdig,
	                          k00_tipo,
	                          k00_tipojm )
	                 values ( rTermoSimulaReg.v23_numpre,
	                          rTermoSimulaReg.v23_numpar,
	                          rTermoSimulaReg.v23_numcgm,
	                          rTermoSimulaReg.v23_dtoper,
	                          rTermoSimulaReg.v23_receit,
	                          rTermoSimulaReg.v23_hist,
	                          round(nValorParcela,2),
	                          rTermoSimulaReg.v23_dtvenc,
	                          rTermoSimulaReg.v23_numtot,
	                          rTermoSimulaReg.v23_numdig,
	                          rTermoSimulaReg.v23_tipo,
	                          rTermoSimulaReg.v23_tipojm );

    else

      if lGeraAbatimento is true then

         insert into arrecantpgtoparcial ( k00_numpre,
                                           k00_numpar,
                                           k00_numcgm,
                                           k00_dtoper,
                                           k00_receit,
                                           k00_hist,
                                           k00_valor,
                                           k00_dtvenc,
                                           k00_numtot,
                                           k00_numdig,
                                           k00_tipo,
                                           k00_tipojm,
                                           k00_abatimento
                                         ) values (
                                           rTermoSimulaReg.v23_numpre,
                                           rTermoSimulaReg.v23_numpar,
                                           rTermoSimulaReg.v23_numcgm,
                                           rTermoSimulaReg.v23_dtoper,
                                           rTermoSimulaReg.v23_receit,
                                           rTermoSimulaReg.v23_hist,
                                           rTermoSimulaReg.v23_valor,
                                           rTermoSimulaReg.v23_dtvenc,
                                           rTermoSimulaReg.v23_numtot,
                                           rTermoSimulaReg.v23_numdig,
                                           rTermoSimulaReg.v23_tipo,
                                           rTermoSimulaReg.v23_tipojm,
                                           iAbatimento
                                         );
      end if;

    end if;

  end loop;


  /*******************************************************************************************************************
   *
   *  Geração do recibo avulso para os dados da compensação
   *
   ******************************************************************************************************************/
  if lGeraAbatimento is true then

       for rAbatimento in select arreckey.k00_tipo                         as tipo,
                                 arreckey.k00_receit                       as receit,
                                 sum(abatimentoarreckey.k128_valorabatido) as vlrAbatido
                            from arreckey
                                 inner join abatimentoarreckey on abatimentoarreckey.k128_arreckey = arreckey.k00_sequencial
                           where abatimentoarreckey.k128_abatimento = iAbatimento
                           group by arreckey.k00_tipo,
                                    arreckey.k00_receit
       loop

	       insert into recibo ( k00_numcgm,
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
	                            k00_codsubrec,
	                            k00_numnov
	                          ) values (
	                            rTermoSimulaReg.v23_numcgm,
	                            cast(fc_getsession('DB_datausu') as date),
	                            rAbatimento.receit,
	                            506,
	                            rAbatimento.vlrabatido,
	                            cast(fc_getsession('DB_datausu') as date),
	                            iNumpreReciboAvulso,
	                            1,
	                            1,
	                            0,
	                            rAbatimento.tipo,
	                            0,
	                            0,
	                            0
	                          );

       end loop;

       insert into arrehist ( k00_numpre,
                              k00_numpar,
                              k00_hist,
                              k00_dtoper,
                              k00_hora,
                              k00_id_usuario,
                              k00_histtxt,
                              k00_limithist,
                              k00_idhist
                            ) values (
                              iNumpreReciboAvulso,
                              1,
                              506,
                              cast(fc_getsession('DB_datausu') as date),
                              '00:00',
                              1,
                              'Recibo avulso referente compensação do Parcelamento: ' || iParcelamento,
                              null,
                              nextval('arrehist_k00_idhist_seq')
                            );

       insert into arrenumcgm (k00_numpre, k00_numcgm)
                       select distinct
                              iNumpreReciboAvulso,
                              arrenumcgm.k00_numcgm
                         from arrenumcgm
                        where arrenumcgm.k00_numpre = iNumpreParcelamento;


       insert into arrematric (k00_numpre, k00_matric, k00_perc)
                       select distinct
                              iNumpreReciboAvulso,
                              arrematric.k00_matric,
                              arrematric.k00_perc
                         from arrematric
                        where arrematric.k00_numpre = iNumpreParcelamento;


       insert into arreinscr (k00_numpre, k00_inscr, k00_perc)
                      select distinct
                             iNumpreReciboAvulso,
                             arreinscr.k00_inscr,
                             arreinscr.k00_perc
                        from arreinscr
                       where arreinscr.k00_numpre = iNumpreParcelamento;

  end if;
  /*******************************************************************************************************************
   *
   *  FIM da Geração do recibo avulso para os dados da compensação
   *
   ******************************************************************************************************************/



  --
  -- Funcao fc_parc_getselectorigens(iParcelamento,iTipoParcelamento) retorna sql com as origens do parcelamento(arreold)
  --
  sSql := fc_parc_getselectorigens(iParcelamento,iTipoParcelamento);

  if iTipoParcelamento = 3 then  -- parcelamento de inicial

    -- select para varrer apenas as iniciais
    sSqlInicial = 'select distinct inicial from (' || sSql || ') as x';
    -- varrendo inicial por inicial
    for rInicialMov in execute sSqlInicial
    loop
      -- inserindo na inicialmov registro de movimento 6: inicial com parcelamento cancelado
      iIdInicialMov := nextval('inicialmov_v56_codmov_seq');
      insert into inicialmov values (iIdInicialMov,rInicialMov.inicial,6,'',current_date,iCodUsuario);
      update inicial
         set v50_codmov = iIdInicialMov
       where v50_inicial = rInicialMov.inicial;

    end loop;
  end if;

  perform fc_debug('Deletando numpre : '||coalesce(iNumpreParcelamento,0)||' do arrecad e inserindo no arreold ',lRaise,false,false);

  -- insere no arreold os registros atuais do arrecad
  insert into arreold
	select k00_numpre,k00_numpar,k00_numcgm,k00_dtoper,k00_receit,k00_hist,k00_valor,k00_dtvenc,k00_numtot,k00_numdig,k00_tipo,k00_tipojm
	  from arrecad
   where k00_numpre = iNumpreParcelamento;

  -- deleta do arrecad os registros atuais
  delete from arrecad
   where k00_numpre = iNumpreParcelamento;

  -- registrando o parcelamento como anulado
  update termo
     set v07_situacao = 2
   where v07_parcel = iParcelamento;

  if iReparcelamento is not null and iTipoParcelamento = 2 then
		-- ativa o parcelamento de origem
    update termo
       set v07_situacao = 1
     where v07_parcel = iReparcelamento;
  end if;


  for record_inicial in select inicial from termoini where parcel = iParcelamento
  loop
      insert into inicialmov
           values (nextval('inicialmov_v56_codmov_seq'), record_inicial.inicial, 1, sMotivo, fc_getsession('db_datausu')::date, iCodUsuario);

      update inicial
         set v50_codmov = currval('inicialmov_v56_codmov_seq')
       where v50_inicial = record_inicial.inicial;

  end loop;

  -- Deletando víncilo de numpres que já foram pagos para Realizar reemissão de inicial
  for rNumprePago in select distinct inicialnumpre.v59_numpre from termoini
    inner join termo on parcel = v07_parcel
    inner join arrecant on v07_numpre = k00_numpre
    inner join inicialnumpre on v59_inicial = inicial
    where parcel = iParcelamento and not exists (select 1 from arrecad where arrecad.k00_numpre = v59_numpre)
  loop
    delete from inicialnumpre where v59_numpre = rNumprePago.v59_numpre;
  end loop;

  raise notice '%',fc_debug('Processamento concluido !',lRaise,false,true);
  return '1 - OK';

end;

$$ language 'plpgsql';

SQL;
        $this->execute($sql);
    }

    public function down()
    {
        $sql = <<<SQL
set check_function_bodies to on;
create or replace function fc_excluiparcelamento(integer,integer,text,integer)
returns varchar(100)
as $$
declare

  iCodTermoSimula				 alias for $1; -- parcelamento
  iCodUsuario						 alias for $2; -- login de quem anulou
  sMotivo                alias for $3; -- motivo da anulacao
  iCodProcesso           alias for $4; -- processo de protocolo

  iParcelamento          integer default 0;
  iNumpreParcelamento	   integer default 0;
  iSituacaoParcelamento  integer default 0; -- v07_situacao = 1-ativo 2-anulado 3-reparcelado
  iSeqTermoanu           integer default 0;
  iSeqTermoAnuSimula     integer default 0;
  iTipoParcelamento 	 	 integer default 0;
  iReparcelamento        integer default 0;
  iInstit         		 	 integer default 0;
  iIdInicialMov   		 	 integer default 0;
  iTipoAnuParc    		 	 integer default 0;
  iAnousu         		 	 integer default 0;
  iAbatimento            integer default 0;
  iArrecKey              integer default 0;
  iArrecadCompos         integer default 0;
  iAbatimentoArreckey    integer default 0;
  iNumpreReciboAvulso    integer default 0;

  record_inicial         record;

  nValorParcela          numeric default 0;
  nPercParcial           numeric default 0;
  nTotalComJurMul        numeric default 0;
  nPercRetorno           numeric default 0;
  nValorPago             numeric default 0;
  inicialTermo           integer default 0;

  lGeraAbatimento        boolean default false;

  sRetornoPag            text;
  sTipoParcelamento      text;
  sSql                   text;
  sSqlInicial            text;

  rTermoSimulaReg        record;
  rInicialMov            record;
  rAbatimento            record;

	lRaise	           		 boolean default false;

begin

  lRaise  := ( case when fc_getsession('DB_debugon') is null then false else true end );
  iInstit := fc_getsession('DB_instit');
  if iInstit is null then
    raise exception 'Variavel de sessão instituição não encontrada.';
  end if;

  iAnousu := fc_getsession('DB_anousu');
  if iAnousu is null then
    raise exception 'Variavel de sessão exercicio não encontrada.';
  end if;

  perform fc_debug('INICIANDO ANULACAO DO PARCELAMENTO '||coalesce(iParcelamento,0)||'...',lRaise,true,false);
  perform fc_debug('Buscando dados da simulacao '||iCodTermoSimula,lRaise,true,false);

  select v21_parcel,
         v07_numpre,
         k40_tipoanulacao,
         v21_percretorno,
         v21_valorpago
    into iParcelamento,
         iNumpreParcelamento,
         iTipoAnuParc,
         nPercRetorno,
         nValorPago
    from termosimula
         inner join termo       on termo.v07_parcel = termosimula.v21_parcel
         inner join cadtipoparc on cadtipoparc.k40_codigo = termo.v07_desconto
   where v21_sequencial = iCodTermoSimula;


  perform fc_debug('Inserindo dados da anulação do parcelamento na termoanu',lRaise,true,false);
  iSeqTermoanu := nextval('termoanu_v09_sequencial_seq');
  insert into termoanu (v09_sequencial,v09_parcel,v09_usuario,v09_data,v09_hora,v09_motivo)
               values  (iSeqTermoanu,iParcelamento,iCodUsuario,current_date::date,current_time::char(5),sMotivo);

  if iCodProcesso is not null then

    perform fc_debug('Inserindo o processo de protocolo',lRaise,true,false);
    insert into termoanuproc (v22_sequencial,v22_termoanu,v22_processo)
    values(nextval('termoanuproc_v22_sequencial_seq'),iSeqTermoanu,iCodProcesso);

  end if;

  perform fc_debug('Gerando tabela de ligacao da simulacao com a anulacao do parcelamento (termoanusimula)...',lRaise,true,false);
  iSeqTermoAnuSimula := nextval('termoanusimula_v20_sequencial_seq');
  insert into termoanusimula ( v20_sequencial,
                               v20_termosimula,
                               v20_termoanu )
                      values ( iSeqTermoAnuSimula,
                               iCodTermoSimula,
                               iSeqTermoanu );


  perform fc_debug('Buscando o tipo do parcelamento...',lRaise,true,false);

  sTipoParcelamento := fc_parc_gettipoparcelamento(iParcelamento);

  iTipoParcelamento := ( case
                           when sTipoParcelamento is not null and sTipoParcelamento = 'termoreparc'  then 2
                           when sTipoParcelamento is not null and sTipoParcelamento = 'termodiv'     then 1
                           when sTipoParcelamento is not null and sTipoParcelamento = 'termoini'     then 3
                           when sTipoParcelamento is not null and sTipoParcelamento = 'termodiver'   then 4
                           when sTipoParcelamento is not null and sTipoParcelamento = 'termocontrib' then 5
                         end );
  perform fc_debug('Tipo do Parcelamento'||iTipoParcelamento||' - '||sTipoParcelamento,lRaise,true,false);

    /*******************************************************************************************************************
     *  GERA ABATIMENTOS
     ******************************************************************************************************************/
    --
    -- Verifica se existe abatimentos sendo eles compensação
    --

  if nValorPago > 0 then

    lGeraAbatimento := true;

    if lRaise is true then
      perform fc_debug('GERANDO ABATIMENTO (COMPENSACAO)...',lRaise,true,false);
    end if;

  end if;

  if lGeraAbatimento is true then

      -- Insere Abatimento
       select nextval('abatimento_k125_sequencial_seq')
              into iAbatimento;

      insert into abatimento ( k125_sequencial,
                               k125_tipoabatimento,
                               k125_datalanc,
                               k125_hora,
                               k125_usuario,
                               k125_instit,
                               k125_valor,
                               k125_perc
                             ) values (
                               iAbatimento,
                               4,
                               cast(fc_getsession('DB_datausu') as date),
                               to_char(current_timestamp,'HH24:MI'),
                               cast(fc_getsession('DB_id_usuario') as integer),
                               iInstit,
                               nValorPago,
                               nPercRetorno
                             );

      -- Gera um Recibo Avulso
      select nextval('numpref_k03_numpre_seq')
        into iNumpreReciboAvulso;


      insert into abatimentorecibo ( k127_sequencial,
                                     k127_abatimento,
                                     k127_numprerecibo,
                                     k127_numpreoriginal
                                   ) values (
                                     nextval('abatimentorecibo_k127_sequencial_seq'),
                                     iAbatimento,
                                     iNumpreReciboAvulso,
                                     iNumpreParcelamento
                                   );

  end if;

  --
  -- For buscando os registros da termosimulareg e gerando arrecad
  --
  for rTermoSimulaReg in select * from termosimulareg where v23_termosimula = iCodTermoSimula
  loop

  /*******************************************************************************************************************
   *
   *  Geração da compensação dos pagamentos efetuados para o parcelamento
   *
   ******************************************************************************************************************/

          select arreckey.k00_sequencial,
                 arrecadcompos.k00_sequencial
            into iArreckey,
                 iArrecadCompos
            from arreckey
                 left join arrecadcompos on arrecadcompos.k00_arreckey = arreckey.k00_sequencial
           where k00_numpre = rTermoSimulaReg.v23_numpre
             and k00_numpar = rTermoSimulaReg.v23_numpar
             and k00_receit = rTermoSimulaReg.v23_receit
             and k00_hist   = rTermoSimulaReg.v23_hist;

          if iArreckey is null then

            select nextval('arreckey_k00_sequencial_seq')
              into iArreckey;
           raise notice 'inserindo arreckey';
            insert into arreckey ( k00_sequencial,
                                   k00_numpre,
                                   k00_numpar,
                                   k00_receit,
                                   k00_hist,
                                   k00_tipo
                                 ) values (
                                   iArreckey,
                                   rTermoSimulaReg.v23_numpre,
                                   rTermoSimulaReg.v23_numpar,
                                   rTermoSimulaReg.v23_receit,
                                   rTermoSimulaReg.v23_hist,
                                   rTermoSimulaReg.v23_tipo
                                 );

          end if;


          if lGeraAbatimento is true then

             -- Insere ligação do abatimento com o débito
             select nextval('abatimentoarreckey_k128_sequencial_seq')
               into iAbatimentoArreckey;

             -- raise notice 'inserindo abatimentoarreckey';
             insert into abatimentoarreckey ( k128_sequencial,
                                              k128_arreckey,
                                              k128_abatimento,
                                              k128_valorabatido,
                                              k128_correcao,
                                              k128_juros,
                                              k128_multa
                                            ) values (
                                              iAbatimentoArreckey,
                                              iArreckey,
                                              iAbatimento,
                                              rTermoSimulaReg.v23_vlrabatido,
                                              rTermoSimulaReg.v23_vlrcor,
                                              rTermoSimulaReg.v23_vlrjur,
                                              rTermoSimulaReg.v23_vlrmul
                                            );

             if iArrecadCompos is not null then

               -- raise notice 'inserindo abatimentoarreckeyarrecadcompos';
               insert into abatimentoarreckeyarrecadcompos ( k129_sequencial,
                                                             k129_abatimentoarreckey,
                                                             k129_arrecadcompos,
                                                             k129_vlrhist,
                                                             k129_correcao,
                                                             k129_juros,
                                                             k129_multa
                                                           ) values (
                                                             nextval('abatimentoarreckeyarrecadcompos_k129_sequencial_seq'),
                                                             iAbatimentoArreckey,
                                                             iArrecadCompos,
                                                             rTermoSimulaReg.v23_valor,
                                                             rTermoSimulaReg.v23_vlrcor,
                                                             rTermoSimulaReg.v23_vlrjur,
                                                             rTermoSimulaReg.v23_vlrmul
                                                           );
             end if;

          end if;
  /*******************************************************************************************************************
   *
   *  FIM da geração da compensação dos pagamentos efetuados para o parcelamento
   *
   ******************************************************************************************************************/


    perform k00_numpre from arreold where k00_numpre = rTermoSimulaReg.v23_numpre;
    if found then

      perform fc_debug('Excluindo dados do numpre '||rTermoSimulaReg.v23_numpre||' da tabela arreold',lRaise,true,false);
      delete from arreold where k00_numpre = rTermoSimulaReg.v23_numpre;

    end if;


    perform fc_debug(''                              ,lRaise,true,false);
    perform fc_debug('Calculando valor da Parcela...',lRaise,true,false);
    perform fc_debug('Tipo de Anulaca: '||iTipoAnuParc,lRaise,true,false);

    nTotalComJurMul := ( rTermoSimulaReg.v23_vlrcor + rTermoSimulaReg.v23_vlrjur + rTermoSimulaReg.v23_vlrmul );

    perform fc_debug('Total com Juro e Multa ..:'||nTotalComJurMul,lRaise,true,false);
    if (iTipoAnuParc = 3  or iTipoAnuParc = 2) and nTotalComJurMul > rTermoSimulaReg.v23_vlrabatido then

      nPercParcial  := ( ( rTermoSimulaReg.v23_vlrabatido * 100 ) / nTotalComJurMul );
      perform fc_debug('Percentual para calculo ..:'||'( ( '||rTermoSimulaReg.v23_vlrabatido||' * 100 ) / '||nTotalComJurMul||' ) = '||nPercParcial,lRaise,true,false);

      nValorParcela := ( rTermoSimulaReg.v23_valor - ( ( rTermoSimulaReg.v23_valor * nPercParcial ) / 100 ) );
      perform fc_debug('Valor da Parcela .........:'||'( '||rTermoSimulaReg.v23_valor||' - ( ( '||rTermoSimulaReg.v23_valor||' * '||nPercParcial||' ) / 100 ) ) = '||nValorParcela,lRaise,true,false);

    else

      nValorParcela := ( rTermoSimulaReg.v23_valor - rTermoSimulaReg.v23_vlrabatido );
      perform fc_debug('Valor da Parcela .........:'||rTermoSimulaReg.v23_valor||' - '||rTermoSimulaReg.v23_vlrabatido||' = '||nValorParcela,lRaise,true,false);

    end if;




  /*******************************************************************************************************************
   *
   *  Processamento do retorno das origens do parcelamento para o arrecad
   *
   *  Somente são inseridas no arrecad as parcelas com o valor maior que zero, as parcelas já quitadas são inseridas na
   *  tabela arrecantpgtoparcial para o abatimento(compensacao) gerado.
   *
   ******************************************************************************************************************/

    if round(nValorParcela,2) > 0 then

	    insert into arrecad ( k00_numpre,
	                          k00_numpar,
	                          k00_numcgm,
	                          k00_dtoper,
	                          k00_receit,
	                          k00_hist,
	                          k00_valor,
	                          k00_dtvenc,
	                          k00_numtot,
	                          k00_numdig,
	                          k00_tipo,
	                          k00_tipojm )
	                 values ( rTermoSimulaReg.v23_numpre,
	                          rTermoSimulaReg.v23_numpar,
	                          rTermoSimulaReg.v23_numcgm,
	                          rTermoSimulaReg.v23_dtoper,
	                          rTermoSimulaReg.v23_receit,
	                          rTermoSimulaReg.v23_hist,
	                          round(nValorParcela,2),
	                          rTermoSimulaReg.v23_dtvenc,
	                          rTermoSimulaReg.v23_numtot,
	                          rTermoSimulaReg.v23_numdig,
	                          rTermoSimulaReg.v23_tipo,
	                          rTermoSimulaReg.v23_tipojm );

    else

      if lGeraAbatimento is true then

         insert into arrecantpgtoparcial ( k00_numpre,
                                           k00_numpar,
                                           k00_numcgm,
                                           k00_dtoper,
                                           k00_receit,
                                           k00_hist,
                                           k00_valor,
                                           k00_dtvenc,
                                           k00_numtot,
                                           k00_numdig,
                                           k00_tipo,
                                           k00_tipojm,
                                           k00_abatimento
                                         ) values (
                                           rTermoSimulaReg.v23_numpre,
                                           rTermoSimulaReg.v23_numpar,
                                           rTermoSimulaReg.v23_numcgm,
                                           rTermoSimulaReg.v23_dtoper,
                                           rTermoSimulaReg.v23_receit,
                                           rTermoSimulaReg.v23_hist,
                                           rTermoSimulaReg.v23_valor,
                                           rTermoSimulaReg.v23_dtvenc,
                                           rTermoSimulaReg.v23_numtot,
                                           rTermoSimulaReg.v23_numdig,
                                           rTermoSimulaReg.v23_tipo,
                                           rTermoSimulaReg.v23_tipojm,
                                           iAbatimento
                                         );
      end if;

    end if;

  end loop;


  /*******************************************************************************************************************
   *
   *  Geração do recibo avulso para os dados da compensação
   *
   ******************************************************************************************************************/
  if lGeraAbatimento is true then

       for rAbatimento in select arreckey.k00_tipo                         as tipo,
                                 arreckey.k00_receit                       as receit,
                                 sum(abatimentoarreckey.k128_valorabatido) as vlrAbatido
                            from arreckey
                                 inner join abatimentoarreckey on abatimentoarreckey.k128_arreckey = arreckey.k00_sequencial
                           where abatimentoarreckey.k128_abatimento = iAbatimento
                           group by arreckey.k00_tipo,
                                    arreckey.k00_receit
       loop

	       insert into recibo ( k00_numcgm,
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
	                            k00_codsubrec,
	                            k00_numnov
	                          ) values (
	                            rTermoSimulaReg.v23_numcgm,
	                            cast(fc_getsession('DB_datausu') as date),
	                            rAbatimento.receit,
	                            506,
	                            rAbatimento.vlrabatido,
	                            cast(fc_getsession('DB_datausu') as date),
	                            iNumpreReciboAvulso,
	                            1,
	                            1,
	                            0,
	                            rAbatimento.tipo,
	                            0,
	                            0,
	                            0
	                          );

       end loop;

       insert into arrehist ( k00_numpre,
                              k00_numpar,
                              k00_hist,
                              k00_dtoper,
                              k00_hora,
                              k00_id_usuario,
                              k00_histtxt,
                              k00_limithist,
                              k00_idhist
                            ) values (
                              iNumpreReciboAvulso,
                              1,
                              506,
                              cast(fc_getsession('DB_datausu') as date),
                              '00:00',
                              1,
                              'Recibo avulso referente compensação do Parcelamento: ' || iParcelamento,
                              null,
                              nextval('arrehist_k00_idhist_seq')
                            );

       insert into arrenumcgm (k00_numpre, k00_numcgm)
                       select distinct
                              iNumpreReciboAvulso,
                              arrenumcgm.k00_numcgm
                         from arrenumcgm
                        where arrenumcgm.k00_numpre = iNumpreParcelamento;


       insert into arrematric (k00_numpre, k00_matric, k00_perc)
                       select distinct
                              iNumpreReciboAvulso,
                              arrematric.k00_matric,
                              arrematric.k00_perc
                         from arrematric
                        where arrematric.k00_numpre = iNumpreParcelamento;


       insert into arreinscr (k00_numpre, k00_inscr, k00_perc)
                      select distinct
                             iNumpreReciboAvulso,
                             arreinscr.k00_inscr,
                             arreinscr.k00_perc
                        from arreinscr
                       where arreinscr.k00_numpre = iNumpreParcelamento;

  end if;
  /*******************************************************************************************************************
   *
   *  FIM da Geração do recibo avulso para os dados da compensação
   *
   ******************************************************************************************************************/



  --
  -- Funcao fc_parc_getselectorigens(iParcelamento,iTipoParcelamento) retorna sql com as origens do parcelamento(arreold)
  --
  sSql := fc_parc_getselectorigens(iParcelamento,iTipoParcelamento);

  if iTipoParcelamento = 3 then  -- parcelamento de inicial

    -- select para varrer apenas as iniciais
    sSqlInicial = 'select distinct inicial from (' || sSql || ') as x';
    -- varrendo inicial por inicial
    for rInicialMov in execute sSqlInicial
    loop
      -- inserindo na inicialmov registro de movimento 6: inicial com parcelamento cancelado
      iIdInicialMov := nextval('inicialmov_v56_codmov_seq');
      insert into inicialmov values (iIdInicialMov,rInicialMov.inicial,6,'',current_date,iCodUsuario);
      update inicial
         set v50_codmov = iIdInicialMov
       where v50_inicial = rInicialMov.inicial;

    end loop;
  end if;

  perform fc_debug('Deletando numpre : '||coalesce(iNumpreParcelamento,0)||' do arrecad e inserindo no arreold ',lRaise,false,false);

  -- insere no arreold os registros atuais do arrecad
  insert into arreold
	select k00_numpre,k00_numpar,k00_numcgm,k00_dtoper,k00_receit,k00_hist,k00_valor,k00_dtvenc,k00_numtot,k00_numdig,k00_tipo,k00_tipojm
	  from arrecad
   where k00_numpre = iNumpreParcelamento;

  -- deleta do arrecad os registros atuais
  delete from arrecad
   where k00_numpre = iNumpreParcelamento;

  -- registrando o parcelamento como anulado
  update termo
     set v07_situacao = 2
   where v07_parcel = iParcelamento;

  if iReparcelamento is not null and iTipoParcelamento = 2 then
		-- ativa o parcelamento de origem
    update termo
       set v07_situacao = 1
     where v07_parcel = iReparcelamento;
  end if;


  for record_inicial in select inicial from termoini where parcel = iParcelamento
  loop
      insert into inicialmov
           values (nextval('inicialmov_v56_codmov_seq'), record_inicial.inicial, 1, sMotivo, fc_getsession('db_datausu')::date, iCodUsuario);

      update inicial
         set v50_codmov = currval('inicialmov_v56_codmov_seq')
       where v50_inicial = record_inicial.inicial;

  end loop;

  raise notice '%',fc_debug('Processamento concluido !',lRaise,false,true);
  return '1 - OK';

end;

$$ language 'plpgsql';

SQL;

        $this->execute($sql);
    }
}
