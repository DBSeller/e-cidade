<?php

use Classes\PostgresMigration;

class M19410AlteracaoEncerramentoExxercicio extends PostgresMigration
{

    public function up()
    {
        $sql = <<<SQL


insert into conencerramentotipo select 9, 'Enc de Execução Orçamentária da Despesa';

update conhistdoc set c53_tipo = 1000 where c53_coddoc in (2030,2031);

insert into conhistdoctipo (c57_sequencial,c57_descricao) values (1500,'Encerramento Execução Orçamentária da Despesa');

insert into conhistdoc (c53_coddoc,c53_descr,c53_tipo) values (1024,'ENCERRAMENTO DE VALORES A LIQUIDAR DO EXERCÍCIO',1500);
insert into conhistdoc (c53_coddoc,c53_descr,c53_tipo) values (1025,'ENCERRAMENTO DE VALORES EM LIQUIDAÇÃO DO EXERCÍCIO',1500);
insert into conhistdoc (c53_coddoc,c53_descr,c53_tipo) values (1026,'ENCERRAMENTO DE VALORES LIQUIDADOS A PAGAR DO EXERCÍCIO',1500);

insert into conhist (c50_codhist,c50_compl,c50_descr) values (9599,true,'ENCERRAMENTO DA EXECUÇÃO ORÇAMENTÁRIA DA DESPESA');

insert into conhistdocregra (c92_sequencial,c92_conhistdoc,c92_descricao,c92_regra,c92_anousu) values (nextval('conhistdocregra_c92_sequencial_seq'),1024,'ORIGEM DOS DADOS DOC 1024','select * from fc_valores_encerramento_empenho_rp(\'6221301%\', false)',2021);
insert into conhistdocregra (c92_sequencial,c92_conhistdoc,c92_descricao,c92_regra,c92_anousu) values (nextval('conhistdocregra_c92_sequencial_seq'),1025,'ORIGEM DOS DADOS DOC 1025','select * from fc_valores_encerramento_empenho_rp(\'6221302%\', false)',2021);
insert into conhistdocregra (c92_sequencial,c92_conhistdoc,c92_descricao,c92_regra,c92_anousu) values (nextval('conhistdocregra_c92_sequencial_seq'),1026,'ORIGEM DOS DADOS DOC 1026','select * from fc_valores_encerramento_empenho_rp(\'6221303%\', false)',2021);

update conhistdocregra set c92_regra = 'select * from fc_valores_encerramento_empenho_rp(\'6221305%\', false)' where c92_conhistdoc = 1011 and c92_anousu >= 2021;
update conhistdocregra set c92_regra = 'select * from fc_valores_encerramento_empenho_rp(\'6221306%\', false)' where c92_conhistdoc = 1012 and c92_anousu >= 2021;
update conhistdocregra set c92_regra = 'select * from fc_valores_encerramento_empenho_rp(\'6221307%\', false)' where c92_conhistdoc = 1013 and c92_anousu >= 2021;

insert into vinculoeventoscontabeis (c115_sequencial,c115_conhistdocinclusao,c115_conhistdocestorno) values (nextval('vinculoeventoscontabeis_c115_sequencial_seq'),1024,null);
insert into vinculoeventoscontabeis (c115_sequencial,c115_conhistdocinclusao,c115_conhistdocestorno) values (nextval('vinculoeventoscontabeis_c115_sequencial_seq'),1025,null);
insert into vinculoeventoscontabeis (c115_sequencial,c115_conhistdocinclusao,c115_conhistdocestorno) values (nextval('vinculoeventoscontabeis_c115_sequencial_seq'),1026,null);


create temp table w_istituicoes as

select nextval('contrans_c45_seqtrans_seq') as contrans,
       nextval('contranslan_c46_seqtranslan_seq') as contranslan,
       currval('contrans_c45_seqtrans_seq') as chave_contrans,
       2021 as ano,
       1024 as coddoc,
       codigo,
       9599 as historico,
       'PRIMEIRO LANÇAMENTO' as descricao
 from db_config

  union all

  select nextval('contrans_c45_seqtrans_seq') as contrans,
         nextval('contranslan_c46_seqtranslan_seq') as contranslan,
         currval('contrans_c45_seqtrans_seq') as chave_contrans,
         2021 as ano,
         1025 as coddoc,
         codigo,
         9599 as historico,
         'PRIMEIRO LANÇAMENTO' as descricao
    from db_config

 union all

 select nextval('contrans_c45_seqtrans_seq') as contrans,
        nextval('contranslan_c46_seqtranslan_seq') as contranslan,
        currval('contrans_c45_seqtrans_seq') as chave_contrans,
        2021 as ano,
        1026 as coddoc,
        codigo,
        9599 as historico,
        'PRIMEIRO LANÇAMENTO' as descricao
   from db_config
 order by codigo;


insert into contrans select contrans,
                            ano,
                            coddoc,
                            codigo
                       from w_istituicoes;

insert into contranslan select contranslan,
                               chave_contrans,
                               historico,
                               descricao,
                               0,
                               true,
                               0,
                               descricao,
                               1
                        from w_istituicoes
                        where contrans = chave_contrans;




  CREATE OR REPLACE FUNCTION public.fc_abertura_exercicio_transferencia_saldos_rp(tipo integer)
    RETURNS SETOF tp_abertura_exercicio_transferencia_saldos_rp
    LANGUAGE 'plpgsql'
AS \$BODY\$
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
             and e60_anousu = ' || iAnoUsu - 1 || '
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
\$BODY\$;



SQL;
        $this->execute($sql);
    }


    public function down()
    {
        $sql = <<<SQL


delete from vinculoeventoscontabeis where c115_conhistdocinclusao in (1024, 1025, 1026);
delete from contranslan where c46_codhist = 9599;
delete from contrans where c45_coddoc in (1024, 1025, 1026);
delete from conhistdocregra where c92_conhistdoc in (1024, 1025, 1026);
delete from conhist where c50_codhist = 9599;
update conhistdoc set c53_tipo = 2000 where c53_coddoc in (2030, 2031);
delete from conhistdoc where c53_coddoc in (1024, 1025, 1026 );
delete from conhistdoctipo where c57_sequencial = 1500;
delete from conencerramentotipo where c43_sequencial = 9;



CREATE OR REPLACE FUNCTION public.fc_abertura_exercicio_transferencia_saldos_rp(tipo integer)
    RETURNS SETOF tp_abertura_exercicio_transferencia_saldos_rp
    LANGUAGE 'plpgsql'
AS \$BODY\$
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
\$BODY\$;



SQL;
        $this->execute($sql);
    }
}
