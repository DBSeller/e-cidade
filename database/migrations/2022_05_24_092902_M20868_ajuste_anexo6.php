<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M20868AjusteAnexo6 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::connection()->getPdo()->exec(<<<SQL
update orcparamseq set o69_origem = 3 where o69_codparamrel = 264 and o69_ordem = 66;

UPDATE orcparamseqfiltropadrao SET o132_filtro = '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter> <contas> <conta estrutural="622130700000000" nivel="" exclusao="false" indicador=""/> <conta estrutural="631300000000000" nivel="" exclusao="false" indicador=""/> <conta estrutural="632100000000000" nivel="" exclusao="false" indicador=""/> <conta estrutural="632700000000000" nivel="" exclusao="false" indicador=""/> </contas> <orgao operador="in" valor="" id="orgao"/> <unidade operador="in" valor="" id="unidade"/> <funcao operador="in" valor="" id="funcao"/> <subfuncao operador="in" valor="" id="subfuncao"/> <programa operador="in" valor="" id="programa"/> <projativ operador="in" valor="" id="projativ"/> <recurso operador="in" valor="" id="recurso"/> <fonterecurso operador="in" valor="" id="fonterecurso"/> <complemento operador="in" valor="" id="complemento"/> <recursocontalinha numerolinha="" id="recursocontalinha"/> <observacao valor=""/> <desdobrarlinha valor="false"/>
</filter>
' where o132_orcparamrel = 264 and o132_orcparamseq = 66;
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
update orcparamseq set o69_origem = 4 where o69_codparamrel = 264 and o69_ordem = 66;
SQL
        );
    }
}
