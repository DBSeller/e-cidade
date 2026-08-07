<?php

use Classes\PostgresMigration;

class M19031Correcao extends PostgresMigration
{
    public function up()
    {
        $stmt = $this->query(<<<SQL
select c70_codlan, extract('year' from c70_data) as ano
  from conlancam
 where c70_data >= '2021-09-01'
 and not exists (select 1 from contabilidade.conlancamcomplementorecurso where o201_codlan = c70_codlan);
SQL
        );

        db_putsession('DB_desativar_account', true);
        db_putsession('DB_anousu', 2021);
        $rows = $stmt->fetchAll();
        foreach ($rows as $row) {
            $complemento = new \ECidade\Financeiro\Contabilidade\LancamentoContabil\ComplementoRecurso();
            $complemento->processar($row['c70_codlan'], $row['ano']);
        }
    }

    public function down()
    {

    }
}
