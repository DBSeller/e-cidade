<?php

use Classes\PostgresMigration;

class M14347DicionarioDadosOuvidoriaprocessoeletronico extends PostgresMigration
{

    public function up()
    {

        $sql = <<<SQL
insert into db_sysarquivo values (1010472, 'ouvidoriaatendimentoprocessoeletronico', 'Processo eletronico do atendimento', 'ov33', '2019-09-17', 'Processo eletronico do atendimento', 0, 'f', 'f', 'f', 'f' );
insert into db_sysarqmod values (66,1010472);
insert into db_syscampo( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel ) values ( 1010749 ,'ov33_sequencial' ,'int4' ,'Sequencial' ,'' ,'Sequencial' ,8 ,'false' ,'false' ,'false' ,1 ,'text' ,'Sequencial' );
insert into db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) values ( 1010472 ,1010749 ,1 ,0 );
insert into db_syscampo( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel ) values ( 1010750 ,'ov33_ouvidoriaatendimento' ,'int4' ,'Sequencial' ,'0' ,'Sequencial' ,10 ,'false' ,'false' ,'false' ,1 ,'text' ,'Sequencial' );
insert into db_syscampodep ( codcam ,codcampai ) values ( 1010750 ,14769 );
insert into db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) values ( 1010472 ,1010750 ,2 ,0 );
insert into db_syscampo( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel ) values ( 1010751 ,'ov33_informacoesprocesso' ,'text' ,'Informa??es do processo' ,'' ,'Informa??es do processo' ,1000 ,'false' ,'true' ,'false' ,0 ,'text' ,'Informações do processo' );
insert into db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) values ( 1010472 ,1010751 ,3 ,0 );
insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010472,1010749,1,1010750);
insert into db_sysforkey values(1010472,1010750,1,2600,0);
insert into db_sysindices values(1008494,'ouvidoriaatendimentoprocessoeletronico_ouvidoriaatendimento_in',1010472,'0');
insert into db_syscadind values(1008494,1010750,1);
insert into db_syssequencia values(1000850, 'ouvidoriaatendimentoprocessoeletronico_ov33_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
update db_sysarqcamp set codsequencia = 1000850 where codarq = 1010472 and codcam = 1010749;
SQL;
        $this->execute($sql);

        $this->criarEstrutura();
    }

    public function down()
    {

        $sql = <<<SQL
delete from db_syscampodef where codcam = 1010749;
delete from db_syscampodef where codcam = 1010750;
delete from db_syscampodef where codcam = 1010751;
delete from db_syscampodep where codcam = 1010750;
delete from db_sysarqcamp  where codcam in (1010749, 1010750, 1010751);
delete from db_sysprikey   where codarq = 1010472;
delete from db_sysforkey   where codarq = 1010472 and referen = 0;
delete from db_sysforkey   where codcam in (1010749, 1010750, 1010751);
delete from db_syscampo    where codcam in (1010749, 1010750, 1010751);
delete from db_sysarqmod   where codarq = 1010472;
delete from db_sysarquivo  where codarq = 1010472;
delete from db_sysindices  where codarq = 1010472;
delete from db_syscadind   where codind = 1008494;
delete from db_syssequencia where codsequencia = 1000850;
DROP TABLE IF EXISTS ouvidoriaatendimentoprocessoeletronico CASCADE;                                                                                                                          
DROP SEQUENCE IF EXISTS ouvidoriaatendimentoprocessoeletronico_ov33_sequencial_seq;
SQL;
        $this->execute($sql);
    }

    private function criarEstrutura()
    {
        $sql = <<<SQL
CREATE SEQUENCE ouvidoria.ouvidoriaatendimentoprocessoeletronico_ov33_sequencial_seq
INCREMENT 1
MINVALUE 1
MAXVALUE 9223372036854775807
START 1
CACHE 1;

CREATE TABLE ouvidoria.ouvidoriaatendimentoprocessoeletronico(                                                                                                                                              
ov33_sequencial   int4  default nextval('ouvidoriaatendimentoprocessoeletronico_ov33_sequencial_seq'),
ov33_ouvidoriaatendimento int4,
ov33_informacoesprocesso json,
CONSTRAINT ouvidoriaatendimentoprocessoeletronico_sequ_pk PRIMARY KEY (ov33_sequencial)
);

ALTER TABLE ouvidoria.ouvidoriaatendimentoprocessoeletronico
ADD CONSTRAINT ouvidoriaatendimentoprocessoeletronico_ouvidoriaatendimento_fk FOREIGN KEY (ov33_ouvidoriaatendimento)
REFERENCES ouvidoria.ouvidoriaatendimento;

CREATE  INDEX ouvidoriaatendimentoprocessoeletronico_ouvidoriaatendimento_in ON ouvidoriaatendimentoprocessoeletronico(ov33_ouvidoriaatendimento);

SQL;
        $this->execute($sql);

    }
}
