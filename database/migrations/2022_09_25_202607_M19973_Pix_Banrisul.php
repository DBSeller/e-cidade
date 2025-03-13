<?php

use App\Domain\Tributario\Arrecadacao\Models\Arretipopix;
use App\Domain\Tributario\Arrecadacao\Models\Arretipopixasso;
use App\Domain\Tributario\Arrecadacao\Pix\Bancos\BancoDoBrasil;
use App\Domain\Tributario\Arrecadacao\Services\ArretipopixbancogeracaoService;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M19973PixBanrisul extends Migration
{
    public function up()
    {
        $this->upEstrutura();
        $this->upDicionario();
        $this->upTriggerDicionario();
        $this->upSetarBanco();
    }

    public function down()
    {
        $this->downEstrutura();
        $this->downDicionario();
    }

    private function upEstrutura()
    {
        Schema::create("caixa.arretipopixbancogeracao", function (Blueprint $table) {
            $table->bigIncrements("k213_sequencial");
            $table->boolean("k213_processando")->default(false);
            $table->integer("k213_quantidade_processados")->default(0);
            $table->integer("k213_ordem_processamento")->default(0);
            $table->integer("k213_arretipopixasso");
            $table->integer("k213_arretipopix");
            $table->timestamps();

            $table->foreign("k213_arretipopixasso")->references("sequencial")->on("caixa.arretipopixasso");
            $table->foreign("k213_arretipopix")->references("codtipopix")->on("caixa.arretipopix");
        });

        Schema::table("caixa.recibobarpix", function ($table) {
            $table->text("k00_codban")->default(BancoDoBrasil::BANK_CODE);
        });

        DB::statement("ALTER TABLE caixa.recibobarpix ALTER COLUMN k00_codban DROP DEFAULT");
    }

    private function downEstrutura()
    {
        Schema::drop("caixa.arretipopixbancogeracao");

        Schema::table("caixa.recibobarpix", function ($table) {
            $table->dropColumn("k00_codban");
        });
    }

    private function upDicionario()
    {
        DB::connection()->getPdo()->exec(<<<SQL
            insert into db_sysarquivo values (1010988, 'arretipopixbancogeracao', 'Tabela que salva a ordem de geração de bancos para geração do pix conforme configuração', 'k213', '2022-10-03', 'arretipopixbancogeracao', 0, 'f', 'f', 'f', 'f' );
            insert into db_sysarqmod values (54,1010988);

            insert into db_syscampo values(1014507,'k213_sequencial','int8','Sequencial da tabela arretipopixbancogeracao','0', 'Sequencial',11,'f','f','f',1,'text','Sequencial');
            insert into db_syscampo values(1014508,'k213_processando','text','Salva se o banco é o que está como principal no momento para geração do PIX','', 'Processando',11,'f','t','f',0,'text','Processando');
            insert into db_syscampo values(1014509,'k213_quantidade_processados','int8','Salva a quantidade de itens que foram processados desde que o banco se tornou o principal.','0', 'Quantidade de Itens Processados',11,'f','f','f',1,'text','Quantidade de Itens Processados');
            insert into db_syscampo values(1014510,'k213_ordem_processamento','int8','Ordem de processamento dos bancos','0', 'Ordem de Processamento',11,'f','f','f',1,'text','Ordem de Processamento');
            insert into db_syscampo values(1014511,'k213_arretipopixasso','int8','Sequencial da tabela arretipopixasso','0', 'Sequencial da tabela arretipopixasso',11,'f','f','f',1,'text','Sequencial da tabela arretipopixasso');
            insert into db_syscampo values(1014512,'k213_arretipopix','int8','Sequencial da tabela arretipopix','0', 'Sequencial da tabela arretipopix',11,'f','f','f',1,'text','Sequencial da tabela arretipopix');

            insert into db_syssequencia values(1001093, 'arretipopixbancogeracao_k213_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);

            insert into db_sysarqcamp values(1010988,1014507,1,1001093);
            insert into db_sysarqcamp values(1010988,1014508,2,0);
            insert into db_sysarqcamp values(1010988,1014509,3,0);
            insert into db_sysarqcamp values(1010988,1014510,4,0);
            insert into db_sysarqcamp values(1010988,1014511,5,0);
            insert into db_sysarqcamp values(1010988,1014512,6,0);

            insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010988,1014507,1,1014507);

            insert into db_syscampo values(1014513,'k00_codban','text','Código do banco','', 'Código do banco',11,'f','t','f',0,'text','Código do banco');
            insert into db_sysarqcamp values(1010917,1014513,12,0);
SQL
        );
    }

    private function downDicionario()
    {
        DB::connection()->getPdo()->exec(<<<SQL
            DELETE FROM db_sysprikey WHERE codarq IN (
                /* arretipopixbancogeracao */
                1010988
            );

            DELETE FROM db_sysarqcamp WHERE codarq IN (
                /* arretipopixbancogeracao */
                1010988
            );

            DELETE FROM db_sysarqcamp WHERE codcam IN (
                /* recibobarpix */
                1014513
            );

            DELETE FROM db_syssequencia WHERE codsequencia IN (
                /* arretipopixbancogeracao */
                1001093
            );

            DELETE FROM db_syscampo WHERE codcam IN (
                /* arretipopixbancogeracao */
                1014507,
                1014508,
                1014509,
                1014510,
                1014511,
                1014512,
                /* recibobarpix */
                1014513
            );

            DELETE FROM db_sysarqarq WHERE codarq IN (
                /* arretipopixbancogeracao */
                1010988
            );

            DELETE FROM db_sysarqmod WHERE codarq IN (
                /* arretipopixbancogeracao */
                1010988
            );

            DELETE FROM db_sysarquivo WHERE codarq IN (
                /* arretipopixbancogeracao */
                1010988
            );
SQL
        );
    }

    private function upTriggerDicionario()
    {
        DB::statement("SELECT configuracoes.fc_auditoria_cria_funcao('caixa.arretipopixbancogeracao');");
        DB::statement("SELECT configuracoes.fc_auditoria_remove_funcao('caixa.recibobarpix');");
        DB::statement("SELECT configuracoes.fc_auditoria_cria_funcao('caixa.recibobarpix');");
    }

    private function upSetarBanco()
    {
        $arretipopixbancogeracaoService = new ArretipopixbancogeracaoService();

        $arretipopixassoList = Arretipopixasso::all();

        $arretipopixList = [];

        foreach ($arretipopixassoList as $arretipopixasso) {
            $arretipopix = Arretipopix::query()->where("k00_tipo", $arretipopixasso->k00_tipo)->first();
            $arretipopixbancogeracaoService->save($arretipopix, $arretipopixasso);
            $arretipopixList[] = $arretipopix;
        }

        foreach (array_unique($arretipopixList) as $arretipopix) {
            $arretipopixbancogeracaoService->reorder($arretipopix);
            $arretipopixbancogeracaoService->chooseBankToGeneratePix($arretipopix, false, true);
        }
    }
}
