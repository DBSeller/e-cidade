<?php

use Classes\PostgresMigration;

class M17907NovaMigration extends PostgresMigration
{
    public function up()
    {
        $this->execute(<<<SQL
            alter table caixa.operacoesrealizadastef add CONSTRAINT operacoestef_fk FOREIGN KEY(k198_operacaotef) REFERENCES operacoestef(k195_sequencial);
SQL
        );
    }

    public function down()
    {
        $this->execute(<<<SQL
            alter table caixa.operacoesrealizadastef drop CONSTRAINT operacoestef_fk;
SQL
        );
    }
}
