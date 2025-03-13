<?php

use Classes\PostgresMigration;

class M17413 extends PostgresMigration
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
     *    addCustomColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Any other destructive changes will result in an error when trying to
     * rollback the migration.
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function up()
    {
        $this->upDicionario();
        $this->upEstrutura();
    }

    public function down()
    {
        $this->downDicionario();
        $this->downEstrutura();
    }

    public function upDicionario()
    {
        $this->execute(<<<SQL
            INSERT INTO db_syscampo values(1012018,'k00_liberacarnesis','bool','Emitir carnê banco vencidos no sistema','f', 'Emitir carnê banco vencidos no sistema',1,'f','f','f',5,'text','Emitir carnê banco vencidos no sistema');
            INSERT INTO db_syscampodef values(1012018,'false','');
            INSERT INTO db_syscampo values(1012019,'k00_liberacarnepref','bool','Emitir carnê banco vencidos no DBPref','f', 'Emitir carnê banco vencidos no DBPref',1,'f','f','f',5,'text','Emitir carnê banco vencidos no DBPref');
            INSERT INTO db_syscampodef values(1012019,'false','');
            INSERT INTO db_sysarqcamp values(82,1012019,46,0);
            INSERT INTO db_sysarqcamp values(82,1012018,47,0);
            UPDATE db_syscampo SET rotulo = 'emite recibo e carnê', rotulorel = 'emite recibo e carnê' WHERE codcam = 474;
SQL
);
    }

    public function upEstrutura()
    {
        $this->execute(<<<SQL
            ALTER TABLE caixa.arretipo 
                ADD COLUMN k00_liberacarnesis BOOLEAN DEFAULT TRUE,
                ADD COLUMN k00_liberacarnepref BOOLEAN DEFAULT TRUE;
SQL
);
    }

    public function downDicionario() {
        $this->execute(<<<SQL
            DELETE FROM db_sysarqcamp WHERE codarq = 82 and codcam = 1012018;
            DELETE FROM db_sysarqcamp WHERE codarq = 82 and codcam = 1012019;
            DELETE FROM db_syscampodef WHERE codcam = 1012018;
            DELETE FROM db_syscampodef WHERE codcam = 1012019;
            DELETE FROM db_syscampo WHERE codcam = 1012018;
            DELETE FROM db_syscampo WHERE codcam = 1012019;
            UPDATE db_syscampo SET rotulo = 'emite recibo', rotulorel = 'emite recibo' WHERE codcam = 474;
SQL
);
    }
    public function downEstrutura() {
        $this->execute(<<<SQL
            ALTER TABLE caixa.arretipo
                DROP COLUMN k00_liberacarnesis,
                DROP COLUMN k00_liberacarnepref;
SQL
);
    }

}
