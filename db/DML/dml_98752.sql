
update cgm set z01_nomecomple = z01_nome
  from cgmfisico
 where z04_numcgm = z01_numcgm
   and z01_nomecomple = '';

update cgm set z01_nomecomple = z01_nome
  from cgmfisico
 where z04_numcgm = z01_numcgm
   and z01_nomecomple is null;