<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M20044AtualizacaoCamposEsocialEventoS2240 extends Migration
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
    
    public function upDicionario() {
        
        $sSql = <<<SQL
insert into db_syscampo (codcam,nomecam,conteudo,descricao,valorinicial,rotulo,tamanho,nulo,maiusculo,autocompl,aceitatipo,tipoobj,rotulorel) 
values (1013706,'rh56_datainicio','date','Data Inicial','null', 'Data Inicial',10,'t','f','f',0,'text','Data Inicial'),
       (1013707,'rh56_datafim','date','Data Final','null', 'Data Final',10,'t','f','f',0,'text','Data Final'),
       (1013708,'rh04_descricaoatividades','text','Atividades desempenhadas','', 'Atividades desempenhadas',999,'t','t','f',0,'text','Atividades desempenhadas'),
       (1013709,'rh37_descricaoatividades','text','Atividades desempenhadas','', 'Atividades desempenhadas',999,'t','t','f',0,'text','Atividades desempenhadas');

insert into db_sysarqcamp (codarq,codcam,seqarq,codsequencia)
values (1543,1013706,8,0),
       (1543,1013707,9,0),
       (1496,1013708,6,0),
       (1174,1013709,14,0);

SQL;
        DB::connection()->getPdo()->exec($sSql);
        
    }
    
    public function upEstrutura() {
        
        $sSql = <<<SQL
alter table rhpeslocaltrab add column rh56_datainicio date;
alter table rhpeslocaltrab add column rh56_datafim date;

alter table rhcargo add column rh04_descricaoatividades text;
                               
alter table rhfuncao add column rh37_descricaoatividades text;
SQL;
        DB::connection()->getPdo()->exec($sSql);
        
        
    }
    
    public function downDicionario() {
        
        $sSql = <<<SQL
delete from db_sysarqcamp where codcam in (1013706,1013707,1013708,1013709);
delete from db_syscampo where codcam in (1013706,1013707,1013708,1013709);
SQL;
        DB::connection()->getPdo()->exec($sSql);
        
    }
    
    public function downEstrutura() {
        
        $sSql = <<<SQL
alter table rhpeslocaltrab drop column rh56_datainicio;
alter table rhpeslocaltrab drop column rh56_datafim;

alter table rhcargo drop column rh04_descricaoatividades;

alter table rhfuncao drop column rh37_descricaoatividades;
SQL;
        DB::connection()->getPdo()->exec($sSql);
        
        
    }
}
