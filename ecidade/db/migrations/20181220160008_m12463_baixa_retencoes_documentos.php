<?php

use Classes\PostgresMigration;

class M12463BaixaRetencoesDocumentos extends PostgresMigration
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

        $this->execute("insert into corgrupotipo values (9, 'Autenticacao de Slip');");
        $this->execute("insert into corgrupotipo values (10, ' estorno da autenticacao de Slip')");
        $this->execute("update conhistdoc set c53_tipo = 100, c53_descr = 'BAIXA POR INGRESSO DE TRIBUTO RETIDO' where c53_coddoc = 6006");
        $this->execute("update conhistdoc set c53_tipo = 101, c53_descr = 'ESTORNO DA BAIXA POR INGRESSO DE TRIBUTO RETIDO' where c53_coddoc = 6007");
        $this->execute("update conhistdoc set c53_descr = 'RECONHECIMENTO DE TRIBUTOS RETIDOS OUTRA ENTIDADE' where c53_coddoc = 6012");
        $this->execute("update conhistdoc set c53_descr = 'ESTORNO DE RECONHECIMENTO DE TRIBUTOS RETIDOS OUTRA ENTIDADE' where c53_coddoc = 6013");
    }

    public function down()
    {
        $this->execute("  delete from  corgrupotipo where k106_sequencial in (9, 10)");
    }
}
