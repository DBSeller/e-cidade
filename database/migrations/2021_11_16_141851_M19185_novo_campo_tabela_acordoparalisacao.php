<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M19185NovoCampoTabelaAcordoparalisacao extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::connection()->getPdo()->exec(<<<SQL
insert into db_syscampo 
values(1013475,'ac47_acordoevento','int4','Evento','NULL', 'Evento',10,'t','f','f',1,'text','Evento');

insert into db_sysarqcamp 
values (3692,1013475,5,0);

insert into db_sysforkey values(3692,1013475,1,3927,0);





SQL
        );

        DB::connection()->getPdo()->exec(<<<SQL
ALTER TABLE acordos.acordoparalisacao ADD ac47_acordoevento int default NULL;
ALTER TABLE acordos.acordoparalisacao ADD FOREIGN KEY (ac47_acordoevento) REFERENCES acordos.acordoevento (ac55_sequencial);
SQL
        );

        DB::connection()->getPdo()->exec(<<<SQL
update db_itensmenu set id_item = 9920 , descricao = 'Paralisação' , help = 'Paralisação de um contrato' , itemativo = '1' , manutencao = '1' , desctec = 'Paralisação de um contrato' , libcliente = 'true' where id_item = 9920;
update db_itensmenu set id_item = 9921 , descricao = 'Inclusão' , help = 'Incluir uma paralisação' , funcao = 'aco4_acordoparalisacao.php?dbopcao=1' , itemativo = '1' , manutencao = '1' , desctec = 'Incluir uma paralisação' , libcliente = 'true' where id_item = 9921;
update db_itensmenu set id_item = 9922 , descricao = 'Alteração' , help = 'Alterar paralisação' , funcao = 'aco4_acordoparalisacao.php?dbopcao=2' , itemativo = '1' , manutencao = '1' , desctec = 'Alterar paralisação' , libcliente = 'true' where id_item = 9922;
update db_itensmenu set id_item = 9923 , descricao = 'Exclusão' , help = 'Excluir paralisação' , funcao = 'aco4_acordoparalisacao.php?dbopcao=3' , itemativo = '1' , manutencao = '1' , desctec = 'Excluir paralisação' , libcliente = 'true' where id_item = 9923;
update db_itensmenu set id_item = 9924 , descricao = 'Reativação' , help = 'Reativar acordo' , itemativo = '1' , manutencao = '1' , desctec = 'Reativar acordo' , libcliente = 'true' where id_item = 9924;
update db_itensmenu set id_item = 9926 , descricao = 'Inclusão' , help = 'Incluir uma reativação' , funcao = 'aco4_acordoreativacao.php?dbopcao=1' , itemativo = '1' , manutencao = '1' , desctec = 'Incluir uma reativação' , libcliente = 'true' where id_item = 9926;
update db_itensmenu set id_item = 9927 , descricao = 'Cancelamento' , help = 'Cancelar uma reativação' , funcao = 'aco4_acordoreativacao.php?dbopcao=2' , itemativo = '1' , manutencao = '1' , desctec = 'Cancelar uma reativação' , libcliente = 'true' where id_item = 9927;
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
delete from db_sysarqcamp where codcam = 1013475;
delete from db_sysforkey where codcam = 1013475;
delete from db_syscampo where codcam = 1013475;
SQL
        );

        DB::connection()->getPdo()->exec(<<<SQL
ALTER TABLE acordos.acordoparalisacao DROP COLUMN ac47_acordoevento;
SQL
         );


         DB::connection()->getPdo()->exec(<<<SQL
update db_itensmenu set libcliente = 'false' where id_item in(9920, 9921, 9922, 9923, 9924, 9926);
SQL
        );
    }
}
