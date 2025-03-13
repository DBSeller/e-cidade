-- descobre maior sequencia usada
create table maior_sequencia_usada as
select max(max)
  from (
        select max(ed57_i_codigo)  from turma
        union all
        select max(ed268_i_codigo) from turmaac
       ) as x;

-- redefine valor da sequencia
select setval('turma_ed57_i_codigo_seq', (select max from maior_sequencia_usada));

-- cria um backup das turmas ac
create table novas_turmasac as
  select nextval('turma_ed57_i_codigo_seq') as new_id, ed268_i_codigo as old_id, turmaac.*
    from turmaac;

-- recria as turmas
insert into turmaac
select new_id,
       ed268_i_codigoinep,
       ed268_i_escola,
       ed268_i_calendario,
       ed268_c_descr,
       ed268_i_turno,
       ed268_i_sala,
       ed268_i_numvagas,
       ed268_i_nummatr,
       ed268_t_obs,
       ed268_i_tipoatend,
       ed268_i_ativqtd,
       ed268_c_aee,
       ed268_programamaiseducacao
  from novas_turmasac;

-- ajusta as tabelas de referencia
update turmaacativ set ed267_i_turmaac = new_id
  from novas_turmasac
 where ed267_i_turmaac = old_id;
update turmaachorario set ed270_i_turmaac = new_id
  from novas_turmasac
 where ed270_i_turmaac = old_id;
update turmaachorarioprofissional set ed346_turmaac = new_id
  from novas_turmasac
 where ed346_turmaac = old_id;
update turmaacmatricula set ed269_i_turmaac = new_id
  from novas_turmasac
 where ed269_i_turmaac = old_id;
update turmalogac set ed288_i_turmaac = new_id
  from novas_turmasac
 where ed288_i_turmaac = old_id;

-- remove turmas antigas
delete from turmaac where ed268_i_codigo in (select old_id from novas_turmasac);