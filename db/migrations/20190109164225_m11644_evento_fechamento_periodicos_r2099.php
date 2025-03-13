<?php

use Classes\PostgresMigration;

class M11644EventoFechamentoPeriodicosR2099 extends PostgresMigration
{
    public function up()
    {
        $this->incluirFormulario();
        $this->incluirMenu();
        $this->incluirDicionarioTabela();
        $this->incluirTabela();
    }

    public function down()
    {
        $this->removerFormulario();
        $this->removerMenu();
        $this->removerDicionarioTabela();
        $this->removerTabela();
    }

    public function incluirFormulario()
    {
        $sql =
<<<SQL
        INSERT INTO avaliacao( db101_sequencial ,db101_avaliacaotipo ,db101_descricao ,db101_identificador ,db101_obs ,db101_ativo ,db101_cargadados ,db101_permiteedicao ) VALUES ( 3000042 ,8 ,'R-2099 - Fechamento dos Eventos Periódicos' ,'r2099-fechamento-dos-eventos-periodicos' ,'Registro do evento de fechamento dos eventos períodicos' ,'true' ,'' ,'true' );

        INSERT INTO esocialformulariotipo 
        VALUES (32, 'R-2099 - Fechamento dos Eventos Periódicos');

        SELECT setval('efdreinfversaoformulario_efd03_sequencial_seq', (SELECT max(efd03_sequencial) FROM efdreinfversaoformulario) );
        INSERT INTO efdreinfversaoformulario 
        VALUES (nextval('efdreinfversaoformulario_efd03_sequencial_seq'), '1.4', 3000042, 32);

        INSERT INTO avaliacaogrupopergunta ( db102_sequencial ,db102_avaliacao ,db102_descricao ,db102_identificador ,db102_identificadorcampo ,db102_ordem ) 
        VALUES ( 4000215 ,3000042 ,'Responsável pelas informações' ,'responsavel-pelas-informacoes' ,'ideRespInf' ,1 )
            , ( 4000216 ,3000042 ,'Informações do Fechamento' ,'informacoes-do-fechamento' ,'infoFech' ,2 );


        INSERT INTO avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) 
        VALUES ( 4000203 ,2 ,4000215 ,'Nome do responsável pelas informações:' ,'nome-do-responsavel-pelas-informacoes' ,'false' ,'true' ,1 ,1 ,'' ,0 ,'false' ,'' ,'nmResp' )
            ,( 4000204 ,2 ,4000215 ,'CPF do responsável:' ,'cpf-do-responsavel' ,'false' ,'true' ,2 ,4 ,'' ,0 ,'false' ,'' ,'cpfResp' )
            ,( 4000205 ,2 ,4000215 ,'Número do telefone (com DDD):' ,'numero-do-telefone-com-ddd' ,'false' ,'true' ,3 ,1 ,'' ,0 ,'false' ,'' ,'telefone' )
            ,( 4000206 ,2 ,4000215 ,'E-mail:' ,'email5c362363207fc' ,'false' ,'true' ,4 ,1 ,'' ,0 ,'false' ,'' ,'email' )
            ,( 4000207 ,1 ,4000216 ,'Contratou serviços sujeitos à retenção de contribuição previdenciária (evento R-2010):' ,'contratou-servicos-sujeitos-a-retencao-de-contribu' ,'true' ,'true' ,1 ,1 ,'' ,0 ,'false' ,'' ,'evtServTm' )
            ,( 4000208 ,1 ,4000216 ,'Prestou serviços sujeitos à retenção de contribuição previdenciária (evento R-2020):' ,'prestou-servicos-sujeitos-a-retencao-de-contribuic' ,'true' ,'true' ,2 ,1 ,'' ,0 ,'false' ,'' ,'evtServPr' )
            ,( 4000209 ,2 ,4000216 ,'Competência a partir de quando não houve movimento, no formato AAAA-MM:' ,'competencia-a-partir-de-quando-nao-houve-movimento' ,'false' ,'true' ,3 ,1 ,'' ,0 ,'false' ,'' ,'compSemMovto' );



        INSERT INTO avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) 
        VALUES ( 4001158 ,4000203 ,'' ,'5c362362e45c3' ,'true' ,0 ,'' ,'nmResp' )
            , ( 4001159 ,4000204 ,'' ,'5c36236312cb4' ,'true' ,0 ,'' ,'cpfResp' )
            , ( 4001160 ,4000205 ,'' ,'5c36236318a3b' ,'true' ,0 ,'' ,'telefone' )
            , ( 4001161 ,4000206 ,'' ,'5c36236323efe' ,'true' ,0 ,'' ,'email' )
            , ( 4001162 ,4000207 ,'Sim' ,'sim5c36236341ec7' ,'false' ,0 ,'1' ,'evtServTm_1' )
            , ( 4001163 ,4000207 ,'Não' ,'nao5c36236344752' ,'false' ,0 ,'2' ,'evtServTm_2' )
            , ( 4001164 ,4000208 ,'Sim' ,'sim5c36236350103' ,'false' ,0 ,'1' ,'evtServPr_1' )
            , ( 4001165 ,4000208 ,'Não' ,'nao5c36236352234' ,'false' ,0 ,'2' ,'evtServPr_2' )
            , ( 4001166 ,4000209 ,'' ,'5c3623635719e' ,'true' ,0 ,'' ,'compSemMovto' );
SQL;
        $this->execute($sql);
    }

    public function removerFormulario()
    {
        $sql =
<<<SQL
        delete from efdreinfversaoformulario where efd03_avaliacao = 3000042;
        delete from esocialformulariotipo where rh209_sequencial = 32;
        
        create temp table x_avaliacaopergunta as
        select db103_sequencial
        from avaliacaopergunta
        where db103_avaliacaogrupopergunta in (select db102_sequencial from avaliacaogrupopergunta where db102_avaliacao = 3000042);
        
        create temp table x_avaliacaoperguntaopcao as
        select db104_sequencial
        from avaliacaoperguntaopcao
        where db104_avaliacaopergunta in (select db103_sequencial from x_avaliacaopergunta);
        
        delete from avaliacaoperguntaopcao where db104_avaliacaopergunta in (select db103_sequencial from x_avaliacaopergunta);
        delete from avaliacaopergunta where db103_sequencial in (select db103_sequencial from x_avaliacaopergunta);
        delete from avaliacaogrupopergunta where db102_avaliacao = 3000042;
        delete from avaliacao where db101_sequencial = 3000042;
        
        drop table x_avaliacaopergunta;
        drop table x_avaliacaoperguntaopcao;
SQL;
        $this->execute($sql);
    }

    private function incluirMenu() 
    {
        $sql = 
<<<SQL
        INSERT INTO db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228092 ,'Fechamento dos Eventos Periódicos' ,'R-2099' ,'efd04_r2099_fechamento_periodicos001.php' ,'1' ,'1' ,'Formulário de Envio do Evento R-2099 - Fechamento dos Eventos Periódicos do EFD-Reinf' ,'true' );
        INSERT INTO db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 228079 ,228092 ,6 ,228077 );
SQL;
        $this->execute($sql);
    }

    private function removerMenu()
    {
        $sql =
<<<SQL
        DELETE FROM db_menu where id_item_filho = 228092 AND modulo = 228077;
        DELETE FROM db_itensmenu where id_item = 228092;
SQL;
        $this->execute($sql);
    }

    public function incluirDicionarioTabela()
    {
        $sql =
<<<SQL
        insert into db_sysarquivo values (1010403, 'avaliacaogruporespostafechamentoefd', 'Guarda o vínculo entre grupo de respostas e os dados chave de envio do evento R-2099 - Fechamento dos Eventos Periódicos do EFD.', 'eso32', '2019-01-09', 'Vínculo entre preenchimento e fechamento do EFD', 0, 'f', 'f', 'f', 'f' );
        insert into db_sysarqmod values (81,1010403);
        
        insert into db_syscampo values(1010280,'eso32_sequencial','int4','Sequencial (chave única) para ligar o preenchimento do formulário com os dados chave do fechamento de período do EFD.','0', 'Sequencial',10,'f','f','f',1,'text','Sequencial');
        insert into db_syscampo values(1010281,'eso32_avaliacaogruporesposta','int4','Sequencial (chave única) da tabela que guarda o grupo de respostas do formulário.','0', 'Código do Grupo de Resposta',10,'f','f','f',1,'text','Código do Grupo de Resposta');
        insert into db_syscampo values(1010282,'eso32_cgmcontribuinte','int4','Sequencial (chave única) do cgm do contribuinte do EFD.','0', 'Cgm do Contribuinte',10,'f','f','f',1,'text','Cgm do Contribuinte');
        insert into db_syscampo values(1010283,'eso32_ano','int4','Ano do período de apuração das informações enviadas ao EFD.','0', 'Ano',4,'f','f','f',1,'text','Ano');
        insert into db_syscampo values(1010284,'eso32_mes','int4','Mês do período de apuração das informações enviadas ao EFD.','0', 'Mês',2,'f','f','f',1,'text','Mês');
        
        insert into db_sysarqcamp values(1010403,1010280,1,0);
        insert into db_sysarqcamp values(1010403,1010281,2,0);
        insert into db_sysarqcamp values(1010403,1010282,3,0);
        insert into db_sysarqcamp values(1010403,1010283,4,0);
        insert into db_sysarqcamp values(1010403,1010284,5,0);
                
        insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010403,1010280,1,1010280);        
        insert into db_sysforkey values(1010403,1010281,1,2987,0);
        insert into db_sysforkey values(1010403,1010282,1,42,0);

        insert into db_sysindices values(1008422,'avaliacaogruporespostafechamentoefd_eso32_avaliacaogruporesposta_in',1010403,'0');
        insert into db_syscadind values(1008422,1010281,1);
        insert into db_sysindices values(1008423,'avaliacaogruporespostafechamentoefd_eso32_cgmcontribuinte_in',1010403,'0');
        insert into db_syscadind values(1008423,1010282,1);
        
        insert into db_syssequencia values(1000811, 'avaliacaogruporespostafechamentoefd_eso32_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
        update db_sysarqcamp set codsequencia = 1000811 where codarq = 1010403 and codcam = 1010280;
SQL;
        $this->execute($sql);
    }

    public function removerDicionarioTabela()
    {
        $sql =
<<<SQL
        delete from db_syssequencia where codsequencia = 1000811;
        delete from db_syscadind where codind in (1008422, 1008423);
        delete from db_sysindices where codind in (1008422, 1008423);
        delete from db_sysforkey where codarq = 1010403;
        delete from db_sysprikey where codarq = 1010403;
        delete from db_sysarqcamp where codarq = 1010403;
        delete from db_syscampo where codcam in (1010280, 1010281, 1010282, 1010283, 1010284);
        delete from db_sysarqmod where codarq = 1010403;
        delete from db_sysarquivo where codarq = 1010403;
SQL;
        $this->execute($sql);
    }

    public function incluirTabela()
    {
        $sql =
<<<SQL
        CREATE SEQUENCE esocial.avaliacaogruporespostafechamentoefd_eso32_sequencial_seq
        INCREMENT 1
        MINVALUE 1
        MAXVALUE 9223372036854775807
        START 1
        CACHE 1;
        
        CREATE TABLE esocial.avaliacaogruporespostafechamentoefd(
        eso32_sequencial int4 default 0,
        eso32_avaliacaogruporesposta int4 not null,
        eso32_cgmcontribuinte int4 not null,
        eso32_ano int4 not null,
        eso32_mes int4 not null,
        CONSTRAINT avaliacaogruporespostafechamentoefd_sequ_pk PRIMARY KEY (eso32_sequencial));
        
        ALTER TABLE esocial.avaliacaogruporespostafechamentoefd
        ADD CONSTRAINT avaliacaogruporespostafechamentoefd_avaliacaogruporesposta_fk FOREIGN KEY (eso32_avaliacaogruporesposta)
        REFERENCES avaliacaogruporesposta;
        
        ALTER TABLE esocial.avaliacaogruporespostafechamentoefd
        ADD CONSTRAINT avaliacaogruporespostafechamentoefd_cgmcontribuinte_fk FOREIGN KEY (eso32_cgmcontribuinte)
        REFERENCES cgm;
        
        CREATE INDEX avaliacaogruporespostafechamentoefd_eso32_avaliacaogruporesposta_in ON avaliacaogruporespostafechamentoefd(eso32_avaliacaogruporesposta);
        CREATE INDEX avaliacaogruporespostafechamentoefd_eso32_cgmcontribuinte_in ON avaliacaogruporespostafechamentoefd(eso32_cgmcontribuinte);
SQL;
       $this->execute($sql);
    }

    public function removerTabela()
    {
        $sql =
<<<SQL
         DROP INDEX IF EXISTS avaliacaogruporespostafechamentoefd_eso32_avaliacaogruporesposta_in;
         DROP INDEX IF EXISTS avaliacaogruporespostafechamentoefd_eso32_cgmcontribuinte_in;
         DROP SEQUENCE IF EXISTS esocial.avaliacaogruporespostafechamentoefd_eso32_sequencial_seq;
         DROP TABLE IF EXISTS esocial.avaliacaogruporespostafechamentoefd;
SQL;
        $this->execute($sql);
    }
}
