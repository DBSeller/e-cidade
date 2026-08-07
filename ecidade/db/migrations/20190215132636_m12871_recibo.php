<?php

use Classes\PostgresMigration;

class M12871Recibo extends PostgresMigration
{
    public function up()
    {
        $sql  = <<<SQL
drop function fc_recibo(integer,date,date,integer);
drop   type tp_recibo;

create type tp_recibo as ( rvMensagem varchar(100),
                           rlErro     boolean );

create or replace function fc_recibo(integer,date,date,integer) returns tp_recibo  as
$$
DECLARE
  NUMPRE                ALIAS FOR $1;
  DTEMITE               ALIAS FOR $2;
  DTVENC                ALIAS FOR $3;
  ANOUSU                ALIAS FOR $4;

  iFormaCorrecao        integer default 2;
  iInstit               integer;
  iExerc                integer;

  iRegraIptu            integer;
  iRegraIss             integer;

  USASISAGUA            BOOLEAN;

  UNICA                 BOOLEAN := FALSE;
  NUMERO_ERRO           char(200);
  NUMCGM                INTEGER;
  RECORD_NUMPRE         RECORD;
  RECORD_ALIAS          RECORD;
  RECORD_GRAVA          RECORD;
  RECORD_NUMPREF        RECORD;
  RECORD_UNICA          RECORD;

  VALOR_RECEITA         FLOAT8;
  VALOR_RECEITA_ORI     FLOAT8;
  DESC_VALOR_RECEITA    FLOAT8 DEFAULT 0;

  VALOR_RECEITAORI      FLOAT8;

  CORRECAO              FLOAT8 DEFAULT 0;
  DESC_CORRECAO         FLOAT8 DEFAULT 0;
  CORRECAOORI           FLOAT8;
  JURO                  FLOAT8 DEFAULT 0;
  MULTA                 FLOAT8 DEFAULT 0;
  vlrjuroparc           FLOAT8 DEFAULT 0;
  vlrmultapar           FLOAT8 DEFAULT 0;
  DESCONTO              FLOAT8;
  nDescontoCorrigido    FLOAT8 default 0;

  numpreValorTotal      numeric default 0;

  RECEITA               INTEGER;
  K03_RECMUL            INTEGER;
  K03_RECJUR            INTEGER;
  V_K00_HIST            INTEGER;
  QUAL_OPER             INTEGER;

  DTOPER                DATE;
  DATAVENC              DATE;
  SQLRECIBO             VARCHAR(400);

  VLRJUROS              FLOAT8 default 0;
  VLRMULTA              FLOAT8 default 0;
  VLRDESCONTO           FLOAT8 default 0;

  V_CADTIPOPARC         INTEGER;
  V_CADTIPOPARC_FORMA   INTEGER;
  NUMPAR                INTEGER;
  NUMTOT                INTEGER;
  NUMDIG                INTEGER;
  ARRETIPO              INTEGER;
  PROCESSA              BOOLEAN DEFAULT FALSE;
  ISSQNVARIAVEL         BOOLEAN;
  CODBCO                INTEGER;
  CODAGE                CHAR(5);
  NUMBCO                VARCHAR(15);
  RECEITA_JUR           INTEGER;
  RECEITA_MUL           INTEGER;
  iTipoVlr              INTEGER;

  PERCDESCJUR           FLOAT8 DEFAULT 0;
  PERCDESCMUL           FLOAT8 DEFAULT 0;
  PERCDESCVLR           FLOAT8 DEFAULT 0;

  nPercArreDesconto     FLOAT8 DEFAULT 0;

  v_composicao          record;

  nComposCorrecao       numeric(15,2) default 0;
  nComposJuros          numeric(15,2) default 0;
  nComposMulta          numeric(15,2) default 0;

  nCorreComposJuros     numeric(15,2) default 0;
  nCorreComposMulta     numeric(15,2) default 0;

  rtp_recibo            tp_recibo%ROWTYPE;

  TOTPERC               FLOAT8;
  TEM_DESCONTO          INTEGER DEFAULT 0;

  lRaise                boolean default false;
  lParcelamento         boolean default false;

  record_alias_numcgm integer;
  record_alias_K00_DTVENC date;

  RECORD_ALIAS_K00_valor numeric;
  RECORD_ALIAS_K00_hist integer;

BEGIN

  select cast( fc_getsession('DB_instit') as integer )
  into iInstit;

  select cast( fc_getsession('DB_anousu') as integer )
  into iExerc;

  select db21_regracgmiptu::integer from db_config where codigo = iInstit
  into iRegraIptu;

  select db21_regracgmiss::integer from db_config where codigo = iInstit
  into iRegraIss;

  select db21_usasisagua
  into USASISAGUA
  from db_config
  where codigo = iInstit;

  select k03_separajurmulparc
  into iFormaCorrecao
  from numpref
  where k03_instit = iInstit
        and k03_anousu = iExerc;

  FOR RECORD_NUMPREF IN SELECT *
                        FROM NUMPREF
                        WHERE K03_ANOUSU = ANOUSU
  LOOP
    RECEITA_JUR := RECORD_NUMPREF.K03_RECJUR;
    RECEITA_MUL := RECORD_NUMPREF.K03_RECMUL;
  END LOOP;

  perform k00_numpre
  from recibo
  where k00_numnov = numpre LIMIT 1;
  if found then

    rtp_recibo.rvMensagem    := '4 - Erro ao gerar recibo. Contate suporte!';
    rtp_recibo.rlErro        := true;

    return  rtp_recibo;

  end if;

  perform 1
  from db_reciboweb
  where k99_numpre_n = numpre limit 1;
  if not found then

    rtp_recibo.rvMensagem    := '2 - Erro ao gerar recibo. Contate suporte!';
    rtp_recibo.rlErro        := true;

    return  rtp_recibo;

  end if;

  -- insert into tempo (descricao, tempo) values (' inicio', clock_timestamp());
  
  -- raise notice ' AAAAAAAAAAAAA ';

  select rinumcgm 
    into record_alias_numcgm
    from fc_socio_promitente((SELECT K99_NUMPRE
                                FROM DB_RECIBOWEB
                               WHERE K99_NUMPRE_N = NUMPRE limit 1), true, iRegraIptu, iRegraIss) limit 1;

  drop table if exists arrecad_temp_teste;
  create table arrecad_temp_teste as 
  select *
    from arrecad
   where k00_numpre in (SELECT distinct K99_NUMPRE
                         FROM DB_RECIBOWEB
                        WHERE K99_NUMPRE_N = NUMPRE);

  FOR RECORD_NUMPRE IN SELECT *
                       FROM DB_RECIBOWEB
                       WHERE K99_NUMPRE_N = NUMPRE
  LOOP

    -- insert into tempo (descricao, tempo) values ((select k00_matric from arrematric where k00_numpre = RECORD_NUMPRE.K99_NUMPRE)::text || ' 1 - for record_numpre', clock_timestamp());

    CODBCO = RECORD_NUMPRE.K99_CODBCO;
    CODAGE = RECORD_NUMPRE.K99_CODAGE;
    --    NUMBCO = RECORD_NUMPRE.K99_NUMBCO;

    select fc_numbcoconvenio(NUMBCO::integer) into NUMBCO;

    TEM_DESCONTO = RECORD_NUMPRE.K99_DESCONTO;

    -- raise notice ' AAAAAAAAAAAAA ';

    FOR RECORD_UNICA IN 
      SELECT DISTINCT
             K00_NUMPRE,
             K00_NUMPAR
        FROM ARRECAD
       WHERE K00_NUMPRE = RECORD_NUMPRE.K99_NUMPRE
         AND CASE WHEN RECORD_NUMPRE.K99_NUMPAR = 0 THEN
               TRUE
             ELSE
               K00_NUMPAR = RECORD_NUMPRE.K99_NUMPAR
             END
    LOOP

      -- insert into tempo (descricao, tempo) values ((select k00_matric from arrematric where k00_numpre = RECORD_NUMPRE.K99_NUMPRE)::text || ' 2 - for record_unica', clock_timestamp());

      PROCESSA := TRUE;

      IF RECORD_NUMPRE.K99_NUMPAR = 0 THEN
        UNICA := TRUE;
      ELSE
        
        IF RECORD_NUMPRE.K99_NUMPAR != RECORD_UNICA.K00_NUMPAR THEN
          PROCESSA := FALSE;
        END IF;

      END IF;

      NUMPAR := RECORD_UNICA.K00_NUMPAR;

      IF PROCESSA = TRUE THEN

        -- raise notice ' PROCESSA = TRUE ';

        record_alias_K00_DTVENC := (select fc_calculavenci(
          RECORD_NUMPRE.K99_NUMPRE,
          NUMPAR,
          (select k00_dtvenc
            FROM ARRECAD
           WHERE K00_NUMPRE = RECORD_NUMPRE.K99_NUMPRE
             AND K00_NUMPAR = NUMPAR limit 1),
          DTEMITE)
        );

        FOR RECORD_ALIAS IN
          
          -- GroupAggregate  (cost=8.71..17.72 rows=1 width=32)
          -- SELECT K00_RECEIT,
          --        K00_DTOPER,
          --        -- (select rinumcgm from fc_socio_promitente(K00_NUMPRE, true, iRegraIptu, iRegraIss) limit 1)as K00_NUMCGM,
          --        record_alias_numcgm
          --         K00_NUMCGM,
          --        fc_calculavenci(k00_numpre,k00_numpar,K00_DTVENC,DTEMITE) AS K00_DTVENC,
          --        K00_NUMPRE,
          --        K00_NUMPAR,
          --        (select K00_hist
          --           from arrecad as a
          --          where a.k00_numpre = arrecad.k00_numpre
          --            and a.k00_numpar = arrecad.k00_numpar
          --            and a.k00_receit = arrecad.k00_receit
          --            and a.k00_tipo = arrecad.k00_tipo order by 1 limit 1
          --            ) as k00_hist,

          --        (select sum(k00_valor)
          --           from arrecad as a
          --          where a.k00_numpre = arrecad.k00_numpre
          --            and a.k00_numpar = arrecad.k00_numpar
          --            and a.k00_receit = arrecad.k00_receit
          --            and a.k00_tipo = arrecad.k00_tipo 
          --            ) as k00_valor,
          --        K00_TIPO
          --   FROM ARRECAD
          --  WHERE K00_NUMPRE = RECORD_NUMPRE.K99_NUMPRE
          --    AND K00_NUMPAR = NUMPAR
          --  group by K00_RECEIT,
          --           K00_DTOPER,
          --           K00_NUMCGM,
          --           fc_calculavenci(k00_numpre,k00_numpar,K00_DTVENC,DTEMITE),
          --           K00_NUMPRE,
          --           K00_NUMPAR,
          --           K00_TIPO
          --  ORDER BY K00_NUMPRE,
          --           K00_NUMPAR,
          --           K00_RECEIT

          -- Index Scan using arrecad_numpre_numpar_receit_in on arrecad  (cost=0.43..8.45 rows=1 width=20)
          select k00_receit,
                 k00_dtoper,
                 record_alias_numcgm as K00_NUMCGM,
                 record_alias_K00_DTVENC AS K00_DTVENC,
                 k00_numpre,
                 k00_numpar,
                 k00_tipo
            from arrecad
           where k00_numpre = RECORD_NUMPRE.K99_NUMPRE
             and k00_numpar = NUMPAR
           ORDER BY K00_NUMPRE,
                    K00_NUMPAR,
                    K00_RECEIT

        LOOP

          select sum(k00_valor), 
                 min(K00_hist)
            into RECORD_ALIAS_K00_valor,
                 RECORD_ALIAS_K00_hist
            from arrecad_temp_teste as a
           where a.k00_numpre = RECORD_ALIAS.k00_numpre
             and a.k00_numpar = RECORD_ALIAS.k00_numpar
             and a.k00_receit = RECORD_ALIAS.K00_RECEIT;

          -- raise notice ' ----> % ', RECORD_ALIAS;

          -- insert into tempo (descricao, tempo) values ((select k00_matric from arrematric where k00_numpre = RECORD_NUMPRE.K99_NUMPRE)::text || ' 3 - record alias', clock_timestamp());

          PROCESSA := TRUE;
          RECEITA  := RECORD_ALIAS.K00_RECEIT;
          ARRETIPO := RECORD_ALIAS.K00_TIPO;
          DTOPER   := RECORD_ALIAS.K00_DTOPER;
          NUMCGM   := RECORD_ALIAS.K00_NUMCGM;
          DATAVENC := RECORD_ALIAS.K00_DTVENC;
          VALOR_RECEITA := RECORD_ALIAS_K00_valor;

          IF VALOR_RECEITA = 0 THEN
            SELECT Q05_VLRINF
            INTO VALOR_RECEITA
            FROM ISSVAR
            WHERE Q05_NUMPRE = RECORD_ALIAS.K00_NUMPRE
                  AND Q05_NUMPAR = RECORD_ALIAS.K00_NUMPAR;
            IF VALOR_RECEITA IS NULL THEN
              VALOR_RECEITA := 0;
            ELSE
              ISSQNVARIAVEL := TRUE;
            END IF;
          END IF;

          QUAL_OPER := 0;
          -- T24879: Se valor da receita nao for 0 (zero) ou
          -- recibo for proveniente de uma emissao geral de iss variavel
          -- continua geracao da recibopaga
          IF ( VALOR_RECEITA <> 0 OR RECORD_NUMPRE.K99_TIPO = 6 ) THEN

            FOR RECORD_GRAVA IN SELECT *
                                FROM ARRECAD
                                WHERE K00_NUMPRE = RECORD_NUMPRE.K99_NUMPRE
                                      AND K00_NUMPAR = NUMPAR
                                      AND K00_RECEIT = RECEITA
            LOOP

              -- insert into tempo (descricao, tempo) values ((select k00_matric from arrematric where k00_numpre = RECORD_NUMPRE.K99_NUMPRE)::text || ' 4 - record grava', clock_timestamp());

              IF QUAL_OPER = 0 THEN
                V_K00_HIST := RECORD_GRAVA.K00_HIST;
                NUMTOT := RECORD_GRAVA.K00_NUMTOT;
                NUMDIG  := RECORD_GRAVA.K00_NUMDIG;
                QUAL_OPER := 1;
              END IF;

            END LOOP;

            -- raise notice 'RECORD_GRAVA -> %', RECORD_GRAVA;
            -- raise notice 'iFormaCorrecao -> %', iFormaCorrecao;

            -- CALCULA CORRECAO
            if VALOR_RECEITA <> 0 then

              if iFormaCorrecao = 1 then

                VALOR_RECEITA_ORI = VALOR_RECEITA;

                select coalesce(rnCorreComposJuros,0),
                  coalesce(rnCorreComposMulta,0),
                  coalesce(rnComposCorrecao,0),
                  coalesce(rnComposJuros,0),
                  coalesce(rnComposMulta,0)
                into nCorreComposJuros,
                  nCorreComposMulta,
                  nComposCorrecao,
                  nComposJuros,
                  nComposMulta
                from fc_retornacomposicao(record_alias.k00_numpre, record_alias.k00_numpar, record_alias.k00_receit, RECORD_ALIAS_K00_hist, dtoper, dtvenc, anousu, datavenc);

                -- raise notice 'asdasdas';

                VALOR_RECEITA = VALOR_RECEITA + nComposCorrecao;
 
                CORRECAO := ROUND( FC_CORRE(RECEITA,DTOPER,VALOR_RECEITA,DTVENC,ANOUSU,DATAVENC) , 2 );

                CORRECAO := ROUND( CORRECAO - VALOR_RECEITA + nComposCorrecao, 2 );

                CORRECAO := CORRECAO + nCorreComposJuros + nCorreComposMulta;

                VALOR_RECEITA = VALOR_RECEITA_ORI;

              else

                CORRECAO := ROUND(FC_CORRE(RECEITA, DTOPER, VALOR_RECEITA, DTVENC, ANOUSU, DATAVENC) - round(VALOR_RECEITA, 2), 2);

              end if;

            else
              CORRECAO := 0;
            end if;

            if TEM_DESCONTO > 0 then

              select sum(fc_calcula_total_arrecad(x.k99_numpre, DTVENC))
                into numpreValorTotal
                from (select distinct db_reciboweb.k99_numpre 
                        from db_reciboweb 
                       where db_reciboweb.k99_numpre_n = NUMPRE) as x;

              select tipoparc.descjur,
                     tipoparc.descmul,
                     tipoparc.descvlr,
                     cadtipoparc.k40_codigo,
                     cadtipoparc.k40_forma,
                     tipoparc.tipovlr
                into percdescjur,
                     percdescmul,
                     percdescvlr,
                     v_cadtipoparc,
                     v_cadtipoparc_forma,
                     iTipoVlr
                from cadtipoparc
                     inner join tipoparc on tipoparc.cadtipoparc = cadtipoparc.k40_codigo
               where DTEMITE between tipoparc.dtini and tipoparc.dtfim
                 and tipoparc.maxparc = 1
                 and fc_verifica_desconto_faixa_valor(numpreValorTotal::numeric, tipoparc.vlrmin::numeric, tipoparc.vlrmax::numeric)
                 and cadtipoparc.k40_codigo = TEM_DESCONTO;

            end if;

            CORRECAOORI := CORRECAO;
            VALOR_RECEITAORI := VALOR_RECEITA;

            --  Trabalhar neste if para utilizar a mesma logica da recibodesconto
            --   alterar o programa de emissao de recibo para selecionar
            --   a regra se o contribuinte for ou nao loteador

            -- Verificar se a receita possui 'valores adicionais' (Juridico > Procedimentos > Processo do Foro > Valores Adicionais) lancados a um processo.
            -- Caso possua, deve-se desconsiderar descontos em cima dessa receita.
            -- Obs.: Esse caso se aplica para debitos de inicial.

            -- Nested Loop  (cost=0.86..16.99 rows=1 width=4)
            -- perform j150_receita
            --    from processoforomulta
            --         inner join processoforoinicial on j150_processoforo = v71_processoforo
            --         inner join inicialnumpre on v71_inicial = v59_inicial
            --   where j150_receita = RECEITA
            --     and v59_numpre   = RECORD_ALIAS.K00_NUMPRE;

            -- Nested Loop  (cost=0.86..16.99 rows=1 width=0)
            perform 1
               from inicialnumpre
                    inner join processoforoinicial on processoforoinicial.v71_inicial = inicialnumpre.v59_inicial
                    inner join processoforomulta on processoforomulta.j150_processoforo = processoforoinicial.v71_processoforo
              where inicialnumpre.v59_numpre = RECORD_ALIAS.K00_NUMPRE
                and processoforomulta.j150_receita = RECEITA;

            -- raise notice ' ----- RECEITA: %, RECORD_ALIAS.K00_NUMPRE: % ', RECEITA, RECORD_ALIAS.K00_NUMPRE;

            -- insert into tempo (descricao, tempo) values ((select k00_matric from arrematric where k00_numpre = RECORD_NUMPRE.K99_NUMPRE)::text || ' 5 - depois multa', clock_timestamp());

            if found then
              percdescvlr := 0;
              percdescmul := 0;
              percdescjur := 0;
            end if;

            if percdescvlr is not null and percdescvlr > 0 then

              -- insert into tempo (descricao, tempo) values ((select k00_matric from arrematric where k00_numpre = RECORD_NUMPRE.K99_NUMPRE)::text || ' ??? - if percdescvlr is not null and percdescvlr > 0 then', clock_timestamp());

              if iTipoVlr = 1 then

                DESC_CORRECAO := ROUND(CORRECAO * percdescvlr / 100,2);
                
                if DESC_CORRECAO > 0 then
                  --

                  INSERT INTO RECIBOPAGA (k00_numcgm,
                                          k00_dtoper,
                                          k00_receit,
                                          k00_hist,
                                          k00_valor,
                                          k00_dtvenc,
                                          k00_numpre,
                                          k00_numpar,
                                          k00_numtot,
                                          k00_numdig,
                                          k00_conta,
                                          k00_dtpaga,
                                          k00_numnov)
                  VALUES (NUMCGM,
                    DTEMITE,
                    RECEITA,
                    918,
                    (DESC_CORRECAO*-1),
                    DATAVENC,
                    RECORD_NUMPRE.K99_NUMPRE,
                    NUMPAR,NUMTOT,
                    NUMDIG,
                    0,
                          DTVENC,
                          NUMPRE);

                    -- insert into tempo (descricao, tempo) values ((select k00_matric from arrematric where k00_numpre = RECORD_NUMPRE.K99_NUMPRE)::text || ' ??? - if DESC_CORRECAO > 0 then', clock_timestamp());

                end if;
              elsif iTipoVlr = 2 then

                nDescontoCorrigido := ROUND((VALOR_RECEITA + CORRECAO) * percdescvlr / 100,2);
                

                -- insert into tempo (descricao, tempo) values ((select k00_matric from arrematric where k00_numpre = RECORD_NUMPRE.K99_NUMPRE)::text || ' ??? - nDescontoCorrigido := ROUND((VALOR_RECEITA + CORRECAO) * percdescvlr / 100,2);', clock_timestamp());

                if nDescontoCorrigido > 0 then
                  --
                  INSERT INTO RECIBOPAGA (k00_numcgm,
                                          k00_dtoper,
                                          k00_receit,
                                          k00_hist,
                                          k00_valor,
                                          k00_dtvenc,
                                          k00_numpre,
                                          k00_numpar,
                                          k00_numtot,
                                          k00_numdig,
                                          k00_conta,
                                          k00_dtpaga,
                                          k00_numnov )
                  VALUES (NUMCGM,
                    DTEMITE,
                    RECEITA,
                    918,
                    (nDescontoCorrigido*-1),
                    DATAVENC,
                    RECORD_NUMPRE.K99_NUMPRE,
                    NUMPAR,
                    NUMTOT,
                    NUMDIG,
                    0,
                          DTVENC,
                          NUMPRE);
                end if;
              end if;

              -- Se a forma de aplicacao da regra for pra loteamentos (= 3)
              -- entao aplica desconto no valor da receita (historico)
              if v_cadtipoparc_forma = 3 then
                DESC_VALOR_RECEITA := ROUND(VALOR_RECEITA * percdescvlr / 100,2);

                if DESC_VALOR_RECEITA > 0 then


                  -- insert into tempo (descricao, tempo) values ((select k00_matric from arrematric where k00_numpre = RECORD_NUMPRE.K99_NUMPRE)::text || ' ??? - if DESC_VALOR_RECEITA > 0 then ----- ', clock_timestamp());

                  --
                  INSERT INTO RECIBOPAGA (k00_numcgm,
                                          k00_dtoper,
                                          k00_receit,
                                          k00_hist,
                                          k00_valor,
                                          k00_dtvenc,
                                          k00_numpre,
                                          k00_numpar,
                                          k00_numtot,
                                          k00_numdig,
                                          k00_conta,
                                          k00_dtpaga,
                                          k00_numnov)
                  VALUES (NUMCGM,
                    DTEMITE,
                    RECEITA,
                    918,
                    (DESC_VALOR_RECEITA*-1),
                    DATAVENC,
                    RECORD_NUMPRE.K99_NUMPRE,
                    NUMPAR,
                    NUMTOT,
                    NUMDIG,
                    0,
                          DTVENC,
                          NUMPRE);
                end if;
              end if;

            end if;

            -- T24879: Se valor diferente de zero ou tipo recibo for da emissao geral do iss
            -- gera recibopaga normalmente
            IF (VALOR_RECEITA + CORRECAO) <> 0 OR RECORD_NUMPRE.K99_TIPO = 6 THEN

              -- insert into tempo (descricao, tempo) values ((select k00_matric from arrematric where k00_numpre = RECORD_NUMPRE.K99_NUMPRE)::text || ' ??? - IF (VALOR_RECEITA + CORRECAO) <> 0 OR RECORD_NUMPRE.K99_TIPO = 6 THEN ----- ', clock_timestamp());

              INSERT INTO RECIBOPAGA ( k00_numcgm,
                                       k00_dtoper,
                                       k00_receit,
                                       k00_hist  ,
                                       k00_valor ,
                                       k00_dtvenc,
                                       k00_numpre,
                                       k00_numpar,
                                       k00_numtot,
                                       k00_numdig,
                                       k00_conta ,
                                       k00_dtpaga,
                                       k00_numnov )
              VALUES ( NUMCGM,
                DTEMITE,
                RECEITA,
                V_K00_HIST + 100,
                ROUND(VALOR_RECEITA+CORRECAO,2),
                DATAVENC,
                RECORD_NUMPRE.K99_NUMPRE,
                NUMPAR,
                NUMTOT,
                NUMDIG,
                0,
                       DTVENC,
                       NUMPRE );

              -- CALCULA DESCONTO DA ARREDESCONTO
              perform v07_numpre
              from termo
              where v07_numpre = RECORD_NUMPRE.K99_NUMPRE;
              if found then
                lParcelamento := true;
              end if;

              if lParcelamento then

                -- insert into tempo (descricao, tempo) values ((select k00_matric from arrematric where k00_numpre = RECORD_NUMPRE.K99_NUMPRE)::text || ' ??? - antes fc_recibodesconto ----- ', clock_timestamp());

                -- Verifica desconto
                nPercArreDesconto := fc_recibodesconto(RECORD_NUMPRE.K99_NUMPRE,
                                                       NUMPAR,
                                                       NUMTOT,
                                                       RECEITA,
                                                       ARRETIPO,
                                                       DTEMITE,
                                                       fc_proximo_dia_util(DATAVENC));


                -- insert into tempo (descricao, tempo) values ((select k00_matric from arrematric where k00_numpre = RECORD_NUMPRE.K99_NUMPRE)::text || ' ??? - depois fc_recibodesconto ----- ', clock_timestamp());

                if nPercArreDesconto > 0 then

                  INSERT INTO RECIBOPAGA ( k00_numcgm,
                                           k00_dtoper,
                                           k00_receit,
                                           k00_hist  ,
                                           k00_valor ,
                                           k00_dtvenc,
                                           k00_numpre,
                                           k00_numpar,
                                           k00_numtot,
                                           k00_numdig,
                                           k00_conta ,
                                           k00_dtpaga,
                                           k00_numnov )
                  VALUES ( NUMCGM,
                    DTEMITE,
                    RECEITA,
                    918,
                    ROUND(((ROUND(VALOR_RECEITA+CORRECAO,2) * nPercArreDesconto)/100),2) * -1,
                    DATAVENC,
                    RECORD_NUMPRE.K99_NUMPRE,
                    NUMPAR,
                    NUMTOT,
                    NUMDIG,
                    0,
                           DTVENC,
                           NUMPRE );

                  -- insert into tempo (descricao, tempo) values ((select k00_matric from arrematric where k00_numpre = RECORD_NUMPRE.K99_NUMPRE)::text || ' ??? - depois insert de fc_recibodesconto ----- ', clock_timestamp());

                end if;
              end if;

            END IF;

            -- insert into tempo (descricao, tempo) values ((select k00_matric from arrematric where k00_numpre = RECORD_NUMPRE.K99_NUMPRE)::text || ' ??? - IF (VALOR_RECEITAORI + CORRECAOORI) <> 0 THEN ', clock_timestamp());

            IF (VALOR_RECEITAORI + CORRECAOORI) <> 0 THEN

              -- CALCULA JUROS
              
              if iFormaCorrecao = 1 then
                JURO := ROUND((VALOR_RECEITAORI + CORRECAO) * FC_JUROS(RECEITA, DATAVENC, DTEMITE, DTOPER, FALSE, ANOUSU), 2);
              else
                JURO := ROUND((CORRECAOORI + VALOR_RECEITAORI) * FC_JUROS(RECEITA, DATAVENC, DTEMITE, DTOPER, FALSE, ANOUSU), 2);
              end if;

              
              JURO = JURO + nComposJuros;

              -- CALCULA MULTA
              if iFormaCorrecao = 1 then
                MULTA := round((VALOR_RECEITAORI + CORRECAO)::numeric(15, 2) * FC_MULTA(RECEITA, DATAVENC, DTEMITE, DTOPER, ANOUSU)::numeric(15, 5), 2);
              else
                MULTA := ROUND((CORRECAOORI + VALOR_RECEITAORI)::numeric(15, 2) * FC_MULTA(RECEITA, DATAVENC, DTEMITE, DTOPER, ANOUSU)::numeric(15, 5), 2);
              end if;

              MULTA = MULTA + nComposMulta;

              SELECT K02_RECMUL,
                K02_RECJUR
              INTO K03_RECMUL,
                K03_RECJUR
              FROM TABREC
              WHERE K02_CODIGO = RECEITA;

              IF K03_RECMUL IS NULL THEN
                K03_RECMUL := RECEITA_MUL;
              END IF;

              IF K03_RECJUR IS NULL THEN
                K03_RECJUR := RECEITA_JUR;
              END IF;
              -- INCLUIDO VARIAVEL DESCONTO NO DB_RECIBOWEB

              if percdescjur is not null and percdescmul is not null and (nPercArreDesconto is null or nPercArreDesconto <= 0) then
                vlrjuroparc := (ROUND(cast(JURO as FLOAT8) * percdescjur / 100,2));

                if vlrjuroparc > 0 then

                  INSERT INTO RECIBOPAGA ( k00_numcgm,
                                           k00_dtoper,
                                           k00_receit,
                                           k00_hist  ,
                                           k00_valor ,
                                           k00_dtvenc,
                                           k00_numpre,
                                           k00_numpar,
                                           k00_numtot,
                                           k00_numdig,
                                           k00_conta ,
                                           k00_dtpaga,
                                           k00_numnov)
                  VALUES ( NUMCGM,
                    DTEMITE,
                    K03_RECJUR,
                    918,
                    (vlrjuroparc * -1),
                    DATAVENC,
                    RECORD_NUMPRE.K99_NUMPRE,
                    NUMPAR,
                    NUMTOT,
                    NUMDIG,
                    0,
                           DTVENC,
                           NUMPRE);
                end if;
                vlrmultapar := (ROUND(cast(MULTA as FLOAT8) * percdescmul / 100,2));
                if vlrmultapar > 0  then
                  
                  INSERT INTO RECIBOPAGA ( k00_numcgm,
                                           k00_dtoper,
                                           k00_receit,
                                           k00_hist  ,
                                           k00_valor ,
                                           k00_dtvenc,
                                           k00_numpre,
                                           k00_numpar,
                                           k00_numtot,
                                           k00_numdig,
                                           k00_conta ,
                                           k00_dtpaga,
                                           k00_numnov )
                  VALUES ( NUMCGM,
                    DTEMITE,
                    K03_RECMUL,
                    918,
                    (vlrmultapar * -1),
                    DATAVENC,
                    RECORD_NUMPRE.K99_NUMPRE,
                    NUMPAR,
                    NUMTOT,
                    NUMDIG,
                    0,
                           DTVENC,
                           NUMPRE );
                end if;
              end if;

              IF K03_RECJUR = 0 OR K03_RECMUL = 0 OR K03_RECJUR = K03_RECMUL THEN

                IF JURO+MULTA <> 0 THEN

                  VLRJUROS := VLRJUROS + JURO;
                  VLRMULTA := VLRMULTA + MULTA;
                  
                  INSERT INTO RECIBOPAGA ( k00_numcgm,
                                           k00_dtoper,
                                           k00_receit,
                                           k00_hist  ,
                                           k00_valor ,
                                           k00_dtvenc,
                                           k00_numpre,
                                           k00_numpar,
                                           k00_numtot,
                                           k00_numdig,
                                           k00_conta ,
                                           k00_dtpaga,
                                           k00_numnov )
                  VALUES ( NUMCGM,
                    DTEMITE,
                    K03_RECJUR,
                    400,
                    ROUND(JURO+MULTA,2),
                    DATAVENC,
                    RECORD_NUMPRE.K99_NUMPRE,
                    NUMPAR,
                    NUMTOT,
                    NUMDIG,
                    0,
                           DTVENC,
                           NUMPRE );
                END IF;

              ELSE

                IF JURO <> 0 THEN

                  VLRJUROS := VLRJUROS + JURO;

                  INSERT INTO RECIBOPAGA ( k00_numcgm,
                                           k00_dtoper,
                                           k00_receit,
                                           k00_hist  ,
                                           k00_valor ,
                                           k00_dtvenc,
                                           k00_numpre,
                                           k00_numpar,
                                           k00_numtot,
                                           k00_numdig,
                                           k00_conta ,
                                           k00_dtpaga,
                                           k00_numnov )
                  VALUES ( NUMCGM,
                    DTEMITE,
                    K03_RECJUR,
                    400,
                    ROUND(JURO,2),
                    DATAVENC,
                    RECORD_NUMPRE.K99_NUMPRE,
                    NUMPAR,
                    NUMTOT,
                    NUMDIG,
                    0,
                           DTVENC,
                           NUMPRE );

                END IF;

                IF MULTA <> 0 THEN

                  VLRMULTA := VLRMULTA + MULTA;

                  INSERT INTO RECIBOPAGA ( k00_numcgm,
                                           k00_dtoper,
                                           k00_receit,
                                           k00_hist  ,
                                           k00_valor ,
                                           k00_dtvenc,
                                           k00_numpre,
                                           k00_numpar,
                                           k00_numtot,
                                           k00_numdig,
                                           k00_conta ,
                                           k00_dtpaga,
                                           k00_numnov )
                  VALUES ( NUMCGM,
                    DTEMITE,
                    K03_RECMUL,
                    401,
                    ROUND(MULTA,2),
                    DATAVENC,
                    RECORD_NUMPRE.K99_NUMPRE,
                    NUMPAR,
                    NUMTOT,
                    NUMDIG,
                    0,
                           DTVENC,
                           NUMPRE );

                END IF;

              END IF;

              --CALCULAR DESCONTO
              IF CORRECAOORI+VALOR_RECEITAORI <> 0 THEN

                -- insert into tempo (descricao, tempo) values ((select k00_matric from arrematric where k00_numpre = RECORD_NUMPRE.K99_NUMPRE)::text || ' ??? - antes FC_DESCONTO ----- ', clock_timestamp());

                DESCONTO := FC_DESCONTO(RECEITA,
                                        DTEMITE,
                                        CORRECAOORI+VALOR_RECEITAORI,
                                        JURO+MULTA,
                                        UNICA,
                                        DATAVENC,
                                        ANOUSU,
                                        RECORD_NUMPRE.K99_NUMPRE);
                IF DESCONTO <> 0 THEN
                  VLRDESCONTO := VLRDESCONTO + DESCONTO;

                  INSERT INTO RECIBOPAGA ( k00_numcgm,
                                           k00_dtoper,
                                           k00_receit,
                                           k00_hist  ,
                                           k00_valor ,
                                           k00_dtvenc,
                                           k00_numpre,
                                           k00_numpar,
                                           k00_numtot,
                                           k00_numdig,
                                           k00_conta ,
                                           k00_dtpaga,
                                           k00_numnov )
                  VALUES ( NUMCGM,
                    DTEMITE,
                    RECEITA,
                    918,
                    ROUND(DESCONTO*-1,2),
                    DATAVENC,
                    RECORD_NUMPRE.K99_NUMPRE,
                    NUMPAR,
                    NUMTOT,
                    NUMDIG,
                    0,
                           DTVENC,
                           NUMPRE );
                END IF;

                -- insert into tempo (descricao, tempo) values ((select k00_matric from arrematric where k00_numpre = RECORD_NUMPRE.K99_NUMPRE)::text || ' ??? - depois FC_DESCONTO ----- ', clock_timestamp());

              END IF;

            END IF;

          ELSE

            IF USASISAGUA = FALSE AND RECEITA <> 401002 THEN

              rtp_recibo.rvMensagem    := '1 - Erro ao gerar recibo. Contate suporte!';
              rtp_recibo.rlErro        := true;

              RETURN rtp_recibo;

            END IF;

          END IF;

          -- insert into tempo (descricao, tempo) values ((select k00_matric from arrematric where k00_numpre = RECORD_NUMPRE.K99_NUMPRE)::text || ' ??? - antes fc_desconto_iptu_nfse ----- ', clock_timestamp());

          -- Aplica desconto no IPTU referente a NFS-e (Plugin)
          PERFORM fc_desconto_iptu_nfse(
            NUMCGM,
            DTEMITE,
            RECEITA,
            DATAVENC,
            RECORD_NUMPRE.K99_NUMPRE,
            NUMPAR,
            NUMTOT,
            NUMDIG,
            DTVENC,
            NUMPRE,
            UNICA
          );

          -- insert into tempo (descricao, tempo) values ((select k00_matric from arrematric where k00_numpre = RECORD_NUMPRE.K99_NUMPRE)::text || ' ??? - saio loop  1 ----- ', clock_timestamp());

        END LOOP;

      END IF;

      -- insert into tempo (descricao, tempo) values ((select k00_matric from arrematric where k00_numpre = RECORD_NUMPRE.K99_NUMPRE)::text || ' ??? - saio loop 2 ----- ', clock_timestamp());

    END LOOP;

    -- insert into tempo (descricao, tempo) values ((select k00_matric from arrematric where k00_numpre = RECORD_NUMPRE.K99_NUMPRE)::text || ' ??? - saio loop 3 ----- ', clock_timestamp());

  END LOOP;

  -- insert into tempo (descricao, tempo) values ( ((select k00_matric from arrematric where k00_numpre = RECORD_NUMPRE.K99_NUMPRE)::text || ' ??? - saio loop 4 ----- ')::text, clock_timestamp());

  IF PROCESSA = TRUE THEN

    if cast(NUMBCO as integer) <> 0 then

      INSERT INTO ARREBANCO (k00_numpre,
                             k00_numpar,
                             k00_codbco,
                             k00_codage,
                             k00_numbco)
      VALUES (NUMPRE    ,
              0,
              CODBCO    ,
              CODAGE    ,
              NUMBCO    );
    end if;

    -- @todo - verificar esta validacao
    perform k00_receit,
      round(sum(k00_valor),2)
    from recibopaga
    where k00_numnov = NUMPRE
    group by k00_receit
    having round(sum(k00_valor),2) < 0;

    if found then
      rtp_recibo.rlErro     := true;
      rtp_recibo.rvMensagem := 'Recibo com registros negativos por receita. Contate suporte!';
    else
      rtp_recibo.rlErro     := false;
      rtp_recibo.rvMensagem := '';
    end if;

    RETURN rtp_recibo;

  ELSE

    rtp_recibo.rvMensagem    := '3 - Erro ao gerar recibo. Contate suporte!';
    rtp_recibo.rlErro        := true;

    RETURN  rtp_recibo;

  END IF;

END;
$$ language 'plpgsql';
SQL;
        $this->execute($sql);
    }

    public function down()
    {
        $sql  = <<<SQL
drop function fc_recibo(integer,date,date,integer);
drop   type tp_recibo;

create type tp_recibo as ( rvMensagem varchar(100),
                           rlErro     boolean );

create or replace function fc_recibo(integer,date,date,integer) returns tp_recibo  as
$$
DECLARE
  NUMPRE                ALIAS FOR $1;
  DTEMITE               ALIAS FOR $2;
  DTVENC                ALIAS FOR $3;
  ANOUSU                ALIAS FOR $4;

  iFormaCorrecao        integer default 2;
  iInstit               integer;
  iExerc                integer;

  iRegraIptu            integer;
  iRegraIss             integer;

  USASISAGUA            BOOLEAN;

  UNICA                 BOOLEAN := FALSE;
  NUMERO_ERRO           char(200);
  NUMCGM                INTEGER;
  RECORD_NUMPRE         RECORD;
  RECORD_ALIAS          RECORD;
  RECORD_GRAVA          RECORD;
  RECORD_NUMPREF        RECORD;
  RECORD_UNICA          RECORD;

  VALOR_RECEITA         FLOAT8;
  VALOR_RECEITA_ORI     FLOAT8;
  DESC_VALOR_RECEITA    FLOAT8 DEFAULT 0;

  VALOR_RECEITAORI      FLOAT8;

  CORRECAO              FLOAT8 DEFAULT 0;
  DESC_CORRECAO         FLOAT8 DEFAULT 0;
  CORRECAOORI           FLOAT8;
  JURO                  FLOAT8 DEFAULT 0;
  MULTA                 FLOAT8 DEFAULT 0;
  vlrjuroparc           FLOAT8 DEFAULT 0;
  vlrmultapar           FLOAT8 DEFAULT 0;
  DESCONTO              FLOAT8;
  nDescontoCorrigido    FLOAT8 default 0;

  numpreValorTotal      numeric default 0;

  RECEITA               INTEGER;
  K03_RECMUL            INTEGER;
  K03_RECJUR            INTEGER;
  V_K00_HIST            INTEGER;
  QUAL_OPER             INTEGER;

  DTOPER                DATE;
  DATAVENC              DATE;
  SQLRECIBO             VARCHAR(400);

  VLRJUROS              FLOAT8 default 0;
  VLRMULTA              FLOAT8 default 0;
  VLRDESCONTO           FLOAT8 default 0;

  V_CADTIPOPARC         INTEGER;
  V_CADTIPOPARC_FORMA   INTEGER;
  NUMPAR                INTEGER;
  NUMTOT                INTEGER;
  NUMDIG                INTEGER;
  ARRETIPO              INTEGER;
  PROCESSA              BOOLEAN DEFAULT FALSE;
  ISSQNVARIAVEL         BOOLEAN;
  CODBCO                INTEGER;
  CODAGE                CHAR(5);
  NUMBCO                VARCHAR(15);
  RECEITA_JUR           INTEGER;
  RECEITA_MUL           INTEGER;
  iTipoVlr              INTEGER;

  PERCDESCJUR           FLOAT8 DEFAULT 0;
  PERCDESCMUL           FLOAT8 DEFAULT 0;
  PERCDESCVLR           FLOAT8 DEFAULT 0;

  nPercArreDesconto     FLOAT8 DEFAULT 0;

  v_composicao          record;

  nComposCorrecao       numeric(15,2) default 0;
  nComposJuros          numeric(15,2) default 0;
  nComposMulta          numeric(15,2) default 0;

  nCorreComposJuros     numeric(15,2) default 0;
  nCorreComposMulta     numeric(15,2) default 0;

  rtp_recibo            tp_recibo%ROWTYPE;

  TOTPERC               FLOAT8;
  TEM_DESCONTO          INTEGER DEFAULT 0;

  lRaise                boolean default false;
  lParcelamento         boolean default false;

  record_alias_numcgm integer;
  record_alias_K00_DTVENC date;

  RECORD_ALIAS_K00_valor numeric;
  RECORD_ALIAS_K00_hist integer;

BEGIN

  select cast( fc_getsession('DB_instit') as integer )
  into iInstit;

  select cast( fc_getsession('DB_anousu') as integer )
  into iExerc;

  select db21_regracgmiptu::integer from db_config where codigo = iInstit
  into iRegraIptu;

  select db21_regracgmiss::integer from db_config where codigo = iInstit
  into iRegraIss;

  select db21_usasisagua
  into USASISAGUA
  from db_config
  where codigo = iInstit;

  select k03_separajurmulparc
  into iFormaCorrecao
  from numpref
  where k03_instit = iInstit
        and k03_anousu = iExerc;

  FOR RECORD_NUMPREF IN SELECT *
                        FROM NUMPREF
                        WHERE K03_ANOUSU = ANOUSU
  LOOP
    RECEITA_JUR := RECORD_NUMPREF.K03_RECJUR;
    RECEITA_MUL := RECORD_NUMPREF.K03_RECMUL;
  END LOOP;

  perform k00_numpre
  from recibo
  where k00_numnov = numpre LIMIT 1;
  if found then

    rtp_recibo.rvMensagem    := '4 - Erro ao gerar recibo. Contate suporte!';
    rtp_recibo.rlErro        := true;

    return  rtp_recibo;

  end if;

  perform 1
  from db_reciboweb
  where k99_numpre_n = numpre limit 1;
  if not found then

    rtp_recibo.rvMensagem    := '2 - Erro ao gerar recibo. Contate suporte!';
    rtp_recibo.rlErro        := true;

    return  rtp_recibo;

  end if;

  -- insert into tempo (descricao, tempo) values (' inicio', clock_timestamp());
  
  -- raise notice ' AAAAAAAAAAAAA ';

  select rinumcgm 
    into record_alias_numcgm
    from fc_socio_promitente((SELECT K99_NUMPRE
                                FROM DB_RECIBOWEB
                               WHERE K99_NUMPRE_N = NUMPRE limit 1), true, iRegraIptu, iRegraIss) limit 1;

  drop table if exists arrecad_temp_teste;
  create table arrecad_temp_teste as 
  select *
    from arrecad
   where k00_numpre in (SELECT distinct K99_NUMPRE
                         FROM DB_RECIBOWEB
                        WHERE K99_NUMPRE_N = NUMPRE);

  FOR RECORD_NUMPRE IN SELECT *
                       FROM DB_RECIBOWEB
                       WHERE K99_NUMPRE_N = NUMPRE
  LOOP

    -- insert into tempo (descricao, tempo) values ((select k00_matric from arrematric where k00_numpre = RECORD_NUMPRE.K99_NUMPRE)::text || ' 1 - for record_numpre', clock_timestamp());

    CODBCO = RECORD_NUMPRE.K99_CODBCO;
    CODAGE = RECORD_NUMPRE.K99_CODAGE;
    --    NUMBCO = RECORD_NUMPRE.K99_NUMBCO;

    select fc_numbcoconvenio(NUMBCO::integer) into NUMBCO;

    TEM_DESCONTO = RECORD_NUMPRE.K99_DESCONTO;

    -- raise notice ' AAAAAAAAAAAAA ';

    FOR RECORD_UNICA IN 
      SELECT DISTINCT
             K00_NUMPRE,
             K00_NUMPAR
        FROM ARRECAD
       WHERE K00_NUMPRE = RECORD_NUMPRE.K99_NUMPRE
         AND CASE WHEN RECORD_NUMPRE.K99_NUMPAR = 0 THEN
               TRUE
             ELSE
               K00_NUMPAR = RECORD_NUMPRE.K99_NUMPAR
             END
    LOOP

      -- insert into tempo (descricao, tempo) values ((select k00_matric from arrematric where k00_numpre = RECORD_NUMPRE.K99_NUMPRE)::text || ' 2 - for record_unica', clock_timestamp());

      PROCESSA := TRUE;

      IF RECORD_NUMPRE.K99_NUMPAR = 0 THEN
        UNICA := TRUE;
      ELSE
        
        IF RECORD_NUMPRE.K99_NUMPAR != RECORD_UNICA.K00_NUMPAR THEN
          PROCESSA := FALSE;
        END IF;

      END IF;

      NUMPAR := RECORD_UNICA.K00_NUMPAR;

      IF PROCESSA = TRUE THEN

        -- raise notice ' PROCESSA = TRUE ';

        record_alias_K00_DTVENC := (select fc_calculavenci(
          RECORD_NUMPRE.K99_NUMPRE,
          NUMPAR,
          (select k00_dtvenc
            FROM ARRECAD
           WHERE K00_NUMPRE = RECORD_NUMPRE.K99_NUMPRE
             AND K00_NUMPAR = NUMPAR limit 1),
          DTEMITE)
        );

        FOR RECORD_ALIAS IN
          
          -- GroupAggregate  (cost=8.71..17.72 rows=1 width=32)
          -- SELECT K00_RECEIT,
          --        K00_DTOPER,
          --        -- (select rinumcgm from fc_socio_promitente(K00_NUMPRE, true, iRegraIptu, iRegraIss) limit 1)as K00_NUMCGM,
          --        record_alias_numcgm
          --         K00_NUMCGM,
          --        fc_calculavenci(k00_numpre,k00_numpar,K00_DTVENC,DTEMITE) AS K00_DTVENC,
          --        K00_NUMPRE,
          --        K00_NUMPAR,
          --        (select K00_hist
          --           from arrecad as a
          --          where a.k00_numpre = arrecad.k00_numpre
          --            and a.k00_numpar = arrecad.k00_numpar
          --            and a.k00_receit = arrecad.k00_receit
          --            and a.k00_tipo = arrecad.k00_tipo order by 1 limit 1
          --            ) as k00_hist,

          --        (select sum(k00_valor)
          --           from arrecad as a
          --          where a.k00_numpre = arrecad.k00_numpre
          --            and a.k00_numpar = arrecad.k00_numpar
          --            and a.k00_receit = arrecad.k00_receit
          --            and a.k00_tipo = arrecad.k00_tipo 
          --            ) as k00_valor,
          --        K00_TIPO
          --   FROM ARRECAD
          --  WHERE K00_NUMPRE = RECORD_NUMPRE.K99_NUMPRE
          --    AND K00_NUMPAR = NUMPAR
          --  group by K00_RECEIT,
          --           K00_DTOPER,
          --           K00_NUMCGM,
          --           fc_calculavenci(k00_numpre,k00_numpar,K00_DTVENC,DTEMITE),
          --           K00_NUMPRE,
          --           K00_NUMPAR,
          --           K00_TIPO
          --  ORDER BY K00_NUMPRE,
          --           K00_NUMPAR,
          --           K00_RECEIT

          -- Index Scan using arrecad_numpre_numpar_receit_in on arrecad  (cost=0.43..8.45 rows=1 width=20)
          select k00_receit,
                 k00_dtoper,
                 record_alias_numcgm as K00_NUMCGM,
                 record_alias_K00_DTVENC AS K00_DTVENC,
                 k00_numpre,
                 k00_numpar,
                 k00_tipo
            from arrecad
           where k00_numpre = RECORD_NUMPRE.K99_NUMPRE
             and k00_numpar = NUMPAR
           ORDER BY K00_NUMPRE,
                    K00_NUMPAR,
                    K00_RECEIT

        LOOP

          select sum(k00_valor), 
                 min(K00_hist)
            into RECORD_ALIAS_K00_valor,
                 RECORD_ALIAS_K00_hist
            from arrecad_temp_teste as a
           where a.k00_numpre = RECORD_ALIAS.k00_numpre
             and a.k00_numpar = RECORD_ALIAS.k00_numpar
             and a.k00_receit = RECORD_ALIAS.K00_RECEIT;

          -- raise notice ' ----> % ', RECORD_ALIAS;

          -- insert into tempo (descricao, tempo) values ((select k00_matric from arrematric where k00_numpre = RECORD_NUMPRE.K99_NUMPRE)::text || ' 3 - record alias', clock_timestamp());

          PROCESSA := TRUE;
          RECEITA  := RECORD_ALIAS.K00_RECEIT;
          ARRETIPO := RECORD_ALIAS.K00_TIPO;
          DTOPER   := RECORD_ALIAS.K00_DTOPER;
          NUMCGM   := RECORD_ALIAS.K00_NUMCGM;
          DATAVENC := RECORD_ALIAS.K00_DTVENC;
          VALOR_RECEITA := RECORD_ALIAS_K00_valor;

          IF VALOR_RECEITA = 0 THEN
            SELECT Q05_VLRINF
            INTO VALOR_RECEITA
            FROM ISSVAR
            WHERE Q05_NUMPRE = RECORD_ALIAS.K00_NUMPRE
                  AND Q05_NUMPAR = RECORD_ALIAS.K00_NUMPAR;
            IF VALOR_RECEITA IS NULL THEN
              VALOR_RECEITA := 0;
            ELSE
              ISSQNVARIAVEL := TRUE;
            END IF;
          END IF;

          QUAL_OPER := 0;
          -- T24879: Se valor da receita nao for 0 (zero) ou
          -- recibo for proveniente de uma emissao geral de iss variavel
          -- continua geracao da recibopaga
          IF ( VALOR_RECEITA <> 0 OR RECORD_NUMPRE.K99_TIPO = 6 ) THEN

            FOR RECORD_GRAVA IN SELECT *
                                FROM ARRECAD
                                WHERE K00_NUMPRE = RECORD_NUMPRE.K99_NUMPRE
                                      AND K00_NUMPAR = NUMPAR
                                      AND K00_RECEIT = RECEITA
            LOOP

              -- insert into tempo (descricao, tempo) values ((select k00_matric from arrematric where k00_numpre = RECORD_NUMPRE.K99_NUMPRE)::text || ' 4 - record grava', clock_timestamp());

              IF QUAL_OPER = 0 THEN
                V_K00_HIST := RECORD_GRAVA.K00_HIST;
                NUMTOT := RECORD_GRAVA.K00_NUMTOT;
                NUMDIG  := RECORD_GRAVA.K00_NUMDIG;
                QUAL_OPER := 1;
              END IF;

            END LOOP;

            -- raise notice 'RECORD_GRAVA -> %', RECORD_GRAVA;
            -- raise notice 'iFormaCorrecao -> %', iFormaCorrecao;

            -- CALCULA CORRECAO
            if VALOR_RECEITA <> 0 then

              if iFormaCorrecao = 1 then

                VALOR_RECEITA_ORI = VALOR_RECEITA;

                select coalesce(rnCorreComposJuros,0),
                  coalesce(rnCorreComposMulta,0),
                  coalesce(rnComposCorrecao,0),
                  coalesce(rnComposJuros,0),
                  coalesce(rnComposMulta,0)
                into nCorreComposJuros,
                  nCorreComposMulta,
                  nComposCorrecao,
                  nComposJuros,
                  nComposMulta
                from fc_retornacomposicao(record_alias.k00_numpre, record_alias.k00_numpar, record_alias.k00_receit, RECORD_ALIAS_K00_hist, dtoper, dtvenc, anousu, datavenc);

                -- raise notice 'asdasdas';

                VALOR_RECEITA = VALOR_RECEITA + nComposCorrecao;
 
                CORRECAO := ROUND( FC_CORRE(RECEITA,DTOPER,VALOR_RECEITA,DTVENC,ANOUSU,DATAVENC) , 2 );

                CORRECAO := ROUND( CORRECAO - VALOR_RECEITA + nComposCorrecao, 2 );

                CORRECAO := CORRECAO + nCorreComposJuros + nCorreComposMulta;

                VALOR_RECEITA = VALOR_RECEITA_ORI;

              else

                CORRECAO := ROUND(FC_CORRE(RECEITA, DTOPER, VALOR_RECEITA, DTVENC, ANOUSU, DATAVENC) - round(VALOR_RECEITA, 2), 2);

              end if;

            else
              CORRECAO := 0;
            end if;

            if TEM_DESCONTO > 0 then

              select sum(fc_calcula_total_arrecad(x.k99_numpre, DTVENC))
                into numpreValorTotal
                from (select distinct db_reciboweb.k99_numpre 
                        from db_reciboweb 
                       where db_reciboweb.k99_numpre_n = NUMPRE) as x;

              select tipoparc.descjur,
                     tipoparc.descmul,
                     tipoparc.descvlr,
                     cadtipoparc.k40_codigo,
                     cadtipoparc.k40_forma,
                     tipoparc.tipovlr
                into percdescjur,
                     percdescmul,
                     percdescvlr,
                     v_cadtipoparc,
                     v_cadtipoparc_forma,
                     iTipoVlr
                from cadtipoparc
                     inner join tipoparc on tipoparc.cadtipoparc = cadtipoparc.k40_codigo
               where DTEMITE between tipoparc.dtini and tipoparc.dtfim
                 and tipoparc.maxparc = 1
                 and fc_verifica_desconto_faixa_valor(numpreValorTotal::numeric, tipoparc.vlrmin::numeric, tipoparc.vlrmax::numeric)
                 and cadtipoparc.k40_codigo = TEM_DESCONTO;

            end if;

            CORRECAOORI := CORRECAO;
            VALOR_RECEITAORI := VALOR_RECEITA;

            --  Trabalhar neste if para utilizar a mesma logica da recibodesconto
            --   alterar o programa de emissao de recibo para selecionar
            --   a regra se o contribuinte for ou nao loteador

            -- Verificar se a receita possui 'valores adicionais' (Jurdico > Procedimentos > Processo do Foro > Valores Adicionais) lanados a um processo.
            -- Caso possua, deve-se desconsiderar descontos em cima dessa receita.
            -- Obs.: Esse caso se aplica para dbitos de inicial.

            -- Nested Loop  (cost=0.86..16.99 rows=1 width=4)
            -- perform j150_receita
            --    from processoforomulta
            --         inner join processoforoinicial on j150_processoforo = v71_processoforo
            --         inner join inicialnumpre on v71_inicial = v59_inicial
            --   where j150_receita = RECEITA
            --     and v59_numpre   = RECORD_ALIAS.K00_NUMPRE;

            -- Nested Loop  (cost=0.86..16.99 rows=1 width=0)
            perform 1
               from inicialnumpre
                    inner join processoforoinicial on processoforoinicial.v71_inicial = inicialnumpre.v59_inicial
                    inner join processoforomulta on processoforomulta.j150_processoforo = processoforoinicial.v71_processoforo
              where inicialnumpre.v59_numpre = RECORD_ALIAS.K00_NUMPRE
                and processoforomulta.j150_receita = RECEITA;

            -- raise notice ' ----- RECEITA: %, RECORD_ALIAS.K00_NUMPRE: % ', RECEITA, RECORD_ALIAS.K00_NUMPRE;

            -- insert into tempo (descricao, tempo) values ((select k00_matric from arrematric where k00_numpre = RECORD_NUMPRE.K99_NUMPRE)::text || ' 5 - depois multa', clock_timestamp());

            if found then
              percdescvlr := 0;
              percdescmul := 0;
              percdescjur := 0;
            end if;

            if percdescvlr is not null and percdescvlr > 0 then

              -- insert into tempo (descricao, tempo) values ((select k00_matric from arrematric where k00_numpre = RECORD_NUMPRE.K99_NUMPRE)::text || ' ??? - if percdescvlr is not null and percdescvlr > 0 then', clock_timestamp());

              if iTipoVlr = 1 then

                DESC_CORRECAO := ROUND(CORRECAO * percdescvlr / 100,2);
                
                if DESC_CORRECAO > 0 then
                  --

                  INSERT INTO RECIBOPAGA (k00_numcgm,
                                          k00_dtoper,
                                          k00_receit,
                                          k00_hist,
                                          k00_valor,
                                          k00_dtvenc,
                                          k00_numpre,
                                          k00_numpar,
                                          k00_numtot,
                                          k00_numdig,
                                          k00_conta,
                                          k00_dtpaga,
                                          k00_numnov)
                  VALUES (NUMCGM,
                    DTEMITE,
                    RECEITA,
                    918,
                    (DESC_CORRECAO*-1),
                    DATAVENC,
                    RECORD_NUMPRE.K99_NUMPRE,
                    NUMPAR,NUMTOT,
                    NUMDIG,
                    0,
                          DTVENC,
                          NUMPRE);

                    -- insert into tempo (descricao, tempo) values ((select k00_matric from arrematric where k00_numpre = RECORD_NUMPRE.K99_NUMPRE)::text || ' ??? - if DESC_CORRECAO > 0 then', clock_timestamp());

                end if;
              elsif iTipoVlr = 2 then

                nDescontoCorrigido := ROUND((VALOR_RECEITA + CORRECAO) * percdescvlr / 100,2);
                

                -- insert into tempo (descricao, tempo) values ((select k00_matric from arrematric where k00_numpre = RECORD_NUMPRE.K99_NUMPRE)::text || ' ??? - nDescontoCorrigido := ROUND((VALOR_RECEITA + CORRECAO) * percdescvlr / 100,2);', clock_timestamp());

                if nDescontoCorrigido > 0 then
                  --
                  INSERT INTO RECIBOPAGA (k00_numcgm,
                                          k00_dtoper,
                                          k00_receit,
                                          k00_hist,
                                          k00_valor,
                                          k00_dtvenc,
                                          k00_numpre,
                                          k00_numpar,
                                          k00_numtot,
                                          k00_numdig,
                                          k00_conta,
                                          k00_dtpaga,
                                          k00_numnov )
                  VALUES (NUMCGM,
                    DTEMITE,
                    RECEITA,
                    918,
                    (nDescontoCorrigido*-1),
                    DATAVENC,
                    RECORD_NUMPRE.K99_NUMPRE,
                    NUMPAR,
                    NUMTOT,
                    NUMDIG,
                    0,
                          DTVENC,
                          NUMPRE);
                end if;
              end if;

              -- Se a forma de aplicacao da regra for pra loteamentos (= 3)
              -- entao aplica desconto no valor da receita (historico)
              if v_cadtipoparc_forma = 3 then
                DESC_VALOR_RECEITA := ROUND(VALOR_RECEITA * percdescvlr / 100,2);

                if DESC_VALOR_RECEITA > 0 then


                  -- insert into tempo (descricao, tempo) values ((select k00_matric from arrematric where k00_numpre = RECORD_NUMPRE.K99_NUMPRE)::text || ' ??? - if DESC_VALOR_RECEITA > 0 then ----- ', clock_timestamp());

                  --
                  INSERT INTO RECIBOPAGA (k00_numcgm,
                                          k00_dtoper,
                                          k00_receit,
                                          k00_hist,
                                          k00_valor,
                                          k00_dtvenc,
                                          k00_numpre,
                                          k00_numpar,
                                          k00_numtot,
                                          k00_numdig,
                                          k00_conta,
                                          k00_dtpaga,
                                          k00_numnov)
                  VALUES (NUMCGM,
                    DTEMITE,
                    RECEITA,
                    918,
                    (DESC_VALOR_RECEITA*-1),
                    DATAVENC,
                    RECORD_NUMPRE.K99_NUMPRE,
                    NUMPAR,
                    NUMTOT,
                    NUMDIG,
                    0,
                          DTVENC,
                          NUMPRE);
                end if;
              end if;

            end if;

            -- T24879: Se valor diferente de zero ou tipo recibo for da emissao geral do iss
            -- gera recibopaga normalmente
            IF (VALOR_RECEITA + CORRECAO) <> 0 OR RECORD_NUMPRE.K99_TIPO = 6 THEN

              -- insert into tempo (descricao, tempo) values ((select k00_matric from arrematric where k00_numpre = RECORD_NUMPRE.K99_NUMPRE)::text || ' ??? - IF (VALOR_RECEITA + CORRECAO) <> 0 OR RECORD_NUMPRE.K99_TIPO = 6 THEN ----- ', clock_timestamp());

              INSERT INTO RECIBOPAGA ( k00_numcgm,
                                       k00_dtoper,
                                       k00_receit,
                                       k00_hist  ,
                                       k00_valor ,
                                       k00_dtvenc,
                                       k00_numpre,
                                       k00_numpar,
                                       k00_numtot,
                                       k00_numdig,
                                       k00_conta ,
                                       k00_dtpaga,
                                       k00_numnov )
              VALUES ( NUMCGM,
                DTEMITE,
                RECEITA,
                V_K00_HIST + 100,
                ROUND(VALOR_RECEITA+CORRECAO,2),
                DATAVENC,
                RECORD_NUMPRE.K99_NUMPRE,
                NUMPAR,
                NUMTOT,
                NUMDIG,
                0,
                       DTVENC,
                       NUMPRE );

              -- CALCULA DESCONTO DA ARREDESCONTO
              perform v07_numpre
              from termo
              where v07_numpre = RECORD_NUMPRE.K99_NUMPRE;
              if found then
                lParcelamento := true;
              end if;

              if lParcelamento then

                -- insert into tempo (descricao, tempo) values ((select k00_matric from arrematric where k00_numpre = RECORD_NUMPRE.K99_NUMPRE)::text || ' ??? - antes fc_recibodesconto ----- ', clock_timestamp());

                -- Verifica desconto
                nPercArreDesconto := fc_recibodesconto(RECORD_NUMPRE.K99_NUMPRE,
                                                       NUMPAR,
                                                       NUMTOT,
                                                       RECEITA,
                                                       ARRETIPO,
                                                       DTEMITE,
                                                       fc_proximo_dia_util(DATAVENC));


                -- insert into tempo (descricao, tempo) values ((select k00_matric from arrematric where k00_numpre = RECORD_NUMPRE.K99_NUMPRE)::text || ' ??? - depois fc_recibodesconto ----- ', clock_timestamp());

                if nPercArreDesconto > 0 then

                  INSERT INTO RECIBOPAGA ( k00_numcgm,
                                           k00_dtoper,
                                           k00_receit,
                                           k00_hist  ,
                                           k00_valor ,
                                           k00_dtvenc,
                                           k00_numpre,
                                           k00_numpar,
                                           k00_numtot,
                                           k00_numdig,
                                           k00_conta ,
                                           k00_dtpaga,
                                           k00_numnov )
                  VALUES ( NUMCGM,
                    DTEMITE,
                    RECEITA,
                    918,
                    ROUND(((ROUND(VALOR_RECEITA+CORRECAO,2) * nPercArreDesconto)/100),2) * -1,
                    DATAVENC,
                    RECORD_NUMPRE.K99_NUMPRE,
                    NUMPAR,
                    NUMTOT,
                    NUMDIG,
                    0,
                           DTVENC,
                           NUMPRE );

                  -- insert into tempo (descricao, tempo) values ((select k00_matric from arrematric where k00_numpre = RECORD_NUMPRE.K99_NUMPRE)::text || ' ??? - depois insert de fc_recibodesconto ----- ', clock_timestamp());

                end if;
              end if;

            END IF;

            -- insert into tempo (descricao, tempo) values ((select k00_matric from arrematric where k00_numpre = RECORD_NUMPRE.K99_NUMPRE)::text || ' ??? - IF (VALOR_RECEITAORI + CORRECAOORI) <> 0 THEN ', clock_timestamp());

            IF (VALOR_RECEITAORI + CORRECAOORI) <> 0 THEN

              -- CALCULA JUROS
              
              if iFormaCorrecao = 1 then
                JURO := ROUND((VALOR_RECEITAORI + CORRECAO) * FC_JUROS(RECEITA, DATAVENC, DTEMITE, DTOPER, FALSE, ANOUSU), 2);
              else
                JURO := ROUND((CORRECAOORI + VALOR_RECEITAORI) * FC_JUROS(RECEITA, DATAVENC, DTEMITE, DTOPER, FALSE, ANOUSU), 2);
              end if;

              
              JURO = JURO + nComposJuros;

              -- CALCULA MULTA
              if iFormaCorrecao = 1 then
                MULTA := round((VALOR_RECEITAORI + CORRECAO)::numeric(15, 2) * FC_MULTA(RECEITA, DATAVENC, DTEMITE, DTOPER, ANOUSU)::numeric(15, 5), 2);
              else
                MULTA := ROUND((CORRECAOORI + VALOR_RECEITAORI)::numeric(15, 2) * FC_MULTA(RECEITA, DATAVENC, DTEMITE, DTOPER, ANOUSU)::numeric(15, 5), 2);
              end if;

              MULTA = MULTA + nComposMulta;

              SELECT K02_RECMUL,
                K02_RECJUR
              INTO K03_RECMUL,
                K03_RECJUR
              FROM TABREC
              WHERE K02_CODIGO = RECEITA;

              IF K03_RECMUL IS NULL THEN
                K03_RECMUL := RECEITA_MUL;
              END IF;

              IF K03_RECJUR IS NULL THEN
                K03_RECJUR := RECEITA_JUR;
              END IF;
              -- INCLUIDO VARIAVEL DESCONTO NO DB_RECIBOWEB

              if percdescjur is not null and percdescmul is not null and (nPercArreDesconto is null or nPercArreDesconto <= 0) then
                vlrjuroparc := (ROUND(cast(JURO as FLOAT8) * percdescjur / 100,2));

                if vlrjuroparc > 0 then

                  INSERT INTO RECIBOPAGA ( k00_numcgm,
                                           k00_dtoper,
                                           k00_receit,
                                           k00_hist  ,
                                           k00_valor ,
                                           k00_dtvenc,
                                           k00_numpre,
                                           k00_numpar,
                                           k00_numtot,
                                           k00_numdig,
                                           k00_conta ,
                                           k00_dtpaga,
                                           k00_numnov)
                  VALUES ( NUMCGM,
                    DTEMITE,
                    K03_RECJUR,
                    918,
                    (vlrjuroparc * -1),
                    DATAVENC,
                    RECORD_NUMPRE.K99_NUMPRE,
                    NUMPAR,
                    NUMTOT,
                    NUMDIG,
                    0,
                           DTVENC,
                           NUMPRE);
                end if;
                vlrmultapar := (ROUND(cast(MULTA as FLOAT8) * percdescmul / 100,2));
                if vlrmultapar > 0  then
                  
                  INSERT INTO RECIBOPAGA ( k00_numcgm,
                                           k00_dtoper,
                                           k00_receit,
                                           k00_hist  ,
                                           k00_valor ,
                                           k00_dtvenc,
                                           k00_numpre,
                                           k00_numpar,
                                           k00_numtot,
                                           k00_numdig,
                                           k00_conta ,
                                           k00_dtpaga,
                                           k00_numnov )
                  VALUES ( NUMCGM,
                    DTEMITE,
                    K03_RECMUL,
                    918,
                    (vlrmultapar * -1),
                    DATAVENC,
                    RECORD_NUMPRE.K99_NUMPRE,
                    NUMPAR,
                    NUMTOT,
                    NUMDIG,
                    0,
                           DTVENC,
                           NUMPRE );
                end if;
              end if;

              IF K03_RECJUR = 0 OR K03_RECMUL = 0 OR K03_RECJUR = K03_RECMUL THEN

                IF JURO+MULTA <> 0 THEN

                  VLRJUROS := VLRJUROS + JURO;
                  VLRMULTA := VLRMULTA + MULTA;
                  
                  INSERT INTO RECIBOPAGA ( k00_numcgm,
                                           k00_dtoper,
                                           k00_receit,
                                           k00_hist  ,
                                           k00_valor ,
                                           k00_dtvenc,
                                           k00_numpre,
                                           k00_numpar,
                                           k00_numtot,
                                           k00_numdig,
                                           k00_conta ,
                                           k00_dtpaga,
                                           k00_numnov )
                  VALUES ( NUMCGM,
                    DTEMITE,
                    K03_RECJUR,
                    400,
                    ROUND(JURO+MULTA,2),
                    DATAVENC,
                    RECORD_NUMPRE.K99_NUMPRE,
                    NUMPAR,
                    NUMTOT,
                    NUMDIG,
                    0,
                           DTVENC,
                           NUMPRE );
                END IF;

              ELSE

                IF JURO <> 0 THEN

                  VLRJUROS := VLRJUROS + JURO;

                  INSERT INTO RECIBOPAGA ( k00_numcgm,
                                           k00_dtoper,
                                           k00_receit,
                                           k00_hist  ,
                                           k00_valor ,
                                           k00_dtvenc,
                                           k00_numpre,
                                           k00_numpar,
                                           k00_numtot,
                                           k00_numdig,
                                           k00_conta ,
                                           k00_dtpaga,
                                           k00_numnov )
                  VALUES ( NUMCGM,
                    DTEMITE,
                    K03_RECJUR,
                    400,
                    ROUND(JURO,2),
                    DATAVENC,
                    RECORD_NUMPRE.K99_NUMPRE,
                    NUMPAR,
                    NUMTOT,
                    NUMDIG,
                    0,
                           DTVENC,
                           NUMPRE );

                END IF;

                IF MULTA <> 0 THEN

                  VLRMULTA := VLRMULTA + MULTA;

                  INSERT INTO RECIBOPAGA ( k00_numcgm,
                                           k00_dtoper,
                                           k00_receit,
                                           k00_hist  ,
                                           k00_valor ,
                                           k00_dtvenc,
                                           k00_numpre,
                                           k00_numpar,
                                           k00_numtot,
                                           k00_numdig,
                                           k00_conta ,
                                           k00_dtpaga,
                                           k00_numnov )
                  VALUES ( NUMCGM,
                    DTEMITE,
                    K03_RECMUL,
                    401,
                    ROUND(MULTA,2),
                    DATAVENC,
                    RECORD_NUMPRE.K99_NUMPRE,
                    NUMPAR,
                    NUMTOT,
                    NUMDIG,
                    0,
                           DTVENC,
                           NUMPRE );

                END IF;

              END IF;

              --CALCULAR DESCONTO
              IF CORRECAOORI+VALOR_RECEITAORI <> 0 THEN

                -- insert into tempo (descricao, tempo) values ((select k00_matric from arrematric where k00_numpre = RECORD_NUMPRE.K99_NUMPRE)::text || ' ??? - antes FC_DESCONTO ----- ', clock_timestamp());

                DESCONTO := FC_DESCONTO(RECEITA,
                                        DTEMITE,
                                        CORRECAOORI+VALOR_RECEITAORI,
                                        JURO+MULTA,
                                        UNICA,
                                        DATAVENC,
                                        ANOUSU,
                                        RECORD_NUMPRE.K99_NUMPRE);
                IF DESCONTO <> 0 THEN
                  VLRDESCONTO := VLRDESCONTO + DESCONTO;

                  INSERT INTO RECIBOPAGA ( k00_numcgm,
                                           k00_dtoper,
                                           k00_receit,
                                           k00_hist  ,
                                           k00_valor ,
                                           k00_dtvenc,
                                           k00_numpre,
                                           k00_numpar,
                                           k00_numtot,
                                           k00_numdig,
                                           k00_conta ,
                                           k00_dtpaga,
                                           k00_numnov )
                  VALUES ( NUMCGM,
                    DTEMITE,
                    RECEITA,
                    918,
                    ROUND(DESCONTO*-1,2),
                    DATAVENC,
                    RECORD_NUMPRE.K99_NUMPRE,
                    NUMPAR,
                    NUMTOT,
                    NUMDIG,
                    0,
                           DTVENC,
                           NUMPRE );
                END IF;

                -- insert into tempo (descricao, tempo) values ((select k00_matric from arrematric where k00_numpre = RECORD_NUMPRE.K99_NUMPRE)::text || ' ??? - depois FC_DESCONTO ----- ', clock_timestamp());

              END IF;

            END IF;

          ELSE

            IF USASISAGUA = FALSE AND RECEITA <> 401002 THEN

              rtp_recibo.rvMensagem    := '1 - Erro ao gerar recibo. Contate suporte!';
              rtp_recibo.rlErro        := true;

              RETURN rtp_recibo;

            END IF;

          END IF;

          -- insert into tempo (descricao, tempo) values ((select k00_matric from arrematric where k00_numpre = RECORD_NUMPRE.K99_NUMPRE)::text || ' ??? - antes fc_desconto_iptu_nfse ----- ', clock_timestamp());

          -- Aplica desconto no IPTU referente a NFS-e (Plugin)
          PERFORM fc_desconto_iptu_nfse(
            NUMCGM,
            DTEMITE,
            RECEITA,
            DATAVENC,
            RECORD_NUMPRE.K99_NUMPRE,
            NUMPAR,
            NUMTOT,
            NUMDIG,
            DTVENC,
            NUMPRE,
            UNICA
          );

          -- insert into tempo (descricao, tempo) values ((select k00_matric from arrematric where k00_numpre = RECORD_NUMPRE.K99_NUMPRE)::text || ' ??? - saio loop  1 ----- ', clock_timestamp());

        END LOOP;

      END IF;

      -- insert into tempo (descricao, tempo) values ((select k00_matric from arrematric where k00_numpre = RECORD_NUMPRE.K99_NUMPRE)::text || ' ??? - saio loop 2 ----- ', clock_timestamp());

    END LOOP;

    -- insert into tempo (descricao, tempo) values ((select k00_matric from arrematric where k00_numpre = RECORD_NUMPRE.K99_NUMPRE)::text || ' ??? - saio loop 3 ----- ', clock_timestamp());

  END LOOP;

  -- insert into tempo (descricao, tempo) values ( ((select k00_matric from arrematric where k00_numpre = RECORD_NUMPRE.K99_NUMPRE)::text || ' ??? - saio loop 4 ----- ')::text, clock_timestamp());

  IF PROCESSA = TRUE THEN

    if cast(NUMBCO as integer) <> 0 then

      INSERT INTO ARREBANCO (k00_numpre,
                             k00_numpar,
                             k00_codbco,
                             k00_codage,
                             k00_numbco)
      VALUES (NUMPRE    ,
              0,
              CODBCO    ,
              CODAGE    ,
              NUMBCO    );
    end if;

    -- @todo - verificar esta validacao
    perform k00_receit,
      round(sum(k00_valor),2)
    from recibopaga
    where k00_numnov = NUMPRE
    group by k00_receit
    having round(sum(k00_valor),2) < 0;

    if found then
      rtp_recibo.rlErro     := true;
      rtp_recibo.rvMensagem := 'Recibo com registros negativos por receita. Contate suporte!';
    else
      rtp_recibo.rlErro     := false;
      rtp_recibo.rvMensagem := '';
    end if;

    RETURN rtp_recibo;

  ELSE

    rtp_recibo.rvMensagem    := '3 - Erro ao gerar recibo. Contate suporte!';
    rtp_recibo.rlErro        := true;

    RETURN  rtp_recibo;

  END IF;

END;
$$ language 'plpgsql';
SQL;
        $this->execute($sql);
    }
}
