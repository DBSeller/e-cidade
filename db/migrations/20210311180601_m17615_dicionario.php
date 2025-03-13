<?php

use Classes\PostgresMigration;

class M17615Dicionario extends PostgresMigration
{
    public function up()
    {
        // remove campos antigos
        $this->execute(<<<SQL
delete from db_syscadind  where codcam in (1012574, 1012575, 1012576, 1012577, 1012578, 1012579, 1012508, 1012509, 1012510, 1012511, 1012512, 1012513, 1012514, 1012515, 1012516, 1012517, 1012518);
delete from db_sysprikey  where codarq in (1010708, 1010722);
delete from db_sysforkey  where codarq in (1010708, 1010722);
delete from db_sysindices where codarq in (1010708, 1010722);
delete from db_sysarqcamp where codarq in (1010708, 1010722);
delete from db_syscampo where codcam in (1012574, 1012575, 1012576, 1012577, 1012578, 1012579, 1012508, 1012509, 1012510, 1012511, 1012512, 1012513, 1012514, 1012515, 1012516, 1012517, 1012518);
SQL
        );

        $this->execute(<<<SQL
insert into db_syscampo
values (1012921,'planejamento_id','int4','FK com planejamento','0', 'Planejamento',10,'f','f','f',1,'text','Planejamento'),
       (1012922,'anoorcamento','int4','Ano do orçamento','0', 'Ano do orçamento',10,'f','f','f',1,'text','Ano do orçamento'),
       (1012923,'orcfontes_id','int4','FK orcfontes','0', 'Fonte',10,'f','f','f',1,'text','Fonte'),
       (1012924,'recurso_id','int4','FK orctiporec','0', 'Recurso',10,'f','f','f',1,'text','Recurso'),
       (1012925,'instituicao_id','int4','Instituição','0', 'Instituição',10,'f','f','f',1,'text','Instituição'),
       (1012926,'concarpeculiar_id','varchar(10)','Característica Peculiar','0', 'Característica Peculiar',10,'f','f','f',1,'text','Característica Peculiar'),
       (1012927,'orcorgao_id','int4','Orgão','0', 'Orgão',10,'f','f','f',1,'text','Orgão'),
       (1012928,'orcunidade_id','int4','Unidade','0', 'Unidade',10,'f','f','f',1,'text','Unidade'),
       (1012929,'esferaorcamentaria','int4','Esfera Orçamentária','0', 'Esfera Orçamentária',10,'f','f','f',1,'text','Esfera Orçamentária'),
       (1012930,'inclusaomanual','bool','Inclusão Manual','f', 'Inclusão Manual',1,'f','f','f',5,'text','Inclusão Manual');

insert into db_sysarqcamp
values (1010722,1011345,1,0),
       (1010722,1012921,2,0),
       (1010722,1012922,3,0),
       (1010722,1012923,4,0),
       (1010722,15983,5,0),
       (1010722,15985,6,0),
       (1010722,1012583,7,0),
       (1010722,1012584,8,0),
       (1010708,1011345,1,0),
       (1010708,1012921,2,0),
       (1010708,1012922,3,0),
       (1010708,1012923,4,0),
       (1010708,1012924,5,0),
       (1010708,1012925,6,0),
       (1010708,1012926,7,0),
       (1010708,1012927,8,0),
       (1010708,1012928,9,0),
       (1010708,1012929,10,0),
       (1010708,1012930,11,0),
       (1010708,1012583,12,0),
       (1010708,1012584,13,0);

insert into db_sysprikey (codarq,codcam,sequen,camiden)
values (1010722,1011345,1,1011345),
       (1010708,1011345,1,1011345);

insert into db_sysforkey
values (1010722,1012921,1,1010702,0),
       (1010722,1012923,1,755,0),
       (1010722,1012922,2,755,0),
       (1010708,1012921,1,1010702,0),
       (1010708,1012923,1,755,0),
       (1010708,1012922,2,755,0),
       (1010708,1012924,1,749,0),
       (1010708,1012925,1,83,0),
       (1010708,1012926,1,1862,0),
       (1010708,1012922,1,757,0),
       (1010708,1012927,2,757,0),
       (1010708,1012928,3,757,0);
SQL
        );
    }

    public function down()
    {
        $this->execute(<<<SQL
delete from db_sysprikey where codarq in (1010708, 1010722);
delete from db_sysforkey  where codarq in (1010708, 1010722);
delete from db_sysindices where codarq in (1010708, 1010722);
delete from db_sysarqcamp where codarq in (1010708, 1010722);
delete from db_sysarqcamp where codarq in (1010708, 1010722);
delete from db_syscampo where codcam in (1012921, 1012922, 1012923, 1012924, 1012925, 1012926, 1012927, 1012928, 1012929, 1012930);
SQL
        );
    }
}
