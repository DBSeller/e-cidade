<?php

use Classes\PostgresMigration;

class M16565ParametroCustaBoleto extends PostgresMigration
{

    public function up()
    {

        $sSql = <<<SQL

 
CREATE SEQUENCE arrecadacao.conveniocustaboleto_ar49_sequencial_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1 CACHE 1;
create table arrecadacao.conveniocustaboleto (
 
ar49_sequencial int4 NOT NULL DEFAULT nextval('arrecadacao.conveniocustaboleto_ar49_sequencial_seq'),
ar49_conveniocustaboleto int4 NOT NULL,
ar49_descricao varchar(255),
CONSTRAINT conveniocustaboleto_seq_pk PRIMARY KEY (ar49_sequencial)
);
 
insert into arrecadacao.conveniocustaboleto
select nextval('arrecadacao.conveniocustaboleto_ar49_sequencial_seq'),
3 ,
'Convênio das Custas de Boleto' ;
 
insert into cadmodcarne
select 22,
'CONVENIO COBRANCA REGISTRADA',
k47_obs,
k47_altura,
k47_largura,
k47_orientacao,
k47_tipoconvenio
from cadmodcarne
where k47_sequencial = 2;



SQL;

      $this->execute($sSql);

    }

    public function down()
    {

        $sSql = <<<SQL

          drop table arrecadacao.conveniocustaboleto;
          drop sequence conveniocustaboleto_ar49_sequencial_seq;
          delete from cadmodcarne where k47_sequencial = 22 and k47_descr = 'CONVENIO COBRANCA REGISTRADA';

SQL;

      $this->execute($sSql);

    }

}
