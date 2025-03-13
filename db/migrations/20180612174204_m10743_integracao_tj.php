<?php

use Classes\PostgresMigration;

class M10743IntegracaoTj extends PostgresMigration
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

        $this->addDicionarioDados();
        $this->criarTabelas();
    }

    public function down()
    {

        $this->removerDicionarioDados();
        $this->droparDML();
    }

    public function addDicionarioDados()
    {

        /**
         * Cria tabelas
         */
        $aColumns = array(
            'codarq',
            'nomearq',
            'descricao',
            'sigla',
            'dataincl',
            'rotulo',
            'tipotabela',
            'naolibclass',
            'naolibfunc',
            'naolibprog',
            'naolibform'
        );
        $aValues = array(
            array(
                1010286,
                'integracaoprocessoeletronico',
                'Integração com o tj do rio de janeiro',
                'v38',
                '2018-06-12',
                'Integração com o tj do rio de janeiro',
                0,
                'f',
                'f',
                'f',
                'f'
            ),
            array(
                1010287,
                'integracaoprocessoeletronicomovimentacao',
                'Movimentações da inicial no processo Eletronico',
                'v39',
                '2018-06-12',
                'Movimentações da inicial no processo Eletronico',
                0,
                'f',
                'f',
                'f',
                'f'
            ),
            array(
                1010290,
                'integracaoprocessoeletronicoarquivo',
                'integracaoprocessoeletronicoarquivo',
                'v40',
                '2018-06-14',
                'Arquivos do processo eletronico',
                0,
                'f',
                'f',
                'f',
                'f'
            ),
            array(
                1010291,
                'integracaoprocessoeletronicoconfiguracao',
                'Configurações do Processo Eletrônico',
                'v41',
                '2018-06-20',
                'Configurações do Processo Eletrônico',
                0,
                'f',
                'f',
                'f',
                'f'
            ),
        );
        $table = $this->table('db_sysarquivo', array('schema' => 'configuracoes'));
        $table->insert($aColumns, $aValues);
        $table->saveData();

        // vincula modulo
        $aColumns = array('codmod', 'codarq');
        $aValues = array(
            array(21, 1010286),
            array(21, 1010287),
            array(21, 1010290),
            array(21, 1010291),
        );
        $table = $this->table('db_sysarqmod', array('schema' => 'configuracoes'));
        $table->insert($aColumns, $aValues);
        $table->saveData();

        /**
         * Cria campos
         */
        $aColumns = array(
            'codcam',
            'nomecam',
            'conteudo',
            'descricao',
            'valorinicial',
            'rotulo',
            'tamanho',
            'nulo',
            'maiusculo',
            'autocompl',
            'aceitatipo',
            'tipoobj',
            'rotulorel'
        );
        $aValues = array(
            array(
                1009765,
                'v38_sequencial',
                'int4',
                'Código',
                '',
                'Código',
                10,
                'false',
                'false',
                'false',
                1,
                'text',
                'Código'
            ),
            array(
                1009766,
                'v38_inicial',
                'int4',
                'Inicial',
                '',
                'Inicial',
                10,
                'false',
                'false',
                'false',
                1,
                'text',
                'Inicial'
            ),
            array(
                1009767,
                'v38_situacao',
                'int4',
                'Situação da inicial no TJ',
                '1',
                'Situação da inicial no TJ',
                10,
                'false',
                'false',
                'false',
                1,
                'text',
                'Situação da inicial no TJ'
            ),
            array(
                1009781,
                'v38_recibo',
                'text',
                'Recibo de Entrega',
                '',
                'Recibo de Entrega',
                1,
                'true',
                'true',
                'false',
                0,
                'text',
                'Recibo de Entrega'
            ),
            array(
                1009792,
                'v38_parte',
                'int4',
                'Parte Executada',
                '',
                'Parte Executada',
                10,
                'false',
                'false',
                'false',
                1,
                'text',
                'Parte Executada'
            ),
            array(
                1009768,
                'v39_sequencial',
                'int4',
                'ecidade_niteroi/',
                '',
                'Código',
                10,
                'false',
                'false',
                'false',
                1,
                'text',
                'Código'
            ),
            array(
                1009769,
                'v39_integracaoprocessoeletronico',
                'int4',
                'Código da Integração',
                '',
                'Código da Integração',
                10,
                'false',
                'false',
                'false',
                1,
                'text',
                'Código da Integração'
            ),
            array(
                1009770,
                'v39_dataenvio',
                'date',
                'Data de Envio',
                '',
                'Data de Envio',
                10,
                'false',
                'false',
                'false',
                1,
                'text',
                'Data de Envio'
            ),
            array(
                1009771,
                'v39_retorno ',
                'text',
                'Valor retornado pela API',
                'null',
                'Retorno ',
                1,
                'true',
                'true',
                'false',
                0,
                'text',
                'Retorno '
            ),
            array(
                1009782,
                'v39_protocolo',
                'varchar(255)',
                'Protocolo de Recebimento',
                '',
                'Protocolo de Recebimento',
                255,
                'false',
                'true',
                'false',
                0,
                'text',
                'Protocolo de Recebimento'
            ),
            array(
                1009783,
                'v40_sequencial',
                'int4',
                'Código',
                '',
                'Código',
                10,
                'false',
                'false',
                'false',
                1,
                'text',
                'Código'
            ),
            array(
                1009784,
                'v40_integracaoprocessoeletronico',
                'int4',
                'Processo Eletronico',
                '',
                'Processo Eletronico',
                10,
                'false',
                'false',
                'false',
                1,
                'text',
                'Processo Eletronico'
            ),
            array(
                1009785,
                'v40_data',
                'date',
                'Data do Arquivo',
                '',
                'Data do Arquivo',
                10,
                'false',
                'false',
                'false',
                1,
                'text',
                'Data do Arquivo'
            ),
            array(
                1009786,
                'v40_arquivo',
                'text',
                'Conteudo do arquivo em base64',
                '',
                'Arquivo',
                1,
                'false',
                'true',
                'false',
                0,
                'text',
                'Arquivo'
            ),
            array(
                1009787,
                'v40_nome',
                'varchar',
                'Nome do Arquivo',
                '',
                'Nome do Arquivo',
                10,
                'false',
                'false',
                'false',
                1,
                'text',
                'Nome do Arquivo'
            ),
            array(
                1009793,
                'v41_sequencial',
                'int4',
                'Código',
                '',
                'Código',
                10,
                'false',
                'false',
                'false',
                1,
                'text',
                'Código'
            ),
            array(
                1009794,
                'v41_instituicao',
                'int4',
                'Instituição',
                '',
                'Instituição',
                10,
                'false',
                'false',
                'false',
                1,
                'text',
                'Instituição'
            ),
            array(
                1009795,
                'v41_usuario',
                'varchar(100)',
                'usuário',
                '',
                'Usuário',
                100,
                'false',
                'true',
                'false',
                0,
                'text',
                'Usuário'
            ),
            array(
                1009796,
                'v41_senha',
                'varchar(255)',
                'Senha',
                '',
                'Senha',
                255,
                'false',
                'true',
                'false',
                0,
                'text',
                'Senha'
            ),
            array(
                1009797,
                'v41_codigolocalidade',
                'int4',
                'Código da Localidade',
                '',
                'Código da Localidade',
                10,
                'false',
                'false',
                'false',
                1,
                'text',
                'Código da Localidade'
            )
        );
        $table = $this->table('db_syscampo', array('schema' => 'configuracoes'));
        $table->insert($aColumns, $aValues);
        $table->saveData();

        /**
         * db_sysarqcamp
         */
        $aColumns = array('codarq', 'codcam', 'seqarq', 'codsequencia');
        $aValues = array(
            array(1010286, 1009765, 1, 0),
            array(1010286, 1009766, 2, 0),
            array(1010286, 1009767, 3, 0),
            array(1010286, 1009781, 4, 0),
            array(1010286, 1009792, 5, 0),
            array(1010287, 1009768, 1, 0),
            array(1010287, 1009769, 2, 0),
            array(1010287, 1009770, 3, 0),
            array(1010287, 1009771, 4, 0),
            array(1010287, 1009782, 5, 0),
            array(1010290, 1009783, 1, 0),
            array(1010290, 1009784, 2, 0),
            array(1010290, 1009785, 3, 0),
            array(1010290, 1009786, 4, 0),
            array(1010290, 1009787, 5, 0),
            array(1010291, 1009793, 1, 0),
            array(1010291, 1009794, 2, 0),
            array(1010291, 1009795, 3, 0),
            array(1010291, 1009796, 4, 0),
            array(1010291, 1009797, 5, 0)

        );
        $table = $this->table('db_sysarqcamp', array('schema' => 'configuracoes'));
        $table->insert($aColumns, $aValues);
        $table->saveData();


        // inclui a sequence
        $aColumns = array(
            'codsequencia',
            'nomesequencia',
            'incrseq',
            'minvalueseq',
            'maxvalueseq',
            'startseq',
            'cacheseq'
        );
        $aValues = array(
            array(1000737, 'integracaoprocessoeletronico_v38_sequencial_seq', 1, 1, 9223372036854775807, 1, 1),
            array(
                1000738,
                'integracaoprocessoeletronicomovimentacao_v39_sequencial_seq',
                1,
                1,
                9223372036854775807,
                1,
                1
            ),
            array(1000741, 'integracaoprocessoeletronicoarquivo_v40_sequencial_seq', 1, 1, 9223372036854775807, 1, 1),
            array(
                1000742,
                'integracaoprocessoeletronicoconfiguracao_v41_sequencial_seq',
                1,
                1,
                9223372036854775807,
                1,
                1
            ),
        );
        $table = $this->table('db_syssequencia', array('schema' => 'configuracoes'));
        $table->insert($aColumns, $aValues);
        $table->saveData();

        // inclui a chave primaria
        $aColumns = array('codarq', 'codcam', 'sequen', 'camiden');
        $aValues = array(
            array(1010286, 1009765, 1, 1009766),
            array(1010287, 1009769, 1, 1009769),
            array(1010290, 1009784, 1, 1009783),
            array(1010291, 1009793, 1, 1009794),
        );
        $table = $this->table('db_sysprikey', array('schema' => 'configuracoes'));
        $table->insert($aColumns, $aValues);
        $table->saveData();

        // inclui a chave estrangeira
        $aColumns = array('codarq', 'codcam', 'sequen', 'referen', 'tipoobjrel');
        $aValues = array(
            array(1010286, 1009766, 1, 108, 0),
            array(1010286, 1009792, 1, 42, 0),
            array(1010287, 1009769, 1, 1010286, 0),
            array(1010290, 1009784, 1, 1010286, 0),
            array(1010291, 1009794, 1, 83, 0),


        );
        $table = $this->table('db_sysforkey', array('schema' => 'configuracoes'));
        $table->insert($aColumns, $aValues);
        $table->saveData();

        // inclui os indices
        $aColumns = array('codind', 'nomeind', 'codarq', 'campounico');
        $aValues = array(
            array(1008286, 'integracaoprocessoeletronico_inicial_in', 1010286, '0'),
            array(1008287, 'integracaoprocessoeletronico_situacao', 1010286, '0'),
            array(1008288, 'integracaoprocessoeletronicomovimentacao_integracao_in', 1010287, '0'),
            array(1008292, 'integracaoprocessoeletronicoarquivo_processo_in', 1010290, '0'),
            array(1008293, 'integracaoprocessoeletronico_parte_in', 1010286, '0'),
            array(1008294, 'integracaoprocessoeletronicoconfiguracao_instit_in', 1010291, '1'),


        );
        $table = $this->table('db_sysindices', array('schema' => 'configuracoes'));
        $table->insert($aColumns, $aValues);
        $table->saveData();

        // vincula os indices
        $aColumns = array('codind', 'codcam', 'sequen');
        $aValues = array(
            array(1008286, 1009766, 1),
            array(1008287, 1009767, 1),
            array(1008288, 1009769, 1),
            array(1008292, 1009784, 1),
            array(1008293, 1009792, 1),
            array(1008294, 1009794, 1),

        );
        $table = $this->table('db_syscadind', array('schema' => 'configuracoes'));
        $table->insert($aColumns, $aValues);
        $table->saveData();

        $this->execute("update db_sysarqcamp set codsequencia = 1000737 where codarq = 1010286 and codcam = 1009765;");
        $this->execute("update db_sysarqcamp set codsequencia = 1000738 where codarq = 1010287 and codcam = 1009768;");
        $this->execute("update db_sysarqcamp set codsequencia = 1000741 where codarq = 1010290 and codcam = 1009783;");
        $this->execute("update db_sysarqcamp set codsequencia = 1000742 where codarq = 1010291 and codcam = 1009793;");

        $this->execute(<<<SQL
    insert into db_itensmenu ( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 10532 ,'Processo Eletrônico' ,'Processo Eletrônico' ,'' ,'1' ,'1' ,'Processamento da lista de iniciais' ,'true' );
    insert into db_itensmenu ( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 10533 ,'Processar Lista de Iniciais' ,'Processar Lista de Iniciais' ,'jur4_processainiciais.php' ,'1' ,'1' ,'Realiza o processamento da lista gerada e realiza do download das CDS para serem assinadas' ,'false' );
    insert into db_itensmenu ( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 10534 ,'Configurações' ,'Configurações para envio de processos ao TJ' ,'jur4_configuraccoesprocessoeletronico.php' ,'1' ,'1' ,'Configurações para envio de processos ao TJ' ,'true' );
    insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 1818 ,10532 ,118 ,313 );
    insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 10532 ,10533 ,1 ,313 );
    insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 10532 ,10534 ,2 ,313 );
SQL
        );


    }

    public function criarTabelas()
    {


        $this->execute(<<<SQL
      create table juridico.integracaoprocessoeletronico (v38_sequencial serial primary key , 
                                                 v38_inicial integer not null, 
                                                 v38_situacao integer default 1, 
                                                 v38_recibo text, 
                                                 v38_parte int not null);
      alter table juridico.integracaoprocessoeletronico add constraint integracaoprocessoeletronico_inicial_fk foreign key (v38_inicial)references inicial(v50_inicial);
      alter table juridico.integracaoprocessoeletronico add constraint integracaoprocessoeletronico_parte_fk foreign key (v38_parte)references protocolo.cgm(z01_numcgm);
      create index integracaoprocessoeletronico_inicial_in on juridico.integracaoprocessoeletronico(v38_inicial);
      create index integracaoprocessoeletronico_situacao_in on juridico.integracaoprocessoeletronico(v38_situacao);
      create index integracaoprocessoeletronico_parte_in on juridico.integracaoprocessoeletronico(v38_parte);

SQL
        );


        $this->execute(<<<SQL
      create table juridico.integracaoprocessoeletronicomovimentacao (v39_sequencial serial primary key , 
                                                 v39_integracaoprocessoeletronico integer not null,
                                                 v39_protocolo text not null,  
                                                 v39_dataenvio date,
                                                 v39_retorno text);
      alter table juridico.integracaoprocessoeletronicomovimentacao add constraint integracaoprocessoeletronicomovimentacao_inicial_fk foreign key (v39_integracaoprocessoeletronico) references juridico.integracaoprocessoeletronico (v38_sequencial);
      create index integracaoprocessoeletronicomovimentacao_integracao_in on juridico.integracaoprocessoeletronicomovimentacao(v39_integracaoprocessoeletronico);
      

SQL
        );

        $this->execute(<<<SQL
      create table juridico.integracaoprocessoeletronicoarquivo (v40_sequencial serial primary key , 
                                                 v40_integracaoprocessoeletronico integer not null, 
                                                 v40_data date,
                                                 v40_arquivo text,
                                                 v40_nome varchar);
      alter table juridico.integracaoprocessoeletronicoarquivo add constraint integracaoprocessoeletronicoarquivo_inicial_fk foreign key (v40_integracaoprocessoeletronico) references juridico.integracaoprocessoeletronico (v38_sequencial);
      create index integracaoprocessoeletronicoarquivo_integracao_in on integracaoprocessoeletronicoarquivo(v40_integracaoprocessoeletronico);
      

SQL
        );

        $this->execute(<<<SQL
CREATE SEQUENCE integracaoprocessoeletronicoconfiguracao_v41_sequencial_seq
INCREMENT 1
MINVALUE 1
MAXVALUE 9223372036854775807
START 1
CACHE 1;

CREATE TABLE integracaoprocessoeletronicoconfiguracao(
v41_sequencial		int4 NOT NULL  default nextval('integracaoprocessoeletronicoconfiguracao_v41_sequencial_seq'),
v41_instituicao		int4 NOT NULL ,
v41_usuario		varchar(100) NOT NULL ,
v41_senha		varchar(255) NOT NULL ,
v41_codigolocalidade		int4 ,
CONSTRAINT integracaoprocessoeletronicoconfiguracao_sequ_pk PRIMARY KEY (v41_sequencial));


ALTER TABLE integracaoprocessoeletronicoconfiguracao
ADD CONSTRAINT integracaoprocessoeletronicoconfiguracao_instituicao_fk FOREIGN KEY (v41_instituicao)
REFERENCES db_config;

CREATE UNIQUE INDEX integracaoprocessoeletronicoconfiguracao_instit_in ON integracaoprocessoeletronicoconfiguracao(v41_instituicao);
SQL
        );


    }

    /**
     * Remove dados do dicionario de dados
     */
    private function removerDicionarioDados()
    {

        $this->execute('delete from configuracoes.db_syscadind where codind in(1008286, 1008287, 1008288,1008292, 1008293,1008294)');
        $this->execute('delete from configuracoes.db_sysindices where codind in(1008286, 1008287, 1008288,1008292, 1008293, 1008294)');
        $this->execute('delete from configuracoes.db_sysforkey where codcam in(1009765, 1009766 ,1009767 ,1009768 ,1009769,1009770,1009771,1009783,1009784,1009785,1009786, 1009792)');
        $this->execute('delete from configuracoes.db_syssequencia where codsequencia in(1000737, 1000738,1000741, 1000742)');
        $this->execute('delete from configuracoes.db_sysprikey where codarq in(1010286, 1010287, 1010290, 1010291)');
        $this->execute('delete from configuracoes.db_sysarqcamp where codcam in(1009765, 1009766 ,1009767 ,1009768 ,1009769,1009770,1009771, 1009781, 1009782,1009783,1009784,1009785,1009786, 1009787, 1009792,1009793, 1009794,1009795,1009796,1009797 )');
        $this->execute('delete from configuracoes.db_syscampo where codcam in(1009765, 1009766 ,1009767 ,1009768 ,1009769,1009770,1009771, 1009781, 1009782,1009783,1009784,1009785,1009786, 1009787,1009792,1009794,1009795,1009796,1009797 )');
        $this->execute('delete from configuracoes.db_sysarqmod where codarq in(1010286, 1010287,1010290)');
        $this->execute('delete from configuracoes.db_sysarquivo where codarq in(1010286, 1010287,1010290)');
        $this->execute('delete from db_menu where id_item_filho in(10532, 10533, 10534) AND modulo = 313;');
        $this->execute('delete from db_itensmenu where id_item in (10532, 10533, 10534)');
    }

    private function droparDML()
    {

        $this->execute('drop table if exists integracaoprocessoeletronicomovimentacao');
        $this->execute('drop sequence if exists integracaoprocessoeletronicoconfiguracao_v41_sequencial_seq');
        $this->execute('drop table if exists integracaoprocessoeletronicoarquivo');
        $this->execute('drop table if exists integracaoprocessoeletronico');
        $this->execute('drop table if exists integracaoprocessoeletronicoconfiguracao');

    }

}
