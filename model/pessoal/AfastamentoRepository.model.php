<?php
/*
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2009  DBSeller Servicos de Informatica
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
/**
 * Repositorio para Afastamentos
 *
 * @abstract
 * @package Pessoal
 * @author Renan Silva <renan.silva@dbseller.com.br>
 * @version $Revision: 1.8 $
 */
class AfastamentoRepository {

  const MENSAGEM = 'recursoshumanos.pessoal.AfastamentoRepository.';

  /**
   * Array com instancias de afastamentos
   *
   * @static
   * @var Array
   * @access private
   */
  static private $aColecao = array();

  /**
   * Representa a instancia a classe
   *
   * @var AfastamentoRepository
   * @access private
   */
  private static   $oInstance;

  /**
   * Previne a criação do objeto externamente
   *
   * @return void
   */
  private function __construct() {
    return;
  }


  /**
   * Previne o clone
   *
   * @return void
   */
  private function __clone() {
    return;
  }

  /**
   * Retorna a instancia do repositório
   *
   * @return AfastamentoRepository
   */
  public static function getInstance() {

    if (empty(self::$oInstance)) {

      $sClasse  = get_class();
      self::$oInstance = new AfastamentoRepository();
    }

    return self::$oInstance;
  }

  /**
   * Adiciona a coleção um afastamento
   *
   * @param Afastamento $oAfastamento
   */
  public static function add(Afastamento $oAfastamento) {

    $oRepository = self::getInstance();
    $oRepository->aColecao[$oAfastamento->getCodigoAfastamento()] = $oAfastamento;
  }

  /**
   * @param $iCodigoAfastamento
   * @return Afastamento
   */
  public static function getInstanciaPorCodigo($iCodigoAfastamento) {

    $oRepository = self::getInstance();

    if(!isset($oRepository->aColecao[$iCodigoAfastamento])) {
      self::add($oRepository->make($iCodigoAfastamento));
    }

    return $oRepository->aColecao[$iCodigoAfastamento];
  }

  /**
   * Monta um objeto Afastamento
   *
   * @param  Integer $iCodigoAfastamento
   * @return Afastamento
   */
  public function make($iCodigoAfastamento) {

    $oAfastamento               = new Afastamento($iCodigoAfastamento);
    $oAfastamento->setCompetencia(DBPessoal::getCompetenciaFolha());

    if(!empty($iCodigoAfastamento)) {

      try{

        $oDaoAfasta    = new cl_afasta;
        $sSqlDaoAfasta = $oDaoAfasta->sql_query($iCodigoAfastamento);
        $rsDaoAfasta   = db_query($sSqlDaoAfasta);

        if(!$rsDaoAfasta) {
          throw new DBException("Ocorreu um erro ao buscar o afastamento selecionado");
        }

        if (pg_num_rows($rsDaoAfasta) > 0) {

          $oStdAfasta = db_utils::fieldsMemory($rsDaoAfasta, 0);
          $oAfastamento->setCompetencia(new DBCompetencia($oStdAfasta->r45_anousu, $oStdAfasta->r45_mesusu));
          $oAfastamento->setServidor(ServidorRepository::getInstanciaByCodigo($oStdAfasta->r45_regist, $oStdAfasta->r45_anousu, $oStdAfasta->r45_mesusu));
          $oAfastamento->setDataAfastamento(new DBDate($oStdAfasta->r45_dtafas));
          if (!empty($oStdAfasta->r45_dtreto)) {
            $oAfastamento->setDataRetorno(new DBDate($oStdAfasta->r45_dtreto));
          }
          $oAfastamento->setDataLancamento(new DBDate($oStdAfasta->r45_dtlanc));
          $oAfastamento->setCodigoSituacao($oStdAfasta->r45_situac);
          $oAfastamento->setCodigoAfastamentoSefip($oStdAfasta->r45_codafa);
          $oAfastamento->setCodigoRetornoSefip($oStdAfasta->r45_codret);
          $oAfastamento->setObservacao($oStdAfasta->r45_obs);
        }

      } catch (Exception $oErro) {
        $sErro = $oErro->getMessage();
      }
    }

    return $oAfastamento;
  }

  /**
   * Salva na base de dados um afastamento
   *
   * @param  Afastamento $oAfastamento
   * @return Afastamento
   */
  public static function persist($oAfastamento) {

    $oStdAfasta = new cl_afasta;

    $sCodigoAfastamentoSefip = $oAfastamento->getCodigoAfastamentoSefip();
    $sCodigoRetornoSefip     = $oAfastamento->getCodigoRetornoSefip();

    $oStdAfasta->r45_anousu = ($oAfastamento->getCompetencia() instanceof DBCompetencia ? $oAfastamento->getCompetencia()->getAno() : '');
    $oStdAfasta->r45_mesusu = ($oAfastamento->getCompetencia() instanceof DBCompetencia ? $oAfastamento->getCompetencia()->getMes() : '');
    $oStdAfasta->r45_regist = $oAfastamento->getServidor()->getMatricula();
    $oStdAfasta->r45_situac = $oAfastamento->getCodigoSituacao();
    $oStdAfasta->r45_codafa = (empty($sCodigoAfastamentoSefip) ? '  ' : $sCodigoAfastamentoSefip );
    $oStdAfasta->r45_codret = (empty($sCodigoRetornoSefip) ? '  ' : $sCodigoRetornoSefip );
    $oStdAfasta->r45_obs    = $oAfastamento->getObservacao();
    $oStdAfasta->r45_dtafas = (($oAfastamento->getDataAfastamento() instanceof DBDate) ? $oAfastamento->getDataAfastamento()->getDate() : ($oAfastamento->getDataAfastamento() != null ? $oAfastamento->getDataAfastamento() : ''));
    $oStdAfasta->r45_dtreto = (($oAfastamento->getDataRetorno() instanceof DBDate) ? $oAfastamento->getDataRetorno()->getDate() : ($oAfastamento->getDataRetorno() != null ? $oAfastamento->getDataRetorno() : ''));
    $oStdAfasta->r45_dtlanc = (($oAfastamento->getDataLancamento() instanceof DBDate) ? $oAfastamento->getDataLancamento()->getDate() : ($oAfastamento->getDataLancamento() != null ? $oAfastamento->getDataLancamento() : ''));

    $iCodigoAfastamento = $oAfastamento->getCodigoAfastamento();

    if(empty($iCodigoAfastamento)) {
      $oStdAfasta->incluir(null);
    } else {
      $oStdAfasta->r45_codigo = $iCodigoAfastamento;
      $oStdAfasta->alterar($iCodigoAfastamento);
    }

    if($oStdAfasta->erro_status == "0") {
      throw new DBException("Erro ao salvar afastamento na base de dados.");
    }

    $oAfastamento->setCodigoAfastamento($oStdAfasta->r45_codigo);

    return $oAfastamento;
  }

  /**
   * Remove um afastamento
   *
   * @param  Afastamento $oAfastamento
   * @return bool
   * @throws \DBException
   */
  public static function remove($oAfastamento) {

    $oDaoAfasta = new cl_afasta;
    $oDaoAfasta->excluir($oAfastamento->getCodigoAfastamento());

    if($oDaoAfasta->erro_status == '0') {
      throw new DBException("Erro ao exluir o afastamento.");
    }

    return true;
  }

  /**
   * Retorna todos os assentamentos do servidor
   * @param \Servidor $oServidor
   * @return Afastamento[]
   * @throws \DBException
   */
  public static function getAfastamentosPorServidor(Servidor $oServidor) {

    $oCompetencia  = DBPessoal::getCompetenciaFolha();
    $oDaoAfasta    = new cl_afasta;

    $sWhere  = "r45_regist = {$oServidor->getMatricula()} ";
    $sWhere .= " and r45_anousu = {$oCompetencia->getAno()} ";
    $sWhere .= " and r45_mesusu = {$oCompetencia->getMes()} ";

    $sSqlDaoAfasta = $oDaoAfasta->sql_query_file(null, "r45_codigo", "r45_dtafas, r45_dtreto", $sWhere);
    $rsDaoAfasta   = db_query($sSqlDaoAfasta);
    if (!$rsDaoAfasta) {
      throw new DBException("Erro ao pesquisar os afastamentos do servidor {$oServidor->getMatricula()}");
    }

    $aAssentamentos = db_utils::makeCollectionFromRecord($rsDaoAfasta, function($oAfasta)  {

      $oAssentamento = AfastamentoRepository::make($oAfasta->r45_codigo);
      return $oAssentamento;
    });
    return $aAssentamentos;
  }

  /**
   * Retorna todos os assentamentos do servidor pelo tipo de afastameto informado
   * @param \Servidor $oServidor
   * @param int $tipo
   * @return Afastamento[]
   * @throws \DBException
   */
  public static function getAfastamentosPorServidorETipo(Servidor $oServidor, $tipo) {

    $oCompetencia  = DBPessoal::getCompetenciaFolha();
    $oDaoAfasta    = new cl_afasta;

    $sWhere  = "r45_regist = {$oServidor->getMatricula()} ";
    $sWhere .= " and r45_anousu = {$oCompetencia->getAno()} ";
    $sWhere .= " and r45_mesusu = {$oCompetencia->getMes()} ";
    $sWhere .= " and r45_situac = {$tipo} ";

    $sSqlDaoAfasta = $oDaoAfasta->sql_query_file(null, "r45_codigo", "r45_dtafas, r45_dtreto", $sWhere);
    $rsDaoAfasta   = db_query($sSqlDaoAfasta);
    if (!$rsDaoAfasta) {
      throw new DBException("Erro ao pesquisar os afastamentos do servidor {$oServidor->getMatricula()}");
    }

    $aAssentamentos = db_utils::makeCollectionFromRecord($rsDaoAfasta, function($oAfasta)  {
      $oAssentamento = AfastamentoRepository::make($oAfasta->r45_codigo);
      return $oAssentamento;
    });
    return $aAssentamentos;
  }

    /**
     * @param $matricula
     * @param $ano
     * @throws BusinessException
     * @throws DBException
     * @throws ParameterException
     */
    public static function getPeriodoInicialAfastamentoFamiliar($matricula, $ano) {
        // Pegamos o primeiro afastamento do servidor
        $afastamentos = self::getAfastamentosPorServidorETipo(
            ServidorRepository::getInstanciaByCodigo($matricula),
            Afastamento::AFASTADO_CUIDAR_FAMILIAR
        );

        // A partir do primeiro afastamento, obtemos a data de referencia anual para calculos do afastamento
        $dataInicial = $afastamentos[0]->getDataAfastamento();
        // Pegamos a data atual
        $data = date("Y-m-d", db_getsession("DB_datausu"));
        $dataAtual = new DBDate($data);

        // A data de referencia é composta pelo mes e dia do primeiro afastamento do servidor com o ano atual ou
        // ano anterior no caso da data atual ser inferior a data inicial do afastamento
        $data = $ano . '-' . $dataInicial->getMes() . '-'
            . $dataInicial->getDia();
        $dataReferencia = new DBDate($data);

        // Caso a data de referencia seja superior a data atual, alteramos a data de referencia para 1 ano antes
        // Exemplo:
        //      data de referencia 28/10/2019
        //      data atual 20/10/2019
        //      data de referencia correta 28/10/2018
        if ($dataReferencia->getTimeStamp() > $dataAtual->getTimeStamp()) {
            $ano -= 1;
            $data = ($ano) . '-' . $dataInicial->getMes() . '-'
                . $dataInicial->getDia();
            $dataReferencia = new DBDate($data);
        }
        return $dataReferencia;
    }

    /**
     * @param int $matricula
     * @param string $dataInicio
     * @param string $dataFim
     * @param null $tipoAfastamento
     * @return array
     * @throws BusinessException
     */
    public static function getTodosAfastamentosNoPeriodo($matricula, $dataInicio, $dataFim, $tipoAfastamento = null)
    {
        $afastamentos = array();
        $oDaoAfastamento = new cl_afasta();
        $aWhere[] = "r45_regist = {$matricula}";

        $sWhereDatas = " ((r45_dtreto is null and r45_dtafas <= '{$dataFim}')";
        $sWhereDatas .= " or (r45_dtafas >= '{$dataInicio}' and r45_dtafas <= '{$dataFim}')";
        $sWhereDatas .= " or (r45_dtafas <= '{$dataInicio}' and r45_dtreto >= '{$dataInicio}'))";
        if (!empty($tipoAfastamento)) {
            $sWhereDatas .= " and r45_situac = {$tipoAfastamento} ";
        }

        $aWhere[] = $sWhereDatas;
        $sWhere = implode(" and ", $aWhere);
        $sSqlAfastamentosSemRemuneracao = $oDaoAfastamento->sql_query_file(null, "*", 'r45_dtafas, r45_dtreto', $sWhere);
        $rsAfastamentos = db_query($sSqlAfastamentosSemRemuneracao);
        if (!$rsAfastamentos) {
            throw new BusinessException("Erro ao buscar afastamentos para o servidor");
        }

        $iLinhasAfastamento = pg_num_rows($rsAfastamentos);
        $dataInicial = new \DBDate($dataInicio);
        $dataFinal = new \DBDate($dataFim);
        for ($iAfastamento = 0; $iAfastamento < $iLinhasAfastamento; $iAfastamento++) {
            $afastamento = db_utils::fieldsMemory($rsAfastamentos, $iAfastamento);
            $dataInicialAfastamento = new \DBDate($afastamento->r45_dtafas);
            $dataFinalAfastamento = $dataFinal;
            if (!empty($afastamento->r45_dtreto)) {
                $dataFinalAfastamento = new \DBDate($afastamento->r45_dtreto);
            }
            if ($dataInicialAfastamento->getTimeStamp() <= $dataInicial->getTimeStamp()) {
                $dataInicialAfastamento = $dataInicial;
            }
            if ($dataFinalAfastamento->getTimeStamp() >= $dataFinal->getTimeStamp()) {
                $dataFinalAfastamento = $dataFinal;
            }
            $afastamento->dias = DBDate::getIntervaloEntreDatas($dataFinalAfastamento, $dataInicialAfastamento)->days+1;
            $afastamentos[] = $afastamento;
        }
        return $afastamentos;
    }
}
