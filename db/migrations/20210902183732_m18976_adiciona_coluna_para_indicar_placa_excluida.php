<?php

use Classes\PostgresMigration;

class M18976AdicionaColunaParaIndicarPlacaExcluida extends PostgresMigration
{
    public function up()
    {
        $this->execute("
            insert into db_syscampo values(1013423,'t41_excluido','bool','Indica se o histórico de placa foi excluído ou não.','t', 'Histórico Excluído',1,'f','f','f',5,'text','Histórico Excluído');
            insert into db_sysarqcamp values(1523, 1013423, 8, 0);

            ALTER TABLE bensplaca ADD COLUMN t41_excluido BOOLEAN DEFAULT false;
        ");
    }

    public function down()
    {
        $this->execute("
            delete from db_sysarqcamp where codarq = 1523 AND codcam = 1013423;
            delete from db_syscampo where codcam = 1013423;

            ALTER TABLE bensplaca DROP COLUMN t41_excluido;
        ");

        // delete from db_sysarqcamp where codarq = 1523 AND codcam = 1013423;
        // delete from db_syscampo where codcam = 1013423;

        // ALTER TABLE bensplaca DROP COLUMN t41_ativo;
    }
}
