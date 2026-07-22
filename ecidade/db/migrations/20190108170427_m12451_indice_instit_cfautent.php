<?php

use Classes\PostgresMigration;

class M12451IndiceInstitCfautent extends PostgresMigration
{
    public function up()
    {
        $rsIndexExists = $this->query("
            select
                  t.relname as table_name,
                  i.relname as index_name,
                  a.attname as column_name
                from
                  pg_class t,
                  pg_class i,
                  pg_index ix,
                  pg_attribute a
                where t.oid = ix.indrelid
                  and i.oid = ix.indexrelid
                  and a.attrelid = t.oid
                  and a.attnum = ANY(ix.indkey)
                  and t.relkind = 'r'
                  and t.relname like 'cfautent'
                  and i.relname = 'cfautent_ipterm_instit_in'
                order by
                  t.relname,
                  i.relname;
        ");
        $indexExists = $rsIndexExists->fetchAll(PDO::FETCH_CLASS);

        if (!empty($indexExists)) {
            return;
        }

        $rsRegistrosDuplicados = $this->query("
            select array_agg(k11_id) as k11_id, k11_ipterm, k11_instit from cfautent group by k11_ipterm, k11_instit having count(*) > 1
        ");
        $registrosDuplicados = $rsRegistrosDuplicados->fetchAll(PDO::FETCH_CLASS);

        if (!$registrosDuplicados) {
            return;
        }

        foreach ($registrosDuplicados as $registroDuplicado) {
            $ids = explode(',', str_replace(array('{', '}'), '', $registroDuplicado->k11_id));
            array_shift($ids);

            foreach ($ids as $id) {
                $ip = "ACERTO 12451 - {$id}";

                $this->query("
                    update cfautent set k11_ipterm = '{$ip}' where k11_id = {$id};
                ");
            }
        }

        $this->execute("create unique index cfautent_ipterm_instit_in on cfautent (k11_instit, k11_ipterm);");
    }
}
