<?php

use Classes\PostgresMigration;

class M17908TransacoesTef extends PostgresMigration
{
    public function up()
    {
        $this->execute(<<<SQL
insert into conhistdoc (c53_coddoc,c53_descr,c53_tipo) values (169, 'RECOLHIMENTO DOS VALORES - TEF', 112);

insert into vinculoeventoscontabeis
values (nextval('contabilidade.vinculoeventoscontabeis_c115_sequencial_seq'), 169, null);

insert into contrans(c45_seqtrans, c45_anousu, c45_coddoc, c45_instit)
values (nextval('contrans_c45_seqtrans_seq'), 2021, 169, 1);

insert into contranslan( c46_seqtranslan ,c46_seqtrans ,c46_codhist ,c46_obs ,c46_valor ,c46_obrigatorio ,c46_evento ,c46_descricao ,c46_ordem )
    values ( nextval('contranslan_c46_seqtranslan_seq') , currval('contrans_c45_seqtrans_seq'), 9800 ,'PRIMEIRO LANCAMENTO' ,0 ,'true' ,0 ,'PRIMEIRO LANCAMENTO' ,1 );
insert into contranslan( c46_seqtranslan ,c46_seqtrans ,c46_codhist ,c46_obs ,c46_valor ,c46_obrigatorio ,c46_evento ,c46_descricao ,c46_ordem )
values ( nextval('contranslan_c46_seqtranslan_seq') , currval('contrans_c45_seqtrans_seq'), 9800 ,'SEGUNDO LANÇAMENTO' ,0 ,'true' ,0 ,'SEGUNDO LANÇAMENTO' ,2 );

insert into conplanoinfocomplementar (c121_sequencial, c121_sigla, c121_descricao, c121_sql, c121_ajuda, c121_nomepropriedade,c121_valorpadrao)
values (58, 'AUT', 'Indicador de Parcela', '', 'Autorização', 'cod_autori', 'NI');

update conplanoinfocomplementar set c121_sql =
'select k198_codigoaprovacao
 from conlancamtef
 inner join operacoesrealizadastef on c137_operacoesrealizadastef = k198_sequencial
 where c137_codlan = codigo_lancamento'
 where c121_sequencial = 58;

 update conplanoinfocomplementar set c121_sql =
'select k198_nsuautorizadora
 from conlancamtef
 inner join operacoesrealizadastef on c137_operacoesrealizadastef = k198_sequencial
 where c137_codlan = codigo_lancamento'
 where c121_sequencial = 54;

insert into conplanosistemaatributos (c129_sequencial, c129_conplanosistema, c129_conplanoinfocomplementar, c129_ordem)
values (126, 32, 58, 5);
SQL
        );
    }

    public function down()
    {
        $this->execute(<<<SQL
delete from conplanosistemaatributos where c129_conplanoinfocomplementar = 58;
delete from vinculoeventoscontabeis where c115_conhistdocinclusao = 169;
delete from conplanoinfocomplementar where c121_sequencial = 58;
delete from contranslan where c46_seqtrans in (
    select c45_seqtrans from contrans where c45_coddoc = 169
);
delete from contrans where c45_coddoc = 169;
delete from conhistdoc where c53_coddoc = 169;

 update conplanoinfocomplementar set c121_sql =
'select k198_nsu
 from conlancamtef
 inner join operacoesrealizadastef on c137_operacoesrealizadastef = k198_sequencial
 where c137_codlan = codigo_lancamento'
 where c121_sequencial = 54;
SQL
        );
    }
}
