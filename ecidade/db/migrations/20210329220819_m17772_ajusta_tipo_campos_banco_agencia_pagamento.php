<?php

use Classes\PostgresMigration;

class M17772AjustaTipoCamposBancoAgenciaPagamento extends PostgresMigration
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

                  alter table disbanco alter bancopagamento type varchar;
                  alter table disbanco alter agenciapagamento type varchar; 

		  alter table cadban alter k15_bancopagamento type character(6);
                  alter table cadban alter k15_agenciapagamento type character(6); 

		  alter table cadban alter column k15_bancopagamento drop not null;
		  alter table cadban alter column k15_agenciapagamento drop not null;

		  alter table cadban alter column k15_bancopagamento drop default;
		  alter table cadban alter column k15_agenciapagamento drop default;

		  update db_syscampo set conteudo = 'character(6)' where codcam = 1011863 and nomecam = 'k15_bancopagamento';
                  update db_syscampo set conteudo = 'character(6)' where codcam = 1011864 and nomecam = 'k15_agenciapagamento';

		  update db_syscampo set conteudo = 'varchar' where codcam = 1011865 and nomecam = 'bancopagamento';
                  update db_syscampo set conteudo = 'varchar' where codcam = 1011866 and nomecam = 'agenciapagamento';

SQL;

        $this->execute($sql);
    }

    public function down()
    {
	    $sql = <<<SQL

                  alter table disbanco alter bancopagamento type text;
                  alter table disbanco alter agenciapagamento type text; 

		  alter table cadban alter column k15_bancopagamento set not null ;
		  alter table cadban alter column k15_bancopagamento drop default ;

		  alter table cadban alter column k15_agenciapagamento set not null ;
                  alter table cadban alter column k15_agenciapagamento drop default ;

		  alter table cadban alter k15_bancopagamento type integer using k15_bancopagamento::integer;
		  alter table cadban alter k15_agenciapagamento type integer using k15_agenciapagamento::integer;
                 
		  alter table cadban alter column k15_bancopagamento set default 0;
                  alter table cadban alter column k15_agenciapagamento set default 0;

		  update db_syscampo set conteudo = 'int4' where codcam = 1011863 and nomecam = 'k15_bancopagamento';
                  update db_syscampo set conteudo = 'int4' where codcam = 1011864 and nomecam = 'k15_agenciapagamento';

		  update db_syscampo set conteudo = 'text' where codcam = 1011865 and nomecam = 'bancopagamento';
                  update db_syscampo set conteudo = 'text' where codcam = 1011866 and nomecam = 'agenciapagamento';

SQL;
        
        $this->execute($sql);
    }
}
