<?php

use Classes\PostgresMigration;

class M12642ContaCorrenteRp extends PostgresMigration
{

    public function up()
    {
       $this->execute(<<<SQL_UP
insert into conplanosistema values (27, 'Restos a pagar',2);
insert into conplanosistemaatributos values (nextval('conplanosistemaatributos_c129_sequencial_seq'),27,27, 1);
insert into conplanosistemaatributos values (nextval('conplanosistemaatributos_c129_sequencial_seq'),27,26, 2);
insert into conplanosistemaatributos values (nextval('conplanosistemaatributos_c129_sequencial_seq'),27,36, 3);
insert into conplanosistemaatributos values (nextval('conplanosistemaatributos_c129_sequencial_seq'),27,38, 4);
insert into conplanosistemaatributos values (nextval('conplanosistemaatributos_c129_sequencial_seq'),27,39, 5);
insert into conplanosistemaatributos values (nextval('conplanosistemaatributos_c129_sequencial_seq'),27,37, 6);
insert into conplanosistemaatributos values (nextval('conplanosistemaatributos_c129_sequencial_seq'),27,43, 7);
insert into conplanosistemaatributos values (nextval('conplanosistemaatributos_c129_sequencial_seq'),27,9, 8);


SQL_UP
);
    }

    public function down()
    {
        $this->execute(<<<SQL_DOWn
       DELETE FROM conplanosistemaatributos WHERE c129_conplanosistema = 27;
       DELETE FROM conplanosistema WHERE c122_sequencial = 27;
       
SQL_DOWn
);
    }
}
