drop table if exists w_assentamentos_duplicados;
create table w_assentamentos_duplicados as select count(*), trim(h12_assent) as h12_assent from tipoasse group by trim(h12_assent) having count(*) > 1;
update tipoasse set h12_assent = trim(h12_assent) where trim(h12_assent) not in (select h12_assent from w_assentamentos_duplicados);
