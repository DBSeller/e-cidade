<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M22190AdicionaItemDeMenuManutencaoContaBancariaSagres extends Migration
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

    private function upDicionario()
    {
        $sql = <<<SQL
insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228776 ,'Manutenção de Contas Bancarias - Sagres' ,'Manutenção de Contas Bancarias - Sagres' ,'cai1_manutencaocontabancariasagres001.php' ,'1' ,'1' ,'Manutenção de conta bancarias sagres, afim de criar logica para geração do arquivo diário dos sagres, RelacionamentoCCorrenteFontePagadora, CadastroContaBancaria.' ,'false' );
insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 8138 ,228776 ,7 ,39 );

insert into db_syscampo values(1014514,'k13_outrosdados','text','outros dados saltes, jsonb','', 'outros dados saltes',1,'t','f','f',0,'text','outros dados saltes');
insert into db_sysarqcamp values(212,1014514,10,0);
SQL;

        DB::connection()->getPdo()->exec($sql);
    }

    private function downDicionario()
    {
        $sql = <<<SQL
delete from db_menu where id_item_filho = 228776 AND modulo = 39;
delete from db_itensmenu where id_item = 228776;

delete from db_sysarqcamp where codcam = 1014514;
delete from db_syscampo where codcam = 1014514;

SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function upEstrutura()
    {
        $sql = <<<SQL
alter table caixa.saltes add column k13_outrosdados jsonb default null;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function downEstrutura()
    {
        $sql = <<<SQL
alter table caixa.saltes drop column k13_outrosdados;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }
}
