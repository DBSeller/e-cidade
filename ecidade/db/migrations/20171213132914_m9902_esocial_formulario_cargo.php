<?php

use Classes\PostgresMigration;

class M9902EsocialFormularioCargo extends PostgresMigration
{
    public function up()
    {
        $this->adicionaFormulario();
        $this->adicionaDicionario();
    }

    public function down()
    {
        $this->removeFormulario();
        $this->removeDicionario();
    }

    private function adicionaDicionario()
    {
        $sql = <<<SQL
            -- Menus
            insert into db_itensmenu ( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 10483 ,'Tabela de Cargos' ,'Tabela de Cargos' ,'con4_manutencaoformulario001.php?esocial=5' ,'1' ,'1' ,'Tabela de Cargos para envio do eSocial' ,'true' );
            insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 10466 ,10483 ,5 ,10216 );

            insert into db_itensmenu ( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 10484 ,'Tabela de Funções' ,'Tabela de Funções' ,'con4_manutencaoformulario001.php?esocial=6' ,'1' ,'1' ,'Tabela de funções para envio do eSocial' ,'true' );
            insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 10466 ,10484 ,6 ,10216 );


            insert into db_itensmenu ( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 10485 ,'Tabela de Horários' ,'Tabela de Horários' ,'con4_manutencaoformulario001.php?esocial=7' ,'1' ,'1' ,'Tabela de Horários para envio do eSocial' ,'true' );
            insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 10466 ,10485 ,7 ,10216 );

            -- Novos tipos do eSocial 
            insert into esocialformulariotipo   values(5, 'Cargo');          
            insert into esocialformulariotipo   values(6, 'Funcao');
            insert into esocialformulariotipo   values(7, 'Horario');                   
            insert into esocialversaoformulario values(nextval('esocialversaoformulario_rh211_sequencial_seq'), '2.4', 3000017, 5);

            -- Padroniza os nomes das cargas 
            -- Atualiza a descricao do formulario de rubricas
            update avaliacao set db101_descricao = 'Tabela de Rubricas s1010 v2.4' where db101_sequencial = 3000016;

SQL;
        $this->execute($sql);
    }

    private function adicionaFormulario()
    {
        $sql = <<<SQL
            insert into avaliacao( db101_sequencial ,db101_avaliacaotipo ,db101_descricao ,db101_identificador ,db101_obs ,db101_ativo ,db101_cargadados ,db101_permiteedicao ) values ( 3000017 ,5 ,'Tabela de Cargos s1030 v.2.4' ,'S1030241111' ,'Formulário eSocial Cargo s1030' ,'true' ,'select rh37_funcao as codigo, rh37_descr as nome, rh37_instit as instituicao, rh37_cbo as cbo from rhfuncao where rh37_ativo =''t'' and rh37_instit = fc_getsession(''DB_instit'')::int' ,'true' );
            insert into avaliacaogrupopergunta( db102_sequencial ,db102_avaliacao ,db102_descricao ,db102_identificador ,db102_identificadorcampo ) values ( 3000222 ,3000017 ,'Informações de identificação do cargo' ,'informacoes-de-identificacao-do-cargo' ,'ideCargo' );
            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3000960 ,2 ,3000222 ,'Instituição no e-Cidade:' ,'instituicao-no-ecidade5a3264698599b' ,'true' ,'true' ,1 ,6 ,'' ,0 ,'true' ,'instituicao' ,'instituicao' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003867 ,3000960 ,'' ,'5a3264699d652' ,'true' ,0 ,'' ,'instituicao' );
            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3000961 ,2 ,3000222 ,'Código do cargo.' ,'preencher-com-o-codigo-do-cargo' ,'true' ,'true' ,2 ,6 ,'' ,0 ,'true' ,'codigo' ,'codCargo' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003868 ,3000961 ,'' ,'5a326469a4eab' ,'true' ,0 ,'' ,'codCargo' );
            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3000962 ,2 ,3000222 ,'Ano e mês de início da validade das informações prestadas no evento, no formato AAAA-MM.' ,'preencher-com-o-mes-e-ano-de-inicio-d5a326469a71d8' ,'true' ,'true' ,3 ,1 ,'' ,0 ,'false' ,'' ,'iniValid' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003869 ,3000962 ,'' ,'5a326469a8fa7' ,'true' ,0 ,'' ,'iniValid' );
            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3000963 ,2 ,3000222 ,'Ano e mês de término da validade das informações, no formato AAAA-MM, se houver.' ,'preencher-com-o-mes-e-ano-de-termino-5a326469a979c' ,'false' ,'true' ,4 ,1 ,'' ,0 ,'false' ,'' ,'fimValid' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003870 ,3000963 ,'' ,'5a326469aa2ba' ,'true' ,0 ,'' ,'fimValid' );
            insert into avaliacaogrupopergunta( db102_sequencial ,db102_avaliacao ,db102_descricao ,db102_identificador ,db102_identificadorcampo ) values ( 3000223 ,3000017 ,'Detalhamento das informações do cargo que está sendo incluído' ,'detalhamento-das-informacoes-do-cargo-que-esta-sen' ,'dadosCargo' );
            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3000964 ,2 ,3000223 ,'Nome do cargo.' ,'preencher-com-o-nome-do-cargo' ,'true' ,'true' ,1 ,1 ,'' ,0 ,'false' ,'nome' ,'nmCargo' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003871 ,3000964 ,'' ,'5a326469ac6ad' ,'true' ,0 ,'' ,'nmCargo' );
            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3000965 ,2 ,3000223 ,'Classificação Brasileira de Ocupação - CBO.' ,'classificacao-brasileira-de-ocupacao-cbo' ,'true' ,'true' ,2 ,1 ,'' ,0 ,'false' ,'cbo' ,'codCBO' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003872 ,3000965 ,'' ,'5a326469b0838' ,'true' ,0 ,'' ,'codCBO' );
            insert into avaliacaogrupopergunta( db102_sequencial ,db102_avaliacao ,db102_descricao ,db102_identificador ,db102_identificadorcampo ) values ( 3000224 ,3000017 ,'Detalhamento de informações exclusivas para Cargos e Empregos Públicos' ,'detalhamento-de-informacoes-exclusivas-para-cargos' ,'cargoPublico' );
            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3000966 ,1 ,3000224 ,'Código correspondente à possibilidade de acumulação de cargos:' ,'preencher-com-o-codigo-correspondente-a-possibilid' ,'true' ,'true' ,1 ,1 ,'' ,0 ,'false' ,'' ,'acumCargo' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003873 ,3000966 ,'Não acumulável' ,'nao-acumulavel' ,'false' ,0 ,'1' ,'acumCargo_1' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003874 ,3000966 ,'Profissional de Saúde' ,'profissional-de-saude' ,'false' ,0 ,'2' ,'acumCargo_2' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003875 ,3000966 ,'Professor' ,'professor' ,'false' ,0 ,'3' ,'acumCargo_3' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003876 ,3000966 ,'Técnico/Científico' ,'tecnicocientifico' ,'false' ,0 ,'4' ,'acumCargo_4' );
            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3000967 ,1 ,3000224 ,'Código correspondente a possibilidade de contagem de tempo especial:' ,'preencher-com-o-codigo-correspondente5a326469b39d9' ,'true' ,'true' ,2 ,1 ,'' ,0 ,'false' ,'' ,'contagemEsp' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003877 ,3000967 ,'Não' ,'nao5a326469b46d9' ,'false' ,0 ,'1' ,'contagemEsp_1' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003878 ,3000967 ,'Professor (Infantil, Fundamental e Médio)' ,'professor-infantil-fundamental-e-medio' ,'false' ,0 ,'2' ,'contagemEsp_2' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003879 ,3000967 ,'Professor de Ensino Superior, Magistrado, Membro de Ministério Público, Membro do Tribunal de Contas' ,'professor-de-ensino-superior-magistrado-membro-de-' ,'false' ,0 ,'3' ,'contagemEsp_3' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003880 ,3000967 ,'Atividade de risco' ,'atividade-de-risco' ,'false' ,0 ,'4' ,'contagemEsp_4' );
            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3000968 ,1 ,3000224 ,'Cargo de dedicação exclusiva:' ,'indicar-se-e-cargo-de-dedicacao-exclusiva' ,'true' ,'true' ,3 ,1 ,'' ,0 ,'false' ,'' ,'dedicExcl' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003881 ,3000968 ,'Sim' ,'sim5a326469b6b6a' ,'false' ,0 ,'S' ,'dedicExcl_S' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003882 ,3000968 ,'Não' ,'nao5a326469b7264' ,'false' ,0 ,'N' ,'dedicExcl_N' );
            insert into avaliacaogrupopergunta( db102_sequencial ,db102_avaliacao ,db102_descricao ,db102_identificador ,db102_identificadorcampo ) values ( 3000225 ,3000017 ,'Lei que criou/extinguiu/reestruturou o cargo' ,'lei-que-criouextinguiureestruturou-o-cargo' ,'leiCargo' );
            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3000969 ,2 ,3000225 ,'Número da Lei' ,'numero-da-lei' ,'true' ,'true' ,1 ,6 ,'' ,0 ,'false' ,'' ,'nrLei' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003883 ,3000969 ,'' ,'5a326469b8a51' ,'true' ,0 ,'' ,'nrLei' );
            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3000970 ,2 ,3000225 ,'Data da Lei' ,'data-da-lei' ,'true' ,'true' ,2 ,5 ,'' ,0 ,'false' ,'' ,'dtLei' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003884 ,3000970 ,'' ,'5a326469b9b6c' ,'true' ,0 ,'' ,'dtLei' );
            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3000971 ,1 ,3000225 ,'Situação gerada pela Lei. Preencher com uma das opções:' ,'situacao-gerada-pela-lei-preencher-com-uma-das-opc' ,'true' ,'true' ,3 ,6 ,'' ,0 ,'false' ,'' ,'sitCargo' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003885 ,3000971 ,'Criação' ,'criacao' ,'false' ,0 ,'1' ,'sitCargo_1' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003886 ,3000971 ,'Extinção' ,'extincao5a326469bb44b' ,'false' ,0 ,'2' ,'sitCargo_2' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003887 ,3000971 ,'Reestruturação' ,'reestruturacao' ,'false' ,0 ,'3' ,'sitCargo_3' );

SQL;
        $this->execute($sql);
    }

    private function removeDicionario()
    {
        $sql = <<<SQL
            -- Menus
            delete from db_itensmenu where id_item in (10483, 10484, 10485);
            delete from db_menu where id_item_filho = 10483 AND modulo = 10216;
            delete from db_menu where id_item_filho = 10484 AND modulo = 10216;
            delete from db_menu where id_item_filho = 10485 AND modulo = 10216;

            -- Novos tipos do eSocial 
            delete from esocialversaoformulario where rh211_esocialformulariotipo in (5, 6, 7);
            delete from esocialformulariotipo   where rh209_sequencial in (5, 6, 7);
SQL;
        $this->execute($sql);
    }

    private function removeFormulario()
    {
        $id  = 3000017;
        $sql = <<<SQL
            delete from esocialversaoformulario where rh211_avaliacao = $id;
            delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta in (select db103_sequencial from avaliacaopergunta where db103_avaliacaogrupopergunta in (select db102_sequencial from avaliacaogrupopergunta where db102_avaliacao in ($id)));
            delete from avaliacaoperguntaopcao where db104_avaliacaopergunta in (select db103_sequencial from avaliacaopergunta where db103_avaliacaogrupopergunta in (select db102_sequencial from avaliacaogrupopergunta where db102_avaliacao in ($id)));
            delete from avaliacaopergunta where db103_avaliacaogrupopergunta in (select db102_sequencial from avaliacaogrupopergunta where db102_avaliacao in ($id));
            delete from avaliacaogrupopergunta where db102_avaliacao in ($id);
            delete from esocialversaoformulario where rh211_avaliacao in ($id);
            delete from avaliacao where db101_sequencial in ($id);

            delete from esocialversaoformulario where  rh211_versao =  '2.4' and rh211_avaliacao = $id;
SQL;
        $this->execute($sql);
    }
}
