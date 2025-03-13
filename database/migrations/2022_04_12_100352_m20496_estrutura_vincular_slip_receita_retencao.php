<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M20496EstruturaVincularSlipReceitaRetencao extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        $sql = <<<SQL


insert into db_sysarquivo values (1010897, 'slipretencaoreceitas', 'Slip de Receitas Extras de Retencao, que são gerados automaticamente', 'k206', '2022-04-12', 'Slip de Receita de Retencao', 0, 'f', 'f', 'f', 'f' );
insert into db_sysarqmod values (5,1010897);

insert into db_sysarqarq values(0,1010897);

insert into db_syscampo values(1013977,'k206_sequencial','int4','Sequencial','0', 'Sequencial',10,'f','f','f',1,'text','Sequencial');
insert into db_syscampo values(1013978,'k206_retencaoreceitas','int4','Receitas da Retenção','0', 'Receitas da Retenção',10,'f','f','f',1,'text','Receitas da Retenção');
insert into db_syscampo values(1013979,'k206_slip','int4','Slip','0', 'Slip',10,'f','f','f',1,'text','Slip');

insert into db_sysarqcamp values(1010897,1013977,1,0);
insert into db_sysarqcamp values(1010897,1013978,2,0);
insert into db_sysarqcamp values(1010897,1013979,3,0);

insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010897,1013977,1,1013977);

insert into db_sysforkey values(1010897,1013979,1,196,0);
insert into db_sysforkey values(1010897,1013978,1,2116,0);

insert into db_sysindices values(1008742,'k206_sequencial_in',1010897,'1');
insert into db_syscadind values(1008742,1013977,1);
insert into db_sysindices values(1008743,'k206_retencaoreceitas_in',1010897,'0');
insert into db_syscadind values(1008743,1013978,1);
insert into db_sysindices values(1008744,'k206_slip_in',1010897,'0');
insert into db_syscadind values(1008744,1013979,1);

insert into db_syssequencia values(1001044, 'slipretencaoreceitas_k206_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
update db_sysarqcamp set codsequencia = 1001044 where codarq = 1010897 and codcam = 1013977;



CREATE SEQUENCE slipretencaoreceitas_k206_sequencial_seq
INCREMENT 1
MINVALUE 1
MAXVALUE 9223372036854775807
START 1
CACHE 1;

CREATE TABLE slipretencaoreceitas(
k206_sequencial		int4 NOT NULL default nextval('slipretencaoreceitas_k206_sequencial_seq'::regclass),
k206_retencaoreceitas		int4 NOT NULL default 0,
k206_slip		int4 default 0,
CONSTRAINT slipretencaoreceitas_sequ_pk PRIMARY KEY (k206_sequencial));

ALTER TABLE slipretencaoreceitas
ADD CONSTRAINT slipretencaoreceitas_slip_fk FOREIGN KEY (k206_slip)
REFERENCES slip;

ALTER TABLE slipretencaoreceitas
ADD CONSTRAINT slipretencaoreceitas_retencaoreceitas_fk FOREIGN KEY (k206_retencaoreceitas)
REFERENCES retencaoreceitas;

CREATE UNIQUE INDEX k206_sequencial_in ON slipretencaoreceitas(k206_sequencial);

CREATE  INDEX k206_retencaoreceitas_in ON slipretencaoreceitas(k206_retencaoreceitas);

CREATE  INDEX k206_slip_in ON slipretencaoreceitas(k206_slip);



-- parametro
insert into db_syscampo values(1013980,'k29_gerarslipautomaticoreceitaretencao','bool','Slip Automat. receita retencao','f', 'Slip Automat. receita retencao',1,'f','f','f',5,'text','Slip Automat. receita retencao');
insert into db_sysarqcamp values(1503,1013980,13,0);
alter table caiparametro add COLUMN k29_gerarslipautomaticoreceitaretencao boolean default false;






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

delete from db_sysarqcamp where codarq = 1010897;
delete from db_sysprikey where codarq = 1010897;
delete from db_sysforkey where codarq = 1010897 and referen = 0;
delete from db_sysforkey where codarq = 1010897 and referen = 0;

delete from db_syscampo where codcam in (1013977, 1013978, 1013979);


DROP TABLE IF EXISTS slipretencaoreceitas CASCADE;
DROP SEQUENCE IF EXISTS slipretencaoreceitas_k206_sequencial_seq;
drop constraint slipretencaoreceitas_retencaoreceitas_fk;
drop constraint slipretencaoreceitas_slip_fk;

drop table slipretencaoreceitas;



SQL;

      DB::connection()->getPdo()->exec($sql);


    }
}
