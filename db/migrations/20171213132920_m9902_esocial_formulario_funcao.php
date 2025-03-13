<?php

use Classes\PostgresMigration;

class M9902EsocialFormularioFuncao extends PostgresMigration
{
    public function up()
    {
       $this->adicionaFormulario();
        
    }

    public function down()
    {   
        $this->removeFormulario();
    }


    public function adicionaFormulario() 
    {

        $sql = "
            insert into avaliacao( db101_sequencial ,db101_avaliacaotipo ,db101_descricao ,db101_identificador ,db101_obs ,db101_ativo,db101_cargadados ,db101_permiteedicao ) values ( 3000018 ,5 ,'Tabela de Funções s1040 v2.4' ,'sSS1040203' ,'Formulario' ,'true' ,'select rhcargo.rh04_codigo as codigo, rhcargo.rh04_descr as descricao, rhcargo.rh04_instit as instituicao from rhcargo inner join db_config on db_config.codigo = rhcargo.rh04_instit where rhcargo.rh04_instit = fc_getsession(''DB_instit'')::int' ,'true' );
            insert into avaliacaogrupopergunta( db102_sequencial ,db102_avaliacao ,db102_descricao ,db102_identificador ,db102_identificadorcampo ) values ( 3000226 ,3000018 ,'Informações de identificação da função e validade das informações que estão sendo incluídas' ,'informacoes-de-identificacao-da-funcao-e-validade-' ,'ideFuncao' );
            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3000972 ,2 ,3000226 ,'Instituição no e-Cidade:' ,'instituicao-no-ecidade5a3286afba050' ,'true' ,'true' ,1 ,6 ,'' ,0 ,'true' ,'instituicao' ,'instituicao' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003888 ,3000972 ,'' ,'5a3286afc6d5a' ,'true' ,0 ,'' ,'instituicao' );
            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3000973 ,2 ,3000226 ,'Preencher com o código da função, se utilizado pelo empregador. ' ,'preencher-com-o-codigo-da-funcao-se-utilizado-pelo' ,'true' ,'true' ,2 ,6 ,'' ,0 ,'true' ,'codigo' ,'codFuncao' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003889 ,3000973 ,'' ,'5a3286afceb71' ,'false' ,0 ,'' ,'codFuncao' );
            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3000974 ,2 ,3000226 ,'Ano e mês de início da validade das informações prestadas no evento, no formato AAAA-MM.' ,'preencher-com-o-mes-e-ano-de-inicio-d5a3286afd189e' ,'true' ,'true' ,3 ,1 ,'' ,0 ,'false' ,'' ,'iniValid' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003890 ,3000974 ,'' ,'5a3286afd5172' ,'false' ,0 ,'' ,'iniValid' );
            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3000975 ,2 ,3000226 ,'Ano e mês de término da validade das informações, no formato AAAA-MM, se houver.' ,'preencher-com-o-mes-e-ano-de-termino-5a3286afd7843' ,'false' ,'true' ,4 ,1 ,'' ,0 ,'false' ,'' ,'fimValid' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003891 ,3000975 ,'' ,'5a3286afdb433' ,'false' ,0 ,'' ,'fimValid' );
            insert into avaliacaogrupopergunta( db102_sequencial ,db102_avaliacao ,db102_descricao ,db102_identificador ,db102_identificadorcampo ) values ( 3000227 ,3000018 ,'Detalhamento das informações da função que está sendo incluída' ,'detalhamento-das-informacoes-da-funcao-que-esta-se' ,'dadosFuncao' );
            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3000976 ,2 ,3000227 ,'Nome da Função de confiança/Cargo em Comissão' ,'nome-da-funcao-de-confiancacargo-em-comissao' ,'true' ,'true' ,1 ,1 ,'' ,0 ,'false' ,'descricao' ,'dscFuncao' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003892 ,3000976 ,'' ,'5a3286afe2a40' ,'false' ,0 ,'' ,'dscFuncao' );
            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3000977 ,2 ,3000227 ,'Classificação Brasileira de Ocupação - CBO.' ,'classificacao-brasileira-de-ocupacao-5a3286afe4f22' ,'true' ,'true' ,2 ,1 ,'' ,0 ,'false' ,'' ,'codCBO' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003893 ,3000977 ,'' ,'5a3286afe88b9' ,'false' ,0 ,'' ,'codCBO' );

            insert into esocialversaoformulario values(nextval('esocialversaoformulario_rh211_sequencial_seq'), '2.4', 3000018, 6);
        ";   
          $this->execute($sql);   
    }
 
     public function removeFormulario()
    {
        $id  = 3000018;
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

