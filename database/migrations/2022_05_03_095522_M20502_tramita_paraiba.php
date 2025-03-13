<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M20502TramitaParaiba extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->upMenu();
        $this->upDicionario();
        $this->upEstrutura();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->downMenu();
        $this->downDicionario();
        $this->downEstrutura();
    }

    public function upMenu()
    {
        DB::connection()->getPdo()->exec(<<<SQL
insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228655 ,'Importação Tramita' ,'Importação Tramita' ,'lic4_importacao_tramita.php' ,'1' ,'1' ,'Realiza a consistência das licitações existentes no arquivo tramita com as licitações do e-cidade' ,'false' );
insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 4680 ,228655 ,13 ,381 );
SQL
        );
    }

    public function downMenu()
    {
        DB::connection()->getPdo()->exec(<<<SQL
delete from db_menu where id_item_filho = 228655 AND modulo = 381;
delete from db_itensmenu where id_item = 228655;
SQL
        );
    }

    protected function upDicionario()
    {
        DB::connection()->getPdo()->exec(<<<SQL
insert into db_sysarquivo values (1010911, 'licitacaotramita', 'Licitações que estão no Tramita', 'l29', '2022-05-03', 'Licitação Tramita', 0, 'f', 'f', 'f', 'f' );
insert into db_sysarqmod values (19,1010911);
insert into db_syscampo values(1014054,'licitacao_id','int4','Licitação','0', 'Licitação',10,'f','f','f',1,'text','Licitação');
delete from db_sysarqcamp where codarq = 1010911;
insert into db_sysarqcamp values(1010911,1011345,1,0);
insert into db_sysarqcamp values(1010911,1014054,2,0);
delete from db_sysprikey where codarq = 1010911;
insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010911,1011345,1,1011345);
delete from db_sysforkey where codarq = 1010911 and referen = 0;
insert into db_sysforkey values(1010911,1014054,1,1260,0);
insert into db_syssequencia values(1001054, 'licitacaotramita_id_seq', 1, 1, 9223372036854775807, 1, 1);
update db_sysarqcamp set codsequencia = 1001054 where codarq = 1010911 and codcam = 1011345;
SQL
        );
    }

    protected function downDicionario()
    {
        DB::connection()->getPdo()->exec(<<<SQL
delete from db_syssequencia where codsequencia = 1001054;
delete from db_sysprikey where codarq = 1010911;
delete from db_sysforkey where codarq = 1010911;
delete from db_sysarqcamp where codarq = 1010911;
delete from db_syscampo where codcam = 1014054;
delete from db_sysarqmod where codarq = 1010911;
delete from db_sysarquivo where codarq = 1010911;
SQL
        );
    }

    protected function upEstrutura()
    {
        DB::connection()->getPdo()->exec(<<<SQL
create table licitacao.licitacaotramita (
	id serial primary key,
	licitacao_id int not null,
	foreign key (licitacao_id) references licitacao.liclicita on delete cascade
);

select configuracoes.fc_auditoria_cria_funcao('licitacao.licitacaotramita');
SQL
        );
    }

    protected function downEstrutura()
    {
        DB::connection()->getPdo()->exec(<<<SQL
drop table licitacao.licitacaotramita;
SQL
        );
    }
}
