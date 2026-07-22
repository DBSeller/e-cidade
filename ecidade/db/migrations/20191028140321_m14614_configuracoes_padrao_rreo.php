<?php

use Classes\PostgresMigration;

class M14614ConfiguracoesPadraoRreo extends PostgresMigration
{
    public function up()
    {

        $this->execute(<<<SQL_UP

delete from orcparamseqfiltropadrao where o132_orcparamrel = 192 and o132_orcparamseq in (62, 66, 67);

insert into orcparamseqfiltropadrao
            (o132_sequencial, o132_orcparamrel, o132_orcparamseq, o132_anousu, o132_filtro)
     values (nextval('orcparamelementospadrao_o132_sequencial_seq'), 192, 67, 2019,
             '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
    <contas>
        <conta estrutural="112410100000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="112410200000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="112410300000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="112430100000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="112430200000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="112430300000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="112440100000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="112440200000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="112440300000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="112450100000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="112450200000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="112450300000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="114110100000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="114110200000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="114110300000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="114111500000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="114119900000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="114200000000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="114300000000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="121110301000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="121110302000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="121130300000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="121140301000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="121140302000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="121140303000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="121140304000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="121150301000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="121150302000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="121150303000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="121150304000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="121310100000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="121310200000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="121310300000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="121310400000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="112910300000000" nivel="" exclusao="true" indicador=""/>
        <conta estrutural="112920300000000" nivel="" exclusao="true" indicador=""/>
        <conta estrutural="112930300000000" nivel="" exclusao="true" indicador=""/>
        <conta estrutural="112940300000000" nivel="" exclusao="true" indicador=""/>
        <conta estrutural="112950300000000" nivel="" exclusao="true" indicador=""/>
        <conta estrutural="114910000000000" nivel="" exclusao="true" indicador=""/>
        <conta estrutural="121119903000000" nivel="" exclusao="true" indicador=""/>
        <conta estrutural="121139900000000" nivel="" exclusao="true" indicador=""/>
        <conta estrutural="121149900000000" nivel="" exclusao="true" indicador=""/>
        <conta estrutural="121159900000000" nivel="" exclusao="true" indicador=""/>
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
</filter>');




insert into orcparamseqfiltropadrao
(o132_sequencial, o132_orcparamrel, o132_orcparamseq, o132_anousu, o132_filtro)
values (nextval('orcparamelementospadrao_o132_sequencial_seq'), 192, 66, 2019,
        '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
<contas>
   <conta estrutural="631300000000000" nivel="" exclusao="false" indicador=""/>
   <conta estrutural="632100000000000" nivel="" exclusao="false" indicador=""/>
   <conta estrutural="632700000000000" nivel="" exclusao="false" indicador=""/>
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
</filter>');



insert into orcparamseqfiltropadrao
(o132_sequencial, o132_orcparamrel, o132_orcparamseq, o132_anousu, o132_filtro)
values (nextval('orcparamelementospadrao_o132_sequencial_seq'), 192, 62, 2019,
        '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
    <contas>
        <conta estrutural="212110100000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="212130100000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="212140100000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="212150100000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="212210100000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="218410000000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="218430000000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="218440000000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="218450000000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="222110100000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="222130100000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="222140100000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="222150100000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="222210100000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="228310000000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="228330000000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="228340000000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="228350000000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="212110201000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="212110298000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="212110300000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="212130201000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="212130298000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="212139900000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="212140201000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="212140298000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="212140300000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="212150201000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="212150298000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="212150300000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="212510100000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="212510200000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="212530100000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="212530200000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="212540100000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="212540200000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="212550000000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="222110200000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="222110300000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="222130200000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="222139900000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="222140200000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="222140300000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="222150200000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="222150300000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="222510100000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="222510200000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="222530000000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="222540000000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="222550000000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="212210200000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="212210300000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="212610100000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="212610200000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="222210200000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="222210300000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="222610100000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="222610200000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="212130400000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="212130500000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="222130400000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="222130500000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="212310100000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="212310200000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="212330100000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="212330200000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="212340100000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="212340200000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="212350000000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="212510300000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="212510400000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="212530300000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="212530400000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="212540300000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="212540400000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="213110102000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="213110302000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="213210102000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="213210202000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="222310000000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="222330000000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="222340000000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="222350000000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="222510300000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="222510400000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="223110102000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="223210102000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="212410000000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="212610300000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="212610400000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="222410000000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="222610300000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="222610400000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="214111200000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="214131200000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="214210300000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="214240300000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="214310300000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="214350300000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="224110200000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="224130200000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="224210100000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="224240100000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="224310100000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="224350100000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="211410102000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="211410600000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="211420200000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="211430102000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="211430600000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="211440600000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="211450600000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="221410100000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="221420200000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="221430101000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="211410700000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="211430700000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="221410200000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="221430200000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="221410300000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="213110103000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="213110303000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="213210103000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="213210203000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="223110103000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="223210103000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="218110000000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="218130000000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="218140000000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="218150000000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="228110000000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="228130000000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="228140000000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="228150000000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="211110402000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="211110502000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="211110700000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="211210402000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="211210502000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="211210700000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="211310302000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="211310402000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="213110502000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="213110602000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="213110702000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="213110802000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="213111100000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="221110302000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="221110402000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="221110700000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="221210202000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="221210302000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="221310202000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="221310302000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="223110402000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="223110502000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="223110602000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="223110702000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="223111100000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="853700000000000" nivel="" exclusao="false" indicador=""/>
        <conta estrutural="222910100000000" nivel="" exclusao="true" indicador=""/>
        <conta estrutural="212910100000000" nivel="" exclusao="true" indicador=""/>
        <conta estrutural="212910200000000" nivel="" exclusao="true" indicador=""/>
        <conta estrutural="212810200000000" nivel="" exclusao="true" indicador=""/>
        <conta estrutural="212830200000000" nivel="" exclusao="true" indicador=""/>
        <conta estrutural="212840200000000" nivel="" exclusao="true" indicador=""/>
        <conta estrutural="222810200000000" nivel="" exclusao="true" indicador=""/>
        <conta estrutural="222830000000000" nivel="" exclusao="true" indicador=""/>
        <conta estrutural="222840000000000" nivel="" exclusao="true" indicador=""/>
        <conta estrutural="222850000000000" nivel="" exclusao="true" indicador=""/>
        <conta estrutural="222910200000000" nivel="" exclusao="true" indicador=""/>
        <conta estrutural="212810100000000" nivel="" exclusao="true" indicador=""/>
        <conta estrutural="212830100000000" nivel="" exclusao="true" indicador=""/>
        <conta estrutural="212840100000000" nivel="" exclusao="true" indicador=""/>
        <conta estrutural="222810100000000" nivel="" exclusao="true" indicador=""/>
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
</filter>');


SQL_UP
);
    }

    public function down()
    {

    }
}
