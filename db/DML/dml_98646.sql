insert into db_sysarquivo values (3859, 'abatimentoutilizacaodestino', 'responsável por conter os destinos dos créditos utilizados', 'k170', '2015-08-20', 'abatimento utilizacao destino', 0, 'f', 'f', 'f', 'f' );
insert into db_sysarqmod values (54,3859);
insert into db_syscampo values ( 21486 ,'k170_utilizacao' ,'int4' ,'Abatimento Utilizacao' ,'' ,'Abatimento Utilizacao' ,10 ,'false' ,'false' ,'false' ,1 ,'text' ,'Abatimento Utilizacao' );
delete from db_syscampodef where codcam = 21486;
insert into db_sysarqcamp values ( 3859 ,21486 ,1 ,0 );
insert into db_syscampo values ( 21487 ,'k170_numpre' ,'int4' ,'Numpre Destino' ,'' ,'Numpre Destino' ,8 ,'false' ,'false' ,'false' ,1 ,'text' ,'Numpre Destino' );
delete from db_syscampodef where codcam = 21487;
insert into db_sysarqcamp values ( 3859 ,21487 ,2 ,0 );
insert into db_syscampo values ( 21488 ,'k170_numpar' ,'int4' ,'Parcela Destino' ,'' ,'Parcela destino' ,4 ,'false' ,'false' ,'false' ,1 ,'text' ,'Parcela destino' );
delete from db_syscampodef where codcam = 21488;
insert into db_sysarqcamp values ( 3859 ,21488 ,3 ,0 );
insert into db_syscampo values ( 21489 ,'k170_receit' ,'int4' ,'Receita Destino' ,'' ,'Receita destino' ,10 ,'false' ,'false' ,'false' ,1 ,'text' ,'Receita destino' );
delete from db_syscampodef where codcam = 21489;
insert into db_sysarqcamp values ( 3859 ,21489 ,4 ,0 );
insert into db_syscampo values ( 21490 ,'k170_hist' ,'int4' ,'Historico Destino' ,'' ,'Historico Destino' ,4 ,'false' ,'false' ,'false' ,1 ,'text' ,'Historico Destino' );
delete from db_syscampodef where codcam = 21490;
insert into db_sysarqcamp values ( 3859 ,21490 ,5 ,0 );
insert into db_syscampo values ( 21491 ,'k170_tipo' ,'int4' ,'Tipo Destino' ,'' ,'Tipo Destino' ,4 ,'false' ,'false' ,'false' ,1 ,'text' ,'Tipo Destino' );
delete from db_syscampodef where codcam = 21491;
insert into db_sysarqcamp values ( 3859 ,21491 ,6 ,0 );
insert into db_sysforkey values(3859,21486,1,3484,0);

select fc_executa_ddl('
CREATE TABLE abatimentoutilizacaodestino(
  k170_utilizacao     int4 NOT NULL ,
  k170_numpre     int4 NOT NULL ,
  k170_numpar     int4 NOT NULL ,
  k170_receit     int4 NOT NULL ,
  k170_hist       int4 NOT NULL ,
  k170_tipo       int4 NOT NULL );');

select fc_executa_ddl('ALTER TABLE abatimentoutilizacaodestino
ADD CONSTRAINT abatimentoutilizacaodestino_utilizacao_fk FOREIGN KEY (k170_utilizacao)
REFERENCES abatimentoutilizacao;');
