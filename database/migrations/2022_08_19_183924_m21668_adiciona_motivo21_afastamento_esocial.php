<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M21668AdicionaMotivo21AfastamentoEsocial extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sql = <<<SQL
        update configuracoes.db_cadattdinamicoatributos set db109_tipo = 6 where  db109_descricao = 'Motivo' and db109_valordefault = '21';
        insert into configuracoes.db_cadattdinamicoatributosopcoes (select (select nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq')), db109_sequencial, 21, 'Licença não remunerada ou sem vencimento' from configuracoes.db_cadattdinamicoatributos  where  db109_descricao = 'Motivo' and db109_valordefault = '21');
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $sql = <<<SQL
        update configuracoes.db_cadattdinamicoatributos set db109_tipo = 7 where  db109_descricao = 'Motivo' and db109_valordefault = '21';
        delete from configuracoes.db_cadattdinamicoatributosopcoes where db18_opcao = '21' and db18_valor = 'Licença não remunerada ou sem vencimento';
SQL;
        DB::connection()->getPdo()->exec($sql);
    }
}
