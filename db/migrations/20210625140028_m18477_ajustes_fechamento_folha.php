<?php

use Classes\PostgresMigration;

class M18477AjustesFechamentoFolha extends PostgresMigration
{

    public function up()
    {
        $this->upEstrutura();
    }

    public function down()
    {
        $this->downEstrutura();
    }

    public function upEstrutura()
    {
        $this->execute(<<<SQL

           CREATE UNIQUE INDEX IF NOT EXISTS rhhistoricoponto_regist_folha_rubric_in
              ON rhhistoricoponto(rh144_folhapagamento, rh144_regist, rh144_rubrica);

           DROP INDEX IF EXISTS rhhistoricoponto_folha_reg_in;
SQL
        );
    }

    public function downEstrutura()
    {
        $this->execute(<<<SQL

           DROP INDEX IF EXISTS rhhistoricoponto_regist_folha_rubric_in;

           CREATE INDEX IF NOT EXISTS rhhistoricoponto_folha_reg_in
              ON rhhistoricoponto(rh144_folhapagamento, rh144_regist);

SQL
        );
    }
}
