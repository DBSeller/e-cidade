<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M20075AlteracaoEstruturaRhlocaltrab extends Migration
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
        $sSql = <<<SQL
alter table pessoal.rhlocaltrabagentesnocivos rename column rh256_agentenocivo to rh256_agentequimico;

delete from db_sysforkey where codcam = 1013739;
delete from db_sysarqcamp where codcam = 1013739;
delete from db_syscampo where codcam = 1013739;

delete from db_syscadind where codind in (1008724,1008725);
delete from db_sysindices where codind in (1008724,1008725);
SQL;
        DB::connection()->getPdo()->exec($sSql);
    }
    
    public function upDicionario() {
        
        $sSql = <<<SQL
delete from db_sysforkey where codcam in (1013678,1013679);
delete from db_sysforkey where codarq = 1010859;

delete from db_syscadind where codind = 1008712;
delete from db_sysindices where codind = 1008712;

update db_syscampo set nomecam = 'rh256_agentenocivo', descricao = 'Agente Nocivo', rotulo = 'Agente Nocivo' where codcam = 1013666;

delete from db_sysarqcamp where codcam in (1013667,1013668,1013669,1013670,1013671,1013678,1013679);
delete from db_syscampo where codcam in (1013667,1013668,1013669,1013670,1013671,1013678,1013679);

delete from db_sysarqcamp where codarq = 1010858;
insert into db_sysarqcamp values(1010858,1013663,1,1001033);
insert into db_sysarqcamp values(1010858,1013664,2,0);
insert into db_sysarqcamp values(1010858,1013665,3,0);
insert into db_sysarqcamp values(1010858,1013666,4,0);
insert into db_sysarqcamp values(1010858,1013672,5,0);
insert into db_sysarqcamp values(1010858,1013673,6,0);
insert into db_sysarqcamp values(1010858,1013674,7,0);
insert into db_sysarqcamp values(1010858,1013675,8,0);
insert into db_sysarqcamp values(1010858,1013676,9,0);

insert into db_syscampo values(1013739,'rh257_rhlocaltrabagentesnocivos','int4','Sequencial Agente Nocivo','0', 'Sequencial Agente Nocivo',10,'f','f','f',1,'text','Sequencial Agente Nocivo');

delete from db_sysarqcamp where codarq = 1010859;
insert into db_sysarqcamp values(1010859,1013677,1,1001034);
insert into db_sysarqcamp values(1010859,1013739,2,0);
insert into db_sysarqcamp values(1010859,1013680,3,0);
insert into db_sysarqcamp values(1010859,1013681,4,0);
insert into db_sysarqcamp values(1010859,1013682,5,0);
insert into db_sysarqcamp values(1010859,1013683,6,0);
insert into db_sysarqcamp values(1010859,1013686,7,0);
insert into db_sysarqcamp values(1010859,1013687,8,0);
insert into db_sysarqcamp values(1010859,1013688,9,0);
insert into db_sysarqcamp values(1010859,1013689,10,0);
insert into db_sysarqcamp values(1010859,1013690,11,0);
insert into db_sysarqcamp values(1010859,1013691,12,0);

insert into db_sysforkey values(1010859,1013739,1,1010858,0);

insert into db_sysindices values(1008724,'rhlocaltrabequipamentoprotecao_rhlocaltrabagentesnocivos_in',1010859,'0');
insert into db_syscadind values(1008724,1013739,1);

insert into db_sysindices values(1008725,'rhlocaltrabequipamentoprotecaoepi_documento_descricao_unique_in',1010861,'1');
insert into db_syscadind values(1008725,1013703,1);
insert into db_syscadind values(1008725,1013704,2);
insert into db_syscadind values(1008725,1013705,3);

update db_syscampo set nulo = 't' where codcam in (1013704,1013705);
SQL;
        DB::connection()->getPdo()->exec($sSql);
        
    }
    
    public function upEstrutura() {
        
        $sSql = <<<SQL
alter table pessoal.rhlocaltrabagentesnocivos rename column rh256_agentequimico to rh256_agentenocivo;

alter table pessoal.rhlocaltrabagentesnocivos drop column if exists rh256_agentefisico;
alter table pessoal.rhlocaltrabagentesnocivos drop column if exists rh256_agentebiologico; 
alter table pessoal.rhlocaltrabagentesnocivos drop column if exists rh256_associacaoagente;
alter table pessoal.rhlocaltrabagentesnocivos drop column if exists rh256_outroagente;
alter table pessoal.rhlocaltrabagentesnocivos drop column if exists rh256_ausenciaagente;  

drop table if exists pessoal.rhlocaltrabequipamentoprotecaoepi;
drop sequence pessoal.rhlocaltrabequipamentoprotecaoepi_rh259_sequencial_seq;

drop table if exists pessoal.rhlocaltrabequipamentoprotecao;
drop sequence pessoal.rhlocaltrabequipamentoprotecao_rh257_sequencial_seq;

CREATE TABLE pessoal.rhlocaltrabequipamentoprotecao (
  rh257_sequencial integer primary key,
  rh257_rhlocaltrabagentesnocivos integer,
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
FOREIGN KEY (rh257_rhlocaltrabagentesnocivos) REFERENCES pessoal.rhlocaltrabagentesnocivos(rh256_sequencial);

CREATE INDEX rhlocaltrabequipamentoprotecao_rhlocaltrabagentesnocivos_in ON pessoal.rhlocaltrabequipamentoprotecao(rh257_rhlocaltrabagentesnocivos);

CREATE TABLE pessoal.rhlocaltrabequipamentoprotecaoepi (
  rh259_sequencial integer primary key,
  rh259_rhlocaltrabequipamentoprotecao integer,
  rh259_documentoavaliacao varchar(255),
  rh259_descricao text
);
CREATE SEQUENCE pessoal.rhlocaltrabequipamentoprotecaoepi_rh259_sequencial_seq;

ALTER TABLE pessoal.rhlocaltrabequipamentoprotecaoepi ADD CONSTRAINT "rhlocaltrabequipamentoprotecaoepi_rhlocaltrabequipamentoprotecao_fk" 
FOREIGN KEY (rh259_rhlocaltrabequipamentoprotecao) REFERENCES pessoal.rhlocaltrabequipamentoprotecao(rh257_sequencial);

CREATE INDEX rhlocaltrabequipamentoprotecaoepi_rh259_rhlocaltrabequipamentoprotecao_in ON pessoal.rhlocaltrabequipamentoprotecaoepi(rh259_rhlocaltrabequipamentoprotecao);
CREATE UNIQUE INDEX rhlocaltrabequipamentoprotecaoepi_documento_descricao_unique_in ON pessoal.rhlocaltrabequipamentoprotecaoepi(rh259_rhlocaltrabequipamentoprotecao,rh259_documentoavaliacao,rh259_descricao);

SQL;
        DB::connection()->getPdo()->exec($sSql);
        
    }
    
}