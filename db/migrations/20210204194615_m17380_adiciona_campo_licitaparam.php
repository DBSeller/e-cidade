<?php

use Classes\PostgresMigration;

class M17380AdicionaCampoLicitaparam extends PostgresMigration
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
        $sql = <<<SQL

insert into db_syscampo 
values(1012020,
       'l12_limitetamanhoarquivo',
       'int4',
       'A métrica utilizada para validar o limite de tamanho do arquivo é em bytes.',
       '1000000', 
       'Limite Tamanho Arquivo',
       10,
       'f',
       'f',
       'f',
       1,
       'text',
       'Limite Tamanho Arquivo');

insert into db_sysarqcamp 
values(2055,1012020,6,0);
alter table licitaparam 
  add column l12_limitetamanhoarquivo integer default 1000000;

insert into licitaparam(l12_instit, 
                        l12_escolherprocesso, 
                        l12_escolheprotocolo, 
                        l12_qtdediasliberacaoweb, 
                        l12_tipoliberacaoweb, 
                        l12_limitetamanhoarquivo) 
select codigo, 
       'f'::boolean, 
       'f'::boolean, 
       0, 
       1, 
       1000000  
  from db_config 
 where not exists(select 1 
                    from licitaparam 
                   where l12_instit = codigo);
SQL;
  
      
      $this->execute($sql);
    }

    public function down()
    {
      $sql = <<<SQL
      delete 
        from db_sysarqcamp 
       where codarq = 2055 
         and codcam = 1012020;

      delete 
        from db_syscampo 
       where codcam = 1012020;
      
       alter table licitaparam drop column l12_limitetamanhoarquivo;
SQL;
    
    $this->execute($sql);
    
}

}