<?php

use Classes\PostgresMigration;

class M17721AcertoEsferaOrcamentariaDotacao extends PostgresMigration
{

    public function change()
    {
        $this->execute(<<<SQL
update orcamento.orcdotacao
   set o58_esferaorcamentaria = x.esfera
  from (select codigo, case when db21_tipoinstit in (5,6) then 20 else 10 end as esfera from db_config) as x
 where o58_instit = codigo
  and (o58_esferaorcamentaria is null or o58_esferaorcamentaria = 0)
  and o58_anousu in (2021);
SQL
        );
    }
}