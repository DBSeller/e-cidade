<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M20466CriaTabelaAtividadesexecucao extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::connection()->getPdo()->exec(<<<sql
insert into db_sysarquivo values (1010888, 'atividadesexecucao', 'Guarda as Atividades/Etapas que um tipo de processo deve seguir quando for processos para dar andamento em documentos', 'p114', '2022-03-31', 'Atividades de execução', 0, 't', 'f', 't', 't' );
insert into db_sysarqmod values (4,1010888);

insert into db_syscampo values(1013938,'p114_codigo','int8','Sequencial','0', 'Código',11,'f','f','f',1,'text','Código');
insert into db_syscampo values(1013940,'p114_atividade','varchar(255)','Nome/Descrição da Atividade que será atribuida a uma etada de um tipo de processo','', 'Atividades de execução',255,'f','f','f',0,'text','Atividades de execução');
insert into db_syscampo values(1013941,'p114_status','varchar(100)','Status do processo que teve essa atividade executada','', 'Status',100,'f','f','f',0,'text','Status');

insert into db_sysarqcamp values(1010888,1013938,1,0);
insert into db_sysarqcamp values(1010888,1013940,2,0);
insert into db_sysarqcamp values(1010888,1013941,3,0);

insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010888,1013938,1,1013938);
insert into db_sysindices values(1008740,'atividadesexecucao_pkey',1010888,'0');
sql
        );

        Schema::create('protocolo.atividadesexecucao', function (Blueprint $table) {
            $table->bigIncrements('p114_codigo');
            $table->string('p114_atividade');
            $table->string('p114_status');
        });

        DB::connection()->getPdo()->exec(<<<sql
            insert into protocolo.atividadesexecucao values (1, 'Gerar', 'Gerado'),
                                                            (2, 'Conferir', 'Conferido'),
                                                            (3, 'Assinar', 'Assinado'),
                                                            (4, 'Arquivar', 'Arquivado');
            select setval('atividadesexecucao_p114_codigo_seq', 4);
sql
        );

        DB::statement("SELECT configuracoes.fc_auditoria_cria_funcao('protocolo.atividadesexecucao');");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::connection()->getPdo()->exec(<<<sql
            delete from db_sysindices where codind = 1008740;
            delete from db_sysprikey where codarq = 1010888;
            delete from db_sysarqcamp where codarq = 1010888;
            delete from db_syscampo where codcam in (1013938, 1013940, 1013941);
            delete from db_sysarqmod where codarq = 1010888;
            delete from db_sysarquivo where codarq = 1010888;
sql
        );

        Schema::dropIfExists('protocolo.atividadesexecucao');
    }
}
