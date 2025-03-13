<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M21792AdicionaCampoDb83ContaunicaContabancaria extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*
         * Foi verificado que anteriormente no dicionario de dados existia o campo db83_instit vinculado a tabela configuracoes.contabancaria, 
         * contudo na estrutura das bases de dados dos clientes não consta esse campo.
         * Foi regerado o vinculo dos campos da tabela db_sysarqcamp e o campo db83_instit foi retirado para seguir fiel a estrutura
         * da base de dados. 
         */
        $sql = <<<SQL
insert into db_syscampo values(1014461,'db83_contaunica','bool','Conta única, quando a conta pode ser visualizada em todas as instituições;','f', 'Conta única',1,'f','f','f',5,'text','Conta única');
insert into db_syscampodef values(1014461,'f','NÃO');
insert into db_syscampodef values(1014461,'t','SIM');
delete from db_sysarqcamp where codarq = 2740;
insert into db_sysarqcamp values(2740,15622,1,1652);
insert into db_sysarqcamp values(2740,15623,2,0);
insert into db_sysarqcamp values(2740,15624,3,0);
insert into db_sysarqcamp values(2740,15625,4,0);
insert into db_sysarqcamp values(2740,15626,5,0);
insert into db_sysarqcamp values(2740,15641,6,0);
insert into db_sysarqcamp values(2740,15642,7,0);
insert into db_sysarqcamp values(2740,15643,8,0);
insert into db_sysarqcamp values(2740,18251,9,0);
insert into db_sysarqcamp values(2740,1014461,10,0);

alter table configuracoes.contabancaria add column db83_contaunica boolean default false;
        
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

delete from db_sysarqcamp where codcam = 1014461;
delete from db_syscampodef where codcam = 1014461;
delete from db_syscampo where codcam = 1014461;      

alter table configuracoes.contabancaria drop column db83_contaunica;

SQL;
        DB::connection()->getPdo()->exec($sql);
        
    }
}
