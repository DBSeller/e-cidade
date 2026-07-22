<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M21185AjusteSequencialLayutLinha extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sql = <<<SQL
            insert into configuracoes.db_layoutlinha( db51_codigo ,db51_layouttxt ,db51_descr ,db51_tipolinha ,db51_tamlinha ,db51_linhasantes ,db51_linhasdepois ,db51_obs ,db51_separador ,db51_compacta ) values ( 1000029 ,309 ,'HEADER DO ARQUIVO' ,1 ,130 ,0 ,0 ,'' ,'' ,'0' );
            insert into configuracoes.db_layoutlinha( db51_codigo ,db51_layouttxt ,db51_descr ,db51_tipolinha ,db51_tamlinha ,db51_linhasantes ,db51_linhasdepois ,db51_obs ,db51_separador ,db51_compacta ) values ( 1000031 ,309 ,'REGISTRO' ,3 ,138 ,0 ,0 ,'' ,'' ,'0' );
            insert into configuracoes.db_layoutlinha( db51_codigo ,db51_layouttxt ,db51_descr ,db51_tipolinha ,db51_tamlinha ,db51_linhasantes ,db51_linhasdepois ,db51_obs ,db51_separador ,db51_compacta ) values ( 1000032 ,309 ,'TRAILLER DO ARQUIVO' ,5 ,22 ,0 ,0 ,'' ,'' ,'0' );
            insert into configuracoes.db_layoutlinha( db51_codigo ,db51_layouttxt ,db51_descr ,db51_tipolinha ,db51_tamlinha ,db51_linhasantes ,db51_linhasdepois ,db51_obs ,db51_separador ,db51_compacta ) values ( 1000033 ,310 ,'HEADER DO ARQUIVO' ,1 ,130 ,0 ,0 ,'' ,'' ,'0' );
            insert into configuracoes.db_layoutlinha( db51_codigo ,db51_layouttxt ,db51_descr ,db51_tipolinha ,db51_tamlinha ,db51_linhasantes ,db51_linhasdepois ,db51_obs ,db51_separador ,db51_compacta ) values ( 1000034 ,310 ,'REGISTRO' ,3 ,82 ,0 ,0 ,'' ,'' ,'0' );
            insert into configuracoes.db_layoutlinha( db51_codigo ,db51_layouttxt ,db51_descr ,db51_tipolinha ,db51_tamlinha ,db51_linhasantes ,db51_linhasdepois ,db51_obs ,db51_separador ,db51_compacta ) values ( 1000035 ,310 ,'TRAILLER DO ARQUIVO' ,5 ,22 ,0 ,0 ,'' ,'' ,'0' );

            update configuracoes.db_layoutcampos set db52_layoutlinha = 1000029 where db52_layoutlinha = 1029;
            update configuracoes.db_layoutcampos set db52_layoutlinha = 1000031 where db52_layoutlinha = 1031;
            update configuracoes.db_layoutcampos set db52_layoutlinha = 1000032 where db52_layoutlinha = 1032;
            update configuracoes.db_layoutcampos set db52_layoutlinha = 1000033 where db52_layoutlinha = 1033;
            update configuracoes.db_layoutcampos set db52_layoutlinha = 1000034 where db52_layoutlinha = 1034;
            update configuracoes.db_layoutcampos set db52_layoutlinha = 1000035 where db52_layoutlinha = 1035;

            delete from configuracoes.db_layoutlinha where db51_codigo in (1029, 1030, 1031, 1032, 1033, 1034, 1035);
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
            insert into configuracoes.db_layoutlinha( db51_codigo ,db51_layouttxt ,db51_descr ,db51_tipolinha ,db51_tamlinha ,db51_linhasantes ,db51_linhasdepois ,db51_obs ,db51_separador ,db51_compacta ) values ( 1029 ,309 ,'HEADER DO ARQUIVO' ,1 ,130 ,0 ,0 ,'' ,'' ,'0' );
            insert into configuracoes.db_layoutlinha( db51_codigo ,db51_layouttxt ,db51_descr ,db51_tipolinha ,db51_tamlinha ,db51_linhasantes ,db51_linhasdepois ,db51_obs ,db51_separador ,db51_compacta ) values ( 1031 ,309 ,'REGISTRO' ,3 ,138 ,0 ,0 ,'' ,'' ,'0' );
            insert into configuracoes.db_layoutlinha( db51_codigo ,db51_layouttxt ,db51_descr ,db51_tipolinha ,db51_tamlinha ,db51_linhasantes ,db51_linhasdepois ,db51_obs ,db51_separador ,db51_compacta ) values ( 1032 ,309 ,'TRAILLER DO ARQUIVO' ,5 ,22 ,0 ,0 ,'' ,'' ,'0' );
            insert into configuracoes.db_layoutlinha( db51_codigo ,db51_layouttxt ,db51_descr ,db51_tipolinha ,db51_tamlinha ,db51_linhasantes ,db51_linhasdepois ,db51_obs ,db51_separador ,db51_compacta ) values ( 1033 ,310 ,'HEADER DO ARQUIVO' ,1 ,130 ,0 ,0 ,'' ,'' ,'0' );
            insert into configuracoes.db_layoutlinha( db51_codigo ,db51_layouttxt ,db51_descr ,db51_tipolinha ,db51_tamlinha ,db51_linhasantes ,db51_linhasdepois ,db51_obs ,db51_separador ,db51_compacta ) values ( 1034 ,310 ,'REGISTRO' ,3 ,82 ,0 ,0 ,'' ,'' ,'0' );
            insert into configuracoes.db_layoutlinha( db51_codigo ,db51_layouttxt ,db51_descr ,db51_tipolinha ,db51_tamlinha ,db51_linhasantes ,db51_linhasdepois ,db51_obs ,db51_separador ,db51_compacta ) values ( 1035 ,310 ,'TRAILLER DO ARQUIVO' ,5 ,22 ,0 ,0 ,'' ,'' ,'0' );

            update configuracoes.db_layoutcampos set db52_layoutlinha = 1029 where db52_layoutlinha = 1000029;
            update configuracoes.db_layoutcampos set db52_layoutlinha = 1031 where db52_layoutlinha = 1000031;
            update configuracoes.db_layoutcampos set db52_layoutlinha = 1032 where db52_layoutlinha = 1000032;
            update configuracoes.db_layoutcampos set db52_layoutlinha = 1033 where db52_layoutlinha = 1000033;
            update configuracoes.db_layoutcampos set db52_layoutlinha = 1034 where db52_layoutlinha = 1000034;
            update configuracoes.db_layoutcampos set db52_layoutlinha = 1035 where db52_layoutlinha = 1000035;

            delete from configuracoes.db_layoutlinha where db51_codigo in (1000029, 1000030, 1000031, 1000032, 1000033, 1000034, 1000035);
SQL;

        DB::connection()->getPdo()->exec($sql);
    }
}
