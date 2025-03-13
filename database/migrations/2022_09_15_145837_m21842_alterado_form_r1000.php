<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M21842AlteradoFormR1000 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::connection()->getPdo()->exec(<<<SQL
            insert into avaliacaogrupopergunta( db102_sequencial ,db102_avaliacao ,db102_descricao ,db102_identificador ,db102_identificadorcampo ,db102_ordem ) values ( 4000246 ,3000034 ,'Novo período de validade das informações que estão sendo alteradas' ,'novo-periodo-de-validade-das-informacoes-que-estao' ,'grupo-novaValidade' ,4 );
            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ,db103_somenteleitura ) values ( 4000366 ,2 ,4000246 ,'Preencher com o mês e ano de início da validade das informações prestadas no evento, no formato AAAA-MM' ,'preencher-com-o-mes-e-ano-de-inicio-d63237bdf479ed' ,'false' ,'true' ,1 ,1 ,'' ,0 ,'false' ,'' ,'novainiValid' ,'false' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001491 ,4000366 ,'Nova validade' ,'nova-validade' ,'false' ,0 ,'' ,'novainiValid' );
            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ,db103_somenteleitura ) values ( 4000367 ,2 ,4000246 ,'Preencher com o mês e ano de término da validade das informações, se houver.' ,'preencher-com-o-mes-e-ano-de-termino-63237be293321' ,'false' ,'true' ,2 ,1 ,'' ,0 ,'false' ,'' ,'novafimValid' ,'false' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001492 ,4000367 ,'Fim nova validade' ,'fim-nova-validade' ,'false' ,0 ,'' ,'novafimValid' );
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
            delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 4000367;
            delete from avaliacaogrupoperguntaresposta where db108_avaliacaoresposta in (select db106_sequencial from avaliacaoresposta where db106_avaliacaoperguntaopcao = 4001492);
            delete from avaliacaoresposta where db106_avaliacaoperguntaopcao = 4001492;
            delete from avaliacaoperguntaopcao where db104_avaliacaopergunta = 4000367;
            delete from avaliacaopergunta where db103_sequencial = 4000367;

            delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 4000366;
            delete from avaliacaogrupoperguntaresposta where db108_avaliacaoresposta in (select db106_sequencial from avaliacaoresposta where db106_avaliacaoperguntaopcao = 4001491);
            delete from avaliacaoresposta where db106_avaliacaoperguntaopcao = 4001491;
            delete from avaliacaoperguntaopcao where db104_avaliacaopergunta = 4000366;
            delete from avaliacaopergunta where db103_sequencial = 4000366;

            delete from avaliacaogrupopergunta where db102_sequencial = 4000246;
SQL
        );
    }
}
