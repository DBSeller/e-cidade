<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M19366AcompanhamentoAcs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ambulatorial.acompanhamento_acs', function(Blueprint $table) {
            $table->bigIncrements('s168_id');
            $table->integer('s168_unidade');
            $table->integer('s168_profissional');
            $table->integer('s168_paciente');
            $table->timestamp('s168_data_hora');
            $table->text('s168_evolucao');

            $table->foreign('s168_unidade', 'acompanhamento_acs_unidade_fk')
                ->references('sd02_i_codigo')
                ->on('unidades');
            $table->foreign('s168_profissional', 'acompanhamento_acs_profissional_fk')
                ->references('sd03_i_codigo')
                ->on('medicos');
            $table->foreign('s168_paciente', 'acompanhamento_acs_paciente_fk')
                ->references('z01_i_cgsund')
                ->on('cgs_und');
        });

        DB::statement("SELECT configuracoes.fc_auditoria_cria_funcao('ambulatorial.acompanhamento_acs');");

        $this->upDicionario();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('configuracoes.db_menu')
            ->where('id_item_filho', '=', 228590)
            ->where('id_item', '=', 1818)
            ->delete();
        DB::table('configuracoes.db_itensmenu')->where('id_item', '=', 228590)->delete();

        DB::table('configuracoes.db_sysforkey')->where('codarq', '=', 1010832)->delete();
        DB::table('configuracoes.db_syscadind')->where('codind', '=', 1008694)->delete();
        DB::table('configuracoes.db_sysindices')->where('codind', '=', 1008694)->delete();
        DB::table('configuracoes.db_sysprikey')->where('codarq', '=', 1010832)->delete();
        DB::table('configuracoes.db_sysarqcamp')->where('codarq', '=', 1010832)->delete();
        DB::table('configuracoes.db_syscampo')->whereIn('codcam', [1013466,1013467,1013468,1013469,1013470,1013471])->delete();
        DB::table('configuracoes.db_sysarqmod')->where('codarq', '=', 1010832)->delete();
        DB::table('configuracoes.db_sysarquivo')->where('codarq', '=', 1010832)->delete();

        Schema::drop('ambulatorial.acompanhamento_acs');
    }

    private function upDicionario()
    {
        DB::statement("INSERT INTO db_itensmenu VALUES(228590,'Acompanhamento ACS','Acompanhamento ACS','amb4_acompanhamentoacs.php','1','1','Rotina para o registro de evolução do acompanhamento do ACS','true');");
        DB::statement("INSERT INTO db_menu VALUES(1818,228590,139,1000004);");

        DB::statement("INSERT INTO db_sysarquivo VALUES (1010832, 'acompanhamento_acs', 'Registra a Evolução das visitas do Agente de Saúde', 's168', '2021-11-10', 'Acompanhamento ACS', 0, 't', 't', 't', 't' );");
        DB::statement("INSERT INTO db_sysarqmod VALUES (1000004,1010832);");

        $dados = [
            [1013466,'s168_id','int8','Chave primária da tabela','0', 'Código',10,'f','f','f',1,'text','Código'],
            [1013467,'s168_unidade','int4','Unidade responsável pela visita','0', 'Unidade',10,'f','f','f',1,'text','Unidade'],
            [1013468,'s168_profissional','int4','Profissional responsável pela visita','0', 'Profissional',10,'f','f','f',1,'text','Profissional'],
            [1013469,'s168_paciente','int4','Paciente que recebeu a visita','0', 'Paciente',10,'f','f','f',1,'text','Paciente'],
            [1013470,'s168_data_hora','date','Data e hora da visita','null', 'Data e Hora',10,'f','f','f',1,'text','Data e Hora'],
            [1013471,'s168_evolucao','text','Evolução da visita','', 'Evolução',1,'f','f','f',0,'text','Evolução']
        ];

        DB::table('configuracoes.db_syscampo')->insert($this->dbSysCampo($dados));

        $dados = [
            [1010832,1013466,1,0],
            [1010832,1013467,2,0],
            [1010832,1013468,3,0],
            [1010832,1013469,4,0],
            [1010832,1013470,5,0],
            [1010832,1013471,6,0]
        ];

        DB::table('configuracoes.db_sysarqcamp')->insert($this->dbSysArqCamp($dados));

        DB::statement("INSERT INTO db_sysprikey (codarq,codcam,sequen,camiden) VALUES(1010832,1013466,1,1013466);");
        DB::statement("INSERT INTO db_sysindices VALUES(1008694,'acompanhamento_acs_s168_id_pkey',1010832,'0');");
        DB::statement("INSERT INTO db_syscadind VALUES(1008694,1013466,1);");

        $dados = [
            [1010832,1013467,1,100011,0],
            [1010832,1013468,1,100012,0],
            [1010832,1013469,1,1010144,0]
        ];

        DB::table('configuracoes.db_sysforkey')->insert($this->dbSysForKey($dados));
    }

    private function dbSysCampo($linhas)
    {
        $dados = [];
        
        foreach ($linhas as $values) {
            $dados[] = [
                'codcam' => $values[0],
                'nomecam' => $values[1],
                'conteudo' => $values[2],
                'descricao' => $values[3],
                'valorinicial' => $values[4],
                'rotulo' => $values[5],
                'tamanho' => $values[6],
                'nulo' => $values[7],
                'maiusculo' => $values[8],
                'autocompl' => $values[9],
                'aceitatipo' => $values[10],
                'tipoobj' => $values[11],
                'rotulorel' => $values[12] 
            ];
        }

        return $dados;
    }

    private function dbSysArqCamp($linhas)
    {
        $dados = [];

        foreach ($linhas as $values) {
            $dados[] = [
                'codarq' => $values[0],
                'codcam' => $values[1],
                'seqarq' => $values[2],
                'codsequencia' => $values[3]
            ];
        }

        return $dados;
    }

    private function dbSysForKey($linhas)
    {
        $dados = [];

        foreach ($linhas as $values) {
            $dados[] = [
                'codarq' => $values[0],
                'codcam' => $values[1],
                'sequen' => $values[2],
                'referen' => $values[3],
                'tipoobjrel' => $values[4]
            ];
        }

        return $dados;
    }
}
