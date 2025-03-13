<?php

use Classes\PostgresMigration;

class M11832ArrayAggregate extends PostgresMigration
{
    public function up()
    {
        $sql = "
            CREATE FUNCTION public.fc_array_cat(anyarray, anyarray)
            RETURNS anyarray AS
            $$ SELECT array_cat($1, array[$2]) $$
            LANGUAGE SQL IMMUTABLE;
            
            CREATE FUNCTION public.fc_array_append(anyarray, anynonarray)
            RETURNS anyarray AS
            $$ SELECT array_append($1, $2::anyelement) $$
            LANGUAGE SQL IMMUTABLE;
            
            CREATE AGGREGATE public.fc_array_agg(anyarray) (
                SFUNC = fc_array_cat,
                STYPE = anyarray
            );
            
            CREATE AGGREGATE public.fc_array_agg(anynonarray) (
                SFUNC = fc_array_append,
                STYPE = anyarray
            );
        ";
        $this->execute($sql);
    }

    public function down()
    {
        $sql = "
            DROP AGGREGATE public.fc_array_agg(anynonarray);
            DROP AGGREGATE public.fc_array_agg(anyarray);
            DROP FUNCTION public.fc_array_append(anyarray, anynonarray);
            DROP FUNCTION public.fc_array_cat(anyarray, anyarray);
        ";
        $this->execute($sql);
    }
}
