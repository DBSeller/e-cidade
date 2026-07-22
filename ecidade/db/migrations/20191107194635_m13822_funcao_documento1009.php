<?php

use Classes\PostgresMigration;

class M13822FuncaoDocumento1009 extends PostgresMigration
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
       $sql = <<<SQL


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
$$




SQL;
      $this->execute($sql);
    }

    public function down()
    {

        $this->execute("
            drop function if exists fc_doc_encerramento_2019() ;
            drop type if exists tp_doc_1009 cascade;
        ");
    }
}
