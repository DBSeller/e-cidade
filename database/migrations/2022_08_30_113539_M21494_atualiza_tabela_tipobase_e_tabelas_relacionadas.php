<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M21494AtualizaTabelaTipobaseETabelasRelacionadas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $dados = [
            [1014463,'ed182_ordem_historico','int4','Ordem no Histórico','0', 'Ordem no Histórico',11,'f','f','f',1,'text','Ordem no Histórico']
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
            [1010913,1014463,9,0]
        ];

        foreach ($dados as $linha) {
            DB::table('configuracoes.db_sysarqcamp')->insert([
                'codarq' => $linha[0],
                'codcam' => $linha[1],
                'seqarq' => $linha[2],
                'codsequencia' => $linha[3]
            ]);
        }
        
        Schema::table('secretariadeeducacao.tipobase', function (Blueprint $table) {
            $table->integer('ed182_ordem_historico')->nullable();
        });


        DB::table('secretariadeeducacao.tipobase')->get()->map(function ($ocorrencia) {
            DB::table('secretariadeeducacao.tipobase')->where('ed182_id', $ocorrencia->ed182_id)
                ->update(['ed182_ordem_historico' =>  $ocorrencia->ed182_id]);
        });

        DB::table('secretariadeeducacao.tipobase')->where('ed182_id', 1)
            ->update(['ed182_ordem_historico' => 2]);

        DB::table('secretariadeeducacao.tipobase')->where('ed182_id', 2)
            ->update(['ed182_ordem_historico' => 1]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('configuracoes.db_sysarqcamp')->where('codcam',1014463)->delete();
        DB::table('configuracoes.db_syscampo')->where('codcam',1014463)->delete();

        Schema::table('secretariadeeducacao.tipobase', function (Blueprint $table) {
            $table->dropColumn('ed182_ordem_historico');
        });
    }
}
