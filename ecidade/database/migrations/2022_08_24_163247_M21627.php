<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M21627 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sql = <<<SQL
insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228745 ,'Libera U.O para pagamento de Retenções por RE' ,'Libera U.O para pagamento de Retenções por RE' ,'emp4_permissaoPagamentoRetencoesTRA001.php' ,'1' ,'1' ,'Libera U.O para pagamento de Retenções por RE' ,'true' );
insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 6911 ,228745 ,4 ,398 );

insert into db_syscampo values(1014457,'e21_envioremessabancaria','bool','Permite pagamento por remessa bancária','t', 'Permite pagamento por remessa bancária',1,'f','f','f',5,'text','Permite pagamento por remessa bancária');
insert into db_syscampodef values(1014457,'t','SIM');
insert into db_syscampodef values(1014457,'f','NÃO');
insert into db_sysarqcamp values(2112,1014457,10,0);

insert into db_sysarquivo values (1010983, 'retencaotiporecorcunidadeliberaremessa', 'Registros das unidades que tem liberação para configuração de todas as formas de pagamento de slips oriundos de retenções ', 'e287', '2022-08-26', 'Libera Unidade para Configurar Retenção na Agenda', 0, 'f', 't', 't', 't' );
insert into db_sysarqmod values (38,1010983);

insert into db_syscampo values(1014458,'e287_orgao','int4','Orgão','0', 'Orgão',10,'f','f','f',1,'text','Orgão');
insert into db_syscampo values(1014459,'e287_unidade','int4','Unidade Orçamentária','0', 'Unidade',10,'f','f','f',1,'text','Unidade');
insert into db_syscampo values(1014460,'e287_instituicao','int4','Instituição','0', 'Instituição',10,'f','f','f',1,'text','Instituição');

insert into db_sysarqcamp values(1010983,1014458,1,0);
insert into db_sysarqcamp values(1010983,1014459,2,0);
insert into db_sysarqcamp values(1010983,1014460,3,0);

insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010983,1014458,1,1014459);
insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010983,1014459,2,1014459);
insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010983,1014460,3,1014459);

alter table empenho.retencaotiporec add column e21_envioremessabancaria bool default true;

create table empenho.retencaotiporecorcunidadeliberaremessa (e287_orgao int, 
                                                             e287_unidade int,
                                                             e287_instituicao int, primary key (e287_orgao, e287_unidade, e287_instituicao));

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
delete from db_menu where id_item_filho = 228745;
delete from db_itensmenu where id_item = 228745;

delete from db_sysarqcamp where codcam = 1014457;
delete from db_syscampodef where codcam = 1014457; 
delete from db_syscampo where codcam = 1014457;


delete from db_sysarqcamp where codarq = 1010983;
delete from db_syscampo where codcam in (1014458, 1014459, 1014460);

delete from db_sysarqmod where codarq = 1010983;
delete from db_sysarquivo where codarq = 1010983;

delete from db_sysprikey where codarq = 1010983;

alter table empenho.retencaotiporec drop column e21_envioremessabancaria;

drop table empenho.retencaotiporecorcunidadeliberaremessa;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }
}
