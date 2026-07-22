<?php

use Classes\PostgresMigration;

class M11875CargaHorarios extends PostgresMigration
{
    public function up()
    {
        $sql = "
            UPDATE avaliacao SET db101_cargadados = 'SELECT x.instituicao,
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
            ORDER BY rh188_sequencial) AS y) AS x;' 
            WHERE db101_sequencial = 3000019;
        ";
        $this->execute($sql);
    }

    public function down()
    {
        $sql = "
            UPDATE avaliacao SET db101_cargadados = 'SELECT x.instituicao,
       x.codigo,
       x.descricao,
       x.entrada,
       x.saida,
       x.duracaojornada - x.intervalo AS duracaojornada,
       x.intervalo FROM
       (SELECT instituicao, codigo, descricao, substring(replace(entrada::text,\':\', \'\'), 1, 4) AS entrada, 
       substring(replace(saida::text,\':\', \'\'), 1, 4) AS saida, 
       round(extract(epoch FROM (CASE WHEN (saida::time > entrada::time) 
        THEN (saida - entrada)::time 
        ELSE (((CURRENT_DATE || \' \'||saida)::TIMESTAMP + interval \'1 day\')::TIMESTAMP - (CURRENT_DATE || \' \'|| entrada)::TIMESTAMP)::time END)::time) / 60) AS duracaojornada, round(extract(epoch
FROM (CASE WHEN (intervalo_fim::time > intervalo_inicio::time) THEN (intervalo_fim - intervalo_inicio)::time ELSE (((CURRENT_DATE || \' \'||intervalo_fim)::TIMESTAMP + interval \'1 day\')::TIMESTAMP - (CURRENT_DATE || \' \'|| intervalo_inicio)::TIMESTAMP)::time END)) / 60) AS intervalo
   FROM
        (SELECT DISTINCT fc_getsession(\'DB_instit\')::int AS instituicao, 
        rh188_sequencial AS codigo, 
        rh188_descricao AS descricao,
        (SELECT rh189_hora FROM jornadahoras WHERE rh189_jornada = rh188_sequencial AND rh189_tiporegistro = 1)::time AS entrada, 
        (CASE 
           WHEN (SELECT count(rh189_hora) FROM jornadahoras WHERE rh189_jornada = rh188_sequencial AND rh189_tiporegistro = 4 LIMIT 1) = 1
             THEN (SELECT rh189_hora FROM jornadahoras WHERE rh189_jornada = rh188_sequencial AND rh189_tiporegistro = 4) 
           ELSE (SELECT rh189_hora FROM jornadahoras WHERE rh189_jornada = rh188_sequencial AND rh189_tiporegistro = 2) 
         END )::time AS saida, 
        (CASE 
           WHEN (SELECT count(rh189_hora) FROM jornadahoras WHERE rh189_jornada = rh188_sequencial   AND rh189_tiporegistro = 4 LIMIT 1) = 1 
             THEN (SELECT rh189_hora FROM jornadahoras WHERE rh189_jornada = rh188_sequencial AND rh189_tiporegistro = 2) 
           ELSE \'00:00\' 
         END)::time AS intervalo_inicio, 
        (CASE 
           WHEN (SELECT count(rh189_hora) FROM jornadahoras WHERE rh189_jornada = rh188_sequencial AND rh189_tiporegistro = 4 LIMIT 1) = 1 
             THEN (SELECT rh189_hora FROM jornadahoras WHERE rh189_jornada = rh188_sequencial AND rh189_tiporegistro = 3) 
           ELSE \'00:00\' 
         END)::time AS intervalo_fim
 FROM jornada
 INNER JOIN jornadahoras ON rh188_sequencial = rh189_jornada
 where exists (select 1 from jornadaservidor 
                 join rhpessoal on rhpessoal.rh01_regist = jornadaservidor.rh212_matricula  
                where rh01_instit = fc_getsession(\'DB_instit\')::int
                  and rh212_jornada = rh188_sequencial
              )   
 GROUP BY codigo, descricao
 ORDER BY rh188_sequencial) AS y) AS x;' 
            WHERE db101_sequencial = 3000019;
        ";
        $this->execute($sql);
    }
}
