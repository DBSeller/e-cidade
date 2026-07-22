<?php

use Classes\PostgresMigration;

class M17716AjusteFk extends PostgresMigration
{

    public function change()
    {
        $this->execute(<<<SQL
alter table planejamento.detalhamentoiniciativa
drop constraint detalhamentoiniciativa_pl20_iniciativaprojativ_fkey,
add constraint detalhamentoiniciativa_pl20_iniciativaprojativ_fkey
   foreign key (pl20_iniciativaprojativ)
   references planejamento.iniciativaprojativ
   on delete cascade;


alter table planejamento.cronogramadesembolsodespesa
drop constraint cronogramadesembolsodespesa_detalhamentoiniciativa_id_fkey,
add constraint cronogramadesembolsodespesa_detalhamentoiniciativa_id_fkey
   foreign key (detalhamentoiniciativa_id)
   references planejamento.detalhamentoiniciativa
   on delete cascade;
SQL
        );
    }
}
