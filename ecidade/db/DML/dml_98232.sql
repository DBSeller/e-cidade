select setval('orcparamrel_o42_codparrel_seq', (select coalesce((select max(o42_codparrel) from orcparamrel having max(o42_codparrel) >= 100000), 100000)));

create temp table orcparamrel_acerto as select o42_codparrel as antigo, nextval('orcparamrel_o42_codparrel_seq') as novo from orcparamrel where o42_codparrel > 149 and o42_codparrel < 99999;
alter table conrelinfo disable trigger all;
alter table orcparamfontes disable trigger all;
alter table orcparamrelnota disable trigger all;
alter table orcparamrelperiodos disable trigger all;
alter table orcparamseq disable trigger all;
alter table orcparamseqfiltrousuario disable trigger all;

update conrelinfo set c83_codrel = novo from orcparamrel_acerto where c83_codrel = antigo;
update orcparamfontes set o43_codparrel = novo from orcparamrel_acerto where o43_codparrel = antigo;
update orcparamrelnota set o42_codparrel = novo from orcparamrel_acerto where o42_codparrel = antigo;
update orcparamrelperiodos set o113_orcparamrel = novo from orcparamrel_acerto where o113_orcparamrel = antigo;
update orcparamseq set o69_codparamrel = novo from orcparamrel_acerto where o69_codparamrel = antigo;
update orcparamseqfiltrousuario set o72_orcparamrel = novo from orcparamrel_acerto where o72_orcparamrel = antigo;
update orcparamrel set o42_codparrel = novo from orcparamrel_acerto where o42_codparrel = antigo;

alter table conrelinfo enable trigger all;
alter table orcparamfontes enable trigger all;
alter table orcparamrelnota enable trigger all;
alter table orcparamrelperiodos enable trigger all;
alter table orcparamseq enable trigger all;
alter table orcparamseqfiltrousuario enable trigger all;