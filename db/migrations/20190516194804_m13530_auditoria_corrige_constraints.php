<?php

use Classes\PostgresMigration;

class M13530AuditoriaCorrigeConstraints extends PostgresMigration
{
    /**
     * dup Method.
     *
     * Write your UP migrations using this method.
     *
     * This provide callbacks to be executed after or before a migration,
     * this is possible by implementation of callbackBeforeUp and 
     * callbackAfterUp methods on this class. You can also implement the
     * up methods this is overwrite the parent method and the callback 
     * funcionality will be not available.
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function up()
    {
        $this->execute("
/* Libera agendamentos de migracao pendentes por erro */
UPDATE  db_auditoria_migracao
SET     status = 'NAO INICIADO'
WHERE   status ~ '^INICIADO'
AND     observacoes = 'Retentativas=3';

/* Ajusta CHECK CONSTRAINTS das tabelas db_auditoria de 2019 */
DO
$$
DECLARE
    s RECORD;
BEGIN
    FOR s IN 
        WITH c AS (
            SELECT oid, conrelid::regclass::text AS tablename, conname, string_to_array(conname::text, '_') a FROM pg_constraint WHERE conname ~ '^db_auditoria_2019' AND contype = 'c' ORDER BY conname
        ),
        d AS (
            SELECT oid, tablename, conname, a[3] AS data_str, to_date(a[3]||'01', 'YYYYMMDD') AS data, a[4] AS instit FROM c
        )
        SELECT *,
            'ALTER TABLE '||tablename||' DROP CONSTRAINT '||conname||';' AS drop_cmd,
            'ALTER TABLE '||tablename||' ADD CONSTRAINT '||conname||' CHECK (datahora_servidor >= '||quote_literal(data::timestamptz::text)||' AND datahora_servidor < '||
                quote_literal(((data + '1 month'::interval)::timestamptz)::text)||' AND instit = '||instit||');' AS create_cmd
        FROM d
    LOOP
        RAISE INFO '[%] Dropping constraint %', to_char(clock_timestamp(), 'YYYY-MM-DD HH24:MI:SS'), s.conname;
        EXECUTE s.drop_cmd;
        RAISE INFO '[%] Creating constraint %', to_char(clock_timestamp(), 'YYYY-MM-DD HH24:MI:SS'), s.conname;
        EXECUTE s.create_cmd;
    END LOOP;
END;
$$ LANGUAGE plpgsql;
");
    }

    /**
     * down Method.
     *
     * Write your DOWN migrations using this method.
     *
     * This provide callbacks to be executed after or before a migration,
     * this is possible by implementation of callbackBeforeDown and 
     * callbackAfterDown methods on this class. You can also implement the
     * down method, this is overwrite the parent and the callback funcionality 
     * will be not available.
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function down()
    {
    }
}
