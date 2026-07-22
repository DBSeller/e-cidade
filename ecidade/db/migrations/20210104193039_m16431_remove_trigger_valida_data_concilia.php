<?php

use Classes\PostgresMigration;

class M16431RemoveTriggerValidaDataConcilia extends PostgresMigration
{
    public function up()
    {
        $sSql = <<<SQL

          alter table concilia disable trigger tg_valida_data_conciliacao;
          alter table extratolinha disable trigger tg_valida_data_conciliacao;
          alter table extratosaldo disable trigger tg_valida_data_conciliacao;
          alter table conciliaitem disable trigger tg_valida_data_conciliacao;
          alter table conciliapendcorrente disable trigger tg_valida_data_conciliacao;
          alter table conciliapendextrato disable trigger tg_valida_data_conciliacao;
          alter table conciliacor disable trigger tg_valida_data_conciliacao;
          alter table conciliaextrato disable trigger tg_valida_data_conciliacao;
SQL;
      $this->execute($sSql) ;
    }


    public function down()
    {
        $sSql = <<<SQL

          alter table concilia enable trigger tg_valida_data_conciliacao;
          alter table extratolinha enable trigger tg_valida_data_conciliacao;
          alter table extratosaldo enable trigger tg_valida_data_conciliacao;
          alter table conciliaitem enable trigger tg_valida_data_conciliacao;
          alter table conciliapendcorrente enable trigger tg_valida_data_conciliacao;
          alter table conciliapendextrato enable trigger tg_valida_data_conciliacao;
          alter table conciliacor enable trigger tg_valida_data_conciliacao;
          alter table conciliaextrato enable trigger tg_valida_data_conciliacao;

SQL;
        $this->execute($sSql) ;
    }
}
