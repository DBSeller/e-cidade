<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M20411AtualizacaoMapeamentoRgfAnexoI extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::connection()->getPdo()->exec(<<<SQL
        alter table orcamento.orcparamseq alter COLUMN o69_descr type varchar(65);
SQL
        );
        DB::connection()->getPdo()->exec(<<<SQL
delete from orcparamseqfiltropadrao where o132_orcparamrel = 260 and o132_anousu = 2022;

insert into orcparamseqfiltropadrao (o132_sequencial, o132_orcparamrel, o132_orcparamseq, o132_anousu, o132_filtro) values (nextval('orcparamelementospadrao_o132_sequencial_seq'), 260, 3, 2022, '<?xml version="1.0" encoding="ISO-8859-1"?><filter><contas><conta estrutural="331900400000000" nivel="7" exclusao="false" /><conta estrutural="331900700000000" nivel="7" exclusao="false" /><conta estrutural="331901100000000" nivel="7" exclusao="false" /><conta estrutural="331901200000000" nivel="7" exclusao="false" /><conta estrutural="331901600000000" nivel="7" exclusao="false" /><conta estrutural="331901700000000" nivel="7" exclusao="false" /><conta estrutural="331906700000000" nivel="7" exclusao="false" /><conta estrutural="331909101000000" nivel="9" exclusao="false" /><conta estrutural="331909102000000" nivel="9" exclusao="false" /><conta estrutural="331909108000000" nivel="9" exclusao="false" /><conta estrutural="331909111000000" nivel="9" exclusao="false" /><conta estrutural="331909114000000" nivel="9" exclusao="false" /><conta estrutural="331909117000000" nivel="9" exclusao="false" /><conta estrutural="331909120000000" nivel="9" exclusao="false" /><conta estrutural="331909125000000" nivel="9" exclusao="false" /><conta estrutural="331909126000000" nivel="9" exclusao="false" /><conta estrutural="331909127000000" nivel="9" exclusao="false" /><conta estrutural="331909197000000" nivel="9" exclusao="false" /><conta estrutural="331909199000000" nivel="9" exclusao="false" /><conta estrutural="331909204000000" nivel="9" exclusao="false" /><conta estrutural="331909207000000" nivel="9" exclusao="false" /><conta estrutural="331909211000000" nivel="9" exclusao="false" /><conta estrutural="331909212000000" nivel="9" exclusao="false" /><conta estrutural="331909216000000" nivel="9" exclusao="false" /><conta estrutural="331909217000000" nivel="9" exclusao="false" /><conta estrutural="331909291000000" nivel="9" exclusao="false" /><conta estrutural="331909294000000" nivel="9" exclusao="false" /><conta estrutural="331909296000000" nivel="9" exclusao="false" /><conta estrutural="331909299000000" nivel="9" exclusao="false" /><conta estrutural="331909401000000" nivel="9" exclusao="false" /><conta estrutural="331909402000000" nivel="9" exclusao="false" /><conta estrutural="331909414000000" nivel="9" exclusao="false" /><conta estrutural="331909415000000" nivel="9" exclusao="false" /><conta estrutural="331909499000000" nivel="9" exclusao="false" /><conta estrutural="331909600000000" nivel="7" exclusao="false" /><conta estrutural="331909900000000" nivel="7" exclusao="false" /></contas><orgao operador="in" valor="" id="orgao"/><unidade operador="in" valor="" id="unidade"/><funcao operador="in" valor="" id="funcao"/><subfuncao operador="in" valor="" id="subfuncao"/><programa operador="in" valor="" id="programa"/><projativ operador="in" valor="" id="projativ"/><recurso operador="in" valor="" id="recurso"/><recursocontalinha numerolinha="" id="recursocontalinha"/><observacao valor=""/><desdobrarlinha valor="false"/></filter>');
insert into orcparamseqfiltropadrao (o132_sequencial, o132_orcparamrel, o132_orcparamseq, o132_anousu, o132_filtro) values (nextval('orcparamelementospadrao_o132_sequencial_seq'), 260, 4, 2022, '<?xml version="1.0" encoding="ISO-8859-1"?><filter><contas><conta estrutural="331901300000000" nivel="7" exclusao="false" /><conta estrutural="331909151000000" nivel="9" exclusao="false" /><conta estrutural="331909152000000" nivel="9" exclusao="false" /><conta estrutural="331909153000000" nivel="9" exclusao="false" /><conta estrutural="331909154000000" nivel="9" exclusao="false" /><conta estrutural="331909213000000" nivel="9" exclusao="false" /><conta estrutural="331909451000000" nivel="9" exclusao="false" /></contas><orgao operador="in" valor="" id="orgao"/><unidade operador="in" valor="" id="unidade"/><funcao operador="in" valor="" id="funcao"/><subfuncao operador="in" valor="" id="subfuncao"/><programa operador="in" valor="" id="programa"/><projativ operador="in" valor="" id="projativ"/><recurso operador="in" valor="" id="recurso"/><recursocontalinha numerolinha="" id="recursocontalinha"/><observacao valor=""/><desdobrarlinha valor="false"/></filter>');
insert into orcparamseqfiltropadrao (o132_sequencial, o132_orcparamrel, o132_orcparamseq, o132_anousu, o132_filtro) values (nextval('orcparamelementospadrao_o132_sequencial_seq'), 260, 6, 2022, '<?xml version="1.0" encoding="ISO-8859-1"?><filter><contas><conta estrutural="331900100000000" nivel="7" exclusao="false" /><conta estrutural="331909109000000" nivel="9" exclusao="false" /><conta estrutural="331909112000000" nivel="9" exclusao="false" /><conta estrutural="331909115000000" nivel="9" exclusao="false" /><conta estrutural="331909118000000" nivel="9" exclusao="false" /><conta estrutural="331909123000000" nivel="9" exclusao="false" /><conta estrutural="331909124000000" nivel="9" exclusao="false" /><conta estrutural="331909128000000" nivel="9" exclusao="false" /><conta estrutural="331909129000000" nivel="9" exclusao="false" /><conta estrutural="331909201000000" nivel="9" exclusao="false" /><conta estrutural="331909203000000" nivel="9" exclusao="false" /><conta estrutural="331909403000000" nivel="9" exclusao="false" /><conta estrutural="331909404000000" nivel="9" exclusao="false" /></contas><orgao operador="in" valor="" id="orgao"/><unidade operador="in" valor="" id="unidade"/><funcao operador="in" valor="" id="funcao"/><subfuncao operador="in" valor="" id="subfuncao"/><programa operador="in" valor="" id="programa"/><projativ operador="in" valor="" id="projativ"/><recurso operador="in" valor="" id="recurso"/><recursocontalinha numerolinha="" id="recursocontalinha"/><observacao valor=""/><desdobrarlinha valor="false"/></filter>');
insert into orcparamseqfiltropadrao (o132_sequencial, o132_orcparamrel, o132_orcparamseq, o132_anousu, o132_filtro) values (nextval('orcparamelementospadrao_o132_sequencial_seq'), 260, 7, 2022, '<?xml version="1.0" encoding="ISO-8859-1"?><filter><contas><conta estrutural="331900300000000" nivel="7" exclusao="false" /><conta estrutural="331909110000000" nivel="9" exclusao="false" /><conta estrutural="331909113000000" nivel="9" exclusao="false" /><conta estrutural="331909116000000" nivel="9" exclusao="false" /><conta estrutural="331909119000000" nivel="9" exclusao="false" /><conta estrutural="331909130000000" nivel="9" exclusao="false" /><conta estrutural="331909131000000" nivel="9" exclusao="false" /><conta estrutural="331909136000000" nivel="9" exclusao="false" /><conta estrutural="331909137000000" nivel="9" exclusao="false" /><conta estrutural="331909259000000" nivel="9" exclusao="false" /><conta estrutural="331909406000000" nivel="9" exclusao="false" /><conta estrutural="331909413000000" nivel="9" exclusao="false" /></contas><orgao operador="in" valor="" id="orgao"/><unidade operador="in" valor="" id="unidade"/><funcao operador="in" valor="" id="funcao"/><subfuncao operador="in" valor="" id="subfuncao"/><programa operador="in" valor="" id="programa"/><projativ operador="in" valor="" id="projativ"/><recurso operador="in" valor="" id="recurso"/><recursocontalinha numerolinha="" id="recursocontalinha"/><observacao valor=""/><desdobrarlinha valor="false"/></filter>');
insert into orcparamseqfiltropadrao (o132_sequencial, o132_orcparamrel, o132_orcparamseq, o132_anousu, o132_filtro) values (nextval('orcparamelementospadrao_o132_sequencial_seq'), 260, 8, 2022, '<?xml version="1.0" encoding="ISO-8859-1"?><filter><contas><conta estrutural="333903400000000" nivel="7" exclusao="false" /></contas><orgao operador="in" valor="" id="orgao"/><unidade operador="in" valor="" id="unidade"/><funcao operador="in" valor="" id="funcao"/><subfuncao operador="in" valor="" id="subfuncao"/><programa operador="in" valor="" id="programa"/><projativ operador="in" valor="" id="projativ"/><recurso operador="in" valor="" id="recurso"/><recursocontalinha numerolinha="" id="recursocontalinha"/><observacao valor=""/><desdobrarlinha valor="false"/></filter>');
insert into orcparamseqfiltropadrao (o132_sequencial, o132_orcparamrel, o132_orcparamseq, o132_anousu, o132_filtro) values (nextval('orcparamelementospadrao_o132_sequencial_seq'), 260, 11, 2022, '<?xml version="1.0" encoding="ISO-8859-1"?><filter><contas><conta estrutural="331909400000000" nivel="7" exclusao="false" /></contas><orgao operador="in" valor="" id="orgao"/><unidade operador="in" valor="" id="unidade"/><funcao operador="in" valor="" id="funcao"/><subfuncao operador="in" valor="" id="subfuncao"/><programa operador="in" valor="" id="programa"/><projativ operador="in" valor="" id="projativ"/><recurso operador="in" valor="" id="recurso"/><recursocontalinha numerolinha="" id="recursocontalinha"/><observacao valor=""/><desdobrarlinha valor="false"/></filter>');
insert into orcparamseqfiltropadrao (o132_sequencial, o132_orcparamrel, o132_orcparamseq, o132_anousu, o132_filtro) values (nextval('orcparamelementospadrao_o132_sequencial_seq'), 260, 12, 2022, '<?xml version="1.0" encoding="ISO-8859-1"?><filter><contas><conta estrutural="331909100000000" nivel="7" exclusao="false" /></contas><orgao operador="in" valor="" id="orgao"/><unidade operador="in" valor="" id="unidade"/><funcao operador="in" valor="" id="funcao"/><subfuncao operador="in" valor="" id="subfuncao"/><programa operador="in" valor="" id="programa"/><projativ operador="in" valor="" id="projativ"/><recurso operador="in" valor="" id="recurso"/><recursocontalinha numerolinha="" id="recursocontalinha"/><observacao valor=""/><desdobrarlinha valor="false"/></filter>');
insert into orcparamseqfiltropadrao (o132_sequencial, o132_orcparamrel, o132_orcparamseq, o132_anousu, o132_filtro) values (nextval('orcparamelementospadrao_o132_sequencial_seq'), 260, 13, 2022, '<?xml version="1.0" encoding="ISO-8859-1"?><filter><contas><conta estrutural="331909200000000" nivel="7" exclusao="false" /></contas><orgao operador="in" valor="" id="orgao"/><unidade operador="in" valor="" id="unidade"/><funcao operador="in" valor="" id="funcao"/><subfuncao operador="in" valor="" id="subfuncao"/><programa operador="in" valor="" id="programa"/><projativ operador="in" valor="" id="projativ"/><recurso operador="in" valor="" id="recurso"/><recursocontalinha numerolinha="" id="recursocontalinha"/><observacao valor=""/><desdobrarlinha valor="false"/></filter>');
insert into orcparamseqfiltropadrao (o132_sequencial, o132_orcparamrel, o132_orcparamseq, o132_anousu, o132_filtro) values (nextval('orcparamelementospadrao_o132_sequencial_seq'), 260, 14, 2022, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="331900100000000" nivel="7" exclusao="false" indicador=""/>
  <conta estrutural="331900300000000" nivel="7" exclusao="false" indicador=""/>
  <conta estrutural="331900500000000" nivel="7" exclusao="false" indicador=""/>
  <conta estrutural="331909100000000" nivel="7" exclusao="false" indicador=""/>
  <conta estrutural="331909200000000" nivel="7" exclusao="false" indicador=""/>
  <conta estrutural="331909400000000" nivel="7" exclusao="false" indicador=""/>
 </contas>
 <orgao operador="in" valor="" id="orgao"/>
 <unidade operador="in" valor="" id="unidade"/>
 <funcao operador="in" valor="" id="funcao"/>
 <subfuncao operador="in" valor="" id="subfuncao"/>
 <programa operador="in" valor="" id="programa"/>
 <projativ operador="in" valor="" id="projativ"/>
 <recurso operador="in" valor="50" id="recurso"/>
 <fonterecurso operador="in" valor="" id="fonterecurso"/>
 <complemento operador="in" valor="" id="complemento"/>
 <recursocontalinha numerolinha="" id="recursocontalinha"/>
 <observacao valor=""/>
 <desdobrarlinha valor="false"/>
</filter>
');
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
delete from orcparamseqfiltropadrao where o132_orcparamrel = 260 and o132_anousu = 2022;
SQL
        );
    }
}
