<?php

use Classes\PostgresMigration;

class M15086ReenvioDeFuncaoEncerramento extends PostgresMigration
{
    public function down() {}




    public function up()
    {

        $this->execute(<<<SQL_UP

drop function if exists fc_encerramento_doc_1010(codigoDocumento integer);
drop type if exists tp_encerramento_receita_1010;
create type tp_encerramento_receita_1010 as (
    sequencial integer,
    receita integer,
    conta_debito integer,
    conta_credito integer,
    ano integer,
    estrutural varchar,
    natureza_saldo_conta varchar, /* natureza cadastrada na conta*/
    valor numeric,
    valor_debito numeric,
    valor_credito numeric,
    natureza_saldo varchar /* natureza processada para a conta*/,
    debito_menos_credito numeric,
    credito_menos_debito numeric,
    saldo_conta numeric,
    compara integer
    );

create or replace function fc_encerramento_doc_1010(codigoDocumento integer) returns SETOF tp_encerramento_receita_1010
    language plpgsql
as
$$
declare

    instituicaoSessao integer;
    anoSessao         integer;
    contaComparacao   integer;
    rTransacao        record;
    rReceitasEncerrar record;
    linha             tp_encerramento_receita_1010%ROWTYPE;
    documento         integer;


begin

    instituicaoSessao := fc_getsession('DB_instit');
    anoSessao := fc_getsession('DB_anousu');
    documento := codigoDocumento;

    for rTransacao in
        select c47_seqtranslr,
               c47_debito,
               c47_credito,
               c47_compara,
               c46_ordem
        from contrans
                 join contranslan on c45_seqtrans = c46_seqtrans
                 join contranslr on c47_seqtranslan = c46_seqtranslan
        where c45_coddoc = documento
          and c45_anousu = anoSessao
          and c45_instit = instituicaoSessao
         -- and c47_seqtranslr in (378737)
--            and c47_seqtranslr <= 135343
        order by c47_seqtranslr
        loop

            contaComparacao := rTransacao.c47_debito;
            if rTransacao.c47_compara = 2 then
                contaComparacao := rTransacao.c47_credito;
            end if;

            for rReceitasEncerrar in
                select receita,
                       ano,
                       estrutural,
                       (select case
                                   when c60_naturezasaldo = 1 then 'D'
                                   when c60_naturezasaldo = 2 then 'C'
                                   end
                        from contabilidade.conplanoreduz
                                 inner join contabilidade.conplano on conplanoreduz.c61_codcon = conplano.c60_codcon
                            and conplanoreduz.c61_anousu = conplano.c60_anousu
                        where c61_reduz = contaComparacao
                          and c60_anousu = anoSessao
                       )                                                  as natureza_saldo_conta,
                       sum(valor_credito)                                 as valor_credito,
                       sum(valor_debito)                                  as valor_debito,
                       (round(sum(valor_debito) - sum(valor_credito), 2)) as debito_menos_credito,
                       (round(sum(valor_credito) - sum(valor_debito), 2)) as credito_menos_debito
                from (
                         select o70_codrec         as receita,
                                c69_anousu         as ano,
                                o57_fonte          as estrutural,
                                sum(valor_credito) as valor_credito,
                                sum(valor_debito)  as valor_debito
                         from (select o70_codrec,
                                      coalesce((case
                                                    when c69_credito = contaComparacao
                                                        then c69_valor end), 0) as valor_credito,
                                      coalesce((case
                                                    when c69_debito = contaComparacao
                                                        then c69_valor end), 0) as valor_debito,
                                      c53_tipo,
                                      c71_coddoc,
                                      c69_anousu,
                                      o57_fonte
                               from contabilidade.conlancam
                                        inner join contabilidade.conlancamval
                                                   on conlancam.c70_codlan = conlancamval.c69_codlan
                                        inner join contabilidade.conlancamdoc
                                                   on conlancam.c70_codlan = conlancamdoc.c71_codlan
                                        inner join contabilidade.conhistdoc
                                                   on conlancamdoc.c71_coddoc = conhistdoc.c53_coddoc
                                        inner join contabilidade.conlancaminstit
                                                   on conlancam.c70_codlan = conlancaminstit.c02_codlan
                                        left join contabilidade.conlancamrec on c70_codlan = conlancamrec.c74_codlan
                                        left join orcamento.orcreceita on o70_codrec = c74_codrec
                                   and o70_anousu = c69_anousu
                                        left join orcamento.orcfontes on orcfontes.o57_codfon = orcreceita.o70_codfon
                                   and orcfontes.o57_anousu = orcreceita.o70_anousu
                               where c69_anousu = anoSessao
                                 and (c69_credito = contaComparacao or c69_debito = contaComparacao)
                                 and c02_instit = instituicaoSessao
                                  /* and c53_tipo in (100, 101, 2000)*/
                              ) as x
                         group by 1, 2, 3) as y
                group by receita, ano, estrutural
                loop

                    linha.natureza_saldo_conta = rReceitasEncerrar.natureza_saldo_conta;
                    linha.conta_debito = rTransacao.c47_debito;
                    linha.conta_credito = rTransacao.c47_credito;
                    linha.saldo_conta = round(rReceitasEncerrar.valor_debito - rReceitasEncerrar.valor_credito, 2);
                    linha.valor = round(abs(linha.saldo_conta), 2);
                    linha.natureza_saldo = 'D';
                    linha.sequencial = rTransacao.c47_seqtranslr;
                    linha.receita = rReceitasEncerrar.receita;

                    linha.ano = rReceitasEncerrar.ano;
                    linha.estrutural = rReceitasEncerrar.estrutural;

                    linha.valor_debito = rReceitasEncerrar.valor_debito;
                    linha.valor_credito = rReceitasEncerrar.valor_credito;
                    linha.debito_menos_credito = rReceitasEncerrar.debito_menos_credito;
                    linha.credito_menos_debito = rReceitasEncerrar.credito_menos_debito;
                    linha.compara = rTransacao.c47_compara;

                    if (linha.saldo_conta < 0) then
                        linha.natureza_saldo = 'C';
                    end if;

                    if (linha.natureza_saldo <> rReceitasEncerrar.natureza_saldo_conta) then

                        linha.conta_debito = rTransacao.c47_credito;
                        linha.conta_credito = rTransacao.c47_debito;
                    end if;


                    return next linha;
                end loop;
        end loop;


    return;
end ;
$$;

drop function if exists fc_doc_encerramento_2019(anousu integer, instituicaoLancamento integer) ;
drop type if exists tp_doc_1009 ;

create type tp_doc_1009 as
    (
    reduzido_credito integer,
    reduzido_debito numeric,
    estrutural varchar,
    valor numeric,
    mensagem text,
    erro bool
    );

create or replace function fc_doc_encerramento_2019(anousu integer, instituicaoLancamento integer) returns SETOF tp_doc_1009
    language plpgsql
as
$$
declare
    dados             text[];
    instit            integer;
    rtp_doc1009       tp_doc_1009%ROWTYPE;
    rContasEncerrar   record;
    rConsultaTranscao record;
    contaDebito       integer;
    contaCredito      integer;
    erro              bool default false;
    mensagem          varchar;
begin

    select codigo
      into instit
      from configuracoes.db_config
     where prefeitura is true;

    for rConsultaTranscao in select c47_debito as conta_encerramento,
                                    c60_estrut as estrutural
                               from contabilidade.contrans
                                    inner join contabilidade.contranslan on contrans.c45_seqtrans = contranslan.c46_seqtrans
                                    inner join contabilidade.contranslr on contranslan.c46_seqtranslan = contranslr.c47_seqtranslan
                                    inner join conplanoreduz on conplanoreduz.c61_reduz = contranslr.c47_debito
                                                            and conplanoreduz.c61_anousu = contranslr.c47_anousu
                                    inner join conplano on conplano.c60_codcon = conplanoreduz.c61_codcon
                                                       and conplano.c60_anousu = conplanoreduz.c61_anousu
                              where c45_coddoc = 1009
                                and c45_anousu = anousu
                                and c45_instit = instituicaoLancamento
                                and c46_ordem = 1 order by contranslr.c47_seqtranslr
    loop

        raise notice ' conta: % ano: %', rConsultaTranscao.conta_encerramento, anousu;
        dados := fc_planosaldonovo_array(anousu, rConsultaTranscao.conta_encerramento, cast(anousu || '-01-01' as date),
                                         cast(anousu || '-12-31' as date),
                                         false);

        if dados[4]::numeric > 0 then
            rtp_doc1009.erro = true;
            rtp_doc1009.mensagem :=
                    'Antes de executar, você deve transferir o saldo da conta "2.3.7.1.1.01.00.00.00.00 - SUPERÁVITS OU DÉFICITS DO EXERCÍCIO" para a conta "2.3.7.1.1.02.00.00.00.00 - SUPERÁVITS OU DÉFICITS DE EXERCÍCIOS ANTERIORES". Para realizar este lançamento, acesse o menu: "DB:FINANCEIRO > Contabilidade > Procedimentos > Escrituração Contábil > Manutenção de Lançamentos > Inclusão", usando o código de Documento 3000';
            return next rtp_doc1009;
        end if;

        /**
          Consultamos todas as contas do grupo 3, e 4 e devemos encerra-las
         */
        for rContasEncerrar in select c61_reduz,
                                      c60_estrut,
                                      bal_ver[4] as valor,
                                      bal_ver[6] as natureza_saldo,
                                      natureza_conta
                               from (
                                        select c61_reduz,
                                               c60_estrut,
                                               case
                                                   when c60_naturezasaldo = 1 then 'D'
                                                   when c60_naturezasaldo = 2 then 'C' end as natureza_conta,
                                               fc_planosaldonovo_array(c61_anousu, c61_reduz,
                                                                       cast(anousu || '-01-01' as date),
                                                                       cast(anousu || '-12-31' as date),
                                                                       false)              as bal_ver
                                        from contabilidade.conplanoreduz
                                             inner join contabilidade.conplano on conplanoreduz.c61_codcon = conplano.c60_codcon
                                                                              and conplanoreduz.c61_anousu = conplano.c60_anousu
                                        where substr(c60_estrut, 1, 1) in ('3', '4')
                                          and substring(c60_estrut, 5, 1)::int = substring(rConsultaTranscao.estrutural, 5, 1)::int
                                          and c61_instit = instituicaoLancamento
                                          and c61_anousu = anousu
                                    ) as saldo_conta

                               where bal_ver[4]::numeric <> 0
                               order by c60_estrut
            loop

                erro = false;
                mensagem = '';
                if rContasEncerrar.natureza_saldo <> rContasEncerrar.natureza_conta then

                    erro = true;
                    mensagem = 'Conta '||rContasEncerrar.c60_estrut||' possui natureza de saldo "'||rContasEncerrar.natureza_conta||'". O saldo está invertido ('||rContasEncerrar.natureza_saldo||'), Processamento foi cancelado.';
                end if;
                contaCredito = rContasEncerrar.c61_reduz;
                contaDebito = rConsultaTranscao.conta_encerramento;
                if rContasEncerrar.natureza_saldo = 'C' then
                    contaDebito = rContasEncerrar.c61_reduz;
                    contaCredito = rConsultaTranscao.conta_encerramento;
                end if;
                rtp_doc1009.reduzido_credito = contaCredito;
                rtp_doc1009.reduzido_debito = contaDebito;
                rtp_doc1009.estrutural = rContasEncerrar.c60_estrut;
                rtp_doc1009.valor = rContasEncerrar.valor;
                rtp_doc1009.mensagem = mensagem;
                rtp_doc1009.erro = erro;
                return next rtp_doc1009;
            end loop;
        end loop;
    return;
end;
$$;

drop function if exists fc_encerramento_doc_1019();
drop type if exists tp_encerramento_despesa_1019;
drop function if exists fc_encerramento_doc_1019();
drop type if exists tp_encerramento_despesa_1019;
create type tp_encerramento_despesa_1019 as (
    codigo_transacao integer,
    empenho integer,
    dotacao integer,
    conta_debito integer,
    conta_credito integer,
    comparacao integer,
    ano integer,
    natureza_saldo_conta varchar, /* natureza cadastrada na conta*/
    natureza_saldo varchar, /* natureza processada para a conta*/
    valor numeric,
    valor_debito numeric,
    valor_credito numeric,
    debito_menos_credito numeric,
    credito_menos_debito numeric,
    saldo_conta numeric
    );

create or replace function fc_encerramento_doc_1019() returns SETOF tp_encerramento_despesa_1019
    language plpgsql
as
$$
declare

    instituicaoSessao integer;
    anoSessao         integer;
    contaComparacao   integer;
    rTransacao        record;
    rDespesasEncerrar record;
    linha             tp_encerramento_despesa_1019%ROWTYPE;
    codigoTransacao   integer;

begin

    instituicaoSessao := fc_getsession('DB_instit');
    anoSessao := fc_getsession('DB_anousu');


    for rTransacao in
        select c47_seqtranslr,
               c47_debito,
               c47_credito,
               c47_compara,
               c47_debito,
               c47_credito,
               c46_ordem
        from contabilidade.contrans
                 join contabilidade.contranslan on c45_seqtrans = c46_seqtrans
                 join contabilidade.contranslr on c47_seqtranslan = c46_seqtranslan
        where c45_coddoc = 1019
          and c45_anousu = anoSessao
          and c45_instit = instituicaoSessao
          --and c47_seqtranslr = 378751
        order by c47_seqtranslr
        loop

            contaComparacao := rTransacao.c47_debito;
            if rTransacao.c47_compara = 2 then
                contaComparacao := rTransacao.c47_credito;
            end if;

            for rDespesasEncerrar in
                select empenho,
                       dotacao,
                       ano,
                       natureza_saldo_conta,
                       sum(valor_credito)                                 as valor_credito,
                       sum(valor_debito)                                  as valor_debito,
                       (round(sum(valor_debito) - sum(valor_credito), 2)) as debito_menos_credito,
                       (round(sum(valor_credito) - sum(valor_debito), 2)) as credito_menos_debito
                from (
                         select c75_numemp         as empenho,
                                c73_coddot         as dotacao,
                                c69_anousu         as ano,
                                (select case
                                            when c60_naturezasaldo = 1 then 'D'
                                            when c60_naturezasaldo = 2 then 'C'
                                            end
                                 from contabilidade.conplanoreduz
                                          inner join contabilidade.conplano
                                                     on conplanoreduz.c61_codcon = conplano.c60_codcon
                                                         and conplanoreduz.c61_anousu = conplano.c60_anousu
                                 where c61_reduz = contaComparacao
                                   and c60_anousu = anoSessao
                                )                  as natureza_saldo_conta,
                                sum(valor_credito) as valor_credito,
                                sum(valor_debito)  as valor_debito
                         from (select coalesce((case
                                                    when c69_credito = contaComparacao
                                                        then c69_valor end), 0) as valor_credito,
                                      coalesce((case
                                                    when c69_debito = contaComparacao
                                                        then c69_valor end), 0) as valor_debito,
                                      c71_coddoc,
                                      c69_anousu,
                                      c75_numemp,
                                      c73_coddot
                               from contabilidade.conlancamval
                                        inner join contabilidade.conlancamdoc on c69_codlan = c71_codlan
                                        left join contabilidade.conlancamemp on c75_codlan = c69_codlan
                                        left join contabilidade.conlancamdot on c73_codlan = c69_codlan
                               where (c69_debito = contaComparacao or c69_credito = contaComparacao)
                                 and c69_anousu = anoSessao
                              ) as x
                         group by 1, 2, 3) as y
                group by dotacao, empenho, ano, natureza_saldo_conta
                order by empenho, dotacao
                loop

                    if rDespesasEncerrar.credito_menos_debito = 0 then
                        continue;
                    end if;

                    linha.natureza_saldo_conta = rDespesasEncerrar.natureza_saldo_conta;
                    linha.conta_debito = rTransacao.c47_debito;
                    linha.conta_credito = rTransacao.c47_credito;
                    linha.saldo_conta = round(rDespesasEncerrar.valor_debito - rDespesasEncerrar.valor_credito, 2);
                    linha.codigo_transacao = rTransacao.c47_seqtranslr;
                    linha.valor = round(abs(linha.saldo_conta), 2);
                    linha.natureza_saldo = 'D';
                    linha.empenho = rDespesasEncerrar.empenho;
                    linha.dotacao = rDespesasEncerrar.dotacao;
                    linha.comparacao = rTransacao.c47_compara;
                    linha.ano = rDespesasEncerrar.ano;
                    linha.valor_debito = rDespesasEncerrar.valor_debito;
                    linha.valor_credito = rDespesasEncerrar.valor_credito;
                    linha.debito_menos_credito = rDespesasEncerrar.debito_menos_credito;
                    linha.credito_menos_debito = rDespesasEncerrar.credito_menos_debito;

                    if (linha.saldo_conta < 0) then
                        linha.natureza_saldo = 'C';
                    end if;

                    if (linha.natureza_saldo <> rDespesasEncerrar.natureza_saldo_conta) then

                        linha.conta_debito = rTransacao.c47_credito;
                        linha.conta_credito = rTransacao.c47_debito;
                    end if;

                    return next linha;
                end loop;
        end loop;
    return;
end;
$$;


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


drop function if exists fc_abertura_exercicio_lancamento_despesa();
drop type if exists tp_abertura_exercicio_dotacao;
create type tp_abertura_exercicio_dotacao as (
    dotacao integer,
    valor numeric,
    ano numeric
    );
create function fc_abertura_exercicio_lancamento_despesa() returns setof tp_abertura_exercicio_dotacao as
$$
declare


    iAnoUsu              integer;
    iInstit              integer;
    rValoresLancamento   record;
    rtp_valores_abertura tp_abertura_exercicio_dotacao%ROWTYPE;

begin


    iAnoUsu := cast((select fc_getsession('DB_anousu')) as integer);
    iInstit := cast((select fc_getsession('DB_instit')) as integer);

    if iAnoUsu is null then
        raise exception 'ERRO : Variavel de sessao [DB_anousu] nao encontrada.';
    end if;

    if iInstit is null then
        raise exception 'ERRO : Variavel de sessao [DB_instit] nao encontrada.';
    end if;


    for rValoresLancamento in select o58_coddot as dotacao,
                                     o58_valor  as valor,
                                     o58_anousu  as ano
                              from orcamento.orcdotacao

                              where o58_anousu = iAnoUsu
                                and o58_instit = iInstit
                                and o58_valor <> 0
        loop


            rtp_valores_abertura.valor := rValoresLancamento.valor;
            rtp_valores_abertura.dotacao = rValoresLancamento.dotacao;
            rtp_valores_abertura.ano = rValoresLancamento.ano;
            return next rtp_valores_abertura;

        end loop;
    return;
end
$$
language 'plpgsql';


drop function if exists fc_valores_abertura_empenho_rp(integer);
drop type if exists tp_valores_abertura_rp;

create type tp_valores_abertura_rp as (
    empenho                  integer,
    conta_debito             integer,
    conta_credito            integer,
    valor_credito            numeric,
    valor_debito             numeric,
    valor                    numeric
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
            and c47_seqtranslr = 378729
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

            if (rBuscaTransacao.c47_compara = 2 and saldoCalculado > 0) OR (rBuscaTransacao.c47_compara = 1 and saldoCalculado < 0) then

                linha.conta_debito = rBuscaTransacao.c47_credito;
                linha.conta_credito = rBuscaTransacao.c47_debito;
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
                    if (rBuscaTransacao.c47_compara = 2 and saldoCalculado > 0) OR (rBuscaTransacao.c47_compara = 1 and saldoCalculado < 0) then

                        linha.conta_credito = rBuscaTransacao.c47_debito;
                        linha.conta_debito  = rBuscaTransacao.c47_credito;
                    end if;
                    return next linha;

                end loop;

        end loop;
    return ;
end;
$$ language 'plpgsql';




drop function if exists fc_encerramento_ddr();
drop type if exists tp_encerramento_ddr;


create type tp_encerramento_ddr as (
    compara_tipo_recurso integer,
    conta_credito integer,
    conta_debito integer,
    valor numeric
);

create or replace function fc_encerramento_ddr() returns SETOF tp_encerramento_ddr
    language plpgsql
as
$$
declare

    instituicaoSessao integer;
    anoSessao         integer;
    contaComparacao   integer;
    rTransacao        record;
    rRecursoEncerrar  record;
    linha             tp_encerramento_ddr%ROWTYPE;
    contaDebitoLancamento integer;
    contaCreditoLancamento integer;

begin

    instituicaoSessao := fc_getsession('DB_instit');
    anoSessao := fc_getsession('DB_anousu');

    select c47_debito
      into contaComparacao
      from contrans
           join contranslan on c45_seqtrans = c46_seqtrans
           join contranslr on c47_seqtranslan = c46_seqtranslan
     where c45_coddoc = 1022
       and c45_anousu = anoSessao
       and c45_instit = instituicaoSessao;

    for rRecursoEncerrar in

        select o15_tipo as tipo_recurso,
               round(sum(case when conlancamval.c69_debito = contaComparacao then c70_valor else 0 end),2) as valor_debito,
               round(sum(case when conlancamval.c69_credito = contaComparacao then c70_valor else 0 end),2) as valor_credito
          from conlancam
               inner join conlancamval on conlancamval.c69_codlan = conlancam.c70_codlan
               inner join conlancamdoc on conlancamdoc.c71_codlan = conlancam.c70_codlan
               inner join conlancamemp on conlancamemp.c75_codlan = conlancam.c70_codlan
               inner join empempenho   on empempenho.e60_numemp = conlancamemp.c75_numemp
               inner join orcdotacao   on orcdotacao.o58_coddot = empempenho.e60_coddot
                                      and orcdotacao.o58_anousu = empempenho.e60_anousu
               inner join orctiporec on orctiporec.o15_codigo = orcdotacao.o58_codigo
               inner join conhistdoc on c53_coddoc = c71_coddoc
         where conlancam.c70_data between cast('2019' || '-01-01' as date) and cast('2019' || '-12-31' as date)
           and (conlancamval.c69_credito = contaComparacao or conlancamval.c69_debito = contaComparacao)
           and c53_tipo in (30,31)
           and orctiporec.o15_tipo in (1,2)
         group by 1

        union

        select 3 as tipo_recurso,
               round(sum(case when conlancamval.c69_debito = contaComparacao then c70_valor else 0 end),2) as valor_debito,
               round(sum(case when conlancamval.c69_credito = contaComparacao then c70_valor else 0 end),2) as valor_credito
          from conlancam
               inner join conlancamval on conlancamval.c69_codlan = conlancam.c70_codlan
               inner join conlancamdoc on conlancamdoc.c71_codlan = conlancam.c70_codlan
               left join conlancamemp on conlancamemp.c75_codlan = conlancam.c70_codlan
         where conlancam.c70_data between cast('2019' || '-01-01' as date) and cast('2019' || '-12-31' as date)
           and (conlancamval.c69_credito = contaComparacao or conlancamval.c69_debito = contaComparacao)
           and conlancamemp.c75_codlan is null
           and c71_coddoc in (120, 161, 163, 151, 153, 3000)
         group by 1

        loop

        select c47_debito, c47_credito
          into contaDebitoLancamento, contaCreditoLancamento
          from contrans
               join contranslan on c45_seqtrans = c46_seqtrans
               join contranslr on c47_seqtranslan = c46_seqtranslan
         where c45_coddoc = 1022
           and c45_anousu = anoSessao
           and c45_instit = instituicaoSessao
           and c47_ref = rRecursoEncerrar.tipo_recurso
           and c47_compara = 17;

        linha.valor = abs(round(rRecursoEncerrar.valor_debito - rRecursoEncerrar.valor_credito, 2));
        linha.conta_debito = contaDebitoLancamento;
        linha.conta_credito = contaCreditoLancamento;
        linha.compara_tipo_recurso = rRecursoEncerrar.tipo_recurso;
        return next linha;

    end loop;

    return;
end ;
$$;

drop function if exists fc_encerramento_1023()  cascade;
drop type if exists tp_documento_1023 cascade ;

create type tp_documento_1023 as
(
    conta_debito integer,
    conta_credito numeric,
    valor numeric,
    saldo_conta numeric,
    natureza_conta char
);

create or replace function fc_encerramento_1023() returns SETOF tp_documento_1023
    language plpgsql
as
$$
declare

    linha             tp_documento_1023%ROWTYPE;
    rContasEncerrar   record;
    rConsultaTranscao record;

    anoSessao integer;
    instituicaoSessao integer;

    /* valor da conta inicial na tabela conplanoexe */
    valorInicialCredito numeric;
    valorInicialDebito numeric;
begin

    anoSessao := fc_getsession('DB_anousu')::int;
    instituicaoSessao := fc_getsession('DB_instit')::int;

    for rConsultaTranscao in select c47_debito as conta_debito,
                                    c47_credito as conta_credito
                               from contabilidade.contrans
                                    inner join contabilidade.contranslan on contrans.c45_seqtrans = contranslan.c46_seqtrans
                                    inner join contabilidade.contranslr on contranslan.c46_seqtranslan = contranslr.c47_seqtranslan
                                    inner join conplanoreduz on conplanoreduz.c61_reduz = contranslr.c47_debito
                                                            and conplanoreduz.c61_anousu = contranslr.c47_anousu
                                    inner join conplano on conplano.c60_codcon = conplanoreduz.c61_codcon
                                                       and conplano.c60_anousu = conplanoreduz.c61_anousu
                             where c45_coddoc = 1023
                               and c45_anousu = anoSessao
                               and c45_instit = instituicaoSessao
                               and c46_ordem = 1 order by contranslr.c47_seqtranslr
        loop

        for rContasEncerrar in

            select (select case
                               when c60_naturezasaldo = 1 then 'D'
                               when c60_naturezasaldo = 2 then 'C'
                               end
                    from contabilidade.conplanoreduz
                             inner join contabilidade.conplano on conplanoreduz.c61_codcon = conplano.c60_codcon
                        and conplanoreduz.c61_anousu = conplano.c60_anousu
                    where c61_reduz = rConsultaTranscao.conta_debito
                      and c60_anousu = anoSessao
                   )                                                  as natureza_cadastro_conta,
                   coalesce(sum(case when c69_debito = rConsultaTranscao.conta_debito then c70_valor else 0 end), 0) as valor_debito,
                   coalesce(sum(case when c69_credito = rConsultaTranscao.conta_debito then c70_valor else 0 end), 0) as valor_credito
              from conlancam
                   inner join conlancamval on c69_codlan = c70_codlan
                   inner join conlancamdoc on c71_codlan = c70_codlan
             where c70_data between cast(anoSessao || '-01-01' as date) and cast(anoSessao || '-12-31' as date)
               and (c69_debito = rConsultaTranscao.conta_debito or c69_credito = rConsultaTranscao.conta_debito)
        loop

            /* busca os valores iniciais para as contas debito/credito */
            select c62_vlrcre, c62_vlrdeb
              into valorInicialCredito, valorInicialDebito
              from conplanoexe
             where c62_reduz = rConsultaTranscao.conta_debito
               and c62_anousu = anoSessao;

            valorInicialCredito = (valorInicialCredito + rContasEncerrar.valor_credito);
            valorInicialDebito  = (valorInicialDebito + rContasEncerrar.valor_debito);

            linha.saldo_conta = (valorInicialCredito - valorInicialDebito);
            linha.natureza_conta = 'C';
            linha.conta_debito = rConsultaTranscao.conta_debito;
            linha.conta_credito = rConsultaTranscao.conta_credito;
            linha.valor = round(abs(valorInicialCredito - valorInicialDebito), 2);

            if (linha.saldo_conta < 0) then
                linha.natureza_conta = 'D';
            end if;

            if (linha.natureza_conta <> rContasEncerrar.natureza_cadastro_conta) then
                linha.conta_debito = rConsultaTranscao.conta_credito;
                linha.conta_credito = rConsultaTranscao.conta_debito;
            end if;
            return next linha;

            end loop;
        end loop;
    return;
end;
$$























SQL_UP
);


    }
}
