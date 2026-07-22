<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M19981CreateTableProblemaspaciente extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->upDicionario();

        Schema::create('ambulatorial.problemas', function(Blueprint $table) {
            $table->increments('s169_id');
            $table->string('s169_descricao');
        });
        DB::statement("SELECT configuracoes.fc_auditoria_cria_funcao('ambulatorial.problemas');");
        $this->seedProblemas();

        Schema::create('ambulatorial.problemaspaciente', function(Blueprint $table) {
            $table->increments('s170_id');
            $table->integer('s170_problema');
            $table->integer('s170_paciente');
            $table->integer('s170_usuario');
            $table->date('s170_data');
            $table->date('s170_data_inicio')->nullable();
            $table->date('s170_data_fim')->nullable();
            $table->boolean('s170_ativo');

            $table->foreign('s170_problema', 'problemaspaciente_problemas_fk')
                ->references('s169_id')
                ->on('ambulatorial.problemas');
            
            $table->foreign('s170_paciente', 'problemaspaciente_cgs_und_fk')
                ->references('z01_i_cgsund')
                ->on('ambulatorial.cgs_und');

            $table->foreign('s170_usuario', 'problemaspaciente_db_usuarios_fk')
                ->references('id_usuario')
                ->on('configuracoes.db_usuarios');
            
            $table->index('s170_problema', 'problemaspaciente_s170_problema_ind');
            $table->index('s170_paciente', 'problemaspaciente_s170_paciente_ind');
        });
        DB::statement("SELECT configuracoes.fc_auditoria_cria_funcao('ambulatorial.problemaspaciente');");

        Schema::create('ambulatorial.prontuario_problemaspaciente', function(Blueprint $table) {
            $table->increments('s171_id');
            $table->integer('s171_prontuario');
            $table->integer('s171_problemapaciente');

            $table->foreign('s171_prontuario', 'prontuario_problemaspaciente_prontuarios_fk')
                ->references('sd24_i_codigo')
                ->on('ambulatorial.prontuarios');
            
            $table->foreign('s171_problemapaciente', 'prontuario_problemaspaciente_problemapaciente_fk')
                ->references('s170_id')
                ->on('ambulatorial.problemaspaciente');
            
            $table->index('s171_prontuario', 'prontuario_problemaspaciente_s171_prontuario_ind');
            $table->index('s171_problemapaciente', 'prontuario_problemaspaciente_s171_problemapaciente_ind');
        });
        DB::statement("SELECT configuracoes.fc_auditoria_cria_funcao('ambulatorial.prontuario_problemaspaciente');");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('ambulatorial.prontuario_problemaspaciente');
        Schema::drop('ambulatorial.problemaspaciente');
        Schema::drop('ambulatorial.problemas');
        $this->downDicionario();
    }

    private function seedProblemas()
    {
        DB::table('ambulatorial.problemas')->insert([
            ['s169_descricao' => 'Asma'],
            ['s169_descricao' => 'Câncer de colo de útero'],
            ['s169_descricao' => 'Câncer de mama'],
            ['s169_descricao' => 'Dengue'],
            ['s169_descricao' => 'Desnutrição'],
            ['s169_descricao' => 'Diabetes'],
            ['s169_descricao' => 'DPOC'],
            ['s169_descricao' => 'DST'],
            ['s169_descricao' => 'Hanseníase'],
            ['s169_descricao' => 'Hipertensão arterial'],
            ['s169_descricao' => 'Obesidade'],
            ['s169_descricao' => 'Pré-natal'],
            ['s169_descricao' => 'Puericultura'],
            ['s169_descricao' => 'Puerpério'],
            ['s169_descricao' => 'Reabilitação'],
            ['s169_descricao' => 'Risco cardiovascular'],
            ['s169_descricao' => 'Saúde mental'],
            ['s169_descricao' => 'Saúde sexual e reprodutiva'],
            ['s169_descricao' => 'Tabagismo'],
            ['s169_descricao' => 'Tuberculose'],
            ['s169_descricao' => 'Usuário de álcool'],
            ['s169_descricao' => 'Usuário de outras drogas'],
        ]);
    }

    private function upDicionario()
    {
        DB::connection()->getPdo()->exec(<<<SQL
            insert into db_sysarquivo values (1010866, 'problemas', 'Tabela com a descrição de problemas e condições de saúde', 's169', '2022-03-03', 'Problemas/Condições de Saúde', 0, 't', 't', 't', 't' );
            insert into db_sysarqmod values (1000004,1010866);

            insert into db_syscampo values(1013760,'s169_id','int4','Primary Key da tabela ambulatorial.problemas','0', 'Código',10,'f','f','f',1,'text','Código');
            insert into db_syscampo values(1013761,'s169_descricao','varchar(10)','Descrição do problema','', 'Descrição',10,'f','t','f',0,'text','Descrição');
            insert into db_sysarqcamp values(1010866,1013760,1,0);
            insert into db_sysarqcamp values(1010866,1013761,2,0);
            insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010866,1013760,1,1013760);

            insert into db_sysarquivo values (1010867, 'problemaspaciente', 'Guarda as informações de problemas/condições vinculadas a um paciente.', 's170', '2022-03-03', 'Problemas do Paciente', 0, 't', 't', 't', 't' );
            insert into db_sysarqmod values (1000004,1010867);

            insert into db_syscampo values(1013762,'s170_id','int8','Primary key da tabela','0', 'Código',10,'f','f','f',1,'text','Código');
            insert into db_syscampo values(1013763,'s170_problema','int4','Foreign Key do problema','0', 'Código Problema',10,'f','f','f',1,'text','Código Problema');
            insert into db_syscampo values(1013764,'s170_paciente','int4','Foreign Key do paciente.','0', 'Código Paciente',10,'f','f','f',1,'text','Código Paciente');
            insert into db_syscampo values(1013766,'s170_usuario','int4','Foreign key do usuario','0', 'Código Usuário',10,'f','f','f',1,'text','Código Usuário');
            insert into db_syscampo values(1013767,'s170_data','date','Data da inclusão','null', 'Data',10,'f','f','f',1,'text','Data');
            insert into db_syscampo values(1013769,'s170_data_inicio','date','Data de inicio do problema/condição.','null', 'Data Inicio',10,'t','f','f',1,'text','Data Inicio');
            insert into db_syscampo values(1013770,'s170_data_fim','date','Data fim do problema.','null', 'Data Fim',10,'t','f','f',1,'text','Data Fim');
            insert into db_syscampo values(1013771,'s170_ativo','bool','Indica se o problema está ativo ou não.','f', 'Ativo',1,'f','f','f',5,'text','Ativo');
            insert into db_sysarqcamp values(1010867,1013762,1,0);
            insert into db_sysarqcamp values(1010867,1013763,2,0);
            insert into db_sysarqcamp values(1010867,1013764,3,0);
            insert into db_sysarqcamp values(1010867,1013766,4,0);
            insert into db_sysarqcamp values(1010867,1013767,5,0);
            insert into db_sysarqcamp values(1010867,1013769,6,0);
            insert into db_sysarqcamp values(1010867,1013770,7,0);
            insert into db_sysarqcamp values(1010867,1013771,8,0);
            insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010867,1013762,1,1013762);
            insert into db_sysforkey values(1010867,1013763,1,1010866,0);
            insert into db_sysforkey values(1010867,1013764,1,1010144,0);
            insert into db_sysforkey values(1010867,1013766,1,109,0);
            insert into db_sysindices values(1008729,'problemaspaciente_s170_problema_ind',1010867,'0');
            insert into db_syscadind values(1008729,1013763,1);
            insert into db_sysindices values(1008730,'problemaspaciente_s170_paciente_ind',1010867,'0');
            insert into db_syscadind values(1008730,1013764,1);

            insert into db_sysarquivo values (1010868, 'prontuario_problemaspaciente', 'Problemas do paciente vinculados ao prontuario.', 's171', '2022-03-03', 'Prontuario Problemas Paciente', 0, 't', 't', 't', 't' );
            insert into db_sysarqmod values (1000004,1010868);

            insert into db_syscampo values(1013772,'s171_id','int4','Primary key da tabela','0', 'Código',10,'f','f','f',1,'text','Código');
            insert into db_syscampo values(1013773,'s171_prontuario','int4','Foreign key da tabela prontuarios','0', 'Código Prontuario',10,'f','f','f',1,'text','Código Prontuario');
            insert into db_syscampo values(1013774,'s171_problemapaciente','int4','Foreign key da tabela problemaspaciente','0', 'Código Problema Paciente',10,'f','f','f',1,'text','Código Problema Paciente');
            insert into db_sysarqcamp values(1010868,1013772,1,0);
            insert into db_sysarqcamp values(1010868,1013773,2,0);
            insert into db_sysarqcamp values(1010868,1013774,3,0);
            insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010868,1013772,1,1013772);
            insert into db_sysforkey values(1010868,1013773,1,1010134,0);
            insert into db_sysforkey values(1010868,1013774,1,1010867,0);
            insert into db_sysindices values(1008731,'prontuario_problemaspaciente_s171_prontuario_ind',1010868,'0');
            insert into db_syscadind values(1008731,1013773,1);
            insert into db_sysindices values(1008732,'prontuario_problemaspaciente_s171_problemapaciente_ind',1010868,'0');
            insert into db_syscadind values(1008732,1013774,1);
SQL
        );
    }

    private function downDicionario()
    {
        DB::connection()->getPdo()->exec(<<<SQL
            delete from db_syscadind where codind in (1008732, 1008731, 1008730, 1008729);
            delete from db_sysindices where codind in (1008732, 1008731, 1008730, 1008729);
            delete from db_sysforkey where codarq in (1010868, 1010867);
            delete from db_sysprikey where codarq in (1010868, 1010867, 1010866);
            delete from db_sysarqcamp where codarq in (1010868, 1010867, 1010866);
            delete from db_syscampo where codcam in (1013760, 1013761, 1013762, 1013763, 1013764, 1013766, 1013767, 1013769, 1013770, 1013771, 1013772, 1013773, 1013774);
            delete from db_sysarqmod where codarq in (1010868, 1010867, 1010866);
            delete from db_sysarquivo where codarq in (1010868, 1010867, 1010866);
SQL
        );
    }
}
