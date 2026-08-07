<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M20496EstruturaVincularReceitaExtraPlanilhaComSlip extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sql = <<<SQL

insert into db_sysarquivo values (1010899, 'slipplacaixarec', 'slip das receitas Extra de planilha, diferente da placaixarecslip que guarda um grupo de receitas para um slip, está ira guardar os slips gerados automaticamente para cada receita EXTRA da planilha', 'k207', '2022-04-14', 'slip das receitas de planilha', 0, 'f', 'f', 'f', 'f' );
insert into db_sysarqmod values (5,1010899);

insert into db_syscampo values(1013991,'k207_sequencial','int4','Sequencial dos slips vinculados com receitas de uma planilha','0', 'Sequencial',10,'f','f','f',1,'text','Sequencial');
insert into db_syscampo values(1013992,'k207_placaixarec','int4','Sequencial da receita de uma planilha','0', 'Sequencial da receita de uma planilha',10,'f','f','f',1,'text','Sequencial da receita de uma planilha');
insert into db_syscampo values(1013993,'k207_slip','int4','Codigo de um Slip vinculado a uma receita extra de uma planilha','0', 'Codigo de um Slip',10,'f','f','f',1,'text','Codigo de um Slip');

insert into db_sysarqcamp values(1010899,1013991,1,0);
insert into db_sysarqcamp values(1010899,1013992,2,0);
insert into db_sysarqcamp values(1010899,1013993,3,0);

insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010899,1013991,1,1013991);

insert into db_sysforkey values(1010899,1013992,1,1024,0);
insert into db_sysforkey values(1010899,1013993,1,196,0);

insert into db_sysindices values(1008746,'k207_sequencial_in',1010899,'0');
insert into db_syscadind values(1008746,1013991,1);
insert into db_sysindices values(1008747,'k207_placaixarec_in',1010899,'0');
insert into db_syscadind values(1008747,1013992,1);
insert into db_sysindices values(1008748,'k207_slip_in',1010899,'0');
insert into db_syscadind values(1008748,1013993,1);

insert into db_syssequencia values(1001045, 'slipplacaixarec_k207_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
update db_sysarqcamp set codsequencia = 1001045 where codarq = 1010899 and codcam = 1013991;

CREATE SEQUENCE slipplacaixarec_k207_sequencial_seq
INCREMENT 1
MINVALUE 1
MAXVALUE 9223372036854775807
START 1
CACHE 1;

CREATE TABLE slipplacaixarec(
k207_sequencial		int4 NOT NULL default 0,
k207_placaixarec		int4 NOT NULL default 0,
k207_slip		int4 default 0,
CONSTRAINT slipplacaixarec_sequ_pk PRIMARY KEY (k207_sequencial));

ALTER TABLE slipplacaixarec
ADD CONSTRAINT slipplacaixarec_slip_fk FOREIGN KEY (k207_slip)
REFERENCES slip;

ALTER TABLE slipplacaixarec
ADD CONSTRAINT slipplacaixarec_placaixarec_fk FOREIGN KEY (k207_placaixarec)
REFERENCES placaixarec;


CREATE  INDEX k207_sequencial_in ON slipplacaixarec(k207_sequencial);
CREATE  INDEX k207_placaixarec_in ON slipplacaixarec(k207_placaixarec);
CREATE  INDEX k207_slip_in ON slipplacaixarec(k207_slip);

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

delete from db_syssequencia where codsequencia = 1001045;
delete from db_sysindices where codarq = 1010899;
delete from db_sysarqcamp where codarq = 1010899;
delete from db_sysprikey where codarq = 1010899;
delete from db_sysforkey where codarq = 1010899 and referen = 0;
delete from db_syscampo where codcam in (1013991, 1013992, 1013993);
delete from db_sysarqmod where codarq = 1010899;
delete from db_sysarquivo where codarq = 1010899;


DROP TABLE IF EXISTS slipplacaixarec CASCADE;
DROP SEQUENCE IF EXISTS slipplacaixarec_k207_sequencial_seq;




SQL;

        DB::connection()->getPdo()->exec($sql);
    }
}
