<?php

use Classes\PostgresMigration;

class M11184PrevisaoDespesa extends PostgresMigration
{
    public function up()
    {
        $this->inserirMenus();
        $this->dicionario();
        $this->estrutura();
    }

    public function down()
    {
        $this->deletarMenus();
        $this->execute("
            delete from db_syssequencia where codsequencia = 1000745;
            delete from db_sysprikey where codarq = 1010295;
            delete from db_sysarqcamp where codarq = 1010295;
            delete from db_syscampo where codcam in (1009818, 1009819, 1009820, 1009821, 1009822, 1009823, 1009824, 1009825, 1009826, 1009827, 1009828, 1009829, 1009830, 1009831, 1009832, 1009833, 1009834, 1009835, 1009836, 1009837);
            delete from db_sysarqmod where codarq = 1010295;
            delete from db_sysarquivo where codarq = 1010295;
        ");
        $this->execute("
            DROP TABLE IF EXISTS previsaodespesa CASCADE;
            DROP SEQUENCE IF EXISTS previsaodespesa_c333_sequencial_seq;
        ");
    }

    private function inserirMenus()
    {
        $this->inserirMenu();
        $this->inserirMenuInclusao();
        $this->inserirMenuAlteracao();
        $this->inserirMenuRelatorio();
    }

    private function inserirMenuInclusao()
    {
        $sql = "
            INSERT INTO db_itensmenu (id_item, descricao, help, funcao, manutencao, desctec)
            VALUES (10549, 'Inclusão', 'Inclusão', 'con1_previsao_despesa.php', '1', 'Inclusão');
            
            INSERT INTO db_menu (id_item, id_item_filho, menusequencia, modulo) VALUES (10547, 10549, 1, 209);
        ";

        $this->execute($sql);
    }

    private function inserirMenuAlteracao()
    {
        $sql = "
            INSERT INTO db_itensmenu (id_item, descricao, help, funcao, manutencao, desctec)
            VALUES (10550, 'Alteração', 'Alteração', 'con2_previsao_despesa.php', '1', 'Alteração');
            
            INSERT INTO db_menu (id_item, id_item_filho, menusequencia, modulo) VALUES (10547, 10550, 2, 209);
        ";

        $this->execute($sql);
    }

    private function inserirMenuRelatorio()
    {
        $sql = "
            INSERT INTO db_itensmenu (id_item, descricao, help, funcao, manutencao, desctec)
            VALUES (
              10548,
              'Conferência da Previsão da Despesa LOA 2019',
              'Conferência da Previsão da Despesa LOA 2019',
              'orc1_previsao_despesa.php',
              '1',
              'Conferência da Previsão da Despesa LOA 2019'
            );
            
            INSERT INTO db_menu (id_item, id_item_filho, menusequencia, modulo) VALUES (30, 10548, 474, 116);
        ";

        $this->execute($sql);
    }

    private function inserirMenu()
    {
        $sql = "
            INSERT INTO db_itensmenu (id_item, descricao, help, funcao, manutencao, desctec)
            VALUES (10547, 'Previsão de Despesa', 'Previsão de Despesa', '', '1', 'Previsão de Despesa');
            
            INSERT INTO db_menu (id_item, id_item_filho, menusequencia, modulo) VALUES (29, 10547, 281, 209);
        ";

        $this->execute($sql);
    }

    private function deletarMenus()
    {
        $sql = "
            DELETE FROM db_menu
            WHERE id_item_filho IN (10547, 10548, 10549, 10550);
            
            DELETE FROM db_itensmenu
            WHERE id_item IN (10547, 10548, 10549, 10550);
        ";

        $this->execute($sql);
    }

    private function dicionario()
    {
        $this->execute("
            insert into db_sysarquivo values (1010295, 'previsaodespesa', 'LOA 2019 previsão da despesa', 'c05', '2018-07-23', 'previsaodespesa', 0, 'f', 'f', 'f', 'f' );
            insert into db_sysarqmod values (32,1010295);
            insert into db_syscampo values(1009818,'c333_sequencial','int4','Código','0', 'Código',10,'f','f','f',1,'text','Código');
            insert into db_syscampo values(1009819,'c333_ano','int4','Ano','0', 'Ano',10,'f','f','f',1,'text','Ano');
            insert into db_syscampo values(1009820,'c333_esferaorcamentaria','int4','Esfera orçamentária','', 'Esfera orçamentária',10,'f','f','f',1,'text','Esfera orçamentária');
            insert into db_syscampo values(1009821,'c333_orcorgao','int4','Orgão','0', 'Orgão',10,'f','f','f',1,'text','Orgão');
            insert into db_syscampo values(1009822,'c333_orcunidade','int4','Unidade','0', 'Unidade',10,'f','f','f',1,'text','Unidade');
            insert into db_syscampo values(1009823,'c333_orcfuncao','int4','Função','0', 'Função',10,'f','f','f',1,'text','Função');
            insert into db_syscampo values(1009824,'c333_orcsubfuncao','int4','Subfunção','0', 'Subfunção',10,'f','f','f',1,'text','Subfunção');
            insert into db_syscampo values(1009825,'c333_orcprojativ','int4','Ação','0', 'Ação',10,'f','f','f',1,'text','Ação');
            insert into db_syscampo values(1009826,'c333_ppasubtitulolocalizadorgasto','int4','Subtítulo','0', 'Subtítulo',10,'f','f','f',1,'text','Subtítulo');
            insert into db_syscampo values(1009827,'c333_orcprograma','int4','Programa','0', 'Programa',10,'f','f','f',1,'text','Programa');
            insert into db_syscampo values(1009828,'c333_conplanoorcamento','int4','Natureza da Despesa','0', 'Natureza da Despesa',10,'f','f','f',1,'text','Natureza da Despesa');
            insert into db_syscampo values(1009829,'c333_identificadoruso','int4','Identificador de Uso','0', 'Identificador de Uso',10,'f','f','f',1,'text','Identificador de Uso');
            insert into db_syscampo values(1009830,'c333_tipodetalhamento','varchar(10)','Tipo de Detalhamento','', 'Tipo de Detalhamento',10,'f','t','f',0,'text','Tipo de Detalhamento');
            insert into db_syscampo values(1009831,'c333_grupofonterecursos','varchar(10)','Grupo da fonte de recursos','', 'Grupo da fonte de recursos',10,'f','f','f',0,'text','Grupo da fonte de recursos');
            insert into db_syscampo values(1009832,'c333_especificacaofonte','varchar(10)','Especificação da Fonte','', 'Especificação da Fonte',10,'f','f','f',0,'text','Especificação da Fonte');
            insert into db_syscampo values(1009833,'c333_identificadorresultadoprimario','varchar(10)','Identificador de Resultado Primário','', 'Identificador de Resultado Primário',10,'f','f','f',0,'text','Identificador de Resultado Primário');
            insert into db_syscampo values(1009834,'c333_real','float8','Real','0', 'Real',10,'f','f','f',4,'text','Real');
            insert into db_syscampo values(1009835,'c333_provavel','float8','Provável','0', 'Provável',10,'f','f','f',4,'text','Provável');
            insert into db_syscampo values(1009836,'c333_previsao','float8','Previsão','0', 'Previsão',10,'f','f','f',4,'text','Previsão');
            insert into db_syscampo values(1009837,'c333_planoorcamentario','text','Plano Orçamentáario','', 'Plano Orçamentáario',1,'f','f','f',0,'text','Plano Orçamentáario');
            
            insert into db_sysarqcamp values(1010295,1009818,1,0);
            insert into db_sysarqcamp values(1010295,1009819,2,0);
            insert into db_sysarqcamp values(1010295,1009820,3,0);
            insert into db_sysarqcamp values(1010295,1009821,4,0);
            insert into db_sysarqcamp values(1010295,1009822,5,0);
            insert into db_sysarqcamp values(1010295,1009823,6,0);
            insert into db_sysarqcamp values(1010295,1009824,7,0);
            insert into db_sysarqcamp values(1010295,1009827,8,0);
            insert into db_sysarqcamp values(1010295,1009825,9,0);
            insert into db_sysarqcamp values(1010295,1009826,10,0);
            insert into db_sysarqcamp values(1010295,1009828,11,0);
            insert into db_sysarqcamp values(1010295,1009829,12,0);
            insert into db_sysarqcamp values(1010295,1009830,13,0);
            insert into db_sysarqcamp values(1010295,1009831,14,0);
            insert into db_sysarqcamp values(1010295,1009832,15,0);
            insert into db_sysarqcamp values(1010295,1009833,16,0);
            insert into db_sysarqcamp values(1010295,1009834,17,0);
            insert into db_sysarqcamp values(1010295,1009835,18,0);
            insert into db_sysarqcamp values(1010295,1009836,19,0);
            insert into db_sysarqcamp values(1010295,1009837,20,0);
            insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010295,1009818,1,1009818);
            insert into db_syssequencia values(1000745, 'previsaodespesa_c333_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
            update db_sysarqcamp set codsequencia = 1000745 where codarq = 1010295 and codcam = 1009818;
        "
        );
    }


    private function estrutura()
    {
        $this->execute("
        create sequence previsaodespesa_c333_sequencial_seq increment 1 minvalue 1 maxvalue 9223372036854775807 start 1 cache 1;
        
        create table previsaodespesa(
        c333_sequencial int4 not null default 0,
        c333_ano int4 not null default 0,
        c333_esferaorcamentaria int not null ,
        c333_orcorgao int4 not null default 0,
        c333_orcunidade int4 not null default 0,
        c333_orcfuncao int4 not null default 0,
        c333_orcsubfuncao int4 not null default 0,
        c333_orcprograma int4 not null default 0,
        c333_orcprojativ int4 not null default 0,
        c333_ppasubtitulolocalizadorgasto int4 not null default 0,
        c333_conplanoorcamento int4 not null default 0,
        c333_identificadoruso int4 not null default 0,
        c333_tipodetalhamento varchar(10) not null ,
        c333_grupofonterecursos varchar(10) not null ,
        c333_especificacaofonte varchar(10) not null ,
        c333_identificadorresultadoprimario varchar(10) not null ,
        c333_real numeric(15, 2) not null default 0,
        c333_provavel numeric(15, 2) not null default 0,
        c333_previsao numeric(15, 2) not null default 0,
        c333_planoorcamentario text ,
        constraint previsaodespesa_sequ_pk primary key (c333_sequencial));
        ");
    }

}
