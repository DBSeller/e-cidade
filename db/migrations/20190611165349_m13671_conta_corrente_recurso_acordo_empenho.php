<?php

use Classes\PostgresMigration;

class M13671ContaCorrenteRecursoAcordoEmpenho extends PostgresMigration
{

    public function up()
    {

        $this->execute(<<<SQL_UP
        
insert into conplanosistema values (31, 'Acordo / Empenho / Recurso', 2);
insert into conplanosistemaatributos
     values (nextval('conplanosistemaatributos_c129_sequencial_seq'), 31, 20, 1),
            (nextval('conplanosistemaatributos_c129_sequencial_seq'), 31, 21, 2),
            (nextval('conplanosistemaatributos_c129_sequencial_seq'), 31, 22, 3),
            (nextval('conplanosistemaatributos_c129_sequencial_seq'), 31, 3,  4),
            (nextval('conplanosistemaatributos_c129_sequencial_seq'), 31, 9,  5);

update conplanoinfocomplementar
   set c121_sql = 'select ac16_numero from conlancamemp inner join empempenhocontrato on e100_numemp = c75_numemp inner join acordo on ac16_sequencial = e100_acordo where c75_codlan = codigo_lancamento limit 1'
 where c121_sequencial = 20;

update conplanoinfocomplementar
   set c121_sql = 'select ac16_anousu from conlancamemp inner join empempenhocontrato on e100_numemp = c75_numemp inner join acordo on ac16_sequencial = e100_acordo where c75_codlan = codigo_lancamento limit 1'
where c121_sequencial = 21;


update conplanoinfocomplementar
   set c121_sql = 'select ac16_contratado from conlancamemp inner join empempenhocontrato on e100_numemp = c75_numemp inner join acordo on ac16_sequencial = e100_acordo where c75_codlan = codigo_lancamento limit 1'
where c121_sequencial = 22;

SQL_UP
);
    }

    public function down()
    {
        $this->execute("delete from conplanosistemaatributos where c129_conplanosistema = 31");
        $this->execute("delete from conplanosistema where c122_sequencial = 31");
    }

}
