<?php

use Classes\PostgresMigration;

class M15704CriaCampoAtivoCensoativcompl extends PostgresMigration
{
    public function up()
    {
        $this->upEstrutura();
        $this->upDados();
    }

    public function down()
    {
        $this->downEstrutura();
        $this->downDados();
    }

    public function upEstrutura()
    {
        $this->execute("insert into db_syscampo values(1011268,'ed133_ativo','bool','campo para ativar/inativar atividades complementar do censo','true', 'ativo',1,'f','f','f',5,'text','ativo');
                            insert into db_sysarqcamp values(2353,1011268,4,0);

                            alter table censoativcompl add column ed133_ativo boolean default true;");
    }

    public function downEstrutura()
    {
        $this->execute("delete from db_sysarqcamp where codcam = 1011268;
                            delete from db_syscampo where codcam = 1011268;

                            alter table censoativcompl drop column ed133_ativo;");
    }

    public function upDados()
    {
        $this->execute("insert into censoativcompl
                                values  (15201, 152, 'Memória e História das Cultura Afro-Brasileira e Africana', true),
                                        (15301, 153, 'Memória e História das Culturas e Indígenas', true),
                                        (17101, 171, 'Educação para o Consumo Sustentável', true),
                                        (17102, 171, 'Economia Solidária e Criativa', true),
                                        (17201, 172, 'Educação Financeira', true),
                                        (17202, 172, 'Economia', true),
                                        (17301, 173, 'Controle social do gasto público', true),
                                        (17302, 173, 'Educação Tributária', true);

                            update censoativcompl set ed133_ativo = false where ed133_i_codigo = 13108;

                            update censoativcompl set ed133_ativo = false
                                 where ed133_i_codigo not in
                                       (11002, 11006, 11011, 12003, 12004, 12005, 12007, 13001, 14001, 14002, 14004, 15001, 15002, 15003, 15004, 16001,
                                        17004, 17002, 19999, 21001, 22007, 22009, 22011, 22012, 22014, 22015, 22018, 22019, 22020, 22021, 22022, 22023,
                                        22024, 22025, 22026, 22027, 22028, 22029, 22032, 29999, 31002, 31001, 39999, 41007, 71007, 10103, 13301, 13104,
                                        14101, 14102, 14103, 14104, 14105, 14201, 14202, 14203, 14999, 15101, 15201, 15301, 17101, 17102, 17201, 17202,
                                        17301, 17302);");
    }

    public function downDados()
    {
        $this->execute("delete from censoativcompl
                                where ed133_i_codigo in (15201, 15301, 17101, 17102, 17201, 17202, 17301, 17302);");
    }
}
