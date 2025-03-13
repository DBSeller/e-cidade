<?php

use Classes\PostgresMigration;

class M18827VinculoLayoutFormularioEsocial extends PostgresMigration
{
    public function up()
    {
        $sql = <<<SQL
            insert into recursoshumanos.esocialversaoformulario values ((select  nextval('esocialversaoformulario_rh211_sequencial_seq')), 'S1.0', 3000017, 5);
            insert into recursoshumanos.esocialversaoformulario values ((select  nextval('esocialversaoformulario_rh211_sequencial_seq')), 'S1.0', 3000018, 6);
            insert into recursoshumanos.esocialversaoformulario values ((select  nextval('esocialversaoformulario_rh211_sequencial_seq')), 'S1.0', 3000019, 7);
            insert into recursoshumanos.esocialversaoformulario values ((select  nextval('esocialversaoformulario_rh211_sequencial_seq')), 'S1.0', 3000014, 4);
            insert into recursoshumanos.esocialversaoformulario values ((select  nextval('esocialversaoformulario_rh211_sequencial_seq')), 'S1.0', 3000013, 3);
            insert into recursoshumanos.esocialversaoformulario values ((select  nextval('esocialversaoformulario_rh211_sequencial_seq')), 'S1.0', 3000016, 2);
            insert into recursoshumanos.esocialversaoformulario values ((select  nextval('esocialversaoformulario_rh211_sequencial_seq')), 'S1.0', 3000020, 8);
            insert into recursoshumanos.esocialversaoformulario values ((select  nextval('esocialversaoformulario_rh211_sequencial_seq')), 'S1.0', 3000023, 12);
            insert into recursoshumanos.esocialversaoformulario values ((select  nextval('esocialversaoformulario_rh211_sequencial_seq')), 'S1.0', 3000025, 13);
            insert into recursoshumanos.esocialversaoformulario values ((select  nextval('esocialversaoformulario_rh211_sequencial_seq')), 'S1.0', 3000027, 15);
            insert into recursoshumanos.esocialversaoformulario values ((select  nextval('esocialversaoformulario_rh211_sequencial_seq')), 'S1.0', 3000021, 9);
            insert into recursoshumanos.esocialversaoformulario values ((select  nextval('esocialversaoformulario_rh211_sequencial_seq')), 'S1.0', 3000022, 11);
            insert into recursoshumanos.esocialversaoformulario values ((select  nextval('esocialversaoformulario_rh211_sequencial_seq')), 'S1.0', 3000030, 18);
            insert into recursoshumanos.esocialversaoformulario values ((select  nextval('esocialversaoformulario_rh211_sequencial_seq')), 'S1.0', 3000029, 16);
            insert into recursoshumanos.esocialversaoformulario values ((select  nextval('esocialversaoformulario_rh211_sequencial_seq')), 'S1.0', 3000028, 17);
            insert into recursoshumanos.esocialversaoformulario values ((select  nextval('esocialversaoformulario_rh211_sequencial_seq')), 'S1.0', 3000031, 19);
            insert into recursoshumanos.esocialversaoformulario values ((select  nextval('esocialversaoformulario_rh211_sequencial_seq')), 'S1.0', 3000032, 20);
            insert into recursoshumanos.esocialversaoformulario values ((select  nextval('esocialversaoformulario_rh211_sequencial_seq')), 'S1.0', 3000026, 14);
            insert into recursoshumanos.esocialversaoformulario values ((select  nextval('esocialversaoformulario_rh211_sequencial_seq')), 'S1.0', 3000033, 21);
            insert into recursoshumanos.esocialversaoformulario values ((select  nextval('esocialversaoformulario_rh211_sequencial_seq')), 'S1.0', 3000043, 31);
            insert into recursoshumanos.esocialversaoformulario values ((select  nextval('esocialversaoformulario_rh211_sequencial_seq')), 'S1.0', 3000036, 24);
            insert into recursoshumanos.esocialversaoformulario values ((select  nextval('esocialversaoformulario_rh211_sequencial_seq')), 'S1.0', 3000041, 28);
            insert into recursoshumanos.esocialversaoformulario values ((select  nextval('esocialversaoformulario_rh211_sequencial_seq')), 'S1.0', 3000044, 34);
            insert into recursoshumanos.esocialversaoformulario values ((select  nextval('esocialversaoformulario_rh211_sequencial_seq')), 'S1.0', 4000103, 35);
SQL;
        $this->execute($sql);
    }

    public function down()
    {
        $this->execute("delete from recursoshumanos.esocialversaoformulario where rh211_versao = 'S1.0' and rh211_avaliacao != 3000015;");
    }
}
