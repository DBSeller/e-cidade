<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M22211CreateColumnNomeSocialCgsUnd extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ambulatorial.cgs_und', function (Blueprint $table) {
            $table->string('z01_nome_social')->nullable();
        });

        \DB::connection()->getPdo()->exec(<<<SQL
            insert into db_syscampo values(1014621,'z01_nome_social','varchar(100)','Nome social CGS.','', 'Nome Social',100,'t','t','f',0,'text','Nome Social');
            insert into db_sysarqcamp values(1010144,1014621,81,0);
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
        \DB::connection()->getPdo()->exec(<<<SQL
            delete from db_sysarqcamp where codcam = 1014621;
            delete from db_syscampo where codcam = 1014621;
SQL
        );

        Schema::table('ambulatorial.cgs_und', function (Blueprint $table) {
            $table->dropColumn(['z01_nome_social']);
        });
    }
}
