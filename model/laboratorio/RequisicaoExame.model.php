<?php
/**
 * E-cidade Software Publico para Gestão Municipal
 *   Copyright (C) 2014 DBSeller Serviços de Informática Ltda
 *                          www.dbseller.com.br
 *                          e-cidade@dbseller.com.br
 *   Este programa é software livre; você pode redistribuí-lo e/ou
 *   modificá-lo sob os termos da Licença Pública Geral GNU, conforme
 *   publicada pela Free Software Foundation; tanto a versão 2 da
 *   Licença como (a seu critério) qualquer versão mais nova.
 *   Este programa e distribuído na expectativa de ser útil, mas SEM
 *   QUALQUER GARANTIA; sem mesmo a garantia implícita de
 *   COMERCIALIZAÇÃO ou de ADEQUAÇÃO A QUALQUER PROPÓSITO EM
 *   PARTICULAR. Consulte a Licença Pública Geral GNU para obter mais
 *   detalhes.
 *   Você deve ter recebido uma cópia da Licença Pública Geral GNU
 *   junto com este programa; se não, escreva para a Free Software
 *   Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *   02111-1307, USA.
 *   Cópia da licença no diretório licenca/licenca_en.txt
 *                                 licenca/licenca_pt.txt
 */

define("MENSAGEM_REQUISICAO_EXAME", "saude.laboratorio.RequisicaoExame.");
/**
 * Exame de uma Requisicao
 * Class RequisicaoExame
 */
class RequisicaoExame {

  private $iCodigo = null;

  /**
   * Paciente da Solicitacao;
   * @var Cgs
   */
  private $oSolicitante = null;

  /**
   * Cpdigo do Exame que deverá ser executado
   * @var integer
   */
  protected $iCodigoExame;

  /**
   * Exame que deverá ser realizado
   * @var Exame
   */
  protected $oExame;

  /**
   * Situacao do Exame
   * @var string
   */
  protected $sSituacao = '';

  /**
   * Exame nao digitado
   * @var string
   */
  const NAO_DIGITADO = '1 - Nao Digitado';

  /**
   * Valores do exame foi lançando
   * @var string
   */
  const LANCADO = '2 - Lancado';

  /**
   * Exame FOi entrege ao solicitante
   * @var string
   */
  const ENTREGUE = '3 - Entregue';

  /**
   * Amostrados do exame foram coletadas
   */
  const COLETADO = '6 - Coletado';

  /**
   * Exame foi conferido e está pronto para a entrega
   */
  const CONFERIDO = '7 - Conferido';

  /**
   * Exame autorizado
   */
  const AUTORIZADO = '8 - Autorizado';

  /**
   * Observação lançada para o exâme
   * @var string
   */
  private $sObservacao;

  /**
   * Instancia o Exame
   * @param $iCodigo
   */
  public function __construct($iCodigo) {

    if (!empty($iCodigo)) {

      $oDaoRequisicao      = new cl_lab_requiitem();
      $sSqlExameRequisicao = $oDaoRequisicao->sql_query($iCodigo);
      $rsExameRequisicao   = $oDaoRequisicao->sql_record($sSqlExameRequisicao);
      if ($rsExameRequisicao && $oDaoRequisicao->numrows > 0) {

        $oDadosRequisicao = db_utils::fieldsMemory($rsExameRequisicao, 0);

        $this->oSolicitante = new Cgs($oDadosRequisicao->la22_i_cgs);
        $this->iCodigo      = $oDadosRequisicao->la21_i_codigo;
        $this->iCodigoExame = $oDadosRequisicao->la08_i_codigo;
        $this->sSituacao    = trim($oDadosRequisicao->la21_c_situacao);
        $this->sObservacao  = trim($oDadosRequisicao->la21_observacao);
      }
    }
  }

  /**
   * Retorna o codigo da requisicao
   * @return integer
   */
  public function getCodigo() {
    return $this->iCodigo;
  }

  /**
   * Define o solicitante da requisicao
   * @param Cgs $oSolicitante Solictante da requisicao
   */
  public function setSolicitante(Cgs $oSolicitante) {
    $this->oSolicitante = $oSolicitante;
  }

  /**
   * Retorna o solicitante da Requisicao
   * @return Cgs
   */
  public function getSolicitante() {
    return $this->oSolicitante;
  }

  /**
   * Exame que deverá ser Realizado
   * @return Exame
   */
  public function getExame() {

    if (empty($this->oExame) && !empty($this->iCodigoExame)) {
      $this->oExame = new Exame($this->iCodigoExame);
    }
    return $this->oExame;
  }

  /**
   * Retorna o Resultado do exame
   * @return ResultadoExame
   */
  public function getResultado() {
    return new ResultadoExame($this);
  }

  /**
   * Define a situacao do exame
   *
   * @param string $sSituacao Sitaucao do Exame
   */
  public function setSituacao($sSituacao) {
    $this->sSituacao = $sSituacao;
  }

  /**
   * Retorna a situacao do exame
   * @return string
   */
  public function getSituacao() {
    return $this->sSituacao;
  }

  /**
   * Persiste os dados do exame
   * @throws BusinessException
   */
  public function salvar() {

    $oDaoItemExame = new cl_lab_requiitem();
    if (!empty($this->iCodigo)) {

      $oDaoItemExame->la21_i_codigo   = $this->iCodigo;
      $oDaoItemExame->la21_c_situacao = $this->getSituacao();
      $oDaoItemExame->la21_observacao = $this->sObservacao;
      $oDaoItemExame->alterar($this->iCodigo);
      if ($oDaoItemExame->erro_status == 0) {
        throw new BusinessException( _M( MENSAGEM_REQUISICAO_EXAME . "erro_salvar" ) );
      }
    }
  }

  /**
   * Retorna o setor no qual o exame está vinculado
   * @return Setor
   */
  public function getLaboratorioSetor() {

    $oDaoRequiItem = new cl_lab_requiitem();
    $sWhere        = "la21_i_codigo = {$this->iCodigo}";
    $sSqlRequiItem = $oDaoRequiItem->sql_query('', 'la24_i_setor', '', $sWhere);
    $rsRequiItem   = db_query( $sSqlRequiItem );

    if ( !$rsRequiItem ) {

      $oMensagem        = new stdClass();
      $oMensagem->sErro = pg_result_error( $rsRequiItem );
      throw new BusinessException( _M( MENSAGEM_REQUISICAO_EXAME . "erro_buscar_setor", $oMensagem ) );
    }

    if ( pg_num_rows( $rsRequiItem ) == 0 ) {
      throw new BusinessException( _M( MENSAGEM_REQUISICAO_EXAME . "setor_nao_encontrado") );
    }

    $iCodigoExame = db_utils::fieldsMemory( $rsRequiItem, 0 )->la24_i_setor;

    return new Setor( $iCodigoExame );
  }

  /**
   * Retorna a observação lançada para o exâme
   * @return string
   */
  public function getObservacao() {
    return $this->sObservacao;
  }

  /**
   * Define uma observação ao exame
   * @param string $sObservacao
   */
  public function setObservacao($sObservacao) {
    $this->sObservacao = $sObservacao;
  }
}