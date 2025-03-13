<?php

use Classes\PostgresMigration;

class M15200IndicesConplanoatributo extends PostgresMigration
{

    public function up()
    {
        $sql = <<<SQL

drop index if exists conplanoatributos_infocomplementar_in;
drop index if exists conplanoatributos_anousu_in          ;
drop index if exists conplanoatributos_conplano_in        ;
drop index if exists conplanoatributos_conplanosistema_in ;

create index conplanoatributos_infocomplementar_in ON conplanoatributos (c120_infocomplementar);
create index conplanoatributos_anousu_in           ON conplanoatributos (c120_anousu);
create index conplanoatributos_conplano_in         ON conplanoatributos (c120_conplano);
create index conplanoatributos_conplanosistema_in  ON conplanoatributos (c120_conplanosistema);

SQL;

        $this->execute($sql);

    }
    public function down()
    {
        $sql = <<<SQL
drop index if exists conplanoatributos_infocomplementar_in;
drop index if exists conplanoatributos_anousu_in          ;
drop index if exists conplanoatributos_conplano_in        ;
drop index if exists conplanoatributos_conplanosistema_in ;
SQL;

        $this->execute($sql);

    }
}
