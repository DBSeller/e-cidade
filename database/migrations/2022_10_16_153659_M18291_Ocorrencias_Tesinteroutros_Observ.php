<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M18291OcorrenciasTesinteroutrosObserv extends Migration
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
                values(1013237,'j84_observacao','varchar(255)','Observação testadas internas outros',
                       ' ', 'Observação',255,'f','t','f',0,'text','Observação')
                ON CONFLICT ON CONSTRAINT db_syscampo_codc_pk DO NOTHING;
            
            insert into db_sysarqcamp (codarq, codcam, seqarq, codsequencia)
                values (1891, 1013237, 3, 0)
                ON CONFLICT ON CONSTRAINT db_sysarqcamp_codc_coda_seqa_pk DO NOTHING;

SQL
        );           
    }

    public function upEstrutura()
    {
        $anosRetroativos = $this->anosRetroativos();

        if (count($anosRetroativos) > 0) {
            foreach ($anosRetroativos as $anosRetroativo) {
                $sSchema = "cadastro_{$anosRetroativo->j153_anousu}";
                // insere a coluna observacao na tabela tesinteroutros em schemas anteriores
                if (!$this->verificaColuna($sSchema)) {
                    DB::statement("ALTER TABLE {$sSchema}.tesinteroutros ADD COLUMN j84_observacao varchar(255) NOT NULL DEFAULT '';");
                }
            }
        }

        $sSchema = "cadastro";
        // insere a coluna tesinteroutros na tabela tesinteroutros.
        if (!$this->verificaColuna($sSchema)) {
            DB::statement("ALTER TABLE tesinteroutros ADD COLUMN j84_observacao varchar(255) NOT NULL DEFAULT '';");
        }

    }

    public function verificaColuna($sSchema)
    {
        $colunaTabela = DB::select("SELECT table_name
                                    FROM information_schema.columns
                                    WHERE table_schema = '{$sSchema}'
                                      AND table_name = 'tesinteroutros'
                                      AND column_name = 'j84_observacao'");

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
            DELETE FROM db_sysarqcamp WHERE codarq = 1891 AND codcam = 1013237;
            DELETE FROM db_syscampo WHERE codcam = 1013237;
SQL
        );           
    }

    public function downEstrutura()
    {
        $anosRetroativos = $this->anosRetroativos();
        if (count($anosRetroativos) > 0) {
            foreach ($anosRetroativos as $anosRetroativo) {
                $sSchema = "cadastro_{$anosRetroativo->j153_anousu}.";
                // apaga a coluna observacao na tabela tesinteroutros em schemas anteriores
                DB::statement("ALTER TABLE {$sSchema}tesinteroutros DROP COLUMN IF EXISTS j84_observacao;");
            }
        }
        
        // apaga a coluna observacao na tabela testada
        $sSchema = "cadastro.";
        DB::statement("ALTER TABLE {$sSchema}tesinteroutros DROP COLUMN IF EXISTS j84_observacao;");
    }
}
