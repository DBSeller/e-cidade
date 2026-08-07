<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class M22240AuditoriaConcessaoDireitos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("SELECT configuracoes.fc_auditoria_cria_funcao('recursoshumanos.assentconf');");
        DB::statement("SELECT configuracoes.fc_auditoria_cria_funcao('recursoshumanos.assentperc');");
        DB::statement("SELECT configuracoes.fc_auditoria_cria_funcao('recursoshumanos.assentform');");
        DB::statement("SELECT configuracoes.fc_auditoria_cria_funcao('recursoshumanos.assentconcedeconf');");
        DB::statement("SELECT configuracoes.fc_auditoria_cria_funcao('recursoshumanos.concessaocalculo');");
        DB::statement("SELECT configuracoes.fc_auditoria_cria_funcao('recursoshumanos.concessaocalculolog');");
        DB::statement("SELECT configuracoes.fc_auditoria_cria_funcao('recursoshumanos.concessaocalculonovadata');");
        DB::statement("SELECT configuracoes.fc_auditoria_cria_funcao('recursoshumanos.concessaoassent');");
        DB::statement("SELECT configuracoes.fc_auditoria_cria_funcao('recursoshumanos.concessaocalculonovadatalog');");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("SELECT configuracoes.fc_auditoria_remove_funcao('recursoshumanos.assentconf');");
        DB::statement("SELECT configuracoes.fc_auditoria_remove_funcao('recursoshumanos.assentperc');");
        DB::statement("SELECT configuracoes.fc_auditoria_remove_funcao('recursoshumanos.assentform');");
        DB::statement("SELECT configuracoes.fc_auditoria_remove_funcao('recursoshumanos.assentconcedeconf');");
        DB::statement("SELECT configuracoes.fc_auditoria_remove_funcao('recursoshumanos.concessaocalculo');");
        DB::statement("SELECT configuracoes.fc_auditoria_remove_funcao('recursoshumanos.concessaocalculolog');");
        DB::statement("SELECT configuracoes.fc_auditoria_remove_funcao('recursoshumanos.concessaocalculonovadata');");
        DB::statement("SELECT configuracoes.fc_auditoria_remove_funcao('recursoshumanos.concessaoassent');");
        DB::statement("SELECT configuracoes.fc_auditoria_remove_funcao('recursoshumanos.concessaocalculonovadatalog');");
    }
}
