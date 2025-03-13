<?php

use Classes\PostgresMigration;

class M9034ContribuinteEfd extends PostgresMigration
{
    public function up()
    {
        $this->criarDicionario();
        $this->criarTabela();
        $this->criaFormulario();
        $this->criaEstruturaSped();
        $this->criaAreaModuloMenu();
        $this->atualizaCampoEvento();
    }

    public function down()
    {
        $this->removerDicionario();
        $this->removerTabela();
        $this->deletaEstruturaSped();
        $this->deletaFormulario();
        $this->deletaAreaModuloMenu();
        $this->reverteCampoEvento();
    }

    private function criarDicionario() {
        $sql = <<<SQL
        insert into db_sysarquivo values (1010353, 'avaliacaogruporespostacontribuinte', 'Guarda o vínculo entre grupo de respostas e cgm (que é o contribuinte) para o arquivo R-1000 - Informações do Contribuinte.', 'eso27', '2018-12-12', 'Vínculo entre preenchimento e contribuinte', 0, 'f', 'f', 'f', 'f' );
        insert into db_sysarqmod values (81,1010353);
        
        insert into db_syscampo values(1010179,'eso27_sequencial','int4','Sequencial (chave única) para ligar o preenchimento do formulário com o cgm do contribuinte.','0', 'Sequencial',10,'f','f','f',1,'text','Sequencial');
        insert into db_syscampo values(1010180,'eso27_avaliacaogruporesposta','int4','Sequencial (chave única) da tabela que guarda o grupo de respostas do formulário.','0', 'Código do Grupo de Resposta',10,'f','f','f',1,'text','Código do Grupo de Resposta');
        insert into db_syscampo values(1010181,'eso27_cgm','int4','Sequencial (chave única) do cgm do contribuinte do EFD.','0', 'Cgm do Contribuinte',10,'f','f','f',1,'text','Cgm do Contribuinte');
        
        insert into db_sysarqcamp values(1010353,1010179,1,0);
        insert into db_sysarqcamp values(1010353,1010180,2,0);
        insert into db_sysarqcamp values(1010353,1010181,3,0);
        
        insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010353,1010179,1,1010179);
        insert into db_sysforkey values(1010353,1010180,1,2987,0);
        insert into db_sysforkey values(1010353,1010181,1,42,0);

        insert into db_syssequencia values(1000795, 'avaliacaogruporespostacontribuinte_eso27_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
        update db_sysarqcamp set codsequencia = 1000795 where codarq = 1010353 and codcam = 1010179;
SQL;
        $this->execute($sql);
    }

    private function removerDicionario() {
        $sql = <<<SQL
        DELETE 
        FROM db_acount 
        WHERE codarq = 1010353;

        delete from db_syssequencia where codsequencia = 1000795;
        delete from db_sysforkey where codarq = 1010353;
        delete from db_sysprikey where codarq = 1010353;
        delete from db_sysarqcamp where codarq = 1010353;
        delete from db_syscampo where codcam in (1010179, 1010180, 1010181);
        delete from db_sysarqmod where codarq = 1010353;
        delete from db_sysarquivo where codarq = 1010353;
SQL;
        $this->execute($sql);
    }

    private function criarTabela() {
        $sql = "
            CREATE SEQUENCE avaliacaogruporespostacontribuinte_eso27_sequencial_seq
            INCREMENT 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1
            CACHE 1;
    
            CREATE TABLE avaliacaogruporespostacontribuinte(
            eso27_sequencial		int4 NOT NULL default 0,
            eso27_avaliacaogruporesposta		int4 NOT NULL default 0,
            eso27_cgm		int4 default 0,
            CONSTRAINT avaliacaogruporespostacontribuinte_sequ_pk PRIMARY KEY (eso27_sequencial));
    
            ALTER TABLE avaliacaogruporespostacontribuinte
            ADD CONSTRAINT avaliacaogruporespostacontribuinte_avaliacaogruporesposta_fk FOREIGN KEY (eso27_avaliacaogruporesposta)
            REFERENCES avaliacaogruporesposta;
            
            ALTER TABLE avaliacaogruporespostacontribuinte
            ADD CONSTRAINT avaliacaogruporespostacontribuinte_cgm_fk FOREIGN KEY (eso27_cgm)
            REFERENCES cgm;
        ";
        $this->execute($sql);
    }

    private function removerTabela() {
        $sql = "
            DROP SEQUENCE IF EXISTS avaliacaogruporespostacontribuinte_eso27_sequencial_seq;
            DROP TABLE IF EXISTS avaliacaogruporespostacontribuinte;
        ";
        $this->execute($sql);
    }

    private function criaFormulario()
    {
        $sql = "
        INSERT INTO avaliacao( db101_sequencial ,db101_avaliacaotipo ,db101_descricao ,db101_identificador ,db101_obs ,db101_ativo ,db101_cargadados ,db101_permiteedicao ) values ( 3000034 ,5 ,'R-1000 - Informações do Contribuinte' ,'r1000-informacoes-do-contribuinte' ,'Registros do evento R-1000 - Informações do Contribuinte' ,'true' ,'' ,'true' );
        INSERT INTO avaliacaogrupopergunta( db102_sequencial ,db102_avaliacao ,db102_descricao ,db102_identificador ,db102_identificadorcampo ,db102_ordem ) values ( 3000526 ,3000034 ,'Período de validade das informações incluídas' ,'periodo-de-validade-das-informacoes-incluidas' ,'idePeriodo' ,2 );
        INSERT INTO avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3002379 ,2 ,3000526 ,'Preencher com o mês e ano de início da validade das informações prestadas no evento, no formato AAAA-MM' ,'preencher-com-o-mes-e-ano-de-inicio-da-validade-da' ,'true' ,'true' ,1 ,1 ,'' ,0 ,'false' ,'' ,'iniValid' );
        INSERT INTO avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4000923 ,3002379 ,'' ,'5c10ffa7e6d90' ,'true' ,0 ,'' ,'iniValid' );
        INSERT INTO avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3002380 ,2 ,3000526 ,'Preencher com o mês e ano de término da validade das informações, se houver.' ,'preencher-com-o-mes-e-ano-de-termino-da-validade-d' ,'false' ,'true' ,2 ,1 ,'' ,0 ,'false' ,'' ,'fimValid' );
        INSERT INTO avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4000924 ,3002380 ,'' ,'5c10ffa7e866d' ,'true' ,0 ,'' ,'fimValid' );
        INSERT INTO avaliacaogrupopergunta( db102_sequencial ,db102_avaliacao ,db102_descricao ,db102_identificador ,db102_identificadorcampo ,db102_ordem ) values ( 3000527 ,3000034 ,'Informações do Contribuinte' ,'informacoes-do-contribuinte' ,'infoCadastro' ,3 );
        INSERT INTO avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3002381 ,2 ,3000527 ,'Preencher com o código correspondente à classificação tributária do contribuinte, conforme tabela 8.' ,'preencher-com-o-codigo-correspondente-a-classifica' ,'true' ,'true' ,1 ,1 ,'' ,0 ,'false' ,'' ,'classTrib' );
        INSERT INTO avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4000925 ,3002381 ,'' ,'5c10ffa7ea3e9' ,'true' ,0 ,'' ,'classTrib' );
        INSERT INTO avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3002382 ,1 ,3000527 ,'Indicativo da obrigatoriedade do contribuinte em fazer a sua escrituração contábil na ECD Escrituração Contábil Digital' ,'indicativo-da-obrigatoriedade-do-contribuinte-em-f' ,'true' ,'true' ,2 ,1 ,'' ,0 ,'false' ,'' ,'indEscrituracao' );
        INSERT INTO avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4000926 ,3002382 ,'Empresa Não obrigada à ECD' ,'empresa-nao-obrigada-a-ecd' ,'false' ,0 ,'0' ,'indEscrituracao_0' );
        INSERT INTO avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4000927 ,3002382 ,'Empresa obrigada à ECD' ,'empresa-obrigada-a-ecd' ,'false' ,0 ,'1' ,'indEscrituracao_1' );
        INSERT INTO avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3002383 ,1 ,3000527 ,'Indicativo de desoneração da folha de pagamento' ,'indicativo-de-desoneracao-da-folha-de-pagamento' ,'true' ,'true' ,3 ,1 ,'' ,0 ,'false' ,'' ,'indDesoneracao' );
        INSERT INTO avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4000928 ,3002383 ,'0 - Não Aplicável' ,'0-nao-aplicavel' ,'false' ,0 ,'0' ,'indDesoneracao_0' );
        INSERT INTO avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4000929 ,3002383 ,'1 - Empresa enquadrada nos artigos 7° a 9° da Lei 12.546/2011.' ,'1-empresa-enquadrada-nos-artigos-7-a-9-da-lei-1254' ,'false' ,0 ,'1' ,'indDesoneracao_1' );
        INSERT INTO avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3002384 ,1 ,3000527 ,'Indicativo da existência de acordo internacional para isenção de multa' ,'indicativo-da-existencia-de-acordo-internacional-p' ,'true' ,'true' ,4 ,1 ,'' ,0 ,'false' ,'' ,'indAcordoIsenMulta' );
        INSERT INTO avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4000930 ,3002384 ,'0 - Sem acordo' ,'0-sem-acordo' ,'false' ,0 ,'0' ,'indAcordoIsenMulta_0' );
        INSERT INTO avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4000931 ,3002384 ,'1 - Com acordo' ,'1-com-acordo' ,'false' ,0 ,'1' ,'indAcordoIsenMulta_1' );
        INSERT INTO avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3002385 ,1 ,3000527 ,'Indicativo da Situação da Pessoa Jurídica' ,'indicativo-da-situacao-da-pessoa-juridica' ,'false' ,'true' ,5 ,1 ,'' ,0 ,'false' ,'' ,'indSitPJ' );
        INSERT INTO avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4000932 ,3002385 ,'0 - Situação Normal' ,'0-situacao-normal' ,'false' ,0 ,'0' ,'indSitPJ_0' );
        INSERT INTO avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4000933 ,3002385 ,'1 - Extinção' ,'1-extincao' ,'false' ,0 ,'1' ,'indSitPJ_1' );
        INSERT INTO avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4000934 ,3002385 ,'2 - Fusão' ,'2-fusao' ,'false' ,0 ,'2' ,'indSitPJ_2' );
        INSERT INTO avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4000935 ,3002385 ,'3 - Cisão' ,'3-cisao' ,'false' ,0 ,'3' ,'indSitPJ_3' );
        INSERT INTO avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4000936 ,3002385 ,'4 - Incorporação' ,'4-incorporacao' ,'false' ,0 ,'4' ,'indSitPJ_4' );
        INSERT INTO avaliacaogrupopergunta( db102_sequencial ,db102_avaliacao ,db102_descricao ,db102_identificador ,db102_identificadorcampo ,db102_ordem ) values ( 3000528 ,3000034 ,'Informações de contato' ,'informacoes-de-contato5c10ffa805ad7' ,'contato' ,4 );
        INSERT INTO avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3002386 ,2 ,3000528 ,'Nome do contato no contribuinte. Pessoa responsável por ser o contato do contribuinte com a Receita Federal do Brasil relativamente à EFD-Reinf' ,'nome-do-contato-no-contribuinte-pessoa-responsavel' ,'true' ,'true' ,1 ,1 ,'' ,0 ,'false' ,'' ,'nmCtt' );
        INSERT INTO avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4000937 ,3002386 ,'' ,'5c10ffa80757b' ,'true' ,0 ,'' ,'nmCtt' );
        INSERT INTO avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3002387 ,2 ,3000528 ,'Preencher com o número do CPF do contato' ,'preencher-com-o-numero-do-cpf-do-contato' ,'true' ,'true' ,2 ,1 ,'' ,0 ,'false' ,'' ,'cpfCtt' );
        INSERT INTO avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4000938 ,3002387 ,'' ,'5c10ffa808e52' ,'true' ,0 ,'' ,'cpfCtt' );
        INSERT INTO avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3002388 ,2 ,3000528 ,'Informar o número do telefone, com DDD' ,'informar-o-numero-do-telefone-com-ddd' ,'false' ,'true' ,3 ,1 ,'' ,0 ,'false' ,'' ,'foneFixo' );
        INSERT INTO avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4000939 ,3002388 ,'' ,'5c10ffa80a211' ,'true' ,0 ,'' ,'foneFixo' );
        INSERT INTO avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3002389 ,2 ,3000528 ,'Telefone celular, com DDD' ,'telefone-celular-com-ddd' ,'false' ,'true' ,4 ,1 ,'' ,0 ,'false' ,'' ,'foneCel' );
        INSERT INTO avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4000940 ,3002389 ,'' ,'5c10ffa80b8b7' ,'true' ,0 ,'' ,'foneCel' );
        INSERT INTO avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3002390 ,2 ,3000528 ,'E-mail' ,'email5c10ffa811aea' ,'false' ,'true' ,5 ,1 ,'' ,0 ,'false' ,'' ,'email' );
        INSERT INTO avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4000941 ,3002390 ,'' ,'5c10ffa812d57' ,'true' ,0 ,'' ,'email' );
        INSERT INTO avaliacaogrupopergunta( db102_sequencial ,db102_avaliacao ,db102_descricao ,db102_identificador ,db102_identificadorcampo ,db102_ordem ) values ( 3000529 ,3000034 ,'Informações de órgãos públicos estaduais e municipais relativas a Ente Federativo Responsável - EFR' ,'informacoes-de-orgaos-publicos-estaduais-e-municip' ,'infoEFR' ,5 );
        INSERT INTO avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3002391 ,1 ,3000529 ,'Informar se o Órgão Público é o Ente Federativo Responsável - EFR ou se é uma unidade administrativa autônoma vinculada a um EFR' ,'informar-se-o-orgao-publico-e-o-ente-federativo-re' ,'true' ,'true' ,1 ,1 ,'' ,0 ,'false' ,'' ,'ideEFR' );
        INSERT INTO avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4000942 ,3002391 ,'S - É EFR' ,'s-e-efr' ,'false' ,0 ,'S' ,'ideEFR_s' );
        INSERT INTO avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4000943 ,3002391 ,'N - Não é EFR' ,'n-nao-e-efr' ,'false' ,0 ,'N' ,'ideEFR_n' );
        ";

        $this->execute($sql);
    }

    private function deletaFormulario()
    {
        $sql = "
            create temp table x_avaliacaopergunta as
             select db103_sequencial
               from avaliacaopergunta
              where db103_avaliacaogrupopergunta in (select db102_sequencial from avaliacaogrupopergunta where db102_avaliacao = 3000034);
            
            create temp table x_avaliacaoperguntaopcao as
             select db104_sequencial
               from avaliacaoperguntaopcao
              where db104_avaliacaopergunta in (select db103_sequencial from x_avaliacaopergunta);
              
            DELETE
            FROM avaliacaogrupoperguntaresposta
            WHERE db108_avaliacaoresposta IN (SELECT db106_sequencial 
                                              FROM avaliacaoresposta  
                                              WHERE db106_avaliacaoperguntaopcao IN (SELECT db104_sequencial 
                                                                               FROM avaliacaoperguntaopcao
                                                                               WHERE db104_avaliacaopergunta IN (SELECT db103_sequencial 
                                                                                                               FROM x_avaliacaopergunta)));        
            
            DELETE 
            FROM avaliacaoresposta  
            WHERE db106_avaliacaoperguntaopcao IN (SELECT db104_sequencial 
                                             FROM avaliacaoperguntaopcao
                                             WHERE db104_avaliacaopergunta IN (SELECT db103_sequencial 
                                                                               FROM x_avaliacaopergunta));
                                                                               
            delete from avaliacaoperguntaopcao where db104_avaliacaopergunta in (select db103_sequencial from x_avaliacaopergunta);
            delete from avaliacaopergunta where db103_sequencial in (select db103_sequencial from x_avaliacaopergunta);
            delete from avaliacaogrupopergunta where db102_avaliacao = 3000034;
            delete from avaliacao where db101_sequencial = 3000034;
            
            drop table x_avaliacaopergunta;
            drop table x_avaliacaoperguntaopcao;
        ";

        $this->execute($sql);
    }

    private function criaEstruturaSped()
    {
        $sql = "
          INSERT INTO recursoshumanos.esocialformulariotipo VALUES (22, 'R-1000 - Informações do Contribuinte');
          
          SELECT setval('esocialversaoformulario_rh211_sequencial_seq', (SELECT max(rh211_sequencial) FROM esocialversaoformulario));
          
          INSERT INTO recursoshumanos.esocialversaoformulario (rh211_sequencial, rh211_versao, rh211_avaliacao, rh211_esocialformulariotipo) 
          VALUES (nextval('esocialversaoformulario_rh211_sequencial_seq'), '2.4', 3000034, 22);
        ";
        $this->execute($sql);
    }

    private function deletaEstruturaSped()
    {
        $sql = "
          DELETE FROM recursoshumanos.esocialversaoformulario WHERE rh211_avaliacao = 3000034;
          DELETE FROM recursoshumanos.esocialformulariotipo WHERE rh209_sequencial = 22;
        ";
        $this->execute($sql);
    }

    private function criaAreaModuloMenu()
    {
        $sql = "
            insert into atendcadarea values ((select max(at26_sequencial) + 1 from atendcadarea), 'DB:INTEGRAÇÕES', 'DBINTEGRACOES.jpg');
            insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228077 ,'EFD-Reinf' ,'EFD-Reinf' ,'' ,'2' ,'1' ,'Módulo do Sistema Público de Escrituração Digital - SPED, a ser utilizado pelas pessoas jurídicas e físicas, em complemento ao Sistema de Escrituração Digital das Obrigações Fiscais, Previdenciárias e Trabalhistas - eSocial.' ,'true' );
            insert into db_modulos( id_item ,nome_modulo ,descr_modulo ,imagem ,temexerc ) values ( 228077 ,'EFD-Reinf' ,'EFD-Reinf' ,'EFDREINFMODULO.jpg' ,'true' );
            insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 228077 ,29 ,1 ,228077 );
            insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 228077 ,30 ,2 ,228077 );
            insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 228077 ,31 ,3 ,228077 );
            insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 228077 ,32 ,4 ,228077 );
            insert into atendcadareamod values ( (select max(at26_sequencia) + 1 from atendcadareamod), (select max(at26_sequencial) from atendcadarea), 228077);
            insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228079 ,'Preenchimentos' ,'Preenchimentos' ,'' ,'1' ,'1' ,'Menu para preenchimentos dos formulários do EFD-Reinf' ,'true' );
            insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 32 ,228079 ,507 ,228077 );
            
            insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228080 ,'Informações do Contribuinte' ,'Informações do Contribuinte', 'sped02_preenchimento.php?integracao=1&formularioTipo=22' ,'1' ,'1' ,'Formulário R-1000', 'true');
            insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 228079 ,228080 ,1 ,228077 );
            
            
            insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228081 ,'Situação de Eventos' ,'Situação de Eventos' ,'eso02_situacaoevento001.php?integracao=1' ,'1' ,'1' ,'Tela para consultar status dos eventos enviados para o EFD-Reinf' ,'true' );
            insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 31 ,228081 ,187 ,228077 );
            
            insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228082 ,'Envio de eventos para o EFD-Reinf' ,'Envio de eventos para o EFD-Reinf' ,'eso01_agendamentoenvio.php?integracao=1' ,'1' ,'1' ,'Tela para processamento e envio dos eventos do EFD para a API' ,'true' );
            insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 32 ,228082 ,508 ,228077 );
            
            insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228083 ,'Configuração' ,'Configuração' ,'' ,'1' ,'1' ,'Submenu de agrupamento de configurações para o EFD-Reinf' ,'true' );
            insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 32 ,228083 ,509 ,228077 );
            
            insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228084 ,'Configuração do Certificado' ,'Configuração do Certificado' ,'eso4_enviocertificado001.php?integracao=1' ,'1' ,'1' ,'Tela para configuração de certificados do EFD-Reinf' ,'true' );
            insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 228083 ,228084 ,1 ,228077 );

            
            
        ";
        $this->execute($sql);
    }

    private function deletaAreaModuloMenu()
    {
        $sql = "
            delete from db_menu where id_item_filho = 228079 AND modulo = 228077;
            delete from atendcadareamod where at26_id_item = 228077;
            delete from db_menu where modulo = 228077;
            delete from db_modulos where id_item = 228077;
            delete from db_itensmenu where id_item in(228077, 228079, 228080, 228081, 228082, 228083, 228084);
            delete from atendcadarea where at25_descr = 'DB:INTEGRAÇÕES';            
        ";
        $this->execute($sql);
    }

    private function atualizaCampoEvento()
    {
        $sql = "alter table esocialenvio alter column rh213_evento type varchar(6)";
        $this->execute($sql);
    }

    private function reverteCampoEvento()
    {
        $sql = "alter table esocialenvio alter column rh213_evento type integer using rh213_evento::integer";
        $this->execute($sql);
    }
}
