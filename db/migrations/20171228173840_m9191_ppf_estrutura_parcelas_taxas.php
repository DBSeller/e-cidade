<?php

use Classes\PostgresMigration;

class M9191PpfEstruturaParcelasTaxas extends PostgresMigration
{
  public function up()
  {

    $this->addDicionarioDados();
    $this->addDbParagrafo();
    $this->criarTabelas();
  }

  public function down()
  {
    $this->removerDicionarioDados();
    $this->removerDbParagrafo();
    $this->droparDML();
  }

  public function addDicionarioDados()
  {

    /**
     * Cria tabelas
     */
    $aColumns = array('codarq', 'nomearq', 'descricao', 'sigla', 'dataincl', 'rotulo', 'tipotabela', 'naolibclass', 'naolibfunc', 'naolibprog', 'naolibform');
    $aValues  = array(
      array(1010251, 'termotaxaparc', 'Guarda o vinculo da parcela com o taxa que sera aplicada.', 'ar29', '2017-12-28', 'Vinculo de parcela com taxas e custas', 0, 'f', 'f', 'f', 'f'),
    );
    $table    = $this->table('db_sysarquivo', array('schema' => 'configuracoes'));
    $table->insert($aColumns, $aValues);
    $table->saveData();

    // vincula modulo
    $aColumns = array('codmod', 'codarq' );
    $aValues  = array(
      /**
      *lista de campos
      */
      array(54,1010251)
    );
    $table    = $this->table('db_sysarqmod', array('schema' => 'configuracoes'));
    $table->insert($aColumns, $aValues);
    $table->saveData();

    /**
     * Cria campos
     */
    $aColumns = array('codcam', 'nomecam', 'conteudo', 'descricao', 'valorinicial', 'rotulo', 'tamanho', 'nulo', 'maiusculo', 'autocompl', 'aceitatipo', 'tipoobj', 'rotulorel');
    $aValues  = array(
        array(1009592,'ar29_sequencial','int4','Sequencial','0', 'Sequencial',10,'f','f','f',1,'text','Sequencial'),
        array(1009593,'ar29_numpar','int4','Parcela','0', 'Parcela',4,'f','f','f',1,'text','Parcela'),
        array(1009594,'ar29_taxa','int4','Taxa','0', 'Taxa',10,'f','f','f',1,'text','Taxa'),
        array(1009595,'ar29_instit','int4','Instituição','0', 'Instituição',10,'f','f','f',1,'text','Instituição'),
        array(1009597,'ar36_aplicajurosmulta','bool','Se a taxa aplica juros e multa','f', 'Aplica Juros e Multa',1,'f','f','f',5,'text','Aplica Juros e Multa')
    );
    $table    = $this->table('db_syscampo', array('schema' => 'configuracoes'));
    $table->insert($aColumns, $aValues);
    $table->saveData();

    /**
     * db_sysarqcamp
     */
    $aColumns = array('codarq', 'codcam', 'seqarq', 'codsequencia');
    $aValues  = array(
        array(1010251,1009592,1,0),
        array(1010251,1009593,2,0),
        array(1010251,1009594,3,0),
        array(1010251,1009595,4,0),
        array(3221, 1009597, 11,0)
    );
    $table    = $this->table('db_sysarqcamp', array('schema' => 'configuracoes'));
    $table->insert($aColumns, $aValues);
    $table->saveData();


    // inclui a sequence
    $aColumns = array('codsequencia', 'nomesequencia', 'incrseq', 'minvalueseq', 'maxvalueseq', 'startseq', 'cacheseq');
    $aValues  = array(
      array(1000711, 'termotaxaparc_ar29_sequencial_seq', 1, 1, 9223372036854775807, 1, 1),
    );
    $table    = $this->table('db_syssequencia', array('schema' => 'configuracoes'));
    $table->insert($aColumns, $aValues);
    $table->saveData();

    // inclui a chave primaria
    $aColumns = array('codarq','codcam','sequen','camiden');
    $aValues  = array(
      array(1010251,1009592,1,1009592),
    );
    $table    = $this->table('db_sysprikey', array('schema' => 'configuracoes'));
    $table->insert($aColumns, $aValues);
    $table->saveData();

    // inclui a chave estrangeira
    $aColumns = array('codarq', 'codcam', 'sequen', 'referen', 'tipoobjrel');
    $aValues  = array(
      array(1010251,1009594,1,3221,0)
    );
    $table    = $this->table('db_sysforkey', array('schema' => 'configuracoes'));
    $table->insert($aColumns, $aValues);
    $table->saveData();

    // inclui os indices
    $aColumns = array('codind', 'nomeind', 'codarq', 'campounico');
    $aValues  = array(
      array(1008246,'termotaxaparc_taxa_in',1010251,'0')
    );
    $table    = $this->table('db_sysindices', array('schema' => 'configuracoes'));
    $table->insert($aColumns, $aValues);
    $table->saveData();

    // vincula os indices
    $aColumns = array('codind', 'codcam', 'sequen');
    $aValues  = array(
      array(1008246,1009594,1)
    );
    $table    = $this->table('db_syscadind', array('schema' => 'configuracoes'));
    $table->insert($aColumns, $aValues);
    $table->saveData();

    $this->execute("update db_sysarqcamp set codsequencia = 1000711 where codarq = 1010251 and codcam = 1009592");
  }

  public function criarTabelas()
  {
    $this->execute("
        --DROP TABLE:
        DROP TABLE IF EXISTS arrecadacao.termotaxaparc CASCADE;

        --Criando drop sequences
        DROP SEQUENCE IF EXISTS termotaxaparc_ar29_sequencial_seq;

        -- Criando  sequences
        CREATE SEQUENCE termotaxaparc_ar29_sequencial_seq
        INCREMENT 1
        MINVALUE 1
        MAXVALUE 9223372036854775807
        START 1
        CACHE 1;


        -- TABELAS E ESTRUTURA

        -- Módulo: arrecadacao
        CREATE TABLE arrecadacao.termotaxaparc(
        ar29_sequencial		int4 NOT NULL default 0,
        ar29_numpar		int4 NOT NULL default 0,
        ar29_taxa		int4 NOT NULL default 0,
        ar29_instit		int4 default 0,
        CONSTRAINT termotaxaparc_sequ_pk PRIMARY KEY (ar29_sequencial));

        -- CHAVE ESTRANGEIRA

        ALTER TABLE arrecadacao.termotaxaparc
        ADD CONSTRAINT termotaxaparc_taxa_fk FOREIGN KEY (ar29_taxa)
        REFERENCES taxa;

        -- INDICE
        CREATE  INDEX termotaxaparc_taxa_in ON termotaxaparc(ar29_taxa);
    ");

    $this->execute("alter table arrecadacao.taxa add column ar36_aplicajurosmulta boolean default false;");
  }

  /**
   * Remove dados do dicionario de dados
   */
  private function removerDicionarioDados()
  {

    $this->execute('delete from configuracoes.db_syscampodef where codcam in(1009592, 1009593, 1009594, 1009595)');
    $this->execute('delete from configuracoes.db_syscadind where codind in(1008246)');
    $this->execute('delete from configuracoes.db_sysindices where codind in(1008246)');
    $this->execute('delete from configuracoes.db_sysforkey where codcam in(1009592, 1009593, 1009594, 1009595)');
    $this->execute('delete from configuracoes.db_syssequencia where codsequencia in(1000711)');
    $this->execute('delete from configuracoes.db_sysprikey where codarq in(1010251)');
    $this->execute('delete from configuracoes.db_sysarqcamp where codcam in(1009592, 1009593, 1009594, 1009595, 1009597)');
    $this->execute('delete from configuracoes.db_syscampo where codcam in(1009592, 1009593, 1009594, 1009595, 1009597)');
    $this->execute('delete from configuracoes.db_sysarqmod where codarq in(1010251)');
    $this->execute('delete from configuracoes.db_sysarquivo where codarq in(1010251)');
  }

  private function droparDML()
  {
    $this->execute('drop table if exists arrecadacao.termotaxaparc');
    $this->execute('alter table arrecadacao.taxa drop column ar36_aplicajurosmulta;');

  }

  private function addDbParagrafo()
  {
    $this->execute("insert into configuracoes.db_paragrafopadrao (db61_codparag, db61_descr, db61_texto, db61_alinha) values (nextval('db_paragrafopadrao_db61_codparag_seq'), 'tabela_custas', 'tabela_custas', 1)");
  }

  private function removerDbParagrafo()
  {
    $this->execute("delete from configuracoes.db_paragrafopadrao where db61_descr = 'tabela_custas' and db61_texto = 'tabela_custas'");
  }
}
