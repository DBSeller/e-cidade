<?php

use Classes\PostgresMigration;

class M18818EsocialS1020S10 extends PostgresMigration
{
    public function up()
    {
        $perguntas = [3000866, 3000867];
        foreach ($perguntas as $pergunta) {
            $this->deletaGrupo($pergunta);
        }
        $this->adicionaGrupos();
    }

    public function down()
    {
        $grupos = [4000232];
        foreach ($grupos as $grupo) {
            $this->deletaGrupo($grupo);
        }

        $this->retornaFormulario();
    }

    private function adicionaGrupos()
    {
        $sql = <<<SQL
            insert into avaliacaogrupopergunta( db102_sequencial ,db102_avaliacao ,db102_descricao ,db102_identificador ,db102_identificadorcampo ,db102_ordem ) values ( 4000232 ,3000014 ,'Informações do operador portuário' ,'informacao-operador-portuario' ,'dadosOpPort' ,6 );
            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ,db103_somenteleitura ) values ( 4000295 ,2 ,4000232 ,'Preencher com a alíquota definida na legislação vigente para a atividade (CNAE) preponderante.' ,'aliqRat_s10' ,'false' ,'true' ,1 ,1 ,'' ,0 ,'false' ,'' ,'aliqRat' ,'false' );
            delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 4000295;
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001334 ,4000295 ,'' ,'aliqRat5a2ab3857ac42_s10' ,'true' ,0 ,'1' ,'aliqRat' );
            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ,db103_somenteleitura ) values ( 4000296 ,2 ,4000232 ,'Fator Acidentário de Prevenção - FAP.' ,'fapS10' ,'false' ,'true' ,2 ,1 ,'' ,0 ,'false' ,'' ,'fap' ,'false' );
            delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 4000296;
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001335 ,4000296 ,'' ,'fap5a2ab3857c60d_s10' ,'true' ,0 ,'' ,'fap' );
SQL;
        $this->execute($sql);
    }

    private function deletaGrupo($db102_sequencial) {
        $this->execute(<<<SQL
            delete from habitacao.avaliacaogrupoperguntaresposta where db108_avaliacaoresposta in (select db106_sequencial from avaliacaoresposta where db106_avaliacaoperguntaopcao in(select db104_sequencial from avaliacaoperguntaopcao where db104_avaliacaopergunta in (select db103_sequencial from avaliacaopergunta where db103_avaliacaogrupopergunta = ${db102_sequencial})));
            delete from habitacao.avaliacaoresposta where db106_avaliacaoperguntaopcao in(select db104_sequencial from avaliacaoperguntaopcao where db104_avaliacaopergunta in (select db103_sequencial from avaliacaopergunta where db103_avaliacaogrupopergunta = ${db102_sequencial}));
            delete from habitacao.avaliacaoperguntaopcao where db104_avaliacaopergunta in (select db103_sequencial from avaliacaopergunta where db103_avaliacaogrupopergunta = ${db102_sequencial});
            delete from habitacao.avaliacaopergunta where db103_avaliacaogrupopergunta = ${db102_sequencial};
            delete from habitacao.avaliacaogrupopergunta where db102_sequencial = ${db102_sequencial};
SQL
        );
    }

    private function deletaPergunta($db103_sequencial) {
        $this->execute(<<<SQL
            delete from habitacao.avaliacaogrupoperguntaresposta where db108_avaliacaoresposta in (select db106_sequencial from avaliacaoresposta where db106_avaliacaoperguntaopcao in(select db104_sequencial from avaliacaoperguntaopcao where db104_avaliacaopergunta in (select db103_sequencial from avaliacaopergunta where db103_sequencial = {$db103_sequencial})));
            delete from habitacao.avaliacaoresposta where db106_avaliacaoperguntaopcao in(select db104_sequencial from avaliacaoperguntaopcao where db104_avaliacaopergunta in ({$db103_sequencial}));
            delete from habitacao.avaliacaoperguntaopcao where db104_avaliacaopergunta in (select db103_sequencial from avaliacaopergunta where db103_sequencial = {$db103_sequencial});
            delete from habitacao.avaliacaopergunta where db103_sequencial = {$db103_sequencial};

SQL
        );
    }

    private function retornaFormulario()
    {
        $sql = <<<SQL
        insert into habitacao.avaliacaopergunta(
            db103_sequencial,
            db103_avaliacaotiporesposta,
            db103_avaliacaogrupopergunta,
            db103_descricao,
            db103_identificador,
            db103_obrigatoria,
            db103_ativo,
            db103_ordem,
            db103_tipo,
            db103_mascara,
            db103_dblayoutcampo,
            db103_perguntaidentificadora,
            db103_camposql,
            db103_identificadorcampo
        ) values (
              3000866,
              1,
              3000193,
              'Selecionar a descrição do FPAS correspondente',
              'preencher_com_o_codigo_relativo_ao_fpas',
              'true',
              'true',
              1,
              1,
              '',
              0,
              'false',
              '',
              'fpas'
        );

        insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003576 ,3000866 ,'Indústria, Escritório e Depósito de Empresa Industrial, Indústria de carnes e derivados entre outros' ,'industria-escritorio-e-deposito-de-empresa-industr' ,'false' ,0 ,'507' ,'fpas_507' );
        insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003577 ,3000866 ,'Comércio atacadista, Varejista, Estabelecimento de serviço de saúde, Comércio transportador entre outros' ,'comercio-atacadista-varejista-estabelecimento-de-s' ,'false' ,0 ,'515' ,'fpas_515' );
        insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003578 ,3000866 ,'Sindicado e associação, trabalhador avulso ou empregador' ,'sindicado-e-associacao-trabalhador-avulso-ou-empre' ,'false' ,0 ,'523' ,'fpas_523' );
        insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003579 ,3000866 ,'Indústria de cana-de-açúcar e laticínios, extração de madeira, matadouro e abatedouro entre outros' ,'industria-de-canadeacucar-e-laticinios-extracao-de' ,'false' ,0 ,'531' ,'fpas_531' );
        insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003580 ,3000866 ,'Empresa de navegação marítima, fluvial e lacustre, Empresa de administração e exploração de portos entre outros' ,'empresa-de-navegacao-maritima-fluvial-e-lacustre-e' ,'false' ,0 ,'540' ,'fpas_540' );
        insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003581 ,3000866 ,'Empresa aeroviária' ,'empresa-aeroviaria' ,'false' ,0 ,'558' ,'fpas_558' );
        insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003582 ,3000866 ,'Empresa de comunicação, publicidade, josrnalista.' ,'empresa-de-comunicacao-publicidade-josrnalista' ,'false' ,0 ,'566' ,'fpas_566' );
        insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003583 ,3000866 ,'Estabelecimento de ensino - Sociedade cooperativa ' ,'estabelecimento-de-ensino-sociedade-cooperativa-' ,'false' ,0 ,'574' ,'fpas_574' );
        insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003584 ,3000866 ,'Órgão de poder público, ' ,'orgao-de-poder-publico-' ,'false' ,0 ,'582' ,'fpas_582' );
        insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003585 ,3000866 ,'Cartório e tabelionato' ,'cartorio-e-tabelionato' ,'false' ,0 ,'590' ,'fpas_590' );
        insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003586 ,3000866 ,'Produtor Rural pessoa física, jurídica, consórcio simplificado de produtores rurais, agroindústria' ,'produtor-rural-pessoa-fisica-juridica-consorcio-si' ,'false' ,0 ,'604' ,'fpas_604' );
        insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003587 ,3000866 ,'Empresa optante pelo simples nacional, transporte rodoviário, transporte simples entre outros' ,'empresa-optante-pelo-simples-nacional-transporte-r' ,'false' ,0 ,'612' ,'fpas_612' );
        insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003588 ,3000866 ,'Tomador de serviço de transportador rodoviário autônomo' ,'tomador-de-servico-de-transportador-rodoviario-aut' ,'false' ,0 ,'620' ,'fpas_620' );
        insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003589 ,3000866 ,'Sociedade beneficente de assistência social' ,'sociedade-beneficente-de-assistencia-social' ,'false' ,0 ,'639' ,'fpas_639' );
        insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003590 ,3000866 ,'Associação desportiva que mantém equipe de futebol profissional ' ,'associacao-desportiva-que-mantem-equipe-de-futebol' ,'false' ,0 ,'647' ,'fpas_647' );
        insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003591 ,3000866 ,'Empresa de trabalho temporário' ,'empresa-de-trabalho-temporario' ,'false' ,0 ,'655' ,'fpas_655' );
        insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003592 ,3000866 ,'Órgão gestor de mão-de-obra' ,'orgao-gestor-de-maodeobra' ,'false' ,0 ,'680' ,'fpas_680' );
        insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003593 ,3000866 ,'Banco comercial e de investimento, Banco de desenvolvimento - caixa eletrônico entre outros' ,'banco-comercial-e-de-investimento-banco-de-desenvo' ,'false' ,0 ,'736' ,'fpas_736' );
        insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003594 ,3000866 ,'Empresa adquirente, consumidora, consignatária ou cooperativa, produtor rural de pessoa física e jurídica' ,'empresa-adquirente-consumidora-consignataria-ou-co' ,'false' ,0 ,'744' ,'fpas_744' );
        insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003595 ,3000866 ,'Associação desportiva que mantém equipe de futebol profissional' ,'associacao-desportiva-que-mantem-equi5a2ab38570caf' ,'false' ,0 ,'779' ,'fpas_779' );
        insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003596 ,3000866 ,'Sindicato federação e confederação patronal rural, Atividade cooperativista rural entre outros' ,'sindicato-federacao-e-confederacao-patronal-rural-' ,'false' ,0 ,'787' ,'fpas_787' );
        insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003597 ,3000866 ,'Estabelecimento Rural e industrial de sociedade cooperativa' ,'estabelecimento-rural-e-industrial-de-sociedade-co' ,'false' ,0 ,'795' ,'fpas_795' );
        insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003598 ,3000866 ,'Tomador de serviço de trabalhador avulso' ,'tomador-de-servico-de-trabalhador-avulso' ,'false' ,0 ,'825' ,'fpas_825' );
        insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003599 ,3000866 ,'Setor indutrial de agroindústria e tomador de serviço trabalhador avulso' ,'setor-indutrial-de-agroindustria-e-tomador-de-serv' ,'false' ,0 ,'833' ,'fpas_833' );
        insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003600 ,3000866 ,'Empregador Doméstico' ,'empregador-domestico5a2ab38572dc5' ,'false' ,0 ,'868' ,'fpas_868' );
        insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003601 ,3000866 ,'Missões diplomáticas e outros organismos a elas equiparados' ,'missoes-diplomaticas-e-outros-organismos-a-elas-eq' ,'false' ,0 ,'876' ,'fpas_876' );


        insert into habitacao.avaliacaopergunta(
            db103_sequencial,
            db103_avaliacaotiporesposta,
            db103_avaliacaogrupopergunta,
            db103_descricao,
            db103_identificador,
            db103_obrigatoria,
            db103_ativo,
            db103_ordem,
            db103_tipo,
            db103_mascara,
            db103_dblayoutcampo,
            db103_perguntaidentificadora,
            db103_camposql,
            db103_identificadorcampo
        ) values (
              3000867,
              2,
              3000193,
              'Preencher com o código de Terceiros conforme tabela 4.',
              'preencher_com_o_codigo_de_terceiros_conforme_tabel',
              'true',
              'true',
              2,
              6,
              '',
              0,
              'false',
              '',
              'codTercs'
          );

        insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003602 ,3000867 ,'' ,'5a2ab385745b5' ,'true' ,0 ,'' ,'codTercs' );

SQL;

    }
}
