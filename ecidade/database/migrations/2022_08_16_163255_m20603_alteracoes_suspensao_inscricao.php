<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;

class M20603AlteracoesSuspensaoInscricao extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->atualizaEstruturaIssbaseparalisacao();
        $this->upDicionario();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->downDicionario();
        $this->retornaEstruturaIssbaseparalisacao();
    }

    private function upDicionario()
    {
        //issbaseparalisacao
        DB::statement("insert into db_syscampo values(1014448,'q140_usuario','int4','Código do usuário','0', 'Usuário',10,'f','f','f',1,'text','Usuário');");
    
         //organiza campos issbaseparalisacao 
         $dados = [
            [3621,1014448,7,0],
        ];

        DB::table('configuracoes.db_sysarqcamp')->insert($this->dbSysArqCamp($dados));

        DB::statement("insert into db_sysindices values(1008805,'issbaseparalisacao_q140_usuario_in',3621,'0');");
        DB::statement("insert into db_syscadind values(1008805,1014448,1);");

        $dados = [
            [3621,1014448,1,109,0],
        ];
        DB::table('configuracoes.db_sysforkey')->insert($this->dbSysForKey($dados));
    }

    private function downDicionario()
    {
        //issbaseparalisacao
        DB::table('db_sysforkey')->where('codarq', 3621)->delete();
        DB::table('db_syscadind')->where('codind', 1008805)->delete();
        DB::table('db_sysindices')->where('codind', 1008805)->delete();
        DB::table('db_sysarqcamp')->where('codcam', 1014448)->delete();
        DB::table('db_syscampo')->where('codcam', 1014448)->delete();
    }

    private function dbSysArqCamp($linhas)
    {
        $dados = [];

        foreach ($linhas as $values) {
            $dados[] = [
                'codarq' => $values[0],
                'codcam' => $values[1],
                'seqarq' => $values[2],
                'codsequencia' => $values[3]
            ];
        }

        return $dados;
    }

    private function dbSysForKey($linhas)
    {
        $dados = [];

        foreach ($linhas as $values) {
            $dados[] = [
                'codarq' => $values[0],
                'codcam' => $values[1],
                'sequen' => $values[2],
                'referen' => $values[3],
                'tipoobjrel' => $values[4]
            ];
        }

        return $dados;
    }

    public function atualizaEstruturaIssbaseparalisacao()
    {
        DB::statement("ALTER TABLE issqn.issbaseparalisacao ADD q140_usuario int4 NOT NULL DEFAULT 0;");
        DB::statement("ALTER TABLE issqn.issbaseparalisacao ADD CONSTRAINT issbaseparalisacao_db_usuarios_fk FOREIGN KEY (q140_usuario) REFERENCES configuracoes.db_usuarios(id_usuario);");
        DB::statement("CREATE INDEX issbaseparalisacao_q140_usuario_in ON issqn.issbaseparalisacao (q140_usuario);");
    }

    public function retornaEstruturaIssbaseparalisacao()
    {
        DB::statement("ALTER TABLE issqn.issbaseparalisacao DROP COLUMN q140_usuario;");
    }
}
