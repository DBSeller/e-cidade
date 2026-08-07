<?php

use Classes\PostgresMigration;

class M10181TabelaResposta extends PostgresMigration
{
    public function up()
    {
        $pre = <<<STRING
insert into db_sysarquivo values (1010311, 'avaliacaogruporespostatsveinicial', 'Grupo de resposta do tsve inicial(trabalhador sem vínculo)', 'eso16', '2018-08-29', 'Grupo de resposta do tsve inicial', 0, 'f', 'f', 'f', 'f' );
insert into db_sysarqmod values (81,1010311);
insert into db_syscampo values(1009924,'eso16_sequencial','int4','Sequencial','0', 'Sequencial',10,'f','f','t',1,'text','Sequencial');
insert into db_syscampo values(1009925,'eso16_avaliacaogruporesposta','int4','Avaliação Grupo Resposta','0', 'Avaliação Grupo Resposta',10,'f','f','f',1,'text','Avaliação Grupo Resposta');
insert into db_syscampo values(1009926,'eso16_rhpessoal','int4','Trabalhador','0', 'Trabalhador',10,'f','f','f',1,'text','Trabalhador');
delete from db_sysarqcamp where codarq = 1010311;
insert into db_sysarqcamp values(1010311,1009924,1,0);
insert into db_sysarqcamp values(1010311,1009925,2,0);
insert into db_sysarqcamp values(1010311,1009926,3,0);
insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010311,1009924,1,1009926);
delete from db_sysforkey where codarq = 1010311 and referen = 0;
insert into db_sysforkey values(1010311,1009926,1,1153,0);
insert into db_sysforkey values(1010311,1009925,1,2987,0);
insert into db_sysindices values(1008317,'avaliacaogruporespostatsveinicial_avaliacaogruporesposta_rhpessoal_in',1010311,'1');
insert into db_syscadind values(1008317,1009925,1);
insert into db_syscadind values(1008317,1009926,2);
insert into db_syssequencia values(1000760, 'avaliacaogruporespostatsveinicial_eso16_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
update db_sysarqcamp set codsequencia = 1000760 where codarq = 1010311 and codcam = 1009924;
STRING;

        $this->execute($pre);

        $ddl = <<<STRING
CREATE SEQUENCE esocial.avaliacaogruporespostatsveinicial_eso16_sequencial_seq
INCREMENT 1
MINVALUE 1
MAXVALUE 9223372036854775807
START 1
CACHE 1;

CREATE TABLE esocial.avaliacaogruporespostatsveinicial(
eso16_sequencial		int4 NOT NULL default 0,
eso16_avaliacaogruporesposta		int4 NOT NULL default 0,
eso16_rhpessoal		int4 default 0,
CONSTRAINT avaliacaogruporespostatsveinicial_sequ_pk PRIMARY KEY (eso16_sequencial));


ALTER TABLE esocial.avaliacaogruporespostatsveinicial
ADD CONSTRAINT avaliacaogruporespostatsveinicial_avaliacaogruporesposta_fk FOREIGN KEY (eso16_avaliacaogruporesposta)
REFERENCES avaliacaogruporesposta;

ALTER TABLE esocial.avaliacaogruporespostatsveinicial
ADD CONSTRAINT avaliacaogruporespostatsveinicial_rhpessoal_fk FOREIGN KEY (eso16_rhpessoal)
REFERENCES rhpessoal;


CREATE UNIQUE INDEX avaliacaogruporespostatsveinicial_avaliacaogruporesposta_rhpessoal_in ON avaliacaogruporespostatsveinicial(eso16_avaliacaogruporesposta,eso16_rhpessoal);
STRING;

        $this->execute($ddl);
    }

    public function down()
    {
        $pre = <<<STRING
delete from db_syssequencia where codsequencia = 1000760;
delete from db_syscadind where codind = 1008317;
delete from db_sysindices where codind = 1008317;
delete from db_sysforkey where codarq = 1010311;
delete from db_sysprikey where codarq = 1010311;
delete from db_sysarqcamp where codarq = 1010311;
delete from db_syscampo where codcam in (1009924,1009925,1009926);
delete from db_sysarqmod where codarq = 1010311;
delete from db_sysarquivo where codarq = 1010311;
STRING;

        $this->execute($pre);

        $ddl = <<<STRING
DROP TABLE IF EXISTS esocial.avaliacaogruporespostatsveinicial CASCADE;
DROP SEQUENCE IF EXISTS esocial.avaliacaogruporespostatsveinicial_eso16_sequencial_seq;
STRING;

        $this->execute($ddl);
    }
}
