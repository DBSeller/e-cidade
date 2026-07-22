<?php

use Classes\PostgresMigration;

class M13589Anexo2 extends PostgresMigration
{
    /**
     * doUp Method.
     *
     * Write your UP migrations using this method.
     *
     * This provide callbacks to be executed after or before a migration,
     * this is possible by implementation of callbackBeforeUp and 
     * callbackAfterUp methods on this class. You can also implement the
     * up methods this is overwrite the parent method and the callback 
     * funcionality will be not available.
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function up()
    {
	$this->execute("
            UPDATE orcparamseqcoluna
               SET o115_formula = '#saldo_anterior'
             WHERE o115_relatorio = 198
               AND o115_sequencial = 364;
        ");
    }

    /**
     * doDown Method.
     *
     * Write your DOWN migrations using this method.
     *
     * This provide callbacks to be executed after or before a migration,
     * this is possible by implementation of callbackBeforeDown and 
     * callbackAfterDown methods on this class. You can also implement the
     * down method, this is overwrite the parent and the callback funcionality 
     * will be not available.
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function down()
    {
    }
}
