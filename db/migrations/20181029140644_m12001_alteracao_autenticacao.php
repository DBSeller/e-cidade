<?php

use Classes\PostgresMigration;

class M12001AlteracaoAutenticacao extends PostgresMigration
{

    public function up()
    {
        $sSql = <<<SQL

create or replace function fc_autentica(integer,integer,date,date,integer,integer,varchar(20),integer) returns tp_autenticacao_arrecadacao as
$$
declare
 rtp_autenticacao tp_autenticacao_arrecadacao%ROWTYPE;

begin

 rtp_autenticacao := fc_autentica($1, $2, $3, $4, $5, $6, $7, $8, 0, 0);
 return rtp_autenticacao;

end;
$$ language 'plpgsql';

create or replace function fc_autentica(integer,integer,date,date,integer,integer,varchar(20),integer,integer) returns tp_autenticacao_arrecadacao as
$$
declare
 rtp_autenticacao tp_autenticacao_arrecadacao%ROWTYPE;

begin

 rtp_autenticacao := fc_autentica($1, $2, $3, $4, $5, $6, $7, $8, $9, 0);
 return rtp_autenticacao;

end;
$$ language 'plpgsql';


create or replace function fc_autentica(integer,integer,date,date,integer,integer,varchar(20),integer, integer, integer) returns tp_autenticacao_arrecadacao as
$$
DECLARE
  NUMPRE           ALIAS FOR $1;
  NUMPAR           ALIAS FOR $2;
  DTEMITE          ALIAS FOR $3;
  DTVENC           ALIAS FOR $4;
  SUBDIR           ALIAS FOR $5;
  CONTA            ALIAS FOR $6;
  IPTERM           ALIAS FOR $7;
  INSTIT           ALIAS FOR $8;
  iCodigoGrupo     alias for $9;
  iCodigoTipoGrupo alias for $10;

  rtp_autenticacao tp_autenticacao_arrecadacao%ROWTYPE;

  iFormaCorrecao integer default 2;
  ANOUSU         integer;

  CODAUT  INTEGER ;
  IDTERM  INTEGER ;
  HORA    CHAR(5) ;
  IDENT1  CHAR(1);
  IDENT2  CHAR(1);
  IDENT3  CHAR(1);

  UNICA         BOOLEAN := FALSE;
  NUMERO_ERRO   char(200);
  NUMTOT        INTEGER;
  NUMDIG        INTEGER;
  NUMCGM        INTEGER;
  RECORD_NUMPRE  RECORD;
  RECORD_ALIAS   RECORD;
  RECORD_GRAVA   RECORD;
  RECORD_NUMPREF RECORD;
  GRAVA_CORNUMP  RECORD;
  VALOR_RECEITA FLOAT8;
  CORRECAO      FLOAT8;
  JURO          FLOAT8;
  MULTA         FLOAT8;
  DESCONTO      FLOAT8 := 0;
  TOTALZAO      FLOAT8;
  TOTALCORRENTE FLOAT8;
  TOTALCORNUMP  FLOAT8;
  RECEITA       INTEGER;
  K03_RECMUL    INTEGER;
  K03_RECJUR    INTEGER;
  DTOPER        DATE;
  DATAVENC      DATE;
  QUAL_OPER     INTEGER;
  SQLRECIBO     VARCHAR(400);
  VLRCORRECAO   FLOAT8 := 0;
  VLRJUROS      FLOAT8 := 0;
  VLRMULTA      FLOAT8 := 0;
  VLRDESCONTO   FLOAT8 := 0;
  HIST          INTEGER;
  PROCESSA      BOOLEAN;
  NUM_PAR       INTEGER;
  DB_ARQUIVO    VARCHAR(10);
  GRAVAR_RECUNI BOOLEAN DEFAULT FALSE;
  VLRINF    FLOAT8;
  AUTENTICACAO  TEXT;
  totperc   float8;
  TIPOAUTENT  INTEGER;
  iParcelasPagasCanceladas integer;
  iNumpreRecibopaga        integer;
  iNumpreDigitado          integer;
  iTipoGrupo               integer;

  TEMHISTORICO  TEXT := null;

  v_composicao record;

  nComposCorrecao   numeric(15,2) default 0;
  nComposJuros      numeric(15,2) default 0;
  nComposMulta      numeric(15,2) default 0;

  nCorreComposJuros numeric(15,2) default 0;
  nCorreComposMulta numeric(15,2) default 0;

  lRaise  boolean default false;


  VTIPO   VARCHAR(1);
  VINSTIT INTEGER;

  --
  -- MODIFICACAO REALIZADA PARA UTILIZACAO DAS TAXAS DE EXPEDIENTE JUNTO AO CAIXA
  --
  --
  -- Inicio modificacao DB_utiliza_taxa_expediente_autenticacao
  --

  GRAVA_RECEITA_RECIBO boolean default false;

  VTAXAACRESCIMO numeric(15,2) default 0;
  RECEITATAXAACRESCIMO INTEGER;

  --
  -- Fim modificacao DB_utiliza_taxa_expediente_autenticacao
  --

  BEGIN

    lRaise  := ( case when fc_getsession('DB_debugon') is null then false else true end );
    if lRaise is true then
      perform fc_debug('<autentica>  Inicio processamento autenticação...',lRaise, true, false);
    end if;

    select cast( fc_getsession('DB_anousu') as integer )
      into ANOUSU;

    rtp_autenticacao.id           := 0;
    rtp_autenticacao.data         := null;
    rtp_autenticacao.codautent    := 0;
    rtp_autenticacao.autenticacao := '';
    rtp_autenticacao.erro         := true;
    rtp_autenticacao.mensagem     := '';

    select k03_separajurmulparc
      into iFormaCorrecao
      from numpref
     where k03_instit = INSTIT
       and k03_anousu = ANOUSU;

    select k00_instit
      into VINSTIT
      from arreinstit
     where k00_numpre = NUMPRE;
    if VINSTIT is null then

      if lRaise is true then
        perform fc_debug('<autentica>  1 - Fim do processamento da autenticação...',lRaise, false, true);
      end if;
      rtp_autenticacao.erro     := true;
      rtp_autenticacao.mensagem := '9 NUMPRE '|| NUMPRE ||' SEM INSTITUICAO DEFINIDA';
      return rtp_autenticacao;

    end if;

    if VINSTIT <> INSTIT then

      if lRaise is true then
        perform fc_debug('<autentica>  2 - Fim do processamento da autenticação...',lRaise, false, true);
      end if;
      rtp_autenticacao.erro     := true;
      rtp_autenticacao.mensagem := '7 INSTITUICAO INVALIDA PARA AUTENTICACAO DESTE NUMPRE';
      return rtp_autenticacao;

    end if;

    --
    -- VERIFICAMOS SE ESTA SETADA A VARIAVEL DB_utiliza_taxa_expediente_autenticacao NA SESSAO
    -- SE ESTIVER SETADA UTILIZAMOS A LOGICA DE UTILIZAR A TAXA DE EXPEDIENTE NAS AUTENTICACOES
    --
    -- Inicio modificacao DB_utiliza_taxa_expediente_autenticacao
    --
    if fc_getsession('DB_utiliza_taxa_expediente_autenticacao') is not null then
      select k07_valorf ,
             k07_codigo
        into VTAXAACRESCIMO,
             RECEITATAXAACRESCIMO
        from TABDESC
             inner join tabdesccadban on k114_tabdesc = codsubrec
             inner join cadban        on k114_codban = k15_codigo
       where k15_conta = CONTA;

       if not found then
          VTAXAACRESCIMO := 0;
       end if;
    end if;
    --
    -- Fim modificacao DB_utiliza_taxa_expediente_autenticacao
    --

    perform ar22_sequencial
       from numprebloqpag
      where ar22_numpre = NUMPRE
        and ar22_numpar = NUMPAR;

    if found then

      if lRaise is true then
        perform fc_debug('<autentica>  3 - Fim do processamento da autenticação...',lRaise, false, true);
      end if;
      rtp_autenticacao.erro     := true;
      rtp_autenticacao.mensagem := '9 DÉBITO NÃO PODE SER PAGO, FAVOR VERIFICAR COM CHEFE DO SETOR DE ARRECADAÇÃO';
      return rtp_autenticacao;

    end if;

    IF NUMPAR = '000' then

      -- testar se esse numpre está na recibopaga
      perform k00_numpre
      from recibopaga
      where k00_numnov = NUMPRE;
      if found then

        perform recibopaga.k00_numpre
        from recibopaga
        left join arrecad on recibopaga.k00_numpre = arrecad.k00_numpre
                         and recibopaga.k00_numpar = arrecad.k00_numpar
--                         and recibopaga.k00_receit = arrecad.k00_receit
        where k00_numnov = NUMPRE
          and arrecad.k00_numpre is null;

        if found is true then

          if lRaise is true then
            perform fc_debug('<autentica>  4 - Fim do processamento da autenticação...',lRaise, false, true);
          end if;

          rtp_autenticacao.erro     := true;
          rtp_autenticacao.mensagem := '9 RECIBO INCONSISTENTE. EXISTEM PARCELA(S) PAGA(S)/CANCELADA(S). EMITA OUTRO RECIBO.';
          return rtp_autenticacao;


        end if;

      else

        select abs(count(distinct k00_numpar)-k00_numtot)
        into iParcelasPagasCanceladas
        from arrecad
        where k00_numpre = NUMPRE
        group by k00_numtot
        having count(distinct k00_numpar)<>k00_numtot;

        if found is true and iParcelasPagasCanceladas > 0 then

          if lRaise is true then
            perform fc_debug('<autentica>  5 - Fim do processamento da autenticação...',lRaise, false, true);
          end if;

          rtp_autenticacao.erro     := true;
          rtp_autenticacao.mensagem := '9 RECIBO INCONSISTENTE. EXISTEM PARCELA(S) PAGA(S)/CANCELADA(S). EMITA OUTRO RECIBO.';
          return rtp_autenticacao;

        end if;

      end if;

    end if;


    FOR RECORD_NUMPREF IN SELECT * FROM NUMPREF WHERE K03_ANOUSU = SUBDIR LOOP
      K03_RECJUR := RECORD_NUMPREF.K03_RECJUR;
      K03_RECMUL := RECORD_NUMPREF.K03_RECMUL;
    END LOOP;

--raise notice '%-%',numpre,numpar;
    VALOR_RECEITA := 0;

    FOR RECORD_NUMPRE IN   SELECT * FROM ( SELECT DISTINCT
                                                  K00_NUMPRE,
                                                  K00_NUMPAR
                                             FROM ARRECAD
                                            WHERE K00_NUMPRE = NUMPRE
                                          UNION ALL
                                           SELECT DISTINCT
                                                  RECIBO.K00_NUMPRE,
                                                  RECIBO.K00_NUMPAR
                                             FROM RECIBO
                                                  LEFT OUTER JOIN ARREPAGA ON RECIBO.K00_NUMPRE = ARREPAGA.K00_NUMPRE
                                             WHERE RECIBO.K00_NUMPRE = NUMPRE
                                               AND ARREPAGA.K00_NUMPRE IS NULL
                                          UNION ALL
                                             SELECT DISTINCT
                                                    K00_NUMPRE,
                                                    K00_NUMPAR
                                               FROM RECIBOPAGA
                                              WHERE K00_NUMNOV = NUMPRE
                                             ) AS X
                                             ORDER BY K00_NUMPRE,K00_NUMPAR
    LOOP

      IF NUMPAR != 0 THEN
        IF RECORD_NUMPRE.K00_NUMPAR != NUMPAR THEN
          NUM_PAR := 0;
        ELSE
          NUM_PAR := NUMPAR;
        END IF;
      ELSE
        NUM_PAR := RECORD_NUMPRE.K00_NUMPAR;
        perform k00_numpre from arrecad where k00_numpre = numpre;
        if found then
          unica = true;
        else
          unica := exists( select *
                           from db_reciboweb
                          where k99_numpre_n = numpre
                            and k99_numpar = 0 );
        end if;
      END IF;

      IF NUM_PAR != 0 or unica is true THEN
        PROCESSA := FALSE;

        if lRaise is true then
          perform fc_debug('<autentica>  ok '||numpre||' np '||numpar||' npa-'||RECORD_NUMPRE.K00_NUMPRE||'-'||RECORD_NUMPRE.K00_NUMPAR,lRaise,false,false);
        end if;

        FOR RECORD_ALIAS IN  SELECT * FROM (
          SELECT k00_numpar,K00_RECEIT,max(k00_hist) as k00_hist,K00_DTOPER,K00_NUMCGM,fc_calculavenci(K00_NUMPRE,K00_NUMPAR,K00_DTVENC,DTEMITE) AS K00_DTVENC,K00_NUMPRE,SUM(K00_VALOR) AS K00_VALOR
            FROM ARRECAD
           WHERE K00_NUMPRE = NUMPRE AND K00_NUMPAR = NUM_PAR
           GROUP BY k00_numpar,K00_RECEIT,K00_DTOPER,K00_NUMCGM,K00_DTVENC,K00_NUMPRE,K00_NUMPAR
         UNION ALL
          SELECT k00_numpar, K00_RECEIT, max(k00_hist) as k00_hist, K00_DTOPER,K00_NUMCGM,K00_DTVENC,K00_NUMPRE,SUM(K00_VALOR) AS K00_VALOR
            FROM RECIBO
           WHERE K00_NUMPRE = NUMPRE AND K00_NUMPAR = NUM_PAR
           GROUP BY k00_numpar,K00_RECEIT, K00_DTOPER,K00_NUMCGM,K00_DTVENC,K00_NUMPRE
        UNION ALL
          SELECT k00_numpar, K00_RECEIT, max(k00_hist) as k00_hist, K00_DTOPER,K00_NUMCGM,K00_DTVENC,K00_NUMPRE,SUM(K00_VALOR) AS K00_VALOR
            FROM RECIBOPAGA
           WHERE K00_NUMPRE = RECORD_NUMPRE.K00_NUMPRE
             AND K00_NUMPAR = RECORD_NUMPRE.K00_NUMPAR
             AND K00_NUMNOV = NUMPRE
             AND K00_CONTA = 0
           GROUP BY k00_numpar,K00_RECEIT,K00_DTOPER,K00_NUMCGM,K00_DTVENC,K00_NUMPRE ) AS X
           ORDER BY K00_NUMPRE,K00_RECEIT
      LOOP

          VALOR_RECEITA := RECORD_ALIAS.K00_VALOR;

          if lRaise then
            perform fc_debug('<autentica>  -- Dentro do for principal --',lRaise,false,false);
            perform fc_debug('<autentica>   Numpar recibopaga : '||RECORD_ALIAS.k00_numpar||' Numpar select principal(db_reciboweb): '||RECORD_NUMPRE.K00_NUMPAR,lRaise,false,false);
          end if;

          IF RECORD_ALIAS.K00_VALOR = 0 THEN
            SELECT Q05_VLRINF
            INTO VLRINF
            FROM ISSVAR
            WHERE Q05_NUMPRE = RECORD_NUMPRE.K00_NUMPRE AND Q05_NUMPAR = RECORD_NUMPRE.K00_NUMPAR;
            IF VLRINF = 0 THEN
              IF UNICA = TRUE THEN

                if lRaise is true then
                  perform fc_debug('<autentica>  6 - Fim do processamento da autenticação...',lRaise, false, true);
                end if;
                rtp_autenticacao.erro     := true;
                rtp_autenticacao.mensagem := '5 VALOR UNICA ZERADO';
                return rtp_autenticacao;

              ELSE

                if lRaise is true then
                   perform fc_debug('<autentica>  7 - Fim do processamento da autenticação...',lRaise, false, true);
                end if;

                rtp_autenticacao.erro     := true;
                rtp_autenticacao.mensagem := '9 VALOR DA PARCELA ZERADA';
                return rtp_autenticacao;

              END IF;
            END IF;
            VALOR_RECEITA := VLRINF;
          END IF;

          RECEITA  := RECORD_ALIAS.K00_RECEIT;
          DTOPER   := RECORD_ALIAS.K00_DTOPER;
          NUMCGM   := RECORD_ALIAS.K00_NUMCGM;
          DATAVENC := RECORD_ALIAS.K00_DTVENC;
          QUAL_OPER := 0;


          if lRaise is true then
            perform fc_debug('<autentica> Processando inclusao dos dados no arrepaga...',lRaise, false, false);
            perform fc_debug('<autentica> Buscando dados do Numpre: '||NUMPRE||' Parcela: '||NUM_PAR||' Receita: '||RECEITA||' nas tabelas arrecad, recibo e recibopaga ',lRaise, false, false);
          end if;

          FOR RECORD_GRAVA IN SELECT * FROM (
            SELECT arrecad.K00_NUMCGM,
            arrecad.K00_DTOPER,
            arrecad.K00_RECEIT,
            arrecad.K00_HIST,
            arrecad.K00_VALOR,
            arrecad.K00_DTVENC,
            arrecad.K00_NUMPRE,
            arrecad.K00_NUMPAR,
            arrecad.K00_NUMTOT,
            arrecad.K00_NUMDIG,
            arrecad.K00_TIPO ,'ARRECAD' AS DB_ALIAS ,
            (select k00_histtxt
               from arrehist
              where arrehist.k00_numpre = arrecad.k00_numpre
                and arrehist.k00_numpar = arrecad.k00_numpar
                limit 1 ) as k00_histtxt
            FROM ARRECAD
            WHERE arrecad.K00_NUMPRE = NUMPRE AND arrecad.K00_NUMPAR = NUM_PAR AND arrecad.K00_RECEIT = RECEITA

            UNION ALL
            SELECT RECIBO.K00_NUMCGM,
            RECIBO.K00_DTOPER,
            RECIBO.K00_RECEIT,
            RECIBO.K00_HIST,
            RECIBO.K00_VALOR,
            RECIBO.K00_DTVENC,
            RECIBO.K00_NUMPRE,
            RECIBO.K00_NUMPAR,
            RECIBO.K00_NUMTOT,
            RECIBO.K00_NUMDIG,
            RECIBO.K00_TIPO,'RECIBO'  AS DB_ALIAS ,
            (select k00_histtxt
               from arrehist
              where arrehist.k00_numpre = recibo.k00_numpre
                limit 1 ) as k00_histtxt
            FROM RECIBO
            WHERE RECIBO.K00_NUMPRE = NUMPRE
            AND RECIBO.K00_NUMPAR = NUM_PAR
            AND RECIBO.K00_RECEIT = RECEITA

            UNION ALL

            SELECT recibopaga.K00_NUMCGM,
                   recibopaga.K00_DTOPER,
                   recibopaga.K00_RECEIT,
                   recibopaga.K00_HIST,
                   recibopaga.K00_VALOR,
                   recibopaga.K00_DTVENC,
                   recibopaga.K00_NUMPRE,
                   recibopaga.K00_NUMPAR,
                   recibopaga.K00_NUMTOT,
                   recibopaga.K00_NUMDIG,
                   20 AS K00_TIPO,
                   'RECUNI'  AS DB_ALIAS ,
                   (select k00_histtxt
                      from arrehist
                     where arrehist.k00_numpre = recibopaga.k00_numnov
                       limit 1 ) as k00_histtxt
              FROM RECIBOPAGA
             WHERE K00_NUMNOV = NUMPRE
               AND recibopaga.K00_NUMPRE = RECORD_NUMPRE.K00_NUMPRE
               AND recibopaga.K00_NUMPAR = RECORD_NUMPRE.K00_NUMPAR
               AND recibopaga.K00_RECEIT = RECEITA ) AS X
         LOOP

            IF (length(RECORD_GRAVA.K00_HISTTXT)>0 )  THEN
              TEMHISTORICO = RECORD_GRAVA.K00_HISTTXT;
            END IF;

            IF QUAL_OPER = 0 THEN
              HIST      := RECORD_GRAVA.K00_HIST;
              NUMTOT    := RECORD_GRAVA.K00_NUMTOT;
              NUMDIG    := RECORD_GRAVA.K00_NUMDIG;
              QUAL_OPER := 1;
              DB_ARQUIVO = RECORD_GRAVA.DB_ALIAS;
            END IF;

            if lRaise is true then
              perform fc_debug('<autentica>  db_arquivo:'||db_arquivo,lRaise,false,false);
            end if;

            PROCESSA := TRUE;
            IF DB_ARQUIVO = 'ARRECAD' THEN
              INSERT INTO ARRECANT  (
              K00_NUMCGM,
              K00_DTOPER,
              K00_RECEIT,
              K00_HIST,
              K00_VALOR,
              K00_DTVENC,
              K00_NUMPRE,
              K00_NUMPAR,
              K00_NUMTOT,
              K00_NUMDIG,
              K00_TIPO,
              K00_TIPOJM
              )
              VALUES (
              RECORD_GRAVA.K00_NUMCGM,
              RECORD_GRAVA.K00_DTOPER,
              RECORD_GRAVA.K00_RECEIT,
              RECORD_GRAVA.K00_HIST,
              RECORD_GRAVA.K00_VALOR,
              RECORD_GRAVA.K00_DTVENC,
              RECORD_GRAVA.K00_NUMPRE,
              RECORD_GRAVA.K00_NUMPAR,
              RECORD_GRAVA.K00_NUMTOT,
              RECORD_GRAVA.K00_NUMDIG,
              RECORD_GRAVA.K00_TIPO,
              0);
            ELSE

              if lRaise is true then
                perform fc_debug('<autentica> 1 - Inserindo dados na arrepaga... Numpre: '||RECORD_GRAVA.K00_NUMPRE||' Numpar:'||RECORD_GRAVA.K00_NUMPAR||' Receita:'||RECORD_GRAVA.K00_RECEIT,lRaise,false,false);
              end if;
              INSERT INTO ARREPAGA
              (
              K00_NUMCGM,
              K00_DTOPER,
              K00_RECEIT,
              K00_HIST,
              K00_VALOR,
              K00_DTVENC,
              K00_NUMPRE,
              K00_NUMPAR,
              K00_NUMTOT,
              K00_NUMDIG,
              K00_CONTA,
              K00_DTPAGA)
              VALUES (
              RECORD_GRAVA.K00_NUMCGM,
              RECORD_GRAVA.K00_DTOPER,
              RECORD_GRAVA.K00_RECEIT,
              RECORD_GRAVA.K00_HIST,
              RECORD_GRAVA.K00_VALOR,
              DATAVENC,
              RECORD_GRAVA.K00_NUMPRE,
              RECORD_GRAVA.K00_NUMPAR,
              RECORD_GRAVA.K00_NUMTOT,
              RECORD_GRAVA.K00_NUMDIG,
              CONTA,
              DTEMITE);
            END IF;
          END LOOP;

          IF DB_ARQUIVO = 'ARRECAD' THEN
            DELETE FROM ARRECAD WHERE K00_NUMPRE = NUMPRE AND K00_NUMPAR = NUM_PAR AND K00_RECEIT = RECEITA;
-- CALCULA CORRECAO
            IF VALOR_RECEITA <> 0 THEN
--       raise notice '%-%-%-%-%-%',RECEITA,DTOPER,VALOR_RECEITA,DTVENC,SUBDIR,DTVENC;

              if iFormaCorrecao = 1 then

                --if  <> 918 then
                  select rnCorreComposJuros, rnCorreComposMulta, rnComposCorrecao, rnComposJuros, rnComposMulta
                    into nCorreComposJuros, nCorreComposMulta, nComposCorrecao, nComposJuros, nComposMulta
                    from fc_retornacomposicao(record_alias.k00_numpre, record_alias.k00_numpar, record_alias.k00_receit, record_alias.k00_hist, dtoper, dtvenc, subdir, datavenc);

                  if lRaise is true then
                     perform fc_debug('<autentica>  1=nComposCorrecao: '||nComposCorrecao||' - VALOR_RECEITA: '||VALOR_RECEITA,lRaise,false,false);
                  end if;

                  valor_receita = valor_receita + nComposCorrecao;

                  if lRaise is true then
                     perform fc_debug('<autentica>  2=nComposCorrecao: '||nComposCorrecao||' - VALOR_RECEITA: '||VALOR_RECEITA,lRaise,false,false);
                  end if;

                --else

                  --valor_receita = valor_receita - RECORD_GRAVA.K00_VALOR;

                --end if;

              end if;

              CORRECAO := ROUND( FC_CORRE(RECEITA,DTOPER,VALOR_RECEITA,DTVENC,SUBDIR,DATAVENC) , 2 );

              if lRaise is true then
                perform fc_debug('<autentica>  CORRECAO 1: '||CORRECAO,lRaise,false,false);
              end if;

              valor_receita = valor_receita - nComposCorrecao;

              CORRECAO := ROUND( CORRECAO - VALOR_RECEITA , 2 );

              if lRaise is true then
                perform fc_debug('<autentica>  CORRECAO 2: '||CORRECAO||' - nCorreComposJuros: '||nCorreComposJuros||' - nCorreComposMulta: '||nCorreComposMulta,lRaise,false,false);
                perform fc_debug('<autentica>  codrec -- '||RECEITA||' correcao -- '||CORRECAO||' receita -- '||VALOR_RECEITA,lRaise,false,false);
              end if;

              correcao := correcao + nCorreComposJuros + nCorreComposMulta;

              if lRaise is true then
                perform fc_debug('<autentica>  CORRECAO 3: '||CORRECAO,lRaise,false,false);
                perform fc_debug('<autentica>  codrec -- '||RECEITA||' correcao -- '||CORRECAO||' receita -- '||VALOR_RECEITA,lRaise,false,false);
              end if;

            ELSE
              CORRECAO := 0;
            END IF;


            VLRCORRECAO := VLRCORRECAO + CORRECAO + VALOR_RECEITA;

            if lRaise is true then
              perform fc_debug('<autentica>  VLRCORRECAO (2): '||VLRCORRECAO,lRaise,false,false);
            end if;

--          IF (VALOR_RECEITA + CORRECAO) <> 0 AND UNICA = FALSE THEN
            IF (VALOR_RECEITA + CORRECAO) <> 0 THEN

              if lRaise is true then
                 perform fc_debug('<autentica>  FC_JUROS('||RECEITA||','||DATAVENC||','||DTEMITE||','||DTOPER||',false,'||SUBDIR||')',lRaise,false,false);
                 perform fc_debug('<autentica>  FC_MULTA('||RECEITA||','||DATAVENC||','||DTEMITE||','||DTOPER||','||SUBDIR||')',lRaise,false,false);
              end if;
-- CALCULA JUROS
              JURO  := ROUND(( CORRECAO+VALOR_RECEITA) * FC_JUROS(RECEITA,DATAVENC,DTEMITE,DTOPER,FALSE,SUBDIR),2 );
              JURO = JURO + nComposJuros;
-- CALCULA MULTA
              MULTA := ROUND(( CORRECAO+VALOR_RECEITA) * FC_MULTA(RECEITA,DATAVENC,DTEMITE,DTOPER,SUBDIR),2 );
              MULTA = MULTA + nComposMulta;

              SELECT K02_RECMUL, K02_RECJUR
              INTO K03_RECMUL,K03_RECJUR
              FROM TABREC
              WHERE K02_CODIGO = RECEITA;
              IF K03_RECMUL IS NULL THEN
                K03_RECMUL = RECEITA_MUL;
              END IF;
              IF K03_RECJUR IS NULL THEN
                K03_RECJUR = RECEITA_JUR;
              END IF;

              IF K03_RECJUR = 0 OR K03_RECMUL = 0 OR K03_RECJUR = K03_RECMUL THEN
                IF JURO+MULTA <> 0 THEN
                  VLRJUROS := VLRJUROS + JURO;
                  VLRMULTA := VLRMULTA + MULTA;

                  if lRaise is true then
                    perform fc_debug('<autentica> 2 - Inserindo dados na arrepaga... Numpre: '||NUMPRE||' Numpar:'||NUM_PAR||' Receita:'||K03_RECJUR,lRaise,false,false);
                  end if;
                  INSERT INTO ARREPAGA
                  (
                  K00_NUMCGM,
                  K00_DTOPER,
                  K00_RECEIT,
                  K00_HIST,
                  K00_VALOR,
                  K00_DTVENC,
                  K00_NUMPRE,
                  K00_NUMPAR,
                  K00_NUMTOT,
                  K00_NUMDIG,
                  K00_CONTA,
                  K00_DTPAGA
                  )
                  VALUES (NUMCGM,
                  DTEMITE,
                  K03_RECJUR,
                  400,
                  (JURO+MULTA),
                  DATAVENC,
                  NUMPRE,
                  NUM_PAR,
                  NUMTOT,
                  NUMDIG,
                  CONTA,
                  DTEMITE
                  );
                END IF;
              ELSE
                IF JURO <> 0 THEN
                  VLRJUROS := VLRJUROS + JURO;

                  if lRaise is true then
                    perform fc_debug('<autentica> 3 - Inserindo dados na arrepaga... Numpre: '||NUMPRE||' Numpar:'||NUM_PAR||' Receita:'||K03_RECJUR,lRaise,false,false);
                  end if;
                  INSERT INTO ARREPAGA
                  (
                  K00_NUMCGM,
                  K00_DTOPER,
                  K00_RECEIT,
                  K00_HIST,
                  K00_VALOR,
                  K00_DTVENC,
                  K00_NUMPRE,
                  K00_NUMPAR,
                  K00_NUMTOT,
                  K00_NUMDIG,
                  K00_CONTA,
                  K00_DTPAGA
                  )
                  VALUES (NUMCGM,
                  DTEMITE,
                  K03_RECJUR,
                  400,
                  JURO,
                  DATAVENC,
                  NUMPRE,
                  NUM_PAR,
                  NUMTOT,
                  NUMDIG,
                  CONTA,
                  DTEMITE
                  );

                END IF;
                IF MULTA <> 0 THEN
                  VLRMULTA := VLRMULTA + MULTA;

                  if lRaise is true then
                    perform fc_debug('<autentica> 4 - Inserindo dados na arrepaga... Numpre: '||NUMPRE||' Numpar:'||NUM_PAR||' Receita:'||K03_RECJUR,lRaise,false,false);
                  end if;
                  INSERT INTO ARREPAGA
                  (
                  K00_NUMCGM,
                  K00_DTOPER,
                  K00_RECEIT,
                  K00_HIST,
                  K00_VALOR,
                  K00_DTVENC,
                  K00_NUMPRE,
                  K00_NUMPAR,
                  K00_NUMTOT,
                  K00_NUMDIG,
                  K00_CONTA,
                  K00_DTPAGA
                  )
                  VALUES (NUMCGM,
                  DTEMITE,
                  K03_RECMUL,
                  401,
                  MULTA,
                  DATAVENC,
                  NUMPRE,
                  NUM_PAR,
                  NUMTOT,
                  NUMDIG,
                  CONTA,
                  DTEMITE
                  );
                END IF;
              END IF;
            END IF;

            if lRaise is true then
              perform fc_debug('<autentica>  valor_receita + correcao = '||(VALOR_RECEITA + CORRECAO),lRaise,false,false);
            end if;

            IF (VALOR_RECEITA + CORRECAO) <> 0 THEN
--              raise notice 'fc_desconto(%,%,%,%,%,%,%,%)',RECEITA,DTEMITE,CORRECAO+VALOR_RECEITA,JURO+MULTA,UNICA,DATAVENC,SUBDIR,NUMPRE;
              DESCONTO := COALESCE(FC_DESCONTO(RECEITA,DTEMITE,CORRECAO+VALOR_RECEITA,JURO+MULTA,UNICA,DATAVENC,SUBDIR,NUMPRE), 0);
              if unica then
                IF DESCONTO <> 0 THEN
                  VLRDESCONTO := VLRDESCONTO + DESCONTO;

                                    if lRaise is true then
                                      perform fc_debug('<autentica> 5 - Inserindo dados na arrepaga... Numpre: '||NUMPRE||' Numpar:'||NUM_PAR||' Receita:'||RECEITA,lRaise,false,false);
                                    end if;
                  INSERT INTO ARREPAGA ( K00_NUMCGM, K00_DTOPER, K00_RECEIT, K00_HIST, K00_VALOR, K00_DTVENC, K00_NUMPRE, K00_NUMPAR, K00_NUMTOT, K00_NUMDIG, K00_CONTA, K00_DTPAGA )
                  VALUES (NUMCGM, DTEMITE, RECEITA, 990,(VALOR_RECEITA+CORRECAO)-DESCONTO, DATAVENC, NUMPRE, NUM_PAR, NUMTOT, NUMDIG, CONTA, DTEMITE );
--                  raise notice 'inserindo unica | valor -- % desconto -- %',(VALOR_RECEITA+CORRECAO),DESCONTO
                else
                                    if lRaise is true then
                                      perform fc_debug('<autentica> 6 - Inserindo dados na arrepaga... Numpre: '||NUMPRE||' Numpar:'||NUM_PAR||' Receita:'||RECEITA,lRaise,false,false);
                                    end if;
                  INSERT INTO ARREPAGA ( K00_NUMCGM, K00_DTOPER, K00_RECEIT, K00_HIST, K00_VALOR, K00_DTVENC, K00_NUMPRE, K00_NUMPAR, K00_NUMTOT, K00_NUMDIG, K00_CONTA, K00_DTPAGA )
                  VALUES (NUMCGM, DTEMITE, RECEITA, 990,(VALOR_RECEITA+CORRECAO), DATAVENC, NUMPRE, NUM_PAR, NUMTOT, NUMDIG, CONTA, DTEMITE );
                END IF;

              if lRaise is true then
                perform fc_debug('<autentica>   (receita + correcao) = '||(VALOR_RECEITA+CORRECAO)||' desconto = '||(DESCONTO*-1),lRaise,false,false);
              end if;

              else

                                if lRaise is true then
                                  perform fc_debug('<autentica> 7 - Inserindo dados na arrepaga... Numpre: '||NUMPRE||' Numpar:'||NUM_PAR||' Receita:'||RECEITA,lRaise,false,false);
                                end if;
                INSERT INTO ARREPAGA ( K00_NUMCGM, K00_DTOPER, K00_RECEIT, K00_HIST, K00_VALOR, K00_DTVENC, K00_NUMPRE, K00_NUMPAR, K00_NUMTOT, K00_NUMDIG, K00_CONTA, K00_DTPAGA )
                              VALUES (NUMCGM, DTEMITE, RECEITA, HIST + 100, VALOR_RECEITA+CORRECAO, DATAVENC, NUMPRE, NUM_PAR, NUMTOT, NUMDIG, CONTA, DTEMITE );
                --CALCULAR DESCONTO
                IF DESCONTO <> 0 THEN
                  VLRDESCONTO := VLRDESCONTO + DESCONTO;

                                    if lRaise is true then
                                      perform fc_debug('<autentica> 8 - Inserindo dados na arrepaga... Numpre: '||NUMPRE||' Numpar:'||NUM_PAR||' Receita:'||RECEITA,lRaise,false,false);
                                    end if;
                  INSERT INTO ARREPAGA ( K00_NUMCGM, K00_DTOPER, K00_RECEIT, K00_HIST, K00_VALOR, K00_DTVENC, K00_NUMPRE, K00_NUMPAR, K00_NUMTOT, K00_NUMDIG, K00_CONTA, K00_DTPAGA )
                  VALUES (NUMCGM, DTEMITE, RECEITA, 918, DESCONTO*-1, DATAVENC, NUMPRE, NUM_PAR, NUMTOT, NUMDIG, CONTA, DTEMITE );
                END IF;
              end if;

            END IF;


          ELSE

            if lRaise then
              perform fc_debug('<autentica>  else db_arquivo != arrecad',lRaise,false,false);
            end if;

            IF RECORD_GRAVA.K00_TIPO = 400 THEN
              VLRJUROS := VLRJUROS + RECORD_ALIAS.K00_VALOR;
            ELSE
              IF RECORD_GRAVA.K00_TIPO = 401 THEN
                VLRMULTA := VLRMULTA + RECORD_ALIAS.K00_VALOR;
              ELSE
                VLRCORRECAO := VLRCORRECAO + RECORD_ALIAS.K00_VALOR ;
              END IF;
            END IF ;

            IF DB_ARQUIVO = 'RECUNI' THEN
              if lRaise then
                perform fc_debug('<autentica>  db_arquivo = recuni',lRaise,false,false);
              end if;

              INSERT INTO ARRECANT
              (
              K00_NUMCGM,
              K00_DTOPER,
              K00_RECEIT,
              K00_HIST,
              K00_VALOR,
              K00_DTVENC,
              K00_NUMPRE,
              K00_NUMPAR,
              K00_NUMTOT,
              K00_NUMDIG,
              K00_TIPO,
              K00_TIPOJM
              )
              SELECT K00_NUMCGM,
                     K00_DTOPER,
                     K00_RECEIT,
                     K00_HIST,
                     K00_VALOR,
                     K00_DTVENC,
                     K00_NUMPRE,
                     K00_NUMPAR,
                     K00_NUMTOT,
                     K00_NUMDIG,
                     K00_TIPO,
                     K00_TIPOJM
                FROM ARRECAD
               WHERE ARRECAD.K00_NUMPRE = RECORD_NUMPRE.K00_NUMPRE
                 and ARRECAD.K00_NUMPAR = RECORD_NUMPRE.K00_NUMPAR
                 AND ARRECAD.K00_RECEIT = RECEITA;

              DELETE FROM ARRECAD
               WHERE ARRECAD.K00_NUMPRE = RECORD_NUMPRE.K00_NUMPRE
                 and ARRECAD.K00_NUMPAR = RECORD_NUMPRE.K00_NUMPAR
                 AND ARRECAD.K00_RECEIT = RECEITA;

              UPDATE RECIBOPAGA
                 SET K00_CONTA = CONTA
               WHERE RECIBOPAGA.K00_NUMPRE = RECORD_NUMPRE.K00_NUMPRE
                 and RECIBOPAGA.K00_NUMPAR = RECORD_NUMPRE.K00_NUMPAR
                 and RECIBOPAGA.K00_RECEIT = RECEITA
                 and RECIBOPAGA.K00_NUMNOV = NUMPRE;

            END IF;
          END IF;
        END LOOP;
      END IF;
    END LOOP;

    --
    -- ADICIONADA A SOMA DA VARIAVEL VTAXAACRESCIMO A VARIAVEL TOTALZAO
    -- ESTA VARIAVEL EH UTILIZADA QUANDO UTILIZAMOS A TAXA DE EXPEDIENTE NO CAIXA
    -- SEU VALOR EH SETADO QUANDO A VARIAVEL DB_utiliza_taxa_expediente_autenticacao EXISTE NA SESSAO
    --
    if fc_getsession('DB_utiliza_taxa_expediente_autenticacao') is not null then
      TOTALZAO = VLRCORRECAO+VLRJUROS+VLRMULTA-VLRDESCONTO + VTAXAACRESCIMO;
    else
      TOTALZAO = VLRCORRECAO+VLRJUROS+VLRMULTA-VLRDESCONTO;
    end if;
    if lRaise is true then
       perform fc_debug('<autentica>  VLRCORRECAO = '||VLRCORRECAO||' VLRJUROS = '||VLRJUROS||' VLRMULTA = '||VLRMULTA||' VLRDESCONTO = '||VLRDESCONTO,lRaise,false,false);
       perform fc_debug('<autentica>  k12_valor: '||TOTALZAO,lRaise,false,false);
    end if;

    IF PROCESSA = TRUE THEN

--RAISE NOTICE '%X%X%X%', VLRCORRECAO,VLRJUROS,VLRMULTA,VLRDESCONTO;

      SELECT K11_ID,K11_IDENT1,K11_IDENT2,K11_IDENT3,K11_TIPAUTENT
        INTO IDTERM,IDENT1,IDENT2,IDENT3,TIPOAUTENT
        FROM CFAUTENT
       WHERE K11_IPTERM = IPTERM
         AND K11_INSTIT = INSTIT;

--raise notice '-%-',idterm;
      IF NOT IDTERM IS NULL AND TIPOAUTENT != 3 THEN
        SELECT MAX(K12_AUTENT)
        INTO CODAUT
        FROM CORRENTE WHERE K12_ID = IDTERM AND K12_DATA = DTEMITE;
        IF CODAUT IS NULL THEN
          CODAUT := 1;
        ELSE
          CODAUT := CODAUT + 1;
        END IF;

-- GRAVA AUTENTICACAO
        hora := to_char(now(), 'HH24:MI');

         --
         -- ADICIONADA A SOMA DA VARIAVEL VTAXAACRESCIMO AOS VALORES QUE SERAO GRAVADOS NO CAMPO K12_VALOR
         -- ESTA VARIAVEL EH UTILIZADA QUANDO UTILIZAMOS A TAXA DE EXPEDIENTE NO CAIXA
         -- SEU VALOR EH SETADO QUANDO A VARIAVEL DB_utiliza_taxa_expediente_autenticacao EXISTE NA SESSAO
         --
         if fc_getsession('DB_utiliza_taxa_expediente_autenticacao') is not null then

           BEGIN
              INSERT INTO CORRENTE ( k12_id, k12_data, k12_autent, k12_hora, k12_conta, k12_valor, k12_estorn, k12_instit )
                            values ( IDTERM, DTEMITE, CODAUT, hora, conta, ABS(VLRCORRECAO+VLRJUROS+VLRMULTA-VLRDESCONTO + VTAXAACRESCIMO), false, INSTIT );
           EXCEPTION WHEN OTHERS THEN
             rtp_autenticacao.erro     := true;
             rtp_autenticacao.mensagem := SQLERRM;
             return rtp_autenticacao;
           END;

         else

           BEGIN
              INSERT INTO CORRENTE ( k12_id, k12_data, k12_autent, k12_hora, k12_conta, k12_valor, k12_estorn, k12_instit )
                            values ( IDTERM, DTEMITE, CODAUT, hora, conta, ABS(VLRCORRECAO+VLRJUROS+VLRMULTA-VLRDESCONTO), false, INSTIT );
           EXCEPTION WHEN OTHERS THEN
             rtp_autenticacao.erro     := true;
             rtp_autenticacao.mensagem := SQLERRM;
             return rtp_autenticacao;
           END;

         end if;


 --
   iTipoGrupo := 3;
   if iCodigoTipoGrupo <> 0 then
     iTipoGrupo := iCodigoTipoGrupo;
   end if;

-- GRAVA GRUPO DA AUTENTICAÇÃO
   if iCodigoGrupo <> 0 then
     insert into
       corgrupocorrente (
          k105_sequencial,
          k105_corgrupo,
          k105_data,
          k105_autent,
          k105_id,
            k105_corgrupotipo
        ) values (
          nextval('corgrupocorrente_k105_sequencial_seq'),
          iCodigoGrupo,
          DTEMITE,
          CODAUT,
          IDTERM,
          iTipoGrupo
      );
   end if;

-- GRAVA HISTORICO
        IF TEMHISTORICO IS NOT NULL THEN
          INSERT INTO CORHIST ( K12_ID, K12_DATA, K12_AUTENT, K12_HISTCOR)
                       values ( IDTERM, DTEMITE, CODAUT, TEMHISTORICO );
        END IF;

        --
        -- Setamos a variavel GRAVA_RECEITA_RECIBO como false para que seja gravada a taxa de expediente
        -- Esta modificacao so eh utlizada quando a variavel DB_utiliza_taxa_expediente_autenticacao esta setada na sessao
        --
        -- Inicio modificacao DB_utiliza_taxa_expediente_autenticacao
        --

        if fc_getsession('DB_utiliza_taxa_expediente_autenticacao') is not null then
           GRAVA_RECEITA_RECIBO := FALSE;
        end if;

        --
        -- FIM MODIFICACAO DB_utiliza_taxa_expediente_autenticacao
        --

        FOR RECORD_NUMPRE IN SELECT DISTINCT K00_NUMPRE,K00_NUMPAR
                               FROM ARREPAGA
                              WHERE K00_NUMPRE = NUMPRE
                            UNION ALL
                             SELECT DISTINCT
                                    recibopaga.K00_NUMPRE,
                                    recibopaga.K00_NUMPAR
                               FROM RECIBOPAGA
                               WHERE K00_NUMNOV = NUMPRE
                               ORDER BY K00_NUMPRE,K00_NUMPAR
        LOOP
/*
        perform k12_numpre, k12_numpar
           from cornump
                inner join corrente on corrente.k12_id     = cornump.k12_id
                                   and corrente.k12_data   = cornump.k12_data
                                   and corrente.k12_autent = cornump.k12_autent
          where cornump.k12_numpre = record_numpre.k00_numpre
            and cornump.k12_numpar = record_numpre.k00_numpar
          group by k12_numpre, k12_numpar
         having cast(round(sum(cornump.k12_valor),2) as numeric) > cast(round(0,2) as numeric);

         if found then
           raise notice 'encontrou parcela paga passando para o proximo';
           continue;
         end if;
*/
          -- FABRIZIO
          IF NUMPAR != 0 THEN
            IF RECORD_NUMPRE.K00_NUMPAR != NUMPAR THEN
              NUM_PAR := 0;
            ELSE
              NUM_PAR := NUMPAR;
            END IF;
          ELSE
            NUM_PAR := RECORD_NUMPRE.K00_NUMPAR;
            UNICA = TRUE;
            perform k00_numpre from arrecad where k00_numpre = numpre;
            if found then
              unica = true;
            else
              unica := exists( select *
                                 from db_reciboweb
                                where k99_numpre_n = numpre
                                  and k99_numpar = 0 );
            end if;

          END IF;


          IF NUM_PAR != 0 or unica is true THEN

          --
          -- ADICIONADA INCLUSAO DA TAXA DE EXPEDIENTE NA ARREPAGA
          -- ESTA VARIAVEL EH UTILIZADA QUANDO UTILIZAMOS A TAXA DE EXPEDIENTE NO CAIXA
          -- SEU VALOR EH SETADO QUANDO A VARIAVEL DB_utiliza_taxa_expediente_autenticacao EXISTE NA SESSAO
          --
          -- Inicio modificacao DB_utiliza_taxa_expediente_autenticacao
          --
          if fc_getsession('DB_utiliza_taxa_expediente_autenticacao') is not null then

             IF GRAVA_RECEITA_RECIBO = FALSE AND VTAXAACRESCIMO > 0 THEN

                INSERT INTO ARREPAGA ( K00_NUMCGM,
                                       K00_DTOPER,
                                       K00_RECEIT,
                                       K00_HIST,
                                       K00_VALOR,
                                       K00_DTVENC,
                                       K00_NUMPRE,
                                       K00_NUMPAR,
                                       K00_NUMTOT,
                                       K00_NUMDIG,
                                       K00_CONTA,
                                       K00_DTPAGA )
                              VALUES ( NUMCGM,
                                       DTEMITE,
                                       RECEITATAXAACRESCIMO,
                                       200,
                                       VTAXAACRESCIMO,
                                       DATAVENC,
                                       RECORD_NUMPRE.K00_NUMPRE,
                                       NUM_PAR,
                                       NUMTOT,
                                       NUMDIG,
                                       CONTA,
                                       DTEMITE );

                GRAVA_RECEITA_RECIBO := TRUE;

             END IF;

          end if;
          --
          -- FIM MODIFICACAO DB_utiliza_taxa_expediente_autenticacao
          --

            FOR GRAVA_CORNUMP IN SELECT K00_RECEIT,SUM(K00_VALOR) AS VALOR
                                   FROM ARREPAGA
                                  WHERE K00_NUMPRE = RECORD_NUMPRE.K00_NUMPRE
                                AND K00_NUMPAR = NUM_PAR
                                  GROUP BY K00_RECEIT
      LOOP

-- quando grava no corrente jÃ¡ grava a instituiÃ§Ã£o passada para esta funÃ§Ã£o
-- quando for gravar aqui no corrente, verifica a instituiÃ§Ã£o da receita
-- se ela confere com a instituiÃ§Ã£o informada
              SELECT K02_TIPO
                INTO VTIPO
                FROM TABREC
               WHERE K02_CODIGO = GRAVA_CORNUMP.K00_RECEIT;
              if lRaise then
               perform fc_debug('<autentica>  Tipo de receita: '||VTIPO,lRaise,false,false);
              end if;

              IF VTIPO = 'O' THEN
                SELECT O70_INSTIT
                  INTO VINSTIT
                  FROM TABORC
                       inner join orcreceita on o70_codrec = k02_codrec
                                            and o70_anousu = k02_anousu
                 WHERE TABORC.K02_CODIGO = GRAVA_CORNUMP.K00_RECEIT
                   AND TABORC.K02_ANOUSU = extract( year from DTEMITE)::integer;

                if VINSTIT is null then
                  rtp_autenticacao.erro     := true;
                  rtp_autenticacao.mensagem := '8 NAO ENCONTRADO REGISTRO DA RECEITA '|| GRAVA_CORNUMP.K00_RECEIT ||' NA ORCRECEITA PARA O ANO '||extract( year from DTEMITE)::integer;
                  return rtp_autenticacao;

                end if;

              ELSE

                SELECT C61_INSTIT
                  INTO VINSTIT
                  FROM TABPLAN
                       INNER JOIN CONPLANOREDUZ ON C61_REDUZ  = k02_REDUZ
                                               AND C61_ANOUSU = K02_ANOUSU
                                               AND C61_INSTIT = INSTIT
                 WHERE TABPLAN.K02_CODIGO = GRAVA_CORNUMP.K00_RECEIT
                   AND K02_ANOUSU = extract( year from DTEMITE)::integer;

                if VINSTIT is null then
                  rtp_autenticacao.erro     := true;
                  rtp_autenticacao.mensagem := '7 NAO ENCONTRADO REGISTRO DA RECEITA '|| GRAVA_CORNUMP.K00_RECEIT ||' NA TABPLAN PARA O ANO '||extract( year from DTEMITE)::integer;
                  return rtp_autenticacao;
                end if;

                if lRaise is true then
                  perform fc_debug('<autentica>  GRAVA_CORNUMP.K00_RECEIT: '||GRAVA_CORNUMP.K00_RECEIT||', INSTIT: '||INSTIT||', DTEMITE: '||DTEMITE||', VINSTIT:'||VINSTIT,lRaise,false,false);
                end if;

              END IF ;

              IF VINSTIT IS NULL  or VINSTIT != INSTIT THEN

                if lRaise is true then
                  perform fc_debug('<autentica>  8 - Fim do processamento da autenticação... ',lRaise, false, true);
                end if;
                rtp_autenticacao.erro     := true;
                rtp_autenticacao.mensagem := '6 RECEITA '|| GRAVA_CORNUMP.K00_RECEIT ||' DE INSTITUICAO DIFERENTE';
                return rtp_autenticacao;

              END IF;

              if lRaise is true then
                perform fc_debug('<autentica>  numpre: '||RECORD_NUMPRE.K00_NUMPRE||' - numpar: '||NUM_PAR||' - receita: '||GRAVA_CORNUMP.K00_RECEIT,lRaise,false,false);
              end if;
              INSERT INTO CORNUMP (k12_id,k12_data,k12_autent,k12_numpre,k12_numpar,k12_numtot,k12_numdig,k12_receit,k12_valor,k12_numnov)
                           values ( IDTERM, DTEMITE, CODAUT, RECORD_NUMPRE.K00_NUMPRE,NUM_PAR, NUMTOT,NUMDIG,GRAVA_CORNUMP.K00_RECEIT,GRAVA_CORNUMP.VALOR, NUMPRE );

            END LOOP;
          END IF;
        END LOOP;
      ELSE
        IF TIPOAUTENT = 3 THEN
-- ERRO QUANDO O TERMINAL NAO ESTA CADASTRADO

          if lRaise is true then
            perform fc_debug('<autentica>  9 - Fim do processamento da autenticação...',lRaise, false, true);
          end if;
          rtp_autenticacao.erro     := true;
          rtp_autenticacao.mensagem := '1 BAIXADO SEM AUTENTICACAO';
          return rtp_autenticacao;

        ELSE

          if lRaise is true then
            perform fc_debug('<autentica>  10 - Fim do processamento da autenticação...',lRaise, false, true);
          end if;
          rtp_autenticacao.erro     := true;
          rtp_autenticacao.mensagem := '2 TERMINAL NAO CADASTRADO';
          return rtp_autenticacao;

        END IF;
      END IF;
-- AUTENTICACAO CORRETA
perform fc_debug('<autentica>  data -- '||IDTERM||' idterm -- '||DTEMITE||' autent -- '||CODAUT,lRaise,false,false);

      select VAL_CORRENTE - VAL_CORNUMP, VAL_CORRENTE, VAL_CORNUMP
        into TOTALZAO, TOTALCORRENTE, TOTALCORNUMP
        from (SELECT (SELECT ROUND(SUM(K12_VALOR),2)
                FROM CORRENTE
                       WHERE K12_DATA   = DTEMITE
                         AND K12_ID     = IDTERM
                         AND K12_AUTENT = CODAUT ) AS VAL_CORRENTE,
                     (SELECT ROUND(SUM(K12_VALOR),2)
              FROM CORNUMP
             WHERE K12_DATA   = DTEMITE
                         AND K12_ID     = IDTERM
                         AND K12_AUTENT = CODAUT) AS VAL_CORNUMP
             ) AS X;

      if lRaise is true then
        perform fc_debug('<autentica>  TOTALZAO: '||TOTALZAO||' - VAL_CORRENTE: '||TOTALCORRENTE||' - VAL_CORNUMP: '||TOTALCORNUMP,lRaise,false,false);
      end if;

      IF TOTALZAO <> 0 THEN

        if lRaise is true then
          perform fc_debug('<autentica>  11 - Fim do processamento da autenticação...',lRaise, false, true);
        end if;
        rtp_autenticacao.erro     := true;
        rtp_autenticacao.mensagem := '5 - INCONSISTENCIA NA AUTENTICACAO! CONTATE SUPORTE!';
        return rtp_autenticacao;

      END IF;

      if fc_getsession('DB_utiliza_taxa_expediente_autenticacao') is not null then
         AUTENTICACAO:= TO_CHAR(CODAUT,'999999') ||
                        DTEMITE ||
                        IDENT1 ||
                        IDENT2 ||
                        IDENT3 ||
                        TO_CHAR(NUMPRE,'99999999') ||
                        TO_CHAR(NUM_PAR,'999') ||
                        TO_CHAR(ABS(VLRCORRECAO+VLRJUROS+VLRMULTA-VLRDESCONTO + VTAXAACRESCIMO),'999999999.99')||'+';
      else
         AUTENTICACAO:= TO_CHAR(CODAUT,'999999') ||
                        DTEMITE ||
                        IDENT1 ||
                        IDENT2 ||
                        IDENT3 ||
                        TO_CHAR(NUMPRE,'99999999') ||
                        TO_CHAR(NUM_PAR,'999') ||
                        TO_CHAR(ABS(VLRCORRECAO+VLRJUROS+VLRMULTA-VLRDESCONTO),'999999999.99')||'+';
      end if;

      INSERT INTO CORAUTENT (K12_ID,
                             K12_DATA,
                             K12_AUTENT,
                             K12_CODAUTENT)
                     VALUES (IDTERM,
                             DTEMITE,
                             CODAUT,
                             AUTENTICACAO);

      if lRaise is true then
        perform fc_debug('<autentica>  12 - Fim do processamento da autenticação...',lRaise, false, true);
      end if;

      rtp_autenticacao.id           := IDTERM;
      rtp_autenticacao.data         := DTEMITE;
      rtp_autenticacao.codautent    := CODAUT;
      rtp_autenticacao.autenticacao := '1' || AUTENTICACAO;
      rtp_autenticacao.erro         := false;
      rtp_autenticacao.mensagem     := '1' || AUTENTICACAO;
      return rtp_autenticacao;

    ELSE
-- NUMPRE NAO PROCESSADO
--raise notice 'numpar: % - NUMPRE % ',NUMPAR,NUMPRE;
      IF NUMPAR = 0 THEN
        SELECT SUM(K00_VALOR)
          INTO VLRCORRECAO
          FROM ARREPAGA
         WHERE K00_NUMPRE = NUMPRE ;
      ELSE
        SELECT SUM(K00_VALOR)
          INTO VLRCORRECAO
          FROM ARREPAGA
         WHERE K00_NUMPRE = NUMPRE
           AND K00_NUMPAR = NUMPAR;
      END IF;
      IF VLRCORRECAO IS NULL THEN

        if lRaise is true then
          perform fc_debug('<autentica>  13 - Fim do processamento da autenticação...',lRaise, false, true);
        end if;
        rtp_autenticacao.erro     := true;
        rtp_autenticacao.mensagem := '4 VALOR ZERADO';
        return rtp_autenticacao;

      ELSE

        if lRaise is true then
          perform fc_debug('<autentica>  14 - Fim do processamento da autenticação...',lRaise, false, true);
        end if;
        rtp_autenticacao.erro     := true;
        rtp_autenticacao.mensagem := '3 VALOR PAGO';
        return rtp_autenticacao;

      END IF;
    END IF;

  END;
$$ language 'plpgsql';


SQL;



        $this->execute($sSql);
    }

    public function down()
    {
        $sSql = <<<SQL

create or replace function fc_autentica(integer,integer,date,date,integer,integer,varchar(20),integer) returns tp_autenticacao_arrecadacao as
$$
declare
 rtp_autenticacao tp_autenticacao_arrecadacao%ROWTYPE;

begin

 rtp_autenticacao := fc_autentica($1, $2, $3, $4, $5, $6, $7, $8, 0);
 return rtp_autenticacao;

end;
$$ language 'plpgsql';

create or replace function fc_autentica(integer,integer,date,date,integer,integer,varchar(20),integer, integer) returns tp_autenticacao_arrecadacao as
$$
DECLARE
  NUMPRE       ALIAS FOR $1;
  NUMPAR       ALIAS FOR $2;
  DTEMITE      ALIAS FOR $3;
  DTVENC       ALIAS FOR $4;
  SUBDIR       ALIAS FOR $5;
  CONTA        ALIAS FOR $6;
  IPTERM       ALIAS FOR $7;
  INSTIT       ALIAS FOR $8;
  iCodigoGrupo alias for $9;

  rtp_autenticacao tp_autenticacao_arrecadacao%ROWTYPE;

  iFormaCorrecao integer default 2;
  ANOUSU         integer;

  CODAUT  INTEGER ;
  IDTERM  INTEGER ;
  HORA    CHAR(5) ;
  IDENT1  CHAR(1);
  IDENT2  CHAR(1);
  IDENT3  CHAR(1);

  UNICA         BOOLEAN := FALSE;
  NUMERO_ERRO   char(200);
  NUMTOT        INTEGER;
  NUMDIG        INTEGER;
  NUMCGM        INTEGER;
  RECORD_NUMPRE  RECORD;
  RECORD_ALIAS   RECORD;
  RECORD_GRAVA   RECORD;
  RECORD_NUMPREF RECORD;
  GRAVA_CORNUMP  RECORD;
  VALOR_RECEITA FLOAT8;
  CORRECAO      FLOAT8;
  JURO          FLOAT8;
  MULTA         FLOAT8;
  DESCONTO      FLOAT8 := 0;
  TOTALZAO      FLOAT8;
  TOTALCORRENTE FLOAT8;
  TOTALCORNUMP  FLOAT8;
  RECEITA       INTEGER;
  K03_RECMUL    INTEGER;
  K03_RECJUR    INTEGER;
  DTOPER        DATE;
  DATAVENC      DATE;
  QUAL_OPER     INTEGER;
  SQLRECIBO     VARCHAR(400);
  VLRCORRECAO   FLOAT8 := 0;
  VLRJUROS      FLOAT8 := 0;
  VLRMULTA      FLOAT8 := 0;
  VLRDESCONTO   FLOAT8 := 0;
  HIST          INTEGER;
  PROCESSA      BOOLEAN;
  NUM_PAR       INTEGER;
  DB_ARQUIVO    VARCHAR(10);
  GRAVAR_RECUNI BOOLEAN DEFAULT FALSE;
  VLRINF    FLOAT8;
  AUTENTICACAO  TEXT;
  totperc   float8;
  TIPOAUTENT  INTEGER;
  iParcelasPagasCanceladas integer;
  iNumpreRecibopaga        integer;
  iNumpreDigitado          integer;
  TEMHISTORICO  TEXT := null;

  v_composicao record;

  nComposCorrecao   numeric(15,2) default 0;
  nComposJuros      numeric(15,2) default 0;
  nComposMulta      numeric(15,2) default 0;

  nCorreComposJuros numeric(15,2) default 0;
  nCorreComposMulta numeric(15,2) default 0;

  lRaise  boolean default false;


  VTIPO   VARCHAR(1);
  VINSTIT INTEGER;

  --
  -- MODIFICACAO REALIZADA PARA UTILIZACAO DAS TAXAS DE EXPEDIENTE JUNTO AO CAIXA
  --
  --
  -- Inicio modificacao DB_utiliza_taxa_expediente_autenticacao
  --

  GRAVA_RECEITA_RECIBO boolean default false;

  VTAXAACRESCIMO numeric(15,2) default 0;
  RECEITATAXAACRESCIMO INTEGER;

  --
  -- Fim modificacao DB_utiliza_taxa_expediente_autenticacao
  --

  BEGIN

    lRaise  := ( case when fc_getsession('DB_debugon') is null then false else true end );
    if lRaise is true then
      perform fc_debug('<autentica>  Inicio processamento autenticação...',lRaise, true, false);
    end if;

    select cast( fc_getsession('DB_anousu') as integer )
      into ANOUSU;


    rtp_autenticacao.id           := 0;
    rtp_autenticacao.data         := null;
    rtp_autenticacao.codautent    := 0;
    rtp_autenticacao.autenticacao := '';
    rtp_autenticacao.erro         := true;
    rtp_autenticacao.mensagem     := '';


    select k03_separajurmulparc
      into iFormaCorrecao
      from numpref
     where k03_instit = INSTIT
       and k03_anousu = ANOUSU;

    select k00_instit
      into VINSTIT
      from arreinstit
     where k00_numpre = NUMPRE;
    if VINSTIT is null then

      if lRaise is true then
        perform fc_debug('<autentica>  1 - Fim do processamento da autenticação...',lRaise, false, true);
      end if;
      rtp_autenticacao.erro     := true;
      rtp_autenticacao.mensagem := '9 NUMPRE '|| NUMPRE ||' SEM INSTITUICAO DEFINIDA';
      return rtp_autenticacao;

    end if;

    if VINSTIT <> INSTIT then

      if lRaise is true then
        perform fc_debug('<autentica>  2 - Fim do processamento da autenticação...',lRaise, false, true);
      end if;
      rtp_autenticacao.erro     := true;
      rtp_autenticacao.mensagem := '7 INSTITUICAO INVALIDA PARA AUTENTICACAO DESTE NUMPRE';
      return rtp_autenticacao;

    end if;

    --
    -- VERIFICAMOS SE ESTA SETADA A VARIAVEL DB_utiliza_taxa_expediente_autenticacao NA SESSAO
    -- SE ESTIVER SETADA UTILIZAMOS A LOGICA DE UTILIZAR A TAXA DE EXPEDIENTE NAS AUTENTICACOES
    --
    -- Inicio modificacao DB_utiliza_taxa_expediente_autenticacao
    --
    if fc_getsession('DB_utiliza_taxa_expediente_autenticacao') is not null then
      select k07_valorf ,
             k07_codigo
        into VTAXAACRESCIMO,
             RECEITATAXAACRESCIMO
        from TABDESC
             inner join tabdesccadban on k114_tabdesc = codsubrec
             inner join cadban        on k114_codban = k15_codigo
       where k15_conta = CONTA;

       if not found then
          VTAXAACRESCIMO := 0;
       end if;
    end if;
    --
    -- Fim modificacao DB_utiliza_taxa_expediente_autenticacao
    --

    perform ar22_sequencial
       from numprebloqpag
      where ar22_numpre = NUMPRE
        and ar22_numpar = NUMPAR;

    if found then

      if lRaise is true then
        perform fc_debug('<autentica>  3 - Fim do processamento da autenticação...',lRaise, false, true);
      end if;
      rtp_autenticacao.erro     := true;
      rtp_autenticacao.mensagem := '9 DÉBITO NÃO PODE SER PAGO, FAVOR VERIFICAR COM CHEFE DO SETOR DE ARRECADAÇÃO';
      return rtp_autenticacao;

    end if;

    IF NUMPAR = '000' then

      -- testar se esse numpre está na recibopaga
      perform k00_numpre
      from recibopaga
      where k00_numnov = NUMPRE;
      if found then

        perform recibopaga.k00_numpre
        from recibopaga
        left join arrecad on recibopaga.k00_numpre = arrecad.k00_numpre
                         and recibopaga.k00_numpar = arrecad.k00_numpar
--                         and recibopaga.k00_receit = arrecad.k00_receit
        where k00_numnov = NUMPRE
          and arrecad.k00_numpre is null;

        if found is true then

          if lRaise is true then
            perform fc_debug('<autentica>  4 - Fim do processamento da autenticação...',lRaise, false, true);
          end if;

          rtp_autenticacao.erro     := true;
          rtp_autenticacao.mensagem := '9 RECIBO INCONSISTENTE. EXISTEM PARCELA(S) PAGA(S)/CANCELADA(S). EMITA OUTRO RECIBO.';
          return rtp_autenticacao;


        end if;

      else

        select abs(count(distinct k00_numpar)-k00_numtot)
        into iParcelasPagasCanceladas
        from arrecad
        where k00_numpre = NUMPRE
        group by k00_numtot
        having count(distinct k00_numpar)<>k00_numtot;

        if found is true and iParcelasPagasCanceladas > 0 then

          if lRaise is true then
            perform fc_debug('<autentica>  5 - Fim do processamento da autenticação...',lRaise, false, true);
          end if;

          rtp_autenticacao.erro     := true;
          rtp_autenticacao.mensagem := '9 RECIBO INCONSISTENTE. EXISTEM PARCELA(S) PAGA(S)/CANCELADA(S). EMITA OUTRO RECIBO.';
          return rtp_autenticacao;

        end if;

      end if;

    end if;


    FOR RECORD_NUMPREF IN SELECT * FROM NUMPREF WHERE K03_ANOUSU = SUBDIR LOOP
      K03_RECJUR := RECORD_NUMPREF.K03_RECJUR;
      K03_RECMUL := RECORD_NUMPREF.K03_RECMUL;
    END LOOP;

--raise notice '%-%',numpre,numpar;
    VALOR_RECEITA := 0;

    FOR RECORD_NUMPRE IN   SELECT * FROM ( SELECT DISTINCT
                                                  K00_NUMPRE,
                                                  K00_NUMPAR
                                             FROM ARRECAD
                                            WHERE K00_NUMPRE = NUMPRE
                                          UNION ALL
                                           SELECT DISTINCT
                                                  RECIBO.K00_NUMPRE,
                                                  RECIBO.K00_NUMPAR
                                             FROM RECIBO
                                                  LEFT OUTER JOIN ARREPAGA ON RECIBO.K00_NUMPRE = ARREPAGA.K00_NUMPRE
                                             WHERE RECIBO.K00_NUMPRE = NUMPRE
                                               AND ARREPAGA.K00_NUMPRE IS NULL
                                          UNION ALL
                                             SELECT DISTINCT
                                                    K00_NUMPRE,
                                                    K00_NUMPAR
                                               FROM RECIBOPAGA
                                              WHERE K00_NUMNOV = NUMPRE
                                             ) AS X
                                             ORDER BY K00_NUMPRE,K00_NUMPAR
    LOOP

      IF NUMPAR != 0 THEN
        IF RECORD_NUMPRE.K00_NUMPAR != NUMPAR THEN
          NUM_PAR := 0;
        ELSE
          NUM_PAR := NUMPAR;
        END IF;
      ELSE
        NUM_PAR := RECORD_NUMPRE.K00_NUMPAR;
        perform k00_numpre from arrecad where k00_numpre = numpre;
        if found then
          unica = true;
        else
          unica := exists( select *
                           from db_reciboweb
                          where k99_numpre_n = numpre
                            and k99_numpar = 0 );
        end if;
      END IF;

      IF NUM_PAR != 0 or unica is true THEN
        PROCESSA := FALSE;

        if lRaise is true then
          perform fc_debug('<autentica>  ok '||numpre||' np '||numpar||' npa-'||RECORD_NUMPRE.K00_NUMPRE||'-'||RECORD_NUMPRE.K00_NUMPAR,lRaise,false,false);
        end if;

        FOR RECORD_ALIAS IN  SELECT * FROM (
          SELECT k00_numpar,K00_RECEIT,max(k00_hist) as k00_hist,K00_DTOPER,K00_NUMCGM,fc_calculavenci(K00_NUMPRE,K00_NUMPAR,K00_DTVENC,DTEMITE) AS K00_DTVENC,K00_NUMPRE,SUM(K00_VALOR) AS K00_VALOR
            FROM ARRECAD
           WHERE K00_NUMPRE = NUMPRE AND K00_NUMPAR = NUM_PAR
           GROUP BY k00_numpar,K00_RECEIT,K00_DTOPER,K00_NUMCGM,K00_DTVENC,K00_NUMPRE,K00_NUMPAR
         UNION ALL
          SELECT k00_numpar, K00_RECEIT, max(k00_hist) as k00_hist, K00_DTOPER,K00_NUMCGM,K00_DTVENC,K00_NUMPRE,SUM(K00_VALOR) AS K00_VALOR
            FROM RECIBO
           WHERE K00_NUMPRE = NUMPRE AND K00_NUMPAR = NUM_PAR
           GROUP BY k00_numpar,K00_RECEIT, K00_DTOPER,K00_NUMCGM,K00_DTVENC,K00_NUMPRE
        UNION ALL
          SELECT k00_numpar, K00_RECEIT, max(k00_hist) as k00_hist, K00_DTOPER,K00_NUMCGM,K00_DTVENC,K00_NUMPRE,SUM(K00_VALOR) AS K00_VALOR
            FROM RECIBOPAGA
           WHERE K00_NUMPRE = RECORD_NUMPRE.K00_NUMPRE
             AND K00_NUMPAR = RECORD_NUMPRE.K00_NUMPAR
             AND K00_NUMNOV = NUMPRE
             AND K00_CONTA = 0
           GROUP BY k00_numpar,K00_RECEIT,K00_DTOPER,K00_NUMCGM,K00_DTVENC,K00_NUMPRE ) AS X
           ORDER BY K00_NUMPRE,K00_RECEIT
      LOOP

          VALOR_RECEITA := RECORD_ALIAS.K00_VALOR;

          if lRaise then
            perform fc_debug('<autentica>  -- Dentro do for principal --',lRaise,false,false);
            perform fc_debug('<autentica>   Numpar recibopaga : '||RECORD_ALIAS.k00_numpar||' Numpar select principal(db_reciboweb): '||RECORD_NUMPRE.K00_NUMPAR,lRaise,false,false);
          end if;

          IF RECORD_ALIAS.K00_VALOR = 0 THEN
            SELECT Q05_VLRINF
            INTO VLRINF
            FROM ISSVAR
            WHERE Q05_NUMPRE = RECORD_NUMPRE.K00_NUMPRE AND Q05_NUMPAR = RECORD_NUMPRE.K00_NUMPAR;
            IF VLRINF = 0 THEN
              IF UNICA = TRUE THEN

                if lRaise is true then
                  perform fc_debug('<autentica>  6 - Fim do processamento da autenticação...',lRaise, false, true);
                end if;
                rtp_autenticacao.erro     := true;
                rtp_autenticacao.mensagem := '5 VALOR UNICA ZERADO';
                return rtp_autenticacao;

              ELSE

                if lRaise is true then
                   perform fc_debug('<autentica>  7 - Fim do processamento da autenticação...',lRaise, false, true);
                end if;

                rtp_autenticacao.erro     := true;
                rtp_autenticacao.mensagem := '9 VALOR DA PARCELA ZERADA';
                return rtp_autenticacao;

              END IF;
            END IF;
            VALOR_RECEITA := VLRINF;
          END IF;

          RECEITA  := RECORD_ALIAS.K00_RECEIT;
          DTOPER   := RECORD_ALIAS.K00_DTOPER;
          NUMCGM   := RECORD_ALIAS.K00_NUMCGM;
          DATAVENC := RECORD_ALIAS.K00_DTVENC;
          QUAL_OPER := 0;


          if lRaise is true then
            perform fc_debug('<autentica> Processando inclusao dos dados no arrepaga...',lRaise, false, false);
            perform fc_debug('<autentica> Buscando dados do Numpre: '||NUMPRE||' Parcela: '||NUM_PAR||' Receita: '||RECEITA||' nas tabelas arrecad, recibo e recibopaga ',lRaise, false, false);
          end if;

          FOR RECORD_GRAVA IN SELECT * FROM (
            SELECT arrecad.K00_NUMCGM,
            arrecad.K00_DTOPER,
            arrecad.K00_RECEIT,
            arrecad.K00_HIST,
            arrecad.K00_VALOR,
            arrecad.K00_DTVENC,
            arrecad.K00_NUMPRE,
            arrecad.K00_NUMPAR,
            arrecad.K00_NUMTOT,
            arrecad.K00_NUMDIG,
            arrecad.K00_TIPO ,'ARRECAD' AS DB_ALIAS ,
            (select k00_histtxt
               from arrehist
              where arrehist.k00_numpre = arrecad.k00_numpre
                and arrehist.k00_numpar = arrecad.k00_numpar
                limit 1 ) as k00_histtxt
            FROM ARRECAD
            WHERE arrecad.K00_NUMPRE = NUMPRE AND arrecad.K00_NUMPAR = NUM_PAR AND arrecad.K00_RECEIT = RECEITA

            UNION ALL
            SELECT RECIBO.K00_NUMCGM,
            RECIBO.K00_DTOPER,
            RECIBO.K00_RECEIT,
            RECIBO.K00_HIST,
            RECIBO.K00_VALOR,
            RECIBO.K00_DTVENC,
            RECIBO.K00_NUMPRE,
            RECIBO.K00_NUMPAR,
            RECIBO.K00_NUMTOT,
            RECIBO.K00_NUMDIG,
            RECIBO.K00_TIPO,'RECIBO'  AS DB_ALIAS ,
            (select k00_histtxt
               from arrehist
              where arrehist.k00_numpre = recibo.k00_numpre
                limit 1 ) as k00_histtxt
            FROM RECIBO
            WHERE RECIBO.K00_NUMPRE = NUMPRE
            AND RECIBO.K00_NUMPAR = NUM_PAR
            AND RECIBO.K00_RECEIT = RECEITA

            UNION ALL

            SELECT recibopaga.K00_NUMCGM,
                   recibopaga.K00_DTOPER,
                   recibopaga.K00_RECEIT,
                   recibopaga.K00_HIST,
                   recibopaga.K00_VALOR,
                   recibopaga.K00_DTVENC,
                   recibopaga.K00_NUMPRE,
                   recibopaga.K00_NUMPAR,
                   recibopaga.K00_NUMTOT,
                   recibopaga.K00_NUMDIG,
                   20 AS K00_TIPO,
                   'RECUNI'  AS DB_ALIAS ,
                   (select k00_histtxt
                      from arrehist
                     where arrehist.k00_numpre = recibopaga.k00_numnov
                       limit 1 ) as k00_histtxt
              FROM RECIBOPAGA
             WHERE K00_NUMNOV = NUMPRE
               AND recibopaga.K00_NUMPRE = RECORD_NUMPRE.K00_NUMPRE
               AND recibopaga.K00_NUMPAR = RECORD_NUMPRE.K00_NUMPAR
               AND recibopaga.K00_RECEIT = RECEITA ) AS X
         LOOP

            IF (length(RECORD_GRAVA.K00_HISTTXT)>0 )  THEN
              TEMHISTORICO = RECORD_GRAVA.K00_HISTTXT;
            END IF;

            IF QUAL_OPER = 0 THEN
              HIST      := RECORD_GRAVA.K00_HIST;
              NUMTOT    := RECORD_GRAVA.K00_NUMTOT;
              NUMDIG    := RECORD_GRAVA.K00_NUMDIG;
              QUAL_OPER := 1;
              DB_ARQUIVO = RECORD_GRAVA.DB_ALIAS;
            END IF;

            if lRaise is true then
              perform fc_debug('<autentica>  db_arquivo:'||db_arquivo,lRaise,false,false);
            end if;

            PROCESSA := TRUE;
            IF DB_ARQUIVO = 'ARRECAD' THEN
              INSERT INTO ARRECANT  (
              K00_NUMCGM,
              K00_DTOPER,
              K00_RECEIT,
              K00_HIST,
              K00_VALOR,
              K00_DTVENC,
              K00_NUMPRE,
              K00_NUMPAR,
              K00_NUMTOT,
              K00_NUMDIG,
              K00_TIPO,
              K00_TIPOJM
              )
              VALUES (
              RECORD_GRAVA.K00_NUMCGM,
              RECORD_GRAVA.K00_DTOPER,
              RECORD_GRAVA.K00_RECEIT,
              RECORD_GRAVA.K00_HIST,
              RECORD_GRAVA.K00_VALOR,
              RECORD_GRAVA.K00_DTVENC,
              RECORD_GRAVA.K00_NUMPRE,
              RECORD_GRAVA.K00_NUMPAR,
              RECORD_GRAVA.K00_NUMTOT,
              RECORD_GRAVA.K00_NUMDIG,
              RECORD_GRAVA.K00_TIPO,
              0);
            ELSE

              if lRaise is true then
                perform fc_debug('<autentica> 1 - Inserindo dados na arrepaga... Numpre: '||RECORD_GRAVA.K00_NUMPRE||' Numpar:'||RECORD_GRAVA.K00_NUMPAR||' Receita:'||RECORD_GRAVA.K00_RECEIT,lRaise,false,false);
              end if;
              INSERT INTO ARREPAGA
              (
              K00_NUMCGM,
              K00_DTOPER,
              K00_RECEIT,
              K00_HIST,
              K00_VALOR,
              K00_DTVENC,
              K00_NUMPRE,
              K00_NUMPAR,
              K00_NUMTOT,
              K00_NUMDIG,
              K00_CONTA,
              K00_DTPAGA)
              VALUES (
              RECORD_GRAVA.K00_NUMCGM,
              RECORD_GRAVA.K00_DTOPER,
              RECORD_GRAVA.K00_RECEIT,
              RECORD_GRAVA.K00_HIST,
              RECORD_GRAVA.K00_VALOR,
              DATAVENC,
              RECORD_GRAVA.K00_NUMPRE,
              RECORD_GRAVA.K00_NUMPAR,
              RECORD_GRAVA.K00_NUMTOT,
              RECORD_GRAVA.K00_NUMDIG,
              CONTA,
              DTEMITE);
            END IF;
          END LOOP;

          IF DB_ARQUIVO = 'ARRECAD' THEN
            DELETE FROM ARRECAD WHERE K00_NUMPRE = NUMPRE AND K00_NUMPAR = NUM_PAR AND K00_RECEIT = RECEITA;
-- CALCULA CORRECAO
            IF VALOR_RECEITA <> 0 THEN
--       raise notice '%-%-%-%-%-%',RECEITA,DTOPER,VALOR_RECEITA,DTVENC,SUBDIR,DTVENC;

              if iFormaCorrecao = 1 then

                --if  <> 918 then
                  select rnCorreComposJuros, rnCorreComposMulta, rnComposCorrecao, rnComposJuros, rnComposMulta
                    into nCorreComposJuros, nCorreComposMulta, nComposCorrecao, nComposJuros, nComposMulta
                    from fc_retornacomposicao(record_alias.k00_numpre, record_alias.k00_numpar, record_alias.k00_receit, record_alias.k00_hist, dtoper, dtvenc, subdir, datavenc);

                  if lRaise is true then
                     perform fc_debug('<autentica>  1=nComposCorrecao: '||nComposCorrecao||' - VALOR_RECEITA: '||VALOR_RECEITA,lRaise,false,false);
                  end if;

                  valor_receita = valor_receita + nComposCorrecao;

                  if lRaise is true then
                     perform fc_debug('<autentica>  2=nComposCorrecao: '||nComposCorrecao||' - VALOR_RECEITA: '||VALOR_RECEITA,lRaise,false,false);
                  end if;

                --else

                  --valor_receita = valor_receita - RECORD_GRAVA.K00_VALOR;

                --end if;

              end if;

              CORRECAO := ROUND( FC_CORRE(RECEITA,DTOPER,VALOR_RECEITA,DTVENC,SUBDIR,DATAVENC) , 2 );

              if lRaise is true then
                perform fc_debug('<autentica>  CORRECAO 1: '||CORRECAO,lRaise,false,false);
              end if;

              valor_receita = valor_receita - nComposCorrecao;

              CORRECAO := ROUND( CORRECAO - VALOR_RECEITA , 2 );

              if lRaise is true then
                perform fc_debug('<autentica>  CORRECAO 2: '||CORRECAO||' - nCorreComposJuros: '||nCorreComposJuros||' - nCorreComposMulta: '||nCorreComposMulta,lRaise,false,false);
                perform fc_debug('<autentica>  codrec -- '||RECEITA||' correcao -- '||CORRECAO||' receita -- '||VALOR_RECEITA,lRaise,false,false);
              end if;

              correcao := correcao + nCorreComposJuros + nCorreComposMulta;

              if lRaise is true then
                perform fc_debug('<autentica>  CORRECAO 3: '||CORRECAO,lRaise,false,false);
                perform fc_debug('<autentica>  codrec -- '||RECEITA||' correcao -- '||CORRECAO||' receita -- '||VALOR_RECEITA,lRaise,false,false);
              end if;

            ELSE
              CORRECAO := 0;
            END IF;


            VLRCORRECAO := VLRCORRECAO + CORRECAO + VALOR_RECEITA;

            if lRaise is true then
              perform fc_debug('<autentica>  VLRCORRECAO (2): '||VLRCORRECAO,lRaise,false,false);
            end if;

--          IF (VALOR_RECEITA + CORRECAO) <> 0 AND UNICA = FALSE THEN
            IF (VALOR_RECEITA + CORRECAO) <> 0 THEN

              if lRaise is true then
                 perform fc_debug('<autentica>  FC_JUROS('||RECEITA||','||DATAVENC||','||DTEMITE||','||DTOPER||',false,'||SUBDIR||')',lRaise,false,false);
                 perform fc_debug('<autentica>  FC_MULTA('||RECEITA||','||DATAVENC||','||DTEMITE||','||DTOPER||','||SUBDIR||')',lRaise,false,false);
              end if;
-- CALCULA JUROS
              JURO  := ROUND(( CORRECAO+VALOR_RECEITA) * FC_JUROS(RECEITA,DATAVENC,DTEMITE,DTOPER,FALSE,SUBDIR),2 );
              JURO = JURO + nComposJuros;
-- CALCULA MULTA
              MULTA := ROUND(( CORRECAO+VALOR_RECEITA) * FC_MULTA(RECEITA,DATAVENC,DTEMITE,DTOPER,SUBDIR),2 );
              MULTA = MULTA + nComposMulta;

              SELECT K02_RECMUL, K02_RECJUR
              INTO K03_RECMUL,K03_RECJUR
              FROM TABREC
              WHERE K02_CODIGO = RECEITA;
              IF K03_RECMUL IS NULL THEN
                K03_RECMUL = RECEITA_MUL;
              END IF;
              IF K03_RECJUR IS NULL THEN
                K03_RECJUR = RECEITA_JUR;
              END IF;

              IF K03_RECJUR = 0 OR K03_RECMUL = 0 OR K03_RECJUR = K03_RECMUL THEN
                IF JURO+MULTA <> 0 THEN
                  VLRJUROS := VLRJUROS + JURO;
                  VLRMULTA := VLRMULTA + MULTA;

                  if lRaise is true then
                    perform fc_debug('<autentica> 2 - Inserindo dados na arrepaga... Numpre: '||NUMPRE||' Numpar:'||NUM_PAR||' Receita:'||K03_RECJUR,lRaise,false,false);
                  end if;
                  INSERT INTO ARREPAGA
                  (
                  K00_NUMCGM,
                  K00_DTOPER,
                  K00_RECEIT,
                  K00_HIST,
                  K00_VALOR,
                  K00_DTVENC,
                  K00_NUMPRE,
                  K00_NUMPAR,
                  K00_NUMTOT,
                  K00_NUMDIG,
                  K00_CONTA,
                  K00_DTPAGA
                  )
                  VALUES (NUMCGM,
                  DTEMITE,
                  K03_RECJUR,
                  400,
                  (JURO+MULTA),
                  DATAVENC,
                  NUMPRE,
                  NUM_PAR,
                  NUMTOT,
                  NUMDIG,
                  CONTA,
                  DTEMITE
                  );
                END IF;
              ELSE
                IF JURO <> 0 THEN
                  VLRJUROS := VLRJUROS + JURO;

                  if lRaise is true then
                    perform fc_debug('<autentica> 3 - Inserindo dados na arrepaga... Numpre: '||NUMPRE||' Numpar:'||NUM_PAR||' Receita:'||K03_RECJUR,lRaise,false,false);
                  end if;
                  INSERT INTO ARREPAGA
                  (
                  K00_NUMCGM,
                  K00_DTOPER,
                  K00_RECEIT,
                  K00_HIST,
                  K00_VALOR,
                  K00_DTVENC,
                  K00_NUMPRE,
                  K00_NUMPAR,
                  K00_NUMTOT,
                  K00_NUMDIG,
                  K00_CONTA,
                  K00_DTPAGA
                  )
                  VALUES (NUMCGM,
                  DTEMITE,
                  K03_RECJUR,
                  400,
                  JURO,
                  DATAVENC,
                  NUMPRE,
                  NUM_PAR,
                  NUMTOT,
                  NUMDIG,
                  CONTA,
                  DTEMITE
                  );

                END IF;
                IF MULTA <> 0 THEN
                  VLRMULTA := VLRMULTA + MULTA;

                  if lRaise is true then
                    perform fc_debug('<autentica> 4 - Inserindo dados na arrepaga... Numpre: '||NUMPRE||' Numpar:'||NUM_PAR||' Receita:'||K03_RECJUR,lRaise,false,false);
                  end if;
                  INSERT INTO ARREPAGA
                  (
                  K00_NUMCGM,
                  K00_DTOPER,
                  K00_RECEIT,
                  K00_HIST,
                  K00_VALOR,
                  K00_DTVENC,
                  K00_NUMPRE,
                  K00_NUMPAR,
                  K00_NUMTOT,
                  K00_NUMDIG,
                  K00_CONTA,
                  K00_DTPAGA
                  )
                  VALUES (NUMCGM,
                  DTEMITE,
                  K03_RECMUL,
                  401,
                  MULTA,
                  DATAVENC,
                  NUMPRE,
                  NUM_PAR,
                  NUMTOT,
                  NUMDIG,
                  CONTA,
                  DTEMITE
                  );
                END IF;
              END IF;
            END IF;

            if lRaise is true then
              perform fc_debug('<autentica>  valor_receita + correcao = '||(VALOR_RECEITA + CORRECAO),lRaise,false,false);
            end if;

            IF (VALOR_RECEITA + CORRECAO) <> 0 THEN
--              raise notice 'fc_desconto(%,%,%,%,%,%,%,%)',RECEITA,DTEMITE,CORRECAO+VALOR_RECEITA,JURO+MULTA,UNICA,DATAVENC,SUBDIR,NUMPRE;
              DESCONTO := COALESCE(FC_DESCONTO(RECEITA,DTEMITE,CORRECAO+VALOR_RECEITA,JURO+MULTA,UNICA,DATAVENC,SUBDIR,NUMPRE), 0);
              if unica then
                IF DESCONTO <> 0 THEN
                  VLRDESCONTO := VLRDESCONTO + DESCONTO;

                                    if lRaise is true then
                                      perform fc_debug('<autentica> 5 - Inserindo dados na arrepaga... Numpre: '||NUMPRE||' Numpar:'||NUM_PAR||' Receita:'||RECEITA,lRaise,false,false);
                                    end if;
                  INSERT INTO ARREPAGA ( K00_NUMCGM, K00_DTOPER, K00_RECEIT, K00_HIST, K00_VALOR, K00_DTVENC, K00_NUMPRE, K00_NUMPAR, K00_NUMTOT, K00_NUMDIG, K00_CONTA, K00_DTPAGA )
                  VALUES (NUMCGM, DTEMITE, RECEITA, 990,(VALOR_RECEITA+CORRECAO)-DESCONTO, DATAVENC, NUMPRE, NUM_PAR, NUMTOT, NUMDIG, CONTA, DTEMITE );
--                  raise notice 'inserindo unica | valor -- % desconto -- %',(VALOR_RECEITA+CORRECAO),DESCONTO
                else
                                    if lRaise is true then
                                      perform fc_debug('<autentica> 6 - Inserindo dados na arrepaga... Numpre: '||NUMPRE||' Numpar:'||NUM_PAR||' Receita:'||RECEITA,lRaise,false,false);
                                    end if;
                  INSERT INTO ARREPAGA ( K00_NUMCGM, K00_DTOPER, K00_RECEIT, K00_HIST, K00_VALOR, K00_DTVENC, K00_NUMPRE, K00_NUMPAR, K00_NUMTOT, K00_NUMDIG, K00_CONTA, K00_DTPAGA )
                  VALUES (NUMCGM, DTEMITE, RECEITA, 990,(VALOR_RECEITA+CORRECAO), DATAVENC, NUMPRE, NUM_PAR, NUMTOT, NUMDIG, CONTA, DTEMITE );
                END IF;

              if lRaise is true then
                perform fc_debug('<autentica>   (receita + correcao) = '||(VALOR_RECEITA+CORRECAO)||' desconto = '||(DESCONTO*-1),lRaise,false,false);
              end if;

              else

                                if lRaise is true then
                                  perform fc_debug('<autentica> 7 - Inserindo dados na arrepaga... Numpre: '||NUMPRE||' Numpar:'||NUM_PAR||' Receita:'||RECEITA,lRaise,false,false);
                                end if;
                INSERT INTO ARREPAGA ( K00_NUMCGM, K00_DTOPER, K00_RECEIT, K00_HIST, K00_VALOR, K00_DTVENC, K00_NUMPRE, K00_NUMPAR, K00_NUMTOT, K00_NUMDIG, K00_CONTA, K00_DTPAGA )
                              VALUES (NUMCGM, DTEMITE, RECEITA, HIST + 100, VALOR_RECEITA+CORRECAO, DATAVENC, NUMPRE, NUM_PAR, NUMTOT, NUMDIG, CONTA, DTEMITE );
                --CALCULAR DESCONTO
                IF DESCONTO <> 0 THEN
                  VLRDESCONTO := VLRDESCONTO + DESCONTO;

                                    if lRaise is true then
                                      perform fc_debug('<autentica> 8 - Inserindo dados na arrepaga... Numpre: '||NUMPRE||' Numpar:'||NUM_PAR||' Receita:'||RECEITA,lRaise,false,false);
                                    end if;
                  INSERT INTO ARREPAGA ( K00_NUMCGM, K00_DTOPER, K00_RECEIT, K00_HIST, K00_VALOR, K00_DTVENC, K00_NUMPRE, K00_NUMPAR, K00_NUMTOT, K00_NUMDIG, K00_CONTA, K00_DTPAGA )
                  VALUES (NUMCGM, DTEMITE, RECEITA, 918, DESCONTO*-1, DATAVENC, NUMPRE, NUM_PAR, NUMTOT, NUMDIG, CONTA, DTEMITE );
                END IF;
              end if;

            END IF;


          ELSE

            if lRaise then
              perform fc_debug('<autentica>  else db_arquivo != arrecad',lRaise,false,false);
            end if;

            IF RECORD_GRAVA.K00_TIPO = 400 THEN
              VLRJUROS := VLRJUROS + RECORD_ALIAS.K00_VALOR;
            ELSE
              IF RECORD_GRAVA.K00_TIPO = 401 THEN
                VLRMULTA := VLRMULTA + RECORD_ALIAS.K00_VALOR;
              ELSE
                VLRCORRECAO := VLRCORRECAO + RECORD_ALIAS.K00_VALOR ;
              END IF;
            END IF ;

            IF DB_ARQUIVO = 'RECUNI' THEN
              if lRaise then
                perform fc_debug('<autentica>  db_arquivo = recuni',lRaise,false,false);
              end if;

              INSERT INTO ARRECANT
              (
              K00_NUMCGM,
              K00_DTOPER,
              K00_RECEIT,
              K00_HIST,
              K00_VALOR,
              K00_DTVENC,
              K00_NUMPRE,
              K00_NUMPAR,
              K00_NUMTOT,
              K00_NUMDIG,
              K00_TIPO,
              K00_TIPOJM
              )
              SELECT K00_NUMCGM,
                     K00_DTOPER,
                     K00_RECEIT,
                     K00_HIST,
                     K00_VALOR,
                     K00_DTVENC,
                     K00_NUMPRE,
                     K00_NUMPAR,
                     K00_NUMTOT,
                     K00_NUMDIG,
                     K00_TIPO,
                     K00_TIPOJM
                FROM ARRECAD
               WHERE ARRECAD.K00_NUMPRE = RECORD_NUMPRE.K00_NUMPRE
                 and ARRECAD.K00_NUMPAR = RECORD_NUMPRE.K00_NUMPAR
                 AND ARRECAD.K00_RECEIT = RECEITA;

              DELETE FROM ARRECAD
               WHERE ARRECAD.K00_NUMPRE = RECORD_NUMPRE.K00_NUMPRE
                 and ARRECAD.K00_NUMPAR = RECORD_NUMPRE.K00_NUMPAR
                 AND ARRECAD.K00_RECEIT = RECEITA;

              UPDATE RECIBOPAGA
                 SET K00_CONTA = CONTA
               WHERE RECIBOPAGA.K00_NUMPRE = RECORD_NUMPRE.K00_NUMPRE
                 and RECIBOPAGA.K00_NUMPAR = RECORD_NUMPRE.K00_NUMPAR
                 and RECIBOPAGA.K00_RECEIT = RECEITA
                 and RECIBOPAGA.K00_NUMNOV = NUMPRE;

            END IF;
          END IF;
        END LOOP;
      END IF;
    END LOOP;

    --
    -- ADICIONADA A SOMA DA VARIAVEL VTAXAACRESCIMO A VARIAVEL TOTALZAO
    -- ESTA VARIAVEL EH UTILIZADA QUANDO UTILIZAMOS A TAXA DE EXPEDIENTE NO CAIXA
    -- SEU VALOR EH SETADO QUANDO A VARIAVEL DB_utiliza_taxa_expediente_autenticacao EXISTE NA SESSAO
    --
    if fc_getsession('DB_utiliza_taxa_expediente_autenticacao') is not null then
      TOTALZAO = VLRCORRECAO+VLRJUROS+VLRMULTA-VLRDESCONTO + VTAXAACRESCIMO;
    else
      TOTALZAO = VLRCORRECAO+VLRJUROS+VLRMULTA-VLRDESCONTO;
    end if;
    if lRaise is true then
       perform fc_debug('<autentica>  VLRCORRECAO = '||VLRCORRECAO||' VLRJUROS = '||VLRJUROS||' VLRMULTA = '||VLRMULTA||' VLRDESCONTO = '||VLRDESCONTO,lRaise,false,false);
       perform fc_debug('<autentica>  k12_valor: '||TOTALZAO,lRaise,false,false);
    end if;

    IF PROCESSA = TRUE THEN

--RAISE NOTICE '%X%X%X%', VLRCORRECAO,VLRJUROS,VLRMULTA,VLRDESCONTO;

      SELECT K11_ID,K11_IDENT1,K11_IDENT2,K11_IDENT3,K11_TIPAUTENT
        INTO IDTERM,IDENT1,IDENT2,IDENT3,TIPOAUTENT
        FROM CFAUTENT
       WHERE K11_IPTERM = IPTERM
         AND K11_INSTIT = INSTIT;

--raise notice '-%-',idterm;
      IF NOT IDTERM IS NULL AND TIPOAUTENT != 3 THEN
        SELECT MAX(K12_AUTENT)
        INTO CODAUT
        FROM CORRENTE WHERE K12_ID = IDTERM AND K12_DATA = DTEMITE;
        IF CODAUT IS NULL THEN
          CODAUT := 1;
        ELSE
          CODAUT := CODAUT + 1;
        END IF;

-- GRAVA AUTENTICACAO
        hora := to_char(now(), 'HH24:MI');

         --
         -- ADICIONADA A SOMA DA VARIAVEL VTAXAACRESCIMO AOS VALORES QUE SERAO GRAVADOS NO CAMPO K12_VALOR
         -- ESTA VARIAVEL EH UTILIZADA QUANDO UTILIZAMOS A TAXA DE EXPEDIENTE NO CAIXA
         -- SEU VALOR EH SETADO QUANDO A VARIAVEL DB_utiliza_taxa_expediente_autenticacao EXISTE NA SESSAO
         --
         if fc_getsession('DB_utiliza_taxa_expediente_autenticacao') is not null then

           BEGIN
              INSERT INTO CORRENTE ( k12_id, k12_data, k12_autent, k12_hora, k12_conta, k12_valor, k12_estorn, k12_instit )
                            values ( IDTERM, DTEMITE, CODAUT, hora, conta, ABS(VLRCORRECAO+VLRJUROS+VLRMULTA-VLRDESCONTO + VTAXAACRESCIMO), false, INSTIT );
           EXCEPTION WHEN OTHERS THEN
             rtp_autenticacao.erro     := true;
             rtp_autenticacao.mensagem := SQLERRM;
             return rtp_autenticacao;
           END;

         else

           BEGIN
              INSERT INTO CORRENTE ( k12_id, k12_data, k12_autent, k12_hora, k12_conta, k12_valor, k12_estorn, k12_instit )
                            values ( IDTERM, DTEMITE, CODAUT, hora, conta, ABS(VLRCORRECAO+VLRJUROS+VLRMULTA-VLRDESCONTO), false, INSTIT );
           EXCEPTION WHEN OTHERS THEN
             rtp_autenticacao.erro     := true;
             rtp_autenticacao.mensagem := SQLERRM;
             return rtp_autenticacao;
           END;

         end if;



-- GRAVA GRUPO DA AUTENTICAÇÃO
   if iCodigoGrupo <> 0 then
     insert into
       corgrupocorrente (
          k105_sequencial,
          k105_corgrupo,
          k105_data,
          k105_autent,
          k105_id,
            k105_corgrupotipo
        ) values (
          nextval('corgrupocorrente_k105_sequencial_seq'),
          iCodigoGrupo,
          DTEMITE,
          CODAUT,
          IDTERM,
            3
      );
   end if;

-- GRAVA HISTORICO
        IF TEMHISTORICO IS NOT NULL THEN
          INSERT INTO CORHIST ( K12_ID, K12_DATA, K12_AUTENT, K12_HISTCOR)
                       values ( IDTERM, DTEMITE, CODAUT, TEMHISTORICO );
        END IF;

        --
        -- Setamos a variavel GRAVA_RECEITA_RECIBO como false para que seja gravada a taxa de expediente
        -- Esta modificacao so eh utlizada quando a variavel DB_utiliza_taxa_expediente_autenticacao esta setada na sessao
        --
        -- Inicio modificacao DB_utiliza_taxa_expediente_autenticacao
        --

        if fc_getsession('DB_utiliza_taxa_expediente_autenticacao') is not null then
           GRAVA_RECEITA_RECIBO := FALSE;
        end if;

        --
        -- FIM MODIFICACAO DB_utiliza_taxa_expediente_autenticacao
        --

        FOR RECORD_NUMPRE IN SELECT DISTINCT K00_NUMPRE,K00_NUMPAR
                               FROM ARREPAGA
                              WHERE K00_NUMPRE = NUMPRE
                            UNION ALL
                             SELECT DISTINCT
                                    recibopaga.K00_NUMPRE,
                                    recibopaga.K00_NUMPAR
                               FROM RECIBOPAGA
                               WHERE K00_NUMNOV = NUMPRE
                               ORDER BY K00_NUMPRE,K00_NUMPAR
        LOOP
/*
        perform k12_numpre, k12_numpar
           from cornump
                inner join corrente on corrente.k12_id     = cornump.k12_id
                                   and corrente.k12_data   = cornump.k12_data
                                   and corrente.k12_autent = cornump.k12_autent
          where cornump.k12_numpre = record_numpre.k00_numpre
            and cornump.k12_numpar = record_numpre.k00_numpar
          group by k12_numpre, k12_numpar
         having cast(round(sum(cornump.k12_valor),2) as numeric) > cast(round(0,2) as numeric);

         if found then
           raise notice 'encontrou parcela paga passando para o proximo';
           continue;
         end if;
*/
          -- FABRIZIO
          IF NUMPAR != 0 THEN
            IF RECORD_NUMPRE.K00_NUMPAR != NUMPAR THEN
              NUM_PAR := 0;
            ELSE
              NUM_PAR := NUMPAR;
            END IF;
          ELSE
            NUM_PAR := RECORD_NUMPRE.K00_NUMPAR;
            UNICA = TRUE;
            perform k00_numpre from arrecad where k00_numpre = numpre;
            if found then
              unica = true;
            else
              unica := exists( select *
                                 from db_reciboweb
                                where k99_numpre_n = numpre
                                  and k99_numpar = 0 );
            end if;

          END IF;


          IF NUM_PAR != 0 or unica is true THEN

          --
          -- ADICIONADA INCLUSAO DA TAXA DE EXPEDIENTE NA ARREPAGA
          -- ESTA VARIAVEL EH UTILIZADA QUANDO UTILIZAMOS A TAXA DE EXPEDIENTE NO CAIXA
          -- SEU VALOR EH SETADO QUANDO A VARIAVEL DB_utiliza_taxa_expediente_autenticacao EXISTE NA SESSAO
          --
          -- Inicio modificacao DB_utiliza_taxa_expediente_autenticacao
          --
          if fc_getsession('DB_utiliza_taxa_expediente_autenticacao') is not null then

             IF GRAVA_RECEITA_RECIBO = FALSE AND VTAXAACRESCIMO > 0 THEN

                INSERT INTO ARREPAGA ( K00_NUMCGM,
                                       K00_DTOPER,
                                       K00_RECEIT,
                                       K00_HIST,
                                       K00_VALOR,
                                       K00_DTVENC,
                                       K00_NUMPRE,
                                       K00_NUMPAR,
                                       K00_NUMTOT,
                                       K00_NUMDIG,
                                       K00_CONTA,
                                       K00_DTPAGA )
                              VALUES ( NUMCGM,
                                       DTEMITE,
                                       RECEITATAXAACRESCIMO,
                                       200,
                                       VTAXAACRESCIMO,
                                       DATAVENC,
                                       RECORD_NUMPRE.K00_NUMPRE,
                                       NUM_PAR,
                                       NUMTOT,
                                       NUMDIG,
                                       CONTA,
                                       DTEMITE );

                GRAVA_RECEITA_RECIBO := TRUE;

             END IF;

          end if;
          --
          -- FIM MODIFICACAO DB_utiliza_taxa_expediente_autenticacao
          --

            FOR GRAVA_CORNUMP IN SELECT K00_RECEIT,SUM(K00_VALOR) AS VALOR
                                   FROM ARREPAGA
                                  WHERE K00_NUMPRE = RECORD_NUMPRE.K00_NUMPRE
                                AND K00_NUMPAR = NUM_PAR
                                  GROUP BY K00_RECEIT
      LOOP

-- quando grava no corrente jÃ¡ grava a instituiÃ§Ã£o passada para esta funÃ§Ã£o
-- quando for gravar aqui no corrente, verifica a instituiÃ§Ã£o da receita
-- se ela confere com a instituiÃ§Ã£o informada
              SELECT K02_TIPO
                INTO VTIPO
                FROM TABREC
               WHERE K02_CODIGO = GRAVA_CORNUMP.K00_RECEIT;
              if lRaise then
               perform fc_debug('<autentica>  Tipo de receita: '||VTIPO,lRaise,false,false);
              end if;

              IF VTIPO = 'O' THEN
                SELECT O70_INSTIT
                  INTO VINSTIT
                  FROM TABORC
                       inner join orcreceita on o70_codrec = k02_codrec
                                            and o70_anousu = k02_anousu
                 WHERE TABORC.K02_CODIGO = GRAVA_CORNUMP.K00_RECEIT
                   AND TABORC.K02_ANOUSU = extract( year from DTEMITE)::integer;

                if VINSTIT is null then
                  rtp_autenticacao.erro     := true;
                  rtp_autenticacao.mensagem := '8 NAO ENCONTRADO REGISTRO DA RECEITA '|| GRAVA_CORNUMP.K00_RECEIT ||' NA ORCRECEITA PARA O ANO '||extract( year from DTEMITE)::integer;
                  return rtp_autenticacao;

                end if;

              ELSE

                SELECT C61_INSTIT
                  INTO VINSTIT
                  FROM TABPLAN
                       INNER JOIN CONPLANOREDUZ ON C61_REDUZ  = k02_REDUZ
                                               AND C61_ANOUSU = K02_ANOUSU
                                               AND C61_INSTIT = INSTIT
                 WHERE TABPLAN.K02_CODIGO = GRAVA_CORNUMP.K00_RECEIT
                   AND K02_ANOUSU = extract( year from DTEMITE)::integer;

                if VINSTIT is null then
                  rtp_autenticacao.erro     := true;
                  rtp_autenticacao.mensagem := '7 NAO ENCONTRADO REGISTRO DA RECEITA '|| GRAVA_CORNUMP.K00_RECEIT ||' NA TABPLAN PARA O ANO '||extract( year from DTEMITE)::integer;
                  return rtp_autenticacao;
                end if;

                if lRaise is true then
                  perform fc_debug('<autentica>  GRAVA_CORNUMP.K00_RECEIT: '||GRAVA_CORNUMP.K00_RECEIT||', INSTIT: '||INSTIT||', DTEMITE: '||DTEMITE||', VINSTIT:'||VINSTIT,lRaise,false,false);
                end if;

              END IF ;

              IF VINSTIT IS NULL  or VINSTIT != INSTIT THEN

                if lRaise is true then
                  perform fc_debug('<autentica>  8 - Fim do processamento da autenticação... ',lRaise, false, true);
                end if;
                rtp_autenticacao.erro     := true;
                rtp_autenticacao.mensagem := '6 RECEITA '|| GRAVA_CORNUMP.K00_RECEIT ||' DE INSTITUICAO DIFERENTE';
                return rtp_autenticacao;

              END IF;

              if lRaise is true then
                perform fc_debug('<autentica>  numpre: '||RECORD_NUMPRE.K00_NUMPRE||' - numpar: '||NUM_PAR||' - receita: '||GRAVA_CORNUMP.K00_RECEIT,lRaise,false,false);
              end if;
              INSERT INTO CORNUMP (k12_id,k12_data,k12_autent,k12_numpre,k12_numpar,k12_numtot,k12_numdig,k12_receit,k12_valor,k12_numnov)
                           values ( IDTERM, DTEMITE, CODAUT, RECORD_NUMPRE.K00_NUMPRE,NUM_PAR, NUMTOT,NUMDIG,GRAVA_CORNUMP.K00_RECEIT,GRAVA_CORNUMP.VALOR, NUMPRE );

            END LOOP;
          END IF;
        END LOOP;
      ELSE
        IF TIPOAUTENT = 3 THEN
-- ERRO QUANDO O TERMINAL NAO ESTA CADASTRADO

          if lRaise is true then
            perform fc_debug('<autentica>  9 - Fim do processamento da autenticação...',lRaise, false, true);
          end if;
          rtp_autenticacao.erro     := true;
          rtp_autenticacao.mensagem := '1 BAIXADO SEM AUTENTICACAO';
          return rtp_autenticacao;

        ELSE

          if lRaise is true then
            perform fc_debug('<autentica>  10 - Fim do processamento da autenticação...',lRaise, false, true);
          end if;
          rtp_autenticacao.erro     := true;
          rtp_autenticacao.mensagem := '2 TERMINAL NAO CADASTRADO';
          return rtp_autenticacao;

        END IF;
      END IF;
-- AUTENTICACAO CORRETA
perform fc_debug('<autentica>  data -- '||IDTERM||' idterm -- '||DTEMITE||' autent -- '||CODAUT,lRaise,false,false);

      select VAL_CORRENTE - VAL_CORNUMP, VAL_CORRENTE, VAL_CORNUMP
        into TOTALZAO, TOTALCORRENTE, TOTALCORNUMP
        from (SELECT (SELECT ROUND(SUM(K12_VALOR),2)
                FROM CORRENTE
                       WHERE K12_DATA   = DTEMITE
                         AND K12_ID     = IDTERM
                         AND K12_AUTENT = CODAUT ) AS VAL_CORRENTE,
                     (SELECT ROUND(SUM(K12_VALOR),2)
              FROM CORNUMP
             WHERE K12_DATA   = DTEMITE
                         AND K12_ID     = IDTERM
                         AND K12_AUTENT = CODAUT) AS VAL_CORNUMP
             ) AS X;

      if lRaise is true then
        perform fc_debug('<autentica>  TOTALZAO: '||TOTALZAO||' - VAL_CORRENTE: '||TOTALCORRENTE||' - VAL_CORNUMP: '||TOTALCORNUMP,lRaise,false,false);
      end if;

      IF TOTALZAO <> 0 THEN

        if lRaise is true then
          perform fc_debug('<autentica>  11 - Fim do processamento da autenticação...',lRaise, false, true);
        end if;
        rtp_autenticacao.erro     := true;
        rtp_autenticacao.mensagem := '5 - INCONSISTENCIA NA AUTENTICACAO! CONTATE SUPORTE!';
        return rtp_autenticacao;

      END IF;

      if fc_getsession('DB_utiliza_taxa_expediente_autenticacao') is not null then
         AUTENTICACAO:= TO_CHAR(CODAUT,'999999') ||
                        DTEMITE ||
                        IDENT1 ||
                        IDENT2 ||
                        IDENT3 ||
                        TO_CHAR(NUMPRE,'99999999') ||
                        TO_CHAR(NUM_PAR,'999') ||
                        TO_CHAR(ABS(VLRCORRECAO+VLRJUROS+VLRMULTA-VLRDESCONTO + VTAXAACRESCIMO),'999999999.99')||'+';
      else
         AUTENTICACAO:= TO_CHAR(CODAUT,'999999') ||
                        DTEMITE ||
                        IDENT1 ||
                        IDENT2 ||
                        IDENT3 ||
                        TO_CHAR(NUMPRE,'99999999') ||
                        TO_CHAR(NUM_PAR,'999') ||
                        TO_CHAR(ABS(VLRCORRECAO+VLRJUROS+VLRMULTA-VLRDESCONTO),'999999999.99')||'+';
      end if;

      INSERT INTO CORAUTENT (K12_ID,
                             K12_DATA,
                             K12_AUTENT,
                             K12_CODAUTENT)
                     VALUES (IDTERM,
                             DTEMITE,
                             CODAUT,
                             AUTENTICACAO);

      if lRaise is true then
        perform fc_debug('<autentica>  12 - Fim do processamento da autenticação...',lRaise, false, true);
      end if;

      rtp_autenticacao.id           := IDTERM;
      rtp_autenticacao.data         := DTEMITE;
      rtp_autenticacao.codautent    := CODAUT;
      rtp_autenticacao.autenticacao := '1' || AUTENTICACAO;
      rtp_autenticacao.erro         := false;
      rtp_autenticacao.mensagem     := '1' || AUTENTICACAO;
      return rtp_autenticacao;

    ELSE
-- NUMPRE NAO PROCESSADO
--raise notice 'numpar: % - NUMPRE % ',NUMPAR,NUMPRE;
      IF NUMPAR = 0 THEN
        SELECT SUM(K00_VALOR)
          INTO VLRCORRECAO
          FROM ARREPAGA
         WHERE K00_NUMPRE = NUMPRE ;
      ELSE
        SELECT SUM(K00_VALOR)
          INTO VLRCORRECAO
          FROM ARREPAGA
         WHERE K00_NUMPRE = NUMPRE
           AND K00_NUMPAR = NUMPAR;
      END IF;
      IF VLRCORRECAO IS NULL THEN

        if lRaise is true then
          perform fc_debug('<autentica>  13 - Fim do processamento da autenticação...',lRaise, false, true);
        end if;
        rtp_autenticacao.erro     := true;
        rtp_autenticacao.mensagem := '4 VALOR ZERADO';
        return rtp_autenticacao;

      ELSE

        if lRaise is true then
          perform fc_debug('<autentica>  14 - Fim do processamento da autenticação...',lRaise, false, true);
        end if;
        rtp_autenticacao.erro     := true;
        rtp_autenticacao.mensagem := '3 VALOR PAGO';
        return rtp_autenticacao;

      END IF;
    END IF;

  END;
$$ language 'plpgsql';

SQL;
        $this->execute($sSql);

    }

}
