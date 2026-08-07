<?php

use Classes\PostgresMigration;

class M13009CorrecaoDirf extends PostgresMigration
{
    public function up()
    {
        $this->execute("
            UPDATE db_layoutcampos SET db52_nome = 'idetificador_registro' WHERE db52_codigo = 16558;
            UPDATE db_layoutcampos SET db52_nome = 'marco' WHERE db52_codigo = 16559;
            UPDATE db_layoutcampos SET db52_layoutformat = 1 WHERE db52_layoutlinha = 978 AND db52_posicao > 1 AND db52_nome <> 'pipe';
        ");
    }

    public function down()
    {
        $this->execute("
            UPDATE db_layoutcampos SET db52_layoutformat = 3 WHERE db52_layoutlinha = 978 AND db52_posicao > 1 AND db52_nome <> 'pipe';
            UPDATE db_layoutcampos SET db52_nome = 'março' WHERE db52_codigo = 16559;
            UPDATE db_layoutcampos SET db52_nome = 'identificador' WHERE db52_codigo = 16558;
        ");
    }
}
