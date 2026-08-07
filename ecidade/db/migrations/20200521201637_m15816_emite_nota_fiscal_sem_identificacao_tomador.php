<?php

use Classes\PostgresMigration;

class M15816EmiteNotaFiscalSemIdentificacaoTomador extends PostgresMigration
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
        $this->execute(
            <<<SQL_UP
        insert into grupocaracteristica values ((SELECT max(db139_sequencial) +1 FROM grupocaracteristica ), 1, 'IDENTIFICA TOMADOR');
        insert into caracteristica values ((select max(db140_sequencial) + 1 from caracteristica), 49, 'SIM');
        insert into caracteristica values ((select max(db140_sequencial) + 1 from caracteristica), 49, 'NAO');
        insert into issbasecaracteristica select nextval('issbasecaracteristica_q138_sequencial_seq'),(select db140_sequencial from caracteristica where db140_grupocaracteristica in (select db139_sequencial from grupocaracteristica where db139_descricao = 'IDENTIFICA TOMADOR') and db140_descricao = 'NAO'), q02_inscr from issbase;

SQL_UP
);
    }

    public function down()
    {
        $this->execute(
          <<<SQL_DOWN
      DELETE from issbasecaracteristica
      WHERE q138_caracteristica in (SELECT db140_sequencial
          FROM caracteristica
          WHERE db140_grupocaracteristica IN
              (SELECT db139_sequencial
              FROM grupocaracteristica
              WHERE db139_descricao = 'IDENTIFICA TOMADOR')
                      AND db140_descricao = 'NAO')
                      AND q138_inscr in (SELECT q02_inscr
                      FROM issbase);

       DELETE FROM caracteristica WHERE db140_grupocaracteristica = 49;

       DELETE FROM grupocaracteristica WHERE db139_descricao = 'IDENTIFICA TOMADOR';
SQL_DOWN
);
    }
}
