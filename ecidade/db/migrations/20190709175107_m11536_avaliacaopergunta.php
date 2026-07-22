<?php

use Classes\PostgresMigration;

class M11536Avaliacaopergunta extends PostgresMigration
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
        $this->createDicionarioDados();
        $this->createEstrutura();
        $this->atualizarPerguntas();
    }

    public function down()
    {
        $this->dropDicionarioDados();
        $this->dropEstrutura();
    }
    
    public function createDicionarioDados()
    {
        $sql = <<<SQL
            
            insert into db_syscampo values(1010591,'db103_somenteleitura','bool','Identificador do campo para somente leitura','f', 'Campo Somente Leitura',1,'f','f','f',5,'text','Campo Somente Leitura');
            delete from db_sysarqcamp where codarq = 2983;
            insert into db_sysarqcamp values(2983,16915,1,1888);
            insert into db_sysarqcamp values(2983,17046,2,0);
            insert into db_sysarqcamp values(2983,16916,3,0);
            insert into db_sysarqcamp values(2983,16917,4,0);
            insert into db_sysarqcamp values(2983,19378,5,0);
            insert into db_sysarqcamp values(2983,16918,6,0);
            insert into db_sysarqcamp values(2983,16919,7,0);
            insert into db_sysarqcamp values(2983,17023,8,0);
            insert into db_sysarqcamp values(2983,21839,9,0);
            insert into db_sysarqcamp values(2983,21840,10,0);
            insert into db_sysarqcamp values(2983,1009307,11,0);
            insert into db_sysarqcamp values(2983,1009305,12,0);
            insert into db_sysarqcamp values(2983,1009304,13,0);
            insert into db_sysarqcamp values(2983,1009512,14,0);
            insert into db_sysarqcamp values(2983,1010591,15,0);

SQL;
        $this->execute($sql);
    }

    public function createEstrutura()
    {
        $sql = <<<SQL

            alter table habitacao.avaliacaopergunta add column db103_somenteleitura boolean default false;
SQL;
        $this->execute($sql);

    }

    public function dropDicionarioDados()
    {
        $sql = <<<SQL
            
            delete from db_sysarqcamp where codarq = 2983;
            delete from db_syscampo where codcam = 1010591;
SQL;
        $this->execute($sql);
    }

    public function dropEstrutura()
    {
        $sql = <<<SQL
        
            alter table habitacao.avaliacaopergunta drop db103_somenteleitura;
SQL;
        $this->execute($sql);
    }

    public function atualizarPerguntas()
    {
        $sql = <<<SQL_UP
            update avaliacaopergunta set db103_somenteleitura = true where db103_sequencial in(
              3000654, 3000655, 3000656, 3000657, 3000658, 3000659, 3000660, 3000661, 3000670, 3000671, 3000672
            );
            
            update avaliacaopergunta set db103_somenteleitura = true where db103_sequencial in(
              3000693, 3000694, 3000696, 3000697, 3000699, 3000806, 3000807, 3000859, 3000695
            );
            
            update avaliacaopergunta set db103_somenteleitura = true where db103_sequencial in(
              3000794, 3000795, 3000802, 3000825, 3000826, 3000830, 3000831, 3000847, 3000852
            );
SQL_UP;

        $this->execute($sql);
    }
}
