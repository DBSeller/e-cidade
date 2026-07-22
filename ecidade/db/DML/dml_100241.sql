create temporary table t_db_layoutcampos as select * from db_layoutcampos limit 0;
insert into t_db_layoutcampos
values ( 14801 ,56 ,'cpfcnpj_proprietario' ,'CPF/CNPJ PROPRIETARIO' ,13 ,4616 ,'' ,14 ,'f' ,'t' ,'e' ,'' ,0 ),
       ( 14802 ,56 ,'nosso_numero' ,'NOSSO NUMERO' ,13 ,4630 ,'' ,20 ,'f' ,'t' ,'d' ,'' ,0 );
insert into db_layoutcampos
  select * from t_db_layoutcampos
    where not exists (select 1 from db_layoutcampos where db_layoutcampos.db52_codigo = t_db_layoutcampos.db52_codigo);