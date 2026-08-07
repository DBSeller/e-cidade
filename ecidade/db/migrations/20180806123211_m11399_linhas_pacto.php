<?php

use Classes\PostgresMigration;

class M11399LinhasPacto extends PostgresMigration
{
    public function up()
    {
        $this->addDicionarioDados();
        $this->criarTabelas();
        $this->incluirRegistros();
        $this->incluirMenu();
    }

    public function down()
    {
        $this->removerDicionarioDados();
        $this->droparDML();
        $this->excluirMenu();
    }

    public function addDicionarioDados()
    {

        /**
         * Cria tabelas
         */
        $aColumns = array('codarq', 'nomearq', 'descricao', 'sigla', 'dataincl', 'rotulo', 'tipotabela', 'naolibclass', 'naolibfunc', 'naolibprog', 'naolibform');
        $aValues = array(
            array(1010299, 'linhaspacto', 'Cadastro de linhas de pacto utilizadas na previsão de despesa', 'c07', '2018-08-06', 'linhaspacto', 0, 'f', 'f', 'f', 'f'),
        );
        $table = $this->table('db_sysarquivo', array('schema' => 'configuracoes'));
        $table->insert($aColumns, $aValues);
        $table->saveData();

        // vincula modulo
        $aColumns = array('codmod', 'codarq');
        $aValues = array(
            /**
             *lista de campos
             */
            array(32, 1010299)
        );
        $table = $this->table('db_sysarqmod', array('schema' => 'configuracoes'));
        $table->insert($aColumns, $aValues);
        $table->saveData();

        /**
         * Cria campos
         */
        $aColumns = array('codcam', 'nomecam', 'conteudo', 'descricao', 'valorinicial', 'rotulo', 'tamanho', 'nulo', 'maiusculo', 'autocompl', 'aceitatipo', 'tipoobj', 'rotulorel');
        $aValues = array(
            array(1009861, 'c07_sequencial', 'int4', 'Código sequencial', '0', 'Código', 10, 'f', 'f', 'f', 1, 'text', 'Código'),
            array(1009862, 'c07_titulo', 'varchar(255)', 'Título', '', 'Título', 255, 'f', 'f', 'f', 0, 'text', 'Título'),
            array(1009863, 'c07_valor', 'float8', 'Valor', '0', 'Valor', 15, 'f', 'f', 'f', 4, 'text', 'Valor')
        );
        $table = $this->table('db_syscampo', array('schema' => 'configuracoes'));
        $table->insert($aColumns, $aValues);
        $table->saveData();

        /**
         * db_sysarqcamp
         */
        $aColumns = array('codarq', 'codcam', 'seqarq', 'codsequencia');
        $aValues = array(
            array(1010299, 1009861, 1, 0),
            array(1010299, 1009862, 2, 0),
            array(1010299, 1009863, 3, 0)

        );
        $table = $this->table('db_sysarqcamp', array('schema' => 'configuracoes'));
        $table->insert($aColumns, $aValues);
        $table->saveData();


        // inclui a sequence
        $aColumns = array('codsequencia', 'nomesequencia', 'incrseq', 'minvalueseq', 'maxvalueseq', 'startseq', 'cacheseq');
        $aValues = array(
            array(1000749, 'linhaspacto_c07_sequencial_seq', 1, 1, 9223372036854775807, 1, 1),
        );
        $table = $this->table('db_syssequencia', array('schema' => 'configuracoes'));
        $table->insert($aColumns, $aValues);
        $table->saveData();

        // inclui a chave primaria
        $aColumns = array('codarq', 'codcam', 'sequen', 'camiden');
        $aValues = array(
            array(1010299, 1009861, 1, 1009861),
        );
        $table = $this->table('db_sysprikey', array('schema' => 'configuracoes'));
        $table->insert($aColumns, $aValues);
        $table->saveData();

        // inclui os indices
        $aColumns = array('codind', 'nomeind', 'codarq', 'campounico');
        $aValues = array(
            array(1008308, 'linhaspacto_c07_sequencial_in', 1010299, '0'),

        );
        $table = $this->table('db_sysindices', array('schema' => 'configuracoes'));
        $table->insert($aColumns, $aValues);
        $table->saveData();

        // vincula os indices
        $aColumns = array('codind', 'codcam', 'sequen');
        $aValues = array(
            array(1008308, 1009861, 1),
        );
        $table = $this->table('db_syscadind', array('schema' => 'configuracoes'));
        $table->insert($aColumns, $aValues);
        $table->saveData();

        $this->execute("UPDATE db_sysarqcamp SET codsequencia = 1000749 WHERE codarq = 1010299 AND codcam = 1009861;");
    }

    public function criarTabelas()
    {
        $this->execute("
            -- Criando  sequences
            CREATE SEQUENCE linhaspacto_c07_sequencial_seq
            INCREMENT 1
            MINVALUE 1
            MAXVALUE 9223372036854775807
            START 1
            CACHE 1;
            
            
            -- TABELAS E ESTRUTURA
            
            -- Módulo: contabilidade
            CREATE TABLE linhaspacto(
            c07_sequencial		int4 NOT NULL default 0,
            c07_titulo		varchar(255) NOT NULL ,
            c07_valor		float8 default 0,
            CONSTRAINT linhaspacto_sequ_pk PRIMARY KEY (c07_sequencial));
        ");
    }

    /**
     * Remove dados do dicionario de dados
     */
    private function removerDicionarioDados()
    {

        $this->execute('DELETE FROM configuracoes.db_syscampodef WHERE codcam IN(1009861, 1009862, 1009863)');
        $this->execute('DELETE FROM configuracoes.db_syscadind WHERE codind IN(1008308)');
        $this->execute('DELETE FROM configuracoes.db_sysindices WHERE codind IN(1008308)');
        $this->execute('DELETE FROM configuracoes.db_sysforkey WHERE codcam IN(1009861, 1009862, 1009863)');
        $this->execute('DELETE FROM configuracoes.db_syssequencia WHERE codsequencia IN(1000749)');
        $this->execute('DELETE FROM configuracoes.db_sysprikey WHERE codarq IN(1010299)');
        $this->execute('DELETE FROM configuracoes.db_sysarqcamp WHERE codcam IN(1009861, 1009862, 1009863)');
        $this->execute('DELETE FROM configuracoes.db_syscampo WHERE codcam IN(1009861, 1009862, 1009863)');
        $this->execute('DELETE FROM configuracoes.db_sysarqmod WHERE codarq IN(1010299)');
        $this->execute('DELETE FROM configuracoes.db_sysarquivo WHERE codarq IN(1010299)');
    }

    private function droparDML()
    {
        $this->execute("DROP SEQUENCE IF EXISTS linhaspacto_c07_sequencial_seq");
        $this->execute("DROP TABLE IF EXISTS linhaspacto");

    }

    private function incluirRegistros()
    {
        $this->execute("
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Copa', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Serviço de Limpeza', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Concessionárias', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Benefícios (Transporte e Alimentação)', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Equipamentos Multifuncionais', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Concessionárias', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Estagiários', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Concessionárias', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Material de Limpeza', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Locação Microcomputadores', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Serviços de Informática', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Manutenção Ar Condicionado', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Manutenção de Elevadores', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Aquisição Água, Café e Açúcar', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Manutenção PABX', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Copos Descartáveis', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Material de Expediente', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Despesas com Condomínio', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Publicação Oficial', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Publicação Oficial', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Manutenção Predial', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Reforma do CAN', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Demais Despesas de Custeio e/ou Investimento', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Restaurante Popular', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Abrigo (Alimentação)', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Contrato Temporário', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Contrato Temporário', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Contrato Temporário Educador Social', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Contrato Temporário Digitador e Entrevistador', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Contrato Temporário PETI', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Manutenção Predial', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Acolhimento de Idosos', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Cesta Básica', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Locação de Imóveis', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Locação de Impressora', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Eventos (Estrutura)', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Combustível', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Diagnóstico', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Material de Limpeza', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Material de Higiene Pessoal', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Kit Lanche', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Demais Despesas de Convênios', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Demais Despesas de Custeio e/ou Investimento', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Serviço de Limpeza', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Parcelamento ECONIT', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Combustível', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Saco Plástico de Lixo', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Concessionárias', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Concessionárias', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Contenedores', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Operação e Manutenção de Equipamentos', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Benefícios (Transporte e Alimentação)', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Demais Despesas de Custeio e/ou Investimento', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Secretaria Da Receita Federal / REFIS', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Sirene', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Corpo de Bombeiros - Convênio', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Construção de Postos de Salvamento', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Plano Municipal de Defesa Civil', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Demais Despesas de Custeio e/ou Investimento', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Serviço de Limpeza', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'PROCC', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Alimentação', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Alimentação', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Manutenção Predial', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Manutenção Telhado', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Concessionárias', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Fundo Municipal de Transporte', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Vans Adaptadas', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Uniformes', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Uniformes Merendeiras', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Concessionárias', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Manutenção de Veículos', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Locação de Veículos ', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Conectividade', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Manutenção Subestação', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Estagiários', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Gás', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Locação de Copiadoras', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Concessionárias', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Manutenção Elevadores', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Material de Limpeza', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Locação Microcomputadores', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Sistema de Gestão Escolar ', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Link', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Galões de Água', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Verba Escolar', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Manutenção do Ar Condicionado', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Aquisição de Ar Condicionado', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Obra Para Climatização', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Manutenção Coifa e Fogão', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Material de Expediente', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Gráfica', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Publicação Oficial', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Kit Escolar', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Seguros (Carros e Imóveis)', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Combustível', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Locação de Imóveis', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Aquisição Mobiliário Escolar', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Reforma Mobiliário Escolar ', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Eletrodomésticos', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Dedetização, Descupinização e Desratização', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Placas de Identificação', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Suprimento de Informática - Rede Escolar', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Projetos Pedagógicos', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Pestalozzi', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Evento - 7 de Setembro', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Evento - Semana de Ciência e Tecnologia', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Evento - Jogos Escolares de Niterói', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'UMEI Preventório', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'UMEI Coronel Leôncio', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'UMEI Teixeira de Freitas', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Terraplanagem Teixeira de Freitas', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'CIEP Caramujo', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'UEI Vale Feliz', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Demais Despesas de Convênios', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Demais Despesas de Custeio e/ou Investimento', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Aluguel de Equipamentos', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Aluguel de Equipamentos', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Serviços Terceirizados Administrativos', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Serviços Terceirizados Administrativos', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Serviços Terceirizados Administrativos', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Serviços Terceirizados de Engenharia', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Serviços Terceirizados de Engenharia', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Serviços Terceirizados de Engenharia', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Serviços Terceirizados de Engenharia', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Concessionárias', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Rejuvenescimento Asfáltico', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Rejuvenescimento Asfáltico', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Manutenção do Túnel', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Manutenção do Túnel', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Retirada de Material', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Bicicletário', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Demolição - Marques de Paraná', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Praça Dom Navarro (São Judas Tadeu)', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Cobertura Canal Teixeira de Freitas', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Construção Da Quadra e Vestiário Teixeira de Freitas', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Revitalização Pista de Caminhada Horto do Fonseca', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Herbário do Horto', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Complemento Boulevard Barreto', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Buraco do Boi - Grama Sintética e Cobrimento da Quadra', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Lona Tensionada e Anfiteatro Horto Barreto', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Equipamentos de Informática', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Mobiliário', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Locação de Imóveis', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'UMEI Matapaca', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Manutenção de Cemitérios', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Parcelamentos Fiscais ', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Demais Despesas de Custeio e/ou Investimento', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Demais Despesas de Custeio e/ou Investimento', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Obras Diversas', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Obras Diversas', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Consórcio Transoceânica / Morro do Jacaré - Convênio CEF/CAF', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Consórcio Transoceânica / Morro do Jacaré - Recursos do Município', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Consórcio Transoceânica - Serviços Complementares', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Consórcio Transoceânica Ciclovia CAF', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Macrodrenagem Romanda Gonçalves', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Demais Despesas com Recursos de Operações de Crédito', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Urbanização Comunidade Capim Melado', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Urbanização Comunidade Capim Melado', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Demais Despesas Custeadas com Recursos de Convênios', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Manutenção Da Iluminação Pública', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Tapa Buraco', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Programa de Intervenções - Reestruturação Urbana da Alameda São Boaventura', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Programa de Intervenções - Urbanização da Comunidade de Igrejinha do Caramujo', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Programa de Intervenções - Jardim Japonês & Pista de Patins (Horto do Barreto)', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Programa de Intervenções - Estrutura Estabilizadora - Sistema de Drenagem (Ilha da Boa Viagem)', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Programa de Intervenções - Implantação do Parque Olímpico', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Programa de Intervenções - Macrodrenagem do Canal de Santo Antônio', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Programa de Intervenções - Projeto Básico Reurbanização da Orla Centro-Gragoatá', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Programa de Intervenções - Projeto Básico Reurbanização da Orla Icaraí-Gragoatá', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Programa de Intervenções - Restauração Castelo Dos Escoteiros e Capela de N.S. da Boa Viagem', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Revitalização Praça Evandro da Silveira - Piratininga', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Prefeitura Presente', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Área de Risco', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Manutenção do Caminho Niemeyer', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Parque Rural', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Obras nos CIEPS', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'BID - Imagem GEO Sistemas', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'BID - LOUDON (Auditoria do Programa)', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Praia Sem Barreiras', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Enseada Limpa', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Programa Niterói de Bicicleta', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'COLAB ', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Frente Nacional dos Prefeitos', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Ponto a Ponto', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Ponto a Ponto', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Eventos (Estrutura)', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Eventos (Material)', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Eventos (Sonorização)', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Anti Drogas', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Proteção Animal', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Assessoria de Comunicação', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Campanhas Educativas', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Demais Despesas de Convênios', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Demais Despesas com Recursos de Operações de Crédito', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Demais Despesas de Custeio e/ou Investimento', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Serviços Terceirizados Administrativos', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Serviços Terceirizados Administrativos', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'IMMUB - Instituto Memória Musical - Aprendiz', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Biblioteca Pública - RPA', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Sonorização Teatro Municipal', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Sonorização Teatro Popular', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Projeto Escola em Cena Teatro', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Estagiários', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Concessionárias', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Concessionárias', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Concessionárias', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Locação Ar Condicionado Teatro Municipal', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Arte na Rua - Locação de Som ', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Arte na Rua - RPA', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Artistas - RPA', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Projeto Janete Costa', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Assistência Técnica Aparelhos Ar Condicionado', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Cultura Viva (Custeio)', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Programação Mac', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Programação Teatro Municipal (Dispensa de Licitação e RPA)', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Cinema (Fomento)', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Reforma Petrobras de Cinema (Auditório)', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Festival do Áudio Visual', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Demais Despesas de Convênios', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Benefícios (Transporte e Alimentação)', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Gráfica', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Banner', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Transporte', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Dedetização, Descupinização e Desratização', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Locação de Veículos ', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Publicação Oficial', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Locações de Cadeiras ', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Locação de Salas', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Material de Limpeza', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Locação de Copiadoras ', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Locação Microcomputadores', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Quatro Estações', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Seminário Cultura e Territórios - Produção', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Seminário Cultura e Territórios - RPA', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Seminário Gestão Pública - Infraestrutura e Comunicação', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Seminário Gestão Pública - RPA', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Niterói em Cena', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Projeto Arte Barreto', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Manutenção Teatro Municipal', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Teatro Infantil', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Teatro Adulto', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Projeto Pesquisa e Pedagogia', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Demais Custeio - RPA', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Demais Despesas de Custeio e/ou Investimento', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Escola de Samba', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Carnaval Bloco', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Carnaval Bairro', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Carnaval Estrutura', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Subvenção Escolas', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Demais Eventos', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Site', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Kit Turístico', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Sinalização Turística', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Concessionárias', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Concessionárias', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Concessionárias', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Manutenção Predial', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Sentença Judicial', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Locação de Veículos ', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Demais Despesas de Custeio e/ou Investimento', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Demais Despesas de Convênios', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Estagiários', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Segurança e Monitoramento', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Campeonato de Basquetebol Master', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Festa do Trabalhador', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Festas Juninas', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Itacoatiara Pro', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Benefícios (Transporte e Alimentação)', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Serviços Terceirizados de Trânsito', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Fiscalização Radares', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Kit Lanche', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Locação de Veículos (Reboque) CCO Túnel', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Locação de Veículos ', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Locação de Veículos ', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Serviços de Reboque', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Combustível', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Aquisição Semafórico', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Concessionárias', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Locação de Impressora', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Concessionárias', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Internet', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Gestão de Software', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Galões de Água', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Estagiários', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'FGV', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Almoxarifado', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Benefícios (Transporte e Alimentação)', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Benefícios (Transporte e Alimentação)', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Demais Despesas de Custeio e/ou Investimento', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Prestador - SUS', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Prestador - SUASE', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'HGV - Ideias', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Serviço de Limpeza', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Serviços Terceirizados Administrativos', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Medicamentos', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Medicamentos', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Alimentação', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Linha de Crédito', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Concessionárias', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Laboratórios', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Suport-Informática', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Hemodiálise', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Locação de Imóveis', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Contrato Monitores', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Aluguel Ambulância', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Lavanderia', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Material Médico Hospitalar', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Investimentos', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'União Dos Cegos', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Dietas Enterais e Fórmula Láctea', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Demais Despesas de Convênios', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Demais Despesas de Custeio e/ou Investimento', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Demais Despesas de Custeio e/ou Investimento', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Gases Medicinais', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Videoendoscopia', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'RPA (Gnd 1 - Pessoal e Encargos)', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Serviços Terceirizados de Conservação', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Manutenção Da Iluminação Pública', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Poste / Transoceânica/ Iluminação Pública', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Manutenção Poda', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Manutenção Poda', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Iluminação Pública', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Conservação - Rios e Canais', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Conservação - Pavimentação', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Conservação - Parques e Jardins', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Conservação - Zeladoria', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Conservação - Arborização', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Fornecimento CBUQ', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Combustível', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Locação de Imóveis', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Demais Despesas de Custeio e/ou Investimento', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'CPROEIS', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Conectividade', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Concessionárias', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Combustível', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Manutenção de Viaturas', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Locação de Veículos ', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Armamento Não Letal', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Caixas Herméticas', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Talonário', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Cédula Identidade', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Internet', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Manutenção Ar Condicionado', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Locação de Impressora', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Operacionalização Canil', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Premiação por Desempenho', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Niterói Mais Segura', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Uniforme da Guarda', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'Demais Despesas de Custeio e/ou Investimento', 0);
            insert into linhaspacto values (nextval('linhaspacto_c07_sequencial_seq'), 'RAS (Gnd 1)', 0);

        ");
    }

    private function incluirMenu()
    {
        $this->execute("
            INSERT INTO db_itensmenu ( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) VALUES ( 10559 ,'Linhas de Pacto' ,'Cadastro de linhas de pacto' ,'' ,'1' ,'1' ,'Cadastro de linhas de pacto' ,'false' );
            INSERT INTO db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) VALUES ( 29 ,10559 ,283 ,209 );
            INSERT INTO db_itensmenu ( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) VALUES ( 10560 ,'Inclusão' ,'Inclusão de linhas de pacto' ,'con1_linhaspacto001.php' ,'1' ,'1' ,'Inclusão de linhas de pacto' ,'false' );
            INSERT INTO db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) VALUES ( 10559 ,10560 ,1 ,209 );
            INSERT INTO db_itensmenu ( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) VALUES ( 10561 ,'Alteração' ,'Alteração de linhas de pacto' ,'con1_linhaspacto002.php' ,'1' ,'1' ,'Alteração de linhas de pacto' ,'false' );
            INSERT INTO db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) VALUES ( 10559 ,10561 ,2 ,209 );
            INSERT INTO db_itensmenu ( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) VALUES ( 10562 ,'Exclusão' ,'Exclusão de linhas de pacto' ,'con1_linhaspacto003.php' ,'1' ,'1' ,'Exclusão de linhas de pacto' ,'false' );
            INSERT INTO db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) VALUES ( 10559 ,10562 ,3 ,209 );
        ");
    }

    private function excluirMenu()
    {
        $this->execute("
            DELETE FROM db_menu WHERE id_item_filho IN(10559, 10560, 10561, 10562) AND modulo = 209;
            DELETE FROM db_itensmenu WHERE id_item IN (10559, 10560, 10561, 10562);
        ");
    }
}
