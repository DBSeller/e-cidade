<?php

use Classes\PostgresMigration;

class M10172EsocialAvisoPrevio extends PostgresMigration
{
    public function up() {
        $sql = <<<SQL
            -- formulario            
            insert into avaliacao( db101_sequencial ,db101_avaliacaotipo ,db101_descricao ,db101_identificador ,db101_obs ,db101_ativo ,db101_cargadados ,db101_permiteedicao ) values ( 3000022 ,5 ,'Aviso Prévio - S-2250 - 2.4.02' ,'aviso-previo-s2250-2402' ,'Formulário' ,'true' ,'' ,'true' );
            insert into avaliacaogrupopergunta( db102_sequencial ,db102_avaliacao ,db102_descricao ,db102_identificador ,db102_identificadorcampo ,db102_ordem ) values ( 3000248 ,3000022 ,'Informações de Identificação do Trabalhador e do Vínculo' ,'informacoes-de-identificacao-do-trabalhador-e-do-v' ,'ideVinculo' ,1 );
            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3001062 ,2 ,3000248 ,'Número do CPF do trabalhador:' ,'numero-do-cpf-do-trabalhador' ,'true' ,'true' ,1 ,4 ,'' ,0 ,'false' ,'' ,'cpfTrab' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3004075 ,3001062 ,'' ,'5aa2c59f8aff5' ,'true' ,0 ,'' ,'cpfTrab' );
            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3001063 ,2 ,3000248 ,'Número de Identificação Social - NIS:' ,'numero-de-identificacao-social-nis' ,'true' ,'true' ,2 ,1 ,'' ,0 ,'false' ,'' ,'nisTrab' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3004076 ,3001063 ,'' ,'5aa2c59f9be25' ,'true' ,0 ,'' ,'nisTrab' );
            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3001064 ,2 ,3000248 ,'Matrícula:' ,'matricula5aa2c59f9f498' ,'true' ,'true' ,3 ,6 ,'' ,0 ,'true' ,'' ,'matricula' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3004077 ,3001064 ,'' ,'5aa2c59fa0334' ,'true' ,0 ,'' ,'matricula' );
            insert into avaliacaogrupopergunta( db102_sequencial ,db102_avaliacao ,db102_descricao ,db102_identificador ,db102_identificadorcampo ,db102_ordem ) values ( 3000249 ,3000022 ,'Detalha as informações do evento trabalhista' ,'detalha-as-informacoes-do-evento-trabalhista' ,'detAvPrevio' ,2 );
            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3001065 ,2 ,3000249 ,'Data em que o trabalhador ou o empregador recebeu o aviso de desligamento:' ,'data-em-que-o-trabalhador-ou-o-empregador-recebeu-' ,'true' ,'true' ,1 ,5 ,'' ,0 ,'false' ,'' ,'dtAvPrv' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3004078 ,3001065 ,'' ,'5aa2c59fa6837' ,'true' ,0 ,'' ,'dtAvPrv' );
            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3001066 ,2 ,3000249 ,'Data prevista para o desligamento do trabalhador' ,'data-prevista-para-o-desligamento-do-trabalhador' ,'true' ,'true' ,2 ,5 ,'' ,0 ,'false' ,'' ,'dtPrevDeslig' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3004079 ,3001066 ,'' ,'5aa2c59faad3d' ,'true' ,0 ,'' ,'dtPrevDeslig' );
            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3001067 ,1 ,3000249 ,'Tipo de Aviso Prévio.' ,'tipo-de-aviso-previo' ,'true' ,'true' ,3 ,1 ,'' ,0 ,'false' ,'' ,'tpAvPrevio' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3004080 ,3001067 ,'Aviso prévio trabalhado dado pelo empregador ao empregado, que optou pela redução de duas horas diárias [caput do art. 488 da CLT]' ,'aviso-previo-trabalhado-dado-pelo-empregador-ao-em' ,'false' ,0 ,'1' ,'tpAvPrevio_1' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3004081 ,3001067 ,'Aviso prévio trabalhado dado pelo empregador ao empregado, que optou pela redução de dias corridos [parágrafo único do art. 488 da CLT];' ,'aviso-previo-trabalhado-dado-pelo-emp5aa2c59facb32' ,'false' ,0 ,'2' ,'tpAvPrevio_2' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3004082 ,3001067 ,'Aviso prévio dado pelo empregado (pedido de demissão), não dispensado de seu cumprimento, sob pena de desconto, pelo empregador, dos salários correspondentes ao prazo respectivo (§2o do art. 487 da CLT);' ,'aviso-previo-dado-pelo-empregado-pedido-de-demissa' ,'false' ,0 ,'4' ,'tpAvPrevio_4' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3004083 ,3001067 ,'Aviso prévio trabalhado dado pelo empregador rural ao empregado, com redução de um dia por semana ( art. 15 da Lei no 5889/73);' ,'aviso-previo-trabalhado-dado-pelo-empregador-rural' ,'false' ,0 ,'5' ,'tpAvPrevio_5' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3004084 ,3001067 ,'Aviso prévio trabalhado decorrente de acordo entre empregado e empregador (art. 484-A, "caput", da CLT).' ,'aviso-previo-trabalhado-decorrente-de-acordo-entre' ,'false' ,0 ,'6' ,'tpAvPrevio_6' );
            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3001068 ,2 ,3000249 ,'Observações' ,'observacoes' ,'false' ,'true' ,4 ,1 ,'' ,0 ,'false' ,'' ,'detAvPrevio_observacao' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3004085 ,3001068 ,'' ,'5aa2c59faf719' ,'false' ,0 ,'' ,'detAvPrevio_observacao' );
            insert into avaliacaogrupopergunta( db102_sequencial ,db102_avaliacao ,db102_descricao ,db102_identificador ,db102_identificadorcampo ,db102_ordem ) values ( 3000250 ,3000022 ,'Cancelamento do Aviso Prévio' ,'cancelamento-do-aviso-previo' ,'cancAvPrevio' ,3 );
            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3001069 ,2 ,3000250 ,'Data de cancelamento do aviso prévio' ,'data-de-cancelamento-do-aviso-previo' ,'true' ,'true' ,1 ,5 ,'' ,0 ,'false' ,'' ,'dtCancAvPrv' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3004086 ,3001069 ,'' ,'5aa2c59fb12a2' ,'true' ,0 ,'' ,'dtCancAvPrv' );
            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3001070 ,2 ,3000250 ,'Observações' ,'observacoes5aa2c59fb1ade' ,'false' ,'true' ,2 ,1 ,'' ,0 ,'false' ,'' ,'cancAvPrevio_observacao' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3004087 ,3001070 ,'' ,'5aa2c59fb8723' ,'true' ,0 ,'' ,'cancAvPrevio_observacao' );
            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3001071 ,1 ,3000250 ,'Motivo do Cancelamento do Aviso Prévio' ,'motivo-do-cancelamento-do-aviso-previo' ,'true' ,'true' ,3 ,1 ,'' ,0 ,'false' ,'' ,'mtvCancAvPrevio' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3004088 ,3001071 ,'Reconsideração prevista no artigo 489 da CLT' ,'reconsideracao-prevista-no-artigo-489-da-clt' ,'false' ,0 ,'1' ,'mtvCancAvPrevio_1' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3004089 ,3001071 ,'Determinação Judicial' ,'determinacao-judicial' ,'false' ,0 ,'2' ,'mtvCancAvPrevio_2' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3004090 ,3001071 ,'Cumprimento de norma legal' ,'cumprimento-de-norma-legal' ,'false' ,0 ,'3' ,'mtvCancAvPrevio_3' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3004091 ,3001071 ,'Outros' ,'outros' ,'false' ,0 ,'9' ,'mtvCancAvPrevio_9' );

            -- menu
            update db_itensmenu set id_item = 10220 , descricao = 'Servidores/ Funcionários' , help = 'Servidores/ Funcionários' , funcao = 'eso4_conferenciadados001.php' , itemativo = '1' , manutencao = '1' , desctec = 'Manutenção S2100 - Tabela de Rubricas' , libcliente = 'true' where id_item = 10220;
            insert into db_itensmenu ( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 10514 ,'Aviso Prévio' ,'Aviso Prévio' ,'eso01_preenchimentoavisoprevio.php' ,'1' ,'1' ,'Formulário de Aviso Prévio, utilizado para o envio do arquivo (S-2250) do eSocial.' ,'true' );
            insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 10220 ,10514 ,11 ,10216 );
            -- tipo formulario
            insert into esocialformulariotipo values (11, 'Aviso Prévio');
            -- versao formulario
            insert into esocialversaoformulario values (16, '2.4', 3000022, 11);
SQL;
        $this->execute($sql);

        $this->tabelaNovaDDUp();
        $this->tabelaNovaEstruturaUp();
        $this->adicionaFormula();
    }

    public function down() {
        $id  = 3000022;
        $sql = <<<SQL
            -- formulario
            delete from esocialversaoformulario where rh211_avaliacao = $id;
            delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta in (select db103_sequencial from avaliacaopergunta where db103_avaliacaogrupopergunta in (select db102_sequencial from avaliacaogrupopergunta where db102_avaliacao in ($id)));
            delete from avaliacaoperguntaopcao where db104_avaliacaopergunta in (select db103_sequencial from avaliacaopergunta where db103_avaliacaogrupopergunta in (select db102_sequencial from avaliacaogrupopergunta where db102_avaliacao in ($id)));
            delete from avaliacaopergunta where db103_avaliacaogrupopergunta in (select db102_sequencial from avaliacaogrupopergunta where db102_avaliacao in ($id));
            delete from avaliacaogrupopergunta where db102_avaliacao in ($id);
            delete from esocialversaoformulario where rh211_avaliacao in ($id);
            delete from avaliacao where db101_sequencial in ($id);

            delete from esocialversaoformulario where  rh211_versao =  '2.4' and rh211_avaliacao = $id;

            -- menu
            delete from db_itensmenu where id_item = 10514;
            delete from db_menu where id_item_filho = 10514 AND modulo = 10216;
            -- tipo formulario
            delete from esocialformulariotipo where rh209_sequencial = 11;
            delete from esocialversaoformulario where rh211_sequencial = 16;
            update db_itensmenu set id_item = 10220 , descricao = 'Dados do Servidor' , help = 'Dados do Servidor' , funcao = 'eso4_conferenciadados001.php' , itemativo = '1' , manutencao = '1' , desctec = 'Manutenção S2100 - Tabela de Rubricas' , libcliente = 'true' where id_item = 10220;    
SQL;
        $this->execute($sql);

        $this->tabelaNovaDDDown();
        $this->tabelaNovaEstruturaDown();
    }

    private function tabelaNovaDDUp()
    {
        $sql = <<<DICIONARIO
            insert into db_sysarquivo values (1010267, 'avaliacaogruporespostaavisoprevio', 'Tabela de controle das respostas de aviso prévio do esocial.', 'eso07', '2018-03-12', 'avaliacaogruporespostaavisoprevio', 0, 'f', 'f', 'f', 'f' );
            insert into db_sysarqmod values (81,1010267);
            insert into db_syscampo values(1009661,'eso07_sequencial','int4','Código sequencial','0', 'Código',10,'f','f','f',1,'text','Código');
            insert into db_syscampo values(1009662,'eso07_avaliacaogruporesposta','int4','Código do Preenchimento','0', 'Preenchimento',10,'f','f','f',1,'text','Preenchimento');
            insert into db_syscampo values(1009663,'eso07_empregador','int4','Código do Empregador','0', 'Empregador',10,'f','f','f',1,'text','Empregador');
            insert into db_syscampo values(1009664,'eso07_regist','int4','Código da Matrícula','0', 'Matrícula',10,'f','f','f',1,'text','Matrícula');
            insert into db_sysarqcamp values(1010267,1009661,1,0);
            insert into db_sysarqcamp values(1010267,1009662,2,0);
            insert into db_sysarqcamp values(1010267,1009663,3,0);
            insert into db_sysarqcamp values(1010267,1009664,4,0);
            insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010267,1009661,1,1009661);
            insert into db_sysindices values(1008262,'avaliacaogruporespostaavisoprevio_sequencial_in',1010267,'1');
            insert into db_syscadind values(1008262,1009661,1);
            insert into db_syssequencia values(1000722, 'avaliacaogruporespostaavisoprevio_eso07_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
            update db_sysarqcamp set codsequencia = 1000722 where codarq = 1010267 and codcam = 1009661;
            insert into db_sysforkey values(1010267,1009662,1,2981,0);
            insert into db_sysforkey values(1010267,1009663,1,42,0);
            insert into db_sysforkey values(1010267,1009664,1,1153,0);
DICIONARIO;

        $this->execute($sql);
    }

    private function tabelaNovaDDDown()
    {
        $sql = <<<DICIONARIO
            delete from db_sysforkey where codcam in (1009662,1009663,1009664) ;
            delete from db_syssequencia where codsequencia = 1000722;
            delete from db_syscadind where codind = 1008262;
            delete from db_sysindices where codind = 1008262;
            delete from db_sysprikey where codarq = 1010267;
            delete from db_sysarqcamp where codarq = 1010267;
            delete from db_syscampo where codcam in (1009661, 1009662, 1009663, 1009664);
            delete from db_sysarqmod where codarq = 1010267;
            delete from db_sysarquivo where codarq = 1010267;
DICIONARIO;

        $this->execute($sql);
    }

    private function tabelaNovaEstruturaUp()
    {
        $sql = <<<TABELINHA
            CREATE SEQUENCE esocial.avaliacaogruporespostaavisoprevio_eso07_sequencial_seq
            INCREMENT 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1
            CACHE 1;
            
            -- Módulo: esocial
            CREATE TABLE esocial.avaliacaogruporespostaavisoprevio(
            eso07_sequencial             int4 NOT NULL default 0,
            eso07_avaliacaogruporesposta int4 NOT NULL default 0,
            eso07_empregador             int4 NOT NULL default 0,
            eso07_regist                 int4 default 0,
            CONSTRAINT avaliacaogruporespostaavisoprevio_sequ_pk PRIMARY KEY (eso07_sequencial));
            
            ALTER TABLE avaliacaogruporespostaavisoprevio
            ADD CONSTRAINT avaliacaogruporespostaavisoprevio_empregador_fk FOREIGN KEY (eso07_empregador)
            REFERENCES cgm;
            
            ALTER TABLE avaliacaogruporespostaavisoprevio
            ADD CONSTRAINT avaliacaogruporespostaavisoprevio_avaliacaogruporesposta_fk FOREIGN KEY (eso07_avaliacaogruporesposta)
            REFERENCES avaliacaogruporesposta;
            
            ALTER TABLE avaliacaogruporespostaavisoprevio
            ADD CONSTRAINT avaliacaogruporespostaavisoprevio_regist_fk FOREIGN KEY (eso07_regist)
            REFERENCES rhpessoal;
            
            CREATE UNIQUE INDEX avaliacaogruporespostaavisoprevio_sequencial_in ON avaliacaogruporespostaavisoprevio(eso07_sequencial);
TABELINHA;

        $this->execute($sql);
    }

    private function tabelaNovaEstruturaDown()
    {
        $sql = <<<REMOVETABELINHA
            DROP SEQUENCE IF EXISTS avaliacaogruporespostaavisoprevio_eso07_sequencial_seq;
            DROP TABLE IF EXISTS avaliacaogruporespostaavisoprevio CASCADE;
REMOVETABELINHA;

        $this->execute($sql);
    }

    public function adicionaFormula() {

        $sql = <<<SQL
            insert into avaliacaoperguntadb_formulas
            values (nextval('avaliacaoperguntadb_formulas_eso01_sequencial_seq'), (select db148_sequencial from db_formulas where  db148_nome ='ESOCIAL_CPF_SERVIDOR'), 3001062), 
               (nextval('avaliacaoperguntadb_formulas_eso01_sequencial_seq'), (select db148_sequencial from db_formulas where  db148_nome ='ESOCIAL_PIS_PASEP_SERVIDOR'), 3001063);
SQL;
        $this->execute($sql);            
    }
}
