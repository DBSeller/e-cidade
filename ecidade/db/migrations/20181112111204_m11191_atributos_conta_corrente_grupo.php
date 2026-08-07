<?php

use Classes\PostgresMigration;

class M11191AtributosContaCorrenteGrupo extends PostgresMigration
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


        /**
         * Incluir todos os gerados
         */
        $this->execute("insert into conplanosistema values (3,  'Categoria Econômica' );");
        $this->execute("insert into conplanosistema values (4,  'Grupo de Natureza da Despesa' );");
        $this->execute("insert into conplanosistema values (5,  'Modalidade de Aplicação' );");
        $this->execute("insert into conplanosistema values (6,  'Elemento de Despesa' );");
        $this->execute("insert into conplanosistema values (7,  'Natureza da despesa ' );");
        $this->execute("insert into conplanosistema values (8,  'Natureza da despesa detalhada' );");
        $this->execute("insert into conplanosistema values (9,  'Unidade Orçamentária' );");
        $this->execute("insert into conplanosistema values (10,  'Domicílio Bancário' );");
        $this->execute("insert into conplanosistema values (11,  'Unidade Gestora' );");
        $this->execute("insert into conplanosistema values (12,  'Acordo / Contrato' );");
        $this->execute("insert into conplanosistema values (13,  'Credor' );");
        $this->execute("insert into conplanosistema values (14,  'Despesa Empenhada' );");
        $this->execute("insert into conplanosistema values (15,  'Despesa Liquidada' );");
        $this->execute("insert into conplanosistema values (16,  'Despesa Paga' );");

        $this->execute("select setval('conplanoinfocomplementar_c121_sequencial_seq', (select max(c121_sequencial) from conplanoinfocomplementar)::int);");

        $this->execute("insert into conplanoinfocomplementar values (nextval('conplanoinfocomplementar_c121_sequencial_seq'),  'CTE', 'Categoria Econômica', '', 'Identifica a Categoria Econômica de uma Natureza de Despesa. É definida pelo primeiro dígito da natureza sob o aspecto orçamentário (segundo dígito no estrutural cadastrado no E-cidade).', 'categoria_economica', 'NI' );");
        $this->execute("insert into conplanoinfocomplementar values (nextval('conplanoinfocomplementar_c121_sequencial_seq'),  'UG', 'Unidade Gestora', '', 'Código da Unidade Gestora configurada e vinculada aos seus departamentos subordinados.', 'unidade_gestora', 'NI' );");
        $this->execute("insert into conplanoinfocomplementar values (nextval('conplanoinfocomplementar_c121_sequencial_seq'),  'GND', 'Grupo de Natureza da Despesa', '', 'Identifica o Grupo de Natureza da Despesa. É definido pelo segundo dígito da natureza sob o aspecto orçamentário (terceiro dígito no estrutural cadastrado no E-cidade).', 'grupo_natureza_despesa', 'NI' );");
        $this->execute("insert into conplanoinfocomplementar values (nextval('conplanoinfocomplementar_c121_sequencial_seq'),  'MOD', 'Modalidade de Aplicação', '', 'Identifica a Modalidade de aplicação correspondente a uma natureza de despesa. É definida pelos terceiro e quarto dígitos da natureza sob o aspecto orçamentário (quarto e quinto dígitos no estrutural cadastrado no E-cidade).', 'modalidade_aplicacao', 'NI' );");
        $this->execute("insert into conplanoinfocomplementar values (nextval('conplanoinfocomplementar_c121_sequencial_seq'),  'ELE', 'Elemento de Despesa', '', 'Identifica o código do elemento correspondente a uma natureza de despesa. É definida pelos quinto e sexto dígitos da natureza sob o aspecto orçamentário (sexto e sétimo dígitos no estrutural cadastrado no E-cidade).', 'elemento_despesa', 'NI' );");
        $this->execute("insert into conplanoinfocomplementar values (nextval('conplanoinfocomplementar_c121_sequencial_seq'),  'SBELE', 'Desdobramento da Despesa', '', 'Identifica o código do desdobramento correspondente a uma natureza de despesa. O desdobramento ou subelemento estará presente na fase de execução orçamentária e é definido pelos sétimo e oitavo dígitos da natureza sob o aspecto orçamentário (oitavo e nono dígitos no estrutural cadastrado no E-cidade). Os desdobramentos podem, inclusive serem abertos em niveis de maior detalhamento no plano de contas orçamentário.', 'desdobramento_despesa', 'NI' );");
        $this->execute("insert into conplanoinfocomplementar values (nextval('conplanoinfocomplementar_c121_sequencial_seq'),  'ORG', 'Órgão', '', '', 'orgao', 'NI' );");
        $this->execute("insert into conplanoinfocomplementar values (nextval('conplanoinfocomplementar_c121_sequencial_seq'),  'UO', 'Unidade Orçamentária', '', '99', 'unidade_orcamentaria', 'NI' );");
        $this->execute("insert into conplanoinfocomplementar values (nextval('conplanoinfocomplementar_c121_sequencial_seq'),  'BCO', 'Código do banco', '', 'Representa a codificação que identifica a Instituição Bancária no cadastro da FEBRABAN.', 'codigo_banco', 'NI' );");
        $this->execute("insert into conplanoinfocomplementar values (nextval('conplanoinfocomplementar_c121_sequencial_seq'),  'AGE', 'Código da agência', '', 'Identifica o código da agência da Instituição Bancária da titularidade do cliente.', 'codigo_agencia', 'NI' );");
        $this->execute("insert into conplanoinfocomplementar values (nextval('conplanoinfocomplementar_c121_sequencial_seq'),  'CTA', 'Código da conta corrente', '', 'Identifica o código da conta corrente de titularidade do cliente', 'codigo_conta_corrente', 'NI' );");
        $this->execute("insert into conplanoinfocomplementar values (nextval('conplanoinfocomplementar_c121_sequencial_seq'),  'CNPJ', 'CNPJ da conta corrente', '', 'Identifica o CNPJ do cliente titular da conta corrente bancária.', 'cnpj_conta_corrente', 'NI' );");
        $this->execute("insert into conplanoinfocomplementar values (nextval('conplanoinfocomplementar_c121_sequencial_seq'),  'CTR', 'Número do contrato', '', 'identifica o código do acordo / contrato constante na movimentação contábil.', 'numero_contrato', 'NI' );");
        $this->execute("insert into conplanoinfocomplementar values (nextval('conplanoinfocomplementar_c121_sequencial_seq'),  'ANO', 'Ano', '', 'Identifica o exercício de origem do acordo / contrato.', 'ano_acordo', 'NI' );");
        $this->execute("insert into conplanoinfocomplementar values (nextval('conplanoinfocomplementar_c121_sequencial_seq'),  'CRE', 'CGM', '', 'Código do Credor do contrato cadastrado no CGM.', 'codigo_cgm_contrato', 'NI' );");
        $this->execute("insert into conplanoinfocomplementar values (nextval('conplanoinfocomplementar_c121_sequencial_seq'),  'CRE', 'CGM', '', 'Código do credor no cadastro geral do município', 'nome_cgm_credor', 'NI' );");
        $this->execute("insert into conplanoinfocomplementar values (nextval('conplanoinfocomplementar_c121_sequencial_seq'),  'IDE', 'CNPJ/CPF', '', 'CNPJ/CPF do credor no cadastro geral do município', 'cpf_cnj_credor', 'NI' );");
        $this->execute("insert into conplanoinfocomplementar values (nextval('conplanoinfocomplementar_c121_sequencial_seq'),  'GEN', 'Código da Inscrição genérica', '', 'Código da Inscrição Genérica atribuída ao Credor no cadastro de fornecedores do E-cidade.', 'codigo_inscricao_generica', 'NI' );");
        $this->execute("insert into conplanoinfocomplementar values (nextval('conplanoinfocomplementar_c121_sequencial_seq'),  'ANO', 'Ano do Empenho', '', 'Exercício do Empenho', 'ano_empenho', 'NI' );");
        $this->execute("insert into conplanoinfocomplementar values (nextval('conplanoinfocomplementar_c121_sequencial_seq'),  'NE', 'Nº da Nota de Empenho', '', 'Número do Empenho', 'numero_nota_empenho', 'NI' );");
        $this->execute("insert into conplanoinfocomplementar values (nextval('conplanoinfocomplementar_c121_sequencial_seq'),  'NL', 'Nº da Nota de Liquidação', '', 'Código da Nota de Liquidação do empenho.', 'numero_nota_liquidacao', 'NI' );");
        $this->execute("insert into conplanoinfocomplementar values (nextval('conplanoinfocomplementar_c121_sequencial_seq'),  'OP', 'Nº da Ordem de Pagamento', '', 'Código da Ordem de Pagamento do empenho.', 'numero_ordem_pagamento', 'NI' );");

        $this->execute("insert into conplanosistemaatributos values (nextval('conplanosistemaatributos_c129_sequencial_seq'), 3,(select c121_sequencial from conplanoinfocomplementar where c121_nomepropriedade = 'categoria_economica'), 1)");
        $this->execute("insert into conplanosistemaatributos values (nextval('conplanosistemaatributos_c129_sequencial_seq'), 3,(select c121_sequencial from conplanoinfocomplementar where c121_nomepropriedade = 'unidade_gestora'), 2)");

        $this->execute("insert into conplanosistemaatributos values (nextval('conplanosistemaatributos_c129_sequencial_seq'), 4,(select c121_sequencial from conplanoinfocomplementar where c121_nomepropriedade = 'grupo_natureza_despesa'), 1)");
        $this->execute("insert into conplanosistemaatributos values (nextval('conplanosistemaatributos_c129_sequencial_seq'), 4,(select c121_sequencial from conplanoinfocomplementar where c121_nomepropriedade = 'unidade_gestora'), 2)");

        $this->execute("insert into conplanosistemaatributos values (nextval('conplanosistemaatributos_c129_sequencial_seq'), 5,(select c121_sequencial from conplanoinfocomplementar where c121_nomepropriedade = 'modalidade_aplicacao'), 1)");
        $this->execute("insert into conplanosistemaatributos values (nextval('conplanosistemaatributos_c129_sequencial_seq'), 5,(select c121_sequencial from conplanoinfocomplementar where c121_nomepropriedade = 'unidade_gestora'), 2)");

        $this->execute("insert into conplanosistemaatributos values (nextval('conplanosistemaatributos_c129_sequencial_seq'), 6,(select c121_sequencial from conplanoinfocomplementar where c121_nomepropriedade = 'elemento_despesa'), 1)");
        $this->execute("insert into conplanosistemaatributos values (nextval('conplanosistemaatributos_c129_sequencial_seq'), 6,(select c121_sequencial from conplanoinfocomplementar where c121_nomepropriedade = 'unidade_gestora'), 2)");

        $this->execute("insert into conplanosistemaatributos values (nextval('conplanosistemaatributos_c129_sequencial_seq'), 7,(select c121_sequencial from conplanoinfocomplementar where c121_nomepropriedade = 'categoria_economica'), 1)");
        $this->execute("insert into conplanosistemaatributos values (nextval('conplanosistemaatributos_c129_sequencial_seq'), 7,(select c121_sequencial from conplanoinfocomplementar where c121_nomepropriedade = 'grupo_natureza_despesa'), 2)");
        $this->execute("insert into conplanosistemaatributos values (nextval('conplanosistemaatributos_c129_sequencial_seq'), 7,(select c121_sequencial from conplanoinfocomplementar where c121_nomepropriedade = 'modalidade_aplicacao'), 3)");
        $this->execute("insert into conplanosistemaatributos values (nextval('conplanosistemaatributos_c129_sequencial_seq'), 7,(select c121_sequencial from conplanoinfocomplementar where c121_nomepropriedade = 'elemento_despesa'), 4)");
        $this->execute("insert into conplanosistemaatributos values (nextval('conplanosistemaatributos_c129_sequencial_seq'), 7,(select c121_sequencial from conplanoinfocomplementar where c121_nomepropriedade = 'unidade_gestora'), 5)");

        $this->execute("insert into conplanosistemaatributos values (nextval('conplanosistemaatributos_c129_sequencial_seq'), 8,(select c121_sequencial from conplanoinfocomplementar where c121_nomepropriedade = 'categoria_economica'), 1)");
        $this->execute("insert into conplanosistemaatributos values (nextval('conplanosistemaatributos_c129_sequencial_seq'), 8,(select c121_sequencial from conplanoinfocomplementar where c121_nomepropriedade = 'grupo_natureza_despesa'), 2)");
        $this->execute("insert into conplanosistemaatributos values (nextval('conplanosistemaatributos_c129_sequencial_seq'), 8,(select c121_sequencial from conplanoinfocomplementar where c121_nomepropriedade = 'modalidade_aplicacao'), 3)");
        $this->execute("insert into conplanosistemaatributos values (nextval('conplanosistemaatributos_c129_sequencial_seq'), 8,(select c121_sequencial from conplanoinfocomplementar where c121_nomepropriedade = 'elemento_despesa'), 4)");
        $this->execute("insert into conplanosistemaatributos values (nextval('conplanosistemaatributos_c129_sequencial_seq'), 8,(select c121_sequencial from conplanoinfocomplementar where c121_nomepropriedade = 'desdobramento_despesa'), 5)");
        $this->execute("insert into conplanosistemaatributos values (nextval('conplanosistemaatributos_c129_sequencial_seq'), 8,(select c121_sequencial from conplanoinfocomplementar where c121_nomepropriedade = 'unidade_gestora'), 6)");

        $this->execute("insert into conplanosistemaatributos values (nextval('conplanosistemaatributos_c129_sequencial_seq'), 9,(select c121_sequencial from conplanoinfocomplementar where c121_nomepropriedade = 'orgao'), 1)");
        $this->execute("insert into conplanosistemaatributos values (nextval('conplanosistemaatributos_c129_sequencial_seq'), 9,(select c121_sequencial from conplanoinfocomplementar where c121_nomepropriedade = 'unidade_orcamentaria'), 2)");
        $this->execute("insert into conplanosistemaatributos values (nextval('conplanosistemaatributos_c129_sequencial_seq'), 9,(select c121_sequencial from conplanoinfocomplementar where c121_nomepropriedade = 'unidade_gestora'), 3)");

        $this->execute("insert into conplanosistemaatributos values (nextval('conplanosistemaatributos_c129_sequencial_seq'), 10,(select c121_sequencial from conplanoinfocomplementar where c121_nomepropriedade = 'codigo_banco'), 1)");
        $this->execute("insert into conplanosistemaatributos values (nextval('conplanosistemaatributos_c129_sequencial_seq'), 10,(select c121_sequencial from conplanoinfocomplementar where c121_nomepropriedade = 'codigo_agencia'), 2)");
        $this->execute("insert into conplanosistemaatributos values (nextval('conplanosistemaatributos_c129_sequencial_seq'), 10,(select c121_sequencial from conplanoinfocomplementar where c121_nomepropriedade = 'codigo_conta_corrente'), 3)");
        $this->execute("insert into conplanosistemaatributos values (nextval('conplanosistemaatributos_c129_sequencial_seq'), 10,(select c121_sequencial from conplanoinfocomplementar where c121_nomepropriedade = 'cnpj_conta_corrente'), 4)");
        $this->execute("insert into conplanosistemaatributos values (nextval('conplanosistemaatributos_c129_sequencial_seq'), 10,(select c121_sequencial from conplanoinfocomplementar where c121_nomepropriedade = 'unidade_gestora'), 5)");

        $this->execute("insert into conplanosistemaatributos values (nextval('conplanosistemaatributos_c129_sequencial_seq'), 11,(select c121_sequencial from conplanoinfocomplementar where c121_nomepropriedade = 'unidade_gestora'), 1)");

        $this->execute("insert into conplanosistemaatributos values (nextval('conplanosistemaatributos_c129_sequencial_seq'), 12,(select c121_sequencial from conplanoinfocomplementar where c121_nomepropriedade = 'numero_contrato'), 1)");
        $this->execute("insert into conplanosistemaatributos values (nextval('conplanosistemaatributos_c129_sequencial_seq'), 12,(select c121_sequencial from conplanoinfocomplementar where c121_nomepropriedade = 'ano_acordo'), 2)");
        $this->execute("insert into conplanosistemaatributos values (nextval('conplanosistemaatributos_c129_sequencial_seq'), 12,(select c121_sequencial from conplanoinfocomplementar where c121_nomepropriedade = 'codigo_cgm_contrato'), 3)");
        $this->execute("insert into conplanosistemaatributos values (nextval('conplanosistemaatributos_c129_sequencial_seq'), 12,(select c121_sequencial from conplanoinfocomplementar where c121_nomepropriedade = 'unidade_gestora'), 4)");

        $this->execute("insert into conplanosistemaatributos values (nextval('conplanosistemaatributos_c129_sequencial_seq'), 13,(select c121_sequencial from conplanoinfocomplementar where c121_nomepropriedade = 'nome_cgm_credor'), 1)");
        $this->execute("insert into conplanosistemaatributos values (nextval('conplanosistemaatributos_c129_sequencial_seq'), 13,(select c121_sequencial from conplanoinfocomplementar where c121_nomepropriedade = 'cpf_cnj_credor'), 2)");
        $this->execute("insert into conplanosistemaatributos values (nextval('conplanosistemaatributos_c129_sequencial_seq'), 13,(select c121_sequencial from conplanoinfocomplementar where c121_nomepropriedade = 'codigo_inscricao_generica'), 3)");
        $this->execute("insert into conplanosistemaatributos values (nextval('conplanosistemaatributos_c129_sequencial_seq'), 13,(select c121_sequencial from conplanoinfocomplementar where c121_nomepropriedade = 'unidade_gestora'), 4)");

        $this->execute("insert into conplanosistemaatributos values (nextval('conplanosistemaatributos_c129_sequencial_seq'), 14,(select c121_sequencial from conplanoinfocomplementar where c121_nomepropriedade = 'ano_empenho'), 1)");
        $this->execute("insert into conplanosistemaatributos values (nextval('conplanosistemaatributos_c129_sequencial_seq'), 14,(select c121_sequencial from conplanoinfocomplementar where c121_nomepropriedade = 'numero_nota_empenho'), 2)");
        $this->execute("insert into conplanosistemaatributos values (nextval('conplanosistemaatributos_c129_sequencial_seq'), 14,(select c121_sequencial from conplanoinfocomplementar where c121_nomepropriedade = 'unidade_gestora'), 3)");

        $this->execute("insert into conplanosistemaatributos values (nextval('conplanosistemaatributos_c129_sequencial_seq'), 15,(select c121_sequencial from conplanoinfocomplementar where c121_nomepropriedade = 'ano_empenho'), 1)");
        $this->execute("insert into conplanosistemaatributos values (nextval('conplanosistemaatributos_c129_sequencial_seq'), 15,(select c121_sequencial from conplanoinfocomplementar where c121_nomepropriedade = 'numero_nota_empenho'), 2)");
        $this->execute("insert into conplanosistemaatributos values (nextval('conplanosistemaatributos_c129_sequencial_seq'), 15,(select c121_sequencial from conplanoinfocomplementar where c121_nomepropriedade = 'numero_nota_liquidacao'), 3)");
        $this->execute("insert into conplanosistemaatributos values (nextval('conplanosistemaatributos_c129_sequencial_seq'), 15,(select c121_sequencial from conplanoinfocomplementar where c121_nomepropriedade = 'unidade_gestora'), 4)");

        $this->execute("insert into conplanosistemaatributos values (nextval('conplanosistemaatributos_c129_sequencial_seq'), 16,(select c121_sequencial from conplanoinfocomplementar where c121_nomepropriedade = 'ano_empenho'), 1)");
        $this->execute("insert into conplanosistemaatributos values (nextval('conplanosistemaatributos_c129_sequencial_seq'), 16,(select c121_sequencial from conplanoinfocomplementar where c121_nomepropriedade = 'numero_nota_empenho'), 2)");
        $this->execute("insert into conplanosistemaatributos values (nextval('conplanosistemaatributos_c129_sequencial_seq'), 16,(select c121_sequencial from conplanoinfocomplementar where c121_nomepropriedade = 'numero_nota_liquidacao'), 3)");
        $this->execute("insert into conplanosistemaatributos values (nextval('conplanosistemaatributos_c129_sequencial_seq'), 16,(select c121_sequencial from conplanoinfocomplementar where c121_nomepropriedade = 'numero_ordem_pagamento'), 4)");
        $this->execute("insert into conplanosistemaatributos values (nextval('conplanosistemaatributos_c129_sequencial_seq'), 16,(select c121_sequencial from conplanoinfocomplementar where c121_nomepropriedade = 'unidade_gestora'), 5)");
        /**
         * Atualizar dados do siconfi
         */
        $this->execute("update conplanoinfocomplementar set c121_nomepropriedade = 'atributo_po' where c121_sequencial = 1");
        $this->execute("update conplanoinfocomplementar set c121_nomepropriedade = 'atributo_fp' where c121_sequencial = 2");
        $this->execute("update conplanoinfocomplementar set c121_nomepropriedade = 'atributo_fr' where c121_sequencial = 3");
        $this->execute("update conplanoinfocomplementar set c121_nomepropriedade = 'atributo_nr' where c121_sequencial = 4");
        $this->execute("update conplanoinfocomplementar set c121_nomepropriedade = 'atributo_nd' where c121_sequencial = 5");
        $this->execute("update conplanoinfocomplementar set c121_nomepropriedade = 'atributo_fs' where c121_sequencial = 6");
        $this->execute("update conplanoinfocomplementar set c121_nomepropriedade = 'atributo_dc' where c121_sequencial = 7");

        $this->execute("update contabilidade.conplanosistema set c122_tipo = 2 where c122_sequencial > 1");

        /**
         * Ajustar os sqls:
         */
        $this->execute("update conplanoinfocomplementar 
                       set c121_sql = 'SELECT codtrib::varchar AS infocomplementar_valor FROM db_config WHERE codigo = instituicao' 
                     where c121_sequencial = 1");
        $this->execute("update conplanoinfocomplementar 
                       set c121_sql = 'SELECT c60_naturezasaldo as infocomplementar_valor 
                 FROM conplanoreduz                                 
                INNER JOIN conplano ON c61_codcon = c60_codcon     
                                   AND c61_anousu = c60_anousu                     
                WHERE c61_reduz = conta_reduzida                
                  AND c61_anousu = anousu                         
                  AND c61_instit = instituicao' 
                     where c121_sequencial = 2");
        $this->execute("update conplanoinfocomplementar 
                       set c121_sql = 'SELECT distinct (
                        CASE 
                             WHEN c74_codlan IS NOT NULL AND c53_tipo in(100, 101) THEN lpad(orcreceita.o70_codigo::varchar, 4, ''0'')
                             WHEN c75_codlan IS NOT NULL AND c71_coddoc not in (6002,6003, 6004, 6005, 6008, 6009, 6010, 6011) AND c53_tipo in(30, 31) THEN lpad(c61_codigo::varchar, 4, ''0'')
                             WHEN c71_coddoc in ( 6002,6003, 6004, 6005, 6008, 6009, 6010, 6011) THEN lpad(dotemp.o58_codigo::varchar, 4, ''0'')
                             WHEN c75_codlan IS NOT NULL AND c53_tipo NOT in(30, 31) THEN lpad(dotemp.o58_codigo::varchar, 4, ''0'')         
                             WHEN c73_codlan IS NOT NULL AND c53_tipo NOT in(30, 31) THEN lpad(dotlan.o58_codigo::varchar, 4, ''0'')         
                             WHEN c74_codrec IS NOT NULL and dotrec.o58_codigo is not null THEN lpad(dotrec.o58_codigo::varchar, 4, ''0'')   
                             WHEN c74_codrec IS NOT NULL THEN lpad(o70_codigo::varchar, 4, ''0'')                                            
                             WHEN recursopagdebito.c61_reduz IS NOT NULL THEN lpad(c61_codigo::varchar, 4, ''0'')                            
                             ELSE (SELECT lpad(c61_codigo::varchar, 4, ''0'')                                                                
                                   FROM conplanoreduz                                                                                      
                                   WHERE c61_reduz = conta_reduzida                                                                     
                                   AND c61_anousu = anousu)                                                                                
                        END
                    ) AS infocomplementar_valor                                                                                     
               FROM conlancam                                                                                                             
                    INNER JOIN conlancamdoc ON c71_codlan = c70_codlan                                                                   
                    INNER JOIN conhistdoc ON c53_coddoc = c71_coddoc                                                                   
                    LEFT JOIN conlancamemp ON c75_codlan = c70_codlan                                                                   
                    LEFT JOIN empempenho empemp1 ON c75_numemp = empemp1.e60_numemp                                                    
                    LEFT JOIN orcdotacao dotemp ON empemp1.e60_coddot = dotemp.o58_coddot                                                
                                               AND empemp1.e60_anousu = dotemp.o58_anousu                                               
                    LEFT JOIN conlancamdot ON c73_codlan = c70_codlan                                                                    
                    LEFT JOIN orcdotacao dotlan ON c73_coddot = dotlan.o58_coddot                                                        
                                                AND c73_anousu = dotlan.o58_anousu                                                       
                    LEFT JOIN conlancamrec ON c74_codlan = c70_codlan                                                                    
                    LEFT JOIN orcreceita ON c74_codrec = o70_codrec                                                                      
                                        AND c74_anousu = o70_anousu                                                                     
                    LEFT JOIN conlancampag ON c82_codlan = c70_codlan                                                                    
                    LEFT JOIN conplanoreduz AS recursopagdebito ON c82_reduz = recursopagdebito.c61_reduz                                
                                                               AND c82_anousu = recursopagdebito.c61_anousu                             
                    LEFT JOIN conlancamcorrente conlancorr1 ON conlancorr1.c86_conlancam =  c70_codlan                                   
                    LEFT JOIN corgrupocorrente corgrpcor1 ON corgrpcor1.k105_data = conlancorr1.c86_data                                 
                                                         AND corgrpcor1.k105_autent = conlancorr1.c86_autent                           
                                                         AND corgrpcor1.k105_id = conlancorr1.c86_id                                   
                                                         AND corgrpcor1.k105_corgrupotipo = 3                                          
                    LEFT JOIN corgrupocorrente corgrpcor2 ON corgrpcor2.k105_corgrupo = corgrpcor1.k105_corgrupo                         
                                                         AND corgrpcor2.k105_corgrupotipo = 1                                           
                    LEFT JOIN coremp ON k12_id = corgrpcor2.k105_id                                                                 
                                    AND k12_data = corgrpcor2.k105_data                                                               
                                    AND k12_autent = corgrpcor2.k105_autent                                                             
                    LEFT JOIN empempenho empemp2 ON  k12_empen = empemp2.e60_numemp                                                      
                    LEFT JOIN orcdotacao dotrec ON empemp2.e60_coddot = dotrec.o58_coddot                                               
                                               AND empemp2.e60_anousu = dotrec.o58_anousu                                              
                                                                                                                                         
              WHERE c70_codlan = codigo_lancamento' 
                     where c121_sequencial = 3");
        $this->execute("update conplanoinfocomplementar 
                       set c121_sql = 'SELECT distinct orcfontes.o57_fonte AS infocomplementar_valor             
                 FROM contabilidade.conlancam                                     
                      INNER JOIN contabilidade.conlancamdoc ON c71_codlan = c70_codlan 
                      INNER JOIN contabilidade.conhistdoc ON c53_coddoc = c71_coddoc   
                      LEFT JOIN contabilidade.conlancamrec ON c74_codlan = c70_codlan  
                      LEFT JOIN orcamento.orcreceita ON c74_codrec = o70_codrec        
                                                    AND c74_anousu = o70_anousu                                  
                      LEFT JOIN orcamento.orcfontes  ON o57_codfon = o70_Codfon         
                                                    AND o57_anousu = o70_anousu                                  
                WHERE c70_Codlan = codigo_lancamento' 
                     where c121_sequencial = 4");

        $this->execute("update conplanoinfocomplementar 
                       set c121_sql = 'SELECT distinct (CASE WHEN c75_codlan IS NOT NULL THEN eleemp.o56_elemento::varchar
                           ELSE eledot.o56_elemento::varchar END) AS infocomplementar_valor      
                FROM conlancam                                                            
                     INNER JOIN conlancamdoc ON c71_codlan = c70_codlan                        
                     INNER JOIN conhistdoc ON c53_coddoc = c71_coddoc                          
                     LEFT JOIN conlancamemp ON c75_codlan = c70_codlan                         
                     LEFT JOIN empempenho ON c75_numemp = e60_numemp                           
                     LEFT JOIN orcdotacao dotemp ON e60_coddot = dotemp.o58_coddot             
                                                AND e60_anousu = dotemp.o58_anousu                                    
                     LEFT JOIN orcelemento eleemp ON dotemp.o58_codele = eleemp.o56_codele     
                                                 AND dotemp.o58_anousu = eleemp.o56_anousu                             
                     LEFT JOIN conlancamdot       ON c73_codlan = c70_codlan                         
                     LEFT JOIN orcdotacao dotlan  ON c73_coddot = dotlan.o58_coddot             
                                                  AND c73_anousu = dotlan.o58_anousu                                    
                     LEFT JOIN orcelemento eledot  ON dotlan.o58_codele = eledot.o56_codele     
                                                  AND dotlan.o58_anousu = eledot.o56_anousu                             
              WHERE c70_Codlan = codigo_lancamento' 
                     where c121_sequencial = 5");

        $this->execute("update conplanoinfocomplementar 
                       set c121_sql = 'SELECT distinct (CASE WHEN c75_codlan IS NOT NULL 
                            THEN lpad(dotemp.o58_funcao, 2, ''0'')::varchar||lpad(dotemp.o58_subfuncao, 3, ''0'')::varchar
                            ELSE lpad(dotlan.o58_funcao, 2, ''0'')::varchar||lpad(dotlan.o58_subfuncao, 3, ''0'')::varchar END) AS infocomplementar_valor      
                 FROM conlancam                                                                                                                     
                      INNER JOIN conlancamdoc ON c71_codlan = c70_codlan                                                                                 
                      INNER JOIN conhistdoc ON c53_coddoc = c71_coddoc                                                                                   
                      LEFT JOIN conlancamemp ON c75_codlan = c70_codlan                                                                                  
                      LEFT JOIN empempenho ON c75_numemp = e60_numemp                                                                                    
                      LEFT JOIN orcdotacao dotemp ON e60_coddot = dotemp.o58_coddot                                                                      
                                                 AND e60_anousu = dotemp.o58_anousu                                                                                             
                      LEFT JOIN conlancamdot ON c73_codlan = c70_codlan                                                                                  
                      LEFT JOIN orcdotacao dotlan ON c73_coddot = dotlan.o58_coddot                                                                      
                                                 AND c73_anousu = dotlan.o58_anousu                                                                                             
                WHERE c70_Codlan = codigo_lancamento' 
                     where c121_sequencial = 6");

        $this->execute("update conplanoinfocomplementar 
                       set c121_sql = 'select (CASE WHEN c60_codsis = 9 THEN 0 ELSE 1 END) AS infocomplementar_valor  from conplano where c60_codcon =  conta and  c60_anousu = anousu' 
                     where c121_sequencial = 7");


        $this->execute("update conplanoinfocomplementar 
                       set c121_sql = 'SELECT distinct substr((CASE WHEN c75_codlan IS NOT NULL THEN eleemp.o56_elemento::varchar
                           ELSE eledot.o56_elemento::varchar END), 2, 1) AS infocomplementar_valor
                FROM conlancam
                     INNER JOIN conlancamdoc ON c71_codlan = c70_codlan
                     INNER JOIN conhistdoc ON c53_coddoc = c71_coddoc
                     LEFT JOIN conlancamemp ON c75_codlan = c70_codlan
                     LEFT JOIN empempenho ON c75_numemp = e60_numemp
                     LEFT JOIN orcdotacao dotemp ON e60_coddot = dotemp.o58_coddot
    AND e60_anousu = dotemp.o58_anousu
                     LEFT JOIN orcelemento eleemp ON dotemp.o58_codele = eleemp.o56_codele
    AND dotemp.o58_anousu = eleemp.o56_anousu
                     LEFT JOIN conlancamdot       ON c73_codlan = c70_codlan
                     LEFT JOIN orcdotacao dotlan  ON c73_coddot = dotlan.o58_coddot
    AND c73_anousu = dotlan.o58_anousu
                     LEFT JOIN orcelemento eledot  ON dotlan.o58_codele = eledot.o56_codele
    AND dotlan.o58_anousu = eledot.o56_anousu
              WHERE c70_Codlan = codigo_lancamento'
                     where c121_nomepropriedade = 'categoria_economica'");

        $this->execute("update conplanoinfocomplementar 
                       set c121_sql = 'SELECT distinct substr((CASE WHEN c75_codlan IS NOT NULL THEN eleemp.o56_elemento::varchar
                           ELSE eledot.o56_elemento::varchar END), 3, 1) AS infocomplementar_valor
                FROM conlancam
                     INNER JOIN conlancamdoc ON c71_codlan = c70_codlan
                     INNER JOIN conhistdoc ON c53_coddoc = c71_coddoc
                     LEFT JOIN conlancamemp ON c75_codlan = c70_codlan
                     LEFT JOIN empempenho ON c75_numemp = e60_numemp
                     LEFT JOIN orcdotacao dotemp ON e60_coddot = dotemp.o58_coddot
    AND e60_anousu = dotemp.o58_anousu
                     LEFT JOIN orcelemento eleemp ON dotemp.o58_codele = eleemp.o56_codele
    AND dotemp.o58_anousu = eleemp.o56_anousu
                     LEFT JOIN conlancamdot       ON c73_codlan = c70_codlan
                     LEFT JOIN orcdotacao dotlan  ON c73_coddot = dotlan.o58_coddot
    AND c73_anousu = dotlan.o58_anousu
                     LEFT JOIN orcelemento eledot  ON dotlan.o58_codele = eledot.o56_codele
    AND dotlan.o58_anousu = eledot.o56_anousu
              WHERE c70_Codlan = codigo_lancamento' 
                     where c121_nomepropriedade = 'grupo_natureza_despesa'");

        $this->execute("update conplanoinfocomplementar 
                       set c121_sql = 'SELECT distinct substr((CASE WHEN c75_codlan IS NOT NULL THEN eleemp.o56_elemento::varchar
                           ELSE eledot.o56_elemento::varchar END), 4, 2) AS infocomplementar_valor
                FROM conlancam
                     INNER JOIN conlancamdoc ON c71_codlan = c70_codlan
                     INNER JOIN conhistdoc ON c53_coddoc = c71_coddoc
                     LEFT JOIN conlancamemp ON c75_codlan = c70_codlan
                     LEFT JOIN empempenho ON c75_numemp = e60_numemp
                     LEFT JOIN orcdotacao dotemp ON e60_coddot = dotemp.o58_coddot
    AND e60_anousu = dotemp.o58_anousu
                     LEFT JOIN orcelemento eleemp ON dotemp.o58_codele = eleemp.o56_codele
    AND dotemp.o58_anousu = eleemp.o56_anousu
                     LEFT JOIN conlancamdot       ON c73_codlan = c70_codlan
                     LEFT JOIN orcdotacao dotlan  ON c73_coddot = dotlan.o58_coddot
    AND c73_anousu = dotlan.o58_anousu
                     LEFT JOIN orcelemento eledot  ON dotlan.o58_codele = eledot.o56_codele
    AND dotlan.o58_anousu = eledot.o56_anousu
              WHERE c70_Codlan = codigo_lancamento' 
                     where c121_nomepropriedade = 'modalidade_aplicacao'");
        $this->execute("update conplanoinfocomplementar 
                       set c121_sql = 'SELECT distinct substr((CASE WHEN c75_codlan IS NOT NULL THEN eleemp.o56_elemento::varchar
                           ELSE eledot.o56_elemento::varchar END), 6, 2) AS infocomplementar_valor
                FROM conlancam
                     INNER JOIN conlancamdoc ON c71_codlan = c70_codlan
                     INNER JOIN conhistdoc ON c53_coddoc = c71_coddoc
                     LEFT JOIN conlancamemp ON c75_codlan = c70_codlan
                     LEFT JOIN empempenho ON c75_numemp = e60_numemp
                     LEFT JOIN orcdotacao dotemp ON e60_coddot = dotemp.o58_coddot
    AND e60_anousu = dotemp.o58_anousu
                     LEFT JOIN orcelemento eleemp ON dotemp.o58_codele = eleemp.o56_codele
    AND dotemp.o58_anousu = eleemp.o56_anousu
                     LEFT JOIN conlancamdot       ON c73_codlan = c70_codlan
                     LEFT JOIN orcdotacao dotlan  ON c73_coddot = dotlan.o58_coddot
    AND c73_anousu = dotlan.o58_anousu
                     LEFT JOIN orcelemento eledot  ON dotlan.o58_codele = eledot.o56_codele
    AND dotlan.o58_anousu = eledot.o56_anousu
              WHERE c70_Codlan = codigo_lancamento' 
                     where c121_nomepropriedade = 'elemento_despesa'");
        $this->execute("update conplanoinfocomplementar 
                       set c121_sql = 'SELECT distinct substr((CASE WHEN c75_codlan IS NOT NULL THEN eleemp.o56_elemento::varchar
                           ELSE eledot.o56_elemento::varchar END), 8, 2) AS infocomplementar_valor
                FROM conlancam
                     INNER JOIN conlancamdoc ON c71_codlan = c70_codlan
                     INNER JOIN conhistdoc ON c53_coddoc = c71_coddoc
                     LEFT JOIN conlancamemp ON c75_codlan = c70_codlan
                     LEFT JOIN empempenho ON c75_numemp = e60_numemp
                     LEFT JOIN orcdotacao dotemp ON e60_coddot = dotemp.o58_coddot
    AND e60_anousu = dotemp.o58_anousu
                     LEFT JOIN orcelemento eleemp ON dotemp.o58_codele = eleemp.o56_codele
    AND dotemp.o58_anousu = eleemp.o56_anousu
                     LEFT JOIN conlancamdot       ON c73_codlan = c70_codlan
                     LEFT JOIN orcdotacao dotlan  ON c73_coddot = dotlan.o58_coddot
    AND c73_anousu = dotlan.o58_anousu
                     LEFT JOIN orcelemento eledot  ON dotlan.o58_codele = eledot.o56_codele
    AND dotlan.o58_anousu = eledot.o56_anousu
              WHERE c70_Codlan = codigo_lancamento' 
                     where c121_nomepropriedade = 'desdobramento_despesa'");

        $this->execute("update conplanoinfocomplementar 
                       set c121_sql = 'select case when k171_departamento is not null then k171_sequencial  else k180_unidadegestora end from conlancamdepartamento left join unidadegestoradepartamentos on c128_departamento = k180_depart left join unidadegestora on k171_departamento = c128_departamento where c128_conlancam = codigo_lancamento limit 1' 
                     where c121_nomepropriedade = 'unidade_gestora'");


        $this->execute("update conplanoinfocomplementar 
                       set c121_sql = 'select e60_anousu from conlancamemp inner join empempenho on c75_numemp = e60_numemp where c75_codlan  = codigo_lancamento' 
                     where c121_nomepropriedade = 'ano_empenho'");

        $this->execute("update conplanoinfocomplementar 
                       set c121_sql = 'select e60_codemp from conlancamemp inner join empempenho on c75_numemp = e60_numemp where c75_codlan  = codigo_lancamento' 
                     where c121_nomepropriedade = 'numero_nota_empenho'");

        $this->execute("update conplanoinfocomplementar 
                       set c121_sql = 'select e71_codord from conlancamnota inner join empnota on c66_codnota = e69_codnota inner join pagordemnota  on e71_codnota = e69_codnota and e71_anulado is false where c66_codlan = codigo_lancamento' 
                     where c121_nomepropriedade = 'numero_nota_liquidacao'");

        $this->execute("update conplanoinfocomplementar 
                       set c121_sql = 'select max(e82_codmov) from conlancamnota inner join empnota on c66_codnota = e69_codnota inner join pagordemnota on e71_codnota = e69_codnota and e71_anulado is false inner join empord on e82_codord = e71_codord where c66_codlan = codigo_lancamento' 
                     where c121_nomepropriedade = 'numero_ordem_pagamento'");

        $this->execute("update conplanoinfocomplementar 
                       set c121_sql  = 'select z01_numcgm from conlancamcgm inner join cgm on z01_numcgm = c76_numcgm where c76_codlan = codigo_lancamento' 
                     where c121_nomepropriedade = 'nome_cgm_credor'");

        $this->execute("update conplanoinfocomplementar 
                       set c121_sql  = 'select z01_cgccpf from conlancamcgm inner join cgm on z01_numcgm = c76_numcgm where c76_codlan = codigo_lancamento' 
                     where c121_nomepropriedade = 'cpf_cnj_credor'");

        $this->execute("update conplanoinfocomplementar 
                       set c121_sql  = 'select c25_tipoidentificacaocredor from conlancamcgm inner join cgm on z01_numcgm = c76_numcgm inner join compras.pcforne on pc60_numcgm = z01_numcgm inner join pcfornetipoidentificacaocredorgenerica on c26_pcforne = pc60_numcgm inner join tipoidentificacaocredorgenerica on c25_sequencial = c26_tipoidentificacaocredorgenerica  where c76_codlan = codigo_lancamento limit 1' 
                     where c121_nomepropriedade = 'codigo_inscricao_generica'");

        $this->execute("update conplanoinfocomplementar 
                       set c121_sql  = 'select ac16_numero from conlancamemp inner join empempenhocontrato on e100_numemp = c75_codlan inner join acordo on ac16_sequencial = e100_acordo where c75_codlan = codigo_lancamento limit 1' 
                     where c121_nomepropriedade = 'numero_contrato'");

        $this->execute("update conplanoinfocomplementar 
                       set c121_sql  = 'select ac16_anousu from conlancamemp inner join empempenhocontrato on e100_numemp = c75_codlan inner join acordo on ac16_sequencial = e100_acordo where c75_codlan = codigo_lancamento limit 1' 
                     where c121_nomepropriedade = 'ano_acordo'");
        $this->execute("update conplanoinfocomplementar 
                       set c121_sql  = 'select ac16_contratado from conlancamemp inner join empempenhocontrato on e100_numemp = c75_codlan inner join acordo on ac16_sequencial = e100_acordo where c75_codlan = codigo_lancamento limit 1' 
                     where c121_nomepropriedade = 'codigo_cgm_contrato'");

    }

    /**
     *
     */
    public function down()
    {
        $this->execute("delete from conplanosistemaatributos;");
        $this->execute("delete from conplanosistema where c122_sequencial > 1;");
        $this->execute("delete from conplanoinfocomplementar where c121_sequencial > 7;");

    }
}
