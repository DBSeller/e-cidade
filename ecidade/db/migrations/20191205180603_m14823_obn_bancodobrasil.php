<?php

use Classes\PostgresMigration;

class M14823ObnBancodobrasil extends PostgresMigration
{
    public function up()
    {
        $this->execute(<<<SQL
                insert into db_layoutcampos VALUES
                (nextval('db_layoutcampos_db52_codigo_seq') ,685,'tipo_favorecido','TIPO DO FAVORECIDO',1 ,200 ,'',1 , 'f' , 't'  , 'd','',0),
                (nextval('db_layoutcampos_db52_codigo_seq') ,685,'cpf_cnpj_favorecido',' CNPJ / CPF FAVORECIDO',1 ,201 ,'',14 , 'f', 't', 'd','',0);
                update db_layoutcampos SET db52_posicao = 215 , db52_tamanho = (db52_tamanho-15) where db52_codigo = 10872;
SQL
        );

    }

    public function down()
    {
        $this->execute(<<<SQL
                delete from db_layoutcampos where db52_layoutlinha = 685 and db52_nome in ('tipo_favorecido','cpf_cnpj_favorecido');
                update db_layoutcampos SET db52_posicao = 200 , db52_tamanho = 64 where db52_codigo = 10872;
SQL
        );

    }
}
