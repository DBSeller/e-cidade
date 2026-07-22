<?php

use Classes\PostgresMigration;

class M17614Estrutura extends PostgresMigration
{
    public function up()
    {
        $this->execute(<<<SQL
CREATE TABLE planejamento.detalhamentoiniciativa (
  pl20_codigo SERIAL PRIMARY KEY,
  pl20_anoorcamento int not null,
  pl20_iniciativaprojativ int not null,
  pl20_instituicao int not null,
  pl20_orcorgao int not null,
  pl20_orcunidade int not null,
  pl20_orcfuncao int not null,
  pl20_orcsubfuncao int not null,
  pl20_orcelemento int not null,
  pl20_recurso int  not null,
  pl20_concarpeculiar varchar(3) not null,
  pl20_subtitulo int not null,
  pl20_esferaorcamentaria int ,
  pl20_valorbase numeric(15, 2) default 0,
  created_at timestamp,
  updated_at timestamp,
  foreign key (pl20_iniciativaprojativ) references planejamento.iniciativaprojativ,
  foreign key (pl20_anoorcamento, pl20_orcorgao) references orcamento.orcorgao(o40_anousu, o40_orgao),
  foreign key (pl20_anoorcamento, pl20_orcorgao, pl20_orcunidade) references orcamento.orcunidade(o41_anousu, o41_orgao, o41_unidade),
  foreign key (pl20_orcelemento, pl20_anoorcamento) references orcamento.orcelemento(o56_codele, o56_anousu),
  foreign key (pl20_recurso) references orcamento.orctiporec,
  foreign key (pl20_orcfuncao) references orcamento.orcfuncao,
  foreign key (pl20_subtitulo) references orcamento.ppasubtitulolocalizadorgasto,
  foreign key (pl20_orcsubfuncao) references orcamento.orcsubfuncao,
  foreign key (pl20_concarpeculiar) references contabilidade.concarpeculiar,
  foreign key (pl20_instituicao) references configuracoes.db_config
);
select configuracoes.fc_auditoria_cria_funcao('planejamento.detalhamentoiniciativa');

create table planejamento.cronogramadesembolsodespesa (
    id SERIAL PRIMARY KEY,
    detalhamentoiniciativa_id int,
    exercicio int,
    janeiro numeric(15,2),
    fevereiro numeric(15,2),
    marco numeric(15,2),
    abril numeric(15,2),
    maio numeric(15,2),
    junho numeric(15,2),
    julho numeric(15,2),
    agosto numeric(15,2),
    setembro numeric(15,2),
    outubro numeric(15,2),
    novembro numeric(15,2),
    dezembro numeric(15,2),
    created_at timestamp,
    updated_at timestamp,
    foreign key (detalhamentoiniciativa_id) references planejamento.detalhamentoiniciativa
);
select configuracoes.fc_auditoria_cria_funcao('planejamento.cronogramadesembolsodespesa');
SQL
        );
    }

    public function down()
    {
        $this->execute(<<<SQL
drop table if exists planejamento.cronogramadesembolsodespesa;
drop table if exists planejamento.detalhamentoiniciativa;
SQL
        );
    }
}
