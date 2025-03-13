alter table itbi.itbiintermediador alter column it35_cgm drop default;

alter table itbi.itbiintermediador alter column it35_cgm type integer using (it35_cgm::integer);