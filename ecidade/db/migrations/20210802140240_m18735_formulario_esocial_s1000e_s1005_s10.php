<?php

use Classes\PostgresMigration;

class M18735FormularioEsocialS1000eS1005S10 extends PostgresMigration
{

    public function up()
    {
        $this->adicionaPerguntas();
        $this->adicionaVersao();
        $grupos = [3000198, 3000199, 3000200, 3000201, 3000204, 3000205, 3000213];
        foreach ($grupos as $grupo) {
            $this->deletaGrupo($grupo);
        }

        $perguntas = [3000878, 3000876, 3000883, 3000884, 3000885, 3000923, 3000933, 3000935, 3000937];

        foreach ($perguntas as $pergunta) {
            $this->deletaPergunta($pergunta);
        }
        $this->obrigaDesobrigaPergunta(false);
        $this->atualizaOrdem();
        $this->atualizaDescricao();
    }

    public function down()
    {
        $this->removeVersao();
        $perguntas = [4000268, 4000264, 4000269, 4000279, 4000294];
        foreach ($perguntas as $pergunta) {
            $this->deletaPergunta($pergunta);
        }
        $this->deletaGrupo(4000224);
        $this->deletaGrupo(4000231);
        $this->obrigaDesobrigaPergunta(true);
        $this->retornaGrupos();
        $this->retornaOrdem();
        $this->retornaDescricao();
    }

    private function obrigaDesobrigaPergunta($tipo)
    {
        if ($tipo) {
            $tipo = 'true';
        } else {
            $tipo = 'false';
        }

        $sql = <<<SQL
        update habitacao.avaliacaopergunta set db103_obrigatoria = {$tipo} where db103_sequencial in (3000924, 3000925, 3000926, 3000927, 3000928, 3000929, 3000931);
SQL;
        $this->execute($sql);
    }

    private function adicionaVersao()
    {
        $sql = <<<SQL
            insert into recursoshumanos.esocialversao values ((select  nextval('esocialversao_rh210_sequencial_seq')), 'S1.0');
            insert into recursoshumanos.esocialversaoformulario values (
                  (select  nextval('esocialversaoformulario_rh211_sequencial_seq')),
                  'S1.0',
                  3000015,
                  1
            );
SQL;
        $this->execute($sql);
    }

    private function adicionaPerguntas()
    {
        $sql = <<<SQL
            insert into habitacao.avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ,db103_somenteleitura ) values ( 4000268 ,2 ,3000196 ,'CNPJ do Ente Federativo Responsável - EFR.' ,'cnpj_do_ente_federativo_responsavel_S10' ,'false' ,'true' ,8 ,1 ,'' ,0 ,'false' ,'' ,'cnpjEFR' ,'false' );
            delete from esocial.avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 4000268;
            insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values (4001325 ,4000268 ,'' ,'5a2ac5a43f4ea_S10' ,'true' ,0 ,'' ,'cnpjEFR' );

            insert into habitacao.avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ,db103_somenteleitura ) values ( 4000269 ,1 ,3000196 ,'Indicativo de microempresa (ME) ou empresa de pequeno porte (EPP) para permissão de acesso ao módulo simplificado (não preencher caso o usuário não se enquadre como micro ou pequena empresa)' ,'indicativo_de_microempresa_S10' ,'false' ,'true' ,6 ,1 ,'' ,0 ,'false' ,'' ,'indPorte' ,'false' );
            delete from esocial.avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 4000269;
            insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001326 ,4000269 ,'Sim' ,'simindPorte_S10' ,'false' ,0 ,'S' ,'indPorte_S' );
            insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001327 ,4000269 ,'Não' ,'naoindPorte_S10' ,'false' ,0 ,'' ,'indPorte_N' );

            insert into habitacao.avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ,db103_somenteleitura ) values ( 4000264 ,1 ,3000196 ,'Indicativo de Construtora:' ,'indicativo_de_construtora_S10' ,'false' ,'true' ,3 ,1 ,'' ,0 ,'false' ,'' ,'indConstr' ,'false' );
            delete from esocial.avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 4000264;
            insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001332 ,4000264 ,'Não é Construtora' ,'nao-e-construtora_S10' ,'false' ,0 ,'0' ,'indConstr_0' );
            insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001333 ,4000264 ,'Empresa Construtora' ,'empresa-construtora_S10' ,'false' ,0 ,'1' ,'indConstr_1' );

            insert into habitacao.avaliacaogrupopergunta( db102_sequencial ,db102_avaliacao ,db102_descricao ,db102_identificador ,db102_identificadorcampo ,db102_ordem ) values ( 4000224 ,3000015 ,'Informações exclusivas de organismos internacionais e outras instituições extraterritoriais' ,'informacoes-exclusivas-de-organismos_S10' ,'infoOrgInternacional' ,3 );
            insert into habitacao.avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ,db103_somenteleitura ) values ( 4000279 ,1 ,4000224 ,'Indicativo da existência de acordo internacional para isenção de multa:' ,'indicativo_da_existencia_de_acordo_in5a2ac5a_S10' ,'false' ,'true' ,1 ,1 ,'' ,0 ,'false' ,'' ,'indAcordoIsenMulta' ,'false' );
            delete from esocial.avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 4000279;
            insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001328 ,4000279 ,'Sem acordo' ,'sem-acordo5a2ac5a44b3f5_S10' ,'false' ,0 ,'0' ,'indAcordoIsenMulta_0' );
            insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001329 ,4000279 ,'Com acordo' ,'com-acordo5a2ac5a44bb99_S10' ,'false' ,0 ,'1' ,'indAcordoIsenMulta_1' );

            insert into habitacao.avaliacaogrupopergunta( db102_sequencial ,db102_avaliacao ,db102_descricao ,db102_identificador ,db102_identificadorcampo ,db102_ordem ) values ( 4000231 ,3000015 ,'Registro preenchido exclusivamente por empresa construtora (CNO).' ,'registro-preenchido-exclusivamente-por-empres_S10' ,'infoObra' ,10 );
            insert into habitacao.avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ,db103_somenteleitura ) values ( 4000294 ,1 ,4000231 ,'Indicativo de Substituição da Contribuição Patronal de Obra de Construção Civil:' ,'indicativo_de_substituicao_da_contrib_S10' ,'false' ,'true' ,1 ,1 ,'' ,0 ,'false' ,'' ,'indSubstPatrObra' ,'false' );
            delete from esocial.avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 4000294;
            insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001330 ,4000294 ,'Contribuição Patronal Substituída' ,'contribuicao-patronal-substituida_S10' ,'false' ,0 ,'1' ,'indSubstPatrObra_1' );
            insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001331 ,4000294 ,'Contribuição Patronal Não Substituída' ,'contribuicao-patronal-nao-substituida_S10' ,'false' ,0 ,'2' ,'indSubstPatrObra_2' );

SQL;
        $this->execute($sql);
    }


    private function removeVersao()
    {
        $sql = <<<SQL
            delete from recursoshumanos.esocialversaoformulario where rh211_avaliacao = 3000045 and rh211_versao = 'S1.0';
            delete from recursoshumanos.esocialversao where rh210_versao = 'S1.0';
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


    private function retornaGrupos()
    {
        $this->execute(<<<SQL

                insert into habitacao.avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3000878 ,1 ,3000196 ,'Preencher com o código da Natureza Jurídica do Contribuinte, conforme tabela 21.' ,'preencher-com-o-codigo-da-natureza-ju5a2ac5a3e8f65' ,'false' ,'true' ,3 ,1 ,'' ,0 ,'false' ,'' ,'natJurid' );
                insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003615 ,3000878 ,'101-5 - Órgão Público do Poder Executivo Federal' ,'1015-orgao-publico-do-poder-execut5a2ac5a3e9e3e' ,'false' ,0 ,'1015' ,'natJurid_101-5' );
                insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003616 ,3000878 ,'102-3 - Órgão Público do Poder Executivo Estadual ou do Distrito Federal' ,'1023-orgao-publico-do-poder-execut5a2ac5a3ea661' ,'false' ,0 ,'1023' ,'natJurid_102-3' );
                insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003617 ,3000878 ,'103-1 - Órgão Público do Poder Executivo Municipal' ,'1031-orgao-publico-do-poder-execut5a2ac5a3eaebe' ,'false' ,0 ,'1031' ,'natJurid_103-1' );
                insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003618 ,3000878 ,'104-0 - Órgão Público do Poder Legislativo Federal' ,'1040-orgao-publico-do-poder-legisl5a2ac5a3eb866' ,'false' ,0 ,'1040' ,'natJurid_104-0' );
                insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003619 ,3000878 ,'105-8 - Órgão Público do Poder Legislativo Estadual ou do Distrito Federal' ,'1058-orgao-publico-do-poder-legisl5a2ac5a3ec0cb' ,'false' ,0 ,'1058' ,'natJurid_105-8' );
                insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003620 ,3000878 ,'106-6 - Órgão Público do Poder Legislativo Municipal' ,'1066-orgao-publico-do-poder-legisl5a2ac5a3ec8b2' ,'false' ,0 ,'1066' ,'natJurid_106-6' );
                insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003621 ,3000878 ,'107-4 - Órgão Público do Poder Judiciário Federal' ,'1074-orgao-publico-do-poder-judici5a2ac5a3ed070' ,'false' ,0 ,'1074' ,'natJurid_107-4' );
                insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003622 ,3000878 ,'108-2 - Órgão Público do Poder Judiciário Estadual' ,'1082-orgao-publico-do-poder-judici5a2ac5a3ed847' ,'false' ,0 ,'1082' ,'natJurid_108-2' );
                insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003623 ,3000878 ,'110-4 - Autarquia Federal' ,'1104-autarquia-federal5a2ac5a3ee001' ,'false' ,0 ,'1104' ,'natJurid_110_4' );
                insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003624 ,3000878 ,'111-2 - Autarquia Estadual ou do Distrito Federal' ,'1112-autarquia-estadual-ou-do-dist5a2ac5a3ee7da' ,'false' ,0 ,'1112' ,'natJurid_111_2' );
                insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003625 ,3000878 ,'112-0 - Autarquia Municipal' ,'1120-autarquia-municipal5a2ac5a3eef87' ,'false' ,0 ,'1120' ,'natJurid_112-0' );
                insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003626 ,3000878 ,'113-9 - Fundação Pública de Direito Público Federal' ,'1139-fundacao-publica-de-direito-p5a2ac5a3ef785' ,'false' ,0 ,'1139' ,'natJurid_113-9' );
                insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003627 ,3000878 ,'114-7 - Fundação Pública de Direito Público Estadual ou do Distrito Federal' ,'1147-fundacao-publica-de-direito-p5a2ac5a3eff68' ,'false' ,0 ,'1147' ,'natJurid_114-7' );
                insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003628 ,3000878 ,'115-5 - Fundação Pública de Direito Público Municipal' ,'1155-fundacao-publica-de-direito-p5a2ac5a3f076c' ,'false' ,0 ,'1155' ,'natJurid_115-5' );
                insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003629 ,3000878 ,'116-3 - Órgão Público Autônomo Federal' ,'1163-orgao-publico-autonomo-federa5a2ac5a3f0f59' ,'false' ,0 ,'1163' ,'natJurid_116-3' );
                insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003630 ,3000878 ,'117-1 - Órgão Público Autônomo Estadual ou do Distrito Federal' ,'1171-orgao-publico-autonomo-estadu5a2ac5a3f1734' ,'false' ,0 ,'1171' ,'natJurid_117-1' );
                insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003631 ,3000878 ,'118-0 - Órgão Público Autônomo Municipal' ,'1180-orgao-publico-autonomo-munici5a2ac5a3f2073' ,'false' ,0 ,'1180' ,'natJurid_118-0' );
                insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003632 ,3000878 ,'119-8 - Comissão Polinacional' ,'1198-comissao-polinacional5a2ac5a3f28da' ,'false' ,0 ,'1198' ,'natJurid_119-8' );
                insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003633 ,3000878 ,'120-1 - Fundo Público' ,'1201-fundo-publico5a2ac5a3f30da' ,'false' ,0 ,'1201' ,'natJurid_120-1' );
                insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003634 ,3000878 ,'121-0 - Consórcio Público de Direito Público (Associação Pública)' ,'1210-consorcio-publico-de-direito-5a2ac5a3f38c0' ,'false' ,0 ,'1210' ,'natJurid_121-0' );
                insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003635 ,3000878 ,'122-8 - Consórcio Público de Direito Privado' ,'1228-consorcio-publico-de-direito-5a2ac5a3f411f' ,'false' ,0 ,'1228' ,'natJurid_122-8' );
                insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003636 ,3000878 ,'123-6 - Estado ou Distrito Federal' ,'1236-estado-ou-distrito-federal5a2ac5a4007b8' ,'false' ,0 ,'1236' ,'natJurid_123-6' );
                insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003637 ,3000878 ,'124-4 - Município' ,'1244-municipio5a2ac5a400fd9' ,'false' ,0 ,'1244' ,'natJurid_124-4' );
                insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003638 ,3000878 ,'125-2 - Fundação Pública de Direito Privado Federal' ,'1252-fundacao-publica-de-direito-p5a2ac5a4018b4' ,'false' ,0 ,'1252' ,'natJurid_125-2' );
                insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003639 ,3000878 ,'126-0 - Fundação Pública de Direito Privado Estadual ou do Distrito Federal' ,'1260-fundacao-publica-de-direito-p5a2ac5a4020d0' ,'false' ,0 ,'1260' ,'natJurid_126-0' );
                insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003640 ,3000878 ,'127-9 - Fundação Pública de Direito Privado Municipal' ,'1279-fundacao-publica-de-direito-p5a2ac5a4028df' ,'false' ,0 ,'1279' ,'natJurid_127-9' );
                insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003641 ,3000878 ,'201-1 - Empresa Pública' ,'2011-empresa-publica5a2ac5a40316e' ,'false' ,0 ,'2011' ,'natJurid_201-1' );
                insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003642 ,3000878 ,'203-8 - Sociedade de Economia Mista' ,'2038-sociedade-de-economia-mista5a2ac5a403af6' ,'false' ,0 ,'2038' ,'natJurid_203-8' );
                insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003643 ,3000878 ,'204-6 - Sociedade Anônima Aberta' ,'2046-sociedade-anonima-aberta5a2ac5a4044ce' ,'false' ,0 ,'2046' ,'natJurid_204-6' );
                insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003644 ,3000878 ,'205-4 - Sociedade Anônima Fechada' ,'2054-sociedade-anonima-fechada5a2ac5a404ca4' ,'false' ,0 ,'2054' ,'natJurid_205-4' );
                insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003645 ,3000878 ,'206-2 - Sociedade Empresária Limitada' ,'2062-sociedade-empresaria-limitada5a2ac5a40548d' ,'false' ,0 ,'2062' ,'natJurid_206-2' );
                insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003646 ,3000878 ,'207-0 - Sociedade Empresária em Nome Coletivo' ,'2070-sociedade-empresaria-em-nome-5a2ac5a405cad' ,'false' ,0 ,'2070' ,'natJurid_207-0' );
                insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003647 ,3000878 ,'208-9 - Sociedade Empresária em Comandita Simples' ,'2089-sociedade-empresaria-em-coman5a2ac5a4064c7' ,'false' ,0 ,'2089' ,'natJurid_208-9' );
                insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003648 ,3000878 ,'209-7 - Sociedade Empresária em Comandita por Ações' ,'2097-sociedade-empresaria-em-comandita-por-acoes' ,'false' ,0 ,'2097' ,'natJurid_209-7' );
                insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003649 ,3000878 ,'212-7 - Sociedade em Conta de Participação' ,'2127-sociedade-em-conta-de-partici5a2ac5a40c6e5' ,'false' ,0 ,'2127' ,'natJurid_212-7' );
                insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003650 ,3000878 ,'213-5 - Empresário (Individual)' ,'2135-empresario-individual5a2ac5a40d017' ,'false' ,0 ,'2135' ,'natJurid_213-5' );
                insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003651 ,3000878 ,'214-3 - Cooperativa' ,'2143-cooperativa5a2ac5a40d7e0' ,'false' ,0 ,'2143' ,'natJurid_214-3' );
                insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003652 ,3000878 ,'215-1 - Consórcio de Sociedades' ,'2151-consorcio-de-sociedades5a2ac5a40e1ad' ,'false' ,0 ,'2151' ,'natJurid_215-1' );
                insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003653 ,3000878 ,'216-0 - Grupo de Sociedades' ,'2160-grupo-de-sociedades5a2ac5a40e9f6' ,'false' ,0 ,'2160' ,'natJurid_216-0' );
                insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003654 ,3000878 ,'217-8 - Estabelecimento, no Brasil, de Sociedade Estrangeira' ,'2178-estabelecimento-no-brasil-d5a2ac5a40f239' ,'false' ,0 ,'2178' ,'natJurid_217-8' );
                insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003655 ,3000878 ,'219-4 - Estabelecimento, no Brasil, de Empresa Binacional Argentino-Brasileira' ,'2194-estabelecimento-no-brasil-d5a2ac5a40fbc7' ,'false' ,0 ,'2194' ,'natJurid_219-4' );
                insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003656 ,3000878 ,'221-6 - Empresa Domiciliada no Exterior' ,'2216-empresa-domiciliada-no-exteri5a2ac5a410455' ,'false' ,0 ,'2216' ,'natJurid_221-6' );
                insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003657 ,3000878 ,'222-4 - Clube/Fundo de Investimento' ,'2224-clubefundo-de-investimento5a2ac5a410c34' ,'false' ,0 ,'2224' ,'natJurid_222-4' );
                insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003658 ,3000878 ,'223-2 - Sociedade Simples Pura' ,'2232-sociedade-simples-pura5a2ac5a411408' ,'false' ,0 ,'2232' ,'natJurid_223-2' );
                insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003659 ,3000878 ,'224-0 - Sociedade Simples Limitada' ,'2240-sociedade-simples-limitada5a2ac5a411c06' ,'false' ,0 ,'2240' ,'natJurid_224-0' );
                insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003660 ,3000878 ,'225-9 - Sociedade Simples em Nome Coletivo' ,'2259-sociedade-simples-em-nome-col5a2ac5a4123ee' ,'false' ,0 ,'2259' ,'natJurid_225-9' );
                insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003661 ,3000878 ,'226-7 - Sociedade Simples em Comandita Simples' ,'2267-sociedade-simples-em-comandit5a2ac5a412ce6' ,'false' ,0 ,'2267' ,'natJurid_226-7' );
                insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003662 ,3000878 ,'227-5 - Empresa Binacional' ,'2275-empresa-binacional5a2ac5a4135c3' ,'false' ,0 ,'2275' ,'natJurid_227-5' );
                insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003663 ,3000878 ,'228-3 - Consórcio de Empregadores' ,'2283-consorcio-de-empregadores5a2ac5a413edb' ,'false' ,0 ,'2283' ,'natJurid_228-3' );
                insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003664 ,3000878 ,'229-1 - Consórcio Simples' ,'2291-consorcio-simples5a2ac5a4146c3' ,'false' ,0 ,'2291' ,'natJurid_229-1' );
                insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003665 ,3000878 ,'230-5 - Empresa Individual de Responsabilidade Limitada (de Natureza Empresária)' ,'2305-empresa-individual-de-respons5a2ac5a414ea1' ,'false' ,0 ,'2305' ,'natJurid_230-5' );
                insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003666 ,3000878 ,'231-3 - Empresa Individual de Responsabilidade Limitada (de Natureza Simples)' ,'2313-empresa-individual-de-respons5a2ac5a415692' ,'false' ,0 ,'2313' ,'natJurid_231-3' );
                insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003667 ,3000878 ,'232-1 - Sociedade Unipessoal de Advogados' ,'2321-sociedade-unipessoal-de-advog5a2ac5a415edd' ,'false' ,0 ,'2321' ,'natJurid_232-1' );
                insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003668 ,3000878 ,'233-0 - Cooperativas de Consumo' ,'2330-cooperativas-de-consumo5a2ac5a4166f7' ,'false' ,0 ,'2330' ,'natJurid_233-0' );

                insert into habitacao.avaliacaogrupopergunta( db102_sequencial ,db102_avaliacao ,db102_descricao ,db102_identificador ,db102_identificadorcampo ) values ( 3000198 ,3000015 ,'Informações de contato' ,'informacoes-de-contato5a2ac5a433a81' ,'contato' );
                insert into habitacao.avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3000894 ,2 ,3000198 ,'Nome do contato na empresa. Pessoa responsável por ser o contato do empregador com os órgãos gestores do eSocial.' ,'nome-do-contato-na-empresa-pessoa-re5a2ac5a43417d' ,'true' ,'true' ,1 ,1 ,'' ,0 ,'false' ,'' ,'nmCtt' );
                insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003692 ,3000894 ,'' ,'5a2ac5a434e6f' ,'true' ,0 ,'' ,'contato_nmCtt' );
                insert into habitacao.avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3000895 ,2 ,3000198 ,'Preencher com o número do CPF do contato.' ,'preencher-com-o-numero-do-cpf-do-cont5a2ac5a43567a' ,'true' ,'true' ,2 ,4 ,'' ,0 ,'false' ,'' ,'cpfCtt' );
                insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003693 ,3000895 ,'' ,'5a2ac5a4362ae' ,'true' ,0 ,'' ,'contato_cpfCtt' );
                insert into habitacao.avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3000896 ,2 ,3000198 ,'Informar o número do telefone, com DDD.' ,'informar-o-numero-do-telefone-com-dd5a2ac5a436a66' ,'false' ,'true' ,3 ,7 ,'' ,0 ,'false' ,'' ,'foneFixo' );
                insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003694 ,3000896 ,'' ,'5a2ac5a4377e0' ,'true' ,0 ,'' ,'contato_foneFixo' );
                insert into habitacao.avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3000897 ,2 ,3000198 ,'Telefone celular, com DDD.' ,'telefone-celular-com-ddd5a2ac5a438123' ,'false' ,'true' ,4 ,7 ,'' ,0 ,'false' ,'' ,'foneCel' );
                insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003695 ,3000897 ,'' ,'5a2ac5a438dbf' ,'true' ,0 ,'' ,'contato_foneCel' );
                insert into habitacao.avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3000898 ,2 ,3000198 ,'Endereço eletrônico.' ,'endereco-eletronico5a2ac5a43958a' ,'false' ,'true' ,5 ,1 ,'' ,0 ,'false' ,'' ,'email' );
                insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003696 ,3000898 ,'' ,'5a2ac5a43a14c' ,'true' ,0 ,'' ,'contato_email' );

                insert into habitacao.avaliacaogrupopergunta( db102_sequencial ,db102_avaliacao ,db102_descricao ,db102_identificador ,db102_identificadorcampo ) values ( 3000199 ,3000015 ,'Informações relativas a Órgãos Públicos' ,'informacoes-relativas-a-orgaos-public5a2ac5a43a913' ,'infoOP' );
                insert into habitacao.avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3000899 ,2 ,3000199 ,'Preencher com o número SIAFI - Sistema Integrado de Administração Financeira, caso seja órgão público usuário do sistema.' ,'preencher-com-o-numero-siafi-sistem5a2ac5a43b007' ,'true' ,'true' ,1 ,1 ,'' ,0 ,'false' ,'' ,'nrSiafi' );
                insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003697 ,3000899 ,'' ,'5a2ac5a43be61' ,'true' ,0 ,'' ,'nrSiafi' );

                insert into habitacao.avaliacaogrupopergunta( db102_sequencial ,db102_avaliacao ,db102_descricao ,db102_identificador ,db102_identificadorcampo ) values ( 3000200 ,3000015 ,'Informações relativas a Ente Federativo Responsável - EFR' ,'informacoes-relativas-a-ente-federati5a2ac5a43c672' ,'infoEFR' );
                insert into habitacao.avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3000900 ,1 ,3000200 ,'Informar se o Órgão Público é o Ente Federativo Responsável - EFR ou se é uma unidade administrativa autônoma vinculada a um EFR.' ,'informar-se-o-orgao-publico-e-o-ente-5a2ac5a43cdbd' ,'true' ,'true' ,1 ,1 ,'' ,0 ,'false' ,'' ,'ideEFR' );
                insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003698 ,3000900 ,'É EFR' ,'e-efr5a2ac5a43d98e' ,'false' ,0 ,'S' ,'ideEFR_S' );
                insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003699 ,3000900 ,'Não é EFR' ,'nao-e-efr5a2ac5a43e15f' ,'false' ,0 ,'N' ,'ideEFR_N' );
                insert into habitacao.avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3000901 ,2 ,3000200 ,'CNPJ do Ente Federativo Responsável - EFR.' ,'cnpj-do-ente-federativo-responsavel-5a2ac5a43e921' ,'false' ,'true' ,2 ,3 ,'' ,0 ,'false' ,'' ,'cnpjEFR' );
                insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003700 ,3000901 ,'' ,'5a2ac5a43f4ea' ,'true' ,0 ,'' ,'cnpjEFR' );

                insert into habitacao.avaliacaogrupopergunta( db102_sequencial ,db102_avaliacao ,db102_descricao ,db102_identificador ,db102_identificadorcampo ) values ( 3000201 ,3000015 ,'Informações relativas ao ente federativo estadual, distrital ou municipal' ,'informacoes-relativas-ao-ente-federat5a2ac5a43fd23' ,'infoEnte' );
                insert into habitacao.avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3000902 ,2 ,3000201 ,'Nome do Ente Federativo ao qual o órgão está vinculado' ,'nome-do-ente-federativo-ao-qual-o-org5a2ac5a4404a0' ,'true' ,'true' ,1 ,1 ,'' ,0 ,'false' ,'' ,'nmEnte' );
                insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003701 ,3000902 ,'' ,'5a2ac5a441256' ,'true' ,0 ,'' ,'nmEnte' );
                insert into habitacao.avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3000903 ,2 ,3000201 ,'Preencher com a sigla da Unidade da Federação.' ,'preencher-com-a-sigla-da-unidade-da-f5a2ac5a441a47' ,'true' ,'true' ,2 ,1 ,'' ,0 ,'false' ,'' ,'uf' );
                insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003702 ,3000903 ,'' ,'5a2ac5a4426c0' ,'true' ,0 ,'' ,'uf' );
                insert into habitacao.avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3000904 ,2 ,3000201 ,'Preencher com o código do município, conforme tabela do IBGE.' ,'preencher-com-o-codigo-do-municipio-5a2ac5a442ecd' ,'false' ,'true' ,3 ,1 ,'' ,0 ,'false' ,'' ,'codMunic' );
                insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003703 ,3000904 ,'' ,'5a2ac5a443aaa' ,'true' ,0 ,'' ,'codMunic' );
                insert into habitacao.avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3000905 ,1 ,3000201 ,'Informar se o ente público possui Regime Próprio de Previdência Social - RPPS.' ,'informar-se-o-ente-publico-possui-reg5a2ac5a4443d6' ,'true' ,'true' ,4 ,1 ,'' ,0 ,'false' ,'' ,'indRPPS' );
                insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003704 ,3000905 ,'Sim' ,'sim5a2ac5a445096' ,'false' ,0 ,'S' ,'indRPPS_S' );
                insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003705 ,3000905 ,'Não' ,'nao5a2ac5a44584e' ,'false' ,0 ,'N' ,'indRPPS_N' );
                insert into habitacao.avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3000906 ,1 ,3000201 ,'Preencher com o poder a que se refere o subteto:' ,'preencher-com-o-poder-a-que-se-refere5a2ac5a445ffc' ,'true' ,'true' ,5 ,1 ,'' ,0 ,'false' ,'' ,'subteto' );
                insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003706 ,3000906 ,'Executivo' ,'executivo5a2ac5a446c05' ,'false' ,0 ,'1' ,'subteto_1' );
                insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003707 ,3000906 ,'Judiciário' ,'judiciario5a2ac5a447465' ,'false' ,0 ,'2' ,'subteto_2' );
                insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003708 ,3000906 ,'Legislativo' ,'legislativo5a2ac5a447c4b' ,'false' ,0 ,'3' ,'subteto_3' );
                insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003709 ,3000906 ,'Todos os poderes' ,'todos-os-poderes5a2ac5a4483f4' ,'false' ,0 ,'9' ,'subteto_9' );
                insert into habitacao.avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3000907 ,2 ,3000201 ,'Preencher com o valor do subteto do Ente Federativo.' ,'preencher-com-o-valor-do-subteto-do-e5a2ac5a448c18' ,'true' ,'true' ,6 ,8 ,'' ,0 ,'false' ,'' ,'vrSubteto' );
                insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003710 ,3000907 ,'' ,'5a2ac5a449956' ,'true' ,0 ,'' ,'vrSubteto' );

                insert into habitacao.avaliacaogrupopergunta( db102_sequencial ,db102_avaliacao ,db102_descricao ,db102_identificador ,db102_identificadorcampo ) values ( 3000204 ,3000015 ,'Informações Complementares - Pessoa Jurídica' ,'informacoes-complementares-pessoa-j5a2ac5a453339' ,'situacaoPJ' );
                insert into habitacao.avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3000914 ,1 ,3000204 ,'Indicativo da Situação da Pessoa Jurídica:' ,'indicativo-da-situacao-da-pessoa-juri5a2ac5a453a01' ,'true' ,'true' ,1 ,1 ,'' ,0 ,'false' ,'' ,'indSitPJ' );
                insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003718 ,3000914 ,'Situação Normal' ,'situacao-normal5a2ac5a4545e0' ,'false' ,0 ,'0' ,'indSitPJ_0' );
                insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003719 ,3000914 ,'Extinção' ,'extincao5a2ac5a454dbb' ,'false' ,0 ,'1' ,'indSitPJ_1' );
                insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003720 ,3000914 ,'Fusão' ,'fusao5a2ac5a455641' ,'false' ,0 ,'2' ,'indSitPJ_2' );
                insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003721 ,3000914 ,'Cisão' ,'cisao5a2ac5a455df0' ,'false' ,0 ,'3' ,'indSitPJ_3' );
                insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003722 ,3000914 ,'Incorporação' ,'incorporacao5a2ac5a456624' ,'false' ,0 ,'4' ,'indSitPJ_4' );

                insert into habitacao.avaliacaogrupopergunta( db102_sequencial ,db102_avaliacao ,db102_descricao ,db102_identificador ,db102_identificadorcampo ) values ( 3000205 ,3000015 ,'Informações Complementares - Pessoa Física' ,'informacoes-complementares-pessoa-f5a2ac5a456e03' ,'situacaoPF' );
                insert into habitacao.avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3000915 ,1 ,3000205 ,'Indicativo da Situação da Pessoa Física:' ,'indicativo-da-situacao-da-pessoa-fisi5a2ac5a4574f4' ,'true' ,'true' ,1 ,1 ,'' ,0 ,'false' ,'' ,'indSitPF' );
                insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003723 ,3000915 ,'Situação Normal' ,'situacao-normal5a2ac5a4580ed' ,'false' ,0 ,'0' ,'indSitPF_0' );
                insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003724 ,3000915 ,'Encerramento de espólio' ,'encerramento-de-espolio5a2ac5a458936' ,'false' ,0 ,'1' ,'indSitPF_1' );
                insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003725 ,3000915 ,'Saída do país em caráter permanente' ,'saida-do-pais-em-carater-permanente' ,'false' ,0 ,'2' ,'indSitPF_2' );

                insert into habitacao.avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3000876 ,2 ,3000196 ,'Informar o nome do contribuinte.' ,'informar-o-nome-do-contribuinte' ,'true' ,'true' ,1 ,1 ,'' ,0 ,'false' ,'' ,'nmRazao' );
                insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003613 ,3000876 ,'' ,'5a2ac5a3d2a1a' ,'true' ,0 ,'' ,'nmRazao' );

                insert into habitacao.avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3000883 ,1 ,3000196 ,'Indicativo de entidade educativa sem fins lucrativos que tenha por objetivo a assistência ao adolescente e à educação profissional:' ,'indicativo-de-entidade-educativa-sem-5a2ac5a41f430' ,'false' ,'true' ,8 ,1 ,'' ,0 ,'false' ,'' ,'indEntEd' );
                insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003679 ,3000883 ,'Sim' ,'sim5a2ac5a42009a' ,'false' ,0 ,'S' ,'indEntEd_S' );
                insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003680 ,3000883 ,'Não' ,'nao5a2ac5a420943' ,'false' ,0 ,'N' ,'indEntEd_N' );

                insert into habitacao.avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3000884 ,1 ,3000196 ,'Indicativo de Empresa de Trabalho Temporário (Lei n° 6.019/1974), com registro no Ministério do Trabalho:' ,'indicativo-de-empresa-de-trabalho-tem5a2ac5a42115e' ,'true' ,'true' ,9 ,1 ,'' ,0 ,'false' ,'' ,'indEtt' );
                insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003681 ,3000884 ,'Sim' ,'sim5a2ac5a421d55' ,'false' ,0 ,'S' ,'indEtt_S' );
                insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003682 ,3000884 ,'Não' ,'nao5a2ac5a427416' ,'false' ,0 ,'N' ,'indEtt_N' );

                insert into habitacao.avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3000885 ,2 ,3000196 ,'Número do registro da Empresa de Trabalho Temporário no Ministério do Trabalho.' ,'numero-do-registro-da-empresa-de-trab5a2ac5a427e27' ,'false' ,'true' ,10 ,1 ,'' ,0 ,'false' ,'' ,'nrRegEtt' );
                insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003683 ,3000885 ,'' ,'5a2ac5a428aae' ,'true' ,0 ,'' ,'nrRegEtt' );

                -- S1005
                insert into habitacao.avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3000923 ,2 ,3000208 ,'Alíquota do RAT após ajuste pelo FAP.' ,'aliquota-do-rat-apos-ajuste-pelo-fap5a2ac5a464de6' ,'false' ,'true' ,3 ,1 ,'' ,0 ,'false' ,'' ,'aliqRatAjust' );
                insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003735 ,3000923 ,'' ,'5a2ac5a465c8b' ,'true' ,0 ,'' ,'aliqRatAjust' );

                insert into habitacao.avaliacaogrupopergunta( db102_sequencial ,db102_avaliacao ,db102_descricao ,db102_identificador ,db102_identificadorcampo ) values ( 3000213 ,3000015 ,'Informações Trabalhistas relativas ao estabelecimento' ,'informacoes-trabalhistas-relativas-ao5a2ac5a475268' ,'infoTrab' );
                insert into habitacao.avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3000932 ,1 ,3000213 ,'Opção de registro de ponto (jornada) adotada pelo estabelecimento:' ,'opcao-de-registro-de-ponto-jornada-5a2ac5a475a68' ,'true' ,'true' ,1 ,1 ,'' ,0 ,'false' ,'' ,'regPt' );
                insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003749 ,3000932 ,'Não utiliza' ,'nao-utiliza5a2ac5a4766b4' ,'false' ,0 ,'0' ,'regPt_0' );
                insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003750 ,3000932 ,'Manual' ,'manual5a2ac5a476e6c' ,'false' ,0 ,'1' ,'regPt_1' );
                insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003751 ,3000932 ,'Mecânico' ,'mecanico5a2ac5a47768e' ,'false' ,0 ,'2' ,'regPt_2' );
                insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003752 ,3000932 ,'Eletrônico (portaria MTE 1.510/2009)' ,'eletronico-portaria-mte-151020095a2ac5a477e4a' ,'false' ,0 ,'3' ,'regPt_3' );
                insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003753 ,3000932 ,'Não eletrônico alternativo (art. 1° da Portaria MTE 373/2011)' ,'nao-eletronico-alternativo-art-1-d5a2ac5a478646' ,'false' ,0 ,'4' ,'regPt_4' );
                insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003754 ,3000932 ,'Eletrônico alternativo ( art. 2° da Portaria MTE 373/2011)' ,'eletronico-alternativo-art-2-da-p5a2ac5a478e26' ,'false' ,0 ,'5' ,'regPt_5' );
                insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003755 ,3000932 ,'Eletrônico - outros' ,'eletronico-outros5a2ac5a47961a' ,'false' ,0 ,'6' ,'regPt_6' );

                insert into habitacao.avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3000933 ,1 ,3000214 ,'Indicativo de contratação de aprendiz:' ,'indicativo-de-contratacao-de-aprendiz5a2ac5a47a4a0' ,'true' ,'true' ,1 ,1 ,'' ,0 ,'false' ,'' ,'contApr' );
                insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003756 ,3000933 ,'Dispensado de acordo com a lei' ,'dispensado-de-acordo-com-a-lei5a2ac5a47b05b' ,'false' ,0 ,'0' ,'infoApr_contApr_0' );
                insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003757 ,3000933 ,'Dispensado, mesmo que parcialmente, em virtude de processo judicial' ,'dispensado-mesmo-que-parcialmente-e5a2ac5a47b847' ,'false' ,0 ,'1' ,'infoApr_contApr_1' );
                insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003758 ,3000933 ,'Obrigado' ,'obrigado5a2ac5a47c028' ,'false' ,0 ,'2' ,'infoApr_contApr_2' );

                insert into habitacao.avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3000935 ,1 ,3000214 ,'Informar se o estabelecimento realiza a contratação de aprendiz por intermédio de entidade educativa sem fins lucrativos:' ,'informar-se-o-estabelecimento-realiza5a2ac5a47dd19' ,'false' ,'true' ,3 ,1 ,'' ,0 ,'false' ,'' ,'contEntEd' );
                insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003760 ,3000935 ,'Sim' ,'sim5a2ac5a47e934' ,'false' ,0 ,'S' ,'infoApr_contEntEd_S' );
                insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003761 ,3000935 ,'Não' ,'nao5a2ac5a47f12b' ,'false' ,0 ,'N' ,'infoApr_contEntEd_N' );

                insert into habitacao.avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3000937 ,1 ,3000216 ,'Indicativo de contratação de PCD:' ,'indicativo-de-contratacao-de-pcd5a2ac5a481c85' ,'true' ,'true' ,1 ,1 ,'' ,0 ,'false' ,'' ,'contPCD' );
                insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003763 ,3000937 ,'Dispensado de acordo com a lei' ,'dispensado-de-acordo-com-a-lei5a2ac5a482918' ,'false' ,0 ,'0' ,'infoPCD_contPCD_0' );
                insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003764 ,3000937 ,'Dispensado, mesmo que parcialmente, em virtude de processo judicial' ,'dispensado-mesmo-que-parcialmente-e5a2ac5a48314d' ,'false' ,0 ,'1' ,'infoPCD_contPCD_1' );
                insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003765 ,3000937 ,'Com exigibilidade suspensa, mesmo que parcialmente em virtude de Termo de Compromisso firmado com o Ministério do Trabalho' ,'com-exigibilidade-suspensa-mesmo-que5a2ac5a4839a2' ,'false' ,0 ,'2' ,'infoPCD_contPCD_2' );
                insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3003766 ,3000937 ,'Obrigado' ,'obrigado5a2ac5a4841df' ,'false' ,0 ,'9' ,'infoPCD_contPCD_9' );


SQL
        );
    }

    private function atualizaOrdem()
    {
        $sql = <<<SQL
            update habitacao.avaliacaopergunta set db103_ordem = 1  where db103_sequencial = 3000877;
            update habitacao.avaliacaopergunta set db103_ordem = 2  where db103_sequencial = 3000879;
            update habitacao.avaliacaopergunta set db103_ordem = 4  where db103_sequencial = 3000881;
            update habitacao.avaliacaopergunta set db103_ordem = 5  where db103_sequencial = 3002360;
            update habitacao.avaliacaopergunta set db103_ordem = 7  where db103_sequencial = 3000882;
            update habitacao.avaliacaopergunta set db103_ordem = 9 where db103_sequencial = 4000250;
            update habitacao.avaliacaopergunta set db103_ordem = 10 where db103_sequencial = 4000251;
SQL;
        $this->execute($sql);
    }

    private function retornaOrdem()
    {
        $sql = <<<SQL
            update habitacao.avaliacaopergunta set db103_ordem = 2  where db103_sequencial = 3000877;
            update habitacao.avaliacaopergunta set db103_ordem = 4  where db103_sequencial = 3000879;
            update habitacao.avaliacaopergunta set db103_ordem = 6  where db103_sequencial = 3000881;
            update habitacao.avaliacaopergunta set db103_ordem = 7  where db103_sequencial = 3000882;
            update habitacao.avaliacaopergunta set db103_ordem = 8  where db103_sequencial = 3002360;
            update habitacao.avaliacaopergunta set db103_ordem = 11 where db103_sequencial = 4000250;
            update habitacao.avaliacaopergunta set db103_ordem = 12 where db103_sequencial = 4000251;

SQL;
        $this->execute($sql);
    }

    private function atualizaDescricao()
    {
        $sql = <<<SQL
            update habitacao.avaliacaopergunta set  db103_descricao = 'Informar a alíquota RAT, quando divergente da legislação vigente para a atividade (CNAE) preponderante. A divergência só é permitida se existir o grupo com informações sobre o processo administrativo/judicial que permite a aplicação de alíquota diferente.' where db103_sequencial = 3000921;
SQL;
        $this->execute($sql);
    }

    private function retornaDescricao()
    {
        $sql = <<<SQL
            update habitacao.avaliacaopergunta set  db103_descricao = 'Preencher com a alíquota definida na legislação vigente para a atividade (CNAE) preponderante.' where db103_sequencial = 3000921;
SQL;
        $this->execute($sql);
    }
}
