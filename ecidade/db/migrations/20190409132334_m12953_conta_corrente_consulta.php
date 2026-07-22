<?php

use Classes\PostgresMigration;

class M12953ContaCorrenteConsulta extends PostgresMigration
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

insert into conplanosistema values (30, 'Fonte Resumida', 2);


insert into conplanosistemaatributos values 
      (nextval('conplanosistemaatributos_c129_sequencial_seq'), 30, (select c121_sequencial from conplanoinfocomplementar where c121_nomepropriedade = 'recurso_grupo'), 1),
      (nextval('conplanosistemaatributos_c129_sequencial_seq'), 30, (select c121_sequencial from conplanoinfocomplementar where c121_nomepropriedade = 'recurso_especificacao'), 2)
SQL_UP
        );

    }


    public function down()
    {
        $this->execute('delete from conplanosistemaatributos where c129_conplanosistema in (30)');
        $this->execute('delete from conplanosistema where c122_sequencial in(30)');
    }
}
