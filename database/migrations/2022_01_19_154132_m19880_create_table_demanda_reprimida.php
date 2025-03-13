<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M19880CreateTableDemandaReprimida extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('farmacia.demanda_reprimida', function(Blueprint $table) {
            $table->bigIncrements('fa67_id');
            $table->dateTime('fa67_data_hora');
            $table->integer('fa67_paciente');
            $table->integer('fa67_medicamento');
            $table->integer('fa67_quantidade');
            $table->integer('fa67_usuario');
            $table->integer('fa67_unidade_saude');
            $table->text('fa67_observacoes');

            $table->foreign('fa67_paciente', 'demanda_reprimida_cgs_und_fk')
                ->references('z01_i_cgsund')
                ->on('ambulatorial.cgs_und');
            $table->foreign('fa67_medicamento', 'demanda_reprimida_far_matersaude_fk')
                ->references('fa01_i_codigo')
                ->on('farmacia.far_matersaude');
            $table->foreign('fa67_usuario', 'demanda_reprimida_db_usuarios_fk')
                ->references('id_usuario')
                ->on('configuracoes.db_usuarios');

            $table->foreign('fa67_unidade_saude', 'demanda_reprimida_unidades_fk')
                ->references('sd02_i_codigo')
                ->on('ambulatorial.unidades');

            $table->index('fa67_paciente', 'demanda_reprimida_paciente_ind');
        });
        DB::statement("SELECT configuracoes.fc_auditoria_cria_funcao('farmacia.demanda_reprimida');");
        $this->upDicionario();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('farmacia.demanda_reprimida');
        $this->downDicionario();
    }

    private function upDicionario()
    {
        DB::connection()->getPdo()->exec(<<<SQL
            insert into db_sysarquivo values (1010854, 'demanda_reprimida', 'Demanda dos medicamentos no qual a farmácia não possui estoque (demanda reprimida).', 'fa67', '2022-01-19', 'Demanda Reprimida', 0, 't', 't', 't', 't' );
            insert into db_sysarqmod values (52,1010854);

            insert into db_syscampo values(1013629,'fa67_id','int4','Chave primária da tabela','0', 'Código',10,'f','f','f',1,'text','Código');
            insert into db_syscampo values(1013630,'fa67_data_hora','date','Data e hora em que foi realizado o cadastro do registro.','null', 'Data e Hora',10,'f','f','f',1,'text','Data e Hora');
            insert into db_syscampo values(1013631,'fa67_paciente','int4','Foreign key do paciente que fez o pedido do medicamento','0', 'Código Paciente',10,'f','f','f',1,'text','Código Paciente');
            insert into db_syscampo values(1013632,'fa67_medicamento','int4','Foreign key do medicamento com estoque zerado','0', 'Código Medicamento',10,'f','f','f',1,'text','Código Medicamento');
            insert into db_syscampo values(1013633,'fa67_quantidade','int4','Quantidade que foi solicitada pelo paciente e/ou restante não atendida.','0', 'Quantidade',10,'f','f','f',1,'text','Quantidade');
            insert into db_syscampo values(1013634,'fa67_usuario','int4','Foreign key do usuário que incluiu o registro no sistema.','0', 'Código Usuário',10,'f','f','f',1,'text','Código Usuário');
            insert into db_syscampo values(1013635,'fa67_observacoes','text','Observações sobre o cadastro.','', 'Observações',1,'f','t','f',0,'text','Observações');
            insert into db_syscampo values(1013637,'fa67_unidade_saude','int4','Foreign key da unidade de saúde.','0', 'Código Unidade Saúde',10,'f','f','f',1,'text','Código Unidade Saúde');

            insert into db_sysarqcamp values(1010854,1013629,1,0);
            insert into db_sysarqcamp values(1010854,1013630,2,0);
            insert into db_sysarqcamp values(1010854,1013631,3,0);
            insert into db_sysarqcamp values(1010854,1013632,4,0);
            insert into db_sysarqcamp values(1010854,1013634,5,0);
            insert into db_sysarqcamp values(1010854,1013633,6,0);
            insert into db_sysarqcamp values(1010854,1013635,7,0);
            insert into db_sysarqcamp values(1010854,1013637,8,0);

            insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010854,1013629,1,1013629);

            insert into db_sysforkey values(1010854,1013631,1,1010144,0);
            insert into db_sysforkey values(1010854,1013632,1,2104,0);
            insert into db_sysforkey values(1010854,1013634,1,109,0);
            insert into db_sysforkey values(1010854,1013637,1,100011,0);

            insert into db_sysindices values(1008708,'demanda_reprimida_paciente_ind',1010854,'0');
            insert into db_syscadind values(1008708,1013631,1);
SQL
        );
    }

    private function downDicionario()
    {
        DB::connection()->getPdo()->exec(<<<SQL
            delete from db_syscadind where codind = 1008708;
            delete from db_sysindices where codarq = 1010854;
            delete from db_sysforkey where codarq = 1010854;
            delete from db_sysprikey where codarq = 1010854;
            delete from db_sysarqcamp where codarq = 1010854;
            delete from db_syscampo where codcam in (1013629, 1013630, 1013631, 1013632, 1013633, 1013634, 1013635,1013637);
            delete from db_sysarqmod where codarq = 1010854;
            delete from db_sysarquivo where codarq = 1010854;
SQL
        );
    }
}
