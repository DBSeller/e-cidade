<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CriacaoVinculoAfastamentoEsocial extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->upCamposDinamicos();
    }

    private function upCamposDinamicos()
    {
        $this->upAdicionaCamposDinamicos();
        $this->upAcertoBaseMotivoAfastamentoEsocial();
        $this->upAcertoBaseCampoDinamicoAtributosOpcoes();
    }

     /**
     * Run the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->downCamposDinamicos();
    }

    private function downCamposDinamicos()
    {
        $this->downAcertoBaseCampoDinamicoAtributosOpcoes();
        $this->downAcertoBaseMotivoAfastamentoEsocial();

    }

    private function upAdicionaCamposDinamicos()
    {
        $sql = <<<SQL
			create temp table dados_originais_motivo_afastamento_esocial as
            (SELECT db_cadattdinamico.db118_sequencial,
                   db_cadattdinamicoatributos.db109_sequencial,
                   db_cadattdinamicoatributosopcoes.db18_cadattdinamicoatributos
            FROM esocial.grupomotivoafastamentoesocial
                INNER JOIN esocial.motivoafastamentoesocial ON eso09_grupomotivoafastamentoesocial = eso10_sequencial
                INNER JOIN configuracoes.db_cadattdinamico ON eso10_db_cadattdinamico = db118_sequencial
                INNER JOIN configuracoes.db_cadattdinamicoatributos ON db118_sequencial = db109_db_cadattdinamico
                INNER JOIN configuracoes.db_cadattdinamicoatributosopcoes ON db18_cadattdinamicoatributos = db109_sequencial
            WHERE eso09_sequencial = 27);

SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function upAcertoBaseMotivoAfastamentoEsocial()
    {
        $sql = <<<SQL
        INSERT INTO esocial.motivoafastamentoesocial values(41,'Qualificação: Afastamento por suspensão do contrato de acordo com o art. 17 da MP 1.116/2022',15);
        INSERT INTO esocial.motivoafastamentoesocial values(42,'Qualificação: Afastamento por suspensão do contrato de acordo com o art. 19 da MP 1.116/2022',15);
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function upAcertoBaseCampoDinamicoAtributosOpcoes()
    {
        $sql = <<<SQL
        INSERT INTO configuracoes.db_cadattdinamicoatributosopcoes VALUES(nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'),
            (SELECT distinct db18_cadattdinamicoatributos FROM dados_originais_motivo_afastamento_esocial), 
            41,'Cód. 41: Qualificação - Afastamento por suspensão do contrato de acordo com o art. 17 da MP 1.116/2022');
            
        INSERT INTO configuracoes.db_cadattdinamicoatributosopcoes VALUES(nextval('db_cadattdinamicoatributosopcoes_db18_sequencial_seq'),
            (SELECT distinct db18_cadattdinamicoatributos FROM dados_originais_motivo_afastamento_esocial), 
            42,'Cód. 42: Qualificação - Afastamento por suspensão do contrato de acordo com o art. 19 da MP 1.116/2022');

SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function downAcertoBaseCampoDinamicoAtributosOpcoes()
    {
        $sql = <<<SQL
        DELETE FROM configuracoes.db_cadattdinamicoatributosopcoes where db18_opcao in ('41','42');

SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function downAcertoBaseMotivoAfastamentoEsocial()
    {
        $sql = <<<SQL
        DELETE FROM esocial.motivoafastamentoesocial where eso09_sequencial in (41,42);
SQL;
        DB::connection()->getPdo()->exec($sql);
    }
}
