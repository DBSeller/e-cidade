<?php

use Classes\PostgresMigration;

class M16507ComparaValoresAvaliacao extends PostgresMigration
{
    public function up()
    {
        $this->upDicionario();
        $this->upEstrutura();
    }

    public function down()
    {
        $this->downDicionario();
        $this->downEstrutura();
    }

    public function upDicionario()
    {
        $this->execute(<<<SQL
            /* paritbi */
            INSERT INTO db_syscampo VALUES(1011827,'it24_comparavaloresavaliacao','bool','Campo que parametriza a comparação de valores de avaliação do itbi.','f', 'Compara Valores Avaliação',1,'f','f','f',5,'text','Compara Valores Avaliação');
            INSERT INTO db_syscampo VALUES(1011922,'it24_padraoconstrutivobrigatorio','bool','Seta se o campo Padrão Construtivo é obrigatório ou não na aba de benfeitorias no ITBI.','t', 'Padrão Construtivo Obrigatório',1,'f','f','f',1,'text','Padrão Construtivo Obrigatório');
            INSERT INTO db_syscampo VALUES(1011923,'it24_carregaconstrucoesbenfeitoriasitbi','bool','Seta se carrega ou não as benfeitorias no ITBI vindo do cadastro imobiliário.','f', 'Carrega Contruções Benfeitoria ITBI',1,'f','f','f',1,'text','Carrega Contruções Benfeitoria ITBI');

            INSERT INTO db_sysarqcamp VALUES(2362,1011827,17,0);
            INSERT INTO db_sysarqcamp VALUES(2362,1011922,17,0);
            INSERT INTO db_sysarqcamp VALUES(2362,1011923,17,0);

            INSERT INTO db_syscampo VALUES(1011828,'it37_iniciofaixa','float4','Valor que inicia a faixa para o cálculo.','0', 'Inicio Faixa',11,'t','f','f',4,'text','Inicio Faixa');
            INSERT INTO db_syscampo VALUES(1011829,'it37_fimfaixa','float4','Valor final da faixa','0', 'Fim Faixa',10,'t','f','f',4,'text','Fim Faixa');
            INSERT INTO db_sysarqcamp VALUES(1010600,1011828,5,0);
            INSERT INTO db_sysarqcamp VALUES(1010600,1011829,6,0);

            /* cartorioextra */
            INSERT INTO db_sysarquivo VALUES (1010619, 'cartorioextra', 'Tabela que salva os cartórios extrajudiciais.', 'j167', '2020-09-30', 'Cartório Extrajudicial', 0, 'f', 'f', 'f', 'f' );
            INSERT INTO db_sysarqmod VALUES (21,1010619);

            INSERT INTO db_syscampo VALUES(1011830,'j167_sequencial','int4','Sequencial da tabela cartorioextra.','0', 'Sequencial',11,'f','f','f',1,'text','Sequencial');
            INSERT INTO db_syscampo VALUES(1011831,'j167_descricao','text','Descrição do cartório extrajudicial.','', 'Descrição',100,'f','t','f',0,'text','Descrição');
            INSERT INTO db_syscampo VALUES(1011837,'j167_numcgm','int4','Número do CGM do cartório.','0', 'CGM',11,'f','f','f',1,'text','CGM');
            INSERT INTO db_syscampo VALUES(1011838,'j167_observacao','text','Observação sobre o cartório','', 'Observação',500,'t','f','f',0,'text','Observação');

            INSERT INTO db_syssequencia VALUES(1000971, 'cartorioextra_j167_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);

            INSERT INTO db_sysarqcamp VALUES(1010619,1011830,1,1000971);
            INSERT INTO db_sysarqcamp VALUES(1010619,1011831,2,0);
            INSERT INTO db_sysarqcamp VALUES(1010619,1011837,3,0);
            INSERT INTO db_sysarqcamp VALUES(1010619,1011838,4,0);

            INSERT INTO db_sysprikey (codarq,codcam,sequen,camiden) VALUES(1010619,1011830,1,1011831);

            INSERT INTO db_sysforkey VALUES(1010619,1011837,1,42,0);

            /* tiposcartorioextra */

            INSERT INTO db_sysarquivo VALUES (1010621, 'tiposcartorioextra', 'Tabela de guarda os Tipos de Cartórios Extrajudiciais.', 'j169', '2020-09-30', 'Tipos de Cartórios Extrajudiciais', 0, 'f', 'f', 'f', 'f' );
            INSERT INTO db_sysarqmod VALUES (21,1010621);

            INSERT INTO db_syscampo VALUES(1011834,'j169_sequencial','int4','Sequencial da tabela tiposcartorioextra.','0', 'Sequencial',11,'f','f','f',1,'text','Sequencial');
            INSERT INTO db_syscampo VALUES(1011835,'j169_descricao','text','Descrição do tipo de Cartório Extrajudicial','', 'Tipo de Cartório Extrajudicial',1,'f','t','f',0,'text','Tipo de Cartório Extrajudicial');

            INSERT INTO db_syssequencia VALUES(1000972, 'tiposcartorioextra_j169_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);

            INSERT INTO db_sysarqcamp VALUES(1010621,1011834,1,1000972);
            INSERT INTO db_sysarqcamp VALUES(1010621,1011835,2,0);

            INSERT INTO db_sysprikey (codarq,codcam,sequen,camiden) VALUES(1010621,1011834,1,1011834);

            /* cartorioextratipo */
            INSERT INTO db_sysarquivo VALUES (1010620, 'cartorioextratipo', 'Tabela que guarda que tipo é o cartório', 'j168', '2020-09-30', 'Cartório Extrajudicial Tipo', 0, 'f', 'f', 'f', 'f' );
            INSERT INTO db_sysarqmod VALUES (21,1010620);

            INSERT INTO db_syssequencia VALUES(1000973, 'cartorioextratipo_j168_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);

            INSERT INTO db_syscampo VALUES(1011836,'j168_sequencial','int4','Sequencial da tabela cartorioextratipo.','0', 'Sequencial',11,'f','f','f',1,'text','Sequencial');
            INSERT INTO db_syscampo VALUES(1011832,'j168_cartorioextra','int4','Código do cartório extrajudicial','0', 'Código do cartório extrajudicial',11,'f','f','f',1,'text','Código do cartório extrajudicial');
            INSERT INTO db_syscampo VALUES(1011833,'j168_tiposcartorioextra','int4','Tipo do cartório extrajudicial','0', 'Tipo do cartório extrajudicial',11,'f','f','f',1,'text','Tipo do cartório extrajudicial');

            insert into db_sysarqcamp values(1010620,1011836,1,1000973);
            INSERT INTO db_sysarqcamp VALUES(1010620,1011832,1,0);
            INSERT INTO db_sysarqcamp VALUES(1010620,1011833,2,0);

            INSERT INTO db_sysforkey VALUES(1010620,1011832,1,1010619,0);
            INSERT INTO db_sysforkey VALUES(1010620,1011833,1,1010621,0);

            INSERT INTO db_sysprikey (codarq,codcam,sequen,camiden) VALUES(1010620,1011836,1,1011836);


            /* menu */

            INSERT INTO db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) VALUES ( 228329 ,'Cartório Extrajudicial' ,'Cartório Extrajudicial' ,'' ,'1' ,'1' ,'Menu de cadastros de cartórios extrajudiciais.' ,'true' );
            INSERT INTO db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) VALUES ( 29 ,228329 ,295 ,313 );

            INSERT INTO db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) VALUES ( 228330 ,'Inclusão' ,'Incusão de cartórios extrajudiciais' ,'jur01_cartorioextra001.php?db_opcao=1' ,'1' ,'1' ,'Inclusão de cartórios extrajudiciais' ,'true' );
            INSERT INTO db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) VALUES ( 228329 ,228330 ,1 ,313 );

            INSERT INTO db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) VALUES ( 228331 ,'Alteração' ,'Alteração de cartórios extrajudiciais' ,'jur01_cartorioextra001.php?db_opcao=2' ,'1' ,'1' ,'Alteração de cartórios extrajudiciais' ,'true' );
            INSERT INTO db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) VALUES ( 228329 ,228331 ,2 ,313 );

            /* itbi */

            INSERT INTO db_syscampo VALUES(1011840,'it01_cartorioextra','int4','Código do cartório','0', 'Cartório',11,'t','f','f',1,'text','Cartório');
            INSERT INTO db_sysarqcamp VALUES(792,1011840,23,0);
SQL
        );
    }

    public function downDicionario()
    {
        $this->execute(<<<SQL
            DELETE FROM db_sysforkey WHERE codarq IN (
                /* cartorioextra */
                1010619,
                /* cartorioextratipo */
                1010620
            );

            DELETE FROM db_sysprikey WHERE codarq IN (
                /* cartorioextra */
                1010619,
                /* tiposcartorioextra */
                1010621,
                /* cartorioextratipo */
                1010620
            );

            DELETE FROM db_sysarqcamp WHERE codcam IN (
                /* taxasitbitaxa */
                1011828,
                1011829,
                /* paritbi */
                1011827,
                1011922,
                1011923,
                /* cartorioextra */
                1011830,
                1011831,
                1011837,
                1011838,
                /* tiposcartorioextra */
                1011834,
                1011835,
                /* cartorioextratipo */
                1011836,
                1011832,
                1011833,
                /* itbi */
                1011840
            );

            DELETE FROM db_syscampo WHERE codcam IN (
                /* taxasitbitaxa */
                1011828,
                1011829,
                /* paritbi */
                1011827,
                1011922,
                1011923,
                /* cartorioextra */
                1011830,
                1011831,
                1011837,
                1011838,
                /* tiposcartorioextra */
                1011834,
                1011835,
                /* cartorioextratipo */
                1011836,
                1011832,
                1011833,
                /* itbi */
                1011840
            );

            DELETE FROM db_sysarqmod WHERE codarq IN (
                /* cartorioextra */
                1010619,
                /* tiposcartorioextra */
                1010621,
                /* cartorioextratipo */
                1010620
            );

            DELETE FROM db_sysarquivo WHERE codarq IN (
                /* cartorioextra */
                1010619,
                /* tiposcartorioextra */
                1010621,
                /* cartorioextratipo */
                1010620
            );

            DELETE FROM db_menu WHERE (id_item = 228329 OR id_item_filho = 228329);

            DELETE FROM db_itensmenu WHERE id_item IN (
                228329,
                228330,
                228331,
                228332
            );

            DELETE FROM db_syssequencia WHERE codsequencia IN (
                1000971,
                1000972,
                1000973
            );
SQL
        );
    }

    public function upEstrutura()
    {
        $this->execute(<<<SQL
            ALTER TABLE itbi.paritbi ADD it24_comparavaloresavaliacao boolean DEFAULT FALSE;
            ALTER TABLE itbi.paritbi ADD it24_padraoconstrutivobrigatorio boolean DEFAULT TRUE;
            ALTER TABLE itbi.paritbi ADD it24_carregaconstrucoesbenfeitoriasitbi boolean DEFAULT FALSE;

            ALTER TABLE itbi.taxasitbitaxa ADD COLUMN it37_iniciofaixa numeric;
            ALTER TABLE itbi.taxasitbitaxa ADD COLUMN it37_fimfaixa numeric;

            CREATE TABLE juridico.cartorioextra
            (
                j167_sequencial serial not null,
                j167_descricao text not null,
                j167_numcgm integer not null,
                j167_observacao text,
                CONSTRAINT cartorioextra_sequencial_pk PRIMARY KEY (j167_sequencial),
                CONSTRAINT cgm_fk FOREIGN KEY (j167_numcgm) REFERENCES protocolo.cgm(z01_numcgm)
            );

            CREATE TABLE juridico.tiposcartorioextra
            (
                j169_sequencial serial not null,
                j169_descricao text not null,
                CONSTRAINT tiposcartorioextra_sequencial_pk PRIMARY KEY (j169_sequencial)
            );

            INSERT INTO juridico.tiposcartorioextra
                (
                    j169_descricao
                )
            VALUES
                ('Cartório de Registro Civil'),
                ('Cartório de Notas'),
                ('Cartório de Registro de Imóveis'),
                ('Cartório de Protesto'),
                ('Cartório de Registro de Títulos e Documentos');

            CREATE TABLE juridico.cartorioextratipo
            (
                j168_sequencial serial not null,
                j168_cartorioextra integer not null,
                j168_tiposcartorioextra integer not null,
                CONSTRAINT cartorioextratipo_sequencial_pk PRIMARY KEY (j168_sequencial),
                CONSTRAINT cartorioextra_fk FOREIGN KEY (j168_cartorioextra) REFERENCES juridico.cartorioextra(j167_sequencial),
                CONSTRAINT tiposcartorioextra_fk FOREIGN KEY (j168_tiposcartorioextra) REFERENCES juridico.tiposcartorioextra(j169_sequencial)
            );

            ALTER TABLE itbi.itbi ADD COLUMN it01_cartorioextra integer;
            ALTER TABLE itbi.itbi ADD CONSTRAINT cartorioextra_fk FOREIGN KEY (it01_cartorioextra) REFERENCES juridico.cartorioextra(j167_sequencial);

            ALTER TABLE itbi.itburbano ALTER COLUMN it05_itbisituacao DROP NOT NULL;
            ALTER TABLE itbi.itburbano ALTER COLUMN it05_itbisituacao DROP DEFAULT;

            ALTER TABLE itbiformapagamentovalor ALTER COLUMN it26_valor TYPE numeric(15,2);
SQL
        );
    }

    public function downEstrutura()
    {
        $this->execute(<<<SQL
            ALTER TABLE itbi.paritbi DROP COLUMN it24_comparavaloresavaliacao;
            ALTER TABLE itbi.paritbi ADD it24_padraoconstrutivobrigatorio boolean DEFAULT FALSE;
            ALTER TABLE itbi.paritbi ADD it24_carregaconstrucoesbenfeitoriasitbi boolean DEFAULT FALSE;

            ALTER TABLE itbi.taxasitbitaxa DROP COLUMN it37_iniciofaixa;
            ALTER TABLE itbi.taxasitbitaxa DROP COLUMN it37_fimfaixa;

            DROP TABLE juridico.cartorioextratipo CASCADE;
            DROP TABLE juridico.tiposcartorioextra CASCADE;
            DROP TABLE juridico.cartorioextra CASCADE;

            ALTER TABLE itbi.itbi DROP COLUMN it01_cartorioextra;

            ALTER TABLE itbi.itburbano ALTER COLUMN it05_itbisituacao SET NOT NULL;
            ALTER TABLE itbi.itburbano ALTER COLUMN it05_itbisituacao SET DEFAULT 0;
SQL
        );
    }
}
