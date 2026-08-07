<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M20466AdicionarCampoTipoProcessoPermissao extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::connection()->getPdo()->exec(<<<sql
insert into db_syscampo values(1014217,'db69_tipoprocesso','int8','Tipo de Processo','0', 'Tipo de Processo',10,'f','f','f',1,'text','Tipo de Processo');
insert into db_sysarqcamp values(1010894,1014217,3,0);
insert into db_sysforkey values(1010894,1014217,1,393,0);

ALTER TABLE db_permemp_atividadesexecucao ADD COLUMN db69_tipoprocesso int8;
ALTER TABLE db_permemp_atividadesexecucao ADD CONSTRAINT db_permemp_atividadesexecucao_db69_tipoprocesso_fk FOREIGN KEY (db69_tipoprocesso) REFERENCES tipoproc(p51_codigo);
sql
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::connection()->getPdo()->exec(<<<sql
delete from db_sysforkey where codcam = 1014217;
delete from db_sysarqcamp where codcam = 1014217;
delete from db_syscampo where codcam = 1014217;

ALTER TABLE db_permemp_atividadesexecucao DROP CONSTRAINT db_permemp_atividadesexecucao_db69_tipoprocesso_fk;
ALTER TABLE db_permemp_atividadesexecucao DROP COLUMN db69_tipoprocesso;
sql
        );
    }
}
