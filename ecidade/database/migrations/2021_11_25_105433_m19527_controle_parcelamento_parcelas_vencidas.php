<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M19527ControleParcelamentoParcelasVencidas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->upDicionario();  

        Schema::create('arrecadacao.controleparc_acao', function(Blueprint $table) {
            $table->increments('ar50_id');
            $table->string('ar50_descricao');
        });

        $dados = [
            ['ar50_descricao' => 'Antecipa vencimento maior data vencida'],
            ['ar50_descricao' => 'Antecipa vencimento menor data vencida'],
            ['ar50_descricao' => 'Antecipa vencimento para data do termo']
        ];
        
        DB::table('arrecadacao.controleparc_acao')->insert($dados);

        Schema::create('arrecadacao.controleparc_agendamento', function(Blueprint $table) {
            $table->bigIncrements('ar49_id');
            $table->string('ar49_dia_semana');
            $table->time('ar49_horario');
            $table->integer('ar49_prazo_dias');
            $table->integer('ar49_margem_dias');
            $table->integer('ar49_parcelas_vencidas');
            $table->integer('ar49_acao');
            $table->integer('ar49_regra_parcelamento');
            $table->string('ar49_agendamento_ativo');

            $table->foreign('ar49_acao', 'controleparc_agendamento_fk_acao')
                ->references('ar50_id')
                ->on('arrecadacao.controleparc_acao');
            $table->foreign('ar49_regra_parcelamento')
                ->references('k40_codigo')
                ->on('caixa.cadtipoparc');
        });

        DB::statement("SELECT configuracoes.fc_auditoria_cria_funcao('arrecadacao.controleparc_agendamento');");

        Schema::create('arrecadacao.controleparc_registrosorig', function(Blueprint $table) {
            $table->bigIncrements('ar51_id');
            $table->integer('ar51_numpre');
            $table->integer('ar51_numpar');
            $table->integer('ar51_receit');
            $table->date('ar51_dtvenc');
            $table->date('ar51_novadtvenc');
            $table->integer('ar51_id_agendamento');
            $table->date('ar51_dtproc');

            $table->foreign('ar51_id_agendamento', 'controleparc_registrosorig_id_agendamento')
                ->references('ar49_id')
                ->on('arrecadacao.controleparc_agendamento');
        });

        Schema::create('arrecadacao.controleparc_rollback', function(Blueprint $table) {
            $table->bigIncrements('ar52_id');
            $table->date('ar52_dtrollback');
            $table->integer('ar52_numpre');
            $table->integer('ar52_numpar');
            $table->integer('ar52_receit');
            $table->date('ar52_dtvenc'); 
            $table->string('ar52_usuario');
        });
        
        DB::statement("SELECT configuracoes.fc_auditoria_cria_funcao('arrecadacao.controleparc_rollback');");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->downDicionario();

        Schema::drop('arrecadacao.controleparc_registrosorig');
        Schema::drop('arrecadacao.controleparc_agendamento');
        Schema::drop('arrecadacao.controleparc_acao');
        Schema::drop('arrecadacao.controleparc_rollback');
        
    }

    private function upDicionario()
    {
        // itens menu
        DB::statement("insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228592 ,'Controle de parcelamentos vencidos' ,'Controle de parcelamentos vencidos' ,'' ,'1' ,'1' ,'Controle no sistema para monitoramento de parcelamentos vencidos a antecipação das parcelas vincendas.' ,'true' );");
        DB::statement("insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 32 ,228592 ,547 ,1985522 );");
        DB::statement("insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228593 ,'Agendamento' ,'Agendamento' ,'arr4_agendamendo_parc_venc.php' ,'1' ,'1' ,'Agendamento de mudança de vencimentos de parcelas' ,'true' );");
        DB::statement("insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 228592 ,228593 ,1 ,1985522 );");
        DB::statement("insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228611 ,'Rollback' ,'Rollback' ,'arr4_rollback_parc_venc.php' ,'1' ,'1' ,'Rotina para rollback do controle de parcelamentos vencidos' ,'true' );");
        DB::statement("insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 228592 ,228611 ,4 ,1985522 );");

        // controleparc_acao
        DB::statement("insert into db_sysarquivo values (1010836, 'controleparc_acao', 'Armazena ações de processamento para alteração de datas de vencimento da tabela arrecad ou baixa do numero de parcelas do arrecad', 'ar50', '2021-12-01', 'Ação de Controle de Parcelas Vencidas', 1, 't', 't', 't', 't' );");
        DB::statement("insert into db_sysarqmod values (54,1010836);");
        DB::statement("insert into db_syscampo values(1013492,'ar50_id','int4','Chave primária da tabela','0', 'Código',10,'f','f','f',1,'text','Código');");
        DB::statement("insert into db_syscampo values(1013493,'ar50_descricao','varchar(10)','Descrição da ação','', 'Descrição',10,'f','f','f',0,'text','Descrição');");
        DB::statement("insert into db_sysarqcamp values(1010836,1013492,1,0);");
        DB::statement("insert into db_sysarqcamp values(1010836,1013493,2,0);");
        DB::statement("insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010836,1013492,1,1013492);");
        DB::statement("insert into db_sysindices values(1008697,'controleparc_acao_ar50_id_ind',1010836,'1');");
        DB::statement("insert into db_syscadind values(1008697,1013492,1);");

        // controleparc_agendamento
        DB::statement("insert into db_sysarquivo values (1010837, 'controleparc_agendamento', 'Agendamento de Controle de Parcelas Vencidas', 'ar49', '2021-12-01', 'Agendamento de Controle de Parcelas Vencidas', 1, 't', 't', 't', 't' );");
        DB::statement("insert into db_sysarqmod values (54,1010837);");
        DB::statement("insert into db_syscampo values(1013494,'ar49_id','int8','Chave primária','0', 'Código',10,'f','f','f',1,'text','Código');");
        DB::statement("insert into db_syscampo values(1013495,'ar49_dia_semana','text','Código dia da semana','0', 'Código dia da semana',10,'f','f','f',0,'text','Código dia da semana');");
        DB::statement("insert into db_syscampo values(1013496,'ar49_horario','varchar(10)','Horário de processamento','', 'Horário de processamento',10,'f','f','f',0,'text','Horário de processamento');");
        DB::statement("insert into db_syscampo values(1013497,'ar49_prazo_dias','int4','Parâmetro de margem de dias para desconsiderar no processamento ex: configurar processamento para 5 dias anterior a data que está sendo processado','0', 'Prazo dias',10,'f','f','f',1,'text','Prazo dias');");
        DB::statement("insert into db_syscampo values(1013498,'ar49_margem_dias','int4','Dias de tolerância após vencimento de parcela para que ela seja considerada no parcelamento','0', 'Margem dias de tolerância',10,'f','f','f',1,'text','Margem dias de tolerância');");
        DB::statement("insert into db_syscampo values(1013499,'ar49_parcelas_vencidas','int4','Quantidade de parcelas vencidas definidas por parâmetro dinâmico','0', 'Quantidade de parcelas vencidas',10,'f','f','f',1,'text','Quantidade de parcelas vencidas');");
        DB::statement("insert into db_syscampo values(1013500,'ar49_acao','int4','Ação utilizada para alterar data de vencimento ou excluir parcelamento vencido','0', 'Ação controle parcelamento vencidas',10,'f','f','f',0,'text','Ação controle parcelamento vencidas');");
        DB::statement("insert into db_syscampo values(1013501,'ar49_regra_parcelamento','int4','Regra de parcelamento','0', 'Regra de parcelamento',10,'f','f','f',1,'text','Regra de parcelamento');");
        DB::statement("insert into db_syscampo values(1013570,'ar49_agendamento_ativo','bool','Indica se o agendamento está ativo ou não.','0', 'Situação do agendamento',10,'f','f','f',5,'text','Situação do agendamento');");

        // organiza campos controleparc_agendamamento
        $dados = [
            [1010837,1013494,1,0],
            [1010837,1013495,2,0],
            [1010837,1013496,3,0],
            [1010837,1013497,4,0],
            [1010837,1013498,5,0],
            [1010837,1013499,6,0],
            [1010837,1013500,7,0],
            [1010837,1013501,8,0],
            [1010837,1013570,9,0],
        ];

        DB::table('configuracoes.db_sysarqcamp')->insert($this->dbSysArqCamp($dados));

        DB::statement("insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010837,1013494,1,1013494);");
        DB::statement("insert into db_sysindices values(1008698,'controleparc_agendamento_ar49_id_ind',1010837,'1');");
        DB::statement("insert into db_syscadind values(1008698,1013494,1);");

        $dados = [
            [1010837,1013500,1,1010836,0],
            [1010837,1013501,1,1257,0],
        ];

        DB::table('configuracoes.db_sysforkey')->insert($this->dbSysForKey($dados));

        // controleparc_registrosorig
        DB::statement("insert into db_sysarquivo values (1010842, 'controleparc_registrosorig', 'Registros originais anteriores ao processamento', 'ar51', '2021-12-14', 'Registros originais anteriores ao processamento', 1, 't', 't', 't', 't' );");
        DB::statement("insert into db_sysarqmod values (54,1010842);");
        DB::statement("insert into db_syscampo values(1013546,'ar51_id','int8','Chave primária','0', 'Código',10,'f','f','f',1,'text','Código');");
        DB::statement("insert into db_syscampo values(1013547,'ar51_numpre','int4','Numpre de parcelamento que foi atualizado','0', 'Numpre',10,'f','f','f',1,'text','Numpre');");
        DB::statement("insert into db_syscampo values(1013548,'ar51_numpar','int4','Numpar do parcelamento que foi atualizado','0', 'Número da parcela',10,'f','f','f',1,'text','Número da parcela');");
        DB::statement("insert into db_syscampo values(1013556,'ar51_receit','int4','Receita','0', 'Valor receita',10,'f','f','f',1,'text','Receita');");
        DB::statement("insert into db_syscampo values(1013549,'ar51_dtvenc','date','Data de vencimento original do parcelamento que foi atualizado','0', 'Data de vencimento',10,'f','f','f',0,'text','Data de vencimento');");
        DB::statement("insert into db_syscampo values(1013572,'ar51_novadtvenc','date','Data de vencimento atualizada na arrecad','0', 'Nova data de vencimento',10,'f','f','f',1,'text','Nova data de vencimento');");
        DB::statement("insert into db_syscampo values(1013573,'ar51_id_agendamento','int4','id que referencia o id do agendamento processado','0', 'id do agendamento processado',10,'f','f','f',1,'text','id do agendamento processado');");
        DB::statement("insert into db_syscampo values(1013574,'ar51_dtproc','date','Data em que o agendamento foi processado','0', 'Data de processamento do agendamento',10,'f','f','f',1,'text','Data de processamento do agendamento');");
        
         // organiza campos controleparc_registrosorig 
         $dados = [
            [1010842,1013546,1,0],
            [1010842,1013547,2,0],
            [1010842,1013548,3,0],
            [1010842,1013556,4,0],
            [1010842,1013549,5,0],
            [1010842,1013572,6,0],
            [1010842,1013573,7,0],
            [1010842,1013574,8,0],
        ];

        DB::table('configuracoes.db_sysarqcamp')->insert($this->dbSysArqCamp($dados));

        DB::statement("insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010842,1013546,1,1013546);");
        DB::statement("insert into db_sysindices values(1008702,'controleparc_registrosorig_ar51_id_ind',1010842,'1');");
        DB::statement("insert into db_syscadind values(1008702,1013546,1);");

        $dados = [
            [1010842,1013573,1,1010842,0],
        ];

        DB::table('configuracoes.db_sysforkey')->insert($this->dbSysForKey($dados));

        // controleparc_rollback
        DB::statement("insert into db_sysarquivo values (1010844, 'controleparc_rollback', 'Registros salvos na controleparc_registorig que tiveram rollback para arrecad', 'ar52', '2021-12-30', 'Registros que foram retornados para arrecad', 1, 't', 't', 't', 't' );");
        DB::statement("insert into db_sysarqmod values (54,1010844);");
        DB::statement("insert into db_syscampo values(1013575,'ar52_id','int8','id','0', 'id',10,'f','f','f',1,'text','id');");
        DB::statement("insert into db_syscampo values(1013576,'ar52_dtrollback','date','Data de rollback do parcelamento','0', 'Data de rollback',10,'f','f','f',1,'text','Data de rollback');");
        DB::statement("insert into db_syscampo values(1013577,'ar52_numpre','int4','Numpre do parcelamento que teve rollback','0', 'Numpre',10,'f','f','f',0,'text','Numpre');");
        DB::statement("insert into db_syscampo values(1013578,'ar52_numpar','int4','Numpar do parcelamemto que teve rollback','0', 'Numpar',10,'f','f','f',1,'text','Numpar');");
        DB::statement("insert into db_syscampo values(1013579,'ar52_receit','int4','Receita do parcelamento que teve rollback','0', 'Receita',10,'f','f','f',1,'text','Receita');");
        DB::statement("insert into db_syscampo values(1013580,'ar52_dtvenc','date','Data de vencimento do parcelamento','0', 'Data de vencimento',10,'f','f','f',1,'text','Data de vencimento');");
        DB::statement("insert into db_syscampo values(1013581,'ar52_usuario','text','Usuario responsável pelo rollback','0', 'Usuario responsavel rollback',10,'f','f','f',1,'text','Usuario responsavel rollback');");

        // organiza campos controleparc_rollback 
        $dados = [
            [1010844 ,1013575 ,1 ,0],
            [1010844 ,1013576 ,2 ,0],
            [1010844 ,1013577 ,3 ,0],
            [1010844 ,1013578 ,4 ,0],
            [1010844 ,1013579 ,5 ,0],
            [1010844 ,1013580 ,6 ,0],
            [1010844 ,1013581 ,7 ,0],
        ];
        
        DB::table('configuracoes.db_sysarqcamp')->insert($this->dbSysArqCamp($dados));

        DB::statement("insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010844,1013575,1,1013575);");
        DB::statement("insert into db_sysindices values(1008703,'controleparc_rollback_ar52_id_ind',1010844,'0');");
        DB::statement("insert into db_syscadind values(1008703,1013575,1);");
    }

    private function downDicionario()
    {
        // itens menu
        DB::statement("delete from db_itensmenu where id_item in (228593, 228592, 228611);");
        DB::statement("delete from db_menu where id_item_filho in (228593, 228592);");
        DB::statement("delete from db_menu where id_item_filho in (228593, 228611);");

        // controleparc_agendamento
        DB::table('db_sysforkey')->where('codarq', 1010837)->delete();
        DB::table('db_syscadind')->where('codind', 1008698)->delete();
        DB::table('db_sysindices')->where('codind', 1008698)->delete();
        DB::table('db_sysprikey')->where('codcam', 1013494)->delete();
        DB::table('db_sysarqcamp')
            ->whereIn('codcam', [1013570, 1013501, 1013500, 1013499, 1013498, 1013497, 1013496, 1013495, 1013494])
            ->delete();
        DB::table('db_syscampo')
            ->whereIn('codcam', [1013570, 1013501, 1013500, 1013499, 1013498, 1013497, 1013496, 1013495, 1013494])
            ->delete();
        DB::statement("delete from db_sysarqmod where codarq = 1010837;");
        DB::statement("delete from db_sysarquivo where codarq = 1010837;");

        // controleparc_acao
        DB::statement("delete from db_syscadind where codind = 1008697;");
        DB::statement("delete from db_sysindices where codind = 1008697;");
        DB::table('db_sysprikey')->where('codcam', 1013492)->delete();
        DB::table('db_sysarqcamp')->whereIn('codcam', [1013492, 1013493])->delete();
        DB::table('db_syscampo')->whereIn('codcam', [1013492, 1013493])->delete();
        DB::table('db_sysarqmod')->where('codarq', 1010836)->delete();
        DB::table('db_sysarquivo')->where('codarq', 1010836)->delete();

        //controleparc_registrosorig
        DB::table('db_sysforkey')->where('codarq', 1010842)->delete();
        DB::table('db_syscadind')->where('codind', 1008702)->delete();
        DB::table('db_sysindices')->where('codind', 1008702)->delete();
        DB::table('db_sysprikey')->where('codcam', 1013546)->delete();
        DB::table('db_sysarqcamp')->whereIn('codcam', [1013574 ,1013573 ,1013572, 1013546, 1013547, 1013548, 1013556, 1013549])->delete();
        DB::table('db_syscampo')->whereIn('codcam', [1013574, 1013573 ,1013572, 1013546, 1013547, 1013548, 1013556, 1013549])->delete();
        DB::table('db_sysarqmod')->where('codarq', 1010842)->delete();
        DB::table('db_sysarquivo')->where('codarq', 1010842)->delete();

        //controleparc_rollback
        DB::table('db_syscadind')->where('codind', 1008703)->delete();
        DB::table('db_sysindices')->where('codind', 1008703)->delete();
        DB::table('db_sysprikey')->where('codcam', 1013575)->delete();
        DB::table('db_sysarqcamp')->whereIn('codcam', [1013581 ,1013580 ,1013579, 1013578, 1013577, 1013576, 1013575])->delete();
        DB::table('db_syscampo')->whereIn('codcam', [1013581 ,1013580 ,1013579, 1013578, 1013577, 1013576, 1013575])->delete();
        DB::table('db_sysarqmod')->where('codarq', 1010844)->delete();
        DB::table('db_sysarquivo')->where('codarq', 1010844)->delete();
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