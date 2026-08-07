<?php

use Classes\PostgresMigration;

class M12502AjusteContaCorrente extends PostgresMigration
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

        $this->execute(<<<SQL
        
      update conplanoinfocomplementar set c121_sql = 'select case when o58_orgao is not null then o58_orgao else o70_orcorgao end as orgao                                                       
   from conlancam                                                                                                                            
        left join conlancamdot on c73_codlan = c70_codlan                                                                                  
        left join orcdotacao on c73_coddot = o58_coddot                                                                                     
                           and c73_anousu = o58_anousu                                                                                       
        left join conlancamrec on c74_codlan = c70_codlan                                                                                  
        left join orcreceita on c74_codrec = o70_codrec                                                                                      
                           and c74_anousu = o70_anousu                                                                                    
  where c70_codlan =codigo_lancamento'         
 where c121_nomepropriedade = 'orgao'
SQL
);
    }
}
