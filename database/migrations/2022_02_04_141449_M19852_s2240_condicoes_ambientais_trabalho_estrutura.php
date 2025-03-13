<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M19852S2240CondicoesAmbientaisTrabalhoEstrutura extends Migration
{
    
    public function up() 
    {
        
        $sSql = <<<SQL
ALTER TABLE pessoal.rhlocaltrab ADD COLUMN rh55_tipolocal integer;
ALTER TABLE pessoal.rhlocaltrab ADD COLUMN rh55_endereco varchar(100);
ALTER TABLE pessoal.rhlocaltrab ADD COLUMN rh55_tipoestabelecimento integer;
ALTER TABLE pessoal.rhlocaltrab ADD COLUMN rh55_tipoinscricao integer;
ALTER TABLE pessoal.rhlocaltrab ADD COLUMN rh55_numeroinscricao varchar(14);
ALTER TABLE pessoal.rhlocaltrab ADD COLUMN rh55_observacaoregistrosambientais text;

CREATE TABLE pessoal.rhlocaltrabagentesnocivos (
  rh256_sequencial integer primary key,
  rh256_rhlocaltrab integer,
  rh256_instituicao integer,
  rh256_agentequimico varchar(10),
  rh256_agentefisico varchar(10),
  rh256_agentebiologico varchar(10),
  rh256_associacaoagente varchar(10),
  rh256_outroagente varchar(10),
  rh256_ausenciaagente varchar(10),
  rh256_tipoavaliacao integer,
  rh256_intensidadeconcentracao varchar(10),
  rh256_tolerancialimite varchar(10),
  rh256_medida varchar(3),
  rh256_tecnicamedicao varchar(40)
);
CREATE SEQUENCE pessoal.rhlocaltrabagentesnocivos_rh256_sequencial_seq;

ALTER TABLE pessoal.rhlocaltrabagentesnocivos ADD CONSTRAINT "rhlocaltrabagentesnocivos_rhlocaltrab_fk" 
FOREIGN KEY (rh256_rhlocaltrab, rh256_instituicao) REFERENCES pessoal.rhlocaltrab(rh55_codigo, rh55_instit);

CREATE TABLE pessoal.rhlocaltrabequipamentoprotecao (
  rh257_sequencial integer primary key,
  rh257_rhlocaltrab integer,
  rh257_instituicao integer,
  rh257_utilizaepc integer,
  rh257_eficaciaepc varchar(1),
  rh257_utilizaepi integer,
  rh257_eficaciaepi varchar(1),
  rh257_medidaprotecaoepi varchar(1),
  rh257_funcionamentoepi varchar(1),
  rh257_usoininterruptoepi varchar(1),
  rh257_validadeepi varchar(1),
  rh257_periodicidadeepi varchar(1),
  rh257_higienizacaoepi varchar(1)
);
CREATE SEQUENCE pessoal.rhlocaltrabequipamentoprotecao_rh257_sequencial_seq;

ALTER TABLE pessoal.rhlocaltrabequipamentoprotecao ADD CONSTRAINT "rhlocaltrabequipamentoprotecao_rhlocaltrab_fk" 
FOREIGN KEY (rh257_rhlocaltrab, rh257_instituicao) REFERENCES pessoal.rhlocaltrab(rh55_codigo, rh55_instit);

CREATE TABLE pessoal.rhlocaltrabequipamentoprotecaoepi (
  rh259_sequencial integer primary key,
  rh259_rhlocaltrabequipamentoprotecao integer,
  rh259_documentoavaliacao varchar(255),
  rh259_descricao text
);
CREATE SEQUENCE pessoal.rhlocaltrabequipamentoprotecaoepi_rh259_sequencial_seq;

ALTER TABLE pessoal.rhlocaltrabequipamentoprotecaoepi ADD CONSTRAINT "rhlocaltrabequipamentoprotecaoepi_rhlocaltrabequipamentoprotecao_fk" 
FOREIGN KEY (rh259_rhlocaltrabequipamentoprotecao) REFERENCES pessoal.rhlocaltrabequipamentoprotecao(rh257_sequencial);

CREATE TABLE pessoal.rhlocaltrabregistroambiental (
  rh258_sequencial integer primary key,
  rh258_rhlocaltrab integer,
  rh258_instituicao integer,
  rh258_cpfresponsavel varchar(11),
  rh258_identificacaoorgao integer,
  rh258_numeroinscricaoorgao varchar(14),
  rh258_descricaoorgao varchar(20),
  rh258_uforgao varchar(2),
  rh258_periodoinicial date,
  rh258_periodofinal date
);
CREATE SEQUENCE pessoal.rhlocaltrabregistroambiental_rh258_sequencial_seq;
ALTER TABLE pessoal.rhlocaltrabregistroambiental ADD CONSTRAINT "rhlocaltrabregistroambiental_rhlocaltrab_fk" 
FOREIGN KEY (rh258_rhlocaltrab, rh258_instituicao) REFERENCES rhlocaltrab(rh55_codigo, rh55_instit);

CREATE INDEX rhlocaltrabregistroambiental_rhlocaltrab_in ON pessoal.rhlocaltrabregistroambiental(rh258_rhlocaltrab,rh258_instituicao);  
CREATE INDEX rhlocaltrabequipamentoprotecao_rhlocaltrab_in ON pessoal.rhlocaltrabequipamentoprotecao(rh257_rhlocaltrab,rh257_instituicao);
CREATE INDEX rhlocaltrabequipamentoprotecaoepi_rhlocaltrab_in ON pessoal.rhlocaltrabequipamentoprotecaoepi(rh259_rhlocaltrabequipamentoprotecao);
CREATE INDEX rhlocaltrabagentesnocivos_rhlocaltrab_in ON pessoal.rhlocaltrabagentesnocivos(rh256_rhlocaltrab,rh256_instituicao);    

SELECT SETVAL('pessoal.rhlocaltrab_rh55_codigo_seq',(select max(rh55_codigo) from rhlocaltrab));

SQL;
        DB::connection()->getPdo()->exec($sSql);
        
        
    }
    
    public function down() 
    {
        
        $sSql = <<<SQL
ALTER TABLE pessoal.rhlocaltrab DROP COLUMN rh55_tipolocal;
ALTER TABLE pessoal.rhlocaltrab DROP COLUMN rh55_endereco;
ALTER TABLE pessoal.rhlocaltrab DROP COLUMN rh55_tipoestabelecimento;
ALTER TABLE pessoal.rhlocaltrab DROP COLUMN rh55_tipoinscricao;
ALTER TABLE pessoal.rhlocaltrab DROP COLUMN rh55_numeroinscricao;
ALTER TABLE pessoal.rhlocaltrab DROP COLUMN rh55_observacaoregistrosambientais;

DROP TABLE pessoal.rhlocaltrabagentesnocivos;
DROP SEQUENCE pessoal.rhlocaltrabagentesnocivos_rh256_sequencial_seq;

DROP TABLE pessoal.rhlocaltrabequipamentoprotecaoepi;
DROP SEQUENCE pessoal.rhlocaltrabequipamentoprotecaoepi_rh259_sequencial_seq;

DROP TABLE pessoal.rhlocaltrabequipamentoprotecao;
DROP SEQUENCE pessoal.rhlocaltrabequipamentoprotecao_rh257_sequencial_seq;

DROP TABLE pessoal.rhlocaltrabregistroambiental;
DROP SEQUENCE pessoal.rhlocaltrabregistroambiental_rh258_sequencial_seq;
SQL;
        DB::connection()->getPdo()->exec($sSql);
        
        
    }
    
}
