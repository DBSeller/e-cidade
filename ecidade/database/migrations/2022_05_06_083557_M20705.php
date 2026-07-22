<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M20705 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::connection()->getPdo()->exec(<<<SQL
insert into orcparamseqcoluna (o115_sequencial, o115_anousu, o115_descricao, o115_tipo, o115_valoresdefault, o115_nomecoluna, o115_formula, o115_origem, o115_relatorio)
values (nextval('orcparamseqcoluna_o115_sequencial_seq'), 2021, 'Bal Ver Saldo Final Acumulado', 1, '', 'saldo_final_acumulado', '', 0, 264);

update orcparamseqorcparamseqcoluna set o116_orcparamseqcoluna = currval('orcparamseqcoluna_o115_sequencial_seq')
where o116_codseq = 82 and o116_codparamrel = 264;
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
        return true;
    }
}
