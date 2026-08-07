<?php

use Classes\PostgresMigration;

class M16072TiposItbi extends PostgresMigration
{
    public function up()
    {
        $this->upEstrutura();
        $this->upDicionario();
        $this->upCriaModImprime();
    }

    public function down()
    {
        $this->downEstrutura();
        $this->downDicionario();
        $this->downCriaModImprime();
    }

    private function upEstrutura()
    {
        $this->execute(<<<SQL
            CREATE TABLE itbi.taxasitbi
            (
                it36_sequencial serial NOT NULL,
                it36_descricao text NOT NULL,
                it36_imovelurbano boolean,
                it36_imovelrural boolean,
                it36_imovelurbanopleno boolean,
                CONSTRAINT "taxasitbi_pk" PRIMARY KEY ("it36_sequencial")
            );

            CREATE TABLE itbi.taxasitbitaxa
            (
                it37_sequencial serial NOT NULL,
                it37_taxasitbi integer NOT NULL,
                it37_taxaslancadas integer NOT NULL,
                it37_calculasobre integer NOT NULL DEFAULT 1,
                CONSTRAINT "taxasitbitaxa_pk" PRIMARY KEY ("it37_sequencial"),
                CONSTRAINT "taxasitbi_fk" FOREIGN KEY ("it37_taxasitbi") REFERENCES itbi.taxasitbi ("it36_sequencial"),
                CONSTRAINT "taxaslancadas_fk" FOREIGN KEY ("it37_taxaslancadas") REFERENCES arrecadacao.taxaslancadas ("ar44_sequencial")
            );

            CREATE TABLE itbi.itbitaxasitbi
            (
                it38_sequencial serial NOT NULL,
                it38_itbi integer NOT NULL,
                it38_taxasitbi integer NOT NULL,
                CONSTRAINT "itbitaxasitbi_pk" PRIMARY KEY ("it38_sequencial"),
                CONSTRAINT "itbi_fk" FOREIGN KEY ("it38_itbi") REFERENCES itbi.itbi ("it01_guia"),
                CONSTRAINT "taxasitbi_fk" FOREIGN KEY ("it38_taxasitbi") REFERENCES itbi.taxasitbi ("it36_sequencial")
            );

            CREATE TABLE itbi.itbitaxasavalia
            (
                it39_sequencial serial NOT NULL,
                it39_guia integer NOT NULL,
                it39_taxaslancadas integer NOT NULL,
                it39_calculasobre integer NOT NULL,
                it39_aliquota numeric,
                it39_valor numeric NOT NULL,
                CONSTRAINT "itbitaxasavalia_pk" PRIMARY KEY ("it39_sequencial"),
                CONSTRAINT "taxaslancadas_fk" FOREIGN KEY ("it39_taxaslancadas") REFERENCES arrecadacao.taxaslancadas ("ar44_sequencial")
            );

            ALTER TABLE itbi.itbi ADD COLUMN it01_notificado BOOLEAN DEFAULT FALSE;
            ALTER TABLE itbi.paritbi ADD COLUMN it24_solicitanotificacao BOOLEAN NOT NULL DEFAULT FALSE;
            ALTER TABLE itbi.paritbi ADD COLUMN it24_carregavalorvenal BOOLEAN NOT NULL DEFAULT FALSE;
SQL
        );
    }

    private function downEstrutura()
    {
        $this->execute(<<<SQL
            DROP TABLE IF EXISTS itbi.itbitaxasavalia;
            DROP TABLE IF EXISTS itbi.itbitaxasitbi;
            DROP TABLE IF EXISTS itbi.taxasitbitaxa;
            DROP TABLE IF EXISTS itbi.taxasitbi;

            ALTER TABLE itbi.paritbi DROP COLUMN it24_solicitanotificacao;
            ALTER TABLE itbi.paritbi DROP COLUMN it24_carregavalorvenal;
            ALTER TABLE itbi.itbi DROP COLUMN it01_notificado;
SQL
        );
    }

    private function upDicionario()
    {
        $this->execute(<<<SQL
            INSERT INTO db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) VALUES ( 228271 ,'Taxas do ITBI' ,'Cadastro de taxas do ITBI' ,'' ,'1' ,'1' ,'Cadastro de taxas do ITBI' ,'true' );
            INSERT INTO db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) VALUES ( 29 ,228271 ,293 ,2544 );

            INSERT INTO db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) VALUES ( 228272 ,'Inclusão' ,'Inclusão de taxa de ITBI' ,'itbi_taxasitbi001.php?db_opcao=1' ,'1' ,'1' ,'Inclusão de taxa de ITBI' ,'true' );
            INSERT INTO db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) VALUES ( 228271 ,228272 ,1 ,2544 );

            INSERT INTO db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) VALUES ( 228273 ,'Alteração' ,'Alteração da taxas do ITBI' ,'itbi_taxasitbi001.php?db_opcao=2' ,'1' ,'1' ,'Alteração da taxas do ITBI' ,'true' );
            INSERT INTO db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) VALUES ( 228271 ,228273 ,2 ,2544 );


            /* taxasitbi */
            INSERT INTO db_sysarquivo VALUES (1010597, 'taxasitbi', 'Tabela para guardar as taxas do ITBI.', 'it36', '2020-07-03', 'taxasitbi', 0, 'f', 'f', 'f', 'f' );
            INSERT INTO db_sysarqmod VALUES (36,1010597);

            INSERT INTO db_syscampo VALUES(1011656,'it36_sequencial','int4','Sequencial da tabela taxasitbi.','0', 'Código',11,'f','f','f',1,'text','Código');
            INSERT INTO db_syscampo VALUES(1011657,'it36_descricao','varchar(255)','Descrição da taxa.','', 'Descriçao',255,'f','t','f',0,'text','Descriçao');
            INSERT INTO db_syscampo VALUES(1011669,'it36_imovelurbano','char(1)','Tipo de imóvel Urbano.','', 'Urbano',1,'f','f','f',0,'text','Urbano');
            INSERT INTO db_syscampo VALUES(1011670,'it36_imovelrural','char(1)','Tipo de imóvel Rural.','', 'Rural',1,'f','f','f',0,'text','Rural');
            INSERT INTO db_syscampo VALUES(1011687,'it36_imovelurbanopleno','char(1)','Tipo de imóvel Urbano Pleno.','', 'Urbano Pleno',1,'f','f','f',0,'text','Urbano Pleno');

            INSERT INTO db_syssequencia VALUES(1000952, 'taxasitbi_it36_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);

            INSERT INTO db_sysarqcamp VALUES(1010597,1011656,1,1000952);
            INSERT INTO db_sysarqcamp VALUES(1010597,1011657,2,0);
            INSERT INTO db_sysarqcamp VALUES(1010597,1011669,7,0);
            INSERT INTO db_sysarqcamp VALUES(1010597,1011670,8,0);
            INSERT INTO db_sysarqcamp VALUES(1010597,1011687,5,0);

            INSERT INTO db_sysprikey (codarq,codcam,sequen,camiden) VALUES(1010597,1011656,1,1011656);

            /* taxasitbitaxa */
            INSERT INTO db_sysarquivo VALUES (1010600, 'taxasitbitaxa', 'Salva o vincula do grupo de taxas com a taxa.', 'it37', '2020-07-06', 'taxasitbitaxa', 0, 'f', 'f', 'f', 'f' );
            INSERT INTO db_sysarqmod VALUES (36,1010600);

            INSERT INTO db_syscampo VALUES(1011681,'it37_sequencial','int4','Sequencial da tabela taxaitbitaxa.','0', 'Sequencial',11,'f','f','f',1,'text','Sequencial');
            INSERT INTO db_syscampo VALUES(1011673,'it37_taxasitbi','int4','Código do grupo de taxas de ITBI.','0', 'Código',11,'f','f','f',1,'text','Código');
            INSERT INTO db_syscampo VALUES(1011674,'it37_taxaslancadas','int4','Código da taxa cadastrada no Lançamento de taxas.','0', 'Código da Taxa',11,'f','f','f',1,'text','Código da Taxa');
            INSERT INTO db_syscampo VALUES(1011680,'it37_calculasobre','int4','Define sobre o que deve ser calculado o valor da taxa..','0', 'Calcula Sobre',1,'f','f','f',1,'text','Calcula Sobre');

            INSERT INTO db_syssequencia VALUES(1000954, 'taxasitbitaxa_it37_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);

            INSERT INTO db_sysarqcamp VALUES(1010600,1011681,1,1000954);
            INSERT INTO db_sysarqcamp VALUES(1010600,1011673,1,0);
            INSERT INTO db_sysarqcamp VALUES(1010600,1011674,2,0);
            INSERT INTO db_sysarqcamp VALUES(1010600,1011680,3,0);

            INSERT INTO db_sysprikey (codarq,codcam,sequen,camiden) VALUES(1010600,1011681,1,1011681);

            INSERT INTO db_sysforkey VALUES(1010600,1011673,1,1010597,0);
            INSERT INTO db_sysforkey VALUES(1010600,1011674,1,1010547,0);

            /* itbitaxasitbi */

            INSERT INTO db_sysarquivo VALUES (1010605, 'itbitaxasitbi', 'Tabela de vinculo entre a guia de ITBI e os tipos de taxas.', 'it38', '2020-07-10', 'itbitaxasitbi', 0, 'f', 'f', 'f', 'f' );
            INSERT INTO db_sysarqmod VALUES (36,1010605);

            INSERT INTO db_syscampo VALUES(1011713,'it38_sequencial','int4','Sequencial da tabela itbitaxasitbi.','0', 'Sequencial',11,'f','f','f',1,'text','Sequencial');
            INSERT INTO db_syscampo VALUES(1011714,'it38_itbi','int4','Código da Guia de ITBI','0', 'Guia de ITBI',11,'f','f','f',1,'text','Guia de ITBI');
            INSERT INTO db_syscampo VALUES(1011715,'it38_taxasitbi','int4','Código do tipo cadastrado nas Taxas de ITBI.','0', 'Código do Tipo',11,'f','f','f',1,'text','Código do Tipo');

            INSERT INTO db_syssequencia VALUES(1000958, 'itbitaxasitbi_it38_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);

            INSERT INTO db_sysarqcamp VALUES(1010605,1011713,1,1000958);
            INSERT INTO db_sysarqcamp VALUES(1010605,1011714,2,0);
            INSERT INTO db_sysarqcamp VALUES(1010605,1011715,3,0);

            INSERT INTO db_sysprikey (codarq,codcam,sequen,camiden) VALUES(1010605,1011713,1,1011713);

            INSERT INTO db_sysforkey VALUES(1010605,1011714,1,792,0);
            INSERT INTO db_sysforkey VALUES(1010605,1011715,1,1010597,0);

            /* itbitaxasavalia */

            insert into db_sysarquivo values (1010606, 'itbitaxasavalia', 'Tabela que guarda as taxas vinculadas a guia de ITBI avaliada.', 'it39', '2020-07-14', 'itbitaxasavalia', 0, 'f', 'f', 'f', 'f' );
            insert into db_sysarqmod values (36,1010606);

            insert into db_syscampo values(1011716,'it39_sequencial','int4','Sequencial da tabela itbitaxasavalia.','0', 'Sequencial',11,'f','f','f',1,'text','Sequencial');
            insert into db_syscampo values(1011717,'it39_guia','int4','Código da guia de ITBI.','0', 'Guia',11,'f','f','f',1,'text','Guia');
            insert into db_syscampo values(1011718,'it39_taxaslancadas','int4','Código da Taxa.','0', 'Código da Taxa',11,'f','f','f',1,'text','Código da Taxa');
            insert into db_syscampo values(1011719,'it39_valor','float4','Valor da Taxa.','0', 'Valor da Taxa',11,'f','f','f',4,'text','Valor da Taxa');
            insert into db_syscampo values(1011720,'it39_calculasobre','int4','Guarda o código que define sobre o que foi calculada a taxa de ITBI (Terreno, Construção, Ambos).','0', 'Calcula Sobre',1,'f','f','f',1,'text','Calcula Sobre');
            insert into db_syscampo values(1011721,'it39_aliquota','float4','Guarda a alíquota que foi utilizada para calcular o valor da taxa.','0', 'Aliquota',11,'f','f','f',4,'text','Aliquota');

            insert into db_syssequencia values(1000960, 'itbitaxasavalia_it39_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);

            insert into db_sysarqcamp values(1010606,1011716,1,1000960);
            insert into db_sysarqcamp values(1010606,1011717,2,0);
            insert into db_sysarqcamp values(1010606,1011718,3,0);
            insert into db_sysarqcamp values(1010606,1011719,4,0);
            insert into db_sysarqcamp values(1010606,1011720,5,0);
            insert into db_sysarqcamp values(1010606,1011721,6,0);

            insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010606,1011716,1,1011716);

            insert into db_sysforkey values(1010606,1011717,1,792,0);
            insert into db_sysforkey values(1010606,1011718,1,1010547,0);

            /* paritbi */

            insert into db_syscampo values(1011724,'it24_solicitanotificacao','char(1)','Define se deve ou não liberar o botão de notificação na liberação de ITBI','', 'Solicita Notificação',1,'f','t','f',0,'text','Solicita Notificação');

            insert into db_sysarqcamp values(2362,1011724,14,0);


            insert into db_syscampo values(1011739,'it24_carregavalorvenal','char(1)','Se carrega ou não o valor venal na inclusão de ITBI.','', 'Carrega Valor Venal',1,'f','t','f',0,'text','Carrega Valor Venal');

            insert into db_sysarqcamp values(2362,1011739,16,0);

            /* itbi */

            insert into db_syscampo values(1011730,'it01_notificado','char(1)','Define se o contribuinte foi notificado sobre aquela ITBI, caso o parâmetro de notificação esteja ativado.','', 'Notificado',1,'f','t','f',0,'text','Notificado');
            
            insert into db_sysarqcamp values(792,1011730,22,0);
SQL
        );
    }

    private function downDicionario()
    {
        $this->execute(<<<SQL
            DELETE FROM db_sysforkey WHERE codarq IN (
                /* itbitaxasavalia */
                1010606,
                /* itbitaxasitbi */
                1010605,
                /* taxasitbitaxa */
                1010600,
                /* taxasitbi */
                1010597
            );

            DELETE FROM db_sysprikey WHERE codarq IN (
                /* itbitaxasavalia */
                1010606,
                /* itbitaxasitbi */
                1010605,
                /* taxasitbitaxa */
                1010600,
                /* taxasitbi */
                1010597
            );

            DELETE FROM db_sysarqcamp WHERE codarq IN (
                /* itbitaxasavalia */
                1010606,
                /* itbitaxasitbi */
                1010605,
                /* taxasitbitaxa */
                1010600,
                /* taxasitbi */
                1010597
            );

            DELETE FROM db_sysarqcamp WHERE codcam IN (
                /* paritbi */
                1011724,
                1011739,
                /* itbi */
                1011730
            );

            DELETE FROM db_syssequencia WHERE codsequencia IN (
                /* itbitaxasavalia */
                1000960,
                /* itbitaxasitbi */
                1000958,
                /* taxasitbitaxa */
                1000954,
                /* taxasitbi */
                1000952
            );

            DELETE FROM db_syscampo WHERE codcam IN (
                /* itbitaxasavalia */
                1011716,
                1011717,
                1011718,
                1011719,
                1011720,
                1011721,
                /* itbitaxasitbi */
                1011713,
                1011714,
                1011715,
                /* taxasitbitaxa */
                1011681,
                1011673,
                1011674,
                1011680,
                /* taxasitbi */
                1011656,
                1011657,
                1011669,
                1011670,
                1011687,
                /* paritbi */
                1011724,
                1011739,
                /* itbi */
                1011730
            );

            DELETE FROM db_sysarqmod WHERE codarq IN (
                /* itbitaxasavalia */
                1010606,
                /* itbitaxasitbi */
                1010605,
                /* taxasitbitaxa */
                1010600,
                /* taxasitbi */
                1010597
            );

            DELETE FROM db_sysarquivo WHERE codarq IN (
                /* itbitaxasavalia */
                1010606,
                /* itbitaxasitbi */
                1010605,
                /* taxasitbitaxa */
                1010600,
                /* taxasitbi */
                1010597
            );

            DELETE FROM db_menu WHERE modulo = 2544 AND id_item_filho IN (
                228271,
                228272,
                228273
            );

            DELETE FROM db_itensmenu WHERE id_item IN (
                228271,
                228272,
                228273
            );
SQL
        );
    }

    public function upCriaModImprime()
    {
        $this->execute(<<<SQL

            INSERT INTO cadmodcarne 
            (
                k47_sequencial,
                k47_descr,
                k47_tipoconvenio
            )
            VALUES 
            (
                102,
                'GUIA DE ITBI COBRANÇA MOD 2',
                2
            );

SQL
    );
    }

    public function downCriaModImprime()
    {
        $this->execute(<<<SQL

        delete from cadmodcarne where k47_sequencial = 102;

SQL
    );
    }
}
