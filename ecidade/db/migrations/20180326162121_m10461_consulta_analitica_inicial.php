<?php

use Classes\PostgresMigration;

class M10461ConsultaAnaliticaInicial extends PostgresMigration
{
    public function up()
    {
        $sql  = " insert into db_syscampo values(1009673,'v19_consultaanaliticainicial','bool','Consulta analítica de débitos de Inicial do Foro na Consulta Geral Financeira','f', 'Consulta analítica de Inicial do Foro',1,'f','f','f',5,'text','Consulta analítica de Inicial do Foro'); ";
        $sql .= " insert into db_sysarqcamp values(2029,1009673,14,0); ";

        $sql .= " alter table parjuridico add column v19_consultaanaliticainicial boolean default false; ";

        $this->execute($sql);
    }

    public function down()
    {
        $sql  = "delete from db_sysarqcamp where codarq = 2029 and codcam = 1009673;";
        $sql .= "delete from db_syscampo where codcam = 1009673;";

        $sql .= " alter table parjuridico drop column v19_consultaanaliticainicial; ";

        $this->execute($sql);
    }
}
