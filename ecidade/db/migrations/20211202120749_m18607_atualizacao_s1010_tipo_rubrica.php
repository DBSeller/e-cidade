<?php

use Classes\PostgresMigration;

class M18607AtualizacaoS1010TipoRubrica extends PostgresMigration
{
    public function up()
    {
        $sqlUpdate = pg_escape_string("
        select
            rh27_rubric as codigorubrica,
            rh27_rubric as identificador,
            rh27_instit as instituicao,
            rh27_descr as descricaorubrica,
            CASE
                WHEN rh27_rubric IN ('R984', 'R975') THEN 3003775
                WHEN rh27_pd = 1 THEN 3003778
                WHEN rh27_pd = 2 THEN 3003777
                WHEN rh27_pd = 3 THEN 3003776
            ELSE null
            end as tprubr,
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
            update avaliacaopergunta set db103_camposql = 'tprubr' where db103_sequencial = 3000946;
            update avaliacao set db101_cargadados = '{$sqlUpdate}' where db101_sequencial = 3000016;
SQL
        );
    }

    public function down()
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
            update avaliacaopergunta set db103_camposql = '' where db103_sequencial = 3000946;
            update avaliacao set db101_cargadados = '{$sqlUpdate}' where db101_sequencial = 3000016
SQL
        );
    }
}
