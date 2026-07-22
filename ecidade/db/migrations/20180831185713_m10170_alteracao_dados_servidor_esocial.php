<?php

use Classes\PostgresMigration;

class M10170AlteracaoDadosServidorEsocial extends PostgresMigration
{
    public function up()
    {

        $this->execute(
            <<<SQL_UP
insert into db_sysarquivo values (1010312, 'avaliacaogruporespostarhpessoalalteracao', 'avaliacaogruporespostarhpessoalalteracao', 'eso17', '2018-08-31', 'avaliacaogruporespostarhpessoalalteracao', 0, 'f', 'f', 'f', 'f' );
insert into db_sysarqmod values (81,1010312);
insert into db_syscampo values(1009928,'eso17_sequencial','int4','Sequencial','0', 'Sequencial',10,'f','f','f',1,'text','Sequencial');
insert into db_syscampo values(1009929,'eso17_avaliacaogruporesposta','int4','Grupo de Resposta','0', 'Grupo de Resposta',10,'f','f','f',1,'text','Grupo de Resposta');
insert into db_syscampo values(1009930,'eso17_rhpessoal','int4','Matrícula','0', 'Matrícula',10,'f','f','f',1,'text','Matrícula');
delete from db_sysarqcamp where codarq = 1010312;
insert into db_sysarqcamp values(1010312,1009928,1,0);
insert into db_sysarqcamp values(1010312,1009929,2,0);
insert into db_sysarqcamp values(1010312,1009930,3,0);
delete from db_sysprikey where codarq = 1010312;
insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010312,1009929,2,1009928);
insert into db_sysforkey values(1010312,1009930,1,1153,0);
insert into db_sysindices values(1008318,'avaliacaogruporespostarhpessoalalteracao_rhpessoal_in',1010312,'0');
insert into db_syscadind values(1008318,1009930,1);
insert into db_syssequencia values(1000761, 'avaliacaogruporespostarhpessoalalteracao_eso17_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
update db_sysarqcamp set codsequencia = 1000761 where codarq = 1010312 and codcam = 1009928;
insert into db_sysforkey values(1010312,1009929,1,2987,0);

CREATE SEQUENCE esocial.avaliacaogruporespostarhpessoalalteracao_eso17_sequencial_seq
INCREMENT 1
MINVALUE 1
MAXVALUE 9223372036854775807
START 1
CACHE 1;

CREATE TABLE esocial.avaliacaogruporespostarhpessoalalteracao(
eso17_sequencial      int4 NOT NULL,
eso17_avaliacaogruporesposta   int4 NOT NULL ,
eso17_rhpessoal   int4 not null,
CONSTRAINT avaliacaogruporespostarhpessoalalteracao_aval_pk PRIMARY KEY (eso17_avaliacaogruporesposta));

ALTER TABLE esocial.avaliacaogruporespostarhpessoalalteracao
ADD CONSTRAINT avaliacaogruporespostarhpessoalalteracao_avaliacaogruporesposta_fk FOREIGN KEY (eso17_avaliacaogruporesposta)
REFERENCES avaliacaogruporesposta;

ALTER TABLE esocial.avaliacaogruporespostarhpessoalalteracao
ADD CONSTRAINT avaliacaogruporespostarhpessoalalteracao_rhpessoal_fk FOREIGN KEY (eso17_rhpessoal)
REFERENCES rhpessoal;

CREATE  INDEX avaliacaogruporespostarhpessoalalteracao_rhpessoal_in ON avaliacaogruporespostarhpessoalalteracao(eso17_rhpessoal);
CREATE  INDEX avaliacaogruporesposta_avaliacaogruporesposta_in ON avaliacaogruporespostarhpessoalalteracao(eso17_avaliacaogruporesposta);


SQL_UP
        );

    }


    
    public function down()
    {

        $this->execute(<<<SQL_DOWN

delete from db_sysforkey where codarq = 1010312;
delete from db_sysarqcamp where codarq = 1010312;
delete from db_syssequencia where codsequencia = 1000761;
delete from db_syscadind where codcam in (1009928, 1009929, 1009930);
delete from db_sysindices where codarq = 1000761;
delete from db_sysforkey where codarq = 1000761;
delete from db_sysprikey where codarq = 1000761;
delete from db_syscampo where codcam in (1009928, 1009929, 1009930);
delete from db_sysarqmod where codarq = 1010312;
delete from db_sysarquivo where codarq = 1010312;

drop sequence avaliacaogruporespostarhpessoalalteracao_eso17_sequencial_seq;
drop table avaliacaogruporespostarhpessoalalteracao;

SQL_DOWN
);
    }
}
