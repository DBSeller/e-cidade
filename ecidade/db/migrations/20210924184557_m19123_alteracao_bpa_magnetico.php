<?php

use Classes\PostgresMigration;

class M19123AlteracaoBpaMagnetico extends PostgresMigration
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
        $this->execute(<<<SQL
            
            insert into db_layoutcampos( db52_codigo ,db52_layoutlinha ,db52_nome ,db52_descr ,db52_layoutformat ,db52_posicao ,db52_default ,db52_tamanho ,db52_ident ,db52_imprimir ,db52_alinha ,db52_obs ,db52_quebraapos ) values ( 171802 ,692 ,'prd_ine' ,'INDENTIFICAÇÃO NACIONAL DE EQUIPES' ,2 ,329 ,'' ,10 ,'f' ,'t' ,'e' ,'' ,0 );
            update db_layoutcampos set db52_codigo = 11023 , db52_layoutlinha = 692 , db52_nome = 'prd_fim' , db52_descr = 'FIM DO ARQUIVO' , db52_layoutformat = 1 , db52_posicao = 339 , db52_default = '' , db52_tamanho = 1 , db52_ident = 'f' , db52_imprimir = 't' , db52_alinha = 'd' , db52_obs = '' , db52_quebraapos = 0 where db52_codigo = 11023;
SQL
        );
    }

    public function down()
    {
        $this->execute(<<<SQL
            delete from db_layoutcampos where db52_codigo = 171802 and db52_layoutlinha = 692;
            update db_layoutcampos set db52_posicao = 329 where db52_codigo = 11023 and db52_layoutlinha = 692;
            
SQL
        );
    }
}
