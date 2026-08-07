<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M21368AdicionadoCamposTabelaCertidao extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::connection()->getPdo()->exec(<<<SQL

        alter table certidao 
          add column p50_nomeservico varchar(50) default null;

        alter table certidao 
          add column p50_resultadowebservice varchar(20) default null;

        alter table certidao 
          add column p50_datahoraconsulta timestamp default null;

        insert into db_syscampo(codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel) 
        values(1014382,'p50_nomeservico','varchar(50)','Nome Serviço que consulta certidão negativa de débito em sistema externo','','Nome Serviço',50,'t','t','f',0,'text','Nome Serviço');
        
        insert into db_syscampo(codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel)
        values(1014383,'p50_resultadowebservice','varchar(20)','Resultado da consulta do webservice','','Retorno do Webservice',20,'t','t','f',0,'text','Retorno do Webservice');
        
        insert into db_syscampo(codcam, nomecam, conteudo, descricao, valorinicial, rotulo, tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel)
        values(1014384,'p50_datahoraconsulta','date','Data e hora em que foi realizado a consulta no sistema externo','null','Data Hora Consulta',20,'t','f','f',1,'text','Data Hora Consulta');

        insert into db_sysarqcamp(codarq, codcam, seqarq, codsequencia)
        values(1475,1014382,15,0);
        insert into db_sysarqcamp(codarq, codcam, seqarq, codsequencia)
        values(1475,1014383,16,0);
        insert into db_sysarqcamp(codarq, codcam, seqarq, codsequencia)
        values(1475,1014384,17,0);
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
        
        alter table certidao 
          drop column p50_nomeservico;

        alter table certidao 
         drop column p50_resultadowebservice;

        alter table certidao 
         drop column p50_datahoraconsulta;
       
         delete 
           from db_sysarqcamp 
          where codcam in (1014382, 1014383, 1014384);

        delete 
          from db_syscampo 
         where codcam in (1014382, 1014383, 1014384);
SQL
);         
    }
}
