create temp table w_cgmendereco as
  select z07_numcgm,
    min(z07_sequencial) as z07_sequencial
  from cgmendereco
  group by z07_numcgm having count(*) > 1 ;

update cgmendereco
set z07_tipo = 'S'
where z07_sequencial in (select b.z07_sequencial
                         from w_cgmendereco a inner join cgmendereco b on a.z07_numcgm = b.z07_numcgm
                                                                          and a.z07_sequencial <> b.z07_sequencial
                                                                          and b.z07_tipo = 'P'
);