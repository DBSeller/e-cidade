<?php

use Illuminate\Database\Migrations\Migration;

class M22032AdicionandoParametroContrachequesCfpess extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::connection()->getPdo()->exec(<<<SQL
insert into db_syscampo values (
        1014506,
        'r11_emissaocontracheque',
        'bool',
        'Controla emissão automática dos contracheques após a virada da folha',
        'f',
        'Contracheques gerados automaticamente e-storage',
        1,
        't',
        'f',
        'f',
        5,
        'text',
        'Contracheques gerados automaticamente'
    );
insert into db_sysarqcamp values (536,1014506,109,0);

alter table cfpess add column r11_emissaocontracheque boolean default false;
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
delete from db_sysarqcamp where codcam = 1014506;
delete from db_syscampo where codcam = 1014506;

alter table cfpess drop column r11_emissaocontracheque;
SQL
        );
    }
}
