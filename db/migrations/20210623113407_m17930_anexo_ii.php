<?php

use Classes\PostgresMigration;

class M17930AnexoIi extends PostgresMigration
{
    public function up()
    {
        $this->execute(<<<SQL
insert into orcparamrel (o42_codparrel, o42_descrrel, o42_orcparamrelgrupo, o42_notapadrao) values (251, 'ANEXO II - AVALIAÇÃO DO CUMPRIMENTO DAS METAS FISCAIS DO EXERCÍCIO ANTERIOR                         ', 3, 'Fonte: Sistema E-cidade, [nome_departamento] Data da emissão: [data_emissao], Hora de Emissão: [hora_emissao].');
insert into orcparamrelperiodos (o113_sequencial, o113_periodo, o113_orcparamrel) values (nextval('orcparamrelperiodos_o113_sequencial_seq'), 1, 251);
insert into orcparamseq (o69_codparamrel, o69_codseq, o69_descr, o69_grupo, o69_grupoexclusao, o69_nivel, o69_libnivel, o69_librec, o69_libsubfunc, o69_libfunc, o69_verificaano, o69_labelrel, o69_manual, o69_totalizador, o69_ordem, o69_nivellinha, o69_observacao, o69_desdobrarlinha, o69_origem) values (251, 1, 'Receita Total', 1, 0, 0, 'f', 'f', 'f', 'f', 'f', 'Receita Total', 't', 'f', 1, 0, '', 'f', 1);
insert into orcparamseq (o69_codparamrel, o69_codseq, o69_descr, o69_grupo, o69_grupoexclusao, o69_nivel, o69_libnivel, o69_librec, o69_libsubfunc, o69_libfunc, o69_verificaano, o69_labelrel, o69_manual, o69_totalizador, o69_ordem, o69_nivellinha, o69_observacao, o69_desdobrarlinha, o69_origem) values (251, 2, 'Receitas Primárias (I)', 1, 0, 0, 'f', 'f', 'f', 'f', 'f', 'Receitas Primárias (I)', 't', 'f', 2, 0, '', 'f', 1);
insert into orcparamseq (o69_codparamrel, o69_codseq, o69_descr, o69_grupo, o69_grupoexclusao, o69_nivel, o69_libnivel, o69_librec, o69_libsubfunc, o69_libfunc, o69_verificaano, o69_labelrel, o69_manual, o69_totalizador, o69_ordem, o69_nivellinha, o69_observacao, o69_desdobrarlinha, o69_origem) values (251, 3, 'Despesa Total', 1, 0, 0, 'f', 'f', 'f', 'f', 'f', 'Despesa Total', 't', 'f', 3, 0, '', 'f', 2);
insert into orcparamseq (o69_codparamrel, o69_codseq, o69_descr, o69_grupo, o69_grupoexclusao, o69_nivel, o69_libnivel, o69_librec, o69_libsubfunc, o69_libfunc, o69_verificaano, o69_labelrel, o69_manual, o69_totalizador, o69_ordem, o69_nivellinha, o69_observacao, o69_desdobrarlinha, o69_origem) values (251, 4, 'Despesas Primárias (II)', 1, 0, 0, 'f', 'f', 'f', 'f', 'f', 'Despesas Primárias (II)', 't', 'f', 4, 0, '', 'f', 2);
insert into orcparamseq (o69_codparamrel, o69_codseq, o69_descr, o69_grupo, o69_grupoexclusao, o69_nivel, o69_libnivel, o69_librec, o69_libsubfunc, o69_libfunc, o69_verificaano, o69_labelrel, o69_manual, o69_totalizador, o69_ordem, o69_nivellinha, o69_observacao, o69_desdobrarlinha, o69_origem) values (251, 5, 'Resultado Primário (III) = (I - II)', 1, 0, 0, 'f', 'f', 'f', 'f', 'f', 'Resultado Primário (III) = (I - II)', 'f', 'f', 5, 0, '', 'f', 2);
insert into orcparamseq (o69_codparamrel, o69_codseq, o69_descr, o69_grupo, o69_grupoexclusao, o69_nivel, o69_libnivel, o69_librec, o69_libsubfunc, o69_libfunc, o69_verificaano, o69_labelrel, o69_manual, o69_totalizador, o69_ordem, o69_nivellinha, o69_observacao, o69_desdobrarlinha, o69_origem) values (251, 6, 'Resultado Nominal', 1, 1, 0, 'f', 'f', 'f', 'f', 'f', 'Resultado Nominal', 't', 'f', 6, 0, '', 'f', 3);
insert into orcparamseq (o69_codparamrel, o69_codseq, o69_descr, o69_grupo, o69_grupoexclusao, o69_nivel, o69_libnivel, o69_librec, o69_libsubfunc, o69_libfunc, o69_verificaano, o69_labelrel, o69_manual, o69_totalizador, o69_ordem, o69_nivellinha, o69_observacao, o69_desdobrarlinha, o69_origem) values (251, 7, 'Dívida Pública Consolidada', 1, 1, 0, 'f', 'f', 'f', 'f', 'f', 'Dívida Pública Consolidada', 't', 'f', 7, 0, '', 'f', 3);
insert into orcparamseq (o69_codparamrel, o69_codseq, o69_descr, o69_grupo, o69_grupoexclusao, o69_nivel, o69_libnivel, o69_librec, o69_libsubfunc, o69_libfunc, o69_verificaano, o69_labelrel, o69_manual, o69_totalizador, o69_ordem, o69_nivellinha, o69_observacao, o69_desdobrarlinha, o69_origem) values (251, 8, 'Dívida Consolidada Líquida', 1, 1, 0, 'f', 'f', 'f', 'f', 'f', 'Dívida Consolidada Líquida', 't', 'f', 8, 0, '', 'f', 3);
insert into orcparamseqfiltropadrao (o132_sequencial, o132_orcparamrel, o132_orcparamseq, o132_anousu, o132_filtro) values (nextval('orcparamelementospadrao_o132_sequencial_seq'), 251, 1, 2021, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="400000000000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="900000000000000" nivel="" exclusao="false" indicador=""/>
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
insert into orcparamseqfiltropadrao (o132_sequencial, o132_orcparamrel, o132_orcparamseq, o132_anousu, o132_filtro) values (nextval('orcparamelementospadrao_o132_sequencial_seq'), 251, 2, 2021, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="411000000000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="911000000000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="412000000000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="912000000000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="417000000000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="917000000000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="413000000000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="913000000000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="414000000000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="914000000000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="415000000000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="915000000000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="416000000000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="916000000000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="416400110000000" nivel="" exclusao="true" indicador=""/>
  <conta estrutural="416400310000000" nivel="" exclusao="true" indicador=""/>
  <conta estrutural="419220120000000" nivel="" exclusao="true" indicador=""/>
  <conta estrutural="419901110000000" nivel="" exclusao="true" indicador=""/>
  <conta estrutural="419909920000000" nivel="" exclusao="true" indicador=""/>
  <conta estrutural="916400110000000" nivel="" exclusao="true" indicador=""/>
  <conta estrutural="916400310000000" nivel="" exclusao="true" indicador=""/>
  <conta estrutural="919220120000000" nivel="" exclusao="true" indicador=""/>
  <conta estrutural="919901110000000" nivel="" exclusao="true" indicador=""/>
  <conta estrutural="919909920000000" nivel="" exclusao="true" indicador=""/>
  <conta estrutural="413210020000000" nivel="" exclusao="true" indicador=""/>
  <conta estrutural="413210030000000" nivel="" exclusao="true" indicador=""/>
  <conta estrutural="413210040000000" nivel="" exclusao="true" indicador=""/>
  <conta estrutural="413210050000000" nivel="" exclusao="true" indicador=""/>
  <conta estrutural="413290010000000" nivel="" exclusao="true" indicador=""/>
  <conta estrutural="913210020000000" nivel="" exclusao="true" indicador=""/>
  <conta estrutural="913210030000000" nivel="" exclusao="true" indicador=""/>
  <conta estrutural="913210040000000" nivel="" exclusao="true" indicador=""/>
  <conta estrutural="913210050000000" nivel="" exclusao="true" indicador=""/>
  <conta estrutural="913290010000000" nivel="" exclusao="true" indicador=""/>
  <conta estrutural="422000000000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="922000000000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="422180110000000" nivel="" exclusao="true" indicador=""/>
  <conta estrutural="422180120000000" nivel="" exclusao="true" indicador=""/>
  <conta estrutural="922180110000000" nivel="" exclusao="true" indicador=""/>
  <conta estrutural="922180120000000" nivel="" exclusao="true" indicador=""/>
  <conta estrutural="424000000000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="924000000000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="429100000000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="429900000000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="929100000000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="929900000000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="419000000000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="919000000000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="413210010000000" nivel="" exclusao="true" indicador=""/>
  <conta estrutural="913210010000000" nivel="" exclusao="true" indicador=""/>
 </contas>
 <orgao operador="in" valor="" id="orgao"/>
 <unidade operador="in" valor="" id="unidade"/>
 <funcao operador="in" valor="" id="funcao"/>
 <subfuncao operador="in" valor="" id="subfuncao"/>
 <programa operador="in" valor="" id="programa"/>
 <projativ operador="in" valor="" id="projativ"/>
 <recurso operador="in" valor="" id="recurso"/>
 <recursocontalinha numerolinha="" id="recursocontalinha"/>
 <observacao valor=" "/>
 <desdobrarlinha valor="false"/>
</filter>
');
insert into orcparamseqfiltropadrao (o132_sequencial, o132_orcparamrel, o132_orcparamseq, o132_anousu, o132_filtro) values (nextval('orcparamelementospadrao_o132_sequencial_seq'), 251, 3, 2021, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="300000000000000" nivel="1" exclusao="false" indicador=""/>
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
insert into orcparamseqfiltropadrao (o132_sequencial, o132_orcparamrel, o132_orcparamseq, o132_anousu, o132_filtro) values (nextval('orcparamelementospadrao_o132_sequencial_seq'), 251, 4, 2021, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="331000000000000" nivel="3" exclusao="false" indicador=""/>
  <conta estrutural="331910000000000" nivel="5" exclusao="true" indicador=""/>
  <conta estrutural="333000000000000" nivel="3" exclusao="false" indicador=""/>
  <conta estrutural="333910000000000" nivel="5" exclusao="true" indicador=""/>
  <conta estrutural="344000000000000" nivel="3" exclusao="false" indicador=""/>
  <conta estrutural="344910000000000" nivel="5" exclusao="true" indicador=""/>
  <conta estrutural="345000000000000" nivel="3" exclusao="false" indicador=""/>
  <conta estrutural="345906600000000" nivel="7" exclusao="true" indicador=""/>
  <conta estrutural="345906300000000" nivel="7" exclusao="true" indicador=""/>
  <conta estrutural="345906400000000" nivel="7" exclusao="true" indicador=""/>
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
insert into orcparamseqcoluna (o115_sequencial, o115_anousu, o115_descricao, o115_tipo, o115_valoresdefault, o115_nomecoluna, o115_formula, o115_origem, o115_relatorio) values (nextval('orcparamseqcoluna_o115_sequencial_seq'), 2021, '% PIB Ano - 2', 1, '', 'realizado_pib_ano_menos_dois', '', 0, 251);
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 1, 251, currval('orcparamseqcoluna_o115_sequencial_seq'), 5, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 2, 251, currval('orcparamseqcoluna_o115_sequencial_seq'), 5, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 3, 251, currval('orcparamseqcoluna_o115_sequencial_seq'), 5, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 4, 251, currval('orcparamseqcoluna_o115_sequencial_seq'), 5, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 5, 251, currval('orcparamseqcoluna_o115_sequencial_seq'), 5, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 6, 251, currval('orcparamseqcoluna_o115_sequencial_seq'), 5, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 7, 251, currval('orcparamseqcoluna_o115_sequencial_seq'), 5, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 8, 251, currval('orcparamseqcoluna_o115_sequencial_seq'), 5, 1, '');
insert into orcparamseqcoluna (o115_sequencial, o115_anousu, o115_descricao, o115_tipo, o115_valoresdefault, o115_nomecoluna, o115_formula, o115_origem, o115_relatorio) values (nextval('orcparamseqcoluna_o115_sequencial_seq'), 2021, '% RCL Ano - 2', 1, '', 'previsto_rcl_ano_menos_dois', '', 0, 251);
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 1, 251, currval('orcparamseqcoluna_o115_sequencial_seq'), 3, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 2, 251, currval('orcparamseqcoluna_o115_sequencial_seq'), 3, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 3, 251, currval('orcparamseqcoluna_o115_sequencial_seq'), 3, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 4, 251, currval('orcparamseqcoluna_o115_sequencial_seq'), 3, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 5, 251, currval('orcparamseqcoluna_o115_sequencial_seq'), 3, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 6, 251, currval('orcparamseqcoluna_o115_sequencial_seq'), 3, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 7, 251, currval('orcparamseqcoluna_o115_sequencial_seq'), 3, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 8, 251, currval('orcparamseqcoluna_o115_sequencial_seq'), 3, 1, '');
insert into orcparamseqcoluna (o115_sequencial, o115_anousu, o115_descricao, o115_tipo, o115_valoresdefault, o115_nomecoluna, o115_formula, o115_origem, o115_relatorio) values (nextval('orcparamseqcoluna_o115_sequencial_seq'), 2021, '% RCL Ano - 2', 1, '', 'realizado_rcl_ano_menos_dois', '', 0, 251);
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 1, 251, currval('orcparamseqcoluna_o115_sequencial_seq'), 6, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 2, 251, currval('orcparamseqcoluna_o115_sequencial_seq'), 6, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 3, 251, currval('orcparamseqcoluna_o115_sequencial_seq'), 6, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 4, 251, currval('orcparamseqcoluna_o115_sequencial_seq'), 6, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 5, 251, currval('orcparamseqcoluna_o115_sequencial_seq'), 6, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 6, 251, currval('orcparamseqcoluna_o115_sequencial_seq'), 6, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 7, 251, currval('orcparamseqcoluna_o115_sequencial_seq'), 6, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 8, 251, currval('orcparamseqcoluna_o115_sequencial_seq'), 6, 1, '');
insert into orcparamseqcoluna (o115_sequencial, o115_anousu, o115_descricao, o115_tipo, o115_valoresdefault, o115_nomecoluna, o115_formula, o115_origem, o115_relatorio) values (nextval('orcparamseqcoluna_o115_sequencial_seq'), 2021, 'Meta Prevista Ano - 2', 1, '', 'vlr_previsto_ano_menos_dois', '', 0, 251);
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 1, 251, currval('orcparamseqcoluna_o115_sequencial_seq'), 1, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 2, 251, currval('orcparamseqcoluna_o115_sequencial_seq'), 1, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 3, 251, currval('orcparamseqcoluna_o115_sequencial_seq'), 1, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 4, 251, currval('orcparamseqcoluna_o115_sequencial_seq'), 1, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 5, 251, currval('orcparamseqcoluna_o115_sequencial_seq'), 1, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 6, 251, currval('orcparamseqcoluna_o115_sequencial_seq'), 1, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 7, 251, currval('orcparamseqcoluna_o115_sequencial_seq'), 1, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 8, 251, currval('orcparamseqcoluna_o115_sequencial_seq'), 1, 1, '');
insert into orcparamseqcoluna (o115_sequencial, o115_anousu, o115_descricao, o115_tipo, o115_valoresdefault, o115_nomecoluna, o115_formula, o115_origem, o115_relatorio) values (nextval('orcparamseqcoluna_o115_sequencial_seq'), 2021, 'Meta Realizada Ano - 2', 1, '', 'vlr_realizado_ano_menos_dois', '', 0, 251);
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 1, 251, currval('orcparamseqcoluna_o115_sequencial_seq'), 4, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 2, 251, currval('orcparamseqcoluna_o115_sequencial_seq'), 4, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 3, 251, currval('orcparamseqcoluna_o115_sequencial_seq'), 4, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 4, 251, currval('orcparamseqcoluna_o115_sequencial_seq'), 4, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 5, 251, currval('orcparamseqcoluna_o115_sequencial_seq'), 4, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 6, 251, currval('orcparamseqcoluna_o115_sequencial_seq'), 4, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 7, 251, currval('orcparamseqcoluna_o115_sequencial_seq'), 4, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 8, 251, currval('orcparamseqcoluna_o115_sequencial_seq'), 4, 1, '');
insert into orcparamseqcoluna (o115_sequencial, o115_anousu, o115_descricao, o115_tipo, o115_valoresdefault, o115_nomecoluna, o115_formula, o115_origem, o115_relatorio) values (nextval('orcparamseqcoluna_o115_sequencial_seq'), 2021, '% PIB Ano - 2', 1, '', 'previsto_pib_ano_menos_dois', '', 0, 251);
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 1, 251, currval('orcparamseqcoluna_o115_sequencial_seq'), 2, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 2, 251, currval('orcparamseqcoluna_o115_sequencial_seq'), 2, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 3, 251, currval('orcparamseqcoluna_o115_sequencial_seq'), 2, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 4, 251, currval('orcparamseqcoluna_o115_sequencial_seq'), 2, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 5, 251, currval('orcparamseqcoluna_o115_sequencial_seq'), 2, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 6, 251, currval('orcparamseqcoluna_o115_sequencial_seq'), 2, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 7, 251, currval('orcparamseqcoluna_o115_sequencial_seq'), 2, 1, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 8, 251, currval('orcparamseqcoluna_o115_sequencial_seq'), 2, 1, '');
SQL
        );
    }

    public function down()
    {
        $this->execute(<<<SQL
delete from orcparamseqfiltroorcamento where o133_orcparamrel = 251;
delete from orcparamrelperiodos where o113_orcparamrel = 251;
delete from orcparamseqfiltropadrao where o132_orcparamrel = 251;
delete from orcparamseqorcparamseqcolunavalor where o117_orcparamseqorcparamseqcoluna in (
    select o116_sequencial from orcparamseqorcparamseqcoluna where o116_codparamrel = 251
);
delete from orcparamseqorcparamseqcoluna where o116_codparamrel = 251;
delete from orcparamseqorcparamseqcoluna where o116_codparamrel = 251;
delete from orcparamseq where o69_codparamrel = 251;
delete from orcparamrel where o42_codparrel = 251;
SQL
        );
    }
}
