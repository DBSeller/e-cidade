
update orcparamseqorcparamseqcoluna
  set   o116_formula = '(in_array(substr(#estrutural, 0, 1), array(1, 3)) && #sinal_final == \'D\') || (in_array(substr(#estrutural, 0, 1), array(2, 4)) && #sinal_final == \'D\') ? #saldo_final *= -1 : #saldo_final'
  where o116_codparamrel = 151 and o116_codseq = 45;

update orcparamseqfiltropadrao
   set o132_filtro = '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
  <contas>
    <conta estrutural="237000000000000" exclusao="false" nivel=""/>
    <conta estrutural="237120000000000" exclusao="true" nivel=""/>
    <conta estrutural="237220000000000" exclusao="true" nivel=""/>
    <conta estrutural="400000000000000" exclusao="false" nivel="" indicador=""/>
    <conta estrutural="300000000000000" exclusao="false" nivel="" indicador=""/>
  </contas>
  <orgao id="orgao" operador="in" valor=""/>
  <unidade id="unidade" operador="in" valor=""/>
  <funcao id="funcao" operador="in" valor=""/>
  <subfuncao id="subfuncao" operador="in" valor=""/>
  <programa id="programa" operador="in" valor=""/>
  <projativ id="projativ" operador="in" valor=""/>
  <recurso id="recurso" operador="in" valor=""/>
  <recursocontalinha id="recursocontalinha" numerolinha=""/>
  <observacao valor=""/>
  <desdobrarlinha valor="false"/>
</filter>'
  where o132_orcparamrel = 151 and o132_orcparamseq = 45;
