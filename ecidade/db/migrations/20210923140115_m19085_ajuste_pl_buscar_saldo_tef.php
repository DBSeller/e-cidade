<?php

use Classes\PostgresMigration;

class M19085AjustePlBuscarSaldoTef extends PostgresMigration
{


    public function up()
    {

        $sql = <<<SQL




create or replace function fc_saltessaldoparcial(integer,date,date,varchar(20),integer)
returns  setof tp_saltessaldo
as
$$
declare

  conta   alias for $1;
  dataini alias for $2;
  datafim alias for $3;
  ipterm  alias for $4;
	instit  alias for $5;

  saldo_ant       float8 default 0;
  vlrdeb          float8 default 0;
  vlrcre          float8 default 0;
  saldo_atu       float8 default 0;
  saldo_ini       float8 default 0;
  dtatualizada    date;
  dtiniciocalculo date;
  id_term         integer;
  valorzero       float8 default 0;
  rtp_saltessaldo   tp_saltessaldo%ROWTYPE;
begin

   if ipterm is not null then
      select k11_id::varchar
      into id_term
      from cfautent
      where k11_ipterm = ipterm;
      if id_term is null then
         rtp_saltessaldo.riestatus = 3;
      end if;
   end if;

   select k13_datvlr
     into dtatualizada
     from saltes
     where k13_conta      = conta;
  rtp_saltessaldo.riconta = conta;
  select sum(round(scol,2))
    into rtp_saltessaldo.rnsaldoanterior
    from
        (select sum(case when recebe = 'p' then round(k12_valor,2)::float8 else 0 end)-
                sum(case when recebe = 'r' then round(k12_valor,2)::float8 else 0 end) as scol
          from (
                select 'r' as recebe,round(c.k12_valor,2) as k12_valor
                  from corrente c
                       inner join corlanc e  on c.k12_id = e.k12_id
                                  	         and c.k12_data   = e.k12_data
                                            and c.k12_autent = e.k12_autent
                                            and e.k12_codigo <> 0
                 where e.k12_conta = conta
                   and c.k12_data between dtatualizada +1 and  dataini-1
                   and case when id_term is not null then c.k12_id = id_term else true end
                   and c.k12_instit = instit

                union all

                select 'p' as recebe,round(c.k12_valor,2) as k12_valor
                  from corrente c
                       inner join corlanc e  on c.k12_id = e.k12_id
                                  	         and c.k12_data   = e.k12_data
	                                           and c.k12_autent = e.k12_autent
                                            and e.k12_codigo <> 0
                 where c.k12_conta = conta
                   and c.k12_data between dtatualizada+1 and dataini-1
                   and case when id_term is not null then c.k12_id = id_term else true end
                   and c.k12_instit = instit

                union all

                select  'p' as recebe,round(c.k12_valor,2) as k12_valor
               	 from corrente c
                       inner join coremp e on c.k12_id = e.k12_id
                                	         and c.k12_data   = e.k12_data
                                          and c.k12_autent = e.k12_autent
                 where c.k12_conta = conta
          	        and c.k12_data between dtatualizada+1 and dataini -1
                   and c.k12_valor >= valorzero
                   and case when id_term is not null then c.k12_id = id_term else true end
                   and c.k12_instit = instit

              	 union all

            	   select  'r' as recebe,round((c.k12_valor*-1),2) as k12_valor
                  from corrente c
                      inner join coremp e  on c.k12_id = e.k12_id
                               	         and c.k12_data   = e.k12_data
                                	         and c.k12_autent = e.k12_autent
              	  where c.k12_conta = conta
                   and c.k12_data between dtatualizada+1 and dataini -1
                   and c.k12_valor < valorzero
                   and case when id_term is not null then c.k12_id = id_term else true end
                   and c.k12_instit = instit


  	              union all

             	 select recebe,k12_valor
               	 from (
                     	 select distinct c.k12_id,
                                         c.k12_data,
                                         c.k12_autent,
                                         'r'::char(1) as recebe,
                                         round(c.k12_valor,2) as k12_valor
                          from corrente c
                       	      inner join cornump e  on c.k12_id = e.k12_id
                                          	         and c.k12_data   = e.k12_data
                                         	         and c.k12_autent = e.k12_autent
                      	  where c.k12_conta = conta
                   	      and c.k12_data between dtatualizada+1 and dataini -1
                	          and c.k12_valor >= valorzero
                           and case when id_term is not null then c.k12_id = id_term else true end
                   	      and c.k12_instit = instit

                      union all


                     	 select distinct c.k12_id,
                                         c.k12_data,
                                         c.k12_autent,
                                         'r'::char(1) as recebe,
                                         round(c.k12_valor,2) as k12_valor
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
                   	      and c.k12_data between dtatualizada+1 and dataini -1
                	          and c.k12_valor >= valorzero
                           and case when id_term is not null then c.k12_id = id_term else true end
                   	      and c.k12_instit = instit


                     	 ) as cnr

	               union all

                select recebe,k12_valor
                  from (
                     	 select distinct c.k12_id,
                                         c.k12_data,
                                         c.k12_autent,
                                         'p'::char(1) as recebe,
                                         round((c.k12_valor*-1),2) as k12_valor
                       	 from corrente c
                        	      inner join cornump e on c.k12_id = e.k12_id
                                         	        and c.k12_data   = e.k12_data
                                        	          and c.k12_autent = e.k12_autent
                         where c.k12_conta = conta
                  	        and c.k12_data between dtatualizada+1 and dataini -1
                  	        and c.k12_valor < valorzero
                           and case when id_term is not null then c.k12_id = id_term else true end
                           and c.k12_instit = instit
                     	 ) as cnp
               ) as x
       ) as y;
 return next rtp_saltessaldo;
 return;
end;
$$
language 'plpgsql';



SQL;

       $this->execute($sql);
    }








    public function down()
    {
        $sql = <<<SQL





create or replace function fc_saltessaldoparcial(integer,date,date,varchar(20),integer)
returns  setof tp_saltessaldo
as
$$
declare

  conta   alias for $1;
  dataini alias for $2;
  datafim alias for $3;
  ipterm  alias for $4;
	instit  alias for $5;

  saldo_ant       float8 default 0;
  vlrdeb          float8 default 0;
  vlrcre          float8 default 0;
  saldo_atu       float8 default 0;
  saldo_ini       float8 default 0;
  dtatualizada    date;
  dtiniciocalculo date;
  id_term         integer;
  valorzero       float8 default 0;
  rtp_saltessaldo   tp_saltessaldo%ROWTYPE;
begin

   if ipterm is not null then
      select k11_id::varchar
      into id_term
      from cfautent
      where k11_ipterm = ipterm;
      if id_term is null then
         rtp_saltessaldo.riestatus = 3;
      end if;
   end if;

   select k13_datvlr
     into dtatualizada
     from saltes
     where k13_conta      = conta;
  rtp_saltessaldo.riconta = conta;
  select sum(round(scol,2))
    into rtp_saltessaldo.rnsaldoanterior
    from
        (select sum(case when recebe = 'p' then round(k12_valor,2)::float8 else 0 end)-
                sum(case when recebe = 'r' then round(k12_valor,2)::float8 else 0 end) as scol
          from (
                select 'r' as recebe,round(c.k12_valor,2) as k12_valor
                  from corrente c
                       inner join corlanc e  on c.k12_id = e.k12_id
                                  	         and c.k12_data   = e.k12_data
                                            and c.k12_autent = e.k12_autent
                                            and e.k12_codigo <> 0
                 where e.k12_conta = conta
                   and c.k12_data between dtatualizada +1 and  dataini-1
                   and case when id_term is not null then c.k12_id = id_term else true end
                   and c.k12_instit = instit

                union all

                select 'p' as recebe,round(c.k12_valor,2) as k12_valor
                  from corrente c
                       inner join corlanc e  on c.k12_id = e.k12_id
                                  	         and c.k12_data   = e.k12_data
	                                           and c.k12_autent = e.k12_autent
                                            and e.k12_codigo <> 0
                 where c.k12_conta = conta
                   and c.k12_data between dtatualizada+1 and dataini-1
                   and case when id_term is not null then c.k12_id = id_term else true end
                   and c.k12_instit = instit

                union all

                select  'p' as recebe,round(c.k12_valor,2) as k12_valor
               	 from corrente c
                       inner join coremp e on c.k12_id = e.k12_id
                                	         and c.k12_data   = e.k12_data
                                          and c.k12_autent = e.k12_autent
                 where c.k12_conta = conta
          	        and c.k12_data between dtatualizada+1 and dataini -1
                   and c.k12_valor >= valorzero
                   and case when id_term is not null then c.k12_id = id_term else true end
                   and c.k12_instit = instit

              	 union all

            	   select  'r' as recebe,round((c.k12_valor*-1),2) as k12_valor
                  from corrente c
                      inner join coremp e  on c.k12_id = e.k12_id
                               	         and c.k12_data   = e.k12_data
                                	         and c.k12_autent = e.k12_autent
              	  where c.k12_conta = conta
                   and c.k12_data between dtatualizada+1 and dataini -1
                   and c.k12_valor < valorzero
                   and case when id_term is not null then c.k12_id = id_term else true end
                   and c.k12_instit = instit

  	              union all

             	 select recebe,k12_valor
               	 from (
                     	 select distinct c.k12_id,
                                         c.k12_data,
                                         c.k12_autent,
                                         'r'::char(1) as recebe,
                                         round(c.k12_valor,2) as k12_valor
                          from corrente c
                       	      inner join cornump e  on c.k12_id = e.k12_id
                                          	         and c.k12_data   = e.k12_data
                                         	         and c.k12_autent = e.k12_autent
                      	  where c.k12_conta = conta
                   	      and c.k12_data between dtatualizada+1 and dataini -1
                	          and c.k12_valor >= valorzero
                           and case when id_term is not null then c.k12_id = id_term else true end
                   	      and c.k12_instit = instit
                     	 ) as cnr

	               union all

                select recebe,k12_valor
                  from (
                     	 select distinct c.k12_id,
                                         c.k12_data,
                                         c.k12_autent,
                                         'p'::char(1) as recebe,
                                         round((c.k12_valor*-1),2) as k12_valor
                       	 from corrente c
                        	      inner join cornump e on c.k12_id = e.k12_id
                                         	        and c.k12_data   = e.k12_data
                                        	          and c.k12_autent = e.k12_autent
                         where c.k12_conta = conta
                  	        and c.k12_data between dtatualizada+1 and dataini -1
                  	        and c.k12_valor < valorzero
                           and case when id_term is not null then c.k12_id = id_term else true end
                           and c.k12_instit = instit
                     	 ) as cnp
               ) as x
       ) as y;
 return next rtp_saltessaldo;
 return;
end;
$$
language 'plpgsql';


SQL;

       $this->execute($sql);
    }

}
