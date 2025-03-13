<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M22263EstruturaEfdreinfConfiuracao extends Migration
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
            CREATE TABLE esocial.efdreinfconfiguracao (
                efd07_sequencial serial NOT NULL,
                efd07_instit int4 NOT NULL,
                efd07_filtraorgaounidade bool NULL DEFAULT false,
                CONSTRAINT efdreinfconfiguracao_pk PRIMARY KEY (efd07_sequencial),
                CONSTRAINT efdreinfconfiguracao_fk FOREIGN KEY (efd07_instit) REFERENCES configuracoes.db_config(codigo)
            );
            SELECT configuracoes.fc_auditoria_cria_funcao('esocial.efdreinfconfiguracao');
SQL
        );
    }

    private function downEstrutura()
    {
        DB::connection()->getPdo()->exec(<<<SQL
            DROP TABLE esocial.efdreinfconfiguracao;
            SELECT configuracoes.fc_auditoria_remove_funcao('esocial.efdreinfconfiguracao');
SQL
        );
    }

    private function upDicionario()
    {
        DB::connection()->getPdo()->exec(<<<SQL
            insert into db_sysarquivo values (1010990, 'efdreinfconfiguracao', 'Configurações Gerais EFD-REINF', 'efd07', '2022-10-13', 'Configurações Gerais EFD-REINF', 0, 'f', 'f', 'f', 'f' );
            insert into db_sysarqmod values (81,1010990);
            insert into db_syscampo values(1014544,'efd07_sequencial','int4','Sequencial de configuração do EFD-REINF','0', 'Sequencial de configuração do EFD-REINF',8,'f','f','f',1,'text','Sequencial de configuração do EFD-REINF');
            insert into db_syscampo values(1014545,'efd07_instit','int4','Instituição da configuração do EFD-REINF','0', 'Instituição da configuração do EFD-REINF',8,'f','f','f',1,'text','Instituição da configuração do EFD-REINF');
            insert into db_syscampo values(1014546,'efd07_filtraorgaounidade','bool','Se o sistema deve filtrar as rotinas do EFD-REINF por Órgão Unidade','f', 'Filtra por Órgão Unidade',1,'f','f','f',5,'text','Filtra por Órgão Unidade');
            insert into db_sysarqcamp values(1010990,1014544,1,0);
            insert into db_sysarqcamp values(1010990,1014545,2,0);
            insert into db_sysarqcamp values(1010990,1014546,3,0);
            insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010990,1014544,1,1014544);
            insert into db_sysforkey values(1010990,1014545,1,83,0);
            insert into db_syssequencia values(1001096, 'efdreinfconfiguracao_efd07_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
            update db_sysarqcamp set codsequencia = 1001096 where codarq = 1010990 and codcam = 1014544;
SQL
        );
    }

    private function downDicionario()
    {
        DB::connection()->getPdo()->exec(<<<SQL
        delete from db_syssequencia where codsequencia = 1001096;
        delete from db_sysprikey where codarq = 1010990;
        delete from db_sysforkey where codarq = 1010990;
        delete from db_sysarqcamp where codarq = 1010990;
        delete from db_syscampo where codcam between 1014544 and 1014546;
        delete from db_sysarqmod where codmod = 81 and codarq = 1010990;
        delete from db_sysarquivo where codarq = 1010990;
SQL
        );
    }
}
