<?php

use Classes\PostgresMigration;

class M16515VinculoEventosContabeis extends PostgresMigration
{
    public function up() {
        
        $this->execute(<<<SQL_UP

insert into vinculoeventoscontabeis select nextval('vinculoeventoscontabeis_c115_sequencial_seq'), 74, null where not exists (select 1 from vinculoeventoscontabeis where c115_conhistdocinclusao = 74);
insert into vinculoeventoscontabeis select nextval('vinculoeventoscontabeis_c115_sequencial_seq'), 75, null where not exists (select 1 from vinculoeventoscontabeis where c115_conhistdocinclusao = 75);
insert into vinculoeventoscontabeis select nextval('vinculoeventoscontabeis_c115_sequencial_seq'), 76, null where not exists (select 1 from vinculoeventoscontabeis where c115_conhistdocinclusao = 76);
insert into vinculoeventoscontabeis select nextval('vinculoeventoscontabeis_c115_sequencial_seq'), 77, null where not exists (select 1 from vinculoeventoscontabeis where c115_conhistdocinclusao = 77);

SQL_UP
);
    }

    public function down() {

        $this->execute(<<<SQL_DOWN

delete from vinculoeventoscontabeis where c115_conhistdocinclusao in (74, 75, 76, 77);

SQL_DOWN
        );
    }
}
