<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M21946AdicionaCamposFartiporeceita extends Migration
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
                ADD COLUMN fa03_data_prescricao BOOLEAN default false,
                ADD COLUMN fa03_dias_prescricao INTEGER default 0;

            insert into db_syscampo values(1014567,'fa03_data_prescricao','bool','Campo que obriga o usuário a informar a data da prescrição da receita. ','f', 'Data Prescrição',1,'f','f','f',5,'text','Data Prescrição');
            insert into db_syscampodef values(1014567,'false','');
            insert into db_syscampo values(1014568,'fa03_dias_prescricao','int4','Especifica o número de dias da validade da prescrição','0', 'N° de dias da validade da Prescrição',10,'f','f','f',1,'text','N° de dias da validade da Prescrição');
            insert into db_syscampodef values(1014568,'0','');

            insert into db_sysarqcamp values(2105,1014567,10,0);
            insert into db_sysarqcamp values(2105,1014568,11,0);

            ALTER TABLE far_retirada
                ADD COLUMN fa04_data_prescricao DATE;

            insert into db_syscampo values(1014575,'fa04_data_prescricao','date','Campo disponível para registro da data de prescrição da receita.','null', 'Data Prescrição',10,'t','f','f',1,'text','Data Prescrição');
            insert into db_sysarqcamp values(2106,1014575,13,0);


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
                DROP COLUMN fa03_data_prescricao,
                DROP COLUMN fa03_dias_prescricao;

            DELETE FROM db_sysarqcamp where codcam in (1014567, 1014568);
            DELETE FROM db_syscampodef where codcam in (1014567, 1014568);
            DELETE FROM db_syscampo where codcam in (1014567, 1014568);

            ALTER TABLE far_retirada
                DROP COLUMN fa04_data_prescricao;

            DELETE FROM db_sysarqcamp where codcam = 1014575;
            DELETE FROM db_syscampo where codcam = 1014575;

SQL
        );
    }
}

