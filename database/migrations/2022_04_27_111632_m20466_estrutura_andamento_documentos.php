<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M20466EstruturaAndamentoDocumentos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->dicionarioUp();
        $this->estruturaUp();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->dicionarioDown();
        $this->estruturaDown();
    }

    private function dicionarioUp()
    {
        DB::connection()->getPdo()->exec(<<<SQL
-- up dicionario processo_usuarios
insert into db_sysarquivo values (1010903, 'processo_usuarios', 'Usuários que executarão atividades nesse processo', 'p119', '2022-04-27', 'Usuários permitidos', 0, 'f', 'f', 'f', 'f' );
insert into db_sysarqmod values (4,1010903);
insert into db_syscampo values(1014019,'p119_codigo','int8','Código sequencial','0', 'Código',11,'f','f','f',1,'text','Código');
insert into db_syscampo values(1014020,'p119_protprocesso','int8','Vinculo com processo','0', 'Processo',11,'f','f','f',1,'text','Processo');
insert into db_syscampo values(1014021,'p119_id_usuario','int8','Usuário que tem permissão no processo','0', 'Usuário',11,'f','f','f',1,'text','Usuário');
insert into db_syscampo values(1014022,'p119_atividadeexecucao','int8','Atividade de execução','0', 'Atividades de execução',11,'f','f','f',1,'text','Atividades de execução');
insert into db_sysarqcamp values(1010903,1014019,1,0);
insert into db_sysarqcamp values(1010903,1014020,2,0);
insert into db_sysarqcamp values(1010903,1014021,3,0);
insert into db_sysarqcamp values(1010903,1014022,4,0);
insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010903,1014019,1,1014019);
insert into db_sysforkey values(1010903,1014020,1,403,0);
insert into db_sysforkey values(1010903,1014021,1,109,0);
insert into db_sysforkey values(1010903,1014022,1,1010888,0);
insert into db_sysindices values(1008764,'processo_usuarios_p119_codigo_in',1010903,'0');
insert into db_syscadind values(1008764,1014019,1);

insert into db_syssequencia values(1001051, 'processo_usuarios_p119_codigo_seq', 1, 1, 9223372036854775807, 1, 1);
update db_sysarqcamp set codsequencia = 1001051 where codarq = 1010903 and codcam = 1014019;

-- up dicionario processo_atividadesexecucao
insert into db_sysarquivo values (1010905, 'processo_atividadesexecucao', 'Workflow de atividades do processo', 'p118', '2022-04-27', 'Atividades de execução', 0, 'f', 'f', 'f', 'f' );
insert into db_sysarqmod values (4,1010905);
insert into db_syscampo values(1014029,'p118_codigo','int8','Código sequencial','0', 'Código',11,'f','f','f',1,'text','Código');
insert into db_syscampo values(1014030,'p118_protprocesso','int8','Vinculo com processo','0', 'Processo',1,'f','f','f',1,'text','Processo');
insert into db_syscampo values(1014031,'p118_atividadesexecucao','int8','Atividade execução','0', 'Atividades de execução',11,'f','f','f',1,'text','Atividades de execução');
insert into db_syscampo values(1014032,'p118_ordem','int4','Ordem de execução da atividade','0', 'Ordem',10,'f','f','f',1,'text','Ordem');
insert into db_sysarqcamp values(1010905,1014029,1,0);
insert into db_sysarqcamp values(1010905,1014030,2,0);
insert into db_sysarqcamp values(1010905,1014031,3,0);
insert into db_sysarqcamp values(1010905,1014032,4,0);
insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010905,1014029,1,1014029);
insert into db_sysforkey values(1010905,1014030,1,403,0);
insert into db_sysforkey values(1010905,1014031,1,1010888,0);
insert into db_sysindices values(1008765,'processo_atividadesexecucao_p118_codigo_in',1010905,'0');
insert into db_syscadind values(1008765,1014029,1);

insert into db_syssequencia values(1001048, 'processo_atividadesexecucao_p118_codigo_seq', 1, 1, 9223372036854775807, 1, 1);
update db_sysarqcamp set codsequencia = 1001048 where codarq = 1010905 and codcam = 1014029;

-- up dicionario documentos_andamento
insert into db_sysarquivo values (1010902, 'documentos_andamento', 'Todo documento gerado que precisa seguir alguma atividade de execução', 'p116', '2022-04-27', 'Documentos para andamento', 0, 'f', 'f', 'f', 'f' );
insert into db_sysarqmod values (4,1010902);
insert into db_syscampo values(1014011,'p116_codigo','int8','Sequencial','0', 'Código',11,'f','f','f',1,'text','Código');
insert into db_syscampo values(1014012,'p116_descricao','varchar(255)','Descrição do documento','', 'Descrição',255,'f','f','f',0,'text','Descrição');
insert into db_syscampo values(1014013,'p116_protprocesso','int8','Vinculo do documento com um processo','0', 'Processo',11,'f','f','f',1,'text','Processo');
insert into db_syscampo values(1014014,'p116_protprocessodocumento','int8','Ultimo documento atualizado, vinculo com protprocesso','0', 'Documento',11,'f','f','f',1,'text','Documento');
insert into db_syscampo values(1014015,'p116_atividade_atual','int8','Atividade atual em que o documento se encontra, atividade já executada','0', 'Atividade atual',11,'f','f','f',1,'text','Atividade atual');
insert into db_syscampo values(1014016,'p116_proxima_atividade','int8','Próxima atividade a ser executada','0', 'P&#341;oxima atividade',11,'t','f','f',1,'text','P&#341;oxima atividade');
insert into db_syscampo values(1014017,'p116_data_criacao','date','Data de criação do registro','null', 'Data de criação',10,'f','f','f',1,'text','Data de criação');
insert into db_syscampo values(1014018,'p116_data_modificacao','date','Data de Modificação','0', 'Data de Modificação',10,'f','f','f',1,'text','Data de Modificação');
insert into db_syscampo values(1014065,'p116_codigo_origem','int8','Exemplo: sequencial do empenho ou de outra tabela que seria a entidade do documento','0', 'Documento de Origem',11,'f','f','f',1,'text','Documento de Origem');
insert into db_sysarqcamp values(1010902,1014011,1,0);
insert into db_sysarqcamp values(1010902,1014012,2,0);
insert into db_sysarqcamp values(1010902,1014013,3,0);
insert into db_sysarqcamp values(1010902,1014014,4,0);
insert into db_sysarqcamp values(1010902,1014015,5,0);
insert into db_sysarqcamp values(1010902,1014016,6,0);
insert into db_sysarqcamp values(1010902,1014017,7,0);
insert into db_sysarqcamp values(1010902,1014018,8,0);
insert into db_sysarqcamp values(1010902,1014065,9,0);
insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010902,1014011,1,1014011);
insert into db_sysforkey values(1010902,1014013,1,403,0);
insert into db_sysforkey values(1010902,1014014,1,3649,0);
insert into db_sysindices values(1008763,'documentos_andamento_p116_codigo_in',1010902,'0');
insert into db_syscadind values(1008763,1014011,1);
insert into db_sysforkey values(1010902,1014015,1,1010905,0);
insert into db_sysforkey values(1010902,1014016,1,1010905,0);

insert into db_syssequencia values(1001050, 'documentos_andamento_p116_codigo_seq', 1, 1, 9223372036854775807, 1, 1);
update db_sysarqcamp set codsequencia = 1001050 where codarq = 1010902 and codcam = 1014011;


-- up dicionario documentos_movimentacao
insert into db_sysarquivo values (1010904, 'documentos_movimentacao', 'Log de movimentações dos documentos', 'p117', '2022-04-27', 'Movimentação dos Documentos', 0, 'f', 'f', 'f', 'f' );
insert into db_sysarqmod values (4,1010904);
insert into db_syscampo values(1014023,'p117_codigo','int8','Código sequencial','0', 'Código',11,'f','f','f',1,'text','Código');
insert into db_syscampo values(1014024,'p117_documento_andamento','int8','Documento que está gravando o log da movimentação','0', 'Documento',11,'f','f','f',1,'text','Documento');
insert into db_syscampo values(1014025,'p117_id_usuario','int8','Usuário que executou a ação','0', 'Usuário',11,'f','f','f',1,'text','Usuário');
insert into db_syscampo values(1014026,'p117_protprocessodocumento','int8','Documento que foi gerado no momento da ação','0', 'Documento do processo',11,'f','f','f',1,'text','Documento do processo');
insert into db_syscampo values(1014027,'p117_processo_atividadesexecucao','int8','Atividade executada no momento da movimentação','0', 'Atividades de execução',11,'f','f','f',1,'text','Atividades de execução');
insert into db_syscampo values(1014028,'p117_data','date','Data e hora da movimentação','null', 'Data',10,'f','f','f',1,'text','Data');
delete from db_sysarqcamp where codarq = 1010904;
insert into db_sysarqcamp values(1010904,1014023,1,0);
insert into db_sysarqcamp values(1010904,1014024,2,0);
insert into db_sysarqcamp values(1010904,1014025,3,0);
insert into db_sysarqcamp values(1010904,1014026,4,0);
insert into db_sysarqcamp values(1010904,1014027,5,0);
insert into db_sysarqcamp values(1010904,1014028,6,0);
delete from db_sysprikey where codarq = 1010904;
insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010904,1014023,1,1014023);
delete from db_sysforkey where codarq = 1010904 and referen = 0;
insert into db_sysforkey values(1010904,1014024,1,1010902,0);
delete from db_sysforkey where codarq = 1010904 and referen = 0;
insert into db_sysforkey values(1010904,1014025,1,109,0);
delete from db_sysforkey where codarq = 1010904 and referen = 0;
insert into db_sysforkey values(1010904,1014026,1,3649,0);
delete from db_sysforkey where codarq = 1010904 and referen = 0;
insert into db_sysforkey values(1010904,1014027,1,1010905,0);
insert into db_sysindices values(1008766,'documentos_movimentacao_p117_codigo_in',1010904,'0');
insert into db_syscadind values(1008766,1014023,1);

insert into db_syssequencia values(1001049, 'documentos_movimentacao_p117_codigo_seq', 1, 1, 9223372036854775807, 1, 1);
update db_sysarqcamp set codsequencia = 1001049 where codarq = 1010904 and codcam = 1014023;

SQL
        );
    }
    private function dicionarioDown()
    {
        DB::connection()->getPdo()->exec(<<<SQL
-- down dicionario processo_usuarios
delete from db_syscadind where codcam = 1014019;
delete from db_sysindices where codarq = 1010903;
delete from db_sysforkey where codarq = 1010903;
delete from db_sysprikey where codarq = 1010903;
delete from db_sysarqcamp where codarq = 1010903;
delete from db_syscampo where codcam in (1014019, 1014020, 1014021, 1014022);
delete from db_sysarqmod where codarq = 1010903;
delete from db_sysarquivo where codarq = 1010903;

-- down dicionario processo_atividadesexecucao
delete from db_syscadind where codcam = 1014029;
delete from db_sysindices where codarq = 1010905;
delete from db_sysforkey where codarq = 1010905;
delete from db_sysprikey where codarq = 1010905;
delete from db_sysarqcamp where codarq = 1010905;
delete from db_syscampo where codcam in (1014029, 1014030, 1014031, 1014032);
delete from db_sysarqmod where codarq = 1010905;
delete from db_sysarquivo where codarq = 1010905;

-- down dicionario documentos_andamento
delete from db_sysforkey where codarq = 1010902;
delete from db_syscadind where codcam = 1014011;
delete from db_sysindices where codarq = 1010902;
delete from db_sysprikey where codarq = 1010902;
delete from db_sysarqcamp where codarq = 1010902;
delete from db_syscampo where codcam in (1014011, 1014012, 1014013, 1014014, 1014015, 1014016, 1014017, 1014018, 1014065);
delete from db_sysarqmod where codarq = 1010902;
delete from db_sysarquivo where codarq = 1010902;

-- down dicionario documentos_movimentacao
delete from db_sysforkey where codarq = 1010904;
delete from db_syscadind where codcam = 1014023;
delete from db_sysindices where codarq = 1010904;
delete from db_sysprikey where codarq = 1010904;
delete from db_sysarqcamp where codarq = 1010904;
delete from db_syscampo where codcam in (1014023, 1014024, 1014025, 1014026, 1014027, 1014028);
delete from db_sysarqmod where codarq = 1010904;
delete from db_sysarquivo where codarq = 1010904;

delete from db_syssequencia where codsequencia in (1001048, 1001049, 1001050, 1001051);
SQL
        );
    }

    private function estruturaUp()
    {
        Schema::create('protocolo.processo_usuarios', function (Blueprint $table) {
            $table->bigIncrements('p119_codigo');

            $table->bigInteger('p119_protprocesso');
            $table->foreign('p119_protprocesso', 'processo_usuarios_p119_protprocesso_fk')
                ->references('p58_codproc')
                ->on('protocolo.protprocesso');

            $table->bigInteger('p119_id_usuario');
            $table->foreign('p119_id_usuario', 'processo_usuarios_p119_id_usuario_fk')
                ->references('id_usuario')
                ->on('configuracoes.db_usuarios');

            $table->bigInteger('p119_atividadeexecucao');
            $table->foreign('p119_atividadeexecucao', 'processo_usuarios_p119_atividadeexecucao_fk')
                ->references('p114_codigo')
                ->on('protocolo.atividadesexecucao');
        });

        Schema::create('protocolo.processo_atividadesexecucao', function (Blueprint $table) {
            $table->bigIncrements('p118_codigo');

            $table->bigInteger('p118_protprocesso');
            $table->foreign('p118_protprocesso', 'processo_atividadesexecucao_p118_protprocesso_fk')
                ->references('p58_codproc')
                ->on('protocolo.protprocesso');

            $table->bigInteger('p118_atividadesexecucao');
            $table->foreign('p118_atividadesexecucao', 'processo_atividadesexecucao_p118_atividadesexecucao_fk')
                ->references('p114_codigo')
                ->on('protocolo.atividadesexecucao');

            $table->bigInteger('p118_ordem');
        });

        Schema::create('protocolo.documentos_andamento', function (Blueprint $table) {
            $table->bigIncrements('p116_codigo');
            $table->string('p116_descricao');

            $table->bigInteger('p116_protprocesso');
            $table->foreign('p116_protprocesso', 'documentos_andamento_p116_protprocesso_fk')
                ->references('p58_codproc')
                ->on('protocolo.protprocesso');

            $table->bigInteger('p116_protprocessodocumento');
            $table->foreign('p116_protprocessodocumento', 'documentos_andamento_p116_protprocessodocumento_fk')
                ->references('p01_sequencial')
                ->on('protocolo.protprocessodocumento');

            $table->bigInteger('p116_atividade_atual');
            $table->foreign('p116_atividade_atual', 'documentos_andamento_p116_atividade_atual_fk')
                ->references('p118_codigo')
                ->on('protocolo.processo_atividadesexecucao');

            $table->bigInteger('p116_proxima_atividade')->nullable();
            $table->foreign('p116_proxima_atividade', 'documentos_andamento_p116_proxima_atividade_fk')
                ->references('p118_codigo')
                ->on('protocolo.processo_atividadesexecucao');

            $table->bigInteger('p116_codigo_origem');
            $table->timestamp('p116_data_criacao')->useCurrent();
            $table->timestamp('p116_data_modificacao')->useCurrent();
        });

        Schema::create('protocolo.documentos_movimentacao', function (Blueprint $table) {
            $table->bigIncrements('p117_codigo');

            $table->bigInteger('p117_documento_andamento');
            $table->foreign('p117_documento_andamento', 'documentos_movimentacao_p117_documento_andamento_fk')
                ->references('p116_codigo')
                ->on('protocolo.documentos_andamento');

            $table->bigInteger('p117_id_usuario');
            $table->foreign('p117_id_usuario', 'documentos_movimentacao_p117_id_usuario_fk')
                ->references('id_usuario')
                ->on('configuracoes.db_usuarios');

            $table->bigInteger('p117_protprocessodocumento');
            $table->foreign('p117_protprocessodocumento', 'documentos_movimentacao_p117_protprocessodocumento_fk')
                ->references('p01_sequencial')
                ->on('protocolo.protprocessodocumento');

            $table->bigInteger('p117_processo_atividadesexecucao');
            $table->foreign('p117_processo_atividadesexecucao', 'documentos_movimentacao_p117_processo_atividadesexecucao_fk')
                ->references('p118_codigo')
                ->on('protocolo.processo_atividadesexecucao');

            $table->timestamp('p117_data')->useCurrent();
        });
    }

    private function estruturaDown()
    {
        Schema::drop('protocolo.documentos_movimentacao');
        Schema::drop('protocolo.documentos_andamento');
        Schema::drop('protocolo.processo_atividadesexecucao');
        Schema::drop('protocolo.processo_usuarios');
    }
}
