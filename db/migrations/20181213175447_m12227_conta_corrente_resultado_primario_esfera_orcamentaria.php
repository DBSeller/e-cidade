<?php

use Classes\PostgresMigration;

class M12227ContaCorrenteResultadoPrimarioEsferaOrcamentaria extends PostgresMigration
{


    public function up()
    {

        $this->execute(<<<SQL_UP

insert into conplanosistema values (19, 'Indicador de Resultado Primário', 2);
insert into conplanosistemaatributos values 
      (nextval('conplanosistemaatributos_c129_sequencial_seq'), 19, (select c121_sequencial from conplanoinfocomplementar where c121_sigla = 'IRP'), 1),
      (nextval('conplanosistemaatributos_c129_sequencial_seq'), 19, (select c121_sequencial from conplanoinfocomplementar where c121_sigla = 'UG'), 2);

insert into conplanosistema values (20, 'Esfera Orçamentária', 2);
insert into conplanosistemaatributos values 
      (nextval('conplanosistemaatributos_c129_sequencial_seq'), 20, (select c121_sequencial from conplanoinfocomplementar where c121_sigla = 'EO'), 1),
      (nextval('conplanosistemaatributos_c129_sequencial_seq'), 20, (select c121_sequencial from conplanoinfocomplementar where c121_sigla = 'UG'), 2);


SQL_UP
);

    }


    public function down()
    {
        $this->execute(<<<SQL_DOWN

delete from conplanosistemaatributos where c129_conplanosistema in (19,20);
delete from conplanosistema where c122_sequencial in (19,20);

SQL_DOWN
);
    }

}
