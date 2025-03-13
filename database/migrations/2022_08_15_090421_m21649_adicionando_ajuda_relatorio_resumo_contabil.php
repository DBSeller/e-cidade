<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;

class M21649AdicionandoAjudaRelatorioResumoContabil extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::connection()->getPdo()->exec(<<<sql
update db_itensmenu set id_item = 228623,
        descricao = 'Resumo contábil de estoque',
        help = 'https://e-cidade.wiki.br/patrimonial/material/#!tutorial_resumo_estoque_contabil.md',
        funcao = 'mat2_resumocontabilestoque.php',
        itemativo = '1',
        manutencao = '1',
        desctec = 'Resumo contábil de estoque',
        libcliente = 'true'
    where id_item = 228623;
sql
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::connection()->getPdo()->exec(<<<sql
update db_itensmenu set id_item = 228623,
        descricao = 'Resumo contábil de estoque',
        help = 'https://e-cidade.wiki.br/patrimonial/material/#!tutorial_resumo_estoque_contabil.md',
        funcao = 'mat2_resumocontabilestoque.php',
        itemativo = '1',
        manutencao = '1',
        desctec = 'Resumo contábil de estoque',
        libcliente = 'true'
    where id_item = 228623;
sql
        );
    }
}
