<?php

use Classes\PostgresMigration;

class M18135MenuConfiguracao extends PostgresMigration
{
    public function up()
    {
        $this->dicionarioUp();
        $this->estruturaUp();
    }

    public function down()
    {
        // menu
        $this->execute(<<<SQL
delete from db_menu where id_item_filho = 228500 AND modulo = 228358;
delete from db_itensmenu where id_item = 228500;
SQL
        );

        $this->execute(<<<SQL
        delete from db_sysarqcamp where codarq = 1010797;
        delete from db_sysprikey where codarq = 1010797;
        delete from db_syscampo where codcam = 1013239;
        delete from db_sysarqmod where codarq = 1010797;
        delete from db_sysarquivo where codarq = 1010797;
SQL
        );

        // ddl
        $this->execute(<<<SQL
        drop table if exists planejamento.planejamentoconfiguracao;
SQL
        );
    }

    public function dicionarioUp()
    {
        $this->execute(<<<SQL
insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228500 ,'Configurações' ,'Configurações' ,'pla4_configuracao.php' ,'1' ,'1' ,'Configurações do módulo planejamento' ,'true' );
insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 228365 ,228500 ,5 ,228358 );
SQL
        );
        // dicionario tabela
        $this->execute(<<<SQL
insert into db_sysarquivo values (1010797, 'planejamentoconfiguracao', 'Configuração', 'pl39', '2021-05-12', 'Configuração', 0, 'f', 'f', 'f', 'f' );
insert into db_sysarqmod values (85,1010797);
insert into db_syscampo values(1013239,'apenas_valor_analitico','bool','Informar valores apenas de forma analítica','f', 'Apenas valor Anaítico',1,'f','f','f',5,'text','Apenas valor Anaítico');
insert into db_sysarqcamp values(1010797,1011345,1,0);
insert into db_sysarqcamp values(1010797,1013239,2,0);
insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010797,1011345,1,1011345);
SQL
        );
    }

    public function estruturaUp()
    {
        $this->execute(<<<SQL
create table planejamento.planejamentoconfiguracao (
    id serial primary key,
    apenas_valor_analitico boolean default false
);

insert into planejamento.planejamentoconfiguracao (apenas_valor_analitico) values (false);
SQL
        );



    }
}
