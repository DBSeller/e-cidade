<?php

use Classes\PostgresMigration;

class M15222AjustesAbertura extends PostgresMigration
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
delete from contabilidade.conhistdocregra where c92_conhistdoc in(2001, 2003, 2030, 2031, 2032, 2033) and c92_anousu = 2020;
insert into contabilidade.conhistdocregra select nextval('conhistdocregra_c92_sequencial_seq'), 2001, 'Registros', 'select * from fc_abertura_exercicio_lancamento_despesa() where valor <> 0', 2020 where exists(select 1 from conhistdoc where c53_coddoc = 2001);
insert into contabilidade.conhistdocregra select nextval('conhistdocregra_c92_sequencial_seq'), 2003, 'Registros', 'select * from fc_abertura_exercicio_lancamento_receitas() where valor <> 0', 2020 where exists(select 1 from conhistdoc where c53_coddoc = 2003);
insert into contabilidade.conhistdocregra select nextval('conhistdocregra_c92_sequencial_seq'), 2030, 'Registros', 'select * from fc_abertura_exercicio_transferencia_saldos_RP(1) where valor <> 0', 2020 where exists(select 1 from conhistdoc where c53_coddoc = 2030);
insert into contabilidade.conhistdocregra select nextval('conhistdocregra_c92_sequencial_seq'), 2031, 'Registros', 'select * from fc_abertura_exercicio_transferencia_saldos_RP(2) where valor <> 0', 2020 where exists(select 1 from conhistdoc where c53_coddoc = 2031);
insert into contabilidade.conhistdocregra select nextval('conhistdocregra_c92_sequencial_seq'), 2032, 'Registros', 'select * from fc_valores_abertura_empenho_rp(2032) where valor <> 0;', 2020 where exists(select 1 from conhistdoc where c53_coddoc = 2032);
insert into contabilidade.conhistdocregra select nextval('conhistdocregra_c92_sequencial_seq'), 2033, 'Registros', 'select * from fc_valores_abertura_empenho_rp(2033) where valor <> 0;', 2020 where exists(select 1 from conhistdoc where c53_coddoc = 2033);
SQL
);

        $this->execute(<<<SQL
drop function if exists fc_valores_abertura_empenho_rp(integer);
drop type if exists tp_valores_abertura_rp;

create type tp_valores_abertura_rp as (
    empenho                  integer,
    conta_debito             integer,
    conta_credito            integer,
    valor_credito            numeric,
    valor_debito             numeric,
    valor                    numeric,
    inverter_lancamento      boolean
    );

create or replace function fc_valores_abertura_empenho_rp(integer) returns setof tp_valores_abertura_rp as
$$
declare

    /* documento de pesquisa */
    documento    alias for $1;

    /* ano da sessao */
    anoSessao integer;

    /* ano da sessao - 1 */
    anoAnterior integer;

    /* instituicao da sessao */
    instituicaoSessao integer;

    /* conta utilizada para comparacao */
    contaComparacao integer;

    rBuscaTransacao record;
    rBuscaEmpenhos record;

    saldoInicialDebito numeric;
    saldoInicialCredito numeric;
    saldoCalculado numeric;
    linha tp_valores_abertura_rp%ROWTYPE;

begin

    anoSessao := cast( (select fc_getsession('DB_anousu')) as integer);
    anoAnterior := cast(anoSessao - 1 as integer);
    instituicaoSessao := cast( (select fc_getsession('DB_instit')) as integer);

    if anoSessao is null then
        raise exception 'ERRO : Variavel de sessao [DB_anousu] nao encontrada.';
    end if;

    if instituicaoSessao is null then
        raise exception 'ERRO : Variavel de sessao [DB_instit] nao encontrada.';
    end if;


    for rBuscaTransacao in
        select c47_seqtranslr,
               c47_debito,
               c47_credito,
               c47_compara,
               c46_ordem
        from contrans
                 inner join contranslan on c45_seqtrans = c46_seqtrans
                 inner join contranslr on c47_seqtranslan = c46_seqtranslan
        where c45_coddoc = documento
          and c45_anousu = anoSessao
          and c45_instit = instituicaoSessao
          and c46_ordem = 1
   order by c47_seqtranslr
        loop

            contaComparacao := rBuscaTransacao.c47_debito;
            if rBuscaTransacao.c47_compara = 2 then
                contaComparacao := rBuscaTransacao.c47_credito;
            end if;

            raise notice 'PERCORRENDO -> %',rBuscaTransacao.c47_seqtranslr;

            /* busca o saldo inicial da conta */
            select c62_vlrcre, c62_vlrdeb
            into saldoInicialCredito, saldoInicialDebito
            from conplanoexe
            where c62_reduz = contaComparacao
              and c62_anousu = anoAnterior;

            saldoCalculado = saldoInicialCredito - saldoInicialDebito;
            linha.empenho       = null;
            linha.conta_debito  = rBuscaTransacao.c47_debito;
            linha.conta_credito = rBuscaTransacao.c47_credito;
            linha.valor_credito = saldoInicialCredito;
            linha.valor_debito  = saldoInicialDebito;
            linha.valor         = abs(saldoCalculado);
            linha.inverter_lancamento = false;

            if (rBuscaTransacao.c47_compara = 2 and saldoCalculado > 0) OR (rBuscaTransacao.c47_compara = 1 and saldoCalculado < 0) then

                linha.conta_debito = rBuscaTransacao.c47_credito;
                linha.conta_credito = rBuscaTransacao.c47_debito;
                linha.inverter_lancamento = true;
            end if;
            return next linha;


            for rBuscaEmpenhos in

                select c75_numemp as numero_empenho,
                       coalesce(round(case when c69_debito = contaComparacao then c69_valor end, 2), 0)  as valor_debito,
                       coalesce(round(case when c69_credito = contaComparacao then c69_valor end, 2), 0) as valor_credito
                from conlancam
                         inner join conlancamval on conlancamval.c69_codlan = conlancam.c70_codlan
                         left  join conlancamemp on conlancamemp.c75_codlan = conlancam.c70_codlan
                where conlancamval.c69_data between cast(anoAnterior||'-01-01' as date) and cast(anoAnterior||'-12-31' as date)
                  and (conlancamval.c69_debito = contaComparacao or conlancamval.c69_credito = contaComparacao)

                loop

                    saldoCalculado = rBuscaEmpenhos.valor_credito - rBuscaEmpenhos.valor_debito;
                    linha.empenho       = rBuscaEmpenhos.numero_empenho;
                    linha.conta_debito  = rBuscaTransacao.c47_debito;
                    linha.conta_credito = rBuscaTransacao.c47_credito;
                    linha.valor_credito = rBuscaEmpenhos.valor_credito;
                    linha.valor_debito  = rBuscaEmpenhos.valor_debito;
                    linha.valor         = abs(saldoCalculado);
                    linha.inverter_lancamento = false;
                    if (rBuscaTransacao.c47_compara = 2 and saldoCalculado > 0) OR (rBuscaTransacao.c47_compara = 1 and saldoCalculado < 0) then

                        linha.conta_credito = rBuscaTransacao.c47_debito;
                        linha.conta_debito  = rBuscaTransacao.c47_credito;
                        linha.inverter_lancamento = true;
                    end if;
                    return next linha;

                end loop;

        end loop;
    return ;
end;
$$ language 'plpgsql';


SQL
        );
    }

    public function down()
    {

    }
}
