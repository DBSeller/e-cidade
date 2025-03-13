<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M18914AdicionarCampoLoginCriadorAutorizacaoEmpenho extends Migration
{
    private function upDicionario()
    {
        DB::connection()->getPdo()->exec(<<<SQL
insert into db_syscampo values(1014039,'e54_logincriador','int4','Login do criador da autorização de empenho.','0', 'Login autorização original',10,'f','f','f',1,'text','Login autorização original');
insert into db_sysarqcamp values(810,1014039,24,0);
insert into db_sysforkey values(810,1014039,1,109,0);
SQL
        );
    }

    private function upEstrutura()
    {
        DB::connection()->getPdo()->exec(<<<SQL
ALTER TABLE empenho.empautoriza ADD COLUMN e54_logincriador INT DEFAULT NULL;

/* Populando o login do criador */
UPDATE empenho.empautoriza SET e54_logincriador = e54_login;

/* Adicionar NOT NULL no campo e54_logincriador */
ALTER TABLE empenho.empautoriza ALTER COLUMN e54_logincriador SET NOT NULL;

ALTER TABLE empenho.empautoriza
ADD CONSTRAINT empautoriza_logincriador_fk FOREIGN KEY (e54_logincriador)
REFERENCES db_usuarios;
SQL
        );
    }

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

    private function downDicionario()
    {
        DB::connection()->getPdo()->exec(<<<SQL
delete from db_sysforkey where codarq = 810 and codcam = 1014039;
delete from db_sysarqcamp where codarq = 810 and codcam = 1014039;
delete from db_syscampo where codcam = 1014039;
SQL
        );
    }

    private function downEstrutura()
    {
        DB::connection()->getPdo()->exec(<<<SQL
ALTER TABLE empenho.empautoriza DROP COLUMN e54_logincriador;
SQL
        );
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
}
