<?php

use Classes\PostgresMigration;

class M17733AdequacaoAnexoViii extends PostgresMigration
{

    public function up(){

        $sql = <<<SQL

insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228476 ,'[Ed 11] Anexo VIII - Dem. Rec. e Desp. MDE (FUNDEB)' ,'[Ed 11] Anexo VIII - Dem. Rec. e Desp. MDE (FUNDEB)' ,'con2_lrfmdefundeb0001.php' ,'1' ,'1' ,'[Ed 11] Receitas e Despesas com MDE FUNDEB' ,'true' );
insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 8033 ,228476 ,18 ,209 );


INSERT INTO orcparamrel VALUES (245, 'ED. 11 - DEM DAS REC E DESP COM MDE', 1, 'FONTE: Sistema E-Cidade, Unidade Responsável: [nome_departamento]. Emissão: [data_emissao], às [hora_emissao]. Assinado Digitalmente no dia [data_emissao], às [hora_emissao].');

INSERT INTO orcparamrelperiodos VALUES (nextval('orcparamrelperiodos_o113_sequencial_seq'), 11, 245);
INSERT INTO orcparamrelperiodos VALUES (nextval('orcparamrelperiodos_o113_sequencial_seq'), 10, 245);
INSERT INTO orcparamrelperiodos VALUES (nextval('orcparamrelperiodos_o113_sequencial_seq'), 9, 245);
INSERT INTO orcparamrelperiodos VALUES (nextval('orcparamrelperiodos_o113_sequencial_seq'), 8, 245);
INSERT INTO orcparamrelperiodos VALUES (nextval('orcparamrelperiodos_o113_sequencial_seq'), 7, 245);
INSERT INTO orcparamrelperiodos VALUES (nextval('orcparamrelperiodos_o113_sequencial_seq'), 6, 245);

INSERT INTO orcparamseqcoluna VALUES (100449, 2021, 'INSCRITAS EM RESTOS A PAGAR NÃO PROCESSADOS (SEM DISPONIBILIDADE DE CAIXA)7', 1, NULL, 'rpnp_sem_dc', NULL, 0, 245);
INSERT INTO orcparamseqcoluna VALUES (100450, 2021, 'Saldo Inicial', 1, NULL, 'saldo_rp_inicial', NULL, 0, 245);
INSERT INTO orcparamseqcoluna VALUES (100451, 2021, 'RP Liquidados', 1, NULL, 'rp_liquidados', NULL, 0, 245);
INSERT INTO orcparamseqcoluna VALUES (100452, 2021, 'RP Pagos', 1, NULL, 'rp_pagos', NULL, 0, 245);
INSERT INTO orcparamseqcoluna VALUES (100453, 2021, 'RP Cancelados', 1, NULL, 'rp_cancelados', NULL, 0, 245);
INSERT INTO orcparamseqcoluna VALUES (100454, 2021, 'Saldo Final', 1, NULL, 'rp_saldo_final', NULL, 0, 245);
INSERT INTO orcparamseqcoluna VALUES (100455, 2021, 'Valor de Superávit Permitido no Exercício Anterior', 1, NULL, 'vlr_superavit_ex_ant', NULL, 0, 245);
INSERT INTO orcparamseqcoluna VALUES (100456, 2021, 'Valor não Aplicado no Exercício Anterior', 1, NULL, 'vlr_naplic_ex_ant', NULL, 0, 245);
INSERT INTO orcparamseqcoluna VALUES (100457, 2021, 'Valor de Superavit Aplicado até o Primeiro Quadrimestre', 1, NULL, 'superavit_aplic_1quadr', NULL, 0, 245);
INSERT INTO orcparamseqcoluna VALUES (100458, 2021, 'Valor Aplicado até o Primeiro Quadrimestre que Integrará o Limite Constitucional', 1, NULL, 'aplic_1q_limite_constitucional', NULL, 0, 245);
INSERT INTO orcparamseqcoluna VALUES (100459, 2021, 'Valor Aplicado após o Primeiro Quadrimestre', 1, NULL, 'aplic_apos_1q', NULL, 0, 245);
INSERT INTO orcparamseqcoluna VALUES (100460, 2021, 'Valor não Aplicado', 1, NULL, 'vlr_nao_aplic', NULL, 0, 245);

INSERT INTO orcparamseq VALUES (245, 1, '1- RECEITA DE IMPOSTOS', 1, 1, 0, false, false, false, false, false, '1- RECEITA DE IMPOSTOS', false, true, 1, 1, '', false, 0);
INSERT INTO orcparamseq VALUES (245, 2, '1.1- Receita Resultante do Imposto sobre a Propriedade Predi', 1, 0, 0, false, false, false, false, false, '1.1- Receita Resultante do Imposto sobre a Propriedade Predial e Territorial Urbana – IPTU', true, false, 2, 2, '', false, 1);
INSERT INTO orcparamseq VALUES (245, 3, '1.2- Receita Resultante do Imposto sobre Transmissão Inter V', 1, 0, 0, false, false, false, false, false, '1.2- Receita Resultante do Imposto sobre Transmissão Inter Vivos – ITBI', true, false, 3, 2, '', false, 1);
INSERT INTO orcparamseq VALUES (245, 4, '1.3- Receita Resultante do Imposto sobre Serviços de Qualque', 1, 0, 0, false, false, false, false, false, '1.3- Receita Resultante do Imposto sobre Serviços de Qualquer Natureza – ISS', true, false, 4, 2, '', false, 1);
INSERT INTO orcparamseq VALUES (245, 5, '1.4- Receita Resultante do Imposto de Renda Retido na Fonte ', 1, 0, 0, false, false, false, false, false, '1.4- Receita Resultante do Imposto de Renda Retido na Fonte – IRRF', true, false, 5, 2, '', false, 1);
INSERT INTO orcparamseq VALUES (245, 6, '2- RECEITA DE TRANSFERÊNCIAS CONSTITUCIONAIS E LEGAIS ', 1, 1, 0, false, false, false, false, false, '2- RECEITA DE TRANSFERÊNCIAS CONSTITUCIONAIS E LEGAIS ', false, true, 6, 1, '', false, 0);
INSERT INTO orcparamseq VALUES (245, 7, '2.1- Cota-Parte FPM ', 1, 1, 0, false, false, false, false, false, '2.1- Cota-Parte FPM ', false, true, 7, 2, '', false, 0);
INSERT INTO orcparamseq VALUES (245, 8, '2.1.1- Parcela referente à CF, art. 159, I, alínea b', 1, 0, 0, false, false, false, false, false, '2.1.1- Parcela referente à CF, art. 159, I, alínea b', true, false, 8, 4, '', false, 1);
INSERT INTO orcparamseq VALUES (245, 9, '2.1.2- Parcela referente à CF, art. 159, I, alíneas d e e', 1, 0, 0, false, false, false, false, false, '2.1.2- Parcela referente à CF, art. 159, I, alíneas d e e', true, false, 9, 4, '', false, 1);
INSERT INTO orcparamseq VALUES (245, 10, '2.2- Cota-Parte ICMS ', 1, 0, 0, false, false, false, false, false, '2.2- Cota-Parte ICMS ', true, false, 10, 2, '', false, 1);
INSERT INTO orcparamseq VALUES (245, 11, '2.3- Cota-Parte IPI-Exportação', 1, 0, 0, false, false, false, false, false, '2.3- Cota-Parte IPI-Exportação', true, false, 11, 2, '', false, 1);
INSERT INTO orcparamseq VALUES (245, 12, '2.4- Cota-Parte ITR ', 1, 0, 0, false, false, false, false, false, '2.4- Cota-Parte ITR ', true, false, 12, 2, '', false, 1);
INSERT INTO orcparamseq VALUES (245, 13, '2.5- Cota-Parte IPVA ', 1, 0, 0, false, false, false, false, false, '2.5- Cota-Parte IPVA ', true, false, 13, 2, '', false, 1);
INSERT INTO orcparamseq VALUES (245, 14, '2.6- Cota-Parte IOF-Ouro', 1, 0, 0, false, false, false, false, false, '2.6- Cota-Parte IOF-Ouro', true, false, 14, 2, '', false, 1);
INSERT INTO orcparamseq VALUES (245, 15, '2.7- Compensações Financeiras Provenientes de Impostos e Tra', 1, 0, 0, false, false, false, false, false, '2.7- Compensações Financeiras Provenientes de Impostos e Transferências Constitucionais', true, false, 15, 2, '', false, 1);
INSERT INTO orcparamseq VALUES (245, 16, '3- TOTAL DA RECEITA RESULTANTE DE IMPOSTOS (1 + 2)', 1, 1, 0, false, false, false, false, false, '3- TOTAL DA RECEITA RESULTANTE DE IMPOSTOS (1 + 2)', false, true, 16, 1, '', false, 0);
INSERT INTO orcparamseq VALUES (245, 17, '4- TOTAL DESTINADO AO FUNDEB - 20% DE ((2.1.1) + (2.2) + (2.', 1, 1, 0, false, false, false, false, false, '4- TOTAL DESTINADO AO FUNDEB - 20% DE ((2.1.1) + (2.2) + (2.3) + (2.4) + (2.5))', false, true, 17, 1, '', false, 0);
INSERT INTO orcparamseq VALUES (245, 18, '5- VALOR MÍNIMO A SER APLICADO ALÉM DO VALOR DESTINADO AO FU', 1, 1, 0, false, false, false, false, false, '5- VALOR MÍNIMO A SER APLICADO ALÉM DO VALOR DESTINADO AO FUNDEB - 5% DE ((2.1.1) + (2.2) + (2.3) + (2.4) + (2.5)) + 25% DE ((1.1)', false, true, 18, 1, '', false, 0);
INSERT INTO orcparamseq VALUES (245, 19, '6- RECEITAS RECEBIDAS DO FUNDEB', 1, 1, 0, false, false, false, false, false, '6- RECEITAS RECEBIDAS DO FUNDEB', false, true, 19, 1, '', false, 0);
INSERT INTO orcparamseq VALUES (245, 20, '6.1- FUNDEB - Impostos e Transferências de Impostos', 1, 1, 0, false, false, false, false, false, '6.1- FUNDEB - Impostos e Transferências de Impostos', false, true, 20, 2, '', false, 0);
INSERT INTO orcparamseq VALUES (245, 21, '6.1.1- Principal', 1, 0, 0, false, false, false, false, false, '6.1.1- Principal', true, false, 21, 4, '', false, 1);
INSERT INTO orcparamseq VALUES (245, 22, '6.1.2- Rendimentos de Aplicação Financeira', 1, 0, 0, false, false, false, false, false, '6.1.2- Rendimentos de Aplicação Financeira', true, false, 22, 4, '', false, 1);
INSERT INTO orcparamseq VALUES (245, 23, '6.2- FUNDEB - Complementação da União - VAAF', 1, 1, 0, false, false, false, false, false, '6.2- FUNDEB - Complementação da União - VAAF', false, true, 23, 2, '', false, 0);
INSERT INTO orcparamseq VALUES (245, 24, '6.2.1- Principal', 1, 0, 0, false, false, false, false, false, '6.2.1- Principal', true, false, 24, 4, '', false, 1);
INSERT INTO orcparamseq VALUES (245, 25, '6.2.2- Rendimentos de Aplicação Financeira', 1, 0, 0, false, false, false, false, false, '6.2.2- Rendimentos de Aplicação Financeira', true, false, 25, 4, '', false, 1);
INSERT INTO orcparamseq VALUES (245, 26, '6.3- FUNDEB - Complementação da União - VAAT', 1, 1, 0, false, false, false, false, false, '6.3- FUNDEB - Complementação da União - VAAT', false, true, 26, 2, '', false, 0);
INSERT INTO orcparamseq VALUES (245, 27, '6.3.1- Principal', 1, 0, 0, false, false, false, false, false, '6.3.1- Principal', true, false, 27, 4, '', false, 1);
INSERT INTO orcparamseq VALUES (245, 28, '6.3.2- Rendimentos de Aplicação Financeira', 1, 0, 0, false, false, false, false, false, '6.3.2- Rendimentos de Aplicação Financeira', true, false, 28, 4, '', false, 1);
INSERT INTO orcparamseq VALUES (245, 29, '7- RESULTADO LÍQUIDO DAS TRANSFERÊNCIAS DO FUNDEB (6.1.1 – 4', 1, 1, 0, false, false, false, false, false, '7- RESULTADO LÍQUIDO DAS TRANSFERÊNCIAS DO FUNDEB (6.1.1 – 4)1', false, true, 29, 1, '', false, 0);
INSERT INTO orcparamseq VALUES (245, 30, '8- TOTAL DOS RECURSOS DE SUPERÁVIT', 1, 1, 0, false, false, false, false, false, '8- TOTAL DOS RECURSOS DE SUPERÁVIT', true, false, 30, 1, '', false, 3);
INSERT INTO orcparamseq VALUES (245, 31, '8.1- SUPERÁVIT DO EXERCÍCIO IMEDIATAMENTE ANTERIOR', 1, 1, 0, false, false, false, false, false, '8.1- SUPERÁVIT DO EXERCÍCIO IMEDIATAMENTE ANTERIOR', true, false, 31, 2, '', false, 0);
INSERT INTO orcparamseq VALUES (245, 32, '8.2- SUPERÁVIT RESIDUAL DE OUTROS EXERCÍCIOS', 1, 1, 0, false, false, false, false, false, '8.2- SUPERÁVIT RESIDUAL DE OUTROS EXERCÍCIOS', true, false, 32, 2, '', false, 0);
INSERT INTO orcparamseq VALUES (245, 33, '9- TOTAL DOS RECURSOS DO FUNDEB DISPONÍVEIS PARA UTILIZAÇÃO ', 1, 1, 0, false, false, false, false, false, '9- TOTAL DOS RECURSOS DO FUNDEB DISPONÍVEIS PARA UTILIZAÇÃO (6 +8)', false, true, 33, 1, '', false, 0);
INSERT INTO orcparamseq VALUES (245, 34, '10- PROFISSIONAIS DA EDUCAÇÃO BÁSICA', 1, 1, 0, false, false, false, false, false, '10- PROFISSIONAIS DA EDUCAÇÃO BÁSICA', false, true, 34, 1, '', false, 0);
INSERT INTO orcparamseq VALUES (245, 35, '10.1- Educação Infantil', 1, 1, 0, false, false, false, false, false, '10.1- Educação Infantil', false, true, 35, 2, '', false, 0);
INSERT INTO orcparamseq VALUES (245, 36, '10.1.1- Creche', 1, 0, 0, false, false, false, false, false, '10.1.1- Creche', true, false, 36, 4, '', false, 2);
INSERT INTO orcparamseq VALUES (245, 37, ' 10.1.2- Pré-escola', 1, 0, 0, false, false, false, false, false, ' 10.1.2- Pré-escola', true, false, 37, 4, '', false, 2);
INSERT INTO orcparamseq VALUES (245, 38, '10.2- Ensino Fundamental', 1, 0, 0, false, false, false, false, false, '10.2- Ensino Fundamental', true, false, 38, 2, '', false, 2);
INSERT INTO orcparamseq VALUES (245, 39, '11- OUTRAS DESPESAS', 1, 1, 0, false, false, false, false, false, '11- OUTRAS DESPESAS', false, true, 39, 1, '', false, 0);
INSERT INTO orcparamseq VALUES (245, 40, '11.1- Educação Infantil', 1, 1, 0, false, false, false, false, false, '11.1- Educação Infantil', false, true, 40, 2, '', false, 0);
INSERT INTO orcparamseq VALUES (245, 41, ' 11.1.1- Creche', 1, 0, 0, false, false, false, false, false, ' 11.1.1- Creche', true, false, 41, 4, '', false, 2);
INSERT INTO orcparamseq VALUES (245, 42, ' 11.1.2- Pré-escola', 1, 0, 0, false, false, false, false, false, ' 11.1.2- Pré-escola', true, false, 42, 4, '', false, 2);
INSERT INTO orcparamseq VALUES (245, 43, '11.2- Ensino Fundamental', 1, 0, 0, false, false, false, false, false, '11.2- Ensino Fundamental', true, false, 43, 2, '', false, 2);
INSERT INTO orcparamseq VALUES (245, 44, '12- TOTAL DAS DESPESAS COM RECURSOS DO FUNDEB (10 + 11)', 1, 1, 0, false, false, false, false, false, '12- TOTAL DAS DESPESAS COM RECURSOS DO FUNDEB (10 + 11)', false, true, 44, 1, '', false, 0);
INSERT INTO orcparamseq VALUES (245, 45, '13- Total das Despesas do FUNDEB com Profissionais da Educaç', 1, 0, 0, false, false, false, false, false, '13- Total das Despesas do FUNDEB com Profissionais da Educação Básica', true, false, 45, 1, '', false, 2);
INSERT INTO orcparamseq VALUES (245, 46, '14- Total das Despesas custeadas com FUNDEB - Impostos e Tra', 1, 0, 0, false, false, false, false, false, '14- Total das Despesas custeadas com FUNDEB - Impostos e Transferências de Impostos', true, false, 46, 1, '', false, 2);
INSERT INTO orcparamseq VALUES (245, 47, '15- Total das Despesas custeadas com FUNDEB - Complementação', 1, 0, 0, false, false, false, false, false, '15- Total das Despesas custeadas com FUNDEB - Complementação da União - VAAF', true, false, 47, 1, '', false, 2);
INSERT INTO orcparamseq VALUES (245, 48, '16- Total das Despesas custeadas com FUNDEB - Complementação', 1, 0, 0, false, false, false, false, false, '16- Total das Despesas custeadas com FUNDEB - Complementação da União - VAAT', true, false, 48, 1, '', false, 2);
INSERT INTO orcparamseq VALUES (245, 49, '17- Total das Despesas custeadas com FUNDEB - Complementação', 1, 0, 0, false, false, false, false, false, '17- Total das Despesas custeadas com FUNDEB - Complementação da União - VAAT Aplicadas na Educação Infantil', true, false, 49, 1, '', false, 2);
INSERT INTO orcparamseq VALUES (245, 50, '18- Total das Despesas custeadas com FUNDEB - Complementação', 1, 0, 0, false, false, false, false, false, '18- Total das Despesas custeadas com FUNDEB - Complementação da União - VAAT Aplicadas em Despesa de Capital', true, false, 50, 1, '', false, 2);
INSERT INTO orcparamseq VALUES (245, 51, '19- Mínimo de 70% do FUNDEB na Remuneração dos Profissionais', 1, 1, 0, false, false, false, false, false, '19- Mínimo de 70% do FUNDEB na Remuneração dos Profissionais da Educação Básica', false, true, 51, 1, '', false, 0);
INSERT INTO orcparamseq VALUES (245, 52, '20 - Percentual de 50% da Complementação da União ao FUNDEB ', 1, 1, 0, false, false, false, false, false, '20 - Percentual de 50% da Complementação da União ao FUNDEB (VAAT) na Educação Infantil', false, true, 52, 1, '', false, 0);
INSERT INTO orcparamseq VALUES (245, 53, '21- Mínimo de 15% da Complementação da União ao FUNDEB - VAA', 1, 1, 0, false, false, false, false, false, '21- Mínimo de 15% da Complementação da União ao FUNDEB - VAAT em Despesas de Capital', false, true, 53, 1, '', false, 0);
INSERT INTO orcparamseq VALUES (245, 54, '22- Total da Receita Recebida e não Aplicada no Exercício ', 1, 1, 0, false, false, false, false, false, '22- Total da Receita Recebida e não Aplicada no Exercício ', false, true, 54, 1, '', false, 0);
INSERT INTO orcparamseq VALUES (245, 55, '23- Total das Despesas custeadas com Superávit do FUNDEB', 1, 1, 0, false, false, false, false, false, '23- Total das Despesas custeadas com Superávit do FUNDEB', false, true, 55, 1, '', false, 0);
INSERT INTO orcparamseq VALUES (245, 56, '23.1- Total das Despesas custeadas com FUNDEB - Impostos e T', 1, 1, 0, false, false, false, false, false, '23.1- Total das Despesas custeadas com FUNDEB - Impostos e Transferências de Impostos', true, false, 56, 2, '', false, 0);
INSERT INTO orcparamseq VALUES (245, 57, '23.2- Total das Despesas custeadas com FUNDEB - Complementaç', 1, 1, 0, false, false, false, false, false, '23.2- Total das Despesas custeadas com FUNDEB - Complementação da União (VAAF + VAAT)', true, false, 57, 2, '', false, 0);
INSERT INTO orcparamseq VALUES (245, 58, '24- EDUCAÇÃO INFANTIL', 1, 1, 0, false, false, false, false, false, '24- EDUCAÇÃO INFANTIL', false, true, 58, 1, '', false, 0);
INSERT INTO orcparamseq VALUES (245, 59, '24.1- Creche', 1, 0, 0, false, false, false, false, false, '24.1- Creche', true, false, 59, 2, '', false, 2);
INSERT INTO orcparamseq VALUES (245, 60, '24.2- Pré-escola', 1, 0, 0, false, false, false, false, false, '24.2- Pré-escola', true, false, 60, 2, '', false, 2);
INSERT INTO orcparamseq VALUES (245, 61, '25- ENSINO FUNDAMENTAL ', 1, 0, 0, false, false, false, false, false, '25- ENSINO FUNDAMENTAL ', true, false, 61, 1, '', false, 2);
INSERT INTO orcparamseq VALUES (245, 62, '26- TOTAL DAS DESPESAS COM AÇÕES TÍPICAS DE MDE (24 + 25)', 1, 1, 0, false, false, false, false, false, '26- TOTAL DAS DESPESAS COM AÇÕES TÍPICAS DE MDE (24 + 25)', false, true, 62, 1, '', false, 0);
INSERT INTO orcparamseq VALUES (245, 63, '27- TOTAL DAS DESPESAS DE MDE CUSTEADAS COM RECURSOS DE IMPO', 1, 1, 0, false, false, false, false, false, '27- TOTAL DAS DESPESAS DE MDE CUSTEADAS COM RECURSOS DE IMPOSTOS (FUNDEB E RECEITA DE IMPOSTOS) = (L14(d ou e) + L26(d ou e) + L23', false, true, 63, 1, '', false, 0);
INSERT INTO orcparamseq VALUES (245, 64, '28 (-) RESULTADO LÍQUIDO DAS TRANSFERÊNCIAS DO FUNDEB = (L7)', 1, 1, 0, false, false, false, false, false, '28 (-) RESULTADO LÍQUIDO DAS TRANSFERÊNCIAS DO FUNDEB = (L7)', false, true, 64, 1, '', false, 0);
INSERT INTO orcparamseq VALUES (245, 65, '29 (-) RESTOS A PAGAR NÃO PROCESSADOS INSCRITOS NO EXERCÍCIO', 1, 1, 0, false, false, false, false, false, '29 (-) RESTOS A PAGAR NÃO PROCESSADOS INSCRITOS NO EXERCÍCIO SEM DISPONIBILIDADE FINANCEIRA DE RECURSOS DO FUNDEB IMPOSTOS4 = (L14', false, true, 65, 0, '', false, 0);
INSERT INTO orcparamseq VALUES (245, 66, '30 (-) RESTOS A PAGAR NÃO PROCESSADOS INSCRITOS NO EXERCÍCIO', 1, 1, 0, false, false, false, false, false, '30 (-) RESTOS A PAGAR NÃO PROCESSADOS INSCRITOS NO EXERCÍCIO SEM DISPONIBILIDADE FINANCEIRA DE RECURSOS DE IMPOSTOS4 e 7 ', true, false, 66, 1, '', false, 0);
INSERT INTO orcparamseq VALUES (245, 67, '31 (-) CANCELAMENTO, NO EXERCÍCIO, DE RESTOS A PAGAR INSCRIT', 1, 1, 0, false, false, false, false, false, '31 (-) CANCELAMENTO, NO EXERCÍCIO, DE RESTOS A PAGAR INSCRITOS COM DISPONIBILIDADE FINANCEIRA DE RECURSOS DE IMPOSTOS VINCULADOS A', false, true, 67, 1, '', false, 0);
INSERT INTO orcparamseq VALUES (245, 68, '32- TOTAL DAS DESPESAS PARA FINS DE LIMITE  (27 – (28 + 29 +', 1, 1, 0, false, false, false, false, false, '32- TOTAL DAS DESPESAS PARA FINS DE LIMITE  (27 – (28 + 29 + 30 + 31))', false, true, 68, 1, '', false, 0);
INSERT INTO orcparamseq VALUES (245, 69, '33- APLICAÇÃO EM MDE SOBRE A RECEITA RESULTANTE DE IMPOSTOS', 1, 1, 0, false, false, false, false, false, '33- APLICAÇÃO EM MDE SOBRE A RECEITA RESULTANTE DE IMPOSTOS', false, true, 69, 0, '', false, 0);
INSERT INTO orcparamseq VALUES (245, 70, '34- RESTOS A PAGAR DE DESPESAS COM MDE', 1, 1, 0, false, false, false, false, false, '34- RESTOS A PAGAR DE DESPESAS COM MDE', false, true, 70, 1, '', false, 0);
INSERT INTO orcparamseq VALUES (245, 71, '34.1 - Executadas com Recursos de Impostos e Transferências ', 1, 0, 0, false, false, false, false, false, '34.1 - Executadas com Recursos de Impostos e Transferências de Impostos', true, false, 71, 2, '', false, 4);
INSERT INTO orcparamseq VALUES (245, 72, '34.2 - Executadas com Recursos do FUNDEB - Impostos', 1, 0, 0, false, false, false, false, false, '34.2 - Executadas com Recursos do FUNDEB - Impostos', true, false, 72, 2, '', false, 4);
INSERT INTO orcparamseq VALUES (245, 73, '34.3 - Executadas com Recursos do FUNDEB - Complementação da', 1, 0, 0, false, false, false, false, false, '34.3 - Executadas com Recursos do FUNDEB - Complementação da União (VAAT + VAAF)', true, false, 73, 2, '', false, 4);
INSERT INTO orcparamseq VALUES (245, 74, '35- RECEITA DE TRANSFERÊNCIAS DO FNDE (INCLUINDO RENDIMENTOS', 1, 1, 0, false, false, false, false, false, '35- RECEITA DE TRANSFERÊNCIAS DO FNDE (INCLUINDO RENDIMENTOS DE APLICAÇÃO FINANCEIRA)', false, true, 74, 1, '', false, 0);
INSERT INTO orcparamseq VALUES (245, 75, '35.1- Salário-Educação', 1, 0, 0, false, false, false, false, false, '35.1- Salário-Educação', true, false, 75, 2, '', false, 1);
INSERT INTO orcparamseq VALUES (245, 76, '35.2- PDDE', 1, 0, 0, false, false, false, false, false, '35.2- PDDE', true, false, 76, 2, '', false, 1);
INSERT INTO orcparamseq VALUES (245, 77, '35.3- PNAE', 1, 0, 0, false, false, false, false, false, '35.3- PNAE', true, false, 77, 2, '', false, 1);
INSERT INTO orcparamseq VALUES (245, 78, '35.4 - PNATE', 1, 0, 0, false, false, false, false, false, '35.4 - PNATE', true, false, 78, 2, '', false, 1);
INSERT INTO orcparamseq VALUES (245, 79, '35.5- Outras Transferências do FNDE', 1, 0, 0, false, false, false, false, false, '35.5- Outras Transferências do FNDE', true, false, 79, 2, '', false, 1);
INSERT INTO orcparamseq VALUES (245, 80, '36- RECEITA DE TRANSFERÊNCIAS DE CONVÊNIOS', 1, 0, 0, false, false, false, false, false, '36- RECEITA DE TRANSFERÊNCIAS DE CONVÊNIOS', true, false, 80, 1, '', false, 1);
INSERT INTO orcparamseq VALUES (245, 81, '37- RECEITA DE ROYALTIES DESTINADOS À EDUCAÇÃO', 1, 0, 0, false, false, false, false, false, '37- RECEITA DE ROYALTIES DESTINADOS À EDUCAÇÃO', true, false, 81, 1, '', false, 1);
INSERT INTO orcparamseq VALUES (245, 82, '38- RECEITA DE OPERAÇÕES DE CRÉDITO VINCULADAS À EDUCAÇÃO', 1, 0, 0, false, false, false, false, false, '38- RECEITA DE OPERAÇÕES DE CRÉDITO VINCULADAS À EDUCAÇÃO', true, false, 82, 1, '', false, 1);
INSERT INTO orcparamseq VALUES (245, 83, '39- OUTRAS RECEITAS PARA FINANCIAMENTO DO ENSINO', 1, 0, 0, false, false, false, false, false, '39- OUTRAS RECEITAS PARA FINANCIAMENTO DO ENSINO', true, false, 83, 1, '', false, 1);
INSERT INTO orcparamseq VALUES (245, 84, '40- TOTAL DAS RECEITAS ADICIONAIS PARA FINANCIAMENTO DO ENSI', 1, 1, 0, false, false, false, false, false, '40- TOTAL DAS RECEITAS ADICIONAIS PARA FINANCIAMENTO DO ENSINO = (35 + 36 + 37 + 38 + 39 )', false, true, 84, 1, '', false, 0);
INSERT INTO orcparamseq VALUES (245, 85, '41- EDUCAÇÃO INFANTIL', 1, 1, 0, false, false, false, false, false, '41- EDUCAÇÃO INFANTIL', false, true, 85, 1, '', false, 0);
INSERT INTO orcparamseq VALUES (245, 86, '41.1- Creche', 1, 0, 0, false, false, false, false, false, '41.1- Creche', true, false, 86, 2, '', false, 2);
INSERT INTO orcparamseq VALUES (245, 87, '41.2- Pré-escola', 1, 0, 0, false, false, false, false, false, '41.2- Pré-escola', true, false, 87, 2, '', false, 2);
INSERT INTO orcparamseq VALUES (245, 88, '42- ENSINO FUNDAMENTAL ', 1, 0, 0, false, false, false, false, false, '42- ENSINO FUNDAMENTAL ', true, false, 88, 1, '', false, 2);
INSERT INTO orcparamseq VALUES (245, 89, '43- ENSINO MÉDIO ', 1, 0, 0, false, false, false, false, false, '43- ENSINO MÉDIO ', true, false, 89, 1, '', false, 2);
INSERT INTO orcparamseq VALUES (245, 90, '44- ENSINO SUPERIOR', 1, 0, 0, false, false, false, false, false, '44- ENSINO SUPERIOR', true, false, 90, 1, '', false, 2);
INSERT INTO orcparamseq VALUES (245, 91, '45- ENSINO PROFISSIONAL NÃO INTEGRADO AO ENSINO REGULAR', 1, 0, 0, false, false, false, false, false, '45- ENSINO PROFISSIONAL NÃO INTEGRADO AO ENSINO REGULAR', true, false, 91, 1, '', false, 2);
INSERT INTO orcparamseq VALUES (245, 92, '46- TOTAL DAS DESPESAS CUSTEADAS COM RECEITAS ADICIONAIS PAR', 1, 1, 0, false, false, false, false, false, '46- TOTAL DAS DESPESAS CUSTEADAS COM RECEITAS ADICIONAIS PARA FINANCIAMENTO DO ENSINO (41 + 42 + 43 + 44 + 45)', false, true, 92, 1, '', false, 0);
INSERT INTO orcparamseq VALUES (245, 93, '47- TOTAL GERAL DAS DESPESAS COM EDUCAÇÃO (12 + 26 + 46)', 1, 1, 0, false, false, false, false, false, '47- TOTAL GERAL DAS DESPESAS COM EDUCAÇÃO (12 + 26 + 46)', false, true, 93, 1, '', false, 0);
INSERT INTO orcparamseq VALUES (245, 94, '47.1- Despesas Correntes', 1, 1, 0, false, false, false, false, false, '47.1- Despesas Correntes', false, true, 94, 2, '', false, 0);
INSERT INTO orcparamseq VALUES (245, 95, '47.1.1- Pessoal Ativo', 1, 0, 0, false, false, false, false, false, '47.1.1- Pessoal Ativo', true, false, 95, 4, '', false, 2);
INSERT INTO orcparamseq VALUES (245, 96, '47.1.2- Pessoal Inativo', 1, 0, 0, false, false, false, false, false, '47.1.2- Pessoal Inativo', true, false, 96, 4, '', false, 2);
INSERT INTO orcparamseq VALUES (245, 97, '47.1.3-Transferências às instituições comunitárias, confessi', 1, 0, 0, false, false, false, false, false, '47.1.3-Transferências às instituições comunitárias, confessionais ou filantrópicas sem fins lucrativos', true, false, 97, 4, '', false, 2);
INSERT INTO orcparamseq VALUES (245, 98, '47.1.4- Outras Despesas Correntes', 1, 0, 0, false, false, false, false, false, '47.1.4- Outras Despesas Correntes', true, false, 98, 4, '', false, 2);
INSERT INTO orcparamseq VALUES (245, 99, '47.2- Despesas de Capital', 1, 1, 0, false, false, false, false, false, '47.2- Despesas de Capital', false, true, 99, 2, '', false, 0);
INSERT INTO orcparamseq VALUES (245, 100, '47.2.1- Transferências às instituições comunitárias, confess', 1, 0, 0, false, false, false, false, false, '47.2.1- Transferências às instituições comunitárias, confessionais ou filantrópicas sem fins lucrativos', true, false, 100, 4, '', false, 2);
INSERT INTO orcparamseq VALUES (245, 101, '47.2.2- Outras Despesas de Capital', 1, 0, 0, false, false, false, false, false, '47.2.2- Outras Despesas de Capital', true, false, 101, 4, '', false, 2);
INSERT INTO orcparamseq VALUES (245, 102, '48- DISPONIBILIDADE FINANCEIRA EM 31 DE DEZEMBRO DE <EXERCÍC', 1, 0, 0, false, false, false, false, false, '48- DISPONIBILIDADE FINANCEIRA EM 31 DE DEZEMBRO DE <EXERCÍCIO ANTERIOR>', true, false, 102, 1, '', false, 3);
INSERT INTO orcparamseq VALUES (245, 103, '49- (+) INGRESSO DE RECURSOS ATÉ O BIMESTRE (orçamentário)', 1, 0, 0, false, false, false, false, false, '49- (+) INGRESSO DE RECURSOS ATÉ O BIMESTRE (orçamentário)', true, false, 103, 1, '', false, 3);
INSERT INTO orcparamseq VALUES (245, 104, '50- (-) PAGAMENTOS EFETUADOS ATÉ O BIMESTRE (orçamentário e ', 1, 0, 0, false, false, false, false, false, '50- (-) PAGAMENTOS EFETUADOS ATÉ O BIMESTRE (orçamentário e restos a pagar)', true, false, 104, 1, '', false, 3);
INSERT INTO orcparamseq VALUES (245, 105, '51- (=) DISPONIBILIDADE FINANCEIRA ATÉ O BIMESTRE', 1, 1, 0, false, false, false, false, false, '51- (=) DISPONIBILIDADE FINANCEIRA ATÉ O BIMESTRE', false, true, 105, 1, '', false, 0);
INSERT INTO orcparamseq VALUES (245, 106, '52- (+) AJUSTES POSITIVOS ( RETENÇÕES E OUTROS VALORES EXTRA', 1, 0, 0, false, false, false, false, false, '52- (+) AJUSTES POSITIVOS ( RETENÇÕES E OUTROS VALORES EXTRAORÇAMENTÁRIOS)', true, false, 106, 1, '', false, 3);
INSERT INTO orcparamseq VALUES (245, 107, '53- (-) AJUSTES NEGATIVOS (OUTROS VALORES EXTRAORÇAMENTÁRIOS', 1, 0, 0, false, false, false, false, false, '53- (-) AJUSTES NEGATIVOS (OUTROS VALORES EXTRAORÇAMENTÁRIOS)', true, false, 107, 1, '', false, 3);
INSERT INTO orcparamseq VALUES (245, 108, '54- (=) SALDO FINANCEIRO CONCILIADO (Saldo Bancário)', 1, 0, 0, false, false, false, false, false, '54- (=) SALDO FINANCEIRO CONCILIADO (Saldo Bancário)', true, false, 108, 1, '', false, 1);
INSERT INTO orcparamseq VALUES (245, 109, '48- DISPONIBILIDADE FINANCEIRA EM 31 DE DEZEMBRO DE <EXERCÍC', 1, 0, 0, false, false, false, false, false, '48- DISPONIBILIDADE FINANCEIRA EM 31 DE DEZEMBRO DE <EXERCÍCIO ANTERIOR>', true, false, 109, 1, '', false, 3);
INSERT INTO orcparamseq VALUES (245, 110, '49- (+) INGRESSO DE RECURSOS ATÉ O BIMESTRE (orçamentário)', 1, 0, 0, false, false, false, false, false, '49- (+) INGRESSO DE RECURSOS ATÉ O BIMESTRE (orçamentário)', true, false, 110, 1, '', false, 3);
INSERT INTO orcparamseq VALUES (245, 111, '50- (-) PAGAMENTOS EFETUADOS ATÉ O BIMESTRE (orçamentário e ', 1, 0, 0, false, false, false, false, false, '50- (-) PAGAMENTOS EFETUADOS ATÉ O BIMESTRE (orçamentário e restos a pagar)', true, false, 111, 1, '', false, 3);
INSERT INTO orcparamseq VALUES (245, 112, '51- (=) DISPONIBILIDADE FINANCEIRA ATÉ O BIMESTRE', 1, 1, 0, false, false, false, false, false, '51- (=) DISPONIBILIDADE FINANCEIRA ATÉ O BIMESTRE', true, true, 112, 1, '', false, 0);
INSERT INTO orcparamseq VALUES (245, 113, '52- (+) AJUSTES POSITIVOS ( RETENÇÕES E OUTROS VALORES EXTRA', 1, 0, 0, false, false, false, false, false, '52- (+) AJUSTES POSITIVOS ( RETENÇÕES E OUTROS VALORES EXTRAORÇAMENTÁRIOS)', true, false, 113, 1, '', false, 3);
INSERT INTO orcparamseq VALUES (245, 114, '53- (-) AJUSTES NEGATIVOS (OUTROS VALORES EXTRAORÇAMENTÁRIOS', 1, 0, 0, false, false, false, false, false, '53- (-) AJUSTES NEGATIVOS (OUTROS VALORES EXTRAORÇAMENTÁRIOS)', true, false, 114, 1, '', false, 3);
INSERT INTO orcparamseq VALUES (245, 115, '54- (=) SALDO FINANCEIRO CONCILIADO (Saldo Bancário)', 1, 1, 0, false, false, false, false, false, '54- (=) SALDO FINANCEIRO CONCILIADO (Saldo Bancário)', true, true, 115, 1, '', false, 0);

INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 68, 245, 103, 1, 11, 'F[63]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 68, 245, 103, 1, 10, 'F[63]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 68, 245, 103, 1, 9, 'F[63]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 68, 245, 103, 1, 8, 'F[63]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 68, 245, 103, 1, 7, 'F[63]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 68, 245, 103, 1, 6, 'F[63]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 30, 245, 103, 1, 11, '#saldo_anterior');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 30, 245, 103, 1, 10, '#saldo_anterior');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 30, 245, 103, 1, 9, '#saldo_anterior');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 30, 245, 103, 1, 8, '#saldo_anterior');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 30, 245, 103, 1, 7, '#saldo_anterior');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 30, 245, 103, 1, 6, '#saldo_anterior');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 29, 245, 152, 2, 11, 'L[21]->rec_atebim - F[17]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 29, 245, 152, 2, 10, 'L[21]->rec_atebim - F[17]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 29, 245, 152, 2, 9, 'L[21]->rec_atebim - F[17]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 29, 245, 152, 2, 8, 'L[21]->rec_atebim - F[17]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 29, 245, 152, 2, 7, 'L[21]->rec_atebim - F[17]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 29, 245, 152, 2, 6, 'L[21]->rec_atebim - F[17]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 29, 245, 151, 1, 11, 'L[21]->prev_atual - F[17]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 29, 245, 151, 1, 10, 'L[21]->prev_atual - F[17]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 29, 245, 151, 1, 9, 'L[21]->prev_atual - F[17]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 29, 245, 151, 1, 8, 'L[21]->prev_atual - F[17]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 29, 245, 151, 1, 7, 'L[21]->prev_atual - F[17]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 29, 245, 151, 1, 6, 'L[21]->prev_atual - F[17]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 18, 245, 152, 2, 11, '((L[8]->rec_atebim+L[10]->rec_atebim+L[11]->rec_atebim+L[12]->rec_atebim+L[13]->rec_atebim) * 0.05) + ((L[2]->rec_atebim+L[3]->rec_atebim+L[4]->rec_atebim+L[5]->rec_atebim+L[9]->rec_atebim+L[14]->rec_atebim+L[15]->rec_atebim) * 0.25)');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 18, 245, 152, 2, 10, '((L[8]->rec_atebim+L[10]->rec_atebim+L[11]->rec_atebim+L[12]->rec_atebim+L[13]->rec_atebim) * 0.05) + ((L[2]->rec_atebim+L[3]->rec_atebim+L[4]->rec_atebim+L[5]->rec_atebim+L[9]->rec_atebim+L[14]->rec_atebim+L[15]->rec_atebim) * 0.25)');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 18, 245, 152, 2, 9, '((L[8]->rec_atebim+L[10]->rec_atebim+L[11]->rec_atebim+L[12]->rec_atebim+L[13]->rec_atebim) * 0.05) + ((L[2]->rec_atebim+L[3]->rec_atebim+L[4]->rec_atebim+L[5]->rec_atebim+L[9]->rec_atebim+L[14]->rec_atebim+L[15]->rec_atebim) * 0.25)');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 18, 245, 152, 2, 8, '((L[8]->rec_atebim+L[10]->rec_atebim+L[11]->rec_atebim+L[12]->rec_atebim+L[13]->rec_atebim) * 0.05) + ((L[2]->rec_atebim+L[3]->rec_atebim+L[4]->rec_atebim+L[5]->rec_atebim+L[9]->rec_atebim+L[14]->rec_atebim+L[15]->rec_atebim) * 0.25)');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 18, 245, 152, 2, 7, '((L[8]->rec_atebim+L[10]->rec_atebim+L[11]->rec_atebim+L[12]->rec_atebim+L[13]->rec_atebim) * 0.05) + ((L[2]->rec_atebim+L[3]->rec_atebim+L[4]->rec_atebim+L[5]->rec_atebim+L[9]->rec_atebim+L[14]->rec_atebim+L[15]->rec_atebim) * 0.25)');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 18, 245, 152, 2, 6, '((L[8]->rec_atebim+L[10]->rec_atebim+L[11]->rec_atebim+L[12]->rec_atebim+L[13]->rec_atebim) * 0.05) + ((L[2]->rec_atebim+L[3]->rec_atebim+L[4]->rec_atebim+L[5]->rec_atebim+L[9]->rec_atebim+L[14]->rec_atebim+L[15]->rec_atebim) * 0.25)');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 18, 245, 151, 1, 11, '((L[8]->prev_atual+L[10]->prev_atual+L[11]->prev_atual+L[12]->prev_atual+L[13]->prev_atual ) * 0.05 )+((L[2]->prev_atual+L[3]->prev_atual+L[4]->prev_atual+L[5]->prev_atual+L[9]->prev_atual+L[14]->prev_atual+L[15]->prev_atual ) * 0.25)');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 18, 245, 151, 1, 10, '((L[8]->prev_atual+L[10]->prev_atual+L[11]->prev_atual+L[12]->prev_atual+L[13]->prev_atual ) * 0.05 )+((L[2]->prev_atual+L[3]->prev_atual+L[4]->prev_atual+L[5]->prev_atual+L[9]->prev_atual+L[14]->prev_atual+L[15]->prev_atual ) * 0.25)');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 18, 245, 151, 1, 9, '((L[8]->prev_atual+L[10]->prev_atual+L[11]->prev_atual+L[12]->prev_atual+L[13]->prev_atual ) * 0.05 )+((L[2]->prev_atual+L[3]->prev_atual+L[4]->prev_atual+L[5]->prev_atual+L[9]->prev_atual+L[14]->prev_atual+L[15]->prev_atual ) * 0.25)');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 18, 245, 151, 1, 8, '((L[8]->prev_atual+L[10]->prev_atual+L[11]->prev_atual+L[12]->prev_atual+L[13]->prev_atual ) * 0.05 )+((L[2]->prev_atual+L[3]->prev_atual+L[4]->prev_atual+L[5]->prev_atual+L[9]->prev_atual+L[14]->prev_atual+L[15]->prev_atual ) * 0.25)');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 18, 245, 151, 1, 7, '((L[8]->prev_atual+L[10]->prev_atual+L[11]->prev_atual+L[12]->prev_atual+L[13]->prev_atual ) * 0.05 )+((L[2]->prev_atual+L[3]->prev_atual+L[4]->prev_atual+L[5]->prev_atual+L[9]->prev_atual+L[14]->prev_atual+L[15]->prev_atual ) * 0.25)');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 18, 245, 151, 1, 6, '((L[8]->prev_atual+L[10]->prev_atual+L[11]->prev_atual+L[12]->prev_atual+L[13]->prev_atual ) * 0.05 )+((L[2]->prev_atual+L[3]->prev_atual+L[4]->prev_atual+L[5]->prev_atual+L[9]->prev_atual+L[14]->prev_atual+L[15]->prev_atual ) * 0.25)');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 13, 245, 152, 2, 11, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 13, 245, 152, 2, 10, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 13, 245, 152, 2, 9, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 13, 245, 152, 2, 8, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 13, 245, 152, 2, 7, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 13, 245, 152, 2, 6, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 13, 245, 151, 1, 11, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 13, 245, 151, 1, 10, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 13, 245, 151, 1, 9, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 13, 245, 151, 1, 8, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 13, 245, 151, 1, 7, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 13, 245, 151, 1, 6, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 107, 245, 103, 1, 11, '#saldo_anterior_credito');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 107, 245, 103, 1, 10, '#saldo_anterior_credito');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 107, 245, 103, 1, 9, '#saldo_anterior_credito');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 107, 245, 103, 1, 8, '#saldo_anterior_credito');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 107, 245, 103, 1, 7, '#saldo_anterior_credito');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 107, 245, 103, 1, 6, '#saldo_anterior_credito');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 106, 245, 103, 1, 11, '#saldo_anterior_debito');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 106, 245, 103, 1, 10, '#saldo_anterior_debito');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 106, 245, 103, 1, 9, '#saldo_anterior_debito');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 106, 245, 103, 1, 8, '#saldo_anterior_debito');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 106, 245, 103, 1, 7, '#saldo_anterior_debito');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 106, 245, 103, 1, 6, '#saldo_anterior_debito');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 108, 245, 103, 1, 11, 'F[105]+L[106]->valor-L[107]->valor');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 108, 245, 103, 1, 10, 'F[105]+L[106]->valor-L[107]->valor');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 108, 245, 103, 1, 9, 'F[105]+L[106]->valor-L[107]->valor');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 108, 245, 103, 1, 8, 'F[105]+L[106]->valor-L[107]->valor');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 108, 245, 103, 1, 7, 'F[105]+L[106]->valor-L[107]->valor');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 108, 245, 103, 1, 6, 'F[105]+L[106]->valor-L[107]->valor');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 105, 245, 103, 1, 11, 'L[102]->valor+L[103]->valor-L[104]->valor');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 105, 245, 103, 1, 10, 'L[102]->valor+L[103]->valor-L[104]->valor');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 105, 245, 103, 1, 9, 'L[102]->valor+L[103]->valor-L[104]->valor');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 105, 245, 103, 1, 8, 'L[102]->valor+L[103]->valor-L[104]->valor');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 105, 245, 103, 1, 7, 'L[102]->valor+L[103]->valor-L[104]->valor');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 105, 245, 103, 1, 6, 'L[102]->valor+L[103]->valor-L[104]->valor');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 104, 245, 103, 1, 11, '#saldo_anterior_credito');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 104, 245, 103, 1, 10, '#saldo_anterior_credito');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 104, 245, 103, 1, 9, '#saldo_anterior_credito');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 104, 245, 103, 1, 8, '#saldo_anterior_credito');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 104, 245, 103, 1, 7, '#saldo_anterior_credito');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 104, 245, 103, 1, 6, '#saldo_anterior_credito');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 103, 245, 103, 1, 11, '#saldo_anterior_debito');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 103, 245, 103, 1, 10, '#saldo_anterior_debito');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 103, 245, 103, 1, 9, '#saldo_anterior_debito');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 103, 245, 103, 1, 8, '#saldo_anterior_debito');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 103, 245, 103, 1, 7, '#saldo_anterior_debito');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 103, 245, 103, 1, 6, '#saldo_anterior_debito');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 102, 245, 103, 1, 11, '#saldo_anterior');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 102, 245, 103, 1, 10, '#saldo_anterior');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 102, 245, 103, 1, 9, '#saldo_anterior');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 102, 245, 103, 1, 8, '#saldo_anterior');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 102, 245, 103, 1, 7, '#saldo_anterior');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 102, 245, 103, 1, 6, '#saldo_anterior');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 115, 245, 103, 1, 11, 'F[112]+L[113]->valor-L[114]->valor');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 115, 245, 103, 1, 10, 'F[112]+L[113]->valor-L[114]->valor');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 115, 245, 103, 1, 9, 'F[112]+L[113]->valor-L[114]->valor');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 115, 245, 103, 1, 8, 'F[112]+L[113]->valor-L[114]->valor');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 115, 245, 103, 1, 7, 'F[112]+L[113]->valor-L[114]->valor');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 115, 245, 103, 1, 6, 'F[112]+L[113]->valor-L[114]->valor');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 114, 245, 103, 1, 11, '#saldo_anterior_credito');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 114, 245, 103, 1, 10, '#saldo_anterior_credito');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 114, 245, 103, 1, 9, '#saldo_anterior_credito');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 114, 245, 103, 1, 8, '#saldo_anterior_credito');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 114, 245, 103, 1, 7, '#saldo_anterior_credito');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 114, 245, 103, 1, 6, '#saldo_anterior_credito');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 113, 245, 103, 1, 11, '#saldo_anterior_debito');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 113, 245, 103, 1, 10, '#saldo_anterior_debito');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 113, 245, 103, 1, 9, '#saldo_anterior_debito');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 113, 245, 103, 1, 8, '#saldo_anterior_debito');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 113, 245, 103, 1, 7, '#saldo_anterior_debito');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 113, 245, 103, 1, 6, '#saldo_anterior_debito');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 112, 245, 103, 1, 11, 'L[109]->valor+L[110]->valor-L[111]->valor');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 112, 245, 103, 1, 10, 'L[109]->valor+L[110]->valor-L[111]->valor');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 112, 245, 103, 1, 9, 'L[109]->valor+L[110]->valor-L[111]->valor');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 112, 245, 103, 1, 8, 'L[109]->valor+L[110]->valor-L[111]->valor');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 112, 245, 103, 1, 7, 'L[109]->valor+L[110]->valor-L[111]->valor');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 112, 245, 103, 1, 6, 'L[109]->valor+L[110]->valor-L[111]->valor');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 111, 245, 103, 1, 11, '#saldo_anterior_credito');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 111, 245, 103, 1, 10, '#saldo_anterior_credito');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 111, 245, 103, 1, 9, '#saldo_anterior_credito');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 111, 245, 103, 1, 8, '#saldo_anterior_credito');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 111, 245, 103, 1, 7, '#saldo_anterior_credito');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 111, 245, 103, 1, 6, '#saldo_anterior_credito');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 110, 245, 103, 1, 11, '#saldo_anterior_debito');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 110, 245, 103, 1, 10, '#saldo_anterior_debito');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 110, 245, 103, 1, 9, '#saldo_anterior_debito');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 110, 245, 103, 1, 8, '#saldo_anterior_debito');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 110, 245, 103, 1, 7, '#saldo_anterior_debito');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 110, 245, 103, 1, 6, '#saldo_anterior_debito');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 109, 245, 103, 1, 11, '#saldo_anterior');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 109, 245, 103, 1, 10, '#saldo_anterior');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 109, 245, 103, 1, 9, '#saldo_anterior');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 109, 245, 103, 1, 8, '#saldo_anterior');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 109, 245, 103, 1, 7, '#saldo_anterior');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 109, 245, 103, 1, 6, '#saldo_anterior');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 55, 245, 100460, 6, 11, 'L[56]->vlr_nao_aplic+L[57]->vlr_nao_aplic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 55, 245, 100460, 6, 10, 'L[56]->vlr_nao_aplic+L[57]->vlr_nao_aplic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 55, 245, 100460, 6, 9, 'L[56]->vlr_nao_aplic+L[57]->vlr_nao_aplic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 55, 245, 100460, 6, 8, 'L[56]->vlr_nao_aplic+L[57]->vlr_nao_aplic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 55, 245, 100460, 6, 7, 'L[56]->vlr_nao_aplic+L[57]->vlr_nao_aplic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 55, 245, 100460, 6, 6, 'L[56]->vlr_nao_aplic+L[57]->vlr_nao_aplic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 55, 245, 100459, 5, 11, 'L[56]->aplic_apos_1q+L[57]->aplic_apos_1q');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 55, 245, 100459, 5, 10, 'L[56]->aplic_apos_1q+L[57]->aplic_apos_1q');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 55, 245, 100459, 5, 9, 'L[56]->aplic_apos_1q+L[57]->aplic_apos_1q');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 55, 245, 100459, 5, 8, 'L[56]->aplic_apos_1q+L[57]->aplic_apos_1q');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 55, 245, 100459, 5, 7, 'L[56]->aplic_apos_1q+L[57]->aplic_apos_1q');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 55, 245, 100459, 5, 6, 'L[56]->aplic_apos_1q+L[57]->aplic_apos_1q');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 55, 245, 100458, 4, 11, 'L[56]->aplic_1q_limite_constitucional+L[57]->aplic_1q_limite_constitucional');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 55, 245, 100458, 4, 10, 'L[56]->aplic_1q_limite_constitucional+L[57]->aplic_1q_limite_constitucional');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 55, 245, 100458, 4, 9, 'L[56]->aplic_1q_limite_constitucional+L[57]->aplic_1q_limite_constitucional');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 55, 245, 100458, 4, 8, 'L[56]->aplic_1q_limite_constitucional+L[57]->aplic_1q_limite_constitucional');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 55, 245, 100458, 4, 7, 'L[56]->aplic_1q_limite_constitucional+L[57]->aplic_1q_limite_constitucional');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 55, 245, 100458, 4, 6, 'L[56]->aplic_1q_limite_constitucional+L[57]->aplic_1q_limite_constitucional');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 55, 245, 100457, 3, 11, 'L[56]->superavit_aplic_1quadr+L[57]->superavit_aplic_1quadr');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 55, 245, 100457, 3, 10, 'L[56]->superavit_aplic_1quadr+L[57]->superavit_aplic_1quadr');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 55, 245, 100457, 3, 9, 'L[56]->superavit_aplic_1quadr+L[57]->superavit_aplic_1quadr');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 55, 245, 100457, 3, 8, 'L[56]->superavit_aplic_1quadr+L[57]->superavit_aplic_1quadr');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 55, 245, 100457, 3, 7, 'L[56]->superavit_aplic_1quadr+L[57]->superavit_aplic_1quadr');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 55, 245, 100457, 3, 6, 'L[56]->superavit_aplic_1quadr+L[57]->superavit_aplic_1quadr');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 55, 245, 100456, 2, 11, 'L[56]->vlr_naplic_ex_ant+L[57]->vlr_naplic_ex_ant');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 55, 245, 100456, 2, 10, 'L[56]->vlr_naplic_ex_ant+L[57]->vlr_naplic_ex_ant');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 55, 245, 100456, 2, 9, 'L[56]->vlr_naplic_ex_ant+L[57]->vlr_naplic_ex_ant');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 55, 245, 100456, 2, 8, 'L[56]->vlr_naplic_ex_ant+L[57]->vlr_naplic_ex_ant');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 55, 245, 100456, 2, 7, 'L[56]->vlr_naplic_ex_ant+L[57]->vlr_naplic_ex_ant');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 55, 245, 100456, 2, 6, 'L[56]->vlr_naplic_ex_ant+L[57]->vlr_naplic_ex_ant');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 55, 245, 100455, 1, 11, 'L[56]->vlr_superavit_ex_ant+L[57]->vlr_superavit_ex_ant');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 55, 245, 100455, 1, 10, 'L[56]->vlr_superavit_ex_ant+L[57]->vlr_superavit_ex_ant');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 55, 245, 100455, 1, 9, 'L[56]->vlr_superavit_ex_ant+L[57]->vlr_superavit_ex_ant');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 56, 245, 100459, 5, 10, '');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 56, 245, 100459, 5, 9, '');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 56, 245, 100459, 5, 8, '');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 56, 245, 100459, 5, 7, '');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 56, 245, 100459, 5, 6, '');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 56, 245, 100458, 4, 11, '');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 56, 245, 100458, 4, 10, '');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 56, 245, 100458, 4, 9, '');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 56, 245, 100458, 4, 8, '');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 56, 245, 100458, 4, 7, '');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 56, 245, 100458, 4, 6, '');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 56, 245, 100457, 3, 11, '');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 56, 245, 100457, 3, 10, '');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 56, 245, 100457, 3, 9, '');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 56, 245, 100457, 3, 8, '');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 56, 245, 100457, 3, 7, '');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 56, 245, 100457, 3, 6, '');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 56, 245, 100456, 2, 11, '');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 56, 245, 100456, 2, 10, '');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 56, 245, 100456, 2, 9, '');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 56, 245, 100456, 2, 8, '');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 56, 245, 100456, 2, 7, '');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 56, 245, 100456, 2, 6, '');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 56, 245, 100455, 1, 11, '');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 56, 245, 100455, 1, 10, '');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 56, 245, 100455, 1, 9, '');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 56, 245, 100455, 1, 8, '');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 56, 245, 100455, 1, 7, '');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 56, 245, 100455, 1, 6, '');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 70, 245, 100454, 5, 11, 'L[71]->rp_saldo_final+L[72]->rp_saldo_final+L[73]->rp_saldo_final');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 70, 245, 100454, 5, 10, 'L[71]->rp_saldo_final+L[72]->rp_saldo_final+L[73]->rp_saldo_final');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 70, 245, 100454, 5, 9, 'L[71]->rp_saldo_final+L[72]->rp_saldo_final+L[73]->rp_saldo_final');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 70, 245, 100454, 5, 8, 'L[71]->rp_saldo_final+L[72]->rp_saldo_final+L[73]->rp_saldo_final');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 70, 245, 100454, 5, 7, 'L[71]->rp_saldo_final+L[72]->rp_saldo_final+L[73]->rp_saldo_final');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 70, 245, 100454, 5, 6, 'L[71]->rp_saldo_final+L[72]->rp_saldo_final+L[73]->rp_saldo_final');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 73, 245, 100454, 5, 11, '(#e91_vlremp-#e91_vlranu-#e91_vlrpag) - (#vlrpag+#vlrpagnproc) - (#vlranuliq+#vlranuliqnaoproc)');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 73, 245, 100454, 5, 10, '(#e91_vlremp-#e91_vlranu-#e91_vlrpag) - (#vlrpag+#vlrpagnproc) - (#vlranuliq+#vlranuliqnaoproc)');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 73, 245, 100454, 5, 9, '(#e91_vlremp-#e91_vlranu-#e91_vlrpag) - (#vlrpag+#vlrpagnproc) - (#vlranuliq+#vlranuliqnaoproc)');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 73, 245, 100454, 5, 8, '(#e91_vlremp-#e91_vlranu-#e91_vlrpag) - (#vlrpag+#vlrpagnproc) - (#vlranuliq+#vlranuliqnaoproc)');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 73, 245, 100454, 5, 7, '(#e91_vlremp-#e91_vlranu-#e91_vlrpag) - (#vlrpag+#vlrpagnproc) - (#vlranuliq+#vlranuliqnaoproc)');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 73, 245, 100454, 5, 6, '(#e91_vlremp-#e91_vlranu-#e91_vlrpag) - (#vlrpag+#vlrpagnproc) - (#vlranuliq+#vlranuliqnaoproc)');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 72, 245, 100454, 5, 11, '(#e91_vlremp-#e91_vlranu-#e91_vlrpag) - (#vlrpag+#vlrpagnproc) - (#vlranuliq+#vlranuliqnaoproc)');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 72, 245, 100454, 5, 10, '(#e91_vlremp-#e91_vlranu-#e91_vlrpag) - (#vlrpag+#vlrpagnproc) - (#vlranuliq+#vlranuliqnaoproc)');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 72, 245, 100454, 5, 9, '(#e91_vlremp-#e91_vlranu-#e91_vlrpag) - (#vlrpag+#vlrpagnproc) - (#vlranuliq+#vlranuliqnaoproc)');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 72, 245, 100454, 5, 8, '(#e91_vlremp-#e91_vlranu-#e91_vlrpag) - (#vlrpag+#vlrpagnproc) - (#vlranuliq+#vlranuliqnaoproc)');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 72, 245, 100454, 5, 7, '(#e91_vlremp-#e91_vlranu-#e91_vlrpag) - (#vlrpag+#vlrpagnproc) - (#vlranuliq+#vlranuliqnaoproc)');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 72, 245, 100454, 5, 6, '(#e91_vlremp-#e91_vlranu-#e91_vlrpag) - (#vlrpag+#vlrpagnproc) - (#vlranuliq+#vlranuliqnaoproc)');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 71, 245, 100454, 5, 11, '(#e91_vlremp-#e91_vlranu-#e91_vlrpag) - (#vlrpag+#vlrpagnproc) - (#vlranuliq+#vlranuliqnaoproc)');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 71, 245, 100454, 5, 10, '(#e91_vlremp-#e91_vlranu-#e91_vlrpag) - (#vlrpag+#vlrpagnproc) - (#vlranuliq+#vlranuliqnaoproc)');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 71, 245, 100454, 5, 9, '(#e91_vlremp-#e91_vlranu-#e91_vlrpag) - (#vlrpag+#vlrpagnproc) - (#vlranuliq+#vlranuliqnaoproc)');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 71, 245, 100454, 5, 8, '(#e91_vlremp-#e91_vlranu-#e91_vlrpag) - (#vlrpag+#vlrpagnproc) - (#vlranuliq+#vlranuliqnaoproc)');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 71, 245, 100454, 5, 7, '(#e91_vlremp-#e91_vlranu-#e91_vlrpag) - (#vlrpag+#vlrpagnproc) - (#vlranuliq+#vlranuliqnaoproc)');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 71, 245, 100454, 5, 6, '(#e91_vlremp-#e91_vlranu-#e91_vlrpag) - (#vlrpag+#vlrpagnproc) - (#vlranuliq+#vlranuliqnaoproc)');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 70, 245, 100453, 4, 11, 'L[71]->rp_cancelados+L[72]->rp_cancelados+L[73]->rp_cancelados');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 70, 245, 100453, 4, 10, 'L[71]->rp_cancelados+L[72]->rp_cancelados+L[73]->rp_cancelados');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 70, 245, 100453, 4, 9, 'L[71]->rp_cancelados+L[72]->rp_cancelados+L[73]->rp_cancelados');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 70, 245, 100453, 4, 8, 'L[71]->rp_cancelados+L[72]->rp_cancelados+L[73]->rp_cancelados');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 70, 245, 100453, 4, 7, 'L[71]->rp_cancelados+L[72]->rp_cancelados+L[73]->rp_cancelados');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 70, 245, 100453, 4, 6, 'L[71]->rp_cancelados+L[72]->rp_cancelados+L[73]->rp_cancelados');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 73, 245, 100453, 4, 11, '#vlranuliq+#vlranuliqnaoproc');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 73, 245, 100453, 4, 10, '#vlranuliq+#vlranuliqnaoproc');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 73, 245, 100453, 4, 9, '#vlranuliq+#vlranuliqnaoproc');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 73, 245, 100453, 4, 8, '#vlranuliq+#vlranuliqnaoproc');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 73, 245, 100453, 4, 7, '#vlranuliq+#vlranuliqnaoproc');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 73, 245, 100453, 4, 6, '#vlranuliq+#vlranuliqnaoproc');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 72, 245, 100453, 4, 11, '#vlranuliq+#vlranuliqnaoproc');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 72, 245, 100453, 4, 10, '#vlranuliq+#vlranuliqnaoproc');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 72, 245, 100453, 4, 9, '#vlranuliq+#vlranuliqnaoproc');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 72, 245, 100453, 4, 8, '#vlranuliq+#vlranuliqnaoproc');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 72, 245, 100453, 4, 7, '#vlranuliq+#vlranuliqnaoproc');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 72, 245, 100453, 4, 6, '#vlranuliq+#vlranuliqnaoproc');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 71, 245, 100453, 4, 11, '#vlranuliq+#vlranuliqnaoproc');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 71, 245, 100453, 4, 10, '#vlranuliq+#vlranuliqnaoproc');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 71, 245, 100453, 4, 9, '#vlranuliq+#vlranuliqnaoproc');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 71, 245, 100453, 4, 8, '#vlranuliq+#vlranuliqnaoproc');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 71, 245, 100453, 4, 7, '#vlranuliq+#vlranuliqnaoproc');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 71, 245, 100453, 4, 6, '#vlranuliq+#vlranuliqnaoproc');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 70, 245, 100452, 3, 11, 'L[71]->rp_pagos+L[72]->rp_pagos+L[73]->rp_pagos');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 70, 245, 100452, 3, 10, 'L[71]->rp_pagos+L[72]->rp_pagos+L[73]->rp_pagos');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 70, 245, 100452, 3, 9, 'L[71]->rp_pagos+L[72]->rp_pagos+L[73]->rp_pagos');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 70, 245, 100452, 3, 8, 'L[71]->rp_pagos+L[72]->rp_pagos+L[73]->rp_pagos');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 70, 245, 100452, 3, 7, 'L[71]->rp_pagos+L[72]->rp_pagos+L[73]->rp_pagos');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 70, 245, 100452, 3, 6, 'L[71]->rp_pagos+L[72]->rp_pagos+L[73]->rp_pagos');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 73, 245, 100452, 3, 11, '#vlrpag+#vlrpagnproc');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 73, 245, 100452, 3, 10, '#vlrpag+#vlrpagnproc');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 73, 245, 100452, 3, 9, '#vlrpag+#vlrpagnproc');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 73, 245, 100452, 3, 8, '#vlrpag+#vlrpagnproc');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 73, 245, 100452, 3, 7, '#vlrpag+#vlrpagnproc');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 73, 245, 100452, 3, 6, '#vlrpag+#vlrpagnproc');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 72, 245, 100452, 3, 11, '#vlrpag+#vlrpagnproc');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 72, 245, 100452, 3, 10, '#vlrpag+#vlrpagnproc');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 72, 245, 100452, 3, 9, '#vlrpag+#vlrpagnproc');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 72, 245, 100452, 3, 8, '#vlrpag+#vlrpagnproc');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 72, 245, 100452, 3, 7, '#vlrpag+#vlrpagnproc');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 72, 245, 100452, 3, 6, '#vlrpag+#vlrpagnproc');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 71, 245, 100452, 3, 11, '#vlrpag+#vlrpagnproc');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 71, 245, 100452, 3, 10, '#vlrpag+#vlrpagnproc');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 71, 245, 100452, 3, 9, '#vlrpag+#vlrpagnproc');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 71, 245, 100452, 3, 8, '#vlrpag+#vlrpagnproc');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 71, 245, 100452, 3, 7, '#vlrpag+#vlrpagnproc');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 71, 245, 100452, 3, 6, '#vlrpag+#vlrpagnproc');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 70, 245, 100451, 2, 11, 'L[71]->rp_liquidados+L[72]->rp_liquidados+L[73]->rp_liquidados');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 70, 245, 100451, 2, 10, 'L[71]->rp_liquidados+L[72]->rp_liquidados+L[73]->rp_liquidados');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 70, 245, 100451, 2, 9, 'L[71]->rp_liquidados+L[72]->rp_liquidados+L[73]->rp_liquidados');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 70, 245, 100451, 2, 8, 'L[71]->rp_liquidados+L[72]->rp_liquidados+L[73]->rp_liquidados');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 70, 245, 100451, 2, 7, 'L[71]->rp_liquidados+L[72]->rp_liquidados+L[73]->rp_liquidados');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 70, 245, 100451, 2, 6, 'L[71]->rp_liquidados+L[72]->rp_liquidados+L[73]->rp_liquidados');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 73, 245, 100451, 2, 11, '#vlrliq');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 73, 245, 100451, 2, 10, '#vlrliq');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 73, 245, 100451, 2, 9, '#vlrliq');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 73, 245, 100451, 2, 8, '#vlrliq');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 73, 245, 100451, 2, 7, '#vlrliq');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 73, 245, 100451, 2, 6, '#vlrliq');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 72, 245, 100451, 2, 11, '#vlrliq');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 72, 245, 100451, 2, 10, '#vlrliq');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 72, 245, 100451, 2, 9, '#vlrliq');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 72, 245, 100451, 2, 8, '#vlrliq');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 72, 245, 100451, 2, 7, '#vlrliq');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 72, 245, 100451, 2, 6, '#vlrliq');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 71, 245, 100451, 2, 11, '#vlrliq');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 71, 245, 100451, 2, 10, '#vlrliq');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 71, 245, 100451, 2, 9, '#vlrliq');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 71, 245, 100451, 2, 8, '#vlrliq');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 71, 245, 100451, 2, 7, '#vlrliq');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 71, 245, 100451, 2, 6, '#vlrliq');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 70, 245, 100450, 1, 11, 'L[71]->saldo_rp_inicial+L[72]->saldo_rp_inicial+L[73]->saldo_rp_inicial');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 70, 245, 100450, 1, 10, 'L[71]->saldo_rp_inicial+L[72]->saldo_rp_inicial+L[73]->saldo_rp_inicial');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 70, 245, 100450, 1, 9, 'L[71]->saldo_rp_inicial+L[72]->saldo_rp_inicial+L[73]->saldo_rp_inicial');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 70, 245, 100450, 1, 8, 'L[71]->saldo_rp_inicial+L[72]->saldo_rp_inicial+L[73]->saldo_rp_inicial');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 70, 245, 100450, 1, 7, 'L[71]->saldo_rp_inicial+L[72]->saldo_rp_inicial+L[73]->saldo_rp_inicial');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 70, 245, 100450, 1, 6, 'L[71]->saldo_rp_inicial+L[72]->saldo_rp_inicial+L[73]->saldo_rp_inicial');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 73, 245, 100450, 1, 11, '#e91_vlremp-#e91_vlranu-#e91_vlrpag');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 73, 245, 100450, 1, 10, '#e91_vlremp-#e91_vlranu-#e91_vlrpag');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 73, 245, 100450, 1, 9, '#e91_vlremp-#e91_vlranu-#e91_vlrpag');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 73, 245, 100450, 1, 8, '#e91_vlremp-#e91_vlranu-#e91_vlrpag');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 73, 245, 100450, 1, 7, '#e91_vlremp-#e91_vlranu-#e91_vlrpag');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 73, 245, 100450, 1, 6, '#e91_vlremp-#e91_vlranu-#e91_vlrpag');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 72, 245, 100450, 1, 11, '#e91_vlremp-#e91_vlranu-#e91_vlrpag');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 72, 245, 100450, 1, 10, '#e91_vlremp-#e91_vlranu-#e91_vlrpag');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 72, 245, 100450, 1, 9, '#e91_vlremp-#e91_vlranu-#e91_vlrpag');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 72, 245, 100450, 1, 8, '#e91_vlremp-#e91_vlranu-#e91_vlrpag');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 72, 245, 100450, 1, 7, '#e91_vlremp-#e91_vlranu-#e91_vlrpag');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 72, 245, 100450, 1, 6, '#e91_vlremp-#e91_vlranu-#e91_vlrpag');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 71, 245, 100450, 1, 11, '#e91_vlremp-#e91_vlranu-#e91_vlrpag');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 71, 245, 100450, 1, 10, '#e91_vlremp-#e91_vlranu-#e91_vlrpag');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 71, 245, 100450, 1, 9, '#e91_vlremp-#e91_vlranu-#e91_vlrpag');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 71, 245, 100450, 1, 8, '#e91_vlremp-#e91_vlranu-#e91_vlrpag');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 71, 245, 100450, 1, 7, '#e91_vlremp-#e91_vlranu-#e91_vlrpag');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 71, 245, 100450, 1, 6, '#e91_vlremp-#e91_vlranu-#e91_vlrpag');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 50, 245, 100449, 5, 11, '');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 50, 245, 100449, 5, 10, '');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 50, 245, 100449, 5, 9, '');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 50, 245, 100449, 5, 8, '');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 50, 245, 100449, 5, 7, '');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 50, 245, 100449, 5, 6, '');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 49, 245, 100449, 5, 11, '');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 49, 245, 100449, 5, 10, '');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 49, 245, 100449, 5, 9, '');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 49, 245, 100449, 5, 8, '');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 49, 245, 100449, 5, 7, '');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 49, 245, 100449, 5, 6, '');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 48, 245, 100449, 5, 11, '');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 48, 245, 100449, 5, 10, '');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 48, 245, 100449, 5, 9, '');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 48, 245, 100449, 5, 8, '');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 48, 245, 100449, 5, 7, '');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 48, 245, 100449, 5, 6, '');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 47, 245, 100449, 5, 11, '');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 47, 245, 100449, 5, 10, '');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 47, 245, 100449, 5, 9, '');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 47, 245, 100449, 5, 8, '');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 47, 245, 100449, 5, 7, '');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 47, 245, 100449, 5, 6, '');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 46, 245, 100449, 5, 11, '');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 46, 245, 100449, 5, 10, '');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 46, 245, 100449, 5, 9, '');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 46, 245, 100449, 5, 8, '');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 46, 245, 100449, 5, 7, '');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 46, 245, 100449, 5, 6, '');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 45, 245, 100449, 5, 11, '');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 99, 245, 176, 4, 7, 'L[100]->desppag+L[101]->desppag');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 99, 245, 176, 4, 6, 'L[100]->desppag+L[101]->desppag');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 99, 245, 156, 3, 11, 'L[100]->liq_atebim+L[101]->liq_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 99, 245, 156, 3, 10, 'L[100]->liq_atebim+L[101]->liq_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 99, 245, 156, 3, 9, 'L[100]->liq_atebim+L[101]->liq_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 99, 245, 156, 3, 8, 'L[100]->liq_atebim+L[101]->liq_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 99, 245, 156, 3, 7, 'L[100]->liq_atebim+L[101]->liq_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 99, 245, 156, 3, 6, 'L[100]->liq_atebim+L[101]->liq_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 99, 245, 155, 2, 11, 'L[100]->emp_atebim+L[101]->emp_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 99, 245, 155, 2, 10, 'L[100]->emp_atebim+L[101]->emp_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 99, 245, 155, 2, 9, 'L[100]->emp_atebim+L[101]->emp_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 99, 245, 155, 2, 8, 'L[100]->emp_atebim+L[101]->emp_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 99, 245, 155, 2, 7, 'L[100]->emp_atebim+L[101]->emp_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 99, 245, 155, 2, 6, 'L[100]->emp_atebim+L[101]->emp_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 99, 245, 154, 1, 11, 'L[100]->dot_atual+L[101]->dot_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 99, 245, 154, 1, 10, 'L[100]->dot_atual+L[101]->dot_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 99, 245, 154, 1, 9, 'L[100]->dot_atual+L[101]->dot_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 99, 245, 154, 1, 8, 'L[100]->dot_atual+L[101]->dot_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 99, 245, 154, 1, 7, 'L[100]->dot_atual+L[101]->dot_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 99, 245, 154, 1, 6, 'L[100]->dot_atual+L[101]->dot_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 94, 245, 188, 5, 11, 'L[95]->rp_nproc+L[96]->rp_nproc+L[97]->rp_nproc+L[98]->rp_nproc');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 94, 245, 188, 5, 10, 'L[95]->rp_nproc+L[96]->rp_nproc+L[97]->rp_nproc+L[98]->rp_nproc');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 94, 245, 188, 5, 9, 'L[95]->rp_nproc+L[96]->rp_nproc+L[97]->rp_nproc+L[98]->rp_nproc');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 94, 245, 188, 5, 8, 'L[95]->rp_nproc+L[96]->rp_nproc+L[97]->rp_nproc+L[98]->rp_nproc');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 94, 245, 188, 5, 7, 'L[95]->rp_nproc+L[96]->rp_nproc+L[97]->rp_nproc+L[98]->rp_nproc');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 94, 245, 188, 5, 6, 'L[95]->rp_nproc+L[96]->rp_nproc+L[97]->rp_nproc+L[98]->rp_nproc');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 94, 245, 176, 4, 11, 'L[95]->desppag+L[96]->desppag+L[97]->desppag+L[98]->desppag');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 94, 245, 176, 4, 10, 'L[95]->desppag+L[96]->desppag+L[97]->desppag+L[98]->desppag');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 94, 245, 176, 4, 9, 'L[95]->desppag+L[96]->desppag+L[97]->desppag+L[98]->desppag');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 94, 245, 176, 4, 8, 'L[95]->desppag+L[96]->desppag+L[97]->desppag+L[98]->desppag');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 94, 245, 176, 4, 7, 'L[95]->desppag+L[96]->desppag+L[97]->desppag+L[98]->desppag');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 94, 245, 176, 4, 6, 'L[95]->desppag+L[96]->desppag+L[97]->desppag+L[98]->desppag');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 94, 245, 156, 3, 11, 'L[95]->liq_atebim+L[96]->liq_atebim+L[97]->liq_atebim+L[98]->liq_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 94, 245, 156, 3, 10, 'L[95]->liq_atebim+L[96]->liq_atebim+L[97]->liq_atebim+L[98]->liq_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 94, 245, 156, 3, 9, 'L[95]->liq_atebim+L[96]->liq_atebim+L[97]->liq_atebim+L[98]->liq_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 94, 245, 156, 3, 8, 'L[95]->liq_atebim+L[96]->liq_atebim+L[97]->liq_atebim+L[98]->liq_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 94, 245, 156, 3, 7, 'L[95]->liq_atebim+L[96]->liq_atebim+L[97]->liq_atebim+L[98]->liq_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 94, 245, 156, 3, 6, 'L[95]->liq_atebim+L[96]->liq_atebim+L[97]->liq_atebim+L[98]->liq_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 94, 245, 155, 2, 11, 'L[95]->emp_atebim+L[96]->emp_atebim+L[97]->emp_atebim+L[98]->emp_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 94, 245, 155, 2, 10, 'L[95]->emp_atebim+L[96]->emp_atebim+L[97]->emp_atebim+L[98]->emp_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 94, 245, 155, 2, 9, 'L[95]->emp_atebim+L[96]->emp_atebim+L[97]->emp_atebim+L[98]->emp_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 94, 245, 155, 2, 8, 'L[95]->emp_atebim+L[96]->emp_atebim+L[97]->emp_atebim+L[98]->emp_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 94, 245, 155, 2, 7, 'L[95]->emp_atebim+L[96]->emp_atebim+L[97]->emp_atebim+L[98]->emp_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 94, 245, 155, 2, 6, 'L[95]->emp_atebim+L[96]->emp_atebim+L[97]->emp_atebim+L[98]->emp_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 94, 245, 154, 1, 11, 'L[95]->dot_atual+L[96]->dot_atual+L[97]->dot_atual+L[98]->dot_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 94, 245, 154, 1, 10, 'L[95]->dot_atual+L[96]->dot_atual+L[97]->dot_atual+L[98]->dot_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 94, 245, 154, 1, 9, 'L[95]->dot_atual+L[96]->dot_atual+L[97]->dot_atual+L[98]->dot_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 94, 245, 154, 1, 8, 'L[95]->dot_atual+L[96]->dot_atual+L[97]->dot_atual+L[98]->dot_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 94, 245, 154, 1, 7, 'L[95]->dot_atual+L[96]->dot_atual+L[97]->dot_atual+L[98]->dot_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 94, 245, 154, 1, 6, 'L[95]->dot_atual+L[96]->dot_atual+L[97]->dot_atual+L[98]->dot_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 93, 245, 188, 5, 11, 'F[44]+F[62]+F[92]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 93, 245, 188, 5, 10, 'F[44]+F[62]+F[92]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 93, 245, 188, 5, 9, 'F[44]+F[62]+F[92]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 93, 245, 188, 5, 8, 'F[44]+F[62]+F[92]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 93, 245, 188, 5, 7, 'F[44]+F[62]+F[92]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 93, 245, 188, 5, 6, 'F[44]+F[62]+F[92]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 93, 245, 176, 4, 11, 'F[44]+F[62]+F[92]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 93, 245, 176, 4, 10, 'F[44]+F[62]+F[92]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 55, 245, 100455, 1, 8, 'L[56]->vlr_superavit_ex_ant+L[57]->vlr_superavit_ex_ant');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 55, 245, 100455, 1, 7, 'L[56]->vlr_superavit_ex_ant+L[57]->vlr_superavit_ex_ant');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 55, 245, 100455, 1, 6, 'L[56]->vlr_superavit_ex_ant+L[57]->vlr_superavit_ex_ant');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 57, 245, 100460, 6, 11, '');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 57, 245, 100460, 6, 10, '');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 57, 245, 100460, 6, 9, '');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 57, 245, 100460, 6, 8, '');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 57, 245, 100460, 6, 7, '');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 57, 245, 100460, 6, 6, '');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 57, 245, 100459, 5, 11, '');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 57, 245, 100459, 5, 10, '');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 57, 245, 100459, 5, 9, '');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 57, 245, 100459, 5, 8, '');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 57, 245, 100459, 5, 7, '');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 57, 245, 100459, 5, 6, '');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 57, 245, 100458, 4, 11, '');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 57, 245, 100458, 4, 10, '');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 57, 245, 100458, 4, 9, '');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 57, 245, 100458, 4, 8, '');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 57, 245, 100458, 4, 7, '');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 57, 245, 100458, 4, 6, '');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 57, 245, 100457, 3, 11, '');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 57, 245, 100457, 3, 10, '');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 57, 245, 100457, 3, 9, '');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 57, 245, 100457, 3, 8, '');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 57, 245, 100457, 3, 7, '');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 57, 245, 100457, 3, 6, '');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 57, 245, 100456, 2, 11, '');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 57, 245, 100456, 2, 10, '');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 57, 245, 100456, 2, 9, '');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 57, 245, 100456, 2, 8, '');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 57, 245, 100456, 2, 7, '');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 57, 245, 100456, 2, 6, '');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 57, 245, 100455, 1, 11, '');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 57, 245, 100455, 1, 10, '');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 57, 245, 100455, 1, 9, '');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 57, 245, 100455, 1, 8, '');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 57, 245, 100455, 1, 7, '');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 57, 245, 100455, 1, 6, '');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 56, 245, 100460, 6, 11, '');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 56, 245, 100460, 6, 10, '');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 56, 245, 100460, 6, 9, '');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 56, 245, 100460, 6, 8, '');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 56, 245, 100460, 6, 7, '');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 56, 245, 100460, 6, 6, '');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 56, 245, 100459, 5, 11, '');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 93, 245, 176, 4, 9, 'F[44]+F[62]+F[92]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 93, 245, 176, 4, 8, 'F[44]+F[62]+F[92]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 93, 245, 176, 4, 7, 'F[44]+F[62]+F[92]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 93, 245, 176, 4, 6, 'F[44]+F[62]+F[92]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 93, 245, 156, 3, 11, 'F[44]+F[62]+F[92]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 93, 245, 156, 3, 10, 'F[44]+F[62]+F[92]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 93, 245, 156, 3, 9, 'F[44]+F[62]+F[92]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 93, 245, 156, 3, 8, 'F[44]+F[62]+F[92]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 93, 245, 156, 3, 7, 'F[44]+F[62]+F[92]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 93, 245, 156, 3, 6, 'F[44]+F[62]+F[92]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 93, 245, 155, 2, 11, 'F[44]+F[62]+F[92]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 93, 245, 155, 2, 10, 'F[44]+F[62]+F[92]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 93, 245, 155, 2, 9, 'F[44]+F[62]+F[92]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 93, 245, 155, 2, 8, 'F[44]+F[62]+F[92]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 93, 245, 155, 2, 7, 'F[44]+F[62]+F[92]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 93, 245, 155, 2, 6, 'F[44]+F[62]+F[92]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 93, 245, 154, 1, 11, 'F[44]+F[62]+F[92]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 93, 245, 154, 1, 10, 'F[44]+F[62]+F[92]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 93, 245, 154, 1, 9, 'F[44]+F[62]+F[92]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 93, 245, 154, 1, 8, 'F[44]+F[62]+F[92]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 93, 245, 154, 1, 7, 'F[44]+F[62]+F[92]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 93, 245, 154, 1, 6, 'F[44]+F[62]+F[92]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 92, 245, 188, 5, 11, 'F[85]+L[88]->rp_nproc+L[89]->rp_nproc+L[90]->rp_nproc+L[91]->rp_nproc');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 92, 245, 188, 5, 10, 'F[85]+L[88]->rp_nproc+L[89]->rp_nproc+L[90]->rp_nproc+L[91]->rp_nproc');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 92, 245, 188, 5, 9, 'F[85]+L[88]->rp_nproc+L[89]->rp_nproc+L[90]->rp_nproc+L[91]->rp_nproc');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 92, 245, 188, 5, 8, 'F[85]+L[88]->rp_nproc+L[89]->rp_nproc+L[90]->rp_nproc+L[91]->rp_nproc');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 92, 245, 188, 5, 7, 'F[85]+L[88]->rp_nproc+L[89]->rp_nproc+L[90]->rp_nproc+L[91]->rp_nproc');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 92, 245, 188, 5, 6, 'F[85]+L[88]->rp_nproc+L[89]->rp_nproc+L[90]->rp_nproc+L[91]->rp_nproc');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 92, 245, 176, 4, 11, 'F[85]+L[88]->desppag+L[89]->desppag+L[90]->desppag+L[91]->desppag');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 92, 245, 176, 4, 10, 'F[85]+L[88]->desppag+L[89]->desppag+L[90]->desppag+L[91]->desppag');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 92, 245, 176, 4, 9, 'F[85]+L[88]->desppag+L[89]->desppag+L[90]->desppag+L[91]->desppag');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 92, 245, 176, 4, 8, 'F[85]+L[88]->desppag+L[89]->desppag+L[90]->desppag+L[91]->desppag');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 92, 245, 176, 4, 7, 'F[85]+L[88]->desppag+L[89]->desppag+L[90]->desppag+L[91]->desppag');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 92, 245, 176, 4, 6, 'F[85]+L[88]->desppag+L[89]->desppag+L[90]->desppag+L[91]->desppag');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 92, 245, 156, 3, 11, 'F[85]+L[88]->liq_atebim+L[89]->liq_atebim+L[90]->liq_atebim+L[91]->liq_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 92, 245, 156, 3, 10, 'F[85]+L[88]->liq_atebim+L[89]->liq_atebim+L[90]->liq_atebim+L[91]->liq_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 92, 245, 156, 3, 9, 'F[85]+L[88]->liq_atebim+L[89]->liq_atebim+L[90]->liq_atebim+L[91]->liq_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 92, 245, 156, 3, 8, 'F[85]+L[88]->liq_atebim+L[89]->liq_atebim+L[90]->liq_atebim+L[91]->liq_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 92, 245, 156, 3, 7, 'F[85]+L[88]->liq_atebim+L[89]->liq_atebim+L[90]->liq_atebim+L[91]->liq_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 92, 245, 156, 3, 6, 'F[85]+L[88]->liq_atebim+L[89]->liq_atebim+L[90]->liq_atebim+L[91]->liq_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 92, 245, 155, 2, 11, 'F[85]+L[88]->emp_atebim+L[89]->emp_atebim+L[90]->emp_atebim+L[91]->emp_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 92, 245, 155, 2, 10, 'F[85]+L[88]->emp_atebim+L[89]->emp_atebim+L[90]->emp_atebim+L[91]->emp_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 92, 245, 155, 2, 9, 'F[85]+L[88]->emp_atebim+L[89]->emp_atebim+L[90]->emp_atebim+L[91]->emp_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 92, 245, 155, 2, 8, 'F[85]+L[88]->emp_atebim+L[89]->emp_atebim+L[90]->emp_atebim+L[91]->emp_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 92, 245, 155, 2, 7, 'F[85]+L[88]->emp_atebim+L[89]->emp_atebim+L[90]->emp_atebim+L[91]->emp_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 92, 245, 155, 2, 6, 'F[85]+L[88]->emp_atebim+L[89]->emp_atebim+L[90]->emp_atebim+L[91]->emp_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 92, 245, 154, 1, 11, 'F[85]+L[88]->dot_atual+L[89]->dot_atual+L[90]->dot_atual+L[91]->dot_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 92, 245, 154, 1, 10, 'F[85]+L[88]->dot_atual+L[89]->dot_atual+L[90]->dot_atual+L[91]->dot_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 92, 245, 154, 1, 9, 'F[85]+L[88]->dot_atual+L[89]->dot_atual+L[90]->dot_atual+L[91]->dot_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 92, 245, 154, 1, 8, 'F[85]+L[88]->dot_atual+L[89]->dot_atual+L[90]->dot_atual+L[91]->dot_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 92, 245, 154, 1, 7, 'F[85]+L[88]->dot_atual+L[89]->dot_atual+L[90]->dot_atual+L[91]->dot_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 92, 245, 154, 1, 6, 'F[85]+L[88]->dot_atual+L[89]->dot_atual+L[90]->dot_atual+L[91]->dot_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 85, 245, 188, 5, 11, 'L[86]->rp_nproc+L[87]->rp_nproc');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 85, 245, 188, 5, 10, 'L[86]->rp_nproc+L[87]->rp_nproc');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 85, 245, 188, 5, 9, 'L[86]->rp_nproc+L[87]->rp_nproc');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 85, 245, 188, 5, 8, 'L[86]->rp_nproc+L[87]->rp_nproc');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 85, 245, 188, 5, 7, 'L[86]->rp_nproc+L[87]->rp_nproc');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 85, 245, 188, 5, 6, 'L[86]->rp_nproc+L[87]->rp_nproc');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 85, 245, 176, 4, 11, 'L[86]->desppag+L[87]->desppag');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 85, 245, 176, 4, 10, 'L[86]->desppag+L[87]->desppag');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 85, 245, 176, 4, 9, 'L[86]->desppag+L[87]->desppag');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 85, 245, 176, 4, 8, 'L[86]->desppag+L[87]->desppag');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 85, 245, 176, 4, 7, 'L[86]->desppag+L[87]->desppag');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 85, 245, 176, 4, 6, 'L[86]->desppag+L[87]->desppag');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 85, 245, 156, 3, 11, 'L[86]->liq_atebim+L[87]->liq_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 85, 245, 156, 3, 10, 'L[86]->liq_atebim+L[87]->liq_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 85, 245, 156, 3, 9, 'L[86]->liq_atebim+L[87]->liq_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 85, 245, 156, 3, 8, 'L[86]->liq_atebim+L[87]->liq_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 85, 245, 156, 3, 7, 'L[86]->liq_atebim+L[87]->liq_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 85, 245, 156, 3, 6, 'L[86]->liq_atebim+L[87]->liq_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 85, 245, 155, 2, 11, 'L[86]->emp_atebim+L[87]->emp_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 85, 245, 155, 2, 10, 'L[86]->emp_atebim+L[87]->emp_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 85, 245, 155, 2, 9, 'L[86]->emp_atebim+L[87]->emp_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 85, 245, 155, 2, 8, 'L[86]->emp_atebim+L[87]->emp_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 85, 245, 155, 2, 7, 'L[86]->emp_atebim+L[87]->emp_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 85, 245, 155, 2, 6, 'L[86]->emp_atebim+L[87]->emp_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 85, 245, 154, 1, 11, 'L[86]->dot_atual+L[87]->dot_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 85, 245, 154, 1, 10, 'L[86]->dot_atual+L[87]->dot_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 85, 245, 154, 1, 9, 'L[86]->dot_atual+L[87]->dot_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 85, 245, 154, 1, 8, 'L[86]->dot_atual+L[87]->dot_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 85, 245, 154, 1, 7, 'L[86]->dot_atual+L[87]->dot_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 85, 245, 154, 1, 6, 'L[86]->dot_atual+L[87]->dot_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 84, 245, 152, 2, 11, 'F[74]+L[80]->rec_atebim+L[81]->rec_atebim+L[82]->rec_atebim+L[83]->rec_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 84, 245, 152, 2, 10, 'F[74]+L[80]->rec_atebim+L[81]->rec_atebim+L[82]->rec_atebim+L[83]->rec_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 84, 245, 152, 2, 9, 'F[74]+L[80]->rec_atebim+L[81]->rec_atebim+L[82]->rec_atebim+L[83]->rec_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 84, 245, 152, 2, 8, 'F[74]+L[80]->rec_atebim+L[81]->rec_atebim+L[82]->rec_atebim+L[83]->rec_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 84, 245, 152, 2, 7, 'F[74]+L[80]->rec_atebim+L[81]->rec_atebim+L[82]->rec_atebim+L[83]->rec_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 84, 245, 152, 2, 6, 'F[74]+L[80]->rec_atebim+L[81]->rec_atebim+L[82]->rec_atebim+L[83]->rec_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 84, 245, 151, 1, 11, 'F[74]+L[80]->prev_atual+L[81]->prev_atual+L[82]->prev_atual+L[83]->prev_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 84, 245, 151, 1, 10, 'F[74]+L[80]->prev_atual+L[81]->prev_atual+L[82]->prev_atual+L[83]->prev_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 84, 245, 151, 1, 9, 'F[74]+L[80]->prev_atual+L[81]->prev_atual+L[82]->prev_atual+L[83]->prev_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 84, 245, 151, 1, 8, 'F[74]+L[80]->prev_atual+L[81]->prev_atual+L[82]->prev_atual+L[83]->prev_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 84, 245, 151, 1, 7, 'F[74]+L[80]->prev_atual+L[81]->prev_atual+L[82]->prev_atual+L[83]->prev_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 84, 245, 151, 1, 6, 'F[74]+L[80]->prev_atual+L[81]->prev_atual+L[82]->prev_atual+L[83]->prev_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 74, 245, 152, 2, 11, 'L[75]->rec_atebim+L[76]->rec_atebim+L[77]->rec_atebim+L[78]->rec_atebim+L[79]->rec_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 74, 245, 152, 2, 10, 'L[75]->rec_atebim+L[76]->rec_atebim+L[77]->rec_atebim+L[78]->rec_atebim+L[79]->rec_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 74, 245, 152, 2, 9, 'L[75]->rec_atebim+L[76]->rec_atebim+L[77]->rec_atebim+L[78]->rec_atebim+L[79]->rec_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 74, 245, 152, 2, 8, 'L[75]->rec_atebim+L[76]->rec_atebim+L[77]->rec_atebim+L[78]->rec_atebim+L[79]->rec_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 74, 245, 152, 2, 7, 'L[75]->rec_atebim+L[76]->rec_atebim+L[77]->rec_atebim+L[78]->rec_atebim+L[79]->rec_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 74, 245, 152, 2, 6, 'L[75]->rec_atebim+L[76]->rec_atebim+L[77]->rec_atebim+L[78]->rec_atebim+L[79]->rec_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 74, 245, 151, 1, 11, 'L[75]->prev_atual+L[76]->prev_atual+L[77]->prev_atual+L[78]->prev_atual+L[79]->prev_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 74, 245, 151, 1, 10, 'L[75]->prev_atual+L[76]->prev_atual+L[77]->prev_atual+L[78]->prev_atual+L[79]->prev_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 74, 245, 151, 1, 9, 'L[75]->prev_atual+L[76]->prev_atual+L[77]->prev_atual+L[78]->prev_atual+L[79]->prev_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 74, 245, 151, 1, 8, 'L[75]->prev_atual+L[76]->prev_atual+L[77]->prev_atual+L[78]->prev_atual+L[79]->prev_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 74, 245, 151, 1, 7, 'L[75]->prev_atual+L[76]->prev_atual+L[77]->prev_atual+L[78]->prev_atual+L[79]->prev_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 74, 245, 151, 1, 6, 'L[75]->prev_atual+L[76]->prev_atual+L[77]->prev_atual+L[78]->prev_atual+L[79]->prev_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 58, 245, 154, 1, 8, 'L[59]->dot_atual+L[60]->dot_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 58, 245, 154, 1, 7, 'L[59]->dot_atual+L[60]->dot_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 58, 245, 154, 1, 6, 'L[59]->dot_atual+L[60]->dot_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 45, 245, 100449, 5, 10, '');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 45, 245, 100449, 5, 9, '');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 45, 245, 100449, 5, 8, '');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 45, 245, 100449, 5, 7, '');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 45, 245, 100449, 5, 6, '');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 99, 245, 188, 5, 11, 'L[100]->rp_nproc+L[101]->rp_nproc');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 99, 245, 188, 5, 10, 'L[100]->rp_nproc+L[101]->rp_nproc');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 99, 245, 188, 5, 9, 'L[100]->rp_nproc+L[101]->rp_nproc');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 99, 245, 188, 5, 8, 'L[100]->rp_nproc+L[101]->rp_nproc');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 99, 245, 188, 5, 7, 'L[100]->rp_nproc+L[101]->rp_nproc');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 99, 245, 188, 5, 6, 'L[100]->rp_nproc+L[101]->rp_nproc');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 99, 245, 176, 4, 11, 'L[100]->desppag+L[101]->desppag');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 99, 245, 176, 4, 10, 'L[100]->desppag+L[101]->desppag');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 99, 245, 176, 4, 9, 'L[100]->desppag+L[101]->desppag');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 99, 245, 176, 4, 8, 'L[100]->desppag+L[101]->desppag');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 44, 245, 188, 5, 11, 'F[34]+F[39]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 44, 245, 188, 5, 10, 'F[34]+F[39]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 44, 245, 188, 5, 9, 'F[34]+F[39]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 44, 245, 188, 5, 8, 'F[34]+F[39]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 44, 245, 188, 5, 7, 'F[34]+F[39]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 44, 245, 188, 5, 6, 'F[34]+F[39]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 44, 245, 176, 4, 11, 'F[34]+F[39]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 44, 245, 176, 4, 10, 'F[34]+F[39]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 44, 245, 176, 4, 9, 'F[34]+F[39]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 44, 245, 176, 4, 8, 'F[34]+F[39]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 44, 245, 176, 4, 7, 'F[34]+F[39]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 44, 245, 176, 4, 6, 'F[34]+F[39]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 44, 245, 156, 3, 11, 'F[34]+F[39]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 44, 245, 156, 3, 10, 'F[34]+F[39]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 44, 245, 156, 3, 9, 'F[34]+F[39]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 44, 245, 156, 3, 8, 'F[34]+F[39]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 44, 245, 156, 3, 7, 'F[34]+F[39]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 44, 245, 156, 3, 6, 'F[34]+F[39]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 44, 245, 155, 2, 11, 'F[34]+F[39]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 44, 245, 155, 2, 10, 'F[34]+F[39]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 44, 245, 155, 2, 9, 'F[34]+F[39]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 44, 245, 155, 2, 8, 'F[34]+F[39]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 44, 245, 155, 2, 7, 'F[34]+F[39]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 44, 245, 155, 2, 6, 'F[34]+F[39]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 44, 245, 154, 1, 11, 'F[34]+F[39]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 44, 245, 154, 1, 10, 'F[34]+F[39]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 44, 245, 154, 1, 9, 'F[34]+F[39]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 44, 245, 154, 1, 8, 'F[34]+F[39]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 44, 245, 154, 1, 7, 'F[34]+F[39]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 44, 245, 154, 1, 6, 'F[34]+F[39]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 40, 245, 188, 5, 11, 'L[41]->rp_nproc+L[42]->rp_nproc');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 40, 245, 188, 5, 10, 'L[41]->rp_nproc+L[42]->rp_nproc');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 40, 245, 188, 5, 9, 'L[41]->rp_nproc+L[42]->rp_nproc');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 40, 245, 188, 5, 8, 'L[41]->rp_nproc+L[42]->rp_nproc');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 40, 245, 188, 5, 7, 'L[41]->rp_nproc+L[42]->rp_nproc');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 40, 245, 188, 5, 6, 'L[41]->rp_nproc+L[42]->rp_nproc');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 40, 245, 176, 4, 11, 'L[41]->desppag+L[42]->desppag');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 40, 245, 176, 4, 10, 'L[41]->desppag+L[42]->desppag');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 40, 245, 176, 4, 9, 'L[41]->desppag+L[42]->desppag');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 40, 245, 176, 4, 8, 'L[41]->desppag+L[42]->desppag');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 40, 245, 176, 4, 7, 'L[41]->desppag+L[42]->desppag');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 40, 245, 176, 4, 6, 'L[41]->desppag+L[42]->desppag');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 40, 245, 156, 3, 11, 'L[41]->liq_atebim+L[42]->liq_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 40, 245, 156, 3, 10, 'L[41]->liq_atebim+L[42]->liq_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 40, 245, 156, 3, 9, 'L[41]->liq_atebim+L[42]->liq_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 40, 245, 156, 3, 8, 'L[41]->liq_atebim+L[42]->liq_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 40, 245, 156, 3, 7, 'L[41]->liq_atebim+L[42]->liq_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 40, 245, 156, 3, 6, 'L[41]->liq_atebim+L[42]->liq_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 40, 245, 155, 2, 11, 'L[41]->emp_atebim+L[42]->emp_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 40, 245, 155, 2, 10, 'L[41]->emp_atebim+L[42]->emp_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 40, 245, 155, 2, 9, 'L[41]->emp_atebim+L[42]->emp_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 40, 245, 155, 2, 8, 'L[41]->emp_atebim+L[42]->emp_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 40, 245, 155, 2, 7, 'L[41]->emp_atebim+L[42]->emp_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 40, 245, 155, 2, 6, 'L[41]->emp_atebim+L[42]->emp_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 40, 245, 154, 1, 11, 'L[41]->dot_atual+L[42]->dot_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 40, 245, 154, 1, 10, 'L[41]->dot_atual+L[42]->dot_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 40, 245, 154, 1, 9, 'L[41]->dot_atual+L[42]->dot_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 40, 245, 154, 1, 8, 'L[41]->dot_atual+L[42]->dot_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 40, 245, 154, 1, 7, 'L[41]->dot_atual+L[42]->dot_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 40, 245, 154, 1, 6, 'L[41]->dot_atual+L[42]->dot_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 39, 245, 188, 5, 11, 'F[40]+L[43]->rp_nproc');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 39, 245, 188, 5, 10, 'F[40]+L[43]->rp_nproc');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 39, 245, 188, 5, 9, 'F[40]+L[43]->rp_nproc');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 39, 245, 188, 5, 8, 'F[40]+L[43]->rp_nproc');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 39, 245, 188, 5, 7, 'F[40]+L[43]->rp_nproc');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 39, 245, 188, 5, 6, 'F[40]+L[43]->rp_nproc');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 39, 245, 176, 4, 11, 'F[40]+L[43]->desppag');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 39, 245, 176, 4, 10, 'F[40]+L[43]->desppag');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 39, 245, 176, 4, 9, 'F[40]+L[43]->desppag');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 39, 245, 176, 4, 8, 'F[40]+L[43]->desppag');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 39, 245, 176, 4, 7, 'F[40]+L[43]->desppag');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 39, 245, 176, 4, 6, 'F[40]+L[43]->desppag');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 39, 245, 156, 3, 11, 'F[40]+L[43]->liq_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 39, 245, 156, 3, 10, 'F[40]+L[43]->liq_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 39, 245, 156, 3, 9, 'F[40]+L[43]->liq_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 39, 245, 156, 3, 8, 'F[40]+L[43]->liq_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 39, 245, 156, 3, 7, 'F[40]+L[43]->liq_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 39, 245, 156, 3, 6, 'F[40]+L[43]->liq_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 39, 245, 155, 2, 11, 'F[40]+L[43]->emp_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 39, 245, 155, 2, 10, 'F[40]+L[43]->emp_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 39, 245, 155, 2, 9, 'F[40]+L[43]->emp_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 39, 245, 155, 2, 8, 'F[40]+L[43]->emp_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 39, 245, 155, 2, 7, 'F[40]+L[43]->emp_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 39, 245, 155, 2, 6, 'F[40]+L[43]->emp_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 39, 245, 154, 1, 11, 'F[40]+L[43]->dot_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 39, 245, 154, 1, 10, 'F[40]+L[43]->dot_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 39, 245, 154, 1, 9, 'F[40]+L[43]->dot_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 39, 245, 154, 1, 8, 'F[40]+L[43]->dot_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 39, 245, 154, 1, 7, 'F[40]+L[43]->dot_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 39, 245, 154, 1, 6, 'F[40]+L[43]->dot_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 35, 245, 188, 5, 11, 'L[36]->rp_nproc+L[37]->rp_nproc');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 35, 245, 188, 5, 10, 'L[36]->rp_nproc+L[37]->rp_nproc');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 35, 245, 188, 5, 9, 'L[36]->rp_nproc+L[37]->rp_nproc');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 35, 245, 188, 5, 8, 'L[36]->rp_nproc+L[37]->rp_nproc');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 35, 245, 188, 5, 7, 'L[36]->rp_nproc+L[37]->rp_nproc');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 35, 245, 188, 5, 6, 'L[36]->rp_nproc+L[37]->rp_nproc');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 35, 245, 176, 4, 11, 'L[36]->desppag+L[37]->desppag');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 35, 245, 176, 4, 10, 'L[36]->desppag+L[37]->desppag');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 35, 245, 176, 4, 9, 'L[36]->desppag+L[37]->desppag');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 35, 245, 176, 4, 8, 'L[36]->desppag+L[37]->desppag');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 35, 245, 176, 4, 7, 'L[36]->desppag+L[37]->desppag');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 35, 245, 176, 4, 6, 'L[36]->desppag+L[37]->desppag');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 35, 245, 156, 3, 11, 'L[36]->liq_atebim+L[37]->liq_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 35, 245, 156, 3, 10, 'L[36]->liq_atebim+L[37]->liq_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 35, 245, 156, 3, 9, 'L[36]->liq_atebim+L[37]->liq_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 35, 245, 156, 3, 8, 'L[36]->liq_atebim+L[37]->liq_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 35, 245, 156, 3, 7, 'L[36]->liq_atebim+L[37]->liq_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 35, 245, 156, 3, 6, 'L[36]->liq_atebim+L[37]->liq_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 35, 245, 155, 2, 11, 'L[36]->emp_atebim+L[37]->emp_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 35, 245, 155, 2, 10, 'L[36]->emp_atebim+L[37]->emp_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 35, 245, 155, 2, 9, 'L[36]->emp_atebim+L[37]->emp_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 35, 245, 155, 2, 8, 'L[36]->emp_atebim+L[37]->emp_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 35, 245, 155, 2, 7, 'L[36]->emp_atebim+L[37]->emp_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 35, 245, 155, 2, 6, 'L[36]->emp_atebim+L[37]->emp_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 35, 245, 154, 1, 11, 'L[36]->dot_atual+L[37]->dot_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 35, 245, 154, 1, 10, 'L[36]->dot_atual+L[37]->dot_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 35, 245, 154, 1, 9, 'L[36]->dot_atual+L[37]->dot_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 35, 245, 154, 1, 8, 'L[36]->dot_atual+L[37]->dot_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 35, 245, 154, 1, 7, 'L[36]->dot_atual+L[37]->dot_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 35, 245, 154, 1, 6, 'L[36]->dot_atual+L[37]->dot_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 34, 245, 188, 5, 11, 'F[35]+L[38]->rp_nproc');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 34, 245, 188, 5, 10, 'F[35]+L[38]->rp_nproc');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 34, 245, 188, 5, 9, 'F[35]+L[38]->rp_nproc');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 34, 245, 154, 1, 11, 'F[35]+L[38]->dot_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 34, 245, 154, 1, 10, 'F[35]+L[38]->dot_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 34, 245, 154, 1, 9, 'F[35]+L[38]->dot_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 34, 245, 154, 1, 8, 'F[35]+L[38]->dot_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 34, 245, 154, 1, 7, 'F[35]+L[38]->dot_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 34, 245, 154, 1, 6, 'F[35]+L[38]->dot_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 33, 245, 103, 1, 11, 'F[19]+F[30]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 33, 245, 103, 1, 10, 'F[19]+F[30]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 33, 245, 103, 1, 9, 'F[19]+F[30]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 33, 245, 103, 1, 8, 'F[19]+F[30]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 33, 245, 103, 1, 7, 'F[19]+F[30]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 33, 245, 103, 1, 6, 'F[19]+F[30]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 26, 245, 152, 2, 11, 'L[27]->rec_atebim+L[28]->rec_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 26, 245, 152, 2, 10, 'L[27]->rec_atebim+L[28]->rec_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 26, 245, 152, 2, 9, 'L[27]->rec_atebim+L[28]->rec_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 26, 245, 152, 2, 8, 'L[27]->rec_atebim+L[28]->rec_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 26, 245, 152, 2, 7, 'L[27]->rec_atebim+L[28]->rec_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 26, 245, 152, 2, 6, 'L[27]->rec_atebim+L[28]->rec_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 26, 245, 151, 1, 11, 'L[27]->prev_atual+L[28]->prev_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 26, 245, 151, 1, 10, 'L[27]->prev_atual+L[28]->prev_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 26, 245, 151, 1, 9, 'L[27]->prev_atual+L[28]->prev_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 26, 245, 151, 1, 8, 'L[27]->prev_atual+L[28]->prev_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 26, 245, 151, 1, 7, 'L[27]->prev_atual+L[28]->prev_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 26, 245, 151, 1, 6, 'L[27]->prev_atual+L[28]->prev_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 23, 245, 152, 2, 11, 'L[24]->rec_atebim+L[25]->rec_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 23, 245, 152, 2, 10, 'L[24]->rec_atebim+L[25]->rec_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 23, 245, 152, 2, 9, 'L[24]->rec_atebim+L[25]->rec_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 23, 245, 152, 2, 8, 'L[24]->rec_atebim+L[25]->rec_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 23, 245, 152, 2, 7, 'L[24]->rec_atebim+L[25]->rec_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 23, 245, 152, 2, 6, 'L[24]->rec_atebim+L[25]->rec_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 23, 245, 151, 1, 11, 'L[24]->prev_atual+L[25]->prev_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 23, 245, 151, 1, 10, 'L[24]->prev_atual+L[25]->prev_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 23, 245, 151, 1, 9, 'L[24]->prev_atual+L[25]->prev_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 23, 245, 151, 1, 8, 'L[24]->prev_atual+L[25]->prev_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 23, 245, 151, 1, 7, 'L[24]->prev_atual+L[25]->prev_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 23, 245, 151, 1, 6, 'L[24]->prev_atual+L[25]->prev_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 20, 245, 152, 2, 11, 'L[21]->rec_atebim+L[22]->rec_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 20, 245, 152, 2, 10, 'L[21]->rec_atebim+L[22]->rec_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 20, 245, 152, 2, 9, 'L[21]->rec_atebim+L[22]->rec_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 20, 245, 152, 2, 8, 'L[21]->rec_atebim+L[22]->rec_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 62, 245, 188, 5, 11, 'F[58]+L[61]->rp_nproc');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 62, 245, 188, 5, 10, 'F[58]+L[61]->rp_nproc');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 62, 245, 188, 5, 9, 'F[58]+L[61]->rp_nproc');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 62, 245, 188, 5, 8, 'F[58]+L[61]->rp_nproc');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 62, 245, 188, 5, 7, 'F[58]+L[61]->rp_nproc');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 62, 245, 188, 5, 6, 'F[58]+L[61]->rp_nproc');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 62, 245, 176, 4, 11, 'F[58]+L[61]->desppag');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 62, 245, 176, 4, 10, 'F[58]+L[61]->desppag');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 62, 245, 176, 4, 9, 'F[58]+L[61]->desppag');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 62, 245, 176, 4, 8, 'F[58]+L[61]->desppag');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 62, 245, 176, 4, 7, 'F[58]+L[61]->desppag');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 62, 245, 176, 4, 6, 'F[58]+L[61]->desppag');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 62, 245, 156, 3, 11, 'F[58]+L[61]->liq_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 62, 245, 156, 3, 10, 'F[58]+L[61]->liq_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 62, 245, 156, 3, 9, 'F[58]+L[61]->liq_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 62, 245, 156, 3, 8, 'F[58]+L[61]->liq_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 62, 245, 156, 3, 7, 'F[58]+L[61]->liq_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 62, 245, 156, 3, 6, 'F[58]+L[61]->liq_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 62, 245, 155, 2, 11, 'F[58]+L[61]->emp_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 62, 245, 155, 2, 10, 'F[58]+L[61]->emp_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 62, 245, 155, 2, 9, 'F[58]+L[61]->emp_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 62, 245, 155, 2, 8, 'F[58]+L[61]->emp_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 62, 245, 155, 2, 7, 'F[58]+L[61]->emp_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 62, 245, 155, 2, 6, 'F[58]+L[61]->emp_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 62, 245, 154, 1, 11, 'F[58]+L[61]->dot_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 62, 245, 154, 1, 10, 'F[58]+L[61]->dot_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 62, 245, 154, 1, 9, 'F[58]+L[61]->dot_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 62, 245, 154, 1, 8, 'F[58]+L[61]->dot_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 62, 245, 154, 1, 7, 'F[58]+L[61]->dot_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 62, 245, 154, 1, 6, 'F[58]+L[61]->dot_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 58, 245, 188, 5, 11, 'L[59]->rp_nproc+L[60]->rp_nproc');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 58, 245, 188, 5, 10, 'L[59]->rp_nproc+L[60]->rp_nproc');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 58, 245, 188, 5, 9, 'L[59]->rp_nproc+L[60]->rp_nproc');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 58, 245, 188, 5, 8, 'L[59]->rp_nproc+L[60]->rp_nproc');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 58, 245, 188, 5, 7, 'L[59]->rp_nproc+L[60]->rp_nproc');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 58, 245, 188, 5, 6, 'L[59]->rp_nproc+L[60]->rp_nproc');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 58, 245, 176, 4, 11, 'L[59]->desppag+L[60]->desppag');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 58, 245, 176, 4, 10, 'L[59]->desppag+L[60]->desppag');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 58, 245, 176, 4, 9, 'L[59]->desppag+L[60]->desppag');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 58, 245, 176, 4, 8, 'L[59]->desppag+L[60]->desppag');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 58, 245, 176, 4, 7, 'L[59]->desppag+L[60]->desppag');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 58, 245, 176, 4, 6, 'L[59]->desppag+L[60]->desppag');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 58, 245, 156, 3, 11, 'L[59]->liq_atebim+L[60]->liq_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 58, 245, 156, 3, 10, 'L[59]->liq_atebim+L[60]->liq_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 58, 245, 156, 3, 9, 'L[59]->liq_atebim+L[60]->liq_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 58, 245, 156, 3, 8, 'L[59]->liq_atebim+L[60]->liq_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 58, 245, 156, 3, 7, 'L[59]->liq_atebim+L[60]->liq_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 58, 245, 156, 3, 6, 'L[59]->liq_atebim+L[60]->liq_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 58, 245, 155, 2, 11, 'L[59]->emp_atebim+L[60]->emp_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 58, 245, 155, 2, 10, 'L[59]->emp_atebim+L[60]->emp_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 58, 245, 155, 2, 9, 'L[59]->emp_atebim+L[60]->emp_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 58, 245, 155, 2, 8, 'L[59]->emp_atebim+L[60]->emp_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 58, 245, 155, 2, 7, 'L[59]->emp_atebim+L[60]->emp_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 58, 245, 155, 2, 6, 'L[59]->emp_atebim+L[60]->emp_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 58, 245, 154, 1, 11, 'L[59]->dot_atual+L[60]->dot_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 58, 245, 154, 1, 10, 'L[59]->dot_atual+L[60]->dot_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 58, 245, 154, 1, 9, 'L[59]->dot_atual+L[60]->dot_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 20, 245, 152, 2, 7, 'L[21]->rec_atebim+L[22]->rec_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 20, 245, 152, 2, 6, 'L[21]->rec_atebim+L[22]->rec_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 20, 245, 151, 1, 11, 'L[21]->prev_atual+L[22]->prev_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 20, 245, 151, 1, 10, 'L[21]->prev_atual+L[22]->prev_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 20, 245, 151, 1, 9, 'L[21]->prev_atual+L[22]->prev_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 20, 245, 151, 1, 8, 'L[21]->prev_atual+L[22]->prev_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 20, 245, 151, 1, 7, 'L[21]->prev_atual+L[22]->prev_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 20, 245, 151, 1, 6, 'L[21]->prev_atual+L[22]->prev_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 19, 245, 152, 2, 11, 'F[20]+F[23]+F[26]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 19, 245, 152, 2, 10, 'F[20]+F[23]+F[26]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 19, 245, 152, 2, 9, 'F[20]+F[23]+F[26]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 19, 245, 152, 2, 8, 'F[20]+F[23]+F[26]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 19, 245, 152, 2, 7, 'F[20]+F[23]+F[26]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 19, 245, 152, 2, 6, 'F[20]+F[23]+F[26]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 19, 245, 151, 1, 11, 'F[20]+F[23]+F[26]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 19, 245, 151, 1, 10, 'F[20]+F[23]+F[26]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 19, 245, 151, 1, 9, 'F[20]+F[23]+F[26]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 19, 245, 151, 1, 8, 'F[20]+F[23]+F[26]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 19, 245, 151, 1, 7, 'F[20]+F[23]+F[26]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 19, 245, 151, 1, 6, 'F[20]+F[23]+F[26]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 17, 245, 152, 2, 11, '(L[8]->rec_atebim+L[10]->rec_atebim+L[11]->rec_atebim+L[12]->rec_atebim+L[13]->rec_atebim)*0.20');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 17, 245, 152, 2, 10, '(L[8]->rec_atebim+L[10]->rec_atebim+L[11]->rec_atebim+L[12]->rec_atebim+L[13]->rec_atebim)*0.20');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 17, 245, 152, 2, 9, '(L[8]->rec_atebim+L[10]->rec_atebim+L[11]->rec_atebim+L[12]->rec_atebim+L[13]->rec_atebim)*0.20');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 17, 245, 152, 2, 8, '(L[8]->rec_atebim+L[10]->rec_atebim+L[11]->rec_atebim+L[12]->rec_atebim+L[13]->rec_atebim)*0.20');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 17, 245, 152, 2, 7, '(L[8]->rec_atebim+L[10]->rec_atebim+L[11]->rec_atebim+L[12]->rec_atebim+L[13]->rec_atebim)*0.20');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 17, 245, 152, 2, 6, '(L[8]->rec_atebim+L[10]->rec_atebim+L[11]->rec_atebim+L[12]->rec_atebim+L[13]->rec_atebim)*0.20');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 17, 245, 151, 1, 11, '(L[8]->prev_atual+L[10]->prev_atual+L[11]->prev_atual+L[12]->prev_atual+L[13]->prev_atual)*0.20');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 17, 245, 151, 1, 10, '(L[8]->prev_atual+L[10]->prev_atual+L[11]->prev_atual+L[12]->prev_atual+L[13]->prev_atual)*0.20');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 17, 245, 151, 1, 9, '(L[8]->prev_atual+L[10]->prev_atual+L[11]->prev_atual+L[12]->prev_atual+L[13]->prev_atual)*0.20');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 17, 245, 151, 1, 8, '(L[8]->prev_atual+L[10]->prev_atual+L[11]->prev_atual+L[12]->prev_atual+L[13]->prev_atual)*0.20');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 17, 245, 151, 1, 7, '(L[8]->prev_atual+L[10]->prev_atual+L[11]->prev_atual+L[12]->prev_atual+L[13]->prev_atual)*0.20');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 17, 245, 151, 1, 6, '(L[8]->prev_atual+L[10]->prev_atual+L[11]->prev_atual+L[12]->prev_atual+L[13]->prev_atual)*0.20');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 16, 245, 152, 2, 11, 'F[1]+F[6]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 16, 245, 152, 2, 10, 'F[1]+F[6]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 16, 245, 152, 2, 9, 'F[1]+F[6]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 16, 245, 152, 2, 8, 'F[1]+F[6]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 16, 245, 152, 2, 7, 'F[1]+F[6]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 16, 245, 152, 2, 6, 'F[1]+F[6]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 16, 245, 151, 1, 11, 'F[1]+F[6]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 16, 245, 151, 1, 10, 'F[1]+F[6]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 16, 245, 151, 1, 9, 'F[1]+F[6]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 16, 245, 151, 1, 8, 'F[1]+F[6]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 16, 245, 151, 1, 7, 'F[1]+F[6]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 16, 245, 151, 1, 6, 'F[1]+F[6]');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 7, 245, 152, 2, 11, 'L[8]->rec_atebim+L[9]->rec_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 7, 245, 152, 2, 10, 'L[8]->rec_atebim+L[9]->rec_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 7, 245, 152, 2, 9, 'L[8]->rec_atebim+L[9]->rec_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 7, 245, 152, 2, 8, 'L[8]->rec_atebim+L[9]->rec_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 7, 245, 152, 2, 7, 'L[8]->rec_atebim+L[9]->rec_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 7, 245, 152, 2, 6, 'L[8]->rec_atebim+L[9]->rec_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 7, 245, 151, 1, 11, 'L[8]->prev_atual+L[9]->prev_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 7, 245, 151, 1, 10, 'L[8]->prev_atual+L[9]->prev_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 7, 245, 151, 1, 9, 'L[8]->prev_atual+L[9]->prev_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 7, 245, 151, 1, 8, 'L[8]->prev_atual+L[9]->prev_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 7, 245, 151, 1, 7, 'L[8]->prev_atual+L[9]->prev_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 7, 245, 151, 1, 6, 'L[8]->prev_atual+L[9]->prev_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 6, 245, 152, 2, 11, 'L[8]->rec_atebim+L[9]->rec_atebim+L[10]->rec_atebim+L[11]->rec_atebim+L[12]->rec_atebim+L[13]->rec_atebim+L[14]->rec_atebim+L[15]->rec_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 6, 245, 152, 2, 10, 'L[8]->rec_atebim+L[9]->rec_atebim+L[10]->rec_atebim+L[11]->rec_atebim+L[12]->rec_atebim+L[13]->rec_atebim+L[14]->rec_atebim+L[15]->rec_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 6, 245, 152, 2, 9, 'L[8]->rec_atebim+L[9]->rec_atebim+L[10]->rec_atebim+L[11]->rec_atebim+L[12]->rec_atebim+L[13]->rec_atebim+L[14]->rec_atebim+L[15]->rec_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 6, 245, 152, 2, 8, 'L[8]->rec_atebim+L[9]->rec_atebim+L[10]->rec_atebim+L[11]->rec_atebim+L[12]->rec_atebim+L[13]->rec_atebim+L[14]->rec_atebim+L[15]->rec_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 6, 245, 152, 2, 7, 'L[8]->rec_atebim+L[9]->rec_atebim+L[10]->rec_atebim+L[11]->rec_atebim+L[12]->rec_atebim+L[13]->rec_atebim+L[14]->rec_atebim+L[15]->rec_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 6, 245, 152, 2, 6, 'L[8]->rec_atebim+L[9]->rec_atebim+L[10]->rec_atebim+L[11]->rec_atebim+L[12]->rec_atebim+L[13]->rec_atebim+L[14]->rec_atebim+L[15]->rec_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 6, 245, 151, 1, 11, 'L[8]->prev_atual+L[9]->prev_atual+L[10]->prev_atual+L[11]->prev_atual+L[12]->prev_atual+L[13]->prev_atual+L[14]->prev_atual+L[15]->prev_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 6, 245, 151, 1, 10, 'L[8]->prev_atual+L[9]->prev_atual+L[10]->prev_atual+L[11]->prev_atual+L[12]->prev_atual+L[13]->prev_atual+L[14]->prev_atual+L[15]->prev_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 6, 245, 151, 1, 9, 'L[8]->prev_atual+L[9]->prev_atual+L[10]->prev_atual+L[11]->prev_atual+L[12]->prev_atual+L[13]->prev_atual+L[14]->prev_atual+L[15]->prev_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 6, 245, 151, 1, 8, 'L[8]->prev_atual+L[9]->prev_atual+L[10]->prev_atual+L[11]->prev_atual+L[12]->prev_atual+L[13]->prev_atual+L[14]->prev_atual+L[15]->prev_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 6, 245, 151, 1, 7, 'L[8]->prev_atual+L[9]->prev_atual+L[10]->prev_atual+L[11]->prev_atual+L[12]->prev_atual+L[13]->prev_atual+L[14]->prev_atual+L[15]->prev_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 6, 245, 151, 1, 6, 'L[8]->prev_atual+L[9]->prev_atual+L[10]->prev_atual+L[11]->prev_atual+L[12]->prev_atual+L[13]->prev_atual+L[14]->prev_atual+L[15]->prev_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 1, 245, 152, 2, 11, 'L[2]->rec_atebim+L[3]->rec_atebim+L[4]->rec_atebim+L[5]->rec_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 1, 245, 152, 2, 10, 'L[2]->rec_atebim+L[3]->rec_atebim+L[4]->rec_atebim+L[5]->rec_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 1, 245, 152, 2, 9, 'L[2]->rec_atebim+L[3]->rec_atebim+L[4]->rec_atebim+L[5]->rec_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 1, 245, 152, 2, 8, 'L[2]->rec_atebim+L[3]->rec_atebim+L[4]->rec_atebim+L[5]->rec_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 1, 245, 152, 2, 7, 'L[2]->rec_atebim+L[3]->rec_atebim+L[4]->rec_atebim+L[5]->rec_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 1, 245, 152, 2, 6, 'L[2]->rec_atebim+L[3]->rec_atebim+L[4]->rec_atebim+L[5]->rec_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 1, 245, 151, 1, 11, 'L[2]->prev_atual+L[3]->prev_atual+L[4]->prev_atual+L[5]->prev_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 1, 245, 151, 1, 10, 'L[2]->prev_atual+L[3]->prev_atual+L[4]->prev_atual+L[5]->prev_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 1, 245, 151, 1, 9, 'L[2]->prev_atual+L[3]->prev_atual+L[4]->prev_atual+L[5]->prev_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 1, 245, 151, 1, 8, 'L[2]->prev_atual+L[3]->prev_atual+L[4]->prev_atual+L[5]->prev_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 1, 245, 151, 1, 7, 'L[2]->prev_atual+L[3]->prev_atual+L[4]->prev_atual+L[5]->prev_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 1, 245, 151, 1, 6, 'L[2]->prev_atual+L[3]->prev_atual+L[4]->prev_atual+L[5]->prev_atual');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 101, 245, 188, 5, 11, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 101, 245, 188, 5, 10, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 101, 245, 188, 5, 9, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 101, 245, 188, 5, 8, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 101, 245, 188, 5, 7, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 101, 245, 188, 5, 6, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 101, 245, 176, 4, 11, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 101, 245, 176, 4, 10, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 101, 245, 176, 4, 9, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 101, 245, 176, 4, 8, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 101, 245, 176, 4, 7, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 101, 245, 176, 4, 6, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 101, 245, 156, 3, 11, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 101, 245, 156, 3, 10, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 101, 245, 156, 3, 9, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 101, 245, 156, 3, 8, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 101, 245, 156, 3, 7, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 101, 245, 156, 3, 6, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 101, 245, 155, 2, 11, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 101, 245, 155, 2, 10, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 101, 245, 155, 2, 9, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 101, 245, 155, 2, 8, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 101, 245, 155, 2, 7, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 101, 245, 155, 2, 6, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 101, 245, 154, 1, 11, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 101, 245, 154, 1, 10, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 101, 245, 154, 1, 9, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 101, 245, 154, 1, 8, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 101, 245, 154, 1, 7, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 101, 245, 154, 1, 6, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 100, 245, 156, 3, 9, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 100, 245, 156, 3, 8, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 100, 245, 156, 3, 7, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 100, 245, 156, 3, 6, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 100, 245, 155, 2, 11, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 100, 245, 155, 2, 10, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 100, 245, 155, 2, 9, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 100, 245, 155, 2, 8, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 100, 245, 155, 2, 7, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 100, 245, 155, 2, 6, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 98, 245, 188, 5, 11, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 98, 245, 188, 5, 10, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 98, 245, 188, 5, 9, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 98, 245, 188, 5, 8, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 98, 245, 188, 5, 7, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 98, 245, 188, 5, 6, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 98, 245, 176, 4, 11, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 98, 245, 176, 4, 10, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 98, 245, 176, 4, 9, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 98, 245, 176, 4, 8, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 98, 245, 176, 4, 7, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 98, 245, 176, 4, 6, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 98, 245, 156, 3, 11, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 98, 245, 156, 3, 10, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 98, 245, 156, 3, 9, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 98, 245, 156, 3, 8, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 98, 245, 156, 3, 7, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 98, 245, 156, 3, 6, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 34, 245, 188, 5, 8, 'F[35]+L[38]->rp_nproc');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 34, 245, 188, 5, 7, 'F[35]+L[38]->rp_nproc');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 34, 245, 188, 5, 6, 'F[35]+L[38]->rp_nproc');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 34, 245, 176, 4, 11, 'F[35]+L[38]->desppag');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 34, 245, 176, 4, 10, 'F[35]+L[38]->desppag');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 34, 245, 176, 4, 9, 'F[35]+L[38]->desppag');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 34, 245, 176, 4, 8, 'F[35]+L[38]->desppag');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 34, 245, 176, 4, 7, 'F[35]+L[38]->desppag');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 34, 245, 176, 4, 6, 'F[35]+L[38]->desppag');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 34, 245, 156, 3, 11, 'F[35]+L[38]->liq_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 34, 245, 156, 3, 10, 'F[35]+L[38]->liq_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 34, 245, 156, 3, 9, 'F[35]+L[38]->liq_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 34, 245, 156, 3, 8, 'F[35]+L[38]->liq_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 34, 245, 156, 3, 7, 'F[35]+L[38]->liq_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 34, 245, 156, 3, 6, 'F[35]+L[38]->liq_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 34, 245, 155, 2, 11, 'F[35]+L[38]->emp_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 34, 245, 155, 2, 10, 'F[35]+L[38]->emp_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 34, 245, 155, 2, 9, 'F[35]+L[38]->emp_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 34, 245, 155, 2, 8, 'F[35]+L[38]->emp_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 34, 245, 155, 2, 7, 'F[35]+L[38]->emp_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 34, 245, 155, 2, 6, 'F[35]+L[38]->emp_atebim');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 98, 245, 155, 2, 11, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 98, 245, 155, 2, 10, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 98, 245, 155, 2, 9, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 98, 245, 155, 2, 8, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 98, 245, 155, 2, 7, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 98, 245, 155, 2, 6, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 98, 245, 154, 1, 11, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 98, 245, 154, 1, 10, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 98, 245, 154, 1, 9, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 98, 245, 154, 1, 8, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 98, 245, 154, 1, 7, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 98, 245, 154, 1, 6, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 97, 245, 188, 5, 11, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 97, 245, 188, 5, 10, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 97, 245, 188, 5, 9, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 97, 245, 188, 5, 8, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 97, 245, 188, 5, 7, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 97, 245, 188, 5, 6, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 97, 245, 176, 4, 11, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 97, 245, 176, 4, 10, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 97, 245, 176, 4, 9, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 97, 245, 176, 4, 8, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 97, 245, 176, 4, 7, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 97, 245, 176, 4, 6, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 97, 245, 156, 3, 11, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 97, 245, 156, 3, 10, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 97, 245, 156, 3, 9, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 97, 245, 156, 3, 8, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 97, 245, 156, 3, 7, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 97, 245, 156, 3, 6, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 97, 245, 155, 2, 11, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 97, 245, 155, 2, 10, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 97, 245, 155, 2, 9, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 97, 245, 155, 2, 8, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 97, 245, 155, 2, 7, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 97, 245, 155, 2, 6, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 97, 245, 154, 1, 11, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 97, 245, 154, 1, 10, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 97, 245, 154, 1, 9, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 97, 245, 154, 1, 8, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 97, 245, 154, 1, 7, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 97, 245, 154, 1, 6, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 96, 245, 188, 5, 11, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 96, 245, 188, 5, 10, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 96, 245, 188, 5, 9, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 96, 245, 188, 5, 8, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 96, 245, 188, 5, 7, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 96, 245, 188, 5, 6, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 96, 245, 176, 4, 11, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 96, 245, 176, 4, 10, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 96, 245, 176, 4, 9, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 96, 245, 176, 4, 8, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 96, 245, 176, 4, 7, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 96, 245, 176, 4, 6, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 96, 245, 156, 3, 11, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 96, 245, 156, 3, 10, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 96, 245, 156, 3, 9, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 96, 245, 156, 3, 8, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 96, 245, 156, 3, 7, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 96, 245, 156, 3, 6, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 96, 245, 155, 2, 11, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 96, 245, 155, 2, 10, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 96, 245, 155, 2, 9, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 96, 245, 155, 2, 8, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 96, 245, 155, 2, 7, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 96, 245, 155, 2, 6, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 96, 245, 154, 1, 11, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 96, 245, 154, 1, 10, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 96, 245, 154, 1, 9, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 96, 245, 154, 1, 8, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 96, 245, 154, 1, 7, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 96, 245, 154, 1, 6, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 95, 245, 188, 5, 11, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 95, 245, 188, 5, 10, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 95, 245, 188, 5, 9, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 95, 245, 188, 5, 8, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 95, 245, 188, 5, 7, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 95, 245, 188, 5, 6, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 95, 245, 176, 4, 11, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 95, 245, 176, 4, 10, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 95, 245, 176, 4, 9, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 95, 245, 176, 4, 8, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 95, 245, 176, 4, 7, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 95, 245, 176, 4, 6, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 95, 245, 156, 3, 11, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 95, 245, 156, 3, 10, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 95, 245, 156, 3, 9, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 95, 245, 156, 3, 8, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 95, 245, 156, 3, 7, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 95, 245, 156, 3, 6, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 95, 245, 155, 2, 11, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 95, 245, 155, 2, 10, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 95, 245, 155, 2, 9, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 95, 245, 155, 2, 8, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 95, 245, 155, 2, 7, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 95, 245, 155, 2, 6, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 95, 245, 154, 1, 11, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 95, 245, 154, 1, 10, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 95, 245, 154, 1, 9, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 95, 245, 154, 1, 8, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 95, 245, 154, 1, 7, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 95, 245, 154, 1, 6, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 89, 245, 188, 5, 9, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 89, 245, 188, 5, 8, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 89, 245, 188, 5, 7, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 89, 245, 188, 5, 6, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 89, 245, 176, 4, 11, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 89, 245, 176, 4, 10, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 89, 245, 176, 4, 9, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 89, 245, 176, 4, 8, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 89, 245, 176, 4, 7, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 89, 245, 176, 4, 6, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 89, 245, 156, 3, 11, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 89, 245, 156, 3, 10, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 89, 245, 156, 3, 9, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 89, 245, 156, 3, 8, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 89, 245, 156, 3, 7, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 89, 245, 156, 3, 6, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 89, 245, 155, 2, 11, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 89, 245, 155, 2, 10, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 89, 245, 155, 2, 9, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 89, 245, 155, 2, 8, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 89, 245, 155, 2, 7, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 89, 245, 155, 2, 6, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 89, 245, 154, 1, 11, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 89, 245, 154, 1, 10, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 89, 245, 154, 1, 9, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 89, 245, 154, 1, 8, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 89, 245, 154, 1, 7, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 89, 245, 154, 1, 6, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 88, 245, 188, 5, 11, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 88, 245, 188, 5, 10, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 88, 245, 188, 5, 9, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 88, 245, 188, 5, 8, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 88, 245, 188, 5, 7, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 88, 245, 188, 5, 6, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 88, 245, 176, 4, 11, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 88, 245, 176, 4, 10, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 88, 245, 176, 4, 9, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 88, 245, 176, 4, 8, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 88, 245, 176, 4, 7, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 88, 245, 176, 4, 6, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 88, 245, 156, 3, 11, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 88, 245, 156, 3, 10, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 88, 245, 156, 3, 9, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 88, 245, 156, 3, 8, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 88, 245, 156, 3, 7, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 88, 245, 156, 3, 6, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 88, 245, 155, 2, 11, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 88, 245, 155, 2, 10, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 88, 245, 155, 2, 9, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 88, 245, 155, 2, 8, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 88, 245, 155, 2, 7, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 88, 245, 155, 2, 6, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 88, 245, 154, 1, 11, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 88, 245, 154, 1, 10, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 88, 245, 154, 1, 9, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 88, 245, 154, 1, 8, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 88, 245, 154, 1, 7, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 88, 245, 154, 1, 6, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 100, 245, 188, 5, 11, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 100, 245, 188, 5, 10, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 100, 245, 188, 5, 9, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 100, 245, 188, 5, 8, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 100, 245, 188, 5, 7, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 100, 245, 188, 5, 6, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 100, 245, 176, 4, 11, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 100, 245, 176, 4, 10, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 100, 245, 176, 4, 9, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 100, 245, 176, 4, 8, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 100, 245, 176, 4, 7, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 100, 245, 176, 4, 6, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 100, 245, 156, 3, 11, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 100, 245, 156, 3, 10, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 100, 245, 154, 1, 11, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 100, 245, 154, 1, 10, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 100, 245, 154, 1, 9, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 100, 245, 154, 1, 8, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 100, 245, 154, 1, 7, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 100, 245, 154, 1, 6, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 87, 245, 188, 5, 11, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 87, 245, 188, 5, 10, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 87, 245, 188, 5, 9, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 87, 245, 188, 5, 8, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 87, 245, 188, 5, 7, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 87, 245, 188, 5, 6, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 87, 245, 176, 4, 11, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 87, 245, 176, 4, 10, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 87, 245, 176, 4, 9, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 87, 245, 176, 4, 8, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 87, 245, 176, 4, 7, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 87, 245, 176, 4, 6, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 87, 245, 156, 3, 11, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 87, 245, 156, 3, 10, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 87, 245, 156, 3, 9, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 87, 245, 156, 3, 8, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 87, 245, 156, 3, 7, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 87, 245, 156, 3, 6, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 87, 245, 155, 2, 11, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 87, 245, 155, 2, 10, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 87, 245, 155, 2, 9, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 87, 245, 155, 2, 8, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 87, 245, 155, 2, 7, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 87, 245, 155, 2, 6, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 87, 245, 154, 1, 11, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 87, 245, 154, 1, 10, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 87, 245, 154, 1, 9, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 87, 245, 154, 1, 8, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 87, 245, 154, 1, 7, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 87, 245, 154, 1, 6, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 86, 245, 188, 5, 11, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 86, 245, 188, 5, 10, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 86, 245, 188, 5, 9, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 86, 245, 188, 5, 8, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 86, 245, 188, 5, 7, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 86, 245, 188, 5, 6, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 86, 245, 176, 4, 11, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 86, 245, 176, 4, 10, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 86, 245, 176, 4, 9, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 86, 245, 176, 4, 8, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 86, 245, 176, 4, 7, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 86, 245, 176, 4, 6, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 86, 245, 156, 3, 11, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 86, 245, 156, 3, 10, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 86, 245, 156, 3, 9, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 86, 245, 156, 3, 8, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 86, 245, 156, 3, 7, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 86, 245, 156, 3, 6, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 86, 245, 155, 2, 11, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 86, 245, 155, 2, 10, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 86, 245, 155, 2, 9, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 86, 245, 155, 2, 8, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 86, 245, 155, 2, 7, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 86, 245, 155, 2, 6, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 86, 245, 154, 1, 11, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 86, 245, 154, 1, 10, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 86, 245, 154, 1, 9, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 86, 245, 154, 1, 8, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 86, 245, 154, 1, 7, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 86, 245, 154, 1, 6, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 83, 245, 152, 2, 11, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 83, 245, 152, 2, 10, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 83, 245, 152, 2, 9, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 83, 245, 152, 2, 8, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 83, 245, 152, 2, 7, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 83, 245, 152, 2, 6, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 83, 245, 151, 1, 11, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 83, 245, 151, 1, 10, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 83, 245, 151, 1, 9, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 83, 245, 151, 1, 8, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 83, 245, 151, 1, 7, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 83, 245, 151, 1, 6, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 82, 245, 152, 2, 11, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 82, 245, 152, 2, 10, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 82, 245, 152, 2, 9, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 82, 245, 152, 2, 8, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 82, 245, 152, 2, 7, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 82, 245, 152, 2, 6, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 82, 245, 151, 1, 11, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 82, 245, 151, 1, 10, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 82, 245, 151, 1, 9, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 82, 245, 151, 1, 8, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 82, 245, 151, 1, 7, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 82, 245, 151, 1, 6, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 81, 245, 152, 2, 11, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 81, 245, 152, 2, 10, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 81, 245, 152, 2, 9, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 81, 245, 152, 2, 8, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 81, 245, 152, 2, 7, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 81, 245, 152, 2, 6, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 81, 245, 151, 1, 11, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 81, 245, 151, 1, 10, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 81, 245, 151, 1, 9, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 81, 245, 151, 1, 8, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 81, 245, 151, 1, 7, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 81, 245, 151, 1, 6, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 80, 245, 152, 2, 11, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 80, 245, 152, 2, 10, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 80, 245, 152, 2, 9, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 80, 245, 152, 2, 8, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 80, 245, 152, 2, 7, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 80, 245, 152, 2, 6, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 80, 245, 151, 1, 11, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 80, 245, 151, 1, 10, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 80, 245, 151, 1, 9, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 80, 245, 151, 1, 8, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 80, 245, 151, 1, 7, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 80, 245, 151, 1, 6, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 79, 245, 152, 2, 11, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 79, 245, 152, 2, 10, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 79, 245, 152, 2, 9, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 79, 245, 152, 2, 8, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 79, 245, 152, 2, 7, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 79, 245, 152, 2, 6, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 79, 245, 151, 1, 11, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 79, 245, 151, 1, 10, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 76, 245, 151, 1, 11, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 76, 245, 151, 1, 10, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 76, 245, 151, 1, 9, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 76, 245, 151, 1, 8, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 76, 245, 151, 1, 7, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 76, 245, 151, 1, 6, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 75, 245, 152, 2, 11, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 75, 245, 152, 2, 10, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 75, 245, 152, 2, 9, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 75, 245, 152, 2, 8, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 75, 245, 152, 2, 7, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 75, 245, 152, 2, 6, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 75, 245, 151, 1, 11, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 75, 245, 151, 1, 10, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 75, 245, 151, 1, 9, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 75, 245, 151, 1, 8, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 75, 245, 151, 1, 7, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 75, 245, 151, 1, 6, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 67, 245, 103, 1, 11, '');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 67, 245, 103, 1, 10, '');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 67, 245, 103, 1, 9, '');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 67, 245, 103, 1, 8, '');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 67, 245, 103, 1, 7, '');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 67, 245, 103, 1, 6, '');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 66, 245, 103, 1, 11, '');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 66, 245, 103, 1, 10, '');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 66, 245, 103, 1, 9, '');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 66, 245, 103, 1, 8, '');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 66, 245, 103, 1, 7, '');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 66, 245, 103, 1, 6, '');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 65, 245, 103, 1, 11, '');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 65, 245, 103, 1, 10, '');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 65, 245, 103, 1, 9, '');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 65, 245, 103, 1, 8, '');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 65, 245, 103, 1, 7, '');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 65, 245, 103, 1, 6, '');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 64, 245, 103, 1, 11, '');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 64, 245, 103, 1, 10, '');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 64, 245, 103, 1, 9, '');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 64, 245, 103, 1, 8, '');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 91, 245, 188, 5, 11, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 91, 245, 188, 5, 10, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 91, 245, 188, 5, 9, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 91, 245, 188, 5, 8, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 91, 245, 188, 5, 7, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 91, 245, 188, 5, 6, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 91, 245, 176, 4, 11, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 91, 245, 176, 4, 10, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 91, 245, 176, 4, 9, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 91, 245, 176, 4, 8, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 91, 245, 176, 4, 7, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 91, 245, 176, 4, 6, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 91, 245, 156, 3, 11, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 91, 245, 156, 3, 10, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 91, 245, 156, 3, 9, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 91, 245, 156, 3, 8, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 91, 245, 156, 3, 7, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 91, 245, 156, 3, 6, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 91, 245, 155, 2, 11, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 91, 245, 155, 2, 10, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 91, 245, 155, 2, 9, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 91, 245, 155, 2, 8, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 91, 245, 155, 2, 7, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 91, 245, 155, 2, 6, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 91, 245, 154, 1, 11, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 91, 245, 154, 1, 10, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 91, 245, 154, 1, 9, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 91, 245, 154, 1, 8, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 91, 245, 154, 1, 7, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 91, 245, 154, 1, 6, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 90, 245, 188, 5, 11, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 90, 245, 188, 5, 10, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 90, 245, 188, 5, 9, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 90, 245, 188, 5, 8, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 90, 245, 188, 5, 7, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 90, 245, 188, 5, 6, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 90, 245, 176, 4, 11, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 90, 245, 176, 4, 10, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 90, 245, 176, 4, 9, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 90, 245, 176, 4, 8, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 90, 245, 176, 4, 7, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 90, 245, 176, 4, 6, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 90, 245, 156, 3, 11, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 90, 245, 156, 3, 10, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 90, 245, 156, 3, 9, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 90, 245, 156, 3, 8, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 90, 245, 156, 3, 7, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 90, 245, 156, 3, 6, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 90, 245, 155, 2, 11, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 90, 245, 155, 2, 10, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 90, 245, 155, 2, 9, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 90, 245, 155, 2, 8, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 90, 245, 155, 2, 7, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 90, 245, 155, 2, 6, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 90, 245, 154, 1, 11, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 90, 245, 154, 1, 10, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 90, 245, 154, 1, 9, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 90, 245, 154, 1, 8, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 90, 245, 154, 1, 7, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 90, 245, 154, 1, 6, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 89, 245, 188, 5, 11, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 89, 245, 188, 5, 10, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 64, 245, 103, 1, 7, '');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 64, 245, 103, 1, 6, '');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 63, 245, 103, 1, 11, '');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 63, 245, 103, 1, 10, '');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 63, 245, 103, 1, 9, '');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 63, 245, 103, 1, 8, '');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 63, 245, 103, 1, 7, '');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 63, 245, 103, 1, 6, '');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 61, 245, 188, 5, 11, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 61, 245, 188, 5, 10, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 61, 245, 188, 5, 9, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 61, 245, 188, 5, 8, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 61, 245, 188, 5, 7, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 61, 245, 188, 5, 6, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 61, 245, 176, 4, 11, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 61, 245, 176, 4, 10, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 61, 245, 176, 4, 9, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 61, 245, 176, 4, 8, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 61, 245, 176, 4, 7, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 61, 245, 176, 4, 6, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 61, 245, 156, 3, 11, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 61, 245, 156, 3, 10, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 61, 245, 156, 3, 9, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 61, 245, 156, 3, 8, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 61, 245, 156, 3, 7, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 61, 245, 156, 3, 6, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 61, 245, 155, 2, 11, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 61, 245, 155, 2, 10, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 61, 245, 155, 2, 9, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 61, 245, 155, 2, 8, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 61, 245, 155, 2, 7, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 61, 245, 155, 2, 6, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 61, 245, 154, 1, 11, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 61, 245, 154, 1, 10, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 61, 245, 154, 1, 9, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 61, 245, 154, 1, 8, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 61, 245, 154, 1, 7, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 61, 245, 154, 1, 6, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 60, 245, 188, 5, 11, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 60, 245, 188, 5, 10, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 60, 245, 188, 5, 9, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 60, 245, 188, 5, 8, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 60, 245, 188, 5, 7, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 60, 245, 188, 5, 6, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 60, 245, 176, 4, 11, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 60, 245, 176, 4, 10, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 60, 245, 176, 4, 9, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 60, 245, 176, 4, 8, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 60, 245, 176, 4, 7, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 60, 245, 176, 4, 6, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 60, 245, 156, 3, 11, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 60, 245, 156, 3, 10, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 60, 245, 156, 3, 9, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 60, 245, 156, 3, 8, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 60, 245, 156, 3, 7, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 60, 245, 156, 3, 6, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 60, 245, 155, 2, 11, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 60, 245, 155, 2, 10, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 60, 245, 155, 2, 9, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 60, 245, 155, 2, 8, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 60, 245, 155, 2, 7, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 60, 245, 155, 2, 6, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 60, 245, 154, 1, 11, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 60, 245, 154, 1, 10, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 60, 245, 154, 1, 9, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 60, 245, 154, 1, 8, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 60, 245, 154, 1, 7, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 60, 245, 154, 1, 6, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 59, 245, 188, 5, 11, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 59, 245, 188, 5, 10, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 59, 245, 188, 5, 9, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 59, 245, 188, 5, 8, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 59, 245, 188, 5, 7, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 59, 245, 188, 5, 6, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 59, 245, 176, 4, 11, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 59, 245, 176, 4, 10, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 59, 245, 176, 4, 9, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 59, 245, 176, 4, 8, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 59, 245, 176, 4, 7, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 59, 245, 176, 4, 6, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 59, 245, 156, 3, 11, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 59, 245, 156, 3, 10, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 59, 245, 156, 3, 9, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 59, 245, 156, 3, 8, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 59, 245, 156, 3, 7, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 59, 245, 156, 3, 6, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 59, 245, 155, 2, 11, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 59, 245, 155, 2, 10, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 59, 245, 155, 2, 9, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 59, 245, 155, 2, 8, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 59, 245, 155, 2, 7, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 59, 245, 155, 2, 6, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 59, 245, 154, 1, 11, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 59, 245, 154, 1, 10, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 59, 245, 154, 1, 9, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 59, 245, 154, 1, 8, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 59, 245, 154, 1, 7, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 59, 245, 154, 1, 6, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 50, 245, 176, 3, 10, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 50, 245, 176, 3, 9, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 50, 245, 176, 3, 8, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 50, 245, 176, 3, 7, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 50, 245, 176, 3, 6, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 50, 245, 156, 2, 11, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 50, 245, 156, 2, 10, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 50, 245, 156, 2, 9, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 50, 245, 156, 2, 8, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 50, 245, 156, 2, 7, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 50, 245, 156, 2, 6, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 50, 245, 155, 1, 11, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 50, 245, 155, 1, 10, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 50, 245, 155, 1, 9, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 50, 245, 155, 1, 8, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 50, 245, 155, 1, 7, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 50, 245, 155, 1, 6, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 49, 245, 188, 4, 11, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 49, 245, 188, 4, 10, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 49, 245, 188, 4, 9, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 49, 245, 188, 4, 8, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 49, 245, 188, 4, 7, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 49, 245, 188, 4, 6, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 49, 245, 176, 3, 11, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 49, 245, 176, 3, 10, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 49, 245, 176, 3, 9, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 49, 245, 176, 3, 8, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 49, 245, 176, 3, 7, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 49, 245, 176, 3, 6, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 49, 245, 156, 2, 11, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 49, 245, 156, 2, 10, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 49, 245, 156, 2, 9, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 49, 245, 156, 2, 8, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 49, 245, 156, 2, 7, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 49, 245, 156, 2, 6, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 49, 245, 155, 1, 11, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 49, 245, 155, 1, 10, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 49, 245, 155, 1, 9, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 49, 245, 155, 1, 8, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 49, 245, 155, 1, 7, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 49, 245, 155, 1, 6, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 48, 245, 188, 4, 11, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 48, 245, 188, 4, 10, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 48, 245, 188, 4, 9, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 48, 245, 188, 4, 8, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 48, 245, 188, 4, 7, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 48, 245, 188, 4, 6, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 48, 245, 176, 3, 11, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 48, 245, 176, 3, 10, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 48, 245, 176, 3, 9, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 48, 245, 176, 3, 8, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 48, 245, 176, 3, 7, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 48, 245, 176, 3, 6, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 48, 245, 156, 2, 11, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 48, 245, 156, 2, 10, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 48, 245, 156, 2, 9, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 48, 245, 156, 2, 8, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 48, 245, 156, 2, 7, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 79, 245, 151, 1, 9, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 79, 245, 151, 1, 8, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 79, 245, 151, 1, 7, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 79, 245, 151, 1, 6, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 78, 245, 152, 2, 11, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 78, 245, 152, 2, 10, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 78, 245, 152, 2, 9, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 78, 245, 152, 2, 8, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 78, 245, 152, 2, 7, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 78, 245, 152, 2, 6, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 78, 245, 151, 1, 11, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 78, 245, 151, 1, 10, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 78, 245, 151, 1, 9, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 78, 245, 151, 1, 8, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 78, 245, 151, 1, 7, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 78, 245, 151, 1, 6, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 77, 245, 152, 2, 11, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 77, 245, 152, 2, 10, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 77, 245, 152, 2, 9, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 77, 245, 152, 2, 8, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 77, 245, 152, 2, 7, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 77, 245, 152, 2, 6, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 77, 245, 151, 1, 11, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 77, 245, 151, 1, 10, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 77, 245, 151, 1, 9, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 77, 245, 151, 1, 8, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 77, 245, 151, 1, 7, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 77, 245, 151, 1, 6, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 76, 245, 152, 2, 11, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 76, 245, 152, 2, 10, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 76, 245, 152, 2, 9, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 76, 245, 152, 2, 8, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 76, 245, 152, 2, 7, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 76, 245, 152, 2, 6, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 48, 245, 156, 2, 6, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 48, 245, 155, 1, 11, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 48, 245, 155, 1, 10, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 48, 245, 155, 1, 9, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 48, 245, 155, 1, 8, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 48, 245, 155, 1, 7, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 48, 245, 155, 1, 6, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 47, 245, 188, 4, 11, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 47, 245, 188, 4, 10, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 47, 245, 188, 4, 9, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 47, 245, 188, 4, 8, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 47, 245, 188, 4, 7, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 47, 245, 188, 4, 6, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 47, 245, 176, 3, 11, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 47, 245, 176, 3, 10, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 47, 245, 176, 3, 9, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 47, 245, 176, 3, 8, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 47, 245, 176, 3, 7, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 47, 245, 176, 3, 6, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 47, 245, 156, 2, 11, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 47, 245, 156, 2, 10, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 47, 245, 156, 2, 9, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 47, 245, 156, 2, 8, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 47, 245, 156, 2, 7, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 47, 245, 156, 2, 6, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 47, 245, 155, 1, 11, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 47, 245, 155, 1, 10, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 47, 245, 155, 1, 9, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 47, 245, 155, 1, 8, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 47, 245, 155, 1, 7, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 47, 245, 155, 1, 6, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 46, 245, 188, 4, 11, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 46, 245, 188, 4, 10, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 46, 245, 188, 4, 9, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 46, 245, 188, 4, 8, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 46, 245, 188, 4, 7, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 46, 245, 188, 4, 6, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 46, 245, 176, 3, 11, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 46, 245, 176, 3, 10, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 46, 245, 176, 3, 9, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 46, 245, 176, 3, 8, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 46, 245, 176, 3, 7, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 46, 245, 176, 3, 6, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 46, 245, 156, 2, 11, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 46, 245, 156, 2, 10, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 46, 245, 156, 2, 9, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 46, 245, 156, 2, 8, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 46, 245, 156, 2, 7, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 46, 245, 156, 2, 6, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 46, 245, 155, 1, 11, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 46, 245, 155, 1, 10, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 46, 245, 155, 1, 9, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 46, 245, 155, 1, 8, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 46, 245, 155, 1, 7, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 46, 245, 155, 1, 6, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 45, 245, 188, 4, 11, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 45, 245, 188, 4, 10, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 45, 245, 188, 4, 9, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 45, 245, 188, 4, 8, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 45, 245, 188, 4, 7, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 45, 245, 188, 4, 6, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 45, 245, 176, 3, 11, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 45, 245, 176, 3, 10, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 45, 245, 176, 3, 9, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 45, 245, 176, 3, 8, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 45, 245, 176, 3, 7, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 45, 245, 176, 3, 6, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 45, 245, 156, 2, 11, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 45, 245, 156, 2, 10, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 45, 245, 156, 2, 9, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 45, 245, 156, 2, 8, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 45, 245, 156, 2, 7, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 45, 245, 156, 2, 6, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 45, 245, 155, 1, 11, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 45, 245, 155, 1, 10, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 45, 245, 155, 1, 9, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 45, 245, 155, 1, 8, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 45, 245, 155, 1, 7, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 45, 245, 155, 1, 6, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 43, 245, 188, 5, 11, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 43, 245, 188, 5, 10, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 43, 245, 188, 5, 9, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 43, 245, 188, 5, 8, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 43, 245, 188, 5, 7, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 43, 245, 188, 5, 6, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 43, 245, 176, 4, 11, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 43, 245, 176, 4, 10, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 43, 245, 176, 4, 9, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 43, 245, 176, 4, 8, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 43, 245, 176, 4, 7, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 43, 245, 176, 4, 6, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 43, 245, 156, 3, 11, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 43, 245, 156, 3, 10, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 43, 245, 156, 3, 9, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 43, 245, 156, 3, 8, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 43, 245, 156, 3, 7, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 43, 245, 156, 3, 6, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 43, 245, 155, 2, 11, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 43, 245, 155, 2, 10, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 43, 245, 155, 2, 9, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 43, 245, 155, 2, 8, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 43, 245, 155, 2, 7, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 43, 245, 155, 2, 6, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 43, 245, 154, 1, 11, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 43, 245, 154, 1, 10, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 43, 245, 154, 1, 9, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 43, 245, 154, 1, 8, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 43, 245, 154, 1, 7, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 43, 245, 154, 1, 6, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 42, 245, 188, 5, 11, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 42, 245, 188, 5, 10, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 42, 245, 188, 5, 9, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 42, 245, 188, 5, 8, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 42, 245, 188, 5, 7, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 42, 245, 188, 5, 6, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 42, 245, 176, 4, 11, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 42, 245, 176, 4, 10, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 42, 245, 176, 4, 9, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 42, 245, 176, 4, 8, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 42, 245, 176, 4, 7, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 42, 245, 176, 4, 6, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 42, 245, 156, 3, 11, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 42, 245, 156, 3, 10, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 42, 245, 156, 3, 9, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 42, 245, 156, 3, 8, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 42, 245, 156, 3, 7, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 42, 245, 156, 3, 6, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 42, 245, 155, 2, 11, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 36, 245, 188, 5, 7, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 36, 245, 188, 5, 6, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 36, 245, 176, 4, 11, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 36, 245, 176, 4, 10, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 36, 245, 176, 4, 9, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 36, 245, 176, 4, 8, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 36, 245, 176, 4, 7, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 36, 245, 176, 4, 6, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 36, 245, 156, 3, 11, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 36, 245, 156, 3, 10, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 36, 245, 156, 3, 9, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 36, 245, 156, 3, 8, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 36, 245, 156, 3, 7, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 36, 245, 156, 3, 6, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 36, 245, 155, 2, 11, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 36, 245, 155, 2, 10, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 36, 245, 155, 2, 9, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 36, 245, 155, 2, 8, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 36, 245, 155, 2, 7, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 36, 245, 155, 2, 6, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 36, 245, 154, 1, 11, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 36, 245, 154, 1, 10, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 36, 245, 154, 1, 9, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 36, 245, 154, 1, 8, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 36, 245, 154, 1, 7, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 36, 245, 154, 1, 6, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 50, 245, 188, 4, 11, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 50, 245, 188, 4, 10, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 50, 245, 188, 4, 9, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 50, 245, 188, 4, 8, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 50, 245, 188, 4, 7, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 50, 245, 188, 4, 6, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 50, 245, 176, 3, 11, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 32, 245, 103, 1, 11, '');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 32, 245, 103, 1, 10, '');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 32, 245, 103, 1, 9, '');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 32, 245, 103, 1, 8, '');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 32, 245, 103, 1, 7, '');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 32, 245, 103, 1, 6, '');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 31, 245, 103, 1, 11, '');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 31, 245, 103, 1, 10, '');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 31, 245, 103, 1, 9, '');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 31, 245, 103, 1, 8, '');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 31, 245, 103, 1, 7, '');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 31, 245, 103, 1, 6, '');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 28, 245, 152, 2, 11, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 28, 245, 152, 2, 10, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 28, 245, 152, 2, 9, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 28, 245, 152, 2, 8, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 28, 245, 152, 2, 7, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 28, 245, 152, 2, 6, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 28, 245, 151, 1, 11, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 28, 245, 151, 1, 10, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 28, 245, 151, 1, 9, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 28, 245, 151, 1, 8, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 28, 245, 151, 1, 7, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 28, 245, 151, 1, 6, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 27, 245, 152, 2, 11, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 27, 245, 152, 2, 10, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 27, 245, 152, 2, 9, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 27, 245, 152, 2, 8, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 27, 245, 152, 2, 7, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 27, 245, 152, 2, 6, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 27, 245, 151, 1, 11, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 27, 245, 151, 1, 10, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 27, 245, 151, 1, 9, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 27, 245, 151, 1, 8, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 27, 245, 151, 1, 7, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 27, 245, 151, 1, 6, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 25, 245, 152, 2, 11, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 25, 245, 152, 2, 10, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 25, 245, 152, 2, 9, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 25, 245, 152, 2, 8, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 25, 245, 152, 2, 7, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 25, 245, 152, 2, 6, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 25, 245, 151, 1, 11, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 25, 245, 151, 1, 10, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 25, 245, 151, 1, 9, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 25, 245, 151, 1, 8, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 25, 245, 151, 1, 7, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 25, 245, 151, 1, 6, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 24, 245, 152, 2, 11, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 24, 245, 152, 2, 10, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 24, 245, 152, 2, 9, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 24, 245, 152, 2, 8, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 24, 245, 152, 2, 7, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 24, 245, 152, 2, 6, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 24, 245, 151, 1, 11, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 24, 245, 151, 1, 10, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 24, 245, 151, 1, 9, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 24, 245, 151, 1, 8, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 24, 245, 151, 1, 7, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 24, 245, 151, 1, 6, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 22, 245, 152, 2, 11, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 22, 245, 152, 2, 10, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 22, 245, 152, 2, 9, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 22, 245, 152, 2, 8, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 22, 245, 152, 2, 7, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 22, 245, 152, 2, 6, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 22, 245, 151, 1, 11, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 22, 245, 151, 1, 10, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 22, 245, 151, 1, 9, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 22, 245, 151, 1, 8, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 22, 245, 151, 1, 7, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 22, 245, 151, 1, 6, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 21, 245, 152, 2, 11, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 21, 245, 152, 2, 10, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 21, 245, 152, 2, 9, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 21, 245, 152, 2, 8, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 21, 245, 152, 2, 7, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 21, 245, 152, 2, 6, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 21, 245, 151, 1, 11, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 21, 245, 151, 1, 10, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 21, 245, 151, 1, 9, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 21, 245, 151, 1, 8, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 21, 245, 151, 1, 7, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 21, 245, 151, 1, 6, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 42, 245, 155, 2, 10, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 42, 245, 155, 2, 9, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 42, 245, 155, 2, 8, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 42, 245, 155, 2, 7, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 42, 245, 155, 2, 6, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 42, 245, 154, 1, 11, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 42, 245, 154, 1, 10, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 42, 245, 154, 1, 9, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 42, 245, 154, 1, 8, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 42, 245, 154, 1, 7, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 42, 245, 154, 1, 6, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 41, 245, 188, 5, 11, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 41, 245, 188, 5, 10, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 41, 245, 188, 5, 9, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 41, 245, 188, 5, 8, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 41, 245, 188, 5, 7, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 41, 245, 188, 5, 6, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 41, 245, 176, 4, 11, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 41, 245, 176, 4, 10, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 41, 245, 176, 4, 9, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 41, 245, 176, 4, 8, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 41, 245, 176, 4, 7, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 41, 245, 176, 4, 6, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 41, 245, 156, 3, 11, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 41, 245, 156, 3, 10, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 41, 245, 156, 3, 9, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 41, 245, 156, 3, 8, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 41, 245, 156, 3, 7, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 41, 245, 156, 3, 6, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 41, 245, 155, 2, 11, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 41, 245, 155, 2, 10, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 41, 245, 155, 2, 9, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 41, 245, 155, 2, 8, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 41, 245, 155, 2, 7, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 41, 245, 155, 2, 6, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 41, 245, 154, 1, 11, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 41, 245, 154, 1, 10, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 41, 245, 154, 1, 9, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 41, 245, 154, 1, 8, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 41, 245, 154, 1, 7, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 41, 245, 154, 1, 6, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 38, 245, 188, 5, 11, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 38, 245, 188, 5, 10, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 38, 245, 188, 5, 9, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 38, 245, 188, 5, 8, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 38, 245, 188, 5, 7, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 38, 245, 188, 5, 6, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 38, 245, 176, 4, 11, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 38, 245, 176, 4, 10, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 38, 245, 176, 4, 9, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 38, 245, 176, 4, 8, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 38, 245, 176, 4, 7, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 38, 245, 176, 4, 6, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 38, 245, 156, 3, 11, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 38, 245, 156, 3, 10, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 38, 245, 156, 3, 9, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 38, 245, 156, 3, 8, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 38, 245, 156, 3, 7, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 38, 245, 156, 3, 6, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 38, 245, 155, 2, 11, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 38, 245, 155, 2, 10, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 38, 245, 155, 2, 9, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 38, 245, 155, 2, 8, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 38, 245, 155, 2, 7, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 38, 245, 155, 2, 6, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 38, 245, 154, 1, 11, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 38, 245, 154, 1, 10, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 38, 245, 154, 1, 9, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 38, 245, 154, 1, 8, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 38, 245, 154, 1, 7, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 38, 245, 154, 1, 6, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 37, 245, 188, 5, 11, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 37, 245, 188, 5, 10, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 37, 245, 188, 5, 9, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 37, 245, 188, 5, 8, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 37, 245, 188, 5, 7, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 37, 245, 188, 5, 6, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 37, 245, 176, 4, 11, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 37, 245, 176, 4, 10, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 37, 245, 176, 4, 9, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 37, 245, 176, 4, 8, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 37, 245, 176, 4, 7, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 37, 245, 176, 4, 6, '#pago_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 37, 245, 156, 3, 11, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 37, 245, 156, 3, 10, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 37, 245, 156, 3, 9, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 37, 245, 156, 3, 8, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 37, 245, 156, 3, 7, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 37, 245, 156, 3, 6, '#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 37, 245, 155, 2, 11, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 37, 245, 155, 2, 10, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 37, 245, 155, 2, 9, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 37, 245, 155, 2, 8, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 37, 245, 155, 2, 7, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 37, 245, 155, 2, 6, '#empenhado_acumulado-#anulado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 37, 245, 154, 1, 11, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 37, 245, 154, 1, 10, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 37, 245, 154, 1, 9, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 37, 245, 154, 1, 8, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 37, 245, 154, 1, 7, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 37, 245, 154, 1, 6, '#dot_ini+#suplementado-#reduzido_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 36, 245, 188, 5, 11, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 36, 245, 188, 5, 10, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 36, 245, 188, 5, 9, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 36, 245, 188, 5, 8, '#empenhado_acumulado-#anulado_acumulado-#liquidado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 15, 245, 152, 2, 11, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 15, 245, 152, 2, 10, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 15, 245, 152, 2, 9, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 15, 245, 152, 2, 8, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 15, 245, 152, 2, 7, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 15, 245, 152, 2, 6, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 15, 245, 151, 1, 11, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 15, 245, 151, 1, 10, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 15, 245, 151, 1, 9, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 15, 245, 151, 1, 8, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 15, 245, 151, 1, 7, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 15, 245, 151, 1, 6, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 14, 245, 152, 2, 11, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 14, 245, 152, 2, 10, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 14, 245, 152, 2, 9, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 14, 245, 152, 2, 8, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 14, 245, 152, 2, 7, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 14, 245, 152, 2, 6, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 14, 245, 151, 1, 11, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 14, 245, 151, 1, 10, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 14, 245, 151, 1, 9, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 14, 245, 151, 1, 8, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 14, 245, 151, 1, 7, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 14, 245, 151, 1, 6, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 12, 245, 152, 2, 11, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 12, 245, 152, 2, 10, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 12, 245, 152, 2, 9, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 12, 245, 152, 2, 8, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 12, 245, 152, 2, 7, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 12, 245, 152, 2, 6, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 12, 245, 151, 1, 11, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 12, 245, 151, 1, 10, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 12, 245, 151, 1, 9, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 12, 245, 151, 1, 8, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 12, 245, 151, 1, 7, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 12, 245, 151, 1, 6, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 11, 245, 152, 2, 11, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 11, 245, 152, 2, 10, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 11, 245, 152, 2, 9, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 11, 245, 152, 2, 8, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 11, 245, 152, 2, 7, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 11, 245, 152, 2, 6, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 11, 245, 151, 1, 11, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 11, 245, 151, 1, 10, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 11, 245, 151, 1, 9, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 11, 245, 151, 1, 8, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 11, 245, 151, 1, 7, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 11, 245, 151, 1, 6, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 10, 245, 152, 2, 11, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 10, 245, 152, 2, 10, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 10, 245, 152, 2, 9, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 10, 245, 152, 2, 8, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 10, 245, 152, 2, 7, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 10, 245, 152, 2, 6, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 10, 245, 151, 1, 11, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 10, 245, 151, 1, 10, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 10, 245, 151, 1, 9, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 10, 245, 151, 1, 8, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 10, 245, 151, 1, 7, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 10, 245, 151, 1, 6, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 9, 245, 152, 2, 11, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 9, 245, 152, 2, 10, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 9, 245, 152, 2, 9, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 9, 245, 152, 2, 8, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 9, 245, 152, 2, 7, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 9, 245, 152, 2, 6, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 9, 245, 151, 1, 11, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 9, 245, 151, 1, 10, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 9, 245, 151, 1, 9, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 9, 245, 151, 1, 8, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 9, 245, 151, 1, 7, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 9, 245, 151, 1, 6, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 8, 245, 152, 2, 11, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 8, 245, 152, 2, 10, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 8, 245, 152, 2, 9, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 8, 245, 152, 2, 8, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 8, 245, 152, 2, 7, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 8, 245, 152, 2, 6, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 8, 245, 151, 1, 11, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 8, 245, 151, 1, 10, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 8, 245, 151, 1, 9, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 8, 245, 151, 1, 8, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 8, 245, 151, 1, 7, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 8, 245, 151, 1, 6, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 5, 245, 152, 2, 11, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 5, 245, 152, 2, 10, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 5, 245, 152, 2, 9, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 5, 245, 152, 2, 8, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 5, 245, 152, 2, 7, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 5, 245, 152, 2, 6, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 5, 245, 151, 1, 11, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 5, 245, 151, 1, 10, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 5, 245, 151, 1, 9, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 5, 245, 151, 1, 8, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 5, 245, 151, 1, 7, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 5, 245, 151, 1, 6, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 4, 245, 152, 2, 11, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 4, 245, 152, 2, 10, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 4, 245, 152, 2, 9, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 4, 245, 152, 2, 8, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 4, 245, 152, 2, 7, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 4, 245, 152, 2, 6, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 4, 245, 151, 1, 11, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 4, 245, 151, 1, 10, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 4, 245, 151, 1, 9, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 4, 245, 151, 1, 8, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 4, 245, 151, 1, 7, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 4, 245, 151, 1, 6, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 3, 245, 152, 2, 11, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 3, 245, 152, 2, 10, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 3, 245, 152, 2, 9, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 3, 245, 152, 2, 8, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 3, 245, 152, 2, 7, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 3, 245, 152, 2, 6, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 3, 245, 151, 1, 11, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 3, 245, 151, 1, 10, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 3, 245, 151, 1, 9, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 3, 245, 151, 1, 8, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 3, 245, 151, 1, 7, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 3, 245, 151, 1, 6, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 2, 245, 152, 2, 11, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 2, 245, 152, 2, 10, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 2, 245, 152, 2, 9, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 2, 245, 152, 2, 8, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 2, 245, 152, 2, 7, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 2, 245, 152, 2, 6, '#saldo_arrecadado_acumulado');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 2, 245, 151, 1, 11, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 2, 245, 151, 1, 10, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 2, 245, 151, 1, 9, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 2, 245, 151, 1, 8, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 2, 245, 151, 1, 7, '#saldo_inicial_prevadic');
INSERT INTO orcparamseqorcparamseqcoluna VALUES (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 2, 245, 151, 1, 6, '#saldo_inicial_prevadic');


INSERT INTO orcparamseqfiltropadrao VALUES (nextval('orcparamelementospadrao_o132_sequencial_seq'), 245, 2, 2021, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="411180110000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="911180110000000" nivel="" exclusao="false" indicador=""/>
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
INSERT INTO orcparamseqfiltropadrao VALUES (nextval('orcparamelementospadrao_o132_sequencial_seq'), 245, 3, 2021, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="411180140000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="911180140000000" nivel="" exclusao="false" indicador=""/>
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
INSERT INTO orcparamseqfiltropadrao VALUES (nextval('orcparamelementospadrao_o132_sequencial_seq'), 245, 4, 2021, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="411180230000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="911180230000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="411180240000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="911180240000000" nivel="" exclusao="false" indicador=""/>
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
INSERT INTO orcparamseqfiltropadrao VALUES (nextval('orcparamelementospadrao_o132_sequencial_seq'), 245, 5, 2021, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="411130310000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="411130340000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="911130310000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="911130340000000" nivel="" exclusao="false" indicador=""/>
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
INSERT INTO orcparamseqfiltropadrao VALUES (nextval('orcparamelementospadrao_o132_sequencial_seq'), 245, 8, 2021, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="417180120000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="917180120000000" nivel="" exclusao="false" indicador=""/>
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
INSERT INTO orcparamseqfiltropadrao VALUES (nextval('orcparamelementospadrao_o132_sequencial_seq'), 245, 9, 2021, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="417180130000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="917180130000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="417180140000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="917180140000000" nivel="" exclusao="false" indicador=""/>
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
INSERT INTO orcparamseqfiltropadrao VALUES (nextval('orcparamelementospadrao_o132_sequencial_seq'), 245, 10, 2021, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="417280110000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="917280110000000" nivel="" exclusao="false" indicador=""/>
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
INSERT INTO orcparamseqfiltropadrao VALUES (nextval('orcparamelementospadrao_o132_sequencial_seq'), 245, 11, 2021, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="417280130000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="917280130000000" nivel="" exclusao="false" indicador=""/>
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
INSERT INTO orcparamseqfiltropadrao VALUES (nextval('orcparamelementospadrao_o132_sequencial_seq'), 245, 12, 2021, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="417180150000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="917180150000000" nivel="" exclusao="false" indicador=""/>
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
INSERT INTO orcparamseqfiltropadrao VALUES (nextval('orcparamelementospadrao_o132_sequencial_seq'), 245, 13, 2021, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="417280120000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="917280120000000" nivel="" exclusao="false" indicador=""/>
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
INSERT INTO orcparamseqfiltropadrao VALUES (nextval('orcparamelementospadrao_o132_sequencial_seq'), 245, 14, 2021, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="417180180000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="917180180000000" nivel="" exclusao="false" indicador=""/>
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
INSERT INTO orcparamseqfiltropadrao VALUES (nextval('orcparamelementospadrao_o132_sequencial_seq'), 245, 21, 2021, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="417580110000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="917580110000000" nivel="" exclusao="false" indicador=""/>
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
INSERT INTO orcparamseqfiltropadrao VALUES (nextval('orcparamelementospadrao_o132_sequencial_seq'), 245, 22, 2021, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="413210010000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="913210010000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="413210020000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="913210020000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="413210030000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="913210030000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="413210050000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="913210050000000" nivel="" exclusao="false" indicador=""/>
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
INSERT INTO orcparamseqfiltropadrao VALUES (nextval('orcparamelementospadrao_o132_sequencial_seq'), 245, 27, 2021, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="417180900000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="917180900000000" nivel="" exclusao="false" indicador=""/>
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
INSERT INTO orcparamseqfiltropadrao VALUES (nextval('orcparamelementospadrao_o132_sequencial_seq'), 245, 30, 2021, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="111110100000000" nivel="" exclusao="false" indicador=""/>
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
INSERT INTO orcparamseqfiltropadrao VALUES (nextval('orcparamelementospadrao_o132_sequencial_seq'), 245, 36, 2021, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="331000000000000" nivel="3" exclusao="false" indicador=""/>
 </contas>
 <orgao operador="in" valor="" id="orgao"/>
 <unidade operador="in" valor="" id="unidade"/>
 <funcao operador="in" valor="" id="funcao"/>
 <subfuncao operador="in" valor="365" id="subfuncao"/>
 <programa operador="in" valor="" id="programa"/>
 <projativ operador="in" valor="" id="projativ"/>
 <recurso operador="in" valor="31" id="recurso"/>
 <recursocontalinha numerolinha="" id="recursocontalinha"/>
 <observacao valor=""/>
 <desdobrarlinha valor="false"/>
</filter>');
INSERT INTO orcparamseqfiltropadrao VALUES (nextval('orcparamelementospadrao_o132_sequencial_seq'), 245, 37, 2021, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="331000000000000" nivel="3" exclusao="false" indicador=""/>
 </contas>
 <orgao operador="in" valor="" id="orgao"/>
 <unidade operador="in" valor="" id="unidade"/>
 <funcao operador="in" valor="" id="funcao"/>
 <subfuncao operador="in" valor="365" id="subfuncao"/>
 <programa operador="in" valor="" id="programa"/>
 <projativ operador="in" valor="" id="projativ"/>
 <recurso operador="in" valor="31" id="recurso"/>
 <recursocontalinha numerolinha="" id="recursocontalinha"/>
 <observacao valor=""/>
 <desdobrarlinha valor="false"/>
</filter>');
INSERT INTO orcparamseqfiltropadrao VALUES (nextval('orcparamelementospadrao_o132_sequencial_seq'), 245, 38, 2021, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="331000000000000" nivel="3" exclusao="false" indicador=""/>
 </contas>
 <orgao operador="in" valor="" id="orgao"/>
 <unidade operador="in" valor="" id="unidade"/>
 <funcao operador="in" valor="" id="funcao"/>
 <subfuncao operador="in" valor="361" id="subfuncao"/>
 <programa operador="in" valor="" id="programa"/>
 <projativ operador="in" valor="" id="projativ"/>
 <recurso operador="in" valor="31" id="recurso"/>
 <recursocontalinha numerolinha="" id="recursocontalinha"/>
 <observacao valor=""/>
 <desdobrarlinha valor="false"/>
</filter>');
INSERT INTO orcparamseqfiltropadrao VALUES (nextval('orcparamelementospadrao_o132_sequencial_seq'), 245, 41, 2021, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="300000000000000" nivel="1" exclusao="false" indicador=""/>
  <conta estrutural="331000000000000" nivel="3" exclusao="true" indicador=""/>
 </contas>
 <orgao operador="in" valor="" id="orgao"/>
 <unidade operador="in" valor="" id="unidade"/>
 <funcao operador="in" valor="" id="funcao"/>
 <subfuncao operador="in" valor="365" id="subfuncao"/>
 <programa operador="in" valor="" id="programa"/>
 <projativ operador="in" valor="" id="projativ"/>
 <recurso operador="in" valor="31" id="recurso"/>
 <recursocontalinha numerolinha="" id="recursocontalinha"/>
 <observacao valor=""/>
 <desdobrarlinha valor="false"/>
</filter>');
INSERT INTO orcparamseqfiltropadrao VALUES (nextval('orcparamelementospadrao_o132_sequencial_seq'), 245, 42, 2021, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="300000000000000" nivel="1" exclusao="false" indicador=""/>
  <conta estrutural="331000000000000" nivel="3" exclusao="true" indicador=""/>
 </contas>
 <orgao operador="in" valor="" id="orgao"/>
 <unidade operador="in" valor="" id="unidade"/>
 <funcao operador="in" valor="" id="funcao"/>
 <subfuncao operador="in" valor="365" id="subfuncao"/>
 <programa operador="in" valor="" id="programa"/>
 <projativ operador="in" valor="" id="projativ"/>
 <recurso operador="in" valor="31" id="recurso"/>
 <recursocontalinha numerolinha="" id="recursocontalinha"/>
 <observacao valor=""/>
 <desdobrarlinha valor="false"/>
</filter>');
INSERT INTO orcparamseqfiltropadrao VALUES (nextval('orcparamelementospadrao_o132_sequencial_seq'), 245, 43, 2021, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="300000000000000" nivel="1" exclusao="false" indicador=""/>
  <conta estrutural="331000000000000" nivel="3" exclusao="true" indicador=""/>
 </contas>
 <orgao operador="in" valor="" id="orgao"/>
 <unidade operador="in" valor="" id="unidade"/>
 <funcao operador="in" valor="" id="funcao"/>
 <subfuncao operador="in" valor="361" id="subfuncao"/>
 <programa operador="in" valor="" id="programa"/>
 <projativ operador="in" valor="" id="projativ"/>
 <recurso operador="in" valor="31" id="recurso"/>
 <recursocontalinha numerolinha="" id="recursocontalinha"/>
 <observacao valor=""/>
 <desdobrarlinha valor="false"/>
</filter>');
INSERT INTO orcparamseqfiltropadrao VALUES (nextval('orcparamelementospadrao_o132_sequencial_seq'), 245, 45, 2021, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="331000000000000" nivel="3" exclusao="false" indicador=""/>
 </contas>
 <orgao operador="in" valor="" id="orgao"/>
 <unidade operador="in" valor="" id="unidade"/>
 <funcao operador="in" valor="" id="funcao"/>
 <subfuncao operador="in" valor="365,361" id="subfuncao"/>
 <programa operador="in" valor="" id="programa"/>
 <projativ operador="in" valor="" id="projativ"/>
 <recurso operador="in" valor="31" id="recurso"/>
 <recursocontalinha numerolinha="" id="recursocontalinha"/>
 <observacao valor=""/>
 <desdobrarlinha valor="false"/>
</filter>');
INSERT INTO orcparamseqfiltropadrao VALUES (nextval('orcparamelementospadrao_o132_sequencial_seq'), 245, 56, 2021, '<?xml version="1.0" encoding="ISO-8859-1"?>
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
 <recurso operador="in" valor="31" id="recurso"/>
 <recursocontalinha numerolinha="" id="recursocontalinha"/>
 <observacao valor=""/>
 <desdobrarlinha valor="false"/>
</filter>');
INSERT INTO orcparamseqfiltropadrao VALUES (nextval('orcparamelementospadrao_o132_sequencial_seq'), 245, 59, 2021, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="300000000000000" nivel="1" exclusao="false" indicador=""/>
 </contas>
 <orgao operador="in" valor="" id="orgao"/>
 <unidade operador="in" valor="" id="unidade"/>
 <funcao operador="in" valor="" id="funcao"/>
 <subfuncao operador="in" valor="365" id="subfuncao"/>
 <programa operador="in" valor="" id="programa"/>
 <projativ operador="in" valor="" id="projativ"/>
 <recurso operador="in" valor="20" id="recurso"/>
 <recursocontalinha numerolinha="" id="recursocontalinha"/>
 <observacao valor=""/>
 <desdobrarlinha valor="false"/>
</filter>');
INSERT INTO orcparamseqfiltropadrao VALUES (nextval('orcparamelementospadrao_o132_sequencial_seq'), 245, 60, 2021, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="300000000000000" nivel="1" exclusao="false" indicador=""/>
 </contas>
 <orgao operador="in" valor="" id="orgao"/>
 <unidade operador="in" valor="" id="unidade"/>
 <funcao operador="in" valor="" id="funcao"/>
 <subfuncao operador="in" valor="365" id="subfuncao"/>
 <programa operador="in" valor="" id="programa"/>
 <projativ operador="in" valor="" id="projativ"/>
 <recurso operador="in" valor="20" id="recurso"/>
 <recursocontalinha numerolinha="" id="recursocontalinha"/>
 <observacao valor=""/>
 <desdobrarlinha valor="false"/>
</filter>');
INSERT INTO orcparamseqfiltropadrao VALUES (nextval('orcparamelementospadrao_o132_sequencial_seq'), 245, 61, 2021, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="300000000000000" nivel="1" exclusao="false" indicador=""/>
 </contas>
 <orgao operador="in" valor="" id="orgao"/>
 <unidade operador="in" valor="" id="unidade"/>
 <funcao operador="in" valor="" id="funcao"/>
 <subfuncao operador="in" valor="361" id="subfuncao"/>
 <programa operador="in" valor="" id="programa"/>
 <projativ operador="in" valor="" id="projativ"/>
 <recurso operador="in" valor="20" id="recurso"/>
 <recursocontalinha numerolinha="" id="recursocontalinha"/>
 <observacao valor=""/>
 <desdobrarlinha valor="false"/>
</filter>');
INSERT INTO orcparamseqfiltropadrao VALUES (nextval('orcparamelementospadrao_o132_sequencial_seq'), 245, 71, 2021, '<?xml version="1.0" encoding="ISO-8859-1"?>
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
 <recurso operador="in" valor="20" id="recurso"/>
 <recursocontalinha numerolinha="" id="recursocontalinha"/>
 <observacao valor=""/>
 <desdobrarlinha valor="false"/>
</filter>');
INSERT INTO orcparamseqfiltropadrao VALUES (nextval('orcparamelementospadrao_o132_sequencial_seq'), 245, 72, 2021, '<?xml version="1.0" encoding="ISO-8859-1"?>
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
 <recurso operador="in" valor="31" id="recurso"/>
 <recursocontalinha numerolinha="" id="recursocontalinha"/>
 <observacao valor=""/>
 <desdobrarlinha valor="false"/>
</filter>');
INSERT INTO orcparamseqfiltropadrao VALUES (nextval('orcparamelementospadrao_o132_sequencial_seq'), 245, 75, 2021, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="417180510000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="413210010000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="413210020000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="413210030000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="413210050000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="917180510000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="913210010000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="913210020000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="913210030000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="913210050000000" nivel="" exclusao="false" indicador=""/>
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
INSERT INTO orcparamseqfiltropadrao VALUES (nextval('orcparamelementospadrao_o132_sequencial_seq'), 245, 76, 2021, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="417180520000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="917180520000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="413210010000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="913210010000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="413210020000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="913210020000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="413210030000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="913210030000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="413210050000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="913210050000000" nivel="" exclusao="false" indicador=""/>
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
INSERT INTO orcparamseqfiltropadrao VALUES (nextval('orcparamelementospadrao_o132_sequencial_seq'), 245, 77, 2021, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="417180530000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="917180530000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="413210010000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="913210010000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="413210020000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="913210020000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="413210030000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="913210030000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="413210050000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="913210050000000" nivel="" exclusao="false" indicador=""/>
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
INSERT INTO orcparamseqfiltropadrao VALUES (nextval('orcparamelementospadrao_o132_sequencial_seq'), 245, 78, 2021, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="417180540000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="917180540000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="413210010000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="913210010000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="413210020000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="913210020000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="413210030000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="913210030000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="413210050000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="913210050000000" nivel="" exclusao="false" indicador=""/>
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
INSERT INTO orcparamseqfiltropadrao VALUES (nextval('orcparamelementospadrao_o132_sequencial_seq'), 245, 79, 2021, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="417180590000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="917180590000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="413210010000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="913210010000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="413210020000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="913210020000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="413210030000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="913210030000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="413210050000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="913210050000000" nivel="" exclusao="false" indicador=""/>
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
INSERT INTO orcparamseqfiltropadrao VALUES (nextval('orcparamelementospadrao_o132_sequencial_seq'), 245, 80, 2021, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="417181020000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="917181020000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="417181090000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="917181090000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="417281020000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="917281020000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="417281090000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="917281090000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="417381020000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="917381020000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="417381090000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="917381090000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="417480120000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="917480120000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="417480190000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="917480190000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="417680120000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="917680120000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="417680190000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="917680190000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="413210010000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="913210010000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="413210020000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="913210020000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="413210030000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="913210030000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="413210050000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="913210050000000" nivel="" exclusao="false" indicador=""/>
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
INSERT INTO orcparamseqfiltropadrao VALUES (nextval('orcparamelementospadrao_o132_sequencial_seq'), 245, 81, 2021, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="417180230000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="917180230000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="417180240000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="917180240000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="417180250000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="917180250000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="417280230000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="917280230000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="413210010000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="913210010000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="413210020000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="913210020000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="413210030000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="913210030000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="413210050000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="913210050000000" nivel="" exclusao="false" indicador=""/>
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
INSERT INTO orcparamseqfiltropadrao VALUES (nextval('orcparamelementospadrao_o132_sequencial_seq'), 245, 82, 2021, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="421000000000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="921000000000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="413210010000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="913210010000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="413210020000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="913210020000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="413210030000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="913210030000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="413210050000000" nivel="" exclusao="false" indicador=""/>
  <conta estrutural="913210050000000" nivel="" exclusao="false" indicador=""/>
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
INSERT INTO orcparamseqfiltropadrao VALUES (nextval('orcparamelementospadrao_o132_sequencial_seq'), 245, 86, 2021, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="300000000000000" nivel="1" exclusao="false" indicador=""/>
 </contas>
 <orgao operador="in" valor="" id="orgao"/>
 <unidade operador="in" valor="" id="unidade"/>
 <funcao operador="in" valor="" id="funcao"/>
 <subfuncao operador="in" valor="365" id="subfuncao"/>
 <programa operador="in" valor="" id="programa"/>
 <projativ operador="in" valor="" id="projativ"/>
 <recurso operador="notin" valor="20,31" id="recurso"/>
 <recursocontalinha numerolinha="" id="recursocontalinha"/>
 <observacao valor=""/>
 <desdobrarlinha valor="false"/>
</filter>');
INSERT INTO orcparamseqfiltropadrao VALUES (nextval('orcparamelementospadrao_o132_sequencial_seq'), 245, 87, 2021, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="300000000000000" nivel="1" exclusao="false" indicador=""/>
 </contas>
 <orgao operador="in" valor="" id="orgao"/>
 <unidade operador="in" valor="" id="unidade"/>
 <funcao operador="in" valor="" id="funcao"/>
 <subfuncao operador="in" valor="365" id="subfuncao"/>
 <programa operador="in" valor="" id="programa"/>
 <projativ operador="in" valor="" id="projativ"/>
 <recurso operador="notin" valor="20,31" id="recurso"/>
 <recursocontalinha numerolinha="" id="recursocontalinha"/>
 <observacao valor=""/>
 <desdobrarlinha valor="false"/>
</filter>');
INSERT INTO orcparamseqfiltropadrao VALUES (nextval('orcparamelementospadrao_o132_sequencial_seq'), 245, 88, 2021, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="300000000000000" nivel="1" exclusao="false" indicador=""/>
 </contas>
 <orgao operador="in" valor="" id="orgao"/>
 <unidade operador="in" valor="" id="unidade"/>
 <funcao operador="in" valor="" id="funcao"/>
 <subfuncao operador="in" valor="361" id="subfuncao"/>
 <programa operador="in" valor="" id="programa"/>
 <projativ operador="in" valor="" id="projativ"/>
 <recurso operador="notin" valor="20,31" id="recurso"/>
 <recursocontalinha numerolinha="" id="recursocontalinha"/>
 <observacao valor=""/>
 <desdobrarlinha valor="false"/>
</filter>');
INSERT INTO orcparamseqfiltropadrao VALUES (nextval('orcparamelementospadrao_o132_sequencial_seq'), 245, 89, 2021, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="300000000000000" nivel="1" exclusao="false" indicador=""/>
 </contas>
 <orgao operador="in" valor="" id="orgao"/>
 <unidade operador="in" valor="" id="unidade"/>
 <funcao operador="in" valor="" id="funcao"/>
 <subfuncao operador="in" valor="362" id="subfuncao"/>
 <programa operador="in" valor="" id="programa"/>
 <projativ operador="in" valor="" id="projativ"/>
 <recurso operador="notin" valor="20,31" id="recurso"/>
 <recursocontalinha numerolinha="" id="recursocontalinha"/>
 <observacao valor=""/>
 <desdobrarlinha valor="false"/>
</filter>');
INSERT INTO orcparamseqfiltropadrao VALUES (nextval('orcparamelementospadrao_o132_sequencial_seq'), 245, 90, 2021, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="300000000000000" nivel="1" exclusao="false" indicador=""/>
 </contas>
 <orgao operador="in" valor="" id="orgao"/>
 <unidade operador="in" valor="" id="unidade"/>
 <funcao operador="in" valor="" id="funcao"/>
 <subfuncao operador="in" valor="364" id="subfuncao"/>
 <programa operador="in" valor="" id="programa"/>
 <projativ operador="in" valor="" id="projativ"/>
 <recurso operador="notin" valor="20,31" id="recurso"/>
 <recursocontalinha numerolinha="" id="recursocontalinha"/>
 <observacao valor=""/>
 <desdobrarlinha valor="false"/>
</filter>');
INSERT INTO orcparamseqfiltropadrao VALUES (nextval('orcparamelementospadrao_o132_sequencial_seq'), 245, 91, 2021, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="300000000000000" nivel="1" exclusao="false" indicador=""/>
 </contas>
 <orgao operador="in" valor="" id="orgao"/>
 <unidade operador="in" valor="" id="unidade"/>
 <funcao operador="in" valor="" id="funcao"/>
 <subfuncao operador="in" valor="363" id="subfuncao"/>
 <programa operador="in" valor="" id="programa"/>
 <projativ operador="in" valor="" id="projativ"/>
 <recurso operador="notin" valor="20,31" id="recurso"/>
 <recursocontalinha numerolinha="" id="recursocontalinha"/>
 <observacao valor=""/>
 <desdobrarlinha valor="false"/>
</filter>');
INSERT INTO orcparamseqfiltropadrao VALUES (nextval('orcparamelementospadrao_o132_sequencial_seq'), 245, 95, 2021, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="331000000000000" nivel="3" exclusao="false" indicador=""/>
  <conta estrutural="331710000000000" nivel="5" exclusao="true" indicador=""/>
 </contas>
 <orgao operador="in" valor="" id="orgao"/>
 <unidade operador="in" valor="" id="unidade"/>
 <funcao operador="in" valor="12" id="funcao"/>
 <subfuncao operador="in" valor="" id="subfuncao"/>
 <programa operador="in" valor="" id="programa"/>
 <projativ operador="in" valor="" id="projativ"/>
 <recurso operador="in" valor="" id="recurso"/>
 <recursocontalinha numerolinha="" id="recursocontalinha"/>
 <observacao valor=""/>
 <desdobrarlinha valor="false"/>
</filter>');
INSERT INTO orcparamseqfiltropadrao VALUES (nextval('orcparamelementospadrao_o132_sequencial_seq'), 245, 96, 2021, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="331900100000000" nivel="7" exclusao="false" indicador=""/>
  <conta estrutural="331900300000000" nivel="7" exclusao="false" indicador=""/>
  <conta estrutural="331909109000000" nivel="9" exclusao="false" indicador=""/>
  <conta estrutural="331909110000000" nivel="9" exclusao="false" indicador=""/>
  <conta estrutural="331909112000000" nivel="9" exclusao="false" indicador=""/>
  <conta estrutural="331909113000000" nivel="9" exclusao="false" indicador=""/>
  <conta estrutural="331909115000000" nivel="9" exclusao="false" indicador=""/>
  <conta estrutural="331909116000000" nivel="9" exclusao="false" indicador=""/>
  <conta estrutural="331909118000000" nivel="9" exclusao="false" indicador=""/>
  <conta estrutural="331909119000000" nivel="9" exclusao="false" indicador=""/>
  <conta estrutural="331909123000000" nivel="9" exclusao="false" indicador=""/>
  <conta estrutural="331909124000000" nivel="9" exclusao="false" indicador=""/>
  <conta estrutural="331909128000000" nivel="9" exclusao="false" indicador=""/>
  <conta estrutural="331909129000000" nivel="9" exclusao="false" indicador=""/>
  <conta estrutural="331909130000000" nivel="9" exclusao="false" indicador=""/>
  <conta estrutural="331909131000000" nivel="9" exclusao="false" indicador=""/>
  <conta estrutural="331909136000000" nivel="9" exclusao="false" indicador=""/>
  <conta estrutural="331909137000000" nivel="9" exclusao="false" indicador=""/>
  <conta estrutural="331909201000000" nivel="9" exclusao="false" indicador=""/>
  <conta estrutural="331909203000000" nivel="9" exclusao="false" indicador=""/>
  <conta estrutural="331909259000000" nivel="9" exclusao="false" indicador=""/>
  <conta estrutural="331909403000000" nivel="9" exclusao="false" indicador=""/>
  <conta estrutural="331909404000000" nivel="9" exclusao="false" indicador=""/>
  <conta estrutural="331909406000000" nivel="9" exclusao="false" indicador=""/>
  <conta estrutural="331909413000000" nivel="9" exclusao="false" indicador=""/>
  <conta estrutural="331919206000000" nivel="9" exclusao="false" indicador=""/>
  <conta estrutural="331919208000000" nivel="9" exclusao="false" indicador=""/>
  <conta estrutural="331919210000000" nivel="9" exclusao="false" indicador=""/>
  <conta estrutural="331919212000000" nivel="9" exclusao="false" indicador=""/>
 </contas>
 <orgao operador="in" valor="" id="orgao"/>
 <unidade operador="in" valor="" id="unidade"/>
 <funcao operador="in" valor="12" id="funcao"/>
 <subfuncao operador="in" valor="" id="subfuncao"/>
 <programa operador="in" valor="" id="programa"/>
 <projativ operador="in" valor="" id="projativ"/>
 <recurso operador="in" valor="" id="recurso"/>
 <recursocontalinha numerolinha="" id="recursocontalinha"/>
 <observacao valor="                                                        "/>
 <desdobrarlinha valor="false"/>
</filter>');
INSERT INTO orcparamseqfiltropadrao VALUES (nextval('orcparamelementospadrao_o132_sequencial_seq'), 245, 97, 2021, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="333504300000000" nivel="7" exclusao="false" indicador=""/>
 </contas>
 <orgao operador="in" valor="" id="orgao"/>
 <unidade operador="in" valor="" id="unidade"/>
 <funcao operador="in" valor="12" id="funcao"/>
 <subfuncao operador="in" valor="" id="subfuncao"/>
 <programa operador="in" valor="" id="programa"/>
 <projativ operador="in" valor="" id="projativ"/>
 <recurso operador="in" valor="" id="recurso"/>
 <recursocontalinha numerolinha="" id="recursocontalinha"/>
 <observacao valor=""/>
 <desdobrarlinha valor="false"/>
</filter>');
INSERT INTO orcparamseqfiltropadrao VALUES (nextval('orcparamelementospadrao_o132_sequencial_seq'), 245, 98, 2021, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="331000000000000" nivel="3" exclusao="true" indicador=""/>
  <conta estrutural="332710000000000" nivel="5" exclusao="true" indicador=""/>
  <conta estrutural="333710000000000" nivel="5" exclusao="true" indicador=""/>
  <conta estrutural="333504300000000" nivel="7" exclusao="true" indicador=""/>
  <conta estrutural="330000000000000" nivel="2" exclusao="false" indicador=""/>
 </contas>
 <orgao operador="in" valor="" id="orgao"/>
 <unidade operador="in" valor="" id="unidade"/>
 <funcao operador="in" valor="12" id="funcao"/>
 <subfuncao operador="in" valor="" id="subfuncao"/>
 <programa operador="in" valor="" id="programa"/>
 <projativ operador="in" valor="" id="projativ"/>
 <recurso operador="in" valor="" id="recurso"/>
 <recursocontalinha numerolinha="" id="recursocontalinha"/>
 <observacao valor=""/>
 <desdobrarlinha valor="false"/>
</filter>');
INSERT INTO orcparamseqfiltropadrao VALUES (nextval('orcparamelementospadrao_o132_sequencial_seq'), 245, 100, 2021, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="344504200000000" nivel="7" exclusao="false" indicador=""/>
  <conta estrutural="345504200000000" nivel="7" exclusao="false" indicador=""/>
 </contas>
 <orgao operador="in" valor="" id="orgao"/>
 <unidade operador="in" valor="" id="unidade"/>
 <funcao operador="in" valor="12" id="funcao"/>
 <subfuncao operador="in" valor="" id="subfuncao"/>
 <programa operador="in" valor="" id="programa"/>
 <projativ operador="in" valor="" id="projativ"/>
 <recurso operador="in" valor="" id="recurso"/>
 <recursocontalinha numerolinha="" id="recursocontalinha"/>
 <observacao valor="
"/>
 <desdobrarlinha valor="false"/>
</filter>');
INSERT INTO orcparamseqfiltropadrao VALUES (nextval('orcparamelementospadrao_o132_sequencial_seq'), 245, 101, 2021, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="340000000000000" nivel="2" exclusao="false" indicador=""/>
  <conta estrutural="344710000000000" nivel="5" exclusao="true" indicador=""/>
  <conta estrutural="345710000000000" nivel="5" exclusao="true" indicador=""/>
  <conta estrutural="346710000000000" nivel="5" exclusao="false" indicador=""/>
  <conta estrutural="344504200000000" nivel="7" exclusao="true" indicador=""/>
  <conta estrutural="345504200000000" nivel="7" exclusao="true" indicador=""/>
 </contas>
 <orgao operador="in" valor="" id="orgao"/>
 <unidade operador="in" valor="" id="unidade"/>
 <funcao operador="in" valor="12" id="funcao"/>
 <subfuncao operador="in" valor="" id="subfuncao"/>
 <programa operador="in" valor="" id="programa"/>
 <projativ operador="in" valor="" id="projativ"/>
 <recurso operador="in" valor="" id="recurso"/>
 <recursocontalinha numerolinha="" id="recursocontalinha"/>
 <observacao valor=""/>
 <desdobrarlinha valor="false"/>
</filter>');
INSERT INTO orcparamseqfiltropadrao VALUES (nextval('orcparamelementospadrao_o132_sequencial_seq'), 245, 102, 2021, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="111000000000000" nivel="3" exclusao="false" indicador=""/>
 </contas>
 <orgao operador="in" valor="" id="orgao"/>
 <unidade operador="in" valor="" id="unidade"/>
 <funcao operador="in" valor="" id="funcao"/>
 <subfuncao operador="in" valor="" id="subfuncao"/>
 <programa operador="in" valor="" id="programa"/>
 <projativ operador="in" valor="" id="projativ"/>
 <recurso operador="in" valor="31" id="recurso"/>
 <recursocontalinha numerolinha="" id="recursocontalinha"/>
 <observacao valor=""/>
 <desdobrarlinha valor="false"/>
</filter>');
INSERT INTO orcparamseqfiltropadrao VALUES (nextval('orcparamelementospadrao_o132_sequencial_seq'), 245, 103, 2021, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="111000000000000" nivel="3" exclusao="false" indicador=""/>
 </contas>
 <orgao operador="in" valor="" id="orgao"/>
 <unidade operador="in" valor="" id="unidade"/>
 <funcao operador="in" valor="" id="funcao"/>
 <subfuncao operador="in" valor="" id="subfuncao"/>
 <programa operador="in" valor="" id="programa"/>
 <projativ operador="in" valor="" id="projativ"/>
 <recurso operador="in" valor="31" id="recurso"/>
 <recursocontalinha numerolinha="" id="recursocontalinha"/>
 <observacao valor=""/>
 <desdobrarlinha valor="false"/>
</filter>');
INSERT INTO orcparamseqfiltropadrao VALUES (nextval('orcparamelementospadrao_o132_sequencial_seq'), 245, 104, 2021, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="111000000000000" nivel="3" exclusao="false" indicador=""/>
 </contas>
 <orgao operador="in" valor="" id="orgao"/>
 <unidade operador="in" valor="" id="unidade"/>
 <funcao operador="in" valor="" id="funcao"/>
 <subfuncao operador="in" valor="" id="subfuncao"/>
 <programa operador="in" valor="" id="programa"/>
 <projativ operador="in" valor="" id="projativ"/>
 <recurso operador="in" valor="31" id="recurso"/>
 <recursocontalinha numerolinha="" id="recursocontalinha"/>
 <observacao valor=""/>
 <desdobrarlinha valor="false"/>
</filter>');
INSERT INTO orcparamseqfiltropadrao VALUES (nextval('orcparamelementospadrao_o132_sequencial_seq'), 245, 106, 2021, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="111000000000000" nivel="3" exclusao="false" indicador=""/>
 </contas>
 <orgao operador="in" valor="" id="orgao"/>
 <unidade operador="in" valor="" id="unidade"/>
 <funcao operador="in" valor="" id="funcao"/>
 <subfuncao operador="in" valor="" id="subfuncao"/>
 <programa operador="in" valor="" id="programa"/>
 <projativ operador="in" valor="" id="projativ"/>
 <recurso operador="in" valor="31" id="recurso"/>
 <recursocontalinha numerolinha="" id="recursocontalinha"/>
 <observacao valor=""/>
 <desdobrarlinha valor="false"/>
</filter>');
INSERT INTO orcparamseqfiltropadrao VALUES (nextval('orcparamelementospadrao_o132_sequencial_seq'), 245, 107, 2021, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="111000000000000" nivel="3" exclusao="false" indicador=""/>
 </contas>
 <orgao operador="in" valor="" id="orgao"/>
 <unidade operador="in" valor="" id="unidade"/>
 <funcao operador="in" valor="" id="funcao"/>
 <subfuncao operador="in" valor="" id="subfuncao"/>
 <programa operador="in" valor="" id="programa"/>
 <projativ operador="in" valor="" id="projativ"/>
 <recurso operador="in" valor="31" id="recurso"/>
 <recursocontalinha numerolinha="" id="recursocontalinha"/>
 <observacao valor=""/>
 <desdobrarlinha valor="false"/>
</filter>');
INSERT INTO orcparamseqfiltropadrao VALUES (nextval('orcparamelementospadrao_o132_sequencial_seq'), 245, 109, 2021, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="111000000000000" nivel="3" exclusao="false" indicador=""/>
 </contas>
 <orgao operador="in" valor="" id="orgao"/>
 <unidade operador="in" valor="" id="unidade"/>
 <funcao operador="in" valor="" id="funcao"/>
 <subfuncao operador="in" valor="" id="subfuncao"/>
 <programa operador="in" valor="" id="programa"/>
 <projativ operador="in" valor="" id="projativ"/>
 <recurso operador="in" valor="1019,1004" id="recurso"/>
 <recursocontalinha numerolinha="" id="recursocontalinha"/>
 <observacao valor=""/>
 <desdobrarlinha valor="false"/>
</filter>');
INSERT INTO orcparamseqfiltropadrao VALUES (nextval('orcparamelementospadrao_o132_sequencial_seq'), 245, 110, 2021, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="111000000000000" nivel="3" exclusao="false" indicador=""/>
 </contas>
 <orgao operador="in" valor="" id="orgao"/>
 <unidade operador="in" valor="" id="unidade"/>
 <funcao operador="in" valor="" id="funcao"/>
 <subfuncao operador="in" valor="" id="subfuncao"/>
 <programa operador="in" valor="" id="programa"/>
 <projativ operador="in" valor="" id="projativ"/>
 <recurso operador="in" valor="1019,1004" id="recurso"/>
 <recursocontalinha numerolinha="" id="recursocontalinha"/>
 <observacao valor=""/>
 <desdobrarlinha valor="false"/>
</filter>');
INSERT INTO orcparamseqfiltropadrao VALUES (nextval('orcparamelementospadrao_o132_sequencial_seq'), 245, 111, 2021, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="111000000000000" nivel="3" exclusao="false" indicador=""/>
 </contas>
 <orgao operador="in" valor="" id="orgao"/>
 <unidade operador="in" valor="" id="unidade"/>
 <funcao operador="in" valor="" id="funcao"/>
 <subfuncao operador="in" valor="" id="subfuncao"/>
 <programa operador="in" valor="" id="programa"/>
 <projativ operador="in" valor="" id="projativ"/>
 <recurso operador="in" valor="1019,1004" id="recurso"/>
 <recursocontalinha numerolinha="" id="recursocontalinha"/>
 <observacao valor=""/>
 <desdobrarlinha valor="false"/>
</filter>');
INSERT INTO orcparamseqfiltropadrao VALUES (nextval('orcparamelementospadrao_o132_sequencial_seq'), 245, 113, 2021, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="111000000000000" nivel="3" exclusao="false" indicador=""/>
 </contas>
 <orgao operador="in" valor="" id="orgao"/>
 <unidade operador="in" valor="" id="unidade"/>
 <funcao operador="in" valor="" id="funcao"/>
 <subfuncao operador="in" valor="" id="subfuncao"/>
 <programa operador="in" valor="" id="programa"/>
 <projativ operador="in" valor="" id="projativ"/>
 <recurso operador="in" valor="1019,1004" id="recurso"/>
 <recursocontalinha numerolinha="" id="recursocontalinha"/>
 <observacao valor=""/>
 <desdobrarlinha valor="false"/>
</filter>');
INSERT INTO orcparamseqfiltropadrao VALUES (nextval('orcparamelementospadrao_o132_sequencial_seq'), 245, 114, 2021, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas>
  <conta estrutural="111000000000000" nivel="3" exclusao="false" indicador=""/>
 </contas>
 <orgao operador="in" valor="" id="orgao"/>
 <unidade operador="in" valor="" id="unidade"/>
 <funcao operador="in" valor="" id="funcao"/>
 <subfuncao operador="in" valor="" id="subfuncao"/>
 <programa operador="in" valor="" id="programa"/>
 <projativ operador="in" valor="" id="projativ"/>
 <recurso operador="in" valor="1019" id="recurso"/>
 <recursocontalinha numerolinha="" id="recursocontalinha"/>
 <observacao valor=""/>
 <desdobrarlinha valor="false"/>
</filter>');




SQL;


        $this->execute($sql);
    }



    public function down(){

        $sql = <<<SQL

          delete from db_menu where id_item = 8033 and id_item_filho = 228476;
          delete from db_itensmenu where id_item = 228476;
          delete from orcparamseqfiltropadrao where o132_orcparamrel = 245;
          delete from orcparamseqorcparamseqcoluna where o116_codparamrel = 245;
          delete from orcparamseqcoluna where o115_relatorio = 245;
          delete from orcparamseq where o69_codparamrel = 245;
          delete from orcparamrelperiodos where o113_orcparamrel = 245;
          delete from orcparamrel where o42_codparrel = 245;

SQL;


        $this->execute($sql);
    }




}
