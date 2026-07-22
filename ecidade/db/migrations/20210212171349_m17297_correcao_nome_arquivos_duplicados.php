<?php

use Classes\PostgresMigration;

class M17297CorrecaoNomeArquivosDuplicados extends PostgresMigration
{
    public function up()
    {

      $this->execute(<<<SQL
create temp table w_acertoacordocomissaomembro as
select sequencia,
       (nome||'_'||sequencia||'.'||extensao) as novonome
  from (
  select nome,
         extensao,
         unnest(aseq) as sequencia,
         minseq
  from (select ac07_nomearquivo,
               split_part(ac07_nomearquivo, '.', 1) as nome,
               split_part(ac07_nomearquivo, '.', 2) as extensao,
               min(ac07_sequencial) as minseq,
               count(*),
               array_agg(ac07_sequencial) as aseq
         from acordos.acordocomissaomembro
        where ac07_nomearquivo <> ''
        group by 1
        having count(*) > 1) as x
) as y
where minseq <> sequencia;
     
update acordos.acordocomissaomembro
   set ac07_nomearquivo = novonome
  from w_acertoacordocomissaomembro
 where ac07_sequencial = sequencia;
 
create temp table w_acertoliccomissao as
select sequencia,
       (nome||'_'||sequencia||'.'||extensao) as novonome
  from (
  select nome,
         extensao,
         unnest(aseq) as sequencia,
         minseq
  from (select l30_nomearquivo,
               split_part(l30_nomearquivo, '.', 1) as nome,
               split_part(l30_nomearquivo, '.', 2) as extensao,
               min(l30_codigo) as minseq,
               count(*),
               array_agg(l30_codigo) as aseq
         from licitacao.liccomissao
        where l30_nomearquivo <> ''
          and l30_nomearquivo is not null
        group by 1
        having count(*) > 1) as x
) as y
where minseq <> sequencia;

update licitacao.liccomissao
   set l30_nomearquivo = novonome
  from w_acertoliccomissao
 where l30_codigo = sequencia;

create temp table w_acertoacordodocumento as
select sequencia,
       (nome||'_'||sequencia||'.'||extensao) as novonome
  from (
  select nome,
         extensao,
         unnest(aseq) as sequencia,
         minseq
  from (select ac40_nomearquivo,
               split_part(ac40_nomearquivo, '.', 1) as nome,
               split_part(ac40_nomearquivo, '.', 2) as extensao,
               min(ac40_sequencial) as minseq,
               count(*),
               array_agg(ac40_sequencial) as aseq
         from acordos.acordodocumento
        where ac40_nomearquivo <> ''
          and ac40_nomearquivo is not null
        group by 1
        having count(*) > 1) as x
) as y
where minseq <> sequencia;
     
update acordos.acordodocumento
   set ac40_nomearquivo = novonome
  from w_acertoacordodocumento
 where ac40_sequencial = sequencia;

create temp table w_acertoliclicitaeventodocumento as
select sequencia,
       (nome||'_'||sequencia||'.'||extensao) as novonome
  from (
  select nome,
         extensao,
         unnest(aseq) as sequencia,
         minseq
  from (select l47_nomearquivo,
               split_part(l47_nomearquivo, '.', 1) as nome,
               split_part(l47_nomearquivo, '.', 2) as extensao,
               min(l47_sequencial) as minseq,
               count(*),
               array_agg(l47_sequencial) as aseq
         from licitacao.liclicitaeventodocumento
        where l47_nomearquivo <> ''
          and l47_nomearquivo is not null
        group by 1
        having count(*) > 1) as x
) as y
where minseq <> sequencia;

update licitacao.liclicitaeventodocumento
   set l47_nomearquivo = novonome
  from w_acertoliclicitaeventodocumento
 where l47_sequencial = sequencia;

SQL
);
    }

    public function down()
    {
      return;       
    }
  
}