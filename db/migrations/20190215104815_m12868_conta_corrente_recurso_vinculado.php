<?php

use Classes\PostgresMigration;

class M12868ContaCorrenteRecursoVinculado extends PostgresMigration
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

        $this->execute(<<<SQL

insert into conplanosistema values (28, 'Recurso Vinculado', 2);
insert into conplanoinfocomplementar
values (
           52,
           'RV',
           'Recurso Vinculado',
           'select c130_orctiporec from conlancamrecurso where c130_conlancam = codigo_lancamento and c130_conta = conta_reduzida and c130_natureza = natureza limit 1 ',
           'Código do recurso vinculado ao evento',
           'recurso_vinculado',
           'NI'
           );

insert into contabilidade.conplanosistemaatributos values (nextval('conplanosistemaatributos_c129_sequencial_seq'), 28, 52, 1);
SQL
        );
    }


    public function down()
    {
        $this->execute(<<<SQL
        
delete from contabilidade.conplanosistemaatributos where c129_conplanosistema = 28;
delete from contabilidade.conplanoinfocomplementar where c121_sequencial = 52;

delete from contabilidade.conplanosistema where c122_sequencial = 28;
SQL
);

    }
}
