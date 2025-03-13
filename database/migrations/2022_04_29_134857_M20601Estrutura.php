<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M20601Estrutura extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->upDicionario();
        $this->upEstrutura();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->downDicionario();
        $this->downEstrutura();
    }

    private function upDicionario()
    {
        DB::connection()->getPdo()->exec(<<<SQL
insert into db_sysarquivo
values (1010906, 'acompanhamentocronogramadespesa', 'Acompanhamento do cronograma de desembolso da despesa', 'o119', '2022-04-29', 'Acompanhamento do cronograma de desembolso da desp', 0, 'f', 'f', 'f', 'f' ),
       (1010907, 'acompanhamentocronogramareceita', 'Acompanhamento do cronograma de desembolso da receita', 'o120', '2022-04-29', 'Acompanhamento do cronograma de desembolso da rec', 0, 'f', 'f', 'f', 'f' );

insert into db_sysarqmod
values (35,1010906),
       (35,1010907);

insert into db_syscampo
values (1014037,'dotacao_id','int4','Dotação','0', 'Dotação',10,'f','f','f',1,'text','Dotação'),
       (1014038,'receita_id','int4','Receita','0', 'Receita',10,'f','f','f',1,'text','Receita'),
       (1014079,'base_calculo','int4','Base de cálculo do acompanhamento do cronograma 1 - Saldo Inicial 2 - Previsão Atualizada 3 - Realizado e Reestimado ','0', 'Base de cálculo',10,'f','f','f',1,'text','Base de cálculo');

insert into db_sysarqcamp
values (1010906,1011345,1,0),
       (1010906,1014037,2,0),
       (1010906,15983,3,0),
       (1010906,1014079,4,0),
       (1010906,1012879,5,0),
       (1010906,1012880,6,0),
       (1010906,1012881,7,0),
       (1010906,1012882,8,0),
       (1010906,1012883,9,0),
       (1010906,1012884,10,0),
       (1010906,1012885,11,0),
       (1010906,1012886,12,0),
       (1010906,1012887,13,0),
       (1010906,1012888,14,0),
       (1010906,1012889,15,0),
       (1010906,1012890,16,0),
       (1010907,1011345,1,0),
       (1010907,1014038,2,0),
       (1010907,15983,3,0),
       (1010907,1014079,4,0),
       (1010907,1012879,5,0),
       (1010907,1012880,6,0),
       (1010907,1012881,7,0),
       (1010907,1012882,8,0),
       (1010907,1012883,9,0),
       (1010907,1012884,10,0),
       (1010907,1012885,11,0),
       (1010907,1012886,12,0),
       (1010907,1012887,13,0),
       (1010907,1012888,14,0),
       (1010907,1012889,15,0),
       (1010907,1012890,16,0);

insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010906,1011345,1,1011345);
insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010907,1011345,1,1011345);

insert into db_sysforkey
values (1010906,15983,1,758,0),
       (1010906,1014037,2,758,0),
       (1010907,15983,1,780,0),
       (1010907,1014038,2,780,0);

insert into db_sysindices
values (1008767,'acompanhamentocronogramadespesa',1010906,'1'),
       (1008768,'acompanhamentocronogramareceita',1010907,'1');

insert into db_syscadind
values (1008767,1014037,1),
       (1008767,15983,2),
       (1008768,1014038,1),
       (1008768,15983,2);

insert into db_syssequencia
values (1001052, 'acompanhamentocronogramadespesa_id_seq', 1, 1, 9223372036854775807, 1, 1),
       (1001053, 'acompanhamentocronogramareceita_id_seq', 1, 1, 9223372036854775807, 1, 1);

update db_sysarqcamp set codsequencia = 1001053 where codarq = 1010907 and codcam = 1011345;
update db_sysarqcamp set codsequencia = 1001052 where codarq = 1010906 and codcam = 1011345;
SQL
        );
    }

    private function upEstrutura()
    {
        DB::connection()->getPdo()->exec(<<<SQL
create table orcamento.acompanhamentocronogramadespesa (
    id serial primary key,
    dotacao_id int4 not null,
    exercicio int4 not null,
    base_calculo int4 not null,
    janeiro numeric (15,2) default 0,
    fevereiro numeric (15,2) default 0,
    marco numeric (15,2) default 0,
    abril numeric (15,2) default 0,
    maio numeric (15,2) default 0,
    junho numeric (15,2) default 0,
    julho numeric (15,2) default 0,
    agosto numeric (15,2) default 0,
    setembro numeric (15,2) default 0,
    outubro numeric (15,2) default 0,
    novembro numeric (15,2) default 0,
    dezembro numeric (15,2) default 0,
    foreign key (exercicio, dotacao_id) references orcamento.orcdotacao on delete cascade
);

select configuracoes.fc_auditoria_cria_funcao('orcamento.acompanhamentocronogramadespesa');

create table orcamento.acompanhamentocronogramareceita (
    id serial primary key,
    receita_id int4 not null,
    exercicio int4 not null,
    base_calculo int4 not null,
    janeiro numeric (15,2) default 0,
    fevereiro numeric (15,2) default 0,
    marco numeric (15,2) default 0,
    abril numeric (15,2) default 0,
    maio numeric (15,2) default 0,
    junho numeric (15,2) default 0,
    julho numeric (15,2) default 0,
    agosto numeric (15,2) default 0,
    setembro numeric (15,2) default 0,
    outubro numeric (15,2) default 0,
    novembro numeric (15,2) default 0,
    dezembro numeric (15,2) default 0,
    foreign key (exercicio, receita_id) references orcamento.orcreceita on delete cascade
);

select configuracoes.fc_auditoria_cria_funcao('orcamento.acompanhamentocronogramareceita');

CREATE UNIQUE INDEX acompanhamentocronogramadespesa_dotacao_exercicio_in ON orcamento.acompanhamentocronogramadespesa(dotacao_id, exercicio);
CREATE UNIQUE INDEX acompanhamentocronogramareceita_receita_exercicio_in ON orcamento.acompanhamentocronogramareceita(receita_id, exercicio);
SQL
        );
    }

    private function downDicionario()
    {
        DB::connection()->getPdo()->exec(<<<SQL
delete from db_syssequencia where codsequencia in (1001052, 1001053);
delete from db_syscadind where codind in (1008767, 1008768);
delete from db_sysindices where codind in (1008767, 1008768);
delete from db_sysprikey where codarq in (1010906, 1010907);
delete from db_sysforkey where codarq in (1010906, 1010907);
delete from db_sysarqcamp where codarq in (1010906, 1010907);
delete from db_syscampo where codcam in (1014037, 1014038, 1014079);
delete from db_sysarqmod where codarq in (1010906, 1010907);
delete from db_sysarquivo where codarq in (1010906, 1010907);
SQL
        );
    }

    private function downEstrutura()
    {
        DB::connection()->getPdo()->exec(<<<SQL
drop table orcamento.acompanhamentocronogramadespesa;
drop table orcamento.acompanhamentocronogramareceita;
SQL
        );
    }
}
