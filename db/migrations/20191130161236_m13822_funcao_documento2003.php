<?php

use Classes\PostgresMigration;

class M13822FuncaoDocumento2003 extends PostgresMigration
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

drop function if exists fc_abertura_exercicio_lancamento_receitas();
drop type if exists tp_abertura_exercicio_receita;
create type tp_abertura_exercicio_receita as (
    receita integer,
    valor numeric
    );
create function fc_abertura_exercicio_lancamento_receitas() returns setof tp_abertura_exercicio_receita as
$$
declare


    iAnoUsu              integer;
    iInstit              integer;
    rValoresLancamento   record;
    rtp_valores_abertura tp_abertura_exercicio_receita%ROWTYPE;

begin


    iAnoUsu := cast((select fc_getsession('DB_anousu')) as integer);
    iInstit := cast((select fc_getsession('DB_instit')) as integer);

    if iAnoUsu is null then
        raise exception 'ERRO : Variavel de sessao [DB_anousu] nao encontrada.';
    end if;

    if iInstit is null then
        raise exception 'ERRO : Variavel de sessao [DB_instit] nao encontrada.';
    end if;


    for rValoresLancamento in select
                o70_codrec as receita,
                o70_valor as valor
     from orcamento.orcreceita
          inner join orcamento.orcfontes      on orcfontes.o57_codfon    = orcreceita.o70_codfon
                                   and orcfontes.o57_anousu    = orcreceita.o70_anousu
                         where o70_anousu = iAnoUsu
                           and o70_instit  = iInstit
        loop


            rtp_valores_abertura.valor := rValoresLancamento.valor;
            rtp_valores_abertura.receita = rValoresLancamento.receita;
            return next rtp_valores_abertura;

        end loop;
    return;
end
$$
language 'plpgsql';

SQL

        );
    }

    public function down()
    {
        $this->execute(<<<SQL

drop function if exists fc_abertura_exercicio_lancamento_receitas();
drop type if exists tp_abertura_exercicio_receita;

SQL
        );
    }

}

