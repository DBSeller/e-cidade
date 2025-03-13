<?php

use Classes\PostgresMigration;

class M18294CriaTabelaRegenciaHorarioDiscSemReg extends PostgresMigration
{
    public function up()
    {
        $this->criaTabela();
        $this->dicionarioUp();
    }

    public function down()
    {
        $this->deletaTabela();
        $this->dicionarioDown();
    }

    private function criaTabela()
    {
        $sql = "CREATE TABLE escola.regenciahorariodiscsemreg (
                ed175_codigo  serial not null,
                ed175_regencia integer not null,
                ed175_diasemana integer not null,
                ed175_periodo integer not null,
                ed175_rechumano integer,
                ed175_ativo boolean not null,
                ed175_tipovinculo integer not null,
                ed175_datainicio date,
                ed175_datafim date,
                CONSTRAINT regenciahorariodiscsemreg_ed175_codigo_pk PRIMARY KEY (ed175_codigo),
                CONSTRAINT regenciahorariodiscsemreg_diasemana_fk FOREIGN KEY (ed175_diasemana) REFERENCES diasemana(ed32_i_codigo),
                CONSTRAINT regenciahorariodiscsemreg_periodo_fk FOREIGN KEY (ed175_periodo) REFERENCES periodoescola(ed17_i_codigo),
                CONSTRAINT regenciahorariodiscsemreg_regencia_fk FOREIGN KEY (ed175_regencia) REFERENCES regencia(ed59_i_codigo)
            );
            CREATE UNIQUE INDEX regenciahorariodiscsemreg_ed175_codigo_fk ON regenciahorariodiscsemreg(ed175_codigo);
            ";
        $this->execute($sql);
    }

    private function deletaTabela()
    {
        $sql = "DROP TABLE escola.regenciahorariodiscsemreg";
        $this->execute($sql);
    }

    private function dicionarioUp()
    {
    $sql = "
        insert into db_sysarquivo values (1010804, 'regenciahorariodiscsemreg', 'Grava as disciplinas que estão sem regente na grade de horário.', 'ed175', '2021-06-08', 'regencia horario disciplina sem regente', 0, 'f', 'f', 'f', 'f' );
        insert into db_sysarqmod values (1008004,1010804);
        insert into db_syscampo values(1013280,'ed175_codigo','int8','Código','0', 'Código',10,'f','f','f',1,'text','Código');
        insert into db_syscampo values(1013281,'ed175_regencia','int8','Regencia','0', 'Regencia',10,'f','f','f',1,'text','Regencia');
        insert into db_syscampo values(1013282,'ed175_diassemana','int8','Dias da Semana','0', 'Dias Semana',10,'f','f','f',1,'text','Dias Semana');
        insert into db_syscampo values(1013283,'ed175_periodo','int8','Período','0', 'Periodo',10,'f','f','f',1,'text','Periodo');
        insert into db_syscampo values(1013284,'ed175_rechumano','int8','rechumano','0', 'RecHumano',10,'t','f','f',1,'text','RecHumano');
        insert into db_syscampo values(1013285,'ed175_ativo','bool','Ativo','f', 'Ativo',1,'t','f','f',5,'text','Ativo');
        insert into db_syscampo values(1013286,'ed175_tipovinculo','int8','Tipo Vinculo','0', 'TipoVinculo',10,'t','f','f',1,'text','TipoVinculo');
        insert into db_syscampo values(1013287,'ed175_datainicio','date','Data Inicio','null', 'Data Inicio',20,'f','f','f',1,'text','Data Inicio');
        insert into db_syscampo values(1013288,'ed175_datafim','date','Data Fim','null', 'Data Fim',20,'f','f','f',1,'text','Data Fim');
        insert into db_sysarqcamp values(1010804,1013280,1,0);
        insert into db_sysarqcamp values(1010804,1013281,2,0);
        insert into db_sysarqcamp values(1010804,1013282,3,0);
        insert into db_sysarqcamp values(1010804,1013283,4,0);
        insert into db_sysarqcamp values(1010804,1013284,5,0);
        insert into db_sysarqcamp values(1010804,1013285,6,0);
        insert into db_sysarqcamp values(1010804,1013286,7,0);
        insert into db_sysarqcamp values(1010804,1013287,8,0);
        insert into db_sysarqcamp values(1010804,1013288,9,0);
        update db_syscampo set nomecam = 'ed175_diasemana', conteudo = 'int8', descricao = 'Dias da Semana', valorinicial = '0', rotulo = 'Dias Semana', nulo = 'f', tamanho = 10, maiusculo = 'f', autocompl = 'f', aceitatipo = 1, tipoobj = 'text', rotulorel = 'Dias Semana' where codcam = 1013282;
        insert into db_sysforkey values(1010804,1013282,1,1010090,0);
        insert into db_sysforkey values(1010804,1013283,1,1010040,0);
        insert into db_sysforkey values(1010804,1013281,1,1010084,0);
        insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010804,1013280,1,1013280);";

    $this->execute($sql);
    }

    private function dicionarioDown()
    {
        $sql = "
        delete from db_sysforkey where codarq = 1010804;
        delete from db_sysprikey where codarq = 1010804;
        delete from db_sysarqcamp where codarq = 1010804;
        delete from db_syscampo where codcam in (1013280,1013281,1013282,1013283,1013284,1013285,1013286,1013287,1013288);
        delete from db_sysarqmod where codarq = 1010804;
        delete from db_sysarquivo where codarq = 1010804;
        ";

        $this->execute($sql);
    }
}
