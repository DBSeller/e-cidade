<?php

use Classes\PostgresMigration;

class M12907AjusteDesempenhoContaBancaria extends PostgresMigration
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
        alter table conplanoconta drop constraint conplanoconta_codc_ae_pk;
create index  if not exists conplanocontabancaria_contabancaria on conplanocontabancaria(c56_contabancaria) ;
drop index  if exists conplanocontabancaria_codcon_ano_in;
drop index  if exists conplanocontabancaria_con_ano_cta_in;
create index  if not exists empagepag_e85_codtipo_in on empagepag(e85_codtipo);
create index  if not exists conplanoconta_banco_in on conplanoconta (c63_banco);
create index  if not exists conplanoconta_anosu_in on conplanoconta (c63_anousu);
create index  if not exists conplanoconta_reduz_in on conplanoconta (c63_reduz);
create index  if not exists conplanoconta_c63_codcon_in on conplanoconta(c63_codcon);
create index  if not exists conplanocontabancaria_reduz_in on conplanocontabancaria(c56_reduz);
create index  if not exists conplanocontabancaria_anousu_in on conplanocontabancaria(c56_anousu);
create index  if not exists conplanocontabancaria_codcon_in on conplanocontabancaria(c56_codcon);
SQL
        );
    }


    public function down()
    {
        $this->execute(<<<SQL
   alter table conplanoconta add constraint conplanoconta_codc_ae_pk primary key (c63_anousu, c63_reduz, c63_codcon);
SQL
        );

    }
}
