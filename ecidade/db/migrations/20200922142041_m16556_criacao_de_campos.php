<?php

use Classes\PostgresMigration;

class M16556CriacaoDeCampos extends PostgresMigration
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

    private function upDicionario()
    {
        $this->execute(<<<SQL

            insert into db_syscampo values(1011819,'k03_apenastiporenuncia','bool','Permite apenas tipo renuncia no cancelamento de débitos da cgf','f', 'Apenas tipo renúncia (CGF)',1,'f','f','f',5,'text','Apenas tipo renúncia (CGF)');
            insert into db_syscampo values(1011820,'k03_filtrardepart','bool','Filtrar por departamento no processamento de cancelamentos','f', 'Filtrar por departamento',1,'f','f','f',5,'text','Filtrar por departamento');
            insert into db_syscampo values(1011821,'k20_depart','int8','Departamento do cancelamento','0', 'Departamento',1,'f','f','f',1,'text','Departamento');
            delete from db_sysarqcamp where codarq = 318;
            insert into db_sysarqcamp values(318,1904,1,0);
            insert into db_sysarqcamp values(318,10716,2,0);
            insert into db_sysarqcamp values(318,1905,3,17);
            insert into db_sysarqcamp values(318,1906,4,0);
            insert into db_sysarqcamp values(318,1907,5,0);
            insert into db_sysarqcamp values(318,1908,6,0);
            insert into db_sysarqcamp values(318,1909,7,0);
            insert into db_sysarqcamp values(318,1910,8,0);
            insert into db_sysarqcamp values(318,1911,9,0);
            insert into db_sysarqcamp values(318,1912,10,0);
            insert into db_sysarqcamp values(318,1913,11,0);
            insert into db_sysarqcamp values(318,1914,12,0);
            insert into db_sysarqcamp values(318,1915,13,0);
            insert into db_sysarqcamp values(318,7918,14,0);
            insert into db_sysarqcamp values(318,7925,15,0);
            insert into db_sysarqcamp values(318,7943,16,0);
            insert into db_sysarqcamp values(318,8737,17,0);
            insert into db_sysarqcamp values(318,8797,18,0);
            insert into db_sysarqcamp values(318,8799,19,0);
            insert into db_sysarqcamp values(318,9419,20,0);
            insert into db_sysarqcamp values(318,11859,21,0);
            insert into db_sysarqcamp values(318,14400,22,0);
            insert into db_sysarqcamp values(318,14484,23,0);
            insert into db_sysarqcamp values(318,14587,24,0);
            insert into db_sysarqcamp values(318,15036,25,0);
            insert into db_sysarqcamp values(318,17195,26,0);
            insert into db_sysarqcamp values(318,17196,27,0);
            insert into db_sysarqcamp values(318,17943,28,0);
            insert into db_sysarqcamp values(318,18059,29,0);
            insert into db_sysarqcamp values(318,18150,30,0);
            insert into db_sysarqcamp values(318,18429,31,0);
            insert into db_sysarqcamp values(318,18468,32,0);
            insert into db_sysarqcamp values(318,18874,33,0);
            insert into db_sysarqcamp values(318,19223,34,0);
            insert into db_sysarqcamp values(318,19647,35,0);
            insert into db_sysarqcamp values(318,20614,36,0);
            insert into db_sysarqcamp values(318,20230,37,0);
            insert into db_sysarqcamp values(318,1010703,38,0);
            insert into db_sysarqcamp values(318,1010704,39,0);
            insert into db_sysarqcamp values(318,1010705,40,0);
            insert into db_sysarqcamp values(318,1010706,41,0);
            insert into db_sysarqcamp values(318,1010707,42,0);
            insert into db_sysarqcamp values(318,1010708,43,0);
            insert into db_sysarqcamp values(318,1010709,44,0);
            insert into db_sysarqcamp values(318,1010710,45,0);
            insert into db_sysarqcamp values(318,1010711,46,0);
            insert into db_sysarqcamp values(318,1010712,47,0);
            insert into db_sysarqcamp values(318,1010713,48,0);
            insert into db_sysarqcamp values(318,1010714,49,0);
            insert into db_sysarqcamp values(318,1010715,50,0);
            insert into db_sysarqcamp values(318,1010716,51,0);
            insert into db_sysarqcamp values(318,1010717,52,0);
            insert into db_sysarqcamp values(318,1010718,53,0);
            insert into db_sysarqcamp values(318,1010719,54,0);
            insert into db_sysarqcamp values(318,1010720,55,0);
            insert into db_sysarqcamp values(318,1010721,56,0);
            insert into db_sysarqcamp values(318,1010722,57,0);
            insert into db_sysarqcamp values(318,1010723,58,0);
            insert into db_sysarqcamp values(318,1010724,59,0);
            insert into db_sysarqcamp values(318,1010725,60,0);
            insert into db_sysarqcamp values(318,1010726,61,0);
            insert into db_sysarqcamp values(318,1010727,62,0);
            insert into db_sysarqcamp values(318,1010728,63,0);
            insert into db_sysarqcamp values(318,1010729,64,0);
            insert into db_sysarqcamp values(318,1010730,65,0);
            insert into db_sysarqcamp values(318,1010731,66,0);
            insert into db_sysarqcamp values(318,1010732,67,0);
            insert into db_sysarqcamp values(318,1010733,68,0);
            insert into db_sysarqcamp values(318,1010734,69,0);
            insert into db_sysarqcamp values(318,1010736,70,0);
            insert into db_sysarqcamp values(318,1010737,71,0);
            insert into db_sysarqcamp values(318,1010738,72,0);
            insert into db_sysarqcamp values(318,1010739,73,0);
            insert into db_sysarqcamp values(318,1011819,74,0);
            insert into db_sysarqcamp values(318,1011820,75,0);
            delete from db_sysarqcamp where codarq = 1217;
            insert into db_sysarqcamp values(1217,7326,1,281);
            insert into db_sysarqcamp values(1217,11749,2,0);
            insert into db_sysarqcamp values(1217,10680,3,0);
            insert into db_sysarqcamp values(1217,7763,4,0);
            insert into db_sysarqcamp values(1217,7328,5,0);
            insert into db_sysarqcamp values(1217,7327,6,0);
            insert into db_sysarqcamp values(1217,7329,7,0);
            insert into db_sysarqcamp values(1217,1011821,8,0);
            insert into db_sysforkey values(1217,1011821,1,154,0);

SQL
        );
    }

    private function upDDL()
    {
        $this->execute(<<<SQL

            ALTER TABLE caixa.numpref ADD COLUMN k03_filtrardepart BOOLEAN default false;
            ALTER TABLE caixa.numpref ADD COLUMN k03_apenastiporenuncia BOOLEAN default true;
            ALTER TABLE caixa.cancdebitos ADD COLUMN k20_depart INTEGER;

            ALTER TABLE caixa.cancdebitos ADD CONSTRAINT cancdebitos_depart_fk FOREIGN KEY (k20_depart) REFERENCES configuracoes.db_depart (coddepto) MATCH FULL;

SQL
        );
    }

    private function downDicionario()
    {
        $this->execute(<<<SQL

            delete from db_sysforkey where codcam = 1011821;
            delete from db_sysarqcamp where codcam in (1011819, 1011820, 1011821);
            delete from db_syscampo where codcam in (1011819, 1011820, 1011821);

SQL
        );
    }

    private function downDDL()
    {
        $this->execute(<<<SQL

            ALTER TABLE caixa.numpref DROP COLUMN k03_filtrardepart;
            ALTER TABLE caixa.numpref DROP COLUMN k03_apenastiporenuncia;
            ALTER TABLE caixa.cancdebitos DROP COLUMN k20_depart;

SQL
        );
    }
}
