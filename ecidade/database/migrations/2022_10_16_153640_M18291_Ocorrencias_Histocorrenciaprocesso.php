<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M18291OcorrenciasHistocorrenciaprocesso extends Migration
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
        $this->upTriggersAuditoriaCadastro();
    }

    public function upDicionario()
    {
        DB::connection()->getPdo()->exec(<<<SQL

            INSERT INTO db_sysarquivo VALUES (1010596, 'histocorrenciaprocesso', 'Guarda os processos vinculados a ocorrencia',
                                              'ar201', '2022-10-16', 'histocorrenciaprocesso', 0, 'f', 'f', 'f', 'f' )
                ON CONFLICT ON CONSTRAINT db_sysarquivo_coda_pk DO NOTHING;

            INSERT INTO db_sysarqmod VALUES (2,1010596)
                ON CONFLICT ON CONSTRAINT db_sysarqmod_codm_coda_pk DO NOTHING;

            INSERT INTO db_syscampo VALUES (1011652,'ar201_sequencial','int4','Sequencial do Processo','0',
                                            'Sequencial do numero de processo da ocorrencia',10,'f','f','f',1,'text','Sequencial do Processo')
                ON CONFLICT ON CONSTRAINT db_syscampo_codc_pk DO NOTHING;

            INSERT INTO db_syscampo VALUES (1011653,'ar201_processo','text','Numero de processo','',
                                            'Numero de processo da ocorrencia do historico',1,'f','f','f',0,'text','Numero de processo')
                ON CONFLICT ON CONSTRAINT db_syscampo_codc_pk DO NOTHING;

            INSERT INTO db_syscampo VALUES (1011654,'ar201_histocorrencia','int8','Numero do histórico de ocorrência','0',
                                            'Numero do historico de ocorrencia',10,'f','f','f',1,'text','Numero do historico de ocorrencia')
                ON CONFLICT ON CONSTRAINT db_syscampo_codc_pk DO NOTHING;

            INSERT INTO db_sysarqcamp VALUES (1010596,1011652,1,0)
                ON CONFLICT ON CONSTRAINT db_sysarqcamp_codc_coda_seqa_pk DO NOTHING;

            INSERT INTO db_sysarqcamp VALUES (1010596,1011654,2,0)
                ON CONFLICT ON CONSTRAINT db_sysarqcamp_codc_coda_seqa_pk DO NOTHING;

            INSERT INTO db_sysarqcamp VALUES (1010596,1011653,3,0)
                ON CONFLICT ON CONSTRAINT db_sysarqcamp_codc_coda_seqa_pk DO NOTHING;

            INSERT INTO db_sysprikey (codarq,codcam,sequen,camiden) VALUES(1010596,1011652,1,1011652)
                ON CONFLICT ON CONSTRAINT db_sysprikey_codc_coda_sequ_pk DO NOTHING;

            INSERT INTO db_sysforkey VALUES (1010596,1011654,1,2651,0)
                ON CONFLICT ON CONSTRAINT db_sysforkey_coda_codc_sequ_refe_pk DO NOTHING;

            INSERT INTO db_syssequencia VALUES (1000951, 'histocorrenciaprocesso_ar201_sequencial_seq', 1, 1, 9223372036854775807, 1, 1)
                ON CONFLICT ON CONSTRAINT db_syssequencia_cods_pk DO NOTHING;

            UPDATE db_sysarqcamp SET codsequencia = 1000951 WHERE codarq = 1010596 AND codcam = 1011652 AND codsequencia <> 1000951;
SQL
        );           
    }

    public function upEstrutura()
    {
        $anosRetroativos = $this->anosRetroativos();

        if (count($anosRetroativos) > 0) {
            foreach ($anosRetroativos as $anosRetroativo) {
                $sSchema = "cadastro_{$anosRetroativo->j153_anousu}";
                // cria a tabela histocorrenciaprocesso em schemas anteriores
                if (!$this->verificaTabela($sSchema)) {
                    DB::connection()->getPdo()->exec(<<<SQL

                        CREATE TABLE IF NOT EXISTS {$sSchema}.histocorrenciaprocesso (
                            ar201_sequencial serial NOT NULL,
                            ar201_processo text NOT NULL,
                            ar201_histocorrencia integer NOT NULL,
                            CONSTRAINT histocorrenciaprocesso_pk PRIMARY KEY (ar201_sequencial),
                            CONSTRAINT histocorrenciaprocesso_histocorrencia_fk FOREIGN KEY (ar201_histocorrencia)
                                REFERENCES histocorrencia(ar23_sequencial)
                        );

                        CREATE UNIQUE INDEX IF NOT EXISTS histocorrenciaprocesso_histocorrencia_in
                            ON {$sSchema}.histocorrenciaprocesso (ar201_histocorrencia);
SQL
                    );
                }
            }
        }

        $sSchema = "cadastro";
        // insere a coluna tesinteroutros na tabela tesinteroutros.
        if (!$this->verificaTabela($sSchema)) {
            DB::connection()->getPdo()->exec(<<<SQL

                CREATE TABLE IF NOT EXISTS cadastro.histocorrenciaprocesso (
                    ar201_sequencial serial NOT NULL,
                    ar201_processo text NOT NULL,
                    ar201_histocorrencia integer NOT NULL,
                    CONSTRAINT histocorrenciaprocesso_pk PRIMARY KEY (ar201_sequencial),
                    CONSTRAINT histocorrenciaprocesso_histocorrencia_fk FOREIGN KEY (ar201_histocorrencia)
                        REFERENCES histocorrencia(ar23_sequencial)
                );

                CREATE UNIQUE INDEX IF NOT EXISTS histocorrenciaprocesso_histocorrencia_in
                    ON cadastro.histocorrenciaprocesso (ar201_histocorrencia);
SQL
            );
        }
    }

    public function upTriggersAuditoriaCadastro()
    {
        DB::connection()->getPdo()->exec(<<<SQL

            SELECT configuracoes.fc_auditoria_cria_funcao('cadastro.histocorrenciaprocesso');
SQL
        );
    }

    public function verificaTabela($sSchema)
    {
        $aTabela = DB::select("SELECT table_name
                                    FROM information_schema.tables
                                    WHERE table_schema = '{$sSchema}'
                                      AND table_name = 'histocorrenciaprocesso'");

        if (count($aTabela) > 0) {
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
        $this->downTriggersAuditoriaCadastro();
        $this->downEstrutura();
    }

    public function downDicionario()
    {
        DB::connection()->getPdo()->exec(<<<SQL

            DELETE FROM db_syssequencia
                WHERE codsequencia = 1000951 AND nomesequencia = 'histocorrenciaprocesso_ar201_sequencial_seq';

            DELETE FROM db_sysforKey WHERE codarq IN (1010596);

            DELETE FROM db_sysprikey where codarq = 1010596 and codcam = 1011652 and sequen = 1 and camiden = 1011652;

            DELETE FROM db_sysarqcamp WHERE codcam IN (1011652,1011653,1011654);

            DELETE FROM db_syscampo WHERE codcam IN (1011652,1011653,1011654);

            DELETE FROM db_acount WHERE codarq IN (1010596);

            DELETE FROM db_sysarqmod WHERE codarq IN (1010596);

            DELETE FROM db_sysarquivo WHERE codarq IN (1010596);
SQL
        );           
    }

    public function downEstrutura()
    {
        $anosRetroativos = $this->anosRetroativos();
        if (count($anosRetroativos) > 0) {
            foreach ($anosRetroativos as $anosRetroativo) {
                $sSchema = "cadastro_{$anosRetroativo->j153_anousu}";
                // apaga a tabela histocorrenciaprocesso em schemas anteriores
                DB::statement("DROP TABLE IF EXISTS {$sSchema}.histocorrenciaprocesso;");
            }
        }
        
        // apaga a tabela histocorrenciaprocesso
        DB::statement("DROP TABLE IF EXISTS cadastro.histocorrenciaprocesso;");
    }

    public function downTriggersAuditoriaCadastro()
    {
        DB::connection()->getPdo()->exec(<<<SQL

            SELECT configuracoes.fc_auditoria_remove_funcao('cadastro.histocorrenciaprocesso');
SQL
        );
    }
}
