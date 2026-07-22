<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M21482 extends Migration
{
    
    public function upDicionario()
    {
        $sql = <<<SQL

insert into configuracoes.db_sysarquivo values (1010975, 'slipdepartamento', 'Armazena o departamento da sessão no momento de que o slip é cadastrado', 'k211', '2022-07-26', 'Departamento do Slip', 0, 'f', 't', 'f', 't' );
insert into configuracoes.db_sysarqmod values (5,1010975);

insert into configuracoes.db_syscampo values(1014409,'k211_slip','int4','Slip','0', 'Slip',10,'f','f','f',1,'text','Slip');
insert into configuracoes.db_syscampo values(1014410,'k211_depart','int4','Departamento','0', 'Departamento',10,'f','f','f',1,'text','Departamento');

insert into configuracoes.db_sysarqcamp values(1010975,1014409,1,0);
insert into configuracoes.db_sysarqcamp values(1010975,1014410,2,0);

insert into configuracoes.db_sysprikey (codarq,codcam,sequen,camiden) values(1010975,1014409,1,1014409);
insert into configuracoes.db_sysforkey values(1010975,1014409,1,196,0);
insert into configuracoes.db_sysforkey values(1010975,1014410,1,154,0);

insert into configuracoes.db_sysindices values(1008803,'slipdepartamento_k211_depart_in',1010975,'0');
insert into configuracoes.db_syscadind values(1008803,1014410,1);

insert into db_itensmenu (id_item,descricao,help,funcao,itemativo,manutencao,desctec,libcliente) 
                  values (228726, 'Manutenção vinculo Slip/Departamento','Manutenção vinculo Slip/Departamento','cai4_manutencaoslipdepartamento001.php','1','1','Manutenção vinculo Slip/Departamento','t');


insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 9372 ,228726 ,9 ,39 );
SQL;
        DB::connection()->getPdo()->exec($sql);
    }
    
    public function downDicionario()
    {
        $sql = <<<SQL

delete from configuracoes.db_syscadind  where codind = 1008803;
delete from configuracoes.db_sysindices where codind = 1008803;

delete from configuracoes.db_sysprikey where codarq = 1010975;
delete from configuracoes.db_sysforkey where codarq = 1010975;

delete from configuracoes.db_sysarqcamp where codarq = 1010975;
delete from configuracoes.db_syscampo where codcam in (1014409,1014410);

delete from configuracoes.db_sysarqmod where codarq = 1010975;
delete from configuracoes.db_sysarquivo where codarq = 1010975;

delete from db_menu where id_item_filho = 228726;
delete from db_itensmenu where id_item = 228726; 

SQL;
        DB::connection()->getPdo()->exec($sql);
    }
    
    public function upEstrutura()
    {
        $sql = <<<SQL
create table caixa.slipdepartamento (k211_slip int,
                                     k211_depart int not null);
alter table caixa.slipdepartamento add constraint slipdepartamento_pk primary key  (k211_slip) ;
alter table caixa.slipdepartamento add constraint slipdepartamento_k211_slip_fk FOREIGN KEY (k211_slip) REFERENCES caixa.slip(k17_codigo);
alter table caixa.slipdepartamento add constraint slipdepartamento_k211_depart_fk FOREIGN KEY (k211_depart) REFERENCES configuracoes.db_depart(coddepto);

create index slipdepartamento_k211_depart_in on caixa.slipdepartamento(k211_depart);
 
SQL;
        DB::connection()->getPdo()->exec($sql);
    }
    
    public function downEstrutura()
    {
        $sql = <<<SQL
drop table caixa.slipdepartamento;
SQL;
        DB::connection()->getPdo()->exec($sql);
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
