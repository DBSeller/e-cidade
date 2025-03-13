<?php

use Classes\PostgresMigration;

class M12736EncerramentoContabilsaldos extends PostgresMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function up()
    {

        $this->execute(<<<SQL
   drop function if exists fc_planosaldonovo_record(anousu integer, reduz integer, dataini date, datafim date, encerramento boolean);
create  or replace function fc_planosaldonovo_record(anousu integer, reduz integer, dataini date, datafim date, encerramento boolean ,
  out reduzido int,
  out saldo_anterior numeric,
  out valor_debito  numeric,
  out valor_credito numeric,
  out saldo_final numeric,
  out natureza_saldo_anterior char(1),
  out natureza_saldo_final char(1)
)
  returns record
language plpgsql
as $$
declare

  dados text[];

begin

  dados := fc_planosaldonovo_array(anousu, reduz, dataini, datafim, encerramento);

  reduzido = reduz;
  saldo_anterior := dados[1]::numeric;
  valor_debito := dados[2]::numeric;
  valor_credito := dados[3]::numeric;
  saldo_final := dados[4]::numeric;
  natureza_saldo_anterior := dados[5];
  natureza_saldo_final := dados[6];


end;
$$;
SQL
);

    }


    public function down()
    {

        $this->execute("drop function if exists fc_planosaldonovo_record(anousu integer, reduz integer, dataini date, datafim date, encerramento boolean);");

    }
}
