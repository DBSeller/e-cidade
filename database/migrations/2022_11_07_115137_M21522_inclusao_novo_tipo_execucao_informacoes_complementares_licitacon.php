<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M21522InclusaoNovoTipoExecucaoInformacoesComplementaresLicitacon extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::connection()->getPdo()->exec(<<<SQL
insert into db_cadattdinamicoatributosopcoes
select nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), db109_sequencial,'F', 'Fornecimento e prestação de serviço associado'
from db_cadattdinamicoatributos
where db109_descricao ilike '%Regime de Execução%';

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
delete from db_cadattdinamicoatributosopcoes
where db18_opcao='F' and db18_cadattdinamicoatributos = (
select db109_sequencial from db_cadattdinamicoatributos
where db18_cadattdinamicoatributos = db109_sequencial);

SQL
        );
    }
}
