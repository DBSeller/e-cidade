<?php

use Classes\PostgresMigration;

class M17143AcertoBase extends PostgresMigration
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
    public function up()
    {
      $this->execute("delete from contabilidade.conhistdocregra 
                       where c92_anousu = 2020 
                         and c92_conhistdoc in (402, 403) 
                         and c92_regra not ilike '%matestoqueinimei%'");
                          
      $this->execute("update contranslr
                         set c47_tiporesto = 0
                       where c47_tiporesto is null;");

      $this->execute("update contranslr
                         set c47_compara = 0
                       where c47_compara is null;");

      $this->execute("update contranslan
                         set c46_obs = c46_descricao
                       where c46_obs is null
                          or c46_obs = '';");
    }
}