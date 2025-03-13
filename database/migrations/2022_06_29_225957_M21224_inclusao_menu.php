<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M21224InclusaoMenu extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sql = <<<SQL
insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) 
                 values ( 228682 ,
                          'Cadastro de movimentações do servidor' ,
                          'Cadastro de movimentações do servidor' ,
                          'pes1_cadastroMovimentacaoServidor001.php' ,
                          '1' ,
                          '1' ,
                          'Cadastro de movimentações do servidor' ,'true' );
insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 4354 ,228682 ,12 ,952 );

insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) 
                 values ( 228708 ,
                          'Liberar acesso competência anterior para manutenção de servidores' ,
                          'Liberar acesso competência anterior para manutenção de servidores',
                          '' ,
                          '1' ,
                          '1',
                          'Liberar acesso competência anterior para manutenção de servidores.
Item de menu para controle de permissões, habilitando o botão para carregar os dados da movimentação do servidor da competência anterior.' ,
                          'true' );
insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 3516 ,228708 ,17 ,952 );

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
delete from db_menu where id_item_filho in(228682,228708);
delete from db_itensmenu where id_item in(228682,228708);
SQL;
        DB::connection()->getPdo()->exec($sql);
    }
}
