<?php

use Classes\PostgresMigration;

class M18965PlAlteracoesOrcamentarias extends PostgresMigration
{
    public function up()
    {
        $this->execute(<<<SQL
drop function if exists fc_alteracoes_orcamentarias(integer, int, date, date);
SQL
        );

        $this->execute(<<<SQL
create or replace function fc_alteracoes_orcamentarias(
    f_ano integer,
    f_coddot integer,
    f_dataInicio date,
    f_dataFim date
)
    returns table
            (
                suplementado                   numeric(17, 2),
                especial                       numeric(17, 2),
                reducoes                       numeric(17, 2),
                saldo_alteracoes_orcamentarias numeric(17, 2)
            )
    language plpgsql
as
$$
begin

    return query
        select x.suplementado,
               x.especial,
               x.reducoes,
               (x.suplementado + x.especial - x.reducoes) as saldo_alteracoes_orcamentarias
        from (
            select coalesce(
                           sum((case when c53_tipo = 40 then round(c70_valor, 2) else 0::numeric end) -
                               (case when c53_tipo = 41 then round(c70_valor, 2) else 0::numeric end)
                               ),
                           0) as suplementado,
                   coalesce(
                           sum((case when c53_tipo = 50 then round(c70_valor, 2) else 0::numeric end) -
                               (case when c53_tipo = 51 then round(c70_valor, 2) else 0::numeric end)
                               ),
                           0) as especial,
                   coalesce(
                           sum((case when c53_tipo = 60 then round(c70_valor, 2) else 0::numeric end) -
                               (case when c53_tipo = 61 then round(c70_valor, 2) else 0::numeric end)
                               ),
                           0) as reducoes
            from conlancamdot
                     join conlancam on conlancam.c70_codlan = conlancamdot.c73_codlan
                     join conlancamdoc on conlancamdoc.c71_codlan = conlancam.c70_codlan
                     join conhistdoc on conhistdoc.c53_coddoc = conlancamdoc.c71_coddoc
            where conlancamdot.c73_coddot = f_coddot
              and conlancamdot.c73_anousu = f_ano
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
drop function if exists fc_alteracoes_orcamentarias;
SQL
        );
    }
}
