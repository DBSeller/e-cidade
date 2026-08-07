<?php

use Classes\PostgresMigration;

class M12001Retencaotipocalcreceitas extends PostgresMigration
{

    public function up()
    {

        $sSql = <<<SQL
insert into db_sysarquivo values (1010331, 'retencaotipocalcreceitas', 'Configuração das receias por tipo de calculo', 'e17', '2018-10-23', 'Configuração das receias por tipo de calculo', 0, 'f', 'f', 'f', 'f' );
insert into db_sysarqmod values (38,1010331);
insert into db_syscampo   ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel ) values ( 1010039 ,'e17_sequencial' ,'int8' ,'Código Sequencial' ,'' ,'Sequencial' ,10 ,'false' ,'false' ,'false' ,1 ,'text' ,'Sequencial' );
insert into db_syscampo    values(1010040,'e17_receit','int4','Código da Receita','0', 'Receita',6,'f','f','f',1,'text','Receita');
insert into db_syscampodep values(1010040,'382');
insert into db_syscampo    values(1010041,'e17_instit','int4','Código da Instituição','0', 'Código da Instituição',2,'f','f','f',0,'text','Código da Instituição');
insert into db_syscampodep values(1010041,'449');
insert into db_syscampo    values(1010042,'e17_retencaotipocalc','int4','TIpo do calculo','0', 'TIpo do calculo',10,'f','f','f',1,'text','TIpo do calculo');
insert into db_syscampodep values(1010042,'12157');
insert into db_sysarqcamp values(1010331,1010039,1,0);
insert into db_sysarqcamp values(1010331,1010041,2,0);
insert into db_sysarqcamp values(1010331,1010042,3,0);
insert into db_sysarqcamp values(1010331,1010040,4,0);
insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010331,1010039,1,1010042);
insert into db_sysforkey values(1010331,1010040,1,75,0);
insert into db_sysforkey values(1010331,1010041,1,83,0);
insert into db_sysforkey values(1010331,1010042,1,2111,0);
insert into db_sysindices values(1008336,'retencaotipocalcreceitas_instit_in',1010331,'0');
insert into db_syscadind values(1008336,1010041,1);
insert into db_sysindices values(1008337,'retencaotipocalcreceitas_retencaotipocalc_in',1010331,'0');
insert into db_syscadind values(1008337,1010042,1);
insert into db_sysindices values(1008338,'retencaotipocalcreceitas_instit_tipocalc_in',1010331,'1');
insert into db_syscadind values(1008338,1010041,1);
insert into db_syscadind values(1008338,1010042,2);
insert into db_sysindices values(1008339,'retencaotipocalcreceitas_receit_in',1010331,'0');
insert into db_syscadind values(1008339,1010040,1);
insert into db_syssequencia values(1000775, 'retencaotipocalcreceitas_e17_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
update db_sysarqcamp set codsequencia = 1000775 where codarq = 1010331 and codcam = 1010039;
insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228059,'Config. Receita por tipo de Cálculo' ,'Configuração de Receitas por tipo de Cálculo' ,'emp1_retencaotipocalcreceitas001.php' ,'1' ,'1' ,'Configuração de Receitas por tipo de Cálculo' ,'true' );
insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values (6906 ,228059 ,4 ,398 );
SQL;
       $this->execute($sSql);

       $sSqlDDL = <<<SQL
CREATE SEQUENCE empenho.retencaotipocalcreceitas_e17_sequencial_seq
INCREMENT 1
MINVALUE 1
MAXVALUE 9223372036854775807
START 1
CACHE 1;
CREATE TABLE empenho.retencaotipocalcreceitas(
e17_sequencial      int8  default nextval('retencaotipocalcreceitas_e17_sequencial_seq'),
e17_instit          int8 not null,
e17_receit           int8 not null,
e17_retencaotipocalc int8 not null,
CONSTRAINT retencaotipocalcreceitas_sequ_pk PRIMARY KEY (e17_sequencial));
ALTER TABLE empenho.retencaotipocalcreceitas
ADD CONSTRAINT retencaotipocalcreceitas_instit_fk FOREIGN KEY (e17_instit)
REFERENCES db_config;
ALTER TABLE empenho.retencaotipocalcreceitas
ADD CONSTRAINT retencaotipocalcreceitas_receit_fk FOREIGN KEY (e17_receit)
REFERENCES tabrec;
ALTER TABLE empenho.retencaotipocalcreceitas
ADD CONSTRAINT retencaotipocalcreceitas_retencaotipocalc_fk FOREIGN KEY (e17_retencaotipocalc)
REFERENCES retencaotipocalc;
CREATE  INDEX retencaotipocalcreceitas_instit_in ON empenho.retencaotipocalcreceitas(e17_instit);
CREATE  INDEX retencaotipocalcreceitas_retencaotipocalc_in ON empenho.retencaotipocalcreceitas(e17_retencaotipocalc);
CREATE UNIQUE INDEX retencaotipocalcreceitas_instit_tipocalc_in ON empenho.retencaotipocalcreceitas(e17_instit,e17_retencaotipocalc,e17_receit);
CREATE  INDEX retencaotipocalcreceitas_receit_in ON empenho.retencaotipocalcreceitas(e17_receit);
SQL;
       $this->execute($sSqlDDL);

        $sSql = <<<SQL
        insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228060,'Rotinas administrativas' ,'Rotinas administrativas' ,'' ,'1' ,'1' ,'Rotinas administrativas' ,'true' );
        insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228061,'Ativar apropriação automática de retenções' ,'Ativar apropriação automática de retenções' ,'emp4_ativarapropriacaoautomatica001.php' ,'1' ,'1' ,'Ativar apropriação automática de retenções' ,'true' );
        insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 32 ,228060 ,503 ,398 );
        insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 228060 ,228061 ,1 ,398 );
SQL;
        $this->execute($sSql);
    }


    public function down()
    {

        $sSqlDownDDL = <<<SQL
      drop table if exists empenho.retencaotipocalcreceitas;
      drop sequence if exists empenho.retencaotipocalcreceitas_e17_sequencial_seq;
SQL;

        $this->execute($sSqlDownDDL);

        $sSqlDown = <<<SQL
delete from db_menu         where id_item_filho = 228059 AND modulo = 398;
delete from db_itensmenu    where id_item = 228059;
delete from db_menu         where id_item_filho = 228060 AND modulo = 398;
delete from db_itensmenu    where id_item = 228060;
delete from db_menu         where id_item_filho = 228061 AND modulo = 398;
delete from db_itensmenu    where id_item = 228061;
delete from db_syssequencia where codsequencia = 1000775;
delete from db_sysindices   where codind in (1008339,1008338,1008337,1008336);
delete from db_syscadind    where codind in (1008339,1008338,1008337,1008336);
delete from db_sysprikey    where codarq = 1010331;
delete from db_sysforkey    where codarq = 1010331;
delete from db_sysarqcamp   where codarq = 1010331; 
delete from db_syscampodep  where codcam in (1010040,1010041,1010042);
delete from db_syscampo     where codcam in (1010039,1010040,1010041,1010042);
delete from db_sysarqmod    where codarq = 1010331;
delete from db_sysarquivo   where codarq = 1010331;
SQL;
        $this->execute($sSqlDown);





    }
}
