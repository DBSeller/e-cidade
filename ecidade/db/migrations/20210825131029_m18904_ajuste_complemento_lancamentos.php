<?php

use Classes\PostgresMigration;

class M18904AjusteComplementoLancamentos extends PostgresMigration
{
    public function change()
    {
        $sql = "
        select c70_codlan,
               c71_coddoc,
               c53_descr,
               c70_data
          from conlancam
         inner join conlancamdoc   on c70_codlan = c71_codlan
         inner join conhistdoc     on c71_coddoc = c53_coddoc
          left join conlancamcompl on c70_codlan = c72_codlan
         where c72_codlan is null
           and c70_data >= '2021-01-01'
          order by c70_data
        ";

        foreach ($this->query($sql) as $row) {
            $complemento = sprintf(
                "Referente ao lançamento contábil realizado no evento %s - %s em %s",
                $row['c71_coddoc'],
                $row['c53_descr'],
                $row['c70_data']
            );

            $insert = sprintf(
                "insert into contabilidade.conlancamcompl (c72_codlan, c72_complem) values (%s, '%s')",
                $row['c70_codlan'],
                $complemento
            );

            $this->execute($insert);
        }
    }
}
