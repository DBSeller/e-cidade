<?php

use Classes\PostgresMigration;

class M11021CorrecaoCenso2018 extends PostgresMigration
{
    public function up()
    {
        $sql = <<<SQL
update db_layoutcampos
   set db52_nome = 'formacao_complementacao_pedagogica_3' ,
       db52_descr = 'FORMAÇÃO/COMPLEMENTAÇÃO PEDAGÓGIGA 3' 
 where db52_codigo in ( select max(db_layoutcampos.db52_codigo)
                          from db_layouttxt
                               inner join db_layoutlinha on db51_layouttxt = db50_codigo
                               inner join db_layoutcampos on db52_layoutlinha = db51_codigo
                         where db50_codigo = 303
                           and db51_descr ilike '%50%'
                           and db52_nome = 'formacao_complementacao_pedagogica_2');
SQL;
        $this->execute($sql);
    }

    public function down()
    {
        $sql = <<<SQL
update db_layoutcampos
   set db52_nome = 'formacao_complementacao_pedagogica_2' ,
       db52_descr = 'FORMAÇÃO/COMPLEMENTAÇÃO PEDAGÓGIGA 2' 
 where db52_codigo in ( select max(db_layoutcampos.db52_codigo)
                          from db_layouttxt
                               inner join db_layoutlinha on db51_layouttxt = db50_codigo
                               inner join db_layoutcampos on db52_layoutlinha = db51_codigo
                         where db50_codigo = 303
                           and db51_descr ilike '%50%'
                           and db52_nome = 'formacao_complementacao_pedagogica_3');
SQL;
        $this->execute($sql);
    }
}
