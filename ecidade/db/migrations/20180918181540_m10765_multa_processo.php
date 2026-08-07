<?php

use Classes\PostgresMigration;

class M10765MultaProcesso extends PostgresMigration
{
    public function up()
    {

        $this->execute("insert into db_itensmenu ( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 10579 ,'Valores Adicionais' ,'Valores Adicionais' ,'jur4_inclusaomulta.php' ,'1' ,'1' ,'Valores Adicionais' ,'true' )");
        $this->execute("insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 1798 ,10579 ,4 ,313 )");
        $this->execute("insert into db_sysarquivo values (1010317, 'processoforomulta', 'Multa para processo do foro', 'j150', '2018-09-18', 'Multa para processo do foro', 0, 'f', 'f', 'f', 'f' )");
        $this->execute("insert into db_sysarqmod values (21,1010317)");
        $this->execute("insert into db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel ) values ( 1009949 ,'j150_sequencial' ,'int4' ,'Squencial' ,'' ,'Sequencial' ,10 ,'false' ,'false' ,'false' ,1 ,'text' ,'Sequencial' );");
        $this->execute("insert into db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) values ( 1010317 ,1009949 ,1 ,0 );");
        $this->execute("insert into db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel ) values ( 1009950 ,'j150_processoforo' ,'int4' ,'Processo' ,'' ,'Processo' ,10 ,'false' ,'false' ,'false' ,1 ,'text' ,'Processo' );");
        $this->execute("insert into db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) values ( 1010317 ,1009950 ,2 ,0 );");
        $this->execute("insert into db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel ) values ( 1009951 ,'j150_data' ,'date' ,'Data da Multa' ,'' ,'Data' ,10 ,'false' ,'false' ,'false' ,1 ,'text' ,'Data' );");
        $this->execute("insert into db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) values ( 1010317 ,1009951 ,3 ,0 );");
        $this->execute("insert into db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel ) values ( 1009952 ,'j150_percentual' ,'float4' ,'Percentual' ,'0' ,'Percentual' ,10 ,'true' ,'false' ,'false' ,4 ,'text' ,'Percentual' );");
        $this->execute("insert into db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) values ( 1010317 ,1009952 ,4 ,0 );");
        $this->execute("insert into db_syscampo ( codcam ,nomecam ,conteudo ,descricao ,valorinicial ,rotulo ,tamanho ,nulo ,maiusculo ,autocompl ,aceitatipo ,tipoobj ,rotulorel ) values ( 1009953 ,'j150_receita' ,'int4' ,'Receita' ,'' ,'Receita' ,10 ,'false' ,'false' ,'false' ,1 ,'text' ,'Receita' );");
        $this->execute("insert into db_sysarqcamp ( codarq ,codcam ,seqarq ,codsequencia ) values ( 1010317 ,1009953 ,5 ,0 );");

        $this->execute("insert into db_syscampo values(1010009,'j150_valortotal','float4','Valor Calculado','0', 'Valor Calculado',10,'f','f','f',4,'text','Valor Calculado');");
        $this->execute("insert into db_sysarqcamp values(1010317,1010009,6,0);");

        $this->execute("insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010317,1009949,1,1009950);");
        $this->execute("insert into db_sysforkey values(1010317,1009950,1,3069,0);");
        $this->execute("insert into db_sysindices values(1008325,'processoforomulta_processoforo_in',1010317,'0');");
        $this->execute("insert into db_syscadind values(1008325,1009950,1);");
        $this->execute("insert into db_sysindices values(1008326,'processoforomulta_receita_in',1010317,'0');");
        $this->execute("insert into db_syscadind values(1008326,1009953,1);");
        $this->execute("insert into db_syssequencia values(1000766, 'processoforomulta_j150_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);");
        $this->execute("update db_sysarqcamp set codsequencia = 1000766 where codarq = 1010317 and codcam = 1009949;");
        $this->execute("insert into db_sysforkey values(1010317,1009953,1,75,0)");



        $this->execute(<<<SQL
CREATE SEQUENCE juridico.processoforomulta_j150_sequencial_seq
INCREMENT 1
MINVALUE 1
MAXVALUE 9223372036854775807
START 1
CACHE 1;

CREATE TABLE juridico.processoforomulta(
j150_sequencial	  int4 NOT NULL  default nextval('juridico.processoforomulta_j150_sequencial_seq'),
j150_processoforo  int4 NOT NULL ,
j150_data       date NOT NULL ,
j150_percentual numeric  default 0,
j150_receita    int4 ,
j150_valortotal numeric not null,
CONSTRAINT processoforomulta_sequ_pk PRIMARY KEY (j150_sequencial));



ALTER TABLE juridico.processoforomulta
ADD CONSTRAINT processoforomulta_processoforo_fk FOREIGN KEY (j150_processoforo)
REFERENCES juridico.processoforo;

ALTER TABLE juridico.processoforomulta
ADD CONSTRAINT processoforomulta_tabrec_fk FOREIGN KEY (j150_receita)
REFERENCES caixa.tabrec;


CREATE  INDEX processoforomulta_processoforo_in ON juridico.processoforomulta(j150_processoforo);

CREATE  INDEX processoforomulta_receita_in ON juridico.processoforomulta(j150_receita);

SQL
);

    }


    public function down()
    {

        $this->execute("delete from db_menu where id_item_filho = 10579 AND modulo = 313;");
        $this->execute("delete from db_itensmenu where id_item = 10579");
        $this->execute("delete from db_syssequencia where codsequencia = 1000766");
        $this->execute("delete from db_syscadind where codind in (1008326, 1008325)");
        $this->execute("delete from db_sysindices where codind in (1008326, 1008325)");
        $this->execute("delete from db_sysforkey where codarq in (1010317)");
        $this->execute("delete from db_sysprikey where codarq in (1010317)");
        $this->execute("delete from db_sysarqcamp where codarq in (1010317)");
        $this->execute("delete from db_syscampo   where codcam in (1009949, 1009950, 1009951, 1009952, 1009953, 1010009)");
        $this->execute("delete from db_sysarqmod  where codarq in (1010317)");
        $this->execute("delete from db_sysarquivo where codarq in (1010317)");
        $this->execute (<<<SQL
       
DROP TABLE IF EXISTS juridico.processoforomulta;
DROP SEQUENCE IF EXISTS juridico.processoforomulta_j150_sequencial_seq;

SQL
);


    }
}
