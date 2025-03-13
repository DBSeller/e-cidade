<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M21116 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::connection()->getPdo()->exec(<<<SQL
        CREATE OR REPLACE FUNCTION public.fc_atualiza_personacgm() RETURNS TRIGGER LANGUAGE plpgsql
        AS $$ DECLARE

        sOperacao varchar DEFAULT lower(TG_OP);

        sSql varchar;

        iCodigoInscricao integer;

        BEGIN

        IF sOperacao IN ('insert') THEN

        INSERT INTO personacgm (p121_cgm, p121_persona)
        VALUES( NEW.q86_numcgm, 3 );

        RETURN NEW;

        ELSEIF sOperacao IN ('delete') THEN

        DELETE
        FROM personacgm
        WHERE p121_cgm = OLD.q86_numcgm
        AND p121_persona = 3;

        RETURN OLD;
        ELSE

        RETURN NEW;

        END IF;

        END;
        $$;
SQL
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::connection()->getPdo()->exec(<<<SQL
     CREATE OR REPLACE FUNCTION fc_atualiza_personacgm()
            RETURNS trigger
            LANGUAGE plpgsql
            AS $$
            declare

            sOperacao varchar default lower(TG_OP);
            sSql varchar;
            iCodigoInscricao integer;

            begin

            if sOperacao in ('insert') then

              insert into personacgm (p121_cgm,p121_persona) values( NEW.q10_numcgm, 3 );

              return new;

            elseif sOperacao in ('delete') then

              delete from personacgm where p121_cgm = OLD.q10_numcgm
                                          and p121_persona     = 3;

              return old;

            else

              return new;

            end if;

            end;
            $$;
SQL
        );
    }
}
