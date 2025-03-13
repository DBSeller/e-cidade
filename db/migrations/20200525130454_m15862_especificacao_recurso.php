<?php

use Classes\PostgresMigration;

class M15862EspecificacaoRecurso extends PostgresMigration
{
    public function up()
    {
        $this->execute(<<<SQL_UP

insert into db_syscampo values(1011314,'o205_sequencial','int4','Sequencial','0', 'Sequencial',10,'f','f','f',1,'text','Sequencial');
insert into db_syscampo values(1011315,'o205_codigo','varchar(10)','Código','', 'Código',10,'f','t','f',0,'text','Código');
insert into db_syscampo values(1011316,'o205_descricao','varchar(100)','Descrição','', 'Descrição',100,'f','t','f',0,'text','Descrição');
insert into db_syscampo values(1011317,'o205_estado','varchar(2)','Estado','', 'Estado',2,'t','t','f',0,'text','Estado');
insert into db_sysarquivo values (1010570, 'recursoespecificacao', 'recursoespecificacao', 'o205', '2020-05-25', 'recursoespecificacao', 0, 'f', 'f', 'f', 'f' );
insert into db_sysarqmod values (35,1010570);
delete from db_sysarqcamp where codarq = 1010570;
insert into db_sysarqcamp values(1010570,1011314,1,0);
insert into db_sysarqcamp values(1010570,1011315,2,0);
insert into db_sysarqcamp values(1010570,1011316,3,0);
insert into db_sysarqcamp values(1010570,1011317,4,0);
insert into db_syssequencia values(1000917, 'recursoespecificacao_o205_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
update db_sysarqcamp set codsequencia = 1000917 where codarq = 1010570 and codcam = 1011314;
delete from db_sysprikey where codarq = 1010570;
insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010570,1011314,1,1011316);
insert into db_sysindices values(1008576,'recursoespecificacao_codigo_in',1010570,'0');
insert into db_syscadind values(1008576,1011315,1);

create table orcamento.recursoespecificacao(
    o205_sequencial serial primary key not null,
    o205_codigo varchar not null,
    o205_descricao varchar not null,
    o205_estado varchar(2) default null
);
create index recursoespecificacao_codigo_in on orcamento.recursoespecificacao(o205_codigo);

insert into orcamento.recursoespecificacao values (1  , '00', 'Ordinários Provenientes de Impostos', 'RJ');
insert into orcamento.recursoespecificacao values (2  , '01', 'Operações de Crédito', 'RJ');
insert into orcamento.recursoespecificacao values (3  , '02', 'Recursos de Convênios', 'RJ');
insert into orcamento.recursoespecificacao values (4  , '03', 'Recursos Próprios Não Financeiros', 'RJ');
insert into orcamento.recursoespecificacao values (5  , '05', 'Contribuição do Salário-Educação', 'RJ');
insert into orcamento.recursoespecificacao values (6  , '06', 'Recursos Destinados à Alimentação Escolar', 'RJ');
insert into orcamento.recursoespecificacao values (7  , '07', 'Recursos do Sistema Único de Saúde', 'RJ');
insert into orcamento.recursoespecificacao values (8  , '08', 'Recursos do Fundo Nacional de Assistência Social', 'RJ');
insert into orcamento.recursoespecificacao values (9  , '10', 'Recursos Vinculados ao Fundo de Mobilidade', 'RJ');
insert into orcamento.recursoespecificacao values (10 , '12', 'Outorga Onerosa do Direito de Construir', 'RJ');
insert into orcamento.recursoespecificacao values (11 , '13', 'Ordinários Não Provenientes de Impostos', 'RJ');
insert into orcamento.recursoespecificacao values (12 , '14', 'Transferências Constitucionais Provenientes de Impostos', 'RJ');
insert into orcamento.recursoespecificacao values (13 , '15', 'Recursos do Fundeb', 'RJ');
insert into orcamento.recursoespecificacao values (14 , '17', 'Outras Transferências da União', 'RJ');
insert into orcamento.recursoespecificacao values (15 , '18', 'Recursos Vinculados à Previdência Municipal', 'RJ');
insert into orcamento.recursoespecificacao values (16 , '36', 'Recursos de Multas de Trânsito', 'RJ');
insert into orcamento.recursoespecificacao values (17 , '37', 'Contribuição sobre a Iluminação Pública', 'RJ');
insert into orcamento.recursoespecificacao values (18 , '38', 'Compensação Financeira pela Exploração e Produção de Petróleo', 'RJ');
insert into orcamento.recursoespecificacao values (19 , '53', 'Taxas e Multas pelo Exercício do Poder de Polícia', 'RJ');
insert into orcamento.recursoespecificacao values (20 , '80', 'Remuneração das Disponibilidades do Tesouro', 'RJ');
insert into orcamento.recursoespecificacao values (21 , '82', 'Recursos Próprios Financeiros', 'RJ');
insert into orcamento.recursoespecificacao values (22 , '83', 'Recursos de Alienação de Bens e Direitos do Patrimônio Público', 'RJ');
insert into orcamento.recursoespecificacao values (23 , '90', 'Recursos do Tesouro - a Definir', 'RJ');
insert into orcamento.recursoespecificacao values (24 , '99', 'Recursos Extraorçamentários', 'RJ');

select setval('recursoespecificacao_o205_sequencial_seq', 10000);

insert into orcamento.recursoespecificacao
     select nextval('recursoespecificacao_o205_sequencial_seq'),
            o15_codigo::varchar,
            o15_descr,
            null
       from orctiporec
      where (o15_datalimite is null or o15_datalimite > current_date)
      order by o15_codigo;

SQL_UP
);

        $row = $this->fetchRow("select uf from db_config order by codigo limit 1;");
        if (strtoupper($row['uf']) === 'RS') {
            $this->execute("update orctiporec set o15_loaespecificacao = o15_codigo;");
        }
    }

    public function down()
    {
        $this->execute(<<<SQL_DOWN

drop table if exists orcamento.recursoespecificacao;
delete from db_sysforkey where codarq = 1010570;
delete from db_sysindices where codarq = 1010570;
delete from db_syscadind where codcam in (1011314,1011315,1011316,1011317);
delete from db_sysprikey where codarq = 1010570;
delete from db_syssequencia where codsequencia = 1000917;
delete from db_sysarqcamp where codarq = 1010570;
delete from db_sysarqmod where codarq = 1010570;
delete from db_sysarquivo where codarq = 1010570;
delete from db_syscampo where codcam in (1011314,1011315,1011316,1011317);




SQL_DOWN
        );
    }
}
