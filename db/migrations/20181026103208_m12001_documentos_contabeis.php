<?php

use Classes\PostgresMigration;

class M12001DocumentosContabeis extends PostgresMigration
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
        $this->execute("insert into conhistdoc values (6000,'RETENÇÃO DE TRIBUTOS', 100);");
        $this->execute("insert into conhistdoc values (6001,'ESTORNO DE RETENÇÃO DE TRIBUTOS', 101);");
        $this->execute("insert into conhistdoc values (6002,'RETENÇÃO DE VALORES CONSIGNADOS', 30);");
        $this->execute("insert into conhistdoc values (6003,'ESTORNO DE RETENÇÃO DE VALORES CONSIGNADOS', 31);");
        $this->execute("insert into conhistdoc values (6004,'BAIXA DE RETENÇÃO APROPRIADA', 30);");
        $this->execute("insert into conhistdoc values (6005,'ESTORNO DA BAIXA DE RETENÇÃO APROPRIADA', 31);");
        $this->execute("insert into conhistdoc values (6006,'RECONHECIMENTO DE TRIBUTOS RETIDOS OUTRA ENTIDADE', 150);");
        $this->execute("insert into conhistdoc values (6007, 'ESTORNO DE RECONHECIMENTO DE TRIBUTOS RETIDOS OUTRA ENTIDADE', 151);");
        $this->execute("insert into conhistdoc values (6008, 'RETENÇÃO DE VALORES CONSIGNADOS - RPP', 30);");
        $this->execute("insert into conhistdoc values (6009, 'ESTORNO RETENÇÃO DE VALORES CONSIGNADOS - RPP', 31);");
        $this->execute("insert into conhistdoc values (6010, 'RETENÇÃO DE VALORES CONSIGNADOS - RPNP', 30);");
        $this->execute("insert into conhistdoc values (6011,'ESTORNO RETENÇÃO DE VALORES CONSIGNADOS - RPNP', 31);");
        $this->execute("insert into conhistdoc values (6012,'BAIXA POR INGRESSO DE TRIBUTO RETIDO', 152);");
        $this->execute("insert into conhistdoc values (6013,'ESTORNO DA BAIXA POR INGRESSO DE TRIBUTO RETIDO', 153);");

        $this->execute("insert into vinculoeventoscontabeis values (nextval('vinculoeventoscontabeis_c115_sequencial_seq'), 6000, 6001);");
        $this->execute("insert into vinculoeventoscontabeis values (nextval('vinculoeventoscontabeis_c115_sequencial_seq'), 6002, 6003);");
        $this->execute("insert into vinculoeventoscontabeis values (nextval('vinculoeventoscontabeis_c115_sequencial_seq'), 6004, 6005);");
        $this->execute("insert into vinculoeventoscontabeis values (nextval('vinculoeventoscontabeis_c115_sequencial_seq'), 6006, 6007);");
        $this->execute("insert into vinculoeventoscontabeis values (nextval('vinculoeventoscontabeis_c115_sequencial_seq'), 6008, 6009);");
        $this->execute("insert into vinculoeventoscontabeis values (nextval('vinculoeventoscontabeis_c115_sequencial_seq'), 6010, 6011);");
        $this->execute("insert into vinculoeventoscontabeis values (nextval('vinculoeventoscontabeis_c115_sequencial_seq'), 6012, 6013);");
        $this->execute("insert into corgrupotipo values (7, 'Autenticação Recibo Apropriação - Repasse')");
        $this->execute("insert into corgrupotipo values (8, 'Extorno da Autenticação Recibo Apropriação')");
    }

    public function down()
    {
        $this->execute("Delete from vinculoeventoscontabeis where c115_conhistdocinclusao between 6000 and 6013");
        $this->execute("Delete from conhistdoc where c53_coddoc between 6000 and 6013");
        $this->execute("Delete from corgrupotipo where k106_sequencial in(7,8)");
        
    }
}