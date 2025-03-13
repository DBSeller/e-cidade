<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M21949AdicionaCamposFartiporeceita extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::connection()->getPdo()->exec(<<<SQL

            ALTER TABLE far_tiporeceita
                ADD COLUMN fa03_numero_notificacao BOOLEAN default false;

            insert into db_syscampo values(1014610,'fa03_numero_notificacao','char(1)','Campo que obriga o usuário a informar o número de notificação da receita.','', 'Número de Notificação',1,'f','t','f',0,'text','Número de Notificação');
            update db_syscampo set nomecam = 'fa03_numero_notificacao', conteudo = 'bool', descricao = 'Campo que obriga o usuário a informar o número de notificação da receita.', valorinicial = 'f', rotulo = 'Número de Notificação', nulo = 'f', tamanho = 1, maiusculo = 'f', autocompl = 'f', aceitatipo = 5, tipoobj = 'text', rotulorel = 'Número de Notificação' where codcam = 1014610;
            insert into db_syscampodef values(1014610,'false','');
            insert into db_sysarqcamp values(2105,1014610,12,0);


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

            ALTER TABLE far_tiporeceita
                DROP COLUMN fa03_numero_notificacao;

            DELETE FROM db_sysarqcamp where codcam = 1014610;
            DELETE FROM db_syscampodef where codcam = 1014610;
            DELETE FROM db_syscampo where codcam = 1014610;


SQL
        );
    }
}
