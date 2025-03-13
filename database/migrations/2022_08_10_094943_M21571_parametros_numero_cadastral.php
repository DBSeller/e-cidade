<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M21571ParametrosNumeroCadastral extends Migration
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

    public function upDicionario()
    {
        DB::connection()->getPdo()->exec(<<<SQL
        
        insert into db_itensmenu( id_item ,descricao, help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) 
        values ( 228741 ,'Configuração Número Cadastral' ,'Configuração para gerar o número cadastral' ,'cad4_parametrosnumerocadastral001.php' ,'1' ,'1' ,'Configuração para gerar o número cadastral' ,'true' );
        insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 32 ,228741 ,559 ,578 );
        
        insert into db_sysarquivo values (1010982, 'parametrosnumerocadastral', 'Configuração para gerar numeração', 'j180', '2022-08-15', 'Parâmetros Número Cadastral', 1, 't', 't', 't', 't' );
        insert into db_sysarqmod values (2,1010982);
        insert into db_syscampo values(1014445,'j180_instit','int8','Instituição com o parâmetro','1', 'Instituição',10,'f','f','f',1,'text','Instituição');
        insert into db_syscampo values(1014446,'j180_separadormascara','varchar(1)','Separador que contida na máscara','', 'Separador da Máscara',1,'f','f','f',0,'text','Separador da Máscara');
        insert into db_syscampo values(1014447,'j180_configuracao','varchar(255)','Dados em JSON para configurar os campos que irão compor o número','', 'Configuração com os campos',255,'f','f','f',0,'text','Configuração com os campos');
        delete from db_sysarqcamp where codarq = 1010982;
        insert into db_sysarqcamp values(1010982,1014445,1,0);
        insert into db_sysarqcamp values(1010982,1014446,2,0);
        insert into db_sysarqcamp values(1010982,1014447,3,0);
        delete from db_sysprikey where codarq = 1010982;
        insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010982,1014445,1,1014445);
        delete from db_sysforkey where codarq = 1010982 and referen = 0;
        insert into db_sysforkey values(1010982,1014445,1,83,0);
SQL
);
    }
    
    public function upEstrutura()
    {
        DB::connection()->getPdo()->exec(<<<SQL
        
        create table cadastro.parametrosnumerocadastral (j180_instit integer,
                                                         j180_separadormascara varchar(1) default '', 
                                                         j180_configuracao json,
                                                         primary key(j180_instit),
                                                         foreign key(j180_instit) references db_config(codigo)
                                                        );
                                                        
        select configuracoes.fc_auditoria_cria_funcao('cadastro.parametrosnumerocadastral');
SQL
);
    }   

    public function downDicionario()
    {
        DB::connection()->getPdo()->exec(<<<SQL
        
        delete 
          from db_menu 
         where id_item_filho = 228741 
           AND modulo = 578;
        
        delete
          from db_itensmenu
         where id_item = 228741;
        
        delete 
          from db_sysarqcamp 
        where codarq = 1010982;

        delete 
          from db_sysprikey 
         where codarq = 1010982;

        delete 
          from db_sysforkey 
         where codarq = 1010982;
        
        delete 
          from db_sysarqmod
         where codarq = 1010982;

        delete 
          from db_sysarquivo 
         where codarq = 1010982;
 
        delete 
          from db_syscampo 
         where codcam = 1014445;

        delete 
          from db_syscampo 
         where codcam = 1014446;

        delete 
          from db_syscampo 
         where codcam = 1014447;

SQL
);
    }

    public function downEstrutura()
    {
        DB::connection()->getPdo()->exec(<<<SQL
        
        drop table cadastro.parametrosnumerocadastral;
SQL
);
    }

}
