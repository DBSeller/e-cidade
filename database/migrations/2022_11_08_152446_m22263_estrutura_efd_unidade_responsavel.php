<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M22263EstruturaEfdUnidadeResponsavel extends Migration
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

    private function upEstrutura()
    {
        DB::connection()->getPdo()->exec(<<<SQL
            CREATE TABLE esocial.efdreinfunidaderesponsavel (
                efd08_sequencial serial NOT NULL,
                efd08_instit int4 NOT NULL,
                efd08_cgm int4 NOT NULL,
                CONSTRAINT efdreinfunidaderesponsavel_pk PRIMARY KEY (efd08_sequencial),
                CONSTRAINT efdreinfunidaderesponsavel_fk FOREIGN KEY (efd08_cgm) REFERENCES protocolo.cgm(z01_numcgm),
                CONSTRAINT efdreinfunidaderesponsavel_fk_1 FOREIGN KEY (efd08_instit) REFERENCES configuracoes.db_config(codigo)
            );
            SELECT configuracoes.fc_auditoria_cria_funcao('esocial.efdreinfunidaderesponsavel');
SQL
        );
    }

    private function downEstrutura()
    {
        DB::connection()->getPdo()->exec(<<<SQL
            DROP TABLE esocial.efdreinfunidaderesponsavel;
            SELECT configuracoes.fc_auditoria_remove_funcao('esocial.efdreinfunidaderesponsavel');
SQL
        );
    }

    private function upDicionario()
    {
        DB::connection()->getPdo()->exec(<<<SQL
            insert into db_sysarquivo values (1010995, 'efdreinfunidaderesponsavel', 'Usada para lista de contribuintes quando filtro de órgão unidade está ativo.', 'efd08', '2022-11-08', 'Unidade Responsável', 0, 'f', 'f', 'f', 'f' );
            insert into db_sysarqmod values (81,1010995);

            insert into db_syscampo values(1014577,'efd08_sequencial','int4','Sequencial unidade responsável','0', 'Sequencial unidade responsável',8,'f','f','f',1,'text','Sequencial unidade responsável');
            insert into db_syscampo values(1014578,'efd08_instit','int4','Instituição da Unidade Responsável','0', 'Instituição da Unidade Responsável',8,'f','f','f',1,'text','Instituição da Unidade Responsável');
            insert into db_syscampo values(1014579,'efd08_cgm','int4','CGM da Unidade Responsável','0', 'CGM da Unidade Responsável',8,'f','f','f',1,'text','CGM da Unidade Responsável');

            insert into db_sysarqcamp values(1010995,1014577,1,0);
            insert into db_sysarqcamp values(1010995,1014579,2,0);
            insert into db_sysarqcamp values(1010995,1014578,3,0);

            insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010995,1014577,1,1014577);
            insert into db_sysforkey values(1010995,1014579,1,42,0);
            insert into db_sysforkey values(1010995,1014578,1,83,0);
            insert into db_syssequencia values(1001097, 'efdreinfunidaderesponsavel_efd08_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
            update db_sysarqcamp set codsequencia = 1001097 where codarq = 1010995 and codcam = 1014577;
SQL
        );
    }

    private function downDicionario()
    {
        DB::connection()->getPdo()->exec(<<<SQL
            delete from db_syssequencia where codsequencia = 1001097;
            delete from db_sysprikey where codarq = 1010995;
            delete from db_sysforkey where codarq = 1010995;
            delete from db_sysarqcamp where codarq = 1010995;
            delete from db_syscampo where codcam between 1014577 and 1014579;
            delete from db_sysarqmod where codmod = 81 and codarq = 1010995;
            delete from db_sysarquivo where codarq = 1010995;
SQL
        );
    }
}
