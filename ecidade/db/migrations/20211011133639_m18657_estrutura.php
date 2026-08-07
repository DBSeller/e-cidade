<?php

use Classes\PostgresMigration;

class M18657Estrutura extends PostgresMigration
{
    public function up()
    {
        $this->upMenu();
        $this->upDicionario();
        $this->upEstrutura();
    }

    public function down()
    {
        $this->downMenu();
        $this->downDicionario();
        $this->downEstrutura();
    }

    private function downMenu()
    {
        $this->execute(<<<SQL
delete from db_menu where id_item_filho = 228580 AND modulo = 1;
delete from db_itensmenu where id_item = 228580;
SQL
        );
    }

    private function upMenu()
    {
        $this->execute(<<<SQL
update db_itensmenu
set funcao = 'pla2_abas_rreo.php?anexo=8'
where id_item = 228476;
SQL
);

        $this->execute(<<<SQL
insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228580 ,'Templates Relatórios Legais' ,'Templates Relatórios Legais' ,'con4_upload_template004.php' ,'1' ,'1' ,'Templates Relatórios Legais' ,'true' );
insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 7598 ,228580 ,8 ,1 );
SQL
        );
    }

    private function upDicionario()
    {
        $this->execute(<<<SQL
insert into db_sysarquivo values (1010831, 'templaterelatorioslegais', 'Armazena o template do relatório legal para cada período do mesmo.', 'c138', '2021-10-11', 'Template', 0, 'f', 'f', 'f', 'f' );
insert into db_sysarqmod values (7,1010831);
insert into db_syscampo
values (1013458,'c138_id','int4','Código','0', 'Código',10,'f','f','f',1,'text','Código'),
       (1013462,'c138_orcparamrel','int4','Relatório Legal','0', 'Relatório',1,'f','f','f',1,'text','Relatório'),
       (1013463,'c138_periodo','int4','Período ','0', 'Período',10,'f','f','f',1,'text','Período'),
       (1013460,'c138_modelo','int4','Modelo de impressão, sedo: 0 - Não se aplica 1 - Modelo In13 2 - Modelo Porto Velho 3 - Modelo MDF','0', 'Modelo',10,'f','f','f',1,'text','Modelo'),
       (1013461,'c138_path','text','Caminho do arquivo dentro do e-cidade','', 'File Path',1,'f','t','f',0,'text','File Path');

insert into db_sysarqcamp
values (1010831,1013462,2,0),
       (1010831,1013463,3,0),
       (1010831,1013460,4,0),
       (1010831,1013461,5,0);

insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010831,1013458,1,1013458);

insert into db_sysforkey
values (1010831,1013462,1,901,0),
       (1010831,1013463,1,2480,0);

insert into db_sysindices values(1008693,'templaterelatorioslegais_relatorio_periodo_modelo_in',1010831,'1');
insert into db_syscadind
values (1008693,1013463,1),
       (1008693,1013462,2),
       (1008693,1013460,3);

insert into db_syssequencia values(1001016, 'templaterelatorioslegais_c138_id_seq', 1, 1, 9223372036854775807, 1, 1);
update db_sysarqcamp set codsequencia = 1001016 where codarq = 1010831 and codcam = 1013458;
SQL
        );
    }

    private function upEstrutura()
    {
        $this->execute(<<<SQL
CREATE TABLE configuracoes.templaterelatorioslegais(
    c138_id	serial primary key,
    c138_orcparamrel int,
    c138_periodo int,
    c138_modelo int,
    c138_path text,
    foreign key (c138_orcparamrel) references orcamento.orcparamrel on delete cascade,
    foreign key (c138_periodo) references configuracoes.periodo on delete cascade
);
CREATE INDEX templaterelatorioslegais_orcparamrel_in ON templaterelatorioslegais(c138_orcparamrel);
CREATE INDEX templaterelatorioslegais_periodo_in ON templaterelatorioslegais(c138_periodo);
CREATE UNIQUE INDEX templaterelatorioslegais_relatorio_periodo_modelo_in
    ON templaterelatorioslegais(c138_periodo,c138_orcparamrel,c138_modelo);

select configuracoes.fc_auditoria_cria_funcao('configuracoes.templaterelatorioslegais');
SQL
        );
    }

    private function downDicionario()
    {
        $this->execute(<<<SQL
delete from db_sysprikey where codarq = 1010831;
delete from db_sysforkey where codarq = 1010831;
delete from db_sysindices where codind = 1008693;
delete from db_syscadind where codind = 1008693;
delete from db_syssequencia where codsequencia = 1001016;
delete from db_sysarqcamp where codarq = 1010831;
delete from db_syscampo where codcam in (1013458, 1013462, 1013463, 1013460, 1013461);
delete from db_sysarqmod where codarq = 1010831;
delete from db_sysarquivo where codarq = 1010831;
SQL
        );
    }

    private function downEstrutura()
    {
        $this->execute(<<<SQL
DROP TABLE IF EXISTS configuracoes.templaterelatorioslegais CASCADE;
SQL
        );
    }
}
