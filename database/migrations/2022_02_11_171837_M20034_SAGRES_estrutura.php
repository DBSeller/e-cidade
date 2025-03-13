<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M20034SAGRESEstrutura extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	    $sSql = <<<SQL
CREATE TABLE contabilidade.sagresordenadordespesa ( 
    c139_sequencial int primary key,
    c139_instit integer not null,
    c139_cgm	integer not null,
    c139_cgmsubstituto integer,
    c139_principal varchar(1) not null,
    c139_substituto varchar(1) not null,
    c139_datainicio date not null,
    c139_datafim date,
    c139_tipoatojuridico integer not null,
    c139_titulo varchar(50),
    c139_ativo varchar(1) default 't' not null,
    c139_datainatividade date,
    c139_idusuario integer,
    c139_datainiciosub date,
    c139_datafimsub date
);
CREATE SEQUENCE contabilidade.sagresordenadordespesa_c139_sequencial_seq;

ALTER TABLE contabilidade.sagresordenadordespesa ADD CONSTRAINT "sagresordenadordespesa_instit_fk" 
FOREIGN KEY (c139_instit) REFERENCES db_config(codigo);

ALTER TABLE contabilidade.sagresordenadordespesa ADD CONSTRAINT "sagresordenadordespesa_numcgm_fk" 
FOREIGN KEY (c139_cgm) REFERENCES cgm(z01_numcgm);

CREATE INDEX sagresordenadordespesa_instit_in ON contabilidade.sagresordenadordespesa(c139_instit);
CREATE INDEX sagresordenadordespesa_numcgm_in ON contabilidade.sagresordenadordespesa(c139_cgm);

CREATE TABLE contabilidade.sagresresponsavelunidadeorcamentaria ( 
    c140_sequencial int primary key,
    c140_orgao integer not null,
    c140_unidade integer not null,
    c140_cgm integer not null,
    c140_cgmsubstituto	integer,
    c140_principal	varchar(1) not null,
    c140_substituto varchar(1) not null,
    c140_datainicio date not null,
    c140_datafim date,
    c140_tipoatojuridico integer not null,
    c140_ativo varchar(1) default 't' not null,
    c140_datainatividade date,
    c140_idusuario integer,
    c140_anousu integer not null,
    c140_instit integer not null,
    c140_datainiciosub date,
    c140_datafimsub date
);
CREATE SEQUENCE contabilidade.sagresresponsavelunidadeorcamentaria_c140_sequencial_seq;

ALTER TABLE contabilidade.sagresresponsavelunidadeorcamentaria ADD CONSTRAINT "sagresresponsavelunidadeorcamentaria_orgao_fk" 
FOREIGN KEY (c140_anousu, c140_orgao) REFERENCES orcorgao(o40_anousu, o40_orgao);

ALTER TABLE contabilidade.sagresresponsavelunidadeorcamentaria ADD CONSTRAINT "sagresresponsavelunidadeorcamentaria_unidade_fk" 
FOREIGN KEY (c140_anousu, c140_orgao, c140_unidade) REFERENCES orcunidade(o41_anousu, o41_orgao, o41_unidade);

ALTER TABLE contabilidade.sagresresponsavelunidadeorcamentaria ADD CONSTRAINT "sagresresponsavelunidadeorcamentaria_numcgm_fk" 
FOREIGN KEY (c140_cgm) REFERENCES cgm(z01_numcgm);

ALTER TABLE contabilidade.sagresresponsavelunidadeorcamentaria ADD CONSTRAINT "sagresresponsavelunidadeorcamentaria_instit_fk" 
FOREIGN KEY (c140_instit) REFERENCES db_config(codigo);

CREATE INDEX sagresresponsavelunidadeorcamentaria_orgao_in ON contabilidade.sagresresponsavelunidadeorcamentaria(c140_orgao);
CREATE INDEX sagresresponsavelunidadeorcamentaria_unidade_in ON contabilidade.sagresresponsavelunidadeorcamentaria(c140_unidade);
CREATE INDEX sagresresponsavelunidadeorcamentaria_numcgm_in ON contabilidade.sagresresponsavelunidadeorcamentaria(c140_cgm);

CREATE TABLE contabilidade.sagresarquivogerado (
    c141_sequencial int primary key,
    c141_usuario integer,
    c141_data date,
    c141_codlayout integer,
    c141_nomearquivo varchar(70),
    c141_json json
);
CREATE SEQUENCE contabilidade.sagresarquivogerado_c141_sequencial_seq;

ALTER TABLE contabilidade.sagresarquivogerado ADD CONSTRAINT "sagresarquivogerado_usuario_fk" 
FOREIGN KEY (c141_usuario) REFERENCES db_usuarios(id_usuario);

CREATE INDEX sagresarquivogerado_usuario_in ON contabilidade.sagresarquivogerado(c141_usuario);
SQL;
        DB::connection()->getPdo()->exec($sSql);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

$sSql = <<<SQL
DROP TABLE contabilidade.sagresordenadordespesa;
DROP SEQUENCE contabilidade.sagresordenadordespesa_c139_sequencial_seq;

DROP TABLE contabilidade.sagresresponsavelunidadeorcamentaria;
DROP SEQUENCE contabilidade.sagresresponsavelunidadeorcamentaria_c140_sequencial_seq;

DROP TABLE contabilidade.sagresarquivogerado;
DROP SEQUENCE contabilidade.sagresarquivogerado_c141_sequencial_seq;
SQL;
DB::connection()->getPdo()->exec($sSql);

    }
}
