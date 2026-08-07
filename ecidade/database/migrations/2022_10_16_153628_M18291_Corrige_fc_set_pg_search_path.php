<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M18291CorrigeFcSetPgSearchPath extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::connection()->getPdo()->exec(<<<SQL

            create or replace function fc_set_pg_search_path() returns boolean as
            $$
            declare
            	lRetorno   boolean default true;
            
            	sSchemasPath text;
            begin
            
            	begin
            		sSchemasPath :=
            			array_to_string(
            				array(
            					select *
            					  from (
            						(select distinct regexp_replace(lower(to_ascii(nomemod)), '[^A-Za-z]' , '', 'g') as nomemod
            						   from configuracoes.db_sysmodulo) order by 1
            					) as x
            					where exists (select 1 from pg_namespace where nspname = nomemod)
            				), ', '
            			);
            
            		sSchemasPath := '"\$user",public,'||sSchemasPath;
            		perform fc_putsession('DB_pg_search_path', sSchemasPath);
            
            		execute 'alter database '||quote_ident(current_database())||' set search_path='||sSchemasPath||';';
            		execute 'set search_path='||sSchemasPath||';';
            
            	exception
            		when others then
            			lRetorno := false;
            	end;
            
            	return lRetorno;
            end;
            $$
            language 'plpgsql';
            
            /* Para garantir que apos criada na base ela seja executada */
            select fc_set_pg_search_path();

SQL
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        return true;
    }
}
