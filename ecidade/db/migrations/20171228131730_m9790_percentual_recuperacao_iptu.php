<?php

use Classes\PostgresMigration;

class M9790PercentualRecuperacaoIptu extends PostgresMigration
{
    public function up()
    {
        $sSqlDicionario  = "insert into db_sysarquivo values (1010250, 'iptupercentualexercicio', 'Percentual do Exercício', 'j145', '2017-12-28', 'Percentual do Exercício', 0, 'f', 'f', 'f', 'f' );";
        $sSqlDicionario .= "insert into db_sysarqmod values (2,1010250);";
        $sSqlDicionario .= "insert into db_syscampo values(1009589,'j145_anousu','int4','Exercício','0', 'Exercício',4,'f','f','f',1,'text','Exercício');";
        $sSqlDicionario .= "insert into db_syscampo values(1009590,'j145_sequencial','int4','Código Sequencial','0', 'Código Sequencial',10,'f','f','f',1,'text','Código Sequencial');";
        $sSqlDicionario .= "insert into db_syscampo values(1009591,'j145_valor','float4','Valor','0', 'Valor',15,'f','f','f',4,'text','Valor');";
        $sSqlDicionario .= "delete from db_sysarqcamp where codarq = 1010250;";
        $sSqlDicionario .= "insert into db_sysarqcamp values(1010250,1009590,1,0);";
        $sSqlDicionario .= "insert into db_sysarqcamp values(1010250,1009589,2,0);";
        $sSqlDicionario .= "insert into db_sysarqcamp values(1010250,1009591,3,0);";
        $sSqlDicionario .= "delete from db_sysprikey where codarq = 1010250;";
        $sSqlDicionario .= "insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010250,1009590,1,1009590);";
        $sSqlDicionario .= "insert into db_sysindices values(1008245,'iptupercentualexercicio_j145_sequencial_in',1010250,'1');";
        $sSqlDicionario .= "insert into db_syscadind values(1008245,1009590,1);";
        $sSqlDicionario .= "insert into db_syssequencia values(1000710, 'iptupercentualexercicio_j145_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);";
        $sSqlDicionario .= "update db_sysarqcamp set codsequencia = 1000710 where codarq = 1010250 and codcam = 1009590;";

        $this->execute($sSqlDicionario);

        $sSqlEstrutura  = "CREATE SEQUENCE cadastro.iptupercentualexercicio_j145_sequencial_seq ";
        $sSqlEstrutura .= "INCREMENT 1 ";
        $sSqlEstrutura .= "MINVALUE 1 ";
        $sSqlEstrutura .= "MAXVALUE 9223372036854775807 ";
        $sSqlEstrutura .= "START 1 ";
        $sSqlEstrutura .= "CACHE 1;";

        $sSqlEstrutura .= "CREATE TABLE cadastro.iptupercentualexercicio(";
        $sSqlEstrutura .= "      j145_sequencial		int4 NOT NULL default 0,";
        $sSqlEstrutura .= "      j145_anousu		int4 NOT NULL default 0,";
        $sSqlEstrutura .= "      j145_valor		float4 default 0,";
        $sSqlEstrutura .= "      CONSTRAINT iptupercentualexercicio_sequ_pk PRIMARY KEY (j145_sequencial));";

        $sSqlEstrutura .= "CREATE UNIQUE INDEX iptupercentualexercicio_j145_sequencial_in ON iptupercentualexercicio(j145_sequencial);";

        $this->execute($sSqlEstrutura);
        $this->execute("insert into iptucadlogcalc values (116, 'ANO DA CONSTRUÇÃO INVÁLIDO.', true)");

        $sSqlDicionarioFuncao  = " insert into db_sysfuncoes( codfuncao ,nomefuncao ,nomearquivo ,obsfuncao ,corpofuncao ,triggerfuncao ) values ( 186 ,'fc_calculoiptu_osorio_2018' ,'calculoiptu_osorio_2018.sql' ,'Cálculo de IPTU de Osório para 2018' ,'.' ,'0' ); ";
        $sSqlDicionarioFuncao .= " insert into db_sysfuncoesparam( db42_sysfuncoesparam ,db42_funcao ,db42_ordem ,db42_nome ,db42_tipo ,db42_tamanho ,db42_precisao ,db42_valor_default ,db42_descricao ) values ( 1001 ,186 ,1 ,'iMatricula' ,'int4' ,0 ,0 ,'0' ,'MATRICULA' ); ";
        $sSqlDicionarioFuncao .= " insert into db_sysfuncoesparam( db42_sysfuncoesparam ,db42_funcao ,db42_ordem ,db42_nome ,db42_tipo ,db42_tamanho ,db42_precisao ,db42_valor_default ,db42_descricao ) values ( 1002 ,186 ,2 ,'iAnousu' ,'int4' ,0 ,0 ,'0' ,'ANO DE CALCULO ' ); ";
        $sSqlDicionarioFuncao .= " insert into db_sysfuncoesparam( db42_sysfuncoesparam ,db42_funcao ,db42_ordem ,db42_nome ,db42_tipo ,db42_tamanho ,db42_precisao ,db42_valor_default ,db42_descricao ) values ( 1003 ,186 ,6 ,'lCalculogeral' ,'bool' ,0 ,0 ,'0' ,'SE CALCULO GERAL' ); ";
        $sSqlDicionarioFuncao .= " insert into db_sysfuncoesparam( db42_sysfuncoesparam ,db42_funcao ,db42_ordem ,db42_nome ,db42_tipo ,db42_tamanho ,db42_precisao ,db42_valor_default ,db42_descricao ) values ( 1004 ,186 ,7 ,'lDemo' ,'bool' ,0 ,0 ,'0' ,'SE E DEMOSNTRATIVO' ); ";
        $sSqlDicionarioFuncao .= " insert into db_sysfuncoesparam( db42_sysfuncoesparam ,db42_funcao ,db42_ordem ,db42_nome ,db42_tipo ,db42_tamanho ,db42_precisao ,db42_valor_default ,db42_descricao ) values ( 1005 ,186 ,8 ,'iParcelaini' ,'int4' ,0 ,0 ,'0' ,'PARCELA INICIAL' ); ";
        $sSqlDicionarioFuncao .= " insert into db_sysfuncoesparam( db42_sysfuncoesparam ,db42_funcao ,db42_ordem ,db42_nome ,db42_tipo ,db42_tamanho ,db42_precisao ,db42_valor_default ,db42_descricao ) values ( 1006 ,186 ,9 ,'iParcelafim' ,'int4' ,0 ,0 ,'0' ,'PARCELA FIM' ); ";
        $sSqlDicionarioFuncao .= " insert into db_sysfuncoesparam( db42_sysfuncoesparam ,db42_funcao ,db42_ordem ,db42_nome ,db42_tipo ,db42_tamanho ,db42_precisao ,db42_valor_default ,db42_descricao ) values ( 1007 ,186 ,3 ,'lGerafinanc' ,'bool' ,0 ,0 ,'0' ,'SE GERA FINANCEIRO' ); ";
        $sSqlDicionarioFuncao .= " insert into db_sysfuncoesparam( db42_sysfuncoesparam ,db42_funcao ,db42_ordem ,db42_nome ,db42_tipo ,db42_tamanho ,db42_precisao ,db42_valor_default ,db42_descricao ) values ( 1008 ,186 ,4 ,'lAtualizap' ,'bool' ,0 ,0 ,'0' ,'ATUALIZA PARCELAS' ); ";
        $sSqlDicionarioFuncao .= " insert into db_sysfuncoesparam( db42_sysfuncoesparam ,db42_funcao ,db42_ordem ,db42_nome ,db42_tipo ,db42_tamanho ,db42_precisao ,db42_valor_default ,db42_descricao ) values ( 1009 ,186 ,5 ,'lNovonumpre' ,'bool' ,0 ,0 ,'0' ,'NOVO NUMPRE' ); ";

        $this->execute($sSqlDicionarioFuncao);
    }

    public function down()
    {
        $sSqlDicionario  = "delete from db_syssequencia where codsequencia = 1000710;";
        $sSqlDicionario .= "delete from db_syscadind where codind = 1008245;";
        $sSqlDicionario .= "delete from db_sysindices where codind = 1008245;";
        $sSqlDicionario .= "delete from db_sysprikey where codarq = 1010250;";
        $sSqlDicionario .= "delete from db_sysarqcamp where codarq = 1010250;";
        $sSqlDicionario .= "delete from db_syscampo where codcam in (1009589, 1009590, 1009591);";
        $sSqlDicionario .= "delete from db_sysarqmod where codarq = 1010250;";
        $sSqlDicionario .= "delete from db_sysarquivo where codarq = 1010250;";

        $this->execute($sSqlDicionario);

        $sSqlEstruturaDROP  = "DROP SEQUENCE IF EXISTS iptupercentualexercicio_j145_sequencial_seq;";
        $sSqlEstruturaDROP .= "DROP TABLE IF EXISTS iptupercentualexercicio CASCADE;";

        $this->execute($sSqlEstruturaDROP);
        $this->execute("delete from iptucadlogcalc where j62_codigo = 116;");

        $sSqlDicionarioFuncao  = " delete from db_sysfuncoesparam where db42_funcao = 186; ";
        $sSqlDicionarioFuncao .= " delete from db_sysfuncoes where codfuncao = 186; ";

        $this->execute($sSqlDicionarioFuncao);
    }
}
