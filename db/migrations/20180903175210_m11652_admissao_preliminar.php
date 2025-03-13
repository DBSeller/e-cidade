<?php

use Classes\PostgresMigration;

class M11652AdmissaoPreliminar extends PostgresMigration
{
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
        $aColumns = array('codarq', 'nomearq', 'descricao', 'sigla', 'dataincl', 'rotulo', 'tipotabela', 'naolibclass', 'naolibfunc', 'naolibprog', 'naolibform');
        $aValues = array(
            array(1010314, 'avaliacaogruporespostaadmissaopreliminar', 'Guarda os dados de admissão preliminar do eSocial', 'eso18', '2018-09-03', 'avaliacaogruporespostaadmissaopreliminar', 0, 'f', 'f', 'f', 'f'),
        );
        $table = $this->table('db_sysarquivo', array('schema' => 'configuracoes'));
        $table->insert($aColumns, $aValues);
        $table->saveData();

        // vincula modulo
        $aColumns = array('codmod', 'codarq');
        $aValues = array(
            /**
             *lista de campos
             */
            array(81, 1010314)
        );
        $table = $this->table('db_sysarqmod', array('schema' => 'configuracoes'));
        $table->insert($aColumns, $aValues);
        $table->saveData();

        /**
         * Cria campos
         */
        $aColumns = array('codcam', 'nomecam', 'conteudo', 'descricao', 'valorinicial', 'rotulo', 'tamanho', 'nulo', 'maiusculo', 'autocompl', 'aceitatipo', 'tipoobj', 'rotulorel');
        $aValues = array(
            array(1009936, 'eso18_sequencial', 'int4', 'Sequencial', '0', 'Sequencial', 10, 'f', 'f', 'f', 1, 'text', 'Sequencial'),
            array(1009937, 'eso18_avaliacaogruporesposta', 'int4', 'Resposta', '0', 'Resposta', 10, 'f', 'f', 'f', 1, 'text', 'Resposta'),
            array(1009938, 'eso18_cgm', 'int4', 'CGM', '0', 'CGM', 10, 'f', 'f', 'f', 1, 'text', 'CGM'),
            array(1009939, 'eso18_cpf', 'varchar(11)', 'CPF', '', 'CPF', 11, 'f', 'f', 'f', 0, 'text', 'CPF')
        );
        $table = $this->table('db_syscampo', array('schema' => 'configuracoes'));
        $table->insert($aColumns, $aValues);
        $table->saveData();

        /**
         * db_sysarqcamp
         */
        $aColumns = array('codarq', 'codcam', 'seqarq', 'codsequencia');
        $aValues = array(
            array(1010314, 1009936, 1, 0),
            array(1010314, 1009937, 2, 0),
            array(1010314, 1009938, 3, 0),
            array(1010314, 1009939, 4, 0)

        );
        $table = $this->table('db_sysarqcamp', array('schema' => 'configuracoes'));
        $table->insert($aColumns, $aValues);
        $table->saveData();


        // inclui a sequence
        $aColumns = array('codsequencia', 'nomesequencia', 'incrseq', 'minvalueseq', 'maxvalueseq', 'startseq', 'cacheseq');
        $aValues = array(
            array(1000763, 'avaliacaogruporespostaadmissaopreliminar_eso18_sequencial_seq', 1, 1, 9223372036854775807, 1, 1),
        );
        $table = $this->table('db_syssequencia', array('schema' => 'configuracoes'));
        $table->insert($aColumns, $aValues);
        $table->saveData();

        // inclui a chave primaria
        $aColumns = array('codarq', 'codcam', 'sequen', 'camiden');
        $aValues = array(
            array(1010314, 1009936, 1, 1009936),
        );
        $table = $this->table('db_sysprikey', array('schema' => 'configuracoes'));
        $table->insert($aColumns, $aValues);
        $table->saveData();

        // inclui a chave estrangeira
        $aColumns = array('codarq', 'codcam', 'sequen', 'referen', 'tipoobjrel');
        $aValues = array(
            array(1010314, 1009937, 1, 2987, 0),
            array(1010314, 1009938, 1, 42, 0)
        );
        $table = $this->table('db_sysforkey', array('schema' => 'configuracoes'));
        $table->insert($aColumns, $aValues);
        $table->saveData();

        // inclui os indices
        $aColumns = array('codind', 'nomeind', 'codarq', 'campounico');
        $aValues = array(
            array(1008321, 'eso18_sequencial_eso03_avaliacaogruporesposta_in', 1010314, '0')
        );
        $table = $this->table('db_sysindices', array('schema' => 'configuracoes'));
        $table->insert($aColumns, $aValues);
        $table->saveData();

        // vincula os indices
        $aColumns = array('codind', 'codcam', 'sequen');
        $aValues = array(
            array(1008321, 1009936, 1)
        );
        $table = $this->table('db_syscadind', array('schema' => 'configuracoes'));
        $table->insert($aColumns, $aValues);
        $table->saveData();

        $this->execute("UPDATE db_sysarqcamp SET codsequencia = 1000763 WHERE codarq = 1010314 AND codcam = 1009936");
    }

    public function criarTabelas()
    {
        $sql = "
            -- Criando  sequences
            CREATE SEQUENCE avaliacaogruporespostaadmissaopreliminar_eso18_sequencial_seq
            INCREMENT 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1
            CACHE 1;
            
            
            -- TABELAS E ESTRUTURA
            
            -- Módulo: esocial
            CREATE TABLE avaliacaogruporespostaadmissaopreliminar(
            eso18_sequencial		int4 NOT NULL default 0,
            eso18_avaliacaogruporesposta		int4 NOT NULL default 0,
            eso18_cgm		int4 NOT NULL default 0,
            eso18_cpf		varchar(11) ,
            CONSTRAINT avaliacaogruporespostaadmissaopreliminar_sequ_pk PRIMARY KEY (eso18_sequencial));
            
            
            
            
            -- CHAVE ESTRANGEIRA
            
            
            ALTER TABLE avaliacaogruporespostaadmissaopreliminar
            ADD CONSTRAINT avaliacaogruporespostaadmissaopreliminar_avaliacaogruporesposta_fk FOREIGN KEY (eso18_avaliacaogruporesposta)
            REFERENCES avaliacaogruporesposta;
            
            ALTER TABLE avaliacaogruporespostaadmissaopreliminar
            ADD CONSTRAINT avaliacaogruporespostaadmissaopreliminar_cgm_fk FOREIGN KEY (eso18_cgm)
            REFERENCES cgm;
            
            
            
            
            -- INDICES
            
            
            CREATE  INDEX eso18_sequencial_eso03_avaliacaogruporesposta_in ON avaliacaogruporespostaadmissaopreliminar(eso18_sequencial);
        ";
        $this->execute($sql);
    }

    /**
     * Remove dados do dicionario de dados
     */
    private function removerDicionarioDados()
    {

        $this->execute('DELETE FROM configuracoes.db_syscampodef WHERE codcam IN(1009936, 1009937, 1009938, 1009939)');
        $this->execute('DELETE FROM configuracoes.db_syscadind WHERE codind IN(1008321)');
        $this->execute('DELETE FROM configuracoes.db_sysindices WHERE codind IN(1008321)');
        $this->execute('DELETE FROM configuracoes.db_sysforkey WHERE codcam IN(1009936, 1009937, 1009938, 1009939)');
        $this->execute('DELETE FROM configuracoes.db_syssequencia WHERE codsequencia IN(1000763)');
        $this->execute('DELETE FROM configuracoes.db_sysprikey WHERE codarq IN(1010314)');
        $this->execute('DELETE FROM configuracoes.db_sysarqcamp WHERE codcam IN(1009936, 1009937, 1009938, 1009939)');
        $this->execute('DELETE FROM configuracoes.db_syscampo WHERE codcam IN(1009936, 1009937, 1009938, 1009939)');
        $this->execute('DELETE FROM configuracoes.db_sysarqmod WHERE codarq IN(1010314)');
        $this->execute('DELETE FROM configuracoes.db_sysarquivo WHERE codarq IN(1010314)');
    }

    private function droparDML()
    {
        $sql = "
            --Criando drop sequences
            DROP SEQUENCE IF EXISTS avaliacaogruporespostaadmissaopreliminar_eso18_sequencial_seq;
            --DROP TABLE:
            DROP TABLE IF EXISTS avaliacaogruporespostaadmissaopreliminar CASCADE;
        ";
        $this->execute($sql);
    }

}
