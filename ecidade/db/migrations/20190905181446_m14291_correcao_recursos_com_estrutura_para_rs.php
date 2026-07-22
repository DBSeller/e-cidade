<?php

use Classes\PostgresMigration;

class M14291CorrecaoRecursosComEstruturaParaRs extends PostgresMigration
{

    public function up()
    {

        $row = $this->fetchRow("select uf from db_config where prefeitura is true");
        if (strtoupper($row['uf']) !== 'RS') {
            return true;
        }

        $this->execute(<<<SQL_UP

drop table if exists bkp_db_estruturavalor;
create table bkp_db_estruturavalor as
      select *
        from db_estruturavalor
       where db121_db_estrutura = (select o50_estruturarecurso from  orcparametro where o50_anousu = 2019);

insert into db_estruturavalor values (90000000, (select o50_estruturarecurso from  orcparametro where o50_anousu = 2019), '00000', 'APAGAR', null, 1, null);

update orctiporec set o15_db_estruturavalor = 90000000, o15_codtri = lpad(o15_codigo, 4, '0');
delete from db_estruturavalor where db121_sequencial in (select db121_sequencial from bkp_db_estruturavalor);

insert into db_estruturavalor
     select nextval('db_estruturavalor_db121_sequencial_seq'),
            (select o50_estruturarecurso from  orcparametro where o50_anousu = 2019),
            o15_codtri,
            o15_descr,
            null,
            0,
            0
       from orctiporec;

update orctiporec
   set o15_db_estruturavalor = db121_sequencial
  from db_estruturavalor
 where db_estruturavalor.db121_estrutural::varchar = o15_codtri::varchar;

delete from db_estruturavalor where db121_sequencial = 90000000;
drop table if exists bkp_db_estruturavalor;

SQL_UP
);
    }

    public function down()
    {

    }
}
