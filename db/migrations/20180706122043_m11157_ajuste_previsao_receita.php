<?php

use Classes\PostgresMigration;

class M11157AjustePrevisaoReceita extends PostgresMigration
{
    public function up()
    {
        $sql = "
            UPDATE db_sysforkey
            SET referen = 3268
            WHERE codarq = 1010294 AND codcam IN (1009814, 1009815) AND sequen = 1 AND referen = 774 AND tipoobjrel = 0;
            
            ALTER TABLE avaliacaogruporespostaconta
              DROP CONSTRAINT IF EXISTS avaliacaogruporespostaconta_conta_fk,
              ADD CONSTRAINT avaliacaogruporespostaconta_conta_fk FOREIGN KEY (c06_conta, c06_ano)
            REFERENCES conplanoorcamento;
        ";

        $this->execute($sql);
    }

    public function down()
    {
        $sql = "
            UPDATE db_sysforkey
            SET referen = 774
            WHERE codarq = 1010294 AND codcam IN (1009814, 1009815) AND sequen = 1 AND referen = 3268 AND tipoobjrel = 0;
            
            ALTER TABLE avaliacaogruporespostaconta
              DROP CONSTRAINT IF EXISTS avaliacaogruporespostaconta_conta_fk,
              ADD CONSTRAINT avaliacaogruporespostaconta_conta_fk FOREIGN KEY (c06_conta, c06_ano)
            REFERENCES conplano;
        ";

        $this->execute($sql);
    }
}
