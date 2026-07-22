<?php

use Classes\PostgresMigration;

class M12645TriggerRecursoSlip extends PostgresMigration
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

        $this->execute(
            <<<SQL_UP

create or replace function fc_sliprecurso_inc_alt()
  returns trigger as
$$
declare


  recursodebito  integer;
  iAno           integer;
  codigoSlip    integer;

begin


  iAno := fc_getsession('DB_anousu');
  codigoSlip = new.k153_slip;
  if iAno = null
  then
    raise exception 'Ano da sessão não encontrado';
  end if;

  delete from sliprecursocontas where k181_slip = codigoSlip;

  select recurso
      into recursodebito
  from (select case
                 when sliptipooperacaovinculo.k153_slipoperacaotipo in (1, 2, 9, 10, 13, 14)
                         then reduzdebito.c61_codigo
                 when sliptipooperacaovinculo.k153_slipoperacaotipo in (3, 4, 7, 8, 11, 12)
                         then reduzcredito.c61_codigo
                 else null end as recurso
        from slip
               inner join conplanoreduz reduzdebito on k17_debito = reduzdebito.c61_reduz
                                                         and reduzdebito.c61_anousu = iAno
               inner join conplanoreduz reduzcredito on k17_credito = reduzcredito.c61_reduz
                                                          and reduzcredito.c61_anousu = iAno
               inner join sliptipooperacaovinculo on k153_slip = k17_codigo
        where k17_codigo = codigoSlip) as recurso_slip;


  if recursodebito is not null

  then
    insert into sliprecursocontas (k181_sequencial, k181_slip, k181_recursocredito, k181_recursodebito)
    values (nextval('sliprecursocontas_k181_sequencial_seq'), codigoSlip, recursodebito, recursodebito);
  end if;


  return new;

end;
$$
language 'plpgsql';

drop trigger if exists tg_sliprecurso_inc_alt
on sliptipooperacaovinculo;

create trigger tg_sliprecurso_inc_alt
  after INSERT OR UPDATE
  on sliptipooperacaovinculo
  for each row execute procedure fc_sliprecurso_inc_alt();
SQL_UP
        );

    }


    public function down ()
    {
    $this->execute("drop trigger if exists tg_sliprecurso_inc_alt on sliptipooperacaovinculo;");
    }
}
