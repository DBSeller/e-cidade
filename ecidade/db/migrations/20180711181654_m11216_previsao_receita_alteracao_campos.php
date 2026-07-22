<?php

use Classes\PostgresMigration;

class M11216PrevisaoReceitaAlteracaoCampos extends PostgresMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function up()
    {
        $this->execute(<<<SQL
delete from avaliacaoresposta where db106_avaliacaoperguntaopcao = 3004122;
delete from avaliacaoperguntaopcaolayoutcampo where ed313_avaliacaoperguntaopcao = 3004122;
delete from avaliacaoperguntaopcao where db104_sequencial = 3004122;
delete from avaliacaopergunta where db103_sequencial = 3001099;
delete from avaliacaoresposta where db106_avaliacaoperguntaopcao = 3004123;
delete from avaliacaoperguntaopcaolayoutcampo where ed313_avaliacaoperguntaopcao = 3004123;
delete from avaliacaoperguntaopcao where db104_sequencial = 3004123;
delete from avaliacaopergunta where db103_sequencial = 3001100;
delete from avaliacaoresposta where db106_avaliacaoperguntaopcao = 3004125;
delete from avaliacaoperguntaopcaolayoutcampo where ed313_avaliacaoperguntaopcao = 3004125;
delete from avaliacaoperguntaopcao where db104_sequencial = 3004125;
delete from avaliacaopergunta where db103_sequencial = 3001102;
delete from avaliacaoresposta where db106_avaliacaoperguntaopcao = 3004128;
delete from avaliacaoperguntaopcaolayoutcampo where ed313_avaliacaoperguntaopcao = 3004128;
delete from avaliacaoperguntaopcao where db104_sequencial = 3004128;
delete from avaliacaopergunta where db103_sequencial = 3001105;
update avaliacao set db101_sequencial = 3000024 , db101_avaliacaotipo = 7 , db101_descricao = 'Previsão de Receita' , db101_identificador = 'previsao-de-receita5b3e07432a75d' , db101_obs = 'Formulário' , db101_ativo = 'true' , db101_permiteedicao = 'true' where db101_sequencial = 3000024;
update avaliacaogrupopergunta set db102_sequencial = 3000259 , db102_avaliacao = 3000024 , db102_descricao = 'Previsão' , db102_identificador = 'previsao5b3e074334812' , db102_identificadorcampo = 'previsao' , db102_ordem = 1 where db102_sequencial = 3000259;
update avaliacaopergunta set db103_sequencial = 3001095 , db103_avaliacaotiporesposta = 1 , db103_avaliacaogrupopergunta = 3000259 , db103_descricao = 'Esfera Orçamentária' , db103_identificador = 'esfera-orcamentaria5b3e07433603f' , db103_obrigatoria = 'true' , db103_ativo = 'true' , db103_ordem = 1 , db103_tipo = 1 , db103_perguntaidentificadora = 'false' , db103_identificadorcampo = 'esferaOrcamentaria' where db103_sequencial = 3001095;
delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 3001095;
update avaliacaoperguntaopcao set db104_sequencial = 3004115 , db104_avaliacaopergunta = 3001095 , db104_descricao = '10 - F - Orçamento Fiscal' , db104_identificador = '10-f-orcamento-fiscal5b3e074340658' , db104_aceitatexto = 'false' , db104_peso = 0 , db104_valorresposta = '10' , db104_identificadorcampo = 'opcaoEsfera10' where db104_sequencial = 3004115;
update avaliacaoperguntaopcao set db104_sequencial = 3004116 , db104_avaliacaopergunta = 3001095 , db104_descricao = '20 - S - Orçamento da Seguridade Social' , db104_identificador = '20-s-orcamento-da-seguridade-soci5b3e074344005' , db104_aceitatexto = 'false' , db104_peso = 0 , db104_valorresposta = '20' , db104_identificadorcampo = 'opcaoEsfera20' where db104_sequencial = 3004116;
update avaliacaoperguntaopcao set db104_sequencial = 3004117 , db104_avaliacaopergunta = 3001095 , db104_descricao = '30 - I - Orçamento de Investimento' , db104_identificador = '30-i-orcamento-de-investimento5b3e0743461c1' , db104_aceitatexto = 'false' , db104_peso = 0 , db104_valorresposta = '30' , db104_identificadorcampo = 'opcaoEsfera30' where db104_sequencial = 3004117;
update avaliacaopergunta set db103_sequencial = 3001096 , db103_avaliacaotiporesposta = 2 , db103_avaliacaogrupopergunta = 3000259 , db103_descricao = 'Unidade Orçamentária' , db103_identificador = 'unidade-orcamentaria5b3e07434780a' , db103_obrigatoria = 'true' , db103_ativo = 'true' , db103_ordem = 2 , db103_tipo = 1 , db103_perguntaidentificadora = 'false' , db103_identificadorcampo = 'unidadeOrcamentaria' where db103_sequencial = 3001096;
delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 3001096;
update avaliacaoperguntaopcao set db104_sequencial = 3004118 , db104_avaliacaopergunta = 3001096 , db104_identificador = '5b3e074349155' , db104_aceitatexto = 'true' , db104_peso = 0 where db104_sequencial = 3004118;
update avaliacaopergunta set db103_sequencial = 3001098 , db103_avaliacaotiporesposta = 1 , db103_avaliacaogrupopergunta = 3000259 , db103_descricao = 'Indicador Resultado Primário' , db103_identificador = 'indicador-resultado-primario5b3e07434be5e' , db103_obrigatoria = 'true' , db103_ativo = 'true' , db103_ordem = 3 , db103_tipo = 1 , db103_perguntaidentificadora = 'false' , db103_identificadorcampo = 'indicadorResultadoPrimario' where db103_sequencial = 3001098;
delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 3001098;
update avaliacaoperguntaopcao set db104_sequencial = 3004120 , db104_avaliacaopergunta = 3001098 , db104_descricao = 'Financeira' , db104_identificador = 'financeira5b3e07434d6ae' , db104_aceitatexto = 'false' , db104_peso = 0 , db104_valorresposta = '1' where db104_sequencial = 3004120;
update avaliacaoperguntaopcao set db104_sequencial = 3004121 , db104_avaliacaopergunta = 3001098 , db104_descricao = 'Primária' , db104_identificador = 'primaria5b3e07434e0e6' , db104_aceitatexto = 'false' , db104_peso = 0 , db104_valorresposta = '2' where db104_sequencial = 3004121;
update avaliacaogrupopergunta set db102_sequencial = 3000260 , db102_avaliacao = 3000024 , db102_descricao = 'Fonte de Recurso' , db102_identificador = 'fonte-de-recurso5b3e07434eb7e' , db102_identificadorcampo = 'fonteRecurso' , db102_ordem = 1 where db102_sequencial = 3000260;
insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3001111 ,1 ,3000260 ,'Identificador de Uso' ,'identificador-de-uso' ,'true' ,'true' ,1 ,1 ,'' ,0 ,'false' ,'' ,'id_uso' );
delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 3001111;
insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3004137 ,3001111 ,'0 - Recursos não destinados à contrapartida ou à identificação de despesas destinadas ao mínimo da Saúde ou ao mínimo da Educação' ,'0-recursos-nao-destinados-a-contrapartida-ou-a-ide' ,'false' ,0 ,'0' ,'id_uso_0' );
insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3004138 ,3001111 ,'1 -Contrapartida de empréstimos do BIRD' ,'1-contrapartida-de-emprestimos-do-bird' ,'false' ,0 ,'1' ,'id_uso_1' );
insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3004139 ,3001111 ,'2 - Contrapartida de empréstimos do BID' ,'2-contrapartida-de-emprestimos-do-bid' ,'false' ,0 ,'2' ,'id_uso_2' );
insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3004140 ,3001111 ,'3 - Contrapartida de empréstimos do CAF' ,'3-contrapartida-de-emprestimos-do-caf' ,'false' ,0 ,'3' ,'id_uso_3' );
insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3004141 ,3001111 ,'4 - Contrapartida de outros empréstimos' ,'4-contrapartida-de-outros-emprestimos' ,'false' ,0 ,'4' ,'id_uso_4' );
insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3004142 ,3001111 ,'5 - Contrapartida de doações' ,'5-contrapartida-de-doacoes' ,'false' ,0 ,'5' ,'id_uso_5' );
insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3004143 ,3001111 ,'6 - Recursos não destinados à contrapartida, para identificação das despesas destinadas ao mínimo da Saúde' ,'6-recursos-nao-destinados-a-contrapartida-para-ide' ,'false' ,0 ,'6' ,'id_uso_6' );
insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3004144 ,3001111 ,'7 - Recursos de Contrapartida de Convênio' ,'7-recursos-de-contrapartida-de-convenio' ,'false' ,0 ,'7' ,'id_uso_7' );
insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3004145 ,3001111 ,'8 - Recursos não destinados à contrapartida, para identificação das despesas destinadas ao mínimo da Educação' ,'8-recursos-nao-destinados-a-contrapartida-para-ide' ,'false' ,0 ,'8' ,'id_uso_8' );
update avaliacaopergunta set db103_sequencial = 3001106 , db103_avaliacaotiporesposta = 1 , db103_avaliacaogrupopergunta = 3000260 , db103_descricao = 'Tipo de detalhamento' , db103_identificador = 'previsaoTipoDetalhamento' , db103_obrigatoria = 'true' , db103_ativo = 'true' , db103_ordem = 2 , db103_tipo = 1 , db103_perguntaidentificadora = 'false' , db103_identificadorcampo = 'previsaoTipoDetalhamento' where db103_sequencial = 3001106;
delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 3001106;
update avaliacaoperguntaopcao set db104_sequencial = 3004129 , db104_avaliacaopergunta = 3001106 , db104_descricao = '0 - Sem Detalhamento' , db104_identificador = 'opcaotipoDeDetalhamento0b3e074340658' , db104_aceitatexto = 'false' , db104_peso = 0 , db104_valorresposta = '0' , db104_identificadorcampo = 'previsaoTipoDetalhamento_0' where db104_sequencial = 3004129;
update avaliacaoperguntaopcao set db104_sequencial = 3004130 , db104_avaliacaopergunta = 3001106 , db104_descricao = '1 - Cadastro' , db104_identificador = 'opcaotipoDeDetalhamento15b3e074344005' , db104_aceitatexto = 'false' , db104_peso = 0 , db104_valorresposta = '1' , db104_identificadorcampo = 'previsaoTipoDetalhamento_1' where db104_sequencial = 3004130;
update avaliacaoperguntaopcao set db104_sequencial = 3004131 , db104_avaliacaopergunta = 3001106 , db104_descricao = '2 - Operação de Crédito' , db104_identificador = 'opcaotipoDeDetalhamento2b3e0743461c1' , db104_aceitatexto = 'false' , db104_peso = 0 , db104_valorresposta = '2' , db104_identificadorcampo = 'previsaoTipoDetalhamento_2' where db104_sequencial = 3004131;
update avaliacaoperguntaopcao set db104_sequencial = 3004132 , db104_avaliacaopergunta = 3001106 , db104_descricao = '3 - Convênio' , db104_identificador = 'opcaotipoDeDetalhamento3b3e0743461c1' , db104_aceitatexto = 'false' , db104_peso = 0 , db104_valorresposta = '3' , db104_identificadorcampo = 'previsaoTipoDetalhamento_3' where db104_sequencial = 3004132;
insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3001112 ,1 ,3000260 ,'Grupo de Fonte de Recurso' ,'grupo-de-fonte-de-recurso' ,'true' ,'true' ,3 ,1 ,'' ,0 ,'false' ,'' ,'grupo_fonte_recurso' );
delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 3001112;
insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3004146 ,3001112 ,'1 - Recursos do Tesouro - Exercício Corrente' ,'1-recursos-do-tesouro-exercicio-corrente' ,'false' ,0 ,'1' ,'grupo_fonte_recurso_1' );
insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3004147 ,3001112 ,'2 - Recursos de Outras Fontes - Exercício Corrente' ,'2-recursos-de-outras-fontes-exercicio-corrente' ,'false' ,0 ,'2' ,'grupo_fonte_recurso_2' );
insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3001113 ,1 ,3000260 ,'Especificação de Fonte de Recurso' ,'especificacao-de-fonte-de-recurso' ,'true' ,'true' ,4 ,1 ,'' ,0 ,'false' ,'' ,'especificacao_fonte' );
delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 3001113;
insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3004148 ,3001113 ,'00 - Ordinários Não Provenientes de Impostos' ,'00-ordinarios-nao-provenientes-de-impostos' ,'false' ,0 ,'00' ,'especificacao_fonte_00' );
insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3004149 ,3001113 ,'01 - Operações de Crédito' ,'01-operacoes-de-credito' ,'false' ,0 ,'01' ,'especificacao_fonte_01' );
insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3004150 ,3001113 ,'02 - Recursos de Convênios' ,'02-recursos-de-convenios' ,'false' ,0 ,'02' ,'especificacao_fonte_02' );
insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3004151 ,3001113 ,'03 - Recursos Próprios Não Financeiros' ,'03-recursos-proprios-nao-financeiros' ,'false' ,0 ,'03' ,'especificacao_fonte_03' );
insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3004152 ,3001113 ,'05 - Contribuição do Salário-Educação' ,'05-contribuicao-do-salarioeducacao' ,'false' ,0 ,'05' ,'especificacao_fonte_05' );
insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3004153 ,3001113 ,'06 - Recursos Destinados à Alimentação Escolar' ,'06-recursos-destinados-a-alimentacao-escolar' ,'false' ,0 ,'06' ,'especificacao_fonte_06' );
insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3004154 ,3001113 ,'07 - Recursos do Sistema Único de Saúde' ,'07-recursos-do-sistema-unico-de-saude' ,'false' ,0 ,'07' ,'especificacao_fonte_07' );
insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3004155 ,3001113 ,'08 - Recursos do Fundo Nacional de Assistência Social' ,'08-recursos-do-fundo-nacional-de-assistencia-socia' ,'false' ,0 ,'08' ,'especificacao_fonte_08' );
insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3004156 ,3001113 ,'10 - Recursos Vinculados ao Fundo de Mobilidade' ,'10-recursos-vinculados-ao-fundo-de-mobilidade' ,'false' ,0 ,'10' ,'especificacao_fonte_10' );
insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3004157 ,3001113 ,'12 - Outorga Onerosa do Direito de Construir' ,'12-outorga-onerosa-do-direito-de-construir' ,'false' ,0 ,'12' ,'especificacao_fonte_12' );
insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3004158 ,3001113 ,'13 - Ordinários Provenientes de Impostos' ,'13-ordinarios-provenientes-de-impostos' ,'false' ,0 ,'13' ,'especificacao_fonte_13' );
insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3004159 ,3001113 ,'14 - Transferências Constitucionais Provenientes de Impostos' ,'14-transferencias-constitucionais-provenientes-de-' ,'false' ,0 ,'14' ,'especificacao_fonte_14' );
insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3004160 ,3001113 ,'15 - Recursos do Fundeb' ,'15-recursos-do-fundeb' ,'false' ,0 ,'15' ,'especificacao_fonte_15' );
insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3004161 ,3001113 ,'17 - Outras Transferências da União' ,'17-outras-transferencias-da-uniao' ,'false' ,0 ,'17' ,'especificacao_fonte_17' );
insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3004162 ,3001113 ,'18 - Recursos Vinculados à Previdência Municipal' ,'18-recursos-vinculados-a-previdencia-municipal' ,'false' ,0 ,'18' ,'especificacao_fonte_18' );
insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3004163 ,3001113 ,'36 - Recursos de Multas de Trânsito' ,'36-recursos-de-multas-de-transito' ,'false' ,0 ,'36' ,'especificacao_fonte_36' );
insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3004164 ,3001113 ,'37 - Contribuição sobre a Iluminação Pública' ,'37-contribuicao-sobre-a-iluminacao-publica' ,'false' ,0 ,'37' ,'especificacao_fonte_37' );
insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3004165 ,3001113 ,'38 - Compensação Financeira pela Exploração e Produção de Petróleo' ,'38-compensacao-financeira-pela-exploracao-e-produc' ,'false' ,0 ,'38' ,'especificacao_fonte_38' );
insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3004166 ,3001113 ,'53 - Taxas e Multas pelo Exercício do Poder de Polícia' ,'53-taxas-e-multas-pelo-exercicio-do-poder-de-polic' ,'false' ,0 ,'53' ,'especificacao_fonte_53' );
insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3004167 ,3001113 ,'80 - Remuneração das Disponibilidades do Tesouro' ,'80-remuneracao-das-disponibilidades-do-tesouro' ,'false' ,0 ,'80' ,'especificacao_fonte_80' );
insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3004168 ,3001113 ,'82 - Recursos Próprios Financeiros' ,'82-recursos-proprios-financeiros' ,'false' ,0 ,'82' ,'especificacao_fonte_82' );
insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3004169 ,3001113 ,'83 - Recursos de Alienação de Bens e Direitos do Patrimônio Público' ,'83-recursos-de-alienacao-de-bens-e-direitos-do-pat' ,'false' ,0 ,'83' ,'especificacao_fonte_83' );
insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3004170 ,3001113 ,'90 - Recursos do Tesouro - a Definir' ,'90-recursos-do-tesouro-a-definir' ,'false' ,0 ,'90' ,'especificacao_fonte_90' );
insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 3004171 ,3001113 ,'99 - Recursos Extraorçamentários' ,'99-recursos-extraorcamentarios' ,'false' ,0 ,'99' ,'especificacao_fonte_99' );
update avaliacaogrupopergunta set db102_sequencial = 3000261 , db102_avaliacao = 3000024 , db102_descricao = 'Valores' , db102_identificador = 'valoresa984r3dfa984xablau' , db102_identificadorcampo = 'previsaoValores' , db102_ordem = 1 where db102_sequencial = 3000261;
update avaliacaopergunta set db103_sequencial = 3001110 , db103_avaliacaotiporesposta = 2 , db103_avaliacaogrupopergunta = 3000261 , db103_descricao = 'Previsão 2019' , db103_identificador = 'previsaoPrevisao2019' , db103_obrigatoria = 'false' , db103_ativo = 'true' , db103_ordem = 1 , db103_tipo = 8 , db103_perguntaidentificadora = 'false' , db103_identificadorcampo = 'previsaoPrevisao2019' where db103_sequencial = 3001110;
delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 3001110;
update avaliacaoperguntaopcao set db104_sequencial = 3004136 , db104_avaliacaopergunta = 3001110 , db104_identificador = 'previsaoPrevisao2019_2' , db104_aceitatexto = 'true' , db104_peso = 0 , db104_identificadorcampo = 'null' where db104_sequencial = 3004136;
update avaliacaopergunta set db103_sequencial = 3001109 , db103_avaliacaotiporesposta = 2 , db103_avaliacaogrupopergunta = 3000261 , db103_descricao = 'Provável 2018' , db103_identificador = 'previsaoProvavel2018' , db103_obrigatoria = 'false' , db103_ativo = 'true' , db103_ordem = 2 , db103_tipo = 8 , db103_perguntaidentificadora = 'false' , db103_identificadorcampo = 'previsaoProvavel2018' where db103_sequencial = 3001109;
delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 3001109;
update avaliacaoperguntaopcao set db104_sequencial = 3004135 , db104_avaliacaopergunta = 3001109 , db104_identificador = 'previsaoProvavel2018_2' , db104_aceitatexto = 'true' , db104_peso = 0 , db104_identificadorcampo = 'null' where db104_sequencial = 3004135;
update avaliacaopergunta set db103_sequencial = 3001108 , db103_avaliacaotiporesposta = 2 , db103_avaliacaogrupopergunta = 3000261 , db103_descricao = 'Real 2017' , db103_identificador = 'previsaoReal2017' , db103_obrigatoria = 'false' , db103_ativo = 'true' , db103_ordem = 3 , db103_tipo = 8 , db103_perguntaidentificadora = 'false' , db103_identificadorcampo = 'previsaoReal2017' where db103_sequencial = 3001108;
delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 3001108;
update avaliacaoperguntaopcao set db104_sequencial = 3004134 , db104_avaliacaopergunta = 3001108 , db104_identificador = 'previsaoReal2017_2' , db104_aceitatexto = 'true' , db104_peso = 0 , db104_identificadorcampo = 'null' where db104_sequencial = 3004134;
SQL
        );
    }

    public function down()
    {
    }

}
