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
require_once(modification("libs/db_conecta.php"));

use App\Domain\RecursosHumanos\Pessoal\Repository\Helper\CompetenciaHelper;
use ECidade\RecursosHumanos\RH\PontoEletronico\Configuracoes\Collection\RegistraPontoEletronicoHistorico as RegistraPontoEletronicoHistoricoCollection;
use ECidade\RecursosHumanos\RH\PontoEletronico\Configuracoes\Model\RegistraPontoEletronicoHistorico as RegistraPontoEletronicoHistorico;

/**
 * Repositorio para Servidores
 *
 * @abstract
 * @package Pessoal
 * @author Rafael Serpa Nery <rafael.nery@dbseller.com.br>
 * @author Jeferson Belmiro  <jeferson.belmiro@dbseller.com.br>
 */
abstract class ServidorRepository
{

  /**
   * Array com instancias de servidores
   *
   * @static
   * @var Array
   * @access private
   */
    private static $aInstanciasServidores = array();

  /**
   * Adiciona uma rubrica ao array de servidores
   *
   * @static
   * @param Servidor $oServidor
   * @access private
   * @return void
   */
    private static function adicionar(Servidor $oServidor, $iAno, $iMes, $iInstituicao)
    {

        ServidorRepository::$aInstanciasServidores[ $oServidor->getMatricula() ][$iAno][$iMes][$iInstituicao] = $oServidor;
        return;
    }

  /**
   * Retorna instancia do servidor pela matricula e competencia
   *
   * @static
   * @param integer $iMatricula - codigo da matricula
   * @access public
   * @return Servidor
   */
    public static function getInstanciaByCodigo($iMatricula, $iAnoFolha = null, $iMesFolha = null, $iInstituicao = null, $usaInstituicao = true, $bSomenteCalculo = false)
    {

        if (empty($iInstituicao)) {
            $iInstituicao = db_getsession('DB_instit');
        }

        if (empty($iAnoFolha)) {
            $iAnoFolha = DBPessoal::getAnoFolha();
        }

        if (empty($iMesFolha)) {
            $iMesFolha = DBPessoal::getMesFolha();
        }

        if ($iMatricula == 0) {
            throw new BusinessException("Matrícula inválida.");
        }
        /**
         * Se não tiver servidor no array de instancias, adiciona
         */
        if (empty(ServidorRepository::$aInstanciasServidores[$iMatricula][$iAnoFolha][$iMesFolha][$iInstituicao])) {
            ServidorRepository::adicionar(new Servidor($iMatricula, $iAnoFolha, $iMesFolha, $iInstituicao, $usaInstituicao, $bSomenteCalculo), $iAnoFolha, $iMesFolha, $iInstituicao);
        }

        return ServidorRepository::$aInstanciasServidores[$iMatricula][$iAnoFolha][$iMesFolha][$iInstituicao];
    }

  /**
   * Busca Servidores pela Lotacao
   *
   * @static
   * @param mixed $iAnoFolha
   * @param mixed $iMesFolha
   * @param mixed
   *   -- Quando passado apenas um valor busca apenas pelo código da Lotação
   *   -- Quando For passado um array, buscara apenas as Lotacoes Indicadas
   *   -- Quando for passado dois numeros inteiros, vai buscar o intervalo entre eles
   * @param Integer $iInstituicao
   * @access public
   * @return Servidor[] - Array com servidores contidos no filtro informado
   */
    public static function getServidoresByLotacao($iAnoFolha, $iMesFolha, $mLotacoes, $iInstituicao = null)
    {

        if (empty($iInstituicao)) {
            $iInstituicao = db_getsession('DB_instit');
        }

        $aArgumentos           = func_get_args();
        $iQuantidadeArgumentos = func_num_args();
        $aServidores           = array();
        $sTipoBusca            = null;

      /**
       * Iniciando Validação dos parametros passados
       */
        if ($iQuantidadeArgumentos <= 2) {
            throw new ParameterException("Parâmetros passados Incorretamente");
        }

        if (empty($mLotacoes)) {
            throw new BusinessException("Erro ao informar lotação.");
        }

        if ($iQuantidadeArgumentos >= 3 && is_array($mLotacoes)) {
            $sTipoBusca    = "SELECIONADOS";
            $aSelecionados = $mLotacoes;
        } elseif ($iQuantidadeArgumentos >= 3 && DBNumber::isInteger($mLotacoes)) {
            $sTipoBusca  = "CHAVE";
            $iChaveBusca = $mLotacoes;
        } elseif ($iQuantidadeArgumentos >= 3) {
            $sTipoBusca  = "INTERVALO";
            $iChaveBusca = $mLotacoes;
            list($iPrimeiroArgumento, $iSegundoArgumento) = explode(",", $mLotacoes, 2);

            if (strpos($iSegundoArgumento, ",") !== false) {
                throw new BusinessException("Erro ao informar range de lotações.");
            }
        }

      /**
       * Lógica do SQL Implementada
       */
        switch ($sTipoBusca) {
            case "SELECIONADOS":
                  $sWhere = " rh02_lota in (". implode(", ", $aSelecionados) .")";
                break;

            case "CHAVE":
                $sWhere = " rh02_lota = $iChaveBusca ";
                break;

            case "INTERVALO":
                $sWhere = " rh02_lota between $iPrimeiroArgumento and $iSegundoArgumento ";
                break;
        }

        $sWhere          .= " and rh02_anousu = $iAnoFolha and rh02_mesusu = $iMesFolha and rh02_instit = $iInstituicao ";
        $oDaoRHPessoalMov = new cl_rhpessoalmov;
        $sSqlServidores   = $oDaoRHPessoalMov->sql_query_file(null, null, "rh02_regist", "rh02_regist", $sWhere);

        $rsServidores     = db_query($sSqlServidores);

        if (!$rsServidores) {
            throw new DBException("Erro ao Buscar sevidores pela lotação");
        }

      /**
       * Utilizado FOR por causa de Desempenho
       */
        for ($iIndiceServidor = 0; $iIndiceServidor < pg_num_rows($rsServidores); $iIndiceServidor++) {
            $iMatriculaServidor = db_utils::fieldsMemory($rsServidores, $iIndiceServidor)->rh02_regist;
            $aServidores[$iMatriculaServidor] = ServidorRepository::getInstanciaByCodigo($iMatriculaServidor, $iAnoFolha, $iMesFolha, $iInstituicao);
            unset($iMatriculaServidor);
        }

        return $aServidores;
    }

  /**
   * Busca Servidores pela Órgão
   *
   * @static
   * @param mixed $iAnoFolha
   * @param mixed $iMesFolha
   * @param mixed
   *   -- Quando passado apenas um valor busca apenas pelo código da Lotação
   *   -- Quando For passado um array, buscara apenas as Lotacoes Indicadas
   *   -- Quando for passado dois numeros inteiros, vai buscar o intervalo entre eles
   * @param
   * @access public
   * @return Servidor[] - Array com servidores contidos no filtro informado
   */
    public static function getServidoresByOrgao($iAnoFolha, $iMesFolha, $iInstituicao = null)
    {

        if (empty($iInstituicao)) {
            $iInstituicao = db_getsession('DB_instit');
        }

        $aArgumentos           = func_get_args();
        $iQuantidadeArgumentos = func_num_args();
        $aServidores           = array();
        $sTipoBusca            = null;

      /**
       * Iniciando Validação dos parametros passados
       */

        if ($iQuantidadeArgumentos == 2 || $iQuantidadeArgumentos > 4) {
            throw new ParameterException("Parametros passados Incorretamente");
        }

        if ($iQuantidadeArgumentos == 3 && is_array($aArgumentos[2])) {
            $sTipoBusca         = "SELECIONADOS";
            $aSelecionados      = $aArgumentos[2];
        } elseif ($iQuantidadeArgumentos == 3 && DBNumber::isInteger($aArgumentos[2])) {
            $sTipoBusca         = "CHAVE";
            $iChaveBusca        = $aArgumentos[2];
        } elseif ($iQuantidadeArgumentos == 4) {
            $iPrimeiroArgumento = $aArgumentos[2];
            $iSegundoArgumento  = $aArgumentos[3];

            if (!DBNumber::isInteger($iPrimeiroArgumento) || !DBNumber::isInteger($iPrimeiroArgumento)) {
                throw new ParameterException("Parametros devem ser inteiros");
            }

            $sTipoBusca         = "INTERVALO";
        } else {
            throw new ParameterException("Tipo(s) de Parametro(s) passados são incorretos");
        }

      /**
       * Lógica do SQL Implementada
       */
        switch ($sTipoBusca) {
            case "SELECIONADOS":
                  $sWhere = " rh26_orgao in (". implode(", ", $aSelecionados) .")";
                break;

            case "CHAVE":
                $sWhere = " rh26_orgao= $iChaveBusca ";
                break;

            case "INTERVALO":
                $sWhere = " rh26_orgao between $iPrimeiroArgumento and $iSegundoArgumento ";
                break;
        }

        $oDaoRHLotaExe    = new cl_rhlotaexe;
        $sSqlServidores   = $oDaoRHLotaExe->sql_query_servidores($iAnoFolha, $iMesFolha, "rh02_regist", $sWhere, $iInstituicao);
        $rsServidores     = db_query($sSqlServidores);

        if (!$rsServidores) {
            throw new DBException("Erro ao Buscar sevidores pelo Órgão");
        }

      /**
       * Utilizado FOR por causa de Desempenho
       */
        for ($iIndiceServidor = 0; $iIndiceServidor < pg_num_rows($rsServidores); $iIndiceServidor++) {
            $iMatriculaServidor = db_utils::fieldsMemory($rsServidores, $iIndiceServidor)->rh02_regist;
            $aServidores[$iMatriculaServidor] = ServidorRepository::getInstanciaByCodigo($iMatriculaServidor, $iAnoFolha, $iMesFolha, $iInstituicao);
        }

        return $aServidores;
    }

  /**
   * Busca Servidores pelo Regime
   *
   * @static
   * @param mixed $iAnoFolha
   * @param mixed $iMesFolha
   * @param mixed $iCodigoRegime
   * @param
   * @access public
   * @return Servidor[] - Array com servidores contidos no filtro informado
   */
    public static function getServidoresByRegime($iAnoFolha, $iMesFolha, $iCodigoRegime, $iInstituicao = null)
    {

        if (empty($iInstituicao)) {
            $iInstituicao = db_getsession('DB_instit');
        }

        $oDaoRHRegime     = new cl_rhregime;
        $sSqlServidores   = $oDaoRHRegime->sql_query_servidores($iAnoFolha, $iMesFolha, $iCodigoRegime, "rh02_regist", $iInstituicao);
        $rsServidores     = db_query($sSqlServidores);
        $aServidores      = array();

        if (!$rsServidores) {
            throw new DBException("Erro ao Buscar sevidores pelo Regime");
        }

      /**
       * Utilizado FOR por causa de Desempenho
       */
        for ($iIndiceServidor = 0; $iIndiceServidor < pg_num_rows($rsServidores); $iIndiceServidor++) {
            $oDadosServidor     =  db_utils::fieldsMemory($rsServidores, $iIndiceServidor);
            $iMatriculaServidor =  $oDadosServidor->rh02_regist;
            $aServidores[$iMatriculaServidor] = ServidorRepository::getInstanciaByCodigo($iMatriculaServidor, $iAnoFolha, $iMesFolha, $iInstituicao);
        }

        return $aServidores;
    }

  /**
   * Busca Servidores pelo LocalTrabalho
   *
   * @static
   * @param mixed $iAnoFolha
   * @param mixed $iMesFolha
   * @param mixed $iCodigoRegime
   * @param
   * @access public
   * @return Servidor[] - Array com servidores contidos no filtro informado
   */
    public static function getServidoresByLocalTrabalho($iAnoFolha, $iMesFolha, $iCodigoLocalTrabalho, $iInstituicao = null)
    {

        if (empty($iInstituicao)) {
            $iInstituicao = db_getsession('DB_instit');
        }

        $oDaoRHPesLocalTrab = db_utils::getDao("rhpeslocaltrab");
        $sSqlServidores     = $oDaoRHPesLocalTrab->sql_query_servidores($iAnoFolha, $iMesFolha, $iCodigoLocalTrabalho, "rh02_regist", $iInstituicao);
        $rsServidores       = db_query($sSqlServidores);
        $aServidores      = array();

        if (!$rsServidores) {
            throw new DBException("Erro ao Buscar sevidores pelo Local de Trabalho");
        }

      /**
       * Utilizado FOR por causa de Desempenho
       */
        for ($iIndiceServidor = 0; $iIndiceServidor < pg_num_rows($rsServidores); $iIndiceServidor++) {
            $oDadosServidor     =  db_utils::fieldsMemory($rsServidores, $iIndiceServidor);
            $iMatriculaServidor =  $oDadosServidor->rh02_regist;
            $aServidores[$iMatriculaServidor] = ServidorRepository::getInstanciaByCodigo($iMatriculaServidor, $iAnoFolha, $iMesFolha, $iInstituicao);
        }

        return $aServidores;
    }

  /**
   * Busca Servidores pelo Recurso
   *
   * @static
   * @param mixed $iAnoFolha
   * @param mixed $iMesFolha
   * @param mixed $iCodigoRegime
   * @access public
   * @return Servidor[] - Array com servidores contidos no filtro informado
   */
    public static function getServidoresByRecurso($iAnoFolha, $iMesFolha, $iCodigoRecurso, $iInstituicao = null)
    {

        if (empty($iInstituicao)) {
            $iInstituicao = db_getsession('DB_instit');
        }

        $oDaoRHLotaVinc = db_utils::getDao("rhlotavinc");
        $sSqlServidores = $oDaoRHLotaVinc->sql_query_servidores($iAnoFolha, $iMesFolha, $iCodigoRecurso, "rh02_regist", $iInstituicao);
        $rsServidores   = db_query($sSqlServidores);
        $aServidores    = array();

        if (!$rsServidores) {
            throw new DBException("Erro ao Buscar sevidores pelo Recurso");
        }

      /**
       * Utilizado FOR por causa de Desempenho
       */
        for ($iIndiceServidor = 0; $iIndiceServidor < pg_num_rows($rsServidores); $iIndiceServidor++) {
            $oDadosServidor                   =  db_utils::fieldsMemory($rsServidores, $iIndiceServidor);
            $iMatriculaServidor               =  $oDadosServidor->rh02_regist;
            $aServidores[$iMatriculaServidor] = ServidorRepository::getInstanciaByCodigo($iMatriculaServidor, $iAnoFolha, $iMesFolha, $iInstituicao);
        }

        return $aServidores;
    }

  /**
   * Retorna servidor
   * @param integer $iAnoFolha
   * @param integer $iMesFolha
   * @param integer $iCodigoSelecao
   * @param null    $iInstituicao
   * @return \Servidor[]
   * @throws \DBException
   */
    public static function getServidoresBySelecao($iAnoFolha, $iMesFolha, $iCodigoSelecao, $iInstituicao = null)
    {

        if (empty($iInstituicao)) {
            $iInstituicao = db_getsession('DB_instit');
        }

        $oDaoSelecao    = new cl_selecao;
        $sSqlServidores = $oDaoSelecao->sql_query_servidores($iAnoFolha, $iMesFolha, $iCodigoSelecao, "rh02_regist", $iInstituicao);

        $rsServidores   = db_query($sSqlServidores);
        $aServidores    = array();

        if (!$rsServidores) {
            throw new DBException("Erro ao Buscar sevidores pela Selecão");
        }

      /**
       * Utilizado FOR por causa de Desempenho
       */
        for ($iIndiceServidor = 0; $iIndiceServidor < pg_num_rows($rsServidores); $iIndiceServidor++) {
            $oDadosServidor                   = db_utils::fieldsMemory($rsServidores, $iIndiceServidor);
            $iMatriculaServidor               = $oDadosServidor->rh02_regist;
            $aServidores[$iMatriculaServidor] = ServidorRepository::getInstanciaByCodigo($iMatriculaServidor, $iAnoFolha, $iMesFolha, $iInstituicao);
        }

      /**
       * Ordenamos os servidores pela matricula.
       */
        ksort($aServidores);

        return $aServidores;
    }

    /**
   * Retorna array de matriculas
   * @param integer $iAnoFolha
   * @param integer $iMesFolha
   * @param integer $iCodigoSelecao
   * @param DBDate  $dataFinal
   * @param null    $iInstituicao
   * @return [int]
   * @throws \DBException
   */
    public static function getMatriculasBySelecao($iAnoFolha, $iMesFolha, $iCodigoSelecao, \DBDate $dataFinal, $iInstituicao = null)
    {

        if (empty($iInstituicao)) {
            $iInstituicao = db_getsession('DB_instit');
        }

        $oDaoSelecao    = new cl_selecao;
        $sSqlServidores = $oDaoSelecao->sql_query_servidores($iAnoFolha, $iMesFolha, $iCodigoSelecao, "rh02_regist, rh01_admiss", $iInstituicao);

        $rsServidores   = db_query($sSqlServidores);
        $aServidores    = array();

        if (!$rsServidores) {
            throw new DBException("Erro ao Buscar sevidores pela Selecão");
        }

        for ($iIndiceServidor = 0; $iIndiceServidor < pg_num_rows($rsServidores); $iIndiceServidor++) {
            $oDadosServidor = db_utils::fieldsMemory($rsServidores, $iIndiceServidor);
            $iMatriculaServidor = $oDadosServidor->rh02_regist;
            $admissao = new DBDate($oDadosServidor->rh01_admiss);
            if ($admissao->getDate() <= $dataFinal->getDate()) {
                $aServidores[$iMatriculaServidor] = $iMatriculaServidor;
            }
        }

        /**
         * Ordenamos os servidores pela matricula.
         */
        ksort($aServidores);

        return $aServidores;
    }

    public static function getServidoresBySelecaoAndCedencia($iAnoFolha, $iMesFolha, $iCodigoSelecao, $iInstituicao = null)
    {
        $clRhPessoalmov = new \cl_rhpessoalmov();

        if (empty($iInstituicao)) {
            $iInstituicao = db_getsession('DB_instit');
        }

        // $where = " (rh261_datamovimentacao is not null or rh261_devolucao is not null) ";
        $where = " rh261_credencial is not null ";


        if (!empty($iCodigoSelecao)) {
            $clselecao = new \cl_selecao();
            $condicaoSelecao = $clselecao->getCondicaoSelecao($iCodigoSelecao, $iInstituicao);
            $where .= " and {$condicaoSelecao} ";
        }

        $sSqlServidores = $clRhPessoalmov->sql_query_baseServidores(
            $iMesFolha,
            $iAnoFolha,
            $iInstituicao,
            "rh01_regist",
            $where,
            "rh01_regist",
            "rh01_regist"
        );

        $rsServidores   = db_query($sSqlServidores);
        $aServidores    = array();

        if (!$rsServidores) {
            throw new DBException("Erro ao Buscar sevidores pela Selecão");
        }

       /**
       * Utilizado FOR por causa de Desempenho
       */
        for ($iIndiceServidor = 0; $iIndiceServidor < pg_num_rows($rsServidores); $iIndiceServidor++) {
            $oDadosServidor                   = db_utils::fieldsMemory($rsServidores, $iIndiceServidor);
            $iMatriculaServidor               = $oDadosServidor->rh02_regist;
            $aServidores[$iMatriculaServidor] = ServidorRepository::getInstanciaByCodigo($iMatriculaServidor, $iAnoFolha, $iMesFolha, $iInstituicao);
        }

       /**
       * Ordenamos os servidores pela matricula.
       */
        ksort($aServidores);

        return $aServidores;
    }


    public static function getServidoresByMatriculas($iAnoFolha, $iMesFolha, $aMatriculas, $iInstituicao = null)
    {
        $aServidores    = array();
        foreach ($aMatriculas as $iMatriculaServidor) {
            $aServidores[$iMatriculaServidor] = ServidorRepository::getInstanciaByCodigo($iMatriculaServidor, $iAnoFolha, $iMesFolha, $iInstituicao);
        }

        ksort($aServidores);

        return $aServidores;
    }

    /**
   * Retorna uma coleção de objetos Servidor, selecionando por tipo de vinculo
   * @param integer $iAnoFolha
   * @param integer $iMesFolha
   * @param integer $iVinculoServidor
   * @param null    $sInstituicao
   * @return \Servidor[]
   */
    public static function getServidoresPorVinculo($iAnoFolha, $iMesFolha, $iVinculoServidor, $sInstituicao = null)
    {

        if (empty($sInstituicao)) {
            $sInstituicao = db_getsession('DB_instit');
        }

        $oDaoRhRegime = db_utils::getDao('rhregime');

      /**
       * Sql que retorna conjunto de servidores, dependendo do vinculo selecionado
       */
        $sSqlServidoresPorVinculo = $oDaoRhRegime->sql_query_servidorerPorVinculo(
            $iAnoFolha,
            $iMesFolha,
            $iVinculoServidor,
            "rh02_regist, rh02_instit",
            $sInstituicao
        );

        $rsServidoresPorVinculo = $oDaoRhRegime->sql_record($sSqlServidoresPorVinculo);

        $aServidores            = array();

        for ($iIndice =0; $iIndice < $oDaoRhRegime->numrows; $iIndice++) {
            $oServidorPorVinculo = db_utils::fieldsMemory($rsServidoresPorVinculo, $iIndice);
            $aServidores[$oServidorPorVinculo->rh02_regist] = ServidorRepository::getInstanciaByCodigo(
                $oServidorPorVinculo->rh02_regist,
                $iAnoFolha,
                $iMesFolha,
                $oServidorPorVinculo->rh02_instit
            );
        }

        return $aServidores;
    }

  /**
   * Retorna servidores no intervalo informado
   *
   * @param DBDate  $oDataInicial
   * @param DBDate  $oDataFinal
   * @param integer $iMatricula
   * @return Servidor[]
   */
    public static function getServidoresNoIntervalo(DBDate $oDataInicial, DBDate $oDataFinal, $iMatricula)
    {

        $aServidores   = array();
        $aCompetencias = array_reverse(DBPessoal::getCompetenciasIntervalo($oDataInicial, $oDataFinal));

        foreach ($aCompetencias as $oCompetencia) {
            try {
                $oServidorCompetencia = ServidorRepository::getInstanciaByCodigo(
                    $iMatricula,
                    $oCompetencia->getAno(),
                    $oCompetencia->getMes()
                );
            } catch (BusinessException $eErro) {
              //caso não exitsta servidor na competencia.
                continue;
            }
            $aServidores[] = $oServidorCompetencia;
        }

        return $aServidores;
    }

  /**
   * Retorna os servidores no ponto conforme folha de pagamento informada
   *
   * @param  FolhaPagamento $oFolhaPagamento [description]
   * @return
   */
    public static function getServidoresNoPontoPorFolhaPagamento(FolhaPagamento $oFolhaPagamento, $lRetornaDuploVinculo = false, $sMatriculas = null)
    {

        $iMes         = $oFolhaPagamento->getCompetencia()->getMes();
        $iAno         = $oFolhaPagamento->getCompetencia()->getAno();
        $sPonto       = "cl_" . $oFolhaPagamento->getTabelaPonto();
        $oDaoPonto    = new $sPonto();

        switch ($oFolhaPagamento->getTabelaPonto()) {
            case PontoComplementar::TABELA:
                $sSigla = PontoComplementar::SIGLA_TABELA;
                break;
            case PontoSalario::TABELA:
                $sSigla = PontoSalario::SIGLA_TABELA;
                break;
            default:
                return array();
        }

        $iInstituicao = db_getsession('DB_instit');

        $sWhereServidores  = "    {$sSigla}_anousu = {$iAno} ";
        $sWhereServidores .= "and {$sSigla}_mesusu = {$iMes} ";
        $sWhereServidores .= "and {$sSigla}_instit = {$iInstituicao} ";
        if (!empty($sMatriculas)) {
            $sWhereServidores .= "and {$sSigla}_regist in ({$sMatriculas})";
        }

        $sSqlServidores = $oDaoPonto->sql_query_file(null, null, null, null, "distinct {$sSigla}_regist as matricula", null, $sWhereServidores);

        $rsServidores   = db_query($sSqlServidores);

        if (!$rsServidores) {
            throw new DBException($oDaoPonto->erro_msg);
        }

        $aServidores = array();

        for ($iNumeroServidor = 0; $iNumeroServidor < pg_num_rows($rsServidores); $iNumeroServidor++) {
            $oDadosServidor                          = db_utils::fieldsMemory($rsServidores, $iNumeroServidor);
            $oServidor                               = self::getInstanciaByCodigo($oDadosServidor->matricula, $iAno, $iMes);
            $aServidores[$oDadosServidor->matricula] = $oServidor;

            if ($lRetornaDuploVinculo && $oServidor->hasServidorVinculado()) {
                $oServidorVinculado = $oServidor->getServidorVinculado();
                $aServidores[$oServidorVinculado->getMatricula()] = $oServidorVinculado;
            }
        }

        return $aServidores;
    }

  /**
   * Retorna os servidores no ponto conforme folha de pagamento informada
   *
   * @param  FolhaPagamento $oFolhaPagamento [description]
   * @return
   */
    public static function getServidoresNoCalculoPorFolhaPagamento(FolhaPagamento $oFolhaPagamento, $aServidoresCalcular = null)
    {

        $iMes         = $oFolhaPagamento->getCompetencia()->getMes();
        $iAno         = $oFolhaPagamento->getCompetencia()->getAno();
        $sCalculo     = "cl_" . $oFolhaPagamento->getTabelaCalculo();
        $oDaoCalculo    = new $sCalculo();

        switch ($oFolhaPagamento->getTabelaCalculo()) {
            case CalculoFolhaComplementar::TABELA:
                $sSigla = CalculoFolhaComplementar::SIGLA_TABELA;
                break;
            case CalculoFolhaSalario::TABELA:
                $sSigla = CalculoFolhaSalario::SIGLA_TABELA;
                break;
            default:
                return array();
        }

        $sWhere  = "{$sSigla}_anousu = {$iAno} and ";
        $sWhere .= "{$sSigla}_mesusu = {$iMes} and ";
        $sWhere .= "{$sSigla}_regist in (select rh144_regist ";
        $sWhere .= "                  from rhhistoricoponto ";

        if (!empty($aServidoresCalcular) && count($aServidoresCalcular) > 0) {
            $sWhere                   .= " where rh144_folhapagamento = {$oFolhaPagamento->getSequencial()} ";
            $sWhereMatriculasCalcular  = implode(",", $aServidoresCalcular);
            $sWhere                   .= " and rh144_regist in ({$sWhereMatriculasCalcular}) )";
        } else {
            $sWhere .= "                where rh144_folhapagamento = {$oFolhaPagamento->getSequencial()} )";
        }

        $sSqlServidores = $oDaoCalculo->sql_query_file($iAno, $iMes, null, null, "distinct {$sSigla}_regist as matricula", null, $sWhere);


        $rsServidores   = db_query($sSqlServidores);

        if (!$rsServidores) {
            throw new DBException("erro");
        }

        $aServidores = array();

        for ($iNumeroServidor = 0; $iNumeroServidor < pg_num_rows($rsServidores); $iNumeroServidor++) {
            $oServidor     = db_utils::fieldsMemory($rsServidores, $iNumeroServidor);
            $aServidores[] = self::getInstanciaByCodigo($oServidor->matricula, $iAno, $iMes);
        }
        return $aServidores;
    }

    public function getServidoresDuploVinculo(FolhaPagamento $oFolha)
    {

        $oDaoRHPessoalMov = new cl_rhpessoalmov();
        $sSqlRhPessoalMov = $oDaoRHPessoalMov->sql_duplo_vinculo($oFolha->getCompetencia()->getAno(), $oFolha->getCompetencia()->getMes());
        $rsRhPessoalMov   = db_query($sSqlRhPessoalMov);
        $aServidores      = array();


        if (pg_num_rows($rsRhPessoalMov) > 0) {
            for ($iTotalDuploVinculo = 0; $iTotalDuploVinculo < pg_num_rows($rsRhPessoalMov); $iTotalDuploVinculo++) {
                $oServidor = db_utils::fieldsMemory($rsRhPessoalMov, $iTotalDuploVinculo);
                $aServidores = array_merge($aServidores, explode(',', $oServidor->rh01_regist));
            }
        }

        return $aServidores;
    }

  /**
   * Retorna os servidores do histórico cálculo.
   *
   * @static
   * @access public
   * @param FolhaPagamento $oFolhaPagamento
   * @return Servidor[]
   * @throws DBException
   */
    public static function getServidoresHistoricoCalculo(FolhaPagamento $oFolhaPagamento, $aServidoresCalcular = null)
    {

        $iMes                 = $oFolhaPagamento->getCompetencia()->getMes();
        $iAno                 = $oFolhaPagamento->getCompetencia()->getAno();
        $oDaoHistoricoCalculo = new cl_rhhistoricocalculo();

        switch ($oFolhaPagamento->getTipoFolha()) {
            case FolhaPagamento::TIPO_FOLHA_SALARIO:
                $iSequencialFolha = $oFolhaPagamento->getSequencial();
                break;
            case FolhaPagamento::TIPO_FOLHA_COMPLEMENTAR:
                $iSequencialFolha = $oFolhaPagamento->getSequencial();
                break;
            case FolhaPagamento::TIPO_FOLHA_SUPLEMENTAR:
                $iSequencialFolha = $oFolhaPagamento->getSequencial();
                break;
            default:
                return array();
        }

        $sWhere         = "rh143_folhapagamento = {$iSequencialFolha} ";

        if (!empty($aServidoresCalcular) && count($aServidoresCalcular) > 0) {
            $sWhereMatriculasCalcular  = implode(",", $aServidoresCalcular);
            $sWhere                   .= " and rh143_regist in ({$sWhereMatriculasCalcular}) ";
        }

        $sSqlServidores = $oDaoHistoricoCalculo->sql_query_file(null, "distinct rh143_regist as matricula", null, $sWhere);
        $rsServidores   = db_query($sSqlServidores);

        if (!$rsServidores) {
            throw new DBException("Erro ao consultar os servidores do histórico cálculo.");
        }

        $aServidores = array();

        for ($iNumeroServidor = 0; $iNumeroServidor < pg_num_rows($rsServidores); $iNumeroServidor++) {
            $oServidor     = db_utils::fieldsMemory($rsServidores, $iNumeroServidor);
            $aServidores[] = self::getInstanciaByCodigo($oServidor->matricula, $iAno, $iMes);
        }

        return $aServidores;
    }

  /**
   * Retorna os servidores do histórico ponto.
   *
   * @static
   * @access public
   * @param FolhaPagamento $oFolhaPagamento
   * @return Servidor[]
   * @throws DBException
   */
    public static function getServidoresHistoricoPonto(FolhaPagamento $oFolhaPagamento)
    {

        $iMes               = $oFolhaPagamento->getCompetencia()->getMes();
        $iAno               = $oFolhaPagamento->getCompetencia()->getAno();
        $oDaoHistoricoPonto = new cl_rhhistoricoponto();

        switch ($oFolhaPagamento->getTipoFolha()) {
            case FolhaPagamento::TIPO_FOLHA_SALARIO:
                $iSequencialFolha = $oFolhaPagamento->getSequencial();
                break;
            case FolhaPagamento::TIPO_FOLHA_COMPLEMENTAR:
                $iSequencialFolha = $oFolhaPagamento->getSequencial();
                break;
            case FolhaPagamento::TIPO_FOLHA_SUPLEMENTAR:
                $iSequencialFolha = $oFolhaPagamento->getSequencial();
                break;
            default:
                return array();
        }

        $sWhere         = "rh144_folhapagamento = {$iSequencialFolha} ";
        $sSqlServidores = $oDaoHistoricoPonto->sql_query_file(null, "distinct rh144_regist as matricula", null, $sWhere);
        $rsServidores   = db_query($sSqlServidores);

        if (!$rsServidores) {
            throw new DBException("Erro ao consultar os servidores do histórico ponto.");
        }

        $aServidores = array();

        for ($iNumeroServidor = 0; $iNumeroServidor < pg_num_rows($rsServidores); $iNumeroServidor++) {
            $oServidor     = db_utils::fieldsMemory($rsServidores, $iNumeroServidor);
            $aServidores[] = self::getInstanciaByCodigo($oServidor->matricula, $iAno, $iMes);
        }

        return $aServidores;
    }

  /**
   * Persist o servidor na base de dados.
   *
   * @static
   * @access public
   * @param Servidor $oServidor
   * @return Servidor | false
   * @throws DBException
   */
    public static function persistServidor(Servidor $oServidor, $lSalvouVinculado = null)
    {

        $oRetorno = new stdClass();
        $oRetorno->erro_msg    = '';
        $oRetorno->erro_status = 1;
        $oRetorno->servidor    = null;

        if (is_null($oServidor->getContaBancaria()) || is_null($oServidor->getContaBancaria()->getSequencialContaBancaria())) {
            $oRetorno->servidor = $oServidor;
        } else {
            $iCodigoContaBancaria          = $oServidor->getContaBancaria()->salvar();
            $oDaoRHPessoalMovContaBancaria = new cl_rhpessoalmovcontabancaria();
            db_query("delete from rhpessoalmovcontabancaria where rh138_rhpessoalmov = {$oServidor->getCodigoMovimentacao()};");
            $oDaoRHPessoalMovContaBancaria->rh138_rhpessoalmov = $oServidor->getCodigoMovimentacao();
            $oDaoRHPessoalMovContaBancaria->rh138_contabancaria= $iCodigoContaBancaria;
            $oDaoRHPessoalMovContaBancaria->rh138_instit       = db_getsession("DB_instit");
            $oDaoRHPessoalMovContaBancaria->incluir(null);
            $oRetorno->servidor = $oServidor;
        }

      //Buscando da Base os dados da tabela rhpessoalmov
        $oDaoRHPessoalMov = new cl_rhpessoalmov();
        $dbwhere  = "     rh02_anousu = {$oServidor->getAnoCompetencia()} ";
        $dbwhere .= " and rh02_mesusu = {$oServidor->getMesCompetencia()} ";
        $dbwhere .= " and rh02_regist = {$oServidor->getMatricula()}      ";
        $rsRHPessoalMov = $oDaoRHPessoalMov->sql_record($oDaoRHPessoalMov->sql_query_file(null, null, "*", null, $dbwhere));

      //Usando o array post para pegar os nomes dos atributos da classe cl_rhpessoalmov
        $propriedadesRhPessoalMov = $_POST;
        foreach ($propriedadesRhPessoalMov as $key => $value) {
            if (strpos($key, 'rh02') === false) {
                unset($propriedadesRhPessoalMov[$key]);
            }
        }

      /**
       * Caso o servidor não tenha duplo vínculo ou o primeiro dos vinculados. Atualiza os atributos
       * da classe cl_rhpessoalmov com o que foi enviado via POST para persistir na base de dados
       */
        foreach ($propriedadesRhPessoalMov as $key => $value) {
            if (isset($oDaoRHPessoalMov->$key)) {
                $oDaoRHPessoalMov->$key = $value;
            }
        }

        /**
        * Caso vier o regime da jornada de trabalho
        * força a alteração pra mesma
        */
        if (isset($_POST['regime_jornada_trabalho']) && !empty($_POST['regime_jornada_trabalho'])) {
            $oDaoRHPessoalMov->rh02_regimejornadatrabalho = $_POST['regime_jornada_trabalho'];
        }

        $GLOBALS["HTTP_POST_VARS"]['rh02_abonopermanencia'] = $oDaoRHPessoalMov->rh02_abonopermanencia;
        $GLOBALS["HTTP_POST_VARS"]['rh02_equip']            = $oDaoRHPessoalMov->rh02_equip;
        $GLOBALS["HTTP_POST_VARS"]['rh02_deficientefisico'] = $oDaoRHPessoalMov->rh02_deficientefisico;
        $GLOBALS["HTTP_POST_VARS"]['rh02_portadormolestia'] = $oDaoRHPessoalMov->rh02_portadormolestia;

      /**
       * Persist a tabela rhpessoalmov na base de dados chamando o método
       * alterar ou incluir de acordo com o cenário a que se aplica
       */
        if (is_resource($rsRHPessoalMov) && pg_num_rows($rsRHPessoalMov) > 0) {
            $oDaoRHPessoalMov->rh02_seqpes = db_utils::fieldsMemory($rsRHPessoalMov, 0)->rh02_seqpes;
            $oDaoRHPessoalMov->rh02_instit = db_utils::fieldsMemory($rsRHPessoalMov, 0)->rh02_instit;

            if ($oDaoRHPessoalMov->alterar($oDaoRHPessoalMov->rh02_seqpes, $oDaoRHPessoalMov->rh02_instit)) {
                $oRetorno->servidor = $oServidor;
            } else {
                $oRetorno->erro_status = 0;
            }
        } else {
            $oDaoRHPessoalMov = new cl_rhpessoalmov();
            $oDaoRHPessoalMov->rh02_instit = $oServidor->getCodigoInstituicao();

            if ($oDaoRHPessoalMov->incluir(null, $oServidor->getCodigoInstituicao())) {
                $oRetorno->servidor = $oServidor;
            } else {
                $oRetorno->erro_status = 0;
            }
        }

        $oRetorno->erro_msg    = $oDaoRHPessoalMov->erro_msg;
        $oServidor->setTabelaPrevidencia($oDaoRHPessoalMov->rh02_tbprev);
        $oServidor->setAbonoPermanencia($oDaoRHPessoalMov->rh02_abonopermanencia);

        return $oRetorno;
    }


    public static function isMatriculaValida($iMatricula, $iAnoFolha = null, $iMesFolha = null, $iInstituicao = null)
    {

        if (empty($iInstituicao)) {
            $iInstituicao = db_getsession('DB_instit');
        }

        $lMatriculaValida = true;

        try {
            $oServidor = new Servidor($iMatricula, $iAnoFolha, $iMesFolha, $iInstituicao);
        } catch (Exception $eException) {
            $lMatriculaValida = false;
        }
        return $lMatriculaValida;
    }

    public static function getServidoresByTabelaPrevidencia($iTabelaPrevidencia, DBCompetencia $oCompetencia = null)
    {

        if (is_null($oCompetencia)) {
            $oCompetencia = DBPessoal::getCompetenciaFolha();
        }

        $oDaoRHPessoalMov       = new cl_rhpessoalmov();
        $sSqlMatriculas         = $oDaoRHPessoalMov->sql_query_file(
            null,
            db_getsession("DB_instit"),
            "rh02_regist",
            null,
            "rh02_tbprev = {$iTabelaPrevidencia} and rh02_anousu = {$oCompetencia->getAno()} and rh02_mesusu = {$oCompetencia->getMes()}"
        );
        $rsMatriculas           = db_query($sSqlMatriculas);
        $aServidoresEncontrados = array();

        if (!$rsMatriculas) {
            throw new DBException("Não foi possível retornar os dados dos servidores para a Tabela de Previdencia informada.");
        }

        $aServidores = db_utils::getCollectionByRecord($rsMatriculas);

        foreach ($aServidores as $oServidor) {
            $aServidoresEncontrados[] = ServidorRepository::getInstanciaByCodigo($oServidor->rh02_regist, $oCompetencia->getAno(), $oCompetencia->getMes());
        }
        return $aServidoresEncontrados;
    }

    public static function getServidoresByCgm(CgmFisico $oCgm, DBCompetencia $oCompetencia = null)
    {

        if (is_null($oCompetencia)) {
            $oCompetencia = DBPessoal::getCompetenciaFolha();
        }

        $oDaoRHPessoalMov       = new cl_rhpessoalmov();
        $sSqlMatriculas         = $oDaoRHPessoalMov->sql_query(
            null,
            db_getsession("DB_instit"),
            "rh02_regist",
            null,
            "rh01_numcgm = {$oCgm->getCodigo()}
      and rh02_anousu = {$oCompetencia->getAno()}
      and rh02_mesusu = {$oCompetencia->getMes()}
      and rh02_instit = ".db_getsession("DB_instit")
        );
        $rsMatriculas           = db_query($sSqlMatriculas);
        $aServidoresEncontrados = array();

        if (!$rsMatriculas) {
            throw new DBException("Não foi possível retornar os dados dos Servidores.");
        }

        $aServidores = db_utils::getCollectionByRecord($rsMatriculas);

        foreach ($aServidores as $oServidor) {
            $aServidoresEncontrados[] = ServidorRepository::getInstanciaByCodigo($oServidor->rh02_regist, $oCompetencia->getAno(), $oCompetencia->getMes());
        }
        return $aServidoresEncontrados;
    }


    public static function getServidoresPorTipoAssentamento($iTipoAssentamento, DBDate $oDataMinima = null)
    {

        $sData = "";
        if ($oDataMinima) {
            $sData = " and h16_dtconc >= '{$oDataMinima->getDate()}' ";
        }

        $oDaoAssentamento = new cl_assenta();
        $sSqlMatriculas   = $oDaoAssentamento->sql_query(
            null,
            'distinct h16_regist',
            null,
            "h16_assent = $iTipoAssentamento $sData"
        );

        $rsMatriculas  = db_query($sSqlMatriculas);

        if (!$rsMatriculas) {
            throw new DBException("Erro ao buscar os servidores pelo tipo de assentamento");
        }

      /**
       * @var Servidor[]
       */
        $aServidores = array();

        foreach (db_utils::getCollectionByRecord($rsMatriculas) as $oDadosServidor) {
            $aServidores[] = ServidorRepository::getInstanciaByCodigo(
                $oDadosServidor->h16_regist,
                DBPessoal::getCompetenciaFolha()->getAno(),
                DBPessoal::getCompetenciaFolha()->getMes()
            );
        }

        return $aServidores;
    }

    public static function getServidoresPorAssentamento($iMatricula, $iAssentamentos)
    {
    
        $oDaoAssentamento = new cl_assenta();
        $sSqlMatriculas   = $oDaoAssentamento->sql_query(
        null, 
        'h16_regist, h16_assent, h16_dtconc', 
        null, 
        "h16_regist = $iMatricula and
        h16_assent in ($iAssentamentos)"
        );

        $rsMatriculas  = db_query($sSqlMatriculas);

        if (!$rsMatriculas) {
        throw new DBException("Erro ao buscar os servidores pelo tipo de assentamento");
        }

        return pg_fetch_row($rsMatriculas);
    
    }

  /**
   * Retorna uma instância de Servidor pelo PIS
   *
   * @param $sPIS
   * @return Servidor
   * @throws BusinessException
   * @throws DBException
   * @throws ParameterException
   */
    public static function getServidorByPIS($sPIS)
    {

        if (empty($sPIS)) {
            throw new ParameterException("PIS/PASEP não informado.");
        }

        $oDaoRhpesdoc   = new cl_rhpesdoc();
        $sWhereRhpesdoc = "rh16_pis = '{$sPIS}' AND rh01_instit = " . db_getsession("DB_instit");
        $sSqlRhpesdoc   = $oDaoRhpesdoc->sql_query_pessoal_res(null, 'rh16_regist', null, $sWhereRhpesdoc);
        $rsRhpesdoc     = db_query($sSqlRhpesdoc);

        if (!$rsRhpesdoc) {
            throw new DBException("Erro ao buscar a matrícula do servidor pelo PIS.");
        }

        if (pg_num_rows($rsRhpesdoc) == 0) {
            return null;
        }

        return self::getInstanciaByCodigo(db_utils::fieldsMemory($rsRhpesdoc, 0)->rh16_regist);
    }

  /**
   * @param $iAno
   * @param $iMes
   * @return Servidor[]
   * @throws DBException
   */
    public static function getServidoresComPontoEletronico($iAno, $iMes)
    {
        $sWhere  = "RH01_REGIST IN(SELECT DISTINCT RH197_MATRICULA ";
        $sWhere .= "                 FROM PONTOELETRONICOARQUIVO ";
        $sWhere .= "                      INNER JOIN PONTOELETRONICOARQUIVODATA ON RH197_PONTOELETRONICOARQUIVO = RH196_SEQUENCIAL ";
        $sWhere .= "                      INNER JOIN PONTOELETRONICOARQUIVODATAREGISTRO ON RH198_PONTOELETRONICOARQUIVODATA = RH197_SEQUENCIAL";
        $sWhere .= "                WHERE rh196_efetividade_exercicio = {$iAno} AND rh196_efetividade_competencia::integer = {$iMes})";

        $oDaoRhPessoal = new cl_rhpessoal();
        $sSqlRhPessoal = $oDaoRhPessoal->sql_query_file(null, 'rh01_regist', null, $sWhere);
        $rsRhPessoal = db_query($sSqlRhPessoal);

        if (!$rsRhPessoal) {
            throw new DBException('Erro ao buscar os servidores do ponto eletrônico.');
        }

        return db_utils::makeCollectionFromRecord($rsRhPessoal, function ($oRetorno) {
            return ServidorRepository::getInstanciaByCodigo($oRetorno->rh01_regist);
        });
    }

  /**
   * @param $iAno
   * @param $iMes
   * @return Servidor[]
   * @throws DBException
   */
    public static function getServidoresPorCompetencia($iAno, $iMes, $oInstituicao = null, $validaRhPessoal = false)
    {
        if (empty($oInstituicao)) {
            $oInstituicao = InstituicaoRepository::getInstituicaoSessao();
        }

        $sWhere  = "     rh02_anousu = {$iAno} AND rh02_mesusu = {$iMes} AND rh02_instit = {$oInstituicao->getCodigo()}";
        $sWhere .= " AND not exists(select 1 from rhpesrescisao where rh05_seqpes = rh02_seqpes)";

        $oDaoRhPessoalMov = new cl_rhpessoalmov();
        $sSqlRhPessoalMov = $oDaoRhPessoalMov->sql_query_file(
            null,
            null,
            'rh02_regist',
            null,
            $sWhere,
            $validaRhPessoal
        );
        $rsRhPessoalMov = db_query($sSqlRhPessoalMov);

        if (!$rsRhPessoalMov) {
            throw new DBException('Erro ao buscar os servidores da competência.');
        }

        return db_utils::makeCollectionFromRecord($rsRhPessoalMov, function ($oRetorno) {
            return ServidorRepository::getInstanciaByCodigo($oRetorno->rh02_regist);
        });
    }

      /**
   * @param $iAno
   * @param $iMes
   * @return Servidor[]
   * @throws DBException
   */
  public static function getServidoresCedidosPorCompetencia($iAno, $iMes, $oInstituicao = null)
  {
      if (empty($oInstituicao)) {
          $oInstituicao = InstituicaoRepository::getInstituicaoSessao();
      }

      $clrhcedencia = new cl_rhcedencia();

      $sWhere = " rh01_instit = '{$oInstituicao->getCodigo()}' and (rh261_datamovimentacao is not null or rh261_devolucao is not null)
                and (rh261_devolucao is null
                    or (rh261_devolucao is not null and (extract(year from rh261_devolucao) <= '{$iAno}'
                        and extract(month from rh261_devolucao) <= '{$iMes}')))";

      $sqlRhCedencia = $clrhcedencia->sql_query_cedencia(null, 'rh01_regist', null, $sWhere);

      $result = db_query($sqlRhCedencia);


      if (!$result) {
          throw new DBException('Erro ao buscar os servidores da competência.');
      }

      return db_utils::makeCollectionFromRecord($result, function ($oRetorno) {
          return ServidorRepository::getInstanciaByCodigo($oRetorno->rh01_regist);
      });
  }

    /**
     * @param $matricula
     * @param null $dataInicio
     * @param null $dataFim
     * @return RegistraPontoEletronicoHistoricoCollection|null
     * @throws DBException
     */
    public static function getRegistraPontoNoPeriodoPorMatricula($matricula, $dataInicio = null, $dataFim = null)
    {
        $whereCondicao1 = array(
            "rh215_matricula = {$matricula}"
        );

        if (!empty($dataInicio) && !empty($dataFim)) {
            $whereCondicao1[] = "rh215_data BETWEEN '{$dataInicio}' AND '{$dataFim}'";
        }

        $whereCondicao1 = implode(' AND ', $whereCondicao1);

        if (empty($dataInicio)) {
            $dataInicio = date('Y-m-d', db_getsession('DB_datausu'));
        }

        $whereSubQuery = implode(' AND ', array(
            "rh215_matricula = {$matricula}",
            "rh215_data < '{$dataInicio}'"
        ));

        $subQuery = "
            SELECT rh215_sequencial
            FROM registrapontoeletronicohistorico
            WHERE {$whereSubQuery}
            ORDER BY rh215_data DESC
            LIMIT 1
        ";

        $sWhere = implode(' OR ', array(
            "({$whereCondicao1})",
            "(rh215_sequencial IN ({$subQuery}))"
        ));

        $oDaoRegistrapontoeletronicohistorico = new cl_registrapontoeletronicohistorico();
        $sSqlRegistrapontoeletronicohistorico = $oDaoRegistrapontoeletronicohistorico->sql_query_file(
            null,
            null,
            'rh215_data DESC',
            $sWhere
        );
        $rsRegistrapontoeletronicohistorico = db_query($sSqlRegistrapontoeletronicohistorico);

        if (!$rsRegistrapontoeletronicohistorico) {
            throw new DBException('Não foi possível buscar o histórico de registro de ponto automático para o servidor.');
        }

        if (pg_num_rows($rsRegistrapontoeletronicohistorico) === 0) {
            return null;
        }

        $historicoRegistraPonto = db_utils::makeCollectionFromRecord(
            $rsRegistrapontoeletronicohistorico,
            function ($oRetorno) {
                $registraPontoEletronicoHistorico = new RegistraPontoEletronicoHistorico();
                $registraPontoEletronicoHistorico->setSequencial($oRetorno->rh215_sequencial);
                $registraPontoEletronicoHistorico->setMatricula($oRetorno->rh215_matricula);
                $registraPontoEletronicoHistorico->setData(new DBDate($oRetorno->rh215_data));
                $registraPontoEletronicoHistorico->setRegistraPontoEletronico($oRetorno->rh215_registrapontoeletronico);

                return $registraPontoEletronicoHistorico;
            }
        );

        $colecaoHistoricoRegistraPonto = new RegistraPontoEletronicoHistoricoCollection();
        $colecaoHistoricoRegistraPonto->setHistoricoRegistraPonto($historicoRegistraPonto);

        return $colecaoHistoricoRegistraPonto;
    }

    /**
     * Retorna se a matrícula está rescindida na competência informada.
     *
     * @param integer $matricula
     * @param int $iAnoCompetencia
     * @param int $iMesCompetencia
     * @return bool
     * @throws Exception
     */
    public static function isMatriculaRescindidaNaCompetencia($iMatricula, $iAnoCompetencia, $iMesCompetencia)
    {

        $daoRHPessoal = new cl_rhpessoal();
        $sql = $daoRHPessoal->sql_verificaSituacaoServidor($iMatricula, $iAnoCompetencia, $iMesCompetencia);
        $rs = db_query($sql);

        if (!$rs) {
            throw new Exception("Erro ao buscar a rescisão para a matrícula {$iMatricula} na competência {$iMesCompetencia}/{$iAnoCompetencia}");
        }

        if (pg_num_rows($rs) == 0) {
            return false;
        }

        $dadosRescisao = db_utils::fieldsMemory($rs, 0);

        return !empty($dadosRescisao->rh05_recis);
    }

  /**
   * Retorna uma instancia do servidor informando o cpf
   *
   * @param string $cpf
   * @return Servidor
   * @throws Exception
   */
    public static function getByCPF($cpf)
    {
        if (empty($cpf)) {
            throw new ParameterException("CPF não informado.");
        }

        $cpf        = preg_replace('/\D/', '', $cpf);
        $oRhpessoal = new cl_rhpessoal();

        $sWhereRhpessoal = "cgm.z01_cgccpf = '{$cpf}'";
        $sSqlRhpessoal   = $oRhpessoal->sql_query_cgm(null, "rhpessoal.rh01_regist", null, $sWhereRhpessoal);
        $rsRhpessoal     = db_query($sSqlRhpessoal);

        if (!$rsRhpessoal) {
            throw new DBException("Erro ao buscar a matrícula do servidor pelo CPF.\n\n". pg_last_error());
        }

        if (pg_num_rows($rsRhpessoal) == 0) {
            return null;
        }

        return self::getInstanciaByCodigo(db_utils::fieldsMemory($rsRhpessoal, 0)->rh01_regist);
    }

  /**
   * Verifica se servidor esta rescindido tambem em virada de ano
   *
   * @param Integer $matricula
   * @param Integer $ano
   * @param Integer $mes
   * @return String
   */
    public static function verificaRescisaoServidor($matricula, $ano, $mes)
    {

        $sSql  = " select rh30_vinculo, rh05_recis, rh05_datapagamento \n";
        $sSql .= "   from rhpessoal \n";
        $sSql .= "        inner join rhpessoalmov  on rhpessoalmov.rh02_regist = rhpessoal.rh01_regist \n";
        $sSql .= "        inner join rhregime      on rhpessoalmov.rh02_codreg  = rhregime.rh30_codreg     \n";
        $sSql .= "        left  join rhpesrescisao on rhpesrescisao.rh05_seqpes = rhpessoalmov.rh02_seqpes \n";
        $sSql .= "  where rh01_regist = {$matricula} AND ";
        $sSql .= " (";
        $sSql .= "( extract(MONTH FROM rh05_recis) = {$mes} AND extract(YEAR FROM rh05_recis) = {$ano} ) ";
        $sSql .= " OR ";
        $sSql .= " ( rhpessoalmov.rh02_anousu = {$ano} AND rhpessoalmov.rh02_mesusu = {$mes} ) ";
        $sSql .= " )";
        $sSql .= "group by rh30_vinculo, rh05_recis, rh05_datapagamento limit 1 ";

        $rs = db_query($sSql);

        if (!$rs) {
            throw new Exception("Erro ao buscar a rescisão para a matrícula {$matricula} na competência {$mes}/{$ano}");
        }

        if (pg_num_rows($rs) == 0) {
            return false;
        }

        $dadosRescisao = db_utils::fieldsMemory($rs, 0);

        return !empty($dadosRescisao->rh05_recis);
    }

    public static function isServidorComESemVinculoByCgm(CgmFisico $cgm, DBCompetencia $competencia)
    {

        $retorno = false;
        $possuiVinculo = false;
        $naoPossuiVinculo = false;
        $aServidores = self::getServidoresByCgm($cgm, $competencia);

        foreach ($aServidores as $servidor) {
            if (!$servidor->isRescindido() && ($servidor->isRgps() || $servidor->isRpps())) {
                if ($servidor->temVinculoEmpregaticio()) {
                    $possuiVinculo = true;
                } else {
                    if (!$servidor->isEstagiario()) {
                        $naoPossuiVinculo = true;
                    }
                }
            }
        }

        if ($possuiVinculo && $naoPossuiVinculo) {
            $retorno = true;
        }

        return $retorno;
    }

    /**
     * @param $iAno
     * @param $iMes
     * @return Servidor[]
     * @throws DBException
     */
    public static function getServidoresPorCompetenciaAdmissao($iAno, $iMes, $oInstituicao = null)
    {
        if (empty($oInstituicao)) {
            $oInstituicao = InstituicaoRepository::getInstituicaoSessao();
        }

        $oDaoRhPessoal = new cl_rhpessoal();
        $dadaInicio = "{$iAno}-{$iMes}-01";
        $quantidadeDias = DBDate::getQuantidadeDiasMes($iMes, $iAno);
        $dadaFim = "{$iAno}-{$iMes}-{$quantidadeDias}";

        $where = "rh01_admiss between '{$dadaInicio}' and '{$dadaFim}' and rh01_instit = {$oInstituicao->getCodigo()}";

        $sSqlRhPessoal = $oDaoRhPessoal->sql_query_file(null, 'rh01_regist', null, $where);
        $rsRhPessoal = db_query($sSqlRhPessoal);

        if (!$rsRhPessoal) {
            throw new DBException('Erro ao buscar os servidores da competência.');
        }

        return db_utils::makeCollectionFromRecord($rsRhPessoal, function ($oRetorno) {
            return ServidorRepository::getInstanciaByCodigo($oRetorno->rh01_regist);
        });
    }

    /**
     * @param $iAno
     * @param $iMes
     * @return Servidor[]
     * @throws DBException
     */
    public static function getServidoresPorCompetenciaInformadaAndCedencia($iAno, $iMes, $oInstituicao = null)
    {
        if (empty($oInstituicao)) {
            $oInstituicao = InstituicaoRepository::getInstituicaoSessao();
        }

        $oDaoRhPessoal = new cl_rhpessoal();

        $where = " rh261_credencial is not null
            and rh01_instit = '{$oInstituicao->getCodigo()}' and (rh261_datamovimentacao is not null or rh261_devolucao is not null)
            and (rh261_devolucao is null or (extract(year from rh261_devolucao) <= '{$iAno}' and extract(month from rh261_devolucao) <= '{$iMes}'))";

        $sSqlRhPessoal = $oDaoRhPessoal->sql_query_cedencia(null, 'rh01_regist', null, $where);
        $rsRhPessoal = db_query($sSqlRhPessoal);

        if (!$rsRhPessoal) {
            throw new DBException('Erro ao buscar os servidores da competência.');
        }

        return db_utils::makeCollectionFromRecord($rsRhPessoal, function ($oRetorno) {
            return ServidorRepository::getInstanciaByCodigo($oRetorno->rh01_regist);
        });
    }

    /**
     * @param $iAno
     * @param $iMes
     * @return ServidorRepository
     * @throws DBException
     */
    public static function getServidoresPorCpf($cpf)
    {
        if (empty($cpf)) {
            throw new ParameterException("CPF não informado.");
        }

        $cpf        = preg_replace('/\D/', '', $cpf);
        $oRhpessoal = new cl_rhpessoal();

        $sWhereRhpessoal = "cgm.z01_cgccpf = '{$cpf}'";
        $sSqlRhpessoal   = $oRhpessoal->sql_query_cgm(null, "rhpessoal.rh01_regist", null, $sWhereRhpessoal);
        $rsRhPessoal     = db_query($sSqlRhpessoal);

        if (!$rsRhPessoal) {
            throw new DBException("Erro ao buscar o servidor pelo CPF.\n\n". pg_last_error());
        }

        if (pg_num_rows($rsRhPessoal) == 0) {
            return null;
        }

        return db_utils::makeCollectionFromRecord($rsRhPessoal, function ($oRetorno) {
            return ServidorRepository::getInstanciaByCodigo($oRetorno->rh01_regist);
        });
    }

    /**
     * @param $iAno
     * @param $iMes
     * @return Servidor[]
     * @throws DBException
     */
    public static function getServidoresPorCompetenciaRescisao($iAno, $iMes, $instituicao)
    {

        $anoCompetencia = CompetenciaHelper::get()->getAno();
        $mesCompetencia = CompetenciaHelper::get()->getMes();

        $sql = "SELECT  rh02_regist FROM rhpesrescisao
            INNER JOIN rhpessoalmov ON rhpesrescisao.rh05_seqpes = rhpessoalmov.rh02_seqpes
            WHERE EXTRACT (MONTH FROM rh05_recis)={$iMes} AND EXTRACT (YEAR FROM rh05_recis)={$iAno}
            AND rh02_instit = {$instituicao} AND rh02_anousu={$anoCompetencia} AND rh02_mesusu={$mesCompetencia}";

        $rs = db_query($sql);
        if (!$rs) {
            throw new DBException('Erro ao buscar os servidores da competência.');
        }

        return db_utils::makeCollectionFromRecord($rs, function ($oRetorno) {
            return ServidorRepository::getInstanciaByCodigo($oRetorno->rh02_regist);
        });
    }

    public static function getServidoresCompetenciaReintegracao($iAno, $iMes, $instituicao)
    {
        $sSql = "SELECT h25_regist FROM recursoshumanos.rhadmissaodado
            WHERE EXTRACT (MONTH FROM h25_datareintegracao) = {$iMes}
            AND EXTRACT (YEAR FROM h25_datareintegracao) = {$iAno}
            AND h25_instit = {$instituicao}";

        $rs = db_query($sSql);
        if (!$rs) {
            throw new DBException('Erro ao buscar os servidores da competência.');
        }

        return db_utils::makeCollectionFromRecord($rs, function ($oRetorno) {
          return ServidorRepository::getInstanciaByCodigo($oRetorno->h25_regist);
        });
    }
}
