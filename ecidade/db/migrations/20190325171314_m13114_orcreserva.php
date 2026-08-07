<?php

use Classes\PostgresMigration;

class M13114Orcreserva extends PostgresMigration
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
      $this->execute("insert into db_syscampo values(1010375,'o80_planoorcamentariolinhapacto','int4','Linha de Pacto','0', 'Linha de Pacto',10,'t','f','f',1,'text','Linha de Pacto');");
      $this->execute("insert into db_sysarqcamp values(788,1010375,9,0);");
      $this->execute("insert into db_sysforkey values(788,1010375,1,1010346,0);");
      $this->execute("insert into db_sysindices values(1008437,'orcreserva_linhapacto_in',788,'0');");
      $this->execute("insert into db_syscadind values(1008437,1010375,1);");


      $this->execute("alter table orcreserva add o80_planoorcamentariolinhapacto integer");
      $this->execute("alter table orcreserva add constraint orcreserva_planoorcamentariolinhapacto foreign key (o80_planoorcamentariolinhapacto) references planoorcamentariolinhapacto(o156_sequencial)");
      $this->execute("create index orcreserva_linhapacto_in on orcreserva (o80_planoorcamentariolinhapacto)");
    }

    public function down()
    {
        $this->execute("alter table orcreserva drop o80_planoorcamentariolinhapacto");
        $this->execute("delete from db_syscadind where codind in(1008437)");
        $this->execute("delete from db_sysindices where codind in(1008437)");
        $this->execute("delete from db_sysforkey where codcam in(1010375)");
        $this->execute("delete from db_sysarqcamp where codcam in(1010375)");
        $this->execute("delete from db_syscampo where codcam in(1010375)");

    }
}

