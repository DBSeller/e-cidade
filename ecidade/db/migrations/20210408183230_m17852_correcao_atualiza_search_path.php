<?php

use Classes\PostgresMigration;

class M17852CorrecaoAtualizaSearchPath extends PostgresMigration
{

  public function up()
  {
      $sql = <<<SQL_UP
      CREATE OR REPLACE FUNCTION fc_atualiza_search_path_inc_del_alt()
      RETURNS trigger
      LANGUAGE plpgsql
     AS $$
     begin
       perform fc_set_pg_search_path();
     
       if TG_OP in ('INSERT', 'UPDATE') then
         return NEW;
       end if;
     
       return OLD;
     end;
     $$ ;
     
     DROP TRIGGER IF EXISTS tg_atualiza_search_path ON db_sysmodulo;
     
     CREATE TRIGGER tg_atualiza_search_path
     AFTER UPDATE or DELETE or INSERT ON db_sysmodulo
     FOR EACH ROW
     EXECUTE PROCEDURE fc_atualiza_search_path_inc_del_alt();
                  
SQL_UP;
      $this->execute($sql);
  }

}
