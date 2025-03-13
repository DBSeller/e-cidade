<?php

use Classes\PostgresMigration;

class M16716AjusteOrcsubfuncao extends PostgresMigration
{

    public function up()
    {

        $sSql = <<<SQL

        drop view if exists v_suplementacao_despesa;
        ALTER TABLE orcsubfuncao ALTER COLUMN o53_descr TYPE varchar(255);

        update db_syscampo set conteudo = 'varchar(255)', tamanho = 255 where codcam = 5257;

        create view v_suplementacao_despesa as 
        SELECT orcsuplem.o46_codlei AS sequencial_projeto,
               orcorgao.o40_orgao AS orgao,
               orcorgao.o40_descr AS descricao_orgao,
               orcunidade.o41_unidade AS unidade,
               orcunidade.o41_descr AS descricao_unidade,
               orcfuncao.o52_funcao AS funcao,
               orcfuncao.o52_descr AS descricao_funcao,
               orcsubfuncao.o53_subfuncao AS subfuncao,
               orcsubfuncao.o53_descr AS descricao_subfuncao,
               orcprograma.o54_programa AS programa,
               orcprograma.o54_descr AS descricao_programa,
               orcprojativ.o55_projativ AS projativ,
               orcprojativ.o55_descr AS descricao_projativ,
               substr(fc_estruturaldespesa(orcelemento.o56_elemento), 3, 10) AS elemento_despesa,
               orcelemento.o56_elemento AS elemento,
               orcelemento.o56_descr AS descricao_elemento,
               orctiporec.o15_loaespecificacao AS recurso,
               orcdotacao.o58_coddot AS dotacao,
               orcsuplemval.o47_valor AS valor_suplementacao
          FROM orcsuplemval
            JOIN orcdotacao ON orcdotacao.o58_anousu = orcsuplemval.o47_anousu 
                           AND orcdotacao.o58_coddot = orcsuplemval.o47_coddot
            JOIN orcorgao ON orcorgao.o40_anousu = orcdotacao.o58_anousu 
                         AND orcorgao.o40_orgao = orcdotacao.o58_orgao
            JOIN orcunidade ON orcunidade.o41_anousu = orcdotacao.o58_anousu 
                           AND orcunidade.o41_orgao = orcdotacao.o58_orgao 
                           AND orcunidade.o41_unidade = orcdotacao.o58_unidade
            JOIN orcprograma ON orcprograma.o54_anousu = orcdotacao.o58_anousu 
                            AND orcprograma.o54_programa = orcdotacao.o58_programa
            JOIN orcprojativ ON orcprojativ.o55_anousu = orcdotacao.o58_anousu 
                            AND orcprojativ.o55_projativ = orcdotacao.o58_projativ
            JOIN orcelemento ON orcelemento.o56_codele = orcdotacao.o58_codele 
                            AND orcelemento.o56_anousu = orcdotacao.o58_anousu
            JOIN orctiporec ON orctiporec.o15_codigo = orcdotacao.o58_codigo
            JOIN orcfuncao ON orcfuncao.o52_funcao = orcdotacao.o58_funcao
            JOIN orcsubfuncao ON orcsubfuncao.o53_subfuncao = orcdotacao.o58_subfuncao
            JOIN orcsuplem ON orcsuplem.o46_codsup = orcsuplemval.o47_codsup
            JOIN orcprojeto ON orcprojeto.o39_codproj = orcsuplem.o46_codlei;


SQL;
        
       $this->execute($sSql);

    }


    public function down()
    {

        $sSql = <<<SQL

        drop view if exists v_suplementacao_despesa;
        ALTER TABLE orcsubfuncao ALTER COLUMN o53_descr TYPE varchar(40);

        update db_syscampo set conteudo = 'varchar(40)', tamanho = 40 where codcam = 5257;

        create view v_suplementacao_despesa as 
        SELECT orcsuplem.o46_codlei AS sequencial_projeto,
               orcorgao.o40_orgao AS orgao,
               orcorgao.o40_descr AS descricao_orgao,
               orcunidade.o41_unidade AS unidade,
               orcunidade.o41_descr AS descricao_unidade,
               orcfuncao.o52_funcao AS funcao,
               orcfuncao.o52_descr AS descricao_funcao,
               orcsubfuncao.o53_subfuncao AS subfuncao,
               orcsubfuncao.o53_descr AS descricao_subfuncao,
               orcprograma.o54_programa AS programa,
               orcprograma.o54_descr AS descricao_programa,
               orcprojativ.o55_projativ AS projativ,
               orcprojativ.o55_descr AS descricao_projativ,
               substr(fc_estruturaldespesa(orcelemento.o56_elemento), 3, 10) AS elemento_despesa,
               orcelemento.o56_elemento AS elemento,
               orcelemento.o56_descr AS descricao_elemento,
               orctiporec.o15_loaespecificacao AS recurso,
               orcdotacao.o58_coddot AS dotacao,
               orcsuplemval.o47_valor AS valor_suplementacao
          FROM orcsuplemval
            JOIN orcdotacao ON orcdotacao.o58_anousu = orcsuplemval.o47_anousu 
                           AND orcdotacao.o58_coddot = orcsuplemval.o47_coddot
            JOIN orcorgao ON orcorgao.o40_anousu = orcdotacao.o58_anousu 
                         AND orcorgao.o40_orgao = orcdotacao.o58_orgao
            JOIN orcunidade ON orcunidade.o41_anousu = orcdotacao.o58_anousu 
                           AND orcunidade.o41_orgao = orcdotacao.o58_orgao 
                           AND orcunidade.o41_unidade = orcdotacao.o58_unidade
            JOIN orcprograma ON orcprograma.o54_anousu = orcdotacao.o58_anousu 
                            AND orcprograma.o54_programa = orcdotacao.o58_programa
            JOIN orcprojativ ON orcprojativ.o55_anousu = orcdotacao.o58_anousu 
                            AND orcprojativ.o55_projativ = orcdotacao.o58_projativ
            JOIN orcelemento ON orcelemento.o56_codele = orcdotacao.o58_codele 
                            AND orcelemento.o56_anousu = orcdotacao.o58_anousu
            JOIN orctiporec ON orctiporec.o15_codigo = orcdotacao.o58_codigo
            JOIN orcfuncao ON orcfuncao.o52_funcao = orcdotacao.o58_funcao
            JOIN orcsubfuncao ON orcsubfuncao.o53_subfuncao = orcdotacao.o58_subfuncao
            JOIN orcsuplem ON orcsuplem.o46_codsup = orcsuplemval.o47_codsup
            JOIN orcprojeto ON orcprojeto.o39_codproj = orcsuplem.o46_codlei;
       




SQL;
        
        $this->execute($sSql);
    }
    
}
