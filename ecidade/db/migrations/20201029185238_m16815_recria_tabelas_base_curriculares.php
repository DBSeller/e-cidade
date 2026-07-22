<?php

use Classes\PostgresMigration;

class M16815RecriaTabelasBaseCurriculares extends PostgresMigration
{
    public function up()
    {
        $sqlExists = "select * from db_itensmenu where id_item = 10397;";
        $stmt = $this->query($sqlExists);
        $exists = $stmt->fetch();
        if ($exists) {
            return;
        }
        $this->criarMenu();
        $this->criarDiscionario();
        $this->createTable();
        $this->migracao();
    }
    private function criarMenu()
    {
        $aColumns = array('id_item', 'descricao', 'help', 'funcao', 'itemativo', 'manutencao', 'desctec', 'libcliente');
        $aValues  = array(array(10397, 'Base', 'Base', 'edu1_basecurricular001.php', '1', '1', 'Cadastro de Base', 'true'));
        $table    = $this->table('db_itensmenu', array('schema' => 'configuracoes'));
        $table->insert($aColumns, $aValues);
        $table->saveData();


        $aColumns = array('id_item', 'id_item_filho', 'menusequencia', 'modulo');
        $aValues  = array(array(10304, 10397, 2, 7159));
        $table    = $this->table('db_menu', array('schema' => 'configuracoes'));
        $table->insert($aColumns, $aValues);
        $table->saveData();
    }

    private function criarDiscionario()
    {

        // tabelas
        $aColumns = array('codarq', 'nomearq', 'descricao', 'sigla', 'dataincl', 'rotulo', 'tipotabela', 'naolibclass', 'naolibfunc', 'naolibprog', 'naolibform');
        $aValues  = array(
            array(4026, 'basecurricular', 'Base Curricular', 'ed141', '2017-02-01', 'Base Curricular', 0, 'f', 'f', 'f', 'f' ),
            array(4027, 'basecurricularserie', 'Etapa da base currícular', 'ed142', '2017-02-01', 'Etapa da Base', 0, 'f', 'f', 'f', 'f' ),
        );
        $table    = $this->table('db_sysarquivo', array('schema' => 'configuracoes'));
        $table->insert($aColumns, $aValues);
        $table->saveData();

        // vincula modulo
        $aColumns = array('codmod', 'codarq' );
        $aValues  = array(
            array(61,4026),
            array(61,4027),
        );
        $table    = $this->table('db_sysarqmod', array('schema' => 'configuracoes'));
        $table->insert($aColumns, $aValues);
        $table->saveData();

        // campos
        $aColumns = array('codcam', 'nomecam', 'conteudo', 'descricao', 'valorinicial', 'rotulo', 'tamanho', 'nulo', 'maiusculo', 'autocompl', 'aceitatipo', 'tipoobj', 'rotulorel');
        $aValues  = array(
            array(22348,'ed141_sequencial','int4','PK','0', 'Codigo',10,'f','f','f',1,'text','Codigo'),
            array(22349,'ed141_cursoedu','int4','Curso','0', 'Curso',10,'f','f','f',1,'text','Curso'),
            array(22350,'ed141_tipo','int4','Tipo da base: 1 - Inicial 2 - Final','0', 'Tipo',10,'f','f','f',1,'text','Tipo'),
            array(22351,'ed141_descricao','varchar(40)','Nome da base','', 'Descrição',40,'f','t','f',0,'text','Descrição'),
            array(22352,'ed142_sequencial','int4','PK','0', 'Código',10,'f','f','f',1,'text','Código'),
            array(22353,'ed142_basecurricular','int4','Base curricular FK','0', 'Base',10,'f','f','f',1,'text','Base'),
            array(22354,'ed142_serie','int4','Etapa','0', 'Etapa',10,'f','f','f',1,'text','Etapa'),
        );
        $table    = $this->table('db_syscampo', array('schema' => 'configuracoes'));
        $table->insert($aColumns, $aValues);
        $table->saveData();


        // inclui a sequence
        $aColumns = array('codsequencia', 'nomesequencia', 'incrseq', 'minvalueseq', 'maxvalueseq', 'startseq', 'cacheseq');
        $aValues  = array(
            array(1000651, 'basecurricular_ed141_sequencial_seq', 1, 1, 9223372036854775807, 1, 1),
            array(1000652, 'basecurricularserie_ed142_sequencial_seq', 1, 1, 9223372036854775807, 1, 1),
        );
        $table    = $this->table('db_syssequencia', array('schema' => 'configuracoes'));
        $table->insert($aColumns, $aValues);
        $table->saveData();

        // vincula os campos as tabelas
        $aColumns = array('codarq', 'codcam', 'seqarq', 'codsequencia');
        $aValues  = array(
            array(4026,22348,1,1000651),
            array(4026,22349,2,0),
            array(4026,22350,3,0),
            array(4026,22351,4,0),
            array(4027,22352,1,1000652),
            array(4027,22353,2,0),
            array(4027,22354,3,0),
        );
        $table    = $this->table('db_sysarqcamp', array('schema' => 'configuracoes'));
        $table->insert($aColumns, $aValues);
        $table->saveData();

        // inclui a chave primaria
        $aColumns = array('codarq','codcam','sequen','camiden');
        $aValues  = array(
            array(4026,22348,1,22351),
            array(4027,22352,1,22352),
        );
        $table    = $this->table('db_sysprikey', array('schema' => 'configuracoes'));
        $table->insert($aColumns, $aValues);
        $table->saveData();

        // inclui a chave estrangeira
        $aColumns = array('codarq', 'codcam', 'sequen', 'referen', 'tipoobjrel');
        $aValues  = array(
            array(4026, 22349, 1, 1010048, 0),
            array(4027, 22353, 1, 4026, 0),
            array(4027, 22354, 1, 1010047, 0),
        );
        $table    = $this->table('db_sysforkey', array('schema' => 'configuracoes'));
        $table->insert($aColumns, $aValues);
        $table->saveData();

        // inclui os indices
        $aColumns = array('codind', 'nomeind', 'codarq', 'campounico');
        $aValues  = array(
            array(4418,'basecurricularserie_basecurricular_in',4027,'0'),
            array(4419,'basecurricularserie_serie_in',4027,'0'),
            array(4420,'basecurricular_cursoedu_in',4026,'0'),
        );
        $table    = $this->table('db_sysindices', array('schema' => 'configuracoes'));
        $table->insert($aColumns, $aValues);
        $table->saveData();

        // vincula os indices
        $aColumns = array('codind', 'codcam', 'sequen');
        $aValues  = array(
            array(4418,22353,1),
            array(4419,22354,1),
            array(4420,22349,1),
        );
        $table    = $this->table('db_syscadind', array('schema' => 'configuracoes'));
        $table->insert($aColumns, $aValues);
        $table->saveData();
    }
    private function createTable()
    {

        $this->execute("CREATE SEQUENCE secretariadeeducacao.basecurricular_ed141_sequencial_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1 CACHE 1;");
        $this->execute("CREATE SEQUENCE secretariadeeducacao.basecurricularserie_ed142_sequencial_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1 CACHE 1;");

        $tabela = $this->table('basecurricular',  array('schema'=>'secretariadeeducacao', 'id'=> false, 'primary_key'=>'ed141_sequencial', 'constraint'=>'basecurricular_ed141_sequencial_pk'));
        $tabela->addColumn('ed141_sequencial', 'integer')
            ->addColumn('ed141_cursoedu', 'integer')
            ->addColumn('ed141_tipo', 'integer')
            ->addColumn('ed141_descricao', 'string', array('limit' => 40))
            ->addForeignKey('ed141_cursoedu', 'escola.cursoedu', 'ed29_i_codigo', array('constraint'=>'basecurricular_cursoedu_fk'))
            ->addIndex(array('ed141_cursoedu'),         array('name' => 'basecurricular_cursoedu_in'))
            ->create();
        $this->execute("ALTER TABLE secretariadeeducacao.basecurricular ALTER COLUMN ed141_sequencial SET DEFAULT nextval('secretariadeeducacao.basecurricular_ed141_sequencial_seq')");


        $tabela = $this->table('basecurricularserie',  array('schema'=>'secretariadeeducacao', 'id'=> false,
            'primary_key'=>'ed142_sequencial',
            'constraint'=>'basecurricularserie_ed142_sequencial_pk'));
        $tabela->addColumn('ed142_sequencial', 'integer')
            ->addColumn('ed142_basecurricular', 'integer')
            ->addColumn('ed142_serie', 'integer')
            ->addForeignKey('ed142_basecurricular', 'secretariadeeducacao.basecurricular', 'ed141_sequencial', array('constraint'=>'basecurricularserie_basecurricular_fk'))
            ->addForeignKey('ed142_serie',          'escola.serie',                        'ed11_i_codigo',    array('constraint'=>'basecurricularserie_serie_fk'))
            ->addIndex(array('ed142_basecurricular'), array('name' => 'basecurricularserie_basecurricular_in'))
            ->addIndex(array('ed142_serie'),          array('name' => 'basecurricularserie_serie_in'))
            ->create();
        $this->execute("ALTER TABLE secretariadeeducacao.basecurricularserie ALTER COLUMN ed142_sequencial SET DEFAULT nextval('secretariadeeducacao.basecurricularserie_ed142_sequencial_seq')");
    }

    private function migracao()
    {
        $this->execute("insert into basecurricular select nextval('basecurricular_ed141_sequencial_seq'::regclass), ed29_i_codigo, 1, 'INICIAL' from cursoedu ");
        $this->execute("insert into basecurricularserie select nextval('basecurricularserie_ed142_sequencial_seq'::regclass), ed141_sequencial, ed11_i_codigo
                                  from basecurricular
                                  join cursoedu on ed29_i_codigo = ed141_cursoedu
                                  join ensino   on ed29_i_ensino = ed10_i_codigo
                                  join serie    on ed11_i_ensino = ed10_i_codigo");
    }

    public function down()
    {

        $this->execute('delete from basecurricularserie;');
        $this->execute('delete from basecurricular;');

        $this->execute('delete from configuracoes.db_menu      where id_item_filho = 10397 and modulo = 7159');
        $this->execute('delete from configuracoes.db_itensmenu where id_item = 10397');

        $this->execute('delete from configuracoes.db_syscadind    where codind in (4418, 4419, 4420) ');
        $this->execute('delete from configuracoes.db_sysindices   where codind in (4418, 4419, 4420) ');
        $this->execute('delete from configuracoes.db_sysforkey    where codarq in (4026, 4027) ');
        $this->execute('delete from configuracoes.db_sysprikey    where codarq in (4026, 4027) ');
        $this->execute('delete from configuracoes.db_sysarqcamp   where codarq in (4026, 4027) ');
        $this->execute('delete from configuracoes.db_syscampo     where codcam in (22348, 22349, 22350, 22351, 22352, 22353, 22354) ');
        $this->execute('delete from configuracoes.db_syssequencia where codsequencia in(1000651, 1000652) ');
        $this->execute('delete from configuracoes.db_sysarqmod    where codarq in (4026, 4027)');
        $this->execute('delete from configuracoes.db_sysarquivo   where codarq in (4026, 4027)');

        $this->execute('drop table if exists secretariadeeducacao.basecurricularserie');
        $this->execute('drop table if exists secretariadeeducacao.basecurricular');
        $this->execute('drop sequence if exists secretariadeeducacao.basecurricularserie_ed142_sequencial_seq');
        $this->execute('drop sequence if exists secretariadeeducacao.basecurricular_ed141_sequencial_seq');
    }
}
