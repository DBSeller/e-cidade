<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M21494AjusteCamposPeriodos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $dados = [
            ['ed99_i_periodoref','varchar(200)','Período de referência','0','Período','t',200,'f','f',0,'text','Período']
        ];

        foreach ($dados as $linha) {
            DB::table('configuracoes.db_syscampo')->where('codcam', 1009015)->update([
                'nomecam' => $linha[0],
                'conteudo' => $linha[1],
                'descricao' => $linha[2],
                'valorinicial' => $linha[3],
                'rotulo' => $linha[4],
                'nulo' => $linha[5],
                'tamanho' => $linha[6],
                'maiusculo' => $linha[7],
                'autocompl' => $linha[8],
                'aceitatipo' => $linha[9],
                'tipoobj' => $linha[10],
                'rotulorel' => $linha[11]
            ]);
        }

        $dados = [
            ['ed62_i_periodoref','varchar(200)','Período de Referência','0','Período','t',200,'f','f',0,'text','Período']
        ];

        foreach ($dados as $linha) {
            DB::table('configuracoes.db_syscampo')->where('codcam', 1008774)->update([
                'nomecam' => $linha[0],
                'conteudo' => $linha[1],
                'descricao' => $linha[2],
                'valorinicial' => $linha[3],
                'rotulo' => $linha[4],
                'nulo' => $linha[5],
                'tamanho' => $linha[6],
                'maiusculo' => $linha[7],
                'autocompl' => $linha[8],
                'aceitatipo' => $linha[9],
                'tipoobj' => $linha[10],
                'rotulorel' => $linha[11]
            ]);
        }
       

        DB::statement("alter table escola.historicompsfora alter column ed99_i_periodoref type varchar(200);");
        DB::statement("alter table escola.historicomps alter column ed62_i_periodoref type varchar(200);");
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $dados = [
            ['ed99_i_periodoref','int4','Período de referência','0','Período','t',10,'f','f',1,'text','Período']
        ];

        foreach ($dados as $linha) {
            DB::table('configuracoes.db_syscampo')->where('codcam', 1009015)->update([
                'nomecam' => $linha[0],
                'conteudo' => $linha[1],
                'descricao' => $linha[2],
                'valorinicial' => $linha[3],
                'rotulo' => $linha[4],
                'nulo' => $linha[5],
                'tamanho' => $linha[6],
                'maiusculo' => $linha[7],
                'autocompl' => $linha[8],
                'aceitatipo' => $linha[9],
                'tipoobj' => $linha[10],
                'rotulorel' => $linha[11]
            ]);
        }



        $dados = [
            ['ed62_i_periodoref','int4','Período de Referência','0','Período','t',10,'f','f',1,'text','Período']
        ];

        foreach ($dados as $linha) {
            DB::table('configuracoes.db_syscampo')->where('codcam', 1008774)->update([
                'nomecam' => $linha[0],
                'conteudo' => $linha[1],
                'descricao' => $linha[2],
                'valorinicial' => $linha[3],
                'rotulo' => $linha[4],
                'nulo' => $linha[5],
                'tamanho' => $linha[6],
                'maiusculo' => $linha[7],
                'autocompl' => $linha[8],
                'aceitatipo' => $linha[9],
                'tipoobj' => $linha[10],
                'rotulorel' => $linha[11]
            ]);
        }

        DB::statement("alter table escola.historicompsfora alter column ed99_i_periodoref type integer using ed99_i_periodoref::integer;");
        DB::statement("alter table escola.historicomps alter column ed62_i_periodoref type integer using ed62_i_periodoref::integer;");
    }
}
