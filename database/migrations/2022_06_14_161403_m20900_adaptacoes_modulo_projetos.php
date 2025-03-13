<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M20900AdaptacoesModuloProjetos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->insertTiposResponsavel();
        $this->atualizaEstruturaAlvara();
        $this->atualizaEstruturaHabite();
        $this->upDicionario();

        Schema::create('projetos.obrasoutrosprop', function(Blueprint $table) {
            $table->integer('ob32_codobra');
            $table->integer('ob32_numcgm');

            $table->foreign('ob32_codobra')
                ->references('ob01_codobra')
                ->on('projetos.obras');

            $table->foreign('ob32_numcgm')
                ->references('z01_numcgm')
                ->on('protocolo.cgm');
        });

        Schema::create('projetos.obrasrenovacaoalvara', function(Blueprint $table) {
            $table->integer('ob33_codobra');
            $table->date('ob33_dtrenovacao');
            $table->date('ob33_dtvalidade');

            $table->foreign('ob33_codobra')
                ->references('ob04_codobra')
                ->on('projetos.obrasalvara');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->downDicionario();
        $this->retornaEstruturaHabite(); 
        $this->retornaEstruturaAlvara();
        $this->deleteTiposResponsavel();

        Schema::drop('projetos.obrasoutrosprop');
        Schema::drop('projetos.obrasrenovacaoalvara');
    }

    private function upDicionario()
    {
        // obrasoutrosprop
        DB::statement("insert into db_sysarquivo values (1010944, 'obrasoutrosprop', 'Outros proprietários da obra', 'ob32', '2022-06-14', 'Outros proprietários da obra', 0, 'f', 't', 'f', 'f' );");
        DB::statement("insert into db_sysarqmod values (40,1010944);");        
        DB::statement("insert into db_syscampo values(1014212,'ob32_codobra','int4','Código da obra','0', 'Código da obra',10,'f','f','f',1,'text','Código da obra');");
        DB::statement("insert into db_syscampo values(1014213,'ob32_numcgm','int4','Numero de Identificação do Contribuinte ou Empresa no Cadastro geral do Município','0', 'Proprietário da obra',8,'f','f','f',1,'text','Numcgm');");

        
         // organiza campos obrasoutrosprop 
         $dados = [
            [1010944,1014212,1,0],
            [1010944,1014213,2,0],
        ];

        DB::table('configuracoes.db_sysarqcamp')->insert($this->dbSysArqCamp($dados));

        $dados = [
            [1010944,1014212,1,946,0],
            [1010944,1014213,1,42,0],
        ];
        DB::table('configuracoes.db_sysforkey')->insert($this->dbSysForKey($dados));

        // obrasrenovacaoalvara
        DB::statement("insert into db_sysarquivo values (1010996, 'obrasrenovacaoalvara', 'Data de renovação do alvará', 'ob33', '2022-11-08', 'Data de renovação do alvará', 0, 'f', 't', 'f', 'f' );");
        DB::statement("insert into db_sysarqmod values (40,1010996);");       
        DB::statement("insert into db_syscampo values(1014580 ,'ob33_codobra' ,'int4' ,'Código da obra' ,'0' ,'ob33_codobra' ,10 ,'false' ,'false' ,'false' ,1 ,'text' ,'ob33_codobra');");
        DB::statement("insert into db_syscampo values(1014581 ,'ob33_dtrenovacao' ,'date' ,'Data de renovação do alvará' ,'0' ,'Data de renovação do alvará' ,10 ,'false' ,'false' ,'false' ,1 ,'text' ,'Data de renovação do alvará');");
        DB::statement("insert into db_syscampo values(1014582 ,'ob33_dtvalidade' ,'date' ,'Data de validade do alvará' ,'0' ,'Data de validade do alvará' ,10 ,'false' ,'false' ,'false' ,1 ,'text' ,'Data de validade do alvará');");
        
         // organiza campos obrasrenovacaoalvara
         $dados = [
            [1010996,1014580,1,0],
            [1010996,1014581,2,0],
            [1010996,1014582,3,0],
        ];

        DB::table('configuracoes.db_sysarqcamp')->insert($this->dbSysArqCamp($dados));

        $dados = [
            [1010996,1014580,1,949,0],
        ];
        DB::table('configuracoes.db_sysforkey')->insert($this->dbSysForKey($dados));

        // obrasalvara
        DB::statement("insert into db_syscampo values(1014584 ,'ob04_idalvara' ,'int4' ,'Id do alvará utilizado para envio do xml do webservice sisobra' ,'0' ,'Id alvará' ,7 ,'t' ,'f' ,'f' ,1 ,'text' ,'Id alvará');");
        DB::statement("delete from db_sysindices where codind = 351;");
        DB::statement("insert into db_sysindices values(1008810,'id_alvara_ob04_idalvara',949,'1');");
        DB::statement("insert into db_syscadind values(1008810,1014584,1);");
        DB::statement("update db_sysarqcamp set codsequencia = 0 where codsequencia = 528;");
        DB::statement("delete from db_syssequencia where codsequencia = 528;");

        // organiza campos obrasalvara
        $dados = [
            [949,1014584,13,1001102],
        ];

        DB::table('configuracoes.db_sysarqcamp')->insert($this->dbSysArqCamp($dados));
        
        DB::statement("insert into db_syssequencia values(1001102, 'obrasalvara_ob04_idalvara_seq', 1, 1, 9999999, 3329, 1);");

    }

    private function downDicionario()
    {
        //obrasoutrosprop
        DB::table('db_sysforkey')->where('codarq', 1010944)->delete();
        DB::table('db_sysarqcamp')->whereIn('codcam', [1014213, 1014212])->delete();
        DB::table('db_syscampo')->whereIn('codcam', [1014213, 1014212])->delete();
        DB::table('db_sysarqmod')->where('codarq', 1010944)->delete();
        DB::table('db_acount')->where('codarq', 1010944)->delete();
        DB::table('db_sysarquivo')->where('codarq', 1010944)->delete();

        //obrasrenovacaoalvara
        DB::table('db_sysforkey')->where('codarq', 1010996)->delete();
        DB::table('db_sysarqcamp')->whereIn('codcam', [1014582, 1014581, 1014580])->delete();
        DB::table('db_syscampo')->whereIn('codcam', [1014582, 1014581, 1014580])->delete();
        DB::table('db_sysarqmod')->where('codarq', 1010996)->delete();
        DB::table('db_acount')->where('codarq', 1010996)->delete();
        DB::table('db_sysarquivo')->where('codarq', 1010996)->delete();

        //obrasalvara
        DB::table('db_sysarqcamp')->where('codcam', 1014584)->delete();
        DB::table('db_syscampo')->where('codcam', 1014584)->delete();
        DB::statement("update db_sysarqcamp set codsequencia = 528 where codarq = 949 and codcam = 5918;");
        DB::statement("insert into db_syssequencia values(528, 'obrasalvara_ob04_alvara_seq', 1, 1, 9223372036854775807, 1, 1);");
        DB::statement("insert into db_sysindices values(351,'indice_alvara_ob04_alvara',949,'1');");
        DB::table('db_syssequencia')->where('codsequencia', 1001102)->delete();
        DB::table('db_sysindices')->where('codind', 1008262)->delete();

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

    private function insertTiposResponsavel() 
    {
        DB::table('obrastiporesp')->insert(['ob02_cod' => 57, 'ob02_descr' => 'EMPRESA LÍDER DO CONSÓRCIO']);
        DB::table('obrastiporesp')->insert(['ob02_cod' => 58, 'ob02_descr' => 'CONSÓRCIO']);
    }

    private function deleteTiposResponsavel() 
    {
        DB::table('obrastiporesp')->where('ob02_cod', '=', 57)->delete();
        DB::table('obrastiporesp')->where('ob02_cod', '=', 58)->delete();
    }

    public function atualizaEstruturaAlvara()
    {
        DB::statement("ALTER TABLE projetos.obrasalvara ALTER COLUMN ob04_alvara TYPE int4 USING ob04_alvara::int4;");
        DB::statement("ALTER TABLE projetos.obrasalvara ADD ob04_idalvara int4 NULL;");
        DB::statement("ALTER TABLE projetos.obrashabite DROP CONSTRAINT IF EXISTS obrashabite_codalvara_fk;");
        DB::statement("DROP INDEX projetos.indice_alvara_ob04_alvara;");
        DB::statement("DROP SEQUENCE IF EXISTS obrasalvara_ob04_alvara_seq;");
        DB::statement("CREATE UNIQUE INDEX id_alvara_ob04_idalvara ON projetos.obrasalvara (ob04_idalvara);");
        DB::statement("CREATE SEQUENCE obrasalvara_ob04_idalvara_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9999999 START 1 CACHE 1;");
    }

    public function retornaEstruturaAlvara()
    {
        DB::statement("ALTER TABLE projetos.obrasalvara ALTER COLUMN ob04_alvara TYPE int8 USING ob04_alvara::int8;");
        DB::statement("ALTER TABLE projetos.obrasalvara DROP column ob04_idalvara;");
        DB::statement("DELETE FROM configuracoes.db_sysindices WHERE codind=1008810;");
        DB::statement("DELETE FROM configuracoes.db_syscadind WHERE codind=1008810 AND codcam=1014584 AND sequen=1;");
        DB::statement("CREATE UNIQUE INDEX indice_alvara_ob04_alvara ON projetos.obrasalvara (ob04_alvara);");
        DB::statement("DROP SEQUENCE IF EXISTS obrasalvara_ob04_idalvara_seq;");
        DB::statement("CREATE SEQUENCE obrasalvara_ob04_alvara_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1 CACHE 1;");

    }

    public function atualizaEstruturaHabite()
    {
        DB::statement("ALTER TABLE projetos.obrashabite ALTER COLUMN ob09_habite TYPE int4 USING ob09_habite::int4;");
    }

    public function retornaEstruturaHabite()
    {
        DB::statement("ALTER TABLE projetos.obrashabite ALTER COLUMN ob09_habite TYPE varchar(15) USING ob09_habite::varchar(15);");
    }
}
