<?php

use Classes\PostgresMigration;

class M13760OrdemConlancamval extends PostgresMigration
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

        $this->execute("SELECT fc_putsession('__disable_audit__', 'on');");
        $this->execute("insert into db_syscampo( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel ) values ( 1010568 ,'c69_ordem' ,'int4' ,'Ordem do Lançamento' ,'0' ,'Ordem do Lançamento' ,10 ,'true' ,'false' ,'false' ,1 ,'text' ,'Ordem do Lançamento' );");
        $this->execute("insert into db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) values ( 790 ,1010568 ,9 ,0 );");

        $this->execute("alter table conlancamval add c69_ordem int default null");

        $this->execute(<<<SQL
        create or replace function fc_ordemlancamento(codigo_lancamento integer, sequen integer) returns int
as
$$
declare
  ordem int default 1;
  lancamentos record;
begin
         for lancamentos in select conlancamval.*
                            from conlancamval
                             where c69_codlan = codigo_lancamento order by c69_sequen loop

                             if sequen = lancamentos.c69_sequen then
                                return ordem;
                             end if;
                             ordem := ordem + 1;
                             end loop;


return ordem;
end;
$$
language 'plpgsql';
SQL
        );

        $this->execute("alter table conlancamval disable trigger all;
update conlancamval set c69_ordem =fc_ordemlancamento(c69_codlan, c69_sequen) where c69_anousu >= 2015;
alter table conlancamval enable trigger all;");


        $this->execute(<<<SQL
create or replace function fc_proxima_ordem_lancamento() returns trigger
as
$$
declare
  ordem int default 0;
begin



       if new.c69_ordem = 0  or new.c69_ordem is null then

          select coalesce(max(c69_ordem), 0)
            into ordem
            from conlancamval
           where c69_codlan = new.c69_codlan;

         new.c69_ordem = ordem + 1;
       end if;

return new ;
end;
$$
language 'plpgsql';


drop   trigger if exists tg_conlancamval_ordem_inc on conlancamval;
create trigger tg_conlancamval_ordem_inc before INSERT  on conlancamval for each row execute procedure fc_proxima_ordem_lancamento();
SQL
        );

        $this->execute("insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228146 ,'Retificação por inclusão' ,'Retificação por inclusão' ,'con4_incluircontalancamento001.php' ,'1' ,'1' ,'Retificação por inclusão' ,'true' );
insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 228145 ,228146 ,3 ,209 );");
    }


    public function down()
    {
        $this->execute("drop trigger if exists tg_conlancamval_ordem_inc on conlancamval");
        $this->execute("drop function fc_proxima_ordem_lancamento();");
        $this->execute("alter table conlancamval drop c69_ordem");
        $this->execute("delete from db_sysarqcamp where codcam = 1010568");
        $this->execute("delete from db_syscampo where codcam = 1010568");
        $this->execute("delete from db_menu where id_item_filho = 228146 AND modulo = 209;
                            delete from db_itensmenu where id_item = 228146;");

    }
}
