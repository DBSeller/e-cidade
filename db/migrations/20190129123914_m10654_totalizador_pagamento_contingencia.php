<?php

use Classes\PostgresMigration;

class M10654TotalizadorPagamentoContingencia extends PostgresMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function up()
    {
        $this->menuUp();
        $this->upFormulario();
        $this->dicionarioUp();
        $this->upDDL();
    }

    public function down()
    {
        $this->menuDown();
        $this->dicionarioDown();
        $this->downDDL();
        $this->downFormulario();
    }

    private function menuUp()
    {
        $sSql  = "insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228097 ,'Solicitação de Totalização para Pagamento em Contingência' ,'Solicitação de Totalização para Pagamento em Contingência' ,'eso01_preenchimentotalizacaopagamentocontingencia.php' ,'1' ,'1' ,'Arquivo enviado quando falha o 1299 para totalização de pagamentos' ,'true' );";
        $sSql .= "delete from db_menu where id_item_filho = 228097 AND modulo = 10216;";
        $sSql .= "insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 10466 ,228097 ,18 ,10216 );";

        $this->execute($sSql);
    }

    private function menuDown()
    {
        $sSql  = "delete from db_menu where id_item_filho = 228097;";
        $sSql .= "delete from db_itensmenu where id_item = 228097;";       

        $this->execute($sSql);
    }

    private function upFormulario()
    {
        $this->execute(<<<SQL
            BEGIN;
            insert into avaliacao( db101_sequencial ,db101_avaliacaotipo ,db101_descricao ,db101_identificador ,db101_obs ,db101_ativo ,db101_cargadados ,db101_permiteedicao ) values ( 4000103 ,5 ,'S-1295-Totalização para Pagamento em Contingência' ,'s1295totalizacao-para-pagamento-em-contingencia' ,'Registros do evento S-1295 - Solicitação de Totalização para Pagamento em Contingência' ,'true' ,'' ,'true' );            
            insert into avaliacaogrupopergunta( db102_sequencial ,db102_avaliacao ,db102_descricao ,db102_identificador ,db102_identificadorcampo ,db102_ordem ) values ( 4000221 ,4000103 ,'Responsável pelas Informações' ,'responsavel-pelas-informacoes5c50913ea2512' ,'ideRespInf' ,3 );
            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 4000225 ,2 ,4000221 ,'Nome do responsável pelas informações' ,'nome-do-responsavel' ,'true' ,'true' ,1 ,1 ,'' ,0 ,'false' ,'' ,'nmResp' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001190 ,4000225 ,'' ,'5c50913ea7710' ,'true' ,0 ,'' ,'nmResp' );
            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 4000226 ,2 ,4000221 ,'CPF do Responsável pelas informações' ,'cpf-do-responsavel5c50913eaa0b0' ,'true' ,'true' ,2 ,4 ,'' ,0 ,'false' ,'' ,'cpfResp' );
            delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 4000226;
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001191 ,4000226 ,'' ,'5c50913ead336' ,'true' ,0 ,'' ,'cpfResp' );
            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 4000227 ,2 ,4000221 ,'Número do Telefone do responsável, com DDD' ,'numero-do-telefone-com-ddd5c50913ec0c29' ,'true' ,'true' ,3 ,7 ,'' ,0 ,'false' ,'' ,'telefone' );
            delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 4000227;
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001192 ,4000227 ,'' ,'5c50913ecf701' ,'true' ,0 ,'' ,'telefone' );
            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 4000228 ,2 ,4000221 ,'Endereço Eletrônico do Responsável' ,'endereco-eletronico-do-responsavel' ,'true' ,'true' ,4 ,1 ,'' ,0 ,'false' ,'' ,'email' );
            delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 4000228;
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001193 ,4000228 ,'' ,'5c50913ed525e' ,'true' ,0 ,'' ,'email' );
            COMMIT;
SQL
);
    }   

    private function downFormulario()
    {
        $this->execute(<<<SQL
            BEGIN;
            create temp table x_avaliacaopergunta as
                select db103_sequencial
                  from avaliacaopergunta
                 where db103_avaliacaogrupopergunta in (select db102_sequencial from avaliacaogrupopergunta where db102_avaliacao = 4000103);            
            create temp table x_avaliacaoperguntaopcao as
                select db104_sequencial
                  from avaliacaoperguntaopcao
                 where db104_avaliacaopergunta in (select db103_sequencial from x_avaliacaopergunta);            
            delete from avaliacaoperguntaopcao where db104_avaliacaopergunta in (select db103_sequencial from x_avaliacaopergunta);
            delete from avaliacaopergunta where db103_sequencial in (select db103_sequencial from x_avaliacaopergunta);
            delete from avaliacaogrupopergunta where db102_avaliacao = 4000103;
            delete from avaliacao where db101_sequencial = 4000103;            
            drop table x_avaliacaopergunta;
            drop table x_avaliacaoperguntaopcao;
            COMMIT;
SQL
    );
    }

    private function dicionarioUp()
    {
        $this->execute(<<<SQL
            insert into db_sysarquivo values (1010418, 'avaliacaogruporespostatotpgcontingencia', 'Tabela de ligação para o formulário de solicitação de totalizador de pagamentos de contingencia', 'eso34', '2019-01-29', 'Totalização Pagamentos Contingência', 0, 'f', 'f', 'f', 'f' );
            insert into db_sysarqmod values (81,1010418);
            insert into db_syscampo values(1010321,'eso34_sequencial','int4','Campo sequencial','0', '',1,'f','f','f',1,'text','');
            insert into db_syscampo values(1010322,'eso34_avaliacaogruporesposta','int4','CHAVE ESTRANGEIRA PARA AVALIACAO','0', '',1,'f','f','f',1,'text','');
            insert into db_syscampo values(1010323,'eso34_empregador','int4','Chave estrangeira para CGM','0', '',1,'f','f','f',1,'text','');
            insert into db_syscampo values(1010324,'eso34_periodo','varchar(7)','Ano relativo ao envio','0', '',1,'f','f','f',1,'text','');
            insert into db_syscampo values(1010325,'eso34_indicativoapuracao','int4','Indicativo de Apuração','0', '',1,'f','f','f',1,'text','');                    
            insert into db_sysarqcamp values(1010418,1010321,1,0);
            insert into db_sysarqcamp values(1010418,1010322,2,0);
            insert into db_sysarqcamp values(1010418,1010323,3,0);
            insert into db_sysarqcamp values(1010418,1010324,4,0);
            insert into db_sysarqcamp values(1010418,1010325,5,0);
            insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010418,1010321,1,1010321);        
            insert into db_sysforkey values(1010418,1010322,1,2987,0);        
            insert into db_sysforkey values(1010418,1010323,1,42,0);
SQL
        );
    }

    private function dicionarioDown()
    {
        $this->execute(<<<SQL
            delete from db_sysforkey where codarq = 1010418;
            delete from db_sysprikey where codarq = 1010418;
            delete from db_sysarqcamp where codarq = 1010418;
            delete from db_syscampo where codcam in (1010321, 1010322, 1010323, 1010324, 1010325);
            delete from db_sysarqmod where codarq = 1010418;
            delete from db_sysarquivo where codarq = 1010418;
SQL
        );
    }

    private function upDDL()
    {
        $this->execute(<<<SQL
            insert into esocialformulariotipo values(35, 'S-1295 - Solicitação de Totalização para Pagamento em Contingência');
            insert into esocialversaoformulario values(nextval('esocialversaoformulario_rh211_sequencial_seq'), '2.5', 4000103, 35);

            CREATE SEQUENCE avaliacaogruporespostatotpgcontingencia_eso34_sequencial_seq
            INCREMENT 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1
            CACHE 1;

            CREATE TABLE esocial.avaliacaogruporespostatotpgcontingencia(
                eso34_sequencial int primary key DEFAULT nextval('avaliacaogruporespostatotpgcontingencia_eso34_sequencial_seq'),
                eso34_avaliacaogruporesposta int not null,
                eso34_empregador int not null,
                eso34_indicativoapuracao int not null,
                eso34_periodo varchar(7) not null
            );

            ALTER TABLE avaliacaogruporespostatotpgcontingencia
            ADD CONSTRAINT avaliacaogruporespostatotpgcontingencia_avaliacaogruporesposta_fk FOREIGN KEY (eso34_avaliacaogruporesposta)
            REFERENCES avaliacaogruporesposta;
            ALTER TABLE avaliacaogruporespostatotpgcontingencia
            ADD CONSTRAINT avaliacaogruporespostatotpgcontingencia_cgm_fk FOREIGN KEY (eso34_empregador)
            REFERENCES cgm;

            CREATE  INDEX avaliacaogruporespostatotpgcontingencia_avaliacaogruporesposta_in ON avaliacaogruporespostatotpgcontingencia(eso34_avaliacaogruporesposta);
            CREATE  INDEX avaliacaogruporespostatotpgcontingencia_cgm_in ON avaliacaogruporespostatotpgcontingencia(eso34_empregador);

SQL
        );
    }

    private function downDDL()
    {
        $this->execute(<<<SQL
            DELETE FROM esocialversaoformulario where rh211_avaliacao = 4000103 and rh211_esocialformulariotipo = 35;
            DELETE FROM esocialformulariotipo where rh209_descricao = 'S-1295 - Solicitação de Totalização para Pagamento em Contingência';
           
            DROP TABLE IF EXISTS avaliacaogruporespostatotpgcontingencia CASCADE;
            DROP SEQUENCE IF EXISTS avaliacaogruporespostatotpgcontingencia_eso34_sequencial_seq;
SQL
        );
    }
}
