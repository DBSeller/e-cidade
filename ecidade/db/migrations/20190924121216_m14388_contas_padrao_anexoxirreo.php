<?php

use Classes\PostgresMigration;

class M14388ContasPadraoAnexoxirreo extends PostgresMigration
{

    public function up()
    {
        $this->execute(<<<SQL_UP

update db_itensmenu set descricao = 'Anexo VI - Dem. Simplificado do RGF - a partir de 2015' where id_item = 4218;

delete from orcparamseqfiltropadrao where o132_orcparamrel = 201;

INSERT INTO orcparamseqfiltropadrao VALUES (nextval('orcparamelementospadrao_o132_sequencial_seq'), 201, 2, 2019, '<?xml version="1.0" encoding="ISO-8859-1"?><filter><contas><conta estrutural="422100000000000" nivel="" exclusao="false" /><conta estrutural="922100000000000" nivel="" exclusao="false" /></contas><orgao operador="in" valor="" id="orgao"/><unidade operador="in" valor="" id="unidade"/><funcao operador="in" valor="" id="funcao"/><subfuncao operador="in" valor="" id="subfuncao"/><programa operador="in" valor="" id="programa"/><projativ operador="in" valor="" id="projativ"/><recurso operador="in" valor="" id="recurso"/><recursocontalinha numerolinha="" id="recursocontalinha"/><observacao valor=""/><desdobrarlinha valor="false"/></filter>');
INSERT INTO orcparamseqfiltropadrao VALUES (nextval('orcparamelementospadrao_o132_sequencial_seq'), 201, 3, 2019, '<?xml version="1.0" encoding="ISO-8859-1"?><filter><contas><conta estrutural="422200000000000" nivel="" exclusao="false" /><conta estrutural="922200000000000" nivel="" exclusao="false" /></contas><orgao operador="in" valor="" id="orgao"/><unidade operador="in" valor="" id="unidade"/><funcao operador="in" valor="" id="funcao"/><subfuncao operador="in" valor="" id="subfuncao"/><programa operador="in" valor="" id="programa"/><projativ operador="in" valor="" id="projativ"/><recurso operador="in" valor="" id="recurso"/><recursocontalinha numerolinha="" id="recursocontalinha"/><observacao valor=""/><desdobrarlinha valor="false"/></filter>');
INSERT INTO orcparamseqfiltropadrao VALUES (nextval('orcparamelementospadrao_o132_sequencial_seq'), 201, 4, 2019, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="422300000000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="482300000000000" nivel="" exclusao="false" indicador=""/>
 </contas>
 <orgao operador="in" valor="" id="orgao"/>
 <unidade operador="in" valor="" id="unidade"/>
 <funcao operador="in" valor="" id="funcao"/>
 <subfuncao operador="in" valor="" id="subfuncao"/>
 <programa operador="in" valor="" id="programa"/>
 <projativ operador="in" valor="" id="projativ"/>
 <recurso operador="in" valor="" id="recurso"/>
 <recursocontalinha numerolinha="" id="recursocontalinha"/>
 <observacao valor=""/>
 <desdobrarlinha valor="false"/>
</filter>
');

INSERT INTO orcparamseqfiltropadrao VALUES (nextval('orcparamelementospadrao_o132_sequencial_seq'), 201, 5, 2019, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="413210010000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="413210020000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="413210030000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="413210040000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="413210050000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="413210060000000" nivel="" exclusao="false" indicador=""/>
 </contas>
 <orgao operador="in" valor="" id="orgao"/>
 <unidade operador="in" valor="" id="unidade"/>
 <funcao operador="in" valor="" id="funcao"/>
 <subfuncao operador="in" valor="" id="subfuncao"/>
 <programa operador="in" valor="" id="programa"/>
 <projativ operador="in" valor="" id="projativ"/>
 <recurso operador="in" valor="" id="recurso"/>
 <recursocontalinha numerolinha="" id="recursocontalinha"/>
 <observacao valor=""/>
 <desdobrarlinha valor="false"/>
</filter>
');


SQL_UP
);
    }

    public function down()
    {
        $this->execute("update db_itensmenu set descricao = 'Anexo VII - Dem Simplificado do RGF' where id_item = 4218;");
    }

}
