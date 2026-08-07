<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M21010 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->upFcAtualizaPersonacgmProprietario();
        $this->upFcAtualizaPersonacgmFuncionario();
    }

    public function upFcAtualizaPersonacgmProprietario()
    {
        DB::connection()->getPdo()->exec(<<<SQL
    CREATE OR REPLACE FUNCTION fc_atualiza_personacgm_proprietario()
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
     where p121_cgm = NEW.j01_numcgm
       and p121_persona = 2;

     if iCodigoInscricao is null then

        insert into personacgm (p121_cgm, p121_persona) values ( NEW.j01_numcgm, 2 );

     end if;
     return new;
  elseif sOperacao in ('update') then

     select count(*)
     into iCodigoInscricao
     from iptubase
     where j01_numcgm = old.j01_numcgm;

     if iCodigoInscricao = 0 then

        delete from personacgm where p121_cgm = OLD.j01_numcgm and p121_persona  = 2;
     end if;

     select p121_cgm
     into iCodigoInscricao
     from personacgm
     where p121_cgm = NEW.j01_numcgm
       and p121_persona = 2;

     if iCodigoInscricao is null then

        insert into personacgm (p121_cgm, p121_persona) values( NEW.j01_numcgm, 2 );

     end if;
     return new;

  elseif sOperacao in ('delete') then

     select count(*)
     into iCodigoInscricao
     from iptubase
     where j01_numcgm = old.j01_numcgm;

     if iCodigoInscricao = 0 then

        delete from personacgm where p121_cgm = OLD.j01_numcgm
                                 and p121_persona     = 2;
     end if;
     return old;
  else
     return new;
  end if;

end;
$$;
DROP TRIGGER IF EXISTS tg_atualiza_personacgm_proprietario ON cadastro.iptubase;
create trigger  tg_atualiza_personacgm_proprietario AFTER INSERT OR UPDATE OR DELETE on cadastro.iptubase FOR EACH ROW EXECUTE PROCEDURE fc_atualiza_personacgm_proprietario();
SQL
        );
    }


    public function upFcAtualizaPersonacgmFuncionario()
    {
        DB::connection()->getPdo()->exec(<<<SQL
CREATE OR REPLACE FUNCTION fc_atualiza_personacgm_funcionario()
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
     where p121_cgm = NEW.rh01_numcgm
       and p121_persona = 6;

     if iCodigoInscricao is null then

        insert into personacgm (p121_cgm,p121_persona) values( NEW.rh01_numcgm, 6 );

     end if;
     return new;

  elseif sOperacao in ('update') then

     select count(*)
     into iCodigoInscricao
     from rhpessoal
     where rh01_numcgm = old.rh01_numcgm;

     if iCodigoInscricao = 0 then

        delete from personacgm where p121_cgm = OLD.rh01_numcgm
                                 and p121_persona = 6;
     end if;

     select p121_cgm
     into iCodigoInscricao
     from personacgm
     where p121_cgm = NEW.rh01_numcgm
       and p121_persona = 6;

     if iCodigoInscricao is null then

        insert into personacgm (p121_cgm,p121_persona) values( NEW.rh01_numcgm, 6 );

     end if;
     return new;

  elseif sOperacao in ('delete') then

     select count(*)
     into iCodigoInscricao
     from rhpessoal
     where rh01_numcgm = old.rh01_numcgm;

     if iCodigoInscricao = 1 then

        delete from personacgm where p121_cgm = OLD.rh01_numcgm
                                 and p121_persona = 6;
     end if;
     return old;
  end if;

end;
$$;
DROP TRIGGER IF EXISTS tg_atualiza_personacgm_funcionario ON pessoal.rhpessoal;
create trigger tg_atualiza_personacgm_funcionario AFTER INSERT OR UPDATE OR DELETE on pessoal.rhpessoal FOR EACH ROW EXECUTE PROCEDURE fc_atualiza_personacgm_funcionario();
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
        return true;
    }
}
