<?php

use Classes\PostgresMigration;

class M11639EfdProcessos extends PostgresMigration
{



    public function up()
    {
        $this->preUp();
        $this->dicionario();
        $this->formulario();
        $this->estrutura();
        $this->menu();
    }

    public function down()
    {
        $this->execute("
            drop table if exists avaliacaogruporespostaefdprocesso cascade;
            drop sequence if exists avaliacaogruporespostaefdprocesso_efd02_sequencial_seq;
            drop table if exists efdreinfversao cascade;
            drop table if exists efdreinfversaoformulario cascade;
            drop sequence if exists efdreinfversao_efd01_sequencial_seq;
            drop sequence if exists efdreinfversaoformulario_efd03_sequencial_seq;
        ");

        $this->execute("
            create temp table x_avaliacaopergunta as 
              select db103_sequencial 
                from avaliacaopergunta 
               where db103_avaliacaogrupopergunta in (select db102_sequencial from avaliacaogrupopergunta where db102_avaliacao = 3000037);
            
            create temp table x_avaliacaoperguntaopcao as 
              select db104_sequencial 
                from avaliacaoperguntaopcao 
               where db104_avaliacaopergunta in (select db103_sequencial from x_avaliacaopergunta);
            
            delete from avaliacaoperguntaopcao where db104_avaliacaopergunta in (select db103_sequencial from x_avaliacaopergunta);
            delete from avaliacaopergunta where db103_sequencial in (select db103_sequencial from x_avaliacaopergunta);
            delete from avaliacaogrupopergunta where db102_avaliacao = 3000037;
            delete from avaliacao where db101_sequencial = 3000037;
            
            drop table x_avaliacaopergunta;
            drop table x_avaliacaoperguntaopcao;
        ");

        $this->execute("
            update avaliacao set db101_avaliacaotipo = 5 where db101_sequencial = 3000034;
            delete from avaliacaotipo where db100_sequencial = 8;
            alter table avaliacao alter COLUMN db101_descricao type varchar(50);
        ");

        $this->execute("
            delete from db_syscadind where codind in (1008397, 1008398, 1008399, 1008401, 1008402, 1008403, 1008404);
            delete from db_sysindices where codind in (1008397, 1008398, 1008399, 1008401, 1008402, 1008403, 1008404);
            delete from db_sysarqcamp where codarq in (1010357, 1010358, 1010359);
            delete from db_sysprikey where codarq in (1010357, 1010358, 1010359);
            delete from db_sysforkey where codarq in (1010357, 1010358, 1010359);
            delete from db_syscampo where codcam in (1010197, 1010198, 1010199, 1010200, 1010201, 1010202, 1010203, 1010204, 1010205, 1010206, 1010207, 1010208);
            delete from db_syssequencia where codsequencia in (1000799, 1000800, 1000801);
            delete from db_sysarqmod where codarq in (1010357, 1010358, 1010359);
            delete from db_sysarquivo where codarq in (1010357, 1010358, 1010359);
        ");

        
        $this->execute("
            delete from db_menu where id_item_filho = 228085 AND modulo = 228077;
            delete from db_itensmenu where id_item = 228085;
        ");

        $this->execute("
            insert into recursoshumanos.esocialversaoformulario (rh211_versao, rh211_avaliacao, rh211_esocialformulariotipo) 
            values ('2.4', 3000034, 21);
            delete from recursoshumanos.esocialformulariotipo where rh209_sequencial = 23

        ");
    }

    private function preUp()
    {
        $this->execute("
          INSERT INTO avaliacaotipo VALUES (8, 'EFD Reinf');
          alter table avaliacao alter COLUMN db101_descricao type varchar(100);
          update avaliacao set db101_avaliacaotipo = 8 where db101_sequencial = 3000034;
          insert into esocialformulariotipo values (23, 'R-1070 - Tabela de Processos Administrativos/Judiciais');
        ");
    }

    private function formulario()
    {
        $this->execute("
            insert into avaliacao( db101_sequencial ,db101_avaliacaotipo ,db101_descricao ,db101_identificador ,db101_obs ,db101_ativo ,db101_cargadados ,db101_permiteedicao ) values ( 3000037 ,8 ,'R-1070 - Tabela de Processos Admin/Judiciais' ,'r1070-tabela-de-processos-adminjudiciais' ,'Tabela de Processos Administrativos/Judiciais' ,'true' ,'' ,'false' );
            insert into avaliacaogrupopergunta( db102_sequencial ,db102_avaliacao ,db102_descricao ,db102_identificador ,db102_identificadorcampo ,db102_ordem ) values ( 3000532 ,3000037 ,'Identificação do Processo e validade das informações.' ,'identificacao-do-processo-e-validade-das-informaco' ,'ideProcesso' ,1 );
            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3002398 ,1 ,3000532 ,'Selecione o tipo de processo' ,'selecione-o-tipo-de-processo' ,'true' ,'true' ,1 ,1 ,'' ,0 ,'false' ,'' ,'tpProc' );
            delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 3002398;
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4000956 ,3002398 ,'Administrativo' ,'administrativo5c17e7d115948' ,'false' ,0 ,'1' ,'tpProc_1' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4000957 ,3002398 ,'Judicial' ,'judicial5c17e7d11776f' ,'false' ,0 ,'2' ,'tpProc_2' );
            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3002399 ,2 ,3000532 ,'Informar o número do processo administrativo/judicial.' ,'informar-o-numero-do-processo-administrativojudici' ,'true' ,'true' ,2 ,1 ,'' ,0 ,'false' ,'' ,'nrProc' );
            delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 3002399;
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4000958 ,3002399 ,'' ,'5c17e7d11bbf9' ,'true' ,0 ,'' ,'nrProc' );
            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3002400 ,2 ,3000532 ,'Preencher com o mês e ano de início da validade das informações prestadas no formato AAAA-MM.' ,'preencher-com-o-mes-e-ano-de-inicio-d5c17e7d11da40' ,'true' ,'true' ,3 ,1 ,'' ,0 ,'false' ,'' ,'iniValid' );
            delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 3002400;
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4000959 ,3002400 ,'' ,'5c17e7d1200a6' ,'true' ,0 ,'' ,'iniValid' );
            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3002401 ,2 ,3000532 ,'Preencher com o mês e ano de término da validade das informações prestadas no formato AAAA-MM.' ,'preencher-com-o-mes-e-ano-de-termino-5c17e7d122004' ,'false' ,'true' ,4 ,1 ,'' ,0 ,'false' ,'' ,'fimValid' );
            delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 3002401;
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4000960 ,3002401 ,'' ,'5c17e7d12497b' ,'true' ,0 ,'' ,'fimValid' );
            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3002402 ,1 ,3000532 ,'Indicativo da autoria da ação judicial' ,'indicativo-da-autoria-da-acao-judicial' ,'true' ,'true' ,5 ,1 ,'' ,0 ,'false' ,'' ,'indAutoria' );
            delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 3002402;
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4000961 ,3002402 ,'Próprio contribuinte' ,'proprio-contribuinte5c17e7d128e88' ,'false' ,0 ,'1' ,'indAutoria_1' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4000962 ,3002402 ,'Outra entidade ou empresa' ,'outra-entidade-ou-empresa' ,'false' ,0 ,'2' ,'indAutoria_2' );
            insert into avaliacaogrupopergunta( db102_sequencial ,db102_avaliacao ,db102_descricao ,db102_identificador ,db102_identificadorcampo ,db102_ordem ) values ( 3000533 ,3000037 ,'1 - Informações de Suspensão de Exibilidade de tributos' ,'1-informacoes-de-suspensao-de-exibilidade-de-tribu' ,'infoSusp_1' ,2 );
            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3002403 ,2 ,3000533 ,'Código do Indicativo da Suspensão, atribuído pelo contribuinte.' ,'codigo-do-indicativo-da-suspensao-atribuido-pelo-c' ,'false' ,'true' ,1 ,1 ,'' ,0 ,'false' ,'' ,'infoSusp_1_codSusp' );
            delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 3002403;
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4000963 ,3002403 ,'' ,'5c17e7d12f970' ,'true' ,0 ,'' ,'infoSusp_1_codSusp' );
            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3002404 ,1 ,3000533 ,'Indicativo de suspensão da exigibilidade' ,'indicativo-de-suspensao-da-exigibilidade' ,'false' ,'true' ,2 ,1 ,'' ,0 ,'false' ,'' ,'infoSusp_1_indSusp' );
            delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 3002404;
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4000964 ,3002404 ,'Liminar em Mandado de Segurança' ,'liminar-em-mandado-de-seguranca5c17e7d133ad9' ,'false' ,0 ,'01' ,'infoSusp_1_indSusp_01' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4000965 ,3002404 ,'Depósito Judicial do Montante Integral' ,'deposito-judicial-do-montante-integra5c17e7d1355c5' ,'false' ,0 ,'02' ,'infoSusp_1_indSusp_02' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4000966 ,3002404 ,'Depósito Administrativo do Montante Integral' ,'deposito-administrativo-do-montante-i5c17e7d1371b0' ,'false' ,0 ,'03' ,'infoSusp_1_indSusp_03' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4000967 ,3002404 ,'Antecipação de Tutela' ,'antecipacao-de-tutela5c17e7d138a55' ,'false' ,0 ,'04' ,'infoSusp_1_indSusp_04' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4000968 ,3002404 ,'Liminar em Medida Cautelar' ,'liminar-em-medida-cautelar5c17e7d13a7ad' ,'false' ,0 ,'05' ,'infoSusp_1_indSusp_05' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4000969 ,3002404 ,'Sentença em Mandado de Segurança Favorável ao Contribuinte' ,'sentenca-em-mandado-de-seguranca-favo5c17e7d13c0b2' ,'false' ,0 ,'08' ,'infoSusp_1_indSusp_08' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4000970 ,3002404 ,'Sentença em Ação Ordinária Favorável ao Contribuinte e Confirmadapelo TRF' ,'sentenca-em-acao-ordinaria-favoravel-5c17e7d13dafd' ,'false' ,0 ,'09' ,'infoSusp_1_indSusp_09' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4000971 ,3002404 ,'Acórdão do TRF Favorável ao Contribuinte' ,'acordao-do-trf-favoravel-ao-contribui5c17e7d13f519' ,'false' ,0 ,'10' ,'infoSusp_1_indSusp_10' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4000972 ,3002404 ,'Acórdão do STJ em Recurso Especial Favorável ao Contribuinte' ,'acordao-do-stj-em-recurso-especial-fa5c17e7d140eb2' ,'false' ,0 ,'11' ,'infoSusp_1_indSusp_11' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4000973 ,3002404 ,'Acórdão do STF em Recurso Extraordinário Favorável ao Contribuinte' ,'acordao-do-stf-em-recurso-extraordina5c17e7d142eb5' ,'false' ,0 ,'12' ,'infoSusp_1_indSusp_12' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4000974 ,3002404 ,'Sentença 1a instância não transitada em julgado com efeito suspensivo' ,'sentenca-1a-instancia-nao-transitada-5c17e7d14461f' ,'false' ,0 ,'13' ,'infoSusp_1_indSusp_13' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4000975 ,3002404 ,'Decisão Definitiva a favor do contribuinte' ,'decisao-definitiva-a-favor-do-contrib5c17e7d1465ff' ,'false' ,0 ,'90' ,'infoSusp_1_indSusp_90' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4000976 ,3002404 ,'Sem suspensão da exigibilidade' ,'sem-suspensao-da-exigibilidade5c17e7d1480c8' ,'false' ,0 ,'92' ,'infoSusp_1_indSusp_92' );
            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3002405 ,2 ,3000533 ,'Data da decisão, sentença ou despacho administrativo.' ,'data-da-decisao-sentenca-ou-despacho-administrativ' ,'false' ,'true' ,3 ,5 ,'' ,0 ,'false' ,'' ,'infoSusp_1_dtDecisao' );
            delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 3002405;
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4000977 ,3002405 ,'' ,'5c17e7d14c364' ,'true' ,0 ,'' ,'infoSusp_1_dtDecisao' );
            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3002406 ,1 ,3000533 ,'Indicativo de Depósito do Montante Integral' ,'indicativo-de-deposito-do-montante-integral' ,'false' ,'true' ,4 ,1 ,'' ,0 ,'false' ,'' ,'infoSusp_1_indDeposito' );
            delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 3002406;
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4000978 ,3002406 ,'Sim' ,'sim5c17e7d155c7b' ,'false' ,0 ,'S' ,'infoSusp_1_indDeposito_S' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4000979 ,3002406 ,'Não' ,'nao5c17e7d158393' ,'false' ,0 ,'N' ,'infoSusp_1_indDeposito_N' );
            insert into avaliacaogrupopergunta( db102_sequencial ,db102_avaliacao ,db102_descricao ,db102_identificador ,db102_identificadorcampo ,db102_ordem ) values ( 3000534 ,3000037 ,'2 - Informações de Suspensão de Exibilidade de tributos' ,'2-informacoes-de-suspensao-de-exibilidade-de-tribu' ,'infoSusp_2' ,3 );
            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3002407 ,2 ,3000534 ,'Código do Indicativo da Suspensão, atribuído pelo contribuinte.' ,'codigo-do-indicativo-da-suspensao-at5c17e7d15be76' ,'false' ,'true' ,1 ,1 ,'' ,0 ,'false' ,'' ,'infoSusp_2_codSusp' );
            delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 3002407;
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4000980 ,3002407 ,'' ,'5c17e7d15ec22' ,'true' ,0 ,'' ,'infoSusp_2_codSusp' );
            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3002408 ,1 ,3000534 ,'Indicativo de suspensão da exigibilidade' ,'indicativo-de-suspensao-da-exigibilid5c17e7d1607e0' ,'false' ,'true' ,2 ,1 ,'' ,0 ,'false' ,'' ,'infoSusp_2_indSusp' );
            delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 3002408;
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4000981 ,3002408 ,'Liminar em Mandado de Segurança' ,'liminar-em-mandado-de-seguranca5c17e7d16324f' ,'false' ,0 ,'01' ,'infoSusp_2_indSusp_01' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4000982 ,3002408 ,'Depósito Judicial do Montante Integral' ,'deposito-judicial-do-montante-integra5c17e7d164e03' ,'false' ,0 ,'02' ,'infoSusp_2_indSusp_02' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4000983 ,3002408 ,'Depósito Administrativo do Montante Integral' ,'deposito-administrativo-do-montante-i5c17e7d1674b8' ,'false' ,0 ,'03' ,'infoSusp_2_indSusp_03' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4000984 ,3002408 ,'Antecipação de Tutela' ,'antecipacao-de-tutela5c17e7d169147' ,'false' ,0 ,'04' ,'infoSusp_2_indSusp_04' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4000985 ,3002408 ,'Liminar em Medida Cautelar' ,'liminar-em-medida-cautelar5c17e7d16ab39' ,'false' ,0 ,'05' ,'infoSusp_2_indSusp_05' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4000986 ,3002408 ,'Sentença em Mandado de Segurança Favorável ao Contribuinte' ,'sentenca-em-mandado-de-seguranca-favo5c17e7d16c4b8' ,'false' ,0 ,'08' ,'infoSusp_2_indSusp_08' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4000987 ,3002408 ,'Sentença em Ação Ordinária Favorável ao Contribuinte e Confirmadapelo TRF' ,'sentenca-em-acao-ordinaria-favoravel-5c17e7d16dd6b' ,'false' ,0 ,'09' ,'infoSusp_2_indSusp_09' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4000988 ,3002408 ,'Acórdão do TRF Favorável ao Contribuinte' ,'acordao-do-trf-favoravel-ao-contribui5c17e7d16f518' ,'false' ,0 ,'10' ,'infoSusp_2_indSusp_10' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4000989 ,3002408 ,'Acórdão do STJ em Recurso Especial Favorável ao Contribuinte' ,'acordao-do-stj-em-recurso-especial-fa5c17e7d170c64' ,'false' ,0 ,'11' ,'infoSusp_2_indSusp_11' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4000990 ,3002408 ,'Acórdão do STF em Recurso Extraordinário Favorável ao Contribuinte' ,'acordao-do-stf-em-recurso-extraordina5c17e7d172475' ,'false' ,0 ,'12' ,'infoSusp_2_indSusp_12' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4000991 ,3002408 ,'Sentença 1a instância não transitada em julgado com efeito suspensivo' ,'sentenca-1a-instancia-nao-transitada-5c17e7d173bc2' ,'false' ,0 ,'13' ,'infoSusp_2_indSusp_13' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4000992 ,3002408 ,'Decisão Definitiva a favor do contribuinte' ,'decisao-definitiva-a-favor-do-contrib5c17e7d17534d' ,'false' ,0 ,'90' ,'infoSusp_2_indSusp_90' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4000993 ,3002408 ,'Sem suspensão da exigibilidade' ,'sem-suspensao-da-exigibilidade5c17e7d176d4a' ,'false' ,0 ,'92' ,'infoSusp_2_indSusp_92' );
            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3002409 ,2 ,3000534 ,'Data da decisão, sentença ou despacho administrativo.' ,'data-da-decisao-sentenca-ou-despacho5c17e7d178f77' ,'false' ,'true' ,3 ,5 ,'' ,0 ,'false' ,'' ,'infoSusp_2_dtDecisao' );
            delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 3002409;
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4000994 ,3002409 ,'' ,'5c17e7d17b82a' ,'true' ,0 ,'' ,'infoSusp_2_dtDecisao' );
            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3002410 ,1 ,3000534 ,'Indicativo de Depósito do Montante Integral' ,'indicativo-de-deposito-do-montante-in5c17e7d17ced3' ,'false' ,'true' ,4 ,1 ,'' ,0 ,'false' ,'' ,'infoSusp_2_indDeposito' );
            delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 3002410;
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4000995 ,3002410 ,'Sim' ,'sim5c17e7d17f79d' ,'false' ,0 ,'S' ,'infoSusp_2_indDeposito_S' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4000996 ,3002410 ,'Não' ,'nao5c17e7d181252' ,'false' ,0 ,'N' ,'infoSusp_2_indDeposito_N' );
            insert into avaliacaogrupopergunta( db102_sequencial ,db102_avaliacao ,db102_descricao ,db102_identificador ,db102_identificadorcampo ,db102_ordem ) values ( 3000535 ,3000037 ,'3 - Informações de Suspensão de Exibilidade de tributos' ,'3-informacoes-de-suspensao-de-exibilidade-de-tribu' ,'infoSusp_3' ,4 );
            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3002411 ,2 ,3000535 ,'Código do Indicativo da Suspensão, atribuído pelo contribuinte.' ,'codigo-do-indicativo-da-suspensao-at5c17e7d184456' ,'false' ,'true' ,1 ,1 ,'' ,0 ,'false' ,'' ,'infoSusp_3_codSusp' );
            delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 3002411;
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4000997 ,3002411 ,'' ,'5c17e7d186b27' ,'true' ,0 ,'' ,'infoSusp_3_codSusp' );
            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3002412 ,1 ,3000535 ,'Indicativo de suspensão da exigibilidade' ,'indicativo-de-suspensao-da-exigibilid5c17e7d1884ad' ,'false' ,'true' ,2 ,1 ,'' ,0 ,'false' ,'' ,'infoSusp_3_indSusp' );
            delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 3002412;
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4000998 ,3002412 ,'Liminar em Mandado de Segurança' ,'liminar-em-mandado-de-seguranca5c17e7d18aac7' ,'false' ,0 ,'01' ,'infoSusp_3_indSusp_01' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4000999 ,3002412 ,'Depósito Judicial do Montante Integral' ,'deposito-judicial-do-montante-integra5c17e7d18c47d' ,'false' ,0 ,'02' ,'infoSusp_3_indSusp_02' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001000 ,3002412 ,'Depósito Administrativo do Montante Integral' ,'deposito-administrativo-do-montante-i5c17e7d18e087' ,'false' ,0 ,'03' ,'infoSusp_3_indSusp_03' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001001 ,3002412 ,'Antecipação de Tutela' ,'antecipacao-de-tutela5c17e7d18fe78' ,'false' ,0 ,'04' ,'infoSusp_3_indSusp_04' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001002 ,3002412 ,'Liminar em Medida Cautelar' ,'liminar-em-medida-cautelar5c17e7d19180f' ,'false' ,0 ,'05' ,'infoSusp_3_indSusp_05' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001003 ,3002412 ,'Sentença em Mandado de Segurança Favorável ao Contribuinte' ,'sentenca-em-mandado-de-seguranca-favo5c17e7d19341d' ,'false' ,0 ,'08' ,'infoSusp_3_indSusp_08' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001004 ,3002412 ,'Sentença em Ação Ordinária Favorável ao Contribuinte e Confirmadapelo TRF' ,'sentenca-em-acao-ordinaria-favoravel-5c17e7d194f4c' ,'false' ,0 ,'09' ,'infoSusp_3_indSusp_09' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001005 ,3002412 ,'Acórdão do TRF Favorável ao Contribuinte' ,'acordao-do-trf-favoravel-ao-contribui5c17e7d196a41' ,'false' ,0 ,'10' ,'infoSusp_3_indSusp_10' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001006 ,3002412 ,'Acórdão do STJ em Recurso Especial Favorável ao Contribuinte' ,'acordao-do-stj-em-recurso-especial-fa5c17e7d19867f' ,'false' ,0 ,'11' ,'infoSusp_3_indSusp_11' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001007 ,3002412 ,'Acórdão do STF em Recurso Extraordinário Favorável ao Contribuinte' ,'acordao-do-stf-em-recurso-extraordina5c17e7d19a12c' ,'false' ,0 ,'12' ,'infoSusp_3_indSusp_12' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001008 ,3002412 ,'Sentença 1a instância não transitada em julgado com efeito suspensivo' ,'sentenca-1a-instancia-nao-transitada-5c17e7d1a0e97' ,'false' ,0 ,'13' ,'infoSusp_3_indSusp_13' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001009 ,3002412 ,'Decisão Definitiva a favor do contribuinte' ,'decisao-definitiva-a-favor-do-contrib5c17e7d1a2d8f' ,'false' ,0 ,'90' ,'infoSusp_3_indSusp_90' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001010 ,3002412 ,'Sem suspensão da exigibilidade' ,'sem-suspensao-da-exigibilidade5c17e7d1a4f19' ,'false' ,0 ,'92' ,'infoSusp_3_indSusp_92' );
            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3002413 ,2 ,3000535 ,'Data da decisão, sentença ou despacho administrativo.' ,'data-da-decisao-sentenca-ou-despacho5c17e7d1a6d2e' ,'false' ,'true' ,3 ,5 ,'' ,0 ,'false' ,'' ,'infoSusp_3_dtDecisao' );
            delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 3002413;
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001011 ,3002413 ,'' ,'5c17e7d1a9bc4' ,'true' ,0 ,'' ,'infoSusp_3_dtDecisao' );
            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3002414 ,1 ,3000535 ,'Indicativo de Depósito do Montante Integral' ,'indicativo-de-deposito-do-montante-in5c17e7d1abb22' ,'false' ,'true' ,4 ,1 ,'' ,0 ,'false' ,'' ,'infoSusp_3_indDeposito' );
            delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 3002414;
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001012 ,3002414 ,'Sim' ,'sim5c17e7d1aeeb3' ,'false' ,0 ,'S' ,'infoSusp_3_indDeposito_S' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001013 ,3002414 ,'Não' ,'nao5c17e7d1b0fa0' ,'false' ,0 ,'N' ,'infoSusp_3_indDeposito_N' );
            insert into avaliacaogrupopergunta( db102_sequencial ,db102_avaliacao ,db102_descricao ,db102_identificador ,db102_identificadorcampo ,db102_ordem ) values ( 3000536 ,3000037 ,'4 - Informações de Suspensão de Exibilidade de tributos' ,'4-informacoes-de-suspensao-de-exibilidade-de-tribu' ,'infoSusp_4' ,5 );
            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3002415 ,2 ,3000536 ,'Código do Indicativo da Suspensão, atribuído pelo contribuinte.' ,'codigo-do-indicativo-da-suspensao-at5c17e7d1b44a1' ,'false' ,'true' ,1 ,1 ,'' ,0 ,'false' ,'' ,'infoSusp_4_codSusp' );
            delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 3002415;
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001014 ,3002415 ,'' ,'5c17e7d1b7634' ,'true' ,0 ,'' ,'infoSusp_4_codSusp' );
            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3002416 ,1 ,3000536 ,'Indicativo de suspensão da exigibilidade' ,'indicativo-de-suspensao-da-exigibilid5c17e7d1ba374' ,'false' ,'true' ,2 ,1 ,'' ,0 ,'false' ,'' ,'infoSusp_4_indSusp' );
            delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 3002416;
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001015 ,3002416 ,'Liminar em Mandado de Segurança' ,'liminar-em-mandado-de-seguranca5c17e7d1c02b7' ,'false' ,0 ,'01' ,'infoSusp_4_indSusp_01' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001016 ,3002416 ,'Depósito Judicial do Montante Integral' ,'deposito-judicial-do-montante-integra5c17e7d1c2ee8' ,'false' ,0 ,'02' ,'infoSusp_4_indSusp_02' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001017 ,3002416 ,'Depósito Administrativo do Montante Integral' ,'deposito-administrativo-do-montante-i5c17e7d1c5bda' ,'false' ,0 ,'03' ,'infoSusp_4_indSusp_03' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001018 ,3002416 ,'Antecipação de Tutela' ,'antecipacao-de-tutela5c17e7d1c82bf' ,'false' ,0 ,'04' ,'infoSusp_4_indSusp_04' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001019 ,3002416 ,'Liminar em Medida Cautelar' ,'liminar-em-medida-cautelar5c17e7d1cac7b' ,'false' ,0 ,'05' ,'infoSusp_4_indSusp_05' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001020 ,3002416 ,'Sentença em Mandado de Segurança Favorável ao Contribuinte' ,'sentenca-em-mandado-de-seguranca-favo5c17e7d1cd328' ,'false' ,0 ,'08' ,'infoSusp_4_indSusp_08' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001021 ,3002416 ,'Sentença em Ação Ordinária Favorável ao Contribuinte e Confirmadapelo TRF' ,'sentenca-em-acao-ordinaria-favoravel-5c17e7d1cf315' ,'false' ,0 ,'09' ,'infoSusp_4_indSusp_09' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001022 ,3002416 ,'Acórdão do TRF Favorável ao Contribuinte' ,'acordao-do-trf-favoravel-ao-contribui5c17e7d1d165f' ,'false' ,0 ,'10' ,'infoSusp_4_indSusp_10' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001023 ,3002416 ,'Acórdão do STJ em Recurso Especial Favorável ao Contribuinte' ,'acordao-do-stj-em-recurso-especial-fa5c17e7d1d320b' ,'false' ,0 ,'11' ,'infoSusp_4_indSusp_11' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001024 ,3002416 ,'Acórdão do STF em Recurso Extraordinário Favorável ao Contribuinte' ,'acordao-do-stf-em-recurso-extraordina5c17e7d1d53bf' ,'false' ,0 ,'12' ,'infoSusp_4_indSusp_12' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001025 ,3002416 ,'Sentença 1a instância não transitada em julgado com efeito suspensivo' ,'sentenca-1a-instancia-nao-transitada-5c17e7d1d6f31' ,'false' ,0 ,'13' ,'infoSusp_4_indSusp_13' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001026 ,3002416 ,'Decisão Definitiva a favor do contribuinte' ,'decisao-definitiva-a-favor-do-contrib5c17e7d1d9a9e' ,'false' ,0 ,'90' ,'infoSusp_4_indSusp_90' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001027 ,3002416 ,'Sem suspensão da exigibilidade' ,'sem-suspensao-da-exigibilidade5c17e7d1db6a6' ,'false' ,0 ,'92' ,'infoSusp_4_indSusp_92' );
            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3002417 ,2 ,3000536 ,'Data da decisão, sentença ou despacho administrativo.' ,'data-da-decisao-sentenca-ou-despacho5c17e7d1dd916' ,'false' ,'true' ,3 ,5 ,'' ,0 ,'false' ,'' ,'infoSusp_4_dtDecisao' );
            delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 3002417;
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001028 ,3002417 ,'' ,'5c17e7d1e0523' ,'true' ,0 ,'' ,'infoSusp_4_dtDecisao' );
            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3002418 ,1 ,3000536 ,'Indicativo de Depósito do Montante Integral' ,'indicativo-de-deposito-do-montante-in5c17e7d1e1f81' ,'false' ,'true' ,4 ,1 ,'' ,0 ,'false' ,'' ,'infoSusp_4_indDeposito' );
            delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 3002418;
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001029 ,3002418 ,'Sim' ,'sim5c17e7d1e48e4' ,'false' ,0 ,'S' ,'infoSusp_4_indDeposito_S' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001030 ,3002418 ,'Não' ,'nao5c17e7d1e63b2' ,'false' ,0 ,'N' ,'infoSusp_4_indDeposito_N' );
            insert into avaliacaogrupopergunta( db102_sequencial ,db102_avaliacao ,db102_descricao ,db102_identificador ,db102_identificadorcampo ,db102_ordem ) values ( 3000537 ,3000037 ,'5 - Informações de Suspensão de Exibilidade de tributos' ,'5-informacoes-de-suspensao-de-exibilidade-de-tribu' ,'infoSusp_5' ,6 );
            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3002419 ,2 ,3000537 ,'Código do Indicativo da Suspensão, atribuído pelo contribuinte.' ,'codigo-do-indicativo-da-suspensao-at5c17e7d1e96fe' ,'false' ,'true' ,1 ,1 ,'' ,0 ,'false' ,'' ,'infoSusp_5_codSusp' );
            delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 3002419;
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001031 ,3002419 ,'' ,'5c17e7d1ebbe0' ,'true' ,0 ,'' ,'infoSusp_5_codSusp' );
            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3002420 ,1 ,3000537 ,'Indicativo de suspensão da exigibilidade' ,'indicativo-de-suspensao-da-exigibilid5c17e7d1edd8b' ,'false' ,'true' ,2 ,1 ,'' ,0 ,'false' ,'' ,'infoSusp_5_indSusp' );
            delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 3002420;
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001032 ,3002420 ,'Liminar em Mandado de Segurança' ,'liminar-em-mandado-de-seguranca5c17e7d1f0e91' ,'false' ,0 ,'01' ,'infoSusp_5_indSusp_01' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001033 ,3002420 ,'Depósito Judicial do Montante Integral' ,'deposito-judicial-do-montante-integra5c17e7d1f4059' ,'false' ,0 ,'02' ,'infoSusp_5_indSusp_02' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001034 ,3002420 ,'Depósito Administrativo do Montante Integral' ,'deposito-administrativo-do-montante-i5c17e7d20ee24' ,'false' ,0 ,'03' ,'infoSusp_5_indSusp_03' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001035 ,3002420 ,'Antecipação de Tutela' ,'antecipacao-de-tutela5c17e7d210dd9' ,'false' ,0 ,'04' ,'infoSusp_5_indSusp_04' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001036 ,3002420 ,'Liminar em Medida Cautelar' ,'liminar-em-medida-cautelar5c17e7d215484' ,'false' ,0 ,'05' ,'infoSusp_5_indSusp_05' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001037 ,3002420 ,'Sentença em Mandado de Segurança Favorável ao Contribuinte' ,'sentenca-em-mandado-de-seguranca-favo5c17e7d217aca' ,'false' ,0 ,'08' ,'infoSusp_5_indSusp_08' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001038 ,3002420 ,'Sentença em Ação Ordinária Favorável ao Contribuinte e Confirmadapelo TRF' ,'sentenca-em-acao-ordinaria-favoravel-5c17e7d21a79b' ,'false' ,0 ,'09' ,'infoSusp_5_indSusp_09' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001039 ,3002420 ,'Acórdão do TRF Favorável ao Contribuinte' ,'acordao-do-trf-favoravel-ao-contribui5c17e7d21c74a' ,'false' ,0 ,'10' ,'infoSusp_5_indSusp_10' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001040 ,3002420 ,'Acórdão do STJ em Recurso Especial Favorável ao Contribuinte' ,'acordao-do-stj-em-recurso-especial-fa5c17e7d21ead6' ,'false' ,0 ,'11' ,'infoSusp_5_indSusp_11' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001041 ,3002420 ,'Acórdão do STF em Recurso Extraordinário Favorável ao Contribuinte' ,'acordao-do-stf-em-recurso-extraordina5c17e7d221758' ,'false' ,0 ,'12' ,'infoSusp_5_indSusp_12' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001042 ,3002420 ,'Sentença 1a instância não transitada em julgado com efeito suspensivo' ,'sentenca-1a-instancia-nao-transitada-5c17e7d224b34' ,'false' ,0 ,'13' ,'infoSusp_5_indSusp_13' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001043 ,3002420 ,'Decisão Definitiva a favor do contribuinte' ,'decisao-definitiva-a-favor-do-contrib5c17e7d227d54' ,'false' ,0 ,'90' ,'infoSusp_5_indSusp_90' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001044 ,3002420 ,'Sem suspensão da exigibilidade' ,'sem-suspensao-da-exigibilidade5c17e7d229e32' ,'false' ,0 ,'92' ,'infoSusp_5_indSusp_92' );
            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3002421 ,2 ,3000537 ,'Data da decisão, sentença ou despacho administrativo.' ,'data-da-decisao-sentenca-ou-despacho5c17e7d22ce41' ,'false' ,'true' ,3 ,5 ,'' ,0 ,'false' ,'' ,'infoSusp_5_dtDecisao' );
            delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 3002421;
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001045 ,3002421 ,'' ,'5c17e7d230672' ,'true' ,0 ,'' ,'infoSusp_5_dtDecisao' );
            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3002422 ,1 ,3000537 ,'Indicativo de Depósito do Montante Integral' ,'indicativo-de-deposito-do-montante-in5c17e7d232f9e' ,'false' ,'true' ,4 ,1 ,'' ,0 ,'false' ,'' ,'infoSusp_5_indDeposito' );
            delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 3002422;
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001046 ,3002422 ,'Sim' ,'sim5c17e7d23b3e0' ,'false' ,0 ,'S' ,'infoSusp_5_indDeposito_S' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001047 ,3002422 ,'Não' ,'nao5c17e7d23d7b8' ,'false' ,0 ,'N' ,'infoSusp_5_indDeposito_N' );
            insert into avaliacaogrupopergunta( db102_sequencial ,db102_avaliacao ,db102_descricao ,db102_identificador ,db102_identificadorcampo ,db102_ordem ) values ( 3000538 ,3000037 ,'Informações Complementares do Processo Judicial' ,'informacoes-complementares-do-process5c17e7d23fe3b' ,'dadosProcJud' ,7 );
            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3002423 ,2 ,3000538 ,'Identificação da Unidade da Federação - UF' ,'identificacao-da-unidade-da-federacao-uf' ,'false' ,'true' ,1 ,1 ,'' ,0 ,'false' ,'' ,'ufVara' );
            delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 3002423;
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001048 ,3002423 ,'' ,'5c17e7d245577' ,'true' ,0 ,'' ,'ufVara' );
            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3002424 ,2 ,3000538 ,'Código de Identificação da Vara.' ,'codigo-de-identificacao-da-vara' ,'false' ,'true' ,2 ,1 ,'' ,0 ,'false' ,'' ,'idVara' );
            delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 3002424;
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001049 ,3002424 ,'' ,'5c17e7d24a731' ,'true' ,0 ,'' ,'idVara' );
            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3002425 ,2 ,3000538 ,'Preencher com o município.' ,'preencher-com-o-municipio' ,'false' ,'true' ,2 ,1 ,'' ,0 ,'false' ,'' ,'codMunic' );
            delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 3002425;
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001050 ,3002425 ,'' ,'5c1a84d1d2dc5' ,'true' ,0 ,'' ,'codMunic' );
        ");
    }

    private function dicionario()
    {
        $this->execute("
            insert into db_sysarquivo 
            values (1010357, 'avaliacaogruporespostaefdprocesso', 'R1070 - Tabela de Processos do EFD', 'efd02', '2018-12-17', '', 0, 'f', 'f', 'f', 'f' ),
                   (1010358, 'efdreinfversao', 'Versão do efd reinf', 'efd01', '2018-12-18', 'Versão do efd', 0, 'f', 'f', 'f', 'f' ),
                   (1010359, 'efdreinfversaoformulario', 'Versão dos formulários do EFD', 'efd03', '2018-12-18', 'formulários efd', 0, 'f', 'f', 'f', 'f' );

            insert into db_sysarqmod 
            values (81,1010357),
                   (81,1010358),
                   (81,1010359);
            
            insert into db_syscampo 
            values (1010197,'efd02_sequencial','int4','PK','0', 'Código',4,'f','f','f',1,'text','Código'),
                   (1010198,'efd02_cgm','int4','Contribuinte','0', 'CGM',10,'f','f','f',1,'text','CGM'),
                   (1010199,'efd02_processo','varchar(100)','Código do processo','', 'Processo',100,'f','t','f',0,'text','Processo'),
                   (1010200,'efd02_tipoprocesso','int4','Tipo de processo: 1 - Administrativo; 2 - Judicial. ','0', 'Tipo de processo',10,'f','f','f',1,'text','Tipo de processo'),
                   (1010201,'efd02_avaliacaogruporesposta','int4','Preenchimento','0', 'Preenchimento',10,'f','f','f',1,'text','Preenchimento'),
                   (1010202,'efd02_avaliacao','int4','Avaliação','0', 'Avaliação',10,'f','f','f',1,'text','Avaliação'),
                   (1010203,'efd01_sequencial','int4','PK','0', 'Código',10,'f','f','f',1,'text','Código'),
                   (1010204,'efd01_versao','varchar(10)','Versão','', 'Versão',10,'f','t','f',0,'text','Versão'),
                   (1010205,'efd03_sequencial','int4','Código','0', 'Código',10,'f','f','f',1,'text','Código'),
                   (1010206,'efd03_versao','varchar(10)','Versão do EFD','', 'Versão',10,'f','t','f',0,'text','Versão'),
                   (1010207,'efd03_avaliacao','int4','Avaliação','0', 'Avaliação',10,'f','f','f',1,'text','Avaliação'),
                   (1010208,'efd03_esocialformulariotipo','int4','Tipo','0', 'Tipo',10,'f','f','f',1,'text','Tipo');

            
            insert into db_sysarqcamp 
            values (1010357,1010197,1,0),
                   (1010357,1010198,2,0),
                   (1010357,1010199,3,0),
                   (1010357,1010200,4,0),
                   (1010357,1010201,5,0),
                   (1010357,1010202,6,0),
                   (1010358,1010203,1,0),
                   (1010358,1010204,2,0),
                   (1010359,1010205,1,0),
                   (1010359,1010206,2,0),
                   (1010359,1010207,3,0),
                   (1010359,1010208,4,0);
            
            insert into db_sysprikey (codarq,codcam,sequen,camiden) 
            values (1010357,1010197,1,1010197),
                   (1010358,1010203,1,1010204),
                   (1010359,1010205,1,1010205);
            
            insert into db_sysforkey 
            values (1010357,1010198,1,42, 0),
                   (1010357,1010201,1,2987,0),
                   (1010357,1010202,1,2980,0),
                   (1010359,1010207,1,2980,0),
                   (1010359,1010208,1,1010283,0);

            insert into db_sysindices 
            values (1008397,'avaliacaogruporespostaefdprocesso_cgm_processo_tipoprocesso',1010357,'1'),
                   (1008398,'avaliacaogruporespostaefdprocesso_avaliacao_in',1010357,'0'),
                   (1008399,'avaliacaogruporespostaefdprocesso__avaliacaogruporesposta_in',1010357,'0'),
                   (1008401,'efdreinfversao_versao_in',1010358,'1'),
                   (1008402,'efdreinfversaoformulario_versao_in',1010359,'0'),
                   (1008403,'efdreinfversaoformulario_avaliacao_in',1010359,'0'),
                   (1008404,'efdreinfversaoformulario_esocialformulariotipo_in',1010359,'0');

            insert into db_syscadind 
            values (1008397,1010198,1),
                   (1008397,1010199,2),
                   (1008397,1010200,3),
                   (1008398,1010202,1),
                   (1008399,1010201,1),
                   (1008401,1010204,1),
                   (1008402,1010206,1),
                   (1008403,1010207,1),
                   (1008404,1010208,1);

            insert into db_syssequencia 
            values (1000799, 'avaliacaogruporespostaefdprocesso_efd02_sequencial_seq', 1, 1, 9223372036854775807, 1, 1),
                   (1000800, 'efdreinfversao_efd01_sequencial_seq', 1, 1, 9223372036854775807, 1, 1),
                   (1000801, 'efdreinfversaoformulario_efd03_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);

            update db_sysarqcamp set codsequencia = 1000799 where codarq = 1010357 and codcam = 1010197;
            update db_sysarqcamp set codsequencia = 1000800 where codarq = 1010358 and codcam = 1010203;
            update db_sysarqcamp set codsequencia = 1000801 where codarq = 1010359 and codcam = 1010205;
        ");
    }

    private function estrutura()
    {
        $this->execute("
            create sequence avaliacaogruporespostaefdprocesso_efd02_sequencial_seq increment 1 minvalue 1 maxvalue 9223372036854775807 start 1 cache 1;

            create table avaliacaogruporespostaefdprocesso(
            efd02_sequencial int4,
            efd02_cgm int4,
            efd02_processo varchar(100),
            efd02_tipoprocesso int4,
            efd02_avaliacaogruporesposta int4,
            efd02_avaliacao int4,
            CONSTRAINT avaliacaogruporespostaefdprocesso_sequ_pk PRIMARY KEY (efd02_sequencial));
            
            alter table avaliacaogruporespostaefdprocesso add constraint avaliacaogruporespostaefdprocesso_cgm_fk foreign key (efd02_cgm) references cgm;
            alter table avaliacaogruporespostaefdprocesso add constraint avaliacaogruporespostaefdprocesso_avaliacao_fk foreign key (efd02_avaliacao) references avaliacao;
            alter table avaliacaogruporespostaefdprocesso add constraint avaliacaogruporespostaefdprocesso_avaliacaogruporesposta_fk foreign key (efd02_avaliacaogruporesposta) references avaliacaogruporesposta;
            
            create unique index avaliacaogruporespostaefdprocesso_cgm_processo_tipoprocesso on avaliacaogruporespostaefdprocesso(efd02_cgm,efd02_processo,efd02_tipoprocesso);
            create index avaliacaogruporespostaefdprocesso_avaliacao_in on avaliacaogruporespostaefdprocesso(efd02_avaliacao);
            create index avaliacaogruporespostaefdprocesso__avaliacaogruporesposta_in on avaliacaogruporespostaefdprocesso(efd02_avaliacaogruporesposta);
            
            create table esocial.efdreinfversao (
              efd01_sequencial serial,
              efd01_versao varchar (10),
              CONSTRAINT efdreinfversao_sequ_pk PRIMARY KEY (efd01_sequencial)
            );
            
            create table esocial.efdreinfversaoformulario (
              efd03_sequencial serial,
              efd03_versao varchar (10) not null,
              efd03_avaliacao integer not null,
              efd03_esocialformulariotipo integer not null,
              CONSTRAINT efdreinfversaoformulario_sequ_pk PRIMARY KEY (efd03_sequencial)
            );
            
            ALTER TABLE efdreinfversaoformulario ADD CONSTRAINT efdreinfversaoformulario_avaliacao_fk FOREIGN KEY (efd03_avaliacao) REFERENCES avaliacao;
            ALTER TABLE efdreinfversaoformulario ADD CONSTRAINT efdreinfversaoformulario_esocialformulariotipo_fk FOREIGN KEY (efd03_esocialformulariotipo) REFERENCES esocialformulariotipo;
            
            CREATE SEQUENCE efdreinfversao_efd01_sequencial_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1 CACHE 1;
            CREATE SEQUENCE efdreinfversaoformulario_efd03_sequencial_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1 CACHE 1;
            
            CREATE UNIQUE INDEX efdreinfversao_versao_in ON efdreinfversao(efd01_versao);
            CREATE  INDEX efdreinfversaoformulario_versao_in ON efdreinfversaoformulario(efd03_versao);
            CREATE  INDEX efdreinfversaoformulario_avaliacao_in ON efdreinfversaoformulario(efd03_avaliacao);
            CREATE  INDEX efdreinfversaoformulario_esocialformulariotipo_in ON efdreinfversaoformulario(efd03_esocialformulariotipo);
        ");

        $this->execute("
            insert into esocial.efdreinfversao (efd01_versao) values ('1.4');
            insert into esocial.efdreinfversaoformulario (efd03_versao, efd03_avaliacao, efd03_esocialformulariotipo) values ('1.4', 3000034, 22);
            insert into esocial.efdreinfversaoformulario (efd03_versao, efd03_avaliacao, efd03_esocialformulariotipo) values ('1.4', 3000037, 23);
            delete from recursoshumanos.esocialversaoformulario where rh211_avaliacao = 3000034;
        ");
    }

    private function menu()
    {
        $this->execute("
            insert into db_itensmenu(id_item, descricao, help, funcao, itemativo, manutencao, desctec, libcliente) values ( 228085 ,'Processos Administrativos/Judiciais' ,'Processos Administrativos/Judiciais' ,'edf04_r1070_processos001.php' ,'1' ,'1' ,'R1070 - Processos Administrativos/Judiciais' ,'true' );
            insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 228079 ,228085 ,2 ,228077 );
        ");
    }
}
