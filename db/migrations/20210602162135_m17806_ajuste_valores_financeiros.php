<?php

use Classes\PostgresMigration;

class M17806AjusteValoresFinanceiros extends PostgresMigration
{
    public function change()
    {
        $this->execute(<<<sql
with valores_financeiros as (
    select item, m89_sequencial, m89_valorfinanceiro, valor_correto
    from (select m81_tipo,
                 m80_data,
                 m82_quant,
                 m89_sequencial,
                 m89_matestoqueinimei,
                 m89_precomedio,
                 m89_valorunitario,
                 m89_valorfinanceiro,
                 case
                     when m81_tipo = 1 then round(m82_quant * m89_valorunitario, 2)
                     else round(m82_quant * m89_precomedio, 2) end valor_correto,
                 m70_codmatmater as                                item
          from matestoqueini
                   inner join matestoquetipo on m80_codtipo = m81_codtipo
                   inner join matestoqueinimei on m82_matestoqueini = m80_codigo
                   left join matestoqueinimeipm on m82_codigo = m89_matestoqueinimei
                   inner join matestoqueitem on m82_matestoqueitem = m71_codlanc
                   inner join matestoque on m71_codmatestoque = m70_codigo
                   inner join db_depart on m70_coddepto = coddepto
                   left join db_almox on db_almox.m91_depto = db_depart.coddepto
          where m71_servico is false
            and m89_valorfinanceiro <> (m82_quant * m89_valorunitario)
          order by 2) as x
    where m89_valorfinanceiro <> valor_correto
) update matestoqueinimeipm set m89_valorfinanceiro = valor_correto from valores_financeiros
    where valores_financeiros.m89_sequencial = matestoqueinimeipm.m89_sequencial;
sql
        );
    }
}
