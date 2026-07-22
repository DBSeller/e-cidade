<?php

use Classes\PostgresMigration;

class M14347CamposMei extends PostgresMigration
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
insert into db_syscampo    values(1010753,'q71_permitemei','bool','Permite MEI','f', 'Permite MEI',1,'f','f','f',5,'text','Permite MEI');
insert into db_syscampo    values(1010754,'q71_classificacaorisco','char(1)','Classificação de Risco','', 'Classificação de Risco',1,'f','t','f',0,'text','Classificação de Risco');
insert into db_syscampodef values(1010754,'B','Baixo');
insert into db_syscampodef values(1010754,'M','Médio');
insert into db_syscampodef values(1010754,'A','Alto');
insert into db_syscampo    values(1010755,'rh70_classificacaorisco','char(1)','Classificação de Risco','', 'Classificação de Risco',1,'f','t','f',0,'text','Classificação de Risco');
insert into db_syscampodef values(1010755,'B','Baixo');
insert into db_syscampodef values(1010755,'M','Médio');
insert into db_syscampodef values(1010755,'A','Alto');

insert into db_sysarqcamp  values(1752,1010754,4,0);
insert into db_sysarqcamp  values(1752,1010753,5,0);

insert into db_sysarqcamp  values(1756,1010755,5,0);

alter table cnae add q71_permitemei boolean default false;
alter table cnae add q71_classificacaorisco char(1);
alter table pessoal.rhcbo add rh70_classificacaorisco char(1);
SQL
        );

    }

    public function down()
    {
        $this->execute('delete from db_syscampodef where codcam in(1010753,1010754, 1010755)');
        $this->execute('delete from db_sysarqcamp where codcam in(1010753,1010754, 1010755)');
        $this->execute('delete from db_syscampo where codcam in(1010753,1010754, 1010755)');

        $this->execute('alter table cnae add q71_permitemei boolean default false;');
        $this->execute('alter table cnae add q71_classificacaorisco char(1);');
        $this->execute('alter table pessoal.rhcbo add rh70_classificacaorisco char(1);');
    }
}
