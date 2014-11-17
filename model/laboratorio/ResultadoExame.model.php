<?php
/**
 * Created by PhpStorm.
 * User: dbseller
 * Date: 20/02/14
 * Time: 15:49
 */

/**
 * Resultado de Um exame Clinico
 * Class ResultadoExame
 */
class ResultadoExame {

  /**
   * Requisicao do Exame
   * @var RequisicaoExame
   */
  private $oRequisicao;


  /**
   * Codigo do Resultado
   * @var integer
   */
  private $iResultado;

  /**
   * Diagnostico do Exame
   * @var string
   */
  private $sConsideracao = '';


  private $aResultadoAtributos = array();

  /**
   * Instancia um novo Resultado
   * @param RequisicaoExame $oRequisicao
   */
  public function __construct(RequisicaoExame $oRequisicao) {

    $this->oRequisicao  = $oRequisicao;
    $oDaoResultadoExame = new cl_lab_resultado();
    $sSqlResultado      = $oDaoResultadoExame->sql_query_file(null,
                                                              "*",
                                                               null,
                                                              "la52_i_requiitem = {$this->oRequisicao->getCodigo()}"
                                                             );
    $rsResultado = $oDaoResultadoExame->sql_record($sSqlResultado);
    if ($rsResultado && $oDaoResultadoExame->numrows > 0) {

      $oDados             = db_utils::fieldsMemory($rsResultado, 0);
      $this->iResultado   = $oDados->la52_i_codigo;
      $this->sConsideracao = $oDados->la52_diagnostico;
    }
  }

  /**
   * Retorna todos Atributos com seus Resultados
   * @return ResultadoExameAtributo[]
   */
  public function getResultadoDosAtributos() {

    if (count($this->aResultadoAtributos) == 0 && !empty($this->iResultado)) {

      $oDaoResultaoItem       = new cl_lab_resultadoitem();
      $sCampos                = "la39_i_atributo,";
      $sCampos               .= "la39_i_codigo,";
      $sCampos               .= "case when la41_f_valor is not null then cast(la41_f_valor as varchar)";
      $sCampos               .= "     when la40_i_valorrefsel is not null then cast(la40_i_valorrefsel as varchar)";
      $sCampos               .= "     when la40_c_valor <> '' then la40_c_valor  else '' end as valor,";
      $sCampos               .= "la41_valorpercentual as valorpercentual,";
      $sCampos               .= "la41_faixaescolhida as faixautilizada";
      $sWhere                 = "la39_i_resultado = {$this->iResultado}";
      $sSqlResultadoAtributos = $oDaoResultaoItem->sql_query_resultado_valores(null, $sCampos, null, $sWhere);
      $rsResultadoAtributo    = $oDaoResultaoItem->sql_record($sSqlResultadoAtributos);
      if ($rsResultadoAtributo && $oDaoResultaoItem->numrows > 0) {

        for ($iItem = 0; $iItem < $oDaoResultaoItem->numrows; $iItem++) {

          $oDadosResultado    = db_utils::fieldsMemory($rsResultadoAtributo, $iItem);
          $oResultadoAtributo = new ResultadoExameAtributo($oDadosResultado->la39_i_codigo);
          $oResultadoAtributo->setAtributo(AtributoExameRepository::getByCodigo($oDadosResultado->la39_i_atributo));
          $oResultadoAtributo->setValorAbsoluto($oDadosResultado->valor);
          $oResultadoAtributo->setValorPercentual($oDadosResultado->valorpercentual);
          if (!empty($oDadosResultado->faixautilizada)) {
             $oResultadoAtributo->setFaixaUtilizada(new AtributoValorReferenciaNumerico($oDadosResultado->faixautilizada));
          }
          $this->aResultadoAtributos[$oResultadoAtributo->getAtributo()->getCodigo()] = $oResultadoAtributo;
        }
      }

    }
    return $this->aResultadoAtributos;
  }

  /**
   * Retorna o resultado do Atributo Informado
   * @param AtributoExame $oAtributo
   * @return ResultadoExameAtributo|string
   */
  public function getValorDoAtributo(AtributoExame $oAtributo) {

    $aAtributos = $this->getResultadoDosAtributos();
    if (isset($aAtributos[$oAtributo->getCodigo()])) {
      return $aAtributos[$oAtributo->getCodigo()];
    }
    return null;
  }

  /**
   * Adiciona um valor do atributo ao exame
   * @param ResultadoExameAtributo $oResultadoAtributo
   * @return bool
   */
  public function adicionarResultadoParaAtributo(ResultadoExameAtributo $oResultadoAtributo) {

    $oAtributoJaLancado = $this->getValorDoAtributo($oResultadoAtributo->getAtributo());
    if (!empty($oAtributoJaLancado)) {
      return false;
    }
    $this->aResultadoAtributos[$oResultadoAtributo->getAtributo()->getCodigo()] = $oResultadoAtributo;
    return true;
  }

  /**
   * Define o Diagnostico do exame
   * @param string $sConsideracao
   */
  public function setConsideracao($sConsideracao) {
    $this->sConsideracao = $sConsideracao;
  }

  /**
   * @return string
   */
  public function getConsideracao() {
    return $this->sConsideracao;
  }


  /**
   * Salva os dados do resultado para o exame
   *
   */
  public function salvar() {

    $oDaoResultadoExame = new cl_lab_resultado();
    $oDaoResultadoExame->la52_diagnostico = $this->getConsideracao();
    if (empty($this->iResultado)) {

      $oDaoResultadoExame->la52_c_hora      = db_hora();
      $oDaoResultadoExame->la52_d_data      = date("Y-m-d", db_getsession("DB_datausu"));
      $oDaoResultadoExame->la52_i_requiitem = $this->oRequisicao->getCodigo();
      $oDaoResultadoExame->la52_i_usuario   = db_getsession("DB_id_usuario");
      $oDaoResultadoExame->la52_t_motivo    = '';
      $oDaoResultadoExame->incluir(null);
      $this->iResultado = $oDaoResultadoExame->la52_i_codigo;
      $this->oRequisicao->setSituacao(RequisicaoExame::LANCADO);
      $this->oRequisicao->salvar();
    } else {

      $oDaoResultadoExame->la52_i_codigo = $this->iResultado;
      $oDaoResultadoExame->alterar($this->iResultado);
    }

    if ($oDaoResultadoExame->erro_status == 0) {
      throw new BusinessException("Erro ao salvar do exame");
    }

    foreach ($this->getResultadoDosAtributos() as $oResultadosAtributos) {
      $oResultadosAtributos->salvar($this->iResultado);
    }
  }
}