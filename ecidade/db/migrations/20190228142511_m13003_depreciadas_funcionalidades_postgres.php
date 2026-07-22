<?php

use Classes\PostgresMigration;

class M13003DepreciadasFuncionalidadesPostgres extends PostgresMigration
{
    public function up()
    {
        $this->execute("
            DROP OPERATOR IF EXISTS || (anyelement, anyelement);
            DROP FUNCTION IF EXISTS public.fc_concat(anyelement, anyelement);
        ");
    }

    public function down()
    {
        $this->execute("
            CREATE OR REPLACE FUNCTION public.fc_concat(anyelement, anyelement) RETURNS text    STRICT IMMUTABLE LANGUAGE SQL AS 'SELECT cast($1 as text) || cast($2 as text);';
            CREATE OPERATOR || (PROCEDURE = fc_concat, LEFTARG = ANYELEMENT, RIGHTARG = ANYELEMENT);
        ");
    }
}
