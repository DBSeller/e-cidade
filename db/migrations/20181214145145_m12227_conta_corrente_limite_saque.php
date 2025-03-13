<?php

use Classes\PostgresMigration;

class M12227ContaCorrenteLimiteSaque extends PostgresMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function up()
    {


        $this->execute(
            <<<SQL_UP

insert into conplanosistema values (25, 'Limite de Saque', 2);

insert into conplanosistemaatributos values 
      (nextval('conplanosistemaatributos_c129_sequencial_seq'), 25, (select c121_sequencial from conplanoinfocomplementar where c121_nomepropriedade = 'recurso_identificador_uso'), 1),
      (nextval('conplanosistemaatributos_c129_sequencial_seq'), 25, (select c121_sequencial from conplanoinfocomplementar where c121_nomepropriedade = 'recurso_tipo_detalhamento'), 2),
      (nextval('conplanosistemaatributos_c129_sequencial_seq'), 25, (select c121_sequencial from conplanoinfocomplementar where c121_nomepropriedade = 'recurso_grupo'), 3),
      (nextval('conplanosistemaatributos_c129_sequencial_seq'), 25, (select c121_sequencial from conplanoinfocomplementar where c121_nomepropriedade = 'recurso_especificacao'), 4),
      (nextval('conplanosistemaatributos_c129_sequencial_seq'), 25, (select c121_sequencial from conplanoinfocomplementar where c121_nomepropriedade = 'detalhamento_fonte_recurso'), 5),
      (nextval('conplanosistemaatributos_c129_sequencial_seq'), 25, (select c121_sequencial from conplanoinfocomplementar where c121_sigla = 'UG'), 6),
      (nextval('conplanosistemaatributos_c129_sequencial_seq'), 25, (select c121_sequencial from conplanoinfocomplementar where c121_sigla = 'VP'), 7);

SQL_UP
        );

    }


    public function down()
    {
        $this->execute('delete from conplanosistemaatributos where c129_conplanosistema = 25');
        $this->execute('delete from conplanosistema where c122_sequencial = 25');
    }

}
