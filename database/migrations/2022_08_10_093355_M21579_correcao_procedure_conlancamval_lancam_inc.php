<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M21579CorrecaoProcedureConlancamvalLancamInc extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::connection()->getPdo()->exec(<<<sql
    CREATE OR REPLACE FUNCTION public.fc_conlancamval_lancam_inc()
    RETURNS trigger
    LANGUAGE plpgsql
AS $$
    DECLARE

       INSTITD  INTEGER;
       INSTITC  INTEGER;
       DATA             DATE;
       nValorConlancam  float8;

    BEGIN
        INSTITD=0;
        INSTITC=0;

        SELECT C61_INSTIT
        INTO INSTITD
        FROM CONPLANOREDUZ
        WHERE C61_ANOUSU = NEW.C69_ANOUSU AND C61_REDUZ = NEW.C69_DEBITO;

        IF INSTITD=0 THEN

           RAISE EXCEPTION 'CONTA DEBITO (%) NAO ENCONTRADA NA CONPLANOREDUZ.',NEW.C69_DEBITO;

        END IF;

        SELECT C61_INSTIT
        INTO INSTITC
        FROM CONPLANOREDUZ
        WHERE C61_ANOUSU = NEW.C69_ANOUSU AND C61_REDUZ = NEW.C69_CREDITO;

        IF INSTITC=0 THEN

           RAISE EXCEPTION 'CONTA CREDITO (%) NAO ENCONTRADA NA CONPLANOREDUZ.',NEW.C69_CREDITO;

        END IF;

        IF INSTITD != INSTITC THEN

           RAISE EXCEPTION 'CONTA CREDITO (%) E DEBITO (%) NAO PERTENCE A MESMA INSTITUICAO', NEW.C69_CREDITO, NEW.C69_DEBITO;

        END IF;

        SELECT C99_DATA
        INTO DATA
        FROM CONDATACONF
        WHERE C99_ANOUSU = TO_CHAR(NEW.C69_DATA,'YYYY')::INTEGER AND
                C99_INSTIT = INSTITD;

        IF DATA IS NOT NULL AND DATA >= NEW.C69_DATA THEN

            RAISE EXCEPTION 'DATA INVÁLIDA. LIMITE : % ',DATA;

        END IF;

        -- VALIDA DATA, PRA NAO ENTRAR ANOUSU DIFERENTE DO ANO DA DATA INFORMADA

        IF TO_CHAR(NEW.C69_DATA,'YYYY')::INTEGER <> NEW.C69_ANOUSU THEN

            RAISE EXCEPTION 'DATA INVÁLIDA. NÃO CONFERE COM EXERCICIO ! ';

        END IF ;

        -- verifica o valor da conlancam
        select c70_valor
        into nValorConlancam
        from conlancam
        where c70_codlan = new.c69_codlan;

        if ROUND(NEW.C69_VALOR, 2) <> nValorConlancam then

            RAISE EXCEPTION 'VALOR INVALIDO. VALOR DIFERENTE DA CONLANCAM!' ;
        END IF;
        IF NEW.C69_VALOR < 0 THEN

            RAISE EXCEPTION 'VALOR INVALIDO. SOMENTE VALORES POSITIVOS SAO PERMITIDOS !';

        END IF;

        NEW.C69_VALOR = ROUND(NEW.C69_VALOR, 2);
        RETURN NEW;

    END;
$$
sql
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::connection()->getPdo()->exec(<<<sql
     CREATE OR REPLACE FUNCTION public.fc_conlancamval_lancam_inc()
    RETURNS trigger
    LANGUAGE plpgsql
AS $$
    DECLARE

       INSTITD  INTEGER;
       INSTITC  INTEGER;
       DATA             DATE;
       nValorConlancam  float8;

    BEGIN
        INSTITD=0;
        INSTITC=0;

        SELECT C61_INSTIT
        INTO INSTITD
        FROM CONPLANOREDUZ
        WHERE C61_ANOUSU = NEW.C69_ANOUSU AND C61_REDUZ = NEW.C69_DEBITO;

        IF INSTITD=0 THEN

           RAISE EXCEPTION 'CONTA DEBITO (%) NAO ENCONTRADA NA CONPLANOREDUZ.',NEW.C69_DEBITO;

        END IF;

        SELECT C61_INSTIT
        INTO INSTITC
        FROM CONPLANOREDUZ
        WHERE C61_ANOUSU = NEW.C69_ANOUSU AND C61_REDUZ = NEW.C69_CREDITO;

        IF INSTITC=0 THEN

           RAISE EXCEPTION 'CONTA CREDITO (%) NAO ENCONTRADA NA CONPLANOREDUZ.',NEW.C69_CREDITO;

        END IF;

        IF INSTITD != INSTITC THEN

           RAISE EXCEPTION 'CONTA CREDITO (%) E DEBITO (%) NAO PERTENCE A MESMA INSTITUICAO', NEW.C69_CREDITO, NEW.C69_DEBITO;

        END IF;

        SELECT C99_DATA
        INTO DATA
        FROM CONDATACONF
        WHERE C99_ANOUSU = TO_CHAR(NEW.C69_DATA,'YYYY')::INTEGER AND
                C99_INSTIT = INSTITD;

        IF DATA IS NOT NULL AND DATA >= NEW.C69_DATA THEN

            RAISE EXCEPTION 'DATA INVÁLIDA. LIMITE : % ',DATA;

        END IF;

        -- VALIDA DATA, PRA NAO ENTRAR ANOUSU DIFERENTE DO ANO DA DATA INFORMADA

        IF TO_CHAR(NEW.C69_DATA,'YYYY')::INTEGER <> NEW.C69_ANOUSU THEN

            RAISE EXCEPTION 'DATA INVÁLIDA. NÃO CONFERE COM EXERCICIO ! ';

        END IF ;

        -- verifica o valor da conlancam
        select c70_valor
        into nValorConlancam
        from conlancam
        where c70_codlan = new.c69_codlan;

        if NEW.C69_VALOR <> nValorConlancam then

            RAISE EXCEPTION 'VALOR INVALIDO. VALOR DIFERENTE DA CONLANCAM!' ;
        END IF;
        IF NEW.C69_VALOR < 0 THEN

            RAISE EXCEPTION 'VALOR INVALIDO. SOMENTE VALORES POSITIVOS SAO PERMITIDOS !';

        END IF;

        NEW.C69_VALOR = ROUND(NEW.C69_VALOR, 2);
        RETURN NEW;

    END;
$$
sql
        );
    }
}
