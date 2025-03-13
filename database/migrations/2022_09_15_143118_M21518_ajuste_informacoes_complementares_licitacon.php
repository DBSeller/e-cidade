<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M21518AjusteInformacoesComplementaresLicitacon extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::connection()->getPdo()->exec(<<<SQL
-- Tipo de Objeto
update db_cadattdinamicoatributosopcoes set db18_valor = 'Concessão de Uso' where db18_cadattdinamicoatributos = 6 and db18_opcao = 'CON';
insert into db_cadattdinamicoatributosopcoes values (nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), 6, 'COL', 'Concessão Lei 8.987');
insert into db_cadattdinamicoatributosopcoes values (nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), 6, 'PPP', 'Parceria Público-Privada');
insert into db_cadattdinamicoatributosopcoes values (nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), 6, 'PRI', 'Privatização');
insert into db_cadattdinamicoatributosopcoes values (nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), 6, 'SAU', 'Serviços de Saúde');
insert into db_cadattdinamicoatributosopcoes values (nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), 6, 'INF', 'Informática - TIC');

-- Tipo de Licitação
update db_cadattdinamicoatributos set db109_descricao = 'Critério de Julgamento' where db109_nome = 'tipolicitacao';
update db_layoutcampos set db52_nome = 'TP_CRITERIO_JULGAMENTO', db52_descr = 'TP_CRITERIO_JULGAMENTO' where db52_codigo = 171260 and db52_layoutlinha = 10290;
update db_cadattdinamicoatributosopcoes set db18_valor = 'Maior Lance' where db18_opcao = 'MLO';

-- Tipo de Beneficio à Microempresa e Empresa de Pequeno Porte
insert into db_cadattdinamicoatributosopcoes values (nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), 11, 'S', 'Subcontratação para ME/EPP');
delete from db_cadattdinamicoatributosopcoes where db18_opcao in ('R', 'P', 'C') and db18_cadattdinamicoatributos = 11;

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
-- Tipo de Objeto
update db_cadattdinamicoatributosopcoes set db18_valor = 'Concessão' where db18_cadattdinamicoatributos = 6 and db18_opcao = 'CON';
delete from db_cadattdinamicoatributosopcoes where db18_opcao in ('COL', 'PPP', 'PRI', 'SAU', 'INF') and db18_cadattdinamicoatributos = 6;

-- Tipo de Licitação
update db_cadattdinamicoatributos set db109_descricao = 'Tipo de Licitação' where db109_nome = 'tipolicitacao';
update db_layoutcampos set db52_nome = 'TP_LICITACAO', db52_descr = 'TP_LICITACAO' where db52_codigo = 171260 and db52_layoutlinha = 10290;
update db_cadattdinamicoatributosopcoes set db18_valor = 'Maior Lance ou Oferta' where db18_opcao = 'MLO';

-- Tipo de Beneficio à Microempresa e Empresa de Pequeno Porte
delete from db_cadattdinamicoatributosopcoes where db18_opcao = 'S' and db18_cadattdinamicoatributos = 11;
insert into db_cadattdinamicoatributosopcoes values (nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), 11, 'R', 'Cota reservada');
insert into db_cadattdinamicoatributosopcoes values (nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), 11, 'P', 'Cota principal');
insert into db_cadattdinamicoatributosopcoes values (nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), 11, 'C', 'Cotas para ME/EPP');

SQL
        );
    }
}
