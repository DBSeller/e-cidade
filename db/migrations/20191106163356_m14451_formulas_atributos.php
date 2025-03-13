<?php

use Classes\PostgresMigration;

class M14451FormulasAtributos extends PostgresMigration
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
        $this->upDicionarioDados();
        $this->upEstruturaFormula();
    }

    public function down()
    {
        $this->downDicionarioDados();
        $this->downEstruturaFormula();
    }

    public function upDicionarioDados()
    {
        $this->execute("

          insert into db_syscampo values(1010782,'la25_sigla','varchar(5)','Sigla para nome de atributos dos exames','', 'Sigla',5,'t','t','f',0,'text','Sigla');
          insert into db_syscampo values(1010783,'la25_formula','varchar(100)','Fórmula para calcular resultados de atributos de exames','', 'Fórmula',100,'t','t','f',0,'text','Fórmula');
          insert into db_sysarqcamp values(2899,1010782,6,0);
          insert into db_sysarqcamp values(2899,1010783,7,0);
");
    }

    public function downDicionarioDados()
    {
        $this->execute("

            delete from db_sysarqcamp where codarq = 2899 and codcam in (1010782,1010783);
            delete from db_syscampo where codcam in (1010782,1010783);

");
    }

    public function upEstruturaFormula()
    {
        $this->execute("
            ALTER TABLE laboratorio.lab_atributo 
                ADD COLUMN la25_sigla varchar(5),
                ADD COLUMN la25_formula varchar(100);

");
    }

    public function downEstruturaFormula()
    {
        $this->execute("
            ALTER TABLE laboratorio.lab_atributo 
                DROP COLUMN lb25_sigla,
                DROP COLUMN la25_formula;
");
    }
}
