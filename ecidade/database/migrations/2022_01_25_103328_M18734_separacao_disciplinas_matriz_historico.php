<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M18734SeparacaoDisciplinasMatrizHistorico extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        $this->upDicionario();
        $this->upEstrutura();
        
        $sSql = <<<SQL
update disciplina set ed12_matrizcurricular = true;
SQL;
        DB::statement($sSql);
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        $this->downDicionario();
        $this->downEstrutura();
    }
    
    public function upDicionario() {
        
        $sSql = <<<SQL
insert into db_syscampo (codcam,nomecam,conteudo,descricao,valorinicial,rotulo,nulo,tamanho,maiusculo,autocompl,aceitatipo,tipoobj,rotulorel)   
                 values (1013641,
                         'ed12_matrizcurricular',
                         'bool',
                         'Se a disciplina compõe a matriz curricular',
                         'f',
                         'Matriz Curricular',
                         'f',
                         1,
                         'f',
                         'f',
                         5,
                         'text',
                         'Matriz Curricular');

insert into db_syscampodef (codcam,defcampo,defdescr)
                    values (1013641,'f','Não'), 
                           (1013641,'t','Sim');

insert into db_sysarqcamp (codarq,codcam,seqarq,codsequencia)
                    values(1010046,1013641,4,0);
SQL;
        DB::connection()->getPdo()->exec($sSql);
        
    }
    
    public function upEstrutura() {
        
        $sSql = <<<SQL
alter table disciplina add column ed12_matrizcurricular bool default false;
SQL;
        DB::connection()->getPdo()->exec($sSql);
        
        
    }
    
    public function downDicionario() {
        
        $sSql = <<<SQL
delete from db_sysarqcamp where codarq = 1010046 and codcam = 1013641;
delete from db_syscampodef where codcam = 1013641;
delete from db_syscampo where codcam = 1013641;
SQL;
        DB::connection()->getPdo()->exec($sSql);
        
    }
    
    public function downEstrutura() {
        
        $sSql = <<<SQL
alter table disciplina drop column ed12_matrizcurricular;
SQL;
        DB::connection()->getPdo()->exec($sSql);
        
        
    }
    
}
