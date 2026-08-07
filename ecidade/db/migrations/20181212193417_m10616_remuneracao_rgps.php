<?php

use Classes\PostgresMigration;

class M10616RemuneracaoRgps extends PostgresMigration
{
    public function up()
    {
        $this->upDicionario();
        $this->upDDL();
    }

    public function down()
    {
        $this->downDicionario();
        $this->downDDL();
    }

    public function upDicionario()
    {
        $this->execute(
          <<<SQL_UP
insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) 
     values ( 228075 ,'Remuneração' ,'Remuneração' ,'' ,'1' ,'1' ,'Menu referente aos formulários de remuneração.' ,'true' ),
            ( 228076 ,'Regime Geral de Previdência Social' ,'Regime Geral de Previdência Social' ,'eso4_regimegeralprevidencia001.php' ,'1' ,'1' ,'Formulário do regime geral de previdência.' ,'true' );
insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) 
     values ( 10466 ,228075 ,15 ,10216 ),
            ( 228075 ,228076 ,1 ,10216 );

insert into avaliacao( db101_sequencial ,db101_avaliacaotipo ,db101_descricao ,db101_identificador ,db101_obs ,db101_ativo ,db101_cargadados ,db101_permiteedicao ) 
    values ( 3000036 ,5 ,'S-1200-Remuneração do Trabalhador Vinculado RGPS' ,'s1200remuneracao-do-trabalhador-vinculado-rgps' ,'Registros do evento S-1200 - Remuneração do Trabalhador Vinculado ao RGPS' ,'true' ,'' ,'true' );
insert into avaliacaogrupopergunta( db102_sequencial ,db102_avaliacao ,db102_descricao ,db102_identificador ,db102_identificadorcampo ,db102_ordem ) 
    values ( 3000531 ,3000036 ,'Enviar exclusivamente se houver processo judicial incidente na folha de pagamento' ,'enviar-exclusivamente-se-houver-processo-judicial-' ,'procJudTrab' ,1 );
insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) 
    values ( 3002395 ,1 ,3000531 ,'Tipo de processo judicial' ,'tipo-de-processo-judicial' ,'false' ,'true' ,1 ,1 ,'' ,0 ,'false' ,'' ,'tpTrib' );
delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 3002395;
insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) 
    values ( 4000950 ,3002395 ,'IRRF' ,'irrf5c127aae1c224' ,'false' ,0 ,'1' ,'tpTrib_1' );
insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) 
    values ( 4000951 ,3002395 ,'Contribuições sociais do trabalhador' ,'contribuicoes-sociais-do-trabalhador5c127aae20cb3' ,'false' ,0 ,'2' ,'tpTrib_2' );
insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) 
    values ( 4000952 ,3002395 ,'FGTS' ,'fgts5c127aae22c1f' ,'false' ,0 ,'3' ,'tpTrib_3' );
insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) 
    values ( 4000953 ,3002395 ,'Contribuição sindical' ,'contribuicao-sindical5c127aae24c81' ,'false' ,0 ,'4' ,'tpTrib_4' );
insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) 
    values ( 3002396 ,2 ,3000531 ,'Número do processo' ,'numero-do-processo' ,'false' ,'true' ,2 ,1 ,'' ,0 ,'false' ,'' ,'nrProcJud' );
delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 3002396;
insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) 
    values ( 4000954 ,3002396 ,'' ,'5c127aae298e6' ,'true' ,0 ,'' ,'nrProcJud' );
insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) 
    values ( 3002397 ,2 ,3000531 ,'Código do Indicativo da Suspensão' ,'codigo-do-indicativo-da-suspensao' ,'false' ,'true' ,3 ,1 ,'' ,0 ,'false' ,'' ,'codSusp' );
delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 3002397;
insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) 
    values ( 4000955 ,3002397 ,'' ,'5c127aae2e40b' ,'true' ,0 ,'' ,'codSusp' );


insert into db_sysarquivo 
    values (1010356, 'avaliacaogruporespostaremuneracaorgps', 'Remuneração RGPS eSocial', 'eso28', '2018-12-14', 'Remuneração RGPS eSocial', 0, 'f', 'f', 'f', 'f' );

insert into db_sysarqmod 
    values (81,1010356);

insert into db_syscampo 
    values (1010192,'eso28_sequencial','int4','Sequencial relativo a Remuneração RGPS','0', 'Sequencial',10,'f','f','f',1,'text','Sequencial'),
        (1010193,'eso28_avaliacaogruporesposta','int4','Avaliação Grupo Resposta','0', 'Avaliação Grupo Resposta',10,'f','f','f',1,'text','Avaliação Grupo Resposta'),
        (1010194,'eso28_cgm','int4','CGM','0', 'CGM',10,'f','f','f',1,'text','CGM'),
        (1010195,'eso28_ano','int4','Ano','0', 'Ano',4,'f','f','f',1,'text','Ano'),
        (1010196,'eso28_mes','int4','Mês','0', 'Mês',2,'f','f','f',1,'text','Mês');

insert into db_sysarqcamp 
    values (1010356,1010192,1,0),
        (1010356,1010193,2,0),
        (1010356,1010194,3,0),
        (1010356,1010195,4,0),
        (1010356,1010196,5,0);


insert into db_sysprikey (codarq,codcam,sequen,camiden) 
    values (1010356,1010192,1,1010192);

insert into db_sysforkey 
    values (1010356,1010194,1,42,0),
        (1010356,1010193,1,2987,0);

insert into db_sysindices 
    values (1008395,'avaliacaogruporespostaremuneracaorgps_avaliacaogruporesposta_in',1010356,'0'),
        (1008396,'avaliacaogruporespostaremuneracaorgps_cgm_in',1010356,'0');

insert into db_syscadind 
    values (1008395,1010193,1),
        (1008396,1010194,1);

insert into db_syssequencia 
    values (1000798, 'avaliacaogruporespostaremuneracaorgps_eso28_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);


update db_sysarqcamp set codsequencia = 1000798 where codarq = 1010356 and codcam = 1010192;

SQL_UP
        );
    }

    public function downDicionario()
    {
        $this->execute(
          <<<SQL_DOWN
delete from db_menu where id_item_filho in(228075, 228076);
delete from db_itensmenu where id_item in(228075, 228076);

DELETE FROM db_sysforkey    where codarq = 1010356;
DELETE FROM db_sysprikey    where codarq = 1010356;
DELETE FROM db_sysindices   where codind in (1008395, 1008396);
DELETE FROM db_syscadind    where codind in (1008395, 1008396);
DELETE FROM db_sysarqmod    where codarq = 1010356;
DELETE FROM db_sysarqcamp   where codarq = 1010356;
DELETE FROM db_syscampo     where codcam in (1010192, 1010193, 1010194, 1010195, 1010196);
DELETE FROM db_syssequencia where codsequencia =  1000798;
DELETE FROM db_sysarquivo   where codarq = 1010356;

SQL_DOWN

        );
    }

    public function upDDL()
    {
        $this->execute(
          <<<SQL_UP
insert into esocialformulariotipo values(24, 'S-1200 - Remuneração RGPS');
insert into esocialversaoformulario values(nextval('esocialversaoformulario_rh211_sequencial_seq'), '2.4', 3000036, 24);

CREATE SEQUENCE avaliacaogruporespostaremuneracaorgps_eso28_sequencial_seq
INCREMENT 1
MINVALUE 1
MAXVALUE 9223372036854775807
START 1
CACHE 1;

CREATE TABLE esocial.avaliacaogruporespostaremuneracaorgps(
    eso28_sequencial int primary key,
    eso28_avaliacaogruporesposta int not null,
    eso28_cgm int not null,
    eso28_ano int not null,
    eso28_mes int not null
);

ALTER TABLE avaliacaogruporespostaremuneracaorgps
ADD CONSTRAINT avaliacaogruporespostaremuneracaorgps_avaliacaogruporesposta_fk FOREIGN KEY (eso28_avaliacaogruporesposta)
REFERENCES avaliacaogruporesposta;

ALTER TABLE avaliacaogruporespostaremuneracaorgps
ADD CONSTRAINT avaliacaogruporespostaremuneracaorgps_cgm_fk FOREIGN KEY (eso28_cgm)
REFERENCES cgm;

CREATE  INDEX avaliacaogruporespostaremuneracaorgps_avaliacaogruporesposta_in ON avaliacaogruporespostaremuneracaorgps(eso28_avaliacaogruporesposta);

CREATE  INDEX avaliacaogruporespostaremuneracaorgps_cgm_in ON avaliacaogruporespostaremuneracaorgps(eso28_cgm);
SQL_UP
        );
    }

    public function downDDL()
    {
        $this->execute(
          <<<SQL_DOWN
DELETE FROM esocialversaoformulario where rh211_avaliacao = 3000036 and rh211_esocialformulariotipo = 24;
DELETE FROM esocialformulariotipo where rh209_descricao = 'S-1200 - Remuneração RGPS';

DELETE FROM avaliacaoperguntaopcao where db104_avaliacaopergunta = 3002397;
DELETE FROM avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 3002397;
DELETE FROM avaliacaopergunta where db103_sequencial = 3002397;
DELETE FROM avaliacaoperguntaopcao where db104_avaliacaopergunta = 3002396;
DELETE FROM avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 3002396;
DELETE FROM avaliacaopergunta where db103_sequencial = 3002396;
DELETE FROM avaliacaoperguntaopcao where db104_avaliacaopergunta = 3002395;
DELETE FROM avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 3002395;
DELETE FROM avaliacaopergunta where db103_sequencial = 3002395;
DELETE FROM avaliacaogrupopergunta where db102_avaliacao = 3000036;
DELETE FROM avaliacao where db101_sequencial = 3000036;

--DROP TABLE:
DROP TABLE IF EXISTS avaliacaogruporespostaremuneracaorgps CASCADE;
--Criando drop sequences
DROP SEQUENCE IF EXISTS avaliacaogruporespostaremuneracaorgps_eso28_sequencial_seq;
SQL_DOWN
        );
    }
}
