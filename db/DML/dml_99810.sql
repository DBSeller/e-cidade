
insert into acordoempempenho
     select nextval('acordoempempenho_ac54_sequencial_seq'),
            e100_acordo,
            e100_numemp,
            null,
            null
       from empempenhocontrato
            inner join acordo on ac16_sequencial = e100_acordo
      where ac16_origem = 6;

update acordo set ac16_origem = 3 where ac16_origem = 6;