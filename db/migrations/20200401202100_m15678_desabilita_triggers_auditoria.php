<?php

use Classes\PostgresMigration;

class M15678DesabilitaTriggersAuditoria extends PostgresMigration
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
        $this->execute(<<<SQL
        
            SELECT fc_executa_ddl('DO
                $$
                DECLARE
                    stmt TEXT DEFAULT \'DROP EVENT TRIGGER IF EXISTS evtg_auditoria_gatilho_ddl;\';
                BEGIN
                    -- Remover EventTrigger de auditoria
                    RAISE INFO \'Executing %\', stmt;
                    EXECUTE stmt;

                    -- Remover Trigger de auditoria de TODAS tabelas com EXCECAO das do JETOM
                    FOR stmt IN
                        SELECT
                            format(\'DROP TRIGGER IF EXISTS %s ON %s;\', tgname, tgrelid::regclass::text)
                        FROM
                            pg_trigger
                        WHERE
                            tgname ~ \'^tg_auditoria_(insert_delete|update)\'
                            AND tgrelid::regclass::text !~ \'^jetom\'
                    LOOP
                        RAISE INFO \'Executing %\', stmt;
                        EXECUTE stmt;
                    END LOOP;
                END;
                $$ LANGUAGE plpgsql');

SQL
        );

    }
}
