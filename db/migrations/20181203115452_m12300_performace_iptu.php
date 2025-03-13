<?php

use Classes\PostgresMigration;

class M12300PerformaceIptu extends PostgresMigration
{
    public function up()
    {
        $this->reciboUp();
        $this->descontoUp();
        $this->reciboDescontoUp();
        $this->socioPromitenteUp();
    }

    public function down()
    {
        $this->reciboDown();
        $this->descontoDown();
        $this->reciboDescontoDown();
        $this->socioPromitenteDown();
    }

    private function reciboUp() 
    {
        $sql = 
<<<SQL

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
  create temp table arrecad_temp_teste as 
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

            -- Verificar se a receita possui 'valores adicionais' (Jurídico > Procedimentos > Processo do Foro > Valores Adicionais) lançados a um processo.
            -- Caso possua, deve-se desconsiderar descontos em cima dessa receita.
            -- Obs.: Esse caso se aplica para débitos de inicial.

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

    private function descontoUp() 
    {
        $sql = 
<<<SQL

--drop function fc_desconto(integer,date,float8,float8,bool,date,integer,integer);
create or replace function fc_desconto(integer,date,float8,float8,bool,date,integer,integer) returns float8 as
$$
declare
  receita              alias for $1;
  v_data_arre          alias for $2;
  valor                alias for $3;
  jurmulta             alias for $4;
  unica                alias for $5;
  v_data_venc          alias for $6;
  subdir               alias for $7;
  numpre               alias for $8;

  z99_carnes           char(10);
  descon               float8  default 0;
  v_tabrec             record;
  v_recibounica        integer;
  v_entroudesctabrecjm boolean default false;
  recunica             record;

  lRaise              boolean default false;
  data_certa          date default null;

  dtDataVencimentoUnica    date;

begin

  -- lRaise := ( case when fc_getsession('DB_debugon') is null then false else true end );

  -- if lRaise is true then
  --   if fc_getsession('db_debug') <> '' then
  --     perform fc_debug('<desconto> Iniciando processamento', lRaise, false, false);
  --   else
  --     perform fc_debug('<desconto> Iniciando processamento', lRaise, true, false);
  --   end if;
  -- end if;

  select *
    into v_tabrec
    from tabrec
         inner join tabrecjm  on tabrec.k02_codjm = tabrecjm.k02_codjm
   where k02_codigo = receita;

  if not found then
    return 0;
  end if;

  if unica is true then

    -- if lRaise is true then
    --   perform fc_debug('<desconto>  ', lRaise, false, false);
    --   perform fc_debug('<desconto> acessou unica is true - receita: '||receita||' - v_tabrec: '||v_tabrec.k02_codjm, lRaise, false, false);
    --   perform fc_debug('<desconto> k02_dtdes4: '||v_tabrec.k02_dtdes4||' - k02_dtdes4: '||v_tabrec.k02_dtdes4||' - k02_dtdes5: '||v_tabrec.k02_dtdes5||', k02_dtdes5: '||v_tabrec.k02_dtdes5, lRaise, false, false);
    -- end if;

-- if v_recibounica = 0 then

    -- if lRaise is true then
    --   perform fc_debug('<desconto> v_data_arre: '||v_data_arre||'  - v_data_venc: '||v_data_venc, lRaise, false, false);
    -- end if;

    if not v_tabrec.k02_dtdes4 isnull and v_data_arre <= v_tabrec.k02_dtdes4 then

      -- if lRaise is true then
      --   perform fc_debug('<desconto>    entrou no if 1 do k02_dtdes4...', lRaise, false, false);
      -- end if;

      if ( not v_tabrec.k02_caldes isnull and v_tabrec.k02_caldes = 't' ) or ( v_tabrec.k02_caldes = 'f' and v_data_venc >= v_data_arre ) then

        descon               := ( v_tabrec.k02_desco4 / 100::float8 );
        v_entroudesctabrecjm := true;

        -- if lRaise is true then
        --   perform fc_debug('<desconto>       entrou no if 2 do k02_dtdes4... k02_desco4: '||v_tabrec.k02_desco4||' - descon: '||descon, lRaise, false, false);
        -- end if;

      end if;

    else
      if not v_tabrec.k02_dtdes5 isnull and v_data_arre <= v_tabrec.k02_dtdes5 then
        -- if lRaise is true then
        --   perform fc_debug('<desconto>    entrou no if 3 do k02_dtdes4...', lRaise, false, false);
        -- end if;
        if ( not v_tabrec.k02_caldes isnull and v_tabrec.k02_caldes = 't' ) or ( v_tabrec.k02_caldes = 'f' and v_data_venc >= v_data_arre ) then
          descon               := ( v_tabrec.k02_desco5 / 100::float8 );
          v_entroudesctabrecjm := true;
          -- if lRaise is true then
          --   perform fc_debug('<desconto>       entrou no if 4 do k02_dtdes4... k02_desco4: '||v_tabrec.k02_desco4||' - descon: '||descon, lRaise, false, false);
          -- end if;
        end if;
      else
        if not v_tabrec.k02_dtdes6 isnull and v_data_arre <= v_tabrec.k02_dtdes6 then
          -- if lRaise is true then
          --   perform fc_debug('<desconto>    entrou no if 5 do k02_dtdes4...', lRaise, false, false);
          -- end if;
          if ( not v_tabrec.k02_caldes isnull and v_tabrec.k02_caldes = 't' ) or ( v_tabrec.k02_caldes = 'f' and v_data_venc >= v_data_arre ) then
            descon               := ( v_tabrec.k02_desco6 / 100::float8 );
            v_entroudesctabrecjm := true;
            -- if lRaise is true then
            --   perform fc_debug('<desconto>       entrou no if 6 do k02_dtdes4... k02_desco4: '||v_tabrec.k02_desco4||' - descon: '||descon, lRaise, false, false);
            -- end if;
          end if;
        end if;
      end if;
    end if;

         -- end if;


    -- if lRaise is true then
    --   perform fc_debug('<desconto>    v_entroudesctabrecjm: '||v_entroudesctabrecjm, lRaise, false, false);
    -- end if;

    if v_entroudesctabrecjm is false or (unica is true and v_tabrec.k02_caldes is false) then

      v_recibounica := 0;


      for recunica in

        select k00_dtvenc,
               k00_percdes
          from recibounica
         where recibounica.k00_numpre = numpre
           and v_data_arre <= fc_proximo_dia_util(recibounica.k00_dtvenc)

      loop

        -- if lRaise is true then
        --   perform fc_debug('<desconto> Encontrou percentual desconto da Tabela recibounica', lRaise, false, false);
        -- end if;

        if v_recibounica = 0 and v_data_arre <= fc_proximo_dia_util(recunica.k00_dtvenc) then

          descon        := descon + ( recunica.k00_percdes / 100::float8 );
          v_recibounica := 1;
        end if;

      end loop;

    end if;

  else

    -- if lRaise is true then
    --   perform fc_debug('<desconto> -------- NAO E UNICA ------------' , lRaise, false, false);
    --   perform fc_debug('<desconto> v_tabrec.k02_dtdes1 = ' || coalesce(v_tabrec.k02_dtdes1::text, 'null')::text , lRaise, false, false);
    --   perform fc_debug('<desconto> v_tabrec.k02_dtdes2 = ' || coalesce(v_tabrec.k02_dtdes2::text, 'null')::text , lRaise, false, false);
    --   perform fc_debug('<desconto> v_tabrec.k02_caldes = ' || coalesce(v_tabrec.k02_caldes::text, 'null')::text , lRaise, false, false);
    --   perform fc_debug('<desconto> v_tabrec.k02_dtdes3 = ' || coalesce(v_tabrec.k02_dtdes3::text, 'null')::text , lRaise, false, false);
    --   perform fc_debug('<desconto> v_tabrec.k02_caldes = ' || coalesce(v_tabrec.k02_caldes::text, 'null')::text , lRaise, false, false);
    --   perform fc_debug('<desconto> v_data_arre         = ' || coalesce(v_data_arre::text, 'null')::text, lRaise, false, false);
    --   perform fc_debug('<desconto> v_data_venc         = ' || coalesce(v_data_venc::text, 'null')::text, lRaise, false, false);
    -- end if;


-------------------


    data_certa := v_data_venc;


    if v_tabrec.k02_sabdom = true then

      -- if lRaise is true then
      --   perform fc_debug('<desconto> L O O P   I N I C I O - calculo do proximo dia util' , lRaise, false, false);
      --   perform fc_debug('<desconto> - data de vencimento: ' || v_data_venc, lRaise, false, false);
      -- end if;

      loop

        perform k13_data
           from calend
          where k13_data = data_certa;

        if found then
          data_certa := data_certa + 1;
          -- perform fc_debug('<desconto> data calculada : ' || data_certa, lRaise, false, false);
        else
          exit;
        end if;

      end loop;

    --   -- if lRaise is true then
    --   --   perform fc_debug('<desconto>', lRaise, false, false);
    --   --   perform fc_debug('<desconto> L O O P   FIM', lRaise, false, false);
    --   -- end if;

    v_data_venc := data_certa;
    -- -- perform fc_debug('<desconto> - nova data de vencimento: ' || v_data_venc, lRaise, false, false);
    end if;


-----------------


    if not v_tabrec.k02_dtdes1 isnull and v_data_arre <= v_tabrec.k02_dtdes1 then

      -- if lRaise is true then
      --   perform fc_debug('<desconto> entrou no if 1' , lRaise, false, false);
      -- end if;

      if (    not v_tabrec.k02_caldes isnull and v_tabrec.k02_caldes = 't' )
           or ( v_tabrec.k02_caldes = 'f' and v_data_venc >= v_data_arre )   then
        descon := ( v_tabrec.k02_desco1 / 100::float8 ) ;

        -- if lRaise is true then
        --   perform fc_debug('<desconto> desconto = ' || descon , lRaise, false, false);
        -- end if;

      end if;

    else

      if not v_tabrec.k02_dtdes2 isnull and v_data_arre <= v_tabrec.k02_dtdes2 then

        -- if lRaise is true then
        --   perform fc_debug('<desconto> entrou no if 2' , lRaise, false, false);
        -- end if;

        if ( not v_tabrec.k02_caldes isnull and v_tabrec.k02_caldes = 't' ) or ( v_tabrec.k02_caldes = 'f' and v_data_venc >= v_data_arre )   then
          descon := ( v_tabrec.k02_desco2 / 100::float8 ) ;

          -- if lRaise is true then
          --   perform fc_debug('<desconto> desconto = ' || descon , lRaise, false, false);
          -- end if;
        end if;

      else

        if not v_tabrec.k02_dtdes3 isnull and v_data_arre <= v_tabrec.k02_dtdes3 then

          -- if lRaise is true then
          --   perform fc_debug('<desconto> entrou no if 2' , lRaise, false, false);
          -- end if;

          if ( not v_tabrec.k02_caldes isnull and v_tabrec.k02_caldes = 't' ) or ( v_tabrec.k02_caldes = 'f' and v_data_venc >= v_data_arre )   then
            descon := ( v_tabrec.k02_desco3 / 100::float8 ) ;

            -- if lRaise is true then
            --   perform fc_debug('<desconto> desconto = ' || descon , lRaise, false, false);
            -- end if;
          end if;
        end if;

      end if;
    end if;

  end if;

  -- if lRaise is true then
  --   perform fc_debug('<desconto> descon: '||descon, lRaise, false, false);
  -- end if;

  if descon <> 0 then
    if not v_tabrec.k02_integr isnull and v_tabrec.k02_integr = 't' then
      descon := round( ( valor + jurmulta ) * descon ,2)::float8 ;
    else
      descon := round( valor * descon ,2)::float8 ;
    end if;
  end if;

  -- if lRaise is true then
  --   perform fc_debug('<desconto> Fim do processamento. Retorno: '||round(descon,2), lRaise, false, true);
  -- end if;
  return round(descon,2);
end;
$$ language 'plpgsql';



SQL;

        $this->execute($sql);
    }

    private function reciboDescontoUp() 
    {
        $sql = 
<<<SQL

create or replace function fc_recibodesconto(integer, integer, integer, integer, integer, date, date) returns float8 as
$$
declare
  iNumpre       alias for $1; -- Numpre do Debito
  iNumpar       alias for $2; -- Numpar do Debito
  iNumtot       alias for $3; -- Total de Parcelas do Debitos
  iReceit       alias for $4; -- Receita do Numpre
  iArreTipo     alias for $5; -- Arretipo do Numpre
  dEmissao      alias for $6; -- Data da Emissao (para calculo)
  dVencimento   alias for $7; -- Data do Vencimento

  iCadTipo      integer;
  iInstitSessao integer;
  iTipoValor    integer;

  rArreDesconto record;

  -- Valores Totais para Calculo
  nVlrHis       numeric(15,2) default 0;
  nVlrCor       numeric(15,2) default 0;
  nVlrJur       numeric(15,2) default 0;
  nVlrMul       numeric(15,2) default 0;
  nVlrDes       numeric(15,2) default 0;
  nVlrTot       numeric(15,2) default 0;
  nVlrTotOri    numeric(15,2) default 0;

  -- Percentual de Desconto
  nPercRetorno  numeric(15,2) default 0;

  -- Percentuais de Descontos da Regra
  nPercDescCor  numeric(15,2) default 0;
  nPercDescJur  numeric(15,2) default 0;
  nPercDescMul  numeric(15,2) default 0;

  sSqlRegra     text   default '';

  lRaise        boolean default false;
begin
  
  -- lRaise := ( case when fc_getsession('DB_debugon') is null then false else true end );
  
  -- if lRaise is true then
  --   if fc_getsession('db_debug') <> '' then  
  --     perform fc_debug('<recibodesconto> Iniciando processamento...', lRaise, false, false);
  --   else 
  --     perform fc_debug('<recibodesconto> Iniciando processamento...', lRaise, true, false);
  --   end if;
  -- end if;
  
  if dEmissao > dVencimento then
    -- if lRaise is true then
    --   perform fc_debug('<recibodesconto> debito vencido, nao calcula desconto',lRaise, false, false);
    --   perform fc_debug('<recibodesconto> retorno: '||nPercRetorno,lRaise, false, false);
    -- end if;
    return nPercRetorno;
  end if;

  iInstitSessao := cast(fc_getsession('DB_instit') as integer);

  -- Busca Arredesconto
  select *
    into rArreDesconto
    from arredesconto
         inner join arreinstit on k00_numpre = k38_numpre
   where k38_numpre = iNumpre
     and k00_instit = iInstitSessao;

  if not found then
    return nPercRetorno;
  end if;

  
  -- if lRaise is true then
  --   perform fc_debug('<recibodesconto> Arredesconto '||rArredesconto,lRaise, false, false);
  --   perform fc_debug('<recibodesconto> ---------------',lRaise, false, false);
  -- end if;


  -- Busca CadTipo do Debito
  select k03_tipo
    into iCadTipo
    from arretipo
   where k00_tipo   = iArreTipo
     and k00_instit = iInstitSessao;

  if not found then
    return nPercRetorno;
  end if;
  

  -- if lRaise is true then
  --   perform fc_debug('<recibodesconto> CadTipo '||iCadTipo,lRaise, false, false);
  --   perform fc_debug('<recibodesconto> ---------------',lRaise, false, false);
  -- end if;

  if iCadTipo in (6,13,16,17) then
    -- if lRaise is true then
    --   perform fc_debug('<recibodesconto> PARCELAMENTO',lRaise, false, false);
    --   perform fc_debug('<recibodesconto> ---------------',lRaise, false, false);
    -- end if;

    select cast(v07_vlrhis as numeric),
           cast(v07_vlrcor - v07_vlrhis as numeric), -- (Valor da Correcao)
           cast(v07_vlrjur as numeric),
           cast(v07_vlrmul as numeric),
           cast(v07_vlrdes as numeric),
           cast(v07_valor  as numeric)
      into nVlrHis,
           nVlrCor,
           nVlrJur,
           nVlrMul,
           nVlrDes,
           nVlrTot
      from termo
     where v07_numpre = iNumpre
       and v07_instit = iInstitSessao;

    -- if lRaise is true then
    --   perform fc_debug('<recibodesconto> TERMO: VlrHis '||nVlrHis||' VlrCor '||nVlrCor||' VlrJur '||nVlrJur||' VlrMul '||nVlrMul||' VlrDes '||nVlrDes||' VlrTot '||nVlrTot,lRaise, false, false);
    --   perform fc_debug('<recibodesconto> ---------------',lRaise, false, false);
    -- end if;

    -- Salva Montante Original do Parcelamento
    nVlrTotOri := nVlrTot;

  else
    --
    -- No futuro colocaremos a FC_CALCULA para podermos fazer o desconto para qualquer tipo de debito
    --
  end if;

  sSqlRegra := ' select tipovlr,
                        descjur, 
                        descmul, 
                        descvlr
                   from cadtipoparc 
                        inner join tipoparc       on tipoparc.cadtipoparc = cadtipoparc.k40_codigo
                  where k40_codigo     = '|| rArreDesconto.k38_cadtipoparc ||'
                    and k40_aplicacao  = 2                 
                    and k40_instit     = '|| iInstitSessao ||'                 
                    and '||iNumtot||' <= maxparc   
                 -- comentado para dar desconto independente da data fim da regra       
                 -- and '||quote_literal(dEmissao)||' between k40_dtini and k40_dtfim 
                 order by maxparc 
                    limit 1;'; 

  -- if lRaise is true then
--    perform fc_debug('<recibodesconto> %', sSqlRegra;
--    perform fc_debug('<recibodesconto> ---------------';
  -- end if;

  -- Buscar Regra de Acordo com a CadTipoParc
  execute sSqlRegra
     into iTipoValor,
          nPercDescJur,
          nPercDescMul,
          nPercDescCor;

  if not found then
    return nPercRetorno;
  end if;

  -- if lRaise is true then
  --   perform fc_debug('<recibodesconto> Regra '||rArreDesconto.k38_cadtipoparc||'  DescJur '||nPercDescJur||'  DescMul '||nPercDescMul||'  DescVlr '||nPercDescCor, lRaise, false, false);
  --   perform fc_debug('<recibodesconto> ---------------',lRaise, false, false);
  --   perform fc_debug('<recibodesconto> nVlrHis : '||nVlrHis||' nVlrCor : '||nVlrCor,lRaise, false, false);
  --   perform fc_debug('<recibodesconto> ---------------',lRaise, false, false);
  -- end if;
  
  --
  -- Se o Tipo de valor for = 2 entao o valor a ser utilizado deve ser o valor corrigido (historio + correcao)
  --   caso contrario deve ser o valor da correcao
  --
  if iTipoValor = 2 then
    nVlrCor := ( nVlrHis + nVlrCor );
    -- if lRaise then
    --   perform fc_debug('<recibodesconto> Calculando percentual com valor corrigido (historico + correcao) Valor Encontrado : '||nVlrCor,lRaise, false, false);
    --   perform fc_debug('<recibodesconto> ---------------',lRaise, false, false);
    -- end if;
  end if;

  -- Recalcula Valores Deduzindo os Descontos
  nVlrJur := nVlrJur - round( (nVlrJur * nPercDescJur)/100, 2 );
  nVlrMul := nVlrMul - round( (nVlrMul * nPercDescMul)/100, 2 );
  nVlrCor := nVlrCor - round( (nVlrCor * nPercDescCor)/100, 2 );

  -- Refaz valor do montante do parcelamento
  if  iTipoValor = 2 then
    nVlrTot := round( nVlrCor + nVlrJur + nVlrMul - nVlrDes, 2);
  else
    nVlrTot := round( nVlrHis + nVlrCor + nVlrJur + nVlrMul - nVlrDes, 2);
  end if;
  
  -- if lRaise then
  --   perform fc_debug('<recibodesconto> nVlrTot : '||nVlrTot,lRaise, false, false);
  -- end if;

  nPercRetorno := abs(100.00 - round( (nVlrTot * 100) / nVlrTotOri, 2 )); 

  -- if lRaise is true then
  --   perform fc_debug('<recibodesconto> JUROS: Desconto '||nPercDescJur||' Valor Com Desconto '||nVlrJur,lRaise, false, false);
  --   perform fc_debug('<recibodesconto> MULTA: Desconto '||nPercDescMul||' Valor Com Desconto '||nVlrMul,lRaise, false, false);
  --   perform fc_debug('<recibodesconto> VALOR: Desconto '||nPercDescCor||' Valor Com Desconto '||nVlrCor,lRaise, false, false);
  --   perform fc_debug('<recibodesconto> ---------------'                                                ,lRaise, false, false);
  --   perform fc_debug('<recibodesconto> TOTAL ANTES DESCONTO: '||nVlrTotOri                             ,lRaise, false, false);
  --   perform fc_debug('<recibodesconto> TOTAL APOS  DESCONTO: '||nVlrTot                                ,lRaise, false, false);
  --   perform fc_debug('<recibodesconto> ---------------'                                                ,lRaise, false, false);
  --   perform fc_debug('<recibodesconto> Percentual de Desconto: '||nPercRetorno                         ,lRaise, false, false);
  --   perform fc_debug('<recibodesconto> '                                                               ,lRaise, false, false);
  --   perform fc_debug('<recibodesconto> Fim do processamento'                                           ,lRaise, false, true);
  -- end if;

  return nPercRetorno;

end;
$$ language 'plpgsql';

SQL;

        $this->execute($sql);
    }

    private function socioPromitenteUp() 
    {
        $sql = 
<<<SQL

create or replace function fc_busca_envolvidos(boolean,integer,char(1),integer) returns setof tp_socio_promitente  as
$$
declare

lPrincipal		  alias for  $1; -- Traz apenas o proprietario principal
iRegra			    alias for  $2; -- Traz a regra configurada na db_config, pardiv ou parjuridico
iTipoOrigem	    alias for  $3; -- Verifica se é "M" Matrícula, "I" Inscrição ou "C" Cgm
iCodOrigem	    alias for  $4; -- Traz o código da Matrícula ou Inscrição

iNumcgm         integer;
iMatricula      integer;
iInscricao      integer;

sNome           varchar(40);

lraise          boolean default false;

sSql            text	  default '';

rSocios         record;
rPromitente     record;
rProprietarios  record;

rtp_promitente  tp_socio_promitente%ROWTYPE;

begin

 if iTipoOrigem = 'I' then

   -- Traz CGM do Issbase

   select z01_numcgm, z01_nome, q02_inscr
     into iNumcgm, sNome, iInscricao
     from issbase
	        inner join cgm  on z01_numcgm = q02_numcgm
	  where q02_inscr = iCodOrigem;

	rtp_promitente.riNumcgm	   := iNumcgm;
	rtp_promitente.rvNome	     := sNome;
	rtp_promitente.riInscr	   := iInscricao;
	rtp_promitente.riTipoEnvol := 4;
	return  next rtp_promitente;

	-- Traz CGM dos Socios
	if iRegra = 1 and lPrincipal = false then

		sSql := 'select z01_nome, z01_numcgm, q02_inscr
			         from issbase
						        inner join socios on q95_cgmpri = q02_numcgm
						        inner join cgm    on z01_numcgm = q95_numcgm
				      where q95_tipo  = 1
                and q02_inscr = '||iCodOrigem;

		for rSocios in execute sSql loop

			rtp_promitente.riNumcgm	   := rSocios.z01_numcgm;
			rtp_promitente.rvNome	     := rSocios.z01_nome;
			rtp_promitente.riInscr	   := rSocios.q02_inscr;
			rtp_promitente.riTipoEnvol := 5;
			return  next rtp_promitente;

		end loop;

	end if;

elsif iTipoOrigem = 'M' then

  if lraise then
	  raise notice 'Regra IPTU: % ',iRegra;
  end if;

  -- Traz CGM do Proprietário e Promitente
  if iRegra = 0 then

	 select z01_numcgm, z01_nome, j01_matric
	   into iNumcgm, sNome, iMatricula
     from cgm
	        inner join iptubase on iptubase.j01_numcgm = cgm.z01_numcgm
	  where j01_matric = iCodOrigem;

	  rtp_promitente.riNumcgm	   := iNumcgm;
	  rtp_promitente.rvNome		   := sNome;
	  rtp_promitente.riMatric	   := iMatricula;
	  rtp_promitente.riTipoEnvol := 1;
	  rtp_promitente.riInscr	   := null;
	  return next rtp_promitente;

      -- Se lPrincipal for true mesmo sendo regra 2 retorna apenas o Proprietário
    if lPrincipal = false then

		 sSql := ' select z01_numcgm, z01_nome, j41_matric
				         from promitente
					            inner join cgm on z01_numcgm = j41_numcgm
					      where j41_matric = '||iCodOrigem||'
             order by j41_tipopro desc';

		 for rPromitente in execute sSql loop

			 rtp_promitente.riNumcgm	  := rPromitente.z01_numcgm;
			 rtp_promitente.rvNome		  := rPromitente.z01_nome;
			 rtp_promitente.riMatric	  := rPromitente.j41_matric;
			 rtp_promitente.riTipoEnvol := 3;
			 return  next rtp_promitente;
		 end loop;

		 sSql := 'select z01_numcgm, z01_nome, j42_matric
				        from propri
				             inner join cgm on z01_numcgm = j42_numcgm
				       where j42_matric = '||iCodOrigem;

		 for rProprietarios in execute sSql loop

		   rtp_promitente.riNumcgm	  := rProprietarios.z01_numcgm;
			 rtp_promitente.rvNome		  := rProprietarios.z01_nome;
			 rtp_promitente.riMatric	  := rProprietarios.j42_matric;
			 rtp_promitente.riTipoEnvol := 2;
		   return  next rtp_promitente;
		 end loop;

	  end if;

   -- Traz CGM do Proprietário
  elsif iRegra = 1 then

	  select z01_numcgm, z01_nome, j01_matric
		  into iNumcgm, sNome, iMatricula
		  from cgm
	         inner join iptubase on iptubase.j01_numcgm = cgm.z01_numcgm
	   where j01_matric = iCodOrigem;

	   rtp_promitente.riNumcgm	  := iNumcgm;
	   rtp_promitente.rvNome	    := sNome;
	   rtp_promitente.riMatric	  := iMatricula;
	   rtp_promitente.riTipoEnvol := 1;
	   rtp_promitente.riInscr	    := null;
	   return  next rtp_promitente;

       -- Se lPrincipal for true retorna outros proprietário
       if lPrincipal = false then

		  sSql := ' select z01_numcgm, z01_nome, j42_matric
					        from propri
					             inner join cgm on z01_numcgm = j42_numcgm
					       where j42_matric = '|| iCodOrigem;

		  for rProprietarios in execute sSql loop

			  rtp_promitente.riNumcgm		 := rProprietarios.z01_numcgm;
			  rtp_promitente.rvNome			 := rProprietarios.z01_nome;
			  rtp_promitente.riMatric		 := rProprietarios.j42_matric;
			  rtp_promitente.riTipoEnvol := 2;
			  return  next rtp_promitente;
		  end loop;

	   end if;

	-- Traz CGM do  Promitente
  elsif iRegra = 2 then

	   sSql := 'select z01_numcgm, z01_nome, j41_matric
				        from promitente
				             inner join cgm on z01_numcgm = j41_numcgm
				       where j41_matric = '||iCodOrigem||' order by j41_tipopro desc ';

				 for rPromitente in execute sSql loop

						rtp_promitente.riNumcgm	   := rPromitente.z01_numcgm;
						rtp_promitente.rvNome	     := rPromitente.z01_nome;
						rtp_promitente.riMatric	   := rPromitente.j41_matric;
						rtp_promitente.riTipoEnvol := 3;
						return  next rtp_promitente;
				 end loop;

       -- Se nao encontrou forca regra = 1
       if not found then
          for rPromitente in select * from fc_busca_envolvidos(lPrincipal, 1, 'M', iCodOrigem) loop

         	  rtp_promitente.riNumcgm		 := rPromitente.riNumcgm;
	  		    rtp_promitente.rvNome			 := rPromitente.rvNome;
			      rtp_promitente.riMatric		 := rPromitente.riMatric;
			      rtp_promitente.riTipoEnvol := 1;
			      return  next rtp_promitente;
          end loop;
       end if;

	end if;

end if;

if iTipoOrigem = 'C' then

   select z01_numcgm, z01_nome
	   into iNumcgm, sNome
	   from cgm
	  where z01_numcgm = iCodOrigem;

	rtp_promitente.riNumcgm		 := iNumcgm;
	rtp_promitente.rvNome		   := sNome;
	rtp_promitente.riMatric		 := null;
	rtp_promitente.riTipoEnvol := 1;
	rtp_promitente.riInscr		 := null;
	return  next rtp_promitente;

end if;

 return;

end;

$$ language 'plpgsql';


/**
* Funcao para retornar Socios ou Promitente
*
* @param iNumpre                        integer  	  Numpre  do debito a ser pesquisado
* @param lPrincipal                     boolean  	  Parametro logico que decide se quer retornar apenas o socio principal ou proprietario quando for regra 2  de IPTU
*	@param iRegraMatric									  integer	  	Regra de IPTU configurada na db_config, pardiv ou parjuridico
*	@param iRegraInscr									  integer	  	Regra de ISS  configurada na db_config, pardiv ou parjuridico
*
* @return riNumcgm                      integer     Numcgm do numpre a ser pesquisado
* @return rvNome                        varchar(40) Nome do contribuinte do numpre a ser pesquisado
* @return riMatric                      integer     Matricula do contribuinte
* @return riInscr                       integer     Inscrição do contribuinte

* @author Felipe Nunes Ribeiro
* @since  06/05/2008
*
*/


create or replace function fc_socio_promitente(integer,boolean,integer,integer) returns setof tp_socio_promitente  as
$$
declare

iNumpre			    alias for  $1; -- Numpre
lPrincipal		  alias for  $2; -- Traz apenas o socio ou proprietario principal
iRegraMatric	  alias for  $3; -- Traz a regra configurada na db_config, pardiv ou parjuridico
iRegraInscr  	  alias for  $4; -- Traz a regra configurada na db_config, pardiv ou parjuridico

iMatric         integer;
iInscr          integer;
iCgm			      integer;
iInstit         integer;

lraise          boolean default true;

rtp_promitente  tp_socio_promitente%ROWTYPE;

begin

iInstit :=  cast(fc_getsession('DB_instit') as integer);

if iInstit  is null then
   raise exception 'ERRO: Instituição %, definida na sessão, é inválida!', iInstit;
end if;

-- Consulta Matrícula ou Inscrição
select distinct
       case when k00_matric is not null
            then j01_numcgm
       else case when k00_inscr is not null
                 then q02_numcgm
                 else k00_numcgm
            end
       end as k00_numcgm,
	   k00_matric,
	   k00_inscr
  into iCgm,
	   iMatric,
	   iInscr
  from arrenumcgm
	     left join arrematric on arrematric.k00_numpre = arrenumcgm.k00_numpre
	     left join arreinscr  on arreinscr.k00_numpre  = arrenumcgm.k00_numpre
	     left join iptubase   on iptubase.j01_matric   = arrematric.k00_matric
	     left join issbase    on issbase.q02_inscr     = arreinscr.k00_inscr
 where arrenumcgm.k00_numpre = iNumpre;

    -- perform fc_debug('Numpre:    ' || coalesce(iNumpre, 0), lRaise);
    -- perform fc_debug('Cgm:       ' || coalesce(iCgm,    0), lRaise);
    -- perform fc_debug('Matricula: ' || coalesce(iMatric, 0), lRaise);
    -- perform fc_debug('Inscricao: ' || coalesce(iInscr,  0), lRaise);

		if iInscr is not null then
			for rtp_promitente  in select * from fc_busca_envolvidos(lPrincipal,iRegraInscr,'I',iInscr)
				loop
					return next rtp_promitente;
				end loop;
			return;
		end if;

		if iMatric is not null then
			for rtp_promitente  in select * from fc_busca_envolvidos(lPrincipal,iRegraMatric,'M',iMatric)
				loop
					return next rtp_promitente;
				end loop;
			return;
		end if;

		-- Caso não tenha Matrícula ou Inscrição retorna CGM do arrenumcgm
		if iMatric is null and iInscr is null then
			for rtp_promitente  in select * from fc_busca_envolvidos(lPrincipal,null,'C',iCgm)
				loop
          -- perform fc_debug('Cgm: ' || iCgm , lRaise);
					return next rtp_promitente;
				end loop;
			return;
		end if;

	return;

end;

$$ language 'plpgsql';

SQL;

        $this->execute($sql);
    }

    private function reciboDown() 
    {
        $sql = 
<<<SQL

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

BEGIN

  lRaise := ( case when fc_getsession('DB_debugon') is null then false else true end );
  if lRaise is true then
    if fc_getsession('db_debug') <> '' then
      perform fc_debug('<recibo> Inicio do processamento do recibo...', lRaise, false, false);
    else
      perform fc_debug('<recibo> Inicio do processamento do recibo...', lRaise, true, false);
    end if;
  end if;

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

  if lRaise is true then
    perform fc_debug('<recibo> Numpre ...............:'||NUMPRE,  lRaise, false, false);
    perform fc_debug('<recibo> Data de Emissao ......:'||DTEMITE, lRaise, false, false);
    perform fc_debug('<recibo> Data de Vencimento ...:'||DTVENC,  lRaise, false, false);
    perform fc_debug('<recibo> AnoUsu ...............:'||ANOUSU,  lRaise, false, false);
  end if;

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

  if lRaise is true then
    perform fc_debug('<recibo>'                                 ,lRaise, false, false);
    perform fc_debug('<recibo> Receita para Juro:'||RECEITA_JUR ,lRaise, false, false);
    perform fc_debug('<recibo> Receita para Multa:'||RECEITA_MUL,lRaise, false, false);
    perform fc_debug('<recibo>'                                 ,lRaise, false, false);
  end if;

  perform k00_numpre
  from recibo
  where k00_numnov = numpre LIMIT 1;
  if found then

    rtp_recibo.rvMensagem    := '4 - Erro ao gerar recibo. Contate suporte!';
    rtp_recibo.rlErro        := true;

    if lRaise is true then
      perform fc_debug('<recibo> Encontrados registros do numpre na tabela recibo'           , lRaise, false, false);
      perform fc_debug('<recibo> '                                                           , lRaise, false, false);
      perform fc_debug('<recibo> 5 - Fim do processamento - Retorno: '||rtp_recibo.rvMensagem, lRaise, false, false);
      perform fc_debug('<recibo> '                                                           , lRaise, false, true);
    end if;

    return  rtp_recibo;

  end if;

  perform 1
  from db_reciboweb
  where k99_numpre_n = numpre limit 1;
  if not found then

    rtp_recibo.rvMensagem    := '2 - Erro ao gerar recibo. Contate suporte!';
    rtp_recibo.rlErro        := true;

    if lRaise is true then
      perform fc_debug('<recibo> Não encontrados registros do numpre na tabela db_reciboweb' , lRaise, false, false);
      perform fc_debug('<recibo> '                                                           , lRaise, false, false);
      perform fc_debug('<recibo> 2 - Fim do processamento - Retorno: '||rtp_recibo.rvMensagem, lRaise, false, false);
      perform fc_debug('<recibo> '                                                           , lRaise, false, true);
    end if;

    return  rtp_recibo;

  end if;

  if lRaise is true then
    perform fc_debug('<recibo> Encontrados registros do numpre '||NUMPRE||' na tabela db_reciboweb, processando...',lRaise, false, false);
  end if;
  FOR RECORD_NUMPRE IN SELECT *
                       FROM DB_RECIBOWEB
                       WHERE K99_NUMPRE_N = NUMPRE
  LOOP

    CODBCO = RECORD_NUMPRE.K99_CODBCO;
    CODAGE = RECORD_NUMPRE.K99_CODAGE;
    --    NUMBCO = RECORD_NUMPRE.K99_NUMBCO;

    if lRaise is true then
      perform fc_debug('<recibo> '                                                           , lRaise, false, false);
      perform fc_debug('<recibo> -- Processando funcao fc_numbcoconvenio...'                 , lRaise, false, false);
    end if;
    select fc_numbcoconvenio(NUMBCO::integer) into NUMBCO;
    if lRaise is true then
      perform fc_debug('<recibo> Numbco : '||NUMBCO,lRaise, false, false);
      perform fc_debug('<recibo> -- Fim do processamento da funcao fc_numbcoconvenio...'     , lRaise, false, false);
      perform fc_debug('<recibo> '                                                           , lRaise, false, false);
    end if;

    TEM_DESCONTO = RECORD_NUMPRE.K99_DESCONTO;
    if lRaise is true then
      perform fc_debug('<recibo> TEM_DESCONTO: '||TEM_DESCONTO, lRaise, false, false);
    end if;

    if lRaise is true then
      perform fc_debug('<recibo> '                                                                  , lRaise, false, false);
      perform fc_debug('<recibo> '||lpad('',100,'-')                                                , lRaise, false, false);
      perform fc_debug('<recibo> 1 Buscando dados na tabela arrecad pelo Numpre '||RECORD_NUMPRE.K99_NUMPRE||' Parcela '||RECORD_NUMPRE.K99_NUMPAR||'...', lRaise, false, false);
    end if;

    FOR RECORD_UNICA IN SELECT DISTINCT
                          K00_NUMPRE,
                          K00_NUMPAR
                        FROM ARRECAD
                        WHERE K00_NUMPRE = RECORD_NUMPRE.K99_NUMPRE
                              AND CASE
                                  WHEN RECORD_NUMPRE.K99_NUMPAR = 0 THEN
                                    TRUE
                                  ELSE
                                    K00_NUMPAR = RECORD_NUMPRE.K99_NUMPAR
                                  END
    LOOP

      if lRaise is true then
        perform fc_debug('<recibo> Encontrou dados, Processa = true'                                  , lRaise, false, false);
        perform fc_debug('<recibo> Nnumpre: '||RECORD_NUMPRE.K99_NUMPRE||' - Numpar: '||RECORD_NUMPRE.K99_NUMPAR||' - processa: '||PROCESSA,lRaise, false, false);
      end if;
      PROCESSA := TRUE;

      IF RECORD_NUMPRE.K99_NUMPAR = 0 THEN
        UNICA := TRUE;

      ELSE
        IF RECORD_NUMPRE.K99_NUMPAR != RECORD_UNICA.K00_NUMPAR THEN
          if lRaise is true then
            perform fc_debug('<recibo> Parcela ('||RECORD_NUMPRE.K99_NUMPAR||') da tabela db_reciboweb diferente da parcela ('||RECORD_UNICA.K00_NUMPAR||') do arrecad', lRaise, false, false);
          end if;
          PROCESSA := FALSE;
        END IF;

      END IF;

      NUMPAR := RECORD_UNICA.K00_NUMPAR;

      IF PROCESSA = TRUE THEN

        if lRaise is true then
          perform fc_debug('<recibo> 2 Buscando dados na tabela arrecad pelo Numpre '||RECORD_NUMPRE.K99_NUMPRE||' Parcela '||NUMPAR||'...', lRaise, false, false);
        end if;

        FOR RECORD_ALIAS IN
        SELECT K00_RECEIT,
          K00_DTOPER,
          ( select rinumcgm from fc_socio_promitente(K00_NUMPRE, true, iRegraIptu, iRegraIss) limit 1 )as K00_NUMCGM,
          fc_calculavenci(k00_numpre,k00_numpar,K00_DTVENC,DTEMITE) AS K00_DTVENC,
          K00_NUMPRE,
          K00_NUMPAR,
          min(K00_hist) as K00_hist,
          (select sum(k00_valor)
           from arrecad as a
           where a.k00_numpre = arrecad.k00_numpre
                 and a.k00_numpar = arrecad.k00_numpar
                 and a.k00_receit = arrecad.k00_receit
                 and a.k00_tipo   = arrecad.k00_tipo ) as k00_valor,
          K00_TIPO
        FROM ARRECAD
        WHERE K00_NUMPRE = RECORD_NUMPRE.K99_NUMPRE
              AND K00_NUMPAR = NUMPAR
        group by K00_RECEIT,
          K00_DTOPER,
          K00_NUMCGM,
          fc_calculavenci(k00_numpre,k00_numpar,K00_DTVENC,DTEMITE),
          K00_NUMPRE,
          K00_NUMPAR,
          K00_TIPO
        ORDER BY K00_NUMPRE,K00_NUMPAR,K00_RECEIT
        LOOP

          if lRaise is true then

            perform fc_debug('<recibo> '                                                                  , lRaise, false, false);
            perform fc_debug('<recibo> Processando registros do Numpre '||RECORD_ALIAS.K00_NUMPRE||'...'  , lRaise, false, false);
            perform fc_debug('<recibo> Parcela .............:'||RECORD_ALIAS.K00_NUMPAR                   , lRaise, false, false);
            perform fc_debug('<recibo> Receita .............:'||RECORD_ALIAS.K00_RECEIT                   , lRaise, false, false);
            perform fc_debug('<recibo> Tipo ................:'||RECORD_ALIAS.K00_TIPO                     , lRaise, false, false);
            perform fc_debug('<recibo> Data de Operacao ....:'||RECORD_ALIAS.K00_DTOPER                   , lRaise, false, false);
            perform fc_debug('<recibo> Data de Vencimento ..:'||RECORD_ALIAS.K00_DTVENC                   , lRaise, false, false);
            perform fc_debug('<recibo> Valor da Receita ....:'||RECORD_ALIAS.K00_RECEIT                   , lRaise, false, false);
            perform fc_debug('<recibo> '                                                                  , lRaise, false, false);
            perform fc_debug('<recibo> Processa = true...'                                                , lRaise, false, false);

          end if;
          PROCESSA := TRUE;
          RECEITA  := RECORD_ALIAS.K00_RECEIT;
          ARRETIPO := RECORD_ALIAS.K00_TIPO;
          DTOPER   := RECORD_ALIAS.K00_DTOPER;
          NUMCGM   := RECORD_ALIAS.K00_NUMCGM;
          DATAVENC := RECORD_ALIAS.K00_DTVENC;
          VALOR_RECEITA := RECORD_ALIAS.K00_VALOR;

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

              IF QUAL_OPER = 0 THEN
                V_K00_HIST := RECORD_GRAVA.K00_HIST;
                NUMTOT := RECORD_GRAVA.K00_NUMTOT;
                NUMDIG  := RECORD_GRAVA.K00_NUMDIG;
                QUAL_OPER := 1;
              END IF;

            END LOOP;

            -- CALCULA CORRECAO
            if VALOR_RECEITA <> 0 then

              if iFormaCorrecao = 1 then

                VALOR_RECEITA_ORI = VALOR_RECEITA;

                if lRaise is true then
                  perform fc_debug('<recibo> Forma de correcao .......: '||iFormaCorrecao, lRaise, false, false);
                  perform fc_debug('<recibo> VALOR_RECEITA_ORI .......: '||VALOR_RECEITA_ORI, lRaise, false, false);
                  perform fc_debug('<recibo> VALOR_RECEITA ...: '||VALOR_RECEITA, lRaise, false, false);
                  perform fc_debug('<recibo> fc_retornacomposicao('||record_alias.k00_numpre||','||record_alias.k00_numpar||','||record_alias.k00_receit||','||record_alias.k00_hist||','||dtoper||','||dtvenc||','||anousu||','||datavenc||')', lRaise, false, false);
                end if;

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
                from fc_retornacomposicao(record_alias.k00_numpre, record_alias.k00_numpar, record_alias.k00_receit, record_alias.k00_hist, dtoper, dtvenc, anousu, datavenc);

                if lRaise is true then
                  perform fc_debug('<recibo> 1=nComposCorrecao: '||nComposCorrecao||' - VALOR_RECEITA: '||VALOR_RECEITA,lRaise, false,false);
                end if;

                VALOR_RECEITA = VALOR_RECEITA + nComposCorrecao;
                if lRaise is true then
                  perform fc_debug('<recibo> 2=nComposCorrecao: '||nComposCorrecao||' - VALOR_RECEITA: '||VALOR_RECEITA||' - VALOR_RECEITA: '||VALOR_RECEITA,lRaise, false,false);
                  perform fc_debug('<recibo> 1 Chamando a funcao fc_corre...',lRaise, false,false);
                end if;

                CORRECAO := ROUND( FC_CORRE(RECEITA,DTOPER,VALOR_RECEITA,DTVENC,ANOUSU,DATAVENC) , 2 );

                if lRaise is true then
                  perform fc_debug('<recibo> CORRECAO 1: '||CORRECAO,lRaise, false,false);
                end if;

                CORRECAO := ROUND( CORRECAO - VALOR_RECEITA + nComposCorrecao, 2 );

                if lRaise is true then
                  perform fc_debug('<recibo> CORRECAO 2: '||CORRECAO||' - nCorreComposJuros: '||nCorreComposJuros||' - nCorreComposMulta: '||nCorreComposMulta,lRaise, false,false);
                end if;

                CORRECAO := CORRECAO + nCorreComposJuros + nCorreComposMulta;

                if lRaise is true then
                  perform fc_debug('<recibo> VALOR_RECEITA: '||VALOR_RECEITA||' VALOR_RECEITA: '||VALOR_RECEITA||' - CORRECAO 3: '||CORRECAO,lRaise, false,false);
                end if;

                VALOR_RECEITA = VALOR_RECEITA_ORI;

              else

                if lRaise is true then
                  perform fc_debug('<recibo> 2 Chamando a funcao fc_corre...',lRaise, false,false);
                end if;

                CORRECAO := ROUND(FC_CORRE(RECEITA, DTOPER, VALOR_RECEITA, DTVENC, ANOUSU, DATAVENC) - round(VALOR_RECEITA, 2), 2);

                if lRaise is true then
                  perform fc_debug('<recibo> Forma de correcao ..............: '||coalesce(iFormaCorrecao,0), lRaise, false, false);
                  perform fc_debug('<recibo> Receita ........................: '||RECEITA, lRaise, false, false);
                  perform fc_debug('<recibo> DtOper .........................: '||DTOPER, lRaise, false, false);
                  perform fc_debug('<recibo> Valor da receita para calculo ..: '||VALOR_RECEITA, lRaise, false, false);
                  perform fc_debug('<recibo> DtVencto .......................: '||DTVENC, lRaise, false, false);
                  perform fc_debug('<recibo> Ano ............................: '||ANOUSU, lRaise, false, false);
                  perform fc_debug('<recibo> Data para Vencimento ...........: '||DATAVENC, lRaise, false, false);
                  perform fc_debug('<recibo> Correcao .......................: '||CORRECAO, lRaise, false, false);
                end if;

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

              if lRaise is true then

                perform fc_debug('<recibo> '                                              ,lRaise, false, false);
                perform fc_debug('<recibo> Desconto em Regra...'                          ,lRaise, false, false);
                perform fc_debug('<recibo> DTVENC ................:'||DTVENC              ,lRaise, false, false);
                perform fc_debug('<recibo> percdescjur ...........:'||percdescjur         ,lRaise, false, false);
                perform fc_debug('<recibo> percdescmul ...........:'||percdescmul         ,lRaise, false, false);
                perform fc_debug('<recibo> percdescvlr ...........:'||percdescvlr         ,lRaise, false, false);
                perform fc_debug('<recibo> v_cadtipoparc .........:'||v_cadtipoparc       ,lRaise, false, false);
                perform fc_debug('<recibo> v_cadtipoparc_forma ...:'||v_cadtipoparc_forma ,lRaise, false, false);
                perform fc_debug('<recibo> iTipoVlr ..............:'||iTipoVlr            ,lRaise, false, false);
                perform fc_debug('<recibo> numpreValorTotal ......:'||numpreValorTotal    ,lRaise, false, false);
                perform fc_debug('<recibo> TEM_DESCONTO ..........:'||TEM_DESCONTO        ,lRaise, false, false);
              end if;

            end if;

            if lRaise is true then
              perform fc_debug('<recibo> CORRECAO '||receita||'-'||dtoper||'-'||VALOR_RECEITA||'-'||VALOR_RECEITA||'-'||datavenc||'-'||dtvenc, lRaise, false, false);
            end if;

            CORRECAOORI := CORRECAO;
            VALOR_RECEITAORI := VALOR_RECEITA;

            --  Trabalhar neste if para utilizar a mesma logica da recibodesconto
            --   alterar o programa de emissao de recibo para selecionar
            --   a regra se o contribuinte for ou nao loteador

            -- Verificar se a receita possui 'valores adicionais' (Jurídico > Procedimentos > Processo do Foro > Valores Adicionais) lançados a um processo.
            -- Caso possua, deve-se desconsiderar descontos em cima dessa receita.
            -- Obs.: Esse caso se aplica para débitos de inicial.
            perform j150_receita
               from processoforomulta
                    inner join processoforoinicial on j150_processoforo = v71_processoforo
                    inner join inicialnumpre on v71_inicial = v59_inicial
              where j150_receita = RECEITA
                and v59_numpre   = RECORD_ALIAS.K00_NUMPRE;

            if found then
              percdescvlr := 0;
              percdescmul := 0;
              percdescjur := 0;
            end if;

            if percdescvlr is not null and percdescvlr > 0 then

              if iTipoVlr = 1 then

                DESC_CORRECAO := ROUND(CORRECAO * percdescvlr / 100,2);
                if lRaise is true then
                  perform fc_debug('<recibo> desconto na correcao 2: '||CORRECAO||' (-'||DESC_CORRECAO||') - VALOR_RECEITA: '||VALOR_RECEITA||' - VALOR_RECEITA: '||VALOR_RECEITA||' - PERCENTUAL: '||percdescvlr,lRaise, false,false);
                end if;
                if DESC_CORRECAO > 0 then
                  --

                  if lRaise is true then

                    perform fc_debug('<recibo> ', lRaise, false, false);
                    perform fc_debug('<recibo> 01 - inserindo na recibopaga... ', lRaise, false, false);
                    perform fc_debug('<recibo> 01 - Numpre .....: '||RECORD_NUMPRE.K99_NUMPRE, lRaise, false, false);
                    perform fc_debug('<recibo> 01 - Numpar .....: '||NUMPAR, lRaise, false, false);
                    perform fc_debug('<recibo> 01 - Receita ....: '||RECEITA, lRaise, false, false);
                    perform fc_debug('<recibo> 01 - Historico ..:  918', lRaise, false, false);
                    perform fc_debug('<recibo> 01 - Valor ......: '||(DESC_CORRECAO*-1), lRaise, false, false);
                    perform fc_debug('<recibo> ', lRaise, false, false);

                  end if;

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
                end if;
              elsif iTipoVlr = 2 then
                nDescontoCorrigido := ROUND((VALOR_RECEITA + CORRECAO) * percdescvlr / 100,2);
                if lRaise is true then
                  perform fc_debug('<recibo> desconto na correcao 2: '||CORRECAO||' (-'||DESC_CORRECAO||') - VALOR_RECEITA: '||VALOR_RECEITA||' - VALOR_RECEITA: '||VALOR_RECEITA||' - PERCENTUAL: '||percdescvlr,lRaise, false,false);
                end if;
                if nDescontoCorrigido > 0 then
                  --
                  if lRaise is true then

                    perform fc_debug('<recibo> ', lRaise, false, false);
                    perform fc_debug('<recibo> 02 - inserindo na recibopaga... ', lRaise, false, false);
                    perform fc_debug('<recibo> 02 - Numpre .....: '||RECORD_NUMPRE.K99_NUMPRE, lRaise, false, false);
                    perform fc_debug('<recibo> 02 - Numpar .....: '||NUMPAR, lRaise, false, false);
                    perform fc_debug('<recibo> 02 - Receita ....: '||RECEITA, lRaise, false, false);
                    perform fc_debug('<recibo> 02 - Historico ..:  918', lRaise, false, false);
                    perform fc_debug('<recibo> 02 - Valor ......: '||(nDescontoCorrigido*-1), lRaise, false, false);
                    perform fc_debug('<recibo> ', lRaise, false, false);

                  end if;

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
                  if lRaise is true then
                    perform fc_debug('<recibo> desconto (3) - DESC_VALOR_RECEITA: '||DESC_VALOR_RECEITA,lRaise, false,false);
                  end if;
                  --
                  if lRaise is true then

                    perform fc_debug('<recibo> ', lRaise, false, false);
                    perform fc_debug('<recibo> 03 - inserindo na recibopaga... ', lRaise, false, false);
                    perform fc_debug('<recibo> 03 - Numpre .....: '||RECORD_NUMPRE.K99_NUMPRE, lRaise, false, false);
                    perform fc_debug('<recibo> 03 - Numpar .....: '||NUMPAR, lRaise, false, false);
                    perform fc_debug('<recibo> 03 - Receita ....: '||RECEITA, lRaise, false, false);
                    perform fc_debug('<recibo> 03 - Historico ..:  918', lRaise, false, false);
                    perform fc_debug('<recibo> 03 - Valor ......: '||(DESC_VALOR_RECEITA*-1), lRaise, false, false);
                    perform fc_debug('<recibo> ', lRaise, false, false);

                  end if;

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

              if lRaise is true then
                perform fc_debug('<recibo> desconto na correcao 2: '||CORRECAO||' - VALOR_RECEITA: '||VALOR_RECEITA||' - VALOR_RECEITA: '||VALOR_RECEITA,lRaise, false,false);
              end if;

            end if;

            /**
             * final na manutencao
             *
             */
            if lRaise is true then
              perform fc_debug('<recibo> '                                                                     , lRaise, false, false);
              perform fc_debug('<recibo> - juro ....................: '||JURO||' - descjur: '||percdescjur     , lRaise, false, false);
              perform fc_debug('<recibo> - multa ...................: '||MULTA||' - descmul: '||percdescmul    , lRaise, false, false);
              perform fc_debug('<recibo> - correcao ................: '||CORRECAO||' - descvlr: '||percdescvlr , lRaise, false, false);
              perform fc_debug('<recibo> - VALOR_RECEITA ...........: '||VALOR_RECEITA                         , lRaise, false, false);
              perform fc_debug('<recibo> - VALOR_RECEITA ...: '||VALOR_RECEITA                 , lRaise, false, false);
              perform fc_debug('<recibo> - cadtipoparc: '||coalesce(v_cadtipoparc::varchar, 'NULL')            , lRaise, false, false);
              perform fc_debug('<recibo> '                                                                     , lRaise, false, false);
            end if;

            -- T24879: Se valor diferente de zero ou tipo recibo for da emissao geral do iss
            -- gera recibopaga normalmente
            IF (VALOR_RECEITA + CORRECAO) <> 0 OR RECORD_NUMPRE.K99_TIPO = 6 THEN

              if lRaise is true then

                perform fc_debug('<recibo> ', lRaise, false, false);
                perform fc_debug('<recibo> 04 - inserindo na recibopaga... ', lRaise, false, false);
                perform fc_debug('<recibo> 04 - Numpre .....: '||RECORD_NUMPRE.K99_NUMPRE, lRaise, false, false);
                perform fc_debug('<recibo> 04 - Numpar .....: '||NUMPAR, lRaise, false, false);
                perform fc_debug('<recibo> 04 - Receita ....: '||RECEITA, lRaise, false, false);
                perform fc_debug('<recibo> 04 - Historico ..: '||V_K00_HIST + 100, lRaise, false, false);
                perform fc_debug('<recibo> 04 - Valor ......: '||ROUND(VALOR_RECEITA+CORRECAO,2), lRaise, false, false);
                perform fc_debug('<recibo> ', lRaise, false, false);

              end if;

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

                -- Verifica desconto
                nPercArreDesconto := fc_recibodesconto(RECORD_NUMPRE.K99_NUMPRE,
                                                       NUMPAR,
                                                       NUMTOT,
                                                       RECEITA,
                                                       ARRETIPO,
                                                       DTEMITE,
                                                       fc_proximo_dia_util(DATAVENC));
                if nPercArreDesconto > 0 then

                  if lRaise is true then
                    perform fc_debug('<recibo> desconto (4) - nPercArreDesconto: '||nPercArreDesconto,lRaise, false,false);
                  end if;

                  if lRaise is true then

                    perform fc_debug('<recibo> ', lRaise, false, false);
                    perform fc_debug('<recibo> 05 - inserindo na recibopaga... ', lRaise, false, false);
                    perform fc_debug('<recibo> 05 - Numpre .....: '||RECORD_NUMPRE.K99_NUMPRE, lRaise, false, false);
                    perform fc_debug('<recibo> 05 - Numpar .....: '||NUMPAR, lRaise, false, false);
                    perform fc_debug('<recibo> 05 - Receita ....: '||RECEITA, lRaise, false, false);
                    perform fc_debug('<recibo> 05 - Historico ..: 918', lRaise, false, false);
                    perform fc_debug('<recibo> 05 - Valor ......: '||ROUND(((ROUND(VALOR_RECEITA+CORRECAO,2) * nPercArreDesconto)/100),2) * -1, lRaise, false, false);
                    perform fc_debug('<recibo> ', lRaise, false, false);

                  end if;

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

                end if;
              end if;

            END IF;

            IF (VALOR_RECEITAORI + CORRECAOORI) <> 0 THEN

              -- CALCULA JUROS
              if lRaise is true then
                perform fc_debug('<recibo> VALOR_RECEITAORI: '||VALOR_RECEITAORI,lRaise, false,false);
              end if;

              if iFormaCorrecao = 1 then
                JURO := ROUND((VALOR_RECEITAORI + CORRECAO) * FC_JUROS(RECEITA, DATAVENC, DTEMITE, DTOPER, FALSE, ANOUSU), 2);
              else
                JURO := ROUND((CORRECAOORI + VALOR_RECEITAORI) * FC_JUROS(RECEITA, DATAVENC, DTEMITE, DTOPER, FALSE, ANOUSU), 2);
              end if;

              if lRaise is true then
                perform fc_debug('<recibo> JURO: '||JURO||' - nComposJuros: '||nComposJuros||' - valor para calcular juros: 1: '||CORRECAOORI||' - 2: '||VALOR_RECEITAORI,lRaise, false,false);
              end if;

              JURO = JURO + nComposJuros;

              -- CALCULA MULTA
              if iFormaCorrecao = 1 then
                MULTA := round((VALOR_RECEITAORI + CORRECAO)::numeric(15, 2) * FC_MULTA(RECEITA, DATAVENC, DTEMITE, DTOPER, ANOUSU)::numeric(15, 5), 2);
              else
                MULTA := ROUND((CORRECAOORI + VALOR_RECEITAORI)::numeric(15, 2) * FC_MULTA(RECEITA, DATAVENC, DTEMITE, DTOPER, ANOUSU)::numeric(15, 5), 2);
              end if;

              if lRaise is true then
                perform fc_debug('<recibo> MULTA: '||MULTA||' - nComposMulta: '||nComposMulta||' - valor para calcular juros: 1: '||CORRECAOORI||' - 2: '||VALOR_RECEITAORI, lRaise, false,false);
                perform fc_debug('<recibo> CORRECAO: '||CORRECAO, lRaise, false, false);
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

                if lRaise is true then
                  perform fc_debug('<recibo> desconto (5) - vlrjuroparc: '||vlrjuroparc, lRaise, false, false);
                end if;

                if vlrjuroparc > 0 then

                  if lRaise is true then

                    perform fc_debug('<recibo> ', lRaise, false, false);
                    perform fc_debug('<recibo> 06 - inserindo na recibopaga... ', lRaise, false, false);
                    perform fc_debug('<recibo> 06 - Numpre .....: '||RECORD_NUMPRE.K99_NUMPRE, lRaise, false, false);
                    perform fc_debug('<recibo> 06 - Numpar .....: '||NUMPAR, lRaise, false, false);
                    perform fc_debug('<recibo> 06 - Receita ....: '||K03_RECJUR, lRaise, false, false);
                    perform fc_debug('<recibo> 06 - Historico ..: 918', lRaise, false, false);
                    perform fc_debug('<recibo> 06 - Valor ......: '||(vlrjuroparc * -1), lRaise, false, false);
                    perform fc_debug('<recibo> ', lRaise, false, false);

                  end if;


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
                  if lRaise is true then
                    perform fc_debug('<recibo> desconto (6) - vlrmultapar: '||vlrmultapar, lRaise, false, false);
                  end if;

                  if lRaise is true then

                    perform fc_debug('<recibo> ', lRaise, false, false);
                    perform fc_debug('<recibo> 07 - inserindo na recibopaga... ', lRaise, false, false);
                    perform fc_debug('<recibo> 07 - Numpre .....: '||RECORD_NUMPRE.K99_NUMPRE, lRaise, false, false);
                    perform fc_debug('<recibo> 07 - Numpar .....: '||NUMPAR, lRaise, false, false);
                    perform fc_debug('<recibo> 07 - Receita ....: '||K03_RECMUL, lRaise, false, false);
                    perform fc_debug('<recibo> 07 - Historico ..: 918', lRaise, false, false);
                    perform fc_debug('<recibo> 07 - Valor ......: '||(vlrmultapar * -1), lRaise, false, false);
                    perform fc_debug('<recibo> ', lRaise, false, false);

                  end if;

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

              if lRaise is true then
                perform fc_debug('<recibo>    2 - juro: '||JURO||' - descjur: '||percdescjur||' - multa: '||MULTA||' - descmul: '||percdescmul||' - correcao: '||CORRECAO||' - VALOR_RECEITA: '||VALOR_RECEITA, lRaise, false, false);
              end if;

              IF K03_RECJUR = 0 OR K03_RECMUL = 0 OR K03_RECJUR = K03_RECMUL THEN

                IF JURO+MULTA <> 0 THEN

                  VLRJUROS := VLRJUROS + JURO;
                  VLRMULTA := VLRMULTA + MULTA;
                  if lRaise is true then
                    perform fc_debug('<recibo>  valor total juros + multa (7) - '||(JURO+MULTA), lRaise, false, false);
                  end if;

                  if lRaise is true then

                    perform fc_debug('<recibo> ', lRaise, false, false);
                    perform fc_debug('<recibo> 08 - inserindo na recibopaga... ', lRaise, false, false);
                    perform fc_debug('<recibo> 08 - Numpre .....: '||RECORD_NUMPRE.K99_NUMPRE, lRaise, false, false);
                    perform fc_debug('<recibo> 08 - Numpar .....: '||NUMPAR, lRaise, false, false);
                    perform fc_debug('<recibo> 08 - Receita ....: '||K03_RECJUR, lRaise, false, false);
                    perform fc_debug('<recibo> 08 - Historico ..: 400', lRaise, false, false);
                    perform fc_debug('<recibo> 08 - Valor ......: '||ROUND(JURO+MULTA,2), lRaise, false, false);
                    perform fc_debug('<recibo> ', lRaise, false, false);

                  end if;

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

                  if lRaise is true then

                    perform fc_debug('<recibo> ', lRaise, false, false);
                    perform fc_debug('<recibo> 09 - inserindo na recibopaga... ', lRaise, false, false);
                    perform fc_debug('<recibo> 09 - Numpre .....: '||RECORD_NUMPRE.K99_NUMPRE, lRaise, false, false);
                    perform fc_debug('<recibo> 09 - Numpar .....: '||NUMPAR, lRaise, false, false);
                    perform fc_debug('<recibo> 09 - Receita ....: '||K03_RECJUR, lRaise, false, false);
                    perform fc_debug('<recibo> 09 - Historico ..: 400', lRaise, false, false);
                    perform fc_debug('<recibo> 09 - Valor ......: '||ROUND(JURO,2), lRaise, false, false);
                    perform fc_debug('<recibo> ', lRaise, false, false);

                  end if;

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

                  if lRaise is true then

                    perform fc_debug('<recibo> ', lRaise, false, false);
                    perform fc_debug('<recibo> 10 - inserindo na recibopaga... ', lRaise, false, false);
                    perform fc_debug('<recibo> 10 - Numpre .....: '||RECORD_NUMPRE.K99_NUMPRE, lRaise, false, false);
                    perform fc_debug('<recibo> 10 - Numpar .....: '||NUMPAR, lRaise, false, false);
                    perform fc_debug('<recibo> 10 - Receita ....: '||K03_RECMUL, lRaise, false, false);
                    perform fc_debug('<recibo> 10 - Historico ..: 401', lRaise, false, false);
                    perform fc_debug('<recibo> 10 - Valor ......: '||ROUND(MULTA,2), lRaise, false, false);
                    perform fc_debug('<recibo> ', lRaise, false, false);

                  end if;


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

                  if lRaise is true then

                    perform fc_debug('<recibo> desconto (8) - '||DESCONTO, lRaise, false, false);

                    perform fc_debug('<recibo> ', lRaise, false, false);
                    perform fc_debug('<recibo> 11 - inserindo na recibopaga... ', lRaise, false, false);
                    perform fc_debug('<recibo> 11 - Numpre .....: '||RECORD_NUMPRE.K99_NUMPRE, lRaise, false, false);
                    perform fc_debug('<recibo> 11 - Numpar .....: '||NUMPAR, lRaise, false, false);
                    perform fc_debug('<recibo> 11 - Receita ....: '||RECEITA, lRaise, false, false);
                    perform fc_debug('<recibo> 11 - Historico ..: 918', lRaise, false, false);
                    perform fc_debug('<recibo> 11 - Valor ......: '||ROUND(DESCONTO*-1,2), lRaise, false, false);
                    perform fc_debug('<recibo> ', lRaise, false, false);

                  end if;

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

              END IF;

            END IF;

          ELSE

            IF USASISAGUA = FALSE AND RECEITA <> 401002 THEN

              rtp_recibo.rvMensagem    := '1 - Erro ao gerar recibo. Contate suporte!';
              rtp_recibo.rlErro        := true;

              if lRaise is true then
                perform fc_debug('<recibo> '                                                           , lRaise, false, false);
                perform fc_debug('<recibo> 1 - Fim do processamento - Retorno: '||rtp_recibo.rvMensagem, lRaise, false, false);
                perform fc_debug('<recibo> '                                                           , lRaise, false, true);
              end if;
              RETURN rtp_recibo;

            END IF;

          END IF;

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

        END LOOP;

      END IF;

    END LOOP;

  END LOOP;

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

    if lRaise is true then
      perform fc_debug('<recibo> '                                                           , lRaise, false, false);
      perform fc_debug('<recibo> 3 - Fim do processamento - Retorno: '||rtp_recibo.rvMensagem, lRaise, false, false);
      perform fc_debug('<recibo> '                                                           , lRaise, false, true);
    end if;

    RETURN rtp_recibo;

  ELSE

    rtp_recibo.rvMensagem    := '3 - Erro ao gerar recibo. Contate suporte!';
    rtp_recibo.rlErro        := true;

    if lRaise is true then
      perform fc_debug('<recibo> Não encontrados registros na tabela arrecad'                , lRaise, false, false);
      perform fc_debug('<recibo> '                                                           , lRaise, false, false);
      perform fc_debug('<recibo> 4 - Fim do processamento - Retorno: '||rtp_recibo.rvMensagem, lRaise, false, false);
      perform fc_debug('<recibo> '                                                           , lRaise, false, true);
    end if;

    RETURN  rtp_recibo;

  END IF;

END;
$$ language 'plpgsql';

SQL;

        $this->execute($sql);
    }

    private function descontoDown() 
    {
        $sql = 
<<<SQL

--drop function fc_desconto(integer,date,float8,float8,bool,date,integer,integer);
create or replace function fc_desconto(integer,date,float8,float8,bool,date,integer,integer) returns float8 as
$$
declare
  receita              alias for $1;
  v_data_arre          alias for $2;
  valor                alias for $3;
  jurmulta             alias for $4;
  unica                alias for $5;
  v_data_venc          alias for $6;
  subdir               alias for $7;
  numpre               alias for $8;

  z99_carnes           char(10);
  descon               float8  default 0;
  v_tabrec             record;
  v_recibounica        integer;
  v_entroudesctabrecjm boolean default false;
  recunica             record;

  lRaise              boolean default false;
  data_certa          date default null;

  dtDataVencimentoUnica    date;

begin

  lRaise := ( case when fc_getsession('DB_debugon') is null then false else true end );

  if lRaise is true then
    if fc_getsession('db_debug') <> '' then
      perform fc_debug('<desconto> Iniciando processamento', lRaise, false, false);
    else
      perform fc_debug('<desconto> Iniciando processamento', lRaise, true, false);
    end if;
  end if;

  select *
    into v_tabrec
    from tabrec
         inner join tabrecjm  on tabrec.k02_codjm = tabrecjm.k02_codjm
   where k02_codigo = receita;

  if not found then
    return 0;
  end if;

  if unica is true then

    if lRaise is true then
      perform fc_debug('<desconto>  ', lRaise, false, false);
      perform fc_debug('<desconto> acessou unica is true - receita: '||receita||' - v_tabrec: '||v_tabrec.k02_codjm, lRaise, false, false);
      perform fc_debug('<desconto> k02_dtdes4: '||v_tabrec.k02_dtdes4||' - k02_dtdes4: '||v_tabrec.k02_dtdes4||' - k02_dtdes5: '||v_tabrec.k02_dtdes5||', k02_dtdes5: '||v_tabrec.k02_dtdes5, lRaise, false, false);
    end if;

--if v_recibounica = 0 then

    if lRaise is true then
      perform fc_debug('<desconto> v_data_arre: '||v_data_arre||'  - v_data_venc: '||v_data_venc, lRaise, false, false);
    end if;

    if not v_tabrec.k02_dtdes4 isnull and v_data_arre <= v_tabrec.k02_dtdes4 then

      if lRaise is true then
        perform fc_debug('<desconto>    entrou no if 1 do k02_dtdes4...', lRaise, false, false);
      end if;

      if ( not v_tabrec.k02_caldes isnull and v_tabrec.k02_caldes = 't' ) or ( v_tabrec.k02_caldes = 'f' and v_data_venc >= v_data_arre ) then

        descon               := ( v_tabrec.k02_desco4 / 100::float8 );
        v_entroudesctabrecjm := true;

        if lRaise is true then
          perform fc_debug('<desconto>       entrou no if 2 do k02_dtdes4... k02_desco4: '||v_tabrec.k02_desco4||' - descon: '||descon, lRaise, false, false);
        end if;

      end if;

    else
      if not v_tabrec.k02_dtdes5 isnull and v_data_arre <= v_tabrec.k02_dtdes5 then
        if lRaise is true then
          perform fc_debug('<desconto>    entrou no if 3 do k02_dtdes4...', lRaise, false, false);
        end if;
        if ( not v_tabrec.k02_caldes isnull and v_tabrec.k02_caldes = 't' ) or ( v_tabrec.k02_caldes = 'f' and v_data_venc >= v_data_arre ) then
          descon               := ( v_tabrec.k02_desco5 / 100::float8 );
          v_entroudesctabrecjm := true;
          if lRaise is true then
            perform fc_debug('<desconto>       entrou no if 4 do k02_dtdes4... k02_desco4: '||v_tabrec.k02_desco4||' - descon: '||descon, lRaise, false, false);
          end if;
        end if;
      else
        if not v_tabrec.k02_dtdes6 isnull and v_data_arre <= v_tabrec.k02_dtdes6 then
          if lRaise is true then
            perform fc_debug('<desconto>    entrou no if 5 do k02_dtdes4...', lRaise, false, false);
          end if;
          if ( not v_tabrec.k02_caldes isnull and v_tabrec.k02_caldes = 't' ) or ( v_tabrec.k02_caldes = 'f' and v_data_venc >= v_data_arre ) then
            descon               := ( v_tabrec.k02_desco6 / 100::float8 );
            v_entroudesctabrecjm := true;
            if lRaise is true then
              perform fc_debug('<desconto>       entrou no if 6 do k02_dtdes4... k02_desco4: '||v_tabrec.k02_desco4||' - descon: '||descon, lRaise, false, false);
            end if;
          end if;
        end if;
      end if;
    end if;

--          end if;


    if lRaise is true then
      perform fc_debug('<desconto>    v_entroudesctabrecjm: '||v_entroudesctabrecjm, lRaise, false, false);
    end if;

    if v_entroudesctabrecjm is false or (unica is true and v_tabrec.k02_caldes is false) then

      v_recibounica := 0;


      for recunica in

        select k00_dtvenc,
               k00_percdes
          from recibounica
         where recibounica.k00_numpre = numpre
           and v_data_arre <= fc_proximo_dia_util(recibounica.k00_dtvenc)

      loop

        if lRaise is true then
          perform fc_debug('<desconto> Encontrou percentual desconto da Tabela recibounica', lRaise, false, false);
        end if;

        if v_recibounica = 0 and v_data_arre <= fc_proximo_dia_util(recunica.k00_dtvenc) then

          descon        := descon + ( recunica.k00_percdes / 100::float8 );
          v_recibounica := 1;
        end if;

      end loop;

    end if;

  else

    if lRaise is true then
      perform fc_debug('<desconto> -------- NAO E UNICA ------------' , lRaise, false, false);
      perform fc_debug('<desconto> v_tabrec.k02_dtdes1 = ' || coalesce(v_tabrec.k02_dtdes1::text, 'null')::text , lRaise, false, false);
      perform fc_debug('<desconto> v_tabrec.k02_dtdes2 = ' || coalesce(v_tabrec.k02_dtdes2::text, 'null')::text , lRaise, false, false);
      perform fc_debug('<desconto> v_tabrec.k02_caldes = ' || coalesce(v_tabrec.k02_caldes::text, 'null')::text , lRaise, false, false);
      perform fc_debug('<desconto> v_tabrec.k02_dtdes3 = ' || coalesce(v_tabrec.k02_dtdes3::text, 'null')::text , lRaise, false, false);
      perform fc_debug('<desconto> v_tabrec.k02_caldes = ' || coalesce(v_tabrec.k02_caldes::text, 'null')::text , lRaise, false, false);
      perform fc_debug('<desconto> v_data_arre         = ' || coalesce(v_data_arre::text, 'null')::text, lRaise, false, false);
      perform fc_debug('<desconto> v_data_venc         = ' || coalesce(v_data_venc::text, 'null')::text, lRaise, false, false);
    end if;


-------------------


    data_certa := v_data_venc;


    if v_tabrec.k02_sabdom = true then

      if lRaise is true then
        perform fc_debug('<desconto> L O O P   I N I C I O - calculo do proximo dia util' , lRaise, false, false);
        perform fc_debug('<desconto> - data de vencimento: ' || v_data_venc, lRaise, false, false);
      end if;

      loop

        perform k13_data
           from calend
          where k13_data = data_certa;

        if found then
          data_certa := data_certa + 1;
          perform fc_debug('<desconto> data calculada : ' || data_certa, lRaise, false, false);
        else
          exit;
        end if;

      end loop;

      if lRaise is true then
        perform fc_debug('<desconto>', lRaise, false, false);
        perform fc_debug('<desconto> L O O P   FIM', lRaise, false, false);
      end if;

    v_data_venc := data_certa;
    perform fc_debug('<desconto> - nova data de vencimento: ' || v_data_venc, lRaise, false, false);
    end if;






-----------------


    if not v_tabrec.k02_dtdes1 isnull and v_data_arre <= v_tabrec.k02_dtdes1 then

      if lRaise is true then
        perform fc_debug('<desconto> entrou no if 1' , lRaise, false, false);
      end if;

      if (    not v_tabrec.k02_caldes isnull and v_tabrec.k02_caldes = 't' )
           or ( v_tabrec.k02_caldes = 'f' and v_data_venc >= v_data_arre )   then
        descon := ( v_tabrec.k02_desco1 / 100::float8 ) ;

        if lRaise is true then
          perform fc_debug('<desconto> desconto = ' || descon , lRaise, false, false);
        end if;

      end if;

    else

      if not v_tabrec.k02_dtdes2 isnull and v_data_arre <= v_tabrec.k02_dtdes2 then

        if lRaise is true then
          perform fc_debug('<desconto> entrou no if 2' , lRaise, false, false);
        end if;

        if ( not v_tabrec.k02_caldes isnull and v_tabrec.k02_caldes = 't' ) or ( v_tabrec.k02_caldes = 'f' and v_data_venc >= v_data_arre )   then
          descon := ( v_tabrec.k02_desco2 / 100::float8 ) ;

          if lRaise is true then
            perform fc_debug('<desconto> desconto = ' || descon , lRaise, false, false);
          end if;
        end if;

      else

        if not v_tabrec.k02_dtdes3 isnull and v_data_arre <= v_tabrec.k02_dtdes3 then

          if lRaise is true then
            perform fc_debug('<desconto> entrou no if 2' , lRaise, false, false);
          end if;

          if ( not v_tabrec.k02_caldes isnull and v_tabrec.k02_caldes = 't' ) or ( v_tabrec.k02_caldes = 'f' and v_data_venc >= v_data_arre )   then
            descon := ( v_tabrec.k02_desco3 / 100::float8 ) ;

            if lRaise is true then
              perform fc_debug('<desconto> desconto = ' || descon , lRaise, false, false);
            end if;
          end if;
        end if;

      end if;
    end if;

  end if;

  if lRaise is true then
    perform fc_debug('<desconto> descon: '||descon, lRaise, false, false);
  end if;

  if descon <> 0 then
    if not v_tabrec.k02_integr isnull and v_tabrec.k02_integr = 't' then
      descon := round( ( valor + jurmulta ) * descon ,2)::float8 ;
    else
      descon := round( valor * descon ,2)::float8 ;
    end if;
  end if;

  if lRaise is true then
    perform fc_debug('<desconto> Fim do processamento. Retorno: '||round(descon,2), lRaise, false, true);
  end if;
  return round(descon,2);
end;
$$ language 'plpgsql';



SQL;

        $this->execute($sql);
    }

    private function reciboDescontoDown() 
    {
        $sql = 
<<<SQL

create or replace function fc_recibodesconto(integer, integer, integer, integer, integer, date, date) returns float8 as
$$
declare
  iNumpre       alias for $1; -- Numpre do Debito
  iNumpar       alias for $2; -- Numpar do Debito
  iNumtot       alias for $3; -- Total de Parcelas do Debitos
  iReceit       alias for $4; -- Receita do Numpre
  iArreTipo     alias for $5; -- Arretipo do Numpre
  dEmissao      alias for $6; -- Data da Emissao (para calculo)
  dVencimento   alias for $7; -- Data do Vencimento

  iCadTipo      integer;
  iInstitSessao integer;
  iTipoValor    integer;

  rArreDesconto record;

  -- Valores Totais para Calculo
  nVlrHis       numeric(15,2) default 0;
  nVlrCor       numeric(15,2) default 0;
  nVlrJur       numeric(15,2) default 0;
  nVlrMul       numeric(15,2) default 0;
  nVlrDes       numeric(15,2) default 0;
  nVlrTot       numeric(15,2) default 0;
  nVlrTotOri    numeric(15,2) default 0;

  -- Percentual de Desconto
  nPercRetorno  numeric(15,2) default 0;

  -- Percentuais de Descontos da Regra
  nPercDescCor  numeric(15,2) default 0;
  nPercDescJur  numeric(15,2) default 0;
  nPercDescMul  numeric(15,2) default 0;

  sSqlRegra     text   default '';

  lRaise        boolean default false;
begin
  
  lRaise := ( case when fc_getsession('DB_debugon') is null then false else true end );
  
  if lRaise is true then
    if fc_getsession('db_debug') <> '' then  
      perform fc_debug('<recibodesconto> Iniciando processamento...', lRaise, false, false);
    else 
      perform fc_debug('<recibodesconto> Iniciando processamento...', lRaise, true, false);
    end if;
  end if;
  
  if dEmissao > dVencimento then
    if lRaise is true then
      perform fc_debug('<recibodesconto> debito vencido, nao calcula desconto',lRaise, false, false);
      perform fc_debug('<recibodesconto> retorno: '||nPercRetorno,lRaise, false, false);
    end if;
    return nPercRetorno;
  end if;

  iInstitSessao := cast(fc_getsession('DB_instit') as integer);

  -- Busca Arredesconto
  select *
    into rArreDesconto
    from arredesconto
         inner join arreinstit on k00_numpre = k38_numpre
   where k38_numpre = iNumpre
     and k00_instit = iInstitSessao;

  if not found then
    return nPercRetorno;
  end if;

  
  if lRaise is true then
    perform fc_debug('<recibodesconto> Arredesconto '||rArredesconto,lRaise, false, false);
    perform fc_debug('<recibodesconto> ---------------',lRaise, false, false);
  end if;


  -- Busca CadTipo do Debito
  select k03_tipo
    into iCadTipo
    from arretipo
   where k00_tipo   = iArreTipo
     and k00_instit = iInstitSessao;

  if not found then
    return nPercRetorno;
  end if;
  

  if lRaise is true then
    perform fc_debug('<recibodesconto> CadTipo '||iCadTipo,lRaise, false, false);
    perform fc_debug('<recibodesconto> ---------------',lRaise, false, false);
  end if;

  if iCadTipo in (6,13,16,17) then
    if lRaise is true then
      perform fc_debug('<recibodesconto> PARCELAMENTO',lRaise, false, false);
      perform fc_debug('<recibodesconto> ---------------',lRaise, false, false);
    end if;

    select cast(v07_vlrhis as numeric),
           cast(v07_vlrcor - v07_vlrhis as numeric), -- (Valor da Correcao)
           cast(v07_vlrjur as numeric),
           cast(v07_vlrmul as numeric),
           cast(v07_vlrdes as numeric),
           cast(v07_valor  as numeric)
      into nVlrHis,
           nVlrCor,
           nVlrJur,
           nVlrMul,
           nVlrDes,
           nVlrTot
      from termo
     where v07_numpre = iNumpre
       and v07_instit = iInstitSessao;

    if lRaise is true then
      perform fc_debug('<recibodesconto> TERMO: VlrHis '||nVlrHis||' VlrCor '||nVlrCor||' VlrJur '||nVlrJur||' VlrMul '||nVlrMul||' VlrDes '||nVlrDes||' VlrTot '||nVlrTot,lRaise, false, false);
      perform fc_debug('<recibodesconto> ---------------',lRaise, false, false);
    end if;

    -- Salva Montante Original do Parcelamento
    nVlrTotOri := nVlrTot;

  else
    --
    -- No futuro colocaremos a FC_CALCULA para podermos fazer o desconto para qualquer tipo de debito
    --
  end if;

  sSqlRegra := ' select tipovlr,
                        descjur, 
                        descmul, 
                        descvlr
                   from cadtipoparc 
                        inner join tipoparc       on tipoparc.cadtipoparc = cadtipoparc.k40_codigo
                  where k40_codigo     = '|| rArreDesconto.k38_cadtipoparc ||'
                    and k40_aplicacao  = 2                 
                    and k40_instit     = '|| iInstitSessao ||'                 
                    and '||iNumtot||' <= maxparc   
                 -- comentado para dar desconto independente da data fim da regra       
                 -- and '||quote_literal(dEmissao)||' between k40_dtini and k40_dtfim 
                 order by maxparc 
                    limit 1;'; 

  if lRaise is true then
--    perform fc_debug('<recibodesconto> %', sSqlRegra;
--    perform fc_debug('<recibodesconto> ---------------';
  end if;

  -- Buscar Regra de Acordo com a CadTipoParc
  execute sSqlRegra
     into iTipoValor,
          nPercDescJur,
          nPercDescMul,
          nPercDescCor;

  if not found then
    return nPercRetorno;
  end if;

  if lRaise is true then
    perform fc_debug('<recibodesconto> Regra '||rArreDesconto.k38_cadtipoparc||'  DescJur '||nPercDescJur||'  DescMul '||nPercDescMul||'  DescVlr '||nPercDescCor, lRaise, false, false);
    perform fc_debug('<recibodesconto> ---------------',lRaise, false, false);
    perform fc_debug('<recibodesconto> nVlrHis : '||nVlrHis||' nVlrCor : '||nVlrCor,lRaise, false, false);
    perform fc_debug('<recibodesconto> ---------------',lRaise, false, false);
  end if;
  
  --
  -- Se o Tipo de valor for = 2 entao o valor a ser utilizado deve ser o valor corrigido (historio + correcao)
  --   caso contrario deve ser o valor da correcao
  --
  if iTipoValor = 2 then
    nVlrCor := ( nVlrHis + nVlrCor );
    if lRaise then
      perform fc_debug('<recibodesconto> Calculando percentual com valor corrigido (historico + correcao) Valor Encontrado : '||nVlrCor,lRaise, false, false);
      perform fc_debug('<recibodesconto> ---------------',lRaise, false, false);
    end if;
  end if;

  -- Recalcula Valores Deduzindo os Descontos
  nVlrJur := nVlrJur - round( (nVlrJur * nPercDescJur)/100, 2 );
  nVlrMul := nVlrMul - round( (nVlrMul * nPercDescMul)/100, 2 );
  nVlrCor := nVlrCor - round( (nVlrCor * nPercDescCor)/100, 2 );

  -- Refaz valor do montante do parcelamento
  if  iTipoValor = 2 then
    nVlrTot := round( nVlrCor + nVlrJur + nVlrMul - nVlrDes, 2);
  else
    nVlrTot := round( nVlrHis + nVlrCor + nVlrJur + nVlrMul - nVlrDes, 2);
  end if;
  
  if lRaise then
    perform fc_debug('<recibodesconto> nVlrTot : '||nVlrTot,lRaise, false, false);
  end if;

  nPercRetorno := abs(100.00 - round( (nVlrTot * 100) / nVlrTotOri, 2 )); 

  if lRaise is true then
    perform fc_debug('<recibodesconto> JUROS: Desconto '||nPercDescJur||' Valor Com Desconto '||nVlrJur,lRaise, false, false);
    perform fc_debug('<recibodesconto> MULTA: Desconto '||nPercDescMul||' Valor Com Desconto '||nVlrMul,lRaise, false, false);
    perform fc_debug('<recibodesconto> VALOR: Desconto '||nPercDescCor||' Valor Com Desconto '||nVlrCor,lRaise, false, false);
    perform fc_debug('<recibodesconto> ---------------'                                                ,lRaise, false, false);
    perform fc_debug('<recibodesconto> TOTAL ANTES DESCONTO: '||nVlrTotOri                             ,lRaise, false, false);
    perform fc_debug('<recibodesconto> TOTAL APOS  DESCONTO: '||nVlrTot                                ,lRaise, false, false);
    perform fc_debug('<recibodesconto> ---------------'                                                ,lRaise, false, false);
    perform fc_debug('<recibodesconto> Percentual de Desconto: '||nPercRetorno                         ,lRaise, false, false);
    perform fc_debug('<recibodesconto> '                                                               ,lRaise, false, false);
    perform fc_debug('<recibodesconto> Fim do processamento'                                           ,lRaise, false, true);
  end if;

  return nPercRetorno;

end;
$$ language 'plpgsql';

SQL;

        $this->execute($sql);
    }

    private function socioPromitenteDown() 
    {
        $sql = 
<<<SQL

create or replace function fc_busca_envolvidos(boolean,integer,char(1),integer) returns setof tp_socio_promitente  as
$$
declare

lPrincipal		  alias for  $1; -- Traz apenas o proprietario principal
iRegra			    alias for  $2; -- Traz a regra configurada na db_config, pardiv ou parjuridico
iTipoOrigem	    alias for  $3; -- Verifica se é "M" Matrícula, "I" Inscrição ou "C" Cgm
iCodOrigem	    alias for  $4; -- Traz o código da Matrícula ou Inscrição

iNumcgm         integer;
iMatricula      integer;
iInscricao      integer;

sNome           varchar(40);

lraise          boolean default false;

sSql            text	  default '';

rSocios         record;
rPromitente     record;
rProprietarios  record;

rtp_promitente  tp_socio_promitente%ROWTYPE;

begin

 if iTipoOrigem = 'I' then

   -- Traz CGM do Issbase

   select z01_numcgm, z01_nome, q02_inscr
     into iNumcgm, sNome, iInscricao
     from issbase
	        inner join cgm  on z01_numcgm = q02_numcgm
	  where q02_inscr = iCodOrigem;

	rtp_promitente.riNumcgm	   := iNumcgm;
	rtp_promitente.rvNome	     := sNome;
	rtp_promitente.riInscr	   := iInscricao;
	rtp_promitente.riTipoEnvol := 4;
	return  next rtp_promitente;

	-- Traz CGM dos Socios
	if iRegra = 1 and lPrincipal = false then

		sSql := 'select z01_nome, z01_numcgm, q02_inscr
			         from issbase
						        inner join socios on q95_cgmpri = q02_numcgm
						        inner join cgm    on z01_numcgm = q95_numcgm
				      where q95_tipo  = 1
                and q02_inscr = '||iCodOrigem;

		for rSocios in execute sSql loop

			rtp_promitente.riNumcgm	   := rSocios.z01_numcgm;
			rtp_promitente.rvNome	     := rSocios.z01_nome;
			rtp_promitente.riInscr	   := rSocios.q02_inscr;
			rtp_promitente.riTipoEnvol := 5;
			return  next rtp_promitente;

		end loop;

	end if;

elsif iTipoOrigem = 'M' then

  if lraise then
	  raise notice 'Regra IPTU: % ',iRegra;
  end if;

  -- Traz CGM do Proprietário e Promitente
  if iRegra = 0 then

	 select z01_numcgm, z01_nome, j01_matric
	   into iNumcgm, sNome, iMatricula
     from cgm
	        inner join iptubase on iptubase.j01_numcgm = cgm.z01_numcgm
	  where j01_matric = iCodOrigem;

	  rtp_promitente.riNumcgm	   := iNumcgm;
	  rtp_promitente.rvNome		   := sNome;
	  rtp_promitente.riMatric	   := iMatricula;
	  rtp_promitente.riTipoEnvol := 1;
	  rtp_promitente.riInscr	   := null;
	  return next rtp_promitente;

      -- Se lPrincipal for true mesmo sendo regra 2 retorna apenas o Proprietário
    if lPrincipal = false then

		 sSql := ' select z01_numcgm, z01_nome, j41_matric
				         from promitente
					            inner join cgm on z01_numcgm = j41_numcgm
					      where j41_matric = '||iCodOrigem||'
             order by j41_tipopro desc';

		 for rPromitente in execute sSql loop

			 rtp_promitente.riNumcgm	  := rPromitente.z01_numcgm;
			 rtp_promitente.rvNome		  := rPromitente.z01_nome;
			 rtp_promitente.riMatric	  := rPromitente.j41_matric;
			 rtp_promitente.riTipoEnvol := 3;
			 return  next rtp_promitente;
		 end loop;

		 sSql := 'select z01_numcgm, z01_nome, j42_matric
				        from propri
				             inner join cgm on z01_numcgm = j42_numcgm
				       where j42_matric = '||iCodOrigem;

		 for rProprietarios in execute sSql loop

		   rtp_promitente.riNumcgm	  := rProprietarios.z01_numcgm;
			 rtp_promitente.rvNome		  := rProprietarios.z01_nome;
			 rtp_promitente.riMatric	  := rProprietarios.j42_matric;
			 rtp_promitente.riTipoEnvol := 2;
		   return  next rtp_promitente;
		 end loop;

	  end if;

   -- Traz CGM do Proprietário
  elsif iRegra = 1 then

	  select z01_numcgm, z01_nome, j01_matric
		  into iNumcgm, sNome, iMatricula
		  from cgm
	         inner join iptubase on iptubase.j01_numcgm = cgm.z01_numcgm
	   where j01_matric = iCodOrigem;

	   rtp_promitente.riNumcgm	  := iNumcgm;
	   rtp_promitente.rvNome	    := sNome;
	   rtp_promitente.riMatric	  := iMatricula;
	   rtp_promitente.riTipoEnvol := 1;
	   rtp_promitente.riInscr	    := null;
	   return  next rtp_promitente;

       -- Se lPrincipal for true retorna outros proprietário
       if lPrincipal = false then

		  sSql := ' select z01_numcgm, z01_nome, j42_matric
					        from propri
					             inner join cgm on z01_numcgm = j42_numcgm
					       where j42_matric = '|| iCodOrigem;

		  for rProprietarios in execute sSql loop

			  rtp_promitente.riNumcgm		 := rProprietarios.z01_numcgm;
			  rtp_promitente.rvNome			 := rProprietarios.z01_nome;
			  rtp_promitente.riMatric		 := rProprietarios.j42_matric;
			  rtp_promitente.riTipoEnvol := 2;
			  return  next rtp_promitente;
		  end loop;

	   end if;

	-- Traz CGM do  Promitente
  elsif iRegra = 2 then

	   sSql := 'select z01_numcgm, z01_nome, j41_matric
				        from promitente
				             inner join cgm on z01_numcgm = j41_numcgm
				       where j41_matric = '||iCodOrigem||' order by j41_tipopro desc ';

				 for rPromitente in execute sSql loop

						rtp_promitente.riNumcgm	   := rPromitente.z01_numcgm;
						rtp_promitente.rvNome	     := rPromitente.z01_nome;
						rtp_promitente.riMatric	   := rPromitente.j41_matric;
						rtp_promitente.riTipoEnvol := 3;
						return  next rtp_promitente;
				 end loop;

       -- Se nao encontrou forca regra = 1
       if not found then
          for rPromitente in select * from fc_busca_envolvidos(lPrincipal, 1, 'M', iCodOrigem) loop

         	  rtp_promitente.riNumcgm		 := rPromitente.riNumcgm;
	  		    rtp_promitente.rvNome			 := rPromitente.rvNome;
			      rtp_promitente.riMatric		 := rPromitente.riMatric;
			      rtp_promitente.riTipoEnvol := 1;
			      return  next rtp_promitente;
          end loop;
       end if;

	end if;

end if;

if iTipoOrigem = 'C' then

   select z01_numcgm, z01_nome
	   into iNumcgm, sNome
	   from cgm
	  where z01_numcgm = iCodOrigem;

	rtp_promitente.riNumcgm		 := iNumcgm;
	rtp_promitente.rvNome		   := sNome;
	rtp_promitente.riMatric		 := null;
	rtp_promitente.riTipoEnvol := 1;
	rtp_promitente.riInscr		 := null;
	return  next rtp_promitente;

end if;

 return;

end;

$$ language 'plpgsql';


/**
* Funcao para retornar Socios ou Promitente
*
* @param iNumpre                        integer  	  Numpre  do debito a ser pesquisado
* @param lPrincipal                     boolean  	  Parametro logico que decide se quer retornar apenas o socio principal ou proprietario quando for regra 2  de IPTU
*	@param iRegraMatric									  integer	  	Regra de IPTU configurada na db_config, pardiv ou parjuridico
*	@param iRegraInscr									  integer	  	Regra de ISS  configurada na db_config, pardiv ou parjuridico
*
* @return riNumcgm                      integer     Numcgm do numpre a ser pesquisado
* @return rvNome                        varchar(40) Nome do contribuinte do numpre a ser pesquisado
* @return riMatric                      integer     Matricula do contribuinte
* @return riInscr                       integer     Inscrição do contribuinte

* @author Felipe Nunes Ribeiro
* @since  06/05/2008
*
*/


create or replace function fc_socio_promitente(integer,boolean,integer,integer) returns setof tp_socio_promitente  as
$$
declare

iNumpre			    alias for  $1; -- Numpre
lPrincipal		  alias for  $2; -- Traz apenas o socio ou proprietario principal
iRegraMatric	  alias for  $3; -- Traz a regra configurada na db_config, pardiv ou parjuridico
iRegraInscr  	  alias for  $4; -- Traz a regra configurada na db_config, pardiv ou parjuridico

iMatric         integer;
iInscr          integer;
iCgm			      integer;
iInstit         integer;

lraise          boolean default true;

rtp_promitente  tp_socio_promitente%ROWTYPE;

begin

iInstit :=  cast(fc_getsession('DB_instit') as integer);

if iInstit  is null then
   raise exception 'ERRO: Instituição %, definida na sessão, é inválida!', iInstit;
end if;

-- Consulta Matrícula ou Inscrição
select distinct
       case when k00_matric is not null
            then j01_numcgm
       else case when k00_inscr is not null
                 then q02_numcgm
                 else k00_numcgm
            end
       end as k00_numcgm,
	   k00_matric,
	   k00_inscr
  into iCgm,
	   iMatric,
	   iInscr
  from arrenumcgm
	     left join arrematric on arrematric.k00_numpre = arrenumcgm.k00_numpre
	     left join arreinscr  on arreinscr.k00_numpre  = arrenumcgm.k00_numpre
	     left join iptubase   on iptubase.j01_matric   = arrematric.k00_matric
	     left join issbase    on issbase.q02_inscr     = arreinscr.k00_inscr
 where arrenumcgm.k00_numpre = iNumpre;

    perform fc_debug('Numpre:    ' || coalesce(iNumpre, 0), lRaise);
    perform fc_debug('Cgm:       ' || coalesce(iCgm,    0), lRaise);
    perform fc_debug('Matricula: ' || coalesce(iMatric, 0), lRaise);
    perform fc_debug('Inscricao: ' || coalesce(iInscr,  0), lRaise);

		if iInscr is not null then
			for rtp_promitente  in select * from fc_busca_envolvidos(lPrincipal,iRegraInscr,'I',iInscr)
				loop
					return next rtp_promitente;
				end loop;
			return;
		end if;

		if iMatric is not null then
			for rtp_promitente  in select * from fc_busca_envolvidos(lPrincipal,iRegraMatric,'M',iMatric)
				loop
					return next rtp_promitente;
				end loop;
			return;
		end if;

		-- Caso não tenha Matrícula ou Inscrição retorna CGM do arrenumcgm
		if iMatric is null and iInscr is null then
			for rtp_promitente  in select * from fc_busca_envolvidos(lPrincipal,null,'C',iCgm)
				loop
          perform fc_debug('Cgm: ' || iCgm , lRaise);
					return next rtp_promitente;
				end loop;
			return;
		end if;

	return;

end;

$$ language 'plpgsql';

SQL;

        $this->execute($sql);
    }
}
