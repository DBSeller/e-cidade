<?php

use Classes\PostgresMigration;

class M13822FuncaoAberturaRestos extends PostgresMigration
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
drop function if exists fc_abertura_exercicio_transferencia_saldos_RP(tipo integer);
drop type if exists tp_abertura_exercicio_transferencia_saldos_rp;
create type tp_abertura_exercicio_transferencia_saldos_rp as (
    empenho integer,
    valor numeric,
    ano integer,
    ano_empenho integer,
    desdobramento integer,
    credor integer
    );
create function fc_abertura_exercicio_transferencia_saldos_RP(tipo integer) returns setof tp_abertura_exercicio_transferencia_saldos_rp as
$$
declare


    iAnoUsu            integer;
    iInstit            integer;
    rValoresLancamento record;
    campo              text;
    sql                text;
    rtp_valores        tp_abertura_exercicio_transferencia_saldos_rp%ROWTYPE;

begin


    iAnoUsu := cast((select fc_getsession('DB_anousu')) as integer);
    iInstit := cast((select fc_getsession('DB_instit')) as integer);

    if iAnoUsu is null then
        raise exception 'ERRO : Variavel de sessao [DB_anousu] nao encontrada.';
    end if;

    if iInstit is null then
        raise exception 'ERRO : Variavel de sessao [DB_instit] nao encontrada.';
    end if;

    campo := 'e91_vlremp - e91_vlrliq - e91_vlranu';
    if tipo = 2 then
        campo := 'e91_vlrliq - e91_vlrpag';
    end if;
    sql := 'select distinct e91_anousu                         as ano,
                e60_anousu                                     as ano_empenho,
                e60_numemp                                     as empenho,
                e60_numcgm                                     as credor,
                e64_codele                                     as desdobramento,
                round(' || campo || ', 2)::numeric as valor
           from empenho.empresto
                inner join empenho.empempenho on e60_numemp = e91_numemp
                inner join empenho.empelemento on empempenho.e60_numemp = empelemento.e64_numemp
           where e91_anousu = ' || iAnoUsu || '
         and e60_instit = ' || iInstit || '
  and round(' || campo || ', 2) > 0 order by e60_numemp';

    for rValoresLancamento in execute sql
        loop


            rtp_valores.valor := rValoresLancamento.valor;
            rtp_valores.ano_empenho = rValoresLancamento.ano_empenho;
            rtp_valores.empenho = rValoresLancamento.empenho;
            rtp_valores.ano = rValoresLancamento.ano;
            rtp_valores.desdobramento = rValoresLancamento.desdobramento;
            rtp_valores.credor = rValoresLancamento.credor;
            return next rtp_valores;

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
drop function if exists fc_abertura_exercicio_transferencia_saldos_RP(tipo integer);
drop type if exists tp_abertura_exercicio_transferencia_saldos_rp;
SQL
        );
    }
}
