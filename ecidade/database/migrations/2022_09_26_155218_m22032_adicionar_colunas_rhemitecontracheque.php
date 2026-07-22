<?php

use Illuminate\Database\Migrations\Migration;

class M22032AdicionarColunasRhemitecontracheque extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::connection()->getPdo()->exec(<<<SQL
insert into db_syscampo values(1014499,'rh85_numero','int4','Numero referente ao contra cheque do mês, complementar 2 ou complementar 3 por exemplo','0', 'Numero',10,'t','f','f',1,'text','Numero');
insert into db_syscampo values(1014500,'rh85_instit','int4','Instituição de emissão do contra cheque','0', 'Instituição',10,'t','f','f',1,'text','Instituição');
insert into db_syscampo values(1014501,'rh85_estorage','int4','Código identificador do arquivo no e-storage','0', 'Código arquivo',10,'t','f','f',1,'text','Código arquivo');
insert into db_syscampo values(1014502,'rh85_tipofolha','int4','Vinculo com rhtipofolha','0', 'Tipo da Folha',11,'t','f','f',1,'text','Tipo da Folha');

insert into db_sysarqcamp values(2563,1014499,14,0);
insert into db_sysarqcamp values(2563,1014500,15,0);
insert into db_sysarqcamp values(2563,1014501,16,0);
insert into db_sysarqcamp values(2563,1014502,17,0);
insert into db_sysforkey values(2563,1014502,1,3728,0);

alter table rhemitecontracheque add column rh85_tipofolha int;
alter table rhemitecontracheque add column rh85_numero int default 0;
alter table rhemitecontracheque add column rh85_instit int;
alter table rhemitecontracheque add column rh85_estorage int;
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
        DB::connection()->getPdo()->exec(<<<SQL
delete from db_sysforkey where codcam = 1014502;
delete from db_sysarqcamp where codcam in (1014499, 1014500, 1014501, 1014502);
delete from db_syscampo where codcam in (1014499, 1014500, 1014501, 1014502);

alter table rhemitecontracheque drop column rh85_tipofolha;
alter table rhemitecontracheque drop column rh85_numero;
alter table rhemitecontracheque drop column rh85_instit;
alter table rhemitecontracheque drop column rh85_estorage;
SQL
        );
    }
}
