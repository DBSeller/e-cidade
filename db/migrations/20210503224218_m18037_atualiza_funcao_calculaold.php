<?php

use Classes\PostgresMigration;

class M18037AtualizaFuncaoCalculaold extends PostgresMigration
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
     *    addCustomColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Any other destructive changes will result in an error when trying to
     * rollback the migration.
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {

	    $sCalculaOld = <<<EOL

create or replace function fc_calculaold(integer,integer,integer,date,date,integer)
   returns varchar as $$

DECLARE
  NUMPRE  ALIAS FOR $1;
  NUMPAR  ALIAS FOR $2;
  CRECEITA ALIAS FOR $3;
  DTEMITE ALIAS FOR $4;
  DTVENC  ALIAS FOR $5;
  SUBDIR  ALIAS FOR $6;

  NUMERO_ERRO   char(1) := '1';    
  RECORD_NUMPRE  RECORD;
  RECORD_ALIAS   RECORD;
  RECORD_GRAVA   RECORD;
  RECORD_NUMPREF RECORD;
  VENC_UNIC      DATE := CURRENT_DATE;
  VENC_UNIC1     DATE;
  VENC_UNIC2     DATE;
  NUM_PAR        INTEGER;

  VALOR_RECEITA FLOAT8;
  CORRECAO      FLOAT8;
  JURO          FLOAT8;
  MULTA         FLOAT8;
  DESCONTO      FLOAT8;
  VALOR_HIST    FLOAT8 := 0;
  RECEITA       INTEGER;
  K03_RECMUL    INTEGER DEFAULT 0;
  K03_RECJUR    INTEGER DEFAULT 0;
  K00_OPERAC    INTEGER;
  K06_OPERAC    INTEGER;
  K09_OPERAC    INTEGER;
  QUALOPERAC    BOOLEAN := FALSE;
  DTOPER        DATE;
  DATAVENC      DATE;
  UNICA         BOOLEAN := FALSE;

  SQLRECIBO     CHAR(255);

  VLRESTORNO FLOAT8:=0;
  VLRCORRECAO   FLOAT8 := 0;
  VLRJUROS      FLOAT8 := 0;
  VLRMULTA      FLOAT8 := 0;
  VLRDESCONTO   FLOAT8 := 0;
        
  CALCULA       BOOLEAN;
  PROCESSA      BOOLEAN := FALSE;
  ISSQNVARIAVEL BOOLEAN := FALSE;
  
  iFormaCorrecao integer default 2;
  iInstit        integer;
  iExerc         integer;
  
  v_composicao    record;

  nComposCorrecao   numeric(15,2) default 0;
  nComposJuros      numeric(15,2) default 0;
  nComposMulta      numeric(15,2) default 0;

  nCorreComposJuros numeric(15,2) default 0;
  nCorreComposMulta numeric(15,2) default 0;

  TOTPERC     FLOAT8 DEFAULT 0;


BEGIN
   
    select cast( fc_getsession('DB_instit') as integer ) 
      into iInstit;

    select cast( fc_getsession('DB_anousu') as integer ) 
      into iExerc;

--    return iExerc; 

    select k03_separajurmulparc
    into iFormaCorrecao
    from numpref
   where k03_instit = iInstit
     and k03_anousu = iExerc;

   FOR RECORD_NUMPREF IN SELECT * FROM NUMPREF WHERE K03_ANOUSU = 2003 LOOP
       K03_RECJUR := RECORD_NUMPREF.K03_RECJUR;
       K03_RECMUL := RECORD_NUMPREF.K03_RECMUL;
   END LOOP;
     
   --  NUMPRE := TO_NUMBER(NUMPRE,'99999999');
   --  NUMPAR := TO_NUMBER(NUMPAR,'999');

   FOR RECORD_NUMPRE IN SELECT * FROM (SELECT DISTINCT K00_NUMPRE,K00_NUMPAR  
                           FROM ARREOLD
                           WHERE K00_NUMPRE = NUMPRE  
                       UNION ALL
                       SELECT DISTINCT RECIBO.K00_NUMPRE,RECIBO.K00_NUMPAR
                       FROM RECIBO
                            LEFT OUTER JOIN ARREPAGA ON RECIBO.K00_NUMPRE = ARREPAGA.K00_NUMPRE
                       WHERE RECIBO.K00_NUMPRE = NUMPRE AND ARREPAGA.K00_NUMPRE IS NULL
                       UNION ALL
                       SELECT DISTINCT K99_NUMPRE_N,1 AS K00_NUMPAR
                       FROM DB_RECIBOWEB
                       WHERE K99_NUMPRE_N = NUMPRE 
                       ) AS X
             ORDER BY K00_NUMPRE,K00_NUMPAR LOOP
    ---- raise notice 'aqui%',numpre;

        IF NUMPAR != 0 THEN
       IF RECORD_NUMPRE.K00_NUMPAR != NUMPAR THEN
          NUM_PAR := 0;
       ELSE
          NUM_PAR := NUMPAR;
       END IF;
    ELSE
       NUM_PAR := RECORD_NUMPRE.K00_NUMPAR;
           UNICA = TRUE;       
    END IF;
    IF NUM_PAR != 0 THEN
    VALOR_RECEITA := 0;
    
    FOR RECORD_ALIAS IN  SELECT * FROM 
                           (SELECT *,'ARREOLD' AS DB_ARQUIVO FROM 
                     (SELECT K00_RECEIT,K00_TIPO,k00_hist,K00_DTOPER,fc_calculavenci(k00_numpre,k00_numpar,K00_DTVENC,DTEMITE) AS K00_DTVENC,K00_NUMPRE,K00_NUMPAR,K00_VALOR AS K00_VALOR 
                      FROM ARREOLD
                      WHERE K00_NUMPRE = NUMPRE AND K00_NUMPAR = NUM_PAR 
                      --GROUP BY K00_RECEIT,K00_TIPO,K00_DTOPER,K00_DTVENC,K00_NUMPRE,K00_NUMPAR
                      ) AS XX
                   UNION ALL
                   SELECT *,'RECIBO' AS DB_ARQUIVO FROM 
                (SELECT K00_RECEIT,K00_TIPO,k00_hist,K00_DTOPER,K00_DTVENC,K00_NUMPRE AS K00_NUMPRE,K00_NUMPAR,K00_VALOR AS K00_VALOR 
                     FROM RECIBO
                     WHERE K00_NUMPRE = NUMPRE AND K00_NUMPAR = NUM_PAR 
                     --GROUP BY K00_RECEIT,K00_TIPO,K00_DTOPER,K00_DTVENC,K00_NUMPRE,K00_NUMPAR
                                ) AS XY
                  UNION ALL
                  SELECT *,'RECUNI' AS DB_ARQUIVO FROM 
                    (SELECT K00_RECEIT,K00_HIST AS K00_TIPO,k00_hist,K00_DTOPER,K00_DTVENC,K00_NUMNOV AS K00_NUMPRE,0 AS K00_NUMPAR,K00_VALOR AS K00_VALOR 
                    FROM RECIBOPAGA
                    WHERE K00_NUMNOV = NUMPRE AND K00_CONTA = 0 
                    --GROUP BY K00_RECEIT,K00_TIPO,K00_DTOPER,K00_DTVENC,K00_NUMNOV,K00_NUMPAR
                                ) AS XZ
                 ) AS X
                 ORDER BY K00_NUMPRE,K00_NUMPAR,K00_RECEIT,k00_hist LOOP

-- raise notice '%',record_alias.db_arquivo;


         IF RECORD_ALIAS.DB_ARQUIVO = 'ARREOLD' THEN

-- raise notice 'ARREOLD';
     
             IF RECORD_ALIAS.K00_VALOR = 0 THEN
            IF UNICA = TRUE THEN
          -- RETURN '6';
         return '6          0.0         0.00         0.00         0.00         0.00                   0.000000';
        ELSE
           IF RECORD_ALIAS.K00_TIPO != 3 THEN
           --   RETURN '7';
            return '7          0.0         0.00         0.00         0.00         0.00                   0.000000';
           END IF;
        END IF;
         END IF;
         CALCULA = FALSE;
         IF ( CRECEITA <> 0 AND CRECEITA = RECORD_ALIAS.K00_RECEIT ) OR CRECEITA = 0 THEN 
        CALCULA = TRUE;
         END IF;
         IF CALCULA = TRUE THEN
        VENC_UNIC := DTVENC;
        RECEITA  := RECORD_ALIAS.K00_RECEIT;
        DTOPER   := RECORD_ALIAS.K00_DTOPER;
        DATAVENC := RECORD_ALIAS.K00_DTVENC;
        VALOR_RECEITA := RECORD_ALIAS.K00_VALOR;
        IF VALOR_RECEITA = 0 THEN
                   SELECT CASE WHEN Q05_VALOR != 0 THEN Q05_VALOR ELSE Q05_VLRINF END  
           INTO VALOR_RECEITA
           FROM ISSVAR WHERE Q05_NUMPRE = RECORD_ALIAS.K00_NUMPRE AND
                             Q05_NUMPAR = RECORD_ALIAS.K00_NUMPAR;
           IF VALOR_RECEITA IS NULL THEN
              VALOR_RECEITA := 0;
           ELSE
              ISSQNVARIAVEL = TRUE;
           END IF;
        END IF;
        VALOR_HIST := VALOR_HIST + VALOR_RECEITA;
        QUALOPERAC := FALSE;
        -- CALCULA CORRECAO 
        PROCESSA := TRUE;
        IF VALOR_RECEITA <> 0 THEN 
        ---- raise notice '%-%-%-%-%-%',RECEITA,DTOPER,VALOR_RECEITA,DTVENC,SUBDIR,DTVENC;
  
        if iFormaCorrecao = 1 then

          select rnCorreComposJuros, rnCorreComposMulta, rnComposCorrecao, rnComposJuros, rnComposMulta
          into nCorreComposJuros, nCorreComposMulta, nComposCorrecao, nComposJuros, nComposMulta
          from fc_retornacomposicao(record_alias.k00_numpre, record_alias.k00_numpar, record_alias.k00_receit, record_alias.k00_hist, dtoper, dtvenc, subdir, datavenc);

          valor_receita = valor_receita + nComposCorrecao;

        end if;

        CORRECAO := FC_CORRE(RECEITA,DTOPER,VALOR_RECEITA,VENC_UNIC,SUBDIR,DATAVENC);

        correcao := correcao + nCorreComposJuros + nCorreComposMulta;

           --IF CORRECAO = -1  THEN
           --   NUMERO_ERRO = '2';
           --   RETURN TRIM(NUMERO_ERRO) || TO_CHAR(VLRCORRECAO,'999999999.99') || TO_CHAR(VLRJUROS,'999999999.99') || TO_CHAR(VLRMULTA,'999999999.99') || TO_CHAR(VLRDESCONTO,'999999999.99') || TO_CHAR(VENC_UNIC,'YYYY-MM-DD');
           --END IF;
           IF CORRECAO = 0 THEN
              --CORRECAO := 0;
              return '9          0.0         0.00         0.00         0.00         0.00                   0.000000';
           END IF;
           CORRECAO := ROUND( CORRECAO - VALOR_RECEITA , 2 );
           ---- raise notice 'ok %',correcao;
         ELSE
            CORRECAO := 0;
         END IF;
     ---- raise notice 'aqui-%',vlrcorrecao;
         VLRCORRECAO := VLRCORRECAO + CORRECAO + VALOR_RECEITA;
     ---- raise notice '--%--%--%',vlrcorrecao,correcao,valor_receita;
         IF (VALOR_RECEITA + CORRECAO) > 0 THEN
            -- CALCULA JUROS
            IF UNICA = FALSE THEN
           JURO  := ROUND(( CORRECAO+VALOR_RECEITA) * FC_JUROS(RECEITA,DATAVENC,DTEMITE,DTOPER,FALSE,SUBDIR)::numeric(20,10) ,2);
           juro = round(juro + nComposJuros,2);
               -- CALCULA MULTA
               MULTA := ROUND( ROUND( CORRECAO+VALOR_RECEITA, 2) * FC_MULTA(RECEITA,DATAVENC,DTEMITE,DTOPER,SUBDIR)::numeric(20,10) ,2);
           multa = round(multa + nComposMulta,2);

               IF K03_RECJUR = 0 OR 
                  K03_RECMUL = 0 OR
                  K03_RECJUR = K03_RECMUL THEN
                  IF JURO+MULTA <> 0 THEN
                 VLRJUROS := VLRJUROS + JURO;
                 VLRMULTA := VLRMULTA + MULTA;
                  END IF;
               ELSE
                  IF JURO <> 0 THEN
                 VLRJUROS := VLRJUROS + JURO;
                  END IF;
                  IF MULTA <> 0 THEN
                 VLRMULTA := VLRMULTA + MULTA;
                  END IF;
                END IF;
            END IF;
                    --CALCULAR DESCONTO
            IF CORRECAO+VALOR_RECEITA <> 0 THEN
               if JURO IS NULL THEN
             JURO = 0;
               end if;
 --            -- raise notice 'desconto === receita: % - venc_unic: % - valor_receita: % - juro: % - unica: % - datavenc: % - subdir: % ',receita,venc_unic,valor_receita,juro,unica,datavenc,subdir;
                       DESCONTO := FC_DESCONTO(RECEITA,VENC_UNIC,CORRECAO+VALOR_RECEITA,JURO+MULTA,UNICA,DATAVENC,SUBDIR, 0);
               IF DESCONTO <> 0 THEN
              VLRDESCONTO := VLRDESCONTO + DESCONTO;
               END IF;  
               ---- raise notice 'desconto %',desconto;
 
             END IF; 
          END IF;
           END IF; 
ELSE
---- raise notice 'recibo';
   IF RECORD_ALIAS.K00_TIPO = 400 THEN
      VLRJUROS := VLRJUROS + RECORD_ALIAS.K00_VALOR;
   ---- raise notice 'jruos %',vlrjuros;
   ELSE
      IF RECORD_ALIAS.K00_TIPO = 401 THEN
         VLRMULTA := VLRMULTA + RECORD_ALIAS.K00_VALOR;
  ---- raise notice 'multa %',vlrmulta;
     ELSE
         VLRCORRECAO := VLRCORRECAO + RECORD_ALIAS.K00_VALOR ;
 --ise notice 'correcao %',vlrcorrecao;
      END IF;
   END IF ;
  PROCESSA := TRUE;
END IF;
          END LOOP;
       END IF;
   END LOOP;
   IF PROCESSA = TRUE THEN
      IF VLRCORRECAO+VLRJUROS+VLRMULTA = 0 THEN
         IF ISSQNVARIAVEL = TRUE THEN
      --  RETURN '8';
        return '8          0.0         0.00         0.00         0.00         0.00                   0.000000';
     ELSE
     --   RETURN '5';
        return '5          0.0         0.00         0.00         0.00         0.00                   0.000000';
     END IF;
      ELSE
         RETURN TRIM(NUMERO_ERRO) || TO_CHAR(VALOR_HIST,'999999999.99')|| TO_CHAR(VLRCORRECAO,'999999999.99') || TO_CHAR(VLRJUROS,'999999999.99') || TO_CHAR(VLRMULTA,'999999999.99') || TO_CHAR(VLRDESCONTO,'999999999.99') || TO_CHAR(VENC_UNIC,'YYYY-MM-DD');
      END IF;
   ELSE
      -- CRIAR SELECT PARA PEGAR VALOR DO ESTORNO VER POSSIBILIDADE
      -- DE LISTAR PELA DATA DE PAGAMENTO
      IF NUMPAR = 0 THEN
         sELECT SUM(K00_VALOR)
         INTO VLRCORRECAO
         FROM ARREPAGA
         WHERE K00_NUMPRE = NUMPRE ;
      ELSE
         SELECT SUM(K00_VALOR)
         INTO VLRCORRECAO
         FROM ARREPAGA
         WHERE K00_NUMPRE = NUMPRE AND K00_NUMPAR = NUMPAR;      
      END IF;
      IF VLRCORRECAO IS NULL THEN
            SELECT SUM(K00_VALOR)
            INTO VLRCORRECAO
           FROM DB_RECIBOWEB,ARREPAGA
           WHERE DB_RECIBOWEB.K99_NUMPRE   = ARREPAGA.K00_NUMPRE AND
                           DB_RECIBOWEB.K99_NUMPAR   = ARREPAGA.K00_NUMPAR AND
          DB_RECIBOWEB.K99_NUMPRE_N = NUMPRE;
           IF VLRCORRECAO IS NULL THEN
                --RETURN '9';
                  return '9          0.0         0.00         0.00         0.00         0.00                   0.000000';
           ELSE

                 SELECT SUM(K00_VALOR)
                 INTO VLRESTORNO
                 FROM RECIBOPAGA
                 WHERE K00_NUMNOV = NUMPRE;
                 IF VLRESTORNO != VLRCORRECAO THEN
                      RETURN '2' || TO_CHAR(VLRCORRECAO,'999999999.99');
                 ELSE
                      RETURN '4' || TO_CHAR(VLRCORRECAO,'999999999.99');
                 END IF;
        
           END IF;
      ELSE
         RETURN '4' || TO_CHAR(VLRCORRECAO,'999999999.99');
      END IF;
   END IF;
   return '9          0.0         0.00         0.00         0.00         0.00                   0.000000';
END;
$$ language 'plpgsql';

ALTER FUNCTION public.fc_calculaold(integer, integer, integer, date, date, integer)
    OWNER TO ecidade;

EOL;
	    $this->execute($sCalculaOld);


    }
}
