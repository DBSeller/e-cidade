<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M21514AtualizacaoFundamentacaoLegal extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::connection()->getPdo()->exec(<<<SQL
insert into db_cadattdinamicoatributosopcoes values (nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), 18, 'A28IIINLL', 'Art. 28, inc. III, da Lei no 14.133/21');
insert into db_cadattdinamicoatributosopcoes values (nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), 18, 'A28IINLL', 'Art. 28, inc. II, da Lei no 14.133/21');
insert into db_cadattdinamicoatributosopcoes values (nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), 18, 'A28INLL', 'Art. 28, inc. I, da Lei no 14.133/21');
insert into db_cadattdinamicoatributosopcoes values (nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), 18, 'A28IVNLL', 'Art. 28, inc. IV, da Lei no 14.133/21');
insert into db_cadattdinamicoatributosopcoes values (nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), 18, 'A75IVC', 'Art. 75, inc. IV, alínea "c" da Lei no 14.133/21');
insert into db_cadattdinamicoatributosopcoes values (nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), 18, 'A75IVD', 'Art. 75, inc. IV, alínea "d" da Lei no 14.133/21');
insert into db_cadattdinamicoatributosopcoes values (nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), 18, 'A75V', 'Art. 75, inc. V, da Lei no 14.133/21');
insert into db_cadattdinamicoatributosopcoes values (nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), 18, 'A78I', 'Art. 78, inc. I, da Lei no 14.133/21');
insert into db_cadattdinamicoatributosopcoes values (nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), 18, 'A78III', 'Art. 78, inc. III, da Lei no 14.133/21');
insert into db_cadattdinamicoatributosopcoes values (nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), 18, 'A29', 'Art.29 da Lei no 13.019/14');
insert into db_cadattdinamicoatributosopcoes values (nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), 18, 'A75IVB', 'Art. 75, inc. IV, alínea "b" da Lei no 14.133/21');
insert into db_cadattdinamicoatributosopcoes values (nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), 18, 'A75P7', 'Art. 75, § 7o, da Lei no 14.133/21');
insert into db_cadattdinamicoatributosopcoes values (nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'), 18, 'A24XXI', 'Art. 24, inc. XXI, da Lei no 8.666/93');
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
delete from db_cadattdinamicoatributosopcoes where db18_opcao in (
    'A28IIINLL',
    'A28IINLL',
    'A28INLL',
    'A28IVNLL',
    'A75IVC',
    'A75IVD',
    'A75V',
    'A78I',
    'A78III',
    'A29',
    'A75IVB',
    'A75P7',
    'A24XXI'
) and db18_cadattdinamicoatributos = 18;
SQL
        );
    }
}
