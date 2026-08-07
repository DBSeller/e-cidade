<?php

use Classes\PostgresMigration;

class M10999EsocialTabelasDatas extends PostgresMigration
{

    public function up()
    {
        $this->dicionario();
        $this->alterarEstrutura();
        $this->adicionaCamposCargaFormulario();
        $this->cargaDados();
    }

    public function down()
    {
        $this->execute("delete from db_sysarqcamp where codcam in (1009968, 1009969, 1009972, 1009973, 1009974, 1009975, 1009995, 1009996);");
        $this->execute("
            delete from db_sysforkey where codarq = 4005 and codcam = 1009996;
            delete from db_sysforkey where codarq = 4007 and codcam = 1009995;
        ");
        $this->execute("delete from db_syscampo where codcam in (1009968, 1009969, 1009972, 1009973, 1009974, 1009975, 1009995, 1009996);");

        $this->execute("
            alter table recursoshumanos.gradeshorarios drop column rh190_datainicial;
            alter table recursoshumanos.gradeshorarios drop column rh190_datafinal;
            alter table recursoshumanos.gradeshorarios drop column rh190_instit;
            alter table recursoshumanos.jornada drop column rh188_instit;
            alter table pessoal.rhfuncao drop column rh37_datainicial;
            alter table pessoal.rhfuncao drop column rh37_datafinal;
            alter table pessoal.rhcargo drop column rh04_datainicial;
            alter table pessoal.rhcargo drop column rh04_datafinal;
        ");

        $this->removeCamposCargaFormulario();
        $this->downCargaDados();
    }

    private function dicionario()
    {
        $this->execute("
            insert into db_syscampo values(1009968,'rh190_datainicial','date','Data de inicio da escala.','null', 'Data Inicial',10,'t','f','f',1,'text','Data Inicial');
            insert into db_syscampo values(1009969,'rh190_datafinal','date','Data final da escala','null', 'Data Final',10,'t','f','f',1,'text','Data Final');
            insert into db_syscampo values(1009972,'rh37_datainicial','date','Data de inicio da escala.','null', 'Data Inicial',10,'t','f','f',1,'text','Data Inicial');
            insert into db_syscampo values(1009973,'rh37_datafinal','date','Data final da escala','null', 'Data Final',10,'t','f','f',1,'text','Data Final');
            insert into db_syscampo values(1009974,'rh04_datainicial','date','Data de inicio da escala','null', 'Data Inicial',10,'t','f','f',1,'text','Data Inicial');
            insert into db_syscampo values(1009975,'rh04_datafinal','date','Data final da escala','null', 'Data Final',10,'t','f','f',1,'text','Data Final');
            insert into db_syscampo values(1009995,'rh190_instit','int4','Instituilção','0', 'Instituicao',10,'f','f','f',1,'text','Instituicao');
            insert into db_syscampo values(1009996,'rh188_instit','int4','Instituicao','0', 'Instituicao',10,'f','f','f',1,'text','Instituicao');
        ");

        $this->execute("
            insert into db_sysarqcamp values(1496,1009975,4,0);
            insert into db_sysarqcamp values(1496,1009974,5,0);
            insert into db_sysarqcamp values(1174,1009972,10,0);
            insert into db_sysarqcamp values(1174,1009973,11,0);
            insert into db_sysarqcamp values(4007,1009968,6,0);
            insert into db_sysarqcamp values(4007,1009969,7,0);
            insert into db_sysarqcamp values(4007,1009995,8,0);
            insert into db_sysarqcamp values(4005,1009996,5,0);
        ");

        $this->execute("
            insert into db_sysforkey values(4007,1009995,1,83,0);
            insert into db_sysforkey values(4005,1009996,1,83,0);
        ");
    }

    private function alterarEstrutura()
    {
        $this->execute("
            alter table recursoshumanos.gradeshorarios add column rh190_datainicial date default null;
            alter table recursoshumanos.gradeshorarios add column rh190_datafinal date default null;
            alter table recursoshumanos.gradeshorarios add column rh190_instit int default null;
            alter table pessoal.rhfuncao add column rh37_datainicial date default null;
            alter table pessoal.rhfuncao add column rh37_datafinal date default null;
            alter table pessoal.rhcargo add column rh04_datainicial date default null;
            alter table pessoal.rhcargo add column rh04_datafinal date default null;
            alter table recursoshumanos.jornada add column rh188_instit int default null;
        ");

        $this->execute("
            alter table recursoshumanos.gradeshorarios add constraint gradeshorarios_rh190_instit_fk FOREIGN KEY (rh190_instit) REFERENCES db_config(codigo);
            alter table recursoshumanos.jornada add constraint jornada_rh188_instit_fk FOREIGN KEY (rh188_instit) REFERENCES db_config(codigo);
        ");

        // altera todas escalas existentes para a instituição do servidor
        $this->execute("
            update recursoshumanos.gradeshorarios set rh190_instit = (select case when (select count(*) from (select distinct rh192_instit as instit from escalaservidor) as x) > 0
                                                                               then (select distinct rh192_instit as instit from escalaservidor limit 1)
                                                                          else 1 end as instit);
            update recursoshumanos.jornada set rh188_instit = (select case when (select count(*) from (select distinct rh192_instit as instit from escalaservidor) as x) > 0
                                                                           then (select distinct rh192_instit as instit from escalaservidor limit 1)
                                                                      else 1 end as instit);
        ");

        $this->execute("
            alter table recursoshumanos.gradeshorarios alter column rh190_instit set not null;
            alter table recursoshumanos.jornada alter column rh188_instit set not null;
        ");

        // define a data inicial
        $this->execute("
            update recursoshumanos.gradeshorarios set rh190_datainicial = '2018-08-01';
            
            update pessoal.rhfuncao set rh37_datainicial = '2018-08-01';
            update pessoal.rhfuncao set rh37_datainicial  = '2018-07-30', rh37_datafinal = '2018-07-30' where rh37_ativo is false;
            update pessoal.rhcargo set rh04_datainicial = '2018-08-01';
        ");
    }

    private function cargaDados()
    {
        // Funcoes
        $this->execute(<<<SQL
        update habitacao.avaliacao 
        set db101_cargadados = 'select rhcargo.rh04_codigo as codigo,
        rhcargo.rh04_descr as descricao, rhcargo.rh04_instit as instituicao,
        (select rh37_cbo from rhfuncao where rh37_funcao = rh04_codigo and rh37_instit = rh04_instit) as cbo,
        to_char(rh04_datainicial, \'YYYY-MM\') as inivalid, to_char(rh04_datafinal, \'YYYY-MM\') as fimvalid
          from rhcargo join db_config on db_config.codigo = rhcargo.rh04_instit
        where rhcargo.rh04_instit = fc_getsession(\'DB_instit\') :: int
          and rh04_datainicial >= \'2018-08-01\'
          and (rh04_datafinal is null or rh04_datafinal >= \'2018-08-01\')' 
        where db101_sequencial = 3000018;
SQL
        );

        // Cargo
        $this->execute(<<<SQL
        update habitacao.avaliacao 
        set db101_cargadados ='
        select rh37_funcao as codigo, rh37_descr as nome, rh37_instit as instituicao, rh37_cbo as cbo,
               to_char(rh37_datainicial, \'YYYY-MM\') as inivalid,
               to_char(rh37_datafinal, \'YYYY-MM\') as fimvalid
        from rhfuncao
        where rh37_instit = fc_getsession(\'DB_instit\')::int
          and rh37_datainicial >= \'2018-08-01\'
          and (rh37_datafinal is null or rh37_datafinal >= \'2018-08-01\')'
        where db101_sequencial = 3000017;
SQL
);

        // horario
        $this->execute(<<<SQL
        update habitacao.avaliacao 
        set db101_cargadados ='
        SELECT x.instituicao,
       x.codigo,
       x.descricao,
       x.entrada,
       x.saida,
       x.duracaojornada - x.intervalo AS duracaojornada,
       x.intervalo,
       x.inivalid,
       x.fimvalid
FROM (SELECT instituicao, codigo, descricao, substring(replace(entrada :: TEXT, \':\', \'\'), 1, 4) AS entrada,
             substring(replace(saida :: TEXT, \':\', \'\'), 1, 4) AS saida,
             round(extract(EPOCH FROM (CASE WHEN (saida :: TIME > entrada :: TIME)
                                            THEN (saida - entrada) :: TIME
                                            ELSE ( ((CURRENT_DATE || \' \'||saida) :: TIMESTAMP + INTERVAL \'1 day\') :: TIMESTAMP - (CURRENT_DATE || \' \'|| entrada) :: TIMESTAMP)::TIME
                                        END)::TIME) / 60) AS duracaojornada,
             round(extract(EPOCH FROM (CASE WHEN (intervalo_fim :: TIME > intervalo_inicio :: TIME)
                                            THEN (intervalo_fim - intervalo_inicio) :: TIME
                                            ELSE ( ((CURRENT_DATE || \' \'||intervalo_fim) :: TIMESTAMP + INTERVAL \'1 day\') :: TIMESTAMP - (CURRENT_DATE || \' \'|| intervalo_inicio) :: TIMESTAMP) :: TIME
                                        END)) / 60) AS intervalo,
             inivalid,
             fimvalid
      FROM (SELECT DISTINCT
                   rh190_instit AS instituicao,
                   rh188_sequencial AS codigo,
                   rh188_descricao AS descricao,
                   (SELECT rh189_hora FROM jornadahoras WHERE rh189_jornada = rh188_sequencial AND rh189_tiporegistro = 1) :: TIME AS entrada,
                   (CASE WHEN (SELECT count(rh189_hora) FROM jornadahoras WHERE rh189_jornada = rh188_sequencial AND rh189_tiporegistro = 4 LIMIT 1) = 1
                         THEN (SELECT rh189_hora FROM jornadahoras WHERE rh189_jornada = rh188_sequencial AND rh189_tiporegistro = 4)
                         ELSE (SELECT rh189_hora FROM jornadahoras WHERE rh189_jornada = rh188_sequencial AND rh189_tiporegistro = 2)
                   END) :: TIME AS saida,
                   (CASE WHEN (SELECT count(rh189_hora) FROM jornadahoras WHERE rh189_jornada = rh188_sequencial AND rh189_tiporegistro = 4 LIMIT 1) = 1
                         THEN (SELECT rh189_hora FROM jornadahoras WHERE rh189_jornada = rh188_sequencial AND rh189_tiporegistro = 2)
                         ELSE \'00:00\'
                   END) :: TIME AS intervalo_inicio,
                   (CASE WHEN (SELECT count(rh189_hora) FROM jornadahoras WHERE rh189_jornada = rh188_sequencial AND rh189_tiporegistro = 4 LIMIT 1) = 1
                         THEN (SELECT rh189_hora FROM jornadahoras WHERE rh189_jornada = rh188_sequencial AND rh189_tiporegistro = 3)
                         ELSE \'00:00\'
                   END) :: TIME AS intervalo_fim,
                   to_char(rh190_datainicial, \'YYYY-MM\') as inivalid,
                   to_char(rh190_datafinal, \'YYYY-MM\') as fimvalid
              FROM jornada
              JOIN jornadahoras ON rh188_sequencial = rh189_jornada
              JOIN gradeshorariosjornada ON gradeshorariosjornada.rh191_jornada = jornada.rh188_sequencial
              JOIN gradeshorarios ON gradeshorarios.rh190_sequencial = gradeshorariosjornada.rh191_gradehorarios
              JOIN escalaservidor ON escalaservidor.rh192_gradeshorarios = gradeshorarios.rh190_sequencial
              JOIN rhpessoal ON rhpessoal.rh01_regist = escalaservidor.rh192_regist
             WHERE gradeshorarios.rh190_instit = fc_getsession(\'DB_instit\')::INT
               and rh190_datainicial >= \'2018-08-01\'
               and (rh190_datafinal is null or rh190_datafinal >= \'2018-08-01\')
             GROUP BY codigo, descricao, rh190_datainicial, rh190_datafinal, rh190_instit
             ORDER BY rh188_sequencial
        ) AS y
     ) AS x;
        ' 
        where db101_sequencial = 3000019;
SQL
        );

    }

    public function downCargaDados()
    {
        // Funcoes
        $this->execute(<<<SQL
        update habitacao.avaliacao 
        set db101_cargadados = '
        select rhcargo.rh04_codigo as codigo, rhcargo.rh04_descr as descricao, rhcargo.rh04_instit as instituicao 
        from rhcargo inner join db_config on db_config.codigo = rhcargo.rh04_instit where rhcargo.rh04_instit = fc_getsession(\'DB_instit\')::int
        ' where db101_sequencial = 3000018;
SQL
        );

        // Cargo
        $this->execute(<<<SQL
        update habitacao.avaliacao 
        set db101_cargadados = 
        'select rh37_funcao as codigo, rh37_descr as nome, rh37_instit as instituicao, rh37_cbo as cbo, \'2018-09\' as inivalid from rhfuncao where rh37_ativo is true and rh37_instit = fc_getsession(\'DB_instit\')::int'
        where db101_sequencial = 3000017;
SQL
        );

        // Horarios
        $this->execute(<<<SQL
        update habitacao.avaliacao 
        set db101_cargadados = 
        'SELECT x.instituicao,
               x.codigo,
               x.descricao,
               x.entrada,
               x.saida,
               x.duracaojornada - x.intervalo AS duracaojornada,
               x.intervalo
        FROM (SELECT instituicao, codigo, descricao, substring(replace(entrada :: TEXT, \':\', \'\'), 1, 4) AS entrada,
             substring(replace(saida :: TEXT, \':\', \'\'), 1, 4) AS saida,
             round(extract(EPOCH FROM (CASE WHEN (saida :: TIME > entrada :: TIME)
                                                 THEN (saida - entrada) :: TIME
                                            ELSE (
                     ((CURRENT_DATE || \' \'||saida) :: TIMESTAMP + INTERVAL \'1 day\') :: TIMESTAMP -
                     (CURRENT_DATE || \' \'|| entrada) :: TIMESTAMP) :: TIME END) :: TIME) / 60) AS duracaojornada,
             round(extract(EPOCH
                           FROM (CASE WHEN (intervalo_fim :: TIME > intervalo_inicio :: TIME)
                                           THEN (intervalo_fim - intervalo_inicio) :: TIME ELSE (
                            ((CURRENT_DATE || \' \'||intervalo_fim) :: TIMESTAMP + INTERVAL \'1 day\') :: TIMESTAMP -
                            (CURRENT_DATE || \' \'|| intervalo_inicio) :: TIMESTAMP) :: TIME END)) / 60) AS intervalo
          FROM (SELECT DISTINCT fc_getsession(\'DB_instit\') :: INT AS instituicao,
                                rh188_sequencial AS codigo,
                                rh188_descricao AS descricao,
                                (SELECT rh189_hora
                                 FROM jornadahoras
                                 WHERE rh189_jornada = rh188_sequencial AND rh189_tiporegistro = 1) :: TIME AS entrada,
                                (CASE WHEN (SELECT count(rh189_hora)
                                            FROM jornadahoras
                                            WHERE rh189_jornada = rh188_sequencial AND rh189_tiporegistro = 4
                                            LIMIT 1) = 1
                                           THEN (SELECT rh189_hora
                                                 FROM jornadahoras
                                                 WHERE rh189_jornada = rh188_sequencial AND rh189_tiporegistro = 4)
                                      ELSE (SELECT rh189_hora
                                            FROM jornadahoras
                                            WHERE rh189_jornada = rh188_sequencial AND rh189_tiporegistro = 2)
                                    END) :: TIME AS saida,
                                (CASE WHEN (SELECT count(rh189_hora)
                                            FROM jornadahoras
                                            WHERE rh189_jornada = rh188_sequencial AND rh189_tiporegistro = 4
                                            LIMIT 1) = 1
                                           THEN (SELECT rh189_hora
                                                 FROM jornadahoras
                                                 WHERE rh189_jornada = rh188_sequencial AND rh189_tiporegistro = 2)
                                      ELSE \'00:00\'
                                    END) :: TIME AS intervalo_inicio,
                                (CASE WHEN (SELECT count(rh189_hora)
                                            FROM jornadahoras
                                            WHERE rh189_jornada = rh188_sequencial AND rh189_tiporegistro = 4
                                            LIMIT 1) = 1
                                           THEN (SELECT rh189_hora
                                                 FROM jornadahoras
                                                 WHERE rh189_jornada = rh188_sequencial AND rh189_tiporegistro = 3)
                                      ELSE \'00:00\'
                                    END) :: TIME AS intervalo_fim
                FROM jornada
                            INNER JOIN jornadahoras ON rh188_sequencial = rh189_jornada
                            LEFT JOIN jornadaservidor ON jornadaservidor.rh212_jornada = jornada.rh188_sequencial
                            LEFT JOIN gradeshorariosjornada ON gradeshorariosjornada.rh191_jornada = jornada.rh188_sequencial
                            LEFT JOIN gradeshorarios ON gradeshorarios.rh190_sequencial = gradeshorariosjornada.rh191_gradehorarios
                            LEFT JOIN escalaservidor ON escalaservidor.rh192_gradeshorarios = gradeshorarios.rh190_sequencial
                            LEFT JOIN rhpessoal AS rhpessoaljornada ON rhpessoaljornada.rh01_regist = jornadaservidor.rh212_matricula
                            LEFT JOIN rhpessoal AS rhpessoalescala ON rhpessoalescala.rh01_regist = escalaservidor.rh192_regist
                WHERE (rhpessoalescala.rh01_instit = fc_getsession(\'DB_instit\') :: INT OR
                       rhpessoaljornada.rh01_instit = fc_getsession(\'DB_instit\') :: INT)
                GROUP BY codigo, descricao
                ORDER BY rh188_sequencial) AS y) AS x'
                where db101_sequencial = 3000019;
SQL
);
    }

    private function adicionaCamposCargaFormulario()
    {
        $this->execute("update avaliacaopergunta set db103_camposql = 'inivalid' where db103_sequencial in (3000942, 3000962, 3000974, 3000981)");
        $this->execute("update avaliacaopergunta set db103_camposql = 'fimvalid' where db103_sequencial in (3000943, 3000963, 3000975, 3000982)");
    }

    private function removeCamposCargaFormulario()
    {
        $this->execute("
            update avaliacaopergunta set db103_camposql = '' where db103_sequencial in (3000942, 3000962, 3000974, 3000981, 3000943, 3000963, 3000975, 3000982);
        "); 
    }
}
