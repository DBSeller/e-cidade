<?php

use Classes\PostgresMigration;

class M10908Arreauto extends PostgresMigration
{
    public function up()
    {
        $this->table('arreauto',
          array('schema' => 'caixa', 'id' => false))
          ->addColumn('k00_numpre', 'integer', array('null' => true))
          ->addColumn('k00_auto', 'integer', array('null' => true))
          ->addIndex(array('k00_numpre', 'k00_auto'), array('name' => 'arreauto_auto_numpre_in'))
          ->create();
    }

    public function down()
    {
        $this->table('arreauto', array('schema' => 'caixa'))->drop();
    }
}
