<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TabelaRhAdiantamento extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::connection()->getPdo()->exec(<<<SQL
        insert into db_sysarquivo values (1010931, 'rhrubricasadiantamento', 'Rubrica de Adiantamento do 13o Salario', 'rh262', '2022-05-23', 'Rubrica de Adiantamento do 13o Salario', 2, 'f', 'f', 'f', 'f' );
        insert into db_sysarqmod values (28,1010931);
        insert into db_sysarqarq values (1177,1010931);
        insert into db_syscampo values(1014155,'rh262_rubrica_principal','varchar(4)','Campo que traz a Rubrica do Saldo do 13o Salario','', 'Rubrica do Saldo do 13o Salario',4,'t','f','f',0,'text','Rubrica do Saldo do 13o Salario');
        insert into db_syscampo values(1014156,'rh262_rubrica_adiantamento','varchar(4)','Campo que traz a Rubrica do Adiantamento do 13o Salario','', 'Rubrica de Adiantamento do 13o Salario',4,'t','f','f',0,'text','Rubrica de Adiantamento do 13o Salario');
        insert into db_syscampo values(1014157,'rh262_instituicao','int4','Instituição das Rubricas','0', 'Código da Instituição',4,'f','f','f',1,'text','Código da Instituição');
        insert into db_sysarqcamp values(1010931,1014155,1,0);
        insert into db_sysarqcamp values(1010931,1014156,2,0);
        insert into db_sysarqcamp values(1010931,1014157,3,0);
        
        create table pessoal.rhrubricasadiantamento 
            (rh262_rubrica_principal varchar(4), 
            rh262_rubrica_adiantamento varchar(4), 
            rh262_instituicao integer,
            PRIMARY KEY (rh262_rubrica_principal,rh262_rubrica_adiantamento, rh262_instituicao),
            CONSTRAINT fk_rhrubricas FOREIGN KEY (rh262_rubrica_principal, rh262_instituicao) REFERENCES pessoal.rhrubricas(rh27_rubric, rh27_instit)
);

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
        delete from db_sysarqcamp where codarq = 1010931;        
        delete from db_syscampo where codcam = 1014155;
        delete from db_syscampo where codcam = 1014156;
        delete from db_syscampo where codcam = 1014157;
        delete from db_sysarqarq where codarqpai = 1177 and codarq = 1010931;
        delete from db_sysarqmod where codmod = 28 and codarq = 1010931;
        delete from db_sysarquivo where codarq = 1010931;        
        drop table pessoal.rhrubricasadiantamento;

SQL
        );
    }
}
