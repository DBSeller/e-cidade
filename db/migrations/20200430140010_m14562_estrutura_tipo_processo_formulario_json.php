<?php

use Classes\PostgresMigration;

class M14562EstruturaTipoProcessoFormularioJson extends PostgresMigration
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
     *    addCustomColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Any other destructive changes will result in an error when trying to
     * rollback the migration.
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */

    public function up()
    {
        $this->adicionaDicionario();
        $this->adicionaTabela();
    }

    public function adicionaTabela()
    {

        $sql = <<<SQL
                    CREATE SEQUENCE protocolo.tipoprocessoformulario_p108_sequencial_seq
                    INCREMENT 1
                    MINVALUE 1
                    MAXVALUE 9223372036854775807
                    START 1
                    CACHE 1;

                    CREATE TABLE protocolo.tipoprocessoformulario(
                    p108_sequencial        int8 default 0,
                    p108_formulario         jsonb not null,
                    p108_tipoproc          int8 not null,
                    CONSTRAINT tipoprocessoformulario_sequ_pk PRIMARY KEY (p108_sequencial));

                    ALTER TABLE protocolo.tipoprocessoformulario
                    ADD CONSTRAINT tipoprocessoformulario_tipoproc_fk FOREIGN KEY (p108_tipoproc)
                    REFERENCES protocolo.tipoproc;
SQL;

        $this->execute($sql);
    }

    public function adicionaDicionario ()
    {
        $sql = <<<SQL
            insert into db_sysarquivo values (1010558, 'tipoprocessoformulario', 'tabela que guarda o json contendo a estrutura do atendimento para esse tipo de processo', 'p108', '2020-04-30', 'formulário tipo processo', 0, 'f', 'f', 'f', 'f' );
            insert into db_sysarqmod values (4,1010558);

            insert into db_syscampo values(1011245,'p108_sequencial','int8','sequencial da tabela','0', 'sequencial',1,'f','f','f',1,'text','sequencial');
            insert into db_syscampo values(1011246,'p108_formulario','text','formulario do tipo de processo','', 'formulario',1,'f','t','f',0,'text','formulario');
            insert into db_syscampo values(1011247,'p108_tipoproc','int8','tipo do processo do formulario','0', 'tipo processo',1,'f','f','f',1,'text','tipo processo');

            delete from db_sysarqcamp where codarq = 1010558;
            insert into db_sysarqcamp values(1010558,1011245,1,0);
            insert into db_sysarqcamp values(1010558,1011246,2,0);
            insert into db_sysarqcamp values(1010558,1011247,3,0);
            delete from db_sysprikey where codarq = 1010558;
            insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010558,1011245,1,1011245);

SQL;
        $this->execute($sql);
    }

    public function down()
    {
        $this->removeDicionario();
        $this->removeTabela();
    }

    public function removeDicionario()
    {
        $sql = <<<SQL
            delete from db_sysprikey where codarq = 1010558;
            delete from db_sysarqcamp where codarq in (1010558);
            delete from db_syscampo where codcam in (1011245, 1011246, 1011247);
            delete from db_sysarqmod where codarq = 1010558;
            delete from db_sysarquivo where codarq = 1010558;
SQL;

        $this->execute($sql);
    }

    public function removeTabela()
    {
        $sql = <<<SQL
            DROP TABLE protocolo.tipoprocessoformulario;
            DROP SEQUENCE protocolo.tipoprocessoformulario_p108_sequencial_seq;
SQL;
        $this->execute($sql);
    }
}
