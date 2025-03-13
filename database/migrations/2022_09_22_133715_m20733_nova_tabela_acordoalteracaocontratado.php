<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M20733NovaTabelaAcordoalteracaocontratado extends Migration
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
        DB::connection()->getPdo()->exec(<<<SQL
insert into db_sysarquivo values (1010987, 'acordoalteracaocontratado', 'Alteração ou Cessão do Contratado', 'ac60', '2022-09-22', 'Alteração ou Cessão do Contratado', 0, 'f', 'f', 'f', 'f' );
insert into db_sysarqmod values (69,1010987);
insert into db_syscampo values(1014494,'ac60_sequencial','int4','Código Alteração Contratado','0', 'Código Alteração Contratado',10,'f','f','f',1,'text','Código Alteração Contratado');
insert into db_syscampo values(1014495,'ac60_acordo','int4','Acordo','0', 'Acordo',10,'f','f','f',1,'text','Acordo');
insert into db_syscampo values(1014496,'ac60_posicao','int4','Posição do Acordo','0', 'Posição do Acordo',10,'f','f','f',1,'text','Posição do Acordo');
insert into db_syscampo values(1014497,'ac60_anterior','int4','Contratado Anterior','0', 'Contratado Anterior',10,'f','f','f',1,'text','Contratado Anterior');
insert into db_syscampo values(1014498,'ac60_novo','int4','Contratado Novo','0', 'Contratado Novo',10,'f','f','f',1,'text','Contratado Novo');

insert into db_sysarqcamp values(1010987,1014494,1,0);
insert into db_sysarqcamp values(1010987,1014495,2,0);
insert into db_sysarqcamp values(1010987,1014496,3,0);
insert into db_sysarqcamp values(1010987,1014497,4,0);
insert into db_sysarqcamp values(1010987,1014498,5,0);

insert into db_sysforkey values(1010987,1014495,1,2828,0);
insert into db_sysforkey values(1010987,1014496,1,2930,0);
insert into db_sysforkey values(1010987,1014497,1,42,0);
insert into db_sysforkey values(1010987,1014498,1,42,0);
insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010987,1014494,1,1014494);
insert into db_syssequencia values(1001092, 'acordoalteracaocontratado_ac60_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
update db_sysarqcamp set codsequencia = 1001092 where codarq = 1010987 and codcam = 1014494;

SQL
        );
    }
    private function downDicionario() {
        DB::connection()->getPdo()->exec(<<<SQL
delete from db_syssequencia where codsequencia = 1001092;
delete from db_sysprikey where codarq = 1010987 and codcam = 1014494;
delete from db_sysforkey where codarq = 1010987 and codcam in (1014495, 1014496, 1014497, 1014498);
delete from db_sysarqcamp where codarq = 1010987 and codcam in (1014494,1014495,1014496,1014497,1014498);
delete from db_syscampo where codcam in (1014494,1014495,1014496,1014497,1014498);
delete from db_sysarqmod where codmod = 69 and codarq = 1010987;
delete from db_sysarquivo where codarq = 1010987;
SQL
        );
    }
    private function upEstrutura() {
        DB::connection()->getPdo()->exec(<<<SQL
CREATE SEQUENCE acordos.acordoalteracaocontratado_ac60_sequencial_seq
    INCREMENT 1
        MINVALUE 1
        MAXVALUE 9223372036854775807
        START 1
        CACHE 1;

CREATE TABLE acordos.acordoalteracaocontratado (
    ac60_sequencial SERIAL PRIMARY KEY ,
    ac60_acordo INTEGER NOT NULL,
    ac60_posicao INTEGER NOT NULL,
    ac60_anterior INTEGER NOT NULL,
    ac60_novo INTEGER NOT NULL,
    CONSTRAINT acordoalteracaocontratado_ac60_acordo_fk
        FOREIGN KEY (ac60_acordo)
            REFERENCES acordo (ac16_sequencial),
    CONSTRAINT acordoalteracaocontratado_ac60_posicao_fk
        FOREIGN KEY (ac60_posicao)
            REFERENCES acordoposicao (ac26_sequencial),
    CONSTRAINT acordoalteracaocontratado_ac60_anterior_fk
        FOREIGN KEY (ac60_anterior)
            REFERENCES cgm (z01_numcgm),
    CONSTRAINT acordoalteracaocontratado_ac60_novo_fk
        FOREIGN KEY (ac60_novo)
            REFERENCES cgm (z01_numcgm)
)
SQL
        );
    }
    private function downEstrutura() {
        DB::connection()->getPdo()->exec(<<<SQL
        DROP SEQUENCE acordoalteracaocontratado_ac60_sequencial_seq;
        DROP TABLE acordoalteracaocontratado;
SQL
        );
    }
}
