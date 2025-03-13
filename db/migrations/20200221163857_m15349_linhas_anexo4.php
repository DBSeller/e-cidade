<?php

use Classes\PostgresMigration;

class M15349LinhasAnexo4 extends PostgresMigration
{

    public function down()
    {
        $this->execute(<<<SQL_DOWN

delete from orcparamseqorcparamseqcoluna where o116_codparamrel = 196 and o116_codseq >= 111;
delete from orcparamseq where o69_codparamrel = 196 and o69_codseq >= 111;

SQL_DOWN
);
    }

    public function up()
    {
        $this->execute(<<<SQL_UP

delete from orcparamseqorcparamseqcoluna where o116_codparamrel = 196 and o116_codseq >= 111;
delete from orcparamseq where o69_codparamrel = 196 and o69_codseq >= 111;


insert into orcparamseq
select o69_codparamrel,
       111,
       'RECEITAS CORRENTES',
       o69_grupo,
       o69_grupoexclusao,
       o69_nivel,
       o69_libnivel,
       o69_librec,
       o69_libsubfunc,
       o69_libfunc,
       o69_verificaano,
       'RECEITAS CORRENTES',
       o69_manual,
       o69_totalizador,
       111,
       0,
       o69_observacao,
       o69_desdobrarlinha,
       o69_origem
  from orcparamseq
 where o69_codparamrel = 196
   and o69_codseq = 4;

insert into orcparamseqorcparamseqcoluna
     select nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'),
            111,
            o116_codparamrel,
            o116_orcparamseqcoluna,
            o116_ordem,
            o116_periodo,
            o116_formula
       from orcparamseqorcparamseqcoluna
      where o116_codparamrel = 196
        and o116_codseq = 4;



insert into orcparamseq
select o69_codparamrel,
       112,
       'TOTAL DAS RECEITAS DA ADMINISTRA플O RPPS - (XII)',
       o69_grupo,
       o69_grupoexclusao,
       o69_nivel,
       o69_libnivel,
       o69_librec,
       o69_libsubfunc,
       o69_libfunc,
       o69_verificaano,
       'TOTAL DAS RECEITAS DA ADMINISTRA플O RPPS - (XII)',
       o69_manual,
       o69_totalizador,
       112,
       0,
       o69_observacao,
       o69_desdobrarlinha,
       o69_origem
from orcparamseq
where o69_codparamrel = 196
  and o69_codseq = 4;

insert into orcparamseqorcparamseqcoluna
select nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'),
       112,
       o116_codparamrel,
       o116_orcparamseqcoluna,
       o116_ordem,
       o116_periodo,
       o116_formula
from orcparamseqorcparamseqcoluna
where o116_codparamrel = 196
  and o116_codseq = 4;

/* despesa */
insert into orcparamseq
select o69_codparamrel,
       113,
       'DESPESAS CORRENTES (XIII)',
       o69_grupo,
       o69_grupoexclusao,
       o69_nivel,
       o69_libnivel,
       o69_librec,
       o69_libsubfunc,
       o69_libfunc,
       o69_verificaano,
       'DESPESAS CORRENTES (XIII)',
       o69_manual,
       o69_totalizador,
       113,
       0,
       o69_observacao,
       o69_desdobrarlinha,
       o69_origem
from orcparamseq
where o69_codparamrel = 196
  and o69_codseq = 106;

insert into orcparamseqorcparamseqcoluna
select nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'),
       113,
       o116_codparamrel,
       o116_orcparamseqcoluna,
       o116_ordem,
       o116_periodo,
       o116_formula
from orcparamseqorcparamseqcoluna
where o116_codparamrel = 196
  and o116_codseq = 106;

insert into orcparamseq
select o69_codparamrel,
       114,
       'DESPESAS DE CAPITAL (XIV)',
       o69_grupo,
       o69_grupoexclusao,
       o69_nivel,
       o69_libnivel,
       o69_librec,
       o69_libsubfunc,
       o69_libfunc,
       o69_verificaano,
       'DESPESAS DE CAPITAL (XIV)',
       o69_manual,
       o69_totalizador,
       114,
       0,
       o69_observacao,
       o69_desdobrarlinha,
       o69_origem
from orcparamseq
where o69_codparamrel = 196
  and o69_codseq = 106;

insert into orcparamseqorcparamseqcoluna
select nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'),
       114,
       o116_codparamrel,
       o116_orcparamseqcoluna,
       o116_ordem,
       o116_periodo,
       o116_formula
from orcparamseqorcparamseqcoluna
where o116_codparamrel = 196
  and o116_codseq = 106;

insert into orcparamseq
select o69_codparamrel,
       115,
       'TOTAL DAS DESPESAS DA ADMINISTRA플O RPPS (XV) = (XIII + XIV)',
       o69_grupo,
       o69_grupoexclusao,
       o69_nivel,
       o69_libnivel,
       o69_librec,
       o69_libsubfunc,
       o69_libfunc,
       o69_verificaano,
       'TOTAL DAS DESPESAS DA ADMINISTRA플O RPPS (XV) = (XIII + XIV)',
       o69_manual,
       o69_totalizador,
       115,
       0,
       o69_observacao,
       o69_desdobrarlinha,
       o69_origem
from orcparamseq
where o69_codparamrel = 196
  and o69_codseq = 106;

insert into orcparamseqorcparamseqcoluna
select nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'),
       115,
       o116_codparamrel,
       o116_orcparamseqcoluna,
       o116_ordem,
       o116_periodo,
       o116_formula
from orcparamseqorcparamseqcoluna
where o116_codparamrel = 196
  and o116_codseq = 106;

insert into orcparamseq
select o69_codparamrel,
       116,
       'RESULTADO DA ADMINISTRA플O RPPS (XVI) = (XII - XV)',
       o69_grupo,
       o69_grupoexclusao,
       o69_nivel,
       o69_libnivel,
       o69_librec,
       o69_libsubfunc,
       o69_libfunc,
       o69_verificaano,
       'RESULTADO DA ADMINISTRA플O RPPS (XVI) = (XII - XV)',
       o69_manual,
       o69_totalizador,
       116,
       0,
       o69_observacao,
       o69_desdobrarlinha,
       o69_origem
from orcparamseq
where o69_codparamrel = 196
  and o69_codseq = 106;

insert into orcparamseqorcparamseqcoluna
select nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'),
       116,
       o116_codparamrel,
       o116_orcparamseqcoluna,
       o116_ordem,
       o116_periodo,
       o116_formula
from orcparamseqorcparamseqcoluna
where o116_codparamrel = 196
  and o116_codseq = 106;

SQL_UP
);
    }

}
