<?php

use Classes\PostgresMigration;

class M15996AcertoComplementoZero extends PostgresMigration
{
    public function up()
    {
        $sql = <<<SQL
insert into complementofonterecurso  (o200_sequencial, o200_descricao, o200_msc)
select  0, 'NÃO SE APLICA', false
 where not exists (select 1 from complementofonterecurso where o200_sequencial = 0);
SQL;
        $this->execute($sql);

    }
}
