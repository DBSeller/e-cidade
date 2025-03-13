<?php

use Classes\PostgresMigration;

class M17677AdequacaoAnexoIv extends PostgresMigration
{


    public function up(){



        $sql = <<<SQL

insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values
 ( 228475 ,'[Ed 11] Anexo IV - Dem.das Rec e Desp do RPPS' ,'[Ed 11] Anexo IV - Dem.das Rec e Desp do RPPS' ,'con2_lrfrecdesprpps0001.php?dfiscal=true' ,'1' ,'1' ,'[Ed 11] Anexo IV' ,'true' );
insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 8033 ,228475 ,17 ,209 );





INSERT INTO orcparamrel VALUES (244, 'ED. 11 - DEM DAS REC E DESP PREVIDENCIARIAS', 1, 'FONTE: Sistema E-Cidade, Unidade Responsável: [nome_departamento]. Emissão: [data_emissao], às [hora_emissao]. Assinado Digitalmente no dia [data_emissao], às [hora_emissao].');



INSERT INTO orcparamrelperiodos VALUES (nextval('orcparamrelperiodos_o113_sequencial_seq'), 11, 244);
INSERT INTO orcparamrelperiodos VALUES (nextval('orcparamrelperiodos_o113_sequencial_seq'), 10, 244);
INSERT INTO orcparamrelperiodos VALUES (nextval('orcparamrelperiodos_o113_sequencial_seq'), 9, 244);
INSERT INTO orcparamrelperiodos VALUES (nextval('orcparamrelperiodos_o113_sequencial_seq'), 8, 244);
INSERT INTO orcparamrelperiodos VALUES (nextval('orcparamrelperiodos_o113_sequencial_seq'), 7, 244);
INSERT INTO orcparamrelperiodos VALUES (nextval('orcparamrelperiodos_o113_sequencial_seq'), 6, 244);



INSERT INTO orcparamseq VALUES (244, 1, 'RECEITAS CORRENTES (I)', 1, 1, 0, false, false, false, false, false, 'RECEITAS CORRENTES (I)', false, true, 1, 1, '', false, 0);
INSERT INTO orcparamseq VALUES (244, 2, 'Receita de Contribuições dos Segurados ', 1, 1, 0, false, false, false, false, false, 'Receita de Contribuições dos Segurados ', false, true, 2, 2, '', false, 0);
INSERT INTO orcparamseq VALUES (244, 3, 'Ativo', 1, 0, 0, false, false, false, false, false, 'Ativo', true, false, 3, 4, '', false, 1);
INSERT INTO orcparamseq VALUES (244, 4, 'Inativo', 1, 0, 0, false, false, false, false, false, 'Inativo', false, false, 4, 4, '', false, 1);
INSERT INTO orcparamseq VALUES (244, 5, 'Pensionista ', 1, 0, 0, false, false, false, false, false, 'Pensionista ', true, false, 5, 4, '', false, 1);
INSERT INTO orcparamseq VALUES (244, 6, 'Receita de Contribuições Patronais', 1, 1, 0, false, false, false, false, false, 'Receita de Contribuições Patronais', false, true, 6, 2, '', false, 0);
INSERT INTO orcparamseq VALUES (244, 7, 'Ativo', 1, 0, 0, false, false, false, false, false, 'Ativo', true, false, 7, 4, '', false, 1);
INSERT INTO orcparamseq VALUES (244, 8, 'Inativo', 1, 0, 0, false, false, false, false, false, 'Inativo', true, false, 8, 4, '', false, 1);
INSERT INTO orcparamseq VALUES (244, 9, 'Pensionista', 1, 0, 0, false, false, false, false, false, 'Pensionista', true, false, 9, 4, '', false, 1);
INSERT INTO orcparamseq VALUES (244, 10, 'Receita Patrimonial', 1, 1, 0, false, false, false, false, false, 'Receita Patrimonial', false, true, 10, 2, '', false, 0);
INSERT INTO orcparamseq VALUES (244, 11, 'Receitas Imobiliárias', 1, 0, 0, false, false, false, false, false, 'Receitas Imobiliárias', true, false, 11, 4, '', false, 1);
INSERT INTO orcparamseq VALUES (244, 12, 'Receitas de Valores Mobiliários', 1, 0, 0, false, false, false, false, false, 'Receitas de Valores Mobiliários', true, false, 12, 4, '', false, 1);
INSERT INTO orcparamseq VALUES (244, 13, 'Outras Receitas Patrimoniais', 1, 0, 0, false, false, false, false, false, 'Outras Receitas Patrimoniais', true, false, 13, 4, '', false, 1);
INSERT INTO orcparamseq VALUES (244, 14, 'Receita de Serviços', 1, 0, 0, false, false, false, false, false, 'Receita de Serviços', true, false, 14, 2, '', false, 1);
INSERT INTO orcparamseq VALUES (244, 15, 'Outras Receitas Correntes', 1, 1, 0, false, false, false, false, false, 'Outras Receitas Correntes', false, true, 15, 2, '', false, 0);
INSERT INTO orcparamseq VALUES (244, 16, 'Compensação Financeira entre os regimes', 1, 0, 0, false, false, false, false, false, 'Compensação Financeira entre os regimes', true, false, 16, 4, '', false, 1);
INSERT INTO orcparamseq VALUES (244, 17, 'Receita de Aportes Periódicos para Amortização de Déficit At', 1, 0, 0, false, false, false, false, false, 'Receita de Aportes Periódicos para Amortização de Déficit Atuarial do RPPS (II)1', true, false, 17, 4, '', false, 1);
INSERT INTO orcparamseq VALUES (244, 18, 'Demais Receitas Correntes', 1, 0, 0, false, false, false, false, false, 'Demais Receitas Correntes', true, false, 18, 4, '', false, 1);
INSERT INTO orcparamseq VALUES (244, 19, 'RECEITAS DE CAPITAL (III)', 1, 1, 0, false, false, false, false, false, 'RECEITAS DE CAPITAL (III)', false, true, 19, 1, '', false, 0);
INSERT INTO orcparamseq VALUES (244, 20, 'Alienação de Bens, Direitos e Ativos', 1, 0, 0, false, false, false, false, false, 'Alienação de Bens, Direitos e Ativos', true, false, 20, 2, '', false, 1);
INSERT INTO orcparamseq VALUES (244, 21, 'Amortização de Empréstimos', 1, 0, 0, false, false, false, false, false, 'Amortização de Empréstimos', true, false, 21, 2, '', false, 1);
INSERT INTO orcparamseq VALUES (244, 22, 'Outras Receitas de Capital', 1, 0, 0, false, false, false, false, false, 'Outras Receitas de Capital', true, false, 22, 2, '', false, 1);
INSERT INTO orcparamseq VALUES (244, 23, 'TOTAL DAS RECEITAS DO FUNDO EM CAPITALIZAÇÃO - (IV) = (I + I', 1, 1, 0, false, false, false, false, false, 'TOTAL DAS RECEITAS DO FUNDO EM CAPITALIZAÇÃO - (IV) = (I + III - II)', false, true, 23, 1, '', false, 0);
INSERT INTO orcparamseq VALUES (244, 24, 'Benefícios', 1, 1, 0, false, false, false, false, false, 'Benefícios', false, true, 24, 1, '', false, 0);
INSERT INTO orcparamseq VALUES (244, 25, 'Aposentadorias', 1, 0, 0, false, false, false, false, false, 'Aposentadorias', true, false, 25, 2, '', false, 2);
INSERT INTO orcparamseq VALUES (244, 26, 'Pensões por Morte', 1, 0, 0, false, false, false, false, false, 'Pensões por Morte', true, false, 26, 2, '', false, 2);
INSERT INTO orcparamseq VALUES (244, 27, 'Outras Despesas Previdenciárias', 1, 1, 0, false, false, false, false, false, 'Outras Despesas Previdenciárias', false, true, 27, 1, '', false, 0);
INSERT INTO orcparamseq VALUES (244, 28, 'Compensação Financeira entre os regimes', 1, 0, 0, false, false, false, false, false, 'Compensação Financeira entre os regimes', true, false, 28, 2, '', false, 2);
INSERT INTO orcparamseq VALUES (244, 29, 'Demais Despesas Previdenciárias', 1, 0, 0, false, false, false, false, false, 'Demais Despesas Previdenciárias', true, false, 29, 2, '', false, 2);
INSERT INTO orcparamseq VALUES (244, 30, 'TOTAL DAS DESPESAS DO FUNDO EM CAPITALIZAÇÃO (V)', 1, 1, 0, false, false, false, false, false, 'TOTAL DAS DESPESAS DO FUNDO EM CAPITALIZAÇÃO (V)', false, true, 30, 1, '', false, 0);
INSERT INTO orcparamseq VALUES (244, 31, 'RESULTADO PREVIDENCIÁRIO - FUNDO EM CAPITALIZAÇÃO (VI) = (IV', 1, 1, 0, false, false, false, false, false, 'RESULTADO PREVIDENCIÁRIO - FUNDO EM CAPITALIZAÇÃO (VI) = (IV – V)2', false, true, 31, 1, '', false, 0);
INSERT INTO orcparamseq VALUES (244, 32, 'VALOR', 1, 0, 0, false, false, false, false, false, 'VALOR', true, false, 32, 1, '', false, 1);
INSERT INTO orcparamseq VALUES (244, 33, 'VALOR', 1, 0, 0, false, false, false, false, false, 'VALOR', false, false, 33, 1, '', false, 2);
INSERT INTO orcparamseq VALUES (244, 34, 'Plano de Amortização - Contribuição Patronal Suplementar', 1, 0, 0, false, false, false, false, false, 'Plano de Amortização - Contribuição Patronal Suplementar', true, false, 34, 1, '', false, 3);
INSERT INTO orcparamseq VALUES (244, 35, 'Plano de Amortização - Aporte Periódico de Valores Predefini', 1, 0, 0, false, false, false, false, false, 'Plano de Amortização - Aporte Periódico de Valores Predefinidos', true, false, 35, 1, '', false, 3);
INSERT INTO orcparamseq VALUES (244, 36, 'Outros Aportes para o RPPS', 1, 0, 0, false, false, false, false, false, 'Outros Aportes para o RPPS', true, false, 36, 1, '', false, 3);
INSERT INTO orcparamseq VALUES (244, 37, 'Recursos para Cobertura de Déficit Financeiro', 1, 0, 0, false, false, false, false, false, 'Recursos para Cobertura de Déficit Financeiro', true, false, 37, 1, '', false, 3);
INSERT INTO orcparamseq VALUES (244, 38, 'Caixa e Equivalentes de Caixa', 1, 0, 0, false, false, false, false, false, 'Caixa e Equivalentes de Caixa', true, false, 38, 1, '', false, 3);
INSERT INTO orcparamseq VALUES (244, 39, 'Investimentos e Aplicações', 1, 0, 0, false, false, false, false, false, 'Investimentos e Aplicações', true, false, 39, 1, '', false, 3);
INSERT INTO orcparamseq VALUES (244, 40, 'Outros Bens e Direitos', 1, 0, 0, false, false, false, false, false, 'Outros Bens e Direitos', true, false, 40, 1, '', false, 3);
INSERT INTO orcparamseq VALUES (244, 41, 'RECEITAS CORRENTES (VII)', 1, 1, 0, false, false, false, false, false, 'RECEITAS CORRENTES (VII)', false, true, 41, 1, '', false, 0);
INSERT INTO orcparamseq VALUES (244, 42, 'Receita de Contribuições dos Segurados', 1, 1, 0, false, false, false, false, false, 'Receita de Contribuições dos Segurados', false, true, 42, 1, '', false, 0);
INSERT INTO orcparamseq VALUES (244, 43, 'Ativo ', 1, 0, 0, false, false, false, false, false, 'Ativo ', true, false, 43, 2, '', false, 1);
INSERT INTO orcparamseq VALUES (244, 44, 'Inativo ', 1, 0, 0, false, false, false, false, false, 'Inativo ', true, false, 44, 2, '', false, 1);
INSERT INTO orcparamseq VALUES (244, 45, 'Pensionista ', 1, 0, 0, false, false, false, false, false, 'Pensionista ', true, false, 45, 2, '', false, 1);
INSERT INTO orcparamseq VALUES (244, 46, 'Receita de Contribuições Patronais', 1, 1, 0, false, false, false, false, false, 'Receita de Contribuições Patronais', false, true, 46, 1, '', false, 0);
INSERT INTO orcparamseq VALUES (244, 47, 'Ativo ', 1, 0, 0, false, false, false, false, false, 'Ativo ', true, false, 47, 2, '', false, 1);
INSERT INTO orcparamseq VALUES (244, 48, 'Inativo ', 1, 0, 0, false, false, false, false, false, 'Inativo ', true, false, 48, 2, '', false, 1);
INSERT INTO orcparamseq VALUES (244, 49, 'Pensionista ', 1, 0, 0, false, false, false, false, false, 'Pensionista ', true, false, 49, 2, '', false, 1);
INSERT INTO orcparamseq VALUES (244, 50, 'Receita Patrimonial', 1, 1, 0, false, false, false, false, false, 'Receita Patrimonial', false, true, 50, 2, '', false, 0);
INSERT INTO orcparamseq VALUES (244, 51, 'Receitas Imobiliárias', 1, 0, 0, false, false, false, false, false, 'Receitas Imobiliárias', true, false, 51, 4, '', false, 1);
INSERT INTO orcparamseq VALUES (244, 52, 'Receitas de Valores Mobiliários', 1, 0, 0, false, false, false, false, false, 'Receitas de Valores Mobiliários', true, false, 52, 4, '', false, 1);
INSERT INTO orcparamseq VALUES (244, 53, 'Outras Receitas Patrimoniais', 1, 0, 0, false, false, false, false, false, 'Outras Receitas Patrimoniais', true, false, 53, 4, '', false, 1);
INSERT INTO orcparamseq VALUES (244, 54, 'Receita de Serviços', 1, 0, 0, false, false, false, false, false, 'Receita de Serviços', true, false, 54, 2, '', false, 1);
INSERT INTO orcparamseq VALUES (244, 55, 'Outras Receitas Correntes', 1, 1, 0, false, false, false, false, false, 'Outras Receitas Correntes', false, true, 55, 2, '', false, 0);
INSERT INTO orcparamseq VALUES (244, 56, 'Compensação Previdenciária entre os regimes', 1, 0, 0, false, false, false, false, false, 'Compensação Previdenciária entre os regimes', true, false, 56, 4, '', false, 1);
INSERT INTO orcparamseq VALUES (244, 57, 'Demais Receitas Correntes', 1, 0, 0, false, false, false, false, false, 'Demais Receitas Correntes', true, false, 57, 4, '', false, 1);
INSERT INTO orcparamseq VALUES (244, 58, 'RECEITAS DE CAPITAL (VIII)', 1, 1, 0, false, false, false, false, false, 'RECEITAS DE CAPITAL (VIII)', false, true, 58, 1, '', false, 0);
INSERT INTO orcparamseq VALUES (244, 59, 'Alienação de Bens, Direitos e Ativos', 1, 0, 0, false, false, false, false, false, 'Alienação de Bens, Direitos e Ativos', true, false, 59, 2, '', false, 1);
INSERT INTO orcparamseq VALUES (244, 60, 'Amortização de Empréstimos', 1, 0, 0, false, false, false, false, false, 'Amortização de Empréstimos', true, false, 60, 2, '', false, 1);
INSERT INTO orcparamseq VALUES (244, 61, 'Outras Receitas de Capital', 1, 0, 0, false, false, false, false, false, 'Outras Receitas de Capital', true, false, 61, 2, '', false, 1);
INSERT INTO orcparamseq VALUES (244, 62, 'TOTAL DAS RECEITAS DO FUNDO EM REPARTIÇÃO (IX) = (VII + VIII', 1, 1, 0, false, false, false, false, false, 'TOTAL DAS RECEITAS DO FUNDO EM REPARTIÇÃO (IX) = (VII + VIII)', false, true, 62, 1, '', false, 0);
INSERT INTO orcparamseq VALUES (244, 63, 'Benefícios', 1, 1, 0, false, false, false, false, false, 'Benefícios', false, true, 63, 1, '', false, 0);
INSERT INTO orcparamseq VALUES (244, 64, 'Aposentadorias ', 1, 0, 0, false, false, false, false, false, 'Aposentadorias ', true, false, 64, 2, '', false, 2);
INSERT INTO orcparamseq VALUES (244, 65, 'Pensões por Morte', 1, 0, 0, false, false, false, false, false, 'Pensões por Morte', true, false, 65, 2, '', false, 2);
INSERT INTO orcparamseq VALUES (244, 66, 'Outras Despesas Previdenciárias', 1, 1, 0, false, false, false, false, false, 'Outras Despesas Previdenciárias', false, true, 66, 1, '', false, 2);
INSERT INTO orcparamseq VALUES (244, 67, 'Compensação Previdenciária entre os regimes', 1, 0, 0, false, false, false, false, false, 'Compensação Previdenciária entre os regimes', true, false, 67, 2, '', false, 2);
INSERT INTO orcparamseq VALUES (244, 68, 'Demais Despesas Previdenciárias', 1, 0, 0, false, false, false, false, false, 'Demais Despesas Previdenciárias', true, false, 68, 2, '', false, 2);
INSERT INTO orcparamseq VALUES (244, 69, 'TOTAL DAS DESPESAS DO FUNDO EM REPARTIÇÃO  (X) ', 1, 1, 0, false, false, false, false, false, 'TOTAL DAS DESPESAS DO FUNDO EM REPARTIÇÃO  (X) ', false, true, 69, 1, '', false, 0);
INSERT INTO orcparamseq VALUES (244, 70, 'RESULTADO PREVIDENCIÁRIO - FUNDO EM REPARTIÇÃO (XI) = (IX – ', 1, 1, 0, false, false, false, false, false, 'RESULTADO PREVIDENCIÁRIO - FUNDO EM REPARTIÇÃO (XI) = (IX – X)2', false, true, 70, 1, '', false, 0);
INSERT INTO orcparamseq VALUES (244, 71, 'Recursos para Cobertura de Insuficiências Financeiras', 1, 0, 0, false, false, false, false, false, 'Recursos para Cobertura de Insuficiências Financeiras', true, false, 71, 1, '', false, 3);
INSERT INTO orcparamseq VALUES (244, 72, 'Recursos para Formação de Reserva', 1, 0, 0, false, false, false, false, false, 'Recursos para Formação de Reserva', true, false, 72, 1, '', false, 3);
INSERT INTO orcparamseq VALUES (244, 73, 'Receitas Correntes', 1, 0, 0, false, false, false, false, false, 'Receitas Correntes', true, false, 73, 1, '', false, 1);
INSERT INTO orcparamseq VALUES (244, 74, 'Despesas Correntes (XIII)', 1, 1, 0, false, false, false, false, false, 'Despesas Correntes (XIII)', false, true, 75, 1, '', false, 0);
INSERT INTO orcparamseq VALUES (244, 75, 'Pessoal e Encargos Sociais', 1, 0, 0, false, false, false, false, false, 'Pessoal e Encargos Sociais', true, false, 76, 2, '', false, 2);
INSERT INTO orcparamseq VALUES (244, 76, 'Demais Despesas Correntes', 1, 0, 0, false, false, false, false, false, 'Demais Despesas Correntes', true, false, 77, 2, '', false, 2);
INSERT INTO orcparamseq VALUES (244, 77, 'Despesas de Capital (XIV)', 1, 0, 0, false, false, false, false, false, 'Despesas de Capital (XIV)', true, false, 78, 1, '', false, 2);
INSERT INTO orcparamseq VALUES (244, 78, 'TOTAL DAS DESPESAS DA ADMINISTRAÇÃO RPPS (XV) = (XIII + XIV)', 1, 1, 0, false, false, false, false, false, 'TOTAL DAS DESPESAS DA ADMINISTRAÇÃO RPPS (XV) = (XIII + XIV)', false, true, 79, 1, '', false, 0);
INSERT INTO orcparamseq VALUES (244, 79, 'RESULTADO DA ADMINISTRAÇÃO RPPS (XVI) = (XII – XV)2', 1, 1, 0, false, false, false, false, false, 'RESULTADO DA ADMINISTRAÇÃO RPPS (XVI) = (XII – XV)2', false, true, 80, 1, '', false, 0);
INSERT INTO orcparamseq VALUES (244, 80, 'Contribuições dos Servidores ', 1, 0, 0, false, false, false, false, false, 'Contribuições dos Servidores ', true, false, 81, 1, '', false, 1);
INSERT INTO orcparamseq VALUES (244, 81, 'Demais Receitas Previdenciárias ', 1, 0, 0, false, false, false, false, false, 'Demais Receitas Previdenciárias ', true, false, 82, 1, '', false, 1);
INSERT INTO orcparamseq VALUES (244, 82, 'TOTAL DAS RECEITAS  (BENEFÍCIOS MANTIDOS PELO TESOURO) (XVII', 1, 1, 0, false, false, false, false, false, 'TOTAL DAS RECEITAS  (BENEFÍCIOS MANTIDOS PELO TESOURO) (XVII)', false, true, 83, 1, '', false, 0);
INSERT INTO orcparamseq VALUES (244, 83, 'Aposentadorias ', 1, 0, 0, false, false, false, false, false, 'Aposentadorias ', true, false, 84, 1, '', false, 2);
INSERT INTO orcparamseq VALUES (244, 84, 'Pensões', 1, 0, 0, false, false, false, false, false, 'Pensões', true, false, 85, 1, '', false, 2);
INSERT INTO orcparamseq VALUES (244, 85, 'Outras Despesas Previdenciárias', 1, 0, 0, false, false, false, false, false, 'Outras Despesas Previdenciárias', true, false, 86, 1, '', false, 2);
INSERT INTO orcparamseq VALUES (244, 86, 'TOTAL DAS DESPESAS (BENEFÍCIOS MANTIDOS PELO TESOURO) (XVIII', 1, 1, 0, false, false, false, false, false, 'TOTAL DAS DESPESAS (BENEFÍCIOS MANTIDOS PELO TESOURO) (XVIII) ', false, true, 87, 1, '', false, 0);
INSERT INTO orcparamseq VALUES (244, 87, 'RESULTADO DOS BENEFÍCIOS MANTIDOS PELO TESOURO (XIX) = (XVII', 1, 1, 0, false, false, false, false, false, 'RESULTADO DOS BENEFÍCIOS MANTIDOS PELO TESOURO (XIX) = (XVII - XVIII)2', false, true, 88, 0, '', false, 0);
INSERT INTO orcparamseq VALUES (244, 88, 'TOTAL DAS RECEITAS DA ADMINISTRAÇÃO RPPS  (XII)', 1, 1, 0, false, false, false, false, false, 'TOTAL DAS RECEITAS DA ADMINISTRAÇÃO RPPS  (XII)', false, true, 74, 1, '', false, 0);








INSERT INTO orcparamseqfiltropadrao VALUES (nextval('orcparamelementospadrao_o132_sequencial_seq'), 244, 3, 2021, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="412180110000000" nivel="8" exclusao="false" indicador=""/>
  <conta estrutural="412180140000000" nivel="8" exclusao="false" indicador=""/>
  <conta estrutural="412180210000000" nivel="8" exclusao="false" indicador=""/>
  <conta estrutural="412180240000000" nivel="8" exclusao="false" indicador=""/>
  <conta estrutural="912180110000000" nivel="8" exclusao="false" indicador=""/>
  <conta estrutural="912180140000000" nivel="8" exclusao="false" indicador=""/>
  <conta estrutural="912180210000000" nivel="8" exclusao="false" indicador=""/>
  <conta estrutural="912180240000000" nivel="8" exclusao="false" indicador=""/>
 </contas>
 <orgao operador="in" valor="" id="orgao"/>
 <unidade operador="in" valor="" id="unidade"/>
 <funcao operador="in" valor="" id="funcao"/>
 <subfuncao operador="in" valor="" id="subfuncao"/>
 <programa operador="in" valor="" id="programa"/>
 <projativ operador="in" valor="" id="projativ"/>
 <recurso operador="in" valor="0400,50,4963,4966,4967,4974" id="recurso"/>
 <recursocontalinha numerolinha="" id="recursocontalinha"/>
 <observacao valor=""/>
 <desdobrarlinha valor="false"/>
</filter>');
INSERT INTO orcparamseqfiltropadrao VALUES (nextval('orcparamelementospadrao_o132_sequencial_seq'), 244, 4, 2021, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="412180120000000" nivel="8" exclusao="false" indicador=""/>
  <conta estrutural="412180150000000" nivel="8" exclusao="false" indicador=""/>
  <conta estrutural="412180220000000" nivel="8" exclusao="false" indicador=""/>
  <conta estrutural="412180250000000" nivel="8" exclusao="false" indicador=""/>
  <conta estrutural="912180120000000" nivel="8" exclusao="false" indicador=""/>
  <conta estrutural="912180150000000" nivel="8" exclusao="false" indicador=""/>
  <conta estrutural="912180220000000" nivel="8" exclusao="false" indicador=""/>
  <conta estrutural="912180250000000" nivel="8" exclusao="false" indicador=""/>
 </contas>
 <orgao operador="in" valor="" id="orgao"/>
 <unidade operador="in" valor="" id="unidade"/>
 <funcao operador="in" valor="" id="funcao"/>
 <subfuncao operador="in" valor="" id="subfuncao"/>
 <programa operador="in" valor="" id="programa"/>
 <projativ operador="in" valor="" id="projativ"/>
 <recurso operador="in" valor="0400,50,4963,4966,4967,4974" id="recurso"/>
 <recursocontalinha numerolinha="" id="recursocontalinha"/>
 <observacao valor=""/>
 <desdobrarlinha valor="false"/>
</filter>');
INSERT INTO orcparamseqfiltropadrao VALUES (nextval('orcparamelementospadrao_o132_sequencial_seq'), 244, 5, 2021, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="412180130000000" nivel="8" exclusao="false" indicador=""/>
  <conta estrutural="412180160000000" nivel="8" exclusao="false" indicador=""/>
  <conta estrutural="412180230000000" nivel="8" exclusao="false" indicador=""/>
  <conta estrutural="412180260000000" nivel="8" exclusao="false" indicador=""/>
  <conta estrutural="912180130000000" nivel="8" exclusao="false" indicador=""/>
  <conta estrutural="912180160000000" nivel="8" exclusao="false" indicador=""/>
  <conta estrutural="912180230000000" nivel="8" exclusao="false" indicador=""/>
  <conta estrutural="912180260000000" nivel="8" exclusao="false" indicador=""/>
 </contas>
 <orgao operador="in" valor="" id="orgao"/>
 <unidade operador="in" valor="" id="unidade"/>
 <funcao operador="in" valor="" id="funcao"/>
 <subfuncao operador="in" valor="" id="subfuncao"/>
 <programa operador="in" valor="" id="programa"/>
 <projativ operador="in" valor="" id="projativ"/>
 <recurso operador="in" valor="0400,50,4963,4966,4967,4974" id="recurso"/>
 <recursocontalinha numerolinha="" id="recursocontalinha"/>
 <observacao valor=""/>
 <desdobrarlinha valor="false"/>
</filter>');
INSERT INTO orcparamseqfiltropadrao VALUES (nextval('orcparamelementospadrao_o132_sequencial_seq'), 244, 7, 2021, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="412180310000000" nivel="8" exclusao="false" indicador=""/>
  <conta estrutural="912180310000000" nivel="8" exclusao="false" indicador=""/>
  <conta estrutural="472180310000000" nivel="8" exclusao="false" indicador=""/>
  <conta estrutural="972180310000000" nivel="8" exclusao="false" indicador=""/>
  <conta estrutural="412180340000000" nivel="8" exclusao="false" indicador=""/>
  <conta estrutural="912180340000000" nivel="8" exclusao="false" indicador=""/>
  <conta estrutural="472180340000000" nivel="8" exclusao="false" indicador=""/>
  <conta estrutural="972180340000000" nivel="8" exclusao="false" indicador=""/>
  <conta estrutural="412180410000000" nivel="8" exclusao="false" indicador=""/>
  <conta estrutural="912180410000000" nivel="8" exclusao="false" indicador=""/>
  <conta estrutural="472180410000000" nivel="8" exclusao="false" indicador=""/>
  <conta estrutural="972180410000000" nivel="8" exclusao="false" indicador=""/>
  <conta estrutural="412180440000000" nivel="8" exclusao="false" indicador=""/>
  <conta estrutural="912180440000000" nivel="8" exclusao="false" indicador=""/>
  <conta estrutural="472180440000000" nivel="8" exclusao="false" indicador=""/>
  <conta estrutural="972180440000000" nivel="8" exclusao="false" indicador=""/>
 </contas>
 <orgao operador="in" valor="" id="orgao"/>
 <unidade operador="in" valor="" id="unidade"/>
 <funcao operador="in" valor="" id="funcao"/>
 <subfuncao operador="in" valor="" id="subfuncao"/>
 <programa operador="in" valor="" id="programa"/>
 <projativ operador="in" valor="" id="projativ"/>
 <recurso operador="in" valor="0400,50,4963,4966,4967,4974" id="recurso"/>
 <recursocontalinha numerolinha="" id="recursocontalinha"/>
 <observacao valor=""/>
 <desdobrarlinha valor="false"/>
</filter>');
INSERT INTO orcparamseqfiltropadrao VALUES (nextval('orcparamelementospadrao_o132_sequencial_seq'), 244, 8, 2021, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="412180320000000" nivel="8" exclusao="false" indicador=""/>
  <conta estrutural="912180320000000" nivel="8" exclusao="false" indicador=""/>
  <conta estrutural="472180320000000" nivel="8" exclusao="false" indicador=""/>
  <conta estrutural="972180320000000" nivel="8" exclusao="false" indicador=""/>
  <conta estrutural="412180350000000" nivel="8" exclusao="false" indicador=""/>
  <conta estrutural="912180350000000" nivel="8" exclusao="false" indicador=""/>
  <conta estrutural="472180350000000" nivel="8" exclusao="false" indicador=""/>
  <conta estrutural="972180350000000" nivel="8" exclusao="false" indicador=""/>
  <conta estrutural="412180420000000" nivel="8" exclusao="false" indicador=""/>
  <conta estrutural="912180420000000" nivel="8" exclusao="false" indicador=""/>
  <conta estrutural="472180420000000" nivel="8" exclusao="false" indicador=""/>
  <conta estrutural="972180420000000" nivel="8" exclusao="false" indicador=""/>
  <conta estrutural="412180450000000" nivel="8" exclusao="false" indicador=""/>
  <conta estrutural="912180450000000" nivel="8" exclusao="false" indicador=""/>
  <conta estrutural="472180450000000" nivel="8" exclusao="false" indicador=""/>
  <conta estrutural="972180450000000" nivel="8" exclusao="false" indicador=""/>
 </contas>
 <orgao operador="in" valor="" id="orgao"/>
 <unidade operador="in" valor="" id="unidade"/>
 <funcao operador="in" valor="" id="funcao"/>
 <subfuncao operador="in" valor="" id="subfuncao"/>
 <programa operador="in" valor="" id="programa"/>
 <projativ operador="in" valor="" id="projativ"/>
 <recurso operador="in" valor="0400,50,4963,4966,4967,4974" id="recurso"/>
 <recursocontalinha numerolinha="" id="recursocontalinha"/>
 <observacao valor=""/>
 <desdobrarlinha valor="false"/>
</filter>');
INSERT INTO orcparamseqfiltropadrao VALUES (nextval('orcparamelementospadrao_o132_sequencial_seq'), 244, 9, 2021, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="412180330000000" nivel="8" exclusao="false" indicador=""/>
  <conta estrutural="912180330000000" nivel="8" exclusao="false" indicador=""/>
  <conta estrutural="472180330000000" nivel="8" exclusao="false" indicador=""/>
  <conta estrutural="972180330000000" nivel="8" exclusao="false" indicador=""/>
  <conta estrutural="412180360000000" nivel="8" exclusao="false" indicador=""/>
  <conta estrutural="912180360000000" nivel="8" exclusao="false" indicador=""/>
  <conta estrutural="472180360000000" nivel="8" exclusao="false" indicador=""/>
  <conta estrutural="972180360000000" nivel="8" exclusao="false" indicador=""/>
  <conta estrutural="412180430000000" nivel="8" exclusao="false" indicador=""/>
  <conta estrutural="912180430000000" nivel="8" exclusao="false" indicador=""/>
  <conta estrutural="472180430000000" nivel="8" exclusao="false" indicador=""/>
  <conta estrutural="972180430000000" nivel="8" exclusao="false" indicador=""/>
  <conta estrutural="412180460000000" nivel="8" exclusao="false" indicador=""/>
  <conta estrutural="912180460000000" nivel="8" exclusao="false" indicador=""/>
  <conta estrutural="472180460000000" nivel="8" exclusao="false" indicador=""/>
  <conta estrutural="972180460000000" nivel="8" exclusao="false" indicador=""/>
 </contas>
 <orgao operador="in" valor="" id="orgao"/>
 <unidade operador="in" valor="" id="unidade"/>
 <funcao operador="in" valor="" id="funcao"/>
 <subfuncao operador="in" valor="" id="subfuncao"/>
 <programa operador="in" valor="" id="programa"/>
 <projativ operador="in" valor="" id="projativ"/>
 <recurso operador="in" valor="0400,50,4963,4966,4967,4974" id="recurso"/>
 <recursocontalinha numerolinha="" id="recursocontalinha"/>
 <observacao valor=""/>
 <desdobrarlinha valor="false"/>
</filter>');
INSERT INTO orcparamseqfiltropadrao VALUES (nextval('orcparamelementospadrao_o132_sequencial_seq'), 244, 10, 2021, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas/>
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
INSERT INTO orcparamseqfiltropadrao VALUES (nextval('orcparamelementospadrao_o132_sequencial_seq'), 244, 11, 2021, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="413100000000000" nivel="4" exclusao="false" indicador=""/>
  <conta estrutural="913100000000000" nivel="4" exclusao="false" indicador=""/>
 </contas>
 <orgao operador="in" valor="" id="orgao"/>
 <unidade operador="in" valor="" id="unidade"/>
 <funcao operador="in" valor="" id="funcao"/>
 <subfuncao operador="in" valor="" id="subfuncao"/>
 <programa operador="in" valor="" id="programa"/>
 <projativ operador="in" valor="" id="projativ"/>
 <recurso operador="in" valor="0400,50,4963,4966,4967,4974" id="recurso"/>
 <recursocontalinha numerolinha="" id="recursocontalinha"/>
 <observacao valor=""/>
 <desdobrarlinha valor="false"/>
</filter>');
INSERT INTO orcparamseqfiltropadrao VALUES (nextval('orcparamelementospadrao_o132_sequencial_seq'), 244, 12, 2021, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="413200000000000" nivel="4" exclusao="false" indicador=""/>
  <conta estrutural="913200000000000" nivel="4" exclusao="false" indicador=""/>
 </contas>
 <orgao operador="in" valor="" id="orgao"/>
 <unidade operador="in" valor="" id="unidade"/>
 <funcao operador="in" valor="" id="funcao"/>
 <subfuncao operador="in" valor="" id="subfuncao"/>
 <programa operador="in" valor="" id="programa"/>
 <projativ operador="in" valor="" id="projativ"/>
 <recurso operador="in" valor="0400,50,4963,4966,4967,4974" id="recurso"/>
 <recursocontalinha numerolinha="" id="recursocontalinha"/>
 <observacao valor=""/>
 <desdobrarlinha valor="false"/>
</filter>');
INSERT INTO orcparamseqfiltropadrao VALUES (nextval('orcparamelementospadrao_o132_sequencial_seq'), 244, 13, 2021, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="413300000000000" nivel="4" exclusao="false" indicador=""/>
  <conta estrutural="913300000000000" nivel="4" exclusao="false" indicador=""/>
  <conta estrutural="413600000000000" nivel="4" exclusao="false" indicador=""/>
  <conta estrutural="913600000000000" nivel="4" exclusao="false" indicador=""/>
  <conta estrutural="413900000000000" nivel="4" exclusao="false" indicador=""/>
  <conta estrutural="913900000000000" nivel="4" exclusao="false" indicador=""/>
 </contas>
 <orgao operador="in" valor="" id="orgao"/>
 <unidade operador="in" valor="" id="unidade"/>
 <funcao operador="in" valor="" id="funcao"/>
 <subfuncao operador="in" valor="" id="subfuncao"/>
 <programa operador="in" valor="" id="programa"/>
 <projativ operador="in" valor="" id="projativ"/>
 <recurso operador="in" valor="0400,50,4963,4966,4967,4974" id="recurso"/>
 <recursocontalinha numerolinha="" id="recursocontalinha"/>
 <observacao valor=""/>
 <desdobrarlinha valor="false"/>
</filter>');
INSERT INTO orcparamseqfiltropadrao VALUES (nextval('orcparamelementospadrao_o132_sequencial_seq'), 244, 14, 2021, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="416000000000000" nivel="3" exclusao="false" indicador=""/>
  <conta estrutural="916000000000000" nivel="3" exclusao="false" indicador=""/>
 </contas>
 <orgao operador="in" valor="" id="orgao"/>
 <unidade operador="in" valor="" id="unidade"/>
 <funcao operador="in" valor="" id="funcao"/>
 <subfuncao operador="in" valor="" id="subfuncao"/>
 <programa operador="in" valor="" id="programa"/>
 <projativ operador="in" valor="" id="projativ"/>
 <recurso operador="in" valor="0400,50,4963,4966,4967,4974" id="recurso"/>
 <recursocontalinha numerolinha="" id="recursocontalinha"/>
 <observacao valor=""/>
 <desdobrarlinha valor="false"/>
</filter>');
INSERT INTO orcparamseqfiltropadrao VALUES (nextval('orcparamelementospadrao_o132_sequencial_seq'), 244, 16, 2021, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="419900300000000" nivel="7" exclusao="false" indicador=""/>
  <conta estrutural="919900300000000" nivel="7" exclusao="false" indicador=""/>
 </contas>
 <orgao operador="in" valor="" id="orgao"/>
 <unidade operador="in" valor="" id="unidade"/>
 <funcao operador="in" valor="" id="funcao"/>
 <subfuncao operador="in" valor="" id="subfuncao"/>
 <programa operador="in" valor="" id="programa"/>
 <projativ operador="in" valor="" id="projativ"/>
 <recurso operador="in" valor="0400,50,4963,4966,4967,4974" id="recurso"/>
 <recursocontalinha numerolinha="" id="recursocontalinha"/>
 <observacao valor=""/>
 <desdobrarlinha valor="false"/>
</filter>');
INSERT INTO orcparamseqfiltropadrao VALUES (nextval('orcparamelementospadrao_o132_sequencial_seq'), 244, 17, 2021, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="419900110000000" nivel="8" exclusao="false" indicador=""/>
  <conta estrutural="479900110000000" nivel="8" exclusao="false" indicador=""/>
  <conta estrutural="919900110000000" nivel="8" exclusao="false" indicador=""/>
  <conta estrutural="979900110000000" nivel="8" exclusao="false" indicador=""/>
 </contas>
 <orgao operador="in" valor="" id="orgao"/>
 <unidade operador="in" valor="" id="unidade"/>
 <funcao operador="in" valor="" id="funcao"/>
 <subfuncao operador="in" valor="" id="subfuncao"/>
 <programa operador="in" valor="" id="programa"/>
 <projativ operador="in" valor="" id="projativ"/>
 <recurso operador="in" valor="0400,50,4963,4966,4967,4974" id="recurso"/>
 <recursocontalinha numerolinha="" id="recursocontalinha"/>
 <observacao valor=""/>
 <desdobrarlinha valor="false"/>
</filter>');
INSERT INTO orcparamseqfiltropadrao VALUES (nextval('orcparamelementospadrao_o132_sequencial_seq'), 244, 18, 2021, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="419000000000000" nivel="3" exclusao="false" indicador=""/>
  <conta estrutural="479000000000000" nivel="3" exclusao="false" indicador=""/>
  <conta estrutural="419900110000000" nivel="8" exclusao="true" indicador=""/>
  <conta estrutural="479900110000000" nivel="8" exclusao="true" indicador=""/>
  <conta estrutural="919900110000000" nivel="8" exclusao="true" indicador=""/>
  <conta estrutural="979900110000000" nivel="8" exclusao="true" indicador=""/>
  <conta estrutural="919000000000000" nivel="3" exclusao="false" indicador=""/>
  <conta estrutural="979000000000000" nivel="3" exclusao="false" indicador=""/>
  <conta estrutural="919900300000000" nivel="7" exclusao="true" indicador=""/>
  <conta estrutural="419900300000000" nivel="7" exclusao="true" indicador=""/>
 </contas>
 <orgao operador="in" valor="" id="orgao"/>
 <unidade operador="in" valor="" id="unidade"/>
 <funcao operador="in" valor="" id="funcao"/>
 <subfuncao operador="in" valor="" id="subfuncao"/>
 <programa operador="in" valor="" id="programa"/>
 <projativ operador="in" valor="" id="projativ"/>
 <recurso operador="in" valor="0400,50,4963,4966,4967,4974" id="recurso"/>
 <recursocontalinha numerolinha="" id="recursocontalinha"/>
 <observacao valor=""/>
 <desdobrarlinha valor="false"/>
</filter>');
INSERT INTO orcparamseqfiltropadrao VALUES (nextval('orcparamelementospadrao_o132_sequencial_seq'), 244, 20, 2021, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="422000000000000" nivel="3" exclusao="false" indicador=""/>
  <conta estrutural="922000000000000" nivel="3" exclusao="false" indicador=""/>
 </contas>
 <orgao operador="in" valor="" id="orgao"/>
 <unidade operador="in" valor="" id="unidade"/>
 <funcao operador="in" valor="" id="funcao"/>
 <subfuncao operador="in" valor="" id="subfuncao"/>
 <programa operador="in" valor="" id="programa"/>
 <projativ operador="in" valor="" id="projativ"/>
 <recurso operador="in" valor="0400,50,4963,4966,4967,4974" id="recurso"/>
 <recursocontalinha numerolinha="" id="recursocontalinha"/>
 <observacao valor=""/>
 <desdobrarlinha valor="false"/>
</filter>');
INSERT INTO orcparamseqfiltropadrao VALUES (nextval('orcparamelementospadrao_o132_sequencial_seq'), 244, 21, 2021, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="423000000000000" nivel="3" exclusao="false" indicador=""/>
  <conta estrutural="923000000000000" nivel="3" exclusao="false" indicador=""/>
 </contas>
 <orgao operador="in" valor="" id="orgao"/>
 <unidade operador="in" valor="" id="unidade"/>
 <funcao operador="in" valor="" id="funcao"/>
 <subfuncao operador="in" valor="" id="subfuncao"/>
 <programa operador="in" valor="" id="programa"/>
 <projativ operador="in" valor="" id="projativ"/>
 <recurso operador="in" valor="0400,50,4963,4966,4967,4974" id="recurso"/>
 <recursocontalinha numerolinha="" id="recursocontalinha"/>
 <observacao valor=""/>
 <desdobrarlinha valor="false"/>
</filter>');
INSERT INTO orcparamseqfiltropadrao VALUES (nextval('orcparamelementospadrao_o132_sequencial_seq'), 244, 22, 2021, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="420000000000000" nivel="2" exclusao="false" indicador=""/>
  <conta estrutural="920000000000000" nivel="2" exclusao="false" indicador=""/>
  <conta estrutural="422000000000000" nivel="3" exclusao="true" indicador=""/>
  <conta estrutural="922000000000000" nivel="3" exclusao="true" indicador=""/>
  <conta estrutural="423000000000000" nivel="3" exclusao="true" indicador=""/>
  <conta estrutural="923000000000000" nivel="3" exclusao="true" indicador=""/>
 </contas>
 <orgao operador="in" valor="" id="orgao"/>
 <unidade operador="in" valor="" id="unidade"/>
 <funcao operador="in" valor="" id="funcao"/>
 <subfuncao operador="in" valor="" id="subfuncao"/>
 <programa operador="in" valor="" id="programa"/>
 <projativ operador="in" valor="" id="projativ"/>
 <recurso operador="in" valor="0400,50,4963,4966,4967,4974" id="recurso"/>
 <recursocontalinha numerolinha="" id="recursocontalinha"/>
 <observacao valor=""/>
 <desdobrarlinha valor="false"/>
</filter>');
INSERT INTO orcparamseqfiltropadrao VALUES (nextval('orcparamelementospadrao_o132_sequencial_seq'), 244, 25, 2021, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="331900100000000" nivel="7" exclusao="false" indicador=""/>
 </contas>
 <orgao operador="in" valor="" id="orgao"/>
 <unidade operador="in" valor="" id="unidade"/>
 <funcao operador="in" valor="" id="funcao"/>
 <subfuncao operador="in" valor="" id="subfuncao"/>
 <programa operador="in" valor="" id="programa"/>
 <projativ operador="in" valor="" id="projativ"/>
 <recurso operador="in" valor="0400,50,4963,4966,4967,4974" id="recurso"/>
 <recursocontalinha numerolinha="" id="recursocontalinha"/>
 <observacao valor=""/>
 <desdobrarlinha valor="false"/>
</filter>');
INSERT INTO orcparamseqfiltropadrao VALUES (nextval('orcparamelementospadrao_o132_sequencial_seq'), 244, 26, 2021, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="331900300000000" nivel="7" exclusao="false" indicador=""/>
 </contas>
 <orgao operador="in" valor="" id="orgao"/>
 <unidade operador="in" valor="" id="unidade"/>
 <funcao operador="in" valor="" id="funcao"/>
 <subfuncao operador="in" valor="" id="subfuncao"/>
 <programa operador="in" valor="" id="programa"/>
 <projativ operador="in" valor="" id="projativ"/>
 <recurso operador="in" valor="0400,50,4963,4966,4967,4974" id="recurso"/>
 <recursocontalinha numerolinha="" id="recursocontalinha"/>
 <observacao valor=""/>
 <desdobrarlinha valor="false"/>
</filter>');
INSERT INTO orcparamseqfiltropadrao VALUES (nextval('orcparamelementospadrao_o132_sequencial_seq'), 244, 28, 2021, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="333200100000000" nivel="7" exclusao="false" indicador=""/>
  <conta estrutural="333200300000000" nivel="7" exclusao="false" indicador=""/>
  <conta estrutural="333909800000000" nivel="7" exclusao="false" indicador=""/>
  <conta estrutural="333919800000000" nivel="7" exclusao="false" indicador=""/>
 </contas>
 <orgao operador="in" valor="" id="orgao"/>
 <unidade operador="in" valor="" id="unidade"/>
 <funcao operador="in" valor="" id="funcao"/>
 <subfuncao operador="in" valor="" id="subfuncao"/>
 <programa operador="in" valor="" id="programa"/>
 <projativ operador="in" valor="" id="projativ"/>
 <recurso operador="in" valor="0400,50,4963,4966,4967,4974" id="recurso"/>
 <recursocontalinha numerolinha="" id="recursocontalinha"/>
 <observacao valor=""/>
 <desdobrarlinha valor="false"/>
</filter>');
INSERT INTO orcparamseqfiltropadrao VALUES (nextval('orcparamelementospadrao_o132_sequencial_seq'), 244, 29, 2021, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="331900100000000" nivel="7" exclusao="true" indicador=""/>
  <conta estrutural="331900300000000" nivel="7" exclusao="true" indicador=""/>
  <conta estrutural="331000000000000" nivel="3" exclusao="false" indicador=""/>
 </contas>
 <orgao operador="in" valor="" id="orgao"/>
 <unidade operador="in" valor="" id="unidade"/>
 <funcao operador="in" valor="" id="funcao"/>
 <subfuncao operador="in" valor="" id="subfuncao"/>
 <programa operador="in" valor="" id="programa"/>
 <projativ operador="in" valor="" id="projativ"/>
 <recurso operador="in" valor="0400,50,4963,4966,4967,4974" id="recurso"/>
 <recursocontalinha numerolinha="" id="recursocontalinha"/>
 <observacao valor=""/>
 <desdobrarlinha valor="false"/>
</filter>');
INSERT INTO orcparamseqfiltropadrao VALUES (nextval('orcparamelementospadrao_o132_sequencial_seq'), 244, 32, 2021, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="499900000000000" nivel="4" exclusao="false" indicador=""/>
  <conta estrutural="999900000000000" nivel="4" exclusao="false" indicador=""/>
 </contas>
 <orgao operador="in" valor="" id="orgao"/>
 <unidade operador="in" valor="" id="unidade"/>
 <funcao operador="in" valor="" id="funcao"/>
 <subfuncao operador="in" valor="" id="subfuncao"/>
 <programa operador="in" valor="" id="programa"/>
 <projativ operador="in" valor="" id="projativ"/>
 <recurso operador="in" valor="0400,50,4963,4966,4967,4974" id="recurso"/>
 <recursocontalinha numerolinha="" id="recursocontalinha"/>
 <observacao valor=""/>
 <desdobrarlinha valor="false"/>
</filter>');
INSERT INTO orcparamseqfiltropadrao VALUES (nextval('orcparamelementospadrao_o132_sequencial_seq'), 244, 33, 2021, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="377000000000000" nivel="3" exclusao="false" indicador=""/>
  <conta estrutural="399000000000000" nivel="3" exclusao="false" indicador=""/>
 </contas>
 <orgao operador="in" valor="" id="orgao"/>
 <unidade operador="in" valor="" id="unidade"/>
 <funcao operador="in" valor="" id="funcao"/>
 <subfuncao operador="in" valor="" id="subfuncao"/>
 <programa operador="in" valor="" id="programa"/>
 <projativ operador="in" valor="" id="projativ"/>
 <recurso operador="in" valor="0400,50,4963,4966,4967,4974" id="recurso"/>
 <recursocontalinha numerolinha="" id="recursocontalinha"/>
 <observacao valor=""/>
 <desdobrarlinha valor="false"/>
</filter>');
INSERT INTO orcparamseqfiltropadrao VALUES (nextval('orcparamelementospadrao_o132_sequencial_seq'), 244, 34, 2021, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="451320205000000" nivel="" exclusao="false" indicador=""/>
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
INSERT INTO orcparamseqfiltropadrao VALUES (nextval('orcparamelementospadrao_o132_sequencial_seq'), 244, 35, 2021, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="451320202000000" nivel="" exclusao="false" indicador=""/>
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
INSERT INTO orcparamseqfiltropadrao VALUES (nextval('orcparamelementospadrao_o132_sequencial_seq'), 244, 36, 2021, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="451320299000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="451329900000000" nivel="" exclusao="false" indicador=""/>
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
INSERT INTO orcparamseqfiltropadrao VALUES (nextval('orcparamelementospadrao_o132_sequencial_seq'), 244, 37, 2021, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="451320201000000" nivel="" exclusao="false" indicador=""/>
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
INSERT INTO orcparamseqfiltropadrao VALUES (nextval('orcparamelementospadrao_o132_sequencial_seq'), 244, 38, 2021, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="111115000000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="111110600000000" nivel="" exclusao="false" indicador=""/>
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
INSERT INTO orcparamseqfiltropadrao VALUES (nextval('orcparamelementospadrao_o132_sequencial_seq'), 244, 39, 2021, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="114110900000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="114111000000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="114111200000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="114111300000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="114111400000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="114910100000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="114910300000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="122300000000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="122910300000000" nivel="" exclusao="false" indicador=""/>
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
INSERT INTO orcparamseqfiltropadrao VALUES (nextval('orcparamelementospadrao_o132_sequencial_seq'), 244, 40, 2021, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="112410700000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="112420700000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="112430700000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="112440700000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="112450700000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="121110303000000" nivel="" exclusao="false" indicador=""/>
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
INSERT INTO orcparamseqfiltropadrao VALUES (nextval('orcparamelementospadrao_o132_sequencial_seq'), 244, 43, 2021, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="412180110000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="412180210000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="412180240000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="912180140000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="912180210000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="912180240000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="472180110000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="972180110000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="472180140000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="972180140000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="472180210000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="972180210000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="972180240000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="472180240000000" nivel="" exclusao="false" indicador=""/>
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
INSERT INTO orcparamseqfiltropadrao VALUES (nextval('orcparamelementospadrao_o132_sequencial_seq'), 244, 44, 2021, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="412180120000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="912180120000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="472180120000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="972180120000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="412180150000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="912180120000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="472180120000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="972180120000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="412180220000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="912180220000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="472180220000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="972180220000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="412180250000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="912180250000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="472180250000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="972180250000000" nivel="" exclusao="false" indicador=""/>
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
INSERT INTO orcparamseqfiltropadrao VALUES (nextval('orcparamelementospadrao_o132_sequencial_seq'), 244, 45, 2021, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="412180130000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="912180130000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="472180130000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="972180130000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="412180160000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="912180160000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="472180160000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="972180160000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="412180230000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="912180230000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="472180230000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="972180230000000" nivel="" exclusao="false" indicador=""/>
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
INSERT INTO orcparamseqfiltropadrao VALUES (nextval('orcparamelementospadrao_o132_sequencial_seq'), 244, 47, 2021, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="412180310000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="912180310000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="472180310000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="972180310000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="412180340000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="912180340000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="472180340000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="972180340000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="412180410000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="912180410000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="472180410000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="972180410000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="412180440000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="912180440000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="472180440000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="972180440000000" nivel="" exclusao="false" indicador=""/>
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
INSERT INTO orcparamseqfiltropadrao VALUES (nextval('orcparamelementospadrao_o132_sequencial_seq'), 244, 48, 2021, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="412180320000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="912180320000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="472180320000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="972180320000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="412180350000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="912180350000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="472180350000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="972180350000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="412180420000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="912180420000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="472180420000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="972180420000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="412180450000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="912180450000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="472180450000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="972180450000000" nivel="" exclusao="false" indicador=""/>
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
INSERT INTO orcparamseqfiltropadrao VALUES (nextval('orcparamelementospadrao_o132_sequencial_seq'), 244, 49, 2021, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="412180330000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="912180330000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="472180330000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="972180330000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="412180360000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="912180360000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="472180360000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="972180360000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="412180430000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="912180430000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="472180430000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="972180430000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="412180460000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="912180460000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="472180460000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="972180460000000" nivel="" exclusao="false" indicador=""/>
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
INSERT INTO orcparamseqfiltropadrao VALUES (nextval('orcparamelementospadrao_o132_sequencial_seq'), 244, 51, 2021, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="413100000000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="913100000000000" nivel="" exclusao="false" indicador=""/>
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
INSERT INTO orcparamseqfiltropadrao VALUES (nextval('orcparamelementospadrao_o132_sequencial_seq'), 244, 52, 2021, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="413200000000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="913200000000000" nivel="" exclusao="false" indicador=""/>
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
INSERT INTO orcparamseqfiltropadrao VALUES (nextval('orcparamelementospadrao_o132_sequencial_seq'), 244, 53, 2021, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="413000000000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="413100000000000" nivel="" exclusao="true" indicador=""/>
  <conta estrutural="913100000000000" nivel="" exclusao="true" indicador=""/>
  <conta estrutural="913000000000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="413200000000000" nivel="" exclusao="true" indicador=""/>
  <conta estrutural="913200000000000" nivel="" exclusao="true" indicador=""/>
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
INSERT INTO orcparamseqfiltropadrao VALUES (nextval('orcparamelementospadrao_o132_sequencial_seq'), 244, 54, 2021, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="416000000000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="916000000000000" nivel="" exclusao="false" indicador=""/>
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
INSERT INTO orcparamseqfiltropadrao VALUES (nextval('orcparamelementospadrao_o132_sequencial_seq'), 244, 56, 2021, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="419900300000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="919903000000000" nivel="" exclusao="false" indicador=""/>
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
INSERT INTO orcparamseqfiltropadrao VALUES (nextval('orcparamelementospadrao_o132_sequencial_seq'), 244, 57, 2021, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="419000000000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="479000000000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="419900000000000" nivel="" exclusao="true" indicador=""/>
  <conta estrutural="919900000000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="919000000000000" nivel="" exclusao="true" indicador=""/>
  <conta estrutural="979000000000000" nivel="" exclusao="true" indicador=""/>
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
INSERT INTO orcparamseqfiltropadrao VALUES (nextval('orcparamelementospadrao_o132_sequencial_seq'), 244, 59, 2021, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="422000000000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="922000000000000" nivel="" exclusao="false" indicador=""/>
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
INSERT INTO orcparamseqfiltropadrao VALUES (nextval('orcparamelementospadrao_o132_sequencial_seq'), 244, 60, 2021, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="423000000000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="923000000000000" nivel="" exclusao="false" indicador=""/>
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
INSERT INTO orcparamseqfiltropadrao VALUES (nextval('orcparamelementospadrao_o132_sequencial_seq'), 244, 61, 2021, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="420000000000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="920000000000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="422000000000000" nivel="" exclusao="true" indicador=""/>
  <conta estrutural="922000000000000" nivel="" exclusao="true" indicador=""/>
  <conta estrutural="423000000000000" nivel="" exclusao="true" indicador=""/>
  <conta estrutural="923000000000000" nivel="" exclusao="true" indicador=""/>
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
INSERT INTO orcparamseqfiltropadrao VALUES (nextval('orcparamelementospadrao_o132_sequencial_seq'), 244, 64, 2021, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="331900100000000" nivel="7" exclusao="false" indicador=""/>
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
INSERT INTO orcparamseqfiltropadrao VALUES (nextval('orcparamelementospadrao_o132_sequencial_seq'), 244, 65, 2021, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="331900300000000" nivel="7" exclusao="false" indicador=""/>
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
INSERT INTO orcparamseqfiltropadrao VALUES (nextval('orcparamelementospadrao_o132_sequencial_seq'), 244, 67, 2021, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="333909800000000" nivel="7" exclusao="false" indicador=""/>
  <conta estrutural="333919800000000" nivel="7" exclusao="false" indicador=""/>
  <conta estrutural="333200100000000" nivel="7" exclusao="false" indicador=""/>
  <conta estrutural="333200300000000" nivel="7" exclusao="false" indicador=""/>
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
INSERT INTO orcparamseqfiltropadrao VALUES (nextval('orcparamelementospadrao_o132_sequencial_seq'), 244, 68, 2021, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="331000000000000" nivel="3" exclusao="false" indicador=""/>
  <conta estrutural="331900100000000" nivel="7" exclusao="true" indicador=""/>
  <conta estrutural="331900300000000" nivel="7" exclusao="true" indicador=""/>
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
INSERT INTO orcparamseqfiltropadrao VALUES (nextval('orcparamelementospadrao_o132_sequencial_seq'), 244, 71, 2021, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="451320101000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="451320199000000" nivel="" exclusao="false" indicador=""/>
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
INSERT INTO orcparamseqfiltropadrao VALUES (nextval('orcparamelementospadrao_o132_sequencial_seq'), 244, 72, 2021, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="451320102000000" nivel="" exclusao="false" indicador=""/>
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
INSERT INTO orcparamseqfiltropadrao VALUES (nextval('orcparamelementospadrao_o132_sequencial_seq'), 244, 73, 2021, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="410000000000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="420000000000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="470000000000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="910000000000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="920000000000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="970000000000000" nivel="" exclusao="false" indicador=""/>
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
INSERT INTO orcparamseqfiltropadrao VALUES (nextval('orcparamelementospadrao_o132_sequencial_seq'), 244, 75, 2021, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="331000000000000" nivel="3" exclusao="false" indicador=""/>
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
INSERT INTO orcparamseqfiltropadrao VALUES (nextval('orcparamelementospadrao_o132_sequencial_seq'), 244, 76, 2021, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="332000000000000" nivel="3" exclusao="false" indicador=""/>
  <conta estrutural="333000000000000" nivel="3" exclusao="false" indicador=""/>
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
INSERT INTO orcparamseqfiltropadrao VALUES (nextval('orcparamelementospadrao_o132_sequencial_seq'), 244, 77, 2021, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="340000000000000" nivel="2" exclusao="false" indicador=""/>
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
INSERT INTO orcparamseqfiltropadrao VALUES (nextval('orcparamelementospadrao_o132_sequencial_seq'), 244, 80, 2021, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="412180100000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="912180100000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="472180100000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="972180100000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="412180200000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="912180200000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="472180200000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="972180200000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="412180300000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="912180300000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="472180300000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="972180300000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="412180400000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="912180400000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="472180400000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="972180400000000" nivel="" exclusao="false" indicador=""/>
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
INSERT INTO orcparamseqfiltropadrao VALUES (nextval('orcparamelementospadrao_o132_sequencial_seq'), 244, 83, 2021, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="331900100000000" nivel="7" exclusao="false" indicador=""/>
 </contas>
 <orgao operador="in" valor="" id="orgao"/>
 <unidade operador="in" valor="" id="unidade"/>
 <funcao operador="in" valor="" id="funcao"/>
 <subfuncao operador="in" valor="274" id="subfuncao"/>
 <programa operador="in" valor="" id="programa"/>
 <projativ operador="in" valor="" id="projativ"/>
 <recurso operador="in" valor="" id="recurso"/>
 <recursocontalinha numerolinha="" id="recursocontalinha"/>
 <observacao valor=""/>
 <desdobrarlinha valor="false"/>
</filter>');
INSERT INTO orcparamseqfiltropadrao VALUES (nextval('orcparamelementospadrao_o132_sequencial_seq'), 244, 84, 2021, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="331900300000000" nivel="7" exclusao="false" indicador=""/>
 </contas>
 <orgao operador="in" valor="" id="orgao"/>
 <unidade operador="in" valor="" id="unidade"/>
 <funcao operador="in" valor="" id="funcao"/>
 <subfuncao operador="in" valor="274" id="subfuncao"/>
 <programa operador="in" valor="" id="programa"/>
 <projativ operador="in" valor="" id="projativ"/>
 <recurso operador="in" valor="" id="recurso"/>
 <recursocontalinha numerolinha="" id="recursocontalinha"/>
 <observacao valor=""/>
 <desdobrarlinha valor="false"/>
</filter>');
INSERT INTO orcparamseqfiltropadrao VALUES (nextval('orcparamelementospadrao_o132_sequencial_seq'), 244, 85, 2021, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="331000000000000" nivel="3" exclusao="false" indicador=""/>
  <conta estrutural="331900100000000" nivel="7" exclusao="true" indicador=""/>
  <conta estrutural="331900300000000" nivel="7" exclusao="true" indicador=""/>
 </contas>
 <orgao operador="in" valor="" id="orgao"/>
 <unidade operador="in" valor="" id="unidade"/>
 <funcao operador="in" valor="" id="funcao"/>
 <subfuncao operador="in" valor="274" id="subfuncao"/>
 <programa operador="in" valor="" id="programa"/>
 <projativ operador="in" valor="" id="projativ"/>
 <recurso operador="in" valor="" id="recurso"/>
 <recursocontalinha numerolinha="" id="recursocontalinha"/>
 <observacao valor=""/>
 <desdobrarlinha valor="false"/>
</filter>');






INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 52, 244, 152, 2, 6, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 8, 244, 152, 2, 11, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 8, 244, 152, 2, 10, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 8, 244, 152, 2, 9, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 8, 244, 152, 2, 8, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 8, 244, 152, 2, 7, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 8, 244, 152, 2, 6, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 66, 244, 188, 5, 11, 'L[67]->rp_nproc+L[68]->rp_nproc');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 66, 244, 188, 5, 10, 'L[67]->rp_nproc+L[68]->rp_nproc');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 66, 244, 188, 5, 9, 'L[67]->rp_nproc+L[68]->rp_nproc');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 66, 244, 188, 5, 8, 'L[67]->rp_nproc+L[68]->rp_nproc');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 66, 244, 188, 5, 7, 'L[67]->rp_nproc+L[68]->rp_nproc');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 66, 244, 188, 5, 6, 'L[67]->rp_nproc+L[68]->rp_nproc');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 66, 244, 176, 4, 11, 'L[67]->desppag+L[68]->desppag');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 66, 244, 176, 4, 10, 'L[67]->desppag+L[68]->desppag');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 66, 244, 176, 4, 9, 'L[67]->desppag+L[68]->desppag');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 66, 244, 176, 4, 8, 'L[67]->desppag+L[68]->desppag');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 66, 244, 176, 4, 7, 'L[67]->desppag+L[68]->desppag');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 66, 244, 176, 4, 6, 'L[67]->desppag+L[68]->desppag');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 66, 244, 156, 3, 11, 'L[67]->liq_atebim+L[68]->liq_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 66, 244, 156, 3, 10, 'L[67]->liq_atebim+L[68]->liq_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 66, 244, 156, 3, 9, 'L[67]->liq_atebim+L[68]->liq_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 66, 244, 156, 3, 8, 'L[67]->liq_atebim+L[68]->liq_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 66, 244, 156, 3, 7, 'L[67]->liq_atebim+L[68]->liq_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 66, 244, 156, 3, 6, 'L[67]->liq_atebim+L[68]->liq_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 66, 244, 155, 2, 11, 'L[67]->emp_atebim+L[68]->emp_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 66, 244, 155, 2, 10, 'L[67]->emp_atebim+L[68]->emp_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 66, 244, 155, 2, 9, 'L[67]->emp_atebim+L[68]->emp_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 66, 244, 155, 2, 8, 'L[67]->emp_atebim+L[68]->emp_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 66, 244, 155, 2, 7, 'L[67]->emp_atebim+L[68]->emp_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 66, 244, 155, 2, 6, 'L[67]->emp_atebim+L[68]->emp_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 66, 244, 154, 1, 11, 'L[67]->dot_atual+L[68]->dot_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 66, 244, 154, 1, 10, 'L[67]->dot_atual+L[68]->dot_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 66, 244, 154, 1, 9, 'L[67]->dot_atual+L[68]->dot_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 66, 244, 154, 1, 8, 'L[67]->dot_atual+L[68]->dot_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 66, 244, 154, 1, 7, 'L[67]->dot_atual+L[68]->dot_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 66, 244, 154, 1, 6, 'L[67]->dot_atual+L[68]->dot_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 28, 244, 154, 1, 11, '#dot_ini+#suplementado_acumulado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 28, 244, 154, 1, 10, '#dot_ini+#suplementado_acumulado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 28, 244, 154, 1, 9, '#dot_ini+#suplementado_acumulado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 28, 244, 154, 1, 8, '#dot_ini+#suplementado_acumulado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 28, 244, 154, 1, 7, '#dot_ini+#suplementado_acumulado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 28, 244, 154, 1, 6, '#dot_ini+#suplementado_acumulado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 25, 244, 154, 1, 11, '#dot_ini+#suplementado_acumulado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 25, 244, 154, 1, 10, '#dot_ini+#suplementado_acumulado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 25, 244, 154, 1, 9, '#dot_ini+#suplementado_acumulado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 25, 244, 154, 1, 8, '#dot_ini+#suplementado_acumulado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 25, 244, 154, 1, 7, '#dot_ini+#suplementado_acumulado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 25, 244, 154, 1, 6, '#dot_ini+#suplementado_acumulado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 1, 244, 152, 2, 11, 'F[2]+F[6]+F[10]+L[14]->rec_atebim+F[15]+F[16]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 1, 244, 152, 2, 10, 'F[2]+F[6]+F[10]+L[14]->rec_atebim+F[15]+F[16]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 1, 244, 152, 2, 9, 'F[2]+F[6]+F[10]+L[14]->rec_atebim+F[15]+F[16]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 1, 244, 152, 2, 8, 'F[2]+F[6]+F[10]+L[14]->rec_atebim+F[15]+F[16]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 1, 244, 152, 2, 7, 'F[2]+F[6]+F[10]+L[14]->rec_atebim+F[15]+F[16]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 1, 244, 152, 2, 6, 'F[2]+F[6]+F[10]+L[14]->rec_atebim+F[15]+F[16]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 1, 244, 151, 1, 11, 'F[2]+F[6]+F[10]+L[14]->prev_atual+F[15]+F[16]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 1, 244, 151, 1, 10, 'F[2]+F[6]+F[10]+L[14]->prev_atual+F[15]+F[16]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 1, 244, 151, 1, 9, 'F[2]+F[6]+F[10]+L[14]->prev_atual+F[15]+F[16]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 1, 244, 151, 1, 8, 'F[2]+F[6]+F[10]+L[14]->prev_atual+F[15]+F[16]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 1, 244, 151, 1, 7, 'F[2]+F[6]+F[10]+L[14]->prev_atual+F[15]+F[16]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 1, 244, 151, 1, 6, 'F[2]+F[6]+F[10]+L[14]->prev_atual+F[15]+F[16]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 43, 244, 151, 1, 11, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 43, 244, 151, 1, 10, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 43, 244, 151, 1, 9, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 43, 244, 151, 1, 8, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 43, 244, 151, 1, 7, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 43, 244, 151, 1, 6, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 23, 244, 152, 2, 11, 'F[1]+F[19]-L[17]->rec_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 23, 244, 152, 2, 10, 'F[1]+F[19]-L[17]->rec_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 23, 244, 152, 2, 9, 'F[1]+F[19]-L[17]->rec_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 23, 244, 152, 2, 8, 'F[1]+F[19]-L[17]->rec_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 23, 244, 152, 2, 7, 'F[1]+F[19]-L[17]->rec_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 23, 244, 152, 2, 6, 'F[1]+F[19]-L[17]->rec_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 23, 244, 151, 1, 11, 'F[1]+F[19]-L[17]->prev_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 23, 244, 151, 1, 10, 'F[1]+F[19]-L[17]->prev_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 23, 244, 151, 1, 9, 'F[1]+F[19]-L[17]->prev_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 23, 244, 151, 1, 8, 'F[1]+F[19]-L[17]->prev_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 23, 244, 151, 1, 7, 'F[1]+F[19]-L[17]->prev_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 23, 244, 151, 1, 6, 'F[1]+F[19]-L[17]->prev_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 87, 244, 176, 4, 11, 'F[83]-F[87]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 87, 244, 176, 4, 10, 'F[83]-F[87]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 87, 244, 176, 4, 9, 'F[83]-F[87]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 87, 244, 176, 4, 8, 'F[83]-F[87]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 87, 244, 176, 4, 7, 'F[83]-F[87]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 87, 244, 176, 4, 6, 'F[83]-F[87]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 87, 244, 156, 3, 11, 'F[83]-F[87]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 87, 244, 156, 3, 10, 'F[83]-F[87]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 87, 244, 156, 3, 9, 'F[83]-F[87]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 87, 244, 156, 3, 8, 'F[83]-F[87]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 87, 244, 156, 3, 7, 'F[83]-F[87]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 87, 244, 156, 3, 6, 'F[83]-F[87]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 87, 244, 155, 2, 11, 'F[83]-F[87]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 87, 244, 155, 2, 10, 'F[83]-F[87]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 87, 244, 155, 2, 9, 'F[83]-F[87]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 87, 244, 155, 2, 8, 'F[83]-F[87]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 87, 244, 155, 2, 7, 'F[83]-F[87]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 87, 244, 155, 2, 6, 'F[83]-F[87]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 87, 244, 154, 1, 11, 'F[83]-F[87]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 87, 244, 154, 1, 10, 'F[83]-F[87]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 87, 244, 154, 1, 9, 'F[83]-F[87]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 87, 244, 154, 1, 8, 'F[83]-F[87]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 87, 244, 154, 1, 7, 'F[83]-F[87]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 87, 244, 154, 1, 6, 'F[83]-F[87]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 86, 244, 188, 5, 11, 'L[84]->rp_nproc+L[85]->rp_nproc+L[86]->rp_nproc');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 86, 244, 188, 5, 10, 'L[84]->rp_nproc+L[85]->rp_nproc+L[86]->rp_nproc');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 86, 244, 188, 5, 9, 'L[84]->rp_nproc+L[85]->rp_nproc+L[86]->rp_nproc');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 86, 244, 188, 5, 8, 'L[84]->rp_nproc+L[85]->rp_nproc+L[86]->rp_nproc');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 86, 244, 188, 5, 7, 'L[84]->rp_nproc+L[85]->rp_nproc+L[86]->rp_nproc');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 86, 244, 188, 5, 6, 'L[84]->rp_nproc+L[85]->rp_nproc+L[86]->rp_nproc');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 86, 244, 176, 4, 11, 'L[84]->desppag+L[85]->desppag+L[86]->desppag');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 86, 244, 176, 4, 10, 'L[84]->desppag+L[85]->desppag+L[86]->desppag');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 86, 244, 176, 4, 9, 'L[84]->desppag+L[85]->desppag+L[86]->desppag');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 86, 244, 176, 4, 8, 'L[84]->desppag+L[85]->desppag+L[86]->desppag');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 86, 244, 176, 4, 7, 'L[84]->desppag+L[85]->desppag+L[86]->desppag');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 86, 244, 176, 4, 6, 'L[84]->desppag+L[85]->desppag+L[86]->desppag');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 86, 244, 156, 3, 11, 'L[84]->liq_atebim+L[85]->liq_atebim+L[86]->liq_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 86, 244, 156, 3, 10, 'L[84]->liq_atebim+L[85]->liq_atebim+L[86]->liq_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 86, 244, 156, 3, 9, 'L[84]->liq_atebim+L[85]->liq_atebim+L[86]->liq_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 86, 244, 156, 3, 8, 'L[84]->liq_atebim+L[85]->liq_atebim+L[86]->liq_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 86, 244, 156, 3, 7, 'L[84]->liq_atebim+L[85]->liq_atebim+L[86]->liq_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 86, 244, 156, 3, 6, 'L[84]->liq_atebim+L[85]->liq_atebim+L[86]->liq_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 86, 244, 155, 2, 11, 'L[84]->emp_atebim+L[85]->emp_atebim+L[86]->emp_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 86, 244, 155, 2, 10, 'L[84]->emp_atebim+L[85]->emp_atebim+L[86]->emp_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 86, 244, 155, 2, 9, 'L[84]->emp_atebim+L[85]->emp_atebim+L[86]->emp_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 86, 244, 155, 2, 8, 'L[84]->emp_atebim+L[85]->emp_atebim+L[86]->emp_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 86, 244, 155, 2, 7, 'L[84]->emp_atebim+L[85]->emp_atebim+L[86]->emp_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 86, 244, 155, 2, 6, 'L[84]->emp_atebim+L[85]->emp_atebim+L[86]->emp_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 86, 244, 154, 1, 11, 'L[84]->dot_atual+L[85]->dot_atual+L[86]->dot_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 86, 244, 154, 1, 10, 'L[84]->dot_atual+L[85]->dot_atual+L[86]->dot_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 86, 244, 154, 1, 9, 'L[84]->dot_atual+L[85]->dot_atual+L[86]->dot_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 86, 244, 154, 1, 8, 'L[84]->dot_atual+L[85]->dot_atual+L[86]->dot_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 86, 244, 154, 1, 7, 'L[84]->dot_atual+L[85]->dot_atual+L[86]->dot_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 86, 244, 154, 1, 6, 'L[84]->dot_atual+L[85]->dot_atual+L[86]->dot_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 82, 244, 152, 2, 11, 'L[81]->rec_atebim+L[82]->rec_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 82, 244, 152, 2, 10, 'L[81]->rec_atebim+L[82]->rec_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 82, 244, 152, 2, 9, 'L[81]->rec_atebim+L[82]->rec_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 82, 244, 152, 2, 8, 'L[81]->rec_atebim+L[82]->rec_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 82, 244, 152, 2, 7, 'L[81]->rec_atebim+L[82]->rec_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 82, 244, 152, 2, 6, 'L[81]->rec_atebim+L[82]->rec_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 82, 244, 151, 1, 11, 'L[81]->prev_atual+L[82]->prev_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 82, 244, 151, 1, 10, 'L[81]->prev_atual+L[82]->prev_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 82, 244, 151, 1, 9, 'L[81]->prev_atual+L[82]->prev_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 82, 244, 151, 1, 8, 'L[81]->prev_atual+L[82]->prev_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 82, 244, 151, 1, 7, 'L[81]->prev_atual+L[82]->prev_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 82, 244, 151, 1, 6, 'L[81]->prev_atual+L[82]->prev_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 79, 244, 188, 5, 11, 'F[74]-F[79]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 79, 244, 188, 5, 10, 'F[74]-F[79]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 79, 244, 188, 5, 9, 'F[74]-F[79]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 79, 244, 188, 5, 8, 'F[74]-F[79]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 79, 244, 188, 5, 7, 'F[74]-F[79]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 79, 244, 188, 5, 6, 'F[74]-F[79]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 79, 244, 176, 4, 11, 'F[74]-F[79]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 79, 244, 176, 4, 10, 'F[74]-F[79]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 79, 244, 176, 4, 9, 'F[74]-F[79]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 79, 244, 176, 4, 8, 'F[74]-F[79]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 79, 244, 176, 4, 7, 'F[74]-F[79]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 79, 244, 176, 4, 6, 'F[74]-F[79]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 79, 244, 156, 3, 11, 'F[74]-F[79]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 79, 244, 156, 3, 10, 'F[74]-F[79]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 79, 244, 156, 3, 9, 'F[74]-F[79]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 79, 244, 156, 3, 8, 'F[74]-F[79]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 79, 244, 156, 3, 7, 'F[74]-F[79]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 79, 244, 156, 3, 6, 'F[74]-F[79]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 79, 244, 155, 2, 11, 'F[74]-F[79]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 79, 244, 155, 2, 10, 'F[74]-F[79]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 79, 244, 155, 2, 9, 'F[74]-F[79]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 79, 244, 155, 2, 8, 'F[74]-F[79]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 79, 244, 155, 2, 7, 'F[74]-F[79]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 79, 244, 155, 2, 6, 'F[74]-F[79]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 79, 244, 154, 1, 11, 'F[74]-F[79]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 79, 244, 154, 1, 10, 'F[74]-F[79]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 79, 244, 154, 1, 9, 'F[74]-F[79]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 79, 244, 154, 1, 8, 'F[74]-F[79]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 79, 244, 154, 1, 7, 'F[74]-F[79]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 79, 244, 154, 1, 6, 'F[74]-F[79]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 78, 244, 188, 5, 11, 'F[75]+L[78]->rp_nproc');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 78, 244, 188, 5, 10, 'F[75]+L[78]->rp_nproc');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 78, 244, 188, 5, 9, 'F[75]+L[78]->rp_nproc');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 78, 244, 188, 5, 8, 'F[75]+L[78]->rp_nproc');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 78, 244, 188, 5, 7, 'F[75]+L[78]->rp_nproc');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 78, 244, 188, 5, 6, 'F[75]+L[78]->rp_nproc');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 78, 244, 176, 4, 11, 'F[75]+L[78]->desppag');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 78, 244, 176, 4, 10, 'F[75]+L[78]->desppag');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 78, 244, 176, 4, 9, 'F[75]+L[78]->desppag');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 78, 244, 176, 4, 8, 'F[75]+L[78]->desppag');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 78, 244, 176, 4, 7, 'F[75]+L[78]->desppag');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 78, 244, 176, 4, 6, 'F[75]+L[78]->desppag');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 78, 244, 156, 3, 11, 'F[75]+L[78]->liq_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 78, 244, 156, 3, 10, 'F[75]+L[78]->liq_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 78, 244, 156, 3, 9, 'F[75]+L[78]->liq_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 78, 244, 156, 3, 8, 'F[75]+L[78]->liq_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 78, 244, 156, 3, 7, 'F[75]+L[78]->liq_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 78, 244, 156, 3, 6, 'F[75]+L[78]->liq_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 78, 244, 155, 2, 11, 'F[75]+L[78]->emp_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 78, 244, 155, 2, 10, 'F[75]+L[78]->emp_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 78, 244, 155, 2, 9, 'F[75]+L[78]->emp_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 78, 244, 155, 2, 8, 'F[75]+L[78]->emp_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 78, 244, 155, 2, 7, 'F[75]+L[78]->emp_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 78, 244, 155, 2, 6, 'F[75]+L[78]->emp_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 78, 244, 154, 1, 11, 'F[75]+L[78]->dot_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 78, 244, 154, 1, 10, 'F[75]+L[78]->dot_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 78, 244, 154, 1, 9, 'F[75]+L[78]->dot_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 78, 244, 154, 1, 8, 'F[75]+L[78]->dot_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 78, 244, 154, 1, 7, 'F[75]+L[78]->dot_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 78, 244, 154, 1, 6, 'F[75]+L[78]->dot_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 74, 244, 188, 5, 11, 'L[76]->rp_nproc+L[77]->rp_nproc');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 74, 244, 188, 5, 10, 'L[76]->rp_nproc+L[77]->rp_nproc');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 74, 244, 188, 5, 9, 'L[76]->rp_nproc+L[77]->rp_nproc');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 74, 244, 188, 5, 8, 'L[76]->rp_nproc+L[77]->rp_nproc');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 74, 244, 188, 5, 7, 'L[76]->rp_nproc+L[77]->rp_nproc');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 74, 244, 188, 5, 6, 'L[76]->rp_nproc+L[77]->rp_nproc');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 74, 244, 176, 4, 11, 'L[76]->desppag+L[77]->desppag');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 74, 244, 176, 4, 10, 'L[76]->desppag+L[77]->desppag');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 74, 244, 176, 4, 9, 'L[76]->desppag+L[77]->desppag');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 74, 244, 176, 4, 8, 'L[76]->desppag+L[77]->desppag');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 74, 244, 176, 4, 7, 'L[76]->desppag+L[77]->desppag');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 74, 244, 176, 4, 6, 'L[76]->desppag+L[77]->desppag');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 74, 244, 156, 3, 11, 'L[76]->liq_atebim+L[77]->liq_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 74, 244, 156, 3, 10, 'L[76]->liq_atebim+L[77]->liq_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 88, 244, 151, 1, 7, 'L[73]->prev_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 88, 244, 151, 1, 6, 'L[73]->prev_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 70, 244, 176, 4, 11, 'F[62]-F[69]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 70, 244, 176, 4, 10, 'F[62]-F[69]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 70, 244, 176, 4, 9, 'F[62]-F[69]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 70, 244, 176, 4, 8, 'F[62]-F[69]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 70, 244, 176, 4, 7, 'F[62]-F[69]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 70, 244, 176, 4, 6, 'F[62]-F[69]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 70, 244, 156, 3, 11, 'F[62]-F[69]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 70, 244, 156, 3, 10, 'F[62]-F[69]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 70, 244, 156, 3, 9, 'F[62]-F[69]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 70, 244, 156, 3, 8, 'F[62]-F[69]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 70, 244, 156, 3, 7, 'F[62]-F[69]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 70, 244, 156, 3, 6, 'F[62]-F[69]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 70, 244, 155, 2, 11, 'F[62]-F[69]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 70, 244, 155, 2, 10, 'F[62]-F[69]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 70, 244, 155, 2, 9, 'F[62]-F[69]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 70, 244, 155, 2, 8, 'F[62]-F[69]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 70, 244, 155, 2, 7, 'F[62]-F[69]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 70, 244, 155, 2, 6, 'F[62]-F[69]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 70, 244, 154, 1, 11, 'F[62]-F[69]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 70, 244, 154, 1, 10, 'F[62]-F[69]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 70, 244, 154, 1, 9, 'F[62]-F[69]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 70, 244, 154, 1, 8, 'F[62]-F[69]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 70, 244, 154, 1, 7, 'F[62]-F[69]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 70, 244, 154, 1, 6, 'F[62]-F[69]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 69, 244, 188, 6, 11, 'F[63]+F[66]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 69, 244, 188, 6, 10, 'F[63]+F[66]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 69, 244, 188, 6, 9, 'F[63]+F[66]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 69, 244, 188, 6, 8, 'F[63]+F[66]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 69, 244, 188, 6, 7, 'F[63]+F[66]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 69, 244, 188, 6, 6, 'F[63]+F[66]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 69, 244, 176, 5, 11, 'F[63]+F[66]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 69, 244, 176, 5, 10, 'F[63]+F[66]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 69, 244, 176, 5, 9, 'F[63]+F[66]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 69, 244, 176, 5, 8, 'F[63]+F[66]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 69, 244, 176, 5, 7, 'F[63]+F[66]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 69, 244, 176, 5, 6, 'F[63]+F[66]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 69, 244, 156, 4, 11, 'F[63]+F[66]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 69, 244, 156, 4, 10, 'F[63]+F[66]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 69, 244, 156, 4, 9, 'F[63]+F[66]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 69, 244, 156, 4, 8, 'F[63]+F[66]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 69, 244, 156, 4, 7, 'F[63]+F[66]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 69, 244, 156, 4, 6, 'F[63]+F[66]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 69, 244, 155, 3, 11, 'F[63]+F[66]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 69, 244, 155, 3, 10, 'F[63]+F[66]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 69, 244, 155, 3, 9, 'F[63]+F[66]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 69, 244, 155, 3, 8, 'F[63]+F[66]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 69, 244, 155, 3, 7, 'F[63]+F[66]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 69, 244, 155, 3, 6, 'F[63]+F[66]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 69, 244, 154, 2, 11, 'F[63]+F[66]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 69, 244, 154, 2, 10, 'F[63]+F[66]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 69, 244, 154, 2, 9, 'F[63]+F[66]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 69, 244, 154, 2, 8, 'F[63]+F[66]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 69, 244, 154, 2, 7, 'F[63]+F[66]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 69, 244, 154, 2, 6, 'F[63]+F[66]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 33, 244, 103, 1, 11, '#dot_ini');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 33, 244, 103, 1, 10, '#dot_ini');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 33, 244, 103, 1, 9, '#dot_ini');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 33, 244, 103, 1, 8, '#dot_ini');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 33, 244, 103, 1, 7, '#dot_ini');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 33, 244, 103, 1, 6, '#dot_ini');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 52, 244, 152, 2, 11, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 52, 244, 152, 2, 10, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 52, 244, 152, 2, 9, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 52, 244, 152, 2, 8, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 52, 244, 152, 2, 7, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 63, 244, 188, 5, 11, 'L[64]->rp_nproc+L[65]->rp_nproc');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 63, 244, 188, 5, 10, 'L[64]->rp_nproc+L[65]->rp_nproc');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 63, 244, 188, 5, 9, 'L[64]->rp_nproc+L[65]->rp_nproc');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 63, 244, 188, 5, 8, 'L[64]->rp_nproc+L[65]->rp_nproc');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 63, 244, 188, 5, 7, 'L[64]->rp_nproc+L[65]->rp_nproc');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 63, 244, 188, 5, 6, 'L[64]->rp_nproc+L[65]->rp_nproc');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 63, 244, 176, 4, 11, 'L[64]->desppag+L[65]->desppag');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 63, 244, 176, 4, 10, 'L[64]->desppag+L[65]->desppag');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 63, 244, 176, 4, 9, 'L[64]->desppag+L[65]->desppag');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 63, 244, 176, 4, 8, 'L[64]->desppag+L[65]->desppag');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 63, 244, 176, 4, 7, 'L[64]->desppag+L[65]->desppag');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 63, 244, 176, 4, 6, 'L[64]->desppag+L[65]->desppag');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 63, 244, 156, 3, 11, 'L[64]->liq_atebim+L[65]->liq_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 63, 244, 156, 3, 10, 'L[64]->liq_atebim+L[65]->liq_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 63, 244, 156, 3, 9, 'L[64]->liq_atebim+L[65]->liq_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 63, 244, 156, 3, 8, 'L[64]->liq_atebim+L[65]->liq_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 63, 244, 156, 3, 7, 'L[64]->liq_atebim+L[65]->liq_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 63, 244, 156, 3, 6, 'L[64]->liq_atebim+L[65]->liq_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 63, 244, 155, 2, 11, 'L[64]->emp_atebim+L[65]->emp_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 63, 244, 155, 2, 10, 'L[64]->emp_atebim+L[65]->emp_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 63, 244, 155, 2, 9, 'L[64]->emp_atebim+L[65]->emp_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 63, 244, 155, 2, 8, 'L[64]->emp_atebim+L[65]->emp_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 63, 244, 155, 2, 7, 'L[64]->emp_atebim+L[65]->emp_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 63, 244, 155, 2, 6, 'L[64]->emp_atebim+L[65]->emp_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 63, 244, 154, 1, 11, 'L[64]->dot_atual+L[65]->dot_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 63, 244, 154, 1, 10, 'L[64]->dot_atual+L[65]->dot_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 63, 244, 154, 1, 9, 'L[64]->dot_atual+L[65]->dot_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 63, 244, 154, 1, 8, 'L[64]->dot_atual+L[65]->dot_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 63, 244, 154, 1, 7, 'L[64]->dot_atual+L[65]->dot_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 63, 244, 154, 1, 6, 'L[64]->dot_atual+L[65]->dot_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 62, 244, 152, 2, 11, 'F[41]+F[58] ');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 62, 244, 152, 2, 10, 'F[41]+F[58] ');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 62, 244, 152, 2, 9, 'F[41]+F[58] ');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 62, 244, 152, 2, 8, 'F[41]+F[58] ');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 62, 244, 152, 2, 7, 'F[41]+F[58] ');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 62, 244, 152, 2, 6, 'F[41]+F[58] ');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 62, 244, 151, 1, 11, 'F[41]+F[58] ');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 62, 244, 151, 1, 10, 'F[41]+F[58] ');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 62, 244, 151, 1, 9, 'F[41]+F[58] ');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 62, 244, 151, 1, 8, 'F[41]+F[58] ');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 62, 244, 151, 1, 7, 'F[41]+F[58] ');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 62, 244, 151, 1, 6, 'F[41]+F[58] ');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 58, 244, 152, 2, 11, 'L[59]->rec_atebim+L[60]->rec_atebim+L[61]->rec_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 58, 244, 152, 2, 10, 'L[59]->rec_atebim+L[60]->rec_atebim+L[61]->rec_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 58, 244, 152, 2, 9, 'L[59]->rec_atebim+L[60]->rec_atebim+L[61]->rec_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 58, 244, 152, 2, 8, 'L[59]->rec_atebim+L[60]->rec_atebim+L[61]->rec_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 58, 244, 152, 2, 7, 'L[59]->rec_atebim+L[60]->rec_atebim+L[61]->rec_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 58, 244, 152, 2, 6, 'L[59]->rec_atebim+L[60]->rec_atebim+L[61]->rec_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 58, 244, 151, 1, 11, 'L[59]->prev_atual+L[60]->prev_atual+L[61]->prev_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 58, 244, 151, 1, 10, 'L[59]->prev_atual+L[60]->prev_atual+L[61]->prev_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 58, 244, 151, 1, 9, 'L[59]->prev_atual+L[60]->prev_atual+L[61]->prev_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 58, 244, 151, 1, 8, 'L[59]->prev_atual+L[60]->prev_atual+L[61]->prev_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 58, 244, 151, 1, 7, 'L[59]->prev_atual+L[60]->prev_atual+L[61]->prev_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 58, 244, 151, 1, 6, 'L[59]->prev_atual+L[60]->prev_atual+L[61]->prev_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 55, 244, 152, 2, 11, 'L[56]->rec_atebim+L[57]->rec_atebim ');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 55, 244, 152, 2, 10, 'L[56]->rec_atebim+L[57]->rec_atebim ');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 55, 244, 152, 2, 9, 'L[56]->rec_atebim+L[57]->rec_atebim ');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 55, 244, 152, 2, 8, 'L[56]->rec_atebim+L[57]->rec_atebim ');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 55, 244, 152, 2, 7, 'L[56]->rec_atebim+L[57]->rec_atebim ');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 55, 244, 152, 2, 6, 'L[56]->rec_atebim+L[57]->rec_atebim ');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 55, 244, 151, 1, 11, 'L[56]->prev_atual+L[57]->prev_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 55, 244, 151, 1, 10, 'L[56]->prev_atual+L[57]->prev_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 55, 244, 151, 1, 9, 'L[56]->prev_atual+L[57]->prev_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 55, 244, 151, 1, 8, 'L[56]->prev_atual+L[57]->prev_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 55, 244, 151, 1, 7, 'L[56]->prev_atual+L[57]->prev_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 55, 244, 151, 1, 6, 'L[56]->prev_atual+L[57]->prev_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 50, 244, 152, 2, 11, 'L[51]->rec_atebim+L[52]->rec_atebim+L[53]->rec_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 50, 244, 152, 2, 10, 'L[51]->rec_atebim+L[52]->rec_atebim+L[53]->rec_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 50, 244, 152, 2, 9, 'L[51]->rec_atebim+L[52]->rec_atebim+L[53]->rec_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 50, 244, 152, 2, 8, 'L[51]->rec_atebim+L[52]->rec_atebim+L[53]->rec_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 50, 244, 152, 2, 7, 'L[51]->rec_atebim+L[52]->rec_atebim+L[53]->rec_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 50, 244, 152, 2, 6, 'L[51]->rec_atebim+L[52]->rec_atebim+L[53]->rec_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 50, 244, 151, 1, 11, 'L[51]->prev_atual+L[52]->prev_atual+L[53]->prev_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 50, 244, 151, 1, 10, 'L[51]->prev_atual+L[52]->prev_atual+L[53]->prev_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 50, 244, 151, 1, 9, 'L[51]->prev_atual+L[52]->prev_atual+L[53]->prev_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 50, 244, 151, 1, 8, 'L[51]->prev_atual+L[52]->prev_atual+L[53]->prev_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 50, 244, 151, 1, 7, 'L[51]->prev_atual+L[52]->prev_atual+L[53]->prev_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 50, 244, 151, 1, 6, 'L[51]->prev_atual+L[52]->prev_atual+L[53]->prev_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 46, 244, 152, 2, 11, 'L[47]->rec_atebim+L[48]->rec_atebim+L[49]->rec_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 46, 244, 152, 2, 10, 'L[47]->rec_atebim+L[48]->rec_atebim+L[49]->rec_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 46, 244, 152, 2, 9, 'L[47]->rec_atebim+L[48]->rec_atebim+L[49]->rec_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 46, 244, 152, 2, 8, 'L[47]->rec_atebim+L[48]->rec_atebim+L[49]->rec_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 46, 244, 152, 2, 7, 'L[47]->rec_atebim+L[48]->rec_atebim+L[49]->rec_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 46, 244, 152, 2, 6, 'L[47]->rec_atebim+L[48]->rec_atebim+L[49]->rec_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 46, 244, 151, 1, 11, 'L[47]->prev_atual+L[48]->prev_atual+L[49]->prev_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 46, 244, 151, 1, 10, 'L[47]->prev_atual+L[48]->prev_atual+L[49]->prev_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 46, 244, 151, 1, 9, 'L[47]->prev_atual+L[48]->prev_atual+L[49]->prev_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 46, 244, 151, 1, 8, 'L[47]->prev_atual+L[48]->prev_atual+L[49]->prev_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 46, 244, 151, 1, 7, 'L[47]->prev_atual+L[48]->prev_atual+L[49]->prev_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 46, 244, 151, 1, 6, 'L[47]->prev_atual+L[48]->prev_atual+L[49]->prev_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 42, 244, 152, 2, 11, 'L[43]->rec_atebim+L[44]->rec_atebim+L[45]->rec_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 42, 244, 152, 2, 10, 'L[43]->rec_atebim+L[44]->rec_atebim+L[45]->rec_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 42, 244, 152, 2, 9, 'L[43]->rec_atebim+L[44]->rec_atebim+L[45]->rec_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 42, 244, 152, 2, 8, 'L[43]->rec_atebim+L[44]->rec_atebim+L[45]->rec_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 42, 244, 152, 2, 7, 'L[43]->rec_atebim+L[44]->rec_atebim+L[45]->rec_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 42, 244, 152, 2, 6, 'L[43]->rec_atebim+L[44]->rec_atebim+L[45]->rec_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 42, 244, 151, 1, 11, 'L[43]->prev_atual+L[44]->prev_atual+L[45]->prev_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 42, 244, 151, 1, 10, 'L[43]->prev_atual+L[44]->prev_atual+L[45]->prev_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 42, 244, 151, 1, 9, 'L[43]->prev_atual+L[44]->prev_atual+L[45]->prev_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 42, 244, 151, 1, 8, 'L[43]->prev_atual+L[44]->prev_atual+L[45]->prev_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 42, 244, 151, 1, 7, 'L[43]->prev_atual+L[44]->prev_atual+L[45]->prev_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 42, 244, 151, 1, 6, 'L[43]->prev_atual+L[44]->prev_atual+L[45]->prev_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 41, 244, 152, 2, 11, 'F[42]+F[46]+F[50]+L[54]->rec_atebim+F[55]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 41, 244, 152, 2, 10, 'F[42]+F[46]+F[50]+L[54]->rec_atebim+F[55]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 41, 244, 152, 2, 9, 'F[42]+F[46]+F[50]+L[54]->rec_atebim+F[55]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 41, 244, 152, 2, 8, 'F[42]+F[46]+F[50]+L[54]->rec_atebim+F[55]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 41, 244, 152, 2, 7, 'F[42]+F[46]+F[50]+L[54]->rec_atebim+F[55]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 41, 244, 152, 2, 6, 'F[42]+F[46]+F[50]+L[54]->rec_atebim+F[55]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 41, 244, 151, 1, 11, 'F[42]+F[46]+F[50]+L[54]->prev_atual+F[55]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 41, 244, 151, 1, 10, 'F[42]+F[46]+F[50]+L[54]->prev_atual+F[55]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 41, 244, 151, 1, 9, 'F[42]+F[46]+F[50]+L[54]->prev_atual+F[55]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 41, 244, 151, 1, 8, 'F[42]+F[46]+F[50]+L[54]->prev_atual+F[55]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 41, 244, 151, 1, 7, 'F[42]+F[46]+F[50]+L[54]->prev_atual+F[55]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 41, 244, 151, 1, 6, 'F[42]+F[46]+F[50]+L[54]->prev_atual+F[55]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 31, 244, 176, 4, 11, 'F[23]-F[30]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 31, 244, 176, 4, 10, 'F[23]-F[30]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 31, 244, 176, 4, 9, 'F[23]-F[30]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 31, 244, 176, 4, 8, 'F[23]-F[30]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 31, 244, 176, 4, 7, 'F[23]-F[30]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 31, 244, 176, 4, 6, 'F[23]-F[30]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 31, 244, 156, 3, 11, 'F[23]-F[30]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 31, 244, 156, 3, 10, 'F[23]-F[30]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 31, 244, 156, 3, 9, 'F[23]-F[30]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 31, 244, 156, 3, 8, 'F[23]-F[30]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 31, 244, 156, 3, 7, 'F[23]-F[30]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 31, 244, 156, 3, 6, 'F[23]-F[30]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 31, 244, 155, 2, 11, 'F[23]-F[30]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 31, 244, 155, 2, 10, 'F[23]-F[30]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 31, 244, 155, 2, 9, 'F[23]-F[30]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 31, 244, 155, 2, 8, 'F[23]-F[30]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 6, 244, 151, 1, 6, 'L[7]->prev_atual+L[8]->prev_atual+L[9]->prev_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 2, 244, 152, 2, 11, 'L[3]->rec_atebim+L[4]->rec_atebim+L[5]->rec_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 2, 244, 152, 2, 10, 'L[3]->rec_atebim+L[4]->rec_atebim+L[5]->rec_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 2, 244, 152, 2, 9, 'L[3]->rec_atebim+L[4]->rec_atebim+L[5]->rec_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 2, 244, 152, 2, 8, 'L[3]->rec_atebim+L[4]->rec_atebim+L[5]->rec_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 2, 244, 152, 2, 7, 'L[3]->rec_atebim+L[4]->rec_atebim+L[5]->rec_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 2, 244, 152, 2, 6, 'L[3]->rec_atebim+L[4]->rec_atebim+L[5]->rec_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 2, 244, 151, 1, 11, 'L[3]->prev_atual+L[4]->prev_atual+L[5]->prev_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 2, 244, 151, 1, 10, 'L[3]->prev_atual+L[4]->prev_atual+L[5]->prev_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 2, 244, 151, 1, 9, 'L[3]->prev_atual+L[4]->prev_atual+L[5]->prev_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 2, 244, 151, 1, 8, 'L[3]->prev_atual+L[4]->prev_atual+L[5]->prev_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 2, 244, 151, 1, 7, 'L[3]->prev_atual+L[4]->prev_atual+L[5]->prev_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 2, 244, 151, 1, 6, 'L[3]->prev_atual+L[4]->prev_atual+L[5]->prev_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 74, 244, 156, 3, 9, 'L[76]->liq_atebim+L[77]->liq_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 74, 244, 156, 3, 8, 'L[76]->liq_atebim+L[77]->liq_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 74, 244, 156, 3, 7, 'L[76]->liq_atebim+L[77]->liq_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 74, 244, 156, 3, 6, 'L[76]->liq_atebim+L[77]->liq_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 74, 244, 155, 2, 11, 'L[76]->emp_atebim+L[77]->emp_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 74, 244, 155, 2, 10, 'L[76]->emp_atebim+L[77]->emp_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 74, 244, 155, 2, 9, 'L[76]->emp_atebim+L[77]->emp_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 74, 244, 155, 2, 8, 'L[76]->emp_atebim+L[77]->emp_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 74, 244, 155, 2, 7, 'L[76]->emp_atebim+L[77]->emp_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 74, 244, 155, 2, 6, 'L[76]->emp_atebim+L[77]->emp_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 74, 244, 154, 1, 11, 'L[76]->dot_atual+L[77]->dot_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 74, 244, 154, 1, 10, 'L[76]->dot_atual+L[77]->dot_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 74, 244, 154, 1, 9, 'L[76]->dot_atual+L[77]->dot_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 74, 244, 154, 1, 8, 'L[76]->dot_atual+L[77]->dot_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 74, 244, 154, 1, 7, 'L[76]->dot_atual+L[77]->dot_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 74, 244, 154, 1, 6, 'L[76]->dot_atual+L[77]->dot_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 88, 244, 152, 2, 11, 'L[73]->rec_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 88, 244, 152, 2, 10, 'L[73]->rec_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 88, 244, 152, 2, 9, 'L[73]->rec_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 88, 244, 152, 2, 8, 'L[73]->rec_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 88, 244, 152, 2, 7, 'L[73]->rec_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 88, 244, 152, 2, 6, 'L[73]->rec_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 88, 244, 151, 1, 11, 'L[73]->prev_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 88, 244, 151, 1, 10, 'L[73]->prev_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 88, 244, 151, 1, 9, 'L[73]->prev_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 88, 244, 151, 1, 8, 'L[73]->prev_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 85, 244, 188, 5, 11, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 85, 244, 188, 5, 10, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 85, 244, 188, 5, 9, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 85, 244, 188, 5, 8, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 85, 244, 188, 5, 7, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 85, 244, 188, 5, 6, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 85, 244, 176, 4, 11, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 85, 244, 176, 4, 10, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 85, 244, 176, 4, 9, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 85, 244, 176, 4, 8, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 85, 244, 176, 4, 7, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 85, 244, 176, 4, 6, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 85, 244, 156, 3, 11, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 85, 244, 156, 3, 10, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 85, 244, 156, 3, 9, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 85, 244, 156, 3, 8, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 85, 244, 156, 3, 7, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 85, 244, 156, 3, 6, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 85, 244, 155, 2, 11, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 85, 244, 155, 2, 10, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 85, 244, 155, 2, 9, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 85, 244, 155, 2, 8, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 85, 244, 155, 2, 7, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 85, 244, 155, 2, 6, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 85, 244, 154, 1, 11, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 85, 244, 154, 1, 10, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 85, 244, 154, 1, 9, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 85, 244, 154, 1, 8, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 85, 244, 154, 1, 7, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 85, 244, 154, 1, 6, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 84, 244, 188, 5, 11, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 84, 244, 188, 5, 10, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 84, 244, 188, 5, 9, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 84, 244, 188, 5, 8, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 84, 244, 188, 5, 7, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 84, 244, 188, 5, 6, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 84, 244, 176, 4, 11, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 84, 244, 176, 4, 10, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 84, 244, 176, 4, 9, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 84, 244, 176, 4, 8, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 84, 244, 176, 4, 7, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 84, 244, 176, 4, 6, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 84, 244, 156, 3, 11, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 84, 244, 156, 3, 10, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 84, 244, 156, 3, 9, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 84, 244, 156, 3, 8, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 84, 244, 156, 3, 7, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 84, 244, 156, 3, 6, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 84, 244, 155, 2, 11, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 84, 244, 155, 2, 10, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 84, 244, 155, 2, 9, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 84, 244, 155, 2, 8, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 84, 244, 155, 2, 7, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 84, 244, 155, 2, 6, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 84, 244, 154, 1, 11, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 84, 244, 154, 1, 10, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 84, 244, 154, 1, 9, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 84, 244, 154, 1, 8, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 84, 244, 154, 1, 7, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 84, 244, 154, 1, 6, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 83, 244, 188, 5, 11, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 83, 244, 188, 5, 10, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 83, 244, 188, 5, 9, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 83, 244, 188, 5, 8, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 83, 244, 188, 5, 7, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 83, 244, 188, 5, 6, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 83, 244, 176, 4, 11, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 83, 244, 176, 4, 10, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 83, 244, 176, 4, 9, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 83, 244, 176, 4, 8, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 83, 244, 176, 4, 7, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 83, 244, 176, 4, 6, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 83, 244, 156, 3, 11, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 83, 244, 156, 3, 10, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 83, 244, 156, 3, 9, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 83, 244, 156, 3, 8, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 83, 244, 156, 3, 7, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 83, 244, 156, 3, 6, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 83, 244, 155, 2, 11, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 83, 244, 155, 2, 10, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 83, 244, 155, 2, 9, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 83, 244, 155, 2, 8, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 83, 244, 155, 2, 7, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 83, 244, 155, 2, 6, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 83, 244, 154, 1, 11, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 83, 244, 154, 1, 10, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 83, 244, 154, 1, 9, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 83, 244, 154, 1, 8, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 83, 244, 154, 1, 7, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 83, 244, 154, 1, 6, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 81, 244, 152, 2, 11, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 81, 244, 152, 2, 10, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 81, 244, 152, 2, 9, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 81, 244, 152, 2, 8, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 81, 244, 152, 2, 7, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 81, 244, 152, 2, 6, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 81, 244, 151, 1, 11, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 81, 244, 151, 1, 10, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 81, 244, 151, 1, 9, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 81, 244, 151, 1, 8, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 81, 244, 151, 1, 7, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 81, 244, 151, 1, 6, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 80, 244, 152, 2, 11, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 80, 244, 152, 2, 10, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 80, 244, 152, 2, 9, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 80, 244, 152, 2, 8, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 80, 244, 152, 2, 7, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 80, 244, 152, 2, 6, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 80, 244, 151, 1, 11, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 80, 244, 151, 1, 10, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 80, 244, 151, 1, 9, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 80, 244, 151, 1, 8, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 80, 244, 151, 1, 7, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 80, 244, 151, 1, 6, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 77, 244, 188, 5, 11, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 77, 244, 188, 5, 10, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 77, 244, 188, 5, 9, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 77, 244, 188, 5, 8, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 77, 244, 188, 5, 7, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 77, 244, 188, 5, 6, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 77, 244, 176, 4, 11, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 77, 244, 176, 4, 10, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 77, 244, 176, 4, 9, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 77, 244, 176, 4, 8, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 77, 244, 176, 4, 7, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 77, 244, 176, 4, 6, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 77, 244, 156, 3, 11, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 77, 244, 156, 3, 10, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 77, 244, 156, 3, 9, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 77, 244, 156, 3, 8, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 77, 244, 156, 3, 7, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 77, 244, 156, 3, 6, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 77, 244, 155, 2, 11, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 77, 244, 155, 2, 10, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 77, 244, 155, 2, 9, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 77, 244, 155, 2, 8, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 77, 244, 155, 2, 7, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 77, 244, 155, 2, 6, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 77, 244, 154, 1, 11, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 77, 244, 154, 1, 10, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 77, 244, 154, 1, 9, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 77, 244, 154, 1, 8, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 77, 244, 154, 1, 7, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 77, 244, 154, 1, 6, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 76, 244, 188, 5, 11, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 76, 244, 188, 5, 10, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 76, 244, 188, 5, 9, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 76, 244, 188, 5, 8, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 76, 244, 188, 5, 7, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 76, 244, 188, 5, 6, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 76, 244, 176, 4, 11, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 76, 244, 176, 4, 10, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 76, 244, 176, 4, 9, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 76, 244, 176, 4, 8, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 76, 244, 176, 4, 7, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 76, 244, 176, 4, 6, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 31, 244, 155, 2, 7, 'F[23]-F[30]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 31, 244, 155, 2, 6, 'F[23]-F[30]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 31, 244, 154, 1, 11, 'F[23]-F[30]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 31, 244, 154, 1, 10, 'F[23]-F[30]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 31, 244, 154, 1, 9, 'F[23]-F[30]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 31, 244, 154, 1, 8, 'F[23]-F[30]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 31, 244, 154, 1, 7, 'F[23]-F[30]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 31, 244, 154, 1, 6, 'F[23]-F[30]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 30, 244, 188, 5, 11, 'F[24]+F[27]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 30, 244, 188, 5, 10, 'F[24]+F[27]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 30, 244, 188, 5, 9, 'F[24]+F[27]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 30, 244, 188, 5, 8, 'F[24]+F[27]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 30, 244, 188, 5, 7, 'F[24]+F[27]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 30, 244, 188, 5, 6, 'F[24]+F[27]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 30, 244, 176, 4, 11, 'F[24]+F[27]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 30, 244, 176, 4, 10, 'F[24]+F[27]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 30, 244, 176, 4, 9, 'F[24]+F[27]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 30, 244, 176, 4, 8, 'F[24]+F[27]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 30, 244, 176, 4, 7, 'F[24]+F[27]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 30, 244, 176, 4, 6, 'F[24]+F[27]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 30, 244, 156, 3, 11, 'F[24]+F[27]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 30, 244, 156, 3, 10, 'F[24]+F[27]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 30, 244, 156, 3, 9, 'F[24]+F[27]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 30, 244, 156, 3, 8, 'F[24]+F[27]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 30, 244, 156, 3, 7, 'F[24]+F[27]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 30, 244, 156, 3, 6, 'F[24]+F[27]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 30, 244, 155, 2, 11, 'F[24]+F[27]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 30, 244, 155, 2, 10, 'F[24]+F[27]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 30, 244, 155, 2, 9, 'F[24]+F[27]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 30, 244, 155, 2, 8, 'F[24]+F[27]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 30, 244, 155, 2, 7, 'F[24]+F[27]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 30, 244, 155, 2, 6, 'F[24]+F[27]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 30, 244, 154, 1, 11, 'F[24]+F[27]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 30, 244, 154, 1, 10, 'F[24]+F[27]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 30, 244, 154, 1, 9, 'F[24]+F[27]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 30, 244, 154, 1, 8, 'F[24]+F[27]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 30, 244, 154, 1, 7, 'F[24]+F[27]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 30, 244, 154, 1, 6, 'F[24]+F[27]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 27, 244, 188, 5, 11, 'L[28]->rp_nproc+L[29]->rp_nproc');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 27, 244, 188, 5, 10, 'L[28]->rp_nproc+L[29]->rp_nproc');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 27, 244, 188, 5, 9, 'L[28]->rp_nproc+L[29]->rp_nproc');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 27, 244, 188, 5, 8, 'L[28]->rp_nproc+L[29]->rp_nproc');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 27, 244, 188, 5, 7, 'L[28]->rp_nproc+L[29]->rp_nproc');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 27, 244, 188, 5, 6, 'L[28]->rp_nproc+L[29]->rp_nproc');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 27, 244, 176, 4, 11, 'L[28]->desppag+L[29]->desppag');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 27, 244, 176, 4, 10, 'L[28]->desppag+L[29]->desppag');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 27, 244, 176, 4, 9, 'L[28]->desppag+L[29]->desppag');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 27, 244, 176, 4, 8, 'L[28]->desppag+L[29]->desppag');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 27, 244, 176, 4, 7, 'L[28]->desppag+L[29]->desppag');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 27, 244, 176, 4, 6, 'L[28]->desppag+L[29]->desppag');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 27, 244, 156, 3, 11, 'L[28]->liq_atebim+L[29]->liq_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 27, 244, 156, 3, 10, 'L[28]->liq_atebim+L[29]->liq_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 27, 244, 156, 3, 9, 'L[28]->liq_atebim+L[29]->liq_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 27, 244, 156, 3, 8, 'L[28]->liq_atebim+L[29]->liq_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 27, 244, 156, 3, 7, 'L[28]->liq_atebim+L[29]->liq_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 27, 244, 156, 3, 6, 'L[28]->liq_atebim+L[29]->liq_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 27, 244, 155, 2, 11, 'L[28]->emp_atebim+L[29]->emp_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 27, 244, 155, 2, 10, 'L[28]->emp_atebim+L[29]->emp_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 27, 244, 155, 2, 9, 'L[28]->emp_atebim+L[29]->emp_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 27, 244, 155, 2, 8, 'L[28]->emp_atebim+L[29]->emp_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 27, 244, 155, 2, 7, 'L[28]->emp_atebim+L[29]->emp_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 27, 244, 155, 2, 6, 'L[28]->emp_atebim+L[29]->emp_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 27, 244, 154, 1, 11, 'L[28]->dot_atual+L[29]->dot_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 27, 244, 154, 1, 10, 'L[28]->dot_atual+L[29]->dot_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 27, 244, 154, 1, 9, 'L[28]->dot_atual+L[29]->dot_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 27, 244, 154, 1, 8, 'L[28]->dot_atual+L[29]->dot_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 27, 244, 154, 1, 7, 'L[28]->dot_atual+L[29]->dot_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 27, 244, 154, 1, 6, 'L[28]->dot_atual+L[29]->dot_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 24, 244, 188, 5, 11, 'L[25]->rp_nproc+L[26]->rp_nproc');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 24, 244, 188, 5, 10, 'L[25]->rp_nproc+L[26]->rp_nproc');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 24, 244, 188, 5, 9, 'L[25]->rp_nproc+L[26]->rp_nproc');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 24, 244, 188, 5, 8, 'L[25]->rp_nproc+L[26]->rp_nproc');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 24, 244, 188, 5, 7, 'L[25]->rp_nproc+L[26]->rp_nproc');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 24, 244, 188, 5, 6, 'L[25]->rp_nproc+L[26]->rp_nproc');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 24, 244, 176, 4, 11, 'L[25]->desppag+L[26]->desppag');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 24, 244, 176, 4, 10, 'L[25]->desppag+L[26]->desppag');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 24, 244, 176, 4, 9, 'L[25]->desppag+L[26]->desppag');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 24, 244, 176, 4, 8, 'L[25]->desppag+L[26]->desppag');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 24, 244, 176, 4, 7, 'L[25]->desppag+L[26]->desppag');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 24, 244, 176, 4, 6, 'L[25]->desppag+L[26]->desppag');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 24, 244, 156, 3, 11, 'L[25]->liq_atebim+L[26]->liq_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 24, 244, 156, 3, 10, 'L[25]->liq_atebim+L[26]->liq_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 24, 244, 156, 3, 9, 'L[25]->liq_atebim+L[26]->liq_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 24, 244, 156, 3, 8, 'L[25]->liq_atebim+L[26]->liq_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 24, 244, 156, 3, 7, 'L[25]->liq_atebim+L[26]->liq_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 24, 244, 156, 3, 6, 'L[25]->liq_atebim+L[26]->liq_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 24, 244, 155, 2, 11, 'L[25]->emp_atebim+L[26]->emp_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 24, 244, 155, 2, 10, 'L[25]->emp_atebim+L[26]->emp_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 24, 244, 155, 2, 9, 'L[25]->emp_atebim+L[26]->emp_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 24, 244, 155, 2, 8, 'L[25]->emp_atebim+L[26]->emp_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 24, 244, 155, 2, 7, 'L[25]->emp_atebim+L[26]->emp_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 24, 244, 155, 2, 6, 'L[25]->emp_atebim+L[26]->emp_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 24, 244, 154, 1, 11, 'L[25]->dot_atual+L[26]->dot_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 24, 244, 154, 1, 10, 'L[25]->dot_atual+L[26]->dot_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 24, 244, 154, 1, 9, 'L[25]->dot_atual+L[26]->dot_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 24, 244, 154, 1, 8, 'L[25]->dot_atual+L[26]->dot_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 24, 244, 154, 1, 7, 'L[25]->dot_atual+L[26]->dot_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 24, 244, 154, 1, 6, 'L[25]->dot_atual+L[26]->dot_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 19, 244, 152, 2, 11, 'L[20]->rec_atebim+L[21]->rec_atebim+L[22]->rec_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 19, 244, 152, 2, 10, 'L[20]->rec_atebim+L[21]->rec_atebim+L[22]->rec_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 19, 244, 152, 2, 9, 'L[20]->rec_atebim+L[21]->rec_atebim+L[22]->rec_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 19, 244, 152, 2, 8, 'L[20]->rec_atebim+L[21]->rec_atebim+L[22]->rec_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 19, 244, 152, 2, 7, 'L[20]->rec_atebim+L[21]->rec_atebim+L[22]->rec_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 19, 244, 152, 2, 6, 'L[20]->rec_atebim+L[21]->rec_atebim+L[22]->rec_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 19, 244, 151, 1, 11, 'L[20]->prev_atual+L[21]->prev_atual+L[22]->prev_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 19, 244, 151, 1, 10, 'L[20]->prev_atual+L[21]->prev_atual+L[22]->prev_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 19, 244, 151, 1, 9, 'L[20]->prev_atual+L[21]->prev_atual+L[22]->prev_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 19, 244, 151, 1, 8, 'L[20]->prev_atual+L[21]->prev_atual+L[22]->prev_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 19, 244, 151, 1, 7, 'L[20]->prev_atual+L[21]->prev_atual+L[22]->prev_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 19, 244, 151, 1, 6, 'L[20]->prev_atual+L[21]->prev_atual+L[22]->prev_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 15, 244, 152, 2, 11, 'L[16]->rec_atebim+L[17]->rec_atebim+L[18]->rec_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 15, 244, 152, 2, 10, 'L[16]->rec_atebim+L[17]->rec_atebim+L[18]->rec_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 15, 244, 152, 2, 9, 'L[16]->rec_atebim+L[17]->rec_atebim+L[18]->rec_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 15, 244, 152, 2, 8, 'L[16]->rec_atebim+L[17]->rec_atebim+L[18]->rec_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 15, 244, 152, 2, 7, 'L[16]->rec_atebim+L[17]->rec_atebim+L[18]->rec_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 15, 244, 152, 2, 6, 'L[16]->rec_atebim+L[17]->rec_atebim+L[18]->rec_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 15, 244, 151, 1, 11, 'L[16]->prev_atual+L[17]->prev_atual+L[18]->prev_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 15, 244, 151, 1, 10, 'L[16]->prev_atual+L[17]->prev_atual+L[18]->prev_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 15, 244, 151, 1, 9, 'L[16]->prev_atual+L[17]->prev_atual+L[18]->prev_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 15, 244, 151, 1, 8, 'L[16]->prev_atual+L[17]->prev_atual+L[18]->prev_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 15, 244, 151, 1, 7, 'L[16]->prev_atual+L[17]->prev_atual+L[18]->prev_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 15, 244, 151, 1, 6, 'L[16]->prev_atual+L[17]->prev_atual+L[18]->prev_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 10, 244, 152, 2, 11, 'L[11]->rec_atebim+L[12]->rec_atebim+L[13]->rec_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 10, 244, 152, 2, 10, 'L[11]->rec_atebim+L[12]->rec_atebim+L[13]->rec_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 10, 244, 152, 2, 9, 'L[11]->rec_atebim+L[12]->rec_atebim+L[13]->rec_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 10, 244, 152, 2, 8, 'L[11]->rec_atebim+L[12]->rec_atebim+L[13]->rec_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 10, 244, 152, 2, 7, 'L[11]->rec_atebim+L[12]->rec_atebim+L[13]->rec_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 10, 244, 152, 2, 6, 'L[11]->rec_atebim+L[12]->rec_atebim+L[13]->rec_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 10, 244, 151, 1, 11, 'L[11]->prev_atual+L[12]->prev_atual+L[13]->prev_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 10, 244, 151, 1, 10, 'L[11]->prev_atual+L[12]->prev_atual+L[13]->prev_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 10, 244, 151, 1, 9, 'L[11]->prev_atual+L[12]->prev_atual+L[13]->prev_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 10, 244, 151, 1, 8, 'L[11]->prev_atual+L[12]->prev_atual+L[13]->prev_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 10, 244, 151, 1, 7, 'L[11]->prev_atual+L[12]->prev_atual+L[13]->prev_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 10, 244, 151, 1, 6, 'L[11]->prev_atual+L[12]->prev_atual+L[13]->prev_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 6, 244, 152, 2, 11, 'L[7]->rec_atebim+L[8]->rec_atebim+L[9]->rec_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 6, 244, 152, 2, 10, 'L[7]->rec_atebim+L[8]->rec_atebim+L[9]->rec_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 6, 244, 152, 2, 9, 'L[7]->rec_atebim+L[8]->rec_atebim+L[9]->rec_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 6, 244, 152, 2, 8, 'L[7]->rec_atebim+L[8]->rec_atebim+L[9]->rec_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 6, 244, 152, 2, 7, 'L[7]->rec_atebim+L[8]->rec_atebim+L[9]->rec_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 6, 244, 152, 2, 6, 'L[7]->rec_atebim+L[8]->rec_atebim+L[9]->rec_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 6, 244, 151, 1, 11, 'L[7]->prev_atual+L[8]->prev_atual+L[9]->prev_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 6, 244, 151, 1, 10, 'L[7]->prev_atual+L[8]->prev_atual+L[9]->prev_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 6, 244, 151, 1, 9, 'L[7]->prev_atual+L[8]->prev_atual+L[9]->prev_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 6, 244, 151, 1, 8, 'L[7]->prev_atual+L[8]->prev_atual+L[9]->prev_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 6, 244, 151, 1, 7, 'L[7]->prev_atual+L[8]->prev_atual+L[9]->prev_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 76, 244, 156, 3, 11, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 76, 244, 156, 3, 10, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 76, 244, 156, 3, 9, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 76, 244, 156, 3, 8, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 76, 244, 156, 3, 7, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 76, 244, 156, 3, 6, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 76, 244, 155, 2, 11, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 76, 244, 155, 2, 10, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 76, 244, 155, 2, 9, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 76, 244, 155, 2, 8, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 76, 244, 155, 2, 7, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 76, 244, 155, 2, 6, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 76, 244, 154, 1, 11, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 76, 244, 154, 1, 10, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 76, 244, 154, 1, 9, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 76, 244, 154, 1, 8, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 76, 244, 154, 1, 7, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 76, 244, 154, 1, 6, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 75, 244, 188, 5, 11, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 75, 244, 188, 5, 10, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 75, 244, 188, 5, 9, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 75, 244, 188, 5, 8, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 75, 244, 188, 5, 7, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 75, 244, 188, 5, 6, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 75, 244, 176, 4, 11, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 75, 244, 176, 4, 10, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 75, 244, 176, 4, 9, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 75, 244, 176, 4, 8, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 75, 244, 176, 4, 7, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 75, 244, 176, 4, 6, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 75, 244, 156, 3, 11, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 75, 244, 156, 3, 10, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 75, 244, 156, 3, 9, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 75, 244, 156, 3, 8, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 75, 244, 156, 3, 7, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 75, 244, 156, 3, 6, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 75, 244, 155, 2, 11, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 75, 244, 155, 2, 10, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 75, 244, 155, 2, 9, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 75, 244, 155, 2, 8, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 75, 244, 155, 2, 7, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 75, 244, 155, 2, 6, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 75, 244, 154, 1, 11, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 75, 244, 154, 1, 10, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 75, 244, 154, 1, 9, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 75, 244, 154, 1, 8, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 75, 244, 154, 1, 7, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 75, 244, 154, 1, 6, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 73, 244, 152, 2, 11, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 73, 244, 152, 2, 10, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 73, 244, 152, 2, 9, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 73, 244, 152, 2, 8, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 73, 244, 152, 2, 7, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 73, 244, 152, 2, 6, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 73, 244, 151, 1, 11, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 73, 244, 151, 1, 10, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 73, 244, 151, 1, 9, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 73, 244, 151, 1, 8, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 73, 244, 151, 1, 7, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 73, 244, 151, 1, 6, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 72, 244, 103, 1, 11, '#saldo_final');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 72, 244, 103, 1, 10, '#saldo_final');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 72, 244, 103, 1, 9, '#saldo_final');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 72, 244, 103, 1, 8, '#saldo_final');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 72, 244, 103, 1, 7, '#saldo_final');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 72, 244, 103, 1, 6, '#saldo_final');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 71, 244, 103, 1, 11, '#saldo_final');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 71, 244, 103, 1, 10, '#saldo_final');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 71, 244, 103, 1, 9, '#saldo_final');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 71, 244, 103, 1, 8, '#saldo_final');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 71, 244, 103, 1, 7, '#saldo_final');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 71, 244, 103, 1, 6, '#saldo_final');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 68, 244, 188, 5, 11, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 54, 244, 152, 2, 8, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 54, 244, 152, 2, 7, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 54, 244, 152, 2, 6, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 54, 244, 151, 1, 11, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 54, 244, 151, 1, 10, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 54, 244, 151, 1, 9, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 54, 244, 151, 1, 8, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 54, 244, 151, 1, 7, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 54, 244, 151, 1, 6, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 53, 244, 152, 2, 11, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 53, 244, 152, 2, 10, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 53, 244, 152, 2, 9, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 53, 244, 152, 2, 8, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 53, 244, 152, 2, 7, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 53, 244, 152, 2, 6, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 53, 244, 151, 1, 11, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 53, 244, 151, 1, 10, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 53, 244, 151, 1, 9, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 53, 244, 151, 1, 8, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 53, 244, 151, 1, 7, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 53, 244, 151, 1, 6, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 52, 244, 151, 1, 11, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 52, 244, 151, 1, 10, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 52, 244, 151, 1, 9, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 52, 244, 151, 1, 8, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 52, 244, 151, 1, 7, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 52, 244, 151, 1, 6, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 51, 244, 152, 2, 11, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 51, 244, 152, 2, 10, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 51, 244, 152, 2, 9, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 51, 244, 152, 2, 8, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 51, 244, 152, 2, 7, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 51, 244, 152, 2, 6, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 51, 244, 151, 1, 11, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 51, 244, 151, 1, 10, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 51, 244, 151, 1, 9, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 51, 244, 151, 1, 8, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 51, 244, 151, 1, 7, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 51, 244, 151, 1, 6, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 49, 244, 152, 2, 11, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 49, 244, 152, 2, 10, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 49, 244, 152, 2, 9, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 49, 244, 152, 2, 8, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 49, 244, 152, 2, 7, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 49, 244, 152, 2, 6, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 49, 244, 151, 1, 11, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 49, 244, 151, 1, 10, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 49, 244, 151, 1, 9, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 49, 244, 151, 1, 8, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 49, 244, 151, 1, 7, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 49, 244, 151, 1, 6, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 48, 244, 152, 2, 11, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 48, 244, 152, 2, 10, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 48, 244, 152, 2, 9, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 48, 244, 152, 2, 8, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 48, 244, 152, 2, 7, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 48, 244, 152, 2, 6, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 48, 244, 151, 1, 11, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 48, 244, 151, 1, 10, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 48, 244, 151, 1, 9, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 48, 244, 151, 1, 8, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 48, 244, 151, 1, 7, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 48, 244, 151, 1, 6, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 47, 244, 152, 2, 11, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 47, 244, 152, 2, 10, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 47, 244, 152, 2, 9, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 47, 244, 152, 2, 8, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 47, 244, 152, 2, 7, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 47, 244, 152, 2, 6, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 47, 244, 151, 1, 11, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 47, 244, 151, 1, 10, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 47, 244, 151, 1, 9, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 47, 244, 151, 1, 8, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 47, 244, 151, 1, 7, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 47, 244, 151, 1, 6, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 45, 244, 152, 2, 11, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 45, 244, 152, 2, 10, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 45, 244, 152, 2, 9, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 45, 244, 152, 2, 8, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 45, 244, 152, 2, 7, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 45, 244, 152, 2, 6, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 45, 244, 151, 1, 11, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 45, 244, 151, 1, 10, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 45, 244, 151, 1, 9, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 45, 244, 151, 1, 8, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 45, 244, 151, 1, 7, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 45, 244, 151, 1, 6, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 44, 244, 152, 2, 11, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 44, 244, 152, 2, 10, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 44, 244, 152, 2, 9, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 44, 244, 152, 2, 8, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 44, 244, 152, 2, 7, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 44, 244, 152, 2, 6, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 44, 244, 151, 1, 11, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 44, 244, 151, 1, 10, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 44, 244, 151, 1, 9, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 44, 244, 151, 1, 8, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 44, 244, 151, 1, 7, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 44, 244, 151, 1, 6, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 43, 244, 152, 2, 11, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 43, 244, 152, 2, 10, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 43, 244, 152, 2, 9, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 43, 244, 152, 2, 8, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 43, 244, 152, 2, 7, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 43, 244, 152, 2, 6, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 40, 244, 177, 1, 11, '#saldo_final');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 40, 244, 177, 1, 10, '#saldo_final');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 40, 244, 177, 1, 9, '#saldo_final');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 40, 244, 177, 1, 8, '#saldo_final');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 40, 244, 177, 1, 7, '#saldo_final');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 40, 244, 177, 1, 6, '#saldo_final');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 39, 244, 177, 1, 11, '#saldo_final');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 39, 244, 177, 1, 10, '#saldo_final');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 39, 244, 177, 1, 9, '#saldo_final');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 39, 244, 177, 1, 8, '#saldo_final');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 39, 244, 177, 1, 7, '#saldo_final');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 39, 244, 177, 1, 6, '#saldo_final');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 38, 244, 177, 1, 11, '#saldo_final');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 38, 244, 177, 1, 10, '#saldo_final');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 38, 244, 177, 1, 9, '#saldo_final');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 38, 244, 177, 1, 8, '#saldo_final');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 38, 244, 177, 1, 7, '#saldo_final');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 38, 244, 177, 1, 6, '#saldo_final');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 37, 244, 103, 1, 11, '#saldo_final');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 37, 244, 103, 1, 10, '#saldo_final');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 37, 244, 103, 1, 9, '#saldo_final');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 37, 244, 103, 1, 8, '#saldo_final');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 37, 244, 103, 1, 7, '#saldo_final');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 37, 244, 103, 1, 6, '#saldo_final');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 36, 244, 103, 1, 11, '#saldo_final');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 36, 244, 103, 1, 10, '#saldo_final');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 36, 244, 103, 1, 9, '#saldo_final');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 36, 244, 103, 1, 8, '#saldo_final');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 36, 244, 103, 1, 7, '#saldo_final');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 36, 244, 103, 1, 6, '#saldo_final');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 35, 244, 103, 1, 11, '#saldo_final');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 35, 244, 103, 1, 10, '#saldo_final');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 35, 244, 103, 1, 9, '#saldo_final');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 35, 244, 103, 1, 8, '#saldo_final');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 35, 244, 103, 1, 7, '#saldo_final');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 35, 244, 103, 1, 6, '#saldo_final');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 34, 244, 103, 1, 11, '#saldo_final');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 34, 244, 103, 1, 10, '#saldo_final');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 34, 244, 103, 1, 9, '#saldo_final');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 34, 244, 103, 1, 8, '#saldo_final');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 34, 244, 103, 1, 7, '#saldo_final');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 34, 244, 103, 1, 6, '#saldo_final');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 32, 244, 103, 1, 11, '#saldo_inicial');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 32, 244, 103, 1, 10, '#saldo_inicial');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 32, 244, 103, 1, 9, '#saldo_inicial');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 29, 244, 176, 4, 8, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 29, 244, 176, 4, 7, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 29, 244, 176, 4, 6, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 29, 244, 156, 3, 11, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 29, 244, 156, 3, 10, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 29, 244, 156, 3, 9, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 29, 244, 156, 3, 8, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 29, 244, 156, 3, 7, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 29, 244, 156, 3, 6, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 29, 244, 155, 2, 11, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 29, 244, 155, 2, 10, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 29, 244, 155, 2, 9, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 29, 244, 155, 2, 8, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 29, 244, 155, 2, 7, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 29, 244, 155, 2, 6, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 29, 244, 154, 1, 11, '#dot_ini+#suplementado_acumulado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 29, 244, 154, 1, 10, '#dot_ini+#suplementado_acumulado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 29, 244, 154, 1, 9, '#dot_ini+#suplementado_acumulado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 29, 244, 154, 1, 8, '#dot_ini+#suplementado_acumulado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 29, 244, 154, 1, 7, '#dot_ini+#suplementado_acumulado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 29, 244, 154, 1, 6, '#dot_ini+#suplementado_acumulado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 28, 244, 188, 5, 11, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 28, 244, 188, 5, 10, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 28, 244, 188, 5, 9, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 28, 244, 188, 5, 8, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 28, 244, 188, 5, 7, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 28, 244, 188, 5, 6, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 28, 244, 176, 4, 11, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 28, 244, 176, 4, 10, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 28, 244, 176, 4, 9, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 28, 244, 176, 4, 8, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 28, 244, 176, 4, 7, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 28, 244, 176, 4, 6, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 28, 244, 156, 3, 11, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 28, 244, 156, 3, 10, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 28, 244, 156, 3, 9, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 28, 244, 156, 3, 8, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 28, 244, 156, 3, 7, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 28, 244, 156, 3, 6, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 28, 244, 155, 2, 11, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 28, 244, 155, 2, 10, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 28, 244, 155, 2, 9, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 28, 244, 155, 2, 8, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 28, 244, 155, 2, 7, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 28, 244, 155, 2, 6, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 68, 244, 188, 5, 10, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 68, 244, 188, 5, 9, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 68, 244, 188, 5, 8, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 68, 244, 188, 5, 7, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 68, 244, 188, 5, 6, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 68, 244, 176, 4, 11, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 68, 244, 176, 4, 10, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 68, 244, 176, 4, 9, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 68, 244, 176, 4, 8, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 68, 244, 176, 4, 7, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 68, 244, 176, 4, 6, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 68, 244, 156, 3, 11, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 68, 244, 156, 3, 10, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 68, 244, 156, 3, 9, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 68, 244, 156, 3, 8, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 68, 244, 156, 3, 7, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 68, 244, 156, 3, 6, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 68, 244, 155, 2, 11, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 68, 244, 155, 2, 10, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 68, 244, 155, 2, 9, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 68, 244, 155, 2, 8, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 68, 244, 155, 2, 7, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 68, 244, 155, 2, 6, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 68, 244, 154, 1, 11, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 68, 244, 154, 1, 10, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 68, 244, 154, 1, 9, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 68, 244, 154, 1, 8, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 68, 244, 154, 1, 7, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 68, 244, 154, 1, 6, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 67, 244, 188, 5, 11, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 67, 244, 188, 5, 10, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 67, 244, 188, 5, 9, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 67, 244, 188, 5, 8, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 67, 244, 188, 5, 7, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 67, 244, 188, 5, 6, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 67, 244, 176, 4, 11, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 67, 244, 176, 4, 10, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 67, 244, 176, 4, 9, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 67, 244, 176, 4, 8, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 67, 244, 176, 4, 7, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 67, 244, 176, 4, 6, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 67, 244, 156, 3, 11, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 67, 244, 156, 3, 10, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 67, 244, 156, 3, 9, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 67, 244, 156, 3, 8, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 67, 244, 156, 3, 7, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 67, 244, 156, 3, 6, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 67, 244, 155, 2, 11, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 67, 244, 155, 2, 10, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 67, 244, 155, 2, 9, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 67, 244, 155, 2, 8, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 67, 244, 155, 2, 7, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 67, 244, 155, 2, 6, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 67, 244, 154, 1, 11, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 67, 244, 154, 1, 10, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 67, 244, 154, 1, 9, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 67, 244, 154, 1, 8, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 67, 244, 154, 1, 7, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 67, 244, 154, 1, 6, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 65, 244, 188, 5, 11, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 65, 244, 188, 5, 10, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 65, 244, 188, 5, 9, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 65, 244, 188, 5, 8, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 65, 244, 188, 5, 7, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 65, 244, 188, 5, 6, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 65, 244, 176, 4, 11, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 65, 244, 176, 4, 10, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 65, 244, 176, 4, 9, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 65, 244, 176, 4, 8, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 65, 244, 176, 4, 7, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 65, 244, 176, 4, 6, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 65, 244, 156, 3, 11, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 65, 244, 156, 3, 10, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 65, 244, 156, 3, 9, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 65, 244, 156, 3, 8, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 65, 244, 156, 3, 7, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 65, 244, 156, 3, 6, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 65, 244, 155, 2, 11, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 65, 244, 155, 2, 10, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 65, 244, 155, 2, 9, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 65, 244, 155, 2, 8, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 65, 244, 155, 2, 7, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 65, 244, 155, 2, 6, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 65, 244, 154, 1, 11, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 65, 244, 154, 1, 10, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 65, 244, 154, 1, 9, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 65, 244, 154, 1, 8, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 65, 244, 154, 1, 7, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 65, 244, 154, 1, 6, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 64, 244, 188, 5, 11, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 64, 244, 188, 5, 10, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 64, 244, 188, 5, 9, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 64, 244, 188, 5, 8, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 64, 244, 188, 5, 7, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 64, 244, 188, 5, 6, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 64, 244, 176, 4, 11, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 64, 244, 176, 4, 10, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 64, 244, 176, 4, 9, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 64, 244, 176, 4, 8, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 64, 244, 176, 4, 7, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 64, 244, 176, 4, 6, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 64, 244, 156, 3, 11, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 64, 244, 156, 3, 10, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 64, 244, 156, 3, 9, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 64, 244, 156, 3, 8, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 64, 244, 156, 3, 7, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 64, 244, 156, 3, 6, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 64, 244, 155, 2, 11, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 64, 244, 155, 2, 10, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 64, 244, 155, 2, 9, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 64, 244, 155, 2, 8, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 64, 244, 155, 2, 7, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 64, 244, 155, 2, 6, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 64, 244, 154, 1, 11, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 64, 244, 154, 1, 10, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 64, 244, 154, 1, 9, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 64, 244, 154, 1, 8, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 64, 244, 154, 1, 7, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 64, 244, 154, 1, 6, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 61, 244, 152, 2, 11, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 61, 244, 152, 2, 10, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 61, 244, 152, 2, 9, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 61, 244, 152, 2, 8, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 61, 244, 152, 2, 7, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 61, 244, 152, 2, 6, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 61, 244, 151, 1, 11, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 61, 244, 151, 1, 10, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 61, 244, 151, 1, 9, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 61, 244, 151, 1, 8, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 61, 244, 151, 1, 7, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 61, 244, 151, 1, 6, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 60, 244, 152, 2, 11, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 60, 244, 152, 2, 10, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 60, 244, 152, 2, 9, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 60, 244, 152, 2, 8, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 60, 244, 152, 2, 7, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 60, 244, 152, 2, 6, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 60, 244, 151, 1, 11, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 60, 244, 151, 1, 10, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 60, 244, 151, 1, 9, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 60, 244, 151, 1, 8, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 60, 244, 151, 1, 7, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 60, 244, 151, 1, 6, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 59, 244, 152, 2, 11, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 59, 244, 152, 2, 10, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 59, 244, 152, 2, 9, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 59, 244, 152, 2, 8, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 59, 244, 152, 2, 7, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 59, 244, 152, 2, 6, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 59, 244, 151, 1, 11, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 59, 244, 151, 1, 10, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 59, 244, 151, 1, 9, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 59, 244, 151, 1, 8, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 59, 244, 151, 1, 7, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 59, 244, 151, 1, 6, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 57, 244, 152, 2, 11, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 57, 244, 152, 2, 10, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 57, 244, 152, 2, 9, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 57, 244, 152, 2, 8, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 57, 244, 152, 2, 7, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 57, 244, 152, 2, 6, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 57, 244, 151, 1, 11, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 57, 244, 151, 1, 10, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 57, 244, 151, 1, 9, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 57, 244, 151, 1, 8, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 57, 244, 151, 1, 7, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 57, 244, 151, 1, 6, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 56, 244, 152, 2, 11, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 56, 244, 152, 2, 10, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 56, 244, 152, 2, 9, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 56, 244, 152, 2, 8, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 56, 244, 152, 2, 7, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 56, 244, 152, 2, 6, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 56, 244, 151, 1, 11, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 56, 244, 151, 1, 10, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 56, 244, 151, 1, 9, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 56, 244, 151, 1, 8, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 56, 244, 151, 1, 7, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 56, 244, 151, 1, 6, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 54, 244, 152, 2, 11, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 54, 244, 152, 2, 10, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 54, 244, 152, 2, 9, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 26, 244, 188, 5, 11, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 26, 244, 188, 5, 10, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 26, 244, 188, 5, 9, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 26, 244, 188, 5, 8, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 26, 244, 188, 5, 7, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 26, 244, 188, 5, 6, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 26, 244, 176, 4, 11, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 26, 244, 176, 4, 10, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 26, 244, 176, 4, 9, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 26, 244, 176, 4, 8, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 26, 244, 176, 4, 7, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 26, 244, 176, 4, 6, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 26, 244, 156, 3, 11, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 26, 244, 156, 3, 10, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 26, 244, 156, 3, 9, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 26, 244, 156, 3, 8, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 26, 244, 156, 3, 7, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 26, 244, 156, 3, 6, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 26, 244, 155, 2, 11, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 26, 244, 155, 2, 10, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 26, 244, 155, 2, 9, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 26, 244, 155, 2, 8, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 26, 244, 155, 2, 7, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 26, 244, 155, 2, 6, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 26, 244, 154, 1, 11, '#dot_ini+#suplementado_acumulado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 26, 244, 154, 1, 10, '#dot_ini+#suplementado_acumulado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 26, 244, 154, 1, 9, '#dot_ini+#suplementado_acumulado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 26, 244, 154, 1, 8, '#dot_ini+#suplementado_acumulado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 26, 244, 154, 1, 7, '#dot_ini+#suplementado_acumulado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 26, 244, 154, 1, 6, '#dot_ini+#suplementado_acumulado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 25, 244, 176, 5, 11, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 25, 244, 176, 5, 10, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 25, 244, 176, 5, 9, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 25, 244, 176, 5, 8, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 25, 244, 176, 5, 7, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 25, 244, 176, 5, 6, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 25, 244, 188, 6, 11, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 25, 244, 188, 6, 10, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 25, 244, 188, 6, 9, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 25, 244, 188, 6, 8, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 25, 244, 188, 6, 7, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 25, 244, 188, 6, 6, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 25, 244, 156, 3, 11, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 25, 244, 156, 3, 10, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 25, 244, 156, 3, 9, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 25, 244, 156, 3, 8, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 25, 244, 156, 3, 7, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 25, 244, 156, 3, 6, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 25, 244, 155, 2, 11, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 25, 244, 155, 2, 10, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 25, 244, 155, 2, 9, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 25, 244, 155, 2, 8, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 25, 244, 155, 2, 7, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 25, 244, 155, 2, 6, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 22, 244, 152, 2, 11, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 22, 244, 152, 2, 10, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 22, 244, 152, 2, 9, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 22, 244, 152, 2, 8, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 22, 244, 152, 2, 7, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 22, 244, 152, 2, 6, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 22, 244, 151, 1, 11, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 22, 244, 151, 1, 10, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 22, 244, 151, 1, 9, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 22, 244, 151, 1, 8, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 22, 244, 151, 1, 7, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 22, 244, 151, 1, 6, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 21, 244, 152, 2, 11, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 21, 244, 152, 2, 10, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 21, 244, 152, 2, 9, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 21, 244, 152, 2, 8, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 21, 244, 152, 2, 7, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 21, 244, 152, 2, 6, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 21, 244, 151, 1, 11, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 21, 244, 151, 1, 10, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 21, 244, 151, 1, 9, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 21, 244, 151, 1, 8, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 21, 244, 151, 1, 7, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 21, 244, 151, 1, 6, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 20, 244, 152, 2, 11, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 20, 244, 152, 2, 10, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 20, 244, 152, 2, 9, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 20, 244, 152, 2, 8, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 20, 244, 152, 2, 7, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 20, 244, 152, 2, 6, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 20, 244, 151, 1, 11, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 20, 244, 151, 1, 10, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 20, 244, 151, 1, 9, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 20, 244, 151, 1, 8, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 20, 244, 151, 1, 7, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 20, 244, 151, 1, 6, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 18, 244, 152, 2, 11, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 18, 244, 152, 2, 10, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 18, 244, 152, 2, 9, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 18, 244, 152, 2, 8, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 18, 244, 152, 2, 7, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 18, 244, 152, 2, 6, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 18, 244, 151, 1, 11, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 18, 244, 151, 1, 10, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 18, 244, 151, 1, 9, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 18, 244, 151, 1, 8, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 18, 244, 151, 1, 7, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 18, 244, 151, 1, 6, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 17, 244, 152, 2, 11, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 17, 244, 152, 2, 10, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 17, 244, 152, 2, 9, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 17, 244, 152, 2, 8, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 17, 244, 152, 2, 7, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 17, 244, 152, 2, 6, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 17, 244, 151, 1, 11, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 17, 244, 151, 1, 10, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 17, 244, 151, 1, 9, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 17, 244, 151, 1, 8, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 17, 244, 151, 1, 7, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 17, 244, 151, 1, 6, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 16, 244, 152, 2, 11, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 16, 244, 152, 2, 10, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 16, 244, 152, 2, 9, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 16, 244, 152, 2, 8, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 16, 244, 152, 2, 7, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 16, 244, 152, 2, 6, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 16, 244, 151, 1, 11, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 16, 244, 151, 1, 10, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 16, 244, 151, 1, 9, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 16, 244, 151, 1, 8, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 16, 244, 151, 1, 7, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 16, 244, 151, 1, 6, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 32, 244, 103, 1, 8, '#saldo_inicial');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 32, 244, 103, 1, 7, '#saldo_inicial');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 32, 244, 103, 1, 6, '#saldo_inicial');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 29, 244, 188, 5, 11, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 29, 244, 188, 5, 10, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 29, 244, 188, 5, 9, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 29, 244, 188, 5, 8, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 29, 244, 188, 5, 7, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 29, 244, 188, 5, 6, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 29, 244, 176, 4, 11, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 29, 244, 176, 4, 10, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 29, 244, 176, 4, 9, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 14, 244, 152, 2, 11, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 14, 244, 152, 2, 10, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 14, 244, 152, 2, 9, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 14, 244, 152, 2, 8, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 14, 244, 152, 2, 7, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 14, 244, 152, 2, 6, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 14, 244, 151, 1, 11, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 14, 244, 151, 1, 10, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 14, 244, 151, 1, 9, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 14, 244, 151, 1, 8, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 14, 244, 151, 1, 7, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 14, 244, 151, 1, 6, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 13, 244, 152, 2, 11, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 13, 244, 152, 2, 10, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 13, 244, 152, 2, 9, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 13, 244, 152, 2, 8, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 13, 244, 152, 2, 7, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 13, 244, 152, 2, 6, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 13, 244, 151, 1, 11, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 13, 244, 151, 1, 10, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 13, 244, 151, 1, 9, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 13, 244, 151, 1, 8, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 13, 244, 151, 1, 7, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 13, 244, 151, 1, 6, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 12, 244, 152, 2, 11, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 12, 244, 152, 2, 10, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 12, 244, 152, 2, 9, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 12, 244, 152, 2, 8, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 12, 244, 152, 2, 7, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 12, 244, 152, 2, 6, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 12, 244, 151, 1, 11, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 12, 244, 151, 1, 10, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 12, 244, 151, 1, 9, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 12, 244, 151, 1, 8, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 12, 244, 151, 1, 7, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 12, 244, 151, 1, 6, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 11, 244, 152, 2, 11, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 11, 244, 152, 2, 10, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 11, 244, 152, 2, 9, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 11, 244, 152, 2, 8, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 11, 244, 152, 2, 7, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 11, 244, 152, 2, 6, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 11, 244, 151, 1, 11, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 11, 244, 151, 1, 10, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 11, 244, 151, 1, 9, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 11, 244, 151, 1, 8, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 11, 244, 151, 1, 7, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 11, 244, 151, 1, 6, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 9, 244, 152, 2, 11, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 9, 244, 152, 2, 10, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 9, 244, 152, 2, 9, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 9, 244, 152, 2, 8, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 9, 244, 152, 2, 7, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 9, 244, 152, 2, 6, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 9, 244, 151, 1, 11, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 9, 244, 151, 1, 10, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 9, 244, 151, 1, 9, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 9, 244, 151, 1, 8, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 9, 244, 151, 1, 7, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 9, 244, 151, 1, 6, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 8, 244, 151, 1, 11, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 8, 244, 151, 1, 10, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 8, 244, 151, 1, 9, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 8, 244, 151, 1, 8, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 8, 244, 151, 1, 7, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 8, 244, 151, 1, 6, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 7, 244, 152, 2, 11, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 7, 244, 152, 2, 10, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 7, 244, 152, 2, 9, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 7, 244, 152, 2, 8, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 7, 244, 152, 2, 7, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 7, 244, 152, 2, 6, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 7, 244, 151, 1, 11, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 7, 244, 151, 1, 10, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 7, 244, 151, 1, 9, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 7, 244, 151, 1, 8, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 7, 244, 151, 1, 7, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 7, 244, 151, 1, 6, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 5, 244, 152, 2, 11, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 5, 244, 152, 2, 10, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 5, 244, 152, 2, 9, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 5, 244, 152, 2, 8, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 5, 244, 152, 2, 7, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 5, 244, 152, 2, 6, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 5, 244, 151, 1, 11, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 5, 244, 151, 1, 10, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 5, 244, 151, 1, 9, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 5, 244, 151, 1, 8, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 5, 244, 151, 1, 7, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 5, 244, 151, 1, 6, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 4, 244, 152, 2, 11, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 4, 244, 152, 2, 10, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 4, 244, 152, 2, 9, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 4, 244, 152, 2, 8, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 4, 244, 152, 2, 7, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 4, 244, 152, 2, 6, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 4, 244, 151, 1, 11, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 4, 244, 151, 1, 10, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 4, 244, 151, 1, 9, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 4, 244, 151, 1, 8, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 4, 244, 151, 1, 7, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 4, 244, 151, 1, 6, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 3, 244, 152, 2, 11, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 3, 244, 152, 2, 10, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 3, 244, 152, 2, 9, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 3, 244, 152, 2, 8, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 3, 244, 152, 2, 7, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 3, 244, 152, 2, 6, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 3, 244, 151, 1, 11, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 3, 244, 151, 1, 10, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 3, 244, 151, 1, 9, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 3, 244, 151, 1, 8, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 3, 244, 151, 1, 7, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 3, 244, 151, 1, 6, '#saldo_inicial_prevadic');




SQL;




        $this->execute($sql);
    }


    public function down(){



        $sql = <<<SQL

         delete from db_menu where id_item = 8033 and id_item_filho = 228475;
         delete from db_itensmenu where id_item = 228475;

         delete from orcparamrelperiodos where o113_orcparamrel = 244;
         delete from orcparamseqorcparamseqcoluna where o116_codparamrel = 244;
         delete from orcparamseqfiltropadrao where o132_orcparamrel = 244;
         delete from orcparamseq where o69_codparamrel = 244;
         delete from orcparamrel where o42_codparrel = 244;

SQL;




        $this->execute($sql);

    }
}
