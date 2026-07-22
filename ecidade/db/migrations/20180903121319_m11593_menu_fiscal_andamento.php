<?php

use Classes\PostgresMigration;

class M11593MenuFiscalAndamento extends PostgresMigration
{
    public function up()
    {
        $sql = "
update db_itensmenu set funcao = 'fis3_fandamauto005.php' where id_item = 2526 and funcao = 'fis3_fandamauto001.php';
update db_itensmenu set funcao = 'fis3_fandamauto005.php?db_opcao=2' where id_item = 2527 and funcao = 'fis3_fandamauto002.php';
update db_itensmenu set funcao = 'fis3_fandamauto005.php?db_opcao=3' where id_item = 2528 and funcao = 'fis3_fandamauto003.php';
update db_itensmenu set funcao = 'fis3_fandamnoti005.php' where id_item = 2485 and funcao = 'fis3_fandamnoti001.php';
update db_itensmenu set funcao = 'fis3_fandamnoti005.php?db_opcao=2' where id_item = 2486 and funcao = 'fis3_fandamnoti002.php';
update db_itensmenu set funcao = 'fis3_fandamnoti005.php?db_opcao=3' where id_item = 2487 and funcao = 'fis3_fandamnoti003.php';
update db_itensmenu set funcao = 'fis3_fandam005.php' where id_item = 2453 and funcao = 'fis3_fandam001.php';
update db_itensmenu set funcao = 'fis3_fandam005.php?db_opcao=2' where id_item = 2454 and funcao = 'fis3_fandam002.php';
update db_itensmenu set funcao = 'fis3_fandam005.php?db_opcao=3' where id_item = 2455 and funcao = 'fis3_fandam003.php';
        ";

        $this->execute($sql);
    }

    public function down()
    {
        $sql = "
update db_itensmenu set funcao = 'fis3_fandamauto001.php' where id_item = 2526 and funcao = 'fis3_fandamauto005.php';
update db_itensmenu set funcao = 'fis3_fandamauto002.php' where id_item = 2527 and funcao = 'fis3_fandamauto005.php?db_opcao=2';
update db_itensmenu set funcao = 'fis3_fandamauto003.php' where id_item = 2528 and funcao = 'fis3_fandamauto005.php?db_opcao=3';
update db_itensmenu set funcao = 'fis3_fandamnoti001.php' where id_item = 2485 and funcao = 'fis3_fandamnoti005.php';
update db_itensmenu set funcao = 'fis3_fandamnoti002.php' where id_item = 2486 and funcao = 'fis3_fandamnoti005.php?db_opcao=2';
update db_itensmenu set funcao = 'fis3_fandamnoti003.php' where id_item = 2487 and funcao = 'fis3_fandamnoti005.php?db_opcao=3';
update db_itensmenu set funcao = 'fis3_fandam001.php' where id_item = 2453 and funcao = 'fis3_fandam005.php';
update db_itensmenu set funcao = 'fis3_fandam002.php' where id_item = 2454 and funcao = 'fis3_fandam005.php?db_opcao=2';
update db_itensmenu set funcao = 'fis3_fandam003.php' where id_item = 2455 and funcao = 'fis3_fandam005.php?db_opcao=3';
        ";

        $this->execute($sql);
    }
}
