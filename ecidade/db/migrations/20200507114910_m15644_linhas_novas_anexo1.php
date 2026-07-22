<?php

use Classes\PostgresMigration;

class M15644LinhasNovasAnexo1 extends PostgresMigration
{
    public function up()
    {
        $this->execute(<<<SQL_UP

insert into orcparamseq values ( 182 , 24 ,'(-)Ajuste de IRRF conforme IN13/2018 TCE-RS' ,1 ,0 ,1 ,false ,false ,false ,false ,false ,'(-)Ajuste de IRRF conforme IN13/2018 TCE-RS' ,true ,false ,24 ,3 , 'EMITE SOMENTE QUANDO FOR IN13',false ,2);
insert into orcparamseqorcparamseqcoluna
     select nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'),
            24,
            182,
            o116_orcparamseqcoluna,
            o116_ordem,
            o116_periodo,
            o116_formula
       from orcparamseqorcparamseqcoluna
      where o116_codparamrel = 182
        and o116_codseq = 5;


insert into orcparamseq values ( 182 , 25 ,'(-) Ajuste pela Transf Financeira inativos pagos pelo Tesour' ,1 ,0 ,1 ,false ,false ,false ,false ,false ,'(-) Ajuste pela Transf Financeira inativos pagos pelo Tesouro conforme IN 13/2018 TCE-RS' ,true ,false ,25 ,3 , 'EMITE SOMENTE QUANDO FOR IN13',false ,2);
insert into orcparamseqorcparamseqcoluna
select nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'),
       25,
       182,
       o116_orcparamseqcoluna,
       o116_ordem,
       o116_periodo,
       o116_formula
from orcparamseqorcparamseqcoluna
where o116_codparamrel = 182
  and o116_codseq = 15;


insert into orcparamseq values ( 182 , 26 ,'1/3 Férias Pessoal Ativo (Parecer nº 9/2013/TCE - Proc. Nº06' ,1 ,0 ,1 ,false ,false ,false ,false ,false ,'1/3 Férias Pessoal Ativo (Parecer nº 9/2013/TCE - Proc. Nº06.00128/2013)' ,true ,false ,26 ,3 , 'EMITE SOMENTE QUANDO FOR PORTO VELHO',false ,2);
insert into orcparamseqorcparamseqcoluna
select nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'),
       26,
       182,
       o116_orcparamseqcoluna,
       o116_ordem,
       o116_periodo,
       o116_formula
from orcparamseqorcparamseqcoluna
where o116_codparamrel = 182
  and o116_codseq = 15;


insert into orcparamseq values ( 182 , 27 ,'(-) Transferências obrigatórias da União' ,1 ,0 ,1 ,false ,false ,false ,false ,false ,'(-) Transferências obrigatórias da União relativas às emendas de bancada  (art 166, § 16 , da CF) (VI)' ,true ,false ,27 ,3 , '',false ,2);
insert into orcparamseqorcparamseqcoluna
select nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'),
       27,
       182,
       o116_orcparamseqcoluna,
       o116_ordem,
       o116_periodo,
       o116_formula
from orcparamseqorcparamseqcoluna
where o116_codparamrel = 182
  and o116_codseq = 18;

SQL_UP
);
    }

    public function down()
    {
        $this->execute(<<<SQL_DOWN

delete from orcparamseqorcparamseqcoluna where o116_codparamrel = 182 and o116_codseq in (24,25,26,27);
delete from orcparamseq where o69_codparamrel = 182 and o69_codseq in (24,25,26,27);

SQL_DOWN
);
    }
}
