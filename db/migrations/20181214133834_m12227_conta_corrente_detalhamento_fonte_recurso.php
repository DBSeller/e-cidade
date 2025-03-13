<?php

use Classes\PostgresMigration;

class M12227ContaCorrenteDetalhamentoFonteRecurso extends PostgresMigration
{

    public function up()
    {

        $this->execute("insert into conplanoinfocomplementar values (nextval('conplanoinfocomplementar_c121_sequencial_seq'), 'DFR', 'Detalhamento da Fonte de Recurso', 'select c08_concarpeculiar from conlancamconcarpeculiar where c08_codlan = codigo_lancamento limit 1', '', 'detalhamento_fonte_recurso', 'NI')");

        $this->execute(<<<SQL_UP
insert into conplanosistema values (23, 'Fonte Detalhada', 2);
insert into conplanosistemaatributos values 
      (nextval('conplanosistemaatributos_c129_sequencial_seq'), 23, (select c121_sequencial from conplanoinfocomplementar where c121_sigla = 'IUFR'), 1),
      (nextval('conplanosistemaatributos_c129_sequencial_seq'), 23, (select c121_sequencial from conplanoinfocomplementar where c121_sigla = 'GFR'), 2),
      (nextval('conplanosistemaatributos_c129_sequencial_seq'), 23, (select c121_sequencial from conplanoinfocomplementar where c121_sigla = 'EFR'), 3),
      (nextval('conplanosistemaatributos_c129_sequencial_seq'), 23, (select c121_sequencial from conplanoinfocomplementar where c121_sigla = 'TDFR'), 4),
      (nextval('conplanosistemaatributos_c129_sequencial_seq'), 23, (select c121_sequencial from conplanoinfocomplementar where c121_sigla = 'DFR'), 5),
      (nextval('conplanosistemaatributos_c129_sequencial_seq'), 23, (select c121_sequencial from conplanoinfocomplementar where c121_sigla = 'UG'), 6);

SQL_UP
        );
    }


    public function down()
    {

        $this->execute('delete from conplanosistemaatributos where c129_conplanosistema = 23');
        $this->execute('delete from conplanosistema where c122_sequencial = 23');
        $this->execute("delete from conplanoinfocomplementar where c121_sigla = 'DFR' ");

    }
}
