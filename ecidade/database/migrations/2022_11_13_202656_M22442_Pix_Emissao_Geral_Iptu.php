<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M22442PixEmissaoGeralIptu extends Migration
{

    public function up()
    {
        $this->upEstrutura();
        $this->upDicionario();
    }

    public function down()
    {
        $this->downEstrutura();
        $this->downDicionario();
    }

    private function upEstrutura()
    {
        Schema::table("tributario.emissaogeral", function ($table) {
            $table->boolean("tr01_emissao_geral_padrao")->default(false);
        });

        DB::statement("ALTER TABLE tributario.emissaogeral ALTER COLUMN tr01_emissao_geral_padrao DROP DEFAULT");

        Schema::table("tributario.emissaogeralregistro", function ($table) {

            $table->integer("tr02_emissaogeralparcelaunica")->nullable()->default(null);
            $table->foreign("tr02_emissaogeralparcelaunica")->references("tr05_sequencial")->on("tributario.emissaogeralparcelaunica");
        });
    }

    private function downEstrutura()
    {
        Schema::table("tributario.emissaogeral", function ($table) {
            $table->dropColumn("tr01_emissao_geral_padrao");
        });

        Schema::table("tributario.emissaogeralregistro", function ($table) {
            $table->dropForeign("tributario_emissaogeralregistro_tr02_emissaogeralparcelaunica_foreign");
            $table->dropColumn("tr02_emissaogeralparcelaunica");
        });
    }

    private function upDicionario()
    {
        DB::connection()->getPdo()->exec(<<<SQL
            insert into db_syscampo values(1014597,'tr01_emissao_geral_padrao','text','Campo que define se os dados foram gerados a partir da rotina de emissão geral padrão ou emissão geral de cobrança','', 'Emissão geral padrão',11,'f','t','f',0,'text','Emissão geral padrão');
            insert into db_sysarqcamp values(3986,1014597,9,0);

            insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228811 ,'Emissao Geral de IPTU (Novo)' ,'Emissao Geral de IPTU (Novo)' ,'cad4_emiteiptuNovo.php?emissaoGeralPadrao=true' ,'1' ,'1' ,'Nova rotina pra a emissão geral de IPTU' ,'true' );
            insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 32 ,228811 ,563 ,578 );

            insert into db_syscampo values(1014622,'tr02_emissaogeralparcelaunica','int8','Sequencial da tabela emissaogeralparcelaunica','0', 'Sequencial',11,'t','f','f',1,'text','Sequencial');
            insert into db_sysarqcamp values(3987,1014622,7,0);
            insert into db_sysforkey values(3987,1014622,1,3991,0);
SQL
        );
    }

    private function downDicionario()
    {
        DB::connection()->getPdo()->exec(<<<SQL
            DELETE FROM db_sysforkey WHERE codcam IN (
                /* emissaogeralregistro */
                1014622
            );

            DELETE FROM db_sysarqcamp WHERE codcam IN (
                /* emissaogeral */
                1014597,
                /* emissaogeralregistro */
                1014622
            );

            DELETE FROM db_syscampo WHERE codcam IN (
                /* emissaogeral */
                1014597,
                /* emissaogeralregistro */
                1014622
            );

        delete from db_itensmenu where id_item = 228811;
        delete from db_menu where id_item_filho = 228811 AND modulo = 578;
SQL
        );
    }
}
