<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M19792ServidorContratoJornada extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->upDicionario();
        $this->upEstrutura();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->downDicionario();
        $this->downEstrutura();
    }

    private function upDicionario() {
        $sql = <<<SQL
        insert into configuracoes.db_sysarquivo values (1010845, 'rhservidorcontratojornada', 'Jornada contratual do servidor', 'rh254', '2022-01-04', 'Jornada contratual do servidor', 0, 'f', 'f', 'f', 'f' );
        insert into configuracoes.db_sysarqmod values (29,1010845);
        insert into configuracoes.db_syscampo values(1013582,'rh254_sequencial','int4','Código sequencial único','0', 'Código sequencial único',10,'f','f','f',1,'text','Código sequencial único');
        insert into configuracoes.db_syscampo values(1013583,'rh254_matricula','int4','Código de matrícula do servidor','0', 'Código de matrícula do servidor',10,'f','f','f',1,'text','Código de matrícula do servidor');
        insert into configuracoes.db_syscampo values(1013584,'rh254_instit','int4','Código de instituição','0', 'Código de instituição',10,'f','f','f',1,'text','Código de instituição');
        insert into configuracoes.db_syscampo values(1013585,'rh254_tipojornada','int4','Tipo de jornada','0', 'Tipo de jornada',10,'t','f','f',1,'text','Tipo de jornada');
        insert into configuracoes.db_syscampo values(1013586,'rh254_tempoparcial','int4','Código relativo ao tipo de contrato em tempo parcial','0', 'Tipo de contrato em tempo parcial',10,'t','f','f',1,'text','Tipo de contrato em tempo parcial');
        insert into configuracoes.db_syscampo values(1013587,'rh254_horarionoturno','varchar(1)','Indicar se a jornada semanal possui horário noturno (no todo ou em parte).','', 'Possui horário noturno',1,'t','t','f',0,'text','Possui horário noturno');
        insert into configuracoes.db_syscampo values(1013588,'rh254_descricaojornada','varchar(255)','Descrição da jornada semanal contratual, contendo os dias da semana e os respectivos horários contratuais (entrada, saída e intervalos)','', 'Descrição da jornada semanal contratual',255,'t','t','f',0,'text','Descrição da jornada semanal contratual');
        delete from configuracoes.db_sysarqcamp where codarq = 1010845;
        insert into configuracoes.db_sysarqcamp values(1010845,1013582,1,0);
        insert into configuracoes.db_sysarqcamp values(1010845,1013583,2,0);
        insert into configuracoes.db_sysarqcamp values(1010845,1013584,3,0);
        insert into configuracoes.db_sysarqcamp values(1010845,1013585,4,0);
        insert into configuracoes.db_sysarqcamp values(1010845,1013586,5,0);
        insert into configuracoes.db_sysarqcamp values(1010845,1013587,6,0);
        insert into configuracoes.db_sysarqcamp values(1010845,1013588,7,0);
        delete from configuracoes.db_sysprikey where codarq = 1010845;
        insert into configuracoes.db_sysprikey (codarq,codcam,sequen,camiden) values(1010845,1013582,1,1013582);
        delete from configuracoes.db_sysforkey where codarq = 1010845 and referen = 0;
        insert into configuracoes.db_sysforkey values(1010845,1013583,1,1153,0);
        delete from configuracoes.db_sysforkey where codarq = 1010845 and referen = 0;
        insert into configuracoes.db_sysforkey values(1010845,1013584,1,83,0);
        insert into configuracoes.db_syssequencia values(1001025, 'rhservidorcontratojornada_rh254_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
        update configuracoes.db_sysarqcamp set codsequencia = 1001025 where codarq = 1010845 and codcam = 1013582;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function downDicionario() {
        $sql = <<<SQL
        delete from configuracoes.db_sysforkey where codarq = 1010845 and codcam = 1013584;
        delete from configuracoes.db_sysforkey where codarq = 1010845 and codcam = 1013583;
        delete from configuracoes.db_sysprikey where codarq = 1010845 and codcam = 1013582;
        delete from configuracoes.db_sysarqcamp where codarq = 1010845 and codcam between 1013582 and 1013588;
        delete from configuracoes.db_syscampo where codcam between 1013582 and 1013588;
        delete from configuracoes.db_sysarqmod where codmod = 29 and codarq = 1010845;
        delete from configuracoes.db_sysarquivo where codarq = 1010845;
        delete from configuracoes.db_syssequencia where codsequencia = 1001025;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function upEstrutura() {
        $sql = <<<SQL
        -- Criando  sequences
        CREATE SEQUENCE recursoshumanos.rhservidorcontratojornada_rh254_sequencial_seq
        INCREMENT 1
        MINVALUE 1
        MAXVALUE 9223372036854775807
        START 1
        CACHE 1;
        -- TABELAS E ESTRUTURA
        -- Módulo: recursoshumanos
        CREATE TABLE recursoshumanos.rhservidorcontratojornada(
        rh254_sequencial		int4 default nextval('rhservidorcontratojornada_rh254_sequencial_seq'),
        rh254_matricula		int4 NOT NULL default 0,
        rh254_instit		int4 NOT NULL default 0,
        rh254_tipojornada		int4  default 0,
        rh254_tempoparcial		int4  default 0,
        rh254_horarionoturno		varchar(1)   default '',
        rh254_descricaojornada		varchar(255)  default '',
        CONSTRAINT rhservidorcontratojornada_sequ_pk PRIMARY KEY (rh254_sequencial));
        -- CHAVE ESTRANGEIRA
        ALTER TABLE recursoshumanos.rhservidorcontratojornada
        ADD CONSTRAINT rhservidorcontratojornada_instit_fk FOREIGN KEY (rh254_instit)
        REFERENCES configuracoes.db_config;
        ALTER TABLE recursoshumanos.rhservidorcontratojornada
        ADD CONSTRAINT rhservidorcontratojornada_matricula_fk FOREIGN KEY (rh254_matricula)
        REFERENCES pessoal.rhpessoal;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function downEstrutura() {
        $sql = <<<SQL
        --DROP TABLE:
        DROP TABLE IF EXISTS recursoshumanos.rhservidorcontratojornada;
        --Criando drop sequences
        DROP SEQUENCE IF EXISTS recursoshumanos.rhservidorcontratojornada_rh254_sequencial_seq;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }
}
