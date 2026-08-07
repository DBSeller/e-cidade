<?php

use Classes\PostgresMigration;

class M14443AnexoIxRreo2019 extends PostgresMigration
{
    public function up()
    {

        $this->execute(<<<SQL_UP

insert into orcparamrel (o42_codparrel, o42_descrrel, o42_orcparamrelgrupo, o42_notapadrao) values (202, 'ANEXO 9 RREO EDIÇÃO 2019', 2, 'FONTE: Sistema E-Cidade, Unidade Responsável: [nome_departamento]. Emissão: [data_emissao], às [hora_emissao]. Assinado Digitalmente no dia [data_emissao], às [hora_emissao].

1  Operações de Crédito descritas na CF, art. 167, inciso III');
insert into orcparamrelperiodos (o113_sequencial, o113_periodo, o113_orcparamrel) values (nextval('orcparamrelperiodos_o113_sequencial_seq'), 11, 202);
insert into orcparamseq (o69_codparamrel, o69_codseq, o69_descr, o69_grupo, o69_grupoexclusao, o69_nivel, o69_libnivel, o69_librec, o69_libsubfunc, o69_libfunc, o69_verificaano, o69_labelrel, o69_manual, o69_totalizador, o69_ordem, o69_nivellinha, o69_observacao, o69_desdobrarlinha, o69_origem) values (202, 1, 'RECEITAS DE OPERAÇÕES DE CRÉDITO¹ (I)', 1, 0, 1, 'f', 'f', 'f', 'f', 'f', 'RECEITAS DE OPERAÇÕES DE CRÉDITO¹ (I)', 't', 'f', 1, 1, '', 'f', 1);
insert into orcparamseq (o69_codparamrel, o69_codseq, o69_descr, o69_grupo, o69_grupoexclusao, o69_nivel, o69_libnivel, o69_librec, o69_libsubfunc, o69_libfunc, o69_verificaano, o69_labelrel, o69_manual, o69_totalizador, o69_ordem, o69_nivellinha, o69_observacao, o69_desdobrarlinha, o69_origem) values (202, 2, 'DESPESAS DE CAPITAL', 1, 0, 1, 'f', 'f', 'f', 'f', 'f', 'DESPESAS DE CAPITAL', 't', 't', 2, 1, '', 'f', 2);
insert into orcparamseq (o69_codparamrel, o69_codseq, o69_descr, o69_grupo, o69_grupoexclusao, o69_nivel, o69_libnivel, o69_librec, o69_libsubfunc, o69_libfunc, o69_verificaano, o69_labelrel, o69_manual, o69_totalizador, o69_ordem, o69_nivellinha, o69_observacao, o69_desdobrarlinha, o69_origem) values (202, 3, 'Investimentos', 1, 0, 1, 'f', 'f', 'f', 'f', 'f', 'Investimentos', 't', 'f', 3, 2, '', 'f', 2);
insert into orcparamseqfiltropadrao (o132_sequencial, o132_orcparamrel, o132_orcparamseq, o132_anousu, o132_filtro) values (nextval('orcparamelementospadrao_o132_sequencial_seq'), 202, 3, 2019, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="344000000000000" nivel="3" exclusao="false" indicador=""/>
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
insert into orcparamseq (o69_codparamrel, o69_codseq, o69_descr, o69_grupo, o69_grupoexclusao, o69_nivel, o69_libnivel, o69_librec, o69_libsubfunc, o69_libfunc, o69_verificaano, o69_labelrel, o69_manual, o69_totalizador, o69_ordem, o69_nivellinha, o69_observacao, o69_desdobrarlinha, o69_origem) values (202, 4, 'Inversões Financeiras', 1, 0, 1, 'f', 'f', 'f', 'f', 'f', 'Inversões Financeiras', 't', 'f', 4, 2, '', 'f', 2);
insert into orcparamseqfiltropadrao (o132_sequencial, o132_orcparamrel, o132_orcparamseq, o132_anousu, o132_filtro) values (nextval('orcparamelementospadrao_o132_sequencial_seq'), 202, 4, 2019, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="3420200000000" nivel="3" exclusao="false" indicador=""/>
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
insert into orcparamseq (o69_codparamrel, o69_codseq, o69_descr, o69_grupo, o69_grupoexclusao, o69_nivel, o69_libnivel, o69_librec, o69_libsubfunc, o69_libfunc, o69_verificaano, o69_labelrel, o69_manual, o69_totalizador, o69_ordem, o69_nivellinha, o69_observacao, o69_desdobrarlinha, o69_origem) values (202, 5, 'Amortização da Dívida', 1, 0, 1, 'f', 'f', 'f', 'f', 'f', 'Amortização da Dívida', 't', 'f', 5, 2, '', 'f', 2);
insert into orcparamseqfiltropadrao (o132_sequencial, o132_orcparamrel, o132_orcparamseq, o132_anousu, o132_filtro) values (nextval('orcparamelementospadrao_o132_sequencial_seq'), 202, 5, 2019, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="346000000000000" nivel="3" exclusao="false" indicador=""/>
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
insert into orcparamseq (o69_codparamrel, o69_codseq, o69_descr, o69_grupo, o69_grupoexclusao, o69_nivel, o69_libnivel, o69_librec, o69_libsubfunc, o69_libfunc, o69_verificaano, o69_labelrel, o69_manual, o69_totalizador, o69_ordem, o69_nivellinha, o69_observacao, o69_desdobrarlinha, o69_origem) values (202, 6, '(-) Incentivos Fiscais a Contribuinte', 1, 0, 1, 'f', 'f', 'f', 'f', 'f', '(-) Incentivos Fiscais a Contribuinte', 't', 'f', 6, 2, '', 'f', 2);
insert into orcparamseq (o69_codparamrel, o69_codseq, o69_descr, o69_grupo, o69_grupoexclusao, o69_nivel, o69_libnivel, o69_librec, o69_libsubfunc, o69_libfunc, o69_verificaano, o69_labelrel, o69_manual, o69_totalizador, o69_ordem, o69_nivellinha, o69_observacao, o69_desdobrarlinha, o69_origem) values (202, 7, '(-) Incentivos Fiscais a Contribuinte por Instituições Finan', 1, 0, 1, 'f', 'f', 'f', 'f', 'f', '(-) Incentivos Fiscais a Contribuinte por Instituições Financeiras', 't', 'f', 7, 2, '', 'f', 2);
insert into orcparamseq (o69_codparamrel, o69_codseq, o69_descr, o69_grupo, o69_grupoexclusao, o69_nivel, o69_libnivel, o69_librec, o69_libsubfunc, o69_libfunc, o69_verificaano, o69_labelrel, o69_manual, o69_totalizador, o69_ordem, o69_nivellinha, o69_observacao, o69_desdobrarlinha, o69_origem) values (202, 8, 'DESPESA DE CAPITAL LÍQUIDA (II)', 1, 1, 1, 'f', 'f', 'f', 'f', 'f', 'DESPESA DE CAPITAL LÍQUIDA (II)', 'f', 't', 8, 1, '', 'f', 0);
insert into orcparamseq (o69_codparamrel, o69_codseq, o69_descr, o69_grupo, o69_grupoexclusao, o69_nivel, o69_libnivel, o69_librec, o69_libsubfunc, o69_libfunc, o69_verificaano, o69_labelrel, o69_manual, o69_totalizador, o69_ordem, o69_nivellinha, o69_observacao, o69_desdobrarlinha, o69_origem) values (202, 9, 'RESULTADO PARA APURAÇÃO DA REGRA DE OURO (III) = (I - II)', 1, 1, 1, 'f', 'f', 'f', 'f', 'f', 'RESULTADO PARA APURAÇÃO DA REGRA DE OURO (III) = (I - II)', 'f', 't', 9, 1, '', 'f', 0);
insert into orcparamseqcoluna (o115_sequencial, o115_anousu, o115_descricao, o115_tipo, o115_valoresdefault, o115_nomecoluna, o115_formula, o115_origem, o115_relatorio) values (nextval('orcparamseqcoluna_o115_sequencial_seq'), 2013, 'Dotação Atualizada', 1, '', 'dot_atual', '', 0, 202);
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 3, 202, currval('orcparamseqcoluna_o115_sequencial_seq'), 1, 11, '#dot_ini+(#suplementado_acumulado-#reduzido_acumulado)');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 4, 202, currval('orcparamseqcoluna_o115_sequencial_seq'), 1, 11, '#dot_ini+(#suplementado_acumulado-#reduzido_acumulado)');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 5, 202, currval('orcparamseqcoluna_o115_sequencial_seq'), 1, 11, '#dot_ini+(#suplementado_acumulado-#reduzido_acumulado)');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 6, 202, currval('orcparamseqcoluna_o115_sequencial_seq'), 1, 11, '#dot_ini+(#suplementado_acumulado-#reduzido_acumulado)');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 7, 202, currval('orcparamseqcoluna_o115_sequencial_seq'), 1, 11, '#dot_ini+(#suplementado_acumulado-#reduzido_acumulado)');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 8, 202, currval('orcparamseqcoluna_o115_sequencial_seq'), 1, 11, 'F[2] - L[6]->dot_atual - L[7]->dot_atual');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 2, 202, currval('orcparamseqcoluna_o115_sequencial_seq'), 1, 11, 'L[3]->dot_atual+L[4]->dot_atual+L[5]->dot_atual');
insert into orcparamseqcoluna (o115_sequencial, o115_anousu, o115_descricao, o115_tipo, o115_valoresdefault, o115_nomecoluna, o115_formula, o115_origem, o115_relatorio) values (nextval('orcparamseqcoluna_o115_sequencial_seq'), 2013, 'DESPESAS EMPENHADAS (f)', 1, '', 'despemp', '', 0, 202);
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 9, 202, currval('orcparamseqcoluna_o115_sequencial_seq'), 2, 11, 'L[8]->empenhado-L[1]->recrealiza');
insert into orcparamseqcoluna (o115_sequencial, o115_anousu, o115_descricao, o115_tipo, o115_valoresdefault, o115_nomecoluna, o115_formula, o115_origem, o115_relatorio) values (nextval('orcparamseqcoluna_o115_sequencial_seq'), 2013, 'RECEITAS REALIZADAS (b)', 1, '', 'recrealiza', '', 0, 202);
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 1, 202, currval('orcparamseqcoluna_o115_sequencial_seq'), 2, 11, '#saldo_arrecadado_acumulado');
insert into orcparamseqcoluna (o115_sequencial, o115_anousu, o115_descricao, o115_tipo, o115_valoresdefault, o115_nomecoluna, o115_formula, o115_origem, o115_relatorio) values (nextval('orcparamseqcoluna_o115_sequencial_seq'), 2013, 'DOTAÇÃO ATUALIZADA (e)', 1, '', 'dotatu', '', 0, 202);
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 9, 202, currval('orcparamseqcoluna_o115_sequencial_seq'), 1, 11, 'L[8]->dot_atual-L[1]->prevatu');
insert into orcparamseqcoluna (o115_sequencial, o115_anousu, o115_descricao, o115_tipo, o115_valoresdefault, o115_nomecoluna, o115_formula, o115_origem, o115_relatorio) values (nextval('orcparamseqcoluna_o115_sequencial_seq'), 2012, 'VALOR EMPENHADO', 1, '', 'empenhado', '', 0, 202);
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 3, 202, currval('orcparamseqcoluna_o115_sequencial_seq'), 2, 11, '#empenhado_acumulado - #anulado_acumulado');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 4, 202, currval('orcparamseqcoluna_o115_sequencial_seq'), 2, 11, '#empenhado_acumulado - #anulado_acumulado');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 5, 202, currval('orcparamseqcoluna_o115_sequencial_seq'), 2, 11, '#empenhado_acumulado - #anulado_acumulado');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 6, 202, currval('orcparamseqcoluna_o115_sequencial_seq'), 2, 11, '#empenhado_acumulado - #anulado_acumulado');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 7, 202, currval('orcparamseqcoluna_o115_sequencial_seq'), 2, 11, '#empenhado_acumulado - #anulado_acumulado');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 8, 202, currval('orcparamseqcoluna_o115_sequencial_seq'), 2, 11, 'F[2] - L[6]->empenhado - L[7]->empenhado');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 2, 202, currval('orcparamseqcoluna_o115_sequencial_seq'), 2, 11, 'L[3]->empenhado+L[4]->empenhado+L[5]->empenhado');
insert into orcparamseqcoluna (o115_sequencial, o115_anousu, o115_descricao, o115_tipo, o115_valoresdefault, o115_nomecoluna, o115_formula, o115_origem, o115_relatorio) values (nextval('orcparamseqcoluna_o115_sequencial_seq'), 2013, 'PREVISÃO ATUALIZADA (a)', 1, '', 'prevatu', '', 0, 202);
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 1, 202, currval('orcparamseqcoluna_o115_sequencial_seq'), 1, 11, '#saldo_inicial_prevadic');
insert into orcparamseqcoluna (o115_sequencial, o115_anousu, o115_descricao, o115_tipo, o115_valoresdefault, o115_nomecoluna, o115_formula, o115_origem, o115_relatorio) values (nextval('orcparamseqcoluna_o115_sequencial_seq'), 2014, 'Saldo', 1, '', 'saldo', '', 0, 202);
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 1, 202, currval('orcparamseqcoluna_o115_sequencial_seq'), 3, 11, '#saldo_inicial_prevadic-#saldo_arrecadado_acumulado');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 8, 202, currval('orcparamseqcoluna_o115_sequencial_seq'), 3, 11, 'F[2] - L[6]->saldo - L[7]->saldo');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 9, 202, currval('orcparamseqcoluna_o115_sequencial_seq'), 3, 11, 'L[8]->saldo-L[1]->saldo');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 2, 202, currval('orcparamseqcoluna_o115_sequencial_seq'), 3, 11, 'L[2]->dot_atual - L[2]->empenhado');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 3, 202, currval('orcparamseqcoluna_o115_sequencial_seq'), 3, 11, '(#dot_ini+(#suplementado_acumulado-#reduzido_acumulado)) - (#empenhado_acumulado - #anulado_acumulado)');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 4, 202, currval('orcparamseqcoluna_o115_sequencial_seq'), 3, 11, '(#dot_ini+(#suplementado_acumulado-#reduzido_acumulado)) - (#empenhado_acumulado - #anulado_acumulado)');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 5, 202, currval('orcparamseqcoluna_o115_sequencial_seq'), 3, 11, '(#dot_ini+(#suplementado_acumulado-#reduzido_acumulado)) - (#empenhado_acumulado - #anulado_acumulado)');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 6, 202, currval('orcparamseqcoluna_o115_sequencial_seq'), 3, 11, '(#dot_ini+(#suplementado_acumulado-#reduzido_acumulado)) - (#empenhado_acumulado - #anulado_acumulado)');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 7, 202, currval('orcparamseqcoluna_o115_sequencial_seq'), 3, 11, '(#dot_ini+(#suplementado_acumulado-#reduzido_acumulado)) - (#empenhado_acumulado - #anulado_acumulado)');


SQL_UP
);

    }


    public function down()
    {

        $this->execute(<<<SQL_DOWN

delete from orcparamseqorcparamseqcoluna where o116_codparamrel = 202;
delete from orcparamseqfiltropadrao where o132_orcparamrel = 202;
delete from orcparamseqfiltroorcamento where o133_orcparamrel = 202;
delete from orcparamseq where o69_codparamrel = 202;
delete from orcparamrelperiodos where o113_orcparamrel = 202;
delete from orcparamrel where o42_codparrel = 202;

SQL_DOWN
    );

    }
}
