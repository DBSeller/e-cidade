<?php

use Classes\PostgresMigration;

class M15075ConsultaDescontoUnica extends PostgresMigration
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
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function up()
    {
        $this->execute(<<<SQL
            create or replace function fc_consultadescontounica(integer) returns integer as
            $$
            declare

              -- Parametros
              iNumpre      alias for $1;
              dtPaga       date;
              dtVencRecibo date;
              sSql         text    default '';
              iDesconto    integer default 0;
              lDtIni       boolean default true;
              dtDtAux      date;
              rReciboUnica record;

            begin

              -- Consulta a data de pagamento
              select distinct k00_dtoper
                into dtPaga
                from (
                  select case when rec1.k00_dtoper is null then rec2.k00_dtoper else rec1.k00_dtoper end as k00_dtoper
                    from arrepaga
                         left join arreidret on arreidret.k00_numpre = arrepaga.k00_numpre
                                            and arreidret.k00_numpar = arrepaga.k00_numpar
                         left join disbanco  on disbanco.idret       = arreidret.idret
                         left join recibopaga rec1 on rec1.k00_numnov = disbanco.k00_numpre
                         left join cornump on cornump.k12_numpre = arrepaga.k00_numpre
                                          and cornump.k12_numpar = arrepaga.k00_numpar
                         left join recibopaga rec2 on rec2.k00_numnov = cornump.k12_numnov
                   where arrepaga.k00_numpre = iNumpre
                   order by rec1.k00_dtoper,
                            rec2.k00_dtoper) as x;

              for rReciboUnica in

                select *
                  from recibounica
                 where k00_numpre = iNumpre
                 order by k00_dtvenc asc

              loop

                if lDtIni = true then

                  if dtPaga <= rReciboUnica.k00_dtvenc then
                    iDesconto := rReciboUnica.k00_percdes;
                  end if;

                  lDtIni = false;
                else

                  if dtPaga > dtDtAux and dtPaga <= rReciboUnica.k00_dtvenc then
                    iDesconto := rReciboUnica.k00_percdes;
                  end if;

                end if;

                dtDtAux = rReciboUnica.k00_dtvenc;

                if iDesconto <> 0 then
                  exit;
                end if;

              end loop;

             return iDesconto;

            end;
            $$
            language 'plpgsql';
SQL
        );
    }

    public function down()
    {
        $this->execute(<<<SQL

            create or replace function fc_consultadescontounica(integer) returns integer as
            $$
            declare

              -- Parametros
              iNumpre      alias for $1;
              dtPaga       date;
              dtVencRecibo date;
              sSql         text    default '';
              iDesconto    integer default 0;
              lDtIni       boolean default true;
              dtDtAux      date;
              rReciboUnica record;

            begin

              -- Consulta a data de pagamento
              select recibopaga.k00_dtoper
                into dtPaga
                from arrepaga
                     left join arreidret on arreidret.k00_numpre = arrepaga.k00_numpre
                                        and arreidret.k00_numpar = arrepaga.k00_numpar
                     left join disbanco  on disbanco.idret       = arreidret.idret
                     left join recibopaga on recibopaga.k00_numnov = disbanco.k00_numpre
               where arrepaga.k00_numpre = iNumpre
               order by recibopaga.k00_dtoper;

              for rReciboUnica in

                select *
                  from recibounica
                 where k00_numpre = iNumpre
                 order by k00_dtvenc asc

              loop

                if lDtIni = true then

                  if dtPaga <= rReciboUnica.k00_dtvenc then
                    iDesconto := rReciboUnica.k00_percdes;
                  end if;

                  lDtIni = false;
                else

                  if dtPaga > dtDtAux and dtPaga <= rReciboUnica.k00_dtvenc then
                    iDesconto := rReciboUnica.k00_percdes;
                  end if;

                end if;

                dtDtAux = rReciboUnica.k00_dtvenc;

                if iDesconto <> 0 then
                  exit;
                end if;

              end loop;

             return iDesconto;

            end;
            $$
            language 'plpgsql';

SQL
        );
    }
}
