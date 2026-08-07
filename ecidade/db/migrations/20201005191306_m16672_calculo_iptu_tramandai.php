<?php

use Classes\PostgresMigration;

class M16672CalculoIptuTramandai extends PostgresMigration
{

    public function up()
    {
        $sql = <<<SQL_UP

               update db_sysfuncoes set nomefuncao = 'fc_calculoiptu_tramandai_2017_old' where nomefuncao = 'fc_calculoiptu_tramandai_2017';
               update db_sysfuncoes set nomefuncao = 'fc_iptu_taxalixo_tramandai_2017_old' where nomefuncao = 'fc_iptu_taxalixo_tramandai_2017';

               insert into db_sysfuncoes( codfuncao ,nomefuncao ,nomearquivo ,obsfuncao ,corpofuncao ,triggerfuncao ) values
                                        ( 203 ,'fc_calculoiptu_tramandai_2017' ,'calculoiptu_tramandai_2017.sql' ,'Cálculo de iptu de tramandaí.' ,'.' ,'0' );
               insert into db_sysfuncoesparam( db42_sysfuncoesparam ,db42_funcao ,db42_ordem ,db42_nome ,db42_tipo ,db42_tamanho ,db42_precisao ,db42_valor_default ,db42_descricao ) values
                                             ( 1102 ,203 ,1 ,'iMatricula' ,'int4' ,0 ,0 ,'' ,'MATRÍCULA DO IMÓVEL' );
               insert into db_sysfuncoesparam( db42_sysfuncoesparam ,db42_funcao ,db42_ordem ,db42_nome ,db42_tipo ,db42_tamanho ,db42_precisao ,db42_valor_default ,db42_descricao ) values
                                             ( 1103 ,203 ,2 ,'iAnousu' ,'int4' ,0 ,0 ,'' ,'ANO DO CÁLCULO' );
               insert into db_sysfuncoesparam( db42_sysfuncoesparam ,db42_funcao ,db42_ordem ,db42_nome ,db42_tipo ,db42_tamanho ,db42_precisao ,db42_valor_default ,db42_descricao ) values
                                             ( 1104 ,203 ,3 ,'lGerafinanc' ,'bool' ,0 ,0 ,'' ,'SE GERA FINANCEIRO' );
               insert into db_sysfuncoesparam( db42_sysfuncoesparam ,db42_funcao ,db42_ordem ,db42_nome ,db42_tipo ,db42_tamanho ,db42_precisao ,db42_valor_default ,db42_descricao ) values
                                             ( 1105 ,203 ,4 ,'lAtualizaParcela' ,'bool' ,0 ,0 ,'' ,'ATUALIZA PARCELAS' );
               insert into db_sysfuncoesparam( db42_sysfuncoesparam ,db42_funcao ,db42_ordem ,db42_nome ,db42_tipo ,db42_tamanho ,db42_precisao ,db42_valor_default ,db42_descricao ) values
                                             ( 1106 ,203 ,5 ,'lNovonumpre' ,'bool' ,0 ,0 ,'' ,'NOVO NUMPRE' );
               insert into db_sysfuncoesparam( db42_sysfuncoesparam ,db42_funcao ,db42_ordem ,db42_nome ,db42_tipo ,db42_tamanho ,db42_precisao ,db42_valor_default ,db42_descricao ) values
                                             ( 1107 ,203 ,6 ,'lCalculogeral' ,'bool' ,0 ,0 ,'' ,'SE CALCULO GERAL' );
               insert into db_sysfuncoesparam( db42_sysfuncoesparam ,db42_funcao ,db42_ordem ,db42_nome ,db42_tipo ,db42_tamanho ,db42_precisao ,db42_valor_default ,db42_descricao ) values
                                             ( 1108 ,203 ,7 ,'lDemonstrativo' ,'bool' ,0 ,0 ,'' ,'SE É DEMONSTRATIVO' );
               insert into db_sysfuncoesparam( db42_sysfuncoesparam ,db42_funcao ,db42_ordem ,db42_nome ,db42_tipo ,db42_tamanho ,db42_precisao ,db42_valor_default ,db42_descricao ) values
                                             ( 1109 ,203 ,8 ,'iParcelaini' ,'int4' ,0 ,0 ,'' ,'PARCELA INICIAL' );
               insert into db_sysfuncoesparam( db42_sysfuncoesparam ,db42_funcao ,db42_ordem ,db42_nome ,db42_tipo ,db42_tamanho ,db42_precisao ,db42_valor_default ,db42_descricao ) values
                                             ( 1110 ,203 ,9 ,'iParcelafim' ,'int4' ,0 ,0 ,'' ,'PARCELA FINAL' );

               insert into db_sysfuncoes( codfuncao ,nomefuncao ,nomearquivo ,obsfuncao ,corpofuncao ,triggerfuncao ) values
                                        ( 204 ,'fc_iptu_calculavvc_tramandai_2017' ,'iptu_calculavvc_tramandai_2017.sql' ,'Cálculo do valor venal da construção.' ,'.' ,'0' );
               insert into db_sysfuncoesparam( db42_sysfuncoesparam ,db42_funcao ,db42_ordem ,db42_nome ,db42_tipo ,db42_tamanho ,db42_precisao ,db42_valor_default ,db42_descricao ) values
                                             ( 1111 ,204 ,1 ,'iMatricula' ,'int4' ,0 ,0 ,'' ,'MATRÍCULA DO IMÓVEL' );
               insert into db_sysfuncoesparam( db42_sysfuncoesparam ,db42_funcao ,db42_ordem ,db42_nome ,db42_tipo ,db42_tamanho ,db42_precisao ,db42_valor_default ,db42_descricao ) values
                                             ( 1112 ,204 ,2 ,'iAnousu' ,'int4' ,0 ,0 ,'' ,'ANO DO CÁLCULO' );
               insert into db_sysfuncoesparam( db42_sysfuncoesparam ,db42_funcao ,db42_ordem ,db42_nome ,db42_tipo ,db42_tamanho ,db42_precisao ,db42_valor_default ,db42_descricao ) values
                                             ( 1113 ,204 ,3 ,'bMostrademo' ,'bool' ,0 ,0 ,'' ,'DEFINE SE EXECUTA O CÁLCULO OU O DEMOSTRATIVO' );
               insert into db_sysfuncoesparam( db42_sysfuncoesparam ,db42_funcao ,db42_ordem ,db42_nome ,db42_tipo ,db42_tamanho ,db42_precisao ,db42_valor_default ,db42_descricao ) values
                                             ( 1114 ,204 ,4 ,'bRaise' ,'bool' ,0 ,0 ,'' ,'DEBUG' );

               insert into db_sysfuncoes( codfuncao ,nomefuncao ,nomearquivo ,obsfuncao ,corpofuncao ,triggerfuncao ) values
                                        ( 205 ,'fc_iptu_calculavvt_tramandai_2017' ,'iptu_calculavvt_tramandai_2017.sql' ,'Função para cálculo do valor venal do terreno de Tramandaí.' ,'.' ,'0' );
               insert into db_sysfuncoesparam( db42_sysfuncoesparam ,db42_funcao ,db42_ordem ,db42_nome ,db42_tipo ,db42_tamanho ,db42_precisao ,db42_valor_default ,db42_descricao ) values
                                             ( 1115 ,205 ,1 ,'iIdbql' ,'int4' ,0 ,0 ,'' ,'CÓDIGO DO IDBQL DO LOTE.' );
               insert into db_sysfuncoesparam( db42_sysfuncoesparam ,db42_funcao ,db42_ordem ,db42_nome ,db42_tipo ,db42_tamanho ,db42_precisao ,db42_valor_default ,db42_descricao ) values
                                             ( 1116 ,205 ,2 ,'iMatricula' ,'int4' ,0 ,0 ,'' ,'MATRICULA DO IMÓVEL' );
               insert into db_sysfuncoesparam( db42_sysfuncoesparam ,db42_funcao ,db42_ordem ,db42_nome ,db42_tipo ,db42_tamanho ,db42_precisao ,db42_valor_default ,db42_descricao ) values
                                             ( 1117 ,205 ,3 ,'iAnousu' ,'int4' ,0 ,0 ,'' ,'ANO DO CÁLCULO' );
               insert into db_sysfuncoesparam( db42_sysfuncoesparam ,db42_funcao ,db42_ordem ,db42_nome ,db42_tipo ,db42_tamanho ,db42_precisao ,db42_valor_default ,db42_descricao ) values
                                             ( 1118 ,205 ,4 ,'nFracao' ,'numeric' ,0 ,0 ,'' ,'VALOR DA FRAÇÃO DO IMÓVEL' );
               insert into db_sysfuncoesparam( db42_sysfuncoesparam ,db42_funcao ,db42_ordem ,db42_nome ,db42_tipo ,db42_tamanho ,db42_precisao ,db42_valor_default ,db42_descricao ) values
                                             ( 1119 ,205 ,5 ,'bMostrademo' ,'bool' ,0 ,0 ,'' ,'DEFINE SE EXECUTA O CÁLCULO OU O DEMOSTRATIVO' );
               insert into db_sysfuncoesparam( db42_sysfuncoesparam ,db42_funcao ,db42_ordem ,db42_nome ,db42_tipo ,db42_tamanho ,db42_precisao ,db42_valor_default ,db42_descricao ) values
                                             ( 1120 ,205 ,6 ,'bRaise' ,'bool' ,0 ,0 ,'' ,'DEBUG' );

               insert into db_sysfuncoes( codfuncao ,nomefuncao ,nomearquivo ,obsfuncao ,corpofuncao ,triggerfuncao ) values
                                        ( 206 ,'fc_iptu_taxalixo_tramandai_2017' ,'iptu_taxalixo_tramandai_2017.sql' ,'Função para calculo da taxa de lixo de Tramandaí.' ,'.' ,'0' );
               insert into db_sysfuncoesparam( db42_sysfuncoesparam ,db42_funcao ,db42_ordem ,db42_nome ,db42_tipo ,db42_tamanho ,db42_precisao ,db42_valor_default ,db42_descricao ) values
                                             ( 1121 ,206 ,1 ,'iReceita' ,'int4' ,0 ,0 ,'' ,'RECEITA' );
               insert into db_sysfuncoesparam( db42_sysfuncoesparam ,db42_funcao ,db42_ordem ,db42_nome ,db42_tipo ,db42_tamanho ,db42_precisao ,db42_valor_default ,db42_descricao ) values
                                             ( 1122 ,206 ,2 ,'iAliquota' ,'numeric' ,0 ,0 ,'' ,'ALIQUOTA' );
               insert into db_sysfuncoesparam( db42_sysfuncoesparam ,db42_funcao ,db42_ordem ,db42_nome ,db42_tipo ,db42_tamanho ,db42_precisao ,db42_valor_default ,db42_descricao ) values
                                             ( 1123 ,206 ,3 ,'iHistCalc' ,'int4' ,0 ,0 ,'' ,'HISTORICO DE CALCULO' );
               insert into db_sysfuncoesparam( db42_sysfuncoesparam ,db42_funcao ,db42_ordem ,db42_nome ,db42_tipo ,db42_tamanho ,db42_precisao ,db42_valor_default ,db42_descricao ) values
                                             ( 1124 ,206 ,4 ,'iPercIsen' ,'numeric' ,0 ,0 ,'' ,'PERCENTUAL DE ISENÇÃO' );
               insert into db_sysfuncoesparam( db42_sysfuncoesparam ,db42_funcao ,db42_ordem ,db42_nome ,db42_tipo ,db42_tamanho ,db42_precisao ,db42_valor_default ,db42_descricao ) values
                                             ( 1125 ,206 ,5 ,'nValpar' ,'numeric' ,0 ,0 ,'' ,'VALOR POR PARÂMETRO' );
               insert into db_sysfuncoesparam( db42_sysfuncoesparam ,db42_funcao ,db42_ordem ,db42_nome ,db42_tipo ,db42_tamanho ,db42_precisao ,db42_valor_default ,db42_descricao ) values
                                             ( 1126 ,206 ,6 ,'bRaise' ,'bool' ,0 ,0 ,'FALSE' ,'DEBUG' );

SQL_UP;
        $this->execute($sql);


        $sql = <<<STRING1

CREATE OR REPLACE FUNCTION fc_calculoiptu_tramandai_2017(
  integer,
  integer,
  boolean,
  boolean,
  boolean,
  boolean,
  boolean,
  integer,
  integer)

    RETURNS character varying
    LANGUAGE 'plpgsql'

    COST 100
    VOLATILE 
AS $$

declare

    iMatricula                  alias   for $1;
    iAnousu                     alias   for $2;
    lGerafinanceiro             alias   for $3;
    lAtualizaParcela            alias   for $4;
    lNovonumpre                 alias   for $5;
    lCalculogeral               alias   for $6;
    lDemonstrativo              alias   for $7;
    iParcelaini                 alias   for $8;
    iParcelafim                 alias   for $9;

    iIdbql                      integer default 0;
    iNumcgm                     integer default 0;
    iCodcli                     integer default 0;
    iCodisen                    integer default 0;
    iTipois                     integer default 0;
    iParcelas                   integer default 0;
    iNumeroConstrucoes          integer default 0;
    iZona                       integer default 0;
    iCaracteristica             integer;
    iCodigoErro                 integer;

    dDatabaixa                  date;

    nAreal                      numeric(15,2) default 0;
    nAreaLote                   numeric(15,2) default 0;
    nAreaTotal                  numeric(15,2) default 0;
    nFracao                     numeric(15,2) default 0;
    nFracaolote                 numeric(15,2) default 0;
    nIsenaliq                   numeric(15,2) default 0;
    nArealo                     numeric(15,2) default 0;

    nVvc                        numeric(15,2) default 0;
    nVvt                        numeric(15,2) default 0;
    nValorVenalTotal            numeric(15,2) default 0;
    nValorIPTU                  numeric(15,2) default 0;
    nTotalValorVenalConstrucoes numeric(15,2) default 0;

    nAliquota                   numeric(15,2) default 0;
    nAliquotaCalculo            numeric(15,2) default 0;
    nValorReferencia            numeric(15,2) default 0;
    nValorIndice                numeric(15,2) default 0;
    nValorIPTUCalculo           numeric(15,2) default 0;
    nSaldoCalculoValorVenal     numeric(15,2) default 0;

    tMensagemErro               text  default '';
    tDemonstrativo              text  default '';
    tSqlConstr                  text  default '';
    tErro                       text;

    bFinanceiro                 boolean;
    bDadosIptu                  boolean;
    bIsentaxas                  boolean;
    bTempagamento               boolean;
    bEmpagamento                boolean;
    bTaxasCalculadas            boolean;

    lErro                       boolean;
    lEdificacao                 boolean;
    lRaise                      boolean default false;
    lResidencial                boolean default false;

    rCfiptu                     record;
    /*
     * Adicionado variaveis para compensacao de credito
     *
     */
    /*
     * IN?CIO
     */
    nCreditoTaxaLixo            numeric default 0;
    nCreditoIptu                numeric default 0;
    nValorTotalReceita          numeric default 0;
    nViptu                      numeric(15,2) default 0;

    rIptucalv                   record;
    rDadosIptu                  record;
    /*
     * FIM
     */

 begin

   lRaise    := ( case when fc_getsession('DB_debugon') is null then false else true end );

   perform fc_debug('INICIANDO CALCULO', lRaise, true, false);

   /**
    * Executa PRE CALCULO
    */
   select r_iIdbql, r_nAreal, r_nFracao, r_iNumcgm, r_dDatabaixa, r_nFracaolote,
          r_tDemo, r_lTempagamento, r_lEmpagamento, r_iCodisen, r_iTipois, r_nIsenaliq,
          r_lIsentaxas, r_nArealote, r_iCodCli, r_tRetorno

     into iIdbql, nAreal, nFracao, iNumcgm, dDatabaixa, nFracaolote, tDemonstrativo, bTempagamento,
          bEmpagamento, iCodisen, iTipois, nIsenaliq, bIsentaxas, nArealo, iCodCli, tMensagemErro

     from fc_iptu_precalculo( iMatricula, iAnousu, lCalculogeral, lAtualizaParcela, lDemonstrativo, lRaise );

   perform fc_debug(' RETORNO DA PRE CALCULO: ',              lRaise);
   perform fc_debug('  iIdbql         -> ' || iIdbql,         lRaise);
   perform fc_debug('  nAreal         -> ' || nAreal,         lRaise);
   perform fc_debug('  nFracao        -> ' || nFracao,        lRaise);
   perform fc_debug('  iNumcgm        -> ' || iNumcgm,        lRaise);
   perform fc_debug('  dDatabaixa     -> ' || dDatabaixa,     lRaise);
   perform fc_debug('  nFracaolote    -> ' || nFracaolote,    lRaise);
   perform fc_debug('  tDemonstrativo -> ' || tDemonstrativo, lRaise);
   perform fc_debug('  lTempagamento  -> ' || bTempagamento,  lRaise);
   perform fc_debug('  lEmpagamento   -> ' || bEmpagamento,   lRaise);
   perform fc_debug('  iCodisen       -> ' || iCodisen,       lRaise);
   perform fc_debug('  iTipois        -> ' || iTipois,        lRaise);
   perform fc_debug('  nIsenaliq      -> ' || nIsenaliq,      lRaise);
   perform fc_debug('  lIsentaxas     -> ' || bIsentaxas,     lRaise);
   perform fc_debug('  nArealote      -> ' || nArealo,        lRaise);
   perform fc_debug('  iCodCli        -> ' || iCodCli,        lRaise);
   perform fc_debug('  tMensagemErro -> ' || tMensagemErro, lRaise);

   /**
    * Variavel de retorno contem a mensagem
    * de erro retornada do pre calculo
    */
   if trim(tMensagemErro) <> '' then
     return tMensagemErro;
   end if;

   update tmpdadosiptu set matric = iMatricula;

   /**
    * Guarda os parametros do calculo
    */
   select * from into rCfiptu cfiptu where j18_anousu = iAnousu;

   /**
    * Calcula valor do terreno
    */
     perform fc_debug('PARAMETROS fc_iptu_calculavvt_tramandai_2017 IDBQL: '||iIdbql||' - FRACAO DO LOTE: '||nFracaolote||' DEMO: '||tMensagemErro||'- ERRO: '||lErro, lRaise);

     select rnVvt, rnAreaLote, rtDemonstrativo, rtMensagemErro, rlErro, riCodigoErro, rtErro
       into nVvt, nAreaLote, tDemonstrativo, tMensagemErro, lErro, iCodigoErro, tErro
       from fc_iptu_calculavvt_tramandai_2017( iMatricula, iIdbql, iAnousu, nFracaolote, lDemonstrativo, lRaise );

     perform fc_debug('RETORNO fc_iptu_calculavvt_tramandai_2017 -> VVT: '||nVvt||' - AREA CONSTRUIDA: '||nAreaLote||' - RETORNO: '||tMensagemErro||' - ERRO: '||lErro, lRaise);
     perform fc_debug('', lRaise);

     if lErro is true then

         select fc_iptu_geterro( iCodigoErro, tErro ) into tMensagemErro;
         return tMensagemErro;

     end if;

     perform fc_debug('PARAMETROS fc_iptu_calculavvc_tramandai_2017 MATRICULA: '||iMatricula||' - ANOUSU:'||iAnousu||' - DEMO: '||lDemonstrativo, lRaise);
     select rnVvc, rnAreaTotal, riNumeroConstrucoes, rtDemonstrativo, rtMensagemErro, rlErro, riCodigoErro, rtErro
       into nVvc, nAreaTotal, iNumeroConstrucoes, tDemonstrativo, tMensagemErro, lErro, iCodigoErro, tErro
       from fc_iptu_calculavvc_tramandai_2017( iMatricula, iAnousu, lDemonstrativo, lRaise );

     perform fc_debug('RETORNO fc_iptu_calculavvc_tramandai_2017 -> VVC: '||nVvc||' - AREA TOTAL: '||nAreaTotal||' - NUMERO DE CONSTRU??ES: '||iNumeroConstrucoes||' - RETORNO: '||tMensagemErro||' - ERRO: '||lErro, lRaise);
     perform fc_debug('', lRaise);

     if lErro is true then

         select fc_iptu_geterro(iCodErro, tErro) into tMensagemErro;
         return tMensagemErro;

     end if;

     if nVvc is null or nVvc = 0 and iNumeroConstrucoes <> 0 then

         select fc_iptu_geterro(103, '') into tMensagemErro;
         return tMensagemErro;

     end if;

     /**
      *
      * INICIO CALCULO ALIQUOTA
      *
      * Este calculo eh diferente pois no momento que calculo a aliquota ja estou calculando o iptu
      * por este motivo nao existe funcao de aliquota e a mesma eh calculada aqui
      *
      */
     perform fc_debug( ' ' , lRaise);
     perform fc_debug( 'CALCULANDO ALIQUOTA:' , lRaise);
     perform fc_debug( ' ' , lRaise);

     if iNumeroConstrucoes > 0 then

        perform fc_debug( 'Predial' , lRaise);
        nAliquota := 0.55;

     else

        perform fc_debug( 'Territorial' , lRaise);
        nAliquota := 1.1;

     end if;

     nVvt:= (nVvt * nFracaolote) / 100;

     nValorVenalTotal := nVvc + nVvt;

     nValorIPTU := ((nValorVenalTotal * nAliquota) / 100);

     perform fc_debug('Aliquota Final: ' || nAliquota, lRaise);

     execute 'update tmpdadosiptu set aliq = ' || coalesce( nAliquota, 0 );

     execute 'update tmpdadosiptu set vvt = ' || coalesce( nVvt, 0 );

     perform fc_debug('', lRaise);
     perform fc_debug('FIM CALCULO DA ALIQUOTA', lRaise);
     perform fc_debug('', lRaise);

   select count(*)
     into iParcelas
     from cadvencdesc
          inner join cadvenc on q92_codigo = q82_codigo
    where q92_codigo = rCfiptu.j18_vencim;

   if not found or iParcelas = 0 then

     select fc_iptu_geterro(14, '') into tMensagemErro;
     return tMensagemErro;

   end if;

   perform predial from tmpdadosiptu where predial is true;
   if found then
     insert into tmprecval values (rCfiptu.j18_rpredi, nValorIPTU, 1, false);
   else
     insert into tmprecval values (rCfiptu.j18_rterri, nValorIPTU, 1, false);
   end if;
   perform fc_debug('<calculo_tramandai_2017> - Valor inserido - '|| nValorIPTU, lRaise);

   update tmpdadosiptu
      set matric = iMatricula,
          viptu = nValorIPTU,
          codvenc = rCfiptu.j18_vencim;

   update tmpdadostaxa
      set anousu       = iAnousu,
          matric       = iMatricula,
          idbql        = iIdbql,
          valiptu      = nValorIPTU,
          valref       = rCfiptu.j18_vlrref,
          vvt          = nVvt,
          totareaconst = nAreaTotal,
          nparc        = iParcelas;

   perform fc_debug('PARAMETROS fc_iptu_calculataxas ANOUSU: '||iAnousu||' - CODCLI: '||iCodcli, lRaise);

   select fc_iptu_calculataxas(iMatricula, iAnousu, iCodcli, lRaise)
     into bTaxasCalculadas;

   if bTaxasCalculadas is false or fc_getsession('ERRO_CALCULO_TAXA') is not null then
     select fc_iptu_geterro(99, '- '||fc_getsession('ERRO_CALCULO_TAXA')) into tMensagemErro;
     return tMensagemErro;
   end if;

   perform fc_debug('RETORNO fc_iptu_calculataxas ->  TAXASCALCULADAS: ' || bTaxasCalculadas, lRaise);

   /**
    * Monta o demonstrativo
    */
   select fc_iptu_demonstrativo(iMatricula, iAnousu, iIdbql, lRaise)
     into tDemonstrativo;

   /**
    * Gera financeiro
    *  -> Se nao for demonstrativo gera o financeiro, caso contrario retorna o demonstrativo
    */
   if lDemonstrativo is false then

     select fc_iptu_geradadosiptu(iMatricula, iIdbql, iAnousu, nIsenaliq, lDemonstrativo, lRaise)
       into bDadosIptu;

     --Tabela de creditos gerados exepcionalmente para Alvorada
     --Gera um valor negativo com receita de iptu caso haja valor na coluna c_iptu_2015 e na taxa de lixo, caso haja valor na coluna b_tcl_2015 e tcl_2014
     --Somente para o calculo de 2016
     --Att: Alberto Ferri

     nCreditoTaxaLixo := 0;
     nCreditoIptu     := 0;

     select * into rDadosIptu from tmpdadosiptu;

     perform * from pg_tables where tablename = 'w_creditos';

     if found and iAnousu = '2016' then

       select coalesce(tcl_2014, 0) + coalesce(b_tcl_2015, 0), coalesce(c_iptu_2015, 0)
         into nCreditoTaxaLixo, nCreditoIptu
         from w_creditos
        where matric = iMatricula;

     end if;

     for rIptucalv in select *
                        from tmprecval
                             left join tmptaxapercisen on tmprecval.receita = tmptaxapercisen.rectaxaisen
                       where taxa is false
     loop
       if rIptucalv.receita in (1, 2) and nCreditoIptu > 0 then

         select sum(j21_valor) into nValorTotalReceita
           from iptucalv
          where j21_anousu = iAnousu
            and j21_matric = iMatricula
            and j21_receit = rIptucalv.receita;

         if nCreditoIptu > nValorTotalReceita then
           nCreditoIptu := nValorTotalReceita;
         end if;

         nCreditoIptu :=  round(nCreditoIptu * -1, 2);

         insert into iptucalv ( j21_anousu,
                                j21_matric,
                                j21_receit,
                                j21_valor,
                                j21_quant,
                                j21_codhis )
                       values ( iAnousu,
                                iMatricula,
                                rIptucalv.receita,
                                nCreditoIptu,
                                0,
                                9);
       end if;
     end loop;

     for rIptucalv in select *
                        from tmprecval
                             inner join tmptaxapercisen on tmprecval.receita = tmptaxapercisen.rectaxaisen
                       where taxa is true
     loop
        if rIptucalv.receita= 213 and nCreditoTaxaLixo > 0 then

             nValorTotalReceita :=  round( rIptucalv.valsemisen, 2);

             if rIptucalv.percisen > 0 then

               nValorTotalReceita := nValorTotalReceita + (round( ((rIptucalv.valsemisen * rIptucalv.percisen) / 100),2) * -1);

             end if;

             if nCreditoTaxaLixo > nValorTotalReceita then

               nCreditoTaxaLixo := nValorTotalReceita;

             end if;

             nCreditoTaxaLixo := round( ( nCreditoTaxaLixo * -1 ), 2) ;

             insert into iptucalv ( j21_anousu, j21_matric, j21_receit, j21_valor, j21_quant, j21_codhis )
             values ( iAnousu, iMatricula, rIptucalv.receita,  nCreditoTaxaLixo, 0, 13);

             update tmprecval
                set valor = ( select round(sum(coalesce(j21_valor, 0)), 2)
                                from iptucalv
                               where j21_matric = iMatricula
                                 and j21_receit = receita
                                 and j21_anousu = iAnousu )
              where receita = receita;

           end if;

      end loop;

      if nCreditoIptu is not null and nCreditoIptu > 0 then

          nViptu := (rDadosIptu.viptu - ( rDadosIptu.viptu * ( nIsenaliq / 100) )) + nCreditoIptu;
          update tmpdadosiptu set viptu = nViptu;
          update tmprecval    set valor = nViptu where taxa is false and hist = 1;
          perform fc_debug('<calculo_tramandai_2017> - Valor ALTERADO - '|| , lRaise);

      end if;

       if lGerafinanceiro then

         select fc_iptu_gerafinanceiro( iMatricula, iAnousu, iParcelaini, iParcelafim, lCalculogeral, bTempagamento, lNovonumpre, lDemonstrativo, lRaise )
           into bFinanceiro;
       end if;
   else
      return tDemonstrativo;
   end if;

   if lDemonstrativo is false then

      update iptucalc
         set j23_manual = tDemonstrativo
       where j23_matric = iMatricula
         and j23_anousu = iAnousu;

   end if;

   perform fc_debug('CALCULO CONCLUIDO COM SUCESSO',lRaise, false, true);

   select fc_iptu_geterro(1, '') into tMensagemErro;
   return tMensagemErro;

 end;
$$;

STRING1;

        $this->execute($sql);


        $sql = <<<STRING2

CREATE OR REPLACE FUNCTION fc_iptu_calculavvc_tramandai_2017(
  integer,
  integer,
  boolean,
  boolean,
  OUT rnvvc numeric,
  OUT rnareatotal numeric,
  OUT rinumeroconstrucoes integer,
  OUT rtdemonstrativo text,
  OUT rtmensagemerro text,
  OUT rlerro boolean,
  OUT ricodigoerro integer,
  OUT rterro text)
    RETURNS record
    LANGUAGE 'plpgsql'

    COST 100
    VOLATILE 
AS $$
declare

    /**
     * Parâmetros da função
     */
    iMatricula             alias for $1;
    iAnousu                alias for $2;
    lDemonstrativo         alias for $3;
    lRaise                 alias for $4;

    nValorReferencia       numeric default 0;
    nVm2Construcao         numeric(15,6) default 0;
    iPontuacao             integer default 0;
    nValorVenal            numeric default 0;
    iAnosDepreciacao       integer default 0;
    nDepreciacao           numeric default 0;

    isPredial              boolean default false;

    tSqlConstr             text;
    rConstr                record;

begin

    perform fc_debug('', lRaise);
    perform fc_debug('' || lpad('', 60, '-'), lRaise);
    perform fc_debug('* VVC - INICIANDO CÁLCULO DO VALOR VENAL DA CONSTRUÇÃO', lRaise);

    rnVvc               := 0;
    rnAreaTotal         := 0;
    riNumeroConstrucoes := 0;
    rtDemonstrativo     := '';
    rtMensagemErro      := 'Retorno ok';
    rlErro              := 'f';
    riCodigoErro        := 0;
    rtErro              := '';

    /**
     * Busca valor da URM
     */
    select coalesce(j18_vlrref, 0)
      into nValorReferencia
      from cfiptu
     where j18_anousu = iAnousu;

    if nValorReferencia = 0 or nValorReferencia is null then

        rlErro       := 't';
        riCodigoErro := 99;
        rtErro       := 'NAO INFORMADO VALOR DE REFERENCIA NOS PARAMETROS DO IPTU';
        return;

    end if;

    perform fc_debug(' SELECIONANDO CONSTRUÇÕES : ' || tSqlConstr, lRaise);

    tSqlConstr :=               ' select * ';
    tSqlConstr := tSqlConstr || '   from iptuconstr ';
    tSqlConstr := tSqlConstr || '        inner join iptubase on j01_matric = j39_matric ';
    tSqlConstr := tSqlConstr || '  where j39_matric = ' || iMatricula;
    tSqlConstr := tSqlConstr || '    and j39_dtdemo is null ';
    tSqlConstr := tSqlConstr || '    and exists ( select 1 ';
    tSqlConstr := tSqlConstr || '                   from carconstr ';
    tSqlConstr := tSqlConstr || '                        inner join caracter on j48_caract = j31_codigo ';
    tSqlConstr := tSqlConstr || '                  where j48_matric = iptuconstr.j39_matric ';
    tSqlConstr := tSqlConstr || '                    and j48_idcons = iptuconstr.j39_idcons ';

    -- Calcular somente edificações com a característica 63190 e 63000
    tSqlConstr := tSqlConstr || '                    and caracter.j31_codigo in (63190, 63000) )';

    for rConstr in execute tSqlConstr loop

        perform fc_debug(' --- PROCESSANDO CONSTRUÇÃO ' || rConstr.j39_idcons || '...', lRaise);
        perform fc_debug('     ÁREA DA CONSTRUÇÃO:              ' || rConstr.j39_area, lRaise);

        /**
         * Busca valor do m2 da eficicação por zona
         */
        select coalesce(j141_valorm2, 0)
          into nVm2Construcao
          from lote
               inner join zonassetorvalor on j34_setor = j141_setor
                                         and j34_zona  = j141_zonas
         where j34_idbql = rConstr.j01_idbql
           and j141_anousu = iAnousu;

        if nVm2Construcao = 0 OR not found then

            rlErro       := 't';
            riCodigoErro := 104;
            rtErro       := '34';
            return;

        end if;

        perform fc_debug('     VALOR DO M2 DA CONSTRUÇÃO:       ' || nVm2Construcao, lRaise);
        perform fc_debug('     VALOR DA URM:                    ' || nValorReferencia, lRaise);

        nVm2Construcao := nVm2Construcao * nValorReferencia;

        perform fc_debug('     VALOR DO M2 APÓS APLICAR A URM:  ' || nVm2Construcao, lRaise);

        select sum(j31_pontos)
          into iPontuacao
          from carconstr
               inner join caracter on j48_caract = j31_codigo
         where j48_matric = rConstr.j01_matric
           and j48_idcons = rConstr.j39_idcons;

        perform fc_debug('     PONTUAÇÃO:                       ' || iPontuacao, lRaise);

        select iAnousu - extract (year from rConstr.j39_dtlan)
          into iAnosDepreciacao;

        /**
         * Busca o percentual da depreciação
         */
        select case when iAnosDepreciacao > 30
                    then 0.7 -- desconto de 30%
                    else case when iAnosDepreciacao between 21 and 30
                              then 0.8 -- desconto de 20%
                              else case when iAnosDepreciacao between 10 and 20
                                        then 0.9 -- desconto de 10%
                                        else 1 -- sem desconto
                                   end
                         end
               end into nDepreciacao;
        perform fc_debug('     DEPRECIAÇÃO:                     ' || ((1.0 - nDepreciacao) * 100), lRaise);

        -- CALCULO DO VALOR VENAL DAS CONSTRUÇÕES
        --
        -- Vvc = AU * (Vm2E * URM) * Pontos / 100
        --
        -- Vvc -> Valor Venal da Construção
        -- AU -> Área da edificação (unidade)
        -- Vm2E -> Valor do m2 da edificação
        -- URM -> Unidade de referência monetária
        -- Pontos -> pontos obtidos na aplicação dos fatores corretivos

        nValorVenal := (rConstr.j39_area * (nVm2Construcao * nValorReferencia) * iPontuacao) * nDepreciacao;

        perform fc_debug(' Calculando VVC utilizando fórmula: Vvc = AU * (Vm2E * URM) * Pontos / 100', lRaise);
        perform fc_debug('  -> Valores: VVT := ' || rConstr.j39_area || ' * ( ' || nVm2Construcao || ' * ' || nValorReferencia || ') / ' || iPontuacao || ' / 100 ', lRaise);
        perform fc_debug(' ÁREA:        ' || rConstr.j39_area,    lRaise);
        perform fc_debug(' URM:         ' || nValorReferencia,    lRaise);
        perform fc_debug(' VALOR M2:    ' || nVm2Construcao,      lRaise);
        perform fc_debug(' PONTUAÇÃO:   ' || iPontuacao,          lRaise);
        perform fc_debug(' VVC:         ' || nValorVenal,         lRaise);

        perform fc_debug(' EDIF. ANT:   ' || riNumeroConstrucoes, lRaise);
        perform fc_debug(' ÁREA ANT.:   ' || rnAreaTotal,         lRaise);
        perform fc_debug(' VVC ANT.:    ' || rnVvc,               lRaise);

        rnVvc               := rnVvc + nValorVenal;
        rnAreaTotal         := rnAreaTotal + rConstr.j39_area;
        riNumeroConstrucoes := riNumeroConstrucoes + 1;

        perform fc_debug(' EDIF. ATUAL: ' || riNumeroConstrucoes, lRaise);
        perform fc_debug(' ÁREA ATUAL.: ' || rnAreaTotal,         lRaise);
        perform fc_debug(' VVC ATUAL.:  ' || rnVvc,               lRaise);

        insert into tmpiptucale (anousu, matric, idcons, areaed, vm2, pontos, valor, edificacao)
                         values (iAnousu, iMatricula, rConstr.j39_idcons, rConstr.j39_area, nVm2Construcao, iPontuacao, nValorVenal, true);

        isPredial := true;
    end loop;

    perform fc_debug('  ',lRaise);
    perform fc_debug(' Total de edificações:  '|| coalesce(riNumeroConstrucoes, 0), lRaise);
    perform fc_debug(' Área total construída: '|| coalesce(rnAreaTotal, 0),         lRaise);
    perform fc_debug(' Valor venal total:     '|| coalesce(rnVvc, 0),               lRaise);
    perform fc_debug(' IPTU Predial ?         '|| case when isPredial is true then 'SIM' else 'NAO' end,               lRaise);

    rtDemonstrativo      := '';
    rlErro               := 'f';

    update tmpdadosiptu set predial = isPredial, vvc = vvc + rnVvc;

    perform fc_debug('' || lpad('', 60, '-'), lRaise);
    perform fc_debug('', lRaise);

    return;

end;
$$;

STRING2;

        $this->execute($sql);


        $sql = <<<STRING3

drop function if exists fc_iptu_calculavvt_tramandai_2017(integer, integer, integer, boolean, boolean);

CREATE OR REPLACE FUNCTION fc_iptu_calculavvt_tramandai_2017(
  integer,
  integer,
  integer,
  numeric,
  boolean,
  boolean,
  OUT rnvvt numeric,
  OUT rnarealote numeric,
  OUT rntestada numeric,
  OUT rnvm2terreno numeric,
  OUT rtdemonstrativo text,
  OUT rtmensagemerro text,
  OUT rlerro boolean,
  OUT ricodigoerro integer,
  OUT rterro text)
    RETURNS record
    LANGUAGE 'plpgsql'

    COST 100
    VOLATILE 
AS $$
declare

    /**
     * Parâmetros da função
     */
    iMatricula        alias for $1;
    iIdbql            alias for $2;
    iAnousu           alias for $3;
    nFracao           alias for $4;
    lDemonstrativo    alias for $5;
    lRaise            alias for $6;

    /**
     * Variáveis para cálculo da área - lote.j34_area
     */
    nAreaLoteIsento   numeric default 0;
    nValorCalculoArea numeric default 0;

begin

    /**
     * Variável para retornar o cálculo do valor
     */
    rnVvt         := 0;

    /**
     * Variável para guardar a área do lote - lote.j34_area
     */
    rnAreaLote    := 0;

    /**
     * Variável para guardar a testada - testada.j36_testad
     */
    rnTestada     := 0;

    /**
     * Variável para retornar o valor do metro quadrado
     */
    rnVm2Terreno  := 0;

    /**
     * Demais Variáveis para retorno
     */
    rlErro        := 'f';
    riCodigoErro  := 0;
    rtErro        := '';

    perform fc_debug('' || lpad('', 60, '-'), lRaise);
    perform fc_debug('* VVT - INICIANDO cálculo DO VALOR VENAL TERRITORIAL', lRaise);

    select case when j34_areal = 0
                then j34_area
                else j34_areal
           end
      into rnAreaLote
      from lote
     where j34_idbql = iIdbql;

    if rnAreaLote is null then

      rlErro       := 't';
      riCodigoErro := 6;
      rtErro       := '';
      return;

    end if;

    select rnArealo
      into nAreaLoteIsento
      from fc_iptu_verificaisencoes(iMatricula, iAnousu, lDemonstrativo, lRaise);

    if nAreaLoteIsento > 0 then

      perform fc_debug(' área REAL DO LOTE:            ' || rnAreaLote, lRaise);
      perform fc_debug(' área ISENTA DO LOTE:          ' || nAreaLoteIsento, lRaise);

      rnAreaLote  := rnAreaLote - nAreaLoteIsento;

      if rnAreaLote < 0 then

        rlErro       := 't';
        riCodigoErro := 6;
        rtErro       := 'Area real do lote n?o pode ser menor que 0 (zero)';
        return;

      end if;

    end if;

    perform fc_debug(' área REAL DO LOTE AP?S ISEN??O: ' || rnAreaLote, lRaise);

    /**
     * Busca valor da testada
     */
     select j36_testad
       into rnTestada
       from testada
            inner join testpri on j49_idbql = j36_idbql
                              and j49_face  = j36_face
      where j36_idbql = iIdbql;

    perform fc_debug(' TESTADA:                        ' || rnTestada, lRaise);

    /**
     * Busca valor do m2 por zona
     */
    select coalesce(j141_valorminimo, 0)
      into rnVm2Terreno
      from lote
           inner join zonassetorvalor on j34_setor = j141_setor
                                     and j34_zona  = j141_zonas
     where j34_idbql = iIdbql
       and j141_anousu = iAnousu;

    if rnVm2Terreno = 0 OR rnVm2Terreno is null then

      rlErro       := 't';
      riCodigoErro := 105;
      rtErro       := ' VERIFIQUE VALOR POR ZONA.';
      return;

    end if;

    perform fc_debug(' VALOR M2 DO TERRENO:            ' || rnVm2Terreno, lRaise);

    --
    -- CALCULO DO VALOR VENAL DO TERRENO
    --
    -- Vvt = sqrt(At / T) * T * Vm2T
    --
    -- Vvt -> Valor Venal Terreno
    -- At -> área total do lote
    -- T -> Testada
    -- Vm2T -> Valor do m2 do terreno

    nValorCalculoArea := sqrt(rnAreaLote / rnTestada);

    rnVvt := nValorCalculoArea * rnTestada * rnVm2Terreno;

    if exists(select *
                from lote
                     inner join carlote on j35_idbql = j34_idbql
               where j34_idbql = iIdbql
                 and j35_caract in (46112, 46113)) then

        rnVvt := (rnVvt / 3) + rnVvt;

    end if;

    rnVvt := round(rnVvt, 2);
    
    perform fc_debug(' Calculando VVT utilizando f?rmula: Vvt = sqrt(At / T) * T * Vm2T', lRaise);
    perform fc_debug('  -> Valores: VVT := sqrt(' || rnAreaLote || ' / ' || rnTestada || ') x ' || rnTestada || ' x ' || rnVm2Terreno, lRaise);
    perform fc_debug(' área CORRIG: ' || rnAreaLote,   lRaise);
    perform fc_debug(' TESTADA:     ' || rnTestada,    lRaise);
    perform fc_debug(' VALOR M2:    ' || rnVm2Terreno, lRaise);
    perform fc_debug(' VVT:         ' || rnVvt,        lRaise);

    update tmpdadosiptu
       set vvt    = rnVvt,
           vm2t   = rnVm2Terreno,
           areat  = rnAreaLote,
           matric = iMatricula;

    perform fc_debug('' || lpad('', 60, '-'), lRaise);

    return;

end;
$$;

STRING3;

        $this->execute($sql);


        $sql = <<<STRING4

CREATE OR REPLACE FUNCTION fc_iptu_taxalixo_tramandai_2017(
  integer,
  numeric,
  integer,
  numeric,
  numeric,
  boolean)
    RETURNS boolean
    LANGUAGE 'plpgsql'

    COST 100
    VOLATILE 
AS $$

declare

  iReceita                 alias for $1;
  iAliquota                alias for $2;
  iHistCalc                alias for $3;
  iPercIsen                alias for $4;
  nValpar                  alias for $5;
  lRaise                   alias for $6;

  nValorTotalTaxa          numeric(15,2) default 0;

  debug_caracteristicas    text;

  rDadosTaxa               record;

  pode_calcular_taxa_lixo  boolean;

begin

  perform fc_debug( ' <iptu_taxalixo> CALCULANDO TAXA DE COLETA DE LIXO - PARAMETROS RECEBIDOS ...',lRaise);
  perform fc_debug( '',lRaise);
  perform fc_debug( ' <iptu_taxalixo> receita            - ' || iReceita  ,       lRaise);
  perform fc_debug( ' <iptu_taxalixo> aliq               - ' || iAliquota ,       lRaise);
  perform fc_debug( ' <iptu_taxalixo> historico          - ' || iHistCalc ,       lRaise);
  perform fc_delsession(' <iptu_taxalixo> ERRO_CALCULO_TAXA');
  perform fc_debug( ' <iptu_taxalixo> BUSCA INFORMACOES DA TABELA TMPDADOSTAXA ...',lRaise);

  select *
    into rDadosTaxa
    from tmpdadostaxa;

  perform fc_debug( ' <iptu_taxalixo> BUSCA O VALOR DA TAXA DE LIXO NA TABELA CARVALOR ...',lRaise);

  if rDadosTaxa.totareaconst > 0 then

     select j71_valor,       array_to_string(array_agg(distinct j48_caract::text),',')
       into nValorTotalTaxa, debug_caracteristicas
       from carconstr inner join caracter on j31_codigo = j48_caract
                      inner join carvalor on j71_caract = j48_caract
                                         and j71_anousu = rDadosTaxa.anousu
      where j48_matric = rDadosTaxa.matric
        and j31_grupo  = 62
      group by j71_valor;

     if nValorTotalTaxa is null or nValorTotalTaxa = 0 or not found then
        perform fc_debug(' <iptu_taxalixo> Caracteristica de coleta de limpeza nao encontrada ou valor da taxa nao encontrado na carvalor.', lRaise);
        return false;
     end if;

     perform fc_debug( ' <iptu_taxalixo> CALCULA TAXA DE LIXO:', lRaise);
     perform fc_debug( '',lRaise);
     perform fc_debug( ' <iptu_taxalixo> Caracteristicas encontradas para o Grupo 62: '||debug_caracteristicas, lRaise);
     perform fc_debug( ' <iptu_taxalixo> Valor da Taxa de Lixo: ' ||nValorTotalTaxa, lRaise);

     insert into tmptaxapercisen values (iReceita, iPercIsen, 0, nValorTotalTaxa);
     insert into tmprecval       values (iReceita, nValorTotalTaxa, iHistCalc, true);

     return true;
  else
    perform fc_debug( ' <iptu_taxalixo> NAO CALCULA TAXA DE LIXO '||rDadosTaxa.matric, lRaise);
  end if;

  return false;

end;
$$;

STRING4;

        $this->execute($sql);

    }


    public function down()
    {
        $sql = <<<SQL_DOWN

               delete from db_sysfuncoesparam where db42_funcao between 203 and 206;
               delete from db_sysfuncoes where codfuncao between 203 and 206;
               drop function fc_calculoiptu_tramandai_2017(integer, integer, boolean, boolean, boolean, boolean, boolean, integer, integer);
               drop function fc_iptu_calculavvc_tramandai_2017(integer, integer, boolean, boolean);
               drop function fc_iptu_calculavvt_tramandai_2017(integer, integer, integer, numeric, boolean, boolean);
               drop function fc_iptu_taxalixo_tramandai_2017(integer, numeric, integer, numeric, numeric, boolean);

SQL_DOWN;
       $this->execute($sql);
    }

}
