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

require_once(modification("libs/exceptions/ParameterException.php"));
require_once(modification("libs/exceptions/DBException.php"));
require_once(modification("libs/exceptions/FileException.php"));
require_once(modification("libs/exceptions/BusinessException.php"));

/**
 * Singleton
 *
 * @abstract
 * @package pessoal
 *
 * @author Rafael Serpa Nery <rafael.nery@dbseller.com.br>
 * @author Jeferson Belmiro  <jeferson.belmiro@dbseller.com.br>
 */
abstract class DBPessoal
{
    private static $lUtilizacaoSuplementar = null;
    /**
     * Mes de competencia da folha
     *
     * @static
     * @var integer
     * @access private
     */
    static private $iMesCompetenciaFolha;

    /**
     * ANo de competencia da folha
     *
     * @static
     * @var integer
     * @access private
     */
    static private $iAnoCompetenciaFolha;

    /**
     * Busca e define mes e ano da folha
     *
     * @throws BusinessException
     * @throws DBException
     */
    static private function setCompetencia()
    {
        $sSqlCompetencia = " select r11_anousu,                            \n ";
        $sSqlCompetencia .= "        r11_mesusu                              \n";
        $sSqlCompetencia .= "   from cfpess                                  \n";
        $sSqlCompetencia .= "  where r11_instit = " . db_getsession("DB_instit", false);
        $sSqlCompetencia .= "  order by r11_anousu desc,                     \n";
        $sSqlCompetencia .= "           r11_mesusu desc                      \n";
        $sSqlCompetencia .= "  limit 1                                       \n";

        $rsCompetencia = db_query($sSqlCompetencia);

        if (!$rsCompetencia) {
            throw new DBException("Erro ao Buscar os Dados da Competencia da Folha. " . pg_last_error());
        }

        if (pg_num_rows($rsCompetencia) == 0) {
            throw new BusinessException("Não existe nenhuma compentencia iniciada, Favor efetuar Abertura da Folha.");
        }

        $oDadosCompetencia = pg_fetch_object($rsCompetencia, 0);
        DBPessoal::$iMesCompetenciaFolha = str_pad($oDadosCompetencia->r11_mesusu, 2, 0, STR_PAD_LEFT);
        DBPessoal::$iAnoCompetenciaFolha = $oDadosCompetencia->r11_anousu;

        return;
    }

    /**
     * Retorna mes de competencia da folha
     *
     * @return int
     * @throws BusinessException
     * @throws DBException
     */
    static public function getMesFolha()
    {
        if (is_null(DBPessoal::$iMesCompetenciaFolha)) {
            DBPessoal::setCompetencia();
        }

        return DBPessoal::$iMesCompetenciaFolha;
    }

    /**
     * Retorna ano de competencia da folha
     *
     * @return int
     * @throws BusinessException
     * @throws DBException
     */
    static public function getAnoFolha()
    {
        if (is_null(DBPessoal::$iAnoCompetenciaFolha)) {
            DBPessoal::setCompetencia();
        }

        return DBPessoal::$iAnoCompetenciaFolha;
    }

    /**
     * Retorna a ultima competência da folha de pagamento
     *
     * @return DBCompetencia
     * @throws BusinessException
     * @throws Exception
     */
    static public function getCompetenciaFolha()
    {
        return new DBCompetencia(DBPessoal::getAnoFolha(), DBPessoal::getMesFolha());
    }

    /**
     * Retorna quantidade de avos no periodo especificado
     *
     * @param DBDate $oDataInicial
     * @param DBDate $oDataFinal
     * @param DBDate|null $oHoje
     * @return float|int
     * @throws ParameterException
     */
    static public function getQuantidadeAvos(DBDate $oDataInicial, DBDate $oDataFinal, DBDate $oHoje = null)
    {
        require_once(modification("libs/db_libpessoal.php"));


        if (empty($oHoje)) {
            $oHoje = new DBDate(date("Y-m-d", db_getsession("DB_datausu")));
        }

        $iQuantidadeDiasMesAtual = DBDate::getQuantidadeDiasMes($oHoje->getMes(), $oHoje->getAno());
        $iQuantidadeDiasMesInicial = DBDate::getQuantidadeDiasMes($oDataInicial->getMes(), $oDataInicial->getAno());
        $iQuantidadeDiasMesFinal = DBDate::getQuantidadeDiasMes($oDataFinal->getMes(), $oDataFinal->getAno());

        /**
         * Quantidade de Avos Representa a Fração de 1 mes no ano (1/12 - um, doze avos)
         */
        $iQuantidadeAvos = 0;

        if ($oHoje->getTimeStamp() < $oDataFinal->getTimeStamp()) {
            if ($iQuantidadeDiasMesAtual == $iQuantidadeDiasMesInicial) {
                // no mesmo ano so ver a diferenca de meses
                $iQuantidadeAvos = $oHoje->getMes() - $oDataInicial->getMes();
            } else {
                // meses restante do ano anterior mais meses do ano posterior
                // (12 - mes) = quantidade de meses a contar como periodo aquisitivo no ano
                $iQuantidadeAvos = (12 - $oDataInicial->getMes()) + $oHoje->getMes();
            }

            if (($iQuantidadeDiasMesAtual - $iQuantidadeDiasMesInicial) > 14) {
                // a fração superior a 14 dias - 1/12 avo. , conta como um mes a mais
                $iQuantidadeAvos++;
            }
        } else {
            $iQuantidadeAvos = DBDate::calculaIntervaloEntreDatas($oDataFinal, $oDataInicial,
                "d") / 30; //MES Comercial, 30 dias

            if ($iQuantidadeAvos < 0) {
                $iQuantidadeAvos = $iQuantidadeAvos * (-1);
            }

            // o periodo aquisitivo nao pode ser maior que um ano ou seja 12 meses
            if ($iQuantidadeAvos > 12) {
                $iQuantidadeAvos = 12;
            }
        }

        return $iQuantidadeAvos;
    }

    /**
     * Retorna as Competencias da Folha de Pagamento Tendo Base intervalo de duas datas
     *
     * @param DBDate $oDataInicial
     * @param DBDate $oDataFinal
     * @return array
     * @throws Exception
     */
    static function getCompetenciasIntervalo(DBDate $oDataInicial, DBDate $oDataFinal)
    {
        /**
         * Competencia inicial
         */
        $iMesInicio = (int)$oDataInicial->getMes();
        $iAnoInicio = (int)$oDataInicial->getAno();

        /**
         * Competencia final
         */
        $iMesFim = (int)$oDataFinal->getMes();
        $iAnoFim = (int)$oDataFinal->getAno();

        /**
         * Valida datas, data inicial nao pode ser maior que final
         */
        if ($oDataInicial->getTimeStamp() > $oDataFinal->getTimeStamp()) {
            throw new Exception('Data inicial não pode ser maior que a final');
        }

        $aRetorno = array();

        $iAnoCalculado = $iAnoInicio;
        $iMesCalculado = $iMesInicio;

        /**
         * Subrai 1 mes
         * quando:
         * - Mes de inicio é igual mes final
         * - Ano de inicio diferente do ano final
         * - Mes final maior que 1, para nao deixar mes 0
         */
        if ($iMesInicio == $iMesFim && $iAnoInicio != $iAnoFim && $iMesFim > 1) {
            $iMesFim = $iMesFim - 1;
        }

        while (1) {
            $aRetorno[] = new DBCompetencia($iAnoCalculado, $iMesCalculado);

            /**
             * data dos periodos iguais
             */
            if ($iAnoInicio == $iAnoFim && $iMesInicio == $iMesFim) {
                break;
            }

            /**
             * Final do periodo calculado
             * Ano e mes calculado igual o ano e mes da data final
             */
            if ($iAnoCalculado == $iAnoFim && $iMesCalculado == $iMesFim) {
                break;
            }

            if ($iMesCalculado == 12) {

                $iMesCalculado = 1;
                $iAnoCalculado++;
                continue;
            }
            $iMesCalculado++;
            continue;
        }

        return $aRetorno;
    }

    /**
     * Retorna as Variáveis para cálculo conforme o servidor
     *
     * @example
     *   $iMatricula = 1
     *   $iAnoFolha  = 2013
     *   $iMesFolha  = 11
     *   $oServidor  = ServidorRepository::getInstanciaByCodigo($iMatricula, $iAnoFolha, $iMesFolha);
     *   $oVariaveis = DBPessoal::getVariaveisCalculo($oServidor);
     *
     * @param Servidor $oServidor
     * @param null $sVariavel
     * @return _db_fields|stdClass
     * @throws DBException
     */
    public static function getVariaveisCalculo(Servidor $oServidor, $sVariavel = null)
    {
        $iInstituicao = $oServidor->getInstituicao()->getSequencial();
        $iAno = $oServidor->getAnoCompetencia();
        $iMes = $oServidor->getMesCompetencia();
        $iMatricula = $oServidor->getMatricula();

        $oDaoRhPessoalMov = db_utils::getDao('rhpessoalmov');
        $sSqlVariaveis = $oDaoRhPessoalMov->sql_getVariaveisCalculo($iMatricula, $iAno, $iMes, $iInstituicao);
        $rsSqlVar = db_query($sSqlVariaveis);

        if (!empty($sVariavel)) {
            $sVariavel = strtolower($sVariavel);
        }

        // Conta os domingos no mes
        $rsF031 = db_query($sSqlF031 = "SELECT sum(case (EXTRACT(DOW FROM k13_data)) when 0 then 1 else 0 end) as F031
                                      FROM calend
                                     WHERE extract('year' FROM k13_data)  = {$iAno}
                                       AND extract('month' FROM k13_data) = {$iMes}");

        if (!$rsF031) {
            throw new DBException("Ocorreu um erro ao consultar o valor da F031.\nContate o suporte.");
        }

        $F031 = db_utils::fieldsMemory($rsF031, 0)->f031;


        //Conta os dias úteis no mes
        $rsF032 = db_query($sSqlF032 = "SELECT
                                      count(distinct datas_mes) as F032
                                    FROM (SELECT
                                            (ano_competencia||'-'||lpad(mes_competencia, 2, '0')||'-'||lpad(generate_series(1, ndias(ano_competencia, mes_competencia))::varchar, 2, '0'))::date AS datas_mes
                                            FROM (SELECT
                                                        {$iAno} AS ano_competencia,
                                                        {$iMes} AS mes_competencia
                                                  ) AS competencia
                                          ) as datas_do_mes
                                    WHERE datas_mes NOT IN  ( SELECT
                                                                r62_data
                                                                FROM calendf
                                                               WHERE r62_calend = (SELECT
                                                                                    rh53_calend
                                                                                   FROM rhcadcalend
                                                                                   INNER JOIN rhlotacalend ON rh64_calend = rh53_calend
                                                                                   WHERE rh53_instit = {$iInstituicao}
                                                                                     AND rh64_lota   = {$oServidor->getCodigoLotacao()}
                                                                                  )
                                                                 AND extract('year' from r62_data)  = {$iAno}
                                                                 AND extract('month' from r62_data) = {$iMes}
                                                            )");

        if (!$rsF032) {
            throw new DBException("Ocorreu um erro ao consultar o valor da F032.\nContate o suporte.");
        }

        // Dias Trabalhados para desconto do Vale Alimentacao
        $rsF033 = db_query($sSqlF033 = "
            SELECT coalesce(SUM(existe_marcacao), 0) as F033
                FROM
                  (SELECT *
                   FROM
                     (SELECT rh197_data,
                             CASE WHEN pontoeletronicoarquivodataregistro.rh198_registro <> '' THEN 1 ELSE 0 END AS existe_marcacao
                      FROM pontoeletronicoarquivodata
                      LEFT JOIN pontoeletronicoarquivodataregistro
                      ON pontoeletronicoarquivodataregistro.rh198_pontoeletronicoarquivodata = pontoeletronicoarquivodata.rh197_sequencial,

                        (SELECT *
                         FROM configuracoesdatasefetividade
                         WHERE rh186_exercicio = fc_anofolha(rh186_instituicao)
                           AND rh186_competencia::integer = fc_mesfolha(rh186_instituicao)
                           AND rh186_instituicao = {$iInstituicao}) AS efetividade
                      WHERE rh197_data BETWEEN rh186_datainicioefetividade AND rh186_datafechamentoefetividade
                        AND rh197_matricula = {$iMatricula}
                      GROUP BY rh197_data,
                               pontoeletronicoarquivodataregistro.rh198_registro
                      ORDER BY rh197_data) AS dados group BY rh197_data,
                                                              existe_marcacao
           ORDER BY rh197_data) AS diastrabalhados
    ");

        if (!$rsF033) {
            throw new DBException("Ocorreu um erro ao consultar o valor da F033.\nContate o suporte.");
        }

        $F032 = db_utils::fieldsMemory($rsF032, 0)->f032;

        $F033 = db_utils::fieldsMemory($rsF033, 0)->f033;

        $F032 = $F032 - $F031;


        $fs = db_utils::fieldsMemory($rsSqlVar, 0);
        $fs->f031 = $F031;
        $fs->f032 = $F032;
        $fs->f033 = $F033;

        return (!empty($sVariavel) && isset($fs->{$sVariavel}) ? $fs->{$sVariavel} : $fs);
    }

    /**
     * Valida se o sistema esá apto a utilizar a nova estrutura de cálculo,
     * que modifica basicamente o cálculo de complementar e cria a FIGURA DE FOLHA SUPLEMENTAR
     *
     * @return bool|null
     * @throws BusinessException
     */
    public static function verificarUtilizacaoEstruturaSuplementar()
    {
        if (!is_null(self::$lUtilizacaoSuplementar)) {
            return self::$lUtilizacaoSuplementar;
        }

        $oDaoCfPess = new cl_cfpess();
        $oInstituicao = InstituicaoRepository::getInstituicaoSessao();
        $oCompetencia = DBPessoal::getCompetenciaFolha();

        $sSqlSuplementar = $oDaoCfPess->sql_query_suplementar($oInstituicao, $oCompetencia);
        $rsSuplementar = db_query($sSqlSuplementar);

        if ($rsSuplementar && pg_num_rows($rsSuplementar) > 0) {
            $sUtilizacao = db_utils::fieldsMemory($rsSuplementar, 0)->r11_suplementar;

            return self::$lUtilizacaoSuplementar = $sUtilizacao == "1" ? true : false;

        }

        return self::$lUtilizacaoSuplementar = false;
    }

    /**
     * @param Instituicao $oInstituicao
     * @param DBCompetencia $oCompetencia
     * @return bool
     * @deprecated  Não necessária a utilização
     */
    public static function declararEstruturaFolhaPagamento(Instituicao $oInstituicao, DBCompetencia $oCompetencia)
    {
        return true;
    }

    /**
     * @param $lSuplementar
     * @return bool
     * @deprecated  Não necessária a utilização
     */

  /**
   * Função que verifica se utiliza o filtro da lotação por usuário
   */
  public static function utilizaFiltroLotacoesPorUsuario($iInstit = null, $iAno = null, $iMes = null) {

    $Cfpess           = new cl_cfpess();

    if (empty($iInstit)) {
      $iInstit        = db_getsession('DB_instit');
    }

    if (empty($iAno)) {
      $iAno           = DBPessoal::getAnoFolha();
    }

    if (empty($iMes)) {
      $iMes           = DBPessoal::getMesFolha();
    }

    $rsCfpess = $Cfpess->sql_record($Cfpess->sql_query_file($iAno, $iMes, $iInstit, "r11_filtralotacaousuario"));
    if (db_utils::fieldsMemory($rsCfpess,0)->r11_filtralotacaousuario == "t") {
      return true;
    }
    return false;

  }

  /**
   * Função que busca as permissões das lotações dos usuários e retorna um objeto com as informações das lotações e dos estruturais
   *
   * @param integer $iCodigoUsuario  - Código do Usurio
   * @param integer $iInstit         - Instituição da busca das permissões
   * @param integer $iAno            - Ano da Folha
   * @param integer $iMes            - Mes da Folha
   * @param string  $sTipoBusca      - Tipo de busca
   *                                      U: Apenas as lotacoes que estiverem vinculadas ao usuario
   *                                      S: Busca as lotacoes vinculadas ao usuario e todas as lotacoes da secretaria
   *                                        (substr(lotacaousuario.r70_estrut, 1, 2) = substr(rhlota.r70_estrut, 1, 2))
   * @param boolean $lRetornaMatriculas - true para retornar a lista das matriculas que vinculadas a lotação que usuario tem acesso
   * @throws Exception
   *
   * Retorno:
   * $oRetorno = new stdClass();
   * $oRetorno->lErro = false;
   * $oRetorno->sMsg  = "";
   * $oRetorno->aEstruturais = array();
   * $oRetorno->aLotacoes = array();
   * Se $lRetornaMatriculas for true tera o elemento abaixo:
   * $oRetorno->aMatriculas = array();
   *
   */
  public static function buscaLotacoesPorUsuario($iCodigoUsuario = null, $iInstit = null, $iAno = null, $iMes = null, $sTipoBusca = "U", $lRetornaMatriculas = false) {

    try {

        $Cfpess           = new cl_cfpess();
        $DbUsuarioLotacao = new cl_db_usuariosrhlota();

        if (empty($iCodigoUsuario) || is_null($iCodigoUsuario)) {
          $iCodigoUsuario = db_getsession('DB_id_usuario');
        }

        if (empty($iInstit) || is_null($iInstit)) {
          $iInstit        = db_getsession('DB_instit');
        }

        if (empty($iAno) || is_null($iAno)) {
          $iAno           = DBPessoal::getAnoFolha();
        }

        if (empty($iMes) || is_null($iMes)) {
          $iMes           = DBPessoal::getMesFolha();
        }

        $aEstruturais         = array();
        $aLotacoes            = array();

        $oRetorno = new stdClass();
        $oRetorno->lErro = false;
        $oRetorno->sMsg  = "";
        $oRetorno->aEstruturais = array();
        $oRetorno->aLotacoes = array();

        $sSqlMascaraLotacao = $Cfpess->sql_query($iAno, $iMes, $iInstit, "db77_estrut");
        $rsMascaraLotacao   = db_query($sSqlMascaraLotacao);

        if (!$rsMascaraLotacao) {
            throw new Exception("Erro ao buscar a mascara da lotação.");
        }

        if (pg_num_rows($rsMascaraLotacao) == 0) {
            throw new Exception("Nenhuma lotação configurada para esta competência. Por favor verificar manutenção de parâmetros.");
        }

        $sMascara = db_utils::fieldsMemory($rsMascaraLotacao,0)->db77_estrut;

        //lotacoes do usuario
        if ($sTipoBusca == "U") {
            $sWhere = "rh157_usuario = {$iCodigoUsuario} and r70_instit = {$iInstit} ";
            if (DBPessoal::getLiberaLotacao()) {
               $DbUsuarioLotacao = new cl_rhlota;
               $sWhere = "r70_instit = {$iInstit}";
            }
            $sSqlLotacoesUsuario = $DbUsuarioLotacao->sql_query(null, "distinct r70_codigo, r70_estrut ", null, $sWhere);
        }

        //lotacoes por secretaria
        if ($sTipoBusca == "S") {

           $sWhere = "rh157_usuario = {$iCodigoUsuario} and r70_instit = {$iInstit}";
           if (DBPessoal::getLiberaLotacao()) {
              $DbUsuarioLotacao = new cl_rhlota;
              $sWhere = "r70_instit = {$iInstit}";
           }
           $sSqlLotacoesUsuario = $DbUsuarioLotacao->sql_query(null, "distinct substr(r70_estrut, 1, 2) as secretaria", null, $sWhere);
           $sSqlLotacoesUsuario = "select distinct
                                          r70_codigo,
                                          r70_estrut
                                    from rhlota
                                         inner join ({$sSqlLotacoesUsuario}) as secretarias on secretaria = substr(r70_estrut, 1, 2)";
        }

        $rsLotacoesUsuario   = db_query($sSqlLotacoesUsuario);
        if (!$rsLotacoesUsuario) {
            throw new Exception("Erro ao buscar lotações do usuário.".pg_last_error());
        }

        if (pg_num_rows($rsLotacoesUsuario) == 0) {
            throw new Exception("Nenhuma lotação vinculada à este usuário.");
        }

        $aDados = db_utils::getCollectionByRecord($rsLotacoesUsuario);

        foreach ($aDados as $oLotacao) {
              $aEstruturais[] = trim(str_replace( ".",'', DBEstrutura::removerEstruturalVazio( DBEstrutura::mascararString($sMascara, $oLotacao->r70_estrut) ) ) );
              $aLotacoes[] = $oLotacao->r70_codigo;
        }

        $oRetorno->aEstruturais = $aEstruturais;
        $oRetorno->aLotacoes = $aLotacoes;

        if ($lRetornaMatriculas) {

            $oRetorno->aMatriculas = array();

            $oRHPessoalMov = new cl_rhpessoalmov();

            $aWhereRHPessoalMov = array();
            $aWhereRHPessoalMov[] = " rh02_anousu = {$iAno} ";
            $aWhereRHPessoalMov[] = " rh02_mesusu = {$iMes} ";
            $aWhereRHPessoalMov[] = " rh02_instit = {$iInstit} ";
            $aWhereRHPessoalMov[] = " rh02_lota in (".implode(",", $aLotacoes).")";
            $sWhereRHPessoalMov = implode(" and ",$aWhereRHPessoalMov);

            $sSqlRHPessoalMov = $oRHPessoalMov->sql_query_file(null, $iInstit, "rh02_regist", "rh02_regist", $sWhereRHPessoalMov);
            $rsMatriculas = $oRHPessoalMov->sql_record($sSqlRHPessoalMov);
            if ($oRHPessoalMov->numrows > 0) {

              for ($iInd = 0; $iInd < $oRHPessoalMov->numrows; $iInd++) {
                 $oRetorno->aMatriculas[] = db_utils::fieldsMemory($rsMatriculas,$iInd)->rh02_regist;
              }

            }

        }

        //"r70_estrut ~ '^(".implode('|',$aResultados).")'"
        return $oRetorno;

    } catch (Exception $oErro) {

        $oRetorno->lErro = true;

        echo $oErro->getMessage();
        $oRetorno->sMsg = $oErro->getMessage();
    }

    return $oRetorno;

  }

  public static function verificaBloqueioCompetenciasAbertas($ano = null, $mes = null, $instituicao = null) {

      $Cfpess = new cl_cfpess();
      /*
       * Verificamos se o usuario possui permissao para o item de menu -
       * Se possuir permissao nao eh realizado o bloqueio
       */
      if (db_permissaomenu(db_getsession("DB_anousu"), db_getsession("DB_modulo"), 228798) === "true") {
       return false;
      }

      if (empty($ano)){
          $ano = self::getAnoFolha();
      }

      if (empty($mes)) {
          $mes = self::getMesFolha();
      }

      if (empty($instituicao)) {
          $instituicao = db_getsession("DB_instit");
      }

      $anovalidacao = $ano;
      $mesvalidacao = $mes;
      if ($mes == 12) {
          $anovalidacao++;
          $mesvalidacao = 1;
      } else {
          $mesvalidacao++;
      }

      $rsCfPess = $Cfpess->sql_record($Cfpess->sql_query_file(self::getAnoFolha(),
                                                              self::getMesFolha(),
                                                              $instituicao,
                                                              "r11_bloqueiocompetenciaaberta"));
      if (db_utils::fieldsMemory($rsCfPess,0)->r11_bloqueiocompetenciaaberta == 't') {
        $rsCfPess = $Cfpess->sql_record($Cfpess->sql_query_file($anovalidacao,$mesvalidacao,$instituicao,"r11_instit"));
        if ($Cfpess->numrows == 0) {
            return true;
        }
        return false;
      }
      return false;

  }

  public static function setEstruturaFolhaPagamento($lSuplementar)
    {
        return true;
    }

    /**
     * Data de obrigatoriedade. Por default retorna data fase 1
     * @param $fase
     * @return bool
     *
     */
    public static function getDataFaseEsocial($fase = 1) {
        $competencia = self::getCompetenciaFolha();
        $campoData = "r11_dataenviofase{$fase}";
        $sql = "
            select
               r11_dataenviofase{$fase}
            from
                pessoal.cfpess
            where
                r11_anousu = {$competencia->getAno()}
                and r11_mesusu = {$competencia->getMes()}
                and r11_instit = " . db_getsession('DB_instit');

        $rs = db_query($sql);
        if (!$rs) {
            throw new DBException("Erro ao buscar informações da data de obrigatoriedade do eSocial no banco de dados.");
        }
        if (pg_num_rows($rs) > 0) {
            return new DBDate(db_utils::fieldsMemory($rs, 0)->{$campoData});
        }
        return false;
    }

    /**
     * Função que verifica se utiliza o filtro da lotação por usuário
     */
    public static function getLiberaLotacao() {

      $cldb_usuarios = new cl_db_usuarios;

      $rsDb_usuarios = $cldb_usuarios->sql_record($cldb_usuarios->sql_query_file(db_getsession('DB_id_usuario'), 'liberalotacao'));
      if (db_utils::fieldsMemory($rsDb_usuarios,0)->liberalotacao == 1) {
        return true;
      }
      return false;
    }

    /**
     * @return bool
     * @throws BusinessException
     */
    public static function isEmitirContrachequesEstorage()
    {
        $oDaoCfPess = new cl_cfpess();
        $oInstituicao = InstituicaoRepository::getInstituicaoSessao();
        $oCompetencia = DBPessoal::getCompetenciaFolha();

        $sSqlSuplementar = $oDaoCfPess->sql_query_file(
            $oCompetencia->getAno(),
            $oCompetencia->getMes(),
            $oInstituicao->getCodigo(),
            'r11_emissaocontracheque'
        );
        $rsSuplementar = db_query($sSqlSuplementar);
        if ($rsSuplementar && pg_num_rows($rsSuplementar) > 0) {
            return db_utils::fieldsMemory($rsSuplementar, 0)->r11_emissaocontracheque == 't';
        }
        return false;
    }
}
