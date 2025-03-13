<?php

use Classes\PostgresMigration;

class M10999DatasRubricas extends PostgresMigration
{
    public function up()
    {
        // define a data inicial das rubricas
        $this->execute("
            insert into esocialrubricas (eso26_rubrica, eso26_instituicao, eso26_datainicial) 
            select rh27_rubric, rh27_instit, '2018-08-01'::date from rhrubricas
            where not exists (select 1 from esocialrubricas esb where esb.eso26_rubrica = rh27_rubric and esb.eso26_instituicao = rh27_instit); 
            
            update esocialrubricas set eso26_datainicial = '2018-07-30', eso26_datafinal = '2018-07-30' 
              from rhrubricas 
             where eso26_rubrica = rh27_rubric 
               and eso26_instituicao = rh27_instit 
               and rh27_ativo is false;
        ");

        // Rubricas
        $this->execute("
            update habitacao.avaliacao 
            set db101_cargadados =
            'select rh27_rubric as codigorubrica,
                   rh27_rubric as identificador,
                   rh27_instit as instituicao,
                   rh27_descr  as descricaorubrica,
                   to_char(eso26_datainicial, \'YYYY-MM\') as inivalid,
                   to_char(eso26_datafinal, \'YYYY-MM\') as fimvalid
             from rhrubricas
             join esocialrubricas ON eso26_rubrica = rh27_rubric AND eso26_instituicao = rh27_instit
            where
                rh27_instit =  fc_getsession(\'DB_instit\') :: int
                and eso26_datainicial >= \'2018-08-01\'
                and (eso26_datafinal is null or eso26_datafinal >= \'2018-08-01\')'
            where db101_sequencial = 3000016;"
        );
    }

    public function down()
    {
        // Rubricas
        $this->execute(<<<SQL
        update habitacao.avaliacao 
        set db101_cargadados =
        'select rh27_rubric as codigorubrica, rh27_rubric as identificador,  rh27_instit as instituicao, rh27_descr as descricaorubrica from rhrubricas where rh27_ativo = true AND rh27_instit = fc_getsession(\'DB_instit\')::int'
        where db101_sequencial = 3000016;
SQL
        );
    }
}
