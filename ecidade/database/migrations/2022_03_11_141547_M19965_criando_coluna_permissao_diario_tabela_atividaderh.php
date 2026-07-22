<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M19965CriandoColunaPermissaoDiarioTabelaAtividaderh extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::connection()->getPdo()->exec(<<<SQL
        alter table atividaderh add COLUMN "ed01_permissao_diario" int4 default 0;

                insert into db_syscampo(
                                codcam,
                                nomecam,
                                conteudo,
                                descricao,
                                valorinicial,
                                rotulo,
                                tamanho,
                                nulo,
                                maiusculo,
                                autocompl,
                                aceitatipo,
                                tipoobj,
                                rotulorel)

                        values( 1013651, 
                                'ed01_permissao_diario',
                                'int4',
                                'Permissão acesso ao diário
                                0 - acesso restrito (default)
                                1 - acesso parcial/vínculos 
                                2 - acesso total',
                                0,
                                'Permissão acesso ao diário',
                                10,
                                'f',
                                'f',
                                'f',
                                1,
                                'text',
                                'Permissão acesso ao diário');

                insert into db_sysarqcamp(
                                codarq, codcam,seqarq, codsequencia)
                        values (1010095, 1013651, 11, 0);




SQL
        );
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::connection()->getPdo()->exec(<<<SQL
        alter table atividaderh drop COLUMN "ed01_permissao_diario";


        delete from db_sysarqcamp where codcam = 1013651;
        delete from db_syscampo where codcam = 1013651;

SQL
        );
        
    }
}
