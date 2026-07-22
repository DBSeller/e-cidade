<?php

use Classes\PostgresMigration;

class M18965PlExecucaoDespesa extends PostgresMigration
{
    public function up()
    {
        $this->execute(<<<SQL
drop function if exists fc_execucao_despesa(integer, integer, date, date);
drop function if exists fc_execucao_despesa_recurso(integer, integer, date, date);
SQL
        );

        $this->execute(<<<SQL
create or replace function fc_execucao_despesa(
    f_ano integer,
    f_coddot int,
    f_dataInicio date,
    f_dataFim date
)
    returns table
            (
                empenhado         numeric(17, 2),
                anulado           numeric(17, 2),
                liquidado         numeric(17, 2),
                pago              numeric(17, 2),
                empenhado_liquido numeric(17, 2) -- (empenhado - anulado)
            )
    language plpgsql
as
$$
begin
    return query
        select x.empenhado::numeric(17, 2),
               x.anulado::numeric(17, 2),
               x.liquidado::numeric(17, 2),
               x.pago::numeric(17, 2),
               (x.empenhado - x.anulado)::numeric(17, 2) as empenhado_liquido
        from (
                 select coalesce(sum(case when c53_tipo = 10 then round(c70_valor, 2) else 0::numeric end), 0) as empenhado,
                        coalesce(sum(case when c53_tipo = 11 then round(c70_valor, 2) else 0::numeric end), 0) as anulado,
                        coalesce(sum(case
                                when c53_tipo = 20 then round(c70_valor, 2)
                                when c53_tipo = 21 then round(c70_valor * -(1::numeric), 2)
                                else 0::numeric end
                            ), 0)                                                                    as liquidado,
                        coalesce(sum(case
                                when c53_tipo = 30 then round(c70_valor, 2)
                                when c53_tipo = 31 then round(c70_valor * -(1::numeric), 2)
                                else 0::numeric end
                            ), 0)                                                                    as pago

                 from conlancamdot
                      join conlancam on conlancam.c70_codlan = conlancamdot.c73_codlan
                      join conlancamdoc on conlancamdoc.c71_codlan = conlancam.c70_codlan
                      join conhistdoc on conhistdoc.c53_coddoc = conlancamdoc.c71_coddoc
                 WHERE conlancamdot.c73_coddot = f_coddot
                   and conlancamdot.c73_anousu = f_ano
                   and conlancam.c70_data between f_dataInicio and f_dataFim
             ) as x;

end;
$$;
SQL
        );

        $this->execute(<<<SQL
create or replace function fc_execucao_despesa_recurso(
    f_ano integer,
    f_coddot int,
    f_recurso int,
    f_dataInicio date,
    f_dataFim date
)
    returns table
            (
                empenhado         numeric(17, 2),
                anulado           numeric(17, 2),
                liquidado         numeric(17, 2),
                pago              numeric(17, 2),
                empenhado_liquido numeric(17, 2)
            )
    language plpgsql
as
$$
begin

    return query
        select x.empenhado::numeric(17, 2),
               x.anulado::numeric(17, 2),
               x.liquidado::numeric(17, 2),
               x.pago::numeric(17, 2),
               (x.empenhado - x.anulado)::numeric(17, 2) as empenhado_liquido
        from (
               select coalesce(sum(case when c53_tipo = 10 then round(c70_valor, 2) else 0::numeric end), 0) as empenhado,
                      coalesce(sum(case when c53_tipo = 11 then round(c70_valor, 2) else 0::numeric end), 0) as anulado,
                      coalesce(
                          sum(case
                              when c53_tipo = 20 then round(c70_valor, 2)
                              when c53_tipo = 21 then round(c70_valor * -(1::numeric), 2)
                              else 0::numeric end
                      ), 0) as liquidado,
                      coalesce(
                          sum(case
                              when c53_tipo = 30 then round(c70_valor, 2)
                              when c53_tipo = 31 then round(c70_valor * -(1::numeric), 2)
                              else 0::numeric end
                      ), 0)                                                                    as pago
               from conlancamdot
                    join conlancam on conlancam.c70_codlan = conlancamdot.c73_codlan
                    join conlancamdoc on conlancamdoc.c71_codlan = conlancam.c70_codlan
                    join conhistdoc on conhistdoc.c53_coddoc = conlancamdoc.c71_coddoc
                    join conlancamcomplementorecurso on conlancamcomplementorecurso.o201_codlan = conlancam.c70_codlan
               WHERE conlancamdot.c73_coddot = f_coddot
                 and conlancamdot.c73_anousu = f_ano
                 and conlancamcomplementorecurso.o201_orctiporec = f_recurso
                 and conlancam.c70_data between f_dataInicio and f_dataFim
           ) as x;
end;
$$;
SQL
        );
    }

    public function down()
    {
        $this->execute(<<<SQL
drop function if exists fc_execucao_despesa;
drop function if exists fc_execucao_despesa_recurso;
SQL
        );
    }
}
