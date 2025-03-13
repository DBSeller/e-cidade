<?php

use Classes\PostgresMigration;

class M10849LimiteSaqueNiteroi extends PostgresMigration
{

    public function up()
    {

        /* Novos tipos de Slip */
        $this->execute("
          insert into sliptipooperacao 
               values (500, 'Concessão de Limite de Saque'),
                      (501, 'Estorno da Concessão do Limite de Saque'),
                      (502, 'Recebimento da Concessão do Limite de Saque'),
                      (503, 'Estorno do Recebimento da Concessão do Limite de Saque');
        ");

        /* Documentos */
        $this->execute("
          
          insert into conhist values (10100, 'false', 'LIMITE DE SAQUE');

          insert into conhistdoctipo 
               values (5000, 'CONCESSÃO DO LIMITE DE SAQUE'),
                      (5001, 'ESTORNO DA CONCESSÃO DO LIMITE DE SAQUE'),
                      (5002, 'RECEBIMENTO DO LIMITE DE SAQUE'),           
                      (5003, 'ESTORNO DO RECEBIMENTO DO LIMITE DE SAQUE'),                    
                      (5004, 'BAIXA OBRIGAÇÃO DE PAGAMENTO POR LIMITE DE SAQUE');      
   
          insert into conhistdoc
               values (5000, 'CONCESSÃO DO LIMITE DE SAQUE', 5000),
                      (5001, 'ESTORNO DA CONCESSÃO DO LIMITE DE SAQUE', 5001),
                      (5002, 'RECEBIMENTO DO LIMITE DE SAQUE', 5002),
                      (5003, 'ESTORNO DO RECEBIMENTO DO LIMITE DE SAQUE', 5003),
                      (5004, 'BAIXA OBRIGAÇÃO DE PAGAMENTO POR LIMITE DE SAQUE', 5004);
                      
          insert into vinculoeventoscontabeis
               values (nextval('vinculoeventoscontabeis_c115_sequencial_seq'), 5000, 5001),
                      (nextval('vinculoeventoscontabeis_c115_sequencial_seq'), 5002, 5003),
                      (nextval('vinculoeventoscontabeis_c115_sequencial_seq'), 5004, null);
                      
          select setval('conhistdocregra_c92_sequencial_seq', (select max(c92_sequencial) from conhistdocregra));
          insert into conhistdocregra 
               values (nextval('conhistdocregra_c92_sequencial_seq'), 5000, 'REGRA', 'select 1 from conhistdoc where c53_coddoc = 5000', 2018),
                      (nextval('conhistdocregra_c92_sequencial_seq'), 5001, 'REGRA', 'select 1 from conhistdoc where c53_coddoc = 5001', 2018),
                      (nextval('conhistdocregra_c92_sequencial_seq'), 5002, 'REGRA', 'select 1 from conhistdoc where c53_coddoc = 5002', 2018),
                      (nextval('conhistdocregra_c92_sequencial_seq'), 5003, 'REGRA', 'select 1 from conhistdoc where c53_coddoc = 5003', 2018),
                      (nextval('conhistdocregra_c92_sequencial_seq'), 5004, 'REGRA', 'select 1 from conhistdoc where c53_coddoc = 5004', 2018);
        ");
    }

    public function down()
    {

        $this->execute("delete from conhistdocregra where c92_conhistdoc in (5000, 5001, 5002, 5003, 5004)");
        $this->execute("delete from conhist where c50_codhist = 10100;");
        $this->execute("delete from sliptipooperacao where k152_sequencial in (500, 501, 502, 503);");
        $this->execute("delete from vinculoeventoscontabeis where c115_conhistdocinclusao in (5000, 5002, 5004);");
        $this->execute("delete from conhistdoc where c53_tipo in (5000, 5001, 5002, 5003, 5004);");
        $this->execute("delete from conhistdoctipo where c57_sequencial in (5000, 5001, 5002, 5003, 5004);");

    }

}
