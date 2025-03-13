<?php

use Classes\PostgresMigration;

class M17759RegrasParcelamentoAjustaVlrmindebVlrmaxdeb extends PostgresMigration
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
    public function change()
    {

        $this->execute(<<<SQL


	update divida.tipoparc set vlrmax = 99999999.99, vlrmindeb = 1, vlrmaxdeb = 99999999.99
	  from (
		 select cadtipoparc as cod_tipo
                   from tipoparc inner join cadtipoparc on k40_codigo = cadtipoparc 
                  where k40_dtfim >= current_date

	       ) sq
         where sq.cod_tipo = cadtipoparc;

SQL
	);
    }
}
