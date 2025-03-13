<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;

class M21680PersonaCartorioExtraJudicial extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->upCreatePersona();
        $this->upAtualizarPersonaCgm();
        $this->upTriggerCartorioExtraJudicial();
    }

    public function upCreatePersona()
    {
        DB::connection()->getPdo()->exec(<<<sql
INSERT INTO  persona (p120_sequencial,p120_descricao,p120_objetivo)
VALUES (7,'CARTORIO EXTRA JUDICIAL','CGM vinculado a cartórios extrajudicias');

sql
        );
    }

    public function upAtualizarPersonaCgm()
    {
        DB::connection()->getPdo()->exec(<<<sql
INSERT INTO personacgm (p121_cgm,p121_persona)
SELECT j167_numcgm,7 FROM cartorioextra;
sql
        );
    }

    public function upTriggerCartorioExtraJudicial()
    {

        DB::connection()->getPdo()->exec(<<<sql
 CREATE OR REPLACE FUNCTION fc_atualiza_personacgm_cartorio_extrajudicial()
            RETURNS trigger
            LANGUAGE plpgsql
            AS $$
            declare

            sOperacao varchar default lower(TG_OP);
            iCodigoInscricao integer;

            begin

            if sOperacao in ('insert') then

              select p121_cgm
              into iCodigoInscricao
              from personacgm
              where p121_cgm = NEW.j167_numcgm
                and p121_persona = 7;

              if iCodigoInscricao is null then

                 insert into personacgm (p121_cgm, p121_persona) values ( NEW.j167_numcgm, 7 );

              end if;
              return new;
            elseif sOperacao in ('update') then

              select count(*)
              into iCodigoInscricao
              from cartorioextra
              where j167_numcgm = old.j167_numcgm;

              if iCodigoInscricao = 0 then
                 delete from personacgm where p121_cgm = OLD.j167_numcgm and p121_persona  = 7;
              end if;

              select p121_cgm
              into iCodigoInscricao
              from personacgm
              where p121_cgm = NEW.j167_numcgm
                and p121_persona = 7;

              if iCodigoInscricao is null then

                 insert into personacgm (p121_cgm, p121_persona) values( NEW.j167_numcgm,7 );

              end if;
              return new;

            elseif sOperacao in ('delete') then

              select count(*)
              into iCodigoInscricao
              from cartorioextra
              where j167_numcgm = old.j167_numcgm;

              if iCodigoInscricao = 0 then

                 delete from personacgm where p121_cgm = OLD.j167_numcgm
                                             and p121_persona     = 7;
              end if;
              return old;
            else
              return new;
            end if;

            end;
            $$;

            create trigger  tg_atualiza_personacgm_cartorio_extrajudicial AFTER INSERT OR UPDATE OR DELETE on cartorioextra FOR EACH ROW EXECUTE PROCEDURE fc_atualiza_personacgm_cartorio_extrajudicial();

sql
        );
    }

    public function downDropPersona()
    {
        DB::connection()->getPdo()->exec(<<<sql

DELETE FROM persona where  p120_sequencial = 7;

sql
        );
    }

    public function downDropPersonaCGM()
    {
        DB::connection()->getPdo()->exec(<<<sql

DELETE FROM personacgm where  p121_persona = 7;

sql
        );
    }

    public function downDropTrigger()
    {
        DB::connection()->getPdo()->exec(<<<sql
        DROP TRIGGER IF EXISTS tg_atualiza_personacgm_cartorio_extrajudicial  ON cartorioextra;
        DROP FUNCTION IF EXISTS fc_atualiza_personacgm_cartorio_extrajudicial;
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
        $this->downDropPersonaCGM();
        $this->downDropTrigger();
        $this->downDropPersona();
    }
}
