<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M18771CriandoVinculosRechumanoativ extends Migration
{
    public function up()
    {
        $this->dicionarioUp();
        DB::statement("alter table escola.rechumanohoradisp add column ed33_rechumanoativ integer;");

        DB::statement("alter table escola.rechumanohoradisp
                add constraint rechumanohoradisp_ed33_rechumanoativ_fk foreign key (ed33_rechumanoativ)
                    references rechumanoativ (ed22_i_codigo);");

        DB::statement("with vinculos as (
                select distinct on (ed33_i_codigo) ed33_i_codigo, ed22_i_codigo
                from rechumanohoradisp
                     join periodoescola ON periodoescola.ed17_i_codigo = rechumanohoradisp.ed33_i_periodo
                     join turno ON turno.ed15_i_codigo = periodoescola.ed17_i_turno
                     join turnoreferente ON turnoreferente.ed231_i_turno = turno.ed15_i_codigo
                     join rechumanoescola ON rechumanoescola.ed75_i_codigo = rechumanohoradisp.ed33_rechumanoescola
                     join rechumanoativ ON rechumanoativ.ed22_i_rechumanoescola = rechumanoescola.ed75_i_codigo
                        and rechumanoativ.ed22_turno = turnoreferente.ed231_i_referencia
                where rechumanoativ.ed22_ativo is true
                  and ed33_ativo is true
                  and rechumanoativ.ed22_datafim is null
            ) update rechumanohoradisp set ed33_rechumanoativ = ed22_i_codigo from vinculos
                where rechumanohoradisp.ed33_i_codigo = vinculos.ed33_i_codigo;");
    }

    public function down()
    {
        $this->dicionarioDown();

        DB::statement("alter table escola.rechumanohoradisp drop column ed33_rechumanoativ;");
    }

    private function dicionarioUp()
    {
        DB::statement("insert into db_syscampo values(1013398,'ed33_rechumanoativ','int4','Faz vinculo com a tabela rechumanoativ, cada rechumanohoradisp deve estar vinculado a uma função (rechumanoativ)','0', 'Função',10,'f','f','f',1,'text','Função');");
        DB::statement("insert into db_sysarqcamp values(1010091,1013398,8,0);");
        DB::statement("insert into db_sysforkey values(1010091,1013398,1,1010096,0);");
    }

    private function dicionarioDown()
    {
        DB::statement("delete from db_sysforkey where codcam = 1013398;");
        DB::statement("delete from db_sysarqcamp where codcam = 1013398;");
        DB::statement("delete from db_syscampo where codcam = 1013398;");
    }
}
