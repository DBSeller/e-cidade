<?php

use Classes\PostgresMigration;

class M12258ContaCorrenteDomba extends PostgresMigration
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

        $this->execute("update conplanoinfocomplementar set c121_sql = 'select db89_db_bancos from conplanocontabancaria inner join contabancaria on db83_sequencial = c56_contabancaria inner join bancoagencia on db89_sequencial = db83_bancoagencia where c56_reduz = conta_reduzida and c56_anousu = anousu' where c121_nomepropriedade = 'codigo_banco'");
        $this->execute("update conplanoinfocomplementar set c121_sql = 'select db89_codagencia::varchar||\'-\'||db89_digito::varchar from conplanocontabancaria inner join contabancaria on db83_sequencial = c56_contabancaria inner join bancoagencia on db89_sequencial = db83_bancoagencia where c56_reduz = conta_reduzida and c56_anousu = anousu' where c121_nomepropriedade = 'codigo_agencia'");
        $this->execute("update conplanoinfocomplementar set c121_sql = 'select db83_conta::varchar||\'-\'||db83_dvconta::varchar from conplanocontabancaria inner join contabancaria on db83_sequencial = c56_contabancaria where c56_reduz = conta_reduzida and c56_anousu = anousu' where c121_nomepropriedade = 'codigo_conta_corrente'");
        $this->execute("update conplanoinfocomplementar set c121_sql = 'select db83_identificador::varchar from conplanocontabancaria inner join contabancaria on db83_sequencial = c56_contabancaria where c56_reduz = conta_reduzida and c56_anousu = anousu' where c121_nomepropriedade = 'cnpj_conta_corrente'");
    }

    public function down()
    {

        $this->execute("update conplanoinfocomplementar set c121_sql = 'select null' where c121_nomepropriedade = 'codigo_banco'");
        $this->execute("update conplanoinfocomplementar set c121_sql = 'select null' where c121_nomepropriedade = 'codigo_agencia'");
        $this->execute("update conplanoinfocomplementar set c121_sql = 'select null' where c121_nomepropriedade = 'codigo_conta_corrente'");
        $this->execute("update conplanoinfocomplementar set c121_sql = 'select null' where c121_nomepropriedade = 'cnpj_conta_corrente'");

    }
}
