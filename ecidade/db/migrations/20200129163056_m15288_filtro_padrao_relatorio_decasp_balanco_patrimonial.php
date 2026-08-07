<?php

use Classes\PostgresMigration;

class M15288FiltroPadraoRelatorioDecaspBalancoPatrimonial extends PostgresMigration
{

    public function up()
    {
        $this->execute(<<<SQL_UP


delete from orcparamseqfiltropadrao where o132_orcparamrel = 205 and o132_orcparamseq between 53 and 62;

INSERT INTO orcparamseqfiltropadrao  VALUES (nextval('orcparamelementospadrao_o132_sequencial_seq'), 205, 54, 2020, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="210000000000000" nivel="2" exclusao="false" indicador="F"/>
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
INSERT INTO orcparamseqfiltropadrao  VALUES (nextval('orcparamelementospadrao_o132_sequencial_seq'), 205, 55, 2020, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="220000000000000" nivel="2" exclusao="false" indicador="F"/>
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
INSERT INTO orcparamseqfiltropadrao  VALUES (nextval('orcparamelementospadrao_o132_sequencial_seq'), 205, 56, 2020, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="622130100000000" nivel="" exclusao="false" indicador=""/>
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
INSERT INTO orcparamseqfiltropadrao  VALUES (nextval('orcparamelementospadrao_o132_sequencial_seq'), 205, 57, 2020, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="622130500000000" nivel="" exclusao="false" indicador=""/>
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
INSERT INTO orcparamseqfiltropadrao  VALUES (nextval('orcparamelementospadrao_o132_sequencial_seq'), 205, 58, 2020, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="631100000000000" nivel="" exclusao="false" indicador=""/>
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
INSERT INTO orcparamseqfiltropadrao  VALUES (nextval('orcparamelementospadrao_o132_sequencial_seq'), 205, 59, 2020, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="631710000000000" nivel="" exclusao="false" indicador=""/>
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


INSERT INTO orcparamseqfiltropadrao  VALUES (66023, 205, 61, 2020, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="210000000000000" nivel="2" exclusao="false" indicador="P"/>
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
INSERT INTO orcparamseqfiltropadrao VALUES (66024, 205, 62, 2020, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="220000000000000" nivel="2" exclusao="false" indicador="P"/>
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


insert into orcparamseqfiltropadrao
select nextval('orcparamelementospadrao_o132_sequencial_seq'),
       o132_orcparamrel,
       o132_orcparamseq,
       2019,
       o132_filtro
from orcparamseqfiltropadrao
where o132_orcparamrel = 205
  and o132_orcparamseq between 53 and 62
  and o132_anousu = 2020;





SQL_UP
);
    }


    public function down()
    {
    }

}
