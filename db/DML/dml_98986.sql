select fc_putsession('DB_instit', coalesce((select codigo from db_config where prefeitura is true limit 1), 1)::varchar);

drop table if exists w_arrecad_4262;
create table w_arrecad_4262 as select *
                                    from arrecad
                                   where k00_tipo = 34
                                     and k00_valor = 0;

update arrecad set k00_valor = arreforo.k00_valor
  from w_arrecad_4262
       inner join arreforo on w_arrecad_4262.k00_numpre = arreforo.k00_numpre
                          and w_arrecad_4262.k00_numpar = arreforo.k00_numpar
                          and w_arrecad_4262.k00_receit = arreforo.k00_receit
 where arrecad.k00_numpre = w_arrecad_4262.k00_numpre
   and arrecad.k00_numpar = w_arrecad_4262.k00_numpar
   and arrecad.k00_receit = w_arrecad_4262.k00_receit;