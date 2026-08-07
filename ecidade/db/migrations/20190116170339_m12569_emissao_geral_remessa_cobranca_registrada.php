<?php

use Classes\PostgresMigration;

class M12569EmissaoGeralRemessaCobrancaRegistrada extends PostgresMigration
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
        $this->dicionarioUp();
        $this->alterTableUp();
    }

    public function down()
    {
        $this->dicionarioDown();
        $this->alterTableDown();
    }

    protected function alterTableUp(){
        $sql = "ALTER TABLE remessacobrancaregistrada ";
        $sql .= "ADD COLUMN k147_tiposemissao VARCHAR ";

        $this->execute($sql);
    }

    protected function alterTableDown(){
        $sql =  "ALTER TABLE remessacobrancaregistrada ";
        $sql .= "DROP COLUMN k147_tiposemissao         ";

        $this->execute($sql);
    }

    protected function dicionarioUp(){
        $sql = "insert into db_syscampo values(1010307,'k147_tiposemissao','varchar(1)','Campo que guarda os tipos de débito emitidos na remessa','', '',1,'f','t','f',0,'text','');";
        $sql .= "delete from db_sysarqcamp where codcam = 1010307;  ";
        $sql .= "insert into db_sysarqcamp values(3981,1010307,8,0);";

        $this->execute($sql);
    }

    protected function dicionarioDown(){        
        $sql  = "DELETE FROM db_sysarqcamp where codcam = 1010307;";
        $sql .= "DELETE FROM db_syscampo where codcam = 1010307  ;";

        $this->execute($sql);   
    }
}
