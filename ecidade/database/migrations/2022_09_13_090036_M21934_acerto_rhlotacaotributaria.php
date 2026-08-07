<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M21934AcertoRhlotacaotributaria extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->upAcertoRegistros();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }

    private function upAcertoRegistros()
    {
        $sql = <<<SQL
        -- EXCLUI TABELA TEMPORARIA SE EXISTE
        DROP TABLE IF EXISTS acerto_m21934;
        -- CRIA UMA TABELA TEMPORÁRIA COM REGISTROS ÚNICOS
        CREATE TEMP TABLE acerto_m21934 AS
        SELECT distinct 
            rh268_numcgm,
	        rh268_codigolotacao
	    FROM recursoshumanos.rhlotacaotributaria;
        -- EXCLUI TODOS OS REGISTROS DA TABELA
        TRUNCATE recursoshumanos.rhlotacaotributaria;
        -- INCLUI NA TABELA TODO OS REGISTROS ÚNICOS DA TABELA TEMPORÁRIA
        INSERT INTO recursoshumanos.rhlotacaotributaria
        SELECT nextval('rhlotacaotributaria_rh268_sequencial_seq'),
               rh268_numcgm,
               rh268_codigolotacao 
        FROM acerto_m21934;
SQL;
        DB::connection()->getPdo()->exec($sql);       
    }

}
