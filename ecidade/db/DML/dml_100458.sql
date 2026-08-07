-- #7364
-- Cria tabela temporaria com os casos que precisam ser corrigidos
create table tmp_percentuais_matric_inscr as
select  count(*) ,
          (select count(*) from arrematric m where m.k00_numpre = x.k00_numpre ) as matric,
          (select count(*) from arreinscr  i    where i.k00_numpre = x.k00_numpre ) as inscr,
          k00_numpre,
          sum(k00_perc)::double precision as perc,
          (sum(k00_perc)::double precision  / count(*))::double precision  as div_quantidade,
          (select 'Recibo avulso' from recibo where recibo.k00_numpre = x.k00_numpre limit 1)::text as recibo_avulso,
          ( select 'Pagamento parcial' from abatimentorecibo where k127_numprerecibo = x.k00_numpre )::text as  recibo_pagto_parcial,
          (select k00_tipo from arrecad where arrecad.k00_numpre = x.k00_numpre limit 1) as tipo
  from ( select k00_numpre,
                      k00_perc
              from arrematric
             union all
            select k00_numpre,
                      k00_perc
              from arreinscr
           ) as x
  group by k00_numpre having sum(k00_perc)::double precision <> 100::double precision;

--  Corrige os casos na arrematric
UPDATE arrematric
SET k00_perc = (100::double PRECISION / COUNT::double PRECISION)::double PRECISION
FROM tmp_percentuais_matric_inscr
WHERE arrematric.k00_numpre = tmp_percentuais_matric_inscr.k00_numpre;

--  Corrige arreinscr
UPDATE arreinscr
SET k00_perc = (100::double PRECISION / COUNT::double PRECISION)::double PRECISION
FROM tmp_percentuais_matric_inscr
WHERE arreinscr.k00_numpre = tmp_percentuais_matric_inscr.k00_numpre;

