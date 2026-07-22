<?php

use Classes\PostgresMigration;

class M18992AjusteSaltesSaldo extends PostgresMigration
{

    public function up()
    {

        $sql = <<<SQL


CREATE OR REPLACE FUNCTION public.fc_saltessaldo(
	integer,
	date,
	date,
	character varying,
	integer)
    RETURNS character varying
    LANGUAGE 'plpgsql'
    COST 100
    VOLATILE PARALLEL UNSAFE
AS \$BODY\$
declare
        conta        alias for $1;
        datainiparam alias for $2;
        datafim      alias for $3;
        ipterm       alias for $4;
	    instit       alias for $5;

        temlanc         boolean default 0;
        rvaloresdia     record;
        gcorrente       record;
        saldo_ant       float8 default 0;
        vlrdeb          float8 default 0;
        vlrcre          float8 default 0;
        saldo_atu       float8 default 0;
        saldo_ini       float8 default 0;
        dtimplantacao   date;
        dtatualizada    date;
        dataini         date;
        dtiniciocalculo date;
        id_term         integer;
        valorzero       float8 default 0;
		v_raise         boolean default false;
begin

   dataini := datainiparam;
   dtiniciocalculo := dataini;
   temlanc := false;
   v_raise := cast(case when fc_getsession('db_debugon') is null then false else true end  as boolean);
   if not ipterm is null then
      select k11_id
      into id_term
      from cfautent
      where k11_ipterm = ipterm;
      if id_term is null then
         return '3';
      end if;
   end if;

   select k13_datvlr,
          k13_dtimplantacao
     into dtatualizada, dtimplantacao
     from saltes
     where k13_conta = conta;

   if dataini <= dtimplantacao then
     dataini := dtimplantacao;
    end if;

   if dataini > dtatualizada  then
     select case when k13_vlratu >= 0 then k13_vlratu else 0 end as debito,
            case when k13_vlratu < 0 then k13_vlratu else 0 end as credito
       into vlrdeb, vlrcre
       from saltes
      where k13_conta = conta;
   else

     select case when k13_saldo >= 0 then k13_saldo else 0 end as debito,
            case when k13_saldo < 0 then k13_saldo else 0 end as credito
       into vlrdeb, vlrcre
       from saltes
      where k13_conta = conta;
   end if;
   -- raise notice '%-%',vlrdeb,vlrcre;
	 vlrdeb := coalesce(vlrdeb, 0);
	 vlrcre := coalesce(vlrcre, 0);
   if dataini <= dtatualizada  then
      select rnsaldoanterior
        into saldo_ant
        from fc_saltessaldoanterior($1, $2, $3, $4,$5);
   else

     select rnsaldoanterior
        into saldo_ant
        from fc_saltessaldoparcial($1, $2, $3, $4,$5);
   end if;

   if saldo_ant is null then
      saldo_ant := 0 ;
   end if;
	 if v_raise is true then
     raise notice '1-vlrdeb: % - vlrcre: % - saldo_ant: %',vlrdeb,vlrcre,saldo_ant;
 	 end if;
	 if vlrcre < 0 then
	   saldo_ant = (vlrcre- vlrdeb) - saldo_ant;
	 else
	   saldo_ant = (vlrdeb - vlrcre) - saldo_ant;
	 end if;
	 if v_raise is true then
     raise notice '2-vlrdeb: % - vlrcre: % - saldo_ant: %',vlrdeb,vlrcre,saldo_ant;
 	 end if;
   /*
     calculamos o valor credito/debito do dia
    */
   vlrdeb := 0;
   vlrcre := 0;
   dtiniciocalculo := dataini;



   for rvaloresdia in
    select sum(case when recebe = 'r' then round(k12_valor,2)::float8 else 0 end) as debito,
           sum(case when recebe = 'p' then round(k12_valor,2)::float8 else 0 end) as credito
    from (


       select 'r' as recebe,round(c.k12_valor,2) as k12_valor
         from corrente c
              inner join corlanc e
	      on c.k12_id = e.k12_id
	         and c.k12_data   = e.k12_data
	         and c.k12_autent = e.k12_autent
		 and e.k12_codigo <> 0
	 where e.k12_conta = conta
	       and c.k12_data between dtiniciocalculo and datafim
	       and case when id_term is not null then c.k12_id = id_term else true end
	       and c.k12_instit = instit

	 union all

	 select 'p' as recebe,round(c.k12_valor,2) as k12_valor
	 from corrente c
	      inner join corlanc e
	      on c.k12_id = e.k12_id
	         and c.k12_data   = e.k12_data
	         and c.k12_autent = e.k12_autent
		 and e.k12_codigo <> 0
	 where c.k12_conta = conta
	       and c.k12_data between dtiniciocalculo and datafim
	       and case when id_term is not null then c.k12_id = id_term else true end
	       and c.k12_instit = instit

   union all


	     select 'r' as recebe,round(c.k12_valor,2) as k12_valor
	           from corrente c
	      inner join corlanc e on c.k12_id = e.k12_id
	             and c.k12_data   = e.k12_data
	             and c.k12_autent = e.k12_autent
         inner join conlancamcorrente  on c86_id  = c.k12_id
                                      and c86_data = c.k12_data
                                      and c86_autent = c.k12_autent
         inner join conlancamdoc on c71_codlan = c86_conlancam
                                and c71_coddoc = 169
	     where c.k12_conta = conta
	       and c.k12_data between dtiniciocalculo and datafim
	       and case when id_term is not null then c.k12_id = id_term else true end
	       and c.k12_instit = instit
           and e.k12_codigo = 0

	 union all

	 select  'p' as recebe,round(c.k12_valor,2) as k12_valor
	 from corrente c
	      inner join coremp e
	          on c.k12_id = e.k12_id
	         and c.k12_data   = e.k12_data
	         and c.k12_autent = e.k12_autent
	 where c.k12_conta = conta
	      and c.k12_data between dtiniciocalculo and datafim
	      and c.k12_valor >= valorzero
	      and case when id_term is not null then c.k12_id = id_term else true end
	      and c.k12_instit = instit

       	 union all

	 select  'r' as recebe,round((c.k12_valor*-1),2) as k12_valor
	 from corrente c
	      inner join coremp e
	      on c.k12_id = e.k12_id
	         and c.k12_data   = e.k12_data
	         and c.k12_autent = e.k12_autent
	 where c.k12_conta = conta
	      and c.k12_data between dtiniciocalculo and datafim
	      and c.k12_valor < valorzero
	      and case when id_term is not null then c.k12_id = id_term else true end
	      and c.k12_instit = instit

     	 union all

	 select recebe,k12_valor
	 from (
	 select distinct c.k12_id,c.k12_data,c.k12_autent,'r'::char(1) as recebe,round(c.k12_valor,2) as k12_valor
	 from corrente c
	      inner join cornump e
	      on c.k12_id = e.k12_id
	         and c.k12_data   = e.k12_data
	         and c.k12_autent = e.k12_autent
	 where c.k12_conta = conta
	       and c.k12_data between dtiniciocalculo and datafim
	       and c.k12_valor >= valorzero
	       and case when id_term is not null then c.k12_id = id_term else true end
	       and c.k12_instit = instit
	 ) as cnr

       	 union all

	 select recebe,k12_valor
	 from (
	 select  distinct c.k12_id,c.k12_data,c.k12_autent,'p'::char(1) as recebe,round((c.k12_valor*-1),2) as k12_valor
	 from corrente c
	      inner join cornump e
	      on c.k12_id = e.k12_id
	         and c.k12_data   = e.k12_data
	         and c.k12_autent = e.k12_autent
	 where c.k12_conta = conta
	       and c.k12_data between dtiniciocalculo and datafim
	       and c.k12_valor < valorzero
	       and case when id_term is not null then c.k12_id = id_term else true end
	       and c.k12_instit = instit
	 ) as cnp


)  as x loop

      temlanc := true;
      if not rvaloresdia.credito is null then
          vlrcre := vlrcre + rvaloresdia.credito;
      end if;
      if not rvaloresdia.debito is null then
         vlrdeb := vlrdeb + rvaloresdia.debito;
      end if;
      saldo_atu := saldo_ant + vlrdeb - vlrcre;
	    if v_raise is true then
        raise notice ' no dia :credito: % - debito: %', rvaloresdia.credito, rvaloresdia.debito;
			end if;
   end loop;

   if temlanc = true or saldo_ant > 0 then
      return '1' || to_char(saldo_ant,'999999999.99') || to_char(vlrdeb,'999999999.99') || to_char(vlrcre,'999999999.99') || to_char(saldo_atu,'999999999.99');
   else
      return '2';
   end if;
end;
\$BODY\$;

ALTER FUNCTION public.fc_saltessaldo(integer, date, date, character varying, integer)
    OWNER TO ecidade;


SQL;



        $this->execute($sql);
    }



    public function down()
    {

        $sql = <<<SQL


CREATE OR REPLACE FUNCTION public.fc_saltessaldo(
	integer,
	date,
	date,
	character varying,
	integer)
    RETURNS character varying
    LANGUAGE 'plpgsql'
    COST 100
    VOLATILE PARALLEL UNSAFE
AS \$BODY\$
declare
        conta        alias for $1;
        datainiparam alias for $2;
        datafim      alias for $3;
        ipterm       alias for $4;
	    instit       alias for $5;

        temlanc         boolean default 0;
        rvaloresdia     record;
        gcorrente       record;
        saldo_ant       float8 default 0;
        vlrdeb          float8 default 0;
        vlrcre          float8 default 0;
        saldo_atu       float8 default 0;
        saldo_ini       float8 default 0;
        dtimplantacao   date;
        dtatualizada    date;
        dataini         date;
        dtiniciocalculo date;
        id_term         integer;
        valorzero       float8 default 0;
		v_raise         boolean default false;
begin

   dataini := datainiparam;
   dtiniciocalculo := dataini;
   temlanc := false;
   v_raise := cast(case when fc_getsession('db_debugon') is null then false else true end  as boolean);
   if not ipterm is null then
      select k11_id
      into id_term
      from cfautent
      where k11_ipterm = ipterm;
      if id_term is null then
         return '3';
      end if;
   end if;

   select k13_datvlr,
          k13_dtimplantacao
     into dtatualizada, dtimplantacao
     from saltes
     where k13_conta = conta;

   if dataini <= dtimplantacao then
     dataini := dtimplantacao;
    end if;

   if dataini > dtatualizada  then
     select case when k13_vlratu >= 0 then k13_vlratu else 0 end as debito,
            case when k13_vlratu < 0 then k13_vlratu else 0 end as credito
       into vlrdeb, vlrcre
       from saltes
      where k13_conta = conta;
   else

     select case when k13_saldo >= 0 then k13_saldo else 0 end as debito,
            case when k13_saldo < 0 then k13_saldo else 0 end as credito
       into vlrdeb, vlrcre
       from saltes
      where k13_conta = conta;
   end if;
   -- raise notice '%-%',vlrdeb,vlrcre;
	 vlrdeb := coalesce(vlrdeb, 0);
	 vlrcre := coalesce(vlrcre, 0);
   if dataini <= dtatualizada  then
      select rnsaldoanterior
        into saldo_ant
        from fc_saltessaldoanterior($1, $2, $3, $4,$5);
   else

     select rnsaldoanterior
        into saldo_ant
        from fc_saltessaldoparcial($1, $2, $3, $4,$5);
   end if;

   if saldo_ant is null then
      saldo_ant := 0 ;
   end if;
	 if v_raise is true then
     raise notice '1-vlrdeb: % - vlrcre: % - saldo_ant: %',vlrdeb,vlrcre,saldo_ant;
 	 end if;
	 if vlrcre < 0 then
	   saldo_ant = (vlrcre- vlrdeb) - saldo_ant;
	 else
	   saldo_ant = (vlrdeb - vlrcre) - saldo_ant;
	 end if;
	 if v_raise is true then
     raise notice '2-vlrdeb: % - vlrcre: % - saldo_ant: %',vlrdeb,vlrcre,saldo_ant;
 	 end if;
   /*
     calculamos o valor credito/debito do dia
    */
   vlrdeb := 0;
   vlrcre := 0;
   dtiniciocalculo := dataini;



   for rvaloresdia in
    select sum(case when recebe = 'r' then round(k12_valor,2)::float8 else 0 end) as debito,
           sum(case when recebe = 'p' then round(k12_valor,2)::float8 else 0 end) as credito
    from (


       select 'r' as recebe,round(c.k12_valor,2) as k12_valor
         from corrente c
              inner join corlanc e
	      on c.k12_id = e.k12_id
	         and c.k12_data   = e.k12_data
	         and c.k12_autent = e.k12_autent
		 and e.k12_codigo <> 0
	 where e.k12_conta = conta
	       and c.k12_data between dtiniciocalculo and datafim
	       and case when id_term is not null then c.k12_id = id_term else true end
	       and c.k12_instit = instit

	 union all

	 select 'p' as recebe,round(c.k12_valor,2) as k12_valor
	 from corrente c
	      inner join corlanc e
	      on c.k12_id = e.k12_id
	         and c.k12_data   = e.k12_data
	         and c.k12_autent = e.k12_autent
		 and e.k12_codigo <> 0
	 where c.k12_conta = conta
	       and c.k12_data between dtiniciocalculo and datafim
	       and case when id_term is not null then c.k12_id = id_term else true end
	       and c.k12_instit = instit

	 union all

	 select  'p' as recebe,round(c.k12_valor,2) as k12_valor
	 from corrente c
	      inner join coremp e
	          on c.k12_id = e.k12_id
	         and c.k12_data   = e.k12_data
	         and c.k12_autent = e.k12_autent
	 where c.k12_conta = conta
	      and c.k12_data between dtiniciocalculo and datafim
	      and c.k12_valor >= valorzero
	      and case when id_term is not null then c.k12_id = id_term else true end
	      and c.k12_instit = instit

       	 union all

	 select  'r' as recebe,round((c.k12_valor*-1),2) as k12_valor
	 from corrente c
	      inner join coremp e
	      on c.k12_id = e.k12_id
	         and c.k12_data   = e.k12_data
	         and c.k12_autent = e.k12_autent
	 where c.k12_conta = conta
	      and c.k12_data between dtiniciocalculo and datafim
	      and c.k12_valor < valorzero
	      and case when id_term is not null then c.k12_id = id_term else true end
	      and c.k12_instit = instit

     	 union all

	 select recebe,k12_valor
	 from (
	 select distinct c.k12_id,c.k12_data,c.k12_autent,'r'::char(1) as recebe,round(c.k12_valor,2) as k12_valor
	 from corrente c
	      inner join cornump e
	      on c.k12_id = e.k12_id
	         and c.k12_data   = e.k12_data
	         and c.k12_autent = e.k12_autent
	 where c.k12_conta = conta
	       and c.k12_data between dtiniciocalculo and datafim
	       and c.k12_valor >= valorzero
	       and case when id_term is not null then c.k12_id = id_term else true end
	       and c.k12_instit = instit
	 ) as cnr

       	 union all

	 select recebe,k12_valor
	 from (
	 select  distinct c.k12_id,c.k12_data,c.k12_autent,'p'::char(1) as recebe,round((c.k12_valor*-1),2) as k12_valor
	 from corrente c
	      inner join cornump e
	      on c.k12_id = e.k12_id
	         and c.k12_data   = e.k12_data
	         and c.k12_autent = e.k12_autent
	 where c.k12_conta = conta
	       and c.k12_data between dtiniciocalculo and datafim
	       and c.k12_valor < valorzero
	       and case when id_term is not null then c.k12_id = id_term else true end
	       and c.k12_instit = instit
	 ) as cnp


)  as x loop

      temlanc := true;
      if not rvaloresdia.credito is null then
          vlrcre := vlrcre + rvaloresdia.credito;
      end if;
      if not rvaloresdia.debito is null then
         vlrdeb := vlrdeb + rvaloresdia.debito;
      end if;
      saldo_atu := saldo_ant + vlrdeb - vlrcre;
	    if v_raise is true then
        raise notice ' no dia :credito: % - debito: %', rvaloresdia.credito, rvaloresdia.debito;
			end if;
   end loop;

   if temlanc = true or saldo_ant > 0 then
      return '1' || to_char(saldo_ant,'999999999.99') || to_char(vlrdeb,'999999999.99') || to_char(vlrcre,'999999999.99') || to_char(saldo_atu,'999999999.99');
   else
      return '2';
   end if;
end;
\$BODY\$;

ALTER FUNCTION public.fc_saltessaldo(integer, date, date, character varying, integer)
    OWNER TO ecidade;



SQL;


        $this->execute($sql);
    }



}
