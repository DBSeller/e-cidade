<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M21494CriandoCampoExibeObsDiarioNosModelosDeRelatorio extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->upDicionario();

        Schema::table('secretariadeeducacao.edu_relatmodel', function (Blueprint $table)  {
            $table->boolean('ed217_exibe_obs_diario')->default(false);
        });

        DB::table('secretariadeeducacao.tipobase')->where('ed182_id', 1)->update([
            'ed182_descricao' => 'Base Nacional Comum Curricular'
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->downDicionario();

        Schema::table('secretariadeeducacao.edu_relatmodel', function (Blueprint $table)  {
            $table->dropColumn('ed217_exibe_obs_diario');
        });      
    }

    public function upDicionario()
    {
        $dados = [
            [1014432,'ed217_exibe_obs_diario','bool','Exibe Observação do Diário','false', 'Exibe Observação do Diário',1,'f','f','f',5,'text','Exibe Observação do Diário']
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
            [2571,1014432,15,0]
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
        DB::table('configuracoes.db_sysarqcamp')->where('codcam', 1014432)->delete();
        DB::table('configuracoes.db_syscampo')->where('codcam', 1014432)->delete();
    }
}
