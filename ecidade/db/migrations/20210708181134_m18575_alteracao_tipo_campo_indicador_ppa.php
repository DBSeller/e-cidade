<?php

use Classes\PostgresMigration;

class M18575AlteracaoTipoCampoIndicadorPpa extends PostgresMigration
{
    public function up()
    {
        $this->execute(<<<SQL
alter table planejamento.indicadoresprogramaestrategico alter COLUMN pl22_indice type float;
SQL
        );
    }

    public function down()
    {
        $this->execute(<<<SQL
alter table planejamento.indicadoresprogramaestrategico alter COLUMN pl22_indice type numeric(15,2);
SQL
        );
    }
}
