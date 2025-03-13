<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M20709CampoHistoricoMovimentoAgenda extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sql = <<<SQL


insert into db_sysarquivo values (1010915, 'empagemovhistorico', 'Historico do movimento para a forma DEB, demonstrado no historico da fatura do cartao de credito', 'e141', '2022-05-05', 'Historico do Movimento', 0, 'f', 'f', 'f', 'f' );
insert into db_sysarqmod values (38,1010915);

insert into db_syscampo values(1014067,'e141_sequencial','int4','Sequencial do Historico do Movimento','0', 'Sequencial do Historico do Movimento',10,'f','f','f',1,'text','Sequencial do Historico do Movimento');
insert into db_syscampo values(1014068,'e141_empagemov','int4','Movimento da Agenda','0', 'Movimento da Agenda',10,'f','f','f',1,'text','Movimento da Agenda');
insert into db_syscampo values(1014069,'e141_historico','varchar(255)','Historico do Movimento da agenda','', 'Historico do Movimento',255,'f','t','f',0,'text','Historico do Movimento');

insert into db_sysarqcamp values(1010915,1014067,1,0);
insert into db_sysarqcamp values(1010915,1014068,2,0);
insert into db_sysarqcamp values(1010915,1014069,3,0);

insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010915,1014067,1,1014067);
insert into db_sysforkey values(1010915,1014068,1,995,0);
insert into db_sysindices values(1008770,'e141_sequencial_in',1010915,'1');
insert into db_syscadind values(1008770,1014067,1);

insert into db_sysindices values(1008771,'e141_empagemov_in',1010915,'0');
insert into db_syscadind values(1008771,1014068,1);

insert into db_syssequencia values(1001059, 'empagemovhistorico_e141_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
update db_sysarqcamp set codsequencia = 1001059 where codarq = 1010915 and codcam = 1014067;


CREATE SEQUENCE empagemovhistorico_e141_sequencial_seq
INCREMENT 1
MINVALUE 1
MAXVALUE 9223372036854775807
START 1
CACHE 1;

CREATE TABLE empenho.empagemovhistorico(
e141_sequencial		int4 NOT NULL default nextval('empagemovhistorico_e141_sequencial_seq'::regclass),
e141_empagemov		int4 NOT NULL default 0,
e141_historico		varchar(255)  default null,
CONSTRAINT empagemovhistorico_sequ_pk PRIMARY KEY (e141_sequencial));

ALTER TABLE empagemovhistorico
ADD CONSTRAINT empagemovhistorico_empagemov_fk FOREIGN KEY (e141_empagemov)
REFERENCES empagemov;

CREATE UNIQUE INDEX e141_sequencial_in ON empagemovhistorico(e141_sequencial);
CREATE  INDEX e141_empagemov_in ON empagemovhistorico(e141_empagemov);


SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $sql = <<<SQL

delete from db_sysprikey where codarq = 1010915;
delete from db_sysforkey where codarq = 1010915 and referen = 0;
delete from db_sysforkey where codcam = 1014068;
delete from db_sysindices where codarq in (1010915);
delete from db_syscadind where codind = 1008770;
delete from db_sysarqcamp where codarq = 1010915;
delete from db_sysarqmod where codarq =1010915;
delete from db_syscampo where codcam in (1014067,1014068,1014069);
delete from db_sysarquivo where codarq =1010915;
delete from db_syssequencia where codsequencia = 1001059;

DROP TABLE IF EXISTS empagemovhistorico CASCADE;
DROP SEQUENCE IF EXISTS empagemovhistorico_e141_sequencial_seq;


SQL;
                DB::connection()->getPdo()->exec($sql);
    }
}
