<?php

use Classes\PostgresMigration;

class M12227ContaCorrenteCelulaDespesa extends PostgresMigration
{

    public function up()
    {

        $this->execute(
            <<<SQL_UP

insert into conplanosistema values (17, 'Célula da Despesa', 2);

insert into conplanosistemaatributos values 
      (nextval('conplanosistemaatributos_c129_sequencial_seq'), 17, (select c121_sequencial from conplanoinfocomplementar where c121_sigla = 'EO'), 1),
      (nextval('conplanosistemaatributos_c129_sequencial_seq'), 17, (select c121_sequencial from conplanoinfocomplementar where c121_sigla = 'ORG'), 2),
      (nextval('conplanosistemaatributos_c129_sequencial_seq'), 17, (select c121_sequencial from conplanoinfocomplementar where c121_sigla = 'UO'), 3),
      (nextval('conplanosistemaatributos_c129_sequencial_seq'), 17, (select c121_sequencial from conplanoinfocomplementar where c121_sigla = 'FUN'), 4),
      (nextval('conplanosistemaatributos_c129_sequencial_seq'), 17, (select c121_sequencial from conplanoinfocomplementar where c121_sigla = 'SUBF'), 5),
      (nextval('conplanosistemaatributos_c129_sequencial_seq'), 17, (select c121_sequencial from conplanoinfocomplementar where c121_sigla = 'PROG'), 6),
      (nextval('conplanosistemaatributos_c129_sequencial_seq'), 17, (select c121_sequencial from conplanoinfocomplementar where c121_sigla = 'AC'), 7),
      (nextval('conplanosistemaatributos_c129_sequencial_seq'), 17, (select c121_sequencial from conplanoinfocomplementar where c121_sigla = 'SLG'), 8),
      (nextval('conplanosistemaatributos_c129_sequencial_seq'), 17, (select c121_sequencial from conplanoinfocomplementar where c121_sigla = 'IUFR'), 9),
      (nextval('conplanosistemaatributos_c129_sequencial_seq'), 17, (select c121_sequencial from conplanoinfocomplementar where c121_sigla = 'TDFR'), 10),
      (nextval('conplanosistemaatributos_c129_sequencial_seq'), 17, (select c121_sequencial from conplanoinfocomplementar where c121_sigla = 'GFR'), 11),
      (nextval('conplanosistemaatributos_c129_sequencial_seq'), 17, (select c121_sequencial from conplanoinfocomplementar where c121_sigla = 'EFR'), 12),
      (nextval('conplanosistemaatributos_c129_sequencial_seq'), 17, (select c121_sequencial from conplanoinfocomplementar where c121_sigla = 'IRP'), 13),
      (nextval('conplanosistemaatributos_c129_sequencial_seq'), 17, (select c121_sequencial from conplanoinfocomplementar where c121_sigla = 'UG'), 14);

SQL_UP
        );

    }


    public function down()
    {
        $this->execute('delete from conplanosistemaatributos where c129_conplanosistema = 17');
        $this->execute('delete from conplanosistema where c122_sequencial = 17');
    }
}
