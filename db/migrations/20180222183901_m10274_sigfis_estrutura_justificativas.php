<?php

use Classes\PostgresMigration;

class M10274SigfisEstruturaJustificativas extends PostgresMigration
{

    public function up() {
        $this->criarMenu();
        $this->novoLayoutEmpenho();
        $this->addDicionarioDados();
        $this->criarTabela();

        $this->criarEstruturaRelatorio();
    }

    public function down() {

        $linha = $this->fetchRow("select funcao from db_itensmenu where id_item = 10502");
        $dadosFuncao = explode('=', $linha['funcao']);
        $codigoRelatorioExcluir = $dadosFuncao[1];
        $this->execute("delete from db_relatorio where db63_sequencial = {$codigoRelatorioExcluir}");

        $this->removeritensMenu();
        $this->removerNovoLayoutEmpenho();
        $this->removerDicionarioDados();
        $this->removerTabela();
    }

    private function criarEstruturaRelatorio()
    {
        $buscaProximoSequencial = $this->fetchRow("select nextval('db_relatorio_db63_sequencial_seq') as codigo_relatorio");
        $codigoRelatorio = $buscaProximoSequencial['codigo_relatorio'];

        $xml = <<<DADOS_XML
<?xml version=\'1.0\' encoding=\'ISO-8859-1\'?>
<Relatorio>
 <Versao>1.0</Versao>
 <Propriedades versao=\'1.0\' nome=\'Empenhos Sem Justificativa\' layout=\'dbseller\' formato=\'A4\' orientacao=\'portrait\' margemsup=\'0\' margeminf=\'0\' margemesq=\'20\' margemdir=\'20\' tiposaida=\'pdf\'/>
 <Cabecalho></Cabecalho>
 <Rodape></Rodape>
 <Variaveis>
  <Variavel nome=\'\$data_inicial_emissao\' label=\'Data Emissão\' tipodado=\'date\' valor=\'\'/>
  <Variavel nome=\'\$data_final_emissao\' label=\'Data Final\' tipodado=\'date\' valor=\'\'/>
 </Variaveis>
 <Campos>
  <Campo id=\'5594\' nome=\'e60_numemp\' alias=\'Seq. Empenho\' largura=\'30\' alinhamento=\'c\' alinhamentocab=\'c\' mascara=\'t\' totalizar=\'n\' quebra=\'\'/>
  <Campo id=\'5595\' nome=\'codigo_empenho\' alias=\'Empenho\' largura=\'30\' alinhamento=\'c\' alinhamentocab=\'c\' mascara=\'t\' totalizar=\'n\' quebra=\'\'/>
  <Campo id=\'217\' nome=\'z01_nome\' alias=\'Nome do Fornecedor\' largura=\'130\' alinhamento=\'l\' alinhamentocab=\'c\' mascara=\'t\' totalizar=\'n\' quebra=\'\'/>
 </Campos>
 <Consultas>
  <Consulta tipo=\'Principal\'>
   <Select>
    <Campo id=\'5594\'/>
    <Campo id=\'5595\'/>
    <Campo id=\'217\'/>
   </Select>
   <From>select e60_numemp, e60_codemp||''/''||e60_anousu as codigo_empenho, z01_nome from empempenho inner join cgm on cgm.z01_numcgm = empempenho.e60_numcgm left join empenhojustificativacontratolicitacao on empenhojustificativacontratolicitacao.e08_empempenho = empempenho.e60_numemp where e60_emiss between \$data_inicial_emissao and \$data_final_emissao and empenhojustificativacontratolicitacao.e08_empempenho is null</From>
   <Where/>
   <Group></Group>
   <Order>
    <Ordem id=\'5594\' nome=\'e60_numemp\' ascdesc=\'asc\' alias=\'Seq. Empenho\'/>
   </Order>
  </Consulta>
 </Consultas>
</Relatorio>
DADOS_XML;
;

        $this->execute("insert into db_relatorio values ({$codigoRelatorio}, 3, 1, 'Empenhos Sem Justificativa', '1.0', '2018-02-23', '{$xml}', 1)");
        $this->execute("insert into db_itensmenu ( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 10502 ,'Relatórios de Empenho Sem Justificativa' ,'Empenhos sem justificativa para contratos e licitações' ,'sys4_geradorteladinamica001.php?iCodRelatorio={$codigoRelatorio}' ,'1' ,'1' ,'Empenhos sem justificativa para contratos e licitações' ,'true' );");
        $this->execute("delete from db_menu where id_item_filho = 10502 AND modulo = 209;");
        $this->execute("insert into db_menu ( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 8997 ,10502 ,8 ,209 );");

    }


    private function criarMenu() {

        // Cria o item de MENU
        $aColumns   =  array('id_item' ,'descricao' ,'help' ,'funcao' ,'itemativo' ,'manutencao' ,'desctec' ,'libcliente');
        $aValues    =  array(
            array(10501,'Justificativas do Empenho' ,'Justificativa para não vinculação com Contrato ou Licitação/Dispensa' ,'con4_justificativacontratolicitacaosigfis.php' ,'1' ,'1' ,'Cadastro de justificativas para não vinculação do empenho com Contrato ou Licitação/Dispensa. ' ,'true' ),
        );
        $table      = $this->table('db_itensmenu', array('schema' => 'configuracoes'));
        $table->insert($aColumns, $aValues);
        $table->saveData();

        // Víncula item de menu
        $aColumns   =  array('id_item', 'id_item_filho', 'menusequencia', 'modulo');
        $aValues    =  array( array(8997 ,10501 ,7 ,209) );
        $table      =  $this->table('db_menu', array('schema' => 'configuracoes'));
        $table->insert($aColumns, $aValues);
        $table->saveData();
    }

    private function removeritensMenu()
    {
        $this->execute("delete from configuracoes.db_menu where id_item_filho = 10501 AND modulo = 209");
        $this->execute("delete from configuracoes.db_menu where id_item_filho = 10502 AND modulo = 209");
        $this->execute("delete from configuracoes.db_itensmenu where id_item in (10501, 10502)");
    }

    private function removerNovoLayoutEmpenho()
    {

        $this->execute('delete from db_layoutcampos where db52_layoutlinha = 1013;');
        $this->execute('delete from db_layoutlinha where db51_layouttxt = 300;');
        $this->execute('delete from db_layouttxt where db50_codigo = 300;');

    }

    private function novoLayoutEmpenho()
    {

        $this->execute(
            <<<SQL_UP_LAYOUT

insert into db_layouttxt values (300, 'SIGFIS - EMPENHO 2018', 1, '', 4);
insert into db_layoutlinha values (1013, 300, 'REGISTRO - EMPENHO - 2018', 3, 1054, 0, 0, '', '', false);
INSERT INTO db_layoutcampos VALUES (16630, 1013, 'cd_Unidade', 'Unidade gestora', 14, 1, '', 4, false, true, 'd', '', 0);
INSERT INTO db_layoutcampos VALUES (16631, 1013, 'cd_UnidadeOrcamentaria', 'Unidade orçamentária', 14, 5, '', 4, false, true, 'd', '', 0);
INSERT INTO db_layoutcampos VALUES (16632, 1013, 'nu_Empenho', 'Nº do empenho', 14, 9, '', 10, false, true, 'd', '', 0);
INSERT INTO db_layoutcampos VALUES (16633, 1013, 'nu_ProcessoLicitatorio', 'Nº do processo licitatório', 14, 19, '', 36, false, true, 'd', '', 0);
INSERT INTO db_layoutcampos VALUES (16634, 1013, 'dt_Ano', 'ANO', 14, 55, '', 4, false, true, 'd', '', 0);
INSERT INTO db_layoutcampos VALUES (16635, 1013, 'Tp_ProjetoAtividade', 'Tipo projeto/atividade', 14, 59, '', 1, false, true, 'd', '', 0);
INSERT INTO db_layoutcampos VALUES (16636, 1013, 'nu_ProjetoAtividade', 'No projeto/atividade', 14, 60, '', 4, false, true, 'd', '', 0);
INSERT INTO db_layoutcampos VALUES (16637, 1013, 'cd_FonteRecurso', 'Fonte recurso', 14, 64, '', 4, false, true, 'd', '', 0);
INSERT INTO db_layoutcampos VALUES (16638, 1013, 'Reservado_tce', 'Reservado TCE', 14, 68, '00000000000000', 14, false, true, 'd', '', 0);
INSERT INTO db_layoutcampos VALUES (16639, 1013, 'cd_Elemento', 'Item de despesa', 14, 82, '', 8, false, true, 'd', '', 0);
INSERT INTO db_layoutcampos VALUES (16640, 1013, 'vl_Empenho', 'Valor do empenho', 14, 90, '', 16, false, true, 'd', '', 0);
INSERT INTO db_layoutcampos VALUES (16641, 1013, 'de_Historico', 'Histórico', 14, 106, '', 255, false, true, 'd', '', 0);
INSERT INTO db_layoutcampos VALUES (16642, 1013, 'Tp_Empenho', 'Tipo de empenho', 14, 361, '', 1, false, true, 'd', '', 0);
INSERT INTO db_layoutcampos VALUES (16643, 1013, 'dt_Empenho', 'Data do empenho', 14, 362, '', 8, false, true, 'd', '', 0);
INSERT INTO db_layoutcampos VALUES (16644, 1013, 'nu_Contrato', 'Número do contrato', 14, 370, '', 16, false, true, 'd', '', 0);
INSERT INTO db_layoutcampos VALUES (16645, 1013, 'nm_Credor', 'Nome do credor', 14, 386, '', 50, false, true, 'd', '', 0);
INSERT INTO db_layoutcampos VALUES (16646, 1013, 'dt_AnoMes', 'Competência', 14, 436, '', 6, false, true, 'd', '', 0);
INSERT INTO db_layoutcampos VALUES (16647, 1013, 'nu_CGC_Credor', 'CNPJ/CPF credor', 14, 442, '', 14, false, true, 'd', '', 0);
INSERT INTO db_layoutcampos VALUES (16648, 1013, 'Tp_Pessoa', 'Tipo pessoa credor', 14, 456, '', 1, false, true, 'd', '', 0);
INSERT INTO db_layoutcampos VALUES (16649, 1013, 'cd_Orgao', 'Código do órgão', 14, 457, '', 4, false, true, 'd', '', 0);
INSERT INTO db_layoutcampos VALUES (16650, 1013, 'cd_Dispensa', 'Número da dispensa', 14, 461, '', 16, false, true, 'd', '', 0);
INSERT INTO db_layoutcampos VALUES (16651, 1013, 'Reservado_tce2', 'RESERVADO TCE', 14, 477, '', 1, false, true, 'd', '', 0);
INSERT INTO db_layoutcampos VALUES (16652, 1013, 'cd_Funcao', 'Código da função', 14, 478, '', 2, false, true, 'd', '', 0);
INSERT INTO db_layoutcampos VALUES (16653, 1013, 'cd_Programa', 'Código da subfunção', 14, 480, '', 4, false, true, 'd', '', 0);
INSERT INTO db_layoutcampos VALUES (16654, 1013, 'cd_SubPrograma', 'Código do programa', 14, 484, '', 4, false, true, 'd', '', 0);
INSERT INTO db_layoutcampos VALUES (16669, 1013, 'RESERVADO_TCE_3', 'RESERVADO_TCE_3', 14, 488, '', 2, false, true, 'd', '', 0);
INSERT INTO db_layoutcampos VALUES (16657, 1013, 'NU_CONVENIO', 'Número do convênio', 14, 490, '', 16, false, true, 'd', '', 0);
INSERT INTO db_layoutcampos VALUES (16658, 1013, 'NU_TERMOPARCERIA', 'Número do Termo de Parceria', 14, 506, '', 16, false, true, 'd', '', 0);
INSERT INTO db_layoutcampos VALUES (16659, 1013, 'ST_CONVENIO_APLICAVEL', 'Indicador convênio aplicavel3', 14, 522, '', 1, false, true, 'd', '', 0);
INSERT INTO db_layoutcampos VALUES (16660, 1013, 'ST_TERMOPARCERIA_APLICAVEL', 'Indicador termo de parceria aplicável4', 14, 523, '', 1, false, true, 'd', '', 0);
INSERT INTO db_layoutcampos VALUES (16661, 1013, 'Nu_Aditivo', 'NUMERO DO ADITIVO', 14, 524, '', 16, false, true, 'd', '', 0);
INSERT INTO db_layoutcampos VALUES (16662, 1013, 'RESERVADO_TCE_4', 'RESERVADO_TCE_4', 14, 540, '', 1, false, true, 'd', '', 0);
INSERT INTO db_layoutcampos VALUES (16663, 1013, 'TPJUSTIFICATIVACONTRATO', 'TIPO JUSTIFICATIVA CONTRATO', 14, 541, NULL, 2, false, true, 'd', '', 0);
INSERT INTO db_layoutcampos VALUES (16664, 1013, 'DEJUSTIFICATIVACONTRATO', 'DESCRIÇÃO JUSTIFICATIVA CONTRATO', 14, 543, NULL, 255, false, true, 'd', '', 0);
INSERT INTO db_layoutcampos VALUES (16665, 1013, 'TPJUSTIFICATIVALICITA', 'TIPO JUSTIFICATIVA LICITAÇÃO', 14, 798, NULL, 2, false, true, 'd', '', 0);
INSERT INTO db_layoutcampos VALUES (16666, 1013, 'DEJUSTIFICATIVALICITA', 'DESCRIÇÃO JUSTIFICATIVA LICITAÇÃO', 14, 800, NULL, 255, false, true, 'd', '', 0);

SQL_UP_LAYOUT
        );

    }

    private function addDicionarioDados()
    {
        // Cadastro de Tabelas
        $aColumns  = array('codarq', 'nomearq', 'descricao', 'sigla', 'dataincl', 'rotulo', 'tipotabela', 'naolibclass', 'naolibfunc', 'naolibprog', 'naolibform');
        $aValues   = array(
            array(1010261, 'empenhojustificativacontratolicitacao', 'Guarda as justificativas do não vínculo do empenho com contratos e licitações.', 'e08', '2018-02-22', 'Justificativas', 0, 'f', 'f', 'f', 'f' ),
        );
        $table     = $this->table('db_sysarquivo', array('schema' => 'configuracoes'));
        $table->insert($aColumns, $aValues);
        $table->saveData();

        // Vínculo da tabela com o módulo
        $aColumns  =  array('codmod', 'codarq');
        $aValues   =  array(
            array(38,1010261)
        );
        $table     =  $this->table('db_sysarqmod', array('schema' => 'configuracoes'));
        $table->insert($aColumns, $aValues);
        $table->saveData();

        // Cadastro de campos
        $aColumns  = array('codcam', 'nomecam', 'conteudo', 'descricao', 'valorinicial', 'rotulo', 'tamanho', 'nulo', 'maiusculo', 'autocompl', 'aceitatipo', 'tipoobj', 'rotulorel');
        $aValues   = array(
            array(1009639,'e08_sequencial','int4','Código sequencial da tabela','0', 'Código',10,'f','f','f',1,'text','Código'),
            array(1009640,'e08_empempenho','int4','Vínculo com Empenho','0', 'Código do Empenho',10,'f','f','f',1,'text','Código do Empenho'),
            array(1009641,'e08_tipojustificativalicitacao','int4','Tipo Justificativa Licitação','0', 'Justificativa Licitação',10,'f','f','f',1,'text','Justificativa Licitação'),
            array(1009642,'e08_tipojustificativacontrato','int4','Tipo de Justificativa do Contrato','0', 'Justificativa do Contrato',10,'t','f','f',1,'text','Justificativa do Contrato'),
            array(1009643,'e08_descricaojustificativalicitacao','text','Descrição da Justificativa Licitação','', 'Descrição da Justificativa Licitação',1,'t','f','f',0,'text','Descrição da Justificativa Licitação'),
            array(1009644,'e08_descricaojustificativacontrato','text','Descrição da Justificativa Contrato','', 'Descrição da Justificativa Contrato',1,'t','f','f',0,'text','Descrição da Justificativa Contrato')
        );
        $table     = $this->table('db_syscampo', array('schema' => 'configuracoes'));
        $table->insert($aColumns, $aValues);
        $table->saveData();

        // Vínculo dos campos com a tabela
        $aColumns = array('codarq', 'codcam', 'seqarq', 'codsequencia');
        $aValues  = array(
            array(1010261,1009639,1,0),
            array(1010261,1009640,2,0),
            array(1010261,1009642,3,0),
            array(1010261,1009644,4,0),
            array(1010261,1009641,5,0),
            array(1010261,1009643,6,0),
        );
        $table    = $this->table('db_sysarqcamp', array('schema' => 'configuracoes'));
        $table->insert($aColumns, $aValues);
        $table->saveData();

        // Cadastro da PK
        $aColumns = array('codarq', 'codcam','sequen', 'camiden');
        $aValues  = array(
            array(1010261,1009639,1,1009639)
        );
        $table    = $this->table('db_sysprikey', array('schema' => 'configuracoes'));
        $table->insert($aColumns, $aValues);
        $table->saveData();

        // Cadastro da FK
        $aColumns = array('codarq', 'codcam', 'sequen', 'referen', 'tipoobjrel');
        $aValues  = array(
            array(1010261,1009640,1,889,0),
        );
        $table    = $this->table('db_sysforkey', array('schema' => 'configuracoes'));
        $table->insert($aColumns, $aValues);
        $table->saveData();

        // inclui os indices
        $aColumns = array('codind', 'nomeind', 'codarq', 'campounico');
        $aValues  = array(
            array(1008255,'empenhojustificativacontratolicitacao_e08_empempenho_in',1010261,'0'),
        );
        $table    = $this->table('db_sysindices', array('schema' => 'configuracoes'));
        $table->insert($aColumns, $aValues);
        $table->saveData();

        // vincula os indices
        $aColumns = array('codind', 'codcam', 'sequen');
        $aValues  = array(
            array(1008255,1009640,1),
        );
        $table    = $this->table('db_syscadind', array('schema' => 'configuracoes'));
        $table->insert($aColumns, $aValues);
        $table->saveData();

        // Cadastro de sequências
        $aColumns   = array('codsequencia', 'nomesequencia', 'incrseq', 'minvalueseq', 'maxvalueseq', 'startseq', 'cacheseq');
        $aValues    = array(
            array(1000718, 'empenhojustificativacontratolicitacao_e08_sequencial_seq', 1, 1, 9223372036854775807, 1, 1),
        );
        $table      =  $this->table('db_syssequencia', array('schema' => 'configuracoes'));
        $table->insert($aColumns, $aValues);
        $table->saveData();
        $this->execute("update db_sysarqcamp set codsequencia = 1000718 where codarq = 1010261 and codcam = 1009639");
    }

    private function criarTabela()
    {

        $this->execute(<<<SQL
        CREATE SEQUENCE empenho.empenhojustificativacontratolicitacao_e08_sequencial_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1 CACHE 1;

        CREATE TABLE empenho.empenhojustificativacontratolicitacao(
            e08_sequencial int4 NOT NULL default 0,
            e08_empempenho int4 NOT NULL default 0,
            e08_tipojustificativalicitacao int4,
            e08_tipojustificativacontrato int4,
            e08_descricaojustificativalicitacao text,
            e08_descricaojustificativacontrato text,
            CONSTRAINT empenhojustificativacontratolicitacao_sequ_pk PRIMARY KEY (e08_sequencial),
            CONSTRAINT empenhojustificativacontratolicitacao_e08_empempenho_fk FOREIGN KEY (e08_empempenho) REFERENCES empempenho(e60_numemp)
        );

        CREATE UNIQUE INDEX empenhojustificativacontratolicitacao_e08_empempenho_in ON empenhojustificativacontratolicitacao(e08_empempenho);
SQL
        );
    }

    public function removerDicionarioDados()
    {
        $this->execute('delete from configuracoes.db_syscadind  where codind in (1008255) ');
        $this->execute('delete from configuracoes.db_sysindices where codind in (1008255) ');
        $this->execute('delete from configuracoes.db_sysforkey where codarq in (1010261) ');
        $this->execute("delete from configuracoes.db_sysarqcamp where codcam in (1009639, 1009640, 1009642, 1009644, 1009641, 1009643)");
        $this->execute('delete from configuracoes.db_sysprikey where codarq in(1010261)');
        $this->execute('delete from configuracoes.db_sysarqmod where codarq in(1010261)');
        $this->execute('delete from configuracoes.db_sysarquivo where codarq in(1010261)');
        $this->execute('delete from configuracoes.db_syssequencia where codsequencia in(1000718)');
        $this->execute("delete from configuracoes.db_syscampo where codcam in (1009639, 1009640, 1009642, 1009644, 1009641, 1009643)");
    }

    private function removerTabela()
    {
        $this->execute("DROP SEQUENCE IF EXISTS empenhojustificativacontratolicitacao_e08_sequencial_seq;");
        $this->execute("DROP TABLE IF EXISTS empenhojustificativacontratolicitacao;");
    }
}
