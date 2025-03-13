<?php

use Classes\PostgresMigration;

class M11830CampoLayoutTxt extends PostgresMigration
{
    public function up()
    {
        $this->execute("
            update db_layoutcampos set db52_nome = 'dataoperacao', db52_descr = 'DATA OPERACAO', db52_layoutformat=4 where db52_codigo = 1935;
            update db_layoutcampos set db52_nome = 'datapagamento', db52_descr = 'DATA PAGAMENTO', db52_layoutformat=4 where db52_codigo = 1919;
        ");
    }

    public function down()
    {
        $this->execute("
            update db_layoutcampos set db52_nome = 'dataoperacao', db52_descr = 'DATA DE OPERAÇÃO', db52_layoutformat=4 where db52_codigo = 1935;
            update db_layoutcampos set db52_nome = 'datapagamento', db52_descr = 'DATA DO PAGAMENTO', db52_layoutformat=4 where db52_codigo = 1919;
        ");
    }
}
