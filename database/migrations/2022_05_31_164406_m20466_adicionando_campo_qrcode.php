<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M20466AdicionandoCampoQrcode extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::connection()->getPdo()->exec(<<<sql
insert into db_syscampo values(1014190,'p116_qrcode','varchar(36)','Código Identificador para geração do QR Code','', 'Código Identificador',36,'f','f','f',0,'text','Código Identificador');
insert into db_sysarqcamp values(1010902,1014190,10,0);

alter table documentos_andamento add column p116_qrcode varchar(36);
update documentos_andamento set p116_qrcode = 'invalid';
alter table documentos_andamento alter column p116_qrcode set not null;
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
delete from db_sysarqcamp where codcam = 1014190;
delete from db_syscampo where codcam = 1014190;
alter table documentos_andamento drop column p116_qrcode;
sql
        );
    }
}
