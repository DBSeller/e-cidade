<?php

use Classes\PostgresMigration;

class M18444AlteradoTamanhoTituloProjetoAtividade extends PostgresMigration
{
    public function up()
    {
        $this->execute(<<<SQL
update db_syscampo set conteudo = 'varchar(80)', tamanho = 80  where codcam = 5269;
drop view v_suplementacao_despesa;
alter table orcamento.orcprojativ alter o55_descr type varchar(80);
SQL
        );

        $this->recriaView();
    }

    public function down()
    {
        $this->execute(<<<SQL
update db_syscampo set conteudo = 'varchar(40)', tamanho = 40  where codcam = 5269;
drop view v_suplementacao_despesa;
alter table orcamento.orcprojativ alter o55_descr type varchar(40);
SQL
        );

        $this->recriaView();
    }

    public function recriaView()
    {
        $this->execute(<<<SQL
create or replace view v_suplementacao_despesa as
 select orcsuplem.o46_codlei       as sequencial_projeto,
        orcorgao.o40_orgao         as orgao,
        orcorgao.o40_descr         as descricao_orgao,
        orcunidade.o41_unidade     as unidade,
        orcunidade.o41_descr       as descricao_unidade,
        orcfuncao.o52_funcao       as funcao,
        orcfuncao.o52_descr        as descricao_funcao,
        orcsubfuncao.o53_subfuncao as subfuncao,
        orcsubfuncao.o53_descr     as descricao_subfuncao,
        orcprograma.o54_programa   as programa,
        orcprograma.o54_descr      as descricao_programa,
        orcprojativ.o55_projativ   as projativ,
        orcprojativ.o55_descr      as descricao_projativ,
        substr(fc_estruturaldespesa(orcelemento.o56_elemento),3,10) as elemento_despesa,
        orcelemento.o56_elemento   as elemento,
        orcelemento.o56_descr      as descricao_elemento,
        orctiporec.o15_recurso     as recurso,
        orcdotacao.o58_coddot      as dotacao,
        orcsuplemval.o47_valor     as valor_suplementacao
   from orcsuplemval
        inner join orcdotacao   on orcdotacao.o58_anousu      = orcsuplemval.o47_anousu
                               and orcdotacao.o58_coddot      = orcsuplemval.o47_coddot
        inner join orcorgao     on orcorgao.o40_anousu        = orcdotacao.o58_anousu
                               and orcorgao.o40_orgao         = orcdotacao.o58_orgao
        inner join orcunidade   on orcunidade.o41_anousu      = orcdotacao.o58_anousu
                               and orcunidade.o41_orgao       = orcdotacao.o58_orgao
                               and orcunidade.o41_unidade     = orcdotacao.o58_unidade
        inner join orcprograma  on orcprograma.o54_anousu     = orcdotacao.o58_anousu
                               and orcprograma.o54_programa   = orcdotacao.o58_programa
        inner join orcprojativ  on orcprojativ.o55_anousu     = orcdotacao.o58_anousu
                               and orcprojativ.o55_projativ   = orcdotacao.o58_projativ
        inner join orcelemento  on orcelemento.o56_codele     = orcdotacao.o58_codele
                               and orcelemento.o56_anousu     = orcdotacao.o58_anousu
        inner join orctiporec   on orctiporec.o15_codigo      = orcdotacao.o58_codigo
        inner join orcfuncao    on orcfuncao.o52_funcao       = orcdotacao.o58_funcao
        inner join orcsubfuncao on orcsubfuncao.o53_subfuncao = orcdotacao.o58_subfuncao
        inner join orcsuplem    on orcsuplem.o46_codsup       = orcsuplemval.o47_codsup
        inner join orcprojeto   on orcprojeto.o39_codproj     = orcsuplem.o46_codlei;
SQL
        );
    }
}
