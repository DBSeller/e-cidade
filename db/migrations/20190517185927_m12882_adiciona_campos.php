<?php

use Classes\PostgresMigration;

class M12882AdicionaCampos extends PostgresMigration
{
    public function up()
    {
        $this->execute(<<<SQL
            -- Dicionario de dados
            insert into db_syscampo values(1010477,'db21_unidade_gestora_rpps','bool','Informar se o órgão Público é uma Unidade Gestora do Regime Próprio de Previdência Social - RPPS','f', 'Unidade Gestora do RPPS',1,'f','f','f',5,'text','Unidade Gestora do RPPS');
            insert into db_syscampodef values(1010477,'f','');
            insert into db_sysarqcamp values(83,1010477,48,0);

            insert into db_syscampo values(1010478,'db21_esfera_op','int4','Preencher com a esfera administrativa do órgão Público','0', 'Esfera administrativa do órgão público',8,'t','f','f',1,'text','Esfera administrativa do órgão público');
            insert into db_sysarqcamp values(83,1010478,53,0);

            insert into db_syscampo values(1010479,'db21_valor_teto_remuneratorio','float8','Informar o valor do teto remuneratório específico','0', 'Valor do teto remuneratório específico',15,'t','f','f',4,'text','Valor do teto remuneratório específico');
            insert into db_sysarqcamp values(83,1010479,52,0);

            insert into db_syscampo values(1010482,'db21_ente_federativo_resp','bool','Informar se o órgão Público é o Ente Federativo Responsável - EFR ou se é uma unidade administrativa autônoma vinculada a um EFR;','f', 'Órgão Público é o EFR',1,'f','f','f',5,'text','Órgão Público é o EFR');
            insert into db_syscampodef values(1010482,'f','');
            insert into db_sysarqcamp values(83,1010482,51,0);

            insert into db_syscampo values(1010484,'db21_cnpj_efr','varchar(20)','Informar o CNPJ do Ente Federativo Responsável - EFR','', 'CNPJ do Ente Federativo Responsável',20,'t','f','f',0,'text','CNPJ do Ente Federativo Responsável');
            insert into db_sysarqcamp values(83,1010484,50,0);

            insert into db_syscampo values(1010485,'db21_efr_previdencia_compl','bool','Informar se o EFR instituiu regime de previdência complementar','f', 'EFR instituiu previdência complementar',1,'f','f','f',5,'text','EFR instituiu previdência complementar');
            insert into db_syscampodef values(1010485,'f','');
            insert into db_sysarqcamp values(83,1010485,49,0);

            insert into db_syscampo values(1010486,'db21_possui_rpps','bool','Informar se o ente público possui Regime Próprio de Previdência Social','f', 'Regime Próprio de Previdência Social',1,'f','f','f',5,'text','Regime Próprio de Previdência Social');
            insert into db_syscampodef values(1010486,'f','');
            insert into db_sysarqcamp values(83,1010486,54,0);

            alter table db_config add db21_unidade_gestora_rpps bool default 'f' not null;
            alter table db_config add db21_esfera_op int4 default null;
            alter table db_config add db21_valor_teto_remuneratorio float8 default null;
            alter table db_config add db21_ente_federativo_resp bool default 'f' not null;
            alter table db_config add db21_cnpj_efr varchar(20) default null;
            alter table db_config add db21_efr_previdencia_compl bool default 'f' not null;
            alter table db_config add db21_possui_rpps bool default 'f' not null;
SQL
        );
    }

    public function down()
    {
        $this->execute(<<<SQL
            alter table db_config drop column db21_unidade_gestora_rpps;
            alter table db_config drop column db21_esfera_op;
            alter table db_config drop column db21_valor_teto_remuneratorio;
            alter table db_config drop column db21_ente_federativo_resp;
            alter table db_config drop column db21_cnpj_efr;
            alter table db_config drop column db21_efr_previdencia_compl;
            alter table db_config drop column db21_possui_rpps;

            delete from db_sysarqcamp where codcam in (1010477, 1010478, 1010479, 1010482, 1010484, 1010485, 1010486);
            delete from db_syscampodef where codcam in (1010477, 1010478, 1010479, 1010482, 1010484, 1010485, 1010486);
            delete from db_syscampo where codcam in (1010477, 1010478, 1010479, 1010482, 1010484, 1010485, 1010486);
SQL
        );
    }
}
