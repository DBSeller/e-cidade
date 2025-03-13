<?php

use Classes\PostgresMigration;

class M7030GeracaoListaCorte extends PostgresMigration
{

    public function up()
    {
        $this->upAguaCorteMat();
        $this->upDicionarioDados();
        $this->migraDados();
    }

    public function upAguaCorteMat()
    {
        //Altera tabela aguacortemat
        $table = $this->table('aguacortemat', array('schema'=>'agua'));
        $table->addColumn('x41_aguacontrato', 'integer', array('null' => true, 'limit' => 10))
              ->addForeignKey('x41_aguacontrato', 'agua.aguacontrato', 'x54_sequencial', array('constraint' => 'aguacortemat_aguacontrato_fk'))
              ->addIndex(array('x41_aguacontrato'), array('unique' => false, 'name'=> 'aguacortemat_aguacontrato_in'))
              ->save();
    }

    public function upDicionarioDados()
    {
        $this->execute("INSERT INTO db_syscampo VALUES(1009465,'x41_aguacontrato','int4','Código do contrato','null', 'Contrato',10,'t','f','f',1,'text','Contrato');");
        $this->execute("DELETE FROM db_sysarqcamp WHERE codarq = 1454;");
        $this->execute("INSERT INTO db_sysarqcamp VALUES(1454,8545,1,429);");
        $this->execute("INSERT INTO db_sysarqcamp VALUES(1454,8546,2,0);");
        $this->execute("INSERT INTO db_sysarqcamp VALUES(1454,8547,3,0);");
        $this->execute("INSERT INTO db_sysarqcamp VALUES(1454,8548,4,0);");
        $this->execute("INSERT INTO db_sysarqcamp VALUES(1454,1009465,5,0);");
        $this->execute("DELETE FROM db_sysforkey WHERE codarq = 1454 AND referen = 0;");
        $this->execute("INSERT INTO db_sysforkey VALUES(1454,1009465,1,3966,0);");
        $this->execute("INSERT INTO db_sysindices VALUES(1008226,'aguacortemat_aguacontrato_in',1454,'0');");
        $this->execute("INSERT INTO db_syscadind VALUES(1008226,1009465,1);");

        $this->execute("UPDATE db_syscampo SET descricao = 'Código do Contrato', rotulo = 'Contrato', rotulorel = 'Contrato' where codcam = 22031;");
    }

    public function down()
    {
        $this->downDicionarioDados();
        $this->downAguaCorteMat();
    }

    public function downAguaCorteMat()
    {
        //Altera tabela aguacortemat
        $table = $this->table('aguacortemat', array('schema'=>'agua'));
        $table->removeColumn('x41_aguacontrato')
              ->save();
    }

    public function downDicionarioDados()
    {
        $this->execute("DELETE FROM db_sysarqcamp WHERE codarq = 1454    AND codcam = 1009465;");
        $this->execute("DELETE FROM db_sysforkey  WHERE codarq = 1454    AND codcam = 1009465;");
        $this->execute("DELETE FROM db_syscadind  WHERE codind = 1008226 AND codcam = 1009465;");
        $this->execute("DELETE FROM db_sysindices WHERE codind = 1008226;");
        $this->execute("DELETE FROM db_syscampo   WHERE codcam = 1009465;");
        $this->execute("UPDATE db_syscampo SET descricao = 'Código do Contrato', rotulo = 'Código', rotulorel = 'Código' where codcam = 22031;");
    }

    public function migraDados()
    {
        $this->execute("UPDATE agua.aguacortemat
                           SET x41_aguacontrato = x54_sequencial
                          FROM agua.aguacontrato
                         WHERE x54_aguabase = x41_matric;");
    }
}
