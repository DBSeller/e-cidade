<?php

use Classes\PostgresMigration;

class M18408CriaTabelaTurmaacHorarioProfissionalSemRec extends PostgresMigration
{

    public function up()
    {
        $this->dicionarioUp();
        $this->estruturaUp();
    }

    public function down()
    {
        $this->dicionarioDown();
        $this->estruturaDown();
    }

    private function dicionarioUp()
    {
        $sql = " insert into db_sysarquivo values (1010808, 'turmaachorarioprofissionalsemrec', 'Guarda os registros de horários da turma sem um profissional e uma função vinculada.', 'ed176', '2021-06-18', 'turmaac horario profissional sem rechumano', 0, 'f', 'f', 'f', 'f' );
                insert into db_sysarqmod values (1008004,1010808);
                insert into db_syscampo values(1013299,'ed176_sequencial','int8','Código','0', 'Codigo',10,'f','f','f',1,'text','Codigo');
                insert into db_syscampo values(1013300,'ed176_turmaac','int8','turmaac','0', 'turmaac',10,'f','f','f',1,'text','turmaac');
                insert into db_syscampo values(1013301,'ed176_funcaoatividade','int8','funcaoatividade','0', 'funcaoatividade',10,'t','f','f',1,'text','funcaoatividade');
                insert into db_syscampo values(1013302,'ed176_rechumano','int8','rechumano','0', 'rechumano',10,'t','f','f',1,'text','rechumano');
                insert into db_syscampo values(1013303,'ed176_diasemana','int8','diasemana','0', 'diasemana',10,'f','f','f',1,'text','diasemana');
                insert into db_syscampo values(1013304,'ed176_horainicial','varchar(5)','horainicial','', 'horainicial',5,'f','f','f',0,'text','horainicial');
                insert into db_syscampo values(1013305,'ed176_horafinal','varchar(5)','horafinal','', 'horafinal',5,'t','f','f',0,'text','horafinal');
                delete from db_sysarqcamp where codarq = 1010808;
                insert into db_sysarqcamp values(1010808,1013299,1,0);
                insert into db_sysarqcamp values(1010808,1013300,2,0);
                insert into db_sysarqcamp values(1010808,1013301,3,0);
                insert into db_sysarqcamp values(1010808,1013302,4,0);
                insert into db_sysarqcamp values(1010808,1013303,5,0);
                insert into db_sysarqcamp values(1010808,1013304,6,0);
                insert into db_sysarqcamp values(1010808,1013305,7,0);
                delete from db_sysprikey where codarq = 1010808;
                insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010808,1013299,1,1013299);
                delete from db_sysforkey where codarq = 1010808 and referen = 0;
                insert into db_sysforkey values(1010808,1013303,1,1010090,0);
                delete from db_sysforkey where codarq = 1010808 and referen = 0;
                insert into db_sysforkey values(1010808,1013300,1,2416,0);";
        $this->execute($sql);
    }

    private function dicionarioDown()
    {
       $sql = "delete from db_sysforkey where codarq = 1010808;
               delete from db_sysprikey where codarq = 1010808;
               delete from db_sysarqcamp where codarq = 1010808;
               delete from db_syscampo where codcam in (1013299,1013300,1013301,1013302,1013303,1013304,1013305);
               delete from db_sysarqmod where codarq = 1010808;
               delete from db_sysarquivo where codarq = 1010808;";

       $this->execute($sql);
    }

    private function estruturaUp()
    {
       $sql = "CREATE TABLE escola.turmaachorarioprofissionalsemrec(
        ed176_sequencial  serial not null,
            ed176_turmaac int not null,
            ed176_funcaoatividade int,
            ed176_rechumano int,
            ed176_diasemana int,
            ed176_horainicial varchar(5) not null,
            ed176_horafinal varchar(5),

            CONSTRAINT turmaachorarioprofissionalsemrec_ed176_sequencial_pk PRIMARY KEY (ed176_sequencial),
            CONSTRAINT turmaachorarioprofissionalsemrec_diasemana_fk FOREIGN KEY (ed176_diasemana) REFERENCES diasemana(ed32_i_codigo),
            CONSTRAINT turmaachorarioprofissionalsemrec_turmaac_fk FOREIGN KEY (ed176_turmaac) REFERENCES turmaac(ed268_i_codigo)
        );
        CREATE UNIQUE INDEX turmaachorarioprofissionalsemrec_ed176_sequencial_fk ON turmaachorarioprofissionalsemrec(ed176_sequencial);
        ";

       $this->execute($sql);
    }

    private function estruturaDown()
    {
        $sql = "DROP TABLE escola.turmaachorarioprofissionalsemrec;";
        $this->execute($sql);
    }
}
