<?php

use Classes\PostgresMigration;

class M11983finalidadeseducaocaixa extends PostgresMigration
{

    public function up()
    {
        $columns = array(
            'e151_sequencial',
            'e151_codigo',
            'e151_descricao'
        );

        $row = $this->fetchRow("select nextval('finalidadepagamentofundeb_e151_sequencial_seq')");

        $seq = $row['nextval'];

        $data = array(
            arraY(
                $seq + 1,
                91,
                'Ressarcimento por Escola Municipalizada'
            ),
            array(
                $seq + 2,
                92,
                'Transferências para Transporte Escolar Municipal'
            ),
            array(
                $seq + 3,
                97,
                'Pagamento Instituição Ensino'
            )
        );

        $this->table('finalidadepagamentofundeb', array('schema' => 'empenho'))->insert($columns, $data)->saveData();
    }

    public function down()
    {
        $this->execute("DELETE FROM   empenho.finalidadepagamentofundeb  WHERE  e151_codigo in ('91', '92', '97')");
    }
}
