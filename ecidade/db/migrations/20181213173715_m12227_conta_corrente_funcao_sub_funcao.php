<?php

use Classes\PostgresMigration;

class M12227ContaCorrenteFuncaoSubFuncao extends PostgresMigration
{

    public function up()
    {

        $this->execute(
            <<<SQL_UP
insert into conplanosistema values (18, 'Função e Subfunção', 2);

insert into conplanosistemaatributos values 
      (nextval('conplanosistemaatributos_c129_sequencial_seq'), 18, (select c121_sequencial from conplanoinfocomplementar where c121_sigla = 'FUN'), 1),
      (nextval('conplanosistemaatributos_c129_sequencial_seq'), 18, (select c121_sequencial from conplanoinfocomplementar where c121_sigla = 'SUBF'), 2),
      (nextval('conplanosistemaatributos_c129_sequencial_seq'), 18, (select c121_sequencial from conplanoinfocomplementar where c121_sigla = 'UG'), 3);
SQL_UP
        );
    }

    public function down()
    {

        $this->execute("delete from conplanosistemaatributos where c129_conplanosistema = 18;");
        $this->execute("delete from conplanosistema where c122_sequencial = 18;");
    }
}