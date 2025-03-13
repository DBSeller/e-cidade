<?php

use Classes\PostgresMigration;

class M10500ReginAlteracoes extends PostgresMigration
{
  public function up()
  {
    $this->addDicionarioDados();
    $this->criarTabelas();
  }

  public function down() {
    $this->removerDicionarioDados();
    $this->droparDML();
  }

  public function addDicionarioDados()
  {
    /**
     * Cria tabelas
     */
    $aColumns = array('codarq', 'nomearq', 'descricao', 'sigla', 'dataincl', 'rotulo', 'tipotabela', 'naolibclass', 'naolibfunc', 'naolibprog', 'naolibform');
    $aValues  = array(
      array(1010277, 'juntacomercialprotocoloretorno', 'Tabela que salva o retorno do processamento das requisições do Regin', 'q149', '2018-04-18', 'Tabela de retornos de requisicoes do Regin', 0, 'f', 'f', 'f', 'f'),
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
      array(3,1010277)
    );
    $table    = $this->table('db_sysarqmod', array('schema' => 'configuracoes'));
    $table->insert($aColumns, $aValues);
    $table->saveData();

    /**
     * Cria campos
     */
    $aColumns = array('codcam', 'nomecam', 'conteudo', 'descricao', 'valorinicial', 'rotulo', 'tamanho', 'nulo', 'maiusculo', 'autocompl', 'aceitatipo', 'tipoobj', 'rotulorel');
    $aValues  = array(
        array(1009713,'q149_sequencial','int4','Sequencial','0', 'Sequencial',10,'f','f','f',1,'text','Sequencial'),
        array(1009714,'q149_juntacomercialprotocolo','int4','Protocolo','0', 'Protocolo',10,'f','f','f',1,'text','Protocolo'),
        array(1009715,'q149_xml','text','XML','', 'XML',1,'f','f','f',0,'text','XML')
    );
    $table    = $this->table('db_syscampo', array('schema' => 'configuracoes'));
    $table->insert($aColumns, $aValues);
    $table->saveData();

    /**
     * db_sysarqcamp
     */
    $aColumns = array('codarq', 'codcam', 'seqarq', 'codsequencia');
    $aValues  = array(
        array(1010277,1009713,1,0),
        array(1010277,1009714,2,0),
        array(1010277,1009715,3,0),
    );
    $table    = $this->table('db_sysarqcamp', array('schema' => 'configuracoes'));
    $table->insert($aColumns, $aValues);
    $table->saveData();


    // inclui a sequence
    $aColumns = array('codsequencia', 'nomesequencia', 'incrseq', 'minvalueseq', 'maxvalueseq', 'startseq', 'cacheseq');
    $aValues  = array(
      array(1000729, 'juntacomercialprotocoloretorno_q149_sequencial_seq', 1, 1, 9223372036854775807, 1, 1),
    );
    $table    = $this->table('db_syssequencia', array('schema' => 'configuracoes'));
    $table->insert($aColumns, $aValues);
    $table->saveData();

    // inclui a chave primaria
    $aColumns = array('codarq','codcam','sequen','camiden');
    $aValues  = array(
      array(1010277,1009713,1,1009713),
    );
    $table    = $this->table('db_sysprikey', array('schema' => 'configuracoes'));
    $table->insert($aColumns, $aValues);
    $table->saveData();

    // inclui a chave estrangeira
    $aColumns = array('codarq', 'codcam', 'sequen', 'referen', 'tipoobjrel');
    $aValues  = array(
      array(1010277,1009714,1,1010222,0)
    );
    $table    = $this->table('db_sysforkey', array('schema' => 'configuracoes'));
    $table->insert($aColumns, $aValues);
    $table->saveData();

    // inclui os indices
    $aColumns = array('codind', 'nomeind', 'codarq', 'campounico');
    $aValues  = array(
      array(1008274,'q149_sequencial_in',1010277,'0')

    );
    $table    = $this->table('db_sysindices', array('schema' => 'configuracoes'));
    $table->insert($aColumns, $aValues);
    $table->saveData();

    // vincula os indices
    $aColumns = array('codind', 'codcam', 'sequen');
    $aValues  = array(
      array(1008274,1009713,1),
    );
    $table    = $this->table('db_syscadind', array('schema' => 'configuracoes'));
    $table->insert($aColumns, $aValues);
    $table->saveData();

    $this->execute("update db_sysarqcamp set codsequencia = 1000729 where codarq = 1010277 and codcam = 1009713;");
  }

  public function criarTabelas() {
      $sSql = "
        -- Criando  sequences
        CREATE SEQUENCE juntacomercialprotocoloretorno_q149_sequencial_seq
        INCREMENT 1
        MINVALUE 1
        MAXVALUE 9223372036854775807
        START 1
        CACHE 1;
        -- TABELAS E ESTRUTURA
        -- Módulo: issqn
        CREATE TABLE juntacomercialprotocoloretorno(
        q149_sequencial		int4 NOT NULL default 0,
        q149_juntacomercialprotocolo		int4 NOT NULL default 0,
        q149_xml		text ,
        CONSTRAINT juntacomercialprotocoloretorno_sequ_pk PRIMARY KEY (q149_sequencial));
        -- CHAVE ESTRANGEIRA
        ALTER TABLE juntacomercialprotocoloretorno
        ADD CONSTRAINT juntacomercialprotocoloretorno_juntacomercialprotocolo_fk FOREIGN KEY (q149_juntacomercialprotocolo)
        REFERENCES juntacomercialprotocolo;
        -- INDICES
        CREATE  INDEX q149_sequencial_in ON juntacomercialprotocoloretorno(q149_sequencial);
      ";

      $this->execute($sSql);
  }

  /**
   * Remove dados do dicionario de dados
   */
  private function removerDicionarioDados()
  {
    $this->execute('delete from configuracoes.db_syscampodef where codcam in(1009713, 1009714, 1009715)');
    $this->execute('delete from configuracoes.db_syscadind where codind in(1008274)');
    $this->execute('delete from configuracoes.db_sysindices where codind in(1008274)');
    $this->execute('delete from configuracoes.db_sysforkey where codcam in(1009713, 1009714, 1009715)');
    $this->execute('delete from configuracoes.db_syssequencia where codsequencia in(1000729)');
    $this->execute('delete from configuracoes.db_sysprikey where codarq in(1010277)');
    $this->execute('delete from configuracoes.db_sysarqcamp where codcam in(1009713, 1009714, 1009715)');
    $this->execute('delete from configuracoes.db_syscampo where codcam in(1009713, 1009714, 1009715)');
    $this->execute('delete from configuracoes.db_sysarqmod where codarq in(1010277)');
    $this->execute('delete from configuracoes.db_sysarquivo where codarq in(1010277)');
  }

  private function droparDML()
  {
    $this->execute("DROP TABLE IF EXISTS juntacomercialprotocoloretorno CASCADE;");
    $this->execute("DROP SEQUENCE IF EXISTS juntacomercialprotocoloretorno_q149_sequencial_seq;");
  }

}
