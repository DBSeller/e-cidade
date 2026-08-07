<?php

use Classes\PostgresMigration;

class M13115MenuDocumentoAnulacaoEstornoPagamento extends PostgresMigration
{

    public function up()
    {
        $this->execute(<<<SQL_UP
    insert into db_itensmenu values( 228111, 'Anulação de Pagamento', 'Emite a anulação de pagamento de empenho e apropriação de retenção.', 'emp2_emiteestornoemp001.php', '1', '1', 'Emite a anulação de pagamento de empenho e apropriação de retenção.', '1'	);
    insert into db_menu values(3540,228111,12,398);
SQL_UP
);
    }

    public function down()
    {
        $this->execute(<<<SQL_DOWN

delete from db_menu where id_item_filho = 228111;
delete from db_itensmenu where id_item = 228111;

SQL_DOWN
);
    }
}
