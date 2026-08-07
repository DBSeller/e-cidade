alter table condominiocgm drop constraint if exists condominiocgm_sequencial_fk;

alter table condominiocgm add foreign key(j106_numcgm) references cgm (z01_numcgm);
