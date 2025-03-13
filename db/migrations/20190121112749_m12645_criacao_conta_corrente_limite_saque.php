<?php

use Classes\PostgresMigration;

class M12645CriacaoContaCorrenteLimiteSaque extends PostgresMigration
{

    public function up()
    {

        $this->execute(
            <<<SQL_UP
            
            update conplanoinfocomplementar set c121_sql = 'select null' where  c121_sequencial = 41;
            
            delete from conplanosistemaatributos where c129_conplanosistema = 25;
            
            insert into conplanosistemaatributos 
                 values (nextval('conplanosistemaatributos_c129_sequencial_seq'), 25, 36, 1), 
                        (nextval('conplanosistemaatributos_c129_sequencial_seq'), 25, 37, 2),
                        (nextval('conplanosistemaatributos_c129_sequencial_seq'), 25, 38, 3),
                        (nextval('conplanosistemaatributos_c129_sequencial_seq'), 25, 39, 4),
                        (nextval('conplanosistemaatributos_c129_sequencial_seq'), 25, 41, 5),
                        (nextval('conplanosistemaatributos_c129_sequencial_seq'), 25, 16, 6),
                        (nextval('conplanosistemaatributos_c129_sequencial_seq'), 25, 17, 7),
                        (nextval('conplanosistemaatributos_c129_sequencial_seq'), 25, 18, 8),
                        (nextval('conplanosistemaatributos_c129_sequencial_seq'), 25, 19, 9);
SQL_UP
        );

    }

    public function down()
    {

        $this->execute(<<<SQL_DOWN
        
            delete from conplanosistemaatributos where c129_conplanosistema = 25;
            
            insert into conplanosistemaatributos 
                 values (nextval('conplanosistemaatributos_c129_sequencial_seq'), 25, 36, 1), 
                        (nextval('conplanosistemaatributos_c129_sequencial_seq'), 25, 37, 2),
                        (nextval('conplanosistemaatributos_c129_sequencial_seq'), 25, 38, 3),
                        (nextval('conplanosistemaatributos_c129_sequencial_seq'), 25, 39, 4),
                        (nextval('conplanosistemaatributos_c129_sequencial_seq'), 25, 43, 5),
                        (nextval('conplanosistemaatributos_c129_sequencial_seq'), 25, 41, 6);                        
SQL_DOWN
);
    }
}
