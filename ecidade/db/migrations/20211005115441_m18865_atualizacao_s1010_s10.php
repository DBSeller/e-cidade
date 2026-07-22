<?php

use Classes\PostgresMigration;

class M18865AtualizacaoS1010S10 extends PostgresMigration
{
    public function up()
    {
        $this->atualizaEstrutura();

        $this->deletaGrupo(3000221);
        $this->criaPerguntas();
        $this->deletaPergunta(3000950);
        $this->ordenaPerguntas();
        $opcoes = [3003789, 3003790, 3003798];
        foreach ($opcoes as $opcao) {
            $this->deletaOpcaoPergunta($opcao);
        }

        $this->upCarga();
    }

    public function down()
    {
        $this->retornaFormulario();
        $perguntas = [4000297,4000298];
        foreach ($perguntas as $pergunta) {
            $this->deletaPergunta($pergunta);
        }
        $this->retornaOpcoes();
        $this->retornaEstrutura();
        $this->downCarga();
    }

    public function atualizaEstrutura()
    {
        $sql = <<<SQL

            ALTER TABLE esocial.esocialrubricas drop CONSTRAINT esocialrubricas_avaliacaoperguntaopcao_db104_sequencial_fk_5;
            ALTER TABLE esocial.esocialrubricas drop column eso26_avaliacaoperguntaopcaocodincsind;

            alter table esocial.esocialrubricas add column eso26_avaliacaoperguntaopcaocodinccprp integer;
            alter table esocial.esocialrubricas add column eso26_avaliacaoperguntaopcaocodtetoremun varchar(20) default null;

            insert into configuracoes.db_syscampo values(1013454,'eso26_avaliacaoperguntaopcaocodinccprp','int4','Campo de incidência de RPPS/Regime Militar','0', 'Incidência RPPS/Regime Militar',10,'f','f','f',1,'text','Incidência RPPS/Regime Militar');
            insert into configuracoes.db_syscampo values(1013457,'eso26_avaliacaoperguntaopcaocodtetoremun','varchar(20)','Informa se a rubrica pertence ao teto remuneratório específico (art. 37, XI, da CF/1988).','', 'Teto remuneratório específico',20,'t','t','f',0,'text','Teto remuneratório específico');
            delete from configuracoes.db_sysarqcamp where codarq = 1010325;
            insert into configuracoes.db_sysarqcamp values(1010325,1009986,1,1000770);
            insert into configuracoes.db_sysarqcamp values(1010325,1009987,2,0);
            insert into configuracoes.db_sysarqcamp values(1010325,1009988,3,0);
            insert into configuracoes.db_sysarqcamp values(1010325,1009989,4,0);
            insert into configuracoes.db_sysarqcamp values(1010325,1009991,5,0);
            insert into configuracoes.db_sysarqcamp values(1010325,1009992,6,0);
            insert into configuracoes.db_sysarqcamp values(1010325,1009994,7,0);
            insert into configuracoes.db_sysarqcamp values(1010325,1010003,8,0);
            insert into configuracoes.db_sysarqcamp values(1010325,1010004,9,0);
            insert into configuracoes.db_sysarqcamp values(1010325,1013457,10,0);
            insert into configuracoes.db_sysarqcamp values(1010325,1013454,11,0);

            delete from configuracoes.db_sysforkey where codarq = 1010325 and referen = 2985;
            insert into configuracoes.db_sysforkey values(1010325,1009989,3,2985,0);
            insert into configuracoes.db_sysforkey values(1010325,1009991,4,2985,0);
            insert into configuracoes.db_sysforkey values(1010325,1009992,5,2985,0);
SQL;
        $this->execute($sql);
    }

    public function retornaEstrutura()
    {
        $sql = <<<SQL

            ALTER TABLE esocial.esocialrubricas add column eso26_avaliacaoperguntaopcaocodincsind integer;
            ALTER TABLE esocial.esocialrubricas add CONSTRAINT esocialrubricas_avaliacaoperguntaopcao_db104_sequencial_fk_5 FOREIGN KEY (eso26_avaliacaoperguntaopcaocodincsind) REFERENCES habitacao.avaliacaoperguntaopcao (db104_sequencial);

            delete from configuracoes.db_sysarqcamp where codcam in (1013454, 1013457);
            delete from configuracoes.db_syscampo where codcam in (1013454, 1013457);

            alter table esocial.esocialrubricas drop column eso26_avaliacaoperguntaopcaocodinccprp;
            alter table esocial.esocialrubricas drop column eso26_avaliacaoperguntaopcaocodtetoremun;

            delete from configuracoes.db_sysforkey where codarq = 1010325 and referen = 2985;
            insert into configuracoes.db_sysforkey values(1010325,1009989,3,2985,0);
            insert into configuracoes.db_sysforkey values(1010325,1009991,4,2985,0);
            insert into configuracoes.db_sysforkey values(1010325,1009992,5,2985,0);
            insert into configuracoes.db_sysforkey values(1010325,1009993,6,2985,0);


            delete from configuracoes.db_sysarqcamp where codarq = 1010325;
            insert into configuracoes.db_sysarqcamp values(1010325,1009986,1,1000770);
            insert into configuracoes.db_sysarqcamp values(1010325,1009987,2,0);
            insert into configuracoes.db_sysarqcamp values(1010325,1009988,3,0);
            insert into configuracoes.db_sysarqcamp values(1010325,1009989,4,0);
            insert into configuracoes.db_sysarqcamp values(1010325,1009991,5,0);
            insert into configuracoes.db_sysarqcamp values(1010325,1009992,6,0);
            insert into configuracoes.db_sysarqcamp values(1010325,1009994,7,0);
            insert into configuracoes.db_sysarqcamp values(1010325,1010003,8,0);
            insert into configuracoes.db_sysarqcamp values(1010325,1010004,9,0);
            insert into configuracoes.db_sysarqcamp values(1010325,1009993,10,0);
SQL;
        $this->execute($sql);
    }

    public function deletaGrupo($db102_sequencial)
    {
        $sql = <<<SQL
            delete from habitacao.avaliacaogrupoperguntaresposta where db108_avaliacaoresposta in (select db106_sequencial from avaliacaoresposta where db106_avaliacaoperguntaopcao in(select db104_sequencial from avaliacaoperguntaopcao where db104_avaliacaopergunta in (select db103_sequencial from avaliacaopergunta where db103_avaliacaogrupopergunta = ${db102_sequencial})));
            delete from habitacao.avaliacaoresposta where db106_avaliacaoperguntaopcao in(select db104_sequencial from avaliacaoperguntaopcao where db104_avaliacaopergunta in (select db103_sequencial from avaliacaopergunta where db103_avaliacaogrupopergunta = ${db102_sequencial}));
            delete from habitacao.avaliacaoperguntaopcao where db104_avaliacaopergunta in (select db103_sequencial from avaliacaopergunta where db103_avaliacaogrupopergunta = ${db102_sequencial});
            delete from habitacao.avaliacaopergunta where db103_avaliacaogrupopergunta = ${db102_sequencial};
            delete from habitacao.avaliacaogrupopergunta where db102_sequencial = ${db102_sequencial};
SQL;
        $this->execute($sql);
    }


    private function criaPerguntas()
    {
        $sql = <<<SQL
            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ,db103_somenteleitura ) values ( 4000297 ,1 ,3000217 ,'Código de incidência da rubrica para as contribuições do Regime Próprio de Previdência Social - RPPS/regime militar.' ,'contribuicao_regime_proprio_previdencia_social' ,'false' ,'true' ,8 ,1 ,'' ,0 ,'false' ,'codinccprp' ,'codIncCPRP' ,'false' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001374 ,4000297 ,'00 - Não é base de cálculo de contribuições devidas ao RPPS/regime militar' ,'00_codIncCPRP' ,'false' ,0 ,'00' ,'codIncCPRP_00' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001375 ,4000297 ,'11 - Base de cálculo de contribuições devidas ao RPPS/regime militar' ,'11_codIncCPRP' ,'false' ,0 ,'11' ,'codIncCPRP_11' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001376 ,4000297 ,'12 - Base de cálculo de contribuições devidas ao RPPS/regime militar - 13º salário' ,'12_codIncCPRP' ,'false' ,0 ,'12' ,'codIncCPRP_12' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001377 ,4000297 ,'31 - Contribuição descontada do segurado e beneficiário' ,'31_codIncCPRP' ,'false' ,0 ,'31' ,'codIncCPRP_31' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001378 ,4000297 ,'32 - Contribuição descontada do segurado e beneficiário - 13º salário' ,'32_codIncCPRP' ,'false' ,0 ,'32' ,'codIncCPRP_32' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001379 ,4000297 ,'91 - Suspensão de incidência em decorrência de decisão judicial' ,'91_codIncCPRP' ,'false' ,0 ,'91' ,'codIncCPRP_91' );
            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ,db103_somenteleitura ) values ( 4000298 ,1 ,3000217 ,'Informar se a rubrica compõe o teto remuneratório específico (art. 37, XI, da CF/1988)' ,'rubrica_compoe_remuneratorio' ,'false' ,'true' ,9 ,1 ,'' ,0 ,'false' ,'tetoremun' ,'tetoRemun' ,'false' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001380 ,4000298 ,'Sim' ,'S_tetoRemun' ,'false' ,0 ,'S' ,'tetoRemun_S' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001381 ,4000298 ,'Não' ,'N_tetoRemun' ,'false' ,0 ,'N' ,'tetoRemun_N' );
SQL;
        $this->execute($sql);

    }

    private function deletaPergunta($db103_sequencial) {
        $sql = <<<SQL
            delete from habitacao.avaliacaogrupoperguntaresposta where db108_avaliacaoresposta in (select db106_sequencial from avaliacaoresposta where db106_avaliacaoperguntaopcao in(select db104_sequencial from avaliacaoperguntaopcao where db104_avaliacaopergunta in (select db103_sequencial from avaliacaopergunta where db103_sequencial = {$db103_sequencial})));
            delete from habitacao.avaliacaoresposta where db106_avaliacaoperguntaopcao in(select db104_sequencial from avaliacaoperguntaopcao where db104_avaliacaopergunta in ({$db103_sequencial}));
            delete from esocial.esocialrubricas where eso26_avaliacaoperguntaopcaocodinccp in (select db104_sequencial from avaliacaoperguntaopcao where db104_avaliacaopergunta in ({$db103_sequencial}));
            delete from habitacao.avaliacaoperguntaopcao where db104_avaliacaopergunta in (select db103_sequencial from avaliacaopergunta where db103_sequencial = {$db103_sequencial});
            delete from habitacao.avaliacaopergunta where db103_sequencial = {$db103_sequencial};

SQL;
        $this->execute($sql);

    }

    private function deletaOpcaoPergunta($db104_sequencial) {
        $sql = <<<SQL
            delete from esocial.esocialrubricas where eso26_avaliacaoperguntaopcaocodinccp in ({$db104_sequencial});
            delete from habitacao.avaliacaogrupoperguntaresposta where db108_avaliacaoresposta in (select db106_sequencial from avaliacaoresposta where db106_avaliacaoperguntaopcao in({$db104_sequencial}));
            delete from habitacao.avaliacaoresposta where db106_avaliacaoperguntaopcao in({$db104_sequencial});
            delete from habitacao.avaliacaoperguntaopcao where db104_sequencial in ({$db104_sequencial});
SQL;
        $this->execute($sql);

    }


    private function retornaFormulario()
    {
        $sql = <<<SQL
        insert into habitacao.avaliacaogrupopergunta( db102_sequencial ,db102_avaliacao ,db102_descricao ,db102_identificador ,db102_identificadorcampo ) values
        ( 3000221 ,3000016 ,'Esta rubrica possui processo determinando a não incidência de contrinbuição sindical?' ,'esta-rubrica-possui-processo-determin5a2ae1cb42eac' ,'ideProcessoSIND' );
        insert into habitacao.avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values
        ( 3000959 ,2 ,3000221 ,'Número do processo judicial:' ,'numero-do-processo-judicial5a2ae1cb437fe' ,'false' ,'true' ,1 ,1 ,'' ,0 ,'false' ,'' ,'nrProc' );
        insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values
        ( 3003866 ,3000959 ,'' ,'5a2ae1cb44a79' ,'true' ,0 ,'' ,'ideProcessoSIND_nrProc' );

        update habitacao.avaliacaogrupopergunta set db102_ordem = 6  where db102_sequencial = 3000221;

        insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3000950 ,1 ,3000217 ,'Código de incidência tributária da rubrica para a contribuição sindical laboral:' ,'codigo-de-incidencia-tributaria-da-ru5a2ae1cb2d733' ,'true' ,'true' ,12 ,1 ,'' ,0 ,'false' ,'codincsind' ,'codIncSIND' );
        insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003852 ,3000950 ,'91 - Incidência suspensa em decorrência de decisão judicial' ,'91-incidencia-suspensa-em-decorrenc5a2ae1cb2ea57' ,'false' ,0 ,'91' ,'codIncSIND_91' );
        insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003853 ,3000950 ,'31 - Valor da contribuição sindical laboral descontada' ,'31-valor-da-contribuicao-sindical-laboral-desconta' ,'false' ,0 ,'31' ,'codIncSIND_31' );
        insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003854 ,3000950 ,'11 - Base de cálculo;' ,'11-base-de-calculo' ,'false' ,0 ,'11' ,'codIncSIND_11' );
        insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003855 ,3000950 ,'00 - Não é base de cálculo' ,'00-nao-e-base-de-calculo' ,'false' ,0 ,'00' ,'codIncSIND_00' );
SQL;

        $this->execute($sql);
    }

    private function ordenaPerguntas()
    {
        $sql = <<<SQL
            update habitacao.avaliacaopergunta set db103_ordem = 1 where db103_sequencial = 3000944;
            update habitacao.avaliacaopergunta set db103_ordem = 2 where db103_sequencial = 3000945;
            update habitacao.avaliacaopergunta set db103_ordem = 3 where db103_sequencial = 3000946;
            update habitacao.avaliacaopergunta set db103_ordem = 4 where db103_sequencial = 3000947;
            update habitacao.avaliacaopergunta set db103_ordem = 5 where db103_sequencial = 3000948;
            update habitacao.avaliacaopergunta set db103_ordem = 6 where db103_sequencial = 3000949;
            update habitacao.avaliacaopergunta set db103_ordem = 7 where db103_sequencial = 4000297;
            update habitacao.avaliacaopergunta set db103_ordem = 8 where db103_sequencial = 4000298;
            update habitacao.avaliacaopergunta set db103_ordem = 9 where db103_sequencial = 3000951;
SQL;

        $this->execute($sql);
    }

    private function retornaOpcoes()
    {
        $sql = <<<SQL
            insert into avaliacaoperguntaopcao(db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo) values (3003789 ,3000947 ,'Auxilio doença mensal - Regime Próprio de Previdência Social. (Salário de Contribuição)' ,'auxilio-doenca-mensal-regime-proprio-de-previdenci' ,'false' ,0 ,'23' ,'codIncCP_23' );
            insert into avaliacaoperguntaopcao(db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo) values (3003790 ,3000947 ,'Auxilio doença 13º salário doença - Regime próprio de previdência. (Salário de Contribuição)' ,'auxilio-doenca-13o-salario-doenca-regime-proprio-d' ,'false' ,0 ,'24' ,'codIncCP_24' );
            insert into avaliacaoperguntaopcao(db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo) values (3003798 ,3000947 ,'Outros - Complemento de salário-mínimo - Regime próprio de previdência social' ,'outros-complemento-de-salariominimo-regime-proprio' ,'false' ,0 ,'61' ,'codIncCP_61' );
SQL;
        $this->execute($sql);
    }

    private function downCarga()
    {
        $sqlUpdate = pg_escape_string("
        select
            rh27_rubric as codigorubrica,
            rh27_rubric as identificador,
            rh27_instit as instituicao,
            rh27_descr as descricaorubrica,
            to_char(eso26_datainicial, 'YYYY-MM') as inivalid,
            to_char(eso26_datafinal, 'YYYY-MM') as fimvalid,
            eso26_avaliacaoperguntaopcaocodinccp as codinccp,
            eso26_avaliacaoperguntaopcaocodincirrf as codincirrf,
            eso26_avaliacaoperguntaopcaocodincfgts as codincfgts,
            eso26_avaliacaoperguntaopcaocodincsind as codincsind,
            eso26_natureza as natrubr
          from
            rhrubricas
            join esocialrubricas ON eso26_rubrica = rh27_rubric
            AND eso26_instituicao = rh27_instit
          where rh27_instit = fc_getsession('DB_instit') :: int
            and rh27_ativo is true
            and eso26_datainicial >= '2018-08-01'
            and (
              eso26_datafinal is null
              or eso26_datafinal >= '2018-08-01'
            )
        ");
        $this->execute(<<<SQL
            update avaliacao set db101_cargadados = '{$sqlUpdate}' where db101_sequencial = 3000016
SQL
            );
    }

    private function upCarga()
    {
        $sqlUpdate = pg_escape_string("
        select
            rh27_rubric as codigorubrica,
            rh27_rubric as identificador,
            rh27_instit as instituicao,
            rh27_descr as descricaorubrica,
            to_char(eso26_datainicial, 'YYYY-MM') as inivalid,
            to_char(eso26_datafinal, 'YYYY-MM') as fimvalid,
            eso26_avaliacaoperguntaopcaocodinccp as codinccp,
            eso26_avaliacaoperguntaopcaocodincirrf as codincirrf,
            eso26_avaliacaoperguntaopcaocodincfgts as codincfgts,
            eso26_avaliacaoperguntaopcaocodinccprp as codinccprp,
            eso26_avaliacaoperguntaopcaocodtetoremun as tetoremun,
            eso26_natureza as natrubr
          from
            rhrubricas
            join esocialrubricas ON eso26_rubrica = rh27_rubric
            AND eso26_instituicao = rh27_instit
          where rh27_instit = fc_getsession('DB_instit') :: int
            and rh27_ativo is true
            and eso26_datainicial >= '2018-08-01'
            and (
              eso26_datafinal is null
              or eso26_datafinal >= '2018-08-01'
            )
        ");
        $this->execute(<<<SQL
            update avaliacao set db101_cargadados = '{$sqlUpdate}' where db101_sequencial = 3000016
SQL
            );
    }
}
