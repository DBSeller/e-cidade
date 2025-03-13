<?php

use Classes\PostgresMigration;

class M15644Anexo2NovasLinhas extends PostgresMigration
{

    public function up()
    {
        $this->execute(<<<SQL_UP

insert into orcparamseq values ( 183 , 40 ,'(-) Transferências obrigatórias da União relativas às emenda' ,1 ,0 ,1 ,false ,false ,false ,false ,false ,'(-) Transferências obrigatórias da União relativas às emendas individuais  (art 166-A, § 1º , da CF) (V)' ,true ,false ,40 ,3 , '',false ,null);
insert into orcparamseqorcparamseqcoluna
select nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'),
       40,
       183,
       o116_orcparamseqcoluna,
       o116_ordem,
       o116_periodo,
       ''
from orcparamseqorcparamseqcoluna
where o116_codparamrel = 183
  and o116_codseq = 26;


insert into orcparamseq values ( 183 , 41 ,'RECEITA CORRENTE LÍQUIDA AJUSTADA PARA CÁLCULO DOS LIMITES' ,1 ,0 ,1 ,false ,false ,false ,false ,false ,'RECEITA CORRENTE LÍQUIDA AJUSTADA PARA CÁLCULO DOS LIMITES DE ENDIVIDAMENTO (VI)=(IV-V)' ,true ,false ,41 ,0 , '',false ,null);
insert into orcparamseqorcparamseqcoluna
select nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'),
       41,
       183,
       o116_orcparamseqcoluna,
       o116_ordem,
       o116_periodo,
       ''
from orcparamseqorcparamseqcoluna
where o116_codparamrel = 183
  and o116_codseq = 26;



SQL_UP
);
    }

    public function down()
    {
        $this->execute(<<<SQL_DOWN

delete from orcparamseqorcparamseqcoluna where o116_codparamrel = 183 and o116_codseq in (40, 41);
delete from orcparamseq where o69_codparamrel = 183 and o69_codseq in (40, 41);

SQL_DOWN
);
    }
}
