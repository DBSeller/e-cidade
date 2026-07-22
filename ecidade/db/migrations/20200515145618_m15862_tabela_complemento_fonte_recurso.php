<?php

use Classes\PostgresMigration;

class M15862TabelaComplementoFonteRecurso extends PostgresMigration
{

    public function up()
    {
        $this->execute(<<<SQL_UP

insert into db_syscampo values(1011274,'o200_sequencial','int4','sigla','0', 'código',10,'f','f','f',1,'text','código');
insert into db_syscampo values(1011275,'o200_descricao','varchar(200)','descrição','', 'descrição',100,'f','t','f',0,'text','descrição');
insert into db_syscampo values(1011277,'o200_msc','bool','utilizado na msc','f', 'msc',1,'f','f','f',5,'text','msc');
insert into db_sysarquivo values (1010561, 'complementofonterecurso', 'complementofonterecurso', 'o200', '2020-05-15', 'complementofonterecurso', 0, 'f', 'f', 'f', 'f' );
insert into db_sysarqmod values (35,1010561);
delete from db_sysarqcamp where codarq = 1010561;
insert into db_sysarqcamp values(1010561,1011274,1,0);
insert into db_sysarqcamp values(1010561,1011275,2,0);
insert into db_sysarqcamp values(1010561,1011277,3,0);
delete from db_sysprikey where codarq = 1010561;
insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010561,1011274,1,1011275);


create table orcamento.complementofonterecurso(
    o200_sequencial integer not null primary key,
    o200_descricao varchar(200) not null,
    o200_msc boolean default false
);
create index complementofonterecurso_sequencial_in on orcamento.complementofonterecurso(o200_sequencial);

insert into orcamento.complementofonterecurso values (3110, 'Emendas Parlamentares Individuais', true);
insert into orcamento.complementofonterecurso values (3120, 'Emendas Parlamentares Bancada', true);

SQL_UP
);

        $row = $this->fetchRow("select uf from db_config order by codigo limit 1;");
        if (strtoupper($row['uf']) === 'RS') {

            $this->execute(<<<SQL_RS
insert into orcamento.complementofonterecurso values (3140, 'Emendas Parlamentares Individuais - COVID 19', false);
insert into orcamento.complementofonterecurso values (3150, 'Emendas Parlamentares Bancada - COVID 19', false);
insert into orcamento.complementofonterecurso values (3160, 'COVID 19', false);
SQL_RS
);
        }
    }



    public function down()
    {

        $this->execute(<<<SQL_DOWN

drop table if exists orcamento.complementofonterecurso;
delete from db_sysprikey where codarq = 1010561;
delete from db_syssequencia where codsequencia = 1000909;
delete from db_sysarqcamp where codarq = 1010561;
delete from db_sysarqmod where codarq = 1010561;
delete from db_sysarquivo where codarq = 1010561;
delete from db_syscampo where codcam in (1011274, 1011275, 1011277);


SQL_DOWN
);
    }
}
