<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M21208AdicionadoColunaDatalimiteObrastec extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      DB::connection()->getPdo()->exec(<<<SQL
       alter table projetos.obrastec add column ob15_datalimite date;
       insert into db_syscampo(codcam,nomecam,conteudo,descricao,valorinicial,rotulo,tamanho,nulo,maiusculo,autocompl,aceitatipo,tipoobj,rotulorel)
       values (1014619,'ob15_datalimite','date','Data limite para vincular técnico nas obras',null,'Data Limite',10,'f','f','f',1,'text','Data Limite');
       insert into db_sysarqcamp(codarq,codcam,seqarq,codsequencia) values(1001,1014619,6,0);
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
        alter table projetos.obrastec drop column ob15_datalimite;
        delete from db_sysarqcamp where codcam = 1014619;
        delete from db_syscampo where codcam = 1014619;
SQL
);
    }
}
