<?php

use Classes\PostgresMigration;

class M10222DeParaCodigos extends PostgresMigration
{
    public function up()
    {
        $sSql = "
            update db_tipoinstit set db21_codigosiconfi = '10131' where db21_codtipo in (1, 3, 4, 7, 8, 9, 10, 11, 12) ;
            update db_tipoinstit set db21_codigosiconfi = '10132' where db21_codtipo in (5, 6) ;
            update db_tipoinstit set db21_codigosiconfi = '20212' where db21_codtipo in (14) ;
            update db_tipoinstit set db21_codigosiconfi = '20231' where db21_codtipo in (2) ;
            update db_tipoinstit set db21_codigosiconfi = '30390' where db21_codtipo in (13) ;
            update db_tipoinstit set db21_codigosiconfi = '50511' where db21_codtipo in (101) ;
        ";
        $this->execute($sSql);
    }

    public function down()
    {
        $sSql = "
            update db_tipoinstit set db21_codigosiconfi = null;
        ";
        $this->execute($sSql);
    }
}
