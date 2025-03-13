<?php

use Classes\PostgresMigration;

class M13591AcertoCargaRubricaEsocial extends PostgresMigration
{
    public function up()
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
}
