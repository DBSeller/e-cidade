<?php

use Classes\PostgresMigration;

class M11957AcertoIniciais extends PostgresMigration
{
    public function up()
    {
        $sql = <<<STRING
drop table if exists bkp_inicial_11304;
create table bkp_inicial_11304 as

select * from (
  select nextval('inicialmov_v56_codmov_seq') as novo_movimento, v07_parcel,v07_situacao, inicial.*, (select v56_codsit|| ' - ' ||v52_descr  from inicialmov join situacao on v52_codsit = v56_codsit where v56_inicial = inicial.v50_inicial order by 1 desc limit 1) as situacao
    from inicial
         join termoini on termoini.inicial = inicial.v50_inicial
         join termo on termo.v07_parcel = termoini.parcel
         join termoanu on termoanu.v09_parcel = termo.v07_parcel
   where v50_data between '2006-01-01' and '2018-12-31'
     and termo.v07_situacao = 2
   order by 2
) as x where x.situacao = '4 - Inicial Parcelada';

insert into inicialmov select novo_movimento, v50_inicial, 1, '', current_date, 1 from bkp_inicial_11304;

update inicial
   set v50_codmov = novo_movimento
  from bkp_inicial_11304
 where inicial.v50_inicial = bkp_inicial_11304.v50_inicial
   and inicial.v50_codmov = bkp_inicial_11304.v50_codmov
STRING;

        $this->execute($sql);
    }

    public function down()
    {

    }
}
