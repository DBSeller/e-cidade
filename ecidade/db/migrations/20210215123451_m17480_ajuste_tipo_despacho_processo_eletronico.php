<?php

use Classes\PostgresMigration;

class M17480AjusteTipoDespachoProcessoEletronico extends PostgresMigration
{
    public function up()
    {
        $sql = <<<SQL
            INSERT INTO protocolo.tipodespacho (p100_sequencial,p100_descricao)
            SELECT 1000, 'Resposta Cidadão'
            WHERE NOT EXISTS (
                SELECT 1 FROM tipodespacho WHERE p100_sequencial = 1000
            );

            INSERT INTO protocolo.tipodespacho (p100_sequencial,p100_descricao)
            SELECT 1001, 'Mensagem Cidadão'
            WHERE NOT EXISTS (
                SELECT 1 FROM tipodespacho WHERE p100_sequencial = 1001
            );

            UPDATE procandamint SET p78_tipodespacho = 1000 
                WHERE 
                    p78_tipodespacho = 2    
                    AND EXISTS (
                        SELECT 1 FROM tipodespacho WHERE p100_sequencial = 2 AND p100_descricao = 'Resposta Cidadão'
                    );

            UPDATE procandamint SET p78_tipodespacho = 1001 
                WHERE 
                    p78_tipodespacho = 3
                    AND EXISTS (
                        SELECT 1 FROM tipodespacho WHERE p100_sequencial = 3 AND p100_descricao = 'Mensagem Cidadão'
                    );

            DELETE FROM tipodespacho WHERE p100_sequencial = 2 AND p100_descricao = 'Resposta Cidadão';
            DELETE FROM tipodespacho WHERE p100_sequencial = 3 AND p100_descricao = 'Mensagem Cidadão';
SQL;

        $this->execute($sql);
    }

   public function down()
   {
        $sql = <<<SQL
            INSERT INTO protocolo.tipodespacho (p100_sequencial,p100_descricao)
                SELECT 2, 'Resposta Cidadão'
                WHERE NOT EXISTS (
                    SELECT 1 FROM tipodespacho WHERE p100_sequencial = 2
                );

            INSERT INTO protocolo.tipodespacho (p100_sequencial,p100_descricao)
            SELECT 3, 'Mensagem Cidadão'
                WHERE NOT EXISTS (
                    SELECT 1 FROM tipodespacho WHERE p100_sequencial = 2
                );

            UPDATE procandamint SET p78_tipodespacho = 2 WHERE p78_tipodespacho = 1000;
            UPDATE procandamint SET p78_tipodespacho = 3 WHERE p78_tipodespacho = 1001;

            DELETE FROM tipodespacho WHERE p100_sequencial = 1000;
            DELETE FROM tipodespacho WHERE p100_sequencial = 1001;
SQL;
        
        $this->execute($sql);
   }
}
