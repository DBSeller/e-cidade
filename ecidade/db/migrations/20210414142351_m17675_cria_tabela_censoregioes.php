<?php

use Classes\PostgresMigration;

class M17675CriaTabelaCensoregioes extends PostgresMigration
{

    public function up()
    {
        $this->estruturaUp();
        $this->adicionarDados();
        $this->dicionarioUp();
    }

    public function down()
    {
        $this->estruturaDown();
        $this->dicionarioDown();
    }

    private function dicionarioUp()
    {
        $sql = <<<SQL
        insert into db_sysarquivo values (1010784, 'censoregiao', 'Regiões administrativas do censo', 'ed174', '2021-04-14', 'Regiões Administrativas', 0, 'f', 'f', 'f', 'f' );
        insert into db_sysarqmod values (1008004,1010784);

        insert into db_syscampo values(1013159,'ed174_codigo','int4','Código Sequencial Região','0', 'Codigo ',10,'f','f','f',1,'text','Codigo ');
        insert into db_syscampo values(1013160,'ed174_nome','varchar(50)','Nome da região administrativa','', 'Nome da Região',50,'f','f','f',0,'text','Nome da Região');
        insert into db_syscampo values(1013161,'ed174_censomunic','int4','Município do censo ','0', 'Municipio',10,'f','f','f',1,'text','Municipio');

        delete from db_sysarqcamp where codarq = 1010784;
        insert into db_sysarqcamp values(1010784,1013159,1,0);
        insert into db_sysarqcamp values(1010784,1013160,2,0);
        insert into db_sysarqcamp values(1010784,1013161,3,0);

        delete from db_sysprikey where codarq = 1010784;
        insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010784,1013159,1,1013159);
        delete from db_sysforkey where codarq = 1010784 and referen = 0;
        insert into db_sysforkey values(1010784,1013161,1,2339,0);
        insert into db_sysindices values(1008686,'censoregiao_ed174_codigo_in',1010784,'1');
        insert into db_syscadind values(1008686,1013159,1);
SQL;
        $this->execute($sql);
    }

    private function dicionarioDown()
    {

        $sql = <<<SQL
        delete from db_syscadind where codind = 1008686;
        delete from db_sysindices where codind = 1008686;
        delete from db_sysforkey where  codarq = 1010784;
        delete from db_sysprikey where codarq = 1010784;
        delete from db_sysarqcamp where codarq = 1010784;
        delete from db_syscampo where codcam in (1013159,1013160,1013161);
        delete from db_sysarqmod where codarq = 1010784;
        delete from db_sysarquivo where codarq = 1010784;
SQL;

        $this->execute($sql);
    }

    private function estruturaUp()
    {
        $sql = <<<SQL
        CREATE TABLE escola.censoregiao(
            ed174_codigo  serial,
            ed174_nome    varchar,
            ed174_censomunic int4 not null,

            CONSTRAINT censoregiao_censomunic_fk FOREIGN KEY (ed174_censomunic) REFERENCES censomunic(ed261_i_codigo)
        );
        CREATE UNIQUE INDEX censoregiao_ed174_codigo_in ON censoregiao(ed174_codigo);

SQL;
        $this->execute($sql);
    }

    private function estruturaDown()
    {
        $this->execute(<<<SQL
        DROP TABLE escola.censoregiao;
SQL
        );
    }

    private function adicionarDados()
    {
        $sql = <<<SQL
        insert into censoregiao values(default,'RA I Plano Piloto' , 5300108),
        (default,'RA II Gama' , 5300108),
        (default,'RA III Taguatinga' , 5300108),
        (default,'RA IV Brazlândia' , 5300108),
        (default,'RA V Sobradinho' , 5300108),
        (default,'RA VI Planaltina' , 5300108),
        (default,'RA VII Paranoá' , 5300108),
        (default,'RA VIII Núcleo Bandeirante' , 5300108),
        (default,'RA IX Ceilândia' , 5300108),
        (default,'RA X Guará' , 5300108),
        (default,'RA XI Cruzeiro' , 5300108),
        (default,'RA XII Samambaia' , 5300108),
        (default,'RA XIII Santa Maria' , 5300108),
        (default,'RA XIV São Sebastião' , 5300108),
        (default,'RA XV Recanto das Emas' , 5300108),
        (default,'RA XVI Lago Sul' , 5300108),
        (default,'RA XVII Riacho Fundo' , 5300108),
        (default,'RA XVIII Lago Norte' , 5300108),
        (default,'RA XIX Candangolândia' , 5300108),
        (default,'RA XX Águas Claras' , 5300108),
        (default,'RA XXI Riacho Fundo II' , 5300108),
        (default,'RA XXII Sudoeste / Octogonal' , 5300108),
        (default,'RA XXIII Varjão' , 5300108),
        (default,'RA XXIV Park Way' , 5300108),
        (default,'RA XXV SCIA' , 5300108),
        (default,'RA XXVI Sobradinho II' , 5300108),
        (default,'RA XXVII Jardim Botânico' , 5300108),
        (default,'RA XXVIII Itapoã' , 5300108),
        (default,'RA XXIX SIA' , 5300108),
        (default,'RA XXX Vicente Pires' , 5300108),
        (default,'RA XXXI Fercal' , 5300108),
        (default,'RA XXXII Sol Nascente/Pôr do Sol' , 5300108),
        (default,'RA XXXIII Arniqueira' , 5300108);
SQL;
        $this->execute($sql);
    }
}
