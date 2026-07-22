<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M18692DataEnvioFase extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->upDicionario();
        $this->upEstrutura();
        $this->upAcertoEmBase();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->downDicionario();
         $this->downEstrutura();
    }

    private function upDicionario()
    {
        $sSql = <<<SQL
        insert into configuracoes.db_syscampo values(1013925,'r11_dataenviofase1','date','Data Início de Obrigatoriedade Eventos de Tabela (Fase 01)','null', 'Data Início fase 01 (Eventos Tabela)',10,'t','f','f',1,'text','Data Início fase 01 (Eventos Tabela)');
        insert into configuracoes.db_syscampo values(1013926,'r11_dataenviofase2','date','Data Início de Obrigatoriedade Eventos Não Periódicos (Fase 02)','null', 'Data Início fase 02 (Evt Não Periódicos)',10,'t','f','f',1,'text','Data Início fase 02 (Evt Não Periódicos)');
        insert into configuracoes.db_syscampo values(1013927,'r11_dataenviofase3','date','Data Início de Obrigatoriedade Eventos Periódicos (Fase 03)','null', 'Data Início fase 03 (Eventos Periódicos)',10,'t','f','f',1,'text','Data Início fase 03 (Eventos Periódicos)');
        insert into configuracoes.db_syscampo values(1013928,'r11_dataenviofase4','date','Data Início de Obrigatoriedade Eventos SST (Fase 04)','null', 'Data Início fase 04 (Eventos SST)',10,'t','f','f',1,'text','Data Início fase 04 (Eventos SST)');
        delete from configuracoes.db_sysarqcamp where codarq = 536;
        insert into configuracoes.db_sysarqcamp values(536,9892,1,0);
        insert into configuracoes.db_sysarqcamp values(536,3758,2,0);
        insert into configuracoes.db_sysarqcamp values(536,3759,3,0);
        insert into configuracoes.db_sysarqcamp values(536,3760,4,0);
        insert into configuracoes.db_sysarqcamp values(536,3761,5,0);
        insert into configuracoes.db_sysarqcamp values(536,3762,6,0);
        insert into configuracoes.db_sysarqcamp values(536,3763,7,0);
        insert into configuracoes.db_sysarqcamp values(536,3764,8,0);
        insert into configuracoes.db_sysarqcamp values(536,3765,9,0);
        insert into configuracoes.db_sysarqcamp values(536,3766,10,0);
        insert into configuracoes.db_sysarqcamp values(536,3767,11,0);
        insert into configuracoes.db_sysarqcamp values(536,3768,12,0);
        insert into configuracoes.db_sysarqcamp values(536,3769,13,0);
        insert into configuracoes.db_sysarqcamp values(536,3770,14,0);
        insert into configuracoes.db_sysarqcamp values(536,3771,15,0);
        insert into configuracoes.db_sysarqcamp values(536,3772,16,0);
        insert into configuracoes.db_sysarqcamp values(536,3773,17,0);
        insert into configuracoes.db_sysarqcamp values(536,3774,18,0);
        insert into configuracoes.db_sysarqcamp values(536,3775,19,0);
        insert into configuracoes.db_sysarqcamp values(536,3776,20,0);
        insert into configuracoes.db_sysarqcamp values(536,3777,21,0);
        insert into configuracoes.db_sysarqcamp values(536,3778,22,0);
        insert into configuracoes.db_sysarqcamp values(536,3779,23,0);
        insert into configuracoes.db_sysarqcamp values(536,3780,24,0);
        insert into configuracoes.db_sysarqcamp values(536,3781,25,0);
        insert into configuracoes.db_sysarqcamp values(536,3782,26,0);
        insert into configuracoes.db_sysarqcamp values(536,3783,27,0);
        insert into configuracoes.db_sysarqcamp values(536,3784,28,0);
        insert into configuracoes.db_sysarqcamp values(536,3785,29,0);
        insert into configuracoes.db_sysarqcamp values(536,3786,30,0);
        insert into configuracoes.db_sysarqcamp values(536,3787,31,0);
        insert into configuracoes.db_sysarqcamp values(536,3788,32,0);
        insert into configuracoes.db_sysarqcamp values(536,3789,33,0);
        insert into configuracoes.db_sysarqcamp values(536,3790,34,0);
        insert into configuracoes.db_sysarqcamp values(536,3791,35,0);
        insert into configuracoes.db_sysarqcamp values(536,3792,36,0);
        insert into configuracoes.db_sysarqcamp values(536,3793,37,0);
        insert into configuracoes.db_sysarqcamp values(536,3794,38,0);
        insert into configuracoes.db_sysarqcamp values(536,3795,39,0);
        insert into configuracoes.db_sysarqcamp values(536,3796,40,0);
        insert into configuracoes.db_sysarqcamp values(536,3797,41,0);
        insert into configuracoes.db_sysarqcamp values(536,3798,42,0);
        insert into configuracoes.db_sysarqcamp values(536,3799,43,0);
        insert into configuracoes.db_sysarqcamp values(536,3800,44,0);
        insert into configuracoes.db_sysarqcamp values(536,3801,45,0);
        insert into configuracoes.db_sysarqcamp values(536,3802,46,0);
        insert into configuracoes.db_sysarqcamp values(536,3803,47,0);
        insert into configuracoes.db_sysarqcamp values(536,3804,48,0);
        insert into configuracoes.db_sysarqcamp values(536,3805,49,0);
        insert into configuracoes.db_sysarqcamp values(536,4580,50,0);
        insert into configuracoes.db_sysarqcamp values(536,4581,51,0);
        insert into configuracoes.db_sysarqcamp values(536,4582,52,0);
        insert into configuracoes.db_sysarqcamp values(536,4583,53,0);
        insert into configuracoes.db_sysarqcamp values(536,5690,54,0);
        insert into configuracoes.db_sysarqcamp values(536,8931,55,0);
        insert into configuracoes.db_sysarqcamp values(536,8930,56,0);
        insert into configuracoes.db_sysarqcamp values(536,8929,57,0);
        insert into configuracoes.db_sysarqcamp values(536,8984,58,0);
        insert into configuracoes.db_sysarqcamp values(536,8983,59,0);
        insert into configuracoes.db_sysarqcamp values(536,8982,60,0);
        insert into configuracoes.db_sysarqcamp values(536,9023,61,0);
        insert into configuracoes.db_sysarqcamp values(536,9186,62,0);
        insert into configuracoes.db_sysarqcamp values(536,9437,63,0);
        insert into configuracoes.db_sysarqcamp values(536,9438,64,0);
        insert into configuracoes.db_sysarqcamp values(536,9459,65,0);
        insert into configuracoes.db_sysarqcamp values(536,9484,66,0);
        insert into configuracoes.db_sysarqcamp values(536,9571,67,0);
        insert into configuracoes.db_sysarqcamp values(536,9631,68,0);
        insert into configuracoes.db_sysarqcamp values(536,9633,69,0);
        insert into configuracoes.db_sysarqcamp values(536,9634,70,0);
        insert into configuracoes.db_sysarqcamp values(536,14442,71,0);
        insert into configuracoes.db_sysarqcamp values(536,15700,72,0);
        insert into configuracoes.db_sysarqcamp values(536,17102,73,0);
        insert into configuracoes.db_sysarqcamp values(536,18813,74,0);
        insert into configuracoes.db_sysarqcamp values(536,18814,75,0);
        insert into configuracoes.db_sysarqcamp values(536,18815,76,0);
        insert into configuracoes.db_sysarqcamp values(536,18816,77,0);
        insert into configuracoes.db_sysarqcamp values(536,19165,78,0);
        insert into configuracoes.db_sysarqcamp values(536,19283,79,0);
        insert into configuracoes.db_sysarqcamp values(536,20381,80,0);
        insert into configuracoes.db_sysarqcamp values(536,20436,81,0);
        insert into configuracoes.db_sysarqcamp values(536,20695,82,0);
        insert into configuracoes.db_sysarqcamp values(536,20737,83,0);
        insert into configuracoes.db_sysarqcamp values(536,20899,84,0);
        insert into configuracoes.db_sysarqcamp values(536,20900,85,0);
        insert into configuracoes.db_sysarqcamp values(536,20901,86,0);
        insert into configuracoes.db_sysarqcamp values(536,20988,87,0);
        insert into configuracoes.db_sysarqcamp values(536,21170,88,0);
        insert into configuracoes.db_sysarqcamp values(536,21171,89,0);
        insert into configuracoes.db_sysarqcamp values(536,21582,90,0);
        insert into configuracoes.db_sysarqcamp values(536,21642,91,0);
        insert into configuracoes.db_sysarqcamp values(536,21643,92,0);
        insert into configuracoes.db_sysarqcamp values(536,21644,93,0);
        insert into configuracoes.db_sysarqcamp values(536,21645,94,0);
        insert into configuracoes.db_sysarqcamp values(536,21695,95,0);
        insert into configuracoes.db_sysarqcamp values(536,21698,96,0);
        insert into configuracoes.db_sysarqcamp values(536,21963,97,0);
        insert into configuracoes.db_sysarqcamp values(536,21964,98,0);
        insert into configuracoes.db_sysarqcamp values(536,1009752,99,0);
        insert into configuracoes.db_sysarqcamp values(536,1013925,100,0);
        insert into configuracoes.db_sysarqcamp values(536,1013926,101,0);
        insert into configuracoes.db_sysarqcamp values(536,1013927,102,0);
        insert into configuracoes.db_sysarqcamp values(536,1013928,103,0);
SQL;
        DB::connection()->getPdo()->exec($sSql);
    }

    private function downDicionario()
    {
        $array_arq = ['1013925','1013926','1013927','1013928'];

        foreach ($array_arq as $value) {
            DB::connection()->getPdo()->exec("DELETE FROM configuracoes.db_sysarqcamp WHERE codcam={$value}");
        }

        $array_campo = ['1013925', '1013926', '1013927', '1013928'];

        foreach ($array_campo as $value) {
            DB::connection()->getPdo()->exec("DELETE FROM configuracoes.db_syscampo WHERE codcam={$value}");
        }
    }

    public function upEstrutura() {

        $sSql = <<<SQL
        alter table pessoal.cfpess add column r11_dataenviofase1 date;
        alter table pessoal.cfpess add column r11_dataenviofase2 date;
        alter table pessoal.cfpess add column r11_dataenviofase3 date;
        alter table pessoal.cfpess add column r11_dataenviofase4 date;
SQL;
        DB::connection()->getPdo()->exec($sSql);
    }

    public function downEstrutura() {

        $sSql = <<<SQL
        alter table pessoal.cfpess drop column r11_dataenviofase1;
        alter table pessoal.cfpess drop column r11_dataenviofase2;
        alter table pessoal.cfpess drop column r11_dataenviofase3;
        alter table pessoal.cfpess drop column r11_dataenviofase4;
SQL;
        DB::connection()->getPdo()->exec($sSql);
    }

    public function upAcertoEmbase() {

        DB::update("UPDATE pessoal.cfpess SET r11_dataenviofase1 =
        CASE
            WHEN r11_anousu >= 2021 THEN ('2021-07-21'::date)
            ELSE null
        END");

        DB::update("UPDATE pessoal.cfpess SET r11_dataenviofase2 =
        CASE
            WHEN r11_anousu >= 2021 THEN ('2021-11-22'::date)
            ELSE null
        END");

        DB::update("UPDATE pessoal.cfpess SET r11_dataenviofase3 =
        CASE
            WHEN r11_anousu >= 2021 THEN ('2022-04-22'::date)
            ELSE null
        END");

        DB::update("UPDATE pessoal.cfpess SET r11_dataenviofase4 =
        CASE
            WHEN r11_anousu >= 2021 THEN ('2022-07-11'::date)
            ELSE null
        END");
    }
}
