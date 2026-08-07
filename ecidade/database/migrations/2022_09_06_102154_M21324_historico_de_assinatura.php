<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;

class M21324HistoricoDeAssinatura extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->upDicionarioDeDados();
        $this->upTable();
        $this->upAuditoria();
    }

    public function upTable()
    {

        DB::connection()->getPdo()->exec(<<<SQL
            CREATE TABLE IF NOT EXISTS protocolo.processo_documento_assinatura (
                p122_sequencial serial PRIMARY KEY,
                p122_protprocessodocumento bigint,
                p122_usuario bigint,
                p122_documento_assinado_estorage bigint,
                p122_documento_origem_estorage bigint,
                p122_assinado_em timestamp,
                CONSTRAINT processo_documento_assinatura_protprocessodocumento_fk FOREIGN KEY(p122_protprocessodocumento)
                REFERENCES protocolo.protprocessodocumento(p01_sequencial),
                CONSTRAINT processo_documento_assinatura_db_usuarios_fk FOREIGN KEY(p122_usuario)
                REFERENCES configuracoes.db_usuarios(id_usuario)
            );
SQL
        );
    }

    public function upDicionarioDeDados()
    {
        DB::connection()->getPdo()->exec(<<<SQL
                insert into db_sysarquivo values (1010986, 'processo_documento_assinatura', 'Armazena histórico de assinaturas feitas por usuário e gerencia a alteração de documentos feitos pelo estorage', 'p122', '2022-09-06', 'Processo Documento Assinatura', 0, 'f', 'f', 'f', 'f' );
                insert into db_sysarqmod values (4,1010986);
                insert into db_syscampo values(1014477,'p122_protprocessodocumento','int8','Sequencial da tabela protprocessodocumento','0', 'p122_protprocessodocumento',10,'f','f','f',1,'text','p122_protprocessodocumento');
                insert into db_syscampo values(1014478,'p122_documento_origem_estorage','int8','Versão do documento antes da assinatura','0', 'p122_documento_origem_estorage',10,'f','f','f',1,'text','p122_documento_origem_estorage');
                insert into db_syscampo values(1014479,'p122_documento_assinado_estorage','int8','Versão do documento depois de ser assinado e armazenado no estorage','0', 'documento_assinado_estorage',10,'f','f','f',1,'text','documento_assinado_estorage');
                insert into db_syscampo values(1014480,'p122_usuario','int8','Sequencial referente a coluna id_usuario da tabela configuracoes.db_usuarios ','0', 'p122_usuario',10,'f','f','f',1,'text','p122_usuario');
                insert into db_syscampo values(1014481,'p122_assinado_em','date','Data e hora em qual foi assinado o documento','null', 'Assinado Em',10,'f','f','f',1,'text','Assinado Em');
                insert into db_syscampo values(1014482,'p122_sequencial','int8','Sequencial ','0', 'Sequencial',10,'f','f','t',1,'text','Sequencial');
                insert into db_sysarqcamp values(1010986,1014481,1,0);
                insert into db_sysarqcamp values(1010986,1014480,2,0);
                insert into db_sysarqcamp values(1010986,1014479,3,0);
                insert into db_sysarqcamp values(1010986,1014478,4,0);
                insert into db_sysarqcamp values(1010986,1014477,5,0);
                insert into db_sysarqcamp values(1010986,1014482,6,0);
SQL
        );
    }

    public function upAuditoria()
    {
        DB::statement("select configuracoes.fc_auditoria_cria_funcao('protocolo.processo_documento_assinatura');");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->downDicionarioDeDados();
        $this->downAuditoria();
        $this->downTable();
    }

    public function downDicionarioDeDados()
    {
        DB::connection()->getPdo()->exec(<<<SQL
        delete from db_sysarqcamp where codcam IN (1014477,1014478,1014479,1014480,1014481,1014482);
        delete from db_syscampo where codcam IN (1014477,1014478,1014479,1014480,1014481,1014482);
        delete from db_sysarqmod where codarq = 1010986;
        delete from db_sysarquivo where codarq = 1010986;
SQL
        );
    }

    public function downTable()
    {
        DB::connection()->getPdo()->exec(<<<SQL
        DROP  TABLE IF EXISTS protocolo.processo_documento_assinatura;
SQL
        );
    }

    public function downAuditoria()
    {
        DB::statement("select configuracoes.fc_auditoria_remove_funcao('protocolo.processo_documento_assinatura');");
    }
}
