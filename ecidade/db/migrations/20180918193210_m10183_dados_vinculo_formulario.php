<?php

use Classes\PostgresMigration;

class M10183DadosVinculoFormulario extends PostgresMigration
{

    private function dicionario() {
        $sSql = "
        insert into db_sysarquivo 
        values (1010321, 'avaliacaogruporespostatertrabasemvinc', 'Tabela para guardar vinculo do formulario do esocial com o trabalhador sem vinculo', 'eso24', '2018-09-18', '', 0, 'f', 'f', 'f', 'f' );
        insert into db_sysarqmod values (81,1010321);

        insert into db_syscampo values(1009959,'eso24_sequencial','int8','sequencial da tabela avaliacaogruporespostatertrabasemvinc','0', 'eso24_sequencial',11,'f','f','f',1,'text','eso24_sequencial');
        insert into db_syscampo values(1009961,'eso24_avaliacaogruporesposta','int8','eso24_avaliacaogruporesposta','0', 'eso24_avaliacaogruporesposta',11,'f','f','f',1,'text','eso24_avaliacaogruporesposta');
        insert into db_syscampo values(1009962,'eso24_rhpessoal','int8','eso24_rhpessoal','0', 'eso24_rhpessoal',11,'f','f','f',1,'text','eso24_rhpessoal');   
        
        delete from db_sysarqcamp where codarq = 1010321;        
        insert into db_sysarqcamp values(1010321,1009959,1,0);
        insert into db_sysarqcamp values(1010321,1009961,2,0);
        insert into db_sysarqcamp values(1010321,1009962,3,0);
        
        delete from db_sysprikey where codarq = 1010321;
        insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010321,1009959,1,1009959);
        delete from db_sysforkey where codarq = 1010321 and referen = 0;
        insert into db_sysforkey values(1010321,1009961,1,2987,0);
        
        delete from db_sysforkey where codarq = 1010321 and referen = 0;
        insert into db_sysforkey values(1010321,1009962,1,1153,0);
        
        insert into db_sysindices values(1008329,'avaliacaogruporespostaterminotrabasemvinculo_eso24_sequencial',1010321,'0');
        insert into db_syscadind values(1008329,1009959,1);
        
        insert into db_syssequencia values(1000768, 'avaliacaogruporespostaterminotrabasemvinculo_eso24_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
        update db_sysarqcamp set codsequencia = 1000768 where codarq = 1010321 and codcam = 1009959;

        
        ";

        $this->execute($sSql);
    }

    public function up()
    {
        $this->dicionario();

        $sSql =  <<<SQL_UP

        CREATE SEQUENCE esocial.avaliacaogruporespostatertrabasemvinc_eso24_sequencial_seq
        INCREMENT 1
        MINVALUE 1
        MAXVALUE 9223372036854775807
        START 1
        CACHE 1;

        CREATE TABLE esocial.avaliacaogruporespostatertrabasemvinc(
        eso24_sequencial      int4 NOT NULL,
        eso24_avaliacaogruporesposta   int4 NOT NULL ,
        eso24_rhpessoal   int4 not null,
        
        CONSTRAINT avaliacaogruporespostatertrabasemvinc_aval_pk PRIMARY KEY (eso24_avaliacaogruporesposta));
        
        ALTER TABLE esocial.avaliacaogruporespostatertrabasemvinc
        ADD CONSTRAINT avaliacaogruporespostatertrabasemvinc_avaliacaogruporesposta_fk FOREIGN KEY (eso24_avaliacaogruporesposta)
        REFERENCES avaliacaogruporesposta;
        
        ALTER TABLE esocial.avaliacaogruporespostatertrabasemvinc
        ADD CONSTRAINT avaliacaogruporespostatertrabasemvinc_rhpessoal_fk FOREIGN KEY (eso24_rhpessoal)
        REFERENCES rhpessoal;
        
        CREATE  INDEX avaliacaogruporespostatertrabasemvinc_rhpessoal_in ON avaliacaogruporespostatertrabasemvinc(eso24_rhpessoal);
        CREATE  INDEX avaliacaogruporespostatertrabasemvinc_terminotrabasemvinculo_in ON avaliacaogruporespostatertrabasemvinc(eso24_avaliacaogruporesposta);

SQL_UP;

        $this->execute($sSql);

    }


    private function  deleteDicionario()
    {

        $sSql = "
        
            delete from db_sysforkey    where codarq = 1010321;
            delete from db_sysarqcamp   where codarq = 1010321;
            delete from db_syssequencia where codsequencia = 1000768;
            
            delete from db_syscadind    where codcam in (1009959, 1009961, 1009962);
            delete from db_sysindices   where codarq = 1010321;
            delete from db_sysforkey    where codarq = 1010321;
            delete from db_sysprikey    where codarq = 1010321;
            delete from db_syscampo     where codcam in (1009959, 1009961, 1009962);
            delete from db_sysarqmod    where codarq = 1010321;
            delete from db_sysarquivo   where codarq = 1010321;
        ";

        $this->execute($sSql);


    }

    public function down()
    {

        $this->deleteDicionario();

        $sSql =  <<<SQL_UP
            drop sequence esocial.avaliacaogruporespostatertrabasemvinc_eso24_sequencial_seq;
            drop table esocial.avaliacaogruporespostatertrabasemvinc;
SQL_UP;

        $this->execute($sSql);


    }
}
