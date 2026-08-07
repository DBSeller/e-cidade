<?php

use Classes\PostgresMigration;

class M19053AjusteConfiguracaoPadraoRelatorio216 extends PostgresMigration
{
    public function up()
    {
        $sql = <<<SQL

update orcparamseqfiltropadrao set o132_filtro = '
<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
  <contas>
    <conta estrutural="424000000000000" nivel="" exclusao="false" />
    <conta estrutural="424181000000000" nivel="" exclusao="true"  />
    <conta estrutural="424281000000000" nivel="" exclusao="true"  />
    <conta estrutural="424381000000000" nivel="" exclusao="true"  />
    <conta estrutural="424481000000000" nivel="" exclusao="true"  />
    <conta estrutural="924000000000000" nivel="" exclusao="false" />
    <conta estrutural="924181000000000" nivel="" exclusao="true"  />
    <conta estrutural="924281000000000" nivel="" exclusao="true"  />
    <conta estrutural="924381000000000" nivel="" exclusao="true"  />
    <conta estrutural="924481000000000" nivel="" exclusao="true"  />
  </contas>
  <orgao operador="in" valor="" id="orgao"/>
  <unidade operador="in" valor="" id="unidade"/>
  <funcao operador="in" valor="" id="funcao"/>
  <subfuncao operador="in" valor="" id="subfuncao"/>
  <programa operador="in" valor="" id="programa"/>
  <projativ operador="in" valor="" id="projativ"/>
  <recurso operador="in" valor="" id="recurso"/>
  <recursocontalinha numerolinha="" id="recursocontalinha"/>
  <observacao valor=""/><desdobrarlinha valor="false"/>
</filter>
'
where o132_orcparamrel = 216
  and o132_anousu = 2021
  and o132_orcparamseq = 34;

SQL;
        $this->execute($sql);
    }

    public function down()
    {

        $sql = <<<SQL

update orcparamseqfiltropadrao set o132_filtro = '
  <?xml version="1.0" encoding="ISO-8859-1"?>
  <filter>
    <contas>
      <conta estrutural="924000000000000" nivel="" exclusao="false" />
      <conta estrutural="424181000000000" nivel="" exclusao="true"  />
      <conta estrutural="424281000000000" nivel="" exclusao="true"  />
      <conta estrutural="424381000000000" nivel="" exclusao="true"  />
      <conta estrutural="424481000000000" nivel="" exclusao="true"  />
      <conta estrutural="924000000000000" nivel="" exclusao="false" />
      <conta estrutural="924181000000000" nivel="" exclusao="true"  />
      <conta estrutural="924281000000000" nivel="" exclusao="true"  />
      <conta estrutural="924381000000000" nivel="" exclusao="true"  />
      <conta estrutural="924481000000000" nivel="" exclusao="true"  />
    </contas>
    <orgao operador="in" valor="" id="orgao"/>
    <unidade operador="in" valor="" id="unidade"/>
    <funcao operador="in" valor="" id="funcao"/>
    <subfuncao operador="in" valor="" id="subfuncao"/>
    <programa operador="in" valor="" id="programa"/>
    <projativ operador="in" valor="" id="projativ"/>
    <recurso operador="in" valor="" id="recurso"/>
    <recursocontalinha numerolinha="" id="recursocontalinha"/>
    <observacao valor=""/><desdobrarlinha valor="false"/>
  </filter>
'
where o132_orcparamrel = 216
  and o132_anousu = 2021
  and o132_orcparamseq = 34;

SQL;
        $this->execute($sql);
    }
}
