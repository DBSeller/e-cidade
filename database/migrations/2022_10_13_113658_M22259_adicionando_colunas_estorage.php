<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class M22259AdicionandoColunasEstorage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::connection()->getPdo()->exec(<<<SQL

        ALTER TABLE escola.aluno ADD COLUMN ed47_i_cpf_estorage int8 default null; 

        ALTER TABLE escola.aluno ADD COLUMN ed47_i_certidado_estorage int8 default null;

        ALTER TABLE escola.rhformacaosuperior ADD COLUMN ed183_docpos_estorage int8 default null;

        ALTER TABLE escola.formacao ADD COLUMN ed27_i_docformacao_estorage int8 default null;

        insert into db_syscampo values(1014570,'ed47_i_cpf_estorage','float8','id imagem cpf e-storage','0', 'id imagem cpf e-storage',10,'t','f','f',4,'text','id imagem cpf e-storage');
        insert into db_syscampo values(1014571,'ed47_i_certidado_estorage','float8','id certidao e-storage','0', 'id certidao e-storage',10,'t','f','f',4,'text','id certidao e-storage');
        insert into db_syscampo values(1014572,'ed183_docpos_estorage','int8','id posgraduacao e-storage','0', 'id posgraduacao e-storage',10,'t','f','f',1,'text','id posgraduacao e-storage');
        insert into db_syscampo values(1014573,'ed27_i_docformacao_estorage','int8','id formacao e-storage','0', 'id formacao e-storage',10,'t','f','f',1,'text','id formacao e-storage');

        insert into db_sysarqcamp values(1010051,1014571,82,0);
        insert into db_sysarqcamp values(1010051,1014570,83,0);
        insert into db_sysarqcamp values(1010920,1014572,7,0);
        insert into db_sysarqcamp values(1010089,1014573,10,0);

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
        DB::connection()->getPdo()->exec(<<<SQL
        
        ALTER TABLE escola.aluno DROP COLUMN ed47_i_cpf_estorage;

        ALTER TABLE escola.aluno DROP COLUMN ed47_i_certidado_estorage;

        ALTER TABLE escola.rhformacaosuperior DROP COLUMN ed183_docpos_estorage;

        ALTER TABLE escola.formacao DROP COLUMN ed27_i_docformacao_estorage;

        delete from db_sysarqcamp where codcam in (1014571, 1014570, 1014572, 1014573);

        delete from db_syscampo where codcam in (1014571, 1014570, 1014572, 1014573);
SQL
        );
    }
}
