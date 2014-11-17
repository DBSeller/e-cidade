<?php
 /*
   *     E-cidade Software Publico para Gestao Municipal
   *  Copyright (C) 2014  DBSeller Servicos de Informatica
   *                            www.dbseller.com.br
   *                         e-cidade@dbseller.com.br
   *
   *  Este programa e software livre; voce pode redistribui-lo e/ou
   *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme
   *  publicada pela Free Software Foundation; tanto a versao 2 da
   *  Licenca como (a seu criterio) qualquer versao mais nova.
   *
   *  Este programa e distribuido na expectativa de ser util, mas SEM
   *  QUALQUER GARANTIA; sem mesmo a garantia implicita de
   *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM
   *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais
   *  detalhes.
   *
   *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU
   *  junto com este programa; se nao, escreva para a Free Software
   *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
   *  02111-1307, USA.
   *
   *  Copia da licenca no diretorio licenca/licenca_en.txt
   *                                licenca/licenca_pt.txt
   */


class ImportacaoRelatorioLegal {

  /**
   * codigo do relatorio a ser importado
   * @var integer
   */
  private $iCodigoRelatorio;

  /**
   * objeto json do Relatorio a ser importado
   * @var object
   */
  private $oRelatorioJson;

  /**
   * Constante do caminho da mensagem do model
   * @var string
   */
  const CAMINHO_MENSAGENS = "financeiro/contabilidade/ImportacaoRelatorioLegal.";

  public function __construct( $iCodigoRelatorio = null, $sCaminhoArquivo ) {

    // se instanciar a classe sem o caminho do arquivo lançamos exceção
    if (!isset($sCaminhoArquivo)) {
      throw new ParameterException( _M( self::CAMINHO_MENSAGENS. "sem_arquivo_selecionado"));
    }
    // se nao abrir o arquivo lançamos exceção
    if ( !file_exists($sCaminhoArquivo)) {
      throw new ParameterException( _M( self::CAMINHO_MENSAGENS. "falha_abrir_arquivo"));
    }
    // se vier o parametro $iCodigoRelatorio setamos ele na propriedade
    if (!empty($iCodigoRelatorio)) {
      $this->setCodigoRelatorio($iCodigoRelatorio);
    }

    $sObjectJson = json_decode(file_get_contents($sCaminhoArquivo, FILE_TEXT));
    $this->setRelatorioJson($sObjectJson);
  }

  /**
   * define o objeto json a ser poercorrido e incluido
   * @param object $oRelatorioJson
   */
  private function setRelatorioJson( $oRelatorioJson ){
    $this->oRelatorioJson = $oRelatorioJson;
  }

  /**
   * retorna o objeto json pronto para ser incluido
   * @return object
   */
  private function getRelatorioJson(){
    return $this->oRelatorioJson;
  }

  /**
   * define o codigo do Relatorio, utilizado na alteração de dados
   * @param integer $iCodigoRelatorio
   */
  private function setCodigoRelatorio($iCodigoRelatorio){
    $this->iCodigoRelatorio = $iCodigoRelatorio;
  }

  /**
   * retorna o codigo do Relatorio para importacao
   * @return integer
   */
  public function getCodigoRelatorio(){
    return $this->iCodigoRelatorio;
  }

  /**
   * metodo responsavel pelos dados do relatorio
   *
   * @return stdclas da orcparamrel
   */
  private function getDadosRelatorio(){

    $iRelatorioPassado = $this->getCodigoRelatorio();
    $oRelatorio        = $this->getRelatorioJson();
    //verificamos se o codigo passado na instancia do objeto é o mesmo do Relatorio
    //se nao for lancamos exceção
    if ( !empty($iRelatorioPassado) && ($iRelatorioPassado != $oRelatorio->codigo_relatorio) ) {
      throw new BusinessException(_M( self::CAMINHO_MENSAGENS. "Relatorio_diferente"));
    }
    $oDadosOrcParamRel = new stdClass();
    $oDadosOrcParamRel->o42_codparrel        = $oRelatorio->codigo_relatorio;
    $oDadosOrcParamRel->o42_descrrel         = $oRelatorio->descricao;
    $oDadosOrcParamRel->o42_orcparamrelgrupo = $oRelatorio->grupo_relatorio;
    $oDadosOrcParamRel->o42_notapadrao       = $oRelatorio->nota_padrao;
    return $oDadosOrcParamRel;
  }

  /**
   * retorna os periodos vinculados ao relatorio, para que seja feito o vinculo na orcparamrelperiodos
   * @return array $aPeriodosRelatorio
   */
  private function getPeriodosRelatorio(){

    $oRelatorio         = $this->getRelatorioJson();
    $aPeriodosRelatorio = $oRelatorio->periodos_relatorio;
    return $aPeriodosRelatorio;
  }

  /**
   * metodo responsavel por retornar os dados dos periodos que estao vinculados
   * para que antes do vinculo, se necessario possamos incluir eles na periodo
   * @return unknown
   */
  private function getPeriodos(){

    $oRelatorio = $this->getRelatorioJson();
    $aPeriodos  = $oRelatorio->periodos;
    return $aPeriodos;
  }

  /**
   * metodo que retorna as colunas utilizadas no relatorio, incluirmos na orcparamseqcoluna caso ainda nao exista.
   * @return array $aColunas
   */
  private function getColunas() {

    $oRelatorio = $this->getRelatorioJson();
    $aColunas   = $oRelatorio->colunas;
    return $aColunas;
  }

  /**
   * metodo que irá retornar as linhas do relatorio, com suas colunas vinculadas e seus filtros padroes
   * para incluirmos nas tabelas: orcparamseq
                                  orcparamseqorcparamseqcoluna
                                  orcparamseqfiltropadrao
   * @return array $aLinhas
   */
  private function getLinhasRelatorio() {

    $oRelatorio = $this->getRelatorioJson();
    $aLinhasRelatorio = $oRelatorio->linhas;
    return $aLinhasRelatorio;
  }

  /**
   * metodo que irá ajustar o valor da sequence, para que a atual da base de destino,
   * nao seja menor que a que queremos incluir
   * @param string $sSequencia a ser alterada
   * @param integer $iProximoSequencial sequencial a ser setado
   * @throws DBException
   */
  private function consistenciaSequencial($sSequencia, $iProximoSequencial) {

    // antes de incluir devemos verificar a sequencia atual da tabela para que
    //não seja menor que a sequencia que estamos enviando
    $sSqlSequencial     = "select last_value from {$sSequencia} ";
    $rsSequencial       = db_query($sSqlSequencial);
    $iSequencialAtual   = db_utils::fieldsMemory($rsSequencial, 0)->last_value;
    if ($iSequencialAtual < $iProximoSequencial) {

      $sSqlSetarSequencia = "select setval('{$sSequencia}', {$iProximoSequencial}) ";
      $rsSetarSequencial  = db_query($sSqlSetarSequencia);
      if (!$rsSetarSequencial) {

        $oErroParametro = new stdClass();
        $oErroParametro->sequencia = $sSequencia;
        throw new DBException( _M( self::CAMINHO_MENSAGENS. "erro_setar_sequencial", $oErroParametro->sequencia) );
      }
    }
  }


  /**
   * metodo que ficara responsavel por incluir os periodos utilizados pelo relatorio
   * que ainda não estão na periodo, no caso de um novo periodo cadastrado
   * @return boolean
   */
  private function consistenciaPeriodo() {

    $aPeriodos   = $this->getPeriodos();

    //percorremos os periodos e caso nao tenha na base incluimos
    foreach ($aPeriodos as $iIndicePeriodo => $oDadosPeriodo) {

      $oDaoPeriodo = db_utils::getDao("periodo");
      $sSqlPeriodo = $oDaoPeriodo->sql_query($oDadosPeriodo->codigo);
      $rsPeriodo   = $oDaoPeriodo->sql_record($sSqlPeriodo);

      $oDaoPeriodo->o114_sequencial = $oDadosPeriodo->codigo;
      $oDaoPeriodo->o114_descricao  = addslashes(urldecode($oDadosPeriodo->nome));
      $oDaoPeriodo->o114_qdtporano  = $oDadosPeriodo->quantidade_no_ano;
      $oDaoPeriodo->o114_diainicial = $oDadosPeriodo->dia_inicial;
      $oDaoPeriodo->o114_mesinicial = $oDadosPeriodo->mes_inicial;
      $oDaoPeriodo->o114_diafinal   = $oDadosPeriodo->dia_final;
      $oDaoPeriodo->o114_mesfinal   = $oDadosPeriodo->mes_final;
      $oDaoPeriodo->o114_sigla      = addslashes(urldecode($oDadosPeriodo->sigla));
      $oDaoPeriodo->o114_ordem      = $oDadosPeriodo->ordem;

      if ($oDaoPeriodo->numrows >= 1) {
        $oDaoPeriodo->alterar( $oDaoPeriodo->o114_sequencial );
      } else {

        $this->consistenciaSequencial("periodo_o114_sequencial_seq", $oDaoPeriodo->o114_sequencial);
        $oDaoPeriodo->incluir( $oDaoPeriodo->o114_sequencial );
      }
      if ($oDaoPeriodo->erro_status == '0') {
        throw new DBException($oDaoPeriodo->erro_msg);
      }
    }
    return true;
  }

  /**
   * metodo responsavel pela concistencia das colunas do relatorio, caso tenha alterações ou até uma coluna nova
   * inserimos ou alteramos o registro na orcparamseqcoluna
   * @throws DBException
   * @return boolean
   */
  private function consistenciaColuna() {

    $aColunas = $this->getColunas();
    foreach ( $aColunas as $iIndiceColuna => $oDadosColuna) {

      $oDaoColuna = db_utils::getDao("orcparamseqcoluna");
      $sSqlColuna = $oDaoColuna->sql_query ( $oDadosColuna->codigo);
      $rsColuna   = $oDaoColuna->sql_record($sSqlColuna);

      $oDaoColuna->o115_sequencial      = $oDadosColuna->codigo;
      $oDaoColuna->o115_anousu          = $oDadosColuna->ano;
      $oDaoColuna->o115_descricao       = addslashes(urldecode($oDadosColuna->descricao));
      $oDaoColuna->o115_tipo            = $oDadosColuna->tipo;
      $oDaoColuna->o115_valoresdefault  = $oDadosColuna->valor_padrao;
      $oDaoColuna->o115_nomecoluna      = addslashes(urldecode($oDadosColuna->nome_coluna));

      if ($oDaoColuna->numrows >= 1) {
        $oDaoColuna->alterar ($oDaoColuna->o115_sequencial);
      } else {

        $this->consistenciaSequencial("orcparamseqcoluna_o115_sequencial_seq", $oDaoColuna->o115_sequencial);
        $oDaoColuna->incluir ($oDaoColuna->o115_sequencial);
      }
      if ($oDaoColuna->erro_status == '0') {
        throw new DBException($oDaoColuna->erro_msg);
      }
    }
    return true;
  }

  /**
   * metodo que irá consistenciar os relatorios, verifica se existe caso exista alteramos senao incluimos na
   * orcparamrel
   * @throws DBException
   * @return boolean
   */
  private function consistenciaRelatorio(){

    $oRelatorio    = $this->getDadosRelatorio();
    $oDaoRelatorio = db_utils::getDao("orcparamrel");
    $sSqlRelatorio = $oDaoRelatorio->sql_query_file ($oRelatorio-> o42_codparrel);
    $rsRelatorio   = $oDaoRelatorio->sql_record($sSqlRelatorio);

    $oDaoRelatorio->o42_codparrel        = $oRelatorio->o42_codparrel;
    $oDaoRelatorio->o42_descrrel         = addslashes(urldecode($oRelatorio->o42_descrrel));
    $oDaoRelatorio->o42_orcparamrelgrupo = $oRelatorio->o42_orcparamrelgrupo;
    $oDaoRelatorio->o42_notapadrao       = addslashes(urldecode($oRelatorio->o42_notapadrao));

    if ($oDaoRelatorio->numrows >= 1) {
      $oDaoRelatorio->alterar($oDaoRelatorio->o42_codparrel);
    } else {

      $this->consistenciaSequencial("orcparamrel_o42_codparrel_seq", $oDaoRelatorio->o42_codparrel);
      $oDaoRelatorio->incluir($oDaoRelatorio->o42_codparrel);
    }
    if ($oDaoRelatorio->erro_status == '0') {
      throw new DBException($oDaoRelatorio->erro_msg);
    }

    $this->setCodigoRelatorio($oDaoRelatorio->o42_codparrel);
    return true;
  }

  /**
   * metodo que irá vincular os periodos e relatorio na  orcparamrelperiodos
   * @throws DBException
   * @return boolean
   */
  private function vincularPeriodos() {

    $aPeriodosRelatorio = $this->getPeriodosRelatorio();

    foreach ($aPeriodosRelatorio as $iPeriodosRelatorio => $oDadosPeriodos) {

      $oDaoPeriodos = db_utils::getDao('orcparamrelperiodos');
      $sSqlPeriodos = $oDaoPeriodos->sql_query_file ( $oDadosPeriodos->codigo );
      $rsPeriodo    = $oDaoPeriodos->sql_record($sSqlPeriodos);

      $oDaoPeriodos->o113_sequencial  = $oDadosPeriodos->codigo;
      $oDaoPeriodos->o113_periodo     = $oDadosPeriodos->periodo;
      $oDaoPeriodos->o113_orcparamrel = $this->getCodigoRelatorio();

      if ($oDaoPeriodos->numrows >= 1) {
        $oDaoPeriodos->alterar($oDaoPeriodos->o113_sequencial);
      } else {

        $this->consistenciaSequencial("orcparamrelperiodos_o113_sequencial_seq", $oDaoPeriodos->o113_sequencial);
        $oDaoPeriodos->incluir($oDaoPeriodos->o113_sequencial);
      }
      if ($oDaoPeriodos->erro_status == '0') {
        throw new DBException($oDaoPeriodos->erro_msg);
      }
    }
    return true;
  }

  /**
   * metodo que irá consistenciar as linhas alterando ou incluindo conforme necessidade na orcparamseq
   * @throws DBException
   * @return boolean
   */
  private function consistenciaLinha() {

    $aLinhas = $this->getLinhasRelatorio();
    foreach ($aLinhas as $oDadosLinha) {

      $oDaoLinha = new cl_orcparamseq();
      $sSqlLinha = $oDaoLinha->sql_query_file ( $this->getCodigoRelatorio(), $oDadosLinha->codigo);
      $rsLinha   = $oDaoLinha->sql_record($sSqlLinha);

      $oDaoLinha->o69_codparamrel    = $this->getCodigoRelatorio();
      $oDaoLinha->o69_codseq         = $oDadosLinha->codigo;
      $oDaoLinha->o69_descr          = addslashes(urldecode($oDadosLinha->descricao));
      $oDaoLinha->o69_grupo          = $oDadosLinha->grupo;
      $oDaoLinha->o69_grupoexclusao  = $oDadosLinha->grupo_exclusao;
      $oDaoLinha->o69_nivel          = $oDadosLinha->nivel;
      $oDaoLinha->o69_verificaano    = $oDadosLinha->verifica_ano;
      $oDaoLinha->o69_labelrel       = addslashes(urldecode($oDadosLinha->label));
      $oDaoLinha->o69_manual         = $oDadosLinha->digital_manual;
      $oDaoLinha->o69_totalizador    = $oDadosLinha->totalizadora;
      $oDaoLinha->o69_ordem          = $oDadosLinha->ordem;
      $oDaoLinha->o69_nivellinha     = $oDadosLinha->nivel_linha;
      $oDaoLinha->o69_observacao     = addslashes(urldecode($oDadosLinha->observacao));
      $oDaoLinha->o69_desdobrarlinha = $oDadosLinha->desdobrar;
      $oDaoLinha->o69_origem         = $oDadosLinha->origem;
      $oDaoLinha->o69_libnivel       = $oDadosLinha->libera_nivel;
      $oDaoLinha->o69_librec         = $oDadosLinha->libera_rec;
      $oDaoLinha->o69_libsubfunc     = $oDadosLinha->libera_sub_func;
      $oDaoLinha->o69_libfunc        = $oDadosLinha->liberafunc;

      if ($oDaoLinha->numrows >= 1) {
        $oDaoLinha->alterar( $this->getCodigoRelatorio(), $oDaoLinha->o69_codseq );
      } else {

        //$this->consistenciaSequencial("orcparamsec_o69_codseq_seq", $oDaoLinha->o69_codseq);
        $oDaoLinha->incluir( $this->getCodigoRelatorio(), $oDaoLinha->o69_codseq );
      }
      if ($oDaoLinha->erro_status == '0') {
        throw new DBException($oDaoLinha->erro_msg);
      }
    }
    return true;
  }

  /**
   * metodo que irá verificar manutenção na orcparamseqorcparamseqcoluna que é o vinculo entre linha e coluna
   * se necessario altera senao inclui.
   * @throws DBException
   * @return boolean
   */
  private function vincularColunas(){

    $aLinhas = $this->getLinhasRelatorio();

    foreach ($aLinhas as $oDadosLinha) {

      $aColunas  = $oDadosLinha->aColunas;
      $iLinhaSeq = $oDadosLinha->codigo;

      foreach ($aColunas as $iColuna => $oDadosColuna) {

        $oDaoColuna = db_utils::getDao('orcparamseqorcparamseqcoluna');
        $sSqlColuna = $oDaoColuna->sql_query_file ( $oDadosColuna->codigo );
        $rsColuna   = $oDaoColuna->sql_record($sSqlColuna);

        $oDaoColuna->o116_sequencial        = $oDadosColuna->codigo;
        $oDaoColuna->o116_codseq            = $iLinhaSeq;
        $oDaoColuna->o116_codparamrel       = $this->getCodigoRelatorio();
        $oDaoColuna->o116_orcparamseqcoluna = $oDadosColuna->coluna;
        $oDaoColuna->o116_ordem             = $oDadosColuna->ordem;
        $oDaoColuna->o116_periodo           = $oDadosColuna->periodo;
        $oDaoColuna->o116_formula           = $oDadosColuna->formula;

        if ($oDaoColuna->numrows >= 1) {
          $oDaoColuna->alterar ($oDaoColuna->o116_sequencial);
        } else {

          $this->consistenciaSequencial("orcparamseqorcparamseqcoluna_o116_sequencial_seq", $oDaoColuna->o116_sequencial);
          $oDaoColuna->incluir ($oDaoColuna->o116_sequencial);
        }
        if ($oDaoColuna->erro_status == '0') {
          throw new DBException($oDaoColuna->erro_msg);
        }
      }
    }
    return true;
  }

  /**
   * metodo será responsavel por alterar / incluir filtro padrao
   * na orcparamseqfiltropadrao
   * @return boolean
   */
  private function vincularFiltroPadrao() {

    $aLinhas = $this->getLinhasRelatorio();
    foreach ($aLinhas as $oDadosLinha) {

      $aFiltros  = $oDadosLinha->aFiltros;
      $iLinhaSeq = $oDadosLinha->codigo;
      foreach ($aFiltros as $iFiltro => $oDadosFiltro) {

        $oDaoFiltro = db_utils::getDao('orcparamseqfiltropadrao');
        $sSqlFiltro = $oDaoFiltro->sql_query_file($oDadosFiltro->codigo);
        $rsFiltro   = $oDaoFiltro->sql_record($sSqlFiltro);

        $oDaoFiltro->o132_sequencial  = $oDadosFiltro->codigo;
        $oDaoFiltro->o132_orcparamrel = $this->getCodigoRelatorio();
        $oDaoFiltro->o132_orcparamseq = $iLinhaSeq;
        $oDaoFiltro->o132_anousu      = $oDadosFiltro->ano;
        $oDaoFiltro->o132_filtro      = urldecode($oDadosFiltro->filtro);

        if ($oDaoFiltro->numrows >= 1) {
          $oDaoFiltro->alterar($oDaoFiltro->o132_sequencial);
        } else {

          $this->consistenciaSequencial("orcparamelementospadrao_o132_sequencial_seq", $oDaoFiltro->o132_sequencial );
          $oDaoFiltro->incluir($oDaoFiltro->o132_sequencial);
        }
        if ($oDaoFiltro->erro_status == '0') {
          throw new DBException($oDaoFiltro->erro_msg);
        }
      }
    }
    return true;
  }


  public function importar() {

    if( !db_utils::inTransaction() ){
      throw new DBException(_M( self::CAMINHO_MENSAGENS . 'sem_transacao_ativa'));
    }

    $this->consistenciaPeriodo();
    $this->consistenciaColuna();
    $this->consistenciaRelatorio();
    $this->vincularPeriodos();
    $this->consistenciaLinha();
    $this->vincularColunas();
    $this->vincularFiltroPadrao();

    return true;
  }


}


?>
