<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M20687AdicionandoEstruturaPreautorizacaoEmpenho extends Migration
{
    private function upDicionario()
    {
        DB::connection()->getPdo()->exec(<<<SQL
insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228666 ,'Liberação da Autorização' ,'Liberação da Autorização' ,'emp4_liberacao_autorizacaoempenho_orgaounidade001.php' ,'1' ,'1' ,'Liberação da Autorização para órgão / unidade.' ,'true' );
insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 228060 ,228666 ,2 ,398 );

insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228667 ,'Andamento de Autorizações' ,'Andamento de Autorizações' ,'emp4_andamentoautorizacoes.php?modo_andamento=gerencial' ,'1' ,'1' ,'Andamento de Autorizações (gerenciamento)' ,'true' );
insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 2567 ,228667 ,6 ,398 );

insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228668 ,'Consulta de Andamento de Autorizações' ,'Consulta de Andamento de Autorizações' ,'emp4_andamentoautorizacoes.php?modo_andamento=consulta' ,'1' ,'1' ,'Consulta de Andamento de Autorizações (modo consulta)' ,'true' );
insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 2567 ,228668 ,7 ,398 );


insert into db_syscampo values(1014152,'orgao_id','int4','orgao_id','0', 'orgao_id',10,'f','f','f',1,'text','orgao_id');
insert into db_syscampo values(1014153,'unidade_id','int4','unidade_id','0', 'unidade_id',10,'f','f','f',1,'text','unidade_id');
insert into db_sysarquivo values (1010929, 'emppreautorizacaounidade', 'exercicio orgao_id unidade_id', '', '2022-05-23', 'emppreautorizacaounidade', 0, 'f', 'f', 'f', 'f' );
insert into db_sysarqmod values (38,1010929);

insert into db_sysarqcamp values(1010929,15983,1,0);
insert into db_sysarqcamp values(1010929,1014152,2,0);
insert into db_sysarqcamp values(1010929,1014153,3,0);

insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010929,15983,1,15983);
insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010929,1014152,2,15983);
insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010929,1014153,3,15983);

insert into db_sysforkey values(1010929,15983,1,757,0);
insert into db_sysforkey values(1010929,1014152,2,757,0);
insert into db_sysforkey values(1010929,1014153,3,757,0);


insert into db_sysarquivo values (1010930, 'andamentoemppreautorizacaostatus', 'id status descricao', '', '2022-05-23', 'andamentoemppreautorizacaostatus', 0, 'f', 'f', 'f', 'f' );
insert into db_sysarqmod values (38,1010930);

insert into db_sysarqcamp values(1010930,1011345,1,0);
insert into db_sysarqcamp values(1010930,7856,2,0);
insert into db_sysarqcamp values(1010930,750,3,0);

insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010930,1011345,1,1011345);


insert into db_syscampo values(1014158,'empautoriza_id','int4','empautoriza_id','0', 'empautoriza_id',10,'f','f','f',1,'text','empautoriza_id');
insert into db_syscampo values(1014159,'status_id','int4','status_id','0', 'status_id',10,'f','f','f',1,'text','status_id');
insert into db_sysarquivo values (1010932, 'andamentoemppreautorizacao', 'id empautoriza_id status_id observacao id_usuario data', '', '2022-05-23', 'andamentoemppreautorizacao', 0, 'f', 'f', 'f', 'f' );
insert into db_sysarqmod values (38,1010932);

insert into db_sysarqcamp values(1010932,1011345,1,0);
insert into db_sysarqcamp values(1010932,1014158,2,0);
insert into db_sysarqcamp values(1010932,1014159,3,0);
insert into db_sysarqcamp values(1010932,15999,4,0);
insert into db_sysarqcamp values(1010932,568,5,0);
insert into db_sysarqcamp values(1010932,566,6,0);

insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010932,1011345,1,1011345);

insert into db_sysforkey values(1010932,1014158,1,810,0);

insert into db_sysforkey values(1010932,1014159,1,1010930,0);

insert into db_sysforkey values(1010932,568,1,109,0);
insert into db_sysindices values(1008778,'andamentoemppreautorizacao_empautoriza_id_in',1010932,'0');
insert into db_syscadind values(1008778,1014158,1);
insert into db_sysindices values(1008779,'andamentoemppreautorizacao_status_id_in',1010932,'0');
insert into db_syscadind values(1008779,1014159,1);
insert into db_sysindices values(1008780,'andamentoemppreautorizacao_id_usuario_in',1010932,'0');
insert into db_syscadind values(1008780,568,1);


insert into db_sysarquivo values (1010933, 'empautorizacaoautorizada', 'id empautoriza_id', '', '2022-05-23', 'empautorizacaoautorizada', 0, 'f', 'f', 'f', 'f' );
insert into db_sysarqmod values (38,1010933);

insert into db_sysarqcamp values(1010933,1011345,1,0);
insert into db_sysarqcamp values(1010933,1014158,2,0);
insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010933,1011345,1,1011345);
insert into db_sysforkey values(1010933,1014158,1,810,0);
insert into db_sysindices values(1008781,'empautorizacaoautorizada_un_empautoriza_id_in',1010933,'1');
insert into db_syscadind values(1008781,1014158,1);
insert into db_sysindices values(1008782,'empautorizacaoautorizada_empautoriza_id_in',1010933,'0');
insert into db_syscadind values(1008782,1014158,1);
SQL
        );
    }

    private function upEstrutura()
    {
        DB::connection()->getPdo()->exec(<<<SQL
    
CREATE TABLE empenho.emppreautorizacaounidade(
exercicio int not null,
orgao_id int not null,
unidade_id int not null,
CONSTRAINT emppreautorizacaounidade_orgao_unidade_pk PRIMARY KEY(exercicio, orgao_id, unidade_id));

ALTER TABLE empenho.emppreautorizacaounidade
ADD CONSTRAINT "emppreautorizacaounidade_orgao_unidade_fk" FOREIGN KEY (exercicio, orgao_id, unidade_id)
REFERENCES orcunidade(o41_anousu, o41_orgao, o41_unidade);

select configuracoes.fc_auditoria_cria_funcao('empenho.emppreautorizacaounidade');


CREATE TABLE empenho.andamentoemppreautorizacaostatus(
    id serial primary key,
    status varchar(60) not null,
    descricao varchar(255) null
);

select configuracoes.fc_auditoria_cria_funcao('empenho.andamentoemppreautorizacaostatus');

insert into andamentoemppreautorizacaostatus (status) values ('Aguardando Liberação');
insert into andamentoemppreautorizacaostatus (status) values ('Em Análise');
insert into andamentoemppreautorizacaostatus (status) values ('Autorizado');
insert into andamentoemppreautorizacaostatus (status) values ('Não Autorizado');
insert into andamentoemppreautorizacaostatus (status) values ('Revisar / Pendências');



CREATE TABLE empenho.andamentoemppreautorizacao(
    id serial primary key,
    empautoriza_id int not null,
    status_id int not null,
    observacao text default null, 
    id_usuario int not null,
    data date null
);

ALTER TABLE empenho.andamentoemppreautorizacao
ADD CONSTRAINT "andamentoemppreautorizacao_empautoriza_id_fk" FOREIGN KEY (empautoriza_id)
REFERENCES empautoriza(e54_autori);

ALTER TABLE empenho.andamentoemppreautorizacao
ADD CONSTRAINT "andamentoemppreautorizacao_id_usuario_fk" FOREIGN KEY (id_usuario)
REFERENCES db_usuarios(id_usuario);

ALTER TABLE empenho.andamentoemppreautorizacao
ADD CONSTRAINT "andamentoemppreautorizacao_status_id_fk" FOREIGN KEY (status_id)
REFERENCES andamentoemppreautorizacaostatus(id);

CREATE INDEX andamentoemppreautorizacao_empautoriza_id_in ON empenho.andamentoemppreautorizacao using btree (empautoriza_id);
CREATE INDEX andamentoemppreautorizacao_status_id_in ON empenho.andamentoemppreautorizacao using btree (status_id);
CREATE INDEX andamentoemppreautorizacao_id_usuario_in ON empenho.andamentoemppreautorizacao using btree (id_usuario);

select configuracoes.fc_auditoria_cria_funcao('empenho.andamentoemppreautorizacao');


CREATE TABLE empenho.empautorizacaoautorizada(
    id serial primary key,
    empautoriza_id int not null
);

ALTER TABLE empenho.empautorizacaoautorizada
ADD CONSTRAINT "empautorizacaoautorizada_empautoriza_id_fk" FOREIGN KEY (empautoriza_id)
REFERENCES empautoriza(e54_autori);   

CREATE UNIQUE INDEX empautorizacaoautorizada_un_empautoriza_id_in
    ON empenho.empautorizacaoautorizada(empautoriza_id);
    
CREATE INDEX empautorizacaoautorizada_empautoriza_id_in ON empenho.empautorizacaoautorizada using btree (empautoriza_id);  

select configuracoes.fc_auditoria_cria_funcao('empenho.empautorizacaoautorizada');


INSERT INTO empautorizacaoautorizada(empautoriza_id)
	SELECT e54_autori FROM empautoriza
SQL
        );
    }

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

    private function downDicionario()
    {
        DB::connection()->getPdo()->exec(<<<SQL
delete from db_menu where id_item_filho = 228666 AND modulo = 398;
delete from db_itensmenu where id_item = 228666;

delete from db_menu where id_item_filho = 228667 AND modulo = 398;
delete from db_itensmenu where id_item = 228667;

delete from db_menu where id_item_filho = 228668 AND modulo = 398;
delete from db_itensmenu where id_item = 228668;


delete from db_sysforkey where codarq = 1010929;
delete from db_sysprikey where codarq = 1010929;
delete from db_sysarqcamp where codarq = 1010929;
delete from db_sysarqmod where codmod = 38 and codarq = 1010929;

delete from db_sysarquivo where codarq = 1010929;

delete from db_syscampo where codcam = 1014152;
delete from db_syscampo where codcam = 1014153;


delete from db_sysprikey where codarq = 1010930;
delete from db_sysarqcamp where codarq = 1010930;

delete from db_sysarqmod where codmod = 38 and codarq = 1010930;
delete from db_sysarquivo where codarq = 1010930;


delete from db_syscadind where codind in (1008778, 1008779, 1008780);
delete from db_sysindices where codarq = 1010932;
delete from db_sysforkey where codarq = 1010932;
delete from db_sysprikey where codarq = 1010932;
delete from db_sysarqcamp where codarq = 1010932;
delete from db_sysarqmod where codmod = 38 and codarq = 1010932;
delete from db_sysarquivo where codarq = 1010932;
delete from db_syscampo where codcam = 1014159;


delete from db_syscadind where codind in (1008782, 1008781);
delete from db_sysindices where codarq = 1010933;
delete from db_sysforkey where codarq = 1010933;
delete from db_sysprikey where codarq = 1010933;
delete from db_sysarqcamp where codarq = 1010933;
delete from db_sysarqmod where codmod = 38 and codarq = 1010933;
delete from db_sysarquivo where codarq = 1010933;
delete from db_syscampo where codcam = 1014158;
SQL
        );
    }

    private function downEstrutura()
    {
        DB::connection()->getPdo()->exec(<<<SQL
select configuracoes.fc_auditoria_remove_funcao('empenho.emppreautorizacaounidade');
select configuracoes.fc_auditoria_remove_funcao('empenho.andamentoemppreautorizacaostatus');
select configuracoes.fc_auditoria_remove_funcao('empenho.andamentoemppreautorizacao');
select configuracoes.fc_auditoria_remove_funcao('empenho.empautorizacaoautorizada');

DROP TABLE IF EXISTS empenho.emppreautorizacaounidade;
DROP TABLE IF EXISTS empenho.andamentoemppreautorizacao;
DROP TABLE IF EXISTS empenho.andamentoemppreautorizacaostatus;
DROP TABLE IF EXISTS empenho.empautorizacaoautorizada;
SQL
        );
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
}
