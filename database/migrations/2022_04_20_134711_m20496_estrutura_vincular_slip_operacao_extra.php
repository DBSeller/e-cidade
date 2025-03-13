<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M20496EstruturaVincularSlipOperacaoExtra extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        $sql = <<<SQL

insert into db_sysarquivo values (1010901, 'slipoperacaoextra', 'vinculo de um slip de recebimento gerado nas operações extras com o slip gerado automaticamente para pagamento, invertendo debito / credito', 'k208', '2022-04-20', 'slipoperacaoextra', 0, 'f', 'f', 'f', 'f' );
insert into db_sysarqmod values (5,1010901);

insert into db_syscampo values(1014003,'k208_sequencial','int4','sequencial vinculo de um slip de recebimento gerado nas operações extras com o slip gerado automaticamente para pagamento, invertendo debito / credito','0', 'Sequencial',10,'f','f','f',1,'text','Sequencial');
insert into db_syscampo values(1014004,'k208_recebimento','int4','slip de recebimento gerado nas operações extras ','0', 'Slip de Recebimento',10,'f','f','f',1,'text','Slip de Recebimento');
insert into db_syscampo values(1014005,'k208_pagamento','int4','slip gerado automaticamente para pagamento, invertendo debito / credito','0', 'Slip de Pagamento',10,'f','f','f',1,'text','Slip de Pagamento');


insert into db_sysarqcamp values(1010901,1014003,1,0);
insert into db_sysarqcamp values(1010901,1014004,2,0);
insert into db_sysarqcamp values(1010901,1014005,3,0);

insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010901,1014003,1,1014003);

insert into db_sysforkey values(1010901,1014004,1,196,0);
insert into db_sysforkey values(1010901,1014005,1,196,0);
insert into db_sysindices values(1008749,'k208_sequencial_in',1010901,'1');
insert into db_syscadind values(1008749,1014003,1);
insert into db_sysindices values(1008750,'k208_recebimento_in',1010901,'0');
insert into db_syscadind values(1008750,1014004,1);
insert into db_sysindices values(1008751,'k208_pagamento_in',1010901,'0');
insert into db_syscadind values(1008751,1014005,1);
insert into db_syssequencia values(1001047, 'slipoperacaoextra_k208_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
update db_sysarqcamp set codsequencia = 1001047 where codarq = 1010901 and codcam = 1014003;




CREATE TABLE slipoperacaoextra(
k208_sequencial		int4 NOT NULL default 0,
k208_recebimento		int4 NOT NULL default 0,
k208_pagamento		int4 default 0,
CONSTRAINT slipoperacaoextra_sequ_pk PRIMARY KEY (k208_sequencial));


ALTER TABLE slipoperacaoextra
ADD CONSTRAINT slipoperacaoextra_pagamento_fk FOREIGN KEY (k208_pagamento)
REFERENCES slip;


ALTER TABLE slipoperacaoextra
ADD CONSTRAINT slipoperacaoextra_recebimento_fk FOREIGN KEY (k208_recebimento)
REFERENCES slip;


CREATE UNIQUE INDEX k208_sequencial_in ON slipoperacaoextra(k208_sequencial);

CREATE  INDEX k208_recebimento_in ON slipoperacaoextra(k208_recebimento);

CREATE  INDEX k208_pagamento_in ON slipoperacaoextra(k208_pagamento);

CREATE SEQUENCE slipoperacaoextra_k208_sequencial_seq
INCREMENT 1
MINVALUE 1
MAXVALUE 9223372036854775807
START 1
CACHE 1;





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
delete from db_syssequencia where codsequencia = 1001047;
delete from db_sysindices where codarq = 1010901;
delete from db_syscadind where codcam in (1014003, 1014004, 1014005);
delete from db_sysprikey where codarq = 1010901;
delete from db_sysarqcamp where codarq = 1010901;
delete from db_sysforkey where codarq = 1010901 ;
delete from db_syscampo where codcam in (1014003, 1014004, 1014005);
delete from db_sysarqmod where codarq = 1010901;
delete from db_acount where codarq = 1010901;
delete from db_sysarquivo where codarq = 1010901;


DROP TABLE IF EXISTS slipoperacaoextra CASCADE;
DROP SEQUENCE IF EXISTS slipoperacaoextra_k208_sequencial_seq;





SQL;

       DB::connection()->getPdo()->exec($sql);
    }
}
