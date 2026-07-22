<?php

use Classes\PostgresMigration;

class M12750 extends PostgresMigration
{

    public function up()
    {
        $stmt = $this->query("select * from conlancamdoc  
                                  inner join  conlancamcompl on c71_codlan = c72_codlan  
                                  where c71_coddoc = 90 and c71_data > '2018-01-01';"
        );

        $rows = $stmt->fetchAll();

        foreach ($rows as $row) {
            $complem = "Controle de prestação de contas.";
            $codlan = $row['c71_codlan'];

            $sql = "UPDATE conlancamcompl SET c72_complem = '{$complem}'  WHERE  c72_codlan = {$codlan}";

            $this->execute($sql);
        }
    }

    public function down()
    {
        $stmt = $this->query("select * from conlancamdoc  
                                  inner join  conlancamcompl on c71_codlan = c72_codlan  
                                  where c71_coddoc = 90 and c71_data > '2018-01-01';"
        );

        $rows = $stmt->fetchAll();

        foreach ($rows as $row) {
            $complem = "Lançamento de prestação de contas.";
            $codlan = $row['c71_codlan'];

            $sql = "UPDATE conlancamcompl SET c72_complem = '{$complem}'  WHERE  c72_codlan = {$codlan}";

            $this->execute($sql);
        }
    }
}
