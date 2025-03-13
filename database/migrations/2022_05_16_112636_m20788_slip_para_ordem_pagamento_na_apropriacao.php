<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M20788SlipParaOrdemPagamentoNaApropriacao extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sql = <<<SQL


insert into db_sysarquivo values (1010921, 'slippagordem', 'estrutura de vinculo do slip gerado para transferencias bancarias das retencoes de receitas orçamentarias vinculado a ordem de pagamento antes esse slip era gerado na rotina ', 'k209', '2022-05-16', 'slippagordem', 0, 'f', 'f', 'f', 'f' );
insert into db_sysarqmod values (5,1010921);


insert into db_syscampo values(1014121,'k209_sequencial','int4','Sequencial Slip Ordem de Pagamento','0', 'Sequencial Slip Ordem de Pagamento',10,'f','f','f',1,'text','Sequencial Slip Ordem de Pagamento');
insert into db_syscampo values(1014122,'k209_pagordem','int4','Codigo da OP que gerou o slip de transferencia de fornecedores','0', 'Codigo da OP',10,'f','f','f',1,'text','Codigo da OP');
insert into db_syscampo values(1014123,'k209_slip','int4','Codigo do Slip da OP','0', 'Codigo do Slip da OP',10,'f','f','f',1,'text','Codigo do Slip da OP');

insert into db_sysarqcamp values(1010921,1014121,1,0);
insert into db_sysarqcamp values(1010921,1014122,2,0);
insert into db_sysarqcamp values(1010921,1014123,3,0);


insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010921,1014121,1,1014121);
insert into db_sysforkey values(1010921,1014122,1,808,0);
insert into db_sysforkey values(1010921,1014123,1,196,0);


insert into db_sysindices values(1008774,'k209_sequencial_in',1010921,'1');
insert into db_syscadind values(1008774,1014121,1);

insert into db_sysindices values(1008775,'k209_pagordem_in',1010921,'0');
insert into db_syscadind values(1008775,1014122,1);

insert into db_sysindices values(1008776,'k209_slip_in',1010921,'0');
insert into db_syscadind values(1008776,1014123,1);

insert into db_syssequencia values(1001064, 'slippagordem_k209_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
update db_sysarqcamp set codsequencia = 1001064 where codarq = 1010921 and codcam = 1014121;





CREATE SEQUENCE slippagordem_k209_sequencial_seq
INCREMENT 1
MINVALUE 1
MAXVALUE 9223372036854775807
START 1
CACHE 1;

CREATE TABLE caixa.slippagordem(
k209_sequencial		int4 NOT NULL default 0,
k209_pagordem		int4 NOT NULL default 0,
k209_slip		int4 default 0,
CONSTRAINT slippagordem_sequ_pk PRIMARY KEY (k209_sequencial));


ALTER TABLE slippagordem
ADD CONSTRAINT slippagordem_slip_fk FOREIGN KEY (k209_slip)
REFERENCES slip;

ALTER TABLE slippagordem
ADD CONSTRAINT slippagordem_pagordem_fk FOREIGN KEY (k209_pagordem)
REFERENCES pagordem;

CREATE UNIQUE INDEX k209_sequencial_in ON slippagordem(k209_sequencial);
CREATE  INDEX k209_pagordem_in ON slippagordem(k209_pagordem);
CREATE  INDEX k209_slip_in ON slippagordem(k209_slip);



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



delete from db_sysprikey where codarq = 1010921;
delete from db_sysforkey where codarq = 1010921;
delete from db_sysforkey where codarq = 1010921;
delete from db_sysindices where codarq = 1010921;
delete from db_syscadind where codind in (1008774, 1008775, 1008776 );
delete from db_sysarqcamp where codarq = 1010921;
delete from db_syscampo where codcam in (1014121, 1014122, 1014123);
delete from db_sysarqmod where codarq = 1010921;
delete from db_sysarquivo where codarq = 1010921;
delete from db_syssequencia where codsequencia = 1001064;



DROP TABLE IF EXISTS slippagordem CASCADE;
DROP SEQUENCE IF EXISTS slippagordem_k209_sequencial_seq;



SQL;
        DB::connection()->getPdo()->exec($sql);


    }
}
