<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M21248CodigoAgrupamentoPagordem extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->upDicionario();
        $this->upEstrutura();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->downDicionario();
        $this->downEstrutura();
    }

    public function upEstrutura(){
        $sql = <<<SQL
            create table empenho.pagordemoutrosdados(
                e172_codigo int PRIMARY KEY,
                e172_pagordem int NOT NULL,
                e172_dados jsonb,
                CONSTRAINT fk_pagordem
                    FOREIGN KEY(e172_pagordem) REFERENCES empenho.pagordem(e50_codord)
            );
            create sequence empenho.pagordemoutrosdados_e172_codigo_seq START 1;
SQL;
        DB::connection()->getPdo()->exec($sql);

    }

    public function downEstrutura()
    {
        $sql = <<<SQL
            DROP SEQUENCE empenho.pagordemoutrosdados_e172_codigo_seq;
            DROP TABLE empenho.pagordemoutrosdados;

SQL;
        DB::connection()->getPdo()->exec($sql);

    }

    public function upDicionario()
    {
        $sql = <<<SQL
            insert into configuracoes.db_sysarquivo values (1010950, 'pagordemoutrosdados', 'Outros dados relacionados a ordem de pagamento', 'e172', '2022-07-04', 'Outros dados relacionados a pagordem', 0, 'f', 'f', 'f', 'f' );
            insert into configuracoes.db_sysarqmod values (38,1010950);
            insert into configuracoes.db_syscampo values(1014247,'e172_codigo','int8','codigo dos outros dados da ordem de pagamento','0', 'codigo dos outros dados da ord pagamento',8,'f','f','f',1,'text','codigo dos outros dados da ord pagamento');
            insert into configuracoes.db_syscampo values(1014248,'e172_pagordem','int8','código da ordem de pagamento','0', 'codigo da ordem de pagamento',8,'f','f','f',1,'text','codigo da ordem de pagamento');
            insert into configuracoes.db_syscampo values(1014249,'e172_dados','text','Outros dados da ordem de pagamento','', 'outros dados da ordem de pagamento',1,'t','f','f',0,'text','outros dados da ordem de pagamento');
            insert into configuracoes.db_sysprikey (codarq,codcam,sequen,camiden) values(1010950,1014247,1,1014247);
            insert into configuracoes.db_sysforkey values(1010950,1014248,1,808,0);
            insert into configuracoes.db_syssequencia values(1001075, 'pagordemoutrosdados_e172_codigo_seq', 1, 1, 9223372036854775807, 1, 1);
            update configuracoes.db_sysarqcamp set codsequencia = 1001075 where codarq = 1010950 and codcam = 1014247;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    public function downDicionario()
    {
        $sql = <<<SQL
            delete from configuracoes.db_syssequencia where codsequencia = 1001075;
            delete from configuracoes.db_sysforkey where codcam = 1014248;
            delete from configuracoes.db_sysprikey where codcam = 1014247;
            delete from configuracoes.db_syscampo where codcam = 1014249;
            delete from configuracoes.db_syscampo where codcam = 1014248;
            delete from configuracoes.db_syscampo where codcam = 1014247;
            delete from configuracoes.db_sysarqmod where codarq = 1010950;
            delete from configuracoes.db_sysarquivo where codarq = 1010950
SQL;
        DB::connection()->getPdo()->exec($sql);
    }
}
