<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M20846CriacaoCampoFormaorganizacaoturmaTabelaRegimemat extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->upDicionario();

        Schema::table('escola.regimemat', function($table) {
            $table->integer('ed218_organizacaoturma')->nullable();
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

        Schema::table('escola.regimemat', function($table) {
            $table->dropColumn('ed218_organizacaoturma');
        });
    }

    public function upDicionario()
    {
        $dados = [
            [1014154,'ed218_organizacaoturma','int4','Forma de Organização da Turma','0', 'Forma de Organização da Turma',11,'t','f','f',1,'text','Forma de Oragnização da Turma']
		];
		
		foreach ($dados as $linha) {
			DB::table('configuracoes.db_syscampo')->insert([
				'codcam' => $linha[0],
				'nomecam' => $linha[1],
				'conteudo' => $linha[2],
				'descricao' => $linha[3],
				'valorinicial' => $linha[4],
				'rotulo' => $linha[5],
				'tamanho' => $linha[6],
				'nulo' => $linha[7],
				'maiusculo' => $linha[8],
				'autocompl' => $linha[9],
				'aceitatipo' => $linha[10],
				'tipoobj' => $linha[11],
				'rotulorel' => $linha[12]
			]);
		}

		$dados = [
            [2625,1014154,5,0]
		];

		foreach ($dados as $linha) {
			DB::table('configuracoes.db_sysarqcamp')->insert([
				'codarq' => $linha[0],
				'codcam' => $linha[1],
				'seqarq' => $linha[2],
				'codsequencia' => $linha[3]
			]);
		}
    }
    
    public function downDicionario()
    {
        $dados = 1014154;
        DB::table('configuracoes.db_sysarqcamp')->where('codcam', $dados)->delete();
		DB::table('configuracoes.db_syscampo')->where('codcam', $dados)->delete();
    }

}
