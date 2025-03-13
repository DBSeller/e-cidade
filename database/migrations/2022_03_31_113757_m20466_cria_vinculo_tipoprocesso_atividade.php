<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M20466CriaVinculoTipoprocessoAtividade extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::connection()->getPdo()->exec(<<<sql
insert into db_sysarquivo values (1010892, 'tipoprocesso_atividadeexecucao', 'Vinculo dos tipos de processos com Atividades de Execução', 'p115', '2022-03-31', 'Tipo de processo com Atividades', 0, 't', 't', 't', 't' );
insert into db_sysarqmod values (4,1010892);

insert into db_syscampo values(1013951,'p115_tipoprocesso','int8','Vinculo do Tipo de Processo da tabela tipoproc','0', 'Tipo de Processo',11,'f','f','f',1,'text','Tipo de Processo');
insert into db_syscampo values(1013952,'p115_atividadesexecucao','int8','Vinculo da Atividade de Execução da tabela atividadesexecucao','0', 'Atividade de Execução',11,'f','f','f',1,'text','Atividade de Execução');
insert into db_syscampo values(1013953,'p115_ordem','int4','Ordem de execução da atividade','0', 'Ordem',10,'f','f','f',1,'text','Ordem');

insert into db_sysarqcamp values(1010892,1013951,1,0);
insert into db_sysarqcamp values(1010892,1013952,2,0);
insert into db_sysarqcamp values(1010892,1013953,3,0);
insert into db_sysindices values(1008741,'tipoprocesso_atividadeexecucao_in',1010892,'0');
insert into db_syscadind values(1008741,1013951,1);
insert into db_syscadind values(1008741,1013952,2);

insert into db_sysforkey values(1010892,1013951,1,393,0);
insert into db_sysforkey values(1010892,1013952,1,1010888,0);
sql
        );

        Schema::create('protocolo.tipoprocesso_atividadeexecucao', function (Blueprint $table) {
            $table->bigInteger('p115_tipoprocesso');
            $table->foreign('p115_tipoprocesso', 'tipoprocesso_atividadeexecucao_p115_tipoprocesso_fk')
                ->references('p51_codigo')
                ->on('protocolo.tipoproc');
            $table->bigInteger('p115_atividadesexecucao');
            $table->foreign('p115_atividadesexecucao', 'tipoprocesso_atividadeexecucao_p115_atividadesexecucao_fk')
                ->references('p114_codigo')
                ->on('protocolo.atividadesexecucao');
            $table->integer('p115_ordem');
            $table->index(['p115_tipoprocesso', 'p115_atividadesexecucao'], 'tipoprocesso_atividadeexecucao_in');
        });

        DB::statement("SELECT configuracoes.fc_auditoria_cria_funcao('protocolo.tipoprocesso_atividadeexecucao');");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::connection()->getPdo()->exec(<<<sql
            delete from db_syscadind where codcam in (1013951, 1013952);
            delete from db_sysindices where codarq = 1010892;
            delete from db_sysforkey where codarq = 1010892;
            delete from db_sysarqcamp where codarq = 1010892;
            delete from db_syscampo where codcam in (1013951, 1013952, 1013953);
            delete from db_sysarqmod where codarq = 1010892;
            delete from db_sysarquivo where codarq = 1010892;
sql
        );
        Schema::dropIfExists('protocolo.tipoprocesso_atividadeexecucao');
    }
}
