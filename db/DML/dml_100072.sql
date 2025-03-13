drop table if exists w_percentuais_pagamento_parcial_6502;

create temp table w_percentuais_pagamento_parcial_6502 as select k127_numprerecibo,
       k127_numpreoriginal,
       (select count(k00_matric) from arrematric where arrematric.k00_numpre = k127_numprerecibo) as matricula,
       (select count(k00_inscr) from arreinscr where arreinscr.k00_numpre = k127_numprerecibo) as inscricao,
       (select array_accum(distinct k00_numpre) from recibopaga where k00_numnov = k127_numpreoriginal) as numpre_origem
  from abatimentorecibo
       inner join abatimento on k127_abatimento = k125_sequencial
 where k125_tipoabatimento = 1
 group by k127_numprerecibo, k127_numpreoriginal
having (select count(k00_matric) from arrematric where arrematric.k00_numpre = k127_numprerecibo) > 1
    or (select count(k00_inscr) from arreinscr where arreinscr.k00_numpre = k127_numprerecibo) > 1;

update arrematric set k00_perc = am.k00_perc
  from w_percentuais_pagamento_parcial_6502
       inner join arrematric am on am.k00_numpre = any(numpre_origem)
 where matricula > 1
   and array_length(numpre_origem, 1) = 1
   and arrematric.k00_matric = am.k00_matric
   and arrematric.k00_numpre = k127_numprerecibo;

update arreinscr set k00_perc = ai.k00_perc
  from w_percentuais_pagamento_parcial_6502
       inner join arreinscr ai on ai.k00_numpre = any(numpre_origem)
 where inscricao > 1
   and array_length(numpre_origem, 1) = 1
   and arreinscr.k00_inscr = ai.k00_inscr
   and arreinscr.k00_numpre = k127_numprerecibo;

drop table if exists w_percentuais_pagamento_parcial_6502;
