<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M20228AdicionarCamposGeradorRelatorioPessoal extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sql = <<<SQL
select setval('relrubcampos_rh120_sequencial_seq', (select max(rh120_sequencial) from relrubcampos));

create temp table w_relrubrelrubcampos as
select relrubrelrubcampos.*,
       rh120_campo
  from pessoal.relrubrelrubcampos
       inner join pessoal.relrubcampos on relrubcampos.rh120_sequencial = relrubrelrubcampos.rh121_relrubcampos
 where trim(rh120_campo) in ('rh02_validadepensao', 'rh04_descr', 'rh20_cargo', 'rh02_tbprev', 'z01_telcel', 'z01_mae', 
'z01_pai', 'z01_telef','z01_email', 'rh02_datalaudomolestia', 'rh150_descricao', 'rh02_tipodeficiencia', 
'rh02_diasgozoferias', 'rh02_salari','rh21_regpri', 'rh88_descricao', 'rh02_vincrais', 'rh02_ocorre');
        
delete from pessoal.relrubrelrubcampos using w_relrubrelrubcampos where relrubrelrubcampos.rh121_sequencial = w_relrubrelrubcampos.rh121_sequencial;

delete from pessoal.relrubcampos where trim(rh120_campo) in ('rh02_validadepensao', 'rh04_descr', 'rh20_cargo', 'rh02_tbprev', 'z01_telcel', 
'z01_mae', 'z01_pai','z01_telef', 'z01_email', 'rh02_datalaudomolestia', 'rh150_descricao', 'rh02_tipodeficiencia','rh02_diasgozoferias', 
'rh02_salari', 'rh21_regpri', 'rh88_descricao', 'rh02_vincrais', 'rh02_ocorre');
        
insert into pessoal.relrubcampos values (nextval('relrubcampos_rh120_sequencial_seq'),'rh02_validadepensao',24,20);
insert into pessoal.relrubcampos values (nextval('relrubcampos_rh120_sequencial_seq'),'rh04_descr',30, 40);
insert into pessoal.relrubcampos values (nextval('relrubcampos_rh120_sequencial_seq'),'rh20_cargo',8, 10);
insert into pessoal.relrubcampos values (nextval('relrubcampos_rh120_sequencial_seq'),'rh02_tbprev',8, 10);
insert into pessoal.relrubcampos values (nextval('relrubcampos_rh120_sequencial_seq'),'z01_telcel',15, 20);
insert into pessoal.relrubcampos values (nextval('relrubcampos_rh120_sequencial_seq'),'z01_mae',40, 40);
insert into pessoal.relrubcampos values (nextval('relrubcampos_rh120_sequencial_seq'),'z01_pai',40, 40);
insert into pessoal.relrubcampos values (nextval('relrubcampos_rh120_sequencial_seq'),'z01_telef',15, 20);
insert into pessoal.relrubcampos values (nextval('relrubcampos_rh120_sequencial_seq'),'z01_email',35, 40);
insert into pessoal.relrubcampos values (nextval('relrubcampos_rh120_sequencial_seq'),'rh02_datalaudomolestia',20, 20);
insert into pessoal.relrubcampos values (nextval('relrubcampos_rh120_sequencial_seq'),'rh150_descricao',40, 40);
insert into pessoal.relrubcampos values (nextval('relrubcampos_rh120_sequencial_seq'),'rh02_tipodeficiencia',10, 10);
insert into pessoal.relrubcampos values (nextval('relrubcampos_rh120_sequencial_seq'),'rh02_diasgozoferias',20, 20);
insert into pessoal.relrubcampos values (nextval('relrubcampos_rh120_sequencial_seq'),'rh02_salari',12, 12);
insert into pessoal.relrubcampos values (nextval('relrubcampos_rh120_sequencial_seq'),'rh21_regpri',20, 10);
insert into pessoal.relrubcampos values (nextval('relrubcampos_rh120_sequencial_seq'),'rh88_descricao',35, 35);
insert into pessoal.relrubcampos values (nextval('relrubcampos_rh120_sequencial_seq'),'rh02_vincrais',12, 35);
insert into pessoal.relrubcampos values (nextval('relrubcampos_rh120_sequencial_seq'),'rh02_ocorre',24, 35);


insert into pessoal.relrubrelrubcampos select rh121_sequencial, 
                                      rh121_instit, 
                                      rh121_relrub, 
                                      (select rh120_sequencial from pessoal.relrubcampos where trim(rh120_campo) = trim(w_relrubrelrubcampos.rh120_campo) limit 1) as id_novo_campo, 
                                      rh121_ordem
                                 from w_relrubrelrubcampos;
SQL;
        DB::connection()->getPdo()->exec($sql);
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       //
        $sql = <<<SQL
delete from pessoal.relrubrelrubcampos
      using pessoal.relrubcampos 
      where relrubrelrubcampos.rh121_relrubcampos = relrubcampos.rh120_sequencial 
        and trim(rh120_campo) in ('rh02_validadepensao', 'rh04_descr', 'rh20_cargo', 'rh02_tbprev', 'z01_telcel', 'z01_mae', 'z01_pai','z01_telef', 
'z01_email', 'rh02_datalaudomolestia', 'rh150_descricao', 'rh02_tipodeficiencia','rh02_diasgozoferias', 'rh02_salari', 'rh21_regpri', 'rh88_descricao', 
'rh02_vincrais', 'rh02_ocorre');

delete from pessoal.relrubcampos where trim(rh120_campo) in ('rh02_validadepensao', 'rh04_descr', 'rh20_cargo', 'rh02_tbprev', 'z01_telcel', 'z01_mae', 'z01_pai','z01_telef', 
'z01_email', 'rh02_datalaudomolestia', 'rh150_descricao', 'rh02_tipodeficiencia','rh02_diasgozoferias', 'rh02_salari', 'rh21_regpri', 'rh88_descricao' , 
'rh02_vincrais', 'rh02_ocorre');
SQL;
        DB::connection()->getPdo()->exec($sql);
        
    }
}
