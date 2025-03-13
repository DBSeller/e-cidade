<?php

use Classes\PostgresMigration;

class M15279LancamentoDeTaxas extends PostgresMigration
{
    public function up()
    {
        $this->upCriaTabelas();
        $this->upDicionarioDados();
    }

    public function down()
    {
        $this->downCriaTabelas();
        $this->downDicionarioDados();
    }

    private function upCriaTabelas()
    {
        $this->execute(<<<SQL
            CREATE TABLE arrecadacao.taxaslancadas
            (
                ar44_sequencial serial NOT NULL,
                ar44_descricao text NOT NULL,
                ar44_valorinflator numeric NOT NULL,
                ar44_inflator char(5) NOT NULL,
                ar44_diasvencimento integer NOT NULL,
                ar44_tipo integer NOT NULL DEFAULT 0,
                ar44_receitaxaexpediente integer,
                ar44_valortaxaexpediente numeric,
                ar44_datavigencia date,
                ar44_procedencia integer DEFAULT NULL,
                ar44_receita integer DEFAULT NULL,
                ar44_emissaoweb boolean NOT NULL DEFAULT FALSE,
                ar44_recursoadm boolean NOT NULL DEFAULT FALSE,
                ar44_origem char(1) NOT NULL DEFAULT 'T',
                CONSTRAINT taxaslancadas_pk PRIMARY KEY (ar44_sequencial),
                CONSTRAINT inflator_fk FOREIGN KEY (ar44_inflator) REFERENCES inflatores.inflan(i01_codigo),
                CONSTRAINT procedencia_fk FOREIGN KEY (ar44_procedencia) REFERENCES diversos.procdiver(dv09_procdiver),
                CONSTRAINT receitaxaexpediente_fk FOREIGN KEY (ar44_receitaxaexpediente) REFERENCES caixa.tabrec(k02_codigo),
                CONSTRAINT receita_fk FOREIGN KEY (ar44_receita) REFERENCES caixa.tabrec(k02_codigo)
            );

            CREATE TABLE arrecadacao.taxaslancadasdepart
            (
                ar45_sequencial serial NOT NULL,
                ar45_taxaslancadas integer NOT NULL,
                ar45_departamento integer NOT NULL,
                CONSTRAINT taxaslancadasdepart_pk PRIMARY KEY (ar45_sequencial),
                CONSTRAINT taxaslancadas_fk FOREIGN KEY (ar45_taxaslancadas) REFERENCES arrecadacao.taxaslancadas(ar44_sequencial),
                CONSTRAINT db_depart_fk FOREIGN KEY (ar45_departamento) REFERENCES configuracoes.db_depart(coddepto)
            );

            CREATE TABLE arrecadacao.taxaslancadasrecibo
            (
                ar46_sequencial serial NOT NULL,
                ar46_taxaslancadas integer NOT NULL,
                ar46_numnov integer NOT NULL,
                ar46_tipoemissao integer NOT NULL DEFAULT 0,
                ar46_departamento integer,
                CONSTRAINT taxaslancadasrecibo_pk PRIMARY KEY (ar46_sequencial),
                CONSTRAINT taxaslancadas_fk FOREIGN KEY (ar46_taxaslancadas) REFERENCES arrecadacao.taxaslancadas(ar44_sequencial)
            );

            CREATE TABLE arrecadacao.taxaslancadasdinamicos
            (
                ar47_sequencial serial NOT NULL,
                ar47_taxaslancadas integer NOT NULL,
                ar47_codcam integer NOT NULL,
                ar47_obrigatorio boolean NOT NULL DEFAULT FALSE,
                ar47_tipocampo integer NOT NULL,
                ar47_valordefault text,
                CONSTRAINT taxaslancadasdinamicos_pk PRIMARY KEY (ar47_sequencial),
                CONSTRAINT db_syscampo_fk FOREIGN KEY (ar47_codcam ) REFERENCES db_syscampo(codcam) MATCH FULL DEFERRABLE,
                CONSTRAINT taxaslancadas_fk FOREIGN KEY (ar47_taxaslancadas) REFERENCES arrecadacao.taxaslancadas(ar44_sequencial)
            );

            CREATE TABLE arrecadacao.taxaslancadasdinamicosvalor
            (
                ar48_sequencial serial NOT NULL,
                ar48_codcam integer NOT NULL,
                ar48_conteudo text NOT NULL,
                ar48_numnov integer NOT NULL,
                CONSTRAINT taxaslancadasdinamicosvalor_pk PRIMARY KEY (ar48_sequencial)
            );
SQL
        );
    }

    private function downCriaTabelas()
    {
        $this->execute(<<<SQL
            DROP TABLE arrecadacao.taxaslancadasdinamicosvalor;
            DROP TABLE arrecadacao.taxaslancadasdinamicos;
            DROP TABLE arrecadacao.taxaslancadasrecibo;
            DROP TABLE arrecadacao.taxaslancadasdepart;
            DROP TABLE arrecadacao.taxaslancadas;
SQL
        );
    }

    private function upDicionarioDados()
    {
        $this->execute(<<<SQL
            INSERT INTO db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) VALUES ( 228244 ,'Lançamento de Taxas' ,'Inclusão e alteração das taxas lançadadas' ,'arr4_taxaslancadas001.php' ,'1' ,'1' ,'Cadastro das Taxas.' ,'true' );
            INSERT INTO db_menu(id_item, id_item_filho, menusequencia, modulo) VALUES (29, 228244, 287, 1985522);

            INSERT INTO db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) VALUES ( 228245 ,'Lançamento de Taxas' ,'Gera o recibo para determinada taxa.' ,'arr4_taxaslancadas003.php' ,'1' ,'1' ,'Gera o recibo para as taxas cadastradas na rotina "Arrecadação > Cadastro > Lançamento de Taxas".' , 'true');
            INSERT INTO db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) VALUES ( 32 ,228245 ,513 ,1985522 );

            INSERT INTO db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) VALUES ( 228250 ,'Lançamento de Taxas' ,'Lançamento de Taxas' ,'arr4_taxaslancadas005.php' ,'1' ,'1' ,'Tela que gera relatório de taxas cadastradas em "Arrecadação > Cadastro > Lançamento de Taxas" e processadas em "Arrecadação > Procedimentos > Lançamento de Taxas".' ,'true' );
            INSERT INTO db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) VALUES ( 30 ,228250 ,480 ,1985522 );

            insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228262 ,'Inclusão' ,'Inclusão de taxas' ,'arr4_taxaslancadas001.php?db_opcao=1' ,'1' ,'1' ,'Inclusão de taxas' ,'true' );
            insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 228244 ,228262 ,1 ,1985522 );

            insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228263 ,'Alteração' ,'Alteração de taxas' ,'arr4_taxaslancadas001.php?db_opcao=2' ,'1' ,'1' ,'Alteração das taxas' ,'true' );
            insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 228244 ,228263 ,2 ,1985522 );

            /* taxaslancadas */
            INSERT INTO db_sysarquivo VALUES (1010547, 'taxaslancadas', 'guarda as taxas lançadas.', 'ar44', '2020-04-14', 'lancamento de taxas', 0, 'f', 'f', 'f', 'f' );
            INSERT INTO db_sysarqmod VALUES (54,1010547);

            INSERT INTO db_syscampo VALUES(1011176,'ar44_sequencial','int4', 'Sequencial da tabela taxaslancadas.','0', 'Sequencial',11,'f','f','f',1,'text','Sequencial');
            INSERT INTO db_syscampo VALUES(1011177,'ar44_descricao','varchar(255)', 'Descrição da taxa.','', 'Descrição',255,'f','t','f',0,'text','Descrição');
            INSERT INTO db_syscampo VALUES(1011178,'ar44_valorinflator','float4', 'Quantidade que foi definida para o inflator.','0', 'Quantidade de Inflator da Taxa',11,'f','f','f',4,'text','Quantidade de Inflator da Taxa');
            INSERT INTO db_syscampo VALUES(1011179,'ar44_inflator','char(11)', 'Código do inflator.','', 'Inflator',11,'f','t','f',0,'text','Inflator');
            INSERT INTO db_syscampo VALUES(1011180,'ar44_diasvencimento','int4', 'Quantidade de dias para ser somado a data atual e calcular o dia de vencimento do boleto.','0', 'Dias para Vencimento',11,'f','f','f',1,'text','Dias para Vencimento');
            INSERT INTO db_syscampo VALUES(1011217,'ar44_tipo','int4','campo com o tipo de taxa (fixa / variável).','0', 'tipo de taxa',11,'f','f','f',1,'text','tipo de taxa');
            INSERT INTO db_syscampo VALUES(1011181,'ar44_receitaxaexpediente','int4', 'Receita da taxa de expediente.','0', 'Receita da Taxa de Expediente',11,'t','f','f',1,'text','Receita da Taxa de Expediente');
            INSERT INTO db_syscampo VALUES(1011182,'ar44_valortaxaexpediente','float4', 'Quantidade de Inflator da Taxa de Expediente.','0', 'Quantidade de Inflator da Taxa de Expediente',11,'t','f','f',4,'text','Quantidade de Inflator Taxa Expediente');
            INSERT INTO db_syscampo VALUES(1011183,'ar44_datavigencia','date', 'Data limite que esta taxa está disponível para ser utilizada.','null', 'Data Limite',10,'t','f','f',1,'text','Data Limite');
            INSERT INTO db_syscampo VALUES(1011185,'ar44_procedencia','int4', 'Código da procedência.','0', 'Procedência',11,'t','f','f',1,'text','Procedência');
            INSERT INTO db_syscampo VALUES(1011186,'ar44_receita','int4', 'Código da receita.','0', 'Receita',11,'t','f','f',1,'text','Receita');
            INSERT INTO db_syscampo VALUES(1011187,'ar44_emissaoweb','char(1)', 'Permite que o contribuinte emita o boleto ou não para esta taxa na web.','', 'Emissão na web',1,'f','f','f',0,'text','Emissão na web');
            INSERT INTO db_syscampo VALUES(1011612,'ar44_recursoadm','char(1)','Define se a taxa é de recurso administrativo.','', 'Recurso Administrativo',1,'f','t','f',0,'text','Recurso Administrativo');
            INSERT INTO db_syscampo VALUES(1011756,'ar44_origem','char(1)','Define qual deve ser o tipo de origem da taxa.','', 'Tipo de Origem',1,'f','t','f',0,'text','Tipo de Origem');

            INSERT INTO db_syssequencia VALUES(1000898, 'taxaslancadas_ar44_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);

            INSERT INTO db_sysarqcamp VALUES(1010547,1011187,1,0);
            INSERT INTO db_sysarqcamp VALUES(1010547,1011186,2,0);
            INSERT INTO db_sysarqcamp VALUES(1010547,1011185,3,0);
            INSERT INTO db_sysarqcamp VALUES(1010547,1011183,4,0);
            INSERT INTO db_sysarqcamp VALUES(1010547,1011182,5,0);
            INSERT INTO db_sysarqcamp VALUES(1010547,1011181,6,0);
            INSERT INTO db_sysarqcamp VALUES(1010547,1011180,7,0);
            INSERT INTO db_sysarqcamp VALUES(1010547,1011179,8,0);
            INSERT INTO db_sysarqcamp VALUES(1010547,1011178,9,0);
            INSERT INTO db_sysarqcamp VALUES(1010547,1011177,10,0);
            INSERT INTO db_sysarqcamp VALUES(1010547,1011176,11,1000898);
            INSERT INTO db_sysarqcamp VALUES(1010547,1011217,12,0);
            INSERT INTO db_sysarqcamp VALUES(1010547,1011612,13,0);
            insert into db_sysarqcamp values(1010547,1011756,14,0);

            INSERT INTO db_sysforkey VALUES(1010547,1011179,1,80,0);
            INSERT INTO db_sysforkey VALUES(1010547,1011185,1,374,0);
            INSERT INTO db_sysforkey VALUES(1010547,1011181,1,75,0);
            INSERT INTO db_sysforkey VALUES(1010547,1011186,1,75,0);

            INSERT INTO db_sysprikey (codarq,codcam,sequen,camiden) VALUES(1010547,1011176,1,1011176);

            /* taxaslancadasdepart */

            INSERT INTO db_sysarquivo VALUES (1010548, 'taxaslancadasdepart', 'tabela que guarda os departamentos vinculado as taxas', 'ar45', '2020-04-17', 'departamento taxas lançadas', 0, 'f', 'f', 'f', 'f' );
            INSERT INTO db_sysarqmod VALUES (54,1010548);

            INSERT INTO db_syscampo VALUES(1011190,'ar45_sequencial','int4','sequencial da tabela taxaslancadasdepart','0', 'sequencial',11,'f','f','f',1,'text','sequencial');
            INSERT INTO db_syscampo VALUES(1011191,'ar45_taxaslancadas','int4','código da taxa referenciando a tabela taxaslancadas.','0', 'código taxas',11,'f','f','f',1,'text','código taxas');
            INSERT INTO db_syscampo VALUES(1011192,'ar45_departamento','int4','código do departamento.','0', 'departamento',11,'f','f','f',1,'text','departamento');

            INSERT INTO db_syssequencia VALUES(1000899, 'taxaslancadasdepart_ar45_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);

            INSERT INTO db_sysarqcamp VALUES(1010548,1011190,1,0);
            INSERT INTO db_sysarqcamp VALUES(1010548,1011191,2,0);
            INSERT INTO db_sysarqcamp VALUES(1010548,1011192,3,1000899);

            INSERT INTO db_sysforkey VALUES(1010548,1011191,1,1010547,0);

            INSERT INTO db_sysprikey (codarq,codcam,sequen,camiden) VALUES(1010548,1011190,1,1011190);

            /* taxaslancadasrecibo */

            INSERT INTO db_sysarquivo VALUES (1010549, 'taxaslancadasrecibo', 'Guarda os recibos que foram gerados para a taxa.', 'ar46', '2020-04-20', 'Taxas Lançadas Recibo', 0, 'f', 'f', 'f', 'f' );
            INSERT INTO db_sysarqmod VALUES (54,1010549);

            INSERT INTO db_syscampo VALUES(1011195,'ar46_sequencial','int4','Sequencial da tabela taxaslancadasrecibo.','0', 'sequencial',11,'f','f','f',1,'text','sequencial');
            INSERT INTO db_syscampo VALUES(1011196,'ar46_taxaslancadas','int4','Código da Taxa.','0', 'Código da Taxa',11,'f','f','f',1,'text','Código da Taxa');
            INSERT INTO db_syscampo VALUES(1011197,'ar46_numnov','int4','Código do recibo que foi gerado para determinada taxa.','0', 'Código do Recibo',11,'f','f','f',1,'text','Código do Recibo');
            INSERT INTO db_syscampo VALUES(1011241,'ar46_tipoemissao','int4','seta onde o recibo está sendo emitido (e-cidade ou dbpref)','0', 'tipo de emissão',11,'f','f','f',1,'text','tipo de emissão');
            INSERT INTO db_syscampo VALUES(1011643,'ar46_departamento','int4','Departamento em que a taxa foi gerada.','0', 'Departamento',11,'t','f','f',1,'text','Departamento');

            INSERT INTO db_syssequencia VALUES(1000900, 'taxaslancadasrecibo_ar46_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);

            INSERT INTO db_sysarqcamp VALUES(1010549,1011195,1,1000900);
            INSERT INTO db_sysarqcamp VALUES(1010549,1011196,2,0);
            INSERT INTO db_sysarqcamp VALUES(1010549,1011197,3,0);
            INSERT INTO db_sysarqcamp VALUES(1010549,1011241,4,0);
            INSERT INTO db_sysarqcamp VALUES(1010549,1011643,5,0);

            INSERT INTO db_sysforkey VALUES(1010549,1011196,1,1010547,0);

            INSERT INTO db_sysprikey (codarq,codcam,sequen,camiden) VALUES(1010549,1011195,1,1011195);

            /* taxaslancadasdinamicos */

            INSERT INTO db_sysarquivo VALUES (1010573, 'taxaslancadasdinamicos', 'Tabela que salva os campos dinâmicos do lançamento de taxas.', 'ar47', '2020-05-28', 'Campos dinâmicos', 0, 'f', 'f', 'f', 'f' );
            INSERT INTO db_sysarqmod VALUES (54,1010573);

            INSERT INTO db_syscampo VALUES(1011329,'ar47_sequencial','int4','Sequencial da tabela taxaslancadasdinamicos.','0', 'Sequencial',11,'f','f','f',1,'text','Sequencial');
            INSERT INTO db_syscampo VALUES(1011330,'ar47_taxaslancadas','int4','Sequencial com o código da tabela taxaslancadas.','0', 'Sequencial taxaslancadas',11,'f','f','f',1,'text','Sequencial taxaslancadas');
            INSERT INTO db_syscampo VALUES(1011331,'ar47_codcam','int4','Código do Campo','0', 'Código do Campo',11,'f','f','f',1,'text','Código do Campo');
            INSERT INTO db_syscampo VALUES(1011332,'ar47_obrigatorio','char(1)','Define se o campo é obrigatório ou não','0', 'Obrigatório',11,'f','f','f',1,'text','Obrigatório');
            INSERT INTO db_syscampo VALUES(1011342,'ar47_tipocampo','int4','Tipo de campo que deve ser mostrado na tela.','0', 'Tipo de Campo',1,'f','f','f',1,'text','Tipo de Campo');
            INSERT INTO db_syscampo VALUES(1011343,'ar47_valordefault','varchar(255)','Valor default do campo dinâmico','', 'Valor Default',255,'t','f','f',0,'text','Valor Default');

            INSERT INTO db_syssequencia VALUES(1000919, 'taxaslancadasdinamicos_ar47_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);

            INSERT INTO db_sysarqcamp VALUES(1010573,1011330,1,0);
            INSERT INTO db_sysarqcamp VALUES(1010573,1011331,2,0);
            INSERT INTO db_sysarqcamp VALUES(1010573,1011332,3,0);
            INSERT INTO db_sysarqcamp VALUES(1010573,1011329,4,1000919);
            INSERT INTO db_sysarqcamp VALUES(1010573,1011342,5,0);
            INSERT INTO db_sysarqcamp VALUES(1010573,1011343,6,0);

            INSERT INTO db_sysprikey (codarq,codcam,sequen,camiden) VALUES(1010573,1011329,1,1011330);

            INSERT INTO db_sysforkey VALUES(1010573,1011330,1,1010547,0);
            INSERT INTO db_sysforkey VALUES(1010573,1011331,1,144,0);

            INSERT INTO db_syscampodef VALUES(1011342,'1','');
            INSERT INTO db_syscampodef VALUES(1011342,'2','');
            INSERT INTO db_syscampodef VALUES(1011342,'3','');
            INSERT INTO db_syscampodef VALUES(1011342,'4','');
            INSERT INTO db_syscampodef VALUES(1011342,'5','');
            INSERT INTO db_syscampodef VALUES(1011342,'6','');
            INSERT INTO db_syscampodef VALUES(1011342,'7','');

            /* taxaslancadasdinamicosvalor */

            INSERT INTO db_sysarquivo VALUES (1010574, 'taxaslancadasdinamicosvalor', 'Tabela que salva o valor dos campos dinâmicos vinculados ao recibo.', 'ar48', '2020-06-03', 'Valores campos dinâmicos', 0, 'f', 'f', 'f', 'f' );
            INSERT INTO db_sysarqmod VALUES (54,1010574);

            INSERT INTO db_syscampo VALUES(1011335,'ar48_sequencial','int4','Sequencial da tabela taxaslancadasdinamicosvalor.','0', 'Sequencial',11,'f','f','f',1,'text','Sequencial');
            INSERT INTO db_syscampo VALUES(1011336,'ar48_codcam','int4','Código do campo cadastrado no dicionário de dados.','0', 'Código do Campo',11,'f','f','f',1,'text','Código do Campo');
            INSERT INTO db_syscampo VALUES(1011337,'ar48_conteudo','varchar(255)','Conteúdo do campo dinâmico.','', 'Canteúdo',255,'f','f','f',0,'text','Canteúdo');
            INSERT INTO db_syscampo VALUES(1011338,'ar48_numnov','int4','Código do recibo que foi gerado ao processar.','0', 'Código do Recibo',11,'f','f','f',1,'text','Código do Recibo');
            
            INSERT INTO db_syssequencia VALUES(1000920, 'taxaslancadasdinamicosvalor_ar48_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);

            INSERT INTO db_sysarqcamp VALUES(1010574,1011335,1,1000920);
            INSERT INTO db_sysarqcamp VALUES(1010574,1011336,2,0);
            INSERT INTO db_sysarqcamp VALUES(1010574,1011337,3,0);
            INSERT INTO db_sysarqcamp VALUES(1010574,1011338,4,0);
            
            INSERT INTO db_sysprikey (codarq,codcam,sequen,camiden) VALUES(1010574,1011335,1,1011335);            
SQL
        );
    }

    private function downDicionarioDados()
    {
        $this->execute(<<<SQL
            DELETE FROM db_sysprikey WHERE codarq IN (
                /* taxaslancadasdepart */
                1010548,
                /* taxaslancadasrecibo */
                1010549,
                /* taxaslancadas */
                1010547,
                /* taxaslancadasdinamicos */
                1010573,
                /* taxaslancadasdinamicosvalor */
                1010574
            );

            DELETE FROM db_sysforkey WHERE codarq IN (
                /* taxaslancadasdepart */
                1010548,
                /* taxaslancadasrecibo */
                1010549,
                /* taxaslancadas */
                1010547,
                /* taxaslancadasdinamicos */
                1010573,
                /* taxaslancadasdinamicosvalor */
                1010574
            );

            DELETE FROM db_sysarqcamp WHERE codarq IN (
                /* taxaslancadasdepart */
                1010548,
                /* taxaslancadasrecibo */
                1010549,
                /* taxaslancadas */
                1010547,
                /* taxaslancadasdinamicos */
                1010573,
                /* taxaslancadasdinamicosvalor */
                1010574
            );

            DELETE FROM db_syssequencia WHERE codsequencia IN (
                /* taxaslancadasdepart */
                1000899,
                /* taxaslancadasrecibo */
                1000900,
                /* taxaslancadas */
                1000898,
                /* taxaslancadasdinamicos */
                1000919,
                /* taxaslancadasdinamicosvalor */
                1000920
            );

            DELETE FROM db_syscampo WHERE codcam IN (
                /* taxaslancadasdepart */
                1011190,
                1011191,
                1011192,
                /* taxaslancadasrecibo */
                1011195,
                1011196,
                1011197,
                1011241,
                1010549,
                /* taxaslancadas */
                1011176,
                1011177,
                1011178,
                1011179,
                1011180,
                1011181,
                1011182,
                1011183,
                1011185,
                1011186,
                1011187,
                1011217,
                1011756,
                /* taxaslancadasdinamicos */
                1011329,
                1011330,
                1011331,
                1011332,
                1011342,
                1011343,
                /* taxaslancadasdinamicosvalor */
                1011335,
                1011336,
                1011337,
                1011338
            );

            DELETE FROM db_sysarqmod WHERE codarq IN (
                /* taxaslancadasdepart */
                1010548,
                /* taxaslancadasrecibo */
                1010549,
                /* taxaslancadas */
                1010547,
                /* taxaslancadasdinamicos */
                1010573,
                /* taxaslancadasdinamicosvalor */
                1010574
            );

            DELETE FROM db_sysarquivo WHERE codarq IN (
                /* taxaslancadasdepart */
                1010548,
                /* taxaslancadasrecibo */
                1010549,
                /* taxaslancadas */
                1010547,
                /* taxaslancadasdinamicos */
                1010573,
                /* taxaslancadasdinamicosvalor */
                1010574
            );

            DELETE FROM db_menu WHERE modulo = 1985522 AND id_item_filho IN (
                /* Arrecadação > Cadastro > Lançamento de Taxas */
                228244,
                /* Arrecadação > Procedimentos > Lançamento de Taxas */
                228245,
                /* Arrecadação > Relatórios > Lançamento de Taxas */
                228250,
                /* Arrecadação > Cadastro > Lançamento de Taxas > Inclusão*/
                228262,
                /* Arrecadação > Cadastro > Lançamento de Taxas > Alteração*/
                228263
            );

            DELETE FROM db_itensmenu WHERE id_item IN (
                /* Arrecadação > Cadastro > Lançamento de Taxas */
                228244,
                /* Arrecadação > Procedimentos > Lançamento de Taxas */
                228245,
                /* Arrecadação > Relatórios > Lançamento de Taxas */
                228250,
                /* Arrecadação > Cadastro > Lançamento de Taxas > Inclusão*/
                228262,
                /* Arrecadação > Cadastro > Lançamento de Taxas > Alteração*/
                228263
            );
SQL
        );
    }
}
