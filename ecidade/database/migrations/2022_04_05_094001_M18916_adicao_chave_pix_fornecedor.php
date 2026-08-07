<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M18916AdicaoChavePixFornecedor extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    private function upDicionario()
    {
        DB::connection()->getPdo()->exec(<<<SQL
insert into db_syscampo values(1013954,'pc63_tipopix','int4','Tipos aceitos: 1 - Escolha um tipo de chave, 2 - CPF, 3 - CNPJ, 4 - Celular, 5 - E-mail, 6 - Chave Aleatória','0', 'Tipo de Chave PIX',10,'t','f','f',1,'text','Tipo de Chave PIX');
insert into db_syscampo values(1013955,'pc63_chavepix','text','Chave PIX','', 'Chave PIX',1,'t','f','f',0,'text','Chave PIX');        
insert into db_sysarqcamp values(963,1013954,14,0);
insert into db_sysarqcamp values(963,1013955,15,0);
SQL
        );
    }

    private function upEstrutura()
    {
        DB::connection()->getPdo()->exec(<<<SQL
ALTER TABLE compras.pcfornecon ADD COLUMN pc63_tipopix INT DEFAULT NULL;
ALTER TABLE compras.pcfornecon ADD COLUMN pc63_chavepix TEXT DEFAULT NULL;
SQL
        );
    }

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
    private function downDicionario()
    {
        DB::connection()->getPdo()->exec(<<<SQL
delete from db_sysarqcamp where codarq = 963 and codcam in (1013954, 1013955);
delete from db_syscampo where codcam in (1013954, 1013955);
SQL
        );
    }

    private function downEstrutura()
    {
        DB::connection()->getPdo()->exec(<<<SQL
ALTER TABLE compras.pcfornecon DROP COLUMN pc63_tipopix;
ALTER TABLE compras.pcfornecon DROP COLUMN pc63_chavepix;
SQL
        );
    }

    public function down()
    {
        $this->downDicionario();
        $this->downEstrutura();
    }
}
