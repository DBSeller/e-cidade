<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M17145EmissaoTermoDivida extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->upDicionario();

        Schema::create('divida.termoinscr', function(Blueprint $table) {
            $table->bigIncrements('v92_termo');
            $table->date('v92_dtinsc');
            $table->integer('v92_usuario');
            $table->integer('v92_instit');

            $table->foreign('v92_instit', 'termoinscr_instit_in')
                ->references('codigo')
                ->on('configuracoes.db_config');

            $table->foreign('v92_usuario', 'termoinscr_usuario_in')
                ->references('id_usuario')
                ->on('configuracoes.db_usuarios');

        });


        Schema::create('divida.termoinscrreg', function(Blueprint $table) {
            $table->bigInteger('v93_termo');
            $table->integer('v93_coddiv');
            $table->float('v93_vlrhis');
            $table->float('v93_vlrcor');
            $table->float('v93_vlrjur');
            $table->float('v93_vlrmul');

            $table->foreign('v93_termo', 'termoinscrreg_termo_in')
                ->references('v92_termo')
                ->on('divida.termoinscr');

            $table->foreign('v93_coddiv', 'termoinscrreg_coddiv_in')
                ->references('v01_coddiv')
                ->on('divida.divida');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->downDicionario();

        Schema::drop('divida.termoinscrreg');
        Schema::drop('divida.termoinscr');
    }

    private function upDicionario()
    {
        // itens menu
        DB::statement("insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228624 ,'Emissão Termo Dívida' ,'Emissão Termo Dívida' ,'div2_certtermo_001.php' ,'1' ,'1' ,'Rotina para emissão Termo de Inscrição em Dívida' ,'true' );");
        DB::statement("insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 30 ,228624 ,842 ,81 );");

        // termoinscr
        DB::statement("insert into db_sysarquivo values (1010872, 'termoinscr', 'Termo de inscrição em dívida ativa', 'v92', '2022-03-14', 'termoinscr', 1, 'f', 'f', 'f', 'f' );");
        DB::statement("insert into db_sysarqmod values (8,1010872);");        
        DB::statement("insert into db_syscampo values(1013798,'v92_termo','int8','Chave primária','0', 'Código',10,'f','f','f',1,'text','Código');");
        DB::statement("insert into db_syscampo values(1013799,'v92_dtinsc','date','Data de inscrição','0', 'Data de inscrição',10,'t','f','f',1,'text','Data de inscrição');");
        DB::statement("insert into db_syscampo values(1013800,'v92_usuario','int4','Usuário','0', 'Usuário',10,'f','f','f',1,'text','Usuário');");
        DB::statement("insert into db_syscampo values(1013802,'v92_instit','int4','Instituição','0', 'Instituição',10,'f','f','f',1,'text','Instituição');");
        
         // organiza campos termoinscr 
         $dados = [
            [1010872,1013798,1,0],
            [1010872,1013799,2,0],
            [1010872,1013800,3,0],
            [1010872,1013802,4,0],
        ];

        DB::table('configuracoes.db_sysarqcamp')->insert($this->dbSysArqCamp($dados));

        DB::statement("insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010872,1013798,1,1013798);");
        DB::statement("insert into db_sysindices values(1008756,'termoinscr_instit_in',1010872,'0');");
        DB::statement("insert into db_sysindices values(1008757,'termoinscr_usuario_in',1010872,'0');");
        DB::statement("insert into db_sysindices values(1008760,'termoinscr_termo_pk',1010872,'1');");
        DB::statement("insert into db_syscadind values(1008760,1013798,1);");
        DB::statement("insert into db_syssequencia values(1001061, 'termoinscr_v92_termo_seq', 1, 1, 9223372036854775807, 1, 1);");
        DB::statement("update db_sysarqcamp set codsequencia = 1001061 where codarq = 1010872 and codcam = 1013798;");

        $dados = [
            [1010872,1013802,1,83,0],
            [1010872,1013800,1,109,0],
        ];
        DB::table('configuracoes.db_sysforkey')->insert($this->dbSysForKey($dados));

        // termoinscrreg
        DB::statement("insert into db_sysarquivo values (1010871, 'termoinscrreg', 'Valores dos códigos de divida do termo de inscrição em dívida ativa', 'v93', '2022-03-14', 'termoinscrreg', 1, 'f', 'f', 'f', 'f' );");
        DB::statement("insert into db_sysarqmod values (8,1010871);");
        DB::statement("insert into db_syscampo values(1013783,'v93_termo','int8','Chave primária','0', 'Código',10,'f','f','f',1,'text','Código');");
        DB::statement("insert into db_syscampo values(1013784,'v93_coddiv','int4','Código da dívida referente a tabela dívida','0', 'Código dívida',10,'f','f','f',1,'text','Código dívida');");
        DB::statement("insert into db_syscampo values(1013785,'v93_vlrhis','float8','valor historico','0', 'valor historico',15,'f','f','f',4,'text','valor historico');");
        DB::statement("insert into db_syscampo values(1013786,'v93_vlrcor','float8','valor corrigido','0', 'valor corrigido',15,'f','f','f',4,'text','valor corrigido');");
        DB::statement("insert into db_syscampo values(1013787,'v93_vlrjur','float8','valor juros','0', 'valor juros',15,'t','f','f',4,'text','valor juros');");
        DB::statement("insert into db_syscampo values(1013788,'v93_vlrmul','float8','valor multa','0', 'valor multa',15,'t','f','f',4,'text','valor multa');");
        
         // organiza campos termoinscrreg 
         $dados = [
            [1010871,1013783,1,0],
            [1010871,1013784,2,0],
            [1010871,1013785,3,0],
            [1010871,1013786,4,0],
            [1010871,1013787,5,0],
            [1010871,1013788,6,0],
        ];

        DB::table('configuracoes.db_sysarqcamp')->insert($this->dbSysArqCamp($dados));

        DB::statement("insert into db_sysindices values(1008758,'termoinscrreg_coddiv_in',1010871,'0');");
        DB::statement("insert into db_sysindices values(1008772,'termoinscrreg_termo_in',1010871,'0');");

        $dados = [
            [1010871,1013784,1,96,0],
            [1010871,1013783,1,1010872,0],
        ];
        DB::table('configuracoes.db_sysforkey')->insert($this->dbSysForKey($dados));

    }

    private function downDicionario()
    {
        // itens menu
        DB::statement("delete from db_itensmenu where id_item in (228624);");
        DB::statement("delete from db_menu where id_item_filho in (228624, 81);");

        //termoinscr
        DB::table('db_syssequencia')->where('codsequencia', 1001061)->delete();
        DB::table('db_sysforkey')->where('codarq', 1010872)->delete();
        DB::table('db_syscadind')->whereIn('codind', [1008760, 1008757, 1008756])->delete();
        DB::table('db_sysindices')->whereIn('codind', [1008760, 1008757, 1008756])->delete();
        DB::table('db_sysprikey')->where('codcam', 1013798)->delete();
        DB::table('db_sysarqcamp')->whereIn('codcam', [1013802, 1013800, 1013799, 1013798])->delete();
        DB::table('db_syscampo')->whereIn('codcam', [1013802, 1013800, 1013799, 1013798])->delete();
        DB::table('db_sysarqmod')->where('codarq', 1010872)->delete();
        DB::table('db_acount')->where('codarq', 1010872)->delete();
        DB::table('db_sysarquivo')->where('codarq', 1010872)->delete();

        //termoinscrreg
        DB::table('db_sysforkey')->where('codarq', 1010871)->delete();
        DB::table('db_syscadind')->whereIn('codind', [1008758, 1008772])->delete();
        DB::table('db_sysindices')->whereIn('codind', [1008758, 1008772])->delete();
        DB::table('db_sysarqcamp')->whereIn('codcam', [1013788 ,1013787, 1013786, 1013785, 1013784, 1013783])->delete();
        DB::table('db_syscampo')->whereIn('codcam', [1013788 ,1013787, 1013786, 1013785, 1013784, 1013783])->delete();
        DB::table('db_sysarqmod')->where('codarq', 1010871)->delete();
        DB::table('db_sysarquivo')->where('codarq', 1010871)->delete();
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