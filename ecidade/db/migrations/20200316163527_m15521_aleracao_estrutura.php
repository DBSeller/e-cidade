<?php

use Classes\PostgresMigration;

class M15521AleracaoEstrutura extends PostgresMigration
{
    public function up()
    {
        $this->execute("
            update db_syssequencia set nomesequencia = 'diarioalunoresultadofinal_ed165_codigo_seq' where codsequencia = 1000891;
        ");
        $this->execute("
            drop sequence if exists escola.diarioarearesultadofinal_ed165_codigo_seq;
            create sequence escola.diarioalunoresultadofinal_ed165_codigo_seq increment 1 minvalue 1 maxvalue 9223372036854775807 start 1 cache 1;
        ");
    }

    public function down()
    {
        $this->execute("
            update db_syssequencia set nomesequencia = 'diarioarearesultadofinal_ed165_codigo_seq' where codsequencia = 1000891;
        ");

        $this->execute("
            drop sequence if exists escola.diarioalunoresultadofinal_ed165_codigo_seq;
            create sequence escola.diarioarearesultadofinal_ed165_codigo_seq increment 1 minvalue 1 maxvalue 9223372036854775807 start 1 cache 1;
        ");
    }
}
