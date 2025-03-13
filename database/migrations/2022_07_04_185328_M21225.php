<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M21225 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sql = <<<SQL
delete from db_sysarqcamp where codcam = 1011160;
delete from db_syscampo where codcam = 1011160;

insert into db_syscampo (codcam,nomecam,conteudo,descricao,valorinicial,rotulo,tamanho,nulo,maiusculo,autocompl,aceitatipo,tipoobj,rotulorel)
                 values (1011160, 'rh05_observacao', 'text', 'Texto para observações da rescisão.', null, 'Observações', 1, 't', 't', 'f', 0, 'text', 'Observações');
insert into db_sysarqcamp (codarq, codcam, seqarq, codsequencia )
                   values (1161, 1011160, 17, 0);
                   
alter table pessoal.rhpesrescisao add column if not exists rh05_observacao text;

SQL;
        
        DB::connection()->getPdo()->exec($sql);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $sql = <<<SQL
delete from db_sysarqcamp where codcam = 1011160;
delete from db_syscampo where codcam = 1011160;

alter table pessoal.rhpesrescisao drop column rh05_observacao;
SQL;
        
        DB::connection()->getPdo()->exec($sql);
        
    }
}
