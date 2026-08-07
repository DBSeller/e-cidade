<?php

use Classes\PostgresMigration;

class M11471FeedbackLoaPlano extends PostgresMigration
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
            array(1010303, 'previsaodespesaplano', 'Tabela que armazena os registros de planos orçamentários da despesa.', 'c55', '2018-08-08', 'previsaodespesaplano', 0, 'f', 'f', 'f', 'f'),
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
            array(32, 1010303)
        );
        $table = $this->table('db_sysarqmod', array('schema' => 'configuracoes'));
        $table->insert($aColumns, $aValues);
        $table->saveData();

        /**
         * Cria campos
         */
        $aColumns = array('codcam', 'nomecam', 'conteudo', 'descricao', 'valorinicial', 'rotulo', 'tamanho', 'nulo', 'maiusculo', 'autocompl', 'aceitatipo', 'tipoobj', 'rotulorel');
        $aValues = array(
            array(1009881, 'c55_sequencial', 'int4', 'Sequencial', '0', 'Sequencial', 10, 'f', 'f', 'f', 1, 'text', 'Sequencial'),
            array(1009882, 'c55_previsaodespesa', 'int4', 'Previsão despesa', '0', 'Previsão despesa', 10, 'f', 'f', 'f', 1, 'text', 'Previsão despesa'),
            array(1009883, 'c55_titulo', 'varchar(100)', 'Título', '', 'Título', 100, 'f', 'f', 'f', 0, 'text', 'Título'),
            array(1009884, 'c55_valor', 'float8', 'Valor', '0', 'Valor', 15, 'f', 'f', 'f', 4, 'text', 'Valor')
        );
        $table = $this->table('db_syscampo', array('schema' => 'configuracoes'));
        $table->insert($aColumns, $aValues);
        $table->saveData();

        /**
         * db_sysarqcamp
         */
        $aColumns = array('codarq', 'codcam', 'seqarq', 'codsequencia');
        $aValues = array(
            array(1010303, 1009881, 1, 0),
            array(1010303, 1009882, 2, 0),
            array(1010303, 1009883, 3, 0),
            array(1010303, 1009884, 4, 0)

        );
        $table = $this->table('db_sysarqcamp', array('schema' => 'configuracoes'));
        $table->insert($aColumns, $aValues);
        $table->saveData();


        // inclui a sequence
        $aColumns = array('codsequencia', 'nomesequencia', 'incrseq', 'minvalueseq', 'maxvalueseq', 'startseq', 'cacheseq');
        $aValues = array(
            array(1000753, 'previsaodespesaplano_c55_sequencial_seq', 1, 1, 9223372036854775807, 1, 1)
        );
        $table = $this->table('db_syssequencia', array('schema' => 'configuracoes'));
        $table->insert($aColumns, $aValues);
        $table->saveData();

        // inclui a chave primaria
        $aColumns = array('codarq', 'codcam', 'sequen', 'camiden');
        $aValues = array(
            array(1010303, 1009881, 1, 1009881),
        );
        $table = $this->table('db_sysprikey', array('schema' => 'configuracoes'));
        $table->insert($aColumns, $aValues);
        $table->saveData();

        // inclui a chave estrangeira
        $aColumns = array('codarq', 'codcam', 'sequen', 'referen', 'tipoobjrel');
        $aValues = array(
            array(1010303, 1009882, 1, 1010295, 0)
        );
        $table = $this->table('db_sysforkey', array('schema' => 'configuracoes'));
        $table->insert($aColumns, $aValues);
        $table->saveData();

        // inclui os indices
        $aColumns = array('codind', 'nomeind', 'codarq', 'campounico');
        $aValues = array(
            array(1008311, 'previsaodespesaplano_c55_sequencial_in', 1010303, '0'),

        );
        $table = $this->table('db_sysindices', array('schema' => 'configuracoes'));
        $table->insert($aColumns, $aValues);
        $table->saveData();

        // vincula os indices
        $aColumns = array('codind', 'codcam', 'sequen');
        $aValues = array(
            array(1008311, 1009881, 1),
        );
        $table = $this->table('db_syscadind', array('schema' => 'configuracoes'));
        $table->insert($aColumns, $aValues);
        $table->saveData();

        $this->execute("UPDATE db_sysarqcamp SET codsequencia = 1000753 WHERE codarq = 1010303 AND codcam = 1009881");
    }

    public function criarTabelas()
    {
        $sql = "
            CREATE SEQUENCE previsaodespesaplano_c55_sequencial_seq
            INCREMENT 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1
            CACHE 1;
            
            CREATE TABLE previsaodespesaplano(
            c55_sequencial		int4 NOT NULL default 0,
            c55_previsaodespesa		int4 NOT NULL default 0,
            c55_titulo		varchar(100) NOT NULL ,
            c55_valor		float8 default 0,
            CONSTRAINT previsaodespesaplano_sequ_pk PRIMARY KEY (c55_sequencial));
            
            ALTER TABLE previsaodespesaplano
            ADD CONSTRAINT previsaodespesaplano_previsaodespesa_fk FOREIGN KEY (c55_previsaodespesa)
            REFERENCES previsaodespesa;

            CREATE  INDEX previsaodespesaplano_c55_sequencial_in ON previsaodespesaplano(c55_sequencial);
        ";
        $this->execute($sql);
    }

    /**
     * Remove dados do dicionario de dados
     */
    private function removerDicionarioDados()
    {
        $this->execute('DELETE FROM configuracoes.db_syscampodef WHERE codcam IN(1009881, 1009882, 1009883, 1009884)');
        $this->execute('DELETE FROM configuracoes.db_syscadind WHERE codind IN(1008311)');
        $this->execute('DELETE FROM configuracoes.db_sysindices WHERE codind IN(1008311)');
        $this->execute('DELETE FROM configuracoes.db_sysforkey WHERE codcam IN(1009881, 1009882, 1009883, 1009884)');
        $this->execute('DELETE FROM configuracoes.db_syssequencia WHERE codsequencia IN(1000753)');
        $this->execute('DELETE FROM configuracoes.db_sysprikey WHERE codarq IN(1010303)');
        $this->execute('DELETE FROM configuracoes.db_sysarqcamp WHERE codcam IN(1009881, 1009882, 1009883, 1009884)');
        $this->execute('DELETE FROM configuracoes.db_syscampo WHERE codcam IN(1009881, 1009882, 1009883, 1009884)');
        $this->execute('DELETE FROM configuracoes.db_sysarqmod WHERE codarq IN(1010303)');
        $this->execute('DELETE FROM configuracoes.db_sysarquivo WHERE codarq IN(1010303)');
    }

    private function droparDML()
    {
        $sql = "
            DROP SEQUENCE IF EXISTS previsaodespesaplano_c55_sequencial_seq;
            DROP TABLE IF EXISTS previsaodespesaplano CASCADE;
        ";
        $this->execute($sql);

    }

}
