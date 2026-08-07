<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M22862TableArquivosimplesimportacaoretorno extends Migration
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

    public function upDicionario() {

        DB::connection()->getPdo()->exec(<<<SQL
        insert into db_sysarquivo values (1011010, 'arquivosimplesimportacaoretorno', 'Armazenamento do arquivo processado.', 'q182', '2022-12-26', 'arquivosimplesimportacaoretorno', 0, 'f', 'f', 'f', 'f' );
        insert into db_sysarqmod values (3,1011010);
        insert into db_syscampo values(1014662,'q182_sequencial','int8','Identificador da tabela','0', 'Sequencial',10,'f','f','f',1,'text','Sequencial');
        insert into db_syscampo values(1014663,'q182_id_usuario','int4','Identificação do usuário que processou o arquivo','0', 'Usuário',10,'f','f','f',1,'text','Usuário');
        insert into db_syscampo values(1014664,'q182_nomearquivo','varchar(50)','Nome do arquivo processado','', 'Nome Arquivo',50,'f','f','f',0,'text','Nome Arquivo');
        insert into db_syscampo values(1014665,'q182_id_storage','int8','Identificador do arquivo no sistema e-storage','0', 'id arquivo e-storage',10,'f','f','f',1,'text','id arquivo e-storage');
        insert into db_syscampo values(1014666,'q182_arquivosimplesimportacao','int4','Chave da arquivo simples importação','0', 'Arquivo simples importação',10,'f','f','f',1,'text','Arquivo simples importação');
        insert into db_sysarqcamp values(1011010,1014662,1,1001106);
        insert into db_sysarqcamp values(1011010,1014663,2,0);
        insert into db_sysarqcamp values(1011010,1014664,3,0);
        insert into db_sysarqcamp values(1011010,1014665,4,0);
        insert into db_sysarqcamp values(1011010,1014666,5,0);
        insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1011010,1014662,1,1014662);
        insert into db_sysforkey values(1011010,1014663,1,109,0);
        insert into db_sysforkey values(1011010,1014666,1,3653,0);
        insert into db_sysindices values(1008826,'arquivosimplesimportacaoretorno_id_usuario_in',1011010,'0');
        insert into db_syscadind values(1008826,1014663,1);
        insert into db_syssequencia values(1001106, 'arquivosimplesimportacaoretorno_q182_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
        insert into db_sysindices values(1008827,'arquivosimplesimportacaoretorno_arquivosimplesimportacao_in',1011010,'0');
        insert into db_syscadind values(1008827,1014666,1);
SQL
        );
        
    }

    public function upEstrutura() {
        DB::connection()->getPdo()->exec(<<<SQL

          CREATE SEQUENCE arquivosimplesimportacaoretorno_q182_sequencial_seq
          INCREMENT 1
          MINVALUE 1
          MAXVALUE 9223372036854775807
          START 1
          CACHE 1;

          CREATE TABLE issqn.arquivosimplesimportacaoretorno(
            q182_sequencial		int8 NOT NULL,
            q182_id_usuario		int4 NOT NULL,
            q182_nomearquivo    varchar(50) NOT NULL,
            q182_id_storage		int8 NOT NULL,
            q182_arquivosimplesimportacao int4 NOT NULL,
            CONSTRAINT arquivosimplesimportacaoretorno_sequ_pk PRIMARY KEY (q182_sequencial),
            CONSTRAINT arquivosimplesimportacaoretorno_usuario_fk FOREIGN KEY (q182_id_usuario)
            REFERENCES configuracoes.db_usuarios,
            CONSTRAINT arquivosimplesimportacaoretorno_arquivosimplesimportacao_fk FOREIGN KEY (q182_arquivosimplesimportacao)
            REFERENCES issqn.arquivosimplesimportacao
          );

          CREATE INDEX arquivosimplesimportacaoretorno_id_usuario_in 
              ON issqn.arquivosimplesimportacaoretorno(q182_id_usuario);

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
       $this->downDicionario();
       $this->downEstrutura();
    }

    public function downDicionario() {

       DB::connection()->getPdo()->exec(<<<SQL

        delete 
          from db_syscadind 
         where codind in (1008826, 1008827);

        delete 
          from db_sysindices 
         where codind in (1008826, 1008827);
         
         delete 
           from db_syssequencia 
          where codsequencia = 1001106;  
         
         delete 
           from db_sysprikey 
          where codarq = 1011010;

         delete 
           from db_sysforkey 
          where codarq = 1011010 
            and referen = 109;
         
         delete 
           from db_sysforkey 
          where codarq = 1011010 
            and referen = 3653;

         delete 
           from db_sysarqcamp 
          where codarq = 1011010;

         delete 
           from db_sysarqmod
          where codarq = 1011010;

         delete 
           from db_syscampo
          where codcam in (1014662, 1014663, 1014664, 1014665, 1014666);

         delete 
           from db_sysarquivo  
          where codarq = 1011010;
        
SQL
       );  
    }

    public function downEstrutura() {
       DB::connection()->getPdo()->exec(<<<SQL
       
       DROP TABLE IF EXISTS arquivosimplesimportacaoretorno;
       DROP SEQUENCE IF EXISTS arquivosimplesimportacaoretorno_q182_sequencial_seq; 
SQL
       );
    }
}
