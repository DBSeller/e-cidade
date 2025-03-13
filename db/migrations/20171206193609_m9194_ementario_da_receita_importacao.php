<?php

use Classes\PostgresMigration;

class M9194EmentarioDaReceitaImportacao extends PostgresMigration
{

    public function up()
    {

        $this->execute("CREATE SEQUENCE planocontadetalhe_c95_sequencial_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1 CACHE 1;");
        $this->execute("select setval('planocontadetalhe_c95_sequencial_seq', (select max(c95_sequencial) from planocontadetalhe))");
        $this->execute("insert into modeloplanoconta values (1000, 'Ementário da Receita - TCE/RS', 2018);");
        $this->execute("insert into modeloplanoconta values (1001, 'Ementário da Receita - STN', 2018);");
        $this->dicionarioDadosUP();


        $tabela = $this->table('planocontadetalheconplanoorcamento', array('id'=> 'c97_sequencial', 'primary_key' => 'c97_sequencial'));
        $tabela->addColumn('c97_planocontadetalhe', 'integer');
        $tabela->addColumn('c97_conplanoorcamento', 'integer');
        $tabela->addForeignKey('c97_planocontadetalhe', 'planocontadetalhe', 'c95_sequencial');
        $tabela->save();

        $tabela = $this->table('orcamentoreceita', array('id' => 'c98_sequencial'));
        $tabela->addColumn('c98_codcon', 'integer');
        $tabela->addColumn('c98_anousu', 'integer');
        $tabela->addColumn('c98_estrutural', 'string', array('limit' => 15));
        $tabela->addColumn('c98_descricao', 'string', array('limit' => 50));
        $tabela->addColumn('c98_finalidade', 'text', array('null' => true));
        $tabela->addColumn('c98_codsis', 'integer');
        $tabela->addColumn('c98_codcla', 'integer');
        $tabela->addColumn('c98_consistemaconta', 'integer');
        $tabela->addColumn('c98_identificadorfinanceiro', 'char', array('limit' => 1));
        $tabela->addColumn('c98_naturezasaldo', 'integer');
        $tabela->addColumn('c98_funcao', 'text', array('null' => true));
        $tabela->addColumn('c98_codrec', 'integer', array('null' => true));
        $tabela->addColumn('c98_recurso', 'integer', array('null' => true));
        $tabela->addColumn('c98_valor', 'float', array('null' => true));
        $tabela->addColumn('c98_receitalancada', 'boolean', array('null' => true));
        $tabela->addColumn('c98_instit', 'integer', array('null' => true));
        $tabela->addColumn('c98_concarpeculiar', 'string', array('limit' => 3, 'null' => true));
        $tabela->addColumn('c98_datacriacao', 'date', array('null' => true));
        $tabela->save();

        $this->execute('alter table orcamentoreceita alter column c98_valor type numeric;');

        $this->migrarContasOrcamento();

    }

    public function down()
    {
        $tabela = $this->table('planocontadetalheconplanoorcamento');
        $tabela->drop();

        $tabela = $this->table('orcamentoreceita');
        $tabela->drop();

        $this->execute("delete from planocontadetalhe where c95_modeloplanoconta in (1000, 1001)");
        $this->execute("delete from modeloplanoconta where c94_sequencial in (1000, 1001)");

        $this->execute("delete from db_syssequencia where codsequencia in (1000705, 1000707)");
        $this->execute("delete from db_sysprikey where codarq in (1010245, 1010247)");
        $this->execute("delete from db_sysforkey where codarq in (1010245, 1010247)");
        $this->execute("delete from db_syscadind where codcam in (1009552, 1009553, 1009554, 1009558, 1009559, 1009560, 1009561, 1009562, 1009563, 1009564, 1009565, 1009566, 1009567, 1009568, 1009569, 1009570, 1009571, 1009572, 1009573, 1009574, 1009575, 1009576)");
        $this->execute("delete from db_sysindices where codarq in (1010245, 1010247)");
        $this->execute("delete from db_sysarqcamp where codarq in (1010245, 1010247)");
        $this->execute("delete from db_sysarqmod where  codarq in (1010245, 1010247)");
        $this->execute("delete from db_sysarquivo where codarq in (1010245, 1010247)");
        $this->execute("delete from db_syscampo where codcam in (1009552, 1009553, 1009554, 1009558, 1009559, 1009560, 1009561, 1009562, 1009563, 1009564, 1009565, 1009566, 1009567, 1009568, 1009569, 1009570, 1009571, 1009572, 1009573, 1009574, 1009575, 1009576)");

        $this->execute("delete from db_menu where id_item_filho in(10480, 10482)");
        $this->execute("delete from db_itensfilho where id_item = 10480");
        $this->execute("delete from db_itensmenu where id_item in(10480, 10482)");


    }


    private function dicionarioDadosUP()
    {
        $this->execute(
            <<<DICIONARIO_UP
            
insert into db_itensmenu values( 10480, 'Atualizar Ementário da Receita', 'Atualizar Ementário da Receita', 'con1_atualizacaoementarioreceita001.php', '1', '1', 'Atualizar Ementário da Receita', '1'	);
insert into db_itensfilho (id_item, codfilho) values(10480,1);
delete from db_menu where id_item_filho = 10480 AND modulo = 209;
insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 9072 ,10480 ,6 ,209 );

insert into db_itensmenu ( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 10482 ,'Vínculo Manual do Ementário da Receita' ,'Vínculo Manual do Ementário da Receita' ,'con1_vinculoementarioreceita001.php' ,'1' ,'1' ,'Vinculo Manual do Ementário da Receita' ,'true' );
delete from db_menu where id_item_filho = 10482 AND modulo = 209;
insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 9072 ,10482 ,7 ,209 );

insert into db_syscampo values(1009552,'c97_sequencial','int4','Código','0', 'Código',10,'f','f','f',1,'text','Código');
insert into db_syscampo values(1009553,'c97_planocontadetalhe','int4','Código do Ementário','0', 'Código do Ementário',10,'f','f','f',1,'text','Código do Ementário');
insert into db_syscampo values(1009554,'c97_conplanoorcamento','int4','Código da Conta no Orçamento','0', 'Código da Conta no Orçamento',10,'f','f','f',1,'text','Código da Conta no Orçamento');
insert into db_sysarquivo values (1010245, 'planocontadetalheconplanoorcamento', 'planocontadetalheconplanoorcamento', 'c97', '2017-12-07', 'planocontadetalheconplanoorcamento', 0, 'f', 'f', 'f', 'f' );
insert into db_sysarqmod values (32,1010245);
delete from db_sysarqcamp where codarq = 1010245;
insert into db_sysarqcamp values(1010245,1009552,1,0);
insert into db_sysarqcamp values(1010245,1009553,2,0);
insert into db_sysarqcamp values(1010245,1009554,3,0);
insert into db_sysindices values(1008239,'planocontadetalheconplanoorcamento_planocontadetalhe_in',1010245,'0');
insert into db_syscadind values(1008239,1009553,1);
delete from db_sysforkey where codarq = 1010245 and referen = 0;
insert into db_sysforkey values(1010245,1009553,1,3994,0);
delete from db_sysprikey where codarq = 1010245;
insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010245,1009552,1,1009553);
insert into db_syssequencia values(1000705, 'planocontadetalheconplanoorcamento_c97_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
update db_sysarqcamp set codsequencia = 1000705 where codarq = 1010245 and codcam = 1009552;

insert into db_syscampo values(1009558,'c98_sequencial','int4','Código','0', 'Código',10,'f','f','f',1,'text','Código');
insert into db_syscampo values(1009559,'c98_codcon','int4','Código da Conta no Orçamento','0', 'Código da Conta no Orçamento',10,'f','f','f',1,'text','Código da Conta no Orçamento');
insert into db_syscampo values(1009560,'c98_anousu','int4','Ano da Conta','0', 'Ano da Conta',4,'f','f','f',1,'text','Ano da Conta');
insert into db_syscampo values(1009561,'c98_estrutural','varchar(15)','Estrutural','', 'Estrutural',15,'f','t','f',0,'text','Estrutural');
insert into db_syscampo values(1009562,'c98_descricao','varchar(50)','Descrição','', 'Descrição',50,'f','t','f',0,'text','Descrição');
insert into db_syscampo values(1009563,'c98_finalidade','text','Finalidade','', 'Finalidade',1,'f','t','f',0,'text','Finalidade');
insert into db_syscampo values(1009564,'c98_codsis','int4','Código do Sistema','0', 'Código do Sistema',10,'f','f','f',1,'text','Código do Sistema');
insert into db_syscampo values(1009565,'c98_codcla','int4','Classificação','0', 'Classificação',10,'f','f','f',1,'text','Classificação');
insert into db_syscampo values(1009566,'c98_consistemaconta','int4','Código de Conta do Sistema','0', 'Código de Conta do Sistema',10,'f','f','f',1,'text','Código de Conta do Sistema');
insert into db_syscampo values(1009567,'c98_identificadorfinanceiro','char(1)','Identificador Financeiro','', 'Identificador Financeiro',1,'f','t','f',0,'text','Identificador Financeiro');
insert into db_syscampo values(1009568,'c98_naturezasaldo','int4','Natureza Saldo','0', 'Natureza Saldo',10,'f','f','f',1,'text','Natureza Saldo');
insert into db_syscampo values(1009569,'c98_funcao','text','Função','', 'Função',1,'f','t','f',0,'text','Função');
insert into db_syscampo values(1009570,'c98_codrec','int4','Código da Receita','0', 'Código da Receita',10,'f','f','f',1,'text','Código da Receita');
insert into db_syscampo values(1009571,'c98_recurso','int4','Recurso','0', 'Recurso',10,'f','f','f',1,'text','Recurso');
insert into db_syscampo values(1009572,'c98_valor','float4','Valor','0', 'Valor',10,'f','f','f',4,'text','Valor');
insert into db_syscampo values(1009573,'c98_receitalancada','bool','Receita Lançada','f', 'Receita Lançada',1,'f','f','f',5,'text','Receita Lançada');
insert into db_syscampo values(1009574,'c98_instit','int4','Instituição','0', 'Instituição',10,'f','f','f',1,'text','Instituição');
insert into db_syscampo values(1009575,'c98_concarpeculiar','varchar(3)','CP / CA','', 'CP / CA',3,'f','t','f',0,'text','CP / CA');
insert into db_syscampo values(1009576,'c98_datacriacao','date','Data de Criação','null', 'Data de Criação',10,'f','f','f',1,'text','Data de Criação');
insert into db_sysarquivo values (1010247, 'orcamentoreceita', 'orcamentoreceita', 'c98', '2017-12-12', 'orcamentoreceita', 0, 'f', 'f', 'f', 'f' );
insert into db_sysarqmod values (32,1010247);
delete from db_sysarqcamp where codarq = 1010247;
insert into db_sysarqcamp values(1010247,1009558,1,0);
insert into db_sysarqcamp values(1010247,1009559,2,0);
insert into db_sysarqcamp values(1010247,1009560,3,0);
insert into db_sysarqcamp values(1010247,1009561,4,0);
insert into db_sysarqcamp values(1010247,1009562,5,0);
insert into db_sysarqcamp values(1010247,1009563,6,0);
insert into db_sysarqcamp values(1010247,1009564,7,0);
insert into db_sysarqcamp values(1010247,1009565,8,0);
insert into db_sysarqcamp values(1010247,1009566,9,0);
insert into db_sysarqcamp values(1010247,1009567,10,0);
insert into db_sysarqcamp values(1010247,1009568,11,0);
insert into db_sysarqcamp values(1010247,1009569,12,0);
insert into db_sysarqcamp values(1010247,1009570,13,0);
insert into db_sysarqcamp values(1010247,1009571,14,0);
insert into db_sysarqcamp values(1010247,1009572,15,0);
insert into db_sysarqcamp values(1010247,1009573,16,0);
insert into db_sysarqcamp values(1010247,1009574,17,0);
insert into db_sysarqcamp values(1010247,1009575,18,0);
insert into db_sysarqcamp values(1010247,1009576,19,0);
insert into db_sysindices values(1008241,'orcamentoreceita_codcon_seq',1010247,'0');
insert into db_syscadind values(1008241,1009559,1);
insert into db_syscadind values(1008241,1009560,2);
update db_sysindices set nomeind = 'orcamentoreceita_codcon_anousu_in',campounico = '0' where codind = 1008241;
delete from db_syscadind where codind = 1008241;
insert into db_syscadind values(1008241,1009559,1);
insert into db_syscadind values(1008241,1009560,2);
insert into db_syssequencia values(1000707, 'orcamentoreceita_c98_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
update db_sysarqcamp set codsequencia = 1000707 where codarq = 1010247 and codcam = 1009558;
delete from db_sysprikey where codarq = 1010247;
insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010247,1009558,1,1009562);


DICIONARIO_UP
        );
    }

    private function migrarContasOrcamento()
    {

        $sqlMigracaoContas = "
           insert into orcamentoreceita
                       ( c98_sequencial              
                        ,c98_codcon                  
                        ,c98_anousu                  
                        ,c98_estrutural              
                        ,c98_descricao               
                        ,c98_finalidade              
                        ,c98_codsis                  
                        ,c98_codcla                  
                        ,c98_consistemaconta         
                        ,c98_identificadorfinanceiro 
                        ,c98_naturezasaldo           
                        ,c98_funcao                  
                        ,c98_codrec                  
                        ,c98_recurso                 
                        ,c98_valor                   
                        ,c98_receitalancada          
                        ,c98_instit                  
                        ,c98_concarpeculiar          
                        ,c98_datacriacao)
                 select  nextval('orcamentoreceita_c98_sequencial_seq')
                        ,c60_codcon                 
                        ,c60_anousu                 
                        ,c60_estrut                 
                        ,c60_descr                  
                        ,c60_finali                 
                        ,c60_codsis                 
                        ,c60_codcla                 
                        ,c60_consistemaconta        
                        ,c60_identificadorfinanceiro
                        ,c60_naturezasaldo          
                        ,c60_funcao     
                        ,o70_codrec         
                        ,o70_codigo         
                        ,o70_valor          
                        ,o70_reclan         
                        ,o70_instit         
                        ,o70_concarpeculiar 
                        ,o70_datacriacao
                   from conplanoorcamento 
                        left join orcreceita on o70_codfon = c60_codcon 
                                            and o70_anousu = c60_anousu 
                  where substr(c60_estrut, 1, 1)::int in (4,9) and c60_anousu >= 2018;
        ";
        $this->execute($sqlMigracaoContas);
    }

}
