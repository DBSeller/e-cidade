<?php

use Classes\PostgresMigration;

class M12504EnvioServicosTomadosR2010 extends PostgresMigration
{
    public function up()
    {
        $this->alterarFormulario();
        $this->adicionaMenu();
        $this->dicionarioDadosUp();
        $this->criaTabelaDataEnvio();
    }

    public function down()
    {
        $this->retornarFormulario();
        $this->removeMenu();
        $this->dicionarioDadosDown();
        $this->dropaTabelaDataEnvio();
    }

    public function alterarFormulario()
    {
        $sql =
<<<SQL
        update avaliacaogrupopergunta set db102_descricao = 'Processos relacionados a não retenção de contribuição previdenciária 1' where db102_sequencial = 4000206;
        update avaliacaopergunta set db103_descricao = 'Categoria do processo' where db103_sequencial = 4000158;
        insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 4000194 ,2 ,4000206 ,'Código do indicativo da suspensão' ,'codigo-do-indicativo-da-suspensao5c2de89cc1f45' ,'false' ,'true' ,4 ,1 ,'' ,0 ,'false' ,'' ,'codSusp_1' );
        insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001149 ,4000194 ,'' ,'5c2de89cc40ba' ,'true' ,0 ,'' ,'codSusp_1' );
        update avaliacaopergunta set db103_descricao = 'Valor da retenção que deixou de ser efetuada em função do processo', db103_obrigatoria = 'true', db103_ordem = 5 where db103_sequencial = 4000161;
        
        update avaliacaopergunta set db103_descricao = 'Categoria do processo' where db103_sequencial = 4000162;
        insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 4000195 ,2 ,4000207 ,'Código do indicativo da suspensão' ,'codigo-do-indicativo-da-suspensao5c2de89cd30ec' ,'false' ,'true' ,4 ,1 ,'' ,0 ,'false' ,'' ,'codSusp_2' );
        insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001150 ,4000195 ,'' ,'5c2de89cd4762' ,'true' ,0 ,'' ,'codSusp_2' );
        update avaliacaopergunta set db103_descricao = 'Valor da retenção que deixou de ser efetuada em função do processo', db103_ordem = 5 where db103_sequencial = 4000165;
        
        update avaliacaopergunta set db103_descricao = 'Categoria do processo' where db103_sequencial = 4000166;
        insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 4000196 ,2 ,4000208 ,'Código do indicativo da suspensão' ,'codigo-do-indicativo-da-suspensao5c2de89cddb37' ,'false' ,'true' ,4 ,1 ,'' ,0 ,'false' ,'' ,'codSusp_3' );
       insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001151 ,4000196 ,'' ,'5c2de89cded63' ,'true' ,0 ,'' ,'codSusp_3' );
        update avaliacaopergunta set db103_descricao = 'Valor da retenção que deixou de ser efetuada em função do processo', db103_ordem = 5 where db103_sequencial = 4000169;
        
        update avaliacaopergunta set db103_descricao = 'Categoria do processo' where db103_sequencial = 4000170;
        insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 4000197 ,2 ,4000209 ,'Código do indicativo da suspensão' ,'codigo-do-indicativo-da-suspensao5c2de89cef41e' ,'false' ,'true' ,4 ,1 ,'' ,0 ,'false' ,'' ,'codSusp_4' );
        insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001152 ,4000197 ,'' ,'5c2de89cf075f' ,'true' ,0 ,'' ,'codSusp_4' );
        update avaliacaopergunta set db103_descricao = 'Valor da retenção que deixou de ser efetuada em função do processo', db103_ordem = 5 where db103_sequencial = 4000173;
        
        update avaliacaopergunta set db103_descricao = 'Categoria do processo' where db103_sequencial = 4000174;
        insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 4000198 ,2 ,4000210 ,'Código do indicativo da suspensão' ,'codigo-do-indicativo-da-suspensao5c2de89d08ad4' ,'false' ,'true' ,4 ,1 ,'' ,0 ,'false' ,'' ,'codSusp_5' );
        insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001153 ,4000198 ,'' ,'5c2de89d09e0c' ,'true' ,0 ,'' ,'codSusp_5' );
        update avaliacaopergunta set db103_descricao = 'Valor da retenção que deixou de ser efetuada em função do processo', db103_ordem = 5 where db103_sequencial = 4000177;
        
        update avaliacaopergunta set db103_descricao = 'Categoria do processo' where db103_sequencial = 4000178;
        insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 4000199 ,2 ,4000211 ,'Código do indicativo da suspensão' ,'codigo-do-indicativo-da-suspensao5c2de89d1153c' ,'false' ,'true' ,4 ,1 ,'' ,0 ,'false' ,'' ,'codSusp_6' );
        insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001154 ,4000199 ,'' ,'5c2de89d12506' ,'true' ,0 ,'' ,'codSusp_6' );
        update avaliacaopergunta set db103_descricao = 'Valor da retenção que deixou de ser efetuada em função do processo', db103_ordem = 5 where db103_sequencial = 4000181;
        
        update avaliacaopergunta set db103_descricao = 'Categoria do processo' where db103_sequencial = 4000182;
        insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 4000200 ,2 ,4000212 ,'Código do indicativo da suspensão' ,'codigo-do-indicativo-da-suspensao5c2de89d1acae' ,'false' ,'true' ,4 ,1 ,'' ,0 ,'false' ,'' ,'codSusp_7' );
        insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001155 ,4000200 ,'' ,'5c2de89d1c4e8' ,'true' ,0 ,'' ,'codSusp_7' );
        update avaliacaopergunta set db103_descricao = 'Valor da retenção que deixou de ser efetuada em função do processo', db103_ordem = 5 where db103_sequencial = 4000185;
        
        update avaliacaopergunta set db103_descricao = 'Categoria do processo' where db103_sequencial = 4000186;
        insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 4000201 ,2 ,4000213 ,'Código do indicativo da suspensão' ,'codigo-do-indicativo-da-suspensao5c2de89d261a9' ,'false' ,'true' ,4 ,1 ,'' ,0 ,'false' ,'' ,'codSusp_8' );
        insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001156 ,4000201 ,'' ,'5c2de89d27349' ,'true' ,0 ,'' ,'codSusp_8' );
        update avaliacaopergunta set db103_descricao = 'Valor da retenção que deixou de ser efetuada em função do processo', db103_ordem = 5 where db103_sequencial = 4000189;
        
        update avaliacaopergunta set db103_descricao = 'Categoria do processo' where db103_sequencial = 4000190;
        insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 4000202 ,2 ,4000214 ,'Código do indicativo da suspensão' ,'codigo-do-indicativo-da-suspensao5c2de89d2ed22' ,'false' ,'true' ,4 ,1 ,'' ,0 ,'false' ,'' ,'codSusp_9' );
        insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001157 ,4000202 ,'' ,'5c2de89d2fca4' ,'true' ,0 ,'' ,'codSusp_9' );
        update avaliacaopergunta set db103_descricao = 'Valor da retenção que deixou de ser efetuada em função do processo', db103_ordem = 5 where db103_sequencial = 4000193;
SQL;
        $this->execute($sql);
    }

    public function retornarFormulario()
    {
        $sql =
<<<SQL
        update avaliacaogrupopergunta set db102_descricao = 'Processos relacionados a não retenção de contribuição previdenciária' where db102_sequencial = 4000206;
        update avaliacaopergunta set db103_descricao = 'Principal ou Adicional' where db103_sequencial = 4000158;
        delete from avaliacaoperguntaopcao where db104_sequencial = 4001149;
        delete from avaliacaopergunta where db103_sequencial = 4000194;
        update avaliacaopergunta set db103_descricao = 'Valor da retenção do processo', db103_obrigatoria = 'false', db103_ordem = 4 where db103_sequencial = 4000161;
        
        update avaliacaopergunta set db103_descricao = 'Principal ou Adicional' where db103_sequencial = 4000162;
        delete from avaliacaoperguntaopcao where db104_sequencial = 4001150;
        delete from avaliacaopergunta where db103_sequencial = 4000195;
        update avaliacaopergunta set db103_descricao = 'Valor da retenção do processo', db103_ordem = 4 where db103_sequencial = 4000165;
        
        update avaliacaopergunta set db103_descricao = 'Principal ou Adicional' where db103_sequencial = 4000166;
        delete from avaliacaoperguntaopcao where db104_sequencial = 4001151;
        delete from avaliacaopergunta where db103_sequencial = 4000196;        
        update avaliacaopergunta set db103_descricao = 'Valor da retenção do processo', db103_ordem = 4 where db103_sequencial = 4000169;
        
        update avaliacaopergunta set db103_descricao = 'Principal ou Adicional' where db103_sequencial = 4000170;
        delete from avaliacaoperguntaopcao where db104_sequencial = 4001152;
        delete from avaliacaopergunta where db103_sequencial = 4000197;        
        update avaliacaopergunta set db103_descricao = 'Valor da retenção do processo', db103_ordem = 4 where db103_sequencial = 4000173;
        
        update avaliacaopergunta set db103_descricao = 'Principal ou Adicional' where db103_sequencial = 4000174;
        delete from avaliacaoperguntaopcao where db104_sequencial = 4001153;
        delete from avaliacaopergunta where db103_sequencial = 4000198;
        update avaliacaopergunta set db103_descricao = 'Valor da retenção do processo', db103_ordem = 4 where db103_sequencial = 4000177;
        
        update avaliacaopergunta set db103_descricao = 'Principal ou Adicional' where db103_sequencial = 4000178;
        delete from avaliacaoperguntaopcao where db104_sequencial = 4001154;
        delete from avaliacaopergunta where db103_sequencial = 4000199;
        update avaliacaopergunta set db103_descricao = 'Valor da retenção do processo', db103_ordem = 4 where db103_sequencial = 4000181;
        
        update avaliacaopergunta set db103_descricao = 'Principal ou Adicional' where db103_sequencial = 4000182;
        delete from avaliacaoperguntaopcao where db104_sequencial = 4001155;
        delete from avaliacaopergunta where db103_sequencial = 4000200;
        update avaliacaopergunta set db103_descricao = 'Valor da retenção do processo', db103_ordem = 4 where db103_sequencial = 4000185;
        
        update avaliacaopergunta set db103_descricao = 'Principal ou Adicional' where db103_sequencial = 4000186;
        delete from avaliacaoperguntaopcao where db104_sequencial = 4001156;
        delete from avaliacaopergunta where db103_sequencial = 4000201;
        update avaliacaopergunta set db103_descricao = 'Valor da retenção do processo', db103_ordem = 4 where db103_sequencial = 4000189;
        
        update avaliacaopergunta set db103_descricao = 'Principal ou Adicional' where db103_sequencial = 4000190;
        delete from avaliacaoperguntaopcao where db104_sequencial = 4001157;
        delete from avaliacaopergunta where db103_sequencial = 4000202;
        update avaliacaopergunta set db103_descricao = 'Valor da retenção do processo', db103_ordem = 4 where db103_sequencial = 4000193;
SQL;
        $this->execute($sql);
    }

    public function adicionaMenu()
    {
        $sql = 
<<<SQL
        insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228090 ,'Data para Envio EFD-Reinf' ,'Data de envio dos eventos do EFD-Reinf' ,'efd4_configuracaoenvio001.php' ,'1' ,'1' ,'Data de envio dos eventos do EFD-Reinf' ,'true' );
        delete from db_menu where id_item_filho = 228090 AND modulo = 228077;
        insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 228083 ,228090 ,2 ,228077 );
SQL;
        $this->execute($sql);
    }

    public function removeMenu()
    {
        $sql = 
<<<SQL
        delete from db_menu where id_item_filho = 228090 AND modulo = 228077;
        DELETE FROM db_itensmenu where id_item = 228090;
SQL;
        $this->execute($sql);
    }

    public function dicionarioDadosUp()
    {
        $sql = 
<<<SQL
        insert into db_sysarquivo values (1010400, 'dataenvioefd', 'Data de envio dos eventos do EFD-Reinf', '', '2019-01-03', 'data-envio-efd', 0, 'f', 'f', 'f', 'f' );
        insert into db_sysarqmod values (81,1010400);        
        update db_sysarquivo set nomearq = 'dataenvioefd', descricao = 'Data de envio dos eventos do EFD-Reinf', sigla = 'efd06', dataincl = '2019-01-03', rotulo = 'data-envio-efd', tipotabela = 0, naolibclass = 'f', naolibfunc = 'f', naolibprog = 'f', naolibform = 'f' where codarq = 1010400;
        delete from db_sysarqarq where codarq = 1010400;
        insert into db_sysarqarq values(0,1010400);
        insert into db_syscampo values(1010266,'efd06_sequencial','int4','Sequencial da tabela','0', '',1,'f','f','f',1,'text','');
        insert into db_syscampo values(1010267,'efd06_dataenvio','date','Data do envio do arquivo','null', '',10,'f','f','f',1,'text','');
        insert into db_syscampo values(1010268,'efd06_arquivo','varchar(50)','Nome do arquivo','', '',50,'f','t','f',0,'text','');
        insert into db_syscampo values(1010269,'efd06_instituicao','int4','Instituição','0', '',1,'f','f','f',1,'text','');
        delete from db_sysarqcamp where codarq = 1010400;
        insert into db_sysarqcamp values(1010400,1010266,1,0);
        insert into db_sysarqcamp values(1010400,1010269,2,0);
        insert into db_sysarqcamp values(1010400,1010268,3,0);
        insert into db_sysarqcamp values(1010400,1010267,4,0);
        delete from db_sysprikey where codarq = 1010400;
        insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010400,1010266,1,1010266);
        delete from db_sysforkey where codarq = 1010400 and referen = 0;
        insert into db_sysforkey values(1010400,1010269,1,83,0);
SQL;
        $this->execute($sql);
    }

    public function dicionarioDadosDown()
    {
        $sql = 
<<<SQL
        delete from db_sysforkey where codarq = 1010400;
        delete from db_sysprikey where codarq = 1010400;
        DELETE FROM db_sysarqcamp where codarq = 1010400;
        DELETE FROM db_syscampo where codcam in (1010266, 1010267, 1010268, 1010269);
        DELETE FROM db_sysarqarq where codarq = 1010400;
        DELETE FROM db_sysarqmod where codarq = 1010400;
        DELETE FROM db_sysarquivo where codarq = 1010400;        
SQL;
        $this->execute($sql);
    }

    public function criaTabelaDataEnvio()
    {
        $sql =
<<<SQL
        CREATE SEQUENCE dataenvioefd_efd06_sequencial_seq
        INCREMENT 1
        MINVALUE 1
        MAXVALUE 9223372036854775807
        START 1
        CACHE 1;

        CREATE TABLE esocial.dataenvioefd(
            efd06_sequencial int primary key default nextval('dataenvioefd_efd06_sequencial_seq'),
            efd06_dataenvio date not null,
            efd06_arquivo varchar(50) not null,
            efd06_instituicao int not null
        );

        ALTER TABLE dataenvioefd
        ADD CONSTRAINT dataenvioefd_instituicao_fk FOREIGN KEY (efd06_sequencial)
        REFERENCES db_config;

SQL;
        $this->execute($sql);
    }

    public function dropaTabelaDataEnvio()
    {
        $sql =
<<<SQL
        DROP TABLE dataenvioefd;
        DROP SEQUENCE dataenvioefd_efd06_sequencial_seq;
SQL;
        $this->execute($sql);
    }
}
