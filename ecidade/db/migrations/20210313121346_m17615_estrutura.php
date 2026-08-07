<?php

use Classes\PostgresMigration;

class M17615Estrutura extends PostgresMigration
{
    public function up()
    {
        $this->execute(<<<SQL

drop table if exists planejamento.fatorcorrecaoreceita;
drop table if exists planejamento.estimativareceita;
drop table if exists planejamento.cronogramadesembolsoreceita;

create table planejamento.fatorcorrecaoreceita (
  id serial primary key,
  planejamento_id int not null,
  orcfontes_id int not null,
  anoorcamento int not null,
  exercicio int not null,
  percentual numeric not null,
  created_at timestamp,
  updated_at timestamp,
  foreign key (planejamento_id) references planejamento.planejamento on delete cascade,
  foreign key (orcfontes_id, anoorcamento) references orcamento.orcfontes(o57_codfon, o57_anousu)
);

select configuracoes.fc_auditoria_cria_funcao('planejamento.fatorcorrecaoreceita');

create table planejamento.estimativareceita (
  id serial primary key,
  planejamento_id int not null,
  anoorcamento int not null,
  orcfontes_id int not null,
  recurso_id int not null,
  instituicao_id int not null,
  concarpeculiar_id varchar(3) not null,
  orcorgao_id int,
  orcunidade_id int,
  esferaorcamentaria int,
  valorbase numeric(15,2),
  inclusaomanual boolean default false,
  created_at timestamp,
  updated_at timestamp,
  foreign key (planejamento_id) references planejamento.planejamento on delete cascade,
  foreign key (orcfontes_id, anoorcamento) references orcamento.orcfontes(o57_codfon, o57_anousu),
  foreign key (recurso_id) references orcamento.orctiporec,
  foreign key (instituicao_id) references configuracoes.db_config,
  foreign key (concarpeculiar_id) references contabilidade.concarpeculiar,
  foreign key (anoorcamento, orcorgao_id, orcunidade_id) references orcamento.orcunidade(o41_anousu, o41_orgao, o41_unidade)
);

select configuracoes.fc_auditoria_cria_funcao('planejamento.estimativareceita');

create table planejamento.cronogramadesembolsoreceita (
  id serial primary key,
  estimativareceita_id int not null,
  exercicio int not null,
  janeiro numeric(15,2) not null,
  fevereiro numeric(15,2) not null,
  marco numeric(15,2) not null,
  abril numeric(15,2) not null,
  maio numeric(15,2) not null,
  junho numeric(15,2) not null,
  julho numeric(15,2) not null,
  agosto numeric(15,2) not null,
  setembro numeric(15,2) not null,
  outubro numeric(15,2) not null,
  novembro numeric(15,2) not null,
  dezembro numeric(15,2) not null,
  created_at timestamp,
  updated_at timestamp,
  foreign key (estimativareceita_id) references planejamento.estimativareceita on delete cascade
);

select configuracoes.fc_auditoria_cria_funcao('planejamento.cronogramadesembolsoreceita');

SQL
        );
    }

    public function down()
    {
        $this->execute(<<<SQL

drop table if exists planejamento.cronogramadesembolsoreceita;
drop table if exists planejamento.fatorcorrecaoreceita;
drop table if exists planejamento.estimativareceita;

SQL
        );
    }
}
