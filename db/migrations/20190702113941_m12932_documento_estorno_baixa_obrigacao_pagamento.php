<?php

use Classes\PostgresMigration;

class M12932DocumentoEstornoBaixaObrigacaoPagamento extends PostgresMigration
{

    public function up()
    {
        $this->execute(<<<SQL_UP

insert into conhistdoctipo values (5005, 'ESTORNO DA BAIXA DE PAGAMENTO POR LIMITE DE SAQUE');
insert into conhistdoc values (5005, 'ESTORNO DA BAIXA DE PAGAMENTO POR LIMITE DE SAQUE', 5005); 

delete from vinculoeventoscontabeis where c115_conhistdocinclusao = 5004;
insert into vinculoeventoscontabeis values (nextval('vinculoeventoscontabeis_c115_sequencial_seq'), 5004, 5005 );


SQL_UP
);
    }

    public function down()
    {

        $this->execute(<<<SQL_DOWN

delete from vinculoeventoscontabeis where c115_conhistdocinclusao = 5004;
insert into vinculoeventoscontabeis values (nextval('vinculoeventoscontabeis_c115_sequencial_seq'), 5004, null);
delete from conhistdoc where c53_coddoc = 5005;
delete from conhistdoctipo where c57_sequencial = 5005;

SQL_DOWN
);
    }
}
