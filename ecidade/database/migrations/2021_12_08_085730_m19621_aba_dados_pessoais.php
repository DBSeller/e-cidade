<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M19621AbaDadosPessoais extends Migration
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

    private function upDicionario() {
        $sql = <<<SQL
            insert into configuracoes.db_sysarquivo values (1010840, 'rhimigrante', 'Indentifica se o servidor é imigrante', 'rh252', '2021-12-07', 'Servidor é imigrante', 0, 'f', 'f', 'f', 'f' );
            insert into configuracoes.db_sysarqmod values (28,1010840);
            insert into configuracoes.db_syscampo values(1013509,'rh252_sequencial','int4','Código Sequencial','0', 'Código Sequencial',10,'f','f','f',1,'text','Código Sequencial');
            insert into configuracoes.db_syscampo values(1013510,'rh252_matricula','int4','Matrícula','0', 'Matrícula',10,'f','f','f',1,'text','Matrícula');
            insert into configuracoes.db_syscampo values(1013511,'rh252_residencia','int4','Tipo de Residência','0', 'Tipo de Residência',10,'f','f','f',1,'text','Tipo de Residência');
            insert into configuracoes.db_syscampo values(1013512,'rh252_condicao','int4','Tipo de Condição','0', 'Tipo de Condição',10,'f','f','f',1,'text','Tipo de Condição');
            insert into configuracoes.db_syscampo values(1013513,'rh252_instituicao','int4','Código da Instituição','0', 'Código da Instituição',10,'f','f','f',1,'text','Código da Instituição');
            delete from configuracoes.db_sysarqcamp where codarq = 1010840;
            insert into configuracoes.db_sysarqcamp values(1010840,1013509,1,1001022);
            insert into configuracoes.db_sysarqcamp values(1010840,1013510,2,0);
            insert into configuracoes.db_sysarqcamp values(1010840,1013511,3,0);
            insert into configuracoes.db_sysarqcamp values(1010840,1013512,4,0);
            insert into configuracoes.db_sysarqcamp values(1010840,1013513,5,0);
            delete from configuracoes.db_sysprikey where codarq = 1010840;
            insert into configuracoes.db_sysprikey (codarq,codcam,sequen,camiden) values(1010840,1013509,1,1013509);
            delete from configuracoes.db_sysforkey where codarq = 1010840 and referen = 0;
            insert into configuracoes.db_sysforkey values(1010840,1013510,1,1153,0);
            insert into configuracoes.db_sysindices values(1008699,'rh252_sequencial_seq',1010840,'1');
            insert into configuracoes.db_syscadind values(1008699,1013509,1);
            delete from configuracoes.db_sysindices where codind = 1008699;
            insert into configuracoes.db_syssequencia values(1001022, 'rhimigrante_rh252_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
            update configuracoes.db_sysarqcamp set codsequencia = 1001022 where codarq = 1010840 and codcam = 1013509;
            insert into configuracoes.db_sysindices values(1008700,'rh252_sequencial_in',1010840,'1');
            insert into configuracoes.db_syscadind values(1008700,1013509,1);

            -- Inclusão do campo pessaol.rhcontratoemergencial.rh164_assecuratoria
            insert into configuracoes.db_syscampo values(1013514,'rh164_assecuratoria','varchar(1)','Contém cláusula assecuratória do direito recíproco de rescisão antes da data de seu término.','N', 'Cláusula assecuratória',1,'f','t','f',0,'text','Cláusula assecuratória');
            delete from configuracoes.db_sysarqcamp where codarq = 3817;
            insert into configuracoes.db_sysarqcamp values(3817,21196,1,1000474);
            insert into configuracoes.db_sysarqcamp values(3817,21197,2,0);
            insert into configuracoes.db_sysarqcamp values(3817,21198,3,0);
            insert into configuracoes.db_sysarqcamp values(3817,21199,4,0);
            insert into configuracoes.db_sysarqcamp values(3817,21200,5,0);
            insert into configuracoes.db_sysarqcamp values(3817,1013514,6,0);
SQL;

        DB::connection()->getPdo()->exec($sql);
    }

    private function upEstrutura() {
        $sql = <<<SQL
            --DROP TABLE:
            DROP TABLE IF EXISTS pessoal.rhimigrante CASCADE;
            --Criando drop sequences
            DROP SEQUENCE IF EXISTS pessoal.rhimigrante_rh252_sequencial_seq;

            -- Criando  sequences
            CREATE SEQUENCE pessoal.rhimigrante_rh252_sequencial_seq
            INCREMENT 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1
            CACHE 1;

            -- TABELAS E ESTRUTURA
            -- Módulo: pessoal
            CREATE TABLE pessoal.rhimigrante(
            rh252_sequencial int4 NOT NULL default 0,
            rh252_matricula int4 NOT NULL default 0,
            rh252_residencia int4 NOT NULL default 0,
            rh252_condicao int4 NOT NULL default 0,
            rh252_instituicao int4 NOT NULL default 0,
            CONSTRAINT rhimigrante_sequ_pk PRIMARY KEY (rh252_sequencial));

            -- CHAVE ESTRANGEIRA
            ALTER TABLE pessoal.rhimigrante
            ADD CONSTRAINT rhimigrante_matricula_fk FOREIGN KEY (rh252_matricula)
            REFERENCES rhpessoal;

            -- INDICES
            CREATE UNIQUE INDEX rh252_sequencial_in ON pessoal.rhimigrante(rh252_sequencial);

            -- CRIA A COLUNA pessoal.rhcontratoemergencialrenovacao.rh164_assecuratoria
            ALTER TABLE pessoal.rhcontratoemergencialrenovacao ADD rh164_assecuratoria varchar(1) 
            NOT NULL DEFAULT 'N';
 
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
        $this->downDicionario();
        $this->downEstrutura();
    }

    private function downDicionario() {
        $sql = <<<SQL

            delete from configuracoes.db_syssequencia where codsequencia = 1001022;
            delete from configuracoes.db_syscadind where codind in (1008699, 1008700);
            delete from configuracoes.db_sysindices where codind in (1008699, 1008700);
            delete from configuracoes.db_sysforkey where codarq = 1010840;
            delete from configuracoes.db_sysprikey where codarq = 1010840;
            delete from configuracoes.db_sysarqcamp where codarq = 1010840;
            delete from configuracoes.db_syscampo where codcam in (1013509, 1013510, 1013511, 1013512, 1013513);
            delete from configuracoes.db_sysarqmod where codarq = 1010840;
            delete from configuracoes.db_sysarquivo where codarq = 1010840;

            -- Exclusão do campo pessoal.rhcontratoemergencial.rh164_assecuratoria
            delete from configuracoes.db_sysarqcamp where codcam = 1013514;
            delete from configuracoes.db_syscampo where codcam = 1013514;
SQL;

        DB::connection()->getPdo()->exec($sql);
    }

    private function downEstrutura() {
        $sql = <<<SQL
        DROP SEQUENCE IF EXISTS pessoal.rhimigrante_rh252_sequencial_seq;
        DROP TABLE pessoal.rhimigrante;

        -- EXLUI COLUNA pessoal.rhcontratoemergencialrenovacao.rh164_assecuratoria
        ALTER TABLE pessoal.rhcontratoemergencialrenovacao 
        DROP COLUMN IF EXISTS rh164_assecuratoria;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

}
