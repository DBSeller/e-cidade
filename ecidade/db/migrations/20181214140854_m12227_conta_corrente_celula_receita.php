<?php

use Classes\PostgresMigration;

class M12227ContaCorrenteCelulaReceita extends PostgresMigration
{
    public function up()
    {


        $this->execute("insert into conplanoinfocomplementar
values (nextval('conplanoinfocomplementar_c121_sequencial_seq'), 'NREC', 'Natureza da Receita', 'select o57_fonte from conlancamrec inner join orcreceita on c74_codrec =  o70_codrec and c74_anousu = o70_anousu inner join orcfontes on o57_codfon = o70_codfon and o70_anousu =  o57_anousu where c74_codlan = codigo_lancamento limit 1', '', 'natureza_receita', 'NI');");

        $this->execute(
            <<<SQL_UP

insert into conplanosistema values (26, 'Celula da Receita', 2);

insert into conplanosistemaatributos values 
      (nextval('conplanosistemaatributos_c129_sequencial_seq'), 26, (select c121_sequencial from conplanoinfocomplementar where c121_nomepropriedade = 'recurso_identificador_uso'), 1),
      (nextval('conplanosistemaatributos_c129_sequencial_seq'), 26, (select c121_sequencial from conplanoinfocomplementar where c121_nomepropriedade = 'recurso_tipo_detalhamento'), 2),
      (nextval('conplanosistemaatributos_c129_sequencial_seq'), 26, (select c121_sequencial from conplanoinfocomplementar where c121_nomepropriedade = 'recurso_grupo'), 3),
      (nextval('conplanosistemaatributos_c129_sequencial_seq'), 26, (select c121_sequencial from conplanoinfocomplementar where c121_nomepropriedade = 'recurso_especificacao'), 4),
      (nextval('conplanosistemaatributos_c129_sequencial_seq'), 26, (select c121_sequencial from conplanoinfocomplementar where c121_nomepropriedade = 'detalhamento_fonte_recurso'), 5),
      (nextval('conplanosistemaatributos_c129_sequencial_seq'), 26, (select c121_sequencial from conplanoinfocomplementar where c121_nomepropriedade = 'natureza_receita'), 7),
      (nextval('conplanosistemaatributos_c129_sequencial_seq'), 26, (select c121_sequencial from conplanoinfocomplementar where c121_sigla = 'UG'), 7);

SQL_UP
        );

    }


    public function down()
    {
        $this->execute('delete from conplanosistemaatributos where c129_conplanosistema = 26');
        $this->execute("delete from conplanoinfocomplementar where c121_nomepropriedade= 'natureza_receita'");
        $this->execute('delete from conplanosistema where c122_sequencial = 26');
    }
}
