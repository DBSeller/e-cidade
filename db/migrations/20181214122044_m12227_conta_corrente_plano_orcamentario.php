<?php

use Classes\PostgresMigration;

class M12227ContaCorrentePlanoOrcamentario extends PostgresMigration
{
    public function up()
    {


        $this->execute("insert into conplanoinfocomplementar
values (nextval('conplanoinfocomplementar_c121_sequencial_seq'), 'PO', 'Plano Orçamentário', 'select o156_orcdotacaoplanoorcamentario  from conlancamemp inner join empempenho on c75_numemp = e60_numemp inner join empenho.empempaut on e61_numemp = e60_numemp inner join empautidot on e56_autori = e61_autori inner join planoorcamentariolinhapacto on e56_planoorcamentariolinhapacto = o156_sequencial where c75_codlan = codigo_lancamento', '', 'plano_orcamentario', 'NI');");
        $this->execute(
            <<<SQL_UP

insert into conplanosistema values (24, 'Plano orçamentário', 2);

insert into conplanosistemaatributos values 
      (nextval('conplanosistemaatributos_c129_sequencial_seq'), 24, (select c121_sequencial from conplanoinfocomplementar where c121_nomepropriedade = 'plano_orcamentario'), 1),
      (nextval('conplanosistemaatributos_c129_sequencial_seq'), 24, (select c121_sequencial from conplanoinfocomplementar where c121_sigla = 'UG'), 2);

SQL_UP
        );

    }


    public function down()
    {
        $this->execute('delete from conplanosistemaatributos where c129_conplanosistema = 24');
        $this->execute("delete from conplanoinfocomplementar where c121_nomepropriedade= 'plano_orcamentario'");
        $this->execute('delete from conplanosistema where c122_sequencial = 24');
    }


}
