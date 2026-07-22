<?php

use Classes\PostgresMigration;

class M12046AlteracaoSocio extends PostgresMigration
{
    public function up()
    {
        $sql = <<<PARANAUE
create or replace function fc_socios_inclui_arrenumcgm(integer, integer) returns void as
$$
declare

  iCgmIss   alias for $1;
  iCgmSocio alias for $2;

  _q02_numcgm integer;
  _tipo       integer;
  r_socios    record; 
  _teste      integer;

begin

    for r_socios in select k00_numpre
                      from arrenumcgm
		                 where k00_numcgm = iCgmIss 
    loop
           
	    select k00_numcgm
	      into _teste
	      from arrenumcgm 
	     where k00_numcgm = iCgmSocio 
         and k00_numpre = r_socios.k00_numpre;

      if not found then
       insert into arrenumcgm (k00_numcgm, k00_numpre) 
        values(iCgmSocio, r_socios.k00_numpre);   
      end if;
	 
    end loop;
    

end;
$$ language 'plpgsql';

create or replace function fc_socios_numcgm_inc() returns trigger as
$$
declare 

  _tipo      integer ;
    
begin

    -- Busca o parametro de Vinculacao dos Socios na Prefeitura
	select coalesce(db21_regracgmiss,1)
	  into _tipo
	  from db_config 
	 where prefeitura is true;
	
	-- Verifica se eh para Vincular os Socios
	if _tipo = 1 then
	
	   execute fc_socios_inclui_arrenumcgm(new.q95_cgmpri,new.q95_numcgm);
	
    end if;


  return new;
       
end;
$$ language 'plpgsql';

create or replace function fc_socios_numcgm_alt() returns trigger as 
$$
declare 
  _tipo integer;
begin


    -- Busca o parametro de Vinculacao dos Socios na Prefeitura
	select coalesce(db21_regracgmiss,1)
	  into _tipo
	  from db_config
	 where prefeitura is true;

	-- Verifica se eh para Vincular os Socios
	if _tipo = 1 then

	   execute fc_socios_inclui_arrenumcgm(new.q95_cgmpri,new.q95_numcgm);

    end if;

  
  return old;
     
end;
$$ language 'plpgsql';


  DROP TRIGGER "tg_socios_numcgm_alt" ON socios;
CREATE TRIGGER "tg_socios_numcgm_alt" AFTER UPDATE ON "socios" FOR EACH ROW EXECUTE PROCEDURE "fc_socios_numcgm_alt" () ;
PARANAUE;

        $this->execute($sql);
    }

    public function down()
    {
        $sql = <<<PARANAUE
create or replace function fc_socios_numcgm_alt() returns trigger as 
$$
declare 

begin


  if old.q95_tipo <> new.q95_tipo then


    if new.q95_tipo = 1 then

       execute fc_socios_inclui_arrenumcgm(new.q95_cgmpri,new.q95_numcgm);

    else

      perform  fc_socios_exclui_arrenumcgm(old.q95_cgmpri, old.q95_numcgm);

    end if;


  end if;

  
  return old;
     
end;
$$ language 'plpgsql';


  DROP TRIGGER "tg_socios_numcgm_alt" ON socios;
CREATE TRIGGER "tg_socios_numcgm_alt" AFTER UPDATE ON "socios" FOR EACH ROW EXECUTE PROCEDURE "fc_socios_numcgm_alt" () ;

create or replace function fc_socios_inclui_arrenumcgm(integer, integer) returns void as
$$
declare

  iCgmIss   alias for $1;
  iCgmSocio alias for $2;

  _q02_numcgm integer;
  _tipo       integer;
  r_socios    record;
  _teste      integer;

begin

    for r_socios in select k00_numpre
                      from arrenumcgm
		                 where k00_numcgm = iCgmIss
    loop

	    select k00_numcgm
	      into _teste
	      from arrenumcgm
	     where k00_numcgm = iCgmSocio
         and k00_numpre = r_socios.k00_numpre;

      if not found then
       insert into arrenumcgm (k00_numcgm, k00_numpre)
        values(iCgmSocio, r_socios.k00_numpre);
      end if;

    end loop;


end;
$$ language 'plpgsql';

create or replace function fc_socios_numcgm_inc() returns trigger as
$$
declare

  _tipo      integer ;

begin

  if new.q95_tipo = 1 then

    -- Busca o parametro de Vinculacao dos Socios na Prefeitura
	select coalesce(db21_regracgmiss,1)
	  into _tipo
	  from db_config
	 where prefeitura is true;

	-- Verifica se eh para Vincular os Socios
	if _tipo = 1 then

	   execute fc_socios_inclui_arrenumcgm(new.q95_cgmpri,new.q95_numcgm);

    end if;


  end if;

  return new;

end;
$$ language 'plpgsql';
PARANAUE;

        $this->execute($sql);
    }
}
