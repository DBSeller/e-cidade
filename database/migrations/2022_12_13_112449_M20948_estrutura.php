<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M20948Estrutura extends Migration
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

    private function upDicionario()
    {
        DB::connection()->getPdo()->exec(<<<SQL
insert into db_sysarquivo
values (1011004, 'pcasp', 'Plano de Contas padãr', 'c150', '2022-12-13', 'pcasp', 0, 'f', 'f', 'f', 'f' ),
       (1011005, 'pcaspconplano', 'Vínculo do plano padrão com o plano do ecidade', '151', '2022-12-13', 'pcaspconplano', 0, 'f', 'f', 'f', 'f' ),
       (1011006, 'planoreceita', 'Plano orçamentário padrão da Receita', 'c152', '2022-12-13', 'planoreceita', 0, 'f', 'f', 'f', 'f' ),
       (1011007, 'planodespesa', 'Plano orçamentário padrão da Despesa', 'c153', '2022-12-13', 'planodespesa', 0, 'f', 'f', 'f', 'f' ),
       (1011008, 'planoreceitaconplanoorcamento', 'Vínculo do plano padrão da receita com o do ecidade', 'c154', '2022-12-13', 'planoreceitaconplanoorcamento', 0, 'f', 'f', 'f', 'f' ),
       (1011009, 'planodespesaconplanoorcamento', 'Vínculo do plano padrão da despesa com o do ecidade', 'c156', '2022-12-13', 'planodespesaconplanoorcamento', 0, 'f', 'f', 'f', 'f' );

insert into db_sysarqmod
values (32,1011004),
       (32,1011005),
       (32,1011006),
       (32,1011007),
       (32,1011008),
       (32,1011009);

insert into db_syscampo (codcam, nomecam, conteudo, descricao, valorinicial, rotulo, nulo, tamanho, maiusculo, autocompl, aceitatipo, tipoobj, rotulorel)
values (21169, 'titulo', 'int4', 'Titulo', '0', 'Titulo', 'f', 10, 'f', 'f', 1, 'text', 'Titulo');

insert into db_syscampo
values (1014630,'uniao','bool','Se verdadeiro é o plano da União, se não é do estado','f', 'União',1,'f','f','f',5,'text','União'),
       (1014631,'conta','varchar(15)','Conta','', 'Conta',15,'f','f','f',1,'text','Conta'),
       (1014632,'natureza','varchar(3)','Natureza do saldo; - D - C - C/D','', 'natureza',3,'f','f','f',0,'text','natureza'),
       (1014633,'sintetica','bool','Se a conta é sintética no plano','f', 'Sintética',1,'f','f','f',5,'text','Sintética'),
       (1014634,'indicador','varchar(3)','Indicador de superávit financeiro. Valores válidos: - N - F - P - F/P','', 'Indicador',3,'f','t','f',0,'text','indicador'),
       (1014635,'informacoescomplementares','varchar(100)','Informações complementares da conta','', 'informacoescomplementares',100,'t','f','f',0,'text','informacoescomplementares'),
       (1014636,'classe','int4','Classe ','0', 'Classe',10,'f','f','f',1,'text','Classe'),
       (1014637,'grupo','int4','Grupo do estrutural','0', 'Grupo',10,'f','f','f',1,'text','Grupo'),
       (1014638,'subgrupo','int4','Subgrupo do estrutural','0', 'Subgrupo',10,'f','f','f',1,'text','Subgrupo'),
       (1014639,'subtitulo','int4','subtitulo do estrutural','0', 'Subtitulo',10,'f','f','f',1,'text','subtitulo'),
       (1014640,'item','char(2)','Item do estrutural','', 'Item',2,'f','f','f',1,'text','Item'),
       (1014641,'subitem','char(2)','Subitem do estrutural','', 'Subitem',2,'f','f','f',1,'text','Subitem'),
       (1014642,'desdobramento1','char(2)','Primeiro desdobramento do estrutural','', 'desdobramento1',2,'f','f','f',1,'text','desdobramento1'),
       (1014643,'desdobramento2','char(2)','Segundo desdobramento do estrutural','', 'Desdobramento2',2,'f','f','f',1,'text','Desdobramento2'),
       (1014644,'desdobramento3','char(2)','Terceiro desdobramento do estrutural','', 'Desdobramento3',2,'f','f','f',1,'text','Desdobramento3'),
       (1014645,'c60_codigo','int8','Código único da conta','0', 'Código da conta',10,'f','f','f',1,'text','Código da conta'),
       (1014646,'pcasp_id','int8','Vínculo Pcasp','0', 'pcasp',10,'f','f','f',1,'text','pcasp'),
       (1014647,'conplano_codigo','int8','Vínculo plano contas e-cidade','0', 'Conplano',10,'f','f','f',1,'text','Conplano'),
       (1014648,'categoria','int4','Categoria','0', 'Categoria',10,'f','f','f',1,'text','Categoria'),
       (1014649,'especie','int4','Especie','0', 'especie',10,'f','f','f',1,'text','especie'),
       (1014650,'desdobramento4','char(2)','Quarto desdobramento ','', 'Desdobramento4',2,'f','f','f',1,'text','Desdobramento4'),
       (1014651,'desdobramento5','char(2)','Quinto desdobramento ','', 'Desdobramento5',2,'f','f','f',1,'text','Desdobramento5'),
       (1014652,'desdobramento6','char(2)','Sexto desdobramento ','', 'Desdobramento 6 ',2,'f','f','f',1,'text','Desdobramento 6 '),
       (1014653,'modalidade','char(2)','Modalidade','', 'Modalidade',2,'f','f','f',1,'text','Modalidade'),
       (1014654,'elemento','char(2)','Elemento','', 'Elemento',2,'f','f','f',1,'text','Elemento'),
       (1014655,'subelemento','char(2)','Subelemento','', 'Subelemento',2,'f','f','f',1,'text','Subelemento'),
       (1014656,'planoreceita_id','int8','Vínculo com o plano da receita','0', 'Plano Receita',10,'f','f','f',1,'text','Plano Receita'),
       (1014657,'planodespesa_id','int8','Plano da despesa','0', 'Plano despesa',10,'f','f','f',1,'text','Plano despesa'),
       (1014658,'conplanoorcamento_codigo','int8','Vínculo conplano orçamento','0', 'Conplano Orçamento',10,'f','f','f',1,'text','Conplano Orçamento');

insert into db_sysarqcamp
values (1011004,1011345,1,0),
       (1011004,15983,2,0),
       (1011004,1014630,3,0),
       (1011004,1014631,4,0),
       (1011004,570,5,0),
       (1011004,824,6,0),
       (1011004,1014632,7,0),
       (1011004,1014633,8,0),
       (1011004,1014634,9,0),
       (1011004,1014635,10,0),
       (1011004,1014636,11,0),
       (1011004,1014637,12,0),
       (1011004,1014638,13,0),
       (1011004,21169,14,0),
       (1011004,1014639,15,0),
       (1011004,1014640,16,0),
       (1011004,1014641,17,0),
       (1011004,1014642,18,0),
       (1011004,1014643,19,0),
       (1011004,1014644,20,0),
       (774,1014645,13,0),
       (1011005,1011345,1,0),
       (1011005,1014646,2,0),
       (1011005,1014647,3,0),
       (1011005,1012583,4,0),
       (1011005,1012584,5,0),
       (1011006,1011345,1,0),
       (1011006,15983,2,0),
       (1011006,1014630,3,0),
       (1011006,1014631,4,0),
       (1011006,824,5,0),
       (1011006,1014633,6,0),
       (1011006,1014636,7,0),
       (1011006,1014648,8,0),
       (1011006,2298,9,0),
       (1011006,1014649,10,0),
       (1011006,1014642,11,0),
       (1011006,1014643,12,0),
       (1011006,1014644,13,0),
       (1011006,1073,14,0),
       (1011006,1014650,15,0),
       (1011006,1014651,16,0),
       (1011006,1014652,17,0),
       (1011006,1012583,18,0),
       (1011006,1012584,19,0),
       (1011007,1011345,1,0),
       (1011007,15983,2,0),
       (1011007,1014630,3,0),
       (1011007,1014631,4,0),
       (1011007,570,5,0),
       (1011007,824,6,0),
       (1011007,1014633,7,0),
       (1011007,1014636,8,0),
       (1011007,1014637,9,0),
       (1011007,1014653,10,0),
       (1011007,1014654,11,0),
       (1011007,1014655,12,0),
       (1011007,1014642,13,0),
       (1011007,1014643,14,0),
       (1011007,1014644,15,0),
       (1011007,1012583,16,0),
       (1011007,1012584,17,0),
       (1011009,1011345,1,0),
       (1011009,1014657,2,0),
       (1011009,1014658,3,0),
       (1011008,1011345,1,0),
       (1011008,1014656,2,0),
       (1011008,1014658,3,0);

insert into db_sysprikey (codarq,codcam,sequen,camiden)
values (1011004,1011345,1,1014631),
       (1011005,1011345,1,1011345),
       (1011006,1011345,1,1014631),
       (1011007,1011345,1,1014631),
       (1011009,1011345,1,1011345),
       (1011008,1011345,1,1011345);

insert into db_sysindices
values (1008819,'pcasp_conta_in',1011004,'0'),
       (1008820,'pcaspconplano_pcasp_id_conplano_codigo',1011005,'0'),
       (1008821,'planoreceita_exercicio_uniao_in',1011006,'0'),
       (1008822,'planoreceita_conta_in',1011006,'0'),
       (1008823,'planodespesa_exercicio_uniao_conta_in',1011007,'0'),
       (1008824,'planodespesaconplanoorcamento_conplanoorcamento_codigo_in',1011009,'0'),
       (1008825,'planoreceitaconplanoorcamento_conplanoorcamento_codigo_in',1011008,'0');

insert into db_syscadind
values (1008819,1014631,1),
       (1008820,1014646,1),
       (1008820,1014647,2),
       (1008821,15983,1),
       (1008821,1014630,2),
       (1008822,1014631,1),
       (1008823,15983,1),
       (1008823,1014630,2),
       (1008823,1014631,3),
       (1008824,1014658,1),
       (1008825,1014658,1);

insert into db_sysforkey
values (1011005,1014646,1,1011004,0),
       (1011009,1014657,1,1011007,0),
       (1011008,1014656,1,1011006,0);
SQL
        );
    }

    private function downDicionario()
    {
        DB::connection()->getPdo()->exec(<<<SQL
delete from db_syscadind where codind in (1008819, 1008820, 1008821, 1008822, 1008823, 1008824, 1008825);
delete from db_sysindices where codind in (1008819, 1008820, 1008821, 1008822, 1008823, 1008824, 1008825);
delete from db_sysprikey where codarq in (1011004, 1011005, 1011006, 1011007, 1011008, 1011009);
delete from db_sysforkey where codarq in (1011004, 1011005, 1011006, 1011007, 1011008, 1011009);
delete from db_sysarqcamp where codarq in (1011004, 1011005, 1011006, 1011007, 1011008, 1011009);

delete from db_sysarqcamp where codarq = 774 and codcam = 1014645;
delete from db_syscampo where codcam in (21169, 1014630, 1014631, 1014632, 1014633, 1014634, 1014635, 1014636, 1014637, 1014638, 1014639, 1014640, 1014641, 1014642, 1014643, 1014644, 1014645, 1014646, 1014647, 1014648, 1014649, 1014650, 1014651, 1014652, 1014653, 1014654, 1014655, 1014656, 1014657, 1014658);
delete from db_sysarqmod where codarq in (1011004, 1011005, 1011006, 1011007, 1011008, 1011009);
delete from db_sysarquivo where codarq in (1011004, 1011005, 1011006, 1011007, 1011008, 1011009);
SQL
        );
    }

    private function upEstrutura()
    {
        DB::connection()->getPdo()->exec(<<<SQL
CREATE TYPE superavit_financeiro as enum (
  'N',
  'F',
  'P',
  'F/P'
);

CREATE TYPE natureza_saldo as enum (
  'D',
  'C',
  'C/D'
);

alter table contabilidade.conplano add column c60_codigo bigserial;
alter table contabilidade.conplanoorcamento add column c60_codigo bigserial;
create unique index conplano_codigo_in on contabilidade.conplano(c60_codigo);
create unique index conplanoorcamento_codigo_in on contabilidade.conplanoorcamento(c60_codigo);

create table contabilidade.pcasp (
    id bigserial primary key,
    exercicio integer not null,
    uniao boolean default true,
    conta varchar(15) not null,
    nome varchar(255) not null,
    funcao text,
    natureza natureza_saldo,
    sintetica boolean default true,
    indicador superavit_financeiro,
    informacoescomplementares varchar(100) default null,
    classe int,
    grupo int,
    subgrupo int,
    titulo int,
    subtitulo int,
    item char(2) default '00',
    subitem char(2) default '00',
    desdobramento1 char(2) default '00',
    desdobramento2 char(2) default '00',
    desdobramento3 char(2) default '00',
    created_at timestamp,
    updated_at timestamp
);

create table contabilidade.pcaspconplano (
  id bigserial primary key,
  pcasp_id bigint,
  conplano_codigo bigint,
  created_at timestamp,
  updated_at timestamp,
  foreign key (pcasp_id) references contabilidade.pcasp on delete cascade
);

create table contabilidade.planoreceita (
  id bigserial primary key,
  exercicio integer not null,
  uniao boolean default true,
  conta varchar(15) not null,
  nome varchar(255) not null,
  funcao text default null,
  sintetica boolean default true,
  classe int,
  categoria int,
  origem int,
  especie int,
  desdobramento1 char(1) default '00',
  desdobramento2 char(2) default '00',
  desdobramento3 char(1) default '00',
  tipo int,
  desdobramento4 char(2) default '00',
  desdobramento5 char(2) default '00',
  desdobramento6 char(2) default '00',
  created_at timestamp,
  updated_at timestamp
);

create table contabilidade.planodespesa (
  id bigserial primary key,
  exercicio integer not null,
  uniao boolean default true,
  conta varchar(15) not null,
  nome varchar(255) not null,
  funcao text default null,
  sintetica boolean default true,
  classe int,
  grupo int,
  modalidade char(2) default '00',
  elemento char(2) default '00',
  subelemento char(2) default '00',
  desdobramento1 char(2) default '00',
  desdobramento2 char(2) default '00',
  desdobramento3 char(2) default '00',
  created_at timestamp,
  updated_at timestamp
);

create table contabilidade.planoreceitaconplanoorcamento (
  id bigserial primary key,
  planoreceita_id bigint,
  conplanoorcamento_codigo bigint,
  foreign key (planoreceita_id) references contabilidade.planoreceita on delete cascade
);

create table contabilidade.planodespesaconplanoorcamento (
  id bigserial primary key,
  planodespesa_id bigint,
  conplanoorcamento_codigo bigint,
  foreign key (planodespesa_id) references contabilidade.planodespesa on delete cascade
);

alter table contabilidade.conplanoatributos alter column c120_sequencial set default nextval('contabilidade.conplanoatributos_c120_sequencial_seq');

create index pcasp_conta_in on contabilidade.pcasp(conta);
create index pcaspconplano_pcasp_id_conplano_codigo on contabilidade.pcaspconplano(pcasp_id, conplano_codigo);
create index planodespesa_exercicio_uniao_conta_in on contabilidade.planodespesa(exercicio, uniao, conta);
create index planodespesaconplanoorcamento_conplanoorcamento_codigo_in on contabilidade.planodespesaconplanoorcamento(conplanoorcamento_codigo);
create index planoreceita_exercicio_uniao_conta_in on contabilidade.planoreceita(exercicio, uniao, conta);
create index planoreceitaconplanoorcamento_conplanoorcamento_codigo_in on contabilidade.planoreceitaconplanoorcamento(conplanoorcamento_codigo);

SQL
        );
    }

    private function downEstrutura()
    {
        DB::connection()->getPdo()->exec(<<<SQL
drop table if exists contabilidade.pcaspconplano;
drop table if exists contabilidade.pcasp;

drop table if exists contabilidade.planoreceitaconplanoorcamento ;
drop table if exists contabilidade.planoreceita;

drop table if exists contabilidade.planodespesaconplanoorcamento ;
drop table if exists contabilidade.planodespesa;

alter table contabilidade.conplano drop column c60_codigo;
alter table contabilidade.conplanoorcamento drop column c60_codigo;

drop type superavit_financeiro;
drop type natureza_saldo;
SQL
        );
    }
}
