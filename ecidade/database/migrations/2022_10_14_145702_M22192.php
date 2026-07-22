<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M22192 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::connection()->getPdo()->exec(<<<SQL
            insert into configuracoes.db_syscampo values(1059584,'rh37_cadini','bool','Cargo acumulável','', 'Cargo acumulável',1,'f','t','f',0,'f','Cargo acumulável');
            update db_syscampo set nomecam = 'rh37_acumcargo', conteudo = 'bool', descricao = 'Cargo acumulável', valorinicial = 'f', rotulo = 'Cargo acumulável', nulo = 'f', tamanho = 1, maiusculo = 't', autocompl = 'f', aceitatipo = 0, tipoobj = 'text', rotulorel = 'Cargo acumulável' where codcam = 1059584;
            delete from configuracoes.db_sysarqcamp where codarq = 1174;
            insert into configuracoes.db_sysarqcamp values(1174,9906,1,0);
            insert into configuracoes.db_sysarqcamp values(1174,7094,2,0);
            insert into configuracoes.db_sysarqcamp values(1174,7095,3,0);
            insert into configuracoes.db_sysarqcamp values(1174,7096,4,0);
            insert into configuracoes.db_sysarqcamp values(1174,7097,5,0);
            insert into configuracoes.db_sysarqcamp values(1174,1059584,6,0);
            insert into configuracoes.db_sysarqcamp values(1174,7098,7,0);
            insert into configuracoes.db_sysarqcamp values(1174,7099,8,0);
            insert into configuracoes.db_sysarqcamp values(1174,15329,9,0);
            insert into configuracoes.db_sysarqcamp values(1174,17909,10,0);
            insert into configuracoes.db_sysarqcamp values(1174,1009972,11,0);
            insert into configuracoes.db_sysarqcamp values(1174,1009973,12,0);
            insert into configuracoes.db_sysarqcamp values(1174,1010974,13,0);
            insert into configuracoes.db_sysarqcamp values(1174,1010975,14,0);
            insert into configuracoes.db_sysarqcamp values(1174,1013709,15,0);

            alter table rhfuncao add rh37_cadini BOOLEAN default FALSE;
            alter table rhfuncao RENAME rh37_cadini TO rh37_acumcargo;
            delete from configuracoes.db_syscampodep where codcam = 1059584;
            delete from configuracoes.db_syscampodef where codcam = 1059584;

            delete from db_syscampodep where codcam = 1059584;
            delete from db_syscampodef where codcam = 1059584;
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
        delete from configuracoes.db_sysarqcamp where codcam  = 1059584;
        delete from configuracoes.db_syscampo where codcam  = 1059584;
        
        alter table pessoal.rhfuncao drop column rh37_acumcargo;
SQL
);
    }
}
