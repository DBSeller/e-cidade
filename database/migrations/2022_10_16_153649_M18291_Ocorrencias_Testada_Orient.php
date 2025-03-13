<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M18291OcorrenciasTestadaOrient extends Migration
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

    public function upDicionario()
    {
        DB::connection()->getPdo()->exec(<<<SQL

            insert into db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo,
                                     tamanho, nulo, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel)
                values(1011928,'j36_orientacao','int4','Orientação direcional','0', 'Orientação',
                       4,'f','f','f',1,'text','Orientação')
                ON CONFLICT ON CONSTRAINT db_syscampo_codc_pk DO NOTHING;
            
            insert into db_sysarqcamp (codarq, codcam, seqarq, codsequencia)
                values (24, 1011928, 6, 0)
                ON CONFLICT ON CONSTRAINT db_sysarqcamp_codc_coda_seqa_pk DO NOTHING;

            INSERT INTO db_sysforkey values(24,1011928,1,1712,0)
                ON CONFLICT ON CONSTRAINT db_sysforkey_coda_codc_sequ_refe_pk DO NOTHING;

SQL
        );           
    }

    public function upEstrutura()
    {
        $anosRetroativos = $this->anosRetroativos();

        if (count($anosRetroativos) > 0) {
            foreach ($anosRetroativos as $anosRetroativo) {
                $sSchema = "cadastro_{$anosRetroativo->j153_anousu}";
                // insere a coluna orientacao na tabela testada em schemas anteriores
                if (!$this->verificaColuna($sSchema)) {
                    DB::statement("ALTER TABLE {$sSchema}.testada ADD COLUMN j36_orientacao INTEGER NOT NULL DEFAULT 0;");
                    DB::statement("ALTER TABLE {$sSchema}.testada ADD CONSTRAINT testada_orientacao_fk FOREIGN KEY (j36_orientacao) REFERENCES {$sSchema}.orientacao;");
                }
            }
        }

        $sSchema = "cadastro";
        // insere a coluna orientacao na tabela testada.
        if (!$this->verificaColuna($sSchema)) {
            DB::statement("ALTER TABLE testada ADD COLUMN j36_orientacao INTEGER NOT NULL DEFAULT 0;");
            DB::statement("ALTER TABLE {$sSchema}.testada ADD CONSTRAINT testada_orientacao_fk FOREIGN KEY (j36_orientacao) REFERENCES {$sSchema}.orientacao;");
        }
    }

    public function verificaColuna($sSchema)
    {
        $colunaTabela = DB::select("SELECT table_name
                                    FROM information_schema.columns
                                    WHERE table_schema = '{$sSchema}'
                                      AND table_name = 'testada'
                                      AND column_name = 'j36_orientacao'");

        if (count($colunaTabela) > 0) {
            return true;
        }
        return false;
    }

    public function anosRetroativos()
    {
        $tableCalculos = DB::select("SELECT table_name
                             FROM information_schema.tables
                             WHERE table_schema = 'cadastro'
                               AND table_name = 'calculoretroativoiptuschema'");

        $anos = [];
        if (count($tableCalculos) > 0) {
            $anos = DB::select("SELECT j153_anousu
                                FROM calculoretroativoiptuschema
                                ORDER BY 1 DESC");
        }
        return $anos;
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

    public function downDicionario()
    {
        DB::connection()->getPdo()->exec(<<<SQL
            DELETE FROM db_sysforkey where codarq = 24 and codcam = 1011928;
            DELETE FROM db_sysarqcamp WHERE codarq = 24 AND codcam = 1011928;
            DELETE FROM db_syscampo WHERE codcam = 1011928;
SQL
        );           
    }

    public function downEstrutura()
    {
        $anosRetroativos = $this->anosRetroativos();
        if (count($anosRetroativos) > 0) {
            foreach ($anosRetroativos as $anosRetroativo) {
                $sSchema = "cadastro_{$anosRetroativo->j153_anousu}.";
                // apaga a coluna orientacao na tabela testada em schemas anteriores
                DB::statement("ALTER TABLE {$sSchema}testada DROP CONSTRAINT testada_orientacao_fk;");
                DB::statement("ALTER TABLE {$sSchema}testada DROP COLUMN IF EXISTS j36_orientacao;");
            }
        }
        
        // apaga a coluna orientacao na tabela testada
        $sSchema = "cadastro.";
        DB::statement("ALTER TABLE {$sSchema}testada DROP CONSTRAINT testada_orientacao_fk;");
        DB::statement("ALTER TABLE {$sSchema}testada DROP COLUMN IF EXISTS j36_orientacao;");
    }
}
