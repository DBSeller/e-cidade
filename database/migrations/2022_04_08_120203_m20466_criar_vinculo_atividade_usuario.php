<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M20466CriarVinculoAtividadeUsuario extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->dicionarioUp();
        Schema::create('configuracoes.db_permemp_atividadesexecucao', function (Blueprint $table) {
            $table->bigInteger('db69_codperm');
            $table->foreign('db69_codperm', 'db_permemp_atividadesexecucao_db69_codperm_fk')
                ->references('db20_codperm')
                ->on('configuracoes.db_permemp');
            $table->bigInteger('db69_atividadesexecucao');
            $table->foreign('db69_atividadesexecucao', 'db_permemp_atividadesexecucao_db69_atividadesexecucao_fk')
                ->references('p114_codigo')
                ->on('protocolo.atividadesexecucao');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->dicionarioDown();
        Schema::dropIfExists('configuracoes.db_permemp_atividadesexecucao');
    }

    private function dicionarioUp()
    {
        DB::connection()->getPdo()->exec(<<<sql
insert into db_sysarquivo values (1010894, 'db_permemp_atividadesexecucao', 'Vinculo de Permissões da Despesa com Atividades de Execução', 'db69', '2022-04-08', 'Permissão Atividade', 0, 'f', 't', 't', 't' );
insert into db_sysarqmod values (7,1010894);
insert into db_syscampo values(1013965,'db69_codperm','int4','Vinculo com db_permemp','0', 'Código Permissão',10,'f','f','f',1,'text','Código Permissão');
insert into db_syscampo values(1013966,'db69_atividadesexecucao','int4','Vinculo Atividades de execução','0', 'Atividades de execução',10,'f','f','f',1,'text','Atividades de execução');

insert into db_sysarqcamp values(1010894,1013965,1,0);
insert into db_sysarqcamp values(1010894,1013966,2,0);

insert into db_sysforkey values(1010894,1013966,1,1010888,0);
insert into db_sysforkey values(1010894,1013965,1,883,0);
sql
        );
    }

    private function dicionarioDown() {
        DB::connection()->getPdo()->exec(<<<sql
delete from db_sysforkey where codarq = 1010894;
delete from db_sysarqcamp where codarq = 1010894;
delete from db_syscampo where codcam in (1013965, 1013966);
delete from db_sysarqmod where codarq = 1010894;
delete from db_sysarquivo where codarq = 1010894;
sql
        );
    }
}
