<?php

use Classes\PostgresMigration;

class M16054TabelaOrigemComplementoFonte extends PostgresMigration
{

    public function down()
    {
        $this->execute(<<<SQL_DOWN

drop table if exists orcamento.origemcomplementorecurso;
delete from db_sysforkey where codarq = 1010581;
delete from db_sysindices where codarq = 1010581;
delete from db_syscadind where codcam in (1011346,1011347,1011348,1011608,1011610);
delete from db_sysprikey where codarq = 1010581;
delete from db_syssequencia where codsequencia = 1000922;
delete from db_sysarqcamp where codarq = 1010581;
delete from db_sysarqmod where codarq = 1010581;
delete from db_sysarquivo where codarq = 1010581;
delete from db_syscampo where codcam in (1011346,1011347,1011348,1011608,1011610);




SQL_DOWN
);
    }

    public function up()
    {

        $this->execute(<<<SQL_UP

insert into db_sysarquivo values (1010581, 'origemcomplementorecurso', 'origemcomplementorecurso', 'o206', '2020-06-17', 'origemcomplementorecurso', 0, 'f', 'f', 'f', 'f' );
insert into db_sysarqmod values (35,1010581);
insert into db_syscampo values(1011346,'o206_sequencial','int4','Sequencial','0', 'Sequencial',10,'f','f','f',1,'text','Sequencial');
insert into db_syscampo values(1011347,'o206_origem','int4','origem','0', 'Origem',10,'f','f','f',1,'text','Origem');
insert into db_syscampo values(1011348,'o206_numero','int4','Número','0', 'Número',10,'f','f','f',1,'text','Número');
insert into db_syscampo values(1011608,'o206_recurso','int4','Recurso','0', 'Recurso',10,'f','f','f',1,'text','Recurso');
insert into db_syscampo values(1011610,'o206_complementorecurso','int4','Complemento da Fonte de Recurso','0', 'Complemento da Fonte de Recurso',10,'f','f','f',1,'text','Complemento da Fonte de Recurso');
delete from db_sysarqcamp where codarq = 1010581;
insert into db_sysarqcamp values(1010581,1011346,1,1000922);
insert into db_sysarqcamp values(1010581,1011347,2,0);
insert into db_sysarqcamp values(1010581,1011348,3,0);
insert into db_sysarqcamp values(1010581,1011608,4,0);
insert into db_sysarqcamp values(1010581,1011610,5,0);
delete from db_sysprikey where codarq = 1010581;
insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010581,1011346,1,1011348);
insert into db_sysindices values(1008580,'origemcomplementorecurso_origem_in',1010581,'0');
insert into db_syscadind values(1008580,1011347,1);
insert into db_sysindices values(1008581,'origemcomplementorecurso_numero_in',1010581,'0');
insert into db_syscadind values(1008581,1011348,1);
insert into db_sysindices values(1008582,'origemcomplementorecurso_recurso_in',1010581,'0');
insert into db_syscadind values(1008582,1011608,1);
insert into db_sysindices values(1008583,'origemcomplementorecurso_complemento_in',1010581,'0');
insert into db_syscadind values(1008583,1011610,1);
insert into db_syssequencia values(1000922, 'origemcomplementorecurso_o206_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
update db_sysarqcamp set codsequencia = 1000922 where codarq = 1010581 and codcam = 1011346;
insert into db_sysforkey values(1010581,1011608,1,749,0);


create table orcamento.origemcomplementorecurso(
    o206_sequencial serial primary key ,
    o206_origem integer not null,
    o206_numero integer not null,
    o206_recurso integer not null,
    o206_complementorecurso integer not null
);

CREATE INDEX origemcomplementorecurso_origem_in ON orcamento.origemcomplementorecurso(o206_origem);
CREATE INDEX origemcomplementorecurso_numero_in ON orcamento.origemcomplementorecurso(o206_numero);
CREATE INDEX origemcomplementorecurso_recurso_in ON orcamento.origemcomplementorecurso(o206_recurso);
CREATE INDEX origemcomplementorecurso_complemento_in ON orcamento.origemcomplementorecurso(o206_complementorecurso);

ALTER TABLE orcamento.origemcomplementorecurso ADD CONSTRAINT origemcomplementorecurso_recurso_fk FOREIGN KEY (o206_recurso) REFERENCES orcamento.orctiporec;

SQL_UP
        );
    }
}
