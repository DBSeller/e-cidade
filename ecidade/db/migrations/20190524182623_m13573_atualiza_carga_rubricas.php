<?php

use Classes\PostgresMigration;

class M13573AtualizaCargaRubricas extends PostgresMigration
{
    public function up()
    {
        $this->execute(<<<SQL
            update avaliacaopergunta set db103_camposql = 'codinccp' where db103_sequencial = 3000947;
            update avaliacaopergunta set db103_camposql = 'codincirrf' where db103_sequencial = 3000948;
            update avaliacaopergunta set db103_camposql = 'codincfgts' where db103_sequencial = 3000949;
            update avaliacaopergunta set db103_camposql = 'codincsind' where db103_sequencial = 3000950;
            update avaliacaopergunta set db103_camposql = 'natrubr' where db103_sequencial = 3000945;

            update avaliacao set db101_cargadados =
            'select
            rh27_rubric as codigorubrica,
            rh27_rubric as identificador,
            rh27_instit as instituicao,
            rh27_descr as descricaorubrica,
            to_char(eso26_datainicial, ''YYYY-MM'') as inivalid,
            to_char(eso26_datafinal, ''YYYY-MM'') as fimvalid,
            eso26_avaliacaoperguntaopcaocodinccp as codinccp,
            eso26_avaliacaoperguntaopcaocodincirrf as codincirrf,
            eso26_avaliacaoperguntaopcaocodincfgts as codincfgts,
            eso26_avaliacaoperguntaopcaocodincsind as codincsind,
            eso26_natureza as natrubr
          from
            rhrubricas
            join esocialrubricas ON eso26_rubrica = rh27_rubric
            AND eso26_instituicao = rh27_instit
          where
            rh27_instit = fc_getsession(''DB_instit'') :: int
            and eso26_datainicial >= ''2018-08-01''
            and (
              eso26_datafinal is null
              or eso26_datafinal >= ''2018-08-01''
            )
'
        where db101_sequencial = 3000016;
SQL
        );
    }

    public function down()
    {
        $this->execute(<<<SQL
            update avaliacaopergunta set db103_camposql = '' where db103_sequencial in (3000947, 3000948, 3000949, 3000950, 3000945);

            update avaliacao set db101_cargadados =
            'select
                rh27_rubric as codigorubrica,
                rh27_rubric as identificador,
                rh27_instit as instituicao,
                rh27_descr as descricaorubrica,
                to_char(eso26_datainicial, ''YYYY-MM'') as inivalid,
                to_char(eso26_datafinal, ''YYYY-MM'') as fimvalid
            from
                rhrubricas
                join esocialrubricas ON eso26_rubrica = rh27_rubric
                AND eso26_instituicao = rh27_instit
            where
                rh27_instit = fc_getsession(''DB_instit'') :: int
                and eso26_datainicial >= ''2018-08-01''
                and (
                    eso26_datafinal is null
                    or eso26_datafinal >= ''2018-08-01''
                )'
            where db101_sequencial = 3000016;
SQL
        );
    }
}
