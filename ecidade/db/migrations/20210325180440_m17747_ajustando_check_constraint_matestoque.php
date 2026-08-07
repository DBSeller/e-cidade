<?php

use Classes\PostgresMigration;

class M17747AjustandoCheckConstraintMatestoque extends PostgresMigration
{
    public function up()
    {
        $sql = <<<SQL
            UPDATE matestoqueitem
            SET m71_quant = (m71_quant + (m71_quantatend - m71_quant))
            WHERE m71_quant < m71_quantatend ;

            ALTER TABLE matestoqueitem DROP CONSTRAINT matestoqueitem_quant_ck;
            ALTER TABLE matestoqueitem ADD  CONSTRAINT matestoqueitem_quant_ck CHECK (round(m71_quant, 3) >= round(m71_quantatend, 3));
SQL;

        $this->execute($sql);
    }

    public function down()
    {
        $sql = <<<SQL
            UPDATE matestoqueitem
            SET m71_quant = (m71_quant + (m71_quantatend - m71_quant))
            WHERE m71_quant < m71_quantatend ;

            ALTER TABLE matestoqueitem DROP CONSTRAINT matestoqueitem_quant_ck;
            ALTER TABLE matestoqueitem ADD  CONSTRAINT matestoqueitem_quant_ck CHECK (round(m71_quant, 2) >= round(m71_quantatend, 2));
SQL;
        
        $this->execute($sql);
    }
}
