<?php

use Classes\PostgresMigration;

class M17503AjustaPlFcArrematricArreinscr extends PostgresMigration
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
	public function up()
	{
		$this->execute(<<<SQL

create or replace function fc_arrematric_arreinscr(integer)
returns integer
as
$$

declare

  iNumpre      alias for $1;
  iNumcgm      integer;
  rArreMatric  record;
  rArreInscr   record;
  rSocios      record;
  rIptuBase    record;
  iRegraIptu   integer;
  iRegraIss    integer;
  lMatric      boolean default false;  -- tem matricula
  lInscr       boolean default false;  -- tem matricula

begin

  select coalesce(db21_regracgmiptu,0),coalesce(db21_regracgmiss,1)
    into iRegraIptu, iRegraIss
    from db_config
	 where prefeitura is true;
 --exclui todos os numpres da arrenumcgm;
   delete
     from arrenumcgm
    where k00_numpre = iNumpre;

---- consultando matricula do numpre
/*
 select k00_numpre, k00_matric
   into rArrematric
   from arrematric
  where k00_numpre  = iNumpre;
*/

 FOR rArrematric in ( SELECT distinct
                             arrematric.k00_matric,
                             arrematric.k00_numpre,
                             iptubase.j01_numcgm
                        FROM arrematric
      inner join iptubase ON arrematric.k00_matric = iptubase.j01_matric
                       WHERE arrematric.k00_numpre = iNumpre
                    )

 LOOP
      if rArreMatric.k00_matric is not null then

        ---- arrumando arrenumcgm pela regra do iptu
         if iRegraIptu = 0 then


          select j01_numcgm
            into rIptuBase
            from iptubase
           where j01_matric = rArrematric.k00_matric;

           if not exists(select * from arrenumcgm where k00_numcgm = rIptuBase.j01_numcgm  and k00_numpre = iNumpre) then

                if (rIptuBase.j01_numcgm is null) then

                  raise notice 'Erro  sem CGM % , %', iNumpre, rIptuBase.j01_numcgm;
                end if;
                insert into arrenumcgm (k00_numcgm, k00_numpre) values (rIptuBase.j01_numcgm,iNumpre);
           end if;
            -- Inclui Outros Proprietarios em Arrenumcgm
           perform fc_arrematric_inc_cgmpropri_promit(rArrematric.k00_matric, iNumpre, 0);
            -- Inclui Promitentes em Arrenumcgm
           perform fc_arrematric_inc_cgmpropri_promit(rArrematric.k00_matric, iNumpre, 1);

         elsif iRegraIptu = 1 then

            select j01_numcgm
              into rIptuBase
              from iptubase
             where j01_matric = rArrematric.k00_matric;

           if not exists(select * from arrenumcgm where k00_numcgm = rIptuBase.j01_numcgm  and k00_numpre = iNumpre) then
              insert into arrenumcgm (k00_numcgm, k00_numpre) values (rIptuBase.j01_numcgm,iNumpre);
           end if;
           perform fc_arrematric_inc_cgmpropri_promit(rArrematric.k00_matric, rArrematric.k00_numpre,0);

         elsif iRegraIptu = 2 then

           perform *
                from promitente
             where j41_matric = rArrematric.k00_matric;

         	   if found then

         	     -- Inclui Promitentes em Arrenumcgm
              perform fc_arrematric_inc_cgmpropri_promit(rArrematric.k00_matric, rArrematric.k00_numpre, 1);
         	   else
               select j01_numcgm
                 into rIptuBase
                 from iptubase
                where j01_matric = rArrematric.k00_matric;

           if not exists(select * from arrenumcgm where k00_numcgm = rIptuBase.j01_numcgm  and k00_numpre = iNumpre) then
              insert into arrenumcgm (k00_numcgm, k00_numpre) values (rIptuBase.j01_numcgm,iNumpre);
           end if;
                -- Inclui Outros Proprietarios em Arrenumcgm
              perform fc_arrematric_inc_cgmpropri_promit(rArrematric.k00_matric, rArrematric.k00_numpre, 0);
         	   end if;

        end if; -- fim do if da regra do iptu.
        lMatric := true;
      end if; -- se tem matricula

 END LOOP;

 --- consultando inscricao para o numpre
 select *
   into rArreInscr
   from arreinscr
  where k00_numpre = iNumpre;

 if rArreInscr.k00_inscr is not null then

   select q02_numcgm
     into iNumcgm
     from issbase
   where q02_inscr = rArreinscr.k00_inscr;

  if not exists(select * from arrenumcgm where k00_numcgm = iNumcgm  and k00_numpre = iNumpre) then
    if (iNumcgm is null ) then

        raise notice 'sem cgm (null) numpre - % inscr - %',iNumpre,rArreinscr.k00_inscr;
    end if;
     insert into arrenumcgm (k00_numcgm, k00_numpre) values (iNumcgm, iNumpre);
  end if;
	if iRegraIss = 1 then

			for rSocios in select q95_numcgm
                             from socios
                            where q95_cgmpri = iNumcgm
                              and q95_tipo   = 1
			loop

				perform *
			       from arrenumcgm
				  where k00_numpre = iNumpre
					and k00_numcgm = rSocios.q95_numcgm;

				if not found then
					insert into arrenumcgm (k00_numcgm, k00_numpre) values (rSocios.q95_numcgm, iNumpre);
				end if;

			end loop;

		end if;-- end if regra issqn 1;
    lInscr := true;
 end if; -- fim do if inscricao.

 if lInscr = false and lMatric = false then

   select k00_numcgm
     into iNumcgm
     from arrecad
    where k00_numpre = iNumpre
    limit 1;

	 perform *
 		  from arrenumcgm
		 where k00_numpre = iNumpre
	 		 and k00_numcgm = iNumcgm;

	 if not found then
	   insert into arrenumcgm (k00_numcgm, k00_numpre) values (iNumcgm,iNumpre);
	 end if;
 end if;

 return 1 ;
end;
$$
language 'plpgsql';


SQL
		);
	}

	public function down()
	{
		$this->execute(<<<SQL

create or replace function fc_arrematric_arreinscr(integer)
returns integer
as
$$

declare

  iNumpre      alias for $1;
  iNumcgm      integer;
  rArreMatric  record;
  rArreInscr   record;
  rSocios      record;
  rIptuBase    record;
  iRegraIptu   integer;
  iRegraIss    integer;
  lMatric      boolean default false;  -- tem matricula
  lInscr       boolean default false;  -- tem matricula

begin

  select coalesce(db21_regracgmiptu,0),coalesce(db21_regracgmiss,1)
    into iRegraIptu, iRegraIss
    from db_config
	 where prefeitura is true;
 --exclui todos os numpres da arrenumcgm;
   delete
     from arrenumcgm
    where k00_numpre = iNumpre;

---- consultando matricula do numpre
 select k00_numpre, k00_matric
   into rArrematric
   from arrematric
  where k00_numpre  = iNumpre;

 if rArreMatric.k00_matric is not null then

   ---- arrumando arrenumcgm pela regra do iptu
    if iRegraIptu = 0 then


     select j01_numcgm
       into rIptuBase
       from iptubase
      where j01_matric = rArrematric.k00_matric;

      if not exists(select * from arrenumcgm where k00_numcgm = rIptuBase.j01_numcgm  and k00_numpre = iNumpre) then

           if (rIptuBase.j01_numcgm is null) then

             raise notice 'Erro  sem CGM % , %', iNumpre, rIptuBase.j01_numcgm;
           end if;
           insert into arrenumcgm (k00_numcgm, k00_numpre) values (rIptuBase.j01_numcgm,iNumpre);
      end if;
       -- Inclui Outros Proprietarios em Arrenumcgm
      perform fc_arrematric_inc_cgmpropri_promit(rArrematric.k00_matric, iNumpre, 0);
       -- Inclui Promitentes em Arrenumcgm
      perform fc_arrematric_inc_cgmpropri_promit(rArrematric.k00_matric, iNumpre, 1);

    elsif iRegraIptu = 1 then

       select j01_numcgm
         into rIptuBase
         from iptubase
        where j01_matric = rArrematric.k00_matric;

      if not exists(select * from arrenumcgm where k00_numcgm = rIptuBase.j01_numcgm  and k00_numpre = iNumpre) then
         insert into arrenumcgm (k00_numcgm, k00_numpre) values (rIptuBase.j01_numcgm,iNumpre);
      end if;
      perform fc_arrematric_inc_cgmpropri_promit(rArrematric.k00_matric, rArrematric.k00_numpre,0);

    elsif iRegraIptu = 2 then

      perform *
	       from promitente
        where j41_matric = rArrematric.k00_matric;

		   if found then

		     -- Inclui Promitentes em Arrenumcgm
         perform fc_arrematric_inc_cgmpropri_promit(rArrematric.k00_matric, rArrematric.k00_numpre, 1);
		   else
          select j01_numcgm
            into rIptuBase
            from iptubase
           where j01_matric = rArrematric.k00_matric;

      if not exists(select * from arrenumcgm where k00_numcgm = rIptuBase.j01_numcgm  and k00_numpre = iNumpre) then
         insert into arrenumcgm (k00_numcgm, k00_numpre) values (rIptuBase.j01_numcgm,iNumpre);
      end if;
	       -- Inclui Outros Proprietarios em Arrenumcgm
         perform fc_arrematric_inc_cgmpropri_promit(rArrematric.k00_matric, rArrematric.k00_numpre, 0);
		   end if;

   end if; -- fim do if da regra do iptu.
   lMatric := true;
 end if; -- se tem matricula

 --- consultando inscricao para o numpre
 select *
   into rArreInscr
   from arreinscr
  where k00_numpre = iNumpre;

 if rArreInscr.k00_inscr is not null then

   select q02_numcgm
     into iNumcgm
     from issbase
   where q02_inscr = rArreinscr.k00_inscr;

  if not exists(select * from arrenumcgm where k00_numcgm = iNumcgm  and k00_numpre = iNumpre) then
    if (iNumcgm is null ) then

        raise notice 'sem cgm (null) numpre - % inscr - %',iNumpre,rArreinscr.k00_inscr;
    end if;
     insert into arrenumcgm (k00_numcgm, k00_numpre) values (iNumcgm, iNumpre);
  end if;
	if iRegraIss = 1 then

			for rSocios in select q95_numcgm
                             from socios
                            where q95_cgmpri = iNumcgm
                              and q95_tipo   = 1
			loop

				perform *
			       from arrenumcgm
				  where k00_numpre = iNumpre
					and k00_numcgm = rSocios.q95_numcgm;

				if not found then
					insert into arrenumcgm (k00_numcgm, k00_numpre) values (rSocios.q95_numcgm, iNumpre);
				end if;

			end loop;

		end if;-- end if regra issqn 1;
    lInscr := true;
 end if; -- fim do if inscricao.

 if lInscr = false and lMatric = false then

   select k00_numcgm
     into iNumcgm
     from arrecad
    where k00_numpre = iNumpre
    limit 1;

	 perform *
 		  from arrenumcgm
		 where k00_numpre = iNumpre
	 		 and k00_numcgm = iNumcgm;

	 if not found then
	   insert into arrenumcgm (k00_numcgm, k00_numpre) values (iNumcgm,iNumpre);
	 end if;
 end if;

 return 1 ;
end;
$$
language 'plpgsql';
SQL
		);
	}
}
