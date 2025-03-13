<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M21005ParametroFarmaciaAlertaPacienteNaoMoradorMunicipio extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('farmacia.far_parametros', function (Blueprint $table) {
            $table->boolean('fa02_alerta_nao_morador')->default(false);
            $table->integer('fa02_ibge_municipio')->nullable()->default(null);
        });
        $this->upDicionario();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->downDicionario();
        Schema::table('farmacia.far_parametros', function (Blueprint $table) {
            $table->dropColumn(['fa02_alerta_nao_morador', 'fa02_ibge_municipio']);
        });
    }

    private function upDicionario()
    {
        DB::connection()->getPdo()->exec(<<<SQL
insert into db_syscampo values(1014215,'fa02_alerta_nao_morador','bool','Parâmetro determina se deverá ser exibido um alerta, na rotina de entrega de medicamento, pedindo confirmação do usuário caso o paciente não seja morador do município.','f', 'Mostrar alerta não morador do munícipio',1,'f','f','f',5,'text','Mostrar alerta não morador do munícipio');
insert into db_syscampo values(1014216,'fa02_ibge_municipio','int4','Código IBGE do município.','0', 'Código IBGE',10,'f','f','f',1,'text','Código IBGE');
insert into db_sysarqcamp values(2103,1014215,22,0);
insert into db_sysarqcamp values(2103,1014216,23,0);
SQL
        );
    }

    private function downDicionario()
    {
        DB::connection()->getPdo()->exec(<<<SQL
delete from db_sysarqcamp where codcam in (1014215, 1014216);
delete from db_syscampo where codcam in (1014215, 1014216);
SQL
        );
    }
}
