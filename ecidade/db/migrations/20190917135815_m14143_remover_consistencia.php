<?php

use Classes\PostgresMigration;

class M14143RemoverConsistencia extends PostgresMigration
{

    public function up()
    {
        $this->execute("delete from consistenciasistema where db160_json ilike '%5d35a3c9aac5a%';");
    }

    public function down() 
    {        
    }  
}
