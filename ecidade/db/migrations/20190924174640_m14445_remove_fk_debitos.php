<?php

use Classes\PostgresMigration;

class M14445RemoveFkDebitos extends PostgresMigration
{
    public function up()
    {
        $sSql =
<<< SQL
DO
$$
DECLARE
    r  RECORD;
BEGIN
    FOR r IN
        SELECT  conrelid::regclass::text AS tablename, conname
        FROM    pg_constraint
        WHERE   contype = 'f'
        AND     conname ~ '_instit_fk$'
        AND     (conrelid::regclass::text = 'debitos' OR conrelid::regclass::text ~ '^debitos_[0-9]')
        ORDER   BY 1
    LOOP
        RAISE INFO 'Removendo FK % da tabela %', r.conname, r.tablename;
        EXECUTE format('ALTER TABLE %s DROP CONSTRAINT %I;', r.tablename, r.conname);
    END LOOP;
END;
$$
LANGUAGE plpgsql;

SQL;

    $this->execute($sSql);
    }
}
