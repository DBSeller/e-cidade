
insert into regracalculocargahoraria select nextval('regracalculocargahoraria_ed127_codigo_seq'), 2015, false, ed18_i_codigo
                                       from escola
                                      where not exists ( select 1 from regracalculocargahoraria where ed127_escola = ed18_i_codigo and ed127_ano = 2015);