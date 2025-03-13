<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class M19464PlSchemaDbportal extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement(<<<SQL

        /***
         *
         * set search_path=public, configuracoes;
         * 
         * select 'CREATE SCHEMA '||replace(lower(to_ascii(nomemod)), ' ', '')||';' 
         *   from db_sysmodulo 
         *  where ativo is true 
         * union all
         * select 'ALTER TABLE '||nomearq||' SET SCHEMA '||replace(lower(to_ascii(nomemod)), ' ', '')||';' 
         *   from db_sysarquivo 
         *        inner join db_sysarqmod on db_sysarqmod.codarq = db_sysarquivo.codarq 
         *        inner join db_sysmodulo on db_sysmodulo.codmod = db_sysarqmod.codmod 
         *  where db_sysmodulo.ativo is true
         * union all
         * select distinct 'ALTER SEQUENCE '||nomesequencia||' SET SCHEMA '||replace(lower(to_ascii(nomemod)), ' ', '')||';' 
         *   from db_sysarquivo 
         *        inner join db_sysarqmod    on db_sysarqmod.codarq = db_sysarquivo.codarq 
         *        inner join db_sysmodulo    on db_sysmodulo.codmod = db_sysarqmod.codmod 
         *        inner join db_sysarqcamp   on db_sysarqcamp.codarq = db_sysarquivo.codarq
         *        inner join db_syssequencia on db_syssequencia.codsequencia = db_sysarqcamp.codsequencia
         *  where db_sysmodulo.ativo is true;
         *
         */
        
        create or replace function fc_schemas_dbportal() returns void as
        $$
        declare
          rRelacaoPublic record;
        
          -- Schema padrao das tabelas da documentacao que nao tem modulo atribuido
          sSchemaPadrao  text default 'limbo';
          sSchemaRelacao text;
          sSchemaDDL     text;
          sRelacaoTipo   text;
        begin
        
          -- Percorre tabelas da base que estao no schema 'public'
          for rRelacaoPublic in
            select relname as relation_name,
                   relkind as relation_type
              from pg_class
                   inner join pg_namespace on pg_namespace.oid = pg_class.relnamespace
             where nspname = 'public'
               and relkind in ('r', 'S')
             order by relkind desc, relname
        
          loop
            if rRelacaoPublic.relation_type = 'r' then
              -- Verifica se tabela esta na documentacao
              select regexp_replace(lower(to_ascii(nomemod)), '[^A-Za-z]' , '', 'g')
                into sSchemaRelacao
                from db_sysarquivo
                     left join db_sysarqmod on db_sysarqmod.codarq = db_sysarquivo.codarq 
                     left join db_sysmodulo on db_sysmodulo.codmod = db_sysarqmod.codmod 
               where trim(db_sysarquivo.nomearq) = trim(rRelacaoPublic.relation_name);
        
              sRelacaoTipo := 'TABLE';
            else
              -- Verifica se sequence esta na documentacao
              select regexp_replace(lower(to_ascii(nomemod)), '[^A-Za-z]' , '', 'g')
                into sSchemaRelacao
                from db_sysarquivo 
                     left join db_sysarqmod    on db_sysarqmod.codarq = db_sysarquivo.codarq 
                     left join db_sysmodulo    on db_sysmodulo.codmod = db_sysarqmod.codmod 
                     left join db_sysarqcamp   on db_sysarqcamp.codarq = db_sysarquivo.codarq
                     left join db_syssequencia on db_syssequencia.codsequencia = db_sysarqcamp.codsequencia
               where trim(db_syssequencia.nomesequencia) = trim(rRelacaoPublic.relation_name);
        
              sRelacaoTipo := 'SEQUENCE';
            end if;
        
            -- Se esta na documentacao...
            if found then
              if sSchemaRelacao is not null then
                sSchemaPadrao := sSchemaRelacao;
              end if;
        
              -- Verifica se existe o schema
              if not exists(select 1 from information_schema.schemata where schema_name = sSchemaPadrao) then
                execute 'CREATE SCHEMA '||sSchemaPadrao;
                raise info '%', 'CREATE SCHEMA '||sSchemaPadrao;
              end if;
        
              perform relname
                from pg_class
                     inner join pg_namespace on pg_namespace.oid = pg_class.relnamespace
               where nspname = sSchemaPadrao
                 and relkind = rRelacaoPublic.relation_type
                 and relname = rRelacaoPublic.relation_name;
        
              if not found then
                 execute 'ALTER '||sRelacaoTipo||' public.'||rRelacaoPublic.relation_name||' SET SCHEMA '||sSchemaPadrao;
                 raise info '%', 'ALTER '||sRelacaoTipo||' public.'||rRelacaoPublic.relation_name||' SET SCHEMA '||sSchemaPadrao;
              end if;
            else
              raise info 'ERRO: % % nao consta na documentacao do DBPortal', fc_iif((sRelacaoTipo='TABLE'), 'Tabela'::text, 'Sequencia'::text)::text, rRelacaoPublic.relation_name;
            end if;
                      
          end loop;
        
          return;
        end;
        $$
        language plpgsql


SQL
        );

        DB::statement('select fc_schemas_dbportal();');

}

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement(<<<SQL
        /***
         *
         * set search_path=public, configuracoes;
         * 
         * select 'CREATE SCHEMA '||replace(lower(to_ascii(nomemod)), ' ', '')||';' 
         *   from db_sysmodulo 
         *  where ativo is true 
         * union all
         * select 'ALTER TABLE '||nomearq||' SET SCHEMA '||replace(lower(to_ascii(nomemod)), ' ', '')||';' 
         *   from db_sysarquivo 
         *        inner join db_sysarqmod on db_sysarqmod.codarq = db_sysarquivo.codarq 
         *        inner join db_sysmodulo on db_sysmodulo.codmod = db_sysarqmod.codmod 
         *  where db_sysmodulo.ativo is true
         * union all
         * select distinct 'ALTER SEQUENCE '||nomesequencia||' SET SCHEMA '||replace(lower(to_ascii(nomemod)), ' ', '')||';' 
         *   from db_sysarquivo 
         *        inner join db_sysarqmod    on db_sysarqmod.codarq = db_sysarquivo.codarq 
         *        inner join db_sysmodulo    on db_sysmodulo.codmod = db_sysarqmod.codmod 
         *        inner join db_sysarqcamp   on db_sysarqcamp.codarq = db_sysarquivo.codarq
         *        inner join db_syssequencia on db_syssequencia.codsequencia = db_sysarqcamp.codsequencia
         *  where db_sysmodulo.ativo is true;
         *
         */
        
        create or replace function fc_schemas_dbportal() returns void as
        $$
        declare
          rRelacaoPublic record;
        
          -- Schema padrao das tabelas da documentacao que nao tem modulo atribuido
          sSchemaPadrao  text default 'limbo';
          sSchemaRelacao text;
          sSchemaDDL     text;
          sRelacaoTipo   text;
        begin
        
          -- Percorre tabelas da base que estao no schema 'public'
          for rRelacaoPublic in
            select relname as relation_name,
                   relkind as relation_type
              from pg_class
                   inner join pg_namespace on pg_namespace.oid = pg_class.relnamespace
             where nspname = 'public'
               and relkind in ('r', 'S')
        
          loop
            if rRelacaoPublic.relation_type = 'r' then
              -- Verifica se tabela esta na documentacao
              select regexp_replace(lower(to_ascii(nomemod)), '[^A-Za-z]' , '', 'g')
                into sSchemaRelacao
                from db_sysarquivo
                     left join db_sysarqmod on db_sysarqmod.codarq = db_sysarquivo.codarq 
                     left join db_sysmodulo on db_sysmodulo.codmod = db_sysarqmod.codmod 
               where trim(db_sysarquivo.nomearq) = trim(rRelacaoPublic.relation_name);
        
              sRelacaoTipo := 'TABLE';
            else
              -- Verifica se sequence esta na documentacao
              select regexp_replace(lower(to_ascii(nomemod)), '[^A-Za-z]' , '', 'g')
                into sSchemaRelacao
                from db_sysarquivo 
                     left join db_sysarqmod    on db_sysarqmod.codarq = db_sysarquivo.codarq 
                     left join db_sysmodulo    on db_sysmodulo.codmod = db_sysarqmod.codmod 
                     left join db_sysarqcamp   on db_sysarqcamp.codarq = db_sysarquivo.codarq
                     left join db_syssequencia on db_syssequencia.codsequencia = db_sysarqcamp.codsequencia
               where trim(db_syssequencia.nomesequencia) = trim(rRelacaoPublic.relation_name);
        
              sRelacaoTipo := 'SEQUENCE';
            end if;
        
            -- Se esta na documentacao...
            if found then
              if sSchemaRelacao is not null then
                sSchemaPadrao := sSchemaRelacao;
              end if;
        
              -- Verifica se existe o schema
              if not exists(select 1 from information_schema.schemata where schema_name = sSchemaPadrao) then
                execute 'CREATE SCHEMA '||sSchemaPadrao;
                raise info '%', 'CREATE SCHEMA '||sSchemaPadrao;
              end if;
        
              execute 'ALTER '||sRelacaoTipo||' public.'||rRelacaoPublic.relation_name||' SET SCHEMA '||sSchemaPadrao;
              raise info '%', 'ALTER '||sRelacaoTipo||' public.'||rRelacaoPublic.relation_name||' SET SCHEMA '||sSchemaPadrao;
        
            else
              raise info 'ERRO: % % nao consta na documentacao do DBPortal', fc_iif((sRelacaoTipo='TABLE'), 'Tabela'::text, 'Sequencia'::text)::text, rRelacaoPublic.relation_name;
            end if;
                      
          end loop;
        
          return;
        end;
        $$
        language plpgsql

SQL
        );
    }
}
