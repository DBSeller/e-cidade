<?php

use Classes\PostgresMigration;

class M16504CamposAvaliadoSolicitadoExames extends PostgresMigration
{
  public function up()
  {

    $sql = <<<SQL

insert into db_syscampo values(1013332,'sd104_avaliado','bool','Avaliado','f', 'Avaliado',1,'f','f','f',5,'text','Avaliado');
insert into db_syscampo values(1013333,'sd104_solicitado','bool','Solicitado','f', 'Solicitado',1,'f','f','f',5,'text','Solicitado');
insert into db_sysarqcamp values(3776,1013332,4,0);
insert into db_sysarqcamp values(3776,1013333,5,0);

alter table ambulatorial.examerequisicaoexame add column sd104_solicitado bool default 't';
alter table ambulatorial.examerequisicaoexame add column sd104_avaliado bool default 'f';



SQL;

    $this->execute($sql);

  }




  public function down()
  {

    $sql = <<<SQL

delete from db_sysarqcamp where codcam in (1013332, 1013333);
delete from db_syscampo where codcam in (1013332, 1013333);

ALTER TABLE ambulatorial.examerequisicaoexame DROP COLUMN sd104_avaliado;
ALTER TABLE ambulatorial.examerequisicaoexame DROP COLUMN sd104_solicitado;

SQL;

    $this->execute($sql);

  }


}
